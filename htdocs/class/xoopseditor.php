<?php
/**
 * Editor framework for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		  1.00
 * @version		$Id: xoopseditor.php,v 1.3 2007/10/20 20:33:16 marcan Exp $
 * @package		xoopseditor
 */
class XoopsEditorHandler
{
	var $root_path = "";
	var $nohtml = false;
	var $allowed_editors = array();

  function XoopsEditorHandler()
  {
    include_once dirname(__FILE__)."/xoopseditor.inc.php";
    $this->root_path = xoopseditor_get_rootpath();
  }

	/**
	 * Access the only instance of this class
   *
   * @return	object
   *
   * @static
   * @staticvar   object
	 */
	function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new XoopsEditorHandler();
		}
		return $instance;
	}

	/**
   * @param	string	$name		Editor name which is actually the folder name
   * @param	array 	$options	editor options: $key => $val
   * @param	string	$OnFailure  a pre-validated editor that will be used if the required editor is failed to create
   * @param	bool	$noHtml		dohtml disabled
	 */
  function &get($name = "", $options = null, $noHtml = false, $OnFailure = "")
  {
    if($editor = $this->_loadEditor($name, $options)) {
      return $editor;
    }
    $list = array_keys($this->getList($noHtml));
    /*
    if(!empty($name) && in_array($name, $list)){
    $editor = $this->_loadEditor($name, $options);
    }
    */
    //if(!is_object($editor)){
    if(empty($OnFailure) || !in_array($OnFailure, $list)){
      $OnFailure = $list[0];
    }
    $editor = $this->_loadEditor($OnFailure, $options);
    //}
    return $editor;
  }

  function &getList($noHtml = false)
  {
    if(@ include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.ini.php") {
      load_functions("cache");
      $list = mod_loadCacheFile("list", "xoopseditor");
    }

		if(empty($list)) {

			$list = array();
			$order = array();
			require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
			$_list = XoopsLists::getDirListAsArray($this->root_path.'/');

			foreach($_list as $item){
				if(@include $this->root_path.'/'.$item.'/editor_registry.php'){
					if(empty($config['order'])) continue;
					$order[] = $config['order'];
					$list[$item] = array("title" => $config["title"], "nohtml" => @$config["nohtml"]);
				}
			}
			array_multisort($order, $list);
			if(function_exists("mod_createCacheFile")) {
				mod_createCacheFile($list, "list", "xoopseditor");
			}
		}

		$editors = array_keys($list);
		if(!empty($this->allowed_editors)) {
			$editors = array_intersect($editors, $this->allowed_editors);
		}

		$_list = array();
		foreach($editors as $name){
			if(!empty($noHtml) && empty($list[$name]['nohtml'])) continue;
			$_list[$name] = $list[$name]['title'];
		}
		return $_list;
  }

  function render(&$editor)
  {
    return $editor->render();
  }

  function setConfig(&$editor, $options)
  {
    if(method_exists($editor, 'setConfig')) {
        $editor->setConfig($options);
      }else{
      foreach($options as $key => $val){
        $editor->$key = $val;
      }
    }
  }

  function &_loadEditor($name, $options = null)
  {
    $editor = null;

    if(empty($name)) {
      return $editor;
    }
    $editor_path = $this->root_path."/".$name;

    if(!include $editor_path."/editor_registry.php") {
      return $editor;
    }
    if(empty($config['order'])) {
      return null;
    }
    include_once $config['file'];
    $editor =& new $config['class']($options);
    return $editor;
  }
}
?>