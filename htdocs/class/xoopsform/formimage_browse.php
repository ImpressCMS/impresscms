<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo navegação na Biblioteca de imagens
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital � 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: formimage_browse.php,v 1.1.1.1 2007/04/30 23:18:58 topet05 Exp $
### =============================================================
if ( file_exists("../../mainfile.php") ) {
	include_once("../../mainfile.php");
} elseif (file_exists("../../../mainfile.php")) {
	include_once("../../../mainfile.php");
}
$target = $_REQUEST['target'];
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$op = (empty($_GET['op'])) ? 'list' : $_GET['op'];
$op = (empty($_POST['op'])) ? $op : $_POST['op'];
if (!is_object($xoopsUser)) {
	$groups = array(XOOPS_GROUP_ANONYMOUS);
	$admin = false;
} else {
	$groups =& $xoopsUser->getGroups();
	$admin = (!$xoopsUser->isAdmin(1)) ? false : true;
}
$imgcat_handler = xoops_gethandler('imagecategory');
$criteriaRead = new CriteriaCompo();
if (is_array($groups) && !empty($groups)) {
	$criteriaTray = new CriteriaCompo();
	foreach ($groups as $gid) {
		$criteriaTray->add(new Criteria('gperm_groupid', $gid), 'OR');
	}
	$criteriaRead->add($criteriaTray);
	$criteriaRead->add(new Criteria('gperm_name', 'imgcat_read'));
	$criteriaRead->add(new Criteria('gperm_modid', 1));
}
$criteriaRead->add(new Criteria('imgcat_display', 1));
$imagecategorys =& $imgcat_handler->getObjects($criteriaRead);
$criteriaWrite = new CriteriaCompo();
if (is_array($groups) && !empty($groups)) {
	$criteriaWrite->add($criteriaTray);
	$criteriaWrite->add(new Criteria('gperm_name', 'imgcat_read'));
	$criteriaWrite->add(new Criteria('gperm_modid', 1));
}
$criteriaWrite->add(new Criteria('imgcat_display', 1));
$imagecategorysWrite =& $imgcat_handler->getObjects($criteriaWrite);

