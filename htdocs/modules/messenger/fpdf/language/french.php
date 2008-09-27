<?php
// $Id: french.php,v 1.1.2.1 2004/11/16 21:53:34 phppp Exp $
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

// For end users
$pdf_config['margin'] = array(
	'left'=>25,
	'top'=>25,
	'right'=>25
	);

$pdf_config['logo'] = array(
	'path'=>'images/mp_logo.png',
	'left'=>150,
	'top'=>5,
	'width'=>0,
	'height'=>0
	);

$pdf_config['font']['slogan'] = array(
	'family'=>'Arial',
	'style'=>'bi',
	'size'=>8
	);

$pdf_config['font']['title'] = array(
	'family'=>'Arial',
	'style'=>'biu',
	'size'=>12
	);

$pdf_config['font']['subject'] = array(
	'family'=>'Arial',
	'style'=>'b',
	'size'=>11
	);

$pdf_config['font']['author'] = array(
	'family'=>'Arial',
	'style'=>'',
	'size'=>10
	);

$pdf_config['font']['subtitle'] = array(
	'family'=>'Arial',
	'style'=>'b',
	'size'=>11
	);

$pdf_config['font']['subsubtitle'] = array(
	'family'=>'Arial',
	'style'=>'b',
	'size'=>10
	);

$pdf_config['font']['content'] = array(
	'family'=>'Arial',
	'style'=>'',
	'size'=>10
	);

$pdf_config['font']['footer'] = array(
	'family'=>'Arial',
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
define('MP_PDF_SUBJECT', 'Sujet');
define('MP_PDF_AUTHOR', 'poster par');
define('MP_PDF_DATE', 'le');
define('MP_PDF_PAGE', 'Page %s');

// Usually you do not need change the following class if you are not using: S/T Chinese, Korean, Japanese
// For more details, refer to: http://fpdf.org
class PDF_language extends FPDF
{
	function PDF_language($orientation='P',$unit='mm',$format='A4')
	{
	    //Call parent constructor
	    $this->FPDF($orientation,$unit,$format);
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