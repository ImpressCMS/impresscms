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
		
		function writeText($x, $y, $text, $angle)
		{
			$angle = - floatval($angle);
			if ($angle < 0)
				$angle = 360 + $angle;
			$angle = $angle % 360;
			
			$box = $this->font->getBoundsRect($text);
			$this->font->writeText($this->image, $x + $box['offset_x'], $y + $box['offset_y'], $text, $angle);
		}
	}
?>