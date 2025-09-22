<?
//GIUSEPPE 2018-05-04 da modificare
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
//
//}
//$is_now = time_24(substr($partita['Match']['Data'], 0, strlen("0000-00-00")) . " " . str_replace(".", ":", $partita['Match']['Ora']));
?>
<? $fpdf->setup('P', 'mm', 'a4'); ?>
<? $fpdf->SetMargins('10.0', '5.0'); ?>
<? $fpdf->AddFont('Calibri', '', 'calibri.php'); ?>
<? $fpdf->AddFont('Calibri', 'B', 'calibrib.php'); ?>
<? $fpdf->SetFont('Calibri', '', 10); ?>
<? $fpdf->AddPage(); ?>
<?
$fpdf->Image(APP . '/webroot/img/pdf/head_ricevuta_tesserati.jpg', 20, 10, 150);

$fpdf->SetY(40);
$fpdf->setX(100);
$fpdf->SetTextColor(0, 0, 0);

//$fpdf->Write(5, 'NOTE GARA - ' . $squadra['Squadre']['Denominazione']);

$fpdf->SetY(50);
$fpdf->SetX(20);

$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, 'La squadra: ');

$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, $squadra);


$fpdf->SetY(60);
$fpdf->SetX(20);

$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, 'partecipante alla manifestazione: ');

$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, $manifestazione);


//$fpdf->SetY(60);
//$fpdf->SetX(25);
//$fpdf->SetFont('Calibri', 'B', 12);
//$fpdf->Write(5, 'Società ospitante:');
//$fpdf->SetX(60);
//
//$fpdf->SetFont('Calibri', '', 12);
//$fpdf->Write(5, $partita['Match']['CasaNome']);
//
//$fpdf->SetFont('Calibri', 'B', 12);
//
//$fpdf->SetY(70);
//$fpdf->SetX(25);

$fpdf->SetY(70);
$fpdf->SetX(20);

$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, 'tessera in data: ');

$fpdf->SetFont('Calibri', 'B', 12);
$fpdf->Write(5, $vidimazione);

$fpdf->SetY(80);
$fpdf->SetX(20);

$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, "i seguenti giocatori: ");








$fpdf->SetY(93);
$fpdf->SetX(20);

ob_start();

//$fpdf->SetY(90);
$fpdf->SetFont('Calibri', '', 12);
//
?>

<? $totEuro = 0; ?>
<? $sizeText = ""; ?>

<table border="0" width="98%" >
    <tr>
        <td style="bold"><b>Cognome e Nome</b></td>
        <td style="bold"><b>Nato il</b></td>
        <td style="bold"><b>Assicurazione</b></td>
        <td style="bold"><b>€</b></td>
        <td style="bold"><b>Tessera</b></td>

    </tr>

    <tr>
        <td style="bold"><b>&nbsp;</b></td>
        <td style="bold"><b>&nbsp;</b></td>
        <td style="bold"><b>&nbsp;</b></td>
        <td style="bold"><b>&nbsp;</b></td>
        <td style="bold"><b>&nbsp;</b></td>

    </tr>

<? if (count($partecipanti) > 0): ?>

    <? foreach ($partecipanti as $i => $partecipante): ?>
        <? $index = $i + 1; ?>
            <? $totEuro += $partecipante['costo']; ?>
            <? // if (!in_array(trim($partecipante['Athlete']['Cognome'] . " " . $partecipante['Athlete']['Nome']), $blacklist)): ?>
            <tr>

                <td nowrap>
                    <font size="<?= $sizeText ?>">   <?= $partecipante['atleta']; ?></font>
                </td>
                <td>
                    <font size="<?= $sizeText ?>">   <?= $partecipante['nato'] ?></font>
                </td>
                <td >
                    <font size="<?= $sizeText ?>">  <?= $partecipante['assicurazione'] ?></font>
                </td>
                <td >
                    <font size="<?= $sizeText ?>">   <?= $partecipante['costo'] ?></font>
                </td>
                <td>
                    <font size="<?= $sizeText ?>">  <?= $partecipante['tessera'] ?></font>
                </td>
            </tr> 

        <? // endif;  ?>
    <? endforeach; ?>
    <? $totEuro = number_format($totEuro, 2) ?>
<? else: ?>



    <? endif; ?>

<!--    <tr>
<td colspan="2" style="bold" width="85"><b>Dirigente Responsabile</b></td>
<td colspan="2" >&nbsp;</td>
</tr>
<tr>
<td colspan="2" style="bold" width="85"><b>Allenatore</b></td>
<td colspan="2" >&nbsp;</td>
</tr>
<tr>
<td colspan="2" style="bold" width="85"><b>Accompagnatore</b></td>
<td colspan="2" >&nbsp;</td>
</tr>-->

</table>
<?
$table = ob_get_contents();
//ob_end_clean();
?>
<? $fpdf->htmltable($table); ?>
<? $fpdf->SetY($fpdf->GetY() + 10); ?>
<?
$fpdf->SetX(20);
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, 'Importo totale: ');

$fpdf->SetFont('Calibri', 'B', 14);
$fpdf->Write(5, '€ ' . $totEuro);

$fpdf->SetY($fpdf->GetY() + 8);
$fpdf->SetX(20);
$fpdf->SetFont('Calibri', '', 12);
$fpdf->Write(5, 'Totale iscritti: ');

$fpdf->SetFont('Calibri', 'B', 14);
$fpdf->Write(5, count($partecipanti));

$fpdf->SetY($fpdf->GetY() + 15);
//$fpdf->SetX(20);

$info = "Il presente tesseramento avrà validità per tutta la stagione sportiva in corso, che avrà termine il 31/08. Si ricorda di informarsi sulle condizioni assicurative legate al tesseramento scelto. Tali informazioni sono sempre disponibili in sede o sul sito ufficiale www.midlandsport.it";
$fpdf->SetFont('Calibri', '', 9);
$fpdf->Write(5, $info);
?>

<?
//$table = ob_get_contents();
ob_end_clean();
//$fpdf->htmltable($table);
?>
<? $fpdf->Output("ee.pdf", 'I'); ?>
