<?php
$gare = $this->Session->read('gare');
$gare_prossima_giornata = $this->Session->read('gare_prossima_giornata');
$gare_prossima_giornata2 = $this->Session->read('gare_prossima_giornata2');
$classifica_marcatori = $this->Session->read('classifica_marcatori');
$diffidati = $this->Session->read('diffidati');
$espulsi = $this->Session->read('espulsi');
$classifiche = $this->Session->read('classifiche');
$options_pdf = $this->Session->read('options_pdf');
$gironi = $this->Session->read('gironi');
$giornate = $this->Session->read('giornate');
$n_giornate = count($giornate);
$n_gironi = count($gironi);
$i = 2;

if($options_pdf == '') $options_pdf = 1;

	 if($options_pdf == 1) {
		
			 $fpdf->setup('P','mm','A4'); 
			 
	 }
	else if($options_pdf == 2) {
		
			 $fpdf->setup('L','mm','A4'); 
			 
	}

 foreach($giornate as $giornata) {
		
	foreach($gironi as $girone) {
	
			if($options_pdf == 1) {
		
				 $fpdf->AddPage(); 
				 $fpdf->SetFont('helvetica','B',16); 
				 
			}
			 else if($options_pdf == 2) {
			
				 if($i % 2 != 0) {

					 $fpdf->AddPage(); 
					 $fpdf->SetFont('helvetica','B',16); 
				
				 }
			}				 
							
		 $header_gare = array('Casa', 'Trasferta', 'Risultato'); 
		
		 $body_gare = array(); 
		
		 foreach($gare[$giornata][$girone] as $gara) {
		
				 $body_gare[$gara['Match']['Calendario']] = array($gara['Match']['CasaNome'], $gara['Match']['TrasfertaNome'], $gara['Match']['Risultato']); 
									
		 }
		
		 $fpdf->basicTable($header_gare, $body_gare); 
				
		 $header_classifiche = array('Societ&agrave;', 'Punti', 'Giocate','Vinte','Perse','Nulle','Goal Fatti','Goal Subiti','Coppa Disc.'); 
		
		 $body_classifiche = array(); 
					
			 foreach($classifiche[$giornata][$girone] as $classifica) {
			
				$nome_squadra = $this->requestAction('/admin/prints/findTeam/' . $classifica['SquadraCampionato']); 
				 
				$body_classifiche[$classifica['Classifica']] = array($nome_squadra,$classifica['Punti'],$classifica['Giocate'],$classifica['Vinte'],$classifica['Perse'],$classifica['Nulle'],$classifica['GoalFatti'],$classifica['GoalSubiti'],$classifica['CoppaDisciplina']);

			 }
			
		 $fpdf->basicTable($header_classifiche, $body_classifiche); 
						
		$i ++; 
		
	}

}

echo $fpdf->fpdfOutput('p.pdf','S'); 
?>