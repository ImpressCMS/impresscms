<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * Xoops header
 */
include_once("../../mainfile.php");
include_once("../../header.php");
include_once("class/profile_controler.php");

$controler = new ProfileControlerTribes($xoopsDB,$xoopsUser);

/**
 * Receiving info from get parameters  
 */ 
$tribe_id = $_GET['com_itemid'];
$criteria= new criteria('tribe_id',$tribe_id);
$tribes = $controler->tribes_factory->getObjects($criteria);
$tribe = $tribes[0];

$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {

    $com_replytitle = _MD_PROFILE_TRIBES.": ".$tribe->getVar('tribe_title');
    include ICMS_ROOT_PATH.'/include/comment_new.php';
}
?>
