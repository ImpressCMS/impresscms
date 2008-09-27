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
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/

$xoopsOption['pagetype'] = "search";

include 'mainfile.php';
$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

if ($xoopsConfigSearch['enable_search'] != 1) {
    header('Location: '.ICMS_URL.'/index.php');
    exit();
}
$action = 'search';
if(!empty($_GET['action'])) {$action = trim(StopXSS($_GET['action']));}
elseif(!empty($_POST['action'])) {$action = trim(StopXSS($_POST['action']));}
$query = '';
if(!empty($_GET['query'])) {$query = StopXSS($_GET['query']);}
elseif(!empty($_POST['query'])) {$query = StopXSS($_POST['query']);}
$andor = 'AND';
if(!empty($_GET['andor'])) {$andor = StopXSS($_GET['andor']);}
elseif(!empty($_POST['andor'])) {$andor = StopXSS($_POST['andor']);}
$mid = $uid = $start = 0;
if(!empty($_GET['mid'])) {$mid = intval($_GET['mid']);}
elseif(!empty($_POST['mid'])) {$mid = intval($_POST['mid']);}
if(!empty($_GET['uid'])) {$uid = intval($_GET['uid']);}
elseif(!empty($_POST['uid'])) {$uid = intval($_POST['uid']);}
if(!empty($_GET['start'])) {$start = intval($_GET['start']);}
elseif(!empty($_POST['start'])) {$start = intval($_POST['start']);}


$queries = array();

