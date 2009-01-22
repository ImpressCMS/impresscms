<?php
/**
* Form control creating a rich file element for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformrichfileelement.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
  * @todo	This is not functionnal yet.. it needs further integration
  */

 class IcmsFormRichFileElement extends XoopsFormElementTray {
    function IcmsFormRichFileElement($form_caption, $key, $object) {
        $this->XoopsFormElementTray( $form_caption, '&nbsp;' );
        if($object->getVar('url') != '' ){
        	$caption = $object->getVar('caption') != '' ? $object->getVar('caption') : $object->getVar('url');
        	$this->addElement( new XoopsFormLabel( '', _CO_ICMS_CURRENT_FILE."<a href='" . str_replace('{XOOPS_URL}', XOOPS_URL ,$object->getVar('url')) . "' target='_blank' >". $caption."</a><br/><br/>" ) );
        	//$this->addElement( new XoopsFormLabel( '', "<br/><a href = '".SMARTOBJECT_URL."admin/file.php?op=del&fileid=".$object->id()."'>"._CO_ICMS_DELETE_FILE."</a>"));
        }

        include_once ICMS_ROOT_PATH."/class/icmsform/elements/icmsformfileuploadelement.php";
        if($object->isNew()){
        	$this->addElement(new IcmsFormFileUploadElement($object, $key));
			$this->addElement( new XoopsFormLabel( '', '<br/><br/><small>'._CO_ICMS_URL_FILE_DSC.'</small>'));
			$this->addElement( new XoopsFormLabel( '','<br/>'._CO_ICMS_URL_FILE));
	        $this->addElement(new IcmsFormTextElement($object, 'url_'.$key));

    	}
        $this->addElement( new XoopsFormLabel( '', '<br/>'._CO_ICMS_CAPTION));
        $this->addElement(new IcmsFormTextElement($object, 'caption_'.$key));
        $this->addElement( new XoopsFormLabel( '', '<br/>'._CO_ICMS_DESC.'<br/>'));
        $this->addElement(new XoopsFormTextArea('', 'desc_'.$key, $object->getVar('description')));

		if(!$object->isNew()){
			$this->addElement( new XoopsFormLabel( '','<br/>'._CO_ICMS_CHANGE_FILE));
			$this->addElement(new IcmsFormFileUploadElement($object, $key));
			$this->addElement( new XoopsFormLabel( '', '<br/><br/><small>'._CO_ICMS_URL_FILE_DSC.'</small>'));
			$this->addElement( new XoopsFormLabel( '','<br/>'._CO_ICMS_URL_FILE));
	        $this->addElement(new IcmsFormTextElement($object, 'url_'.$key));
	   	}
    }
}

?>