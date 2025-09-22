<?ob_start();?>
<table border="0">
	<tr>
		<td>Nominativo</td>
		<td>Importo</td>
	</tr>
	
	<? $totale = 0; ?>
		
	<? foreach($lda as $l) : ?>
	
		<tr>
			<td><?=$l['Arbitro'];?></td>
			<td><?='€ ' . $l['Totale'];?></td>
		</tr>
		
		<? $totale += $l['Totale']; ?>
	
	<? endforeach; ?>
</table>
<?$table = ob_get_clean();?>
<? if($export == 'pdf'):?>
<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddFont('CalibriB','','calibrib.php'); ?>
<? $fpdf->SetFont('CalibriB','',8); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->Cell(10,10,'Riepilogo attività LDA dal ' . date("d/m/Y",strtotime($start_date)) . ' al ' . date("d/m/Y",strtotime($end_date)),'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,15.0,180.0,15.0); ?>
<? $fpdf->Line(9.0,20.0,180.0,20.0); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->Ln(); ?>
<? $fpdf->Line(9.0,$fpdf->getY(),180.0,$fpdf->getY()); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->Cell(10,10,'Totale: € ' . number_format($totale, 2, ',', ''),'C'); ?>
<? $fpdf->Ln(); ?>
<? $text = "Il sottoscritto dichiara, sotto la propria responsabilità, che cone le indennità, rimborsi forfettari, premi e compensi richiesti con la presente, non ha superato il limite di € 10.000,00 di cui all'art. 81 comma 1 lettera m del testo Unico Imposte sui redditi, pertanto su tali somme non deve essere applicata la ritenuta d'imposta o d'acconto prevista. Dichiara inoltre che se al momento dell'effettivo pagamento cambiassero le suddette condizioni, sarà propria cura comunicarlo alla associazione sportiva.";?>
<? $fpdf->MultiCell(180,3,$text,'C'); ?>
<? $fpdf->output('files/pdf/gestione_campionati_lda_generale_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/gestione_campionati_lda_generale_'.date("d_m_Y").'.pdf'));?>
<?else:?>
<?$handle = fopen('files/xls/gestione_campionati_lda_generale_'.date("d_m_Y").'.xls',"w+");?>
<?fwrite($handle, iconv('utf8', 'cp1252', $table));?>
<?fclose($handle);?>
<?=json_encode(array('link' => 'files/xls/gestione_campionati_lda_generale_'.date("d_m_Y").'.xls'));?>
<?endif;?>