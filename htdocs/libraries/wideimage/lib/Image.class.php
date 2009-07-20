<?php
	/**
    This file is part of WideImage.
		
    WideImage is free software; you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.
		
    WideImage is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.
		
    You should have received a copy of the GNU Lesser General Public License
    along with WideImage; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  **/
	
	if (!defined('WI_LIB_PATH'))
		define('WI_LIB_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
	
	class wiInvalidImageHandleException extends wiException {}
	class wiInvalidImageSourceException extends wiException {}
	
	abstract class wiImage
	{
		protected $handle = null;
		protected $handleReleased = false;
		protected $canvas = null;
		
		function __construct($handle)
		{
			self::assertValidImageHandle($handle);
			$this->handle = $handle;
		}
		
		function __destruct()
		{
			$this->destroy();
		}
		
		/**
		 * This method destroy the image handle, and releases the image resource
		 */
		function destroy()
		{
			if ($this->isValid() && !$this->handleReleased)
				imagedestroy($this->handle);
			
			$this->handle = null;
		}
		
		/**
		 * Returns the GD image resource
		 */
		function getHandle()
		{
			return $this->handle;
		}
		
		/**
		 * @return bool True, if the image object holds a valid GD image, false otherwise
		 */
		function isValid()
		{
			return self::isValidImageHandle($this->handle);
		}
		
		/**
		 * Releases the handle
		 */
		function releaseHandle()
		{
			$this->handleReleased = true;
		}
		
		/**
		 * Check whether the given handle is a valid GD resource
		 */
		static function isValidImageHandle($handle)
		{
			return (is_resource($handle) && get_resource_type($handle) == 'gd');
		}
		
		/**
		 * Throws exception if the handle isn't a valid GD resource
		 */
		static function assertValidImageHandle($handle)
		{
			if (!self::isValidImageHandle($handle))
				throw new wiInvalidImageHandleException("{$handle} is not a valid image handle.");
		}
		
		function assertValidImage()
		{
			self::assertValidImageHandle($this->handle);
		}
		
		/**
		 * Loads an image from a string, file, or a valid image handle. This function
		 * analyzes the input and decides whether to use wiImage::loadFromHandle(),
		 * wiImage::loadFromFile() or wiImage::loadFromString().
		 * 
		 * The second parameter hints the image format when loading from file/url. 
		 * In most cases, however, hinting isn't needed, because WideImage 
		 * loads the image with imagecreatefromstring().
		 * 
		 * <code>
		 * $img = wiImage::load('http://url/image.png');
		 * $img = wiImage::load('/path/to/image.png', 'jpeg');
		 * $img = wiImage::load($image_resource);
		 * $img = wiImage::load($string);
		 * </code>
		 * 
		 * @param mixed $source File name, url, binary string, or GD image resource
		 * @param string $format Hint for image format
		 * @result wiImage wiPaletteImage or wiTrueColorImage instance
		 */
		static function load($source, $format = null)
		{
			$predictedSourceType = '';
			
			if (!$predictedSourceType && self::isValidImageHandle($source))
				$predictedSourceType = 'Handle';
			
			if (!$predictedSourceType)
			{
				// search first $binLength bytes (at a maximum) for ord<32 characters (binary image data)
				$binLength = 64;
				$sourceLength = strlen($source);
				$maxlen = ($sourceLength > $binLength) ? $binLength : $sourceLength;
				for ($i = 0; $i < $maxlen; $i++)
					if (ord($source[$i]) < 32)
					{
						$predictedSourceType = 'String';
						break;
					}
			}
			
			if (!$predictedSourceType)
				$predictedSourceType = 'File';
			
			return call_user_func(array('wiImage', 'loadFrom' . $predictedSourceType), $source, $format);
		}			
		
		/**
		 * Create and load an image from a file or URL. You can override the file 
		 * format by specifying the second parameter.
		 * 
		 * @param string $uri File or url
		 * @param string $format Format hint, usually not needed
		 * @result wiPaletteImage or wiTrueColorImage instance
		 */
		static function loadFromFile($uri, $format = null)
		{
			$handle = @imagecreatefromstring(file_get_contents($uri));
			if (!self::isValidImageHandle($handle))
			{
				$mapper = wiFileMapperFactory::selectMapper($uri, $format);
				$handle = $mapper->load($uri);
			}
			if (!self::isValidImageHandle($handle))
				throw new wiInvalidImageSourceException("File '{$uri}' appears to be an invalid image source.");
			
			return self::loadFromHandle($handle);
		}
		
		/**
		 * Create and load an image from a string. Format is auto-detected.
		 * 
		 * @param string $string Binary data, i.e. from BLOB field in the database
		 * @result wiPaletteImage or wiTrueColorImage instance
		 */
		static function loadFromString($string)
		{
			$handle = @imagecreatefromstring($string);
			if (!self::isValidImageHandle($handle))
				throw new wiInvalidImageSourceException("String doesn't contain valid image data.");
			
			return self::loadFromHandle($handle);
		}
		
		/**
		 * Create and load an image from an image handle.
		 * 
		 * <b>Note:</b> the resulting image object takes ownership of the passed 
		 * handle. When the newly-created image object is destroyed, the handle is 
		 * destroyed too, so it's not a valid image handle anymore. In order to 
		 * preserve the handle for use after object destruction, you have to call 
		 * wiImage::releaseHandle() on the created image instance prior to its
		 * destruction.
		 * 
		 * <code>
		 * $handle = imagecreatefrompng('file.png');
		 * $image = wiImage::loadFromHandle($handle);
		 * </code>
		 * 
		 * @param resource $handle A valid GD image resource
		 * @result wiPaletteImage or wiTrueColorImage instance
		 */
		static function loadFromHandle($handle)
		{
			if (!self::isValidImageHandle($handle))
				throw new wiInvalidImageSourceException("Handle is not a valid GD image resource.");
			
			if (imageistruecolor($handle))
				return new wiTrueColorImage($handle);
			else
				return new wiPaletteImage($handle);
		}
		
		/**
		 * Saves an image to a file
		 * 
		 * The file type is recognized from the $uri. If you save to a GIF8, truecolor images
		 * are automatically converted to palette.
		 * 
		 * @param string $uri The file locator (can be url)
		 * @param string $format Override the format. If null, the format is determined from $uri extension.
		 */
		function saveToFile($uri, $format = null)
		{
			$mapper = wiFileMapperFactory::selectMapper($uri, $format);
			
			$args = func_get_args();
			unset($args[1]);
			array_unshift($args, $this->getHandle());
			call_user_func_array(array($mapper, 'save'), $args);
		}
		
		/**
		 * Returns binary string with image data in format specified by $format
		 * 
		 * @param string $format The format of the image
		 */
		function asString($format)
		{
			ob_start();
			$args = func_get_args();
			$args[0] = null;
			array_unshift($args, $this->getHandle());
			
			$mapper = wiFileMapperFactory::selectMapper(null, $format);
			call_user_func_array(array($mapper, 'save'), $args);
			
			return ob_get_clean();
		}
		
		/**
		 * @return int Image width
		 */
		function getWidth()
		{
			return imagesx($this->handle);
		}
		
		/**
		 * @return int Image height
		 */
		function getHeight()
		{
			return imagesy($this->handle);
		}
		
		/**
		 * Allocate a color by RGB values.
		 * 
		 * @param mixed $R Red-component value or an RGB array (with red, green, blue keys)
		 * @param int $G If $R is int, this is the green component
		 * @param int $B If $R is int, this is the blue component
		 */
		function allocateColor($R, $G = null, $B = null)
		{
			if (is_array($R))
				return imageColorAllocate($this->handle, $R['red'], $R['green'], $R['blue']);
			else
				return imageColorAllocate($this->handle, $R, $G, $B);
		}
		
		/**
		 * @result bool True if the image is transparent, false otherwise
		 */
		function isTransparent()
		{
			return $this->getTransparentColor() >= 0;
		}
		
		/**
		 * @result int Transparent color index
		 */
		function getTransparentColor()
		{
			return imagecolortransparent($this->handle);
		}
		
		/**
		 * @param int $color Transparent color index
		 */
		function setTransparentColor($color)
		{
			return imagecolortransparent($this->handle, $color);
		}
		
		/**
		 * @result mixed Transparent color RGBA array
		 */
		function getTransparentColorRGB()
		{
			return $this->getColorRGB($this->getTransparentColor());
		}
		
		/**
		 * @result mixed Returns color RGBA array of a pixel at $x, $y 
		 */
		function getRGBAt($x, $y)
		{
			return $this->getColorRGB($this->getColorAt($x, $y));
		}
		
		/**
		 * Writes a pixel at the designated coordinates
		 * 
		 * Takes an associative array of colours and uses getExactColor() to
		 * retrieve the exact index color to write to the image with.
		 *
		 * @param int $x
		 * @param int $y
		 * @param array $color
		 */
		function setRGBAt($x, $y, $color)
		{
			$this->setColorAt($x, $y, $this->getExactColor($color));
		}
		
		/**
		 * @result mixed RGBA array for a color with index $colorIndex
		 */
		function getColorRGB($colorIndex)
		{
			return imageColorsForIndex($this->handle, $colorIndex);
		}
		
		/**
		 * @result int Color index for a pixel at $x, $y
		 */
		function getColorAt($x, $y)
		{
			return imagecolorat($this->handle, $x, $y);
		}
		
		/**
		 * Set the color index $color to a pixel at $x, $y
		 */
		function setColorAt($x, $y, $color)
		{
			return imagesetpixel($this->handle, $x, $y, $color);
		}
		
		/**
		 * Returns closest color index that matches the given RGB value. Uses
		 * PHP's imagecolorclosest()
		 * 
		 * @param mixed $R Red or RGBA array
		 */
		function getClosestColor($R, $G = null, $B = null)
		{
			if (is_array($R))
				return imagecolorclosest($this->handle, $R['red'], $R['green'], $R['blue']);
			else
				return imagecolorclosest($this->handle, $R, $G, $B);
		}
		
		/**
		 * Returns the color index that exactly matches the given RGB value. Uses
		 * PHP's imagecolorexact()
		 * 
		 * @param mixed $R Red or RGBA array
		 */
		function getExactColor($R, $G = null, $B = null)
		{
			if (is_array($R))
				return imagecolorexact($this->handle, $R['red'], $R['green'], $R['blue']);
			else
				return imagecolorexact($this->handle, $R, $G, $B);
		}
		
		/**
		 * Copies transparency information from $sourceImage. Optionally fills
		 * the image with the transparent color at (0, 0).
		 * 
		 * @param object $sourceImage
		 * @param bool $fill True if you want to fill the image with transparent color
		 */
		function copyTransparencyFrom($sourceImage, $fill = true)
		{
			if ($sourceImage->isTransparent())
			{
				$rgba = $sourceImage->getTransparentColorRGB();
				$color = $this->allocateColor($rgba);
				$this->setTransparentColor($color);
				if ($fill)
					$this->fill(0, 0, $color);
			}
		}
		
		/**
		 * Fill the image at ($x, $y) with color index $color
		 */
		function fill($x, $y, $color)
		{
			return imagefill($this->handle, $x, $y, $color);
		}
		
		function getOperation($name)
		{
			return wiOpFactory::get($name);
		}
		
		/**
		 * Returns the image's mask
		 * 
		 * Mask is a greyscale image where the shade defines the alpha channel (black = transparent, white = opaque).
		 * 
		 * For opaque images (JPEG), the result will be white. For images with single-color transparency (GIF, 8-bit PNG), 
		 * the areas with the transparent color will be black. For images with alpha channel transparenct, 
		 * the result will be alpha channel.
		 * 
		 * @return wiImage An image mask
		 **/
		function getMask()
		{
			return $this->getOperation('GetMask')->execute($this);
		}
		
		/**
		 * Resize the image to given dimensions.
		 * 
		 * $width and $height are both smart coordinates. This means that you can pass any of these values in:
		 *   - positive or negative integer (100, -20, ...)
		 *   - positive or negative percent string (30%, -15%, ...)
		 *   - positive or negative center offset string (c-20, c+30%, ...)
		 * 
		 * If $width is null, it's calculated proportionally from $height, and vice versa.
		 * 
		 * Example (resize to half-size):
		 * $smaller = $image->resize('50%');
		 * 
		 * $smaller = $image->resize('100', '100', 'inside', 'down');
		 * is the same as
		 * $smaller = $image->resizeDown(100, 100, 'inside');
		 * 
		 * @var mixed $width The new width (smart coordinate), or null.
		 * @var mixed $height The new height (smart coordinate), or null.
		 * @var string $fit 'inside', 'outside', 'fill'
		 * @var string $scale 'down', 'up', 'any'
		 * @return wiImage resized image
		 */
		function resize($width = null, $height = null, $fit = 'inside', $scale = 'any')
		{
			return $this->getOperation('Resize')->execute($this, $width, $height, $fit, $scale);
		}
		
		/**
		 * Same as wiImage::resize(), but the image is only applied if it is larger then the given dimensions.
		 * Otherwise, the resulting image retains the source's dimensions.
		 * 
		 * @var int $width New width, smart coordinate
		 * @var int $height New height, smart coordinate
		 * @var string $fit 'inside', 'outside', 'fill'
		 * @return wiImage resized image
		 */
		function resizeDown($width = null, $height = null, $fit = 'inside')
		{
			return $this->resize($width, $height, $fit, 'down');
		}
		
		/**
		 * Same as wiImage::resize(), but the image is only applied if it is smaller then the given dimensions.
		 * Otherwise, the resulting image retains the source's dimensions.
		 * 
		 * @var int $width New width, smart coordinate
		 * @var int $height New height, smart coordinate
		 * @var string $fit 'inside', 'outside', 'fill'
		 * @return wiImage resized image
		 */
		function resizeUp($width = null, $height = null, $fit = 'inside')
		{
			return $this->resize($width, $height, $fit, 'up');
		}
		
		/**
		 * Rotate the image for angle $angle clockwise.
		 * 
		 * @param int $angle Angle in degrees
		 * @param int $bgColor color of background
		 * @param bool $ignoreTransparent
		 * @return wiImage The rotated image
		 */
		function rotate($angle, $bgColor = null, $ignoreTransparent = true)
		{
			return $this->getOperation('Rotate')->execute($this, $angle, $bgColor, $ignoreTransparent);
		}
		
		/**
		 * This method lays the overlay (watermark) on the image.
		 * 
		 * Hint: if the overlay is a truecolor image with alpha channel, you should leave $pct at 100.
		 * 
		 * @param wiImage $overlay The overlay image
		 * @param mixed $left Left position of the overlay, smart coordinate
		 * @param mixed $top Top position of the overlay, smart coordinate
		 * @param int $pct The opacity of the overlay
		 * @return wiImage The merged image
		 */
		function merge($overlay, $left = 0, $top = 0, $pct = 100)
		{
			return $this->getOperation('Merge')->execute($this, $overlay, $left, $top, $pct);
		}
		
		/**
		 * Returns an image with applied mask
		 * 
		 * A mask is a grayscale image, where the shade determines the alpha channel. Black is fully transparent
		 * and white is fully opaque.
		 * 
		 * @param wiImage $mask The mask image, greyscale
		 * @param mixed $left Left coordinate, smart coordinate
		 * @param mixed $top Top coordinate, smart coordinate
		 * @result wiImage The resulting image
		 **/
		function applyMask($mask, $left = 0, $top = 0)
		{
			return $this->getOperation('ApplyMask')->execute($this, $mask, $left, $top);
		}
		
		function applyFilter($filter, $arg1 = null, $arg2 = null, $arg3 = null)
		{
			return $this->getOperation('ApplyFilter')->execute($this, $filter, $arg1, $arg2, $arg3);
		}
		
		function applyConvolution($matrix, $div, $offset)
		{
			return $this->getOperation('ApplyConvolution')->execute($this, $matrix, $div, $offset);
		}
		
		/**
		 * Returns a cropped rectangular portion of the image
		 * 
		 * If the rectangle specifies area that is out of bounds, it's limited to the current image bounds.
		 * 
		 * Example:
		 * 	$cropped = $img->crop(10, 10, 150, 200); // crops a 150x200 rect at (10, 10)
		 * 	$cropped = $img->crop(-100, -50, 100, 50); // crops a 100x50 rect at the right-bottom of the image
		 * 	$cropped = $img->crop('c-50', 'c-50', 100, 100); // crops a 100x100 rect from the center of the image
		 * 	$cropped = $img->crop('c-25%', 'c-25%', '50%', '50%'); // crops a 50%x50% rect from the center of the image
		 * 
		 * @param mixed $left Left-coordinate of the crop rect, smart coordinate
		 * @param mixed $top Top-coordinate of the crop rect, smart coordinate
		 * @param mixed $width Width of the crop rect, smart coordinate
		 * @param mixed $height Height of the crop rect, smart coordinate
		 * @result wiImage The cropped image
		 **/
		function crop($left, $top, $width, $height)
		{
			return $this->getOperation('Crop')->execute($this, $left, $top, $width, $height);
		}
		
		/**
		 * Performs an auto-crop on the image
		 *
		 * @var int $margin Margin for the crop rectangle, can be negative.
		 * @var int $rgb_threshold RGB difference which still counts as "same color".
		 * @var int $pixel_cutoff How many pixels need to be different to mark a cut line.
		 * @var int $base_color The base color index. If none specified, left-top pixel is used.
		 * @return wiImage The cropped image
		 */
		function autoCrop($margin = 0, $rgb_threshold = 0, $pixel_cutoff = 1, $base_color = null)
		{
			return $this->getOperation('AutoCrop')->execute($this, $margin, $rgb_threshold, $pixel_cutoff, $base_color);
		}
		
		/**
		 * Returns a negative of the image
		 *
		 * This operation differs from calling wiImage::applyFilter(IMG_FILTER_NEGATIVE), because it's 8-bit and transparency safe.
		 * This means it will return an 8-bit image, if the source image is 8-bit. If that 8-bit image has a palette transparency,
		 * the resulting image will keep transparency.
		 *
		 * @return wiImage negative of the image
		 */
		function asNegative()
		{
			if ($this instanceof wiPaletteImage && $this->isTransparent())
				$trgb = $this->getTransparentColorRGB();
			else
				$trgb = null;
			
			$img = $this->getOperation('ApplyFilter')->execute($this, IMG_FILTER_NEGATE);
			
			if ($this instanceof wiPaletteImage)
			{
				$img = $img->asPalette();
				
				if ($trgb)
				{
					$irgb = array('red' => 255 - $trgb['red'], 'green' => 255 - $trgb['green'], 'blue' => 255 - $trgb['blue']);
					$tci = $img->getExactColor($irgb);
					$img->setTransparentColor($tci);
				}
			}
			
			return $img;
		}
		
		/**
		 * Returns a grayscale copy of the image
		 * 
		 * @return wiImage grayscale copy
		 **/
		function asGrayscale()
		{
			return $this->getOperation('AsGrayscale')->execute($this);
		}
		
		/**
		 * Returns a mirrored copy of the image
		 * 
		 * @return wiImage Mirrored copy
		 **/
		function mirror()
		{
			return $this->getOperation('Mirror')->execute($this);
		}
		
		/**
		 * Applies the unsharp filter
		 * 
		 * @param float $amount
		 * @param float $radius
		 * @param float $threshold
		 * @return wiImage Unsharpened copy of the image
		 **/
		function unsharp($amount, $radius, $threshold)
		{
			return $this->getOperation('Unsharp')->execute($this, $amount, $radius, $threshold);
		}
		
		/**
		 * Returns a flipped (mirrored over horizontal line) copy of the image
		 * 
		 * @return wiImage Flipped copy
		 **/
		function flip()
		{
			return $this->getOperation('Flip')->execute($this);
		}
		
		/**
		 * Corrects gamma on the image
		 * 
		 * @param float $inputGamma
		 * @param float $outputGamma
		 * @return wiImage Image with corrected gamma
		 **/
		function correctGamma($inputGamma, $outputGamma)
		{
			return $this->getOperation('CorrectGamma')->execute($this, $inputGamma, $outputGamma);
		}
		
		function __call($name, $args)
		{
			$op = $this->getOperation($name);
			array_unshift($args, $this);
			return call_user_func_array(array($op, 'execute'), $args);
		}
		
		function __toString()
		{
			if ($this->isTransparent())
				return $this->asString('gif');
			else
				return $this->asString('png');
		}
		
		/**
		 * Returns a copy of the image
		 * 
		 * @return wiImage The copy
		 **/
		function copy()
		{
			$dest = $this->doCreate($this->getWidth(), $this->getHeight());
			$dest->copyTransparencyFrom($this, true);
			$this->copyTo($dest, 0, 0);
			return $dest;
		}
		
		/**
		 * Copies this image to another image
		 * 
		 * @param wiImage $dest
		 * @param int $left
		 * @param int $top
		 **/
		function copyTo($dest, $left = 0, $top = 0)
		{
			imageCopy($dest->getHandle(), $this->handle, $left, $top, 0, 0, $this->getWidth(), $this->getHeight());
		}
		
		/**
		 * Returns the canvas object
		 * 
		 * The Canvas object can be used to draw text and shapes on the image
		 * 
		 * Examples:
		 * 	$canvas = $img->getCanvas();
		 * 	$canvas->setFont(new wiFont_TTF('arial.ttf', 15, $img->allocateColor(200, 220, 255)));
		 *	$canvas->writeText(10, 50, "Hello world!");
		 * 
		 *	$canvas->filledRectangle(10, 10, 80, 40, $img->allocateColor(255, 127, 255));
		 * 	$canvas->line(60, 80, 30, 100, $img->allocateColor(255, 0, 0));
		 * 
		 * @return wiCanvas The Canvas object
		 **/
		function getCanvas()
		{
			if ($this->canvas == null)
				$this->canvas = new wiCanvas($this);
			return $this->canvas;
		}
		
		/**
		 * @return bool True, if the image is true-color
		 **/
		abstract function isTrueColor();
		
		/**
		 * Returns a true-color copy of the image
		 * 
		 * @return wiTrueColorImage
		 **/
		abstract function asTrueColor();
		
		/**
		 * Returns a palette copy (8bit) of the image
		 *
		 * @param int $nColors Number of colors in the resulting image, more than 0, less or equal to 255
		 * @param bool $dither Use dithering or not
		 * @param bool $matchPalette Set to true to use imagecolormatch() to match the resulting palette more closely to the original image 
		 * @return wiImage
		 **/
		abstract function asPalette($nColors = 255, $dither = null, $matchPalette = true);
		
		/**
		 * Retrieve an image with selected channels
		 * 
		 * Examples:
		 * 	$channels = $img->getChannels('red', 'blue');
		 * 	$channels = $img->getChannels('alpha', 'green');
		 * 	$channels = $img->getChannels(array('green', 'blue'));
		 * 
		 * @return wiImage
		 **/
		abstract function getChannels();
		
		/**
		 * Returns an image without an alpha channel
		 * 
		 * @return wiImage
		 **/
		abstract function copyNoAlpha();
	}
?>