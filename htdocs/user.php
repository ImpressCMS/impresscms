<?php
/**
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by stranger <stranger@impresscms.ir>
* @version		$Id$
*/
/**
 * Login page for users, will redirect to userinfo.php if the user is logged in
 * @package kernel 
 * @subpackage users 
 */ 
$xoopsOption['pagetype'] = 'user';
include 'mainfile.php';

$op = 'main';

if ( isset($_POST['op']) ) {
    $op = trim($_POST['op']);
} elseif ( isset($_GET['op']) ) {
    $op = trim($_GET['op']);
}

if ($op == 'main') {
    if ( !$xoopsUser ) {
        $xoopsOption['template_main'] = 'system_userform.html';
        include 'header.php';
        $xoopsTpl->assign('lang_login', _LOGIN);
        $xoopsTpl->assign('lang_username', _USERNAME);
        if (isset($_COOKIE[$xoopsConfig['usercookie']])) {
            $xoopsTpl->assign('usercookie', $_COOKIE[$xoopsConfig['usercookie']]);
        }
        if (isset($_GET['xoops_redirect'])) {
	        $redirect = htmlspecialchars(trim($_GET['xoops_redirect']), ENT_QUOTES);
	        $isExternal = false;
	        if ($pos = strpos( $redirect, '://' )) {
	            $xoopsLocation = substr( ICMS_URL, strpos( ICMS_URL, '://' ) + 3 );
	             if ( substr($redirect, $pos + 3, strlen($xoopsLocation)) != $xoopsLocation)  {
					$redirect = ICMS_URL;
		         }elseif(substr($redirect, $pos + 3, strlen($xoopsLocation)+1) == $xoopsLocation.'.') {
		            $redirect = ICMS_URL;
		         }
	        }
        	$xoopsTpl->assign('redirect_page', $redirect);
        }
        $xoopsTpl->assign('lang_password', _PASSWORD);
        $xoopsTpl->assign('lang_notregister', _US_NOTREGISTERED);
        $xoopsTpl->assign('lang_lostpassword', _US_LOSTPASSWORD);
        $xoopsTpl->assign('lang_noproblem', _US_NOPROBLEM);
        $xoopsTpl->assign('lang_youremail', _US_YOUREMAIL);
        $xoopsTpl->assign('lang_sendpassword', _US_SENDPASSWORD);
        $xoopsTpl->assign('lang_rememberme', _US_REMEMBERME);
        $xoopsTpl->assign('mailpasswd_token', $GLOBALS['xoopsSecurity']->createToken());
        $config_handler =& xoops_gethandler('config');
		$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

		if ($xoopsConfigUser['allow_register'] == 1) {
			$xoopsTpl->assign('allow_registration', $xoopsConfigUser['allow_register']);
		}

		if ($xoopsConfigUser['remember_me'] == 1) {
			$xoopsTpl->assign('rememberme', $xoopsConfigUser['remember_me']);
		}

        $xoopsTpl->assign('xoops_pagetitle', _LOGIN);
        include 'footer.php';
    } elseif ( !empty($_GET['xoops_redirect']) ) {
        $redirect = htmlspecialchars(trim($_GET['xoops_redirect']));
        $isExternal = false;
        if ($pos = strpos( $redirect, '://' )) {
            $xoopsLocation = substr( ICMS_URL, strpos( ICMS_URL, '://' ) + 3 );
             if ( substr($redirect, $pos + 3, strlen($xoopsLocation)) != $xoopsLocation)  {
	              $redirect = ICMS_URL;
	         }elseif(substr($redirect, $pos + 3, strlen($xoopsLocation)+1) == $xoopsLocation.'.') {
	              $redirect = ICMS_URL;
	         }
        }
        header('Location: ' . $redirect);
		exit();
    } else {
        header('Location: '.ICMS_URL.'/userinfo.php?uid='.intval($xoopsUser->getVar('uid')));
		exit();
    }
    exit();
}

if ($op == 'login') {
    include_once ICMS_ROOT_PATH.'/include/checklogin.php';
    exit();
}

