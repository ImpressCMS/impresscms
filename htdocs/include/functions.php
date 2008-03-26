<?php
// $Id: functions.php 1099 2007-10-19 01:08:14Z dugris $
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

// ################## Various functions from here ################

function xoops_header($closehead=true)
{
    global $xoopsConfig, $xoopsTheme, $xoopsConfigMetaFooter;
    $myts =& MyTextSanitizer::getInstance();

    if (!headers_sent()) {
        header('Content-Type:text/html; charset='._CHARSET);
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
        header("Pragma: no-cache");
    }
    echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'._LANGCODE.'" lang="'._LANGCODE.'">
    <head>
    <meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
    <meta http-equiv="content-language" content="'._LANGCODE.'" />
    <meta name="robots" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_robots']).'" />
    <meta name="keywords" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_keywords']).'" />
    <meta name="description" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_desc']).'" />
    <meta name="rating" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_rating']).'" />
    <meta name="author" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_author']).'" />
    <meta name="copyright" content="'.htmlspecialchars($xoopsConfigMetaFooter['meta_copyright']).'" />
    <meta name="generator" content="ImpressCMS" />
    <title>'.htmlspecialchars($xoopsConfig['sitename']).'</title>
    <script type="text/javascript" src="'.XOOPS_URL.'/include/xoops.js"></script>
    ';
    $themecss = getcss($xoopsConfig['theme_set']);
    echo '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/xoops.css" />';
    if ($themecss) {
        echo '<link rel="stylesheet" type="text/css" media="all" href="'.$themecss.'" />';
        //echo '<style type="text/css" media="all"><!-- @import url('.$themecss.'); --></style>';
    }
    if ($closehead) {
        echo '</head><body>';
    }
}

function xoops_footer()
{
    echo '</body></html>';
    ob_end_flush();
}

function xoops_error($msg, $title='')
{
    echo '
    <div class="errorMsg">';
    if ($title != '') {
        echo '<h4>'.$title.'</h4>';
    }
    if (is_array($msg)) {
        foreach ($msg as $m) {
            echo $m.'<br />';
        }
    } else {
        echo $msg;
    }
    echo '</div>';
}

function xoops_warning($msg, $title='')
{
    echo '
    <div class="warningMsg">';
    if ($title != '') {
        echo '<h4>'.$title.'</h4>';
    }
    if (is_array($msg)) {
        foreach ($msg as $m) {
            echo $m.'<br />';
        }
    } else {
        echo $msg;
    }
    echo '</div>';
}

function xoops_result($msg, $title='')
{
    echo '
    <div class="resultMsg">';
    if ($title != '') {
        echo '<h4>'.$title.'</h4>';
    }
    if (is_array($msg)) {
        foreach ($msg as $m) {
            echo $m.'<br />';
        }
    } else {
        echo $msg;
    }
    echo '</div>';
}

function xoops_confirm($hiddens, $action, $msg, $submit='', $addtoken = true)
{
    $submit = ($submit != '') ? trim($submit) : _SUBMIT;
    echo '
    <div class="confirmMsg">
      <h4>'.$msg.'</h4>
      <form method="post" action="'.$action.'">
    ';
    foreach ($hiddens as $name => $value) {
        if (is_array($value)) {
            foreach ($value as $caption => $newvalue) {
                echo '<input type="radio" name="'.$name.'" value="'.htmlspecialchars($newvalue).'" /> '.$caption;
            }
            echo '<br />';
        } else {
            echo '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars($value).'" />';
        }
    }
    if ($addtoken != false) {
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    }
    echo '
        <input type="submit" name="confirm_submit" value="'.$submit.'" /> <input type="button" name="confirm_back" value="'._CANCEL.'" onclick="javascript:history.go(-1);" />
      </form>
    </div>
    ';
}

/**
* Deprecated, use {@link XoopsSecurity} class instead
**/
function xoops_refcheck($docheck=1)
{
    return $GLOBALS['xoopsSecurity']->checkReferer($docheck);
}

function xoops_getUserTimestamp($time, $timeoffset="")
{
    global $xoopsConfig, $xoopsUser;
    if ($timeoffset == '') {
        if ($xoopsUser) {
            $timeoffset = $xoopsUser->getVar("timezone_offset");
        } else {
            $timeoffset = $xoopsConfig['default_TZ'];
        }
    }
    $usertimestamp = intval($time) + (floatval($timeoffset) - $xoopsConfig['server_TZ'])*3600;
    return $usertimestamp;
}



