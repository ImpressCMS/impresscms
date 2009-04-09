<?php

require_once(ICMS_ROOT_PATH."/kernel/page.php");

class SystemPages extends IcmsPage {
	
	public function __construct( & $handler ){
		parent::__construct( $handler );
		
		$this->setControl('page_status', 'yesno');
		$this->setControl('page_moduleid', array (
			'itemHandler' => 'pages',
			'method' => 'getModulesArray',
			'module' => 'system'
		));
	}
	
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ( 'page_status_custom', 'page_moduleid_custom'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}
	
	private function page_status_custom(){
		if($this->getVar('page_status') == 1)
			$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=pages&op=status&page_id='.$this->getVar('page_id').'" title="'._VISIBLE.'" ><img src="'.ICMS_URL.'/images/crystal/actions/button_ok.png" alt="'._VISIBLE.'"/></a>';
		else
			$rtn = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=pages&op=status&page_id='.$this->getVar('page_id').'" title="'._VISIBLE.'" ><img src="'.ICMS_URL.'/images/crystal/actions/button_cancel.png" alt="'._VISIBLE.'"/></a>';
		return $rtn;
	}
	
	private function page_moduleid_custom(){
		$modules = $this->handler->getModulesArray();
		return $modules[$this->getVar('page_moduleid')];
	}
	
	public function getAdminViewItemLink(){
		$rtn = $this->getVar('page_title');
		return $rtn;
	}

	
	public function getViewItemLink() {
		if (preg_match('/\*/',$this->getVar('page_url','e'))){
			$ret = '';
		}else{
			$url = (substr($this->getVar('page_url','e'),0,7) == 'http://')?$this->getVar('page_url','e'):ICMS_URL.'/'.$this->getVar('page_url','e');
			$ret = '<a href="'.$url.'" alt="'._PREVIEW.'" title="'._PREVIEW.'" targe="_blank"><img src="' . ICMS_URL . '/images/crystal/actions/viewmag.png" /></a>';
		}
		
		return $ret;
	}
}


class SystemPagesHandler extends IcmsPageHandler {
	
	private $modules_name;
	
	public function __construct( & $db ){
		$this->IcmsPersistableObjectHandler($db, 'pages', 'page_id', 'page_title', '' , 'system');
		$this->table = $db->prefix('icmspage');
	}
	
	
	public function getModulesArray($full = false){
    	if( !count($this->modules_name) ){
			$icms_module_handler = xoops_gethandler('module');
			$installed_modules =& $icms_module_handler->getObjects();
			foreach( $installed_modules as $module ){
				$this->modules_name[$module->getVar('mid')]['name'] = $module->getVar('name');
				$this->modules_name[$module->getVar('mid')]['dirname'] = $module->getVar('dirname');
			}	
		}
		$rtn = $this->modules_name;
		if(!$full)
			foreach($this->modules_name as $key => $module)
				$rtn[$key] = $module['name'];
    	return $rtn;
    }
	
	function changeStatus( $page_id ){
		$page = $this->get( $page_id );
        $page->setVar( 'page_status', !$page->getVar('page_status') );
        return $this->insert($page, true);
    }
    
    
}
?>