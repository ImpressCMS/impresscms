<?php
/**
 * Database Base Class
 *
 * Defines abstract database wrapper class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Database
 * @subpackage	Legacy
 * @author		Kazumi Ono  <onokazu@xoops.org>
 * @author		Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 * @version		SVN: $Id$
 */

/**
 * Make sure this is only included once!
 */
if (defined("XOOPS_C_DATABASE_INCLUDED")) exit();
define("XOOPS_C_DATABASE_INCLUDED",1);

/**
 * Abstract base class for Database access classes
 *
 * @package database
 * @subpackage  main
 * @author		Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 */
abstract class icms_db_legacy_Database {

	/**
	 * Prefix for tables in the database
	 * @var string
	 */
	public $prefix = '';

	/**
	 * reference to a {@link icms_core_Logger} object
	 * @see icms_core_Logger
	 * @var object icms_core_Logger
	 */
	public $logger;

	/**
	 * If statements that modify the database are selected
	 * @var boolean
	 */
	public $allowWebChanges = false;

	/**
	 * assign a {@link icms_core_Logger} object to the database
	 *
	 * @see icms_core_Logger
	 * @param object $logger reference to a {@link icms_core_Logger} object
	 */
	public function setLogger(&$logger) {
		$this->logger =& $logger;
	}

	/**
	 * set the prefix for tables in the database
	 *
	 * @param string $value table prefix
	 */
	public function setPrefix($value) {
		$this->prefix = $value;
	}

	/**
	 * attach the prefix.'_' to a given tablename
	 *
	 * if tablename is empty, only prefix will be returned
	 *
	 * @param string $tablename tablename
	 * @return string prefixed tablename, just prefix if tablename is empty
	 */
	public function prefix($tablename='') {
		if ( $tablename != '' ) {
			return $this->prefix .'_'. $tablename;
		} else {
			return $this->prefix;
		}
	}
}

