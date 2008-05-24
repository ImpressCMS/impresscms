<?php 
/**
 * TinyMCE adapter for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id: tinymce.php 1522 2008-04-10 19:54:49Z wtravel $
 * @package		xoopseditor
 */

class TinyMCE
{
	var $rootpath;
	var $config = array();
	var $setting = array();

	// PHP 5 Constructor
	function __construct($config)
 	{
		$this->setConfig($config);
		$this->rootpath = $this->config["rootpath"] . "/jscripts";
	}
	
	// PHP 4 Contructor
	function TinyMCE( $config )
	{
		$this->__construct( $config ) ;
	}

	function &instance( $config ) 
	{
		static $instance;
		if(!isset($instance)) {
			$instance = new TinyMCE($config);
		}else{
			$instance->setConfig($config);
		}
		
		return $instance;
	}
	
	function getElements($element = null)
	{
		static $elements = array();
		if(!empty($element)) {
			$elements[] = $element;
		}
		
		return $elements;
	}
	
	function setConfig( $config )
	{
		$config["elements"] = implode(",", $this->getElements($config["elements"]));
		foreach($config as $key => $val) {
			$this->config[$key] = $val;
		}
	}
	
	function init()
	{
		$configured = array();
		if(is_readable(XOOPS_ROOT_PATH . $this->rootpath. '/langs/'.$this->config["language"].'.js')) {
			$this->setting["language"] = $this->config["language"];
		}
		
		if( empty($this->config["theme"]) || !is_dir(XOOPS_ROOT_PATH . $this->rootpath."/themes/".$this->config["theme"]) ) {
			$this->setting["theme"] = "advanced"; 
		}else {
			$this->setting["theme"] = $this->config["theme"];
		}
		$this->setting["mode"] = @$this->config["mode"] ? $this->config["mode"] : "exact";
		$configured[] = "language";
		$configured[] = "theme";
		$configured[] = "mode";
		$this->setting["plugins"] = //"layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking" ;
									"table,advimage,advlink,emotions,insertdatetime,preview,media,contextmenu,paste,fullscreen,visualchars,nonbreaking" ;
		$this->setting["plugins"] .= !empty($this->config["plugins"]) ? ",".$this->config["plugins"] : "";
		$configured[] = "plugins";

		$this->setting["content_css"] = @$this->config["content_css"] ? $this->config["content_css"] : 
										"editor_xoops.css";
		if(!is_readable(XOOPS_ROOT_PATH . $this->rootpath. '/themes/'.$this->setting["theme"].'/css/' .$this->setting["content_css"])) {
			unset( $this->setting["content_css"] );
		}
	
		if( $this->setting["theme"] == "advanced" ) {
			$this->setting["theme_advanced_buttons1"] = "bold,italic,underline,strikethrough,sub,sup,separator,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect";
			$this->setting["theme_advanced_buttons2"] = "bullist,numlist,separator,outdent,indent,separator,undo,redo,removeformat,separator,link,unlink,anchor,image,media,separator,charmap,nonbreaking,hr,emotions,separator,pastetext,pasteword,separator,forecolor,backcolor";
			$this->setting["theme_advanced_buttons3"] = "tablecontrols,separator,cleanup,visualaid,visualchars,separator,insertdate,inserttime,separator,preview,fullscreen,help,code";
		}
		
		if( $this->setting["theme"] != "simple" ) {
			if(empty($this->config["buttons"])) {
				$this->config["buttons"][] = array(
					"before"	=> "",
					"add"		=> "",
					);
				$this->config["buttons"][] = array(
					"before"	=> "",
					"add"		=> "",
					);
				$this->config["buttons"][] = array(
					"before"	=> "",
					"add"		=> "",
					);
			}
			$i = 0;
			foreach($this->config["buttons"] as $button) {
				$i++;
				if(isset($button["before"])) {
					$this->setting["theme_".$this->setting["theme"]."_buttons{$i}_add_before"] = $button["before"];
				}
				if(isset($button["add"])) {
					$this->setting["theme_".$this->setting["theme"]."_buttons{$i}_add"] = $button["add"];
				}
				if(isset($button[""])) {
					$this->setting["theme_".$this->setting["theme"]."_buttons{$i}"] = $button[""];
				}
			}
			$configured[] = "buttons";
			
			if(isset($this->config["toolbar_location"])) {
				$this->setting["theme_".$this->setting["theme"]."_toolbar_location"] = $this->config["toolbar_location"];
				$configured[] = "toolbar_location";
			}else {
				$this->setting["theme_".$this->setting["theme"]."_toolbar_location"] = "top";
			}
			
			if(isset($this->config["toolbar_align"])) {
				$this->setting["theme_".$this->setting["theme"]."_toolbar_align"] = $this->config["toolbar_align"];
				$configured[] = "toolbar_align";
			}else{
				$this->setting["theme_".$this->setting["theme"]."_toolbar_align"] = "left";
			}
			
			if(isset($this->config["statusbar_location"])) {
				$this->setting["theme_".$this->setting["theme"]."_statusbar_location"] = $this->config["statusbar_location"];
				$configured[] = "statusbar_location";
			}
			
			if(isset($this->config["path_location"])) {
				$this->setting["theme_".$this->setting["theme"]."_path_location"] = $this->config["path_location"];
				$configured[] = "path_location";
			}
			
			if(isset($this->config["resize_horizontal"])) {
				$this->setting["theme_".$this->setting["theme"]."_resize_horizontal"] = $this->config["resize_horizontal"];
				$configured[] = "resize_horizontal";
			}
			
			if(isset($this->config["resizing"])) {
				$this->setting["theme_".$this->setting["theme"]."_resizing"] = $this->config["resizing"];
				$configured[] = "resizing";
			}
			
			if(!empty($this->config["fonts"])) {
				$this->setting["theme_".$this->setting["theme"]."_fonts"] = $this->config["fonts"];
				$configured[] = "fonts";
			}
		}
		
		foreach($this->config as $key => $val) {
			if(isset($this->setting[$key]) || in_array($key, $configured)) {
				continue;
			}
			$this->setting[$key] = $val;
		}
		
		if(!is_dir(XOOPS_ROOT_PATH . $this->rootpath."/themes/".$this->setting["theme"]. '/docs/'.$this->config["language"].'/')) {
			$this->setting["docs_language"] = "en";
		}
		
		unset($this->config, $configured);
		
		return true;
	}
	
	function render()
	{
		static $rendered;
		if($rendered) return null;
		
		$rendered = true;
		
		$this->init();
		
		if( !empty($this->setting["callback"]) ) {
			$callback = $this->setting["callback"];
			unset($this->setting["callback"]);
		}else{
			$callback = "";
		}
		
		$ret = '<script language="javascript" type="text/javascript" src="' . XOOPS_URL . $this->rootpath . '/tiny_mce.js"></script>';
		$ret .= '
				<script language="javascript" type="text/javascript">
					tinyMCE.init({
				';
		foreach($this->setting as $key => $val) {
			$ret .= $key . ' : ';
			if($val === true || $val === false) {
				$ret .= $val.',';
			}else{
				$ret .= '"'. $val . '",';
			}
		}
		$ret .= '
					tinymceload : "1"});
				'.$callback.'
				</script>
		';
		return $ret ;
	}
}

?>