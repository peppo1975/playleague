<?ob_start();?>
<table border="0">
	<tr>
		<td>Data</td>
		<td width="11" align="center">Ora</td>
		<td>Casa</td>
		<td>Trasferta</td>
		<td>Campo</td>
		<td>Importo</td>
		<td>Descrizione</td>
	</tr>
	
	<? $importo_totale = 0; ?>
	
	<? foreach($lda as $l) : ?>
	
		<? $importo = 0; ?>
	
		<? if($l['Lda']['Arbitro'] == $athlete_id): ?>
		
				<? $importo += $l['Lda']['ImportoArbitro'];   ?>
				
		<? endif; ?>
			
		<? if($l['Lda']['Arbitro2'] == $athlete_id):  ?>
			
				<? $importo += $l['Lda']['ImportoArbitro2'];  ?>
				
		<? endif; ?>
				
		<? if($l['Lda']['Delegato'] == $athlete_id):  ?>
			
				<? $importo += $l['Lda']['ImportoDelegato'];  ?>
				
		<? endif; ?>
				
		<? if($l['Lda']['DelegatoA'] == $athlete_id): ?>
			
				<? $importo += $l['Lda']['ImportoDelegatoA']; ?>
		
		<? endif; ?>
	
		<tr>
			<td><?=$l['Lda']['Data_it'];?></td>
			<td width="11" align="center"><?=$l['Lda']['Ora'];?></td>
			<td><?=$l['Lda']['CasaNome'];?></td>
			<td><?=$l['Lda']['TrasfertaNome'];?></td>
			<td><?=$l['Lda']['CampoNome'];?></td>
			<td><?='€ ' . $importo;?></td>	
			<td>&nbsp;</td>
		</tr>
		
		<? $importo_totale += $importo; ?>
	
	<? endforeach; ?>
	
	<? foreach($altreSpese as $l): ?>
	
	<?
	
	$importo = $l['AthleteExpense']['Importo'];
	
	?>
	
		<tr>
			<td><?=$l['AthleteExpense']['Data_it'];?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?='€ ' . $l['AthleteExpense']['Importo'];?></td>
			<td><?=$l['AthleteExpense']['Descrizione'];?></td>			
		</tr>	
		
		<? $importo_totale += $importo; ?>
		
		<?// if($importo >= 0): ?>
		
		<?// endif; ?>
	
	<? endforeach; ?>
	
</table>
<?$table = ob_get_clean();?>
<? if($export == 'pdf'): ?>
<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->AddFont('Calibri','','calibri.php'); ?>	
<? $fpdf->SetFont('Calibri','',8); ?>
<? $fpdf->SetFontSize(12.0); ?>
<? $fpdf->Cell(180,10,'Riepilogo attività Sig. ' . $athlete['Athlete']['reverseAnagrafica'] . ' dal ' . date("d/m/Y",strtotime($start_date)) . ' al ' . date("d/m/Y",strtotime($end_date)),0,0,'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,15.0,180.0,15.0); ?>
<? $fpdf->Line(9.0,19.0,180.0,19.0); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->Ln(); ?>
<? $fpdf->Line(9.0,$fpdf->getY(),180.0,$fpdf->getY()); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->Cell(145);?>
<? $fpdf->Cell(10,10,'Totale: € ' . number_format($importo_totale, 2, ',', '')); ?>
<? $fpdf->Ln(); ?>
<? $text = "Il sottoscritto dichiara, sotto la propria responsabilità, che cone le indennità, rimborsi forfettari, premi e compensi richiesti con la presente, non ha superato il limite di € 10.000,00 di cui all'art. 81 comma 1 lettera m del testo Unico Imposte sui redditi, pertanto su tali somme non deve essere applicata la ritenuta d'imposta o d'acconto prevista. Dichiara inoltre che se al momento dell'effettivo pagamento cambiassero le suddette condizioni, sarà propria cura comunicarlo alla associazione sportiva.";?>
<? $fpdf->MultiCell(180,3,$text,'C'); ?>
<? $fpdf->Cell(20,10,'Firma',0,0,'L'); ?>
<? $fpdf->output('files/pdf/gestione_campionati_lda_singolo_'.Inflector::Slug($athlete['Athlete']['reverseAnagrafica']).'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/gestione_campionati_lda_singolo_'.Inflector::Slug($athlete['Athlete']['reverseAnagrafica']).'.pdf'));?>
<?else:?>
<?$handle = fopen('files/xls/gestione_campionati_lda_singolo_'.Inflector::Slug($athlete['Athlete']['reverseAnagrafica']).'.xls',"w+");?>
<?fwrite($handle, iconv('utf8', 'cp1252', $table));?>
<?fclose($handle);?>
<?=json_encode(array('link' => 'files/xls/gestione_campionati_lda_singolo_'.Inflector::Slug($athlete['Athlete']['reverseAnagrafica']).'.xls'));?>
<?endif;?>