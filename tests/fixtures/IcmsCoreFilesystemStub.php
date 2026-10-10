<?php

declare(strict_types=1);

if (!class_exists('icms_core_Filesystem', false)) {
	class icms_core_Filesystem
	{
		public static function mkdir($target, $mode = 0777, $base = ICMS_ROOT_PATH, $metachars = []): bool
		{
			if (is_dir($target)) {
				return true;
			}

			return mkdir($target, $mode, true);
		}
	}
}
