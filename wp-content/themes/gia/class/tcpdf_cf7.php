<?php
class CREATE_PDFCF7{
    function CREATE_PDFCF7Fn($params, $savepath){
        // include the main TCPDF library
        require_once('tcpdf/config/tcpdf_config.php');
        require_once('tcpdf/tcpdf.php');
        // create new pdf document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, A3, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Web Quotation Form');
        $pdf->SetSubject('Web Quotation Form');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetHeaderMargin(0);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // add a page
        $pdf->AddPage();
        //content to print
        $html = get_field('pdf_template', 'option');
        // replace params
        foreach ($params as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        // print text
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $filename = rand().'_'.time().'.pdf';
        $pdf->Output($savepath.$filename,'F');
        
        //thank you page
        $_SESSION['submission_pdf'] = $html;
        return $filename;
    }
}