<?php
/**
 * Image Creation class for the image captcha
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * Originated from DuGris' SecurityImage <http://www.dugris.info>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_form_elements_captcha_ImageHandler {

	private string $code = '';

	private string $font = '';

	private int $spacing = 0;

	private int $width = 0;

	private int $height = 0;

	private ?GdImage $image = null;

	/**
	 * Create a new code, remember it in the session and send the image
	 */
	public function loadImage(): void {
		$this->createCode();

		$session =& icms_captcha_Manager::session();
		$session['image'] = $this->code;

		$this->createImageGd();
	}

	public function createCode(): void {
		$length = $this->setting('captcha_num_chars', 4);
		$skipCharacters = icms_captcha_Manager::config('captcha_skip_characters', array());
		$code = '';

		while (strlen($code) < $length) {
			$code .= str_replace((array) $skipCharacters, '', bin2hex(random_bytes(8)));
		}
		$code = substr($code, 0, $length);

		$this->code = icms_captcha_Manager::config('captcha_casesensitive', 0) ? $code : strtoupper($code);
	}

	public function createImageGd(): void {
		$this->loadFont();
		$this->setImageSize();

		$this->image = imagecreatetruecolor($this->width, $this->height);
		$background = imagecolorallocate($this->image, 255, 255, 255);
		imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $background);

		switch ((int) icms_captcha_Manager::config('captcha_background_type', 0)) {
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

			default:
				$this->drawBars();
		}

		$this->drawBorder();
		$this->drawCode();

		while (ob_get_level() > 0) {
			ob_end_clean();
		}
		header('Content-type: image/jpeg');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		imagejpeg($this->image);
		imagedestroy($this->image);
	}

	private function setting(string $key, int $default): int {
		$value = (int) icms_captcha_Manager::config($key, $default);

		return $value > 0 ? $value : $default;
	}

	/**
	 * @return string[] files of a subfolder of this directory
	 */
	private function listFiles(string $folder, string $pattern): array {
		$files = glob(__DIR__ . "/{$folder}/*.{$pattern}", GLOB_BRACE);

		return $files === false ? array() : $files;
	}

	private function loadFont(): void {
		$fonts = $this->listFiles('fonts', 'ttf');
		$this->font = $fonts[array_rand($fonts)];
	}

	private function setImageSize(): void {
		$maxCharWidth = 0;
		$maxCharHeight = 0;
		$fontSize = $this->setting('captcha_fontsize_max', 12);
		$length = $this->setting('captcha_num_chars', 4);

		for ($angle = -30; $angle <= 30; $angle += 5) {
			for ($ascii = 65; $ascii <= 90; $ascii++) {
				$details = imageftbbox($fontSize, $angle, $this->font, chr($ascii));
				$maxCharWidth = max($maxCharWidth, abs($details[0] + $details[2]));
				$maxCharHeight = max($maxCharHeight, abs($details[1] + $details[5]));
			}
		}

		$this->height = (int) ceil($maxCharHeight) + 2;
		$this->spacing = (int) ceil($maxCharWidth);
		$this->width = (int) ceil(($length * $maxCharWidth) + ($this->spacing / 2));
	}

	private function createFromFile(): void {
		$backgrounds = $this->listFiles('backgrounds', '{gif,jpg,png}');
		$background = null;

		if (!empty($backgrounds)) {
			$file = $backgrounds[array_rand($backgrounds)];
			$background = match (@getimagesize($file)[2] ?? 0) {
				IMAGETYPE_GIF => imagecreatefromgif($file),
				IMAGETYPE_JPEG => imagecreatefromjpeg($file),
				IMAGETYPE_PNG => imagecreatefrompng($file),
				default => false,
			};
		}

		if (!$background) {
			$this->drawBars();
			return;
		}

		imagecopyresized(
			$this->image, $background, 0, 0, 0, 0,
			imagesx($this->image), imagesy($this->image), imagesx($background), imagesy($background)
		);
		imagedestroy($background);
	}

	private function drawCode(): void {
		$length = strlen($this->code);
		$minSize = $this->setting('captcha_fontsize_min', 10);
		$maxSize = max($minSize, $this->setting('captcha_fontsize_max', 12));

		for ($i = 0; $i < $length; $i++) {
			$color = imagecolorallocate($this->image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
			$angle = ($i % 2) ? mt_rand(-30, -10) : mt_rand(10, 30);
			$fontSize = mt_rand($minSize, $maxSize);

			$details = imageftbbox($fontSize, $angle, $this->font, $this->code[$i]);
			$charHeight = abs($details[1] + $details[5]);

			$x = (int) round(($this->spacing / 2) + ($i * $this->spacing));
			$y = (int) round(2 + ($this->height / 2) + ($charHeight / 4));

			imagefttext($this->image, $fontSize, $angle, $x, $y, $color, $this->font, $this->code[$i]);
		}
	}

	private function drawBorder(): void {
		$shade = mt_rand(50, 150);
		$color = imagecolorallocate($this->image, $shade, $shade, $shade);
		imagerectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $color);
	}

	private function backgroundColor(): int {
		return imagecolorallocate($this->image, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
	}

	private function drawCircles(): void {
		for ($i = 0; $i < $this->setting('captcha_background_num', 50); $i++) {
			imagefilledellipse(
				$this->image, mt_rand(0, max(0, $this->width - 10)), mt_rand(0, max(0, $this->height - 3)),
				mt_rand(10, 20), mt_rand(20, 30), $this->backgroundColor()
			);
		}
	}

	private function drawLines(): void {
		for ($i = 0; $i < $this->setting('captcha_background_num', 50); $i++) {
			imageline(
				$this->image, mt_rand(0, $this->width), mt_rand(0, $this->height),
				mt_rand(0, $this->width), mt_rand(0, $this->height), $this->backgroundColor()
			);
		}
	}

	private function drawRectangles(): void {
		for ($i = 0; $i < $this->setting('captcha_background_num', 50); $i++) {
			imagefilledrectangle(
				$this->image, mt_rand(0, $this->width), mt_rand(0, $this->height),
				mt_rand(0, $this->width), mt_rand(0, $this->height), $this->backgroundColor()
			);
		}
	}

	private function drawBars(): void {
		for ($y = 0; $y <= $this->height; $y += 3) {
			imageline($this->image, 0, $y, $this->width, $y, $this->backgroundColor());
		}

		for ($x = 0; $x <= $this->width; $x += 3) {
			imageline($this->image, $x, 0, $x, $this->height, $this->backgroundColor());
		}
	}

	private function drawEllipses(): void {
		for ($i = 0; $i < $this->setting('captcha_background_num', 50); $i++) {
			imageellipse(
				$this->image, mt_rand(0, $this->width), mt_rand(0, $this->height),
				mt_rand(0, $this->width), mt_rand(0, $this->height), $this->backgroundColor()
			);
		}
	}

	private function drawPolygons(): void {
		$points = max(3, $this->setting('captcha_polygon_point', 3));

		for ($i = 0; $i < $this->setting('captcha_background_num', 50); $i++) {
			$coordinates = array();
			for ($j = 0; $j < $points; $j++) {
				$coordinates[] = mt_rand(0, $this->width);
				$coordinates[] = mt_rand(0, $this->height);
			}
			imagefilledpolygon($this->image, $coordinates, $this->backgroundColor());
		}
	}
}
