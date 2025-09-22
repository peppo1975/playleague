<?ob_start();?>
<table border="1">
<tr>
	<td>Cognome</td>
	<td>Nome</td>
	<td>Indirizzo</td>
	<td>Cap</td>
	<td>Localita</td>
	<td>Provincia</td>
	<td>Tel.</td>
	<td>Cel.</td>
	<td>Fax</td>
	<td>Email</td>
</tr>
	<? foreach($data as $annuario): ?>
	
		<tr>
			<td><?=$annuario['Athlete']['Cognome'];?></td>
			<td><?=$annuario['Athlete']['Nome'];?></td>
			<td><?=$annuario['Athlete']['Indirizzo'];?></td>
			<td><?=$annuario['Athlete']['Cap'];?></td>
			<td><?=$annuario['Athlete']['Localita'];?></td>
			<td><?=$annuario['Athlete']['Provincia'];?></td>
			<td><?=$annuario['Athlete']['Telefono'];?></td>
			<td><?=$annuario['Athlete']['Cellulare'];?></td>
			<td><?=$annuario['Athlete']['Fax'];?></td>
			<td><?=$annuario['Athlete']['Email'];?></td>
		</tr>
							
	<? endforeach; ?>
</table>
<?$table = ob_get_clean();?>
<?if($export == 'pdf'):?>
<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddFont('CalibriB','','calibrib.php'); ?>	
<? $fpdf->SetFont('CalibriB','',8); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->output('files/pdf/annuario_responsabili_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/annuario_responsabili_'.date("d_m_Y").'.pdf'));?>
<?else:?>
<?$handle = fopen('files/xls/annuario_responsabili_'.date("d_m_Y").'.xls',"w+");?>
<?fwrite($handle, iconv('utf8', 'cp1252', $table));?>
<?fclose($handle);?>
<?=json_encode(array('link' => 'files/xls/annuario_responsabili_'.date("d_m_Y").'.xls'));?>
<?endif;?>