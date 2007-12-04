<?php
/**
 * user select with page navigation
 *
 * limit: Only work with javascript enabled
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: formselectuser.php 1083 2007-10-16 16:42:51Z phppp $
 * @package		kernal
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/class/xoopsform/formelementtray.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";

class XoopsFormSelectUser extends XoopsFormElementTray
{
	/**
	 * Constructor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	$value	    	Pre-selected value (or array of them).
	 *									For an item with massive members, such as "Registered Users", "$value" should be used to store selected temporary users only instead of all members of that item
	 * @param	bool	$include_anon	Include user "anonymous"?
	 * @param	int		$size	        Number or rows. "1" makes a drop-down-list.
     * @param	bool    $multiple       Allow multiple selections?
	 */
	function XoopsFormSelectUser($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false)
	{
    	$limit = 200;
        $select_element = new XoopsFormSelect("", $name, $value, $size, $multiple);
        if ($include_anon) {
            $select_element->addOption(0, $GLOBALS["xoopsConfig"]['anonymous']);
        }
		$member_handler =& xoops_gethandler('member');
		$user_count = $member_handler->getUserCount();
        $value = is_array($value) ? $value : ( empty($value) ? array() : array($value) );
	    if ($user_count > $limit && count($value) > 0) {
        	$criteria = new CriteriaCompo(new Criteria("uid", "(".implode(",", $value).")", "IN"));
	    } else {
        	$criteria = new CriteriaCompo();
            $criteria->setLimit($limit);
	    }
        $criteria->setSort('uname');
        $criteria->setOrder('ASC');
		$users = $member_handler->getUserList($criteria);
    	$select_element->addOptionArray($users);
    	if ($user_count <= $limit) {
    	    $this->XoopsFormElementTray($caption, "", $name);
    	    $this->addElement($select_element);
    	    return;
    	}
    	
		
		if (!@include_once XOOPS_ROOT_PATH."/language/".$GLOBALS["xoopsConfig"]["language"]."/findusers.php") {
			include_once XOOPS_ROOT_PATH."/language/english/findusers.php";
		}
		
		$js_addusers =
			"<script type=\"text/javascript\">
		    function addusers(opts){
			    var num = opts.substring(0, opts.indexOf(\":\"));
			    opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
        		var sel = xoopsGetElementById(\"". $name . ($multiple ? "[]" : "") . "\");
			    var arr = new Array(num);
			    for (var n=0; n < num; n++) {
			    	var nm = opts.substring(0, opts.indexOf(\":\"));
			    	opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
			    	var val = opts.substring(0, opts.indexOf(\":\"));
			    	opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
			    	var txt = opts.substring(0, nm - val.length);
			    	opts = opts.substring(nm - val.length, opts.length);
					var added = false;
					for (var k = 0; k < sel.options.length; k++) {
						if(sel.options[k].value == val){
							added = true;
							break;
						}
					}
					if (added == false) {
						sel.options[k] = new Option(txt, val);
						sel.options[k].selected = true;
	        		}
			    }
				return true;
		    }
			</script>";
		
        $token = $GLOBALS['xoopsSecurity']->createToken();
	    $action_tray = new XoopsFormElementTray("", " | ");
	    $action_tray->addElement(new XoopsFormLabel('', "<a href='#' onclick='var sel = xoopsGetElementById(\"" . $name . ( $multiple ? "[]" : "" ) . "\");for (var i = sel.options.length-1; i >= 0; i--) {if (!sel.options[i].selected) {sel.options[i] = null;}}; return false;'>"._MA_USER_REMOVE."</a>"));
	    $action_tray->addElement(new XoopsFormLabel('', "<a href='#' onclick='openWithSelfMain(\"".XOOPS_URL."/include/findusers.php?target={$name}&amp;multiple={$multiple}&amp;token={$token}\", \"userselect\", 800, 600, null); return false;' >"._MA_USER_MORE."</a>".$js_addusers));

	    $this->XoopsFormElementTray($caption, "<br /><br />", $name);
	    $this->addElement($select_element);
	    $this->addElement($action_tray);
    }
}
?>