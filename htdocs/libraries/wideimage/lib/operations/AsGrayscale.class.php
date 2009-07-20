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
	
	class wioAsGrayscale
	{
		function execute($image)
		{
			$palette = $image instanceof wiPaletteImage;
			$transparent = $image->isTransparent();
			
			if ($palette && $transparent)
				$tci = $image->getTransparentColor();
			
			$new = $image->asTrueColor();
			imagefilter($new->getHandle(), IMG_FILTER_GRAYSCALE);
			
			if ($palette)
			{
				$new = $new->asPalette();
				if ($transparent)
					$new->setTransparentColor($tci);
			}
			
			return $new;
		}
	}
?>