/*
 * Function to display formatted times in user timezone
 */
function formatTimestamp($time, $format="l", $timeoffset="")
{
    global $xoopsConfig, $xoopsUser;
    $usertimestamp = xoops_getUserTimestamp($time, $timeoffset);
    switch (strtolower($format)) {
    case 's':
        $datestring = _SHORTDATESTRING;
        break;
    case 'm':
        $datestring = _MEDIUMDATESTRING;
        break;
    case 'mysql':
        $datestring = "Y-m-d H:i:s";
        break;
    case 'rss':
        $datestring = "r";
        break;
    case 'l':
        $datestring = _DATESTRING;
        break;
    default:
        if ($format != '') {
            $datestring = $format;
        } else {
            $datestring = _DATESTRING;
        }
        break;
    }
    return ucfirst(date($datestring, $usertimestamp));
}

/*
 * Function to calculate server timestamp from user entered time (timestamp)
 */
function userTimeToServerTime($timestamp, $userTZ=null)
{
    global $xoopsConfig;
    if (!isset($userTZ)) {
        $userTZ = $xoopsConfig['default_TZ'];
    }
    $timestamp = $timestamp - (($userTZ - $xoopsConfig['server_TZ']) * 3600);
    return $timestamp;
}

function xoops_makepass() {
    $makepass = '';
    $syllables = array("er","in","tia","wol","fe","pre","vet","jo","nes","al","len","son","cha","ir","ler","bo","ok","tio","nar","sim","ple","bla","ten","toe","cho","co","lat","spe","ak","er","po","co","lor","pen","cil","li","ght","wh","at","the","he","ck","is","mam","bo","no","fi","ve","any","way","pol","iti","cs","ra","dio","sou","rce","sea","rch","pa","per","com","bo","sp","eak","st","fi","rst","gr","oup","boy","ea","gle","tr","ail","bi","ble","brb","pri","dee","kay","en","be","se");
    srand((double)microtime()*1000000);
    for ($count = 1; $count <= 4; $count++) {
        if (rand()%10 == 1) {
            $makepass .= sprintf("%0.0f",(rand()%50)+1);
        } else {
            $makepass .= sprintf("%s",$syllables[rand()%62]);
        }
    }
    return $makepass;
}

/*
 * Functions to display dhtml loading image box
 */
function OpenWaitBox()
{
    echo "<div id='waitDiv' style='position:absolute;left:40%;top:50%;visibility:hidden;text-align: center;'>
    <table cellpadding='6' border='2' class='bg2'>
      <tr>
        <td align='center'><b><big>" ._FETCHING."</big></b><br /><img src='".XOOPS_URL."/images/await.gif' alt='' /><br />" ._PLEASEWAIT."</td>
      </tr>
    </table>
    </div>
    <script type='text/javascript'>
    <!--//
    var DHTML = (document.getElementById || document.all || document.layers);
    function ap_getObj(name) {
        if (document.getElementById) {
            return document.getElementById(name).style;
        } else if (document.all) {
            return document.all[name].style;
        } else if (document.layers) {
            return document.layers[name];
        }
    }
    function ap_showWaitMessage(div,flag)  {
        if (!DHTML) {
            return;
        }
        var x = ap_getObj(div);
        x.visibility = (flag) ? 'visible' : 'hidden';
        if (!document.getElementById) {
            if (document.layers) {
                x.left=280/2;
            }
        }
        return true;
    }
    ap_showWaitMessage('waitDiv', 1);
    //-->
    </script>";
}

function CloseWaitBox()
{
    echo "<script type='text/javascript'>
    <!--//
    ap_showWaitMessage('waitDiv', 0);
    //-->
    </script>
    ";
}

function checkEmail($email,$antispam = false)
{
    if (!$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$email)){
        return false;
    }
    if ($antispam) {
        $email = str_replace("@", " at ", $email);
        $email = str_replace(".", " dot ", $email);
    }
	return $email;
}

function formatURL($url)
{
    $url = trim($url);
    if ($url != '') {
        if ((!preg_match("/^http[s]*:\/\//i", $url)) && (!preg_match("/^ftp*:\/\//i", $url)) && (!preg_match("/^ed2k*:\/\//i", $url)) ) {
            $url = 'http://'.$url;
        }
    }
    return $url;
}

