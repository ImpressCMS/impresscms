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
	
	class wiDimension
	{
		static function isPercent($dim)
		{
			return preg_match('/^[+-]?[0-9]+(\.[0-9]+)?\%$/', $dim);
		}
		
		static function isCenterRelative($dim)
		{
			return preg_match('/^c((\s+)?[+-](\s+)?[0-9]+(\.[0-9]+)?\%?)?$/', $dim);
		}
		
		static function calculateRelativeDimension($max, $dim)
		{
			return intval(round($max * floatval(str_replace('%', '', $dim)) / 100));
		}
		
		static function fix($base, $dim)
		{
			if (self::isCenterRelative($dim))
			{
				$dim = str_replace(array('c', ' '), '', $dim);
				if ($dim == '')
					$dim = 0;
				$center_relative = true;
			}
			else
				$center_relative = false;
			
			if (self::isPercent($dim))
				$v = self::calculateRelativeDimension($base, $dim);
			else
				$v = intval(round(floatval($dim)));
			
			if ($center_relative)
			{
				$v = intval(round($base / 2 + $v));
				if ($v < 0)
					$v = 0;
				
				if ($v >= $base)
					$v = $base - 1;
				
				return $v;
			}
			
			return $v;
		}
		
		static function fixForResize($img, $width, $height)
		{
			if ($width === null && $height === null)
				return array($img->getWidth(), $img->getHeight());
			
			if ($width !== null)
				$width = wiDimension::fix($img->getWidth(), $width);
			
			if ($height !== null)
				$height = wiDimension::fix($img->getHeight(), $height);
			
			if ($width === null)
				$width = round($img->getWidth() * $height / $img->getHeight());
			
			if ($height === null)
				$height = round($img->getHeight() * $width / $img->getWidth());
			
			return array($width, $height);
		}
	}
?>