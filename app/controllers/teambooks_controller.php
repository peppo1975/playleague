<?

function getDebito($value) {

$tmp = explode('-', $value);

$squadra = $tmp[0];
$anno    = $tmp[1];
$annuario= $tmp[2];

App::Import('Model', 'Teambook');
$Teambook = new Teambook;

App::Import('Model', 'Match');
$Match = new Match;

App::Import('Model', 'Campionati');
$Campionati = new Campionati;

$disciplinari = $Teambook->query("
					
	SELECT *, SUM(Sanzione) as Debito
		FROM Disciplinari
		WHERE Disciplinari.SquadraCampionato = ANY(

		SELECT SquadreCampionati.SquadraCampionato
		FROM SquadreCampionati
		WHERE SquadreCampionati.Squadra = (
		SELECT AnnuarioSquadre.Squadra
		FROM AnnuarioSquadre
		WHERE AnnuarioSquadre.AnnoSportivo = $anno
		AND AnnuarioSquadre.Squadra = $squadra )
		) GROUP BY Disciplinare

");
					
$tot_debito_disciplinari = 0;

foreach($disciplinari as $debito) {

	$match = $Match->findByCalendario($debito['Disciplinari']['Calendario']);
	if($match['Campionati']['AnnoSportivo'] == $anno) {

		$tot_debito_disciplinari += $debito[0]['Debito'];
	
	}

}

$causali = $Teambook->query("

		SELECT *, CausaliRisultato.Sanzione as Debito FROM Calendari, CausaliRisultato
		WHERE (SELECT AnnoSportivo FROM Campionati WHERE Campionati.Campionato = Calendari.Campionato) = $anno

		AND CausaliRisultato.CausaleRisultato = Calendari.CausaleRisultato
		AND CausaliRisultato.Sanzione > 0

		AND (

			Calendari.Casa = ANY(

				SELECT SquadreCampionati.SquadraCampionato
				FROM SquadreCampionati
				WHERE Squadra = $squadra
				
			) OR

			Calendari.Trasferta = ANY(

				SELECT SquadreCampionati.SquadraCampionato
				FROM SquadreCampionati
				WHERE Squadra = $squadra
				
			)
		) GROUP BY Calendari.Calendario
		
");

$tot_debito_causali = 0;

foreach($causali as $causale_debito) {

	$partita = $Match->findByCalendario($causale_debito['Calendari']['Calendario']);
	
	//debug($partita);
	
	list($goalCasa, $goalTrasferta) = split('-', $partita['Match']['Risultato']);
	
	if($goalCasa > $goalTrasferta) {
	
	//Ha vinto la squadra in casa
	
		if($partita['Casa']['Squadra'] != $squadra) {
		
			$tot_debito_causali += $partita['Causalresult']['Sanzione'];
		
		}
	
	} else {
	
	//Ha vinto la squadra in trasferta
	
		if($partita['Trasferta']['Squadra'] != $squadra) {
		
			$tot_debito_causali += $partita['Causalresult']['Sanzione'];
		
		}
	
	}

}

$tot_debito = $tot_debito_causali + $tot_debito_disciplinari;

$data = $Teambook->findByAnnuariosquadra($annuario);

$saldo = $tot_debito - $data['Teambook']['DepositoCauzionale'];

if($saldo < 0) $saldo = 0;

return $saldo;

}

	class TeambooksController extends AppController {
	
			var $name = "Teambooks";
			var $login_required = true;
			var $helpers = array('Backend','fpdf');
			var $uses = array('Yearbook','Athlete','Squadre','Match','TipiAssicurazione','SquadreCampionati','Campionati','AnniSportivi','Teambook','Disciplinari');
						
			function admin_index() {
				
				// $AnnoSportivo = $this->AnniSportivi->find('first',array(
					
					// 'order' => 'AnnoSportivo DESC'
				
				// ));
				
				// debug($this->Session->read($this->name . ".searchFilters"));
				// debug($this->Session->read($this->name . ".searchData"));
				
				// if (!count($this->Session->read($this->name . ".searchFilters")) && !count($this->Session->read($this->name . ".searchData"))) {
					
					// $this->Session->write($this->name . ".searchFilters",
					
						
						// array(
						
							// 'Teambook.AnnoSportivo' =>
								// array('type' => 'equ',
									  // 'value' => $AnnoSportivo['AnniSportivi']['AnnoSportivo']
								// )
						
						// )
					
					
					// );
					
				// }
				
			}
			
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_searchAtleta() {
			
				$this->layout = "ajax";
				
				$atleti = $this->Athlete->find('all',array(
				
					'conditions' =>
					array(
						array('OR' => 
							array(
								'Athlete.Anagrafica LIKE' => $_GET['term'] . '%',
								'Athlete.reverseAnagrafica LIKE' => $_GET['term'] . '%'
							)
						)
					),
					'order' => 'Athlete.reverseAnagrafica ASC',
					'limit' => '15'
					
				));
				
				$ret = array();
				
				foreach ($atleti as $atleta) {
					
					$tmp['id'] = $atleta['Athlete']['Atleta'];
					$tmp['label'] = $atleta['Athlete']['Anagrafica'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_searchSquadra() {
			
				$this->layout = "ajax";
				
				$squadre = $this->Squadre->find('all',array(
				
					'conditions' =>
							array(
								'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
							),
					'order' => 'Squadre.Denominazione ASC',
					'limit' => '15'
					
				));
				
				$ret = array();
				
				foreach ($squadre as $squadra) {
					
					$tmp['id'] = $squadra['Squadre']['Squadra'];
					$tmp['label'] = $squadra['Squadre']['Denominazione'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_searchSquadraCampionato() {
			
				$this->layout = "ajax";
				
				$squadrec = $this->SquadreCampionati->find('all',array(
				
					'conditions' =>
					array(
								'Squadre.Denominazione LIKE' => '%' . $_GET['term'] . '%'
							
						
					),
					'limit' => '15'
					
				));
				
				$ret = array();
		
				foreach ($squadrec as $squadrac) {
					
					$tmp['id'] = $squadrac['SquadreCampionati']['SquadraCampionato'];
					$tmp['label'] = $squadrac['Squadre']['Denominazione'] . " " . $squadrac['Campionati']['Nome'] . " " . $squadrac['Campionati']['AnnoSportivo'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
				
				$this->set('AnniSportivi',$this->AnniSportivi->find('all', array( 'order' => array('AnniSportivi.AnnoSportivo DESC'))));
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
			
			}

 			function admin_add() {
			
			$this->set('AnniSportivi',$this->AnniSportivi->find('all', array( 'order' => array('AnniSportivi.AnnoSportivo DESC'))));
			
				$this->layout = "ajax";	
				
				if (!empty($this->data)) {
				
					$this->Teambook->set($this->data);
					
					if ($this->Teambook->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				$this->set('AnniSportivi',$this->AnniSportivi->find('all', array( 'order' => array('AnniSportivi.AnnoSportivo DESC'))));
				
				if (empty($this->data)) {
								
					$this->data = $this->Teambook->find('first',array('conditions' => array($this->Teambook->primaryKey => $id)));		
				
					$this->Squadre->set('Squadra',$this->data['Teambook']['Squadra']);
					$this->data['Teambook']['SquadraSearch'] = $this->Squadre->field('Denominazione');
					$this->Teambook->set($this->data);
					
					$squadra = $this->data['Teambook']['Squadra'];
					$anno    = $this->data['Teambook']['AnnoSportivo'];
					
					// Gestione riepilogo //
					/*
					
						SELECT * FROM Atleti WHERE Atleti.Atleta IN 
							(SELECT Annuario.Atleta FROM Annuario WHERE Annuario.SquadraCampionato IN 
								(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.Squadra = $squadra) 
							AND Annuario.AnnoSportivo = $anno
							)
							
					*/
					$tesserati = $this->Teambook->query("
					
						SELECT * FROM Atleti,Annuario,SquadreCampionati WHERE
						
						Annuario.Atleta = Atleti.Atleta AND
						Annuario.AnnoSportivo = $anno AND
	 					SquadreCampionati.Squadra = $squadra AND
						SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
						group by Annuario.Atleta
						order by Atleti.Cognome
					");
					
					$disciplinari = $this->Teambook->query("
					
						SELECT *, SUM(Sanzione) as Debito
							FROM Disciplinari
							WHERE Disciplinari.SquadraCampionato = ANY(

							SELECT SquadreCampionati.SquadraCampionato
							FROM SquadreCampionati
							WHERE SquadreCampionati.Squadra = (
							SELECT AnnuarioSquadre.Squadra
							FROM AnnuarioSquadre
							WHERE AnnuarioSquadre.AnnoSportivo = $anno
							AND AnnuarioSquadre.Squadra = $squadra )
							) GROUP BY Disciplinare
					
					");
										
					$tot_debito_disciplinari = 0;
					
					foreach($disciplinari as $k => $debito) {
					
						$match = $this->Match->findByCalendario($debito['Disciplinari']['Calendario']);
						if($match['Campionati']['AnnoSportivo'] == $anno) {

							$tot_debito_disciplinari += $debito[0]['Debito'];
						
						} else {
						
							unset($disciplinari[$k]);
						
						}
					
					}
					
					$causali = $this->Teambook->query("
					
							SELECT *, SUM(CausaliRisultato.Sanzione) As Debito FROM Calendari, CausaliRisultato
							WHERE (SELECT AnnoSportivo FROM Campionati WHERE Campionati.Campionato = Calendari.Campionato) = $anno

							AND CausaliRisultato.CausaleRisultato = Calendari.CausaleRisultato
							AND CausaliRisultato.Sanzione > 0

							AND (

								Calendari.Casa = ANY(

									SELECT SquadreCampionati.SquadraCampionato
									FROM SquadreCampionati
									WHERE Squadra = $squadra
									
								) OR

								Calendari.Trasferta = ANY(

									SELECT SquadreCampionati.SquadraCampionato
									FROM SquadreCampionati
									WHERE Squadra = $squadra
									
								)
							) GROUP BY Calendari.Calendario
							
					");
					
					$tot_debito_causali = 0;
					
					foreach($causali as $k => $causale_debito) {

						$partita = $this->Match->findByCalendario($causale_debito['Calendari']['Calendario']);
						
						list($goalCasa, $goalTrasferta) = split('-', $partita['Match']['Risultato']);
						
						if($goalCasa > $goalTrasferta) {
						
							if($partita['Casa']['Squadra'] != $squadra) {
							
								$tot_debito_causali += $partita['Causalresult']['Sanzione'];
							
							} else {
							
								unset($causali[$k]);
							
							}
						
						} else {
						
							if($partita['Trasferta']['Squadra'] != $squadra) {
							
								$tot_debito_causali += $partita['Causalresult']['Sanzione'];
							
							} else {
							
								unset($causali[$k]);
							
							}
						
						}

					}
					
					$tot_debito = $tot_debito_causali + $tot_debito_disciplinari;
																				
					$this->set('tot_debito', $tot_debito);
					$this->set('causali', $causali);
					$this->set('disciplinari', $disciplinari);
					$this->set('tesserati', $tesserati);
										
					// End gestione riepilogo //
				
				} else {
					
					$this->data['Teambook'][$this->Teambook->primaryKey] = $id;
											
					$this->Teambook->set($this->data);
					
					$ADD_OK = true;

						if ($this->Teambook->save()) {
														
							if ($ADD_OK) {
								$this->set('result','ADD_OK');
								$this->render('/backend/ajaxResult');
							}	
						}
					
				}
			
			}	
			
			function admin_pdf() {
			
				$this->layout = "pdf";
			
			}
			
			function admin_sessionFromView($name) {
			
				$this->layout = "ajax";
				
				$arr = $this->params['arr'];
				
				$this->Session->write($name,$arr);
					
			}
			
	}