/*
 * Function to display banners in all pages
 */
function showbanner()
{
    echo xoops_getbanner();
}

/*
 * Function to get banner html tags for use in templates
 */
function xoops_getbanner()
{
    global $xoopsConfig;
    $db =& Database::getInstance();
    $bresult = $db->query("SELECT COUNT(*) FROM ".$db->prefix("banner"));
    list ($numrows) = $db->fetchRow($bresult);
    if ( $numrows > 1 ) {
        $numrows = $numrows-1;
        mt_srand((double)microtime()*1000000);
        $bannum = mt_rand(0, $numrows);
    } else {
        $bannum = 0;
    }
    if ( $numrows > 0 ) {
        $bresult = $db->query("SELECT * FROM ".$db->prefix("banner"), 1, $bannum);
        list ($bid, $cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date, $htmlbanner, $htmlcode) = $db->fetchRow($bresult);
        if ($xoopsConfig['my_ip'] == xoops_getenv('REMOTE_ADDR')) {
            // EMPTY
        } else {
            $db->queryF(sprintf("UPDATE %s SET impmade = impmade+1 WHERE bid = '%u'", $db->prefix("banner"), intval($bid)));
        }
        /* Check if this impression is the last one and print the banner */
        if ( $imptotal == $impmade ) {
            $newid = $db->genId($db->prefix("bannerfinish")."_bid_seq");
            $sql = sprintf("INSERT INTO %s (bid, cid, impressions, clicks, datestart, dateend) VALUES ('%u', '%u', '%u', '%u', '%u', '%u')", $db->prefix("bannerfinish"), intval($newid), intval($cid), intval($impmade), intval($clicks), intval($date), time());
            $db->queryF($sql);
            $db->queryF(sprintf("DELETE FROM %s WHERE bid = '%u'", $db->prefix("banner"), intval($bid)));
        }
        if ($htmlbanner){
            $bannerobject = $htmlcode;
        }else{
            $bannerobject = '<div><a href="'.XOOPS_URL.'/banners.php?op=click&amp;bid='.$bid.'" target="_blank">';
            if (stristr($imageurl, '.swf')) {
                $bannerobject = $bannerobject
                    .'<object type="application/x-shockwave-flash" data="'.$imageurl.'" width="468" height="60">'
                    .'<param name="movie" value="'.$imageurl.'"></param>'
                    .'<param name="quality" value="high"></param>'
                    .'</object>';
            } else {
                $bannerobject = $bannerobject.'<img src="'.$imageurl.'" alt="" />';
            }

            $bannerobject = $bannerobject.'</a></div>';
        }
        return $bannerobject;
    }
}

