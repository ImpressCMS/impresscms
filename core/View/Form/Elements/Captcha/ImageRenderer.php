<?php
namespace ImpressCMS\Core\View\Form\Elements\Captcha;

use Aura\Session\Segment;
use Aura\Session\Session;
use icms;
use ImpressCMS\Core\File\Filesystem;

/**
 * Image Creation script
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPLv2 or later license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since	XOOPS
 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package	ICMS\Form\Elements\Captcha
 */
class ImageRenderer {
	/**
	 * Do we need to send headers?
	 *
	 * @var bool
	 */
	public $sendHeader = true;

	public $invalid = false;
	/**
	 * Captcha session section
	 *
	 * @var Segment
	 */
	protected $captchaSection;
	private $code;
	private $font;
	private $spacing;
	private $width;
	private $height;
	/**
	 * @var false|resource
	 */
	private $oImage;
	/**
	 * @var string
	 */
	private $mode = 'gd';

	/**
	 * Linked form element name
	 *
	 * @var null|string
	 */
	private $name;

	/**
	 * Constructor
	 */
	public function __construct() {
		/**
		 * @var Session $session
		 */
		$session = \icms::$session;
		$this->captchaSection = $session->getSegment(Image::class);

		if (!$this->name) {
			$this->invalid = true;
		}

		if (!extension_loaded('gd')) {
			$this->mode = 'bmp';
		} else {
			$required_functions = [
				'imagecreatetruecolor',
				'imagecolorallocate',
				'imagefilledrectangle',
				'imagepng',
				'imagedestroy',
				'imageftbbox'
			];
			foreach ($required_functions as $func) {
				if (!function_exists($func)) {
					$this->mode = 'bmp';
					break;
				}
			}
		}
	}

	/**
	 * Loads the captcha image
	 */
	public function loadImage() {
		$this->createCode();
		$this->setCode();
		$this->createImage();
	}

	/**
	 * Creates the Captcha Code
	 */
	public function createCode() {
		global $icmsConfigCaptcha;
		if ($this->invalid) {
			return;
		}

		if ($this->mode === 'bmp') {
			$icmsConfigCaptcha['captcha_num_chars'] = 4;
			$this->code = random_int(10 ** ($icmsConfigCaptcha['captcha_num_chars'] - 1), (int) (str_pad('9', $icmsConfigCaptcha['captcha_num_chars'], '9')));
		} else {
			$raw_code = md5(uniqid(mt_rand(), 1));
			if (isset($icmsConfigCaptcha['captcha_skip_characters'])) {
				$valid_code = str_replace($icmsConfigCaptcha['captcha_skip_characters'], '', $raw_code);
				$this->code = substr($valid_code, 0, $icmsConfigCaptcha['captcha_num_chars']);
			} else {
				$this->code = substr($raw_code, 0, $icmsConfigCaptcha['captcha_num_chars']);
			}
			if (!$icmsConfigCaptcha['captcha_casesensitive']) {
				$this->code = strtoupper($this->code);
			}
		}
	}

	/**
	 * Sets the Captcha code
	 */
	public function setCode() {
		if ($this->invalid) {
			return;
		}

		$this->captchaSection->set('session_code', (string)$this->code);
		$maxAttempts = (int)$this->captchaSection->get('max_attempts');

		// Increase the attempt records on refresh
		if (!empty($maxAttempts)) {
			$this->captchaSection->set(
				'attempt_' . $this->name,
				$attempt = $this->captchaSection->get('attempt_' . $this->name ) + 1
			);
			if ($attempt > $maxAttempts) {
				$this->invalid = true;
			}
		}
	}

	/**
	 * Clear attempts counter
	 */
	public function clearAttempts()
	{
		$this->captchaSection->set(
			'attempt_' . $this->name,
			0
		);
	}

	/**
	 * Creates the Captcha Image File
	 * @param   string $file filename of the Captcha image
	 * @return string|void
	 */
	public function createImage($file = '') {
		if ($this->invalid) {
			if ($this->sendHeader) {
				header('Content-type: image/gif');
			}
			readfile(ICMS_PUBLIC_PATH . '/images/subject/icon2.gif');
			return;
		}
		return $this->mode === 'bmp' ? $this->createImageBmp() : $this->createImageGd();
	}

	/**
	 * Create CAPTCHA iamge with GD
	 * Originated from DuGris' SecurityImage
	 * @param   string $file filename of the Captcha image
	 */
	//  --------------------------------------------------------------------------- //
	// Class : SecurityImage 1.5													//
	// Author: DuGris aka L. Jen <http://www.dugris.info>							//
	// Email : DuGris@wanadoo.fr													//
	// Licence: GNU																	//
	// Project: The XOOPS Project													//
	//  --------------------------------------------------------------------------- //
	/**
	 *  Create CAPTCHA iamge with BMP
	 * @TODO
	 * @param   string $file filename
	 * @return  string $image he image that was created from bmp file
	 */

