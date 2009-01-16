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
	
	class wioRotate
	{
		function execute($image, $angle, $bgColor, $ignoreTransparent)
		{
			$angle = -floatval($angle);
			if ($angle < 0)
				$angle = 360 + $angle;
			$angle = $angle % 360;
			
			if ($angle == 0)
				return $image->copy();
			
			if ($bgColor === null)
			{
				if ($image->isTransparent())
					$bgColor = $image->getTransparentColor();
				else
				{
					$tc = array('red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 127);
					
					if ($image->isTrueColor())
					{
						$bgColor = $image->getExactColorAlpha($tc);
						if ($bgColor == -1)
							$bgColor = $image->allocateColorAlpha($tc);
					}
					else
					{
						$bgColor = $image->getExactColor($tc);
						if ($bgColor == -1)
							$bgColor = $image->allocateColor($tc);
					}
				}
			}
			
			return new wiTrueColorImage(imagerotate($image->getHandle(), $angle, $bgColor, $ignoreTransparent));
		}
	}
?>