<?
$fpdf->setup('P','mm','a4');
$fpdf->SetMargins('10','5');
$i = 0;
$j = 1;
$offsetX = 0;
$offsetY = 0;
?>
	
<?foreach($athletes as $atleta):?>
<?
	$r = $i%36;
	
	if($r == 0) {

		$fpdf->AddPage();
		//$fpdf->AddFont('Calibri','','calibri.php');
		$fpdf->SetFont('Helvetica','',8);
		$offsetX = 0;
		$offsetY = 0;
		$j = 1;
	
	}

	if($j > 3) {
	
		$j = 1;
		$offsetX = 0;
		$offsetY += 25.2;
		
	}
	
?>
<?ob_start();?>
<table width="68" height="24.7">
			<?if($atleta['Athlete']['reverseAnagrafica'] != ''):?>
			<tr>
				<td style="bold"><?=$atleta['Athlete']['reverseAnagrafica'];?></td>
				<td>&nbsp;</td>
			</tr>
			<?endif;?>
			<?if($atleta['Athlete']['Indirizzo'] != ''):?>
			<tr>
				<td><?=$atleta['Athlete']['Indirizzo'];?></td>
				<td>&nbsp;</td>
			</tr>
			<?endif;?>
			<?if($atleta['Athlete']['Cap'] != '' || $atleta['Athlete']['Localita'] != '' || $atleta['Athlete']['Provincia'] != ''):?>
			<tr>
				<td><?=$atleta['Athlete']['Cap'] . ' ' . $atleta['Athlete']['Localita'] . ' ' . $atleta['Athlete']['Provincia'];?></td>
				<td>&nbsp;</td>
			</tr>
			<?endif;?>
</table>
<?
$table = ob_get_clean();

$fpdf->SetY($offsetY);
$fpdf->SetX($offsetX);
$fpdf->htmltable($table);
$i++;
$j++;
$offsetX += 73;

?>
<?endforeach;?>
<? $fpdf->output('files/pdf/etichette_anagrafica_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => '/files/pdf/etichette_anagrafica_'.date("d_m_Y").'.pdf'));?>