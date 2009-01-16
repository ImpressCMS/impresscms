<?php
/**
*
*
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			Venom <webmaster@exode-fr.com>
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/

require_once XOOPS_ROOT_PATH . "/class/xoopsform/formselect.php";

class MPFormSelectUser extends XoopsFormSelect
{
    function MPFormSelectUser($caption, $name, $start = 0, $limit = 200, $value = null, $include_anon = false, $size = 10, $multiple = true)
    {
       $this->XoopsFormSelect($caption, $name, $value, $size, $multiple);
     //   new xoopsFormSelect ($caption, $name, $value, $size, $multiple);

        $criteria = new CriteriaCompo();
        $criteria->setSort('uname');
        $criteria->setOrder('ASC');
        $criteria->setLimit($limit);
        $criteria->setStart($start);

        $member_handler = &xoops_gethandler('member');
        if ($include_anon) {
            global $xoopsConfig;
            $this->addOption(0, $xoopsConfig['anonymous']);
        }
       $this->addOptionArray($member_handler->getUserList($criteria));
    }

    /*
	function XoopsFormSelectUser($caption, $name, $include_anon=false, $value=null, $size=1, $multiple=false)
	{
	    $this->XoopsFormSelect($caption, $name, $value, $size, $multiple);
		$member_handler =& xoops_gethandler('member');
		if ($include_anon) {
			global $xoopsConfig;
			$this->addOption(0, $xoopsConfig['anonymous']);
		}
		$this->addOptionArray($member_handler->getUserList());
	}
	*/
}

?>