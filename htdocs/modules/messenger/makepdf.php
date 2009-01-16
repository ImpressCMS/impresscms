<?php
/**
* PDF Generator
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

error_reporting(0);
include_once("header.php");

$msg_id = empty($_REQUEST['msg_mp']) ? '' : $_REQUEST['msg_mp'];
$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

if ( empty($msg_id) ) {
	redirect_header(XOOPS_URL."/modules/messenger/msgbox.php",1,_PM_REDNON);
}

//verify si utilisateur
global $xoopsUser, $xoopsConfig;

if (empty($xoopsUser)) {
 redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
	}

$myts =& MyTextSanitizer::getInstance();
$size = count($msg_id);
$msg =& $msg_id;
 
if(file_exists(ICMS_ROOT_PATH.'/modules/messenger/language/'.$xoopsConfig['language'].'/main.php')) {
	include_once ICMS_ROOT_PATH.'/modules/messenger/language/'.$xoopsConfig['language'].'/main.php';
} else {
	include_once ICMS_ROOT_PATH.'/modules/messenger/language/english/main.php';
}
require_once ICMS_PDF_LIB_PATH.'/tcpdf.php';
if(file_exists(ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/pdf.php')) {
	include_once ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/pdf.php';
} else {
	include_once ICMS_ROOT_PATH.'/language/english/pdf.php';
}


for ( $i = 0; $i < $size; $i++ ) {

switch( $option )
{
   default:
   redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
   break;
case "pdf_messages":
  $pm_handler  = & xoops_gethandler('priv_msgs'); 
  $pm =& $pm_handler->get($msg_id[$i]);

$pdf_title = _MP_SUBJECT.': '.$pm->getVar('subject');
$pdf_datetime = formatTimestamp($pm->getVar('msg_time'));
//$pdf_subject = preg_replace("/[^0-9a-z\-_\.]/i",'', $pm->getVar('subject').' - '.$pm->getVar('msg_text'));
$pdf_content = $pm->getVar('msg_text');
$pdf_author = XoopsUser::getUnameFromId($pm->getVar('from_userid'));


$content = '';
$content .= '<b><i><u>'.$myts->undoHtmlSpecialChars($pdf_title).'</u></i></b><br />'._POSTEDBY.' : '.$myts->undoHtmlSpecialChars($pdf_author).'<br />'._MP_POSTED.' : '.$pdf_datetime.'<br /><br /><br />';
$content .= $myts->undoHtmlSpecialChars($pdf_content);
$doc_title = $myts->undoHtmlSpecialChars($pdf_title);
$doc_keywords = 'ICMS';
$contents = Generate_PDF ($content, $doc_title, $doc_keywords); 

  break;
  
case "pdf_messagess":
 $pm_handler  = & xoops_gethandler('priv_msgs'); 
 $pm =& $pm_handler->get($msg_id[$i]);
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('msg_pid', $pm->getVar('msg_pid'))); 
 $pm =& $pm_handler->getObjects($criteria);
 
 foreach (array_keys($pm) as $i) { 
 
 
$pdf_title = _MP_SUBJECT.': '.$pm[$i]->getVar('subject');
$pdf_datetime = formatTimestamp($pm[$i]->getVar('msg_time'));
//$pdf_subject = preg_replace("/[^0-9a-z\-_\.]/i",'', $pm[$i]->getVar('subject').' - '.$pm[$i]->getVar('msg_text'));
$pdf_content = $pm[$i]->getVar('msg_text');
$pdf_author = XoopsUser::getUnameFromId($pm[$i]->getVar('from_userid'));


$content = '';
$content .= '<b><i><u>'.$myts->undoHtmlSpecialChars($pdf_title).'</u></i></b><br />'._POSTEDBY.' : '.$myts->undoHtmlSpecialChars($pdf_author).'<br />'._MP_POSTED.' : '.$pdf_datetime.'<br /><br /><br />';
$content .= $myts->undoHtmlSpecialChars($pdf_content);
$doc_title = $myts->undoHtmlSpecialChars($pdf_title);
$doc_keywords = 'ICMS';
$contents = Generate_PDF ($content, $doc_title, $doc_keywords); 

}
 break;
 }
 }
 //$pdf->Output($pdf_data['filename'],'');
?>