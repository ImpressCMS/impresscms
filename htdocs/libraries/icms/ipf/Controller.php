<?php

/**
 * This class is responsible for providing operations to an object for managing the object's manipulation
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Ipf
 * @subpackage	Controller
 * @since		1.1
 * @author		Original idea by Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @author		marcan <marcan@impresscms.org>
 * @version		SVN: $Id: Controller.php 11972 2012-08-26 17:49:13Z skenow $
 * @todo		Use language constants for messages
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 *
 *
 * @category	ICMS
 * @package		Ipf
 * @subpackage	Controller
 *
 */
class icms_ipf_Controller {

	/** */
	public $handler;

	/**
	 *
	 * @param $handler
	 */
	public function __construct($handler) {
		$this->handler=$handler;
	}

	/**
	 *
	 * @param	obj		$icmsObj
	 */
	public function postDataToObject(&$icmsObj) {
		foreach (array_keys($icmsObj->vars) as $key) {
			// do not post data if control is a label
			$control = $icmsObj->getControl($key);
			if (is_array($control) && isset($control['name']) && $control['name'] == "label") continue;

			switch ($icmsObj->vars[$key]['data_type']) {
				case XOBJ_DTYPE_IMAGE:
					if (isset($_POST['url_' . $key]) && $_POST['url_' . $key] !='') {
						$eventResult = $this->handler->executeEvent('beforeFileUnlink', $icmsObj);
						if (!$eventResult) {
							$icmsObj->setErrors("An error occured during the beforeFileUnlink event");
						}
						$oldFile = $icmsObj->getUploadDir(true) . $icmsObj->getVar($key, 'e');
						$icmsObj->setVar($key, $_POST['url_' . $key]);
						if (is_file($oldFile) ) unlink($oldFile);
						$eventResult = $this->handler->executeEvent('afterFileUnlink', $icmsObj);
						if (!$eventResult) {
							$icmsObj->setErrors("An error occured during the afterFileUnlink event");
						}
					}
					if (isset($_POST['delete_' . $key]) && $_POST['delete_' . $key] == '1') {
						$eventResult = $this->handler->executeEvent('beforeFileUnlink', $icmsObj);
						if (!$eventResult) {
							$icmsObj->setErrors("An error occured during the beforeFileUnlink event");
						}
						$oldFile = $icmsObj->getUploadDir(true) . $icmsObj->getVar($key, 'e');
						$icmsObj->setVar($key, '');
						if (is_file($oldFile) ) unlink($oldFile);
						$eventResult = $this->handler->executeEvent('afterFileUnlink', $icmsObj);
						if (!$eventResult) {
							$icmsObj->setErrors("An error occured during the afterFileUnlink event");
						}
					}
					break;

				case XOBJ_DTYPE_URLLINK:
					$linkObj = $icmsObj->getUrlLinkObj($key);
					$linkObj->setVar('mid', $_POST['mid_' . $key]);
					$linkObj->setVar('caption', $_POST['caption_' . $key]);
					$linkObj->setVar('description', $_POST['desc_' . $key]);
					$linkObj->setVar('target', $_POST['target_' . $key]);
					$linkObj->setVar('url', $_POST['url_' . $key]);
					if ($linkObj->getVar('url') != '') {
						$icmsObj->storeUrlLinkObj($linkObj);
					}
					//@todo: catch errors
					$icmsObj->setVar($key, $linkObj->getVar('urllinkid'));
					break;

				case XOBJ_DTYPE_FILE:
					if (!isset($_FILES['upload_' . $key]['name']) || $_FILES['upload_' . $key]['name'] == '') {
						$fileObj = $icmsObj->getFileObj($key);
						$fileObj->setVar('mid', $_POST['mid_' . $key]);
						$fileObj->setVar('caption', $_POST['caption_' . $key]);
						$fileObj->setVar('description', $_POST['desc_' . $key]);
						$fileObj->setVar('url', $_POST['url_' . $key]);
						if (!($fileObj->getVar('url') == '' && $fileObj->getVar('url') == '' && $fileObj->getVar('url') == '')) {
							$res = $icmsObj->storeFileObj($fileObj);
							if ($res) {
								$icmsObj->setVar($key, $fileObj->getVar('fileid'));
							} else {
								//error setted, but no error message (to be improved)
								$icmsObj->setErrors($fileObj->getErrors());
							}
						}
					}
					break;

				case XOBJ_DTYPE_STIME:
				case XOBJ_DTYPE_MTIME:
				case XOBJ_DTYPE_LTIME:
					// check if this field's value is available in the POST array
					if (is_array($_POST[$key]) && isset($_POST[$key]['date'])) {
						$value = strtotime($_POST[$key]['date']) + $_POST[$key]['time'];
					// in case the field is hidden, it's not formated so we can simply take the value and store it
					} elseif (filter_var($_POST[$key], FILTER_VALIDATE_INT) == $_POST[$key]) {
						$value = (int)$_POST[$key];
					} else {
						$value = strtotime($_POST[$key]);
					}
					$icmsObj->setVar($key, $value);
					break;

				case XOBJ_DTYPE_URL:
					if (isset($_POST[$key])) {
						$icmsObj->setVar($key, filter_var($_POST[$key], FILTER_SANITIZE_URL));
					}
					break;

				case XOBJ_DTYPE_ARRAY:
					if (is_array($_POST[$key])) {
						$icmsObj->setVar($key, serialize($_POST[$key]));
					}
					break;

				default:
					$icmsObj->setVar($key, $_POST[$key]);
					break;
			}
		}
	}

