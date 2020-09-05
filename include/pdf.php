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

use Dompdf\Dompdf;

/**
 * Generates a pdf file
 *
 * @param string $content The content to put in the PDF file
 * @param string $doc_title The title for the PDF file
 * @param string $doc_keywords The keywords to put in the PDF file
 * @return string Generated output
 */
function Generate_PDF($content, $doc_title, $doc_keywords) {

	$pdf = new Dompdf([
		'isHtml5ParserEnabled' => true,
	]);
	$pdf
		->setPaper(
			'A4',
			'portrait'
		);
	$content = <<<EOF
<!DOCTYPE html>
<html>
	<head>
		<title>$doc_title</title>
		<meta name="author" content="ImpressCMS">
  		<meta name="keywords" content="{htmlentities($doc_keywords)}">
  		<meta name="description" content="{htmlentities($doc_title)}">
  		<style type="text/css">
  			body {
				margin-top: 27mm;
				margin-bottom: 25mm;
				margin-left: 15mm;
				margin-right: 15mm;
  			}
		</style>
	</head>
	<body>
		$content
	</body>
</html>
EOF;

	$pdf->loadHtml($content, 'UTF-8');
	$pdf->render();

	return $pdf->output();
}
