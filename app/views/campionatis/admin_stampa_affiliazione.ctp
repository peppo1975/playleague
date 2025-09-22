<?


header('Cache-Control: max-age=0');
?>
<? $fpdf->setup('P', 'mm', 'a4'); ?>
<? $fpdf->SetMargins('10.0', '5.0'); ?>
<? $fpdf->AddFont('Calibri', '', 'calibri.php'); ?>
<? $fpdf->AddFont('Calibri', 'B', 'calibrib.php'); ?>
<? $fpdf->SetFont('Calibri', 'B', 12); ?>
<? // $fpdf->AddPage();                ?>
<?


foreach ($responsabili as $key => $responsabile)
{
    $fpdf->AddPage();
    $fpdf->Image(APP . 'webroot/files/immagini/affiliazione/Affiliazione-BAS_pag-1-new.png', 0, 0, 210);

    $fpdf->SetFont('Calibri', 'B', 15);
    $fpdf->SetXY(76, 37);
    //$fpdf->Cell(10, 10, "X", 0, 1, 'L');

    $fpdf->SetFont('Calibri', 'B', 12);

    $fpdf->SetXY(9, 71);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["Denominazione"])), 0, 1, 'L');

    $fpdf->SetXY(9, 85);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["Indirizzo"])), 0, 1, 'L');

    $fpdf->SetXY(9, 99);
    $fpdf->Cell(10, 10, strtoupper($responsabile["Cap"]), 0, 1, 'L');

    $fpdf->SetXY(73, 99);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["Localita"])), 0, 1, 'L');

    $fpdf->SetXY(136, 99);
    $fpdf->Cell(10, 10, strtoupper($responsabile["Provincia"]), 0, 1, 'L');

    $fpdf->SetXY(9, 113);
    $fpdf->Cell(10, 10, strtoupper($responsabile["Cellulare"]), 0, 1, 'L');

    $fpdf->SetXY(105, 113);
    $fpdf->Cell(10, 10, strtolower($responsabile["Email"]), 0, 1, 'L');

    $fpdf->SetXY(9, 127);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["Nome"] . " " . $responsabile["Cognome"])), 0, 1, 'L');


    $fpdf->SetXY(105, 127);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["CodiceFiscale"])), 0, 1, 'L');

    $fpdf->SetXY(9, 155);
    //$fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["sport"])), 0, 1, 'L');

    $fpdf->SetXY(90, 208);
    $fpdf->Cell(10, 10, iconv('UTF-8', 'CP1252', sprintf("%s/%s", $responsabile["AnnoSportivo"] - 1, $responsabile["AnnoSportivo"])), 0, 1, 'L');

//    $fpdf->SetFont('Calibri', 'B', 15);
//    $fpdf->SetXY(11, 261);
//    $fpdf->Cell(45, 10,"FIRENZE", 0, 1, 'L');

    $fpdf->SetFont('Calibri', 'B', 15);
    $fpdf->SetXY(148, 251);
    $fpdf->Cell(45, 10, iconv('UTF-8', 'CP1252', strtoupper(sprintf("%s %s", $responsabile["Nome"], $responsabile["Cognome"]))), 0, 1, 'C');



    $fpdf->AddPage();
    $fpdf->Image(APP . 'webroot/files/immagini/affiliazione/Affiliazione-BAS_pag-2-new.png', 0, 0, 210);
    $fpdf->SetFont('Calibri', 'B', 11);


    $fpdf->SetXY(20, 21);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', "01/09/2022")), 0, 1, 'L');

    $fpdf->SetXY(7, 27);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', $responsabile["Denominazione"])), 0, 1, 'L');

    $fpdf->SetXY(7, 38);
    $sede_legale = [
        $responsabile["Indirizzo"],
        $responsabile["Cap"] == "" ? "" : "- {$responsabile["Cap"]},",
        $responsabile["Localita"],
        $responsabile["Provincia"] == "" ? "" : "({$responsabile["Provincia"]})"
    ];
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', implode(" ", $sede_legale))), 0, 1, 'L');


    $fpdf->SetXY(7, 70);
    $fpdf->Cell(10, 10, strtoupper(iconv('UTF-8', 'CP1252', strtoupper(sprintf("%s %s", $responsabile["Nome"], $responsabile["Cognome"])))), 0, 1, 'L');

    $fpdf->AddPage();
    $fpdf->Image(APP . 'webroot/files/immagini/affiliazione/Affiliazione-BAS_pag-3.png', 0, 0, 210);
}
?><? $fpdf->Output(); ?>
