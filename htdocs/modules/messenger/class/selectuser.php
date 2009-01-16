<?php
// $Id: formselectuser.php 184 2006-01-22 22:34:51Z skalpa $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}
/**
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

// Limitation: Only work with javascript enabled

/**
 * Parent
 */
require_once XOOPS_ROOT_PATH . "/class/xoopsform/formselect.php";
require_once XOOPS_ROOT_PATH . "/class/xoopsform/formelementtray.php";

// RMV-NOTIFY

/**
 * A select field with a choice of available users
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class MPSelectUser extends XoopsFormElementTray
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
	  
	function MPSelectUser($caption, $name, $value, $include_anon = false, $size = 1, $multiple = false)
	{
	
	    $this->XoopsFormElementTray($caption, "<br /><br />", $name);
        //$select_form = new XoopsFormSelect("", $name, $value, $size, $multiple);
		$select_form = new XoopsFormSelect("", $name, $value, $size, $multiple, $name.'[]');
		$select_form -> setExtra("style=\"width:10em;\" ");
        $member_handler = &xoops_gethandler('member');
        $criteria = new CriteriaCompo();
        if (!is_array($value)) {
            $value = array($value);
        }
	    
		//if(is_array($value) && count($value)>0) {
		  //  $id_in = "(".implode(",", $value).")";
			//$criteria->add(new Criteria("uid", $id_in, "IN"));
	       // $criteria->setSort('name');
	       // $criteria->setOrder('ASC');
        //	$users = $member_handler->getUserList($criteria);
        //	$select_form->addOptionArray($member_handler->getUserList($criteria));
	    //}

	       if(is_array($value) && count($value)>0) {
		   foreach ($value as $choix) {	
	       $criteria = new CriteriaCompo(new Criteria('uid', $choix));
           $criteria->setSort('name');
	       $criteria->setOrder('ASC');
		   $select_form->addOptionArray($member_handler->getUserList($criteria));
		   }
		
		   
        

	    }

	    $action_tray = new XoopsFormElementTray("", " | ");
	    
	    $action_tray->addElement(new XoopsFormLabel('', "<img src=\"images/popup.gif\">&nbsp;<a href='###' onclick='return openWithSelfMain(\"".XOOPS_URL."/modules/messenger/userselect.php?action=0&amp;target=".$name."&amp;multiple=".$multiple."\", \"userselect\", 600, 400, null);' >"._MP_SEARCH."</a>"));
		$action_tray->addElement(new XoopsFormLabel('', "<a href='###' onclick='return openWithSelfMain(\"".XOOPS_URL."/modules/messenger/userselect.php?action=2&amp;target=".$name."&amp;multiple=".$multiple."\", \"userselect\", 600, 400, null);' >"._MP_CONTACT."</a>"));
	    $action_tray->addElement(new XoopsFormLabel('', "<a href='###' onclick='var sel = xoopsGetElementById(\"".$name."[]\");for (var i = sel.options.length-1; i >= 0; i--) {if (sel.options[i].selected) {sel.options[i] = null;}}'>"._MP_MDEL."</a>".
			"<script type=\"text/javascript\">
		    function addusers(opts){
			    var num = opts.substring(0, opts.indexOf(\":\"));
			    opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
        		var sel = xoopsGetElementById(\"".$name."[]\");
			    var arr = new Array(num);
			    for(var n=0; n<num; n++){
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
					if(added==false){
						appendSelectOption2(\"".$name."[]\",txt, val);
	        		}
			    }
				return true;
		    }

			</script>"
			));

	    $this->addElement($select_form);
	    $this->addElement($action_tray);
    }
}
?>