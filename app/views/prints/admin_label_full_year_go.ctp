<?
$fpdf->setup('P','mm','a4');
$fpdf->SetMargins('5','5','5');
$i = 0;
$j = 1;
$k = 1;
$offsetX = 0;
$offsetY = 0;
?>
	
<?foreach($athletes as $annuario):?>
<?
	$r = $i%32;
	
	if($r == 0) {

		$fpdf->AddPage();
		$fpdf->AddFont('Calibri','','calibri.php');
		$fpdf->SetFont('Calibri','',8);
		$offsetX = 0;
		$offsetY = 0;
		$j = 1;
		$k = 1;
	
	}

	if($j > 4) {
	
		$j = 1;
		$offsetX = 0;
		
		if($k == 1) {
		
			$offsetY += 41;
		
		} elseif($k == 3 || $k == 4) {
		
			$offsetY += 35;
		
		} elseif($k == 7) {
		
			$offsetY += 35.5;
		
		} else $offsetY += 37;
		
		$k++;
		
	}
	
?>
<?ob_start();?>
<table width="50" height="36">
			<tr>
				<td nowrap>Tessera N: <?=$annuario['Yearbook']['Tessera'];?></td>
			</tr>
			<tr>
				<td nowrap>Data vidimazione: <?=$annuario['Yearbook']['DataVidimazione_it'];?></td>
			</tr>
			<tr>
				<td nowrap>Nome: <?=$annuario['Athlete']['Nome'];?></td>
			</tr>
			<tr>
				<td nowrap>Cognome: <?=$annuario['Athlete']['Cognome'];?></td>
			</tr>
			<tr>
				<td nowrap>Data di nascita: <?=$annuario['Athlete']['DataNascita_it'];?></td>
			</tr>
			<tr>
				<td nowrap>Società: <?=$annuario['Yearbook']['NomeSquadra'];?></td>
			</tr>
</table>
<?
$table = ob_get_clean();

$fpdf->SetY($offsetY);
$fpdf->SetX($offsetX);
$fpdf->htmltable($table);
$i++;
$j++;

if($j == 1) {

$offsetX += 58;

} else if($j == 3) {

$offsetX += 50;

} else $offsetX += 55;

?>
<?endforeach;?>
<?$fpdf->output('files/pdf/etichette_annuario_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => '/files/pdf/etichette_annuario_'.date("d_m_Y").'.pdf'));?>