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
	
	class wiFont_TTF
	{
		public $face;
		public $size;
		public $color;
		
		function __construct($face, $size, $color)
		{
			$this->face = $face;
			$this->size = $size;
			$this->color = $color;
		}
		
		function getBoundsRect($text)
		{
			$box = imagettfbbox($this->size, 0, $this->face, $text);
			
			$rect = array(
				'offset_x' => - $box[0] - 1,
				'offset_y' => abs($box[7]) - 1,
				'width' => abs($box[2] - $box[0]),
				'height' => abs($box[1] - $box[7])
				);
			/**
			print_r($box);
			print_r($rect);
			exit;
			/**/
			return $rect;
		}
		
		function writeText($image, $x, $y, $text, $angle = 0)
		{
			if ($image->isTrueColor())
				$image->alphaBlending(true);
			
			imagettftext($image->getHandle(), $this->size, $angle, $x, $y, $this->color, $this->face, $text);
		}
	}
	
?>