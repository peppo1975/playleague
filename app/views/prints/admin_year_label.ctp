<?ob_start();?>
<table>
			<tr>
				<td>Tessera N:</td>
				<td><?=$annuario['Yearbook']['Tessera'];?></td>
			</tr>
			<tr>
				<td>Data di vidimazione:</td>
				<td><?=$annuario['Yearbook']['DataVidimazione_it'];?></td>
			</tr>
			<tr>
				<td>Nome:</td>
				<td><?=$annuario['Athlete']['Nome'];?></td>
			</tr>
			<tr>
				<td>Cognome:</td>
				<td><?=$annuario['Athlete']['Cognome'];?></td>
			</tr>
			<tr>
				<td>Data di nascita:</td>
				<td><?=$annuario['Athlete']['DataNascita_it'];?></td>
			</tr>
			<tr>
				<td>Società:</td>
				<td><?=$annuario['Yearbook']['NomeSquadra'];?></td>
			</tr>
</table>
<?$table = ob_get_clean();?>
<?$dim_page=array(60,70.9);?>
<? $fpdf->setup('L','mm',$dim_page);?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
	<? $fpdf->AddFont('Calibri','','calibri.php'); ?>	
	<? $fpdf->SetFont('Calibri','',8); ?>
<? $fpdf->SetFontSize(12.0); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->output('etichetta_annuario_'.$annuario['Athlete']['Cognome'].'_'.$annuario['Athlete']['Nome'].'.pdf', 'D'); ?>