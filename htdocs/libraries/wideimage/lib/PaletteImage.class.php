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
	
	class wiPaletteImage extends wiImage
	{
		/**
		 * Create a palette image
		 *
		 * @param int $width
		 * @param int $height
		 * @return wiPaletteImage
		 */
		static function create($width, $height)
		{
			return new wiPaletteImage(imagecreate($width, $height));
		}
		
		function doCreate($width, $height)
		{
			return self::create($width, $height);
		}
		
		function isTrueColor()
		{
			return false;
		}
		
		function asPalette($nColors = 255, $dither = null, $matchPalette = true)
		{
			return $this->copy();
		}
		
		protected function copyAsNew($trueColor = false)
		{
			$width = $this->getWidth();
			$height = $this->getHeight();
			
			if ($trueColor)
				$new = wiTrueColorImage::create($width, $height);
			else
				$new = wiPaletteImage::create($width, $height);
			
			// copy transparency of source to target
			if ($this->isTransparent())
			{
				$rgb = $this->getTransparentColorRGB();
				if (is_array($rgb))
				{
					$tci = $new->allocateColor($rgb['red'], $rgb['green'], $rgb['blue']);
					$new->fill(0, 0, $tci);
					$new->setTransparentColor($tci);
				}
			}
			
			imageCopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height);
			return $new;
		}
		
		function asTrueColor()
		{
			$width = $this->getWidth();
			$height = $this->getHeight();
			$new = wiTrueColorImage::create($width, $height);
			if ($this->isTransparent())
				$new->copyTransparencyFrom($this);
			imageCopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height);
			return $new;
		}
		
		function getChannels()
		{
			$args = func_get_args();
			if (count($args) == 1 && is_array($args[0]))
				$args = $args[0];
			return wiOpFactory::get('CopyChannelsPalette')->execute($this, $args);
		}
		
		function copyNoAlpha()
		{
			return wiImage::loadFromString($this->asString('png'));
		}
	}
?>