	/**
	 *
	 * @param	obj		$icmsObj	Object
	 * @param	int		$objectid
	 * @param	str		$created_success_msg	Message to display on successful creation
	 * @param	str		$modified_success_msg	Message to display on successful modification
	 * @param	bool	$redirect_page			Whether to redirect afterwards, or not
	 * @param	bool	$debug					Whether to display debug information, or not
	 */
	public function &doStoreFromDefaultForm(&$icmsObj, $objectid, $created_success_msg, $modified_success_msg, $redirect_page=false, $debug=false) {
		global $impresscms;
		$this->postDataToObject($icmsObj);

		if ($icmsObj->isNew()) {
			$redirect_msg = $created_success_msg;
		} else {
			$redirect_msg = $modified_success_msg;
		}

		// Check if there were uploaded files
		$uploaderResult = true;
		if (isset($_POST['icms_upload_image']) || isset($_POST['icms_upload_file'])) {
			$uploaderObj = new icms_file_MediaUploadHandler($icmsObj->getImageDir(true), $this->handler->_allowedMimeTypes, $this->handler->_maxFileSize, $this->handler->_maxWidth, $this->handler->_maxHeight);
			foreach ( $_FILES as $name=>$file_array) {
				if (isset ($file_array['name']) && $file_array['name'] != "" && in_array(str_replace('upload_', '', $name), array_keys($icmsObj->vars))) {
					if ($uploaderObj->fetchMedia($name)) {
						$uploaderObj->setTargetFileName(time() . "_" . $uploaderObj->getMediaName());
						if ($uploaderObj->upload()) {
							$uploaderResult = $uploaderResult && true;
							// Find the related field in the icms_ipf_Object
							$related_field = str_replace('upload_', '', $name);
							$uploadedArray[] = $related_field;
							// if it's a richfile
							if ($icmsObj->vars[$related_field]['data_type'] == XOBJ_DTYPE_FILE) {
								$object_fileurl = $icmsObj->getUploadDir();
								$fileObj = $icmsObj->getFileObj($related_field);
								$fileObj->setVar('url', $object_fileurl . $uploaderObj->getSavedFileName());
								$fileObj->setVar('mid', $_POST['mid_' . $related_field]);
								$fileObj->setVar('caption', $_POST['caption_' . $related_field]);
								$fileObj->setVar('description', $_POST['desc_' . $related_field]);
								$icmsObj->storeFileObj($fileObj);
								$icmsObj->setVar($related_field, $fileObj->getVar('fileid'));
							} else {
								$eventResult = $this->handler->executeEvent('beforeFileUnlink', $icmsObj);
								if (!$eventResult) {
									$icmsObj->setErrors("An error occured during the beforeFileUnlink event");
									$uploaderResult = $uploaderResult && false;
								}

								$old_file = $icmsObj->getUploadDir(true) . $icmsObj->getVar($related_field);
								if (is_file($old_file) ) unlink($old_file);
								$icmsObj->setVar($related_field, $uploaderObj->getSavedFileName());

								$eventResult = $this->handler->executeEvent('afterFileUnlink', $icmsObj);
								if (!$eventResult) {
									$icmsObj->setErrors("An error occured during the afterFileUnlink event");
									$uploaderResult = $uploaderResult && false;
								}
							}
						} else {
							$icmsObj->setErrors($uploaderObj->getErrors(false));
							$uploaderResult = $uploaderResult && false;
						}
					} else {
						$icmsObj->setErrors($uploaderObj->getErrors(false));
						$uploaderResult = $uploaderResult && false;
					}
				}

			}
		}

		if ($uploaderResult) {
			if ($debug) {
				$storeResult = $this->handler->insertD($icmsObj);
			} else {
				$storeResult = $this->handler->insert($icmsObj);
			}
		} else {
			$storeResult = false;
		}

		if ($storeResult) {
			if ($this->handler->getPermissions()) {
				$icmspermissions_handler = new icms_ipf_permission_Handler($this->handler);
				$icmspermissions_handler->storeAllPermissionsForId($icmsObj->id());
			}
		}

		if ($redirect_page === null) {
			return $icmsObj;
		} else {
			if (!$storeResult) {
				redirect_header($impresscms->urls['previouspage'], 3, _CO_ICMS_SAVE_ERROR . $icmsObj->getHtmlErrors());
			} else {
				$redirect_page = $redirect_page ? $redirect_page : icms_get_page_before_form();
				redirect_header($redirect_page, 2, $redirect_msg);
			}
		}
	}

