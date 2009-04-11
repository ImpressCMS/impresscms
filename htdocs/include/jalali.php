<?php
// $Id$  //
//  ------------------------------------------------------------------------ //
//                ImpressCMS - PHP Content Management System                 //
//                    Copyright (c) 2008 impresscms.org                      //
//                       <http://www.impresscms.org/>                        //
//                        <http://www.impresscms.ir/>                        //
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
// This program is distributed in the hope that it will be useful,           //
// but WITHOUT ANY WARRANTY; without even the implied warranty of            //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             //
// GNU General Public License for more details.                              //
//                                                                           //
// The main function which convert Gregorian to Jalali calendars is:         //
// JALAI DATE FUNCTION                                                       //
// this function is simillar than date function in PHP.                      //
// "jalali.php" is convertor to and from Gregorian and Jalali calendars.     //
// Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi                 //
// Copyright (C) jalali Date function by Milad Rastian                       //
// (miladmovie AT yahoo DOT com)                                             //
// Copyright (C) 2003 FARSI PROJECTS GROUP                                   //
// This has been imported to ImpressCMS by stranger @ www.impresscms.ir      //
// I would like to thank irmtfan @ www.jadoogaran.org for his script for     //
// xoops (which is based for this work)                                      //
//  ------------------------------------------------------------------------ //

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
// Defining some variables
define("_EXT_TZhours","0");
define("_EXT_TZminute","0");
// End of Variables

// Begin of using jalali date format
function jdate($type,$maket="now")
{
    global $xoopsConfig;
    $myts =& MyTextSanitizer::getInstance();
	icms_loadLanguageFile('core', 'calendar');
	$result="";
	if($maket=="now"){
		$year=date("Y");
		$month=date("m");
		$day=date("d");
		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
		$maket=jmaketime(date("h")+_EXT_TZhours,date("i")+_EXT_TZminute,date("s"),$jmonth,$jday,$jyear);
	}else{
		$maket+=_EXT_TZhours*3600+_EXT_TZminute*60;
		$date=date("Y-m-d",$maket);
		list( $year, $month, $day ) = preg_split ( '/-/', $date );

		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
		}

	$need= $maket;
	$year=date("Y",$need);
	$month=date("m",$need);
	$day=date("d",$need);
	$i=0;
	while($i<strlen($type))
	{
		$subtype=substr($type,$i,1);
		switch ($subtype)
		{

			case "A":
				$result1=date("a",$need);
				if($result1=="pm") $result.=_CAL_PM_LONG;
				else $result.=_CAL_AM_LONG;
				break;

			case "a":
				$result1=date("a",$need);
				if($result1=="pm") $result.=_CAL_PM;
				else $result.=_CAL_AM;
				break;
			case "d":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				if($jday<10)$result1="0".$jday;
				else 	$result1=$jday;
				$result.=$result1;
				break;
			case "D":
				$result1=date("D",$need);
				if($result1=="Sat") $result1=_CAL_SAT;
				else if($result1=="Sun") $result1=_CAL_SUN;
				else if($result1=="Mon") $result1=_CAL_MON;
				else if($result1=="Tue") $result1=_CAL_TUE;
				else if($result1=="Wed") $result1=_CAL_WED;
				else if($result1=="Thu") $result1=_CAL_THU;
                                else if($result1=="Fri") $result1=_CAL_FRI;
				$result.=$result1;
				break;
			case"F":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result.=monthname($jmonth);
				break;
			case "g":
				$result1=date("g",$need);
				$result.=$result1;
				break;
			case "G":
				$result1=date("G",$need);
				$result.=$result1;
				break;
				case "h":
				$result1=date("h",$need);
				$result.=$result1;
				break;
			case "H":
				$result1=date("H",$need);
				$result.=$result1;
				break;
			case "i":
				$result1=date("i",$need);
				$result.=$result1;
				break;
			case "j":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jday;
				$result.=$result1;
				break;
			case "l":
				$result1=date("l",$need);
				if($result1=="Saturday") $result1=_CAL_SATURDAY;
				else if($result1=="Sunday") $result1=_CAL_SUNDAY;
				else if($result1=="Monday") $result1=_CAL_MONDAY;
				else if($result1=="Tuesday") $result1=_CAL_TUESDAY;
				else if($result1=="Wednesday") $result1=_CAL_WEDNESDAY;
				else if($result1=="Thursday") $result1=_CAL_THURSDAY;
				else if($result1=="Friday") $result1=_CAL_FRIDAY;
				$result.=$result1;
				break;
			case "m":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				if($jmonth<10) $result1="0".$jmonth;
				else	$result1=$jmonth;
				$result.=$result1;
				break;
			case "M":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result.=monthname($jmonth);
				break;
			case "n":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jmonth;
				$result.=$result1;
				break;
			case "s":
				$result1=date("s",$need);
				$result.=$result1;
				break;
			case "S":
				$result.=_CAL_Suffix;
				break;
			case "t":
				$result.=lastday ($month,$day,$year);
				break;
			case "w":
				$result1=date("w",$need);
				$result.=$result1;
				break;
			case "y":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=substr($jyear,2,4);
				$result.=$result1;
				break;
			case "Y":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jyear;
				$result.=$result1;
				break;
			default:
				$result.=$subtype;
		}
	$i++;
	}
	return $result;
}
// End of using jalali date format