/*
* Function to redirect a user to certain pages
*/
function redirect_header($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
{
    global $xoopsConfig, $xoopsLogger, $xoopsUserIsAdmin;
    if ( preg_match( "/[\\0-\\31]|about:|script:/i", $url) ) {
        if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url) ) {
            $url = XOOPS_URL;
        }
    }
    if ( !$allowExternalLink && $pos = strpos( $url, '://' ) ) {
        $xoopsLocation = substr( XOOPS_URL, strpos( XOOPS_URL, '://' ) + 3 );
        if ( substr($url, $pos + 3, strlen($xoopsLocation)) != $xoopsLocation)  {
			$url = XOOPS_URL;
	     }elseif(substr($url, $pos + 3, strlen($xoopsLocation)+1) == $xoopsLocation.'.') {
	        $url = XOOPS_URL;
	     }
       /* if (strcasecmp(substr($url, $pos + 3, strlen($xoopsLocation)), $xoopsLocation)) {
            $url = XOOPS_URL;
        }*/
    }
    $theme = $xoopsConfig['theme_set'];
    // if the user selected a theme in the theme block, let's use this theme
	if (isset($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $xoopsConfig['theme_set_allowed'])) {
		$theme = $_SESSION['xoopsUserTheme'];
	} 

    require_once XOOPS_ROOT_PATH . '/class/template.php';
    require_once XOOPS_ROOT_PATH . '/class/theme.php';

	$xoopsThemeFactory =& new xos_opal_ThemeFactory();
	$xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
	$xoopsThemeFactory->defaultTheme = $theme;
	$xoTheme =& $xoopsThemeFactory->createInstance(array("plugins" => array()));
	$xoopsTpl =& $xoTheme->template;

	$xoopsTpl->assign( array(
    	'xoops_theme' => $theme,
    	'xoops_imageurl' => XOOPS_THEME_URL.'/'.$theme.'/',
    	'xoops_themecss'=> xoops_getcss($theme),
    	'xoops_requesturi' => htmlspecialchars( $_SERVER['REQUEST_URI'], ENT_QUOTES),
    	'xoops_sitename' => htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES),
    	'xoops_slogan' => htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES),
    	'xoops_dirname' => @$xoopsModule ? $xoopsModule->getVar( 'dirname' ) : 'system',
    	'xoops_banner' => $xoopsConfig['banners'] ? xoops_getbanner() : '&nbsp;',
    	'xoops_pagetitle' => isset($xoopsModule) && is_object($xoopsModule) ? $xoopsModule->getVar('name') : htmlspecialchars( $xoopsConfig['slogan'], ENT_QUOTES ),
	) );

    if ($xoopsConfig['debug_mode'] == 2 && $xoopsUserIsAdmin) {
        $xoopsTpl->assign('time', 300);
        $xoopsTpl->assign('xoops_logdump', $xoopsLogger->dump());
    } else {
        $xoopsTpl->assign('time', intval($time));
    }
    if (!empty($_SERVER['REQUEST_URI']) && $addredirect && strstr($url, 'user.php')) {
        if (!strstr($url, '?')) {
            $url .= '?xoops_redirect='.urlencode($_SERVER['REQUEST_URI']);
        } else {
            $url .= '&amp;xoops_redirect='.urlencode($_SERVER['REQUEST_URI']);
        }
    }
    if (defined('SID') && SID && (! isset($_COOKIE[session_name()]) || ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '' && !isset($_COOKIE[$xoopsConfig['session_name']])))) {
        if (!strstr($url, '?')) {
            $url .= '?' . SID;
        } else {
            $url .= '&amp;'.SID;
        }
    }
    $url = preg_replace("/&amp;/i", '&', htmlspecialchars($url, ENT_QUOTES));
    $xoopsTpl->assign('url', $url);
    $message = trim($message) != '' ? $message : _TAKINGBACK;
    $xoopsTpl->assign('message', $message);
    $xoopsTpl->assign('lang_ifnotreload', sprintf(_IFNOTRELOAD, $url));
    $xoopsTpl->display('db:system_redirect.html');
    exit();
}

function xoops_getenv($key)
{
    $ret = '';
    if ( array_key_exists( $key, $_SERVER) && isset($_SERVER[$key]) ) {
        $ret = $_SERVER[$key];
        return $ret;
    }
    if ( array_key_exists( $key, $_ENV) && isset($_ENV[$key]) ) {
        $ret = $_ENV[$key];
        return $ret;
    }
    return $ret;
}

/*
 * This function is deprecated. Do not use!
 */
function getTheme()
{
    return $GLOBALS['xoopsConfig']['theme_set'];
}

/*
 * Function to get css file for a certain theme
 * This function will be deprecated.
 */
function getcss($theme = '')
{
    return xoops_getcss($theme);
}

/*
 * Function to get css file for a certain themeset
 */
function xoops_getcss($theme = '')
{
    if ($theme == '') {
        $theme = $GLOBALS['xoopsConfig']['theme_set'];
    }
    $uagent = xoops_getenv('HTTP_USER_AGENT');
    if (stristr($uagent, 'mac')) {
        $str_css = 'styleMAC.css';
    } elseif (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/i", $uagent)) {
        $str_css = 'style.css';
    } else {
        $str_css = 'styleNN.css';
    }
    if (is_dir(XOOPS_THEME_PATH.'/'.$theme)) {
        if (file_exists(XOOPS_THEME_PATH.'/'.$theme.'/'.$str_css)) {
            return XOOPS_THEME_URL.'/'.$theme.'/'.$str_css;
        } elseif (file_exists(XOOPS_THEME_PATH.'/'.$theme.'/style.css')) {
            return XOOPS_THEME_URL.'/'.$theme.'/style.css';
        }
    }
    if (is_dir(XOOPS_THEME_PATH.'/'.$theme . '/css')) {
        if (file_exists(XOOPS_THEME_PATH.'/'.$theme.'/css/'.$str_css)) {
            return XOOPS_THEME_URL.'/'.$theme.'/css/'.$str_css;
        } elseif (file_exists(XOOPS_THEME_PATH.'/'.$theme.'/css/style.css')) {
            return XOOPS_THEME_URL.'/'.$theme.'/css/style.css';
        }
    }
    return '';
}

