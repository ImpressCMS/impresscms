<?php
/**
 * ImpressCMS Mimetypes
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package     ImpressCMS\Modules\System\Class\Mimetype
 */

/* This may be loaded by other modules - and not just through the cpanel */
icms_loadLanguageFile('system', 'mimetype', TRUE);

/**
 * Handler for the mimetype object class
 *
 * @package     ImpressCMS\Modules\System\Class\Mimetype
 */
class mod_system_MimetypeHandler extends icms_ipf_Handler {

	public $objects = FALSE;

	/**
	 * Creates an instance of the mimetype handler
	 *
	 * @param object $db
	 */
	public function __construct($db) {
		parent::__construct($db, 'mimetype', 'mimetypeid', 'mimetypeid', 'name', 'system');
		$this->addPermission('use_extension', _CO_ICMS_MIMETYPE_PERMISSION_VIEW, _CO_ICMS_MIMETYPE_PERMISSION_VIEW_DSC);
	}

	/**
	 *
	 * @return	array
	 */
	public function UserCanUpload() {
		$handler = new icms_ipf_permission_Handler($this);
		return $handler->getGrantedItems('use_extension');
	}

	/**
	 * Returns a list of mimetypes allowed for the user
	 * @return	array
	 */
	public function AllowedMimeTypes() {
		$GrantedItems =  $this->UserCanUpload();
		$array = array();
		$grantedItemValues = array_values($GrantedItems);
		if (!empty($grantedItemValues)) {
			$sql = "SELECT types " . "FROM " . $this->table . " WHERE (mimetypeid='";
			if (count($grantedItemValues)>1) {
				foreach ($grantedItemValues as $grantedItemValue) {
					$sql .= ($grantedItemValue != $grantedItemValues[0]) ? $grantedItemValue . "' OR mimetypeid='" : "";
				}
			}
			$sql .= $grantedItemValues[0] . "')";
			$Qvalues = $this->query($sql, FALSE);
			foreach ($Qvalues as $Qvalue) {
				$values[]= explode(' ', $Qvalue['types']);
			}
			foreach ($values as $item=>$value) {
				$array = array_merge($array, $value);
			}
		}
		return $array;
	}

	/**
	 *
	 *
	 * @param string $mimetype
	 * @param string $module
	 * @return	boolean
	 */
	public function AllowedModules($mimetype, $module) {
		$mimetypeid_allowed = $dirname_allowed = FALSE;
		$GrantedItems = $this->UserCanUpload();
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('types', '%' . $mimetype . '%', 'LIKE'));

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
