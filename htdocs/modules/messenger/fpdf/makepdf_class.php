<?php
// $Id: makepdf_class.php,v 1.1.2.4 2004/11/16 01:23:40 phppp Exp $
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
// based on:
// -------------------------------------------------
// St@neCold
// HTML2PDF by Clément Lavoillotte
// ac.lavoillotte@noos.fr
// webmaster@streetpc.tk
// http://www.streetpc.tk


//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['G']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}


////////////////////////////////////

//class PDF extends FPDF
class PDF extends PDF_language
{
	//variables of html parser
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $CENTER='';
	var $ALIGN='';
	var $IMG;
	var $SRC;
	var $WIDTH;
	var $HEIGHT;
	var $fontList;
	var $issetfont;
	var $issetcolor;
	var $iminfo=array(0,0);

	function PDF($orientation='P',$unit='mm',$format='A4')
	{
	    //Call parent constructor
	    $this->PDF_language($orientation,$unit,$format);
	    //Initialization
	    $this->B=0;
	    $this->I=0;
	    $this->U=0;
	    $this->HREF='';
	    $this->CENTER='';
	    $this->ALIGN='';
	    $this->IMG='';
	    $this->SRC='';
	    $this->WIDTH='';
	    $this->HEIGHT='';
	    $this->fontlist=array("arial","times","courier","helvetica","symbol");

	    $this->issetfont=false;
	    $this->issetcolor=false;
	}

	//////////////////////////////////////
	//html parser

	function WriteHTML($html,$scale)
	{
	//    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //remove all unsupported tags
	    $html=str_replace("\n",' ',$html); //replace carriage returns by spaces
	    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //explodes the string
	    foreach($a as $i=>$e)
	    {
	        if($i%2==0)
	        {
	            //Text
	            if($this->HREF)
	                $this->PutLink($this->HREF,$e);
	            elseif($this->IMG)
	                $this->PutImage($this->SRC,$scale);
			    elseif($this->CENTER)
				$this->Cell(0,5,$e,0,1,'C');
			    elseif($this->ALIGN == 'center')
				$this->Cell(0,5,$e,0,1,'C');
			    elseif($this->ALIGN == 'right')
				$this->Cell(0,5,$e,0,1,'R');
			    elseif($this->ALIGN == 'left')
				$this->Cell(0,5,$e,0,1,'L');
	            else
	                $this->Write(5,stripslashes(txtentities($e)));
	        }
	        else
	        {
	            //Tag
	            if($e{0}=='/')
	                $this->CloseTag(strtoupper(substr($e,1)));
	            else
	            {
	                //Extract attributes
	                $a2=explode(' ',$e);
	                $tag=strtoupper(array_shift($a2));
	                $attr=array();
	                foreach($a2 as $v)
	                    if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
	                        $attr[strtoupper($a3[1])]=$a3[2];
	                $this->OpenTag($tag,$attr,$scale);
	            }
	        }
	    }
	}

