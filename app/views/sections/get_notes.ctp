<?

header('Cache-Control: max-age=0');


function time_24($data)
{


    if (strtotime(date("Y-m-d H:i:s")) > strtotime($data) || strtotime(date("Y-m-d H:i:s")) < strtotime("-1 days", strtotime($data))) {

        return false;
    }

    return true;
}

$is_now = time_24(substr($partita['Match']['Data'], 0, strlen("0000-00-00")) . " " . str_replace(".", ":", $partita['Match']['Ora']));

$now = date("d/m/Y H:i:s");
?>
<? $fpdf->setup('P', 'mm', 'A4'); ?>
<? $fpdf->SetMargins('10.0', '5.0'); ?>
<? $fpdf->AddFont('Calibri', '', 'calibri.php'); ?>
<? $fpdf->AddFont('Calibri', 'B', 'calibrib.php'); ?>
<? $fpdf->SetFont('Calibri', '', 10); ?>
<? $fpdf->AddPage(); ?>
<?

//GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - -
$fpdf->SetY(5);
// Seleziona Arial corsivo 8
$fpdf->SetFont('Arial', 'I', 8);
// Stampa il numero di pagina centrato
// $fpdf->Cell(0, 10, 'Page ' . $fpdf->PageNo(), 0, 0, 'C');
$fpdf->Cell(0, 10, sprintf("Nota gara generata il: %s", $now), 0, 0, 'C');
// - - - - - - - - - - - - - - - - - - - - - - - - - - 
?>
<?

//$fpdf->Image(APP . '/webroot/img/pdf/head_note_gara.jpg', 30, 10, 150);
//GIUSEPPE 2022-09-13 - - - - -
if (isset($_GET['playLeague'])) {
    $fpdf->Image(APP . '/webroot/img/pdf/testata-playleague-pdf-alta.jpg', 40, 13, 130);
} else {
    $fpdf->Image(APP . '/webroot/img/pdf/head_note_gara.jpg', 30, 10, 150);
}
// - - - - - - - - - - - - - -

$fpdf->SetY(48);
$fpdf->setX(10);
$fpdf->SetTextColor(0, 0, 0);

$fpdf->SetFont('Calibri', 'B', 19);
$fpdf->Write(5, 'Nota gara: ' . $squadra['Squadre']['Denominazione']);

$deltaY = 5;

$fpdf->SetY(60 - $deltaY);
$fpdf->SetX(10);
$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, 'Campionato/Torneo: ');

$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, $partita['Campionati']['Nome']);

$fpdf->SetY(68 - $deltaY);
$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, 'Partita:');

$fpdf->SetY(68 - $deltaY);
$fpdf->SetX(27);
$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, 'Società ospitante:');

$fpdf->SetX(62);
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, $partita['Match']['CasaNome']);

$fpdf->SetFont('Calibri', 'B', 12);

$fpdf->SetY(74 - $deltaY);
$fpdf->SetX(27);
$fpdf->Write(5, 'Società ospitata:');

$fpdf->SetX(62);
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, $partita['Match']['TrasfertaNome']);

$fpdf->SetY(83 - $deltaY);
$fpdf->SetX(10);

$fpdf->SetFont('Calibri', 'B', 12);

$fpdf->Write(5, 'Data: ');

$fpdf->SetFont('Calibri', '', 12);

$fpdf->Write(5, $partita['Match']['Data_it'] . "  ");

$fpdf->SetFont('Calibri', 'B', 12);

$fpdf->Write(5, '  Ora: ');

$fpdf->SetFont('Calibri', '', 12);

$fpdf->Write(5, $partita['Match']['Ora'] . "  ");

$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, '  Cat.: ');

$fpdf->SetFont('Calibri', '', 12);

$fpdf->Write(5, '____ ');

//GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - -
$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, '  Girone: ');
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, $partita['Half']['Descrizione'] . "  ");

$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, '  Giornata: ');
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, $partita['Match']['Giornata']);
//- - - - - - - - - - - - - - - - - - - - - - - - - -

$fpdf->SetFont('Calibri', 'B', 12);

//GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
$fpdf->SetY(89 - $deltaY);
//$fpdf->SetX(10);
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
$fpdf->Write(5, 'Campo: ');

$fpdf->SetFont('Calibri', '', 12);

$fpdf->Write(5, $partita['Campi']['Descrizione']);

//$espulsi = array();
//$squalificati_new = array();

$blacklist = array();

//GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
$squalificati_tempo = [];
$squalificati_giornata = [];
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
//GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
if (count($squalificati['SqualificatiTempo']) > 0) {
    foreach ($squalificati['SqualificatiTempo'] as $espulso) {
        $squalificati_tempo[] = $espulso['Anagrafica'];
        $blacklist[] = $espulso['Anagrafica'];
    }
}

if (count($squalificati['SqualificatiGiornata']) > 0) {
    foreach ($squalificati['SqualificatiGiornata'] as $espulso) {
        $squalificati_giornata[] = $espulso['Anagrafica'];
        $blacklist[] = $espulso['Anagrafica'];
    }
}
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 


$offset = 0;

//---------------------------------------------------------------------------------------
//$squalificati_tempo = ["Mordenti Niccolò" , "Cenedese Fabio", "Sina Fabio","Mordenti Niccolò" , "Cenedese Fabio", "Sina Fabio","Mordenti Niccolò" , "Cenedese Fabio", "Sina Fabio","Mordenti Niccolò" , "Cenedese Fabio", "Sina Fabio"];
//$squalificati_giornata = ["Mordenti Niccolò" , "Cenedese Fabio", "Sina Fabio"];
//$squalificati_tempo = $squalificati_giornata  ;
//---------------------------------------------------------------------------------------

if (count($squalificati_tempo)) {

    //    $fpdf->SetY(91);
    $fpdf->SetY(98 + $offset - $deltaY);
    $fpdf->SetX(10);
    $offset += 5;
    $fpdf->SetFont('Calibri', 'B', 12);
    $fpdf->Write(5, 'Squalificati a tempo: ');
    $fpdf->SetFont('Calibri', '', 10);
    $fpdf->Write(5, implode(", ", $squalificati_tempo));
}


if (count($squalificati_giornata)) {

    $fpdf->SetY(100 + $offset - $deltaY);
    //   $fpdf->SetY(105);
    $fpdf->SetX(10);
    $offset += 5;
    $fpdf->SetFont('Calibri', 'B', 12);
    $fpdf->Write(5, 'Squalificati giornata corrente: ');
    $fpdf->SetFont('Calibri', '', 10);
    $fpdf->Write(5, implode(", ", $squalificati_giornata));
}



$fpdf->SetY(96 + $offset);
$fpdf->SetX(10);
ob_start();
?>
<table border="0" width="194px" border=1>
    <tr>
        <td style="bold" width="15mm"><b>Riservato <br>all'arbitro</b></td>
        <td style="bold" width="20mm"><b>Numero/<br>Dir/Tec*</b></td>
        <td style="bold" width="60px"><b>Cognome e Nome</b></td>
        <td style="bold" width="13"><b>Data di nascita</b></td>
        <td style="bold" width="5"><b>Luogo di nascita</b></td>
        <td style="bold" width="5"><b>Sesso</b></td>
        <td style="bold" width="5"><b>Cap./<br>v. cap</b></td>
        <td style="bold" width="15"><b>N. tessera/<br>Documento</b></td>
    </tr>
</table>

<?

$t = ob_get_contents();
ob_end_clean();
// $fpdf->htmltable($t);

$fpdf->SetFont('Calibri', 'B', 10);
// $l_1 = 21;
// $l_2 = 20;
// $l_3 = 68;
// $l_4 = 19;
// $l_5 = 18;
// $l_6 = 5;
// $l_7 = 18;
// $l_8 = 29;
$l_1 = 21 - 4;
$l_2 = 20;
$l_3 = 68 - 12;
$l_4 = 19 ;
$l_5 = 18 + 4 + 9 ;
$l_6 = 5;
$l_7 = 18;
$l_8 = 29 - 9;

$x = $fpdf->GetX();
$y = $fpdf->GetY();
$fpdf->MultiCell($l_1, 4, "Riservato all'arbitro", 1, 1);

$fpdf->SetXY($x + $l_1, $y);
$x = $fpdf->GetX();
$fpdf->MultiCell($l_2, 4, "Numero/Dir/Tec*", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_2 + $l_1, $y);
$fpdf->MultiCell($l_3, 8, "Cognome e Nome", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_2 + $l_1 + $l_3, $y);
$fpdf->MultiCell($l_4, 4, "Data  di nascita", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_2 + $l_1 + $l_3 + $l_4, $y);
$fpdf->MultiCell($l_5, 8, "Luogo di nascita", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_2 + $l_1 + $l_3 + $l_4 + $l_5, $y);
$fpdf->MultiCell($l_6, 8, "S", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_2 + $l_1 + $l_3 + $l_4 + $l_5 + $l_6, $y);
$fpdf->MultiCell($l_7, 4, "Cap./ v. cap", 1, 1);

