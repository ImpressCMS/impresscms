<?php
/**
 * Extended dhtmltextarea editor for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id: dhtmlext.php,v 1.2 2007/10/20 20:33:16 marcan Exp $
 * @package		xoopseditor
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

require_once XOOPS_ROOT_PATH."/class/xoopsform/formdhtmltextarea.php";
//include_once XOOPS_ROOT_PATH."/Frameworks/textsanitizer/module.textsanitizer.php";

class XoopsFormDhtmlTextAreaExtended extends XoopsFormDhtmlTextArea
{

	var $_url;
	var $_maxlength = 0;

	/**
	 * Constructor
	 *
     * @param	string  $caption    Caption
     * @param	string  $name       "name" attribute
     * @param	string  $value      Initial text
     * @param	int     $rows       Number of rows
     * @param	int     $cols       Number of columns
     * @param	string  $hiddentext Hidden Text
	 */
	function XoopsFormDhtmlTextAreaExtended($caption, $name, $value, $rows = 5, $cols = 50, $hiddentext = "xoopsHiddenText")
	{
		$this->XoopsFormDhtmlTextArea($caption, $name, $value, $rows, $cols, $hiddentext);
		$this->_url = XOOPS_URL."/class/xoopseditor/dhtmlext";
	}

	/**
	 * Prepare HTML for output
	 *
     * @return	string  HTML
	 */
	function render()
	{
      	$ret = '<script language="JavaScript" type="text/javascript" src="' . $this->_url . '/xoops.js"></script>';
		$ret .= $this->_codeIcon()."<br />\n";
		$ret .= $this->_fontArray();
		$ret .= "<input type='button' onclick=\"XoopsCheckLength('".$this->getName()."', '".$this->_maxlength."', '"._ALTLENGTH."', '"._ALTLENGTH_MAX."');\" value=' ? ' />";
		$ret .= "<br /><br />\n\n";

		$ret .= "<textarea id='".$this->getName()."' name='".$this->getName()."' onselect=\"xoopsSavePosition('".$this->getName()."');\" onclick=\"xoopsSavePosition('".$this->getName()."');\" onkeyup=\"xoopsSavePosition('".$this->getName()."');\" cols='".$this->getCols()."' rows='".$this->getRows()."'".$this->getExtra().">".$this->getValue()."</textarea><br />\n";
		return $ret;
	}


	function _codeIcon()
	{
		$textarea_id = $this->getName();
		$image_path = $this->_url . "/images";
		$code = "<a name='moresmiley'></a>
			<img src='".XOOPS_URL."/images/url.gif' alt='"._ALTURL."' onclick='xoopsCodeUrl(\"$textarea_id\", \"".htmlspecialchars(_ENTERURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWEBTITLE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"'/>&nbsp;
			<img src='".XOOPS_URL."/images/email.gif' alt='"._ALTEMAIL."' onclick='xoopsCodeEmail(\"$textarea_id\", \"".htmlspecialchars(_ENTEREMAIL, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
			<img src='".XOOPS_URL."/images/imgsrc.gif' alt='"._ALTIMG."' onclick='xoopsCodeImg(\"$textarea_id\", \"".htmlspecialchars(_ENTERIMGURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_IMGPOSRORL, ENT_QUOTES)."\", \"".htmlspecialchars(_ERRORIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
			<img src='".XOOPS_URL."/images/image.gif' alt='"._ALTIMAGE."' onclick='openWithSelfMain(\"".XOOPS_URL."/imagemanager.php?target=".$textarea_id."\",\"imgmanager\",400,430);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
			<img src='".$image_path."/smiley.gif' alt='"._ALTSMILEY."' onclick='openWithSelfMain(\"".XOOPS_URL."/misc.php?action=showpopups&amp;type=smilies&amp;target=".$textarea_id."\",\"smilies\",300,475);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
		if(!defined("XOOPS_ENABLE_EXTENDEDCODE") || XOOPS_ENABLE_EXTENDEDCODE ==1){
			if(defined("EXTCODE_ENABLE_FLASH") && EXTCODE_ENABLE_FLASH == 1){
				$code .= "<img src='".$image_path."/swf.gif' alt='"._ALTFLASH."'  onclick='xoopsCodeFlash(\"$textarea_id\",\"".htmlspecialchars(_ENTERFLASHURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\", ".EXTCODE_ENABLE_DIMENSION_DETECT.");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
			if(defined("EXTCODE_ENABLE_WMP") && EXTCODE_ENABLE_WMP == 1){
				$code .= "<img src='".$image_path."/wmp.gif' alt='"._ALTWMP."'  onclick='xoopsCodeWmp(\"$textarea_id\",\"".htmlspecialchars(_ENTERWMPURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
			if(defined("EXTCODE_ENABLE_MMS") && EXTCODE_ENABLE_MMS == 1){
				$code .= "<img src='".$image_path."/mmssrc.gif' alt='"._ALTMMS."' onclick='xoopsCodeMms(\"$textarea_id\",\"".htmlspecialchars(_ENTERMMSURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
			if(defined("EXTCODE_ENABLE_RTSP") && EXTCODE_ENABLE_RTSP == 1){
				$code .= "<img src='".$image_path."/rtspimg.gif' alt='"._ALTRTSP."' onclick='xoopsCodeRtsp(\"$textarea_id\",\"".htmlspecialchars(_ENTERRTSPURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
			if(defined("EXTCODE_ENABLE_IFRAME") && EXTCODE_ENABLE_IFRAME == 1){
				$code .= "<img src='".$image_path."/iframe.gif' alt='"._ALTIFRAME."' onclick='xoopsCodeIframe(\"$textarea_id\",\"".htmlspecialchars(_ENTERIFRAMEURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
			if(defined("EXTCODE_ENABLE_WIKI") && EXTCODE_ENABLE_WIKI == 1){
				$code .= "<img src='".$image_path."/wiki.gif' alt='"._ALTWIKI."' onclick='xoopsCodeWiki(\"$textarea_id\",\"".htmlspecialchars(_ENTERWIKITERM, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
			}
		}
		$code .="
			<img src='".XOOPS_URL."/images/code.gif' alt='"._ALTCODE."' onclick='xoopsCodeCode(\"$textarea_id\", \"".htmlspecialchars(_ENTERCODE, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
			<img src='".XOOPS_URL."/images/quote.gif' alt='"._ALTQUOTE."' onclick='xoopsCodeQuote(\"$textarea_id\", \"".htmlspecialchars(_ENTERQUOTE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"'/>
		";
		return($code);
	}

	function _fontArray()
	{
		$textarea_id = $this->getName();
		$hiddentext = $this->_hiddenText;
		$fontStr = "<script type=\"text/javascript\" language=\"JavaScript\">
			var _editor_dialog = ''
			+ '<select id=\'{$textarea_id}Size\' onchange=\'_setElementSize(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
			+ '<option value=\'SIZE\'>"._SIZE."</option>'
			";
		foreach($GLOBALS["formtextdhtml_sizes"] as $_val => $_name) {
			$fontStr .= " + '<option value=\'{$_val}\'>{$_name}</option>'";
		};
		$fontStr .= " + '</select>'";

		$fontStr .= "
			+ '<select id=\'{$textarea_id}Font\' onchange=\'_setElementFont(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
			+ '<option value=\'FONT\'>"._FONT."</option>'
			";
		$fontarray = !empty($GLOBALS["formtextdhtml_fonts"]) ? $GLOBALS["formtextdhtml_fonts"] : array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana", "Haettenschweiler");
		foreach($fontarray as $font) {
			$fontStr .= " + '<option value=\'{$font}\'>{$font}</option>'";
		};
		$fontStr .= " + '</select>'";

		$fontStr .= "
			+ '<select id=\'{$textarea_id}Color\' onchange=\'_setElementColor(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
			+ '<option value=\'COLOR\'>"._COLOR."</option>';
			var _color_array = new Array('00', '33', '66', '99', 'CC', 'FF');
			for(var i = 0; i < _color_array.length; i ++) {
				for(var j = 0; j < _color_array.length; j ++) {
					for(var k = 0; k < _color_array.length; k ++) {
						var _color_ele = _color_array[i] + _color_array[j] + _color_array[k];
						_editor_dialog += '<option value=\''+_color_ele+'\' style=\'background-color:#'+_color_ele+';color:#'+_color_ele+';\'>#'+_color_ele+'</option>';
					}
				}
			}
			_editor_dialog += '</select>';";

		$fontStr .= "document.write(_editor_dialog); </script>";

		$styleStr = "<img src='".XOOPS_URL."/images/bold.gif' alt='"._ALTBOLD."' onmouseover='style.cursor=\"hand\"' onclick='_makeBold(\"".$hiddentext."\", \"$textarea_id\");' />&nbsp;";
		$styleStr .= "<img src='".XOOPS_URL."/images/italic.gif' alt='"._ALTITALIC."' onmouseover='style.cursor=\"hand\"' onclick='_makeItalic(\"".$hiddentext."\", \"$textarea_id\");' />&nbsp;";
		$styleStr .= "<img src='".XOOPS_URL."/images/underline.gif' alt='"._ALTUNDERLINE."' onmouseover='style.cursor=\"hand\"' onclick='_makeUnderline(\"".$hiddentext."\", \"$textarea_id\");'/>&nbsp;";
		$styleStr .= "<img src='".XOOPS_URL."/images/linethrough.gif' alt='"._ALTLINETHROUGH."' onmouseover='style.cursor=\"hand\"' onclick='_makeLineThrough(\"".$hiddentext."\", \"$textarea_id\");' /></a>&nbsp;";
		$styleStr .= "<input type='text' id='".$textarea_id."Addtext' size='20' />&nbsp;<input type='button' onclick='xoopsCodeText(\"$textarea_id\", \"".$hiddentext."\", \"".htmlspecialchars(_ENTERTEXTBOX, ENT_QUOTES)."\")' value='"._ADD."' />";

		$fontStr = $fontStr."<br />\n".$styleStr."&nbsp;&nbsp;<span id='".$hiddentext."'>"._EXAMPLE."</span>\n";
		return($fontStr);
	}
}


/**
 * Pseudo class
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2005 XOOPS.org
 */

class FormDhtmlExt extends XoopsFormDhtmlTextAreaExtended
{
	/**
	 * Constructor
	 *
     * @param	array   $configs  Editor Options
     * @param	binary 	$checkCompatible  true - return false on failure
	 */
	function FormDhtmlExt($configs, $checkCompatible = false)
	{
		$this->_rows = 5;
		$this->_cols = 50;
		$this->_hiddentext = "xoopsHiddenText";

		if(is_array($configs)) {
			$vars = array_keys(get_object_vars($this));
			foreach($configs as $key => $val){
				if(in_array("_".$key, $vars)) {
					$this->{"_".$key} = $val;
				}
			}
		}

		$this->XoopsFormDhtmlTextAreaExtended("", @$this->_name, @$this->_value, $this->_rows, $this->_cols, $this->_hiddentext);
	}
}

?>