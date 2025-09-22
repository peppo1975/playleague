<? if($export == 'pdf'): ?>

<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>

<?foreach($tot_lda as $athlete_id => $lda):?>
<? $fpdf->AddPage(); ?>
<? $fpdf->AddFont('Calibri','','calibri.php'); ?>	
<? $fpdf->SetFont('Calibri','',8); ?>
<? $fpdf->SetFontSize(12.0); ?>
<?$athlete = $this->requestAction('/admin/prints/getAthlete/'.$athlete_id);?>
<? $fpdf->Cell(180,10,'Riepilogo attività Sig. ' . $athlete['Athlete']['reverseAnagrafica'] . ' dal ' . date("d/m/Y",strtotime($start_date)) . ' al ' . date("d/m/Y",strtotime($end_date)),0,0,'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,15.0,180.0,15.0); ?>
<? $fpdf->Line(9.0,19.0,180.0,19.0); ?>
<? $fpdf->Ln(); ?>

<?ob_start();?>
<table border="0">
	<tr>
		<td>Data</td>
		<td>Ora</td>
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
			<td width="20"><?=$l['Lda']['Data_it'];?></td>
			<td width="15"><?=$l['Lda']['Ora'];?></td>
			<td><?=$l['Lda']['CasaNome'];?></td>
			<td><?=$l['Lda']['TrasfertaNome'];?></td>
			<td><?=$l['Lda']['CampoNome'];?></td>
			<td><?='€ ' . $importo;?></td>
			<td>&nbsp;</td>			
		</tr>
		
		<? $importo_totale += $importo; ?>
	
	<? endforeach; ?>
	
	<? foreach($altreSpese[$athlete_id] as $l): ?>
	
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
	
	<? endforeach; ?>
	
</table>
<? $table = ob_get_contents();?>
<? ob_end_clean();?>

<? $fpdf->htmltable($table);?>

<? $fpdf->Ln(); ?>
<? $fpdf->Line(9.0,$fpdf->getY(),180.0,$fpdf->getY()); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->Cell(145);?>
<? $fpdf->Cell(10,10,'Totale: € ' . number_format($importo_totale, 2, ',', '')); ?>
<? $fpdf->Ln(); ?>
<? $text = "Il sottoscritto dichiara, sotto la propria responsabilità, che come le indennità, rimborsi forfettari, premi e compensi richiesti con la presente, non ha superato il limite di € 10.000,00 di cui all'art. 81 comma 1 lettera m del testo Unico Imposte sui redditi, pertanto su tali somme non deve essere applicata la ritenuta d'imposta o d'acconto prevista. Dichiara inoltre che se al momento dell'effettivo pagamento cambiassero le suddette condizioni, sarà propria cura comunicarlo alla associazione sportiva.";?>
<? $fpdf->MultiCell(180,3,$text,'C'); ?>
<? $fpdf->Cell(20,10,'Firma',0,0,'L'); ?>

<?endforeach;?>

<?$fpdf->output('files/pdf/gestione_campionati_lda_mensile_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/gestione_campionati_lda_mensile_'.date("d_m_Y").'.pdf'));?>

<?else:?>

<?
	PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setTitle("Lda mensile");
	$objPHPExcel->getProperties()->setSubject("Prova");
	$objPHPExcel->getProperties()->setKeywords("My DB office 2007 openxml php");	
	
	$countSheet = 0;
	
	foreach($tot_lda as $athlete_id => $lda):
	
	$athlete = $this->requestAction('/admin/prints/getAthlete/'.$athlete_id);
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($countSheet);
	$objPHPExcel->getActiveSheet()->setTitle($athlete['Athlete']['reverseAnagrafica']);

	$i = 0;
	$offsetBase = 5;
	
		for($k = 0; $k < 7; $k++) {
		
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($k,2)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($k,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($k,2)->getFill()->getStartColor()->setARGB('DDDFEC');	
		
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', '1', 'Riepilogo attività Sig. ' . $athlete['Athlete']['reverseAnagrafica']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', '1', ' dal ' . date("d/m/Y",strtotime($start_date)));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('5', '1', ' al ' . date("d/m/Y",strtotime($end_date)));
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', '2', 'Data');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', 'Ora');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', '2', 'Casa');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', '2', 'Trasferta');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('5', '2', 'Campo');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('6', '2', 'Importo');

		$importo_totale = 0;
		
		foreach ($lda as $r => $l):
			
			$row = $offsetBase + $r;
			
					$importo = 0;
			
					if($l['Lda']['Arbitro'] == $athlete_id):
				
						$importo += $l['Lda']['ImportoArbitro'];
					
					elseif($l['Lda']['Arbitro2'] == $athlete_id):
					
						$importo += $l['Lda']['ImportoArbitro2'];
						
					elseif($l['Lda']['Delegato'] == $athlete_id):
					
						$importo += $l['Lda']['ImportoDelegato'];
						
					elseif($l['Lda']['DelegatoA'] == $athlete_id):
					
						$importo += $l['Lda']['ImportoDelegatoA'];
				
					endif;			
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $l['Lda']['Data_it']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $l['Lda']['Ora']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $l['Lda']['CasaNome']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', $row, $l['Lda']['TrasfertaNome']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('5', $row, $l['Lda']['CampoNome']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('6', $row, '€ '.$importo);
		
			$importo_totale += $importo;					

			$i++;
					
		endforeach;
		
		$countSheet++;
	
	endforeach;
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="ldaMensile.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('files/xls/gestione_campionati_lda_mensile_'.date("d_m_Y").'.xlsx');
	
?>
<?=json_encode(array('link' => 'files/xls/gestione_campionati_lda_mensile_'.date("d_m_Y").'.xlsx'));?>
<?endif;?>