	public function createImageBmp($file = '')
	{
		$image = '';

		if (empty($file)) {
			if ($this->sendHeader) {
				header('Content-type: image/bmp');
			}
			echo $image;
		} else {
			return $image;
		}
	}


	public function createImageGd($file = '') {

		$this->loadFont();
		$this->setImageSize();

		$this->oImage = imagecreatetruecolor($this->width, $this->height);
		$background = imagecolorallocate($this->oImage, 255, 255, 255);
		imagefilledrectangle($this->oImage, 0, 0, $this->width, $this->height, $background);

		global $icmsConfigCaptcha;
		switch ($icmsConfigCaptcha['captcha_background_type']) {
			default:
			case 0:
				$this->drawBars();
				break;

			case 1:
				$this->drawCircles();
				break;

			case 2:
				$this->drawLines();
				break;

			case 3:
				$this->drawRectangles();
				break;

			case 4:
				$this->drawEllipses();
				break;

			case 5:
				$this->drawPolygons();
				break;

			case 100:
				$this->createFromFile();
				break;
		}
		$this->drawBorder();
		$this->drawCode();
		if (empty($file)) {

			if ($this->sendHeader) {
				header('Content-type: image/png');
			}
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			imagepng($this->oImage);
		} else {
			imagepng($this->oImage, ICMS_CACHE_PATH . '/captcha/' . $file . '.jpg');
		}
		imagedestroy($this->oImage);
	}

	/**
	 * Loads the Captcha font
	 */
	public function loadFont()
	{
		$fonts = $this->_getList( __DIR__ . '/fonts', 'ttf');
		$this->font = __DIR__ . '/fonts/' . $fonts[array_rand($fonts)];
	}

	/**
	 * Gets list of Captcha items (Internal Function)
	 * @param   string $name directory name to look in
	 * @param   string $extension extension of the files to look for
	 * @return  array  array of Captcha items
	 */
	public function _getList($name, $extension = '') {
		$files = array_values(
			Filesystem::getFileList((string)($name), '', [$extension])
		);

		if (function_exists('mod_createCacheFile')) {
			mod_createCacheFile($files, "captcha_{$name}", 'captcha');
		}
		return $files;
	}

	/**
	 * Sets the Captcha image size
	 */
	public function setImageSize() {
		$MaxCharWidth = 0;
		$MaxCharHeight = 0;
		$oImage = imagecreatetruecolor(100, 100);
		$text_color = imagecolorallocate($oImage, random_int(0, 100), random_int(0, 100), random_int(0, 100));
		global $icmsConfigCaptcha;
		$FontSize = $icmsConfigCaptcha['captcha_fontsize_max'];
		for ($Angle = -30; $Angle <= 30; $Angle++) {
			for ($i = 65; $i <= 90; $i++) {
				$CharDetails = imageftbbox($FontSize, $Angle, $this->font, chr($i), array());
				$_MaxCharWidth = abs($CharDetails[0] + $CharDetails[2]);
				if ($_MaxCharWidth > $MaxCharWidth) {
					$MaxCharWidth = $_MaxCharWidth;
				}
				$_MaxCharHeight = abs($CharDetails[1] + $CharDetails[5]);
				if ($_MaxCharHeight > $MaxCharHeight) {
					$MaxCharHeight = $_MaxCharHeight;
				}
			}
		}
		imagedestroy($oImage);

		$this->height = $MaxCharHeight + 2;
		$this->spacing = (int) (($icmsConfigCaptcha['captcha_num_chars'] * $MaxCharWidth) / $icmsConfigCaptcha['captcha_num_chars']);
		$this->width = ($icmsConfigCaptcha['captcha_num_chars'] * $MaxCharWidth) + ($this->spacing / 2);
	}

