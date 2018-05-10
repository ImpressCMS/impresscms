<?php
/**
 * PDF generator
 *
 * System tool that allow create PDF's within ImpressCMS core
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	1.1
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

/**
 * Generates a pdf file
 *
 * @param string $content	The content to put in the PDF file
 * @param string $doc_title	The title for the PDF file
 * @param string $doc_keywords	The keywords to put in the PDF file
 * @return string Generated output by the pdf (@link TCPDF) class
 */
function Generate_PDF($content, $doc_title, $doc_keywords) {
	global $icmsConfig;

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(PDF_AUTHOR);
	$pdf->SetTitle($doc_title);
	$pdf->SetSubject($doc_title);
	$pdf->SetKeywords($doc_keywords);
	$sitename = $icmsConfig['sitename'];
	$siteslogan = $icmsConfig['slogan'];
	$pdfheader = icms_core_DataFilter::undoHtmlSpecialChars($sitename . ' - ' . $siteslogan);
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $pdfheader, ICMS_URL);

	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	//set auto page breaks
	$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

	$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	$reflector = new ReflectionClass(TCPDF::class);
	$base_path = dirname($reflector->getFileName());
	$fonts_path = $base_path . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR;
	$lang_path = $base_path . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;

	$lng = 'eng';
	$long_lang = strtolower($icmsConfig['language']);
	foreach (\ISO639\Languages::$languages as $i => $lang_info) {
		if (strtolower($lang_info[3]) == $long_lang) {
			$lng = $lang_info[0];
			break;
		}
	}

	global $l;
	if (file_exists($file = ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $icmsConfig['language'] . DIRECTORY_SEPARATOR . 'pdf.php')) {
		require $file;
	} elseif (file_exists($file = $lang_path . $lng . '.php')) {
		require $file;
	} else {
		require $lang_path . 'eng.php';
	}
	$pdf->setLanguageArray($l); //set language items
	// set font
	$TextFont = (defined('_PDF_LOCAL_FONT') && !empty(_PDF_LOCAL_FONT) && file_exists($fonts_path . _PDF_LOCAL_FONT . '.php'))? _PDF_LOCAL_FONT : 'dejavusans';
	$pdf->SetFont($TextFont);

	//initialize document
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->writeHTML($content, true, 0);

	return $pdf->Output();
}