$x = $fpdf->GetX();
$fpdf->SetXY($x + $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6 + $l_7, $y);
$fpdf->MultiCell($l_8, 4, "N. tessera/ Documento", 1, 1);


// $fpdf->Cell($l_4, 8, sprintf("%s/%s/%s", $data_nascita[2], $data_nascita[1], $data_nascita[0]), 1);
// $fpdf->Cell($l_5, 8, "", 1);
// $fpdf->Cell($l_6, 8, "", 1);
 //$fpdf->Ln();

$l_tot = $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6 + $l_7 + $l_8;

$num_righe = 20;
$num_righe_fine_pagina = 30;

//$partecipanti[] = $partecipanti[0];
//if (count($partecipanti) > 0):
$fpdf->SetFont('Calibri', '', 10);

foreach ($partecipanti as $partecipante):

    $data_nascita = explode("-", $partecipante['Athlete']['DataNascita']);

    if (!in_array(trim($partecipante['Athlete']['Cognome'] . " " . $partecipante['Athlete']['Nome']), $blacklist)):

        $citta = $city[$partecipante['Athlete']['CityNascita']]['city_name'];

        $fpdf->Cell($l_1, 5, "", 1);
        $fpdf->Cell($l_2, 5, "", 1);
        $fpdf->Cell($l_3, 5, $partecipante['Athlete']['Cognome'] . " " . $partecipante['Athlete']['Nome'], 1);
        $fpdf->Cell($l_4, 5, sprintf("%s/%s/%s", $data_nascita[2], $data_nascita[1], $data_nascita[0]), 1);
        $fpdf->Cell($l_5, 5, mb_strimwidth(ucwords(strtolower($citta)), 0, 17, ""), 1);
        $fpdf->Cell($l_6, 5, $partecipante['Athlete']['Sesso'][0], 1);
        $fpdf->Cell($l_7, 5, "", 1);
        $fpdf->Cell($l_8, 5, "", 1);

        $fpdf->Ln();

    endif;

endforeach;

$righe_vuote = $num_righe - count($partecipanti); // stampo le righe vuote fino alla fine della pagine

if (count($partecipanti) > 16) {
    $righe_vuote = $num_righe_fine_pagina - count($partecipanti);
}
if (count($partecipanti) > 30) {
    $righe_vuote = 5;
}
if (count($partecipanti) > 50) {
    $righe_vuote = 7;
}

for ($i = 0; $i < $righe_vuote; $i++):

    $fpdf->Cell($l_1, 5, "", 1);
    $fpdf->Cell($l_2, 5, "", 1);
    $fpdf->Cell($l_3, 5, "", 1);
    $fpdf->Cell($l_4, 5, "", 1);
    $fpdf->Cell($l_5, 5, "", 1);
    $fpdf->Cell($l_6, 5, "", 1);
    $fpdf->Cell($l_7, 5, "", 1);
    $fpdf->Cell($l_8, 5, "", 1);
    $fpdf->Ln();

endfor;

//endif;

$fpdf->Cell($l_tot, 5, "*Indicare il numero di maglia del giocatore o segnalare se Dirigente con sigla DIR. o tecnico/allenatore con sigla TEC.", 1);
$fpdf->Ln();

//if ($fpdf->GetY() > 215)
//{
//   $fpdf->AddPage();
//}

$image_H = 53;
$fpdf->Cell($l_tot, $image_H + 1, "", 1); // contenitore dell'immagine

$fpdf->SetX(11);
$fpdf->Image(APP . "webroot/img/pdf/spazio-arbitro-nota-gara_def.jpg", $fpdf->GetX(), $fpdf->GetY() + 0.5, $l_tot - 2, $image_H);
$fpdf->SetX(143);
$fpdf->Cell(40, 15, $squadra['Squadre']['Denominazione']);
$fpdf->SetY($fpdf->GetY() + $image_H + 1.1);
$fpdf->SetFont('Calibri', 'B', 9);
$fpdf->Multicell($l_tot, 4, "Tutti i partecipanti alla gara sono a conoscenza ed accettano i regolamenti sia tecnici sia economici previsti dalla manifestazione. La posizione sanitaria deve essere in regola come previsto dalle norme, in caso differente è a rischio e pericolo del partecipante.", 1);

if ($fpdf->PageNo() % 2 == 1) {
    $fpdf->AddPage();
}

//$fpdf->Ln();
// 
?>
<? $fpdf->Output(Inflector::slug($partita['Match']['CasaNome']) . "-" . Inflector::slug($partita['Match']['TrasfertaNome']) . "-" . Inflector::slug($partita['Match']['Data_it']) . "-$squadra_id.pdf", 'I'); ?>