function &getMailer()
{
    global $xoopsConfig;
    $inst = false;
    include_once XOOPS_ROOT_PATH."/class/xoopsmailer.php";
    if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/xoopsmailerlocal.php") ) {
        include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/xoopsmailerlocal.php";
        if ( class_exists("XoopsMailerLocal") ) {
            $inst =& new XoopsMailerLocal();
        }
    }
    if ( !$inst ) {
    	$inst =& new XoopsMailer();
    }
    return $inst;
}

function &xoops_gethandler($name, $optional = false )
{
    static $handlers;
    $name = strtolower(trim($name));
    if (!isset($handlers[$name])) {
        if ( file_exists( $hnd_file = XOOPS_ROOT_PATH.'/kernel/'.$name.'.php' ) ) {
            require_once $hnd_file;
        }
        $class = 'Xoops'.ucfirst($name).'Handler';
        if (class_exists($class)) {
            $handlers[$name] =& new $class($GLOBALS['xoopsDB']);
        }
    }
    if (!isset($handlers[$name]) && !$optional ) {
        trigger_error('Class <b>'.$class.'</b> does not exist<br />Handler Name: '.$name, E_USER_ERROR);
    }
    if ( isset($handlers[$name]) ) {
    	return $handlers[$name];
    }
    $inst = false;
    return $inst;
}

function &xoops_getmodulehandler($name = null, $module_dir = null, $optional = false)
{
    static $handlers;
    // if $module_dir is not specified
    if (!isset($module_dir)) {
        //if a module is loaded
        if (isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule'])) {
            $module_dir = $GLOBALS['xoopsModule']->getVar('dirname');
        } else {
            trigger_error('No Module is loaded', E_USER_ERROR);
        }
    } else {
        $module_dir = trim($module_dir);
    }
    $name = (!isset($name)) ? $module_dir : trim($name);
    if (!isset($handlers[$module_dir][$name])) {
        if ( file_exists( $hnd_file = XOOPS_ROOT_PATH . "/modules/{$module_dir}/class/{$name}.php" ) ) {
            include_once $hnd_file;
        }
        $class = ucfirst(strtolower($module_dir)).ucfirst($name).'Handler';
        if (class_exists($class)) {
            $handlers[$module_dir][$name] =& new $class($GLOBALS['xoopsDB']);
        }
    }
    if (!isset($handlers[$module_dir][$name]) && !$optional) {
        trigger_error('Handler does not exist<br />Module: '.$module_dir.'<br />Name: '.$name, E_USER_ERROR);
    }
    if ( isset($handlers[$module_dir][$name]) ) {
    	return $handlers[$module_dir][$name];
    }
    $inst = false;
    return $inst;

}

function xoops_getrank($rank_id =0, $posts = 0)
{
    $db =& Database::getInstance();
    $myts =& MyTextSanitizer::getInstance();
    $rank_id = intval($rank_id);
    $posts = intval($posts);
    if ($rank_id != 0) {
        $sql = "SELECT rank_title AS title, rank_image AS image FROM ".$db->prefix('ranks')." WHERE rank_id = '".$rank_id."'";
    } else {
        $sql = "SELECT rank_title AS title, rank_image AS image FROM ".$db->prefix('ranks')." WHERE rank_min <= '".$posts."' AND rank_max >= '".$posts."' AND rank_special = '0'";
    }
    $rank = $db->fetchArray($db->query($sql));
    $rank['title'] = $myts->makeTboxData4Show($rank['title']);
    $rank['id'] = $rank_id;
    return $rank;
}


/**
 * Function maintained only for compatibility
 *
 * @todo Search all places that this function is called
 *       and rename it to icms_substr.
 *       After this function can be removed.
 *
 */
function xoops_substr($str, $start, $length, $trimmarker = '...')
{
	return icms_substr($str, $start, $length, $trimmarker);
}

