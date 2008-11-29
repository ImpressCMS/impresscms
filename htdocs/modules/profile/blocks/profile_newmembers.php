<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

function b_profile_newmembers_show($options)
{
    $block = array();
    $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
    $limit = (!empty($options[0])) ? $options[0] : 10;
    $criteria->setOrder('DESC');
    $criteria->setSort('user_regdate');
    $criteria->setLimit($limit);
    $member_handler =& xoops_gethandler('member');
    $newmembers = $member_handler->getUsers($criteria);
    $count = count($newmembers);
    for ($i = 0; $i < $count; $i++) {
        if ( $options[1] == 1 ) {
            $block['users'][$i]['avatar'] = $newmembers[$i]->getVar('user_avatar') != 'blank.gif' ? ICMS_UPLOAD_URL.'/'.$newmembers[$i]->getVar('user_avatar') : '';
        } else {
            $block['users'][$i]['avatar'] = '';
        }
        $block['users'][$i]['id'] = $newmembers[$i]->getVar('uid');
        $block['users'][$i]['name'] = $newmembers[$i]->getVar('uname');
        $block['users'][$i]['joindate'] = formatTimestamp($newmembers[$i]->getVar('user_regdate'), 's');
        $block['users'][$i]['directory'] = basename(  dirname(  dirname( __FILE__ ) ) );
    }
    return $block;
}

function b_profile_newmembers_edit($options)
{
    $inputtag = "<input type='text' name='options[]' value='".$options[0]."' />";
    $form = sprintf(_MB_SPROFILE_DISPLAY,$inputtag);
    $form .= "<br />"._MB_SPROFILE_DISPLAYA."&nbsp;<input type='radio' id='options[]' name='options[]' value='1'";
    if ( $options[1] == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."<input type='radio' id='options[]' name='options[]' value='0'";
    if ( $options[1] == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO."";
    return $form;
}


?>