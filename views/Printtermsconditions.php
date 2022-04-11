<?php

namespace PHPMaker2022\civichub2;

// Page object
$Printtermsconditions = &$Page;
?>
<?php
global $Language;
ob_end_clean();
$val = "";
if (!empty($val)) {
	$taccontent = $val;
} else {
	$taccontent = $Language->phrase('TermsConditionsNotAvailable');
}
if ($Language->phrase("dir")=="rtl") {
	ob_end_clean();
	require_once('plugins/tcpdf/tcpdf.php');
	$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetHeaderData('', 0, Language()->ProjectPhrase("BodyTitle")." - ".Language()->phrase("TermsConditionsTitle"), '');// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, 18, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$lg = Array();
	$lg['a_meta_charset'] = 'UTF-8';
	$lg['a_meta_dir'] = 'rtl';
	$lg['a_meta_language'] = 'fa';
	$lg['w_page'] = 'page';
	$pdf->setLanguageArray($lg);
	$pdf->AddPage();
	$pdf->setRTL(true);
	$pdf->SetFont('freeserif', '', 18); // for italic style, use: aefurat
	$pdf->WriteHTML($taccontent, true, 0, true, 0);
	$pdf->Ln();
	$pdf->SetFont('aealarabiya', '', 18);
	$pdf->Output(str_replace(" ", "_", Language()->ProjectPhrase("BodyTitle"))."_Terms_And_Conditions.pdf", 'I');
} else {
	ob_end_clean();
	require('plugins/fpdf/html2pdf.php');
	$pdf_html = new \PDF_HTML();
	$pdf_html->AddPage();
	$pdf_html->SetFont("Arial");
	$pdf_html->WriteHTML("<strong>".Language()->ProjectPhrase("BodyTitle")." - ".Language()->phrase("TermsConditionsTitle")."</strong><br><br>".$taccontent);
	$pdf_html->Output(str_replace(" ", "_", Language()->ProjectPhrase("BodyTitle"))."_Terms_And_Conditions.pdf", "D");
}	
?>
<?php
echo GetDebugMessage();
?>
