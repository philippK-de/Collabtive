<?php
/*
 * Implements PDF exports
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name MYPDF
 * @version 1.0
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class MYPDF extends TCPDF {
    // String for the header
    private $headerName;
    // cellFill is expected to be an array containing a RGB triplet.
    private $cellFill;

    private $headerMargin = 20;

    public function setup($headerName = "", array $cellFill = array())
    {
        // TCPDF boilerplate setup
        $this->SetMargins(15, $this->headerMargin, 15);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetFont('freeserif', "", 11);

        $this->SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $this->getAliasNbPages();
        $this->AddPage();
        // Set string for display above table
        $this->headerName = $headerName;
        // Set colored fill value for rows. cellFill is expected to be an array containing a RGB triplet. Default alternate is white.
        $this->cellFill = $cellFill;
        $this->Header();
    }
    public function Header()
    {
        // If header name is set print it out
        if ($this->headerName) {
            // make it big and bold
            $this->setFontSize(22);
            $this->SetFont('', 'B');
            $this->Cell(0, 0, $this->headerName, 0, 1, "L", 0);
            $this->headerMargin = $this->GetY() + 10;
            // $this->Cell(0, 0, "", 0, 1, "L", 0);
        }
    }
    public function table($header, $data)
    {
        // font restoration
        $this->setFontSize(12);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Calculate Headers
        $num_headers = count($header);
        $awidth = floor(180 / $num_headers);
        for($i = 0; $i < $num_headers; $i++) {
            if ($i > 0) {
                $this->Cell($awidth, 7, $header[$i], 1, 0, 'C', 1, "", 1);
            } else {
                $this->Cell($awidth + 5, 7, $header[$i], 1, 0, 'C', 1, "", 1);
            }
        }
        $this->Ln();
        // Color and font restoration
        if (!empty($this->cellFill)) {
            $this->SetFillColor($this->cellFill[0], $this->cellFill[1], $this->cellFill[2]);
        } else {
            $this->SetFillColor(224, 235, 255);
        }
        $this->SetFont('');
        $doFill = false;
        // Loop through data array and for each line, draw cells according to the header count
        foreach($data as $row) {
            for($i = 0;$i < $num_headers;$i++) {
                if ($i > 0) {
                    $this->Cell($awidth, 6, $row[$i], 1, 0, 'LR', $doFill, "", 1);
                } else {
                    $this->Cell($awidth + 5, 6, $row[$i], 1, 0, 'LR', $doFill, "", 1);
                }
            }
            // Reverse the value of dofill
            $doFill = !$doFill;
            $this->Ln();
        }

        $this->Cell(180, 0, '', 'T');
    }
}

?>