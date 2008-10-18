<?php
/**
 * Image Creation script
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
*/
 
include "../../../mainfile.php";
error_reporting(0);
$xoopsLogger->activated = false;

if(empty($_SERVER['HTTP_REFERER']) || !preg_match("/^".preg_quote(ICMS_URL, '/')."/", $_SERVER['HTTP_REFERER'])) { 
	exit();
}

class IcmsCaptchaImageHandler {
	//var $mode = "gd"; // GD or bmp
	var $code;
	var $invalid = false;
	
	var $font;
	var $spacing;
	var $width;
	var $height;
	
	function IcmsCaptchaImageHandler()
	{
		if(empty($_SESSION['IcmsCaptcha_name'])) {
			$this->invalid = true;
		}
		
		if(!extension_loaded('gd')) {
			$this->mode = "bmp";
		}else{
			$required_functions = array("imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "imagejpeg", "imagedestroy", "imageftbbox"); 
			foreach($required_functions as $func) {
				if(!function_exists($func)) {
					$this->mode = "bmp";
					break;
				}
			}
		}
	}
	
	function loadImage()
	{
		$this->createCode();
		$this->setCode();
		$this->createImage();
	}

	/**
	* Create Code
	*/
	function createCode() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		if($this->invalid) {
			return;
		}
		
		if($this->mode == "bmp") {
			$IcmsConfigCaptcha['captcha_num_chars'] = 4;
			$this->code = rand( pow(10, $IcmsConfigCaptcha['captcha_num_chars'] - 1), intval( str_pad("9", $IcmsConfigCaptcha['captcha_num_chars'], "9") ) );
		}else{
            $raw_code = md5(uniqid(mt_rand(), 1));
            if (!empty($IcmsConfigCaptcha['captcha_skip_characters'])) {
                $valid_code = str_replace(array($IcmsConfigCaptcha['captcha_skip_characters']), "", $raw_code);
                $this->code = substr( $valid_code, 0, $IcmsConfigCaptcha['captcha_num_chars'] );
            } else {
                $this->code = substr( $raw_code, 0, $IcmsConfigCaptcha['captcha_num_chars'] );
            }
            if (!$IcmsConfigCaptcha['captcha_casesensitive']) {
                $this->code = strtoupper( $this->code );
            }
		}
	}
	
	function setCode()
	{
		if($this->invalid) {
			return;
		}
		
		$_SESSION['IcmsCaptcha_sessioncode'] = strval( $this->code );
		$maxAttempts = intval( @$_SESSION['IcmsCaptcha_maxattempts'] );
		
		// Increase the attempt records on refresh
		if(!empty($maxAttempts)) {
			$_SESSION['IcmsCaptcha_attempt_'.$_SESSION['IcmsCaptcha_name']]++;
			if($_SESSION['IcmsCaptcha_attempt_'.$_SESSION['IcmsCaptcha_name']] > $maxAttempts) {
				$this->invalid = true;
			}
		}
	}

	function createImage($file = "") 
	{
		if($this->invalid) {
			header("Content-type: image/gif");
			readfile(ICMS_ROOT_PATH."/images/subject/icon2.gif");
			return;
		}
		
		if($this->mode == "bmp") {
			return $this->createImageBmp();
		}else{
			return $this->createImageGd();
		}
	}

