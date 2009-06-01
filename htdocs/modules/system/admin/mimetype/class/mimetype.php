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

if (! defined ( "ICMS_ROOT_PATH" ))
	die ( "ImpressCMS root path not defined" );

include_once ICMS_ROOT_PATH . "/kernel/icmspersistableobject.php";

class SystemMimetype extends IcmsPersistableObject {
	
	var $content = false;
	
	function SystemMimetype(&$handler) {
    	$this->IcmsPersistableObject($handler);
    	
		$this->quickInitVar ( 'mimetypeid', XOBJ_DTYPE_INT, true );
		$this->quickInitVar ( 'extension', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_MIMETYPE_EXTENSION, _CO_ICMS_MIMETYPE_EXTENSION_DSC );
		$this->quickInitVar ( 'types', XOBJ_DTYPE_TXTAREA, false, _CO_ICMS_MIMETYPE_TYPES, _CO_ICMS_MIMETYPE_TYPES_DSC );
		$this->quickInitVar ( 'name', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_MIMETYPE_NAME, _CO_ICMS_MIMETYPE_NAME_DSC );
		
	}
	
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array ( $key, array ( ) )) {
			return call_user_func ( array ($this, $key ) );
		}
		return parent::getVar ( $key, $format );
	}
	
	
	function emptyString($var) {
		return (strlen ( $var ) > 0);
	}
	
	function getMimetypeName() {
		$ret = $this->getVar ( 'name' );
		return $ret;
	}
}

class SystemMimetypeHandler extends IcmsPersistableObjectHandler {
	
	var $objects = false;
	
	function SystemMimetypeHandler($db) {
		$this->IcmsPersistableObjectHandler ( $db, 'mimetype', 'mimetypeid', 'mimetypeid', 'name', 'system' );
		$this->addPermission ( 'use_extension', _CO_ICMS_MIMETYPE_PERMISSION_VIEW, _CO_ICMS_MIMETYPE_PERMISSION_VIEW_DSC );
	}
	
	
}

?>