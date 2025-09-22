<?		
		$tesserati    = $this->Session->read('tesserati_prova');
		$causali   	  = $this->Session->read('causali_prova');
		$disciplinari = $this->Session->read('disciplinari_prova');

		$dati 		  = $this->Session->read('dati');
		
		//debug($dati);
			 
		$fpdf->setup('P','mm','A4'); 
		$fpdf->SetMargins('10.2','5.0');
		$fpdf->SetFont('helvetica','B',8); 
						
		$tesserati    = str_replace('<th>', '<td>', $tesserati);
		$tesserati    = str_replace('</th>', '</td>', $tesserati);
		$tesserati    = str_replace('<table', '<table border="1" ', $tesserati);
		
		$causali 	  = str_replace('<th>', '<td>', $causali);
		$causali      = str_replace('</th>', '</td>', $causali);
		$causali      = str_replace('<table', '<table border="1" ', $causali);
		
		$disciplinari = str_replace('<th>', '<td>', $disciplinari);
		$disciplinari = str_replace('</th>', '</td>', $disciplinari);
		$disciplinari = str_replace('<table', '<table border="1" ', $disciplinari);
		
		$check = 0;
				
		if(strlen($disciplinari) > 277) {
		
			$fpdf->AddPage(); 
			
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Anno sportivo: ' . $dati['Anno'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Al giorno: ' . $dati['Data'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Squadra: ' . $dati['Squadra'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Deposito cauzionale: ' .$dati['DepositoCauzionale'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Debito: ' . $dati['Debito'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Cell(30,4,'Saldo: ' . $dati['Saldo'],0,0,'L');
			$fpdf->Ln();
			$fpdf->Ln();
			
			$fpdf->Cell(30,6,'Disciplinari:',0,0,'L');
			
			$fpdf->Ln();
			$fpdf->Ln();
			
			$fpdf->htmltable($disciplinari);
			
			$check = 1;
		
		}
				
		if(strlen($causali) > 289) {
		
			$fpdf->AddPage(); 
			
			if($check == 0) {
			
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Anno sportivo: ' . $dati['Anno'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Al giorno: ' . $dati['Data'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Squadra: ' . $dati['Squadra'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Deposito cauzionale: ' .$dati['DepositoCauzionale'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Debito: ' . $dati['Debito'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Saldo: ' . $dati['Saldo'],0,0,'L');
				$fpdf->Ln();
			
			}
			
			$fpdf->Ln();
			
			$fpdf->Cell(30,6,'Causali:',0,0,'L');
			
			$fpdf->Ln();
			$fpdf->Ln();
						
			$fpdf->htmltable($causali);
			
			$check = 1;
		
		}
		
		$fpdf->AddPage(); 
		
			if($check == 0) {
			
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Anno sportivo: ' . $dati['Anno'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Al giorno: ' . $dati['Data'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Squadra: ' . $dati['Squadra'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Deposito cauzionale: ' .$dati['DepositoCauzionale'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Debito: ' . $dati['Debito'],0,0,'L');
				$fpdf->Ln();
				$fpdf->Cell(30,4,'Saldo: ' . $dati['Saldo'],0,0,'L');
				$fpdf->Ln();
			
			}
			
		$fpdf->Ln();
		
		$fpdf->Cell(30,6,'Tesserati:',0,0,'L');
		
		$fpdf->Ln();
		$fpdf->Ln();
			
		$fpdf->htmltable($tesserati);
					 
		echo $fpdf->fpdfOutput('annuario_squadre_riepilogo_'.date("d_m_Y").'.pdf','D'); 
			 
?>