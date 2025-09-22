<?


header('Cache-Control: max-age=0');


//function time_24($data)
//{
//
//
//    if (strtotime(date("Y-m-d H:i:s")) > strtotime($data) || strtotime(date("Y-m-d H:i:s")) < strtotime("-1 days", strtotime($data)))
//    {
//
//        return false;
//    }
//
//    return true;
//}
//
//
//$is_now = time_24(substr($partita['Match']['Data'], 0, strlen("0000-00-00")) . " " . str_replace(".", ":", $partita['Match']['Ora']));
?>
<? $fpdf->setup('P', 'mm', 'a4'); ?>
<? $fpdf->SetMargins('10.0', '5.0'); ?>
<? $fpdf->AddFont('Calibri', '', 'calibri.php'); ?>
<? $fpdf->AddFont('Calibri', 'B', 'calibrib.php'); ?>
<? $fpdf->SetFont('Calibri', 'B', 10); ?>
<? // $fpdf->AddPage();         ?>
<?


if (count($view) > 0):

    foreach ($view as $key => $squadra):


        //pagina 1
        $fpdf->AddPage();

        $fpdf->Image(APP . 'webroot/files/immagini/liberatoria/liberatoria squadra 2022-2023_Pagina_1.png', 0, 0, 210);

        $fpdf->SetXY(65, 53);
        $fpdf->Cell(100, 10, $squadra['NomeSquadra'], 0, 1, 'L');


        //pagina 2
        $fpdf->AddPage();

        $fpdf->Image(APP . 'webroot/files/immagini/liberatoria/liberatoria squadra 2022-2023_Pagina_2.png', 0, 0, 210);


        //pagina 3
//        $fpdf->AddPage();
//        $fpdf->Image(APP . 'webroot/files/immagini/liberatoria/liberatoria squadra 2022-2023_Pagina_3.png', 0, 0, 210);
//        $fpdf->SetXY(30, 17);
//        $fpdf->Cell(100, 10, $squadra['NomeSquadra'], 0, 1, 'L');
//        $fpdf->SetXY(145, 17);
//        $fpdf->Cell(50, 10, sprintf("%s\%s\%s", date("d"), date("m"), date("Y")), 0, 1, 'L');
//
//
////        $fpdf->SetXY(0, 60);
//        $fpdf->SetY(61);
//        $h = 8.06;

        $index = 0;
        foreach ($squadra['Atleti'] as $key_atleta => $atleta):
            if ($index % 25 == 0)
            {
                $fpdf->AddPage();
                $fpdf->Image(APP . 'webroot/files/immagini/liberatoria/liberatoria squadra 2022-2023_Pagina_3.png', 0, 0, 210);
                $fpdf->SetXY(30, 17);
                $fpdf->Cell(100, 10, $squadra['NomeSquadra'], 0, 1, 'L');
                $fpdf->SetXY(145, 17);
                $fpdf->Cell(50, 10, sprintf("%s\%s\%s", date("d"), date("m"), date("Y")), 0, 1, 'L');


//        $fpdf->SetXY(0, 60);
                $fpdf->SetY(61);
                $h = 8.06;
            }
            $index++;
            $fpdf->SetX(35);
            $fpdf->Cell(50, $h, $atleta['Anagrafica'], 0, 1, 'L');

            $fpdf->SetY($fpdf->GetY() - $h);
            $fpdf->SetX(108);
            $fpdf->Cell(50, $h, $atleta['DataNascita'], 0, 1, 'L');
        endforeach;


        //pagina 4
        $fpdf->AddPage();
        $fpdf->Image(APP . 'webroot/files/immagini/liberatoria/liberatoria squadra 2022-2023_Pagina_4.png', 0, 0, 210);
        $fpdf->SetXY(30, 21);
        $fpdf->Cell(100, 10, $squadra['NomeSquadra'], 0, 1, 'L');
        $fpdf->SetXY(145, 21);
        $fpdf->Cell(50, 10, sprintf("%s\%s\%s", date("d"), date("m"), date("Y")), 0, 1, 'L');

    endforeach;

endif;
?>
<? //  $fpdf->htmltable($table);   ?>
<? // $fpdf->SetY($fpdf->GetY() + 10);   ?>
<?


// $fpdf->SetFont('Calibri', 'B', 12);
// $fpdf->SetX(110);
// $fpdf->Write(5, 'Firma responsabile:');
?>
<? $fpdf->Output(); ?>