/**
* Returns the portion of string specified by the start and length parameters. 
* If $trimmarker is supplied, it is appended to the return string. 
* This function works fine with multi-byte characters if mb_* functions exist on the server.
*
* @param    string    $str
* @param    int       $start
* @param    int       $length
* @param    string    $trimmarker
*
* @return   string
*/
function icms_substr($str, $start, $length, $trimmarker = '...')
{
	$config_handler =& xoops_gethandler('config');
	$im_multilanguageConfig =& $config_handler->getConfigsByCat(IM_CONF_MULILANGUAGE);
    
	if ($im_multilanguageConfig['ml_enable']) {
		$tags = explode(',',$im_multilanguageConfig['ml_tags']);
		$strs = array();
		$hasML = false;
		foreach ($tags as $tag){
			if (preg_match("/\[".$tag."](.*)\[\/".$tag."\]/sU",$str,$matches)){
				if (count($matches) > 0){
					$hasML = true;
					$strs[] = $matches[1];
				}
			}
		}
	}else{
		$hasML = false;
	}
	
	if (!$hasML){
        $strs = array($str);
	}
	
	for ($i = 0; $i <= count($strs)-1; $i++){
		if ( !XOOPS_USE_MULTIBYTES ) {
			$strs[$i] = ( strlen($strs[$i]) - $start <= $length ) ? substr( $strs[$i], $start, $length ) : substr( $strs[$i], $start, $length - strlen($trimmarker) ) . $trimmarker;
		}

		if (function_exists('mb_internal_encoding') && @mb_internal_encoding(_CHARSET)) {
			$str2 = mb_strcut( $strs[$i] , $start , $length - strlen( $trimmarker ) );
			$strs[$i] = $str2 . ( mb_strlen($strs[$i])!=mb_strlen($str2) ? $trimmarker : '' );
		}

		// phppp patch
		$DEP_CHAR=127;
		$pos_st=0;
		$action = false;
		for ( $pos_i = 0; $pos_i < strlen($strs[$i]); $pos_i++ ) {
			if ( ord( substr( $strs[$i], $pos_i, 1) ) > 127 ) {
				$pos_i++;
			}
			if ($pos_i<=$start) {
				$pos_st=$pos_i;
			}
			if ($pos_i>=$pos_st+$length) {
				$action = true;
				break;
			}
		}
		$strs[$i] = ($action) ? substr( $strs[$i], $pos_st, $pos_i - $pos_st - strlen($trimmarker) ) . $trimmarker : $strs[$i];

		$strs[$i] = ($hasML)?'['.$tags[$i].']'.$strs[$i].'[/'.$tags[$i].']':$strs[$i];
	}
	$str = implode('',$strs);

	return $str;
}

// RMV-NOTIFY
// ################ Notification Helper Functions ##################

// We want to be able to delete by module, by user, or by item.
// How do we specify this??

function xoops_notification_deletebymodule ($module_id)
{
    $notification_handler =& xoops_gethandler('notification');
    return $notification_handler->unsubscribeByModule ($module_id);
}

function xoops_notification_deletebyuser ($user_id)
{
    $notification_handler =& xoops_gethandler('notification');
    return $notification_handler->unsubscribeByUser ($user_id);
}

function xoops_notification_deletebyitem ($module_id, $category, $item_id)
{
    $notification_handler =& xoops_gethandler('notification');
    return $notification_handler->unsubscribeByItem ($module_id, $category, $item_id);
}

// ################### Comment helper functions ####################

function xoops_comment_count($module_id, $item_id = null)
{
    $comment_handler =& xoops_gethandler('comment');
    $criteria = new CriteriaCompo(new Criteria('com_modid', intval($module_id)));
    if (isset($item_id)) {
        $criteria->add(new Criteria('com_itemid', intval($item_id)));
    }
    return $comment_handler->getCount($criteria);
}

