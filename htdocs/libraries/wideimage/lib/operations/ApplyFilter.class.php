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
	
	class wioApplyFilter
	{
		static protected $one_arg_filters = array(IMG_FILTER_SMOOTH, IMG_FILTER_CONTRAST, IMG_FILTER_BRIGHTNESS);
		
		function execute($image, $filter, $arg1 = null, $arg2 = null, $arg3 = null)
		{
			$new = $image->asTrueColor();
			
			if (in_array($filter, self::$one_arg_filters))
				imagefilter($new->getHandle(), $filter, $arg1);
			elseif ($filter == IMG_FILTER_COLORIZE)
				imagefilter($new->getHandle(), $filter, $arg1, $arg2, $arg3);
			else
				imagefilter($new->getHandle(), $filter);
			
			return $new;
		}
	}
?>