if ($op == 'logout') {
    $message = '';
    // Regenrate a new session id and destroy old session
    session_regenerate_id(true);
    $_SESSION = array();
    if ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
        setcookie($xoopsConfig['session_name'], '', time()- 3600, '/',  '', 0);
    }
    // autologin hack GIJ (clear autologin cookies)
    $xoops_cookie_path = defined('XOOPS_COOKIE_PATH') ? XOOPS_COOKIE_PATH : preg_replace( '?http://[^/]+(/.*)$?' , "$1" , ICMS_URL ) ;
    if( $xoops_cookie_path == ICMS_URL ) $xoops_cookie_path = '/' ;
    setcookie('autologin_uname', '', time() - 3600, $xoops_cookie_path, '', 0);
    setcookie('autologin_pass', '', time() - 3600, $xoops_cookie_path, '', 0);
    // end of autologin hack GIJ
    // clear entry from online users table
    if (is_object($xoopsUser)) {
        $online_handler =& xoops_gethandler('online');
        $online_handler->destroy($xoopsUser->getVar('uid'));
    }
    $message = _US_LOGGEDOUT.'<br />'._US_THANKYOUFORVISIT;
    redirect_header('index.php', 1, $message);
    exit();
}

if ($op == 'actv') {
    $id = intval($_GET['id']);
    $actkey = trim($_GET['actkey']);
    if (empty($id)) {
        redirect_header('index.php',1,'');
        exit();
    }
    $member_handler =& xoops_gethandler('member');
    $thisuser =& $member_handler->getUser($id);
    if (!is_object($thisuser)) {
        exit();
    }
    if ($thisuser->getVar('actkey') != $actkey) {
        redirect_header('index.php',5,_US_ACTKEYNOT);
    } else {
        if ($thisuser->getVar('level') > 0 ) {
            redirect_header( 'user.php', 5, _US_ACONTACT, false );
        } else {
            if (false != $member_handler->activateUser($thisuser)) {
                $config_handler =& xoops_gethandler('config');
                $xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
                if ($xoopsConfigUser['activation_type'] == 2) {
                    $myts =& MyTextSanitizer::getInstance();
                    $xoopsMailer =& getMailer();
                    $xoopsMailer->useMail();
                    $xoopsMailer->setTemplate('activated.tpl');
                    $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
                    $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
                    $xoopsMailer->assign('SITEURL', ICMS_URL."/");
                    $xoopsMailer->setToUsers($thisuser);
                    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                    $xoopsMailer->setFromName($xoopsConfig['sitename']);
                    $xoopsMailer->setSubject(sprintf(_US_YOURACCOUNT,$xoopsConfig['sitename']));
                    include 'header.php';
                    if ( !$xoopsMailer->send() ) {
                        printf(_US_ACTVMAILNG, $thisuser->getVar('uname'));
                    } else {
                        printf(_US_ACTVMAILOK, $thisuser->getVar('uname'));
                    }
                    include 'footer.php';
                } else {
                	$thisuser->sendWelcomeMessage();
                    redirect_header( 'user.php', 5, _US_ACTLOGIN, false );
                }
            } else {
                redirect_header('index.php',5,'Activation failed!');
            }
        }
    }
    exit();
}

if ($op == 'delete') {
    $config_handler =& xoops_gethandler('config');
    $xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
    if (!$xoopsUser || $xoopsConfigUser['self_delete'] != 1) {
        redirect_header('index.php',5,_US_NOPERMISS);
        exit();
    } else {
        $groups = $xoopsUser->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $groups)){
            // users in the webmasters group may not be deleted
            redirect_header('user.php', 5, _US_ADMINNO);
            exit();
        }
        $ok = !isset($_POST['ok']) ? 0 : intval($_POST['ok']);
        if ($ok != 1) {
            include 'header.php';
            xoops_confirm(array('op' => 'delete', 'ok' => 1), 'user.php', _US_SURETODEL.'<br/>'._US_REMOVEINFO);
            include 'footer.php';
        } else {
            $del_uid = intval($xoopsUser->getVar("uid"));
            $member_handler =& xoops_gethandler('member');
            if (false != $member_handler->deleteUser($xoopsUser)) {
                $online_handler =& xoops_gethandler('online');
                $online_handler->destroy($del_uid);
                xoops_notification_deletebyuser($del_uid);
                redirect_header('index.php', 5, _US_BEENDELED);
            }
            redirect_header('index.php',5,_US_NOPERMISS);
        }
        exit();
    }
}
?>
