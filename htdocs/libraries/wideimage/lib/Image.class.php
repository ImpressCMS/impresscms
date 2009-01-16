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
		
		function destroy()
		{
			if ($this->isValid() && !$this->handleReleased)
				imagedestroy($this->handle);
			
			$this->handle = null;
		}
		
		function getHandle()
		{
			return $this->handle;
		}
		
		function isValid()
		{
			return self::isValidImageHandle($this->handle);
		}
		
		function releaseHandle()
		{
			$this->handleReleased = true;
		}
		
		static function isValidImageHandle($handle)
		{
			return (is_resource($handle) && get_resource_type($handle) == 'gd');
		}
		
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
		 * <code>
		 * $img = wiImage::load('http://url/image.png');
		 * $img = wiImage::load('/path/to/image.png', 'jpeg');
		 * $img = wiImage::load($image_resource);
		 * $img = wiImage::load($string);
		 * </code>
		 * 
		 * @result wiPaletteImage or wiTrueColorImage instance
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
		 * @result wiPaletteImage or wiTrueColorImage instance
		 */
		static function loadFromFile($uri, $format = null)
		{
			$mapper = wiFileMapperFactory::selectMapper($uri, $format);
			$handle = $mapper->load($uri);
			if (!self::isValidImageHandle($handle))
				throw new wiInvalidImageSourceException("File '{$uri}' appears to be an invalid image source.");
			
			return self::loadFromHandle($handle);
		}
		
		/**
		 * Create and load an image from a string. Format is auto-detected.
		 * 
		 * @result wiPaletteImage or wiTrueColorImage instance
		 */
		static function loadFromString($string)
		{
			$handle = imagecreatefromstring($string);
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
		
		function getWidth()
		{
			return imagesx($this->handle);
		}
		
		function getHeight()
		{
			return imagesy($this->handle);
		}
		
		/**
		 * Allocate a color by RGB values.
		 * 
		 * @param mixed $R Red-component value or an RGB array (with red, green, blue keys)
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
		function setRGBAt($x, $y, $color) {
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
		
		function getMask()
		{
			return $this->getOperation('GetMask')->execute($this);
		}
		
		function resize($width = null, $height = null, $fit = 'inside')
		{
			return $this->getOperation('Resize')->execute($this, $width, $height, $fit);
		}
		
		function rotate($angle, $bgColor = null, $ignoreTransparent = true)
		{
			return $this->getOperation('Rotate')->execute($this, $angle, $bgColor, $ignoreTransparent);
		}
		
		function merge($overlay, $x = 0, $y = 0, $pct = 100)
		{
			return $this->getOperation('Merge')->execute($this, $overlay, $x, $y, $pct);
		}
		
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
		
		function crop($left, $top, $width, $height)
		{
			return $this->getOperation('Crop')->execute($this, $left, $top, $width, $height);
		}
		
		function asNegative()
		{
			return $this->getOperation('ApplyFilter')->execute($this, IMG_FILTER_NEGATE);
		}
		
		function asGrayscale()
		{
			return $this->getOperation('ApplyFilter')->execute($this, IMG_FILTER_GRAYSCALE);
		}
		
		function mirror()
		{
			return $this->getOperation('Mirror')->execute($this);
		}
		
		function unsharp($amount, $radius, $threshold)
		{
			return $this->getOperation('Unsharp')->execute($this, $amount, $radius, $threshold);
		}
		
		function flip()
		{
			return $this->getOperation('Flip')->execute($this);
		}
		
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
		
		function copy()
		{
			$dest = $this->doCreate($this->getWidth(), $this->getHeight());
			$dest->copyTransparencyFrom($this, true);
			$this->copyTo($dest, 0, 0);
			return $dest;
		}
		
		function copyTo($dest, $left = 0, $top = 0)
		{
			imageCopy($dest->getHandle(), $this->handle, $left, $top, 0, 0, $this->getWidth(), $this->getHeight());
		}
		
		function getCanvas()
		{
			if ($this->canvas == null)
				$this->canvas = new wiCanvas($this);
			return $this->canvas;
		}
		
		abstract function isTrueColor();
		abstract function asTrueColor();
		abstract function asPalette();
		abstract function getChannels();
		abstract function copyNoAlpha();
	}
?>