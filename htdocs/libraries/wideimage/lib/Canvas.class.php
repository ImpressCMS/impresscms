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
	
	class wiNoFontException extends wiException {}
	class wiInvalidCanvasMethodException extends wiException {}
	
	class wiCanvas
	{
		protected $handle = 0;
		protected $image = null;
		protected $font = null;
		
		function __construct($img)
		{
			$this->handle = $img->getHandle();
			$this->image = $img;
		}
		
		function setFont($font)
		{
			$this->font = $font;
		}
		
		/**
		 * Write text on the image at specified position
		 * 
		 * You must set a font with a call to wiCanvas::setFont() prior to writing text to the image.
		 * 
		 * @param int $x Left
		 * @param int $y Top
		 * @param string $text Text to write
		 * @param int $angle The angle, defaults to 0
		 */
		function writeText($x, $y, $text, $angle = 0)
		{
			if ($this->font === null)
				throw new wiNoFontException("Can't write text without a font.");
			
			$angle = - floatval($angle);
			if ($angle < 0)
				$angle = 360 + $angle;
			$angle = $angle % 360;
			
			$this->font->writeText($this->image, $x, $y, $text, $angle);
		}
		
		/**
		 * A magic method that allows you to call any PHP function that starts with "image".
		 * 
		 * This is a shortcut to call custom functions on the image handle.
		 * 
		 * Example:
		 * $img = wiImage::load('pic.jpg');
		 * $canvas = $img->getCanvas();
		 * $canvas->filledRect(10, 10, 20, 30, $img->allocateColor(0, 0, 0));
		 * $canvas->line(60, 80, 30, 100, $img->allocateColor(255, 0, 0));
		 */
		function __call($method, $params)
		{
			if (function_exists('image' . $method))
			{
				array_unshift($params, $this->handle);
				call_user_func_array('image' . $method, $params);
			}
			else
				throw new wiInvalidCanvasMethodException("Function doesn't exist: image{$method}.");
		}
	}
?>