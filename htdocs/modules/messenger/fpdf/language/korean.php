<?php
// $Id: korean.php,v 1.1.2.3 2004/11/16 01:15:10 phppp Exp $
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
require NEWBB_FPDF_PATH.'/korean.php';

// For end users
$pdf_config['margin'] = array(
	'left'=>25,
	'top'=>25,
	'right'=>25
	);

$pdf_config['logo'] = array(
	'path'=>'images/xoopsbb_slogo.png',
	'left'=>150,
	'top'=>5,
	'width'=>0,
	'height'=>0
	);

$pdf_config['font']['slogan'] = array(
	'family'=>'UHC-hw',
	'style'=>'bi',
	'size'=>8
	);

$pdf_config['font']['title'] = array(
	'family'=>'UHC-hw',
	'style'=>'biu',
	'size'=>12
	);

$pdf_config['font']['subject'] = array(
	'family'=>'UHC-hw',
	'style'=>'b',
	'size'=>11
	);

$pdf_config['font']['author'] = array(
	'family'=>'UHC-hw',
	'style'=>'',
	'size'=>10
	);
	
$pdf_config['font']['subtitle'] = array(
	'family'=>'UHC-hw',
	'style'=>'b',
	'size'=>11
	);

$pdf_config['font']['subsubtitle'] = array(
	'family'=>'UHC-hw',
	'style'=>'b',
	'size'=>10
	);

$pdf_config['font']['content'] = array(
	'family'=>'UHC-hw',
	'style'=>'',
	'size'=>10
	);

$pdf_config['font']['footer'] = array(
	'family'=>'UHC-hw',
	'style'=>'',
	'size'=>8
	);

$pdf_config['action_on_error'] = 0; // 0 - continue; 1 - die
$pdf_config['creator'] = 'FPDF v1.52';
$pdf_config['url'] = XOOPS_URL;
$pdf_config['mail'] = 'mailto:'.$xoopsConfig['adminmail'];
$pdf_config['slogan']=xoops_substr($myts->makeTboxData4Show( $xoopsConfig['slogan'] ),0,20);
// Or set your own slogan:
//$pdf_config['slogan']= "Make PDF for my NewBB";
$pdf_config['scale'] = '0.8';
$pdf_config['dateformat'] = _DATESTRING;

// For local support sites
define('NEWBB_PDF_FORUM', 'Forum');
define('NEWBB_PDF_TOPIC', 'Topic');
define('NEWBB_PDF_SUBJECT', 'Subject');
define('NEWBB_PDF_AUTHOR', _POSTEDBY);
define('NEWBB_PDF_DATE', _MD_POSTEDON);
define('MP_PDF_PAGE', 'Page %s');

// For more details, refer to: http://fpdf.org
class PDF_language extends FPDF
{
	function PDF_language($orientation='P',$unit='mm',$format='A4')
	{
	    //Call parent constructor
	    $this->FPDF($orientation,$unit,$format);
	    //Initialization
		$this->AddUHChwFont();
	}

	function Error($msg)
	{
		global $pdf_config;
		if($pdf_config['action_on_error']){
			//Fatal error
			die('<B>FPDF error: </B>'.$msg);
		}
	}
}
?>