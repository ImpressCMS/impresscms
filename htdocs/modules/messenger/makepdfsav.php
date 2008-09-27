<?php
// $Id: makepdf.php,v 1.1.2.6 2004/11/16 21:43:13 phppp Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

error_reporting(0);
include 'header.php';
require XOOPS_ROOT_PATH.'/modules/messenger/fpdf/fpdf.inc.php';

if ($_SESSION['msg']) {
	$msg_id = $_SESSION['msg'];
} else {
	$msg_id = $_SESSION['msg'];
}

if ( empty($msg_id) ) {
	redirect_header(XOOPS_URL."/modules/messenger/msgbox.php",2,_PM_GOBACK);
}

global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("user.php",1,_PM_REGISTERNOW);
	}


	global $xoopsDB, $xoopsConfig, $xoopsModule, $story, $xoops_meta_keywords,$xoops_meta_description;
	$myts =& MyTextSanitizer::getInstance();
	$size = count($msg_id);
    $msg =& $msg_id;
 

//Other stuff
$puff='<br />';
$puffer='<br /><br /><br />';

//create the A4-PDF...
$pdf=new PDF();
$pdf->SetCreator($pdf_config['creator']);

for ( $i = 0; $i < $size; $i++ ) {
$res = $xoopsDB->query("SELECT subject, from_userid, to_userid, msg_time, msg_text from ".$xoopsDB->prefix("priv_msgsave")." where msg_id= '".$msg_id[$i]."'");	
list($subject, $from_userid, $to_userid, $msg_time, $msg_text) = $xoopsDB->fetchRow($res);

$pdf_data['title'] = MP_PDF_SUBJECT.': '.$subject;
$pdf_data['date'] = formatTimestamp($msg_time);
$pdf_data['filename'] = preg_replace("/[^0-9a-z\-_\.]/i",'', $subject.' - '.$msg_text);
$pdf_data['content'] = $msg_text;
$pdf_data['author'] = XoopsUser::getUnameFromId($from_userid);



$pdf->SetTitle($pdf_data['title']);
$pdf->SetAuthor($pdf_config['url']);

$pdf->SetSubject($pdf_data['author']);
$out=$pdf_config['url'].', '.$pdf_data['author'].', '.$pdf_data['title'].', '.$pdf_data['subtitle'].', '.$pdf_data['subsubtitle'];
$pdf->SetKeywords($out);
$pdf->SetAutoPageBreak(true,25);
$pdf->SetMargins($pdf_config['margin']['left'],$pdf_config['margin']['top'],$pdf_config['margin']['right']);


$pdf->Open();

//First page
$pdf->AddPage();
$pdf->SetXY(24,25);
$pdf->SetTextColor(10,60,160);
$pdf->SetFont($pdf_config['font']['slogan']['family'],$pdf_config['font']['slogan']['style'],$pdf_config['font']['slogan']['size']);
$pdf->WriteHTML($pdf_config['slogan'], $pdf_config['scale']);
$pdf->Image($pdf_config['logo']['path'],$pdf_config['logo']['left'],$pdf_config['logo']['top'],$pdf_config['logo']['width'],$pdf_config['logo']['height'],'',$pdf_config['url']);
$pdf->Line(25,30,190,30);
$pdf->SetXY(25,35);
$pdf->SetFont($pdf_config['font']['title']['family'],$pdf_config['font']['title']['style'],$pdf_config['font']['title']['size']);
$pdf->WriteHTML($pdf_data['title'],$pdf_config['scale']);

if ($pdf_data['subtitle']<>''){
	$pdf->WriteHTML($puff,$pdf_config['scale']);
	$pdf->SetFont($pdf_config['font']['subtitle']['family'],$pdf_config['font']['subtitle']['style'],$pdf_config['font']['subtitle']['size']);
	$pdf->WriteHTML($pdf_data['subtitle'],$pdf_config['scale']);
}
if ($pdf_data['subsubtitle']<>'') {
	$pdf->WriteHTML($puff,$pdf_config['scale']);
	$pdf->SetFont($pdf_config['font']['subsubtitle']['family'],$pdf_config['font']['subsubtitle']['style'],$pdf_config['font']['subsubtitle']['size']);
	$pdf->WriteHTML($pdf_data['subsubtitle'],$pdf_config['scale']);
}


	
$pdf->WriteHTML($puff,$pdf_config['scale']);
$pdf->SetFont($pdf_config['font']['author']['family'],$pdf_config['font']['author']['style'],$pdf_config['font']['author']['size']);
$out=MP_PDF_AUTHOR.': ';
$out.=$pdf_data['author'];
$pdf->WriteHTML($out,$pdf_config['scale']);
$pdf->WriteHTML($puff,$pdf_config['scale']);
$out=MP_PDF_DATE;
$out.=$pdf_data['date'];
$pdf->WriteHTML($out,$pdf_config['scale']);
$pdf->WriteHTML($puff,$pdf_config['scale']);

$pdf->SetTextColor(0,0,0);
$pdf->WriteHTML($puffer,$pdf_config['scale']);

$pdf->SetFont($pdf_config['font']['content']['family'],$pdf_config['font']['content']['style'],$pdf_config['font']['content']['size']);
$pdf->WriteHTML($pdf_data['content'],$pdf_config['scale']);
}
$pdf->Output($pdf_data['filename'],'');

?>