	function OpenTag($tag,$attr,$scale)
	{
	    //Opening tag
	    switch($tag){
	        case 'STRONG':
	            $this->SetStyle('B',true);
	            break;
	        case 'EM':
	            $this->SetStyle('I',true);
	            break;
	        case 'B':
	        case 'I':
	        case 'U':
	            $this->SetStyle($tag,true);
	            break;
	        case 'A':
	            $this->HREF=$attr['HREF'];
	            break;
		case 'P':
		    $this->ALIGN=$attr['ALIGN'];
		    break;
	        case 'IMG':
		    $this->IMG=$attr['IMG'];
		    $this->SRC=$attr['SRC'];
		    $this->WIDTH=$attr['WIDTH'];
		    $this->HEIGHT=$attr['HEIGHT'];
	            $this->PutImage($attr[SRC],$scale);
	            break;
	        case 'TR':
	        case 'BLOCKQUOTE':
	        case 'BR':
	            $this->Ln(5);
	            break;
		case 'HR':
		    if( $attr['WIDTH'] != '' ) $Width = $attr['WIDTH'];
		    else $Width = $this->w - $this->lMargin-$this->rMargin;
		    $this->Ln(2);
		    $x = $this->GetX();
		    $y = $this->GetY();
		    $this->SetLineWidth(0.4);
		    $this->Line($x,$y,$x+$Width,$y);
		    $this->SetLineWidth(0.2);
		    $this->Ln(2);
		    break;
	        case 'FONT':
	            if (isset($attr['COLOR']) and $attr['COLOR']!='') {
	                $coul=hex2dec($attr['COLOR']);
	                $this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
	                $this->issetcolor=true;
	            }
	            if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist)) {
	                $this->SetFont(strtolower($attr['FACE']));
	                $this->issetfont=true;
	            }
	            break;
	    }
	}

	function CloseTag($tag)
	{
	    //Closing tag
	    if($tag=='STRONG')
	        $tag='B';
	    if($tag=='EM')
	        $tag='I';
	    if($tag=='B' or $tag=='I' or $tag=='U')
	        $this->SetStyle($tag,false);
	    if($tag=='A')
	        $this->HREF='';
	    if($tag=='P')
		$this->ALIGN='';
	    if($tag=='IMG'){
	        $this->IMG='';
	        $this->SRC='';
	        $this->WIDTH='';
	        $this->HEIGHT='';
	    }
	    if($tag=='FONT'){
	        if ($this->issetcolor==true) {
	            $this->SetTextColor(0);
	        }
	        if ($this->issetfont) {
	            $this->SetFont('arial');
	            $this->issetfont=false;
	        }
	    }
	}

	function SetStyle($tag,$enable)
	{
	    //Modify style and select corresponding font
	    $this->$tag+=($enable ? 1 : -1);
	    $style='';
	    foreach(array('B','I','U') as $s)
	        if($this->$s>0)
	            $style.=$s;
	    $this->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

	//put the image in pdf with scaling...
	//width and height-options inside the IMG-Tag are ignored,
	//we get the image info directly from PHP...
	//$scale is the global scaling factor, passing through from WriteHTML()
	//(c)2004/03/12 by St@neCold
	function PutImage($url,$scale)
	{
		if($scale<0) $scale=0;
		//$scale<=0: put NO image inside the pdf!
		if($scale>0){
			$xsflag=0;
			$ysflag=0;
			$yhflag=0;
			$xscale=1;
			$yscale=1;
			//get image info
			$oposy=$this->GetY();
			$url = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $url);
			$iminfo=@getimagesize($url);
			$iw=$scale * px2mm($iminfo[0]);
			$ih=$scale * px2mm($iminfo[1]);
			$iw = ($iw)?$iw:1;
			$ih = ($ih)?$ih:1;
			$nw=$iw;
			$nh=$ih;
			//resizing in x-direction
			$xsflag=0;
			if($iw>150)	{
				$xscale=150 / $iw;
				$yscale=$xscale;
				$nw=$xscale * $iw;
				$nh=$xscale * $ih;
				$xsflag=1;
			}
			//now eventually resizing in y-direction
			$ysflag=0;
			if(($oposy+$nh)>250){
				$yscale=(250-$oposy)/$ih;
				$nw=$yscale * $iw;
				$nh=$yscale * $ih;
				$ysflag=1;
			}
			//uups, if the scaling factor of resized image is < 0.33
			//remark: without(!) the global factor $scale!
			//that's hard -> on the next page please...
			$yhflag=0;
			if($yscale<0.33 and ($xsflag==1 or $ysflag==1))	{
				$nw=$xscale * $iw;
				$nh=$xscale * $ih;
				$ysflag==0;
				$xsflag==1;
				$yhflag=1;
			}
			if($yhflag==1) $this->AddPage();
			$oposy=$this->GetY();
			$this->Image($url, $this->GetX(), $this->GetY(), $nw, $nh);
			$this->SetY($oposy+$nh);
			if($yhflag==0 and $ysflag==1) $this->AddPage();
		}
	}

	function Footer()
	{
		//print footer
		//
		global $pdf_config;

		//date+time
		$printpdfdate = date($pdf_config['dateformat']);
		//Position and Font
		$this->SetXY(25,-25);
		$this->SetTextColor(0,0,255);
		$this->SetFont($pdf_config['font']['footer']['family'],$pdf_config['font']['footer']['style'],$pdf_config['font']['footer']['size']);
		//Link+Page number
		$this->Cell(0,10,$pdf_config['url'],'T',0,'L',0,$pdf_config['url']);
		$pn=$this->PageNo();
		$out=$printpdfdate;
		$out.=' / ';
		$out.=sprintf(MP_PDF_PAGE, $pn);
		$this->SetFont($pdf_config['font']['footer']['family'],$pdf_config['font']['footer']['style'],$pdf_config['font']['footer']['size']);
		$this->Cell(0,10,$out,'T',0,'R',0,$pdf_config['mail']);
	}
}
?>