function xoops_comment_delete($module_id, $item_id)
{
    if (intval($module_id) > 0 && intval($item_id) > 0) {
        $comment_handler =& xoops_gethandler('comment');
        $comments =& $comment_handler->getByItemId($module_id, $item_id);
        if (is_array($comments)) {
            $count = count($comments);
            $deleted_num = array();
            for ($i = 0; $i < $count; $i++) {
                if (false != $comment_handler->delete($comments[$i])) {
                    // store poster ID and deleted post number into array for later use
                    $poster_id = $comments[$i]->getVar('com_uid');
                    if ($poster_id != 0) {
                        $deleted_num[$poster_id] = !isset($deleted_num[$poster_id]) ? 1 : ($deleted_num[$poster_id] + 1);
                    }
                }
            }
            $member_handler =& xoops_gethandler('member');
            foreach ($deleted_num as $user_id => $post_num) {
                // update user posts
                $com_poster = $member_handler->getUser($user_id);
                if (is_object($com_poster)) {
                    $member_handler->updateUserByField($com_poster, 'posts', $com_poster->getVar('posts') - $post_num);
                }
            }
            return true;
        }
    }
    return false;
}

// ################ Group Permission Helper Functions ##################

function xoops_groupperm_deletebymoditem($module_id, $perm_name, $item_id = null)
{
    // do not allow system permissions to be deleted
    if (intval($module_id) <= 1) {
        return false;
    }
    $gperm_handler =& xoops_gethandler('groupperm');
    return $gperm_handler->deleteByModule($module_id, $perm_name, $item_id);
}

function xoops_utf8_encode(&$text)
{
    if (XOOPS_USE_MULTIBYTES == 1) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        return $text;
    }
    return utf8_encode($text);
}

function xoops_convert_encoding(&$text)
{
    return xoops_utf8_encode($text);
}

function xoops_getLinkedUnameFromId($userid)
{
    $userid = intval($userid);
    if ($userid > 0) {
        $member_handler =& xoops_gethandler('member');
        $user =& $member_handler->getUser($userid);
        if (is_object($user)) {
            $linkeduser = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$userid.'">'. $user->getVar('uname').'</a>';
            return $linkeduser;
        }
    }
    return $GLOBALS['xoopsConfig']['anonymous'];
}

function xoops_trim($text)
{
    if (function_exists('xoops_language_trim')) {
        return xoops_language_trim($text);
    }
    return trim($text);
}


/**
* Copy a file, or a folder and its contents
*
* @author	Aidan Lister <aidan@php.net>
* @param	string	$source    The source
* @param	string  $dest      The destination
* @return   bool    Returns true on success, false on failure
*/
function icms_copyr($source, $dest) {
	// Simple copy for a file
	if (is_file($source)) {
		return copy($source, $dest);
	}
	// Make destination directory
	if (!is_dir($dest)) {
		mkdir($dest);
	}
	// Loop through the folder
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}
		// Deep copy directories
		if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
			copyr("$source/$entry", "$dest/$entry");
		} else {
			copy("$source/$entry", "$dest/$entry");
		}
	}
	// Clean up
	$dir->close();
	return true;
}
/**
* Create a folder
*
* @author	Newbb2 developpement team
* @param	string	$target    folder being created
* @return   bool    Returns true on success, false on failure
*/
function icms_mkdir($target) {
	// http://www.php.net/manual/en/function.mkdir.php
	// saint at corenova.com
	// bart at cdasites dot com
	if (is_dir($target) || empty ($target)) {
		return true; // best case check first
	}
	if (file_exists($target) && !is_dir($target)) {
		return false;
	}
	if (icms_mkdir(substr($target, 0, strrpos($target, '/')))) {
		if (!file_exists($target)) {
			$res = mkdir($target, 0777); // crawl back up & create dir tree
			icms_chmod($target);
			return $res;
		}
	}
	$res = is_dir($target);
	return $res;
}
/**
* Change the permission of a file or folder
*
* @author	Newbb2 developpement team
* @param	string	$target  target file or folder
* @param	int		$mode    permission
* @return   bool    Returns true on success, false on failure
*/
function icms_chmod($target, $mode = 0777) {
	return @ chmod($target, $mode);
}

 /**
 * Get the dirname of the current module 
 *
 * @return mixed dirname of the current module or false if no module loaded
 */
function icms_getCurrentModuleName() {
	global $xoopsModule;
	if (is_object($xoopsModule)) {
		return $xoopsModule->getVar('dirname');
	} else {
		return false;
	}
}
/**
 * php 4 compat for array_combine
 */
if (!function_exists('array_combine')) {
	function array_combine($arr1,$arr2) {
	   $out = array();
	   foreach($arr1 as $key1 => $value1)    {
	    $out[$value1] = $arr2[$key1];
	   }
	   return $out;
	}
}
?>