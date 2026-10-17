<?php

declare(strict_types=1);

if (!function_exists('icms_loadLanguageFile')) {
	function icms_loadLanguageFile($name, $module = null)
	{
		return true;
	}
}

if (!class_exists('icms', false)) {
	class icms
	{
		public static $config;
	}
}

if (!isset(icms::$config)) {
	icms::$config = new class {
		public function getConfigsByCat($category)
		{
			return [
				'censor_enable' => false,
				'censor_replace' => '*',
				'censor_words' => [],
			];
		}
	};
}

if (!function_exists('icms_currency')) {
	function icms_currency($value)
	{
		return (float) $value;
	}
}
