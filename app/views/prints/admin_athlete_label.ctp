<?ob_start();?>
<table>
			<tr>
				<td style="bold"><?=$atleta['Athlete']['reverseAnagrafica'];?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=$atleta['Athlete']['Indirizzo'];?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=$atleta['Athlete']['Cap'] . ' ' . $atleta['Athlete']['Localita'] . ' ' . $atleta['Athlete']['Provincia'];?></td>
				<td>&nbsp;</td>
			</tr>

</table>
<?$table = ob_get_clean();?>
<?$dim_page=array(30,70.9);?>
<? $fpdf->setup('L','mm',$dim_page); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->AddFont('Calibri','','calibri.php'); ?>
<? $fpdf->SetFont('Calibri','',8); ?>
<? $fpdf->SetFontSize(12.0); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->output('etichetta_anagrafica_'.Inflector::Slug($atleta['Athlete']['reverseAnagrafica'],'_').'.pdf', 'D'); ?>