<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

use WideImage\WideImage;

/**
 * Smarty {resized_image} function plugin for impressCMS
 *
 * Type:     function
 * Name:     resized_image
 * Date:     December 22, 2010
 * Purpose:  resizes an image to the specified size and returns a formatted HTML tag for the image
 * Input:
 *         - file = file (and path) of image (required)
 *         - height = image height (optional, default actual height)
 *         - width = image width (optional, default actual width)
 *         - basedir = base directory for absolute paths, default
 *                     is environment variable DOCUMENT_ROOT
 *         - path_prefix = prefix for path output (optional, default empty)
 *         - return = return an IMG tag or just and URL (defaults to IMG tag)
 *             - fit = The way the image is resized. Possible values:
 * � inside (the image will fit inside the new dimensions while maintaining the aspect ratio)
 * � fill (the image will completely fit inside the new dimensions)
 * � outside (the image will be resized to completely fill the specified rectangle, while still maintaining aspect ratio)
 *
 * Examples: {resized_image file="/images/image.jpg" height=70 width=120 fit="inside"}
 * You can add extra parameters to the tag, such as class, alt or id.
 * {resized_image file="/images/image.jpg" height=70 width=120 fit="inside" class="resized"}
 * @param array
 * @param Smarty
 * @return string
 * @throws \Psr\Cache\InvalidArgumentException
 * @author Ignacio Segura <nacho at pensamientosdivergentes.net>
 * Based on work by Monte Ohrt <monte at ohrt dot com> and Duda <duda@big.hu>
 * @uses smarty_function_escape_special_chars()
 * @uses WideImage library
 * @version  1.11
 */
class ResizeImageFunction
{
	/**
	 * Name of function in smarty
	 */
	const NAME = 'resized_image';