if ($action == "results") {
    if ($query == "") {
         redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
} elseif ($action == "showall") {
    if ($query == "" || empty($mid)) {
        redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
} elseif ($action == "showallbyuser") {
    if (empty($mid) || empty($uid)) {
        redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
}

$groups = is_object($xoopsUser) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler = & xoops_gethandler( 'groupperm' );
$available_modules = $gperm_handler->getItemIds('module_read', $groups);

if ($action == 'search') {
    include ICMS_ROOT_PATH.'/header.php';
    include 'include/searchform.php';
    $search_form->display();
    $xoopsTpl->assign('xoops_pagetitle', _SEARCH);
    include ICMS_ROOT_PATH.'/footer.php';
    exit();
}

if ( $andor != "OR" && $andor != "exact" && $andor != "AND" ) {
    $andor = "AND";
}

$myts =& MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
    if ( $andor != "exact" ) {
        $ignored_queries = array(); // holds kewords that are shorter than allowed minmum length  
        
        preg_match_all('/(?:").*?(?:")|(?:\').*?(?:\')/', $query,$compostas);
        $res = $simpl = array();
        foreach ($compostas[0] as $comp){
        	$res[] = substr($comp,1,strlen($comp)-3);
        }
        $compostas = $res;
        
        $simples = preg_replace('/(?:").*?(?:")|(?:\').*?(?:\')/', '', $query);
        $simples = preg_split('/[\s,]+/', $simples);
        if (count($simples) > 0){
        	foreach ($simples as $k=>$v){
        		if ($v != "\\"){
        			$simpl[] = $v;
        		}
        	}
        	$simples = $simpl;
        }        

        if (count($compostas) > 0 && count($simples) > 0){
          $temp_queries = array_merge($simples,$compostas);
        }elseif (count($compostas) <= 0 && count($simples) > 0){
        	$temp_queries = $simples;
        }elseif (count($compostas) > 0 && count($simples) <= 0){
        	$temp_queries = $compostas;
        }else{
        	$temp_queries = array();
        }
        
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, icms_conv_nr2local($xoopsConfigSearch['keyword_min'])));
            exit();
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, icms_conv_nr2local($xoopsConfigSearch['keyword_min'])));
            exit();
        }
        $queries = array($myts->addSlashes($query));
    }
}
switch ($action) {
    case "results":
    $module_handler =& xoops_gethandler('module');
    $criteria = new CriteriaCompo(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    $criteria->add(new Criteria('mid', "(".implode(',', $available_modules).")", 'IN'));
    $modules =& $module_handler->getObjects($criteria, true);
    $mids = isset($_REQUEST['mids']) ? $_REQUEST['mids'] : array();
    if (empty($mids) || !is_array($mids)) {
        unset($mids);
        $mids = array_keys($modules);
    }
    include ICMS_ROOT_PATH."/header.php";
    echo "<h3>"._SR_SEARCHRESULTS."</h3>\n";
    echo _SR_KEYWORDS.':';
    if ($andor != 'exact') {
        foreach ($queries as $q) {
            echo ' <b>'.htmlspecialchars(stripslashes($q)).'</b>';
        }
        if (!empty($ignored_queries)) {
            echo '<br />';
            printf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']);
            foreach ($ignored_queries as $q) {
                echo ' <b>'.htmlspecialchars(stripslashes($q)).'</b>';
            }
        }
    } else {
        echo ' "<b>'.htmlspecialchars(stripslashes($queries[0])).'</b>"';
    }
    echo '<br />';
    foreach ($mids as $mid) {
        $mid = intval($mid);
        if ( in_array($mid, $available_modules) ) {
            $module =& $modules[$mid];
            $results =& $module->search($queries, $andor, intval($xoopsConfigSearch['search_per_page']), 0);
           $count = count($results);
            if (!is_array($results) || $count == 0 ) {
                 if( $xoopsConfigSearch['search_no_res_mod']){
               		 echo "<h4>".$myts->makeTboxData4Show($module->getVar('name'))."</h4>";
                     echo "<p>"._SR_NOMATCH."</p>";
           		}
            } else {
               	echo "<h4>".$myts->makeTboxData4Show($module->getVar('name'))."</h4>";
                for ($i = 0; $i < $count; $i++) {
                    if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
                        echo "<img style='vertical-align:middle;' src='modules/".$module->getVar('dirname')."/".$results[$i]['image']."' alt='".$myts->makeTboxData4Show($module->getVar('name'))."' />&nbsp;";
                    } else {
                        echo "<img style='vertical-align:middle;' src='images/icons/posticon2.gif' alt='".$myts->makeTboxData4Show($module->getVar('name'))."' width='26' height='26' />&nbsp;";
                    }
                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                        $results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
                    }
                    echo "<b><a href='".$results[$i]['link']."'>".$myts->makeTboxData4Show($results[$i]['title'])."</a></b><br />\n";
	                if( $xoopsConfigSearch['search_user_date']){
	                    echo "<small>";
	                    $results[$i]['uid'] = @intval($results[$i]['uid']);
	                    if ( !empty($results[$i]['uid']) ) {
	                        $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
	                        echo "&nbsp;&nbsp;<a href='".ICMS_URL."/userinfo.php?uid=".$results[$i]['uid']."'>".$uname."</a>\n";
	                    }
	                    echo !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
	                    echo "</small>";
	                }
	                 echo "<br />\n";
                }
                if ( $count >= intval($xoopsConfigSearch['search_per_page']) ) {
                    $search_url = ICMS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
                    $search_url .= "&mid=$mid&action=showall&andor=$andor";
                    echo '<br /><a href="'.htmlspecialchars($search_url).'">'._SR_SHOWALLR.'</a></p>';
                }
            }
        }
        unset($results);
        unset($module);
    }
    include "include/searchform.php";
    $search_form->display();
    $xoopsTpl->assign('xoops_pagetitle', _SR_SEARCHRESULTS);
    break;
    case "showall":
    case 'showallbyuser':
    include ICMS_ROOT_PATH."/header.php";
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->get($mid);
    $results =& $module->search($queries, $andor, 20, $start, $uid);
    $count = count($results);
    if (is_array($results) && $count > 0) {
        $next_results =& $module->search($queries, $andor, 1, $start + 20, $uid);
        $next_count = count($next_results);
        $has_next = false;
        if (is_array($next_results) && $next_count == 1) {
            $has_next = true;
        }
        $xoopsTpl->assign('xoops_pagetitle', _SR_SEARCHRESULTS);
        echo "<h4>"._SR_SEARCHRESULTS."</h4>\n";
        if ($action == 'showall') {
            echo _SR_KEYWORDS.':';
            if ($andor != 'exact') {
                foreach ($queries as $q) {
                    echo ' <b>'.htmlspecialchars(stripslashes($q)).'</b>';
                }
            } else {
                echo ' "<b>'.htmlspecialchars(stripslashes($queries[0])).'</b>"';
            }
            echo '<br />';
        }
        //    printf(_SR_FOUND,$count);
        //    echo "<br />";
        printf(_SR_SHOWING, $start+1, $start + $count);
        echo "<h5>".$myts->makeTboxData4Show($module->getVar('name'))."</h5>";
        for ($i = 0; $i < $count; $i++) {
            if (isset($results[$i]['image']) && $results[$i]['image'] != '') {
                echo "<img style='vertical-align:middle;' src='modules/".$module->getVar('dirname')."/".$results[$i]['image']."' alt='".$myts->makeTboxData4Show($module->getVar('name'))."' />&nbsp;";
            } else {
                echo "<img style='vertical-align:middle;' src='images/icons/posticon2.gif' alt='".$myts->makeTboxData4Show($module->name())."' width='26' height='26' />&nbsp;";
            }
            if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                $results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
            }
            echo "<b><a href='".$results[$i]['link']."'>".$myts->makeTboxData4Show($results[$i]['title'])."</a></b><br />\n";
	        if( $xoopsConfigSearch['search_user_date']){
	            echo "<small>";
	            $results[$i]['uid'] = @intval($results[$i]['uid']);
	            if ( !empty($results[$i]['uid']) ) {
	                $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
	                echo "&nbsp;&nbsp;<a href='".ICMS_URL."/userinfo.php?uid=".$results[$i]['uid']."'>".$uname."</a>\n";
	            }
	            echo !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
	            echo "</small>";
	        }
	        echo "<br />\n";
        }
        echo '
        <table>
          <tr>
        ';
        $search_url = ICMS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
        $search_url .= "&mid=$mid&action=$action&andor=$andor";
        if ($action=='showallbyuser') {
            $search_url .= "&uid=$uid";
        }
        if ( $start > 0 ) {
            $prev = $start - 20;
            echo '<td align="left">
            ';
            $search_url_prev = $search_url."&start=$prev";
            echo '<a href="'.htmlspecialchars($search_url_prev).'">'._SR_PREVIOUS.'</a></td>
            ';
        }
        echo '<td>&nbsp;&nbsp;</td>
        ';
        if (false != $has_next) {
            $next = $start + 20;
            $search_url_next = $search_url."&start=$next";
            echo '<td align="right"><a href="'.htmlspecialchars($search_url_next).'">'._SR_NEXT.'</a></td>
            ';
        }
        echo '
          </tr>
        </table>
        <p>
        ';
    } else {
        echo '<p>'._SR_NOMATCH.'</p>';
    }
    include "include/searchform.php";
    $search_form->display();
    echo '</p>
    ';
    break;
}
include ICMS_ROOT_PATH."/footer.php";
?>