	/**
	 *  Create CAPTCHA iamge with GD
	 *  Originated from DuGris' SecurityImage
	 */
	//  --------------------------------------------------------------------------- //
	// Class : SecurityImage 1.5													//
	// Author: DuGris aka L. Jen <http://www.dugris.info>							//
	// Email : DuGris@wanadoo.fr													//
	// Licence: GNU																	//
	// Project: The XOOPS Project													//
	//  --------------------------------------------------------------------------- //
	function createImageGd($file = "") 
	{
		$this->loadFont();
		$this->setImageSize();
		
		$this->oImage = imagecreatetruecolor($this->width, $this->height);
		$background = imagecolorallocate($this->oImage, 255, 255, 255);
		imagefilledrectangle($this->oImage, 0, 0, $this->width, $this->height, $background);

		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		switch ($IcmsConfigCaptcha['captcha_background_type']) {
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

		if(empty($file)) {
			header("Content-type: image/jpeg");
			imagejpeg ($this->oImage);
		}else{
			imagejpeg ($this->oImage, ICMS_ROOT_PATH. "/cache/captcha/". $file . ".jpg");
		}
		imagedestroy($this->oImage);
	}
	
	function _getList($name, $extension = "") 
	{
		$items = array();
		
		/*if(@ include_once ICMS_ROOT_PATH."/class/captcha/functions.ini.php") {
			load_functions("cache");
			if($items = mod_loadCacheFile("captcha_{$name}", "captcha")) {
				return $items;
			}
		}*/
		
		require_once ICMS_ROOT_PATH."/class/xoopslists.php";
		$file_path = "../{$name}";
		$files = XoopsLists::getFileListAsArray($file_path);
		foreach( $files as $item) {
			if ( empty($extension) || preg_match("/(\.{$extension})$/i",$item) ) {
				$items[] = $item;
			}
		}
		if(function_exists("mod_createCacheFile")) { 
			mod_createCacheFile($items, "captcha_{$name}", "captcha");
		}
		return $items;
	}

	function loadFont() 
	{
		$fonts = $this->_getList("fonts", "ttf");
		$this->font = "../fonts/".$fonts[array_rand($fonts)];
	}
	
	function setImageSize()
	{
		$MaxCharWidth = 0;
		$MaxCharHeight = 0;
		$oImage = imagecreatetruecolor(100, 100);
		$text_color = imagecolorallocate($oImage, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		$FontSize = $IcmsConfigCaptcha['captcha_fontsize_max'];
		for ($Angle = -30; $Angle <= 30; $Angle++) {
			for ($i = 65; $i <= 90; $i++) {
				$CharDetails = imageftbbox($FontSize, $Angle, $this->font, chr($i), array());
				$_MaxCharWidth  = abs($CharDetails[0] + $CharDetails[2]);
				if ($_MaxCharWidth > $MaxCharWidth ) {
					$MaxCharWidth = $_MaxCharWidth;
				}
				$_MaxCharHeight  = abs($CharDetails[1] + $CharDetails[5]);
				if ($_MaxCharHeight > $MaxCharHeight ) {
					$MaxCharHeight = $_MaxCharHeight;
				}
			}
		}
		imagedestroy($oImage);
		
		$this->height = $MaxCharHeight + 2;
		$this->spacing = (int)( ($IcmsConfigCaptcha['captcha_num_chars'] * $MaxCharWidth) / $IcmsConfigCaptcha['captcha_num_chars']);
		$this->width = ($IcmsConfigCaptcha['captcha_num_chars'] * $MaxCharWidth) + ($this->spacing/2);
	}

	/**
	* Return random background
	*
	* @return array
	*/
	function loadBackground() 
	{
		$RandBackground = null;
		if( $backgrounds = $this->_getList("backgrounds", "(gif|jpg|png)") ) {
			$RandBackground = "../backgrounds/".$backgrounds[array_rand($backgrounds)];
		}
		return $RandBackground;
	}

	/**
	* Draw Image background
	*/
	function createFromFile() 
	{
		if ( $RandImage = $this->loadBackground() ) {
			$ImageType = @getimagesize($RandImage);
			switch ( @$ImageType[2] ) {
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
		if(!empty($BackgroundImage)){
			imagecopyresized($this->oImage, $BackgroundImage, 0, 0, 0, 0, imagesx($this->oImage), imagesy($this->oImage), imagesx($BackgroundImage), imagesy($BackgroundImage));
			imagedestroy($BackgroundImage);
		} else {
			$this->drawBars();
		}
	}

	/**
	* Draw Code
	*/
	function drawCode() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for ($i = 0; $i < $IcmsConfigCaptcha['captcha_num_chars'] ; $i++) {
			// select random greyscale colour
			$text_color = imagecolorallocate($this->oImage, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

			// write text to image
			$Angle = mt_rand(10, 30);
			if (($i % 2)) {
				$Angle = mt_rand(-10, -30);
			}

			// select random font size
			$FontSize = mt_rand($IcmsConfigCaptcha['captcha_fontsize_min'], $IcmsConfigCaptcha['captcha_fontsize_max']);

			$CharDetails = imageftbbox($FontSize, $Angle, $this->font, $this->code[$i], array());
			$CharHeight = abs( $CharDetails[1] + $CharDetails[5] );

			// calculate character starting coordinates
			$posX = ($this->spacing/2) + ($i * $this->spacing);
			$posY = 2 + ($this->height / 2) + ($CharHeight / 4);

			imagefttext($this->oImage, $FontSize, $Angle, $posX, $posY, $text_color, $this->font, $this->code[$i], array());
		}
	}

	/**
	* Draw Border
	*/
	function drawBorder() 
	{
		$rgb = rand(50, 150);
		$border_color = imagecolorallocate ($this->oImage, $rgb, $rgb, $rgb);
		imagerectangle($this->oImage, 0, 0, $this->width-1, $this->height-1, $border_color);
	}

	/**
	* Draw Circles background
	*/
	function drawCircles() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for($i = 1; $i <= $IcmsConfigCaptcha['captcha_background_num']; $i++){
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imagefilledellipse($this->oImage, mt_rand(0,$this->width-10), mt_rand(0,$this->height-3), mt_rand(10,20), mt_rand(20,30),$randomcolor);
		}
	}

	/**
	* Draw Lines background
	*/
	function drawLines() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for ($i = 0; $i < $IcmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate($this->oImage, mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imageline($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
		}
	}

	/**
	* Draw Rectangles background
	*/
	function drawRectangles() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for ($i = 1; $i <= $IcmsConfigCaptcha['captcha_background_num']; $i++) {
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imagefilledrectangle($this->oImage, mt_rand(0,$this->width), mt_rand(0,$this->height), mt_rand(0, $this->width), mt_rand(0,$this->height),  $randomcolor);
		}
	}

	/**
	* Draw Bars background
	*/
	function drawBars() 
	{
		for ($i= 0 ; $i <= $this->height;) {
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imageline( $this->oImage, 0, $i, $this->width, $i, $randomcolor );
			$i = $i + 2.5;
		}
		for ($i = 0;$i <= $this->width;) {
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imageline( $this->oImage, $i, 0, $i, $this->height, $randomcolor );
			$i = $i + 2.5;
		}
	}

	/**
	* Draw Ellipses background
	*/
	function drawEllipses() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for($i = 1; $i <= $IcmsConfigCaptcha['captcha_background_num']; $i++){
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			imageellipse($this->oImage, mt_rand(0,$this->width), mt_rand(0,$this->height), mt_rand(0,$this->width), mt_rand(0,$this->height), $randomcolor);
		}
	}

	/**
	* Draw polygons background
	*/
	function drawPolygons() 
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		for($i = 1; $i <= $IcmsConfigCaptcha['captcha_background_num']; $i++){
			$randomcolor = imagecolorallocate ($this->oImage , mt_rand(190,255), mt_rand(190,255), mt_rand(190,255));
			$coords = array();
			for ($j=1; $j <= $IcmsConfigCaptcha['captcha_polygon_point']; $j++) {
				$coords[] = mt_rand(0,$this->width);
				$coords[] = mt_rand(0,$this->height);
			}
			imagefilledpolygon($this->oImage, $coords, $IcmsConfigCaptcha['captcha_polygon_point'], $randomcolor);
		}
	}

	/**
	 *  Create CAPTCHA iamge with BMP
	 *  TODO
	 */
	function createImageBmp($file = "") 
	{
		$image = "";
			
		if(empty($file)) {
			header("Content-type: image/bmp");
			echo $image;
		}else{
			return $image;
		}
	}
}

$image_handler = new IcmsCaptchaImageHandler();
$image_handler->loadImage();

?>