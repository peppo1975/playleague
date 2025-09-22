<?ob_start();?>
<table border="1" width="100%">
	<tr bgcolor="#DDDFEC">
		<td align="center" width="50">Società</td>
		<td align="center">Punti</td>
		<td align="center">Giocate</td>
		<td align="center">Vinte</td>
		<td align="center">Perse</td>
		<td align="center">Nulle</td>
		<td align="center">Goal Fatti</td>
		<td align="center">Goal Subiti</td>
		<td align="center">Coppa Disc.</td>
	</tr>
	<?foreach($classifica as $class):?>
		<tr>					
			<td><?=$class['Ranking']['NomeSquadra'];?></td>
			<td><?=$class['Ranking']['Punti'];?></td>
			<td><?=$class['Ranking']['Giocate'];?></td>
			<td><?=$class['Ranking']['Vinte'];?></td>
			<td><?=$class['Ranking']['Perse'];?></td>
			<td><?=$class['Ranking']['Nulle'];?></td>
			<td><?=$class['Ranking']['GoalFatti'];?></td>
			<td><?=$class['Ranking']['GoalSubiti'];?></td>
			<td><?=$class['Ranking']['CoppaDisciplina'];?></td>					
		</tr>
	<?endforeach;?>
</table>
<?$table = ob_get_clean();?>
<?ob_start();?>
<table border="1" width="100%">
	<tr bgcolor="#DDDFEC">
		<td align="center" width="50">Goal</td>
		<td align="center">Marcatore</td>
	</tr>
	<?foreach($marcatori as $mark):?>
	
		<tr>
			<td><?=$mark[0]['goals'];?></td>
			<td><?=$mark[0]['anagrafica'] . ' (' . $mark[0]['NomeSquadra'] . ') ';?></td>
		</tr>

	<?endforeach;?>
</table>
<?$table_mark = ob_get_clean();?>
<?ob_start();?>
<table border="1" width="100%">
	<tr bgcolor="#DDDFEC">
		<td align="center" width="50">Atleta</td>
		<td align="center">Ammonizioni</td>
		<td align="center">Espulsioni</td>
	</tr>
	<?foreach($disciplinari as $disciplinare):?>	
		<tr>
			<td><?=$disciplinare['Atleta'];?></td>
			<td><?=$disciplinare['Ammonizioni'];?></td>
			<td><?=$disciplinare['Espulsioni'];?></td>
		</tr>

	<?endforeach;?>
</table>
<?$table_disc = ob_get_clean();?>
<?if($export == 'pdf'):?>
<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->AddFont('Calibri','','calibri.php'); ?>	
<? $fpdf->SetFont('Calibri','',8); ?>
<? $fpdf->SetFontSize(11); ?>
<? $fpdf->Cell(170,10,$campionato,0,0,'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,12.0,180.0,12.0); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table);?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->SetFont('helvetica','',8); ?>
<? $fpdf->SetFontSize(11); ?>
<? $fpdf->Cell(170,10,$campionato,0,0,'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,12.0,180.0,12.0); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table_mark);?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddPage(); ?>
<? $fpdf->SetFont('helvetica','',8); ?>
<? $fpdf->SetFontSize(11); ?>
<? $fpdf->Cell(170,10,$campionato,0,0,'C'); ?>
<? $fpdf->SetLineWidth(0.3); ?>
<? $fpdf->Line(9.0,12.0,180.0,12.0); ?>
<? $fpdf->Ln(); ?>
<? $fpdf->htmltable($table_disc);?>
<? $fpdf->output('files/pdf/classifiche_'.Inflector::Slug($campionato).'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/classifiche_'.Inflector::Slug($campionato).'.pdf'));?>
<?else:?>

<?

	PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setTitle("Classifiche");
	$objPHPExcel->getProperties()->setSubject("Prova");
	$objPHPExcel->getProperties()->setKeywords("My DB office 2007 openxml php");
	
	/* Sheet Classifica */
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Classifica');
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', $campionato . ' - ' . $girone);	
		
	$baseRow = 5;
						
			foreach($classifica as $r => $class):
									
					$row = $baseRow + $r;
															
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $class['Ranking']['NomeSquadra']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $class['Ranking']['Punti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $class['Ranking']['Giocate']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', $row, $class['Ranking']['Vinte']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('5', $row, $class['Ranking']['Perse']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('6', $row, $class['Ranking']['Nulle']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('7', $row, $class['Ranking']['GoalFatti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('8', $row, $class['Ranking']['GoalSubiti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('9', $row, $class['Ranking']['CoppaDisciplina']);
													
			endforeach;
			
	/* Sheet marcatori */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setTitle('Classifica marcatori');
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', $campionato . ' - ' . $girone);	
		
	$baseRow = 5;
						
			foreach($marcatori as $r => $mark):
									
					$row = $baseRow + $r;
															
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $mark[0]['goals']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $mark[0]['anagrafica'] . ' (' . $mark[0]['NomeSquadra'] . ') ');
													
			endforeach;
			
	/* Sheet disciplinari */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setTitle('Classifica disciplinari');
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->getStartColor()->setARGB('DDDFEC');
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', $campionato . ' - ' . $girone);	
		
	$baseRow = 5;
						
			foreach($disciplinari as $r => $disc):
									
					$row = $baseRow + $r;
															
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $disc['Atleta']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $disc['Ammonizioni']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $disc['Espulsioni']);
													
			endforeach;
			
	/* Creazione file */
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="xls_classifiche.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('files/xls/classifiche_'.Inflector::Slug($campionato).'.xlsx');
	
?>
<?=json_encode(array('link' => 'files/xls/classifiche_'.Inflector::Slug($campionato).'.xlsx'));?>
<?endif;?>