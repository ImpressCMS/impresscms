<?php
// $Id: search.php 506 2006-05-26 23:10:37Z skalpa $
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

$xoopsOption['pagetype'] = "search";

include 'mainfile.php';
$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

if ($xoopsConfigSearch['enable_search'] != 1) {
    header('Location: '.XOOPS_URL.'/index.php');
    exit();
}
if ($xoopsConfigSearch['enable_deep_search'] == 1) {
	$xoopsOption['template_main'] = 'system_search_deep.html';
	$search_limiter = 0;	// Do not limit search results.
} else {
	$search_limiter = $xoopsConfigSearch['num_shallow_search'];	// Limit the number of search results based on user preference.
}
$xoopsOption['template_main'] = 'system_search.html';

include XOOPS_ROOT_PATH.'/header.php';

$action = "search";
if (!empty($_GET['action'])) {
  $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
  $action = $_POST['action'];
}
$query = "";
if (!empty($_GET['query'])) {
  $query = $_GET['query'];
} elseif (!empty($_POST['query'])) {
  $query = $_POST['query'];
}
$andor = "AND";
if (!empty($_GET['andor'])) {
  $andor = $_GET['andor'];
} elseif (!empty($_POST['andor'])) {
  $andor = $_POST['andor'];
}
$mid = $uid = $start = 0;
if ( !empty($_GET['mid']) ) {
  $mid = intval($_GET['mid']);
} elseif ( !empty($_POST['mid']) ) {
  $mid = intval($_POST['mid']);
}
if (!empty($_GET['uid'])) {
  $uid = intval($_GET['uid']);
} elseif (!empty($_POST['uid'])) {
  $uid = intval($_POST['uid']);
}
if (!empty($_GET['start'])) {
  $start = intval($_GET['start']);
} elseif (!empty($_POST['start'])) {
  $start = intval($_POST['start']);
}

$xoopsTpl->assign("start", $start + 1);
$queries = array();