	/**
	 * Store the object in the database autmatically from a form sending POST data
	 *
	 * @param string $created_success_msg message to display if new object was created
	 * @param string $modified_success_msg message to display if object was successfully edited
	 * @param string $created_redir_page redirect page after creating the object
	 * @param string $modified_redir_page redirect page after editing the object
	 * @param string $redirect_page redirect page, if not set, then we backup once
	 * @param bool $exit if set to TRUE then the script ends
	 * @return bool
	 */
	public function &storeFromDefaultForm($created_success_msg, $modified_success_msg, $redirect_page=false, $debug=false, $x_param = false) {
		$objectid = ( isset($_POST[$this->handler->keyName]) )
				? (int) $_POST[$this->handler->keyName]
				: 0;
		if ($debug) {
			if ($x_param) {
				$icmsObj = $this->handler->getD($objectid, true,  $x_param);
			} else {
				$icmsObj = $this->handler->getD($objectid);
			}

		} else {
			if ($x_param) {
				$icmsObj = $this->handler->get($objectid, true, false, false, $x_param);
			} else {
				$icmsObj = $this->handler->get($objectid);
			}
		}

		/**
		 * @todo multilanguage persistable handler is not fully implemented yet
		 */

		// if handler is the Multilanguage handler, we will need to treat this for multilanguage
		if (is_subclass_of($this->handler, 'icmspersistablemlobjecthandler')) {

			if ($icmsObj->isNew()) {
				// This is a new object. We need to store the meta data and then the language data
				// First, we will get rid of the multilanguage data to only store the meta data
				$icmsObj->stripMultilanguageFields();
				$newObject =& $this->doStoreFromDefaultForm($icmsObj, $objectid, $created_success_msg, $modified_success_msg, $redirect_page, $debug);
				/**
				 * @todo we need to trap potential errors here
				 */

				// ok, the meta daa is stored. Let's recreate the object and then
				// get rid of anything not multilanguage
				unset($icmsObj);
				$icmsObj = $this->handler->get($objectid);
				$icmsObj->stripNonMultilanguageFields();

				$icmsObj->setVar($this->handler->keyName, $newObject->getVar($this->handler->keyName));
				$this->handler->changeTableNameForML();
				$ret =& $this->doStoreFromDefaultForm($icmsObj, $objectid, $created_success_msg, $modified_success_msg, $redirect_page, $debug);

				return $ret;
			}
		} else {
			return $this->doStoreFromDefaultForm($icmsObj, $objectid, $created_success_msg, $modified_success_msg, $redirect_page, $debug);
		}
	}

	/**
	 * Stores an object and shows debug information
	 */
	public function &storeicms_ipf_ObjectD() {
		return $this->storeicms_ipf_Object(true);
	}

	/**
	 *
	 * @param	bool	$debug
	 * @param	bool	$xparam
	 * @see		storeFromDefaultForm
	 */
	public function &storeicms_ipf_Object($debug=false, $xparam = false) {
		$ret =& $this->storeFromDefaultForm('', '', null, $debug, $xparam);

		return $ret;
	}

