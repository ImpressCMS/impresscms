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
 * Xoops header ...
 */
include_once '../../mainfile.php';

$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}



if (!is_object($icmsUser)) {
  redirect_header("index.php",3,_NOPERM);
  exit();
}

/**
 * Factories of tribes  
 */
$configs_factory      = icms_getmodulehandler('configs', $modname, 'profile' );

/**
 * Verify Token
 */
if (!($GLOBALS['xoopsSecurity']->check())){
	redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
}

$criteria = new Criteria('config_uid',$icmsUser->getVar("uid"));
if ($configs_factory->getCount($criteria)>0){
  $configs = $configs_factory->getObjects($criteria);
  $config = $configs[0];
  $config->unsetNew();
} else {
  $config = $configs_factory->create();
}

$config->setVar('config_uid',$icmsUser->getVar("uid"));
if (isset($_POST['pic']))  $config->setVar('pictures',$_POST['pic']);
if (isset($_POST['aud'])) $config->setVar('audio',$_POST['aud']);
if (isset($_POST['vid'])) $config->setVar('videos',$_POST['vid']);
if (isset($_POST['tribes'])) $config->setVar('tribes',$_POST['tribes']);
if (isset($_POST['scraps'])) $config->setVar('scraps',$_POST['scraps']);
if (isset($_POST['friends'])) $config->setVar('friends',$_POST['friends']);
if (isset($_POST['profileContact'])) $config->setVar('profile_contact',$_POST['profileContact']);
if (isset($_POST['gen'])) $config->setVar('profile_general',$_POST['gen']);
if (isset($_POST['stat'])) $config->setVar('profile_stats',$_POST['stat']);
if (!$configs_factory->insert($config)) {

}
redirect_header("configs.php?uid=".$icmsUser->getVar("uid"),3,_MD_PROFILE_CONFIGSSAVE);
?>