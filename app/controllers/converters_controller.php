<?




	class ConvertersController extends AppController {
	
			var $name = "Converters";

			var $helpers = array('Backend');
			var $uses = array('Match','Lda');
						
			function matches() {
				
				$this->layout = null;
				
				$partite = $this->Match->find('all');
				
				foreach ($partite as $partita) {
					
					
				set_time_limit(1000);

					
					print "\n\n\n" . $partita['Match']['Calendario'] . " - conversione...\n";
					
					$this->Lda->create();
					
					$lda = $this->Lda->find('first',array('conditions' => 
					
						array(
						
							'Lda.Data' => $partita['Match']['Data'],
							'Lda.Ora' => $partita['Match']['Ora'],
							'Lda.Casa' => $partita['Match']['Casa'],
							'Lda.Trasferta' => $partita['Match']['Trasferta'],
							'Lda.Campionato' => $partita['Match']['Campionato']
						
						)
					
					));
					
					if (isset($lda['Lda']['LDA'])) {
						
						print $lda['Lda']['LDA'] . " - trovato, salvataggio\n";
						
						$partita['Match']['lda_id'] = $lda['Lda']['LDA'];
						
						mysql_query("UPDATE Calendari SET lda_id = " . $partita['Match']['lda_id'] . " WHERE Calendari.Calendario = " . $partita['Match']['Calendario']);
						
		
					}
					
					
					
				}
				
					
				
			}
			
				
	}
