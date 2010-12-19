<?php
/**
 * ImpressCMS Mimetypes
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

icms_loadLanguageFile('system', 'mimetype', TRUE);

class SystemMimetype extends icms_ipf_Object {
	public $content = FALSE;

	function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('mimetypeid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('extension', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_MIMETYPE_EXTENSION, _CO_ICMS_MIMETYPE_EXTENSION_DSC);
		$this->quickInitVar('types', XOBJ_DTYPE_TXTAREA, TRUE, _CO_ICMS_MIMETYPE_TYPES, _CO_ICMS_MIMETYPE_TYPES_DSC);
		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_MIMETYPE_NAME, _CO_ICMS_MIMETYPE_NAME_DSC);
		$this->quickInitVar('dirname', XOBJ_DTYPE_SIMPLE_ARRAY, TRUE, _CO_ICMS_MIMETYPE_DIRNAME);

		$this->setControl('dirname', array(
			'name' => 'selectmulti',
			'handler' => 'mimetype',
			'method' => 'getModuleList'));
	}

	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}


	public function emptyString($var) {
		return strlen($var) > 0;
	}

	public function getMimetypeName() {
		$ret = $this->getVar('name');
		return $ret;
	}

	public function getMimetypeType() {
		$ret = $this->getVar('types');
		return $ret;
	}

	public function getMimetypeId() {
		$ret = $this->getVar('mimetypeid');
		return $ret;
	}
}

class SystemMimetypeHandler extends icms_ipf_Handler {
	public $objects = FALSE;

	public function __construct($db) {
		parent::__construct($db, 'mimetype', 'mimetypeid', 'mimetypeid', 'name', 'system');
		$this->addPermission('use_extension', _CO_ICMS_MIMETYPE_PERMISSION_VIEW, _CO_ICMS_MIMETYPE_PERMISSION_VIEW_DSC);
	}

	public function UserCanUpload() {
		$handler = new icms_ipf_permission_Handler($this);
		return $handler->getGrantedItems('use_extension');
	}

	public function AllowedMimeTypes() {
		$GrantedItems =  $this->UserCanUpload();
		$array = array();
		$grantedItemValues = array_values($GrantedItems);
		if (!empty($grantedItemValues)) {
			$sql = "SELECT types " . "FROM " . $this->table . " WHERE (mimetypeid='";
			if (count($grantedItemValues)>1) {
				foreach ($grantedItemValues as $grantedItemValue) {
					$sql .= ($grantedItemValue != $grantedItemValues[0]) ? $grantedItemValue."' OR mimetypeid='" : "";
				}
			}
			$sql .= $grantedItemValues[0]."')";
			$Qvalues = $this->query($sql, FALSE);
			for ($i = 0; $i < count($Qvalues); $i++) {
				$values[]= explode(' ', $Qvalues[$i]['types']);
			}
			foreach ($values as $item=>$value) {
				$array = array_merge($array, $value);
			}
		}
		return $array;
	}

	public function getModuleList() {
		return icms_module_Handler::getActive();
	}

	public function AllowedModules($mimetype, $module) {
		$mimetypeid_allowed = $dirname_allowed = FALSE;
		$GrantedItems = $this->UserCanUpload();
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('types', '%'.$mimetype.'%', 'LIKE'));

		$sql = 'SELECT mimetypeid, dirname, types FROM ' . $this->table;
		$rows = $this->query($sql, $criteria);
		if (count($rows) > 1) {
			for ($i = 0; $i < count($rows); $i++) {
				$mimetypeids[]= $rows[$i]['mimetypeid'];
				$dirname[]= explode('|', $rows[$i]['dirname']);
				$types[]= $rows[$i]['types'];
			}

			foreach ($mimetypeids as $mimetypeid) {
				if (in_array($mimetypeid, $GrantedItems)) {
					$mimetypeid_allowed = TRUE;
				}
			}
			foreach ($dirname as $dir) {
				if (!empty($module) && in_array($module, $dir)) {
					$dirname_allowed = TRUE;
				}
			}
		} elseif (count($rows) == 1) {
			$mimetypeid= $rows[0]['mimetypeid'];
			$dirname= explode('|', $rows[0]['dirname']);
			$types= $rows[0]['types'];
			if (in_array($mimetypeid, $GrantedItems)) {
				$mimetypeid_allowed = TRUE;
			}
			if (!empty($module) && in_array($module, $dirname)) {
				$dirname_allowed = TRUE;
			}
		}
		if ($mimetypeid_allowed && $dirname_allowed) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}