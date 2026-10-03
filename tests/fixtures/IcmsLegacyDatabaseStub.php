<?php

declare(strict_types=1);

if (!class_exists('icms_db_legacy_Database', false)) {
	class icms_db_legacy_Database
	{
		public function query($sql)
		{
			return true;
		}

		public function fetchArray($result)
		{
			return [];
		}

		public function escape($value)
		{
			return addslashes((string) $value);
		}
	}
}