if ($action == "recoosults") {
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

global $xoopsUser;
$groups = is_object($xoopsUser) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler = & xoops_gethandler( 'groupperm' );
$available_modules = $gperm_handler->getItemIds('module_read', $groups);

$xoopsTpl->assign('basic_search', false);
if ($action == 'search') {
    // This area seems to handle the 'just display the advanced search page' part.
   $search_form = include 'include/searchform.php';
   $xoopsTpl->assign('search_form', $search_form);
   $xoopsTpl->assign('basic_search', true);
    include XOOPS_ROOT_PATH.'/footer.php';
    exit();
}

if ( $andor != "OR" && $andor != "exact" && $andor != "AND" ) {
    $andor = "AND";
}
$xoopsTpl->assign("search_type", $andor);

$myts =& MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
    if ( $andor != "exact" ) {
        $ignored_queries = array(); // holds kewords that are shorter than allowed minmum length
        $temp_queries = preg_split('/[\s,]+/', $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
        $queries = array($myts->addSlashes($query));
    }
}
$xoopsTpl->assign("label_search_results", _SR_SEARCHRESULTS);

// Keywords section.
$xoopsTpl->assign("label_keywords", _SR_KEYWORDS . ':');
$keywords = array();
$ignored_keywords = array();
foreach ($queries as $q) {
	$keywords[] = htmlspecialchars(stripslashes($q));
}
if (!empty($ignored_queries)) {
	 $xoopsTpl->assign("label_ignored_keywords", sprintf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']));
    foreach ($ignored_queries as $q) {
    	$ignored_keywords[] = htmlspecialchars(stripslashes($q));
    }
    $xoopsTpl->assign("ignored_keywords", $ignored_keywords);
}
$xoopsTpl->assign("searched_keywords", $keywords);

$all_results = array();
$all_results_counts = array();
switch ($action) {
    case "results":
    	$max_results_per_page = $xoopsConfigSearch['num_shallow_search'];
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
	
	    foreach ($mids as $mid) {
	        $mid = intval($mid);
	        if ( in_array($mid, $available_modules) ) {
	            $module =& $modules[$mid];
	            $results =& $module->search($queries, $andor, $search_limiter, 0);
	            $xoopsTpl->assign("showing", sprintf(_SR_SHOWING, 1, $max_results_per_page));
	            $count = count($results);
            	$all_results_counts[$module->getVar('name')] = $count;

            	if (!is_array($results) || $count == 0) {
            		$all_results[$module->getVar('name')] = array();
	            } else {
								(($count - $start) > $max_results_per_page)? $num_show_this_page = $max_results_per_page: $num_show_this_page = $count - $start;
								for ($i = 0; $i < $num_show_this_page; $i++) {
	                	  $results[$i]['processed_image_alt_text'] = $myts->makeTboxData4Show($module->getVar('name')) . ": ";
	                    if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
	                    	  $results[$i]['processed_image_url'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['image'];
	                    } else {
	                    	  $results[$i]['processed_image_url'] = "images/icons/posticon2.gif";
	                    }
	                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
	                        $results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
	                    }
	                    $results[$i]['processed_title'] = $myts->makeTboxData4Show($results[$i]['title']);
	                    $results[$i]['uid'] = @intval($results[$i]['uid']);
	                    if ( !empty($results[$i]['uid']) ) {
	                        $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
	                        $results[$i]['processed_user_name'] = $uname;
	                        $results[$i]['processed_user_url'] = XOOPS_URL."/userinfo.php?uid=".$results[$i]['uid'];
	                    }
	                    $results[$i]['processed_time'] = !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
	                }
									if ($xoopsConfigSearch['enable_deep_search'] == 1) {
										if ( $count > $max_results_per_page) {
		                    $search_url = XOOPS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
		                    $search_url .= "&mid=$mid&action=showall&andor=$andor";
		                } else {
		                	$search_url = "";
		                }
									} else {
		                if ( $count >= $max_results_per_page ) {
		                    $search_url = XOOPS_URL.'/search.php?query='.urlencode(stripslashes(implode(' ', $queries)));
		                    $search_url .= "&mid=$mid&action=showall&andor=$andor";
		                } else {
		                	$search_url = "";
		                }
									}

	            	$all_results[$module->getVar('name')] = array("search_more_title" => _SR_SHOWALLR, 
	                                                            "search_more_url" => htmlspecialchars($search_url), 
	                                                            "results" => array_slice($results, 0, $num_show_this_page));
	            }
	        }
	        unset($results);
	        unset($module);
	    }
	    break;
    case "showall":
    case 'showallbyuser':
    	$max_results_per_page = $xoopsConfigSearch['num_module_search'];
	    $module_handler =& xoops_gethandler('module');
	    $module =& $module_handler->get($mid);
	    $results =& $module->search($queries, $andor, 0, $start, $uid);
      //$xoopsTpl->assign("showing", sprintf(_SR_SHOWING, $start + 1, $start + 20));
	    $count = count($results);
	    $all_results_counts[$module->getVar('name')] = $count;
	    if (is_array($results) && $count > 0) {
					(($count - $start) > $max_results_per_page)? $num_show_this_page = $max_results_per_page: $num_show_this_page = $count - $start;
        	for ($i = 0; $i < $num_show_this_page; $i++) {
            	  $results[$i]['processed_image_alt_text'] = $myts->makeTboxData4Show($module->getVar('name')) . ": ";
                if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
                	  $results[$i]['processed_image_url'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['image'];
                } else {
                	  $results[$i]['processed_image_url'] = "images/icons/posticon2.gif";
                }
                if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                    $results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
                }
                $results[$i]['processed_title'] = $myts->makeTboxData4Show($results[$i]['title']);
                $results[$i]['uid'] = @intval($results[$i]['uid']);
                if ( !empty($results[$i]['uid']) ) {
                    $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                    $results[$i]['processed_user_name'] = $uname;
                    $results[$i]['processed_user_url'] = XOOPS_URL."/userinfo.php?uid=".$results[$i]['uid'];
                }
                $results[$i]['processed_time'] = !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
            }
            
						$search_url_prev = "";
						$search_url_next = "";
						
						$search_url_base = "XOOPS_URL.'/search.php?";
						$search_url_get_params = 'query=' . urlencode(stripslashes(implode(' ', $queries)));
		        $search_url_get_params .= "&mid=$mid&action=$action&andor=$andor";
		        if ($action=='showallbyuser') {
		            $search_url_get_params .= "&uid=$uid";
		        }
						$search_url = $search_url_base . $search_url_get_params;
						
        include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
        $pagenav = new XoopsPageNav($count, $max_results_per_page, $start, "start", $search_url_get_params);
        $all_results[$module->getVar('name')] = array("results" =>array_slice($results, 0, $num_show_this_page),
                                                      "page_nav" => $pagenav->renderNav());
	    } else {
	        echo '<p>'._SR_NOMATCH.'</p>';
	    }
	    break;
}
arsort($all_results_counts);
$xoopsTpl->assign("module_sort_order", $all_results_counts);
$xoopsTpl->assign("search_results", $all_results);

$search_form = include 'include/searchform.php';
$xoopsTpl->assign('search_form', $search_form);

include XOOPS_ROOT_PATH."/footer.php";
?>