	/**
	 * Disabled constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * Magic method to invoke this class as function
	 *
	 * @param $params
	 * @param $smarty
	 * @return string|void
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function __invoke($params, &$smarty)
	{
		require_once $smarty->_get_plugin_filepath('shared', 'escape_special_chars');

		// Preparing arrays that will store original and resized image data
		$original = array();
		$resized = array();

		// Get parameters from the tag
		$alt = '';
		$file = '';
		$height = '';
		$width = '';
		$extra = '';
		$prefix = '';
		$suffix = '';
		$fit = 'inside';
		$return = 'img';
		$server_vars = ($smarty->request_use_auto_globals) ? $_SERVER : $GLOBALS['HTTP_SERVER_VARS']; // Really? HTTP_SERVER_VARS ?? WTF come on, it's been deprecated years!!!!!
		$basedir = $server_vars['DOCUMENT_ROOT'] ?? '';
		$subpath = str_replace($basedir, '', ICMS_ROOT_PATH);
		foreach ($params as $_key => $_val) {
			switch ($_key) {
				case 'file':
				case 'height':
				case 'width':
				case 'fit':
				case 'return':
				case 'path_prefix':
				case 'basedir':
					$$_key = $_val;
					break;

				case 'alt':
					if (!is_array($_val)) {
						$$_key = smarty_function_escape_special_chars($_val);
					} else {
						$smarty->trigger_error("resized_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
					}
					break;

				case 'link':
				case 'href':
					$prefix = '<a href="' . $_val . '">';
					$suffix = '</a>';
					break;

				default:
					if (!is_array($_val)) {
						$extra .= ' ' . $_key . '="' . smarty_function_escape_special_chars($_val) . '"';
					} else {
						$smarty->trigger_error("resized_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
					}
					break;
			}
		}
		// Checking the existence of required parameters
		if (empty($file)) {
			$smarty->trigger_error("resized_image: missing 'file' parameter", E_USER_ERROR);
			return;
		}
		if (!isset($params['width']) && !isset($params['height'])) {
			$smarty->trigger_error('resized_image: New size was not specified', E_USER_ERROR);
			return;
		}
		// If image resized is 'fit', both height and width are required
		if ($fit == 'fill' && (empty($width) || empty($height))) {
			$smarty->trigger_error("resized_image:  When you choose 'fill' fit, you have to specify both width and height", E_USER_ERROR);
		}
		// Transliteration - prepare a clean name for file
		$clean_file = str_replace(' ', '_', $file); // remove spaces
		$from = explode(' ', 'Á á É é è ê È Ê Í í Ó ó Ú ú Ñ ñ Ç ç');
		$to = explode(' ', 'A a E e e e E E I i O o U u N n C c');
		$clean_file = str_replace($from, $to, strtolower($clean_file)); // removing special characters, convert to lowercase, encoding in URL the remaining for safe
		$clean_file = str_replace('%2F', '/', urlencode($clean_file)); // URLencode the remaining, but taking into consideration the / char.
		$clean_file = str_replace($subpath, '', $clean_file); // strip off remnants of the document root (including subdir)

		// Preparing paths
		if (strpos($file, '/') === 0) {
			$original['path'] = $basedir . $file;
			$clean_file = str_replace($basedir, '', urldecode($clean_file)); // Clean file should not have Full Path
			$resized['path'] = $clean_file;
		} elseif (strpos($file, ICMS_URL) === 0) {    // In case of full URL
			$original['path'] = ICMS_ROOT_PATH . str_replace(ICMS_URL, '', $file);
			$clean_file = str_replace(ICMS_URL, '', urldecode($clean_file)); // Clean file should not have Full URL
			$resized['path'] = $clean_file;
		} else {
			$original['path'] = $file;
			$clean_file = str_replace(ICMS_ROOT_PATH, '', urldecode($clean_file));
			$resized['path'] = $clean_file;
		}
		// Check if original image exists
		if (!$_image_data = @getimagesize($original['path'])) {
			if (!file_exists($original['path'])) {
				$smarty->trigger_error("resized_image: unable to find '" . $original['path'] . "'", E_USER_NOTICE);
				return;
			} else if (!is_readable($original['path'])) {
				$smarty->trigger_error("resized_image: unable to read '" . $original['path'] . "'", E_USER_NOTICE);
				return;
			} else {
				$smarty->trigger_error("resized_image: '" . $original['path'] . "' is not a valid image file", E_USER_NOTICE);
				return;
			}
		}
		// Smarty Security check (comes from Smarty html_image tag, being honest, I don't understand what it does).
		if ($smarty->security &&
			($_params = array('resource_type' => 'file', 'resource_name' => $original['path'])) &&
			(require_once(SMARTY_CORE_DIR . 'core.is_secure.php')) &&
			(!smarty_core_is_secure($_params, $smarty))) {
			$smarty->trigger_error("resized_image: (secure) '" . $original['path'] . "' not in secure directory", E_USER_NOTICE);
		}

		// Original and resized dimensions
		if (!isset($params['width'])) {
			$original['width'] = $_image_data[0];
		}
		if (!isset($params['height'])) {
			$original['height'] = $_image_data[1];
		}

		$resized['width'] = $width;
		$resized['height'] = $height;

		// build resized file name
		$resized['dir'] = substr($resized['path'], 0, strrpos($resized['path'], '/')); // extract path
		$resized['path'] = substr($resized['path'], 0, strrpos($resized['path'], '.'))
			. '-' . $resized['width'] . 'x' . $resized['height']
			. substr($resized['path'], strrpos($resized['path'], '.')); // build path + file name

		/**
		 * @var \Psr\Cache\CacheItemPoolInterface $cache
		 */
		$cache = icms::getInstance()->get('cache');
		$cached_image = $cache->getItem('resized-file-' . $resized['path']);

		if (!$cached_image->isHit()) {
			$resized_img = WideImage::load($original['path']);

			$image_data = $resized_img->resize($resized['width'], $resized['height'], $fit)->asString('png');

			$cached_image->set(
				$resized['url'] = 'data:image/png;base64,' . base64_encode($image_data)
			);
		} else {
			$resized['url'] = $cached_image->get();
		}

		if ($return == 'url') {
			return $resized['url'];
		} else {
			return $prefix . '<img src="' . $resized['url'] . '" alt="' . $alt . '" ' . $extra . ' />' . $suffix;
		}
	}

}