	/**
	 * Draw Captcha Bars background
	 */
	public function drawBars()
	{
		for ($i = 0; $i <= $this->height;) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imageline($this->oImage, 0, $i, $this->width, $i, $randomcolor);
			$i += 2.5;
		}
		for ($i = 0; $i <= $this->width;) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imageline($this->oImage, $i, 0, $i, $this->height, $randomcolor);
			$i += 2.5;
		}
	}

	/**
	 * Draw Captcha Circles background
	 */
	public function drawCircles() {
		global $icmsConfigCaptcha;
		for ($i = 1; $i <= $icmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imagefilledellipse($this->oImage, random_int(0, $this->width - 10), random_int(0, $this->height - 3), random_int(10, 20), random_int(20, 30), $randomcolor);
		}
	}

	/**
	 * Draw Captcha Lines background
	 */
	public function drawLines() {
		global $icmsConfigCaptcha;
		for ($i = 0; $i < $icmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imageline($this->oImage, random_int(0, $this->width), random_int(0, $this->height), random_int(0, $this->width), random_int(0, $this->height), $randomcolor);
		}
	}

	/**
	 * Draw Captcha Rectangles background
	 */
	public function drawRectangles() {
		global $icmsConfigCaptcha;
		for ($i = 1; $i <= $icmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imagefilledrectangle($this->oImage, random_int(0, $this->width), random_int(0, $this->height), random_int(0, $this->width), random_int(0, $this->height), $randomcolor);
		}
	}

	/**
	 * Draw Captcha Ellipses background
	 */
	public function drawEllipses() {
		global $icmsConfigCaptcha;
		for ($i = 1; $i <= $icmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			imageellipse($this->oImage, random_int(0, $this->width), random_int(0, $this->height), random_int(0, $this->width), random_int(0, $this->height), $randomcolor);
		}
	}

	/**
	 * Draw Captcha polygons background
	 */
	public function drawPolygons() {
		global $icmsConfigCaptcha;
		for ($i = 1; $i <= $icmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, random_int(190, 255), random_int(190, 255), random_int(190, 255));
			$coords = array();
			for ($j = 1; $j <= $icmsConfigCaptcha['captcha_polygon_point']; $j++) {
				$coords[] = random_int(0, $this->width);
				$coords[] = random_int(0, $this->height);
			}
			imagefilledpolygon($this->oImage, $coords, $icmsConfigCaptcha['captcha_polygon_point'], $randomcolor);
		}
	}

	/**
	 * Draws Image background
	 */
	public function createFromFile()
	{
		if ($RandImage = $this->loadBackground()) {
			$ImageType = @getimagesize($RandImage);
			switch (@$ImageType[2]) {
				case 1:
					$BackgroundImage = imagecreatefromgif($RandImage);
					break;

				case 2:
					$BackgroundImage = imagecreatefromjpeg($RandImage);
					break;

				case 3:
					$BackgroundImage = imagecreatefrompng($RandImage);
					break;
			}
		}
		if (!empty($BackgroundImage)) {
			imagecopyresized($this->oImage, $BackgroundImage, 0, 0, 0, 0, imagesx($this->oImage), imagesy($this->oImage), imagesx($BackgroundImage), imagesy($BackgroundImage));
			imagedestroy($BackgroundImage);
		} else {
			$this->drawBars();
		}
	}

	/**
	 * Returns random background
	 *
	 * @return array Random Background
	 */
	public function loadBackground()
	{
		$RandBackground = null;
		if ($backgrounds = $this->_getList('backgrounds', '(gif|jpg|png)')) {
			$RandBackground = 'backgrounds/' . $backgrounds[array_rand($backgrounds)];
		}
		return $RandBackground;
	}

	/**
	 * Draw Captcha Border
	 */
	public function drawBorder()
	{
		$rgb = rand(50, 150);
		$border_color = imagecolorallocate($this->oImage, $rgb, $rgb, $rgb);
		imagerectangle($this->oImage, 0, 0, $this->width - 1, $this->height - 1, $border_color);
	}

	/**
	 * Draw Captcha Code
	 */
	public function drawCode()
	{
		global $icmsConfigCaptcha;
		for ($i = 0; $i < $icmsConfigCaptcha['captcha_num_chars']; $i++) {
			// select random greyscale colour
			$text_color = imagecolorallocate($this->oImage, random_int(0, 100), random_int(0, 100), random_int(0, 100));

			// write text to image
			$Angle = random_int(10, 30);
			if (($i % 2)) {
				$Angle = mt_rand(-30, -10);
			}

			// select random font size
			$FontSize = random_int($icmsConfigCaptcha['captcha_fontsize_min'], $icmsConfigCaptcha['captcha_fontsize_max']);

			$CharDetails = imageftbbox($FontSize, $Angle, $this->font, $this->code[$i], array());
			$CharHeight = abs($CharDetails[1] + $CharDetails[5]);

			// calculate character starting coordinates
			$posX = ($this->spacing / 2) + ($i * $this->spacing);
			$posY = 2 + ($this->height / 2) + ($CharHeight / 4);

			imagefttext($this->oImage, $FontSize, $Angle, $posX, $posY, $text_color, $this->font, $this->code[$i], array());
		}
	}
}

