<?
// makepdf_config.php,v1.1 2004/03/12 (c) by St@neCold
// ---------------------------------------------------
// For XOOPS2 - PHP Content Management System
//
//(c) 2004/03/12 by St@neCold, stonecold@csgui.de

//edit all these global variables to your's!

//the fpdf-creator-string
$xcreator=('FPDF v1.52');

//your homepage-url
$xurl=XOOPS_URL;

//your contact email
$xmail=('mailto:'.$xoopsConfig['adminmail']);

//the slogan of your site
$xslogan=xoops_substr($myts->makeTboxData4Show( $xoopsConfig['slogan'] ),0,20);


//your logo name, located in .../makepdf/ (if you wish)
//must be a png (best, recommended!), gif (only with gif.php!, slow) or jpg (good)
//recommended size: 320x60px, if you'll try other dimensions you must edit the
//appropriate line with ->Image($xlogo,...) at the end in makepdf.php!
$xlogo=('../../images/logo.png');

//the global scaling factor for images if the HTTP-Var $scale is not passed
//to the script 'makepdf.php'
$xscale=('0.8');

//footer() in makepdf_class.php
//the date format string

$xdformat=(_DATESTRING);
//the string for time and pagenumber
$xsdatepage=_MD_PDF_PAGE;

//remarks at bottom of last page
//the opening string
$xsopen=(_MD_POSTEDON);
//the author string
$xsauthor=_POSTEDBY.": ";

?>