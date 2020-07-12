<?php


namespace ImpressCMS\Core\Models;


use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
 * Describes online user data
 *
 * @package ImpressCMS\Core\Models
 *
 * @property int $online_uid User ID
 * @property string $online_ip User IP
 * @property int $online_updated User last action date
 * @property string $online_uname Username
 * @property int $online_module User last visited module
 */
class Online extends AbstractDatabaseModel
{

	/**
	 * Constructor
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('online_uid', self::DTYPE_INTEGER, null, false);
		$this->initVar('online_ip', self::DTYPE_STRING);
		$this->initVar('online_updated', self::DTYPE_INTEGER);
		$this->initVar('online_uname', self::DTYPE_STRING);
		$this->initVar('online_module', self::DTYPE_INTEGER);

		parent::__construct($handler, $data);
	}

}