	/**
	 * Handles deletion of an object which keyid is passed as a GET param
	 *
	 * @param string $redir_page redirect page after deleting the object
	 * @return bool
	 */
	public function handleObjectDeletion($confirm_msg = false, $op='del', $userSide=false) {
		global $impresscms;

		$objectid = (isset($_REQUEST[$this->handler->keyName])) ? (int) $_REQUEST[$this->handler->keyName] : 0;
		$icmsObj = $this->handler->get($objectid);

		if ($icmsObj->isNew()) {
			redirect_header("javascript:history.go(-1)", 3, _CO_ICMS_NOT_SELECTED);
			exit();
		}

		$confirm = ( isset($_POST['confirm']) ) ? $_POST['confirm'] : 0;
		if ($confirm) {
			if (!$this->handler->delete($icmsObj)) {
				redirect_header($_POST['redirect_page'], 3, _CO_ICMS_DELETE_ERROR . $icmsObj->getHtmlErrors());
				exit;
			}

			redirect_header($_POST['redirect_page'], 3, _CO_ICMS_DELETE_SUCCESS);
			exit();
		} else {
			// no confirm: show deletion condition

			icms_cp_header();

			if (!$confirm_msg) {
				$confirm_msg = _CO_ICMS_DELETE_CONFIRM;
			}

			$hiddens = array(
						'op' => $op,
						$this->handler->keyName => $icmsObj->getVar($this->handler->keyName),
						'confirm' => 1,
						'redirect_page' => $impresscms->urls['previouspage']
			);
			if ($this->handler->_moduleName == 'system') {
				$hiddens['fct'] = isset($_GET['fct']) ? $_GET['fct'] : false;
			}
			icms_core_Message::confirm($hiddens, xoops_getenv('SCRIPT_NAME'), sprintf($confirm_msg , $icmsObj->getVar($this->handler->identifierName)), _CO_ICMS_DELETE);

			icms_cp_footer();

		}
		exit();
	}

	/**
	 *
	 * @param	bool	$confirm_msg
	 * @param	string	$op
	 */
	public function handleObjectDeletionFromUserSide($confirm_msg = false, $op='del') {
		global $icmsTpl, $impresscms;

		$objectid = ( isset($_REQUEST[$this->handler->keyName]) ) ? (int) ($_REQUEST[$this->handler->keyName]) : 0;
		$icmsObj = $this->handler->get($objectid);

		if ($icmsObj->isNew()) {
			redirect_header("javascript:history.go(-1)", 3, _CO_ICMS_NOT_SELECTED);
			exit();
		}

		$confirm = ( isset($_POST['confirm']) ) ? $_POST['confirm'] : 0;
		if ($confirm) {
			if (!$this->handler->delete($icmsObj)) {
				redirect_header($_POST['redirect_page'], 3, _CO_ICMS_DELETE_ERROR . $icmsObj->getHtmlErrors());
				exit;
			}

			redirect_header($_POST['redirect_page'], 3, _CO_ICMS_DELETE_SUCCESS);
			exit();
		} else {
			// no confirm: show deletion condition
			if (!$confirm_msg) {
				$confirm_msg = _CO_ICMS_DELETE_CONFIRM;
			}

			ob_start();
			icms_core_Message::confirm(array(
				'op' => $op,
				$this->handler->keyName => $icmsObj->getVar($this->handler->keyName),
				'confirm' => 1,
				'redirect_page' => $impresscms->urls['previouspage']),
				xoops_getenv('SCRIPT_NAME'),
				sprintf($confirm_msg ,
				$icmsObj->getVar($this->handler->identifierName)),
				_CO_ICMS_DELETE
			);
			$icmspersistable_delete_confirm = ob_get_clean();
			$icmsTpl->assign('icmspersistable_delete_confirm', $icmspersistable_delete_confirm);
		}
	}

