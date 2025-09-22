<?

	$campo  = $booking['campo'];
	$giorni = $booking['giorni'];
	$dow['1'] = 'Lunedì';
	$dow['2'] = 'Martedì';
	$dow['3'] = "Mercoledì";
	$dow['4'] = 'Giovedì';
	$dow['5'] = 'Venerdì';
	$dow['6'] = 'Sabato';
	$dow['7'] = 'Domenica';



	PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setTitle("Bollettino");
	$objPHPExcel->getProperties()->setSubject("Prova");
	$objPHPExcel->getProperties()->setKeywords("My DB office 2007 openxml php");
	
	/* Sheet Gare */
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Gare');
	
	//PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);	
	
	$max = 0;

	foreach ($giorni as $i => $giorno):
	
		/*
		debug('Giorno: ' . strtotime($giorno['Data'] . " 00:00:01"));
		debug('Start: ' . $start);
		debug('End: ' . $end);

		debug('Giorno: ' . date("d-m-Y", strtotime($giorno['Data'] . " 00:00:01")));
		debug('Start: ' . date("d-m-Y", $start));
		debug('End: ' . date("d-m-Y", $end));
		
		exit;
		*/
	
		if(isset($start) && isset($end)) {
			if(strtotime($giorno['Data'] . " 00:00:01") >= $start && strtotime($giorno['Data'] . " 00:00:01") <= $end) {
				//Nada
			} else {
				continue;
			}
		}
	
		$text = substr($dow[$giorno['DayOfWeek']],0,3)." ".date("d/m",strtotime($giorno['Data'] . " 00:00:01"));

		//echo $text."<br />";
	
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 1)->setValueExplicit($text, PHPExcel_Cell_DataType::TYPE_STRING);
		
		
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,1)->getFont()->setBold(true);
		
		if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']);
	
	endforeach;	
	
	for ($k=0;$k<$max;$k++):
	
	foreach ($giorni as $i => $giorno):
	
		if(isset($start) && isset($end)) {
			if(strtotime($giorno['Data'] . " 00:00:01") >= $start && strtotime($giorno['Data'] . " 00:00:01") <= $end) {
				//Nada
			} else {
				continue;
			}
		}
	
		if (isset($giorno['Orari'][$k])) {
		
			if ($giorno['Orari'][$k]['Occupato'] == 1) {

				if(!isset($giorno['Orari'][$k]['bookerNome'])) {
		
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $k+2, substr($giorno['Orari'][$k]['Ora'],0,-3) . "\n\n " . strip_tags($giorno['Orari'][$k]['Info']));
		
				} else {
		
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $k+2, substr($giorno['Orari'][$k]['Ora'],0,-3) . "\n\n " . $giorno['Orari'][$k]['bookerNome'] . " " . $giorno['Orari'][$k]['bookerCognome'] . " - " . $giorno['Orari'][$k]['bookerTelefono'] . " - " . $giorno['Orari'][$k]['bookerEmail']);
		
				}
		
		
			} else {
		
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $k+2, substr($giorno['Orari'][$k]['Ora'],0,-3));		
		
			}
		
		} else {
		
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $k+2, "");		
		
		}
			
	endforeach;
	
	endfor;
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->getActiveSheet()->calculateColumnWidths();	
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
	@unlink(APP . 'webroot/files/xls/affitti_'. $nome . '.xlsx');
	
	$objWriter->save('files/xls/affitti_'. $nome . '.xlsx');

	die(json_encode(array('link' => '/files/xls/affitti_'. $nome . '.xlsx')));	

?>


		