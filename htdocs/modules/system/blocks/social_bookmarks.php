<?php
/**
* Social Bookmarks
*
* System tool that allow's you to bookmark and share your interesting pageswith other people
* Some parts of this tool is based on social_bookmarks module.
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.2
* @author		Sina Asghari (AKA stranger) <stranger@impresscms.ir>
* @author		Rene Sato (AKA sato-san) <www.impresscms.de>
* @version		$Id$
*/

function b_social_bookmarks(){
    global $xoopsConfig, $xoopsUser, $xoopsModule;
		if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
$bookmark ="
<div style=\"border-top-style:solid; padding-top:3px; border-top-width: 1px; border-top-color: #2A4956; float: right;\">
<script language=\"JavaScript\" type=\"text/JavaScript\">

function Bookmark_Load() {
var d=document; if(d.images){ if(!d.Social) d.Social=new Array();
var i,j=d.Social.length,a=Bookmark_Load.arguments; for(i=0; i<a.length; i++)
if (a[i].indexOf(\"#\")!=0){ d.Social[j]=new Image; d.Social[j++].src=a[i];}}
}
Bookmark_Load('".ICMS_URL."/modules/system/images/icons/wong_be.gif','".ICMS_URL."/modules/system/images/icons/webnews_be.gif','".ICMS_URL."/modules/system/images/icons/icio_be.gif','".ICMS_URL."/modules/system/images/icons/oneview_be.gif','".ICMS_URL."/modules/system/images/icons/newsider_be.gif','".ICMS_URL."/modules/system/images/icons/folkd_be.gif','".ICMS_URL."/modules/system/images/icons/yigg_be.gif','".ICMS_URL."/modules/system/images/icons/linkarena_be.gif','".ICMS_URL."/modules/system/images/icons/digg_be.gif','".ICMS_URL."/modules/system/images/icons/del_be.gif','".ICMS_URL."/modules/system/images/icons/reddit_be.gif','".ICMS_URL."/modules/system/images/icons/simpy_be.gif','".ICMS_URL."/modules/system/images/icons/stumbleupon_be.gif','".ICMS_URL."/modules/system/images/icons/slashdot_be.gif','".ICMS_URL."/modules/system/images/icons/netscape_be.gif','".ICMS_URL."/modules/system/images/icons/furl_be.gif','".ICMS_URL."/modules/system/images/icons/yahoo_be.gif','".ICMS_URL."/modules/system/images/icons/spurl_be.gif','".ICMS_URL."/modules/system/images/icons/google_be.gif','".ICMS_URL."/modules/system/images/icons/blinklist_be.gif','".ICMS_URL."/modules/system/images/icons/blogmarks_be.gif','".ICMS_URL."/modules/system/images/icons/diigo_be.gif','".ICMS_URL."/modules/system/images/icons/technorati_be.gif','".ICMS_URL."/modules/system/images/icons/newsvine_be.gif','".ICMS_URL."/modules/system/images/icons/blinkbits_be.gif','".ICMS_URL."/modules/system/images/icons/ma.gnolia_be.gif','".ICMS_URL."/modules/system/images/icons/smarking_be.gif','".ICMS_URL."/modules/system/images/icons/netvouz_be.gif','".ICMS_URL."/modules/system/images/icons/what_be.gif','".ICMS_URL."/modules/system/images/icons/facebook_be.gif','".ICMS_URL."/modules/system/images/icons/ask_be.gif')
function genau() {
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function mach(n, d) {
  var p,i,x; if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=mach(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
  }
function bitte() {
  var i,j=0,x,a=bitte.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
  if ((x=mach(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
  }

</script></div>";
$bookmark .="
<div style=\" border-top-color: #2A4956; float: right;\">";
	   } else {
$bookmark ="
<div style=\"border-top-style:solid; padding-top:3px; border-top-width: 1px; border-top-color: #2A4956; float: left;\">
<script language=\"JavaScript\" type=\"text/JavaScript\">

function Bookmark_Load() {
var d=document; if(d.images){ if(!d.Social) d.Social=new Array();
var i,j=d.Social.length,a=Bookmark_Load.arguments; for(i=0; i<a.length; i++)
if (a[i].indexOf(\"#\")!=0){ d.Social[j]=new Image; d.Social[j++].src=a[i];}}
}
Bookmark_Load('".ICMS_URL."/modules/system/images/icons/wong_be.gif','".ICMS_URL."/modules/system/images/icons/webnews_be.gif','".ICMS_URL."/modules/system/images/icons/icio_be.gif','".ICMS_URL."/modules/system/images/icons/oneview_be.gif','".ICMS_URL."/modules/system/images/icons/newsider_be.gif','".ICMS_URL."/modules/system/images/icons/folkd_be.gif','".ICMS_URL."/modules/system/images/icons/yigg_be.gif','".ICMS_URL."/modules/system/images/icons/linkarena_be.gif','".ICMS_URL."/modules/system/images/icons/digg_be.gif','".ICMS_URL."/modules/system/images/icons/del_be.gif','".ICMS_URL."/modules/system/images/icons/reddit_be.gif','".ICMS_URL."/modules/system/images/icons/simpy_be.gif','".ICMS_URL."/modules/system/images/icons/stumbleupon_be.gif','".ICMS_URL."/modules/system/images/icons/slashdot_be.gif','".ICMS_URL."/modules/system/images/icons/netscape_be.gif','".ICMS_URL."/modules/system/images/icons/furl_be.gif','".ICMS_URL."/modules/system/images/icons/yahoo_be.gif','".ICMS_URL."/modules/system/images/icons/spurl_be.gif','".ICMS_URL."/modules/system/images/icons/google_be.gif','".ICMS_URL."/modules/system/images/icons/blinklist_be.gif','".ICMS_URL."/modules/system/images/icons/blogmarks_be.gif','".ICMS_URL."/modules/system/images/icons/diigo_be.gif','".ICMS_URL."/modules/system/images/icons/technorati_be.gif','".ICMS_URL."/modules/system/images/icons/newsvine_be.gif','".ICMS_URL."/modules/system/images/icons/blinkbits_be.gif','".ICMS_URL."/modules/system/images/icons/ma.gnolia_be.gif','".ICMS_URL."/modules/system/images/icons/smarking_be.gif','".ICMS_URL."/modules/system/images/icons/netvouz_be.gif','".ICMS_URL."/modules/system/images/icons/what_be.gif','".ICMS_URL."/modules/system/images/icons/facebook_be.gif','".ICMS_URL."/modules/system/images/icons/ask_be.gif')
function genau() {
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function mach(n, d) {
  var p,i,x; if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=mach(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
  }
function bitte() {
  var i,j=0,x,a=bitte.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
  if ((x=mach(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
  }

</script></div>";
$bookmark .="
<div style=\" border-top-color: #2A4956; float: left;\">";
           }
    if ( file_exists( ICMS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/social_bookmarks_links.php" ) ) {
		include_once ICMS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/social_bookmarks_links.php";
	} else {
		include_once ICMS_ROOT_PATH."/modules/system/language/english/admin/social_bookmarks_links.php";
	}
  $bookmark .= "</div>";
$block['bookmark']=$bookmark;
return $block;
}
?>