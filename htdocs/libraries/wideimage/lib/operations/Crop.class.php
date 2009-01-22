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
	
	class wioCrop
	{
		function execute($img, $left, $top, $width, $height)
		{
			$width = wiDimension::fix($img->getWidth(), $width);
			$height = wiDimension::fix($img->getHeight(), $height);
			$new = wiTrueColorImage::create($width, $height);
			
			if ($img->isTransparent())
			{
				$new->copyTransparencyFrom($img);
				imagecopyresized(
					$new->getHandle(), $img->getHandle(), 0, 0, $left, $top, $width, $height, $width, $height
					);
			}
			else
			{
				$new->alphaBlending(false);
				$new->saveAlpha(true);
				imagecopyresampled(
					$new->getHandle(), $img->getHandle(), 0, 0, $left, $top, $width, $height, $width, $height
					);
			}
			return $new;
		}
	}
?>