function jmaketime($hour,$minute,$second,$jmonth,$jday,$jyear)
{
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, $jday);
	$i=mktime($hour,$minute,$second,$month,$day,$year);
	return $i;
}


// Begin of finding the begining day Of months
function mstart($month,$day,$year)
{
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, "1");
	$timestamp=mktime(0,0,0,$month,$day,$year);
	return date("w",$timestamp);
}
// End of finding the begining day Of months

function lastday ($month,$day,$year)
{
	$lastdayen=date("d",mktime(0,0,0,$month+1,0,$year));
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	$lastdatep=$jday;
	$jday=$jday2;
	while($jday2!="1")
	{
		if($day<$lastdayen)
		{
			$day++;
			list( $jyear, $jmonth, $jday2 ) = gregorian_to_jalali($year, $month, $day);
			if($jdate2=="1") break;
			if($jdate2!="1") $lastdatep++;
		}
		else
		{
			$day=0;
			$month++;
			if($month==13)
			{
					$month="1";
					$year++;
			}
		}

	}
	return $lastdatep-1;
}

// Begin of translating number of months to name of months
function monthname($month)
{

    if($month=="01") return _CAL_Far;

    if($month=="02") return _CAL_Ord;

    if($month=="03") return _CAL_Kho;

    if($month=="04") return _CAL_Tir;

    if($month=="05") return _CAL_Mor;

    if($month=="06") return _CAL_Sha;

    if($month=="07") return _CAL_Meh;

    if($month=="08") return _CAL_Aba;

    if($month=="09") return _CAL_Aza;

    if($month=="10") return _CAL_Dey;

    if($month=="11") return _CAL_Bah;

    if($month=="12") return _CAL_Esf;
}
// End of translating number of months to name of months

function div($a,$b) {
    return (int) ($a / $b);
}

// Begin of functions imported from "jalali.php"
function gregorian_to_jalali ($g_y, $g_m, $g_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);





   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      $g_day_no++;
   $g_day_no += $gd;

   $j_day_no = $g_day_no-79;

   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
   $j_day_no = $j_day_no % 12053;

   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */

   $j_day_no %= 1461;

   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }

   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
      $j_day_no -= $j_days_in_month[$i];
   $jm = $i+1;
   $jd = $j_day_no+1;

   return array($jy, $jm, $jd);
}

function jalali_to_gregorian($j_y, $j_m, $j_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



   $jy = $j_y-979;
   $jm = $j_m-1;
   $jd = $j_d-1;

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $j_days_in_month[$i];

   $j_day_no += $jd;

   $g_day_no = $j_day_no+79;

   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;

   $leap = true;
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      $g_day_no--;
      $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;

      if ($g_day_no >= 365)
         $g_day_no++;
      else
         $leap = false;
   }

   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
   $g_day_no %= 1461;

   if ($g_day_no >= 366) {
      $leap = false;

      $g_day_no--;
      $gy += div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
   }

   for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
   $gm = $i+1;
   $gd = $g_day_no+1;

   return array($gy, $gm, $gd);
}
// End of functions imported from "jalali.php"

?>