include_once(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/images.php");
if ($op == 'updatecat' && $admin) {
	$imgcat_id = $_POST['imgcat_id'];
	$readgroup = $_POST['readgroup'];
	$writegroup = $_POST['writegroup'];
	if (!$GLOBALS['xoopsSecurity']->check() || $imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imgcat_handler = xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategory->setVar('imgcat_name', $_POST['imgcat_name']);
	$imgcat_display = empty($_POST['imgcat_display']) ? 0 : 1;
	$imagecategory->setVar('imgcat_display', $_POST['imgcat_display']);
	$imagecategory->setVar('imgcat_maxsize', $_POST['imgcat_maxsize']);
	$imagecategory->setVar('imgcat_maxwidth', $_POST['imgcat_maxwidth']);
	$imagecategory->setVar('imgcat_maxheight', $_POST['imgcat_maxheight']);
	$imagecategory->setVar('imgcat_weight', $_POST['imgcat_weight']);
	if (!$imgcat_handler->insert($imagecategory)) {
		exit();
	}
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo(new Criteria('gperm_itemid', $imgcat_id));
	$criteria->add(new Criteria('gperm_modid', 1));
	$criteria2 = new CriteriaCompo(new Criteria('gperm_name', 'imgcat_write'));
	$criteria2->add(new Criteria('gperm_name', 'imgcat_read'), 'OR');
	$criteria->add($criteria2);
	$imagecategoryperm_handler->deleteAll($criteria);
	if (!isset($readgroup)) {
		$readgroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
		array_push($readgroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($readgroup as $rgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $rgroup);
		$imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_read');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	if (!isset($writegroup)) {
		$writegroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
		array_push($writegroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($writegroup as $wgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $wgroup);
		$imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_write');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	$op = "list";
}
if ($op == 'addcat' && $admin) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF'],2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$readgroup = $_POST['readgroup'];
	$writegroup = $_POST['writegroup'];
	$imgcat_handler =& xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->create();
	$imagecategory->setVar('imgcat_name', $_POST['imgcat_name']);
	$imagecategory->setVar('imgcat_maxsize', $_POST['imgcat_maxsize']);
	$imagecategory->setVar('imgcat_maxwidth', $_POST['imgcat_maxwidth']);
	$imagecategory->setVar('imgcat_maxheight', $_POST['imgcat_maxheight']);
	$imgcat_display = empty($_POST['imgcat_display']) ? 0 : 1;
	$imagecategory->setVar('imgcat_display', $imgcat_display);
	$imagecategory->setVar('imgcat_weight', $_POST['imgcat_weight']);
	$imagecategory->setVar('imgcat_storetype', $_POST['imgcat_storetype']);
	$imagecategory->setVar('imgcat_type', 'C');
	if (!$imgcat_handler->insert($imagecategory)) {
		exit();
	}
	$newid = $imagecategory->getVar('imgcat_id');
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	if (!isset($readgroup)) {
		$readgroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
		array_push($readgroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($readgroup as $rgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $rgroup);
		$imagecategoryperm->setVar('gperm_itemid', $newid);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_read');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	if (!isset($writegroup)) {
		$writegroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
		array_push($writegroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($writegroup as $wgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $wgroup);
		$imagecategoryperm->setVar('gperm_itemid', $newid);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_write');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	$op = "list";
}
if ($op == 'delcatok' && $admin) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF']."?target=".$target,3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imgcat_id = intval($_POST['imgcat_id']);
	if ($imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imgcat_handler = xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$image_handler =& xoops_gethandler('image');
	$images =& $image_handler->getObjects(new Criteria('imgcat_id', $imgcat_id), true, false);
	$errors = array();
	foreach (array_keys($images) as $i) {
		$image_handler->delete($images[$i]);
		if (file_exists(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name'))){
			@unlink(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name'));
		}
	}
	$imgcat_handler->delete($imagecategory);
	$op = "list";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo _MD_IMGMAIN ?></title>
<script language="javascript" type="text/javascript">
function tabberObj(argsObj)
{var arg;this.div=null;this.classMain="tabber";this.classMainLive="tabberlive";this.classTab="tabbertab";this.classTabDefault="tabbertabdefault";this.classNav="tabbernav";this.classTabHide="tabbertabhide";this.classNavActive="tabberactive";this.titleElements=['h2','h3','h4','h5','h6'];this.titleElementsStripHTML=true;this.removeTitle=true;this.addLinkId=false;this.linkIdFormat='<tabberid>nav<tabnumberone>';for(arg in argsObj){this[arg]=argsObj[arg];}
this.REclassMain=new RegExp('\\b'+this.classMain+'\\b','gi');this.REclassMainLive=new RegExp('\\b'+this.classMainLive+'\\b','gi');this.REclassTab=new RegExp('\\b'+this.classTab+'\\b','gi');this.REclassTabDefault=new RegExp('\\b'+this.classTabDefault+'\\b','gi');this.REclassTabHide=new RegExp('\\b'+this.classTabHide+'\\b','gi');this.tabs=new Array();if(this.div){this.init(this.div);this.div=null;}}
tabberObj.prototype.init=function(e)
{var
childNodes,i,i2,t,defaultTab=0,DOM_ul,DOM_li,DOM_a,aId,headingElement;if(!document.getElementsByTagName){return false;}
if(e.id){this.id=e.id;}
this.tabs.length=0;childNodes=e.childNodes;for(i=0;i<childNodes.length;i++){if(childNodes[i].className&&childNodes[i].className.match(this.REclassTab)){t=new Object();t.div=childNodes[i];this.tabs[this.tabs.length]=t;if(childNodes[i].className.match(this.REclassTabDefault)){defaultTab=this.tabs.length-1;}}}
DOM_ul=document.createElement("ul");DOM_ul.className=this.classNav;for(i=0;i<this.tabs.length;i++){t=this.tabs[i];t.headingText=t.div.title;if(this.removeTitle){t.div.title='';}
if(!t.headingText){for(i2=0;i2<this.titleElements.length;i2++){headingElement=t.div.getElementsByTagName(this.titleElements[i2])[0];if(headingElement){t.headingText=headingElement.innerHTML;if(this.titleElementsStripHTML){t.headingText.replace(/<br>/gi," ");t.headingText=t.headingText.replace(/<[^>]+>/g,"");}
break;}}}
if(!t.headingText){t.headingText=i+1;}
DOM_li=document.createElement("li");t.li=DOM_li;DOM_a=document.createElement("a");DOM_a.appendChild(document.createTextNode(t.headingText));DOM_a.href="javascript:void(null);";DOM_a.title=t.headingText;DOM_a.onclick=this.navClick;DOM_a.tabber=this;DOM_a.tabberIndex=i;if(this.addLinkId&&this.linkIdFormat){aId=this.linkIdFormat;aId=aId.replace(/<tabberid>/gi,this.id);aId=aId.replace(/<tabnumberzero>/gi,i);aId=aId.replace(/<tabnumberone>/gi,i+1);aId=aId.replace(/<tabtitle>/gi,t.headingText.replace(/[^a-zA-Z0-9\-]/gi,''));DOM_a.id=aId;}
DOM_li.appendChild(DOM_a);DOM_ul.appendChild(DOM_li);}
e.insertBefore(DOM_ul,e.firstChild);e.className=e.className.replace(this.REclassMain,this.classMainLive);this.tabShow(defaultTab);if(typeof this.onLoad=='function'){this.onLoad({tabber:this});}
return this;};tabberObj.prototype.navClick=function(event)
{var
rVal,a,self,tabberIndex,onClickArgs;a=this;if(!a.tabber){return false;}
self=a.tabber;tabberIndex=a.tabberIndex;a.blur();if(typeof self.onClick=='function'){onClickArgs={'tabber':self,'index':tabberIndex,'event':event};if(!event){onClickArgs.event=window.event;}
rVal=self.onClick(onClickArgs);if(rVal===false){return false;}}
self.tabShow(tabberIndex);return false;};tabberObj.prototype.tabHideAll=function()
{var i;for(i=0;i<this.tabs.length;i++){this.tabHide(i);}};tabberObj.prototype.tabHide=function(tabberIndex)
{var div;if(!this.tabs[tabberIndex]){return false;}
div=this.tabs[tabberIndex].div;if(!div.className.match(this.REclassTabHide)){div.className+=' '+this.classTabHide;}
this.navClearActive(tabberIndex);return this;};tabberObj.prototype.tabShow=function(tabberIndex)
{var div;if(!this.tabs[tabberIndex]){return false;}
this.tabHideAll();div=this.tabs[tabberIndex].div;div.className=div.className.replace(this.REclassTabHide,'');this.navSetActive(tabberIndex);if(typeof this.onTabDisplay=='function'){this.onTabDisplay({'tabber':this,'index':tabberIndex});}
return this;};tabberObj.prototype.navSetActive=function(tabberIndex)
{this.tabs[tabberIndex].li.className=this.classNavActive;return this;};tabberObj.prototype.navClearActive=function(tabberIndex)
{this.tabs[tabberIndex].li.className='';return this;};function tabberAutomatic(tabberArgs)
{var
tempObj,divs,i;if(!tabberArgs){tabberArgs={};}
tempObj=new tabberObj(tabberArgs);divs=document.getElementsByTagName("div");for(i=0;i<divs.length;i++){if(divs[i].className&&divs[i].className.match(tempObj.REclassMain)){tabberArgs.div=divs[i];divs[i].tabber=new tabberObj(tabberArgs);}}
return this;}
function tabberAutomaticOnLoad(tabberArgs)
{var oldOnLoad;if(!tabberArgs){tabberArgs={};}
oldOnLoad=window.onload;if(typeof window.onload!='function'){window.onload=function(){tabberAutomatic(tabberArgs);};}else{window.onload=function(){oldOnLoad();tabberAutomatic(tabberArgs);};}}
if(typeof tabberOptions=='undefined'){tabberAutomaticOnLoad();}else{if(!tabberOptions['manualStartup']){tabberAutomaticOnLoad(tabberOptions);}}
</script>
<script language="javascript" type="text/javascript">
<!--
function addItem(itemurl, name, target, cat) {
	var win = opener;
	var campo = win.document.getElementById(target);
	var opcoes = win.document.getElementById('img_cat_'+cat);
	var imagem = win.document.getElementById(target+'_img');
	if(opcoes){
		for(x=0; x<campo.options.length; x++){
			if(campo.options[x].value == itemurl){
				campo.options[x].selected = true;
				imagem.src = "<?php echo XOOPS_URL?>"+itemurl;
				var found = true;
			}
		}
		if(!found){
			var newOption = win.document.createElement("option");
			opcoes.appendChild(newOption);
			newOption.text = name;
			newOption.value = itemurl;
			newOption.selected = true;
			imagem.src = "<?php echo XOOPS_URL?>"+itemurl;
		}
	}
	window.close();
	return;
}
//-->
</script>
<?php		if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/icms_rtl.css" />';
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/modules/system/style_rtl.css" />';
	   } else {
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/icms.css" />';
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/modules/system/style.css" />';
           }
?>
<style type="text/css">
.tabberlive .tabbertabhide {
 display:none;
}

.tabber {
}
.tabberlive {
 margin-top:1em;
}

ul.tabbernav
{
 margin:0;
 padding: 3px 0;
 border-bottom: 1px solid #778;
 font: bold 12px Verdana, sans-serif;
}

ul.tabbernav li
{
 list-style: none;
 margin: 0;
 display: inline;
}

ul.tabbernav li a
{
 padding: 3px 0.5em;
 margin-left: 3px;
 border: 1px solid #778;
 border-bottom: none;
 background: #DDE;
 text-decoration: none;
}

ul.tabbernav li a:link { color: #448; }
ul.tabbernav li a:visited { color: #667; }

ul.tabbernav li a:hover
{
 color: #000;
 background: #AAE;
 border-color: #227;
}

ul.tabbernav li.tabberactive a
{
 background-color: #fff;
 border-bottom: 1px solid #fff;
}

ul.tabbernav li.tabberactive a:hover
{
 color: #000;
 background: white;
 border-bottom: 1px solid white;
}

.tabberlive .tabbertab {
 padding:5px;
 border:1px solid #aaa;
 border-top:0;

 /* If you don't want the tab size changing whenever a tab is changed
    you can set a fixed height */

 height:400px;

 /* If you set a fix height set overflow to auto and you will get a
    scrollbar when necessary */

 overflow:auto;
}

/* If desired, hide the heading since a heading is provided by the tab */
.tabberlive .tabbertab h2 {
 display:none;
}
.tabberlive .tabbertab h3 {
 display:none;
}
</style>
</head>
<body>
<div class="tabber">
		<div class="tabbertab<?php echo ($op == "listimg" || $op == "editcat" || $op == "delcat" || $op == "list") ? ' tabbertabdefault' : '';?>">
				<h2><?php echo _SEARCH ?></h2>
<?PHP
if ($op == 'delcat' && $admin) {
	xoops_confirm(array('op' => 'delcatok', 'target' => $target, 'imgcat_id' => $_GET['imgcat_id']), $_SERVER['PHP_SELF'], _MD_RUDELIMGCAT);
}elseif ($op == 'editcat' && $admin) {
	$imgcat_id = $_GET['imgcat_id'];
	if ($imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imgcat_handler = xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	$form = new XoopsThemeForm(_MD_EDITIMGCAT, 'imagecat_form', $_SERVER['PHP_SELF'], 'post', true);
	$form->addElement(new XoopsFormText(_MD_IMGCATNAME, 'imgcat_name', 50, 255, $imagecategory->getVar('imgcat_name')), true);
	$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATRGRP, 'readgroup', true, $imagecategoryperm_handler->getGroupIds('imgcat_read', $imgcat_id), 5, true));
	$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATWGRP, 'writegroup', true, $imagecategoryperm_handler->getGroupIds('imgcat_write', $imgcat_id), 5, true));
	$form->addElement(new XoopsFormText(_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, $imagecategory->getVar('imgcat_maxsize')));
	$form->addElement(new XoopsFormText(_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, $imagecategory->getVar('imgcat_maxwidth')));
	$form->addElement(new XoopsFormText(_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, $imagecategory->getVar('imgcat_maxheight')));
	$form->addElement(new XoopsFormText(_MD_IMGCATWEIGHT, 'imgcat_weight', 3, 4, $imagecategory->getVar('imgcat_weight')));
	$form->addElement(new XoopsFormRadioYN(_MD_IMGCATDISPLAY, 'imgcat_display', $imagecategory->getVar('imgcat_display'), _YES, _NO));
	$storetype = array('db' => _MD_INDB, 'file' => _MD_ASFILE);
	$form->addElement(new XoopsFormLabel(_MD_IMGCATSTRTYPE, $storetype[$imagecategory->getVar('imgcat_storetype')]));
	$form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
	$form->addElement(new XoopsFormHidden('op', 'updatecat'));
	$form->addElement(new XoopsFormHidden('target', $target));
	$form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
	echo '<a href="'.$_SERVER['PHP_SELF'].'?target='.$target.'">'. _MD_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'<br /><br />';
	$form->display();
}elseif ($op == 'listimg') {
	$imgcat_id = intval($_GET['imgcat_id']);
	$imgcat_handler = xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$image_handler = xoops_gethandler('image');
	echo '<h4><a href="'.$_SERVER['PHP_SELF'].'?target='.$target.'">'. _MD_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'</h4><br /><br />';
	$criteria = new Criteria('imgcat_id', $imgcat_id);
	$imgcount = $image_handler->getCount($criteria);
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$criteria->setStart($start);
	$criteria->setLimit(20);
	$images =& $image_handler->getObjects($criteria, true, false);
	echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'._IMAGENAME.'</td>
	<td style="border: 1px double black; text-align: center">'._IMAGEMIME.'</td>
	<td style="border: 1px double black; text-align: center">'._OPTIONS.'</td>
	</tr></thead><tbody>
	';
	foreach (array_keys($images) as $i) {
		echo '<tr><td width="30%" style="text-align: center">';
		if ($imagecategory->getVar('imgcat_storetype') == 'db') {
			$imagem_url = XOOPS_URL.'/image.php?id='.$i;
			$url = '/image.php?id='.$i;
		} else {
			$imagem_url = XOOPS_UPLOAD_URL.'/'.$images[$i]->getVar('image_name');
			$url = '/uploads/'.$images[$i]->getVar('image_name');
		}
		echo '<img src="'.$imagem_url.'" alt="" width="50" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid white\'" style="border:2px solid white" onclick="addItem(\''.$url.'\', \''.$images[$i]->getVar('image_nicename').'\', \''.$target.'\', \''.$images[$i]->getVar('imgcat_id').'\')"/>';
		echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$images[$i]->getVar('image_nicename').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$images[$i]->getVar('image_mimetype').'</td>';
		echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$url.'\', \''.$images[$i]->getVar('image_nicename').'\', \''.$target.'\', \''.$images[$i]->getVar('imgcat_id').'\')">'._SELECT.'</a></td></tr>';
	}
	echo "</tbody></table>";
	if ($imgcount > 0) {
		if ($imgcount > 20) {
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($imgcount, 20, $start, 'start', 'op=listimg&amp;imgcat_id='.$imgcat_id);
			echo '<div style="text-align:right">'.$nav->renderNav().'</div>';
		}
	}
}else{
	echo '<ul>';
	$catcount = count($imagecategorys);
	$image_handler =& xoops_gethandler('image');
	for ($i = 0; $i < $catcount; $i++) {
		$count = $image_handler->getCount(new Criteria('imgcat_id', $imagecategorys[$i]->getVar('imgcat_id')));
		echo '<li>'.$imagecategorys[$i]->getVar('imgcat_name').' ('.sprintf(_NUMIMAGES, '<b>'.$count.'</b>').') [<a href="'.$_SERVER['PHP_SELF'].'?op=listimg&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'&amp;target='.$target.'">'._LIST.'</a>]'.(($admin) ? ' [<a href="'.$_SERVER['PHP_SELF'].'?op=editcat&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'&amp;target='.$target.'">'._EDIT.'</a>]' : '');
		if ($imagecategorys[$i]->getVar('imgcat_type') == 'C' && $admin) {
			echo ' [<a href="'.$_SERVER['PHP_SELF'].'?op=delcat&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'">'._DELETE.'</a>]';
		}
		echo '</li>';
	}
	echo '</ul>';
}
?>
</div>
<?PHP
if(count($imagecategorysWrite) > 0){
?>
	<div class="tabbertab<?php echo ($op == "addfile") ? ' tabbertabdefault' : '';?>">
				<h2><?PHP echo _ADDIMAGE ?></h2>
<?PHP
if ($op == 'addfile') {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF'], 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imgcat_handler =& xoops_gethandler('imagecategory');
	$imagecategory =& $imgcat_handler->get(intval($_POST['imgcat_id']));
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
	$uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp'), $imagecategory->getVar('imgcat_maxsize'), $imagecategory->getVar('imgcat_maxwidth'), $imagecategory->getVar('imgcat_maxheight'));
	$uploader->setPrefix('img');
	$err = array();
	$ucount = count($_POST['xoops_upload_file']);
	for ($i = 0; $i < $ucount; $i++) {
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
			if (!$uploader->upload()) {
				$err[] = $uploader->getErrors();
			} else {
				$image_handler =& xoops_gethandler('image');
				$image =& $image_handler->create();
				$image->setVar('image_name', $uploader->getSavedFileName());
				$image->setVar('image_nicename', $_POST['image_nicename']);
				$image->setVar('image_mimetype', $uploader->getMediaType());
				$image->setVar('image_created', time());
				$image_display = empty($_POST['image_display']) ? 0 : 1;
				$image->setVar('image_display', $_POST['image_display']);
				$image->setVar('image_weight', $_POST['image_weight']);
				$image->setVar('imgcat_id', $_POST['imgcat_id']);
				if ($imagecategory->getVar('imgcat_storetype') == 'db') {
					$fp = @fopen($uploader->getSavedDestination(), 'rb');
					$fbinary = @fread($fp, filesize($uploader->getSavedDestination()));
					@fclose($fp);
					$image->setVar('image_body', $fbinary, true);
					@unlink($uploader->getSavedDestination());
				}
				if (!$image_handler->insert($image)) {
					$err[] = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
				}
			}
		} else {
			$err[] = sprintf(_FAILFETCHIMG, $i);
			$err = array_merge($err, $uploader->getErrors(false));
		}
	}
	if (count($err) > 0) {
		echo "<fieldset><legend>"._ERRORS."</legend>";
		xoops_error($err);
		echo "</fieldset>";
	}else{
		echo "<fieldset><legend>"._IMGMANAGER."</legend>";
		echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'._IMAGENAME.'</td>
	<td style="border: 1px double black; text-align: center">'._IMAGEMIME.'</td>
	<td style="border: 1px double black; text-align: center">'._OPTIONS.'</td>
	</tr></thead><tbody>
	';
		echo '<tr><td width="30%" style="text-align: center">';
		if ($imagecategory->getVar('imgcat_storetype') == 'db') {
			$imagem_url = XOOPS_URL.'/image.php?id='.$image->getVar('image_id');
			$url = '/image.php?id='.$image->getVar('image_id');
		} else {
			$imagem_url = XOOPS_UPLOAD_URL.'/'.$image->getVar('image_name');
			$url = '/uploads/'.$image->getVar('image_name');
		}
		echo '<img src="'.$imagem_url.'" alt="" width="50" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid white\'" style="border:2px solid white" onclick="addItem(\''.$url.'\', \''.$image->getVar('image_nicename').'\', \''.$target.'\', \''.$image->getVar('imgcat_id').'\')"/>';
		echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$image->getVar('image_nicename').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$image->getVar('image_mimetype').'</td>';
		echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$url.'\', \''.$image->getVar('image_nicename').'\', \''.$target.'\', \''.$image->getVar('imgcat_id').'\')">'._SELECT.'</a></td></tr>';
	}
	echo "</tbody></table></fieldset>";
}
echo '<h3>'._ADDIMAGE.'</h3>';
$imgcat_handler =& xoops_gethandler('imagecategory');
$catcount = count($imagecategorysWrite);
if (!empty($catcount)) {
	$form = new XoopsThemeForm(_ADDIMAGE, 'image_form', $_SERVER['PHP_SELF'], 'post', true);
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new XoopsFormText(_IMAGENAME, 'image_nicename', 50, 255));
	$select = new XoopsFormSelect(_IMAGECAT, 'imgcat_id');
	$select->addOptionArray($imgcat_handler->getList($groups, "imgcat_write", 1));
	$form->addElement($select);
	$form->addElement(new XoopsFormFile(_IMAGEFILE, 'image_file', 5000000));
	$form->addElement(new XoopsFormText(_IMGWEIGHT, 'image_weight', 3, 4, 0));
	$form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'image_display', 1, _YES, _NO));
	$form->addElement(new XoopsFormHidden('op', 'addfile'));
	$form->addElement(new XoopsFormHidden('target', $target));
	$form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
	$form->display();
}
?>
</div>
<?PHP
}
?>
<?php if ($admin) { ?>
<div class="tabbertab<?php echo ($op == "addcat" || count($imagecategorysWrite) <= 0) ? ' tabbertabdefault' : '';?>">
<h2><?php echo _ADD." "._IMAGECAT?></h2>
<?PHP
$form = new XoopsThemeForm(_MD_ADDIMGCAT, 'imagecat_form', $_SERVER['PHP_SELF'], 'post', true);
$form->addElement(new XoopsFormText(_MD_IMGCATNAME, 'imgcat_name', 50, 255), true);
$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATRGRP, 'readgroup', true, XOOPS_GROUP_ADMIN, 5, true));
$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATWGRP, 'writegroup', true, XOOPS_GROUP_ADMIN, 5, true));
$form->addElement(new XoopsFormText(_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, 50000));
$form->addElement(new XoopsFormText(_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, 120));
$form->addElement(new XoopsFormText(_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, 120));
$form->addElement(new XoopsFormText(_MD_IMGCATWEIGHT, 'imgcat_weight', 3, 4, 0));
$form->addElement(new XoopsFormRadioYN(_MD_IMGCATDISPLAY, 'imgcat_display', 1, _YES, _NO));
$storetype = new XoopsFormRadio(_MD_IMGCATSTRTYPE.'<br /><span style="color:#ff0000;">'._MD_STRTYOPENG.'</span>', 'imgcat_storetype', 'file');
$storetype->addOptionArray(array('file' => _MD_ASFILE, 'db' => _MD_INDB));
$form->addElement($storetype);
$form->addElement(new XoopsFormHidden('op', 'addcat'));
$form->addElement(new XoopsFormHidden('target', $target));
$form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
$form->display();
?>
</div>
<?php } ?>
			<div style="float: right">
				<input type="button" id="cancel" name="cancel" value="<?php echo _CLOSE ?>" onclick="window.close();" />
			</div>
</div>
<!--
<!--{xo-logger-output}-->
-->
</body>
</html>