	/**
	 * Retrieve the object admin side link for a {@link icms_ipf_view_Single} page
	 *
	 * @param	object	$icmsObj	reference to the object from which we want the user side link
	 * @param	bool	$onlyUrl	whether or not to return a simple URL or a full <a> link
	 * @param	bool	$withimage	return a linked image instead of linked text
	 * @return	string	admin side link to the object
	 */
	public function getAdminViewItemLink($icmsObj, $onlyUrl=false, $withimage=false) {
		$ret = $this->handler->_moduleUrl . "admin/"
			. $this->handler->_page . "?op=view&amp;"
			. $this->handler->keyName . "="
			. $icmsObj->getVar($this->handler->keyName);
		if ($onlyUrl) {
			return $ret;
		} elseif ($withimage) {
			return "<a href='" . $ret . "'>
					<img src='" . ICMS_IMAGES_SET_URL
					. "/actions/viewmag.png' style='vertical-align: middle;' alt='"
					. _CO_ICMS_ADMIN_VIEW . "'  title='"
					. _CO_ICMS_ADMIN_VIEW . "'/></a>";
		}

		return "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 * Retrieve the object user side link
	 *
	 * @param object $icmsObj reference to the object from which we want the user side link
	 * @param bool $onlyUrl wether or not to return a simple URL or a full <a> link
	 * @return string user side link to the object
	 */
	public function getItemLink(&$icmsObj, $onlyUrl=false) {
		/**
		 * @todo URL Rewrite feature is not finished yet...
		 */
		//$seoMode = smart_getModuleModeSEO($this->handler->_moduleName);
		//$seoModuleName = smart_getModuleNameForSEO($this->handler->_moduleName);
		$seoMode = false;
		$seoModuleName = $this->handler->_moduleName;

		/**
		 * $seoIncludeId feature is not finished yet, so let's put it always to true
		 */
		//$seoIncludeId = smart_getModuleIncludeIdSEO($this->handler->_moduleName);
		$seoIncludeId = true;

		/*if ($seoMode == 'rewrite') {
			$ret = ICMS_URL . '/' . $seoModuleName . '.' . $this->handler->_itemname . ($seoIncludeId ? '.'	. $icmsObj->getVar($this->handler->keyName) : ''). '/' . $icmsObj->getVar('short_url') . '.html';
			} else if ($seoMode == 'pathinfo') {
			$ret = SMARTOBJECT_URL . 'seo.php/' . $seoModuleName . '.' . $this->handler->_itemname . ($seoIncludeId ? '.'	. $icmsObj->getVar($this->handler->keyName) : ''). '/' . $icmsObj->getVar('short_url') . '.html';
			} else {
			*/	$ret = $this->handler->_moduleUrl . $this->handler->_page . "?" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		//}

		if (!$onlyUrl) {
			$ret = "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
		}
		return $ret;
	}

	/**
	 * This method returns a view link of the Object
	 *
	 * @param icms_ipf_Object $icmsObj
	 * @param boolean $onlyUrl
	 * @param boolean $withimage
	 * @param boolean $userSide
	 * @return string
	 */
	public function getViewItemLink($icmsObj, $onlyUrl=false, $withimage=true, $userSide=false) {
		if ($this->handler->_moduleName != 'system') {
			$admin_side = $userSide ? '' : 'admin/';
			$ret = $this->handler->_moduleUrl . $admin_side . $this->handler->_page . "?" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		} else {
			$admin_side = '';
			$ret = $this->handler->_moduleUrl . $admin_side . 'admin.php?fct='
				. $this->handler->_itemname . "&amp;op=view&amp;"
				. $this->handler->keyName . "="
				. $icmsObj->getVar($this->handler->keyName);
		}
		if ($onlyUrl) {
			return $ret;
		} elseif ($withimage) {
			return "<a href='" . $ret . "'>
				<img src='" . ICMS_IMAGES_SET_URL . "/actions/viewmag.png' style='vertical-align: middle;' alt='"
				. _PREVIEW . "'  title='" . _PREVIEW . "'/></a>";
		}

		return "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 *
	 * @param	object	$icmsObj
	 * @param	bool	$onlyUrl
	 * @param	bool	$withimage
	 */
	public function getEditLanguageLink($icmsObj, $onlyUrl=false, $withimage=true) {
		$ret = $this->handler->_moduleUrl . "admin/"
			. $this->handler->_page
			. "?op=mod&amp;" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName)
			. "&amp;language=" . $icmsObj->getVar('language');
		if ($onlyUrl) {
			return $ret;
		} elseif ($withimage) {
			return "<a href='" . $ret . "'>
				<img src='" . ICMS_IMAGES_SET_URL . "/actions/wizard.png' style='vertical-align: middle;' alt='"
				. _CO_ICMS_LANGUAGE_MODIFY . "'  title='" . _CO_ICMS_LANGUAGE_MODIFY . "'/></a>";
		}

		return "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 *
	 * @param	obj		$icmsObj
	 * @param	bool	$onlyUrl
	 * @param	bool	$withimage
	 * @param	bool	$userSide
	 */
	public function getEditItemLink($icmsObj, $onlyUrl=false, $withimage=true, $userSide=false) {
		if ($this->handler->_moduleName != 'system') {
			$admin_side = $userSide ? '' : 'admin/';
			$ret = $this->handler->_moduleUrl . $admin_side . $this->handler->_page
				. "?op=mod&amp;" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		} else {
			/**
			 * @todo: to be implemented...
			 */
			//$admin_side = $userSide ? '' : 'admin/';
			$admin_side = '';
			$ret = $this->handler->_moduleUrl . $admin_side
				. 'admin.php?fct=' . $this->handler->_itemname
				. "&amp;op=mod&amp;" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		}
		if ($onlyUrl) {
			return $ret;
		} elseif ($withimage) {
			return "<a href='" . $ret . "'>
				<img src='" . ICMS_IMAGES_SET_URL . "/actions/edit.png' style='vertical-align: middle;' alt='"
				. _CO_ICMS_MODIFY . "'  title='" . _CO_ICMS_MODIFY . "'/></a>";
		}

		return "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 *
	 * @param	obj		$icmsObj
	 * @param	bool	$onlyUrl
	 * @param	bool	$withimage
	 * @param	bool	$userSide
	 */
	public function getDeleteItemLink($icmsObj, $onlyUrl=false, $withimage=true, $userSide=false) {
		if ($this->handler->_moduleName != 'system') {
			$admin_side = $userSide ? '' : 'admin/';
			$ret = $this->handler->_moduleUrl . $admin_side . $this->handler->_page
				. "?op=del&amp;" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		} else {
			/**
			 * @todo: to be implemented...
			 */
			//$admin_side = $userSide ? '' : 'admin/';
			$admin_side = '';
			$ret = $this->handler->_moduleUrl . $admin_side
				. 'admin.php?fct=' . $this->handler->_itemname
				. "&amp;op=del&amp;" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		}
		if ($onlyUrl) {
			return $ret;
		} elseif ($withimage) {
			return "<a href='" . $ret . "'>
				<img src='" . ICMS_IMAGES_SET_URL . "/actions/editdelete.png' style='vertical-align: middle;' alt='"
				. _CO_ICMS_DELETE . "'  title='" . _CO_ICMS_DELETE . "'/></a>";
		}

		return "<a href='" . $ret . "'>" . $icmsObj->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 *
	 * @param	obj		$icmsObj
	 * @todo	Needs to be completed
	 */
	public function getPrintAndMailLink($icmsObj) {
		global $icmsConfig, $impresscms;

		$ret = '';
		/*		$printlink = $this->handler->_moduleUrl . "print.php?" . $this->handler->keyName . "=" . $icmsObj->getVar($this->handler->keyName);
		 $js = "javascript:openWithSelfMain('" . $printlink . "', 'smartpopup', 700, 519);";
		 $printlink = '<a href="' . $js . '"><img  src="' . ICMS_IMAGES_SET_URL . '/actions/fileprint.png" alt="" style="vertical-align: middle;"/></a>';

		 $icmsModule = icms_getModuleInfo($icmsObj->handler->_moduleName);
		 $link = $impresscms->urls['full']();
		 $mid = $icmsModule->getVar('mid');
		 $friendlink = "<a href=\"javascript:openWithSelfMain('".SMARTOBJECT_URL."sendlink.php?link=" . $link . "&amp;mid=" . $mid . "', ',',',',',','sendmessage', 674, 500);\"><img src=\"".SMARTOBJECT_IMAGES_ACTIONS_URL . "mail_send.png\"  alt=\"" . _CO_ICMS_EMAIL . "\" title=\"" . _CO_ICMS_EMAIL . "\" style=\"vertical-align: middle;\"/></a>";

		 $ret = '<span id="smartobject_print_button">' . $printlink . "&nbsp;</span>" . '<span id="smartobject_mail_button">' . $friendlink . '</span>';
		 */
		return $ret;
	}

	/**
	 * Creates a string from the object's module name and item name
	 */
	public function getModuleItemString() {
		$ret = $this->handler->_moduleName . '_' . $this->handler->_itemname;
		return $ret;
	}
}

