<?

function getPunti($value){
	
	if($value != 0) {
		
		$value = '<div class="td_red">' . $value . '</div>';
		
	}
	
	return $value;
	
}

	class RankingsController extends AppController {
	
			var $name = "Rankings";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Ranking','Match','Campionati','Half','AnniSportivi','Squadre','Campi','Causalresult','SquadreCampionati','Matchgoal','Disciplinari');
						
			function admin_index() {
			
				
				
			}
			
			public function setCampionati() {
			
				$_campionati = $this->Campionati->find('list', array(
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array(
						//'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),	
						'Campionati.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
						'Campionati.InUso' => 'Si',
						//'(SELECT COUNT(*) FROM Calendari WHERE Calendari.Campionato = Campionati.Campionato) = 0'				
					),
					'order' => array('Campionati.Nome DESC')
				
				));
				
				$_campionati['default'] = 'Scegliere un campionato...';
				
				$_campionati = array_reverse($_campionati, true);
				
				$this->set('campionati',$_campionati);
			
			}
			
			function admin_refresh() {
				
				$this->layout = "timmybox";
				
				$this->setCampionati();
								
				if (!empty($this->data)) {
					
					$this->layout = "ajax";
					
					$campionato = $this->data['Ranking']['Campionato'];
					$girone 	= $this->data['Ranking']['GironeCampionato'];
										
					$squadre = $this->SquadreCampionati->find('all', array(
					
						'conditions' => 
							array(
								    'Campionati.Campionato' => $campionato,
								    'Half.GironeCampionato' => $girone
								  )
						)
					);
					
					$last_giornata = $this->Match->find('first',
					
						array(
						
							'conditions' => array(	
												'(SELECT COUNT(*) FROM GoalPartite WHERE GoalPartite.Calendario = Match.Calendario) > 0',
												
												 'Match.Campionato' => $campionato,
												 'Match.GironeCampionato' => $girone
											),
							'order' => 'Match.Giornata DESC'
													
						)
					
					);
					
					if (empty($last_giornata)) $last_giornata = 0;
					else $last_giornata = $last_giornata['Match']['Giornata'];
					
					$partite = array();
					
					$classifiche = array();
					
					foreach ($squadre as $squadra) {
						
						$classifica = array();
						
						$id_classifica = $this->Ranking->find('first', array(
						
							'conditions' => 
								array(
								'Ranking.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
								'Ranking.GironeCampionato' => $girone
								)
							)
							
						);
						
						if (!empty($id_classifica)) {
							$classifica['Classifica'] = $id_classifica['Ranking']['Classifica'];
						} else {
							$classifica['Classifica'] = null;
						}
						
						$classifica['SquadraCampionato'] = $squadra['SquadreCampionati']['SquadraCampionato'];
						$classifica['GironeCampionato'] = $girone;
						$classifica['Giocate'] = 0;
						$classifica['Punti'] = 0;
						$classifica['Vinte'] = 0;
						$classifica['Perse'] = 0;
						$classifica['Nulle'] = 0;
						$classifica['GiocateCasa'] = 0;
						$classifica['VinteCasa'] = 0;
						$classifica['PerseCasa'] = 0;
						$classifica['NulleCasa'] = 0;
						$classifica['GiocateFuori'] = 0;
						$classifica['VinteFuori'] = 0;
						$classifica['PerseFuori'] = 0;
						$classifica['NulleFuori'] = 0;
						$classifica['GoalFatti'] = 0;
						$classifica['GoalSubiti'] = 0;
						$classifica['GoalSubitiFuori'] = 0;
						$classifica['GoalSubitiCasa'] = 0;
						$classifica['GoalFattiFuori'] = 0;
						$classifica['GoalFattiCasa'] = 0;
						$classifica['CoppaDisciplina'] = 0;
 						
						$partite = $this->Match->find('all',array(
						
							'conditions' => 
								array(
							
								'OR' => 
										array('Match.Casa' => $squadra['SquadreCampionati']['SquadraCampionato'],
											  'Match.Trasferta' => $squadra['SquadreCampionati']['SquadraCampionato']
										),
								
								'Match.Campionato' => $campionato,
								'Match.GironeCampionato' => $girone,
								'Match.Giornata <=' => $last_giornata
								)
						
						));
					
						foreach ($partite as $partita) {
							
							$casa_fuori = 'Fuori';
							$fuori_casa = 'Casa';
							$risultato['Casa'] = 0;
							$risultato['Fuori'] = 0;
							
							if ($partita['Match']['Casa'] == $squadra['SquadreCampionati']['SquadraCampionato']) $casa_fuori = 'Casa';
					
							$disciplinari = $this->Disciplinari->find('all',array(
								
								'conditions' => array(
								
									'SquadreCampionati.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
									'Disciplinari.Calendario' => $partita['Match']['Calendario']
								
								)
							
							));
							
							//pr ($partita['Causalresult']);
							
							foreach ($disciplinari as $disciplinare) {
								
								$classifica['CoppaDisciplina'] += $disciplinare['Disciplinari']['Punti'];
								
							}
					
							
					
							if ($partita['Causalresult']['Descrizione'] != 'Recupero' && substr($partita['Causalresult']['Descrizione'],0,strlen('N.D.')) != 'N.D.' && $partita['Causalresult']['Descrizione'] != 'In attesa decisioni G.S.' && $partita['Causalresult']['Descrizione'] != 'Gara non omologabile.' && $partita['Causalresult']['Descrizione'] != 'Risultato non omologabile' && $partita['Causalresult']['Descrizione'] != 'RINV.') {
								
								$classifica['Giocate']++;
								$classifica['Giocate' . $casa_fuori]++;
								
								$goals = $this->Matchgoal->find('all',array(
								
									'conditions' => 
									
											array(
												
												'Matchgoal.Calendario' => $partita['Match']['Calendario'],
												
											)
								));
					
								foreach ($goals as $goal) {
									
									if ($casa_fuori == 'Casa')  $fuori_casa = 'Fuori';
									else 						$fuori_casa = 'Casa';
									
									if ($squadra['SquadreCampionati']['SquadraCampionato'] == $goal['Matchgoal']['SquadraCampionato']) {
										
											$classifica['GoalFatti'] += $goal['Matchgoal']['Goal'];
											$classifica['GoalSubiti'] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
											$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
											
											$risultato[$casa_fuori] += $goal['Matchgoal']['Goal'];
											$risultato[$fuori_casa] += $goal['Matchgoal']['Autogoal'];
											
											if ($goal['Matchgoal']['Ammonizione'] == 'Si') $classifica['CoppaDisciplina']++;
											if ($goal['Matchgoal']['Espulsione']  == 'Si')  $classifica['CoppaDisciplina']+=3;
											
											
									} else {
										
											$classifica['GoalFatti'] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalSubiti'] += $goal['Matchgoal']['Goal'];
											$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
									
											$risultato[$fuori_casa] += $goal['Matchgoal']['Goal'];
											$risultato[$casa_fuori] += $goal['Matchgoal']['Autogoal'];
									}
					
									
								}
								
								if ($risultato[$casa_fuori] == $risultato[$fuori_casa]) {
										
										$classifica['Nulle']++;
										$classifica['Nulle' . $casa_fuori]++;
										$classifica['Punti']++;
										
								}
								
								if ($risultato[$casa_fuori] > $risultato[$fuori_casa]) {
									
										$classifica['Punti'] += 3;
										$classifica['Vinte' . $casa_fuori]++;
										$classifica['Vinte']++;
									
								}
								
								if ($risultato[$casa_fuori] < $risultato[$fuori_casa]) {
									
										$classifica['Perse' . $casa_fuori]++;
										$classifica['Perse']++;
									
										if (substr($partita['Causalresult']['Descrizione'],0,strlen('TAV')) == 'TAV') {
											
											$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
											
										} 
									
								}								
								
					
							} else {
									
									$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
								
							}
						
						}
						
						// Tolgo penalizzazione
						
						$classifica['Punti'] = $classifica['Punti'] - (isset($id_classifica['Ranking']['PuntiPenalizzazione'])? $id_classifica['Ranking']['PuntiPenalizzazione']:0);
						
						
						$classifiche[] = $classifica;
						
					}
					
					$this->set('result',json_encode($classifiche));
					
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_refreshSave() {
			
				$this->layout = "ajax";
				
				if($_POST['ret2'] == null) $_POST['ret2'] = array();
					
				foreach($_POST['ret2'] as $record => $value) {
				
					$this->data['Ranking']['Classifica'] = $value['Classifica'];
					$this->data['Ranking']['SquadraCampionato'] = $value['SquadraCampionato'];
					$this->data['Ranking']['GironeCampionato'] = $value['GironeCampionato'];
					$this->data['Ranking']['Giocate'] = $value['Giocate'];
					$this->data['Ranking']['Punti'] = $value['Punti'];
					$this->data['Ranking']['Vinte'] = $value['Vinte'];
					$this->data['Ranking']['Perse'] = $value['Perse'];
					$this->data['Ranking']['Nulle'] = $value['Nulle'];
					$this->data['Ranking']['GiocateCasa'] = $value['GiocateCasa'];
					$this->data['Ranking']['VinteCasa'] = $value['VinteCasa'];
					$this->data['Ranking']['PerseCasa'] = $value['PerseCasa'];
					$this->data['Ranking']['NulleCasa'] = $value['NulleCasa'];
					$this->data['Ranking']['GiocateFuori'] = $value['GiocateFuori'];
					$this->data['Ranking']['VinteFuori'] = $value['VinteFuori'];
					$this->data['Ranking']['PerseFuori'] = $value['PerseFuori'];
					$this->data['Ranking']['NulleFuori'] = $value['NulleFuori'];
					$this->data['Ranking']['GoalFatti'] = $value['GoalFatti'];
					$this->data['Ranking']['GoalSubiti'] = $value['GoalSubiti'];
					$this->data['Ranking']['GoalSubitiFuori'] = $value['GoalSubitiFuori'];
					$this->data['Ranking']['GoalSubitiCasa'] = $value['GoalSubitiCasa'];
					$this->data['Ranking']['GoalFattiFuori'] = $value['GoalFattiFuori'];
					$this->data['Ranking']['GoalFattiCasa'] = $value['GoalFattiCasa'];
					$this->data['Ranking']['CoppaDisciplina'] = $value['CoppaDisciplina'];
					
					$this->Ranking->set($this->data);
					
					if($this->Ranking->save()) {

						$update = 1;
						
					} else {
					
						$update = 0;
					
					}
				
				}
				
				$this->set('result',json_encode(array('update' => (isset($update))? $update:0)));
				$this->render("/backend/ajaxResult");
				
			
			}

			function admin_searchCampionato() {
			
				$this->layout = "ajax";
				
				$campionatis = $this->Campionati->find('all', array(
				
					'conditions' =>
					array(
					
								'Campionati.Nome LIKE' => $_GET['term'] . '%',
					
						),
					
					'order' => 'Campionati.Nome ASC',
					'limit' => '15',
					
				));
				
				$ret = array();
				
				foreach ($campionatis as $campionato) {
					
					$tmp['id'] = $campionato['Campionati']['Campionato'];
					$tmp['label'] = $campionato['Campionati']['Nome'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_gironeSearch($id_campionato) {
			
				$this->layout = "ajax";
			
				$halfs = $this->Half->find('list', array(
				
					'fields' => array('Half.Descrizione','Half.Descrizione'),
				
					'conditions' =>
					array(
					
								'Half.Campionato' => $id_campionato,
					
						),
					
					'order' => 'Half.Descrizione ASC',
					
				));
				
				$this->set('result', json_encode($halfs));
				$this->render('/backend/ajaxResult');
			
			}
			
			function admin_searchGirone($id_campionato) {
			
				$this->layout = "ajax";
				
				if(!isset($_GET['term'])) $_GET['term'] = '';
				
				$halfs = $this->Half->find('all', array(
				
					'conditions' =>
					array(
					
								'Half.Campionato' => $id_campionato,
								'Half.Descrizione LIKE' => $_GET['term'] . '%',
					
						),
					
					'order' => 'Half.Descrizione ASC',
					'limit' => '15',
					
				));
				
				$ret = array();
				
				foreach ($halfs as $half) {
					
					$tmp['id'] = $half['Half']['GironeCampionato'];
					$tmp['label'] = $half['Half']['Descrizione'];
					
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
				
				if (!isset($this->params['pass'][0])) {
				
				$squadrec = $this->SquadreCampionati->find('all',array(
				
					'conditions' =>
					array(
								'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
							
						
					),
					'limit' => '15'
					
				));
				
				} else {
				
				$squadrec = $this->SquadreCampionati->find('all',array(
				
					'conditions' =>
					array(
								'Half.GironeCampionato' => $this->params['pass'][0]
					),
					'limit' => '15'
					
				));
					
				}
				
				$ret = array();
		
				foreach ($squadrec as $squadrac) {
					
					$tmp['id'] = $squadrac['SquadreCampionati']['SquadraCampionato'];
					$tmp['label'] = $squadrac['Squadre']['Denominazione'] . " (" . $squadrac['Campionati']['Nome'] . " - " . $squadrac['Campionati']['AnnoSportivo'] . ")";
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_searchCampo() {
			
				$this->layout = "ajax";
				
				$campi = $this->Campi->find('all', array(
				
					'conditions' =>
					array(
					
								'Campi.Descrizione LIKE' => $_GET['term'] . '%',
					
						),
					
					'order' => 'Campi.Descrizione ASC',
					'limit' => '15',
					
				));
				
				$ret = array();
				
				foreach ($campi as $campo) {
					
					$tmp['id'] = $campo['Campi']['Campo'];
					$tmp['label'] = $campo['Campi']['Descrizione'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
				
				/*$campionati = $this->Campionati->find('list', array(
				
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array('Campionati.InCorso' => 'Si'),
					'order' => 'Campionati.Nome ASC'
					)
				);
				
				$this->set('campionati', $campionati);
				
				*/
				
				$this->setCampionati(); 

				if (!empty($this->data)) {

					// $data = array();

					// if (preg_match('~(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)[0-9]{2})~Ui',$this->data['Ranking']['Data'],$data)) {
						
						// $this->data['Ranking']['Data'] = $data[3] . "-" . $data[2] . "-" . $data[1];
						
					// }
			   
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
			
			}

 			function admin_add() {

				$this->layout = "ajax";	
				$this->set('causali', $this->Causalresult->find('all'));
				
				if (!empty($this->data)) {

					$this->Ranking->set($this->data);
					
					if ($this->Ranking->save()) {
						
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
				$this->set('causali', $this->Causalresult->find('all'));
						
				if (empty($this->data)) {
											
					$this->data = $this->Ranking->find('first',array('conditions' => array('Ranking.Classifica' => $id)));
					
					$this->data['Ranking']['NomeGirone'] = $this->data['Half']['Descrizione'];
					// $this->data['Match']['Data'] = $this->data['Match']['Data_it'];
					// $this->data['Match']['CampionatoSearch'] = $this->data['Campionati']['Nome'];
					// $this->data['Match']['GironeSearch'] = $this->data['Half']['Descrizione'];
					// $this->data['Match']['SquadraCasaSearch'] = $this->data['Match']['CasaNome'];
					// $this->data['Match']['SquadraTrasfertaSearch'] = $this->data['Match']['TrasfertaNome'];
					// $this->data['Match']['CampoSearch'] = $this->data['Campi']['Descrizione'];

					$this->Ranking->set($this->data);
				
				} else {
										
					$this->Ranking->set($this->data);
					
					$penalizzazione = $this->data['Ranking']['PuntiPenalizzazione'];
					
					$this->data['Ranking']['PuntiPenalizzazione'] = $penalizzazione;
					
					$ADD_OK = true;

					if ($this->Ranking->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}

			function admin_rankingMarkers($campionato_id, $girone = '.') {
			
				if (!isset($girone)) $girone = '.';
			
				$this->layout = "timmybox";
				
				$girone_ = $this->Half->find('first', array(
				
					'conditions' => array(
						
						'Half.Campionato' => $campionato_id,
						'Half.Descrizione' => $girone
					
					)
				
				));
				
				$girone_id = $girone_['Half']['GironeCampionato'];
				$campionatoPrecedente = $girone_['Campionati']['CampionatoPrecedente'];
				
				$classifica_marcatori_campionato = $this->Matchgoal->query(
			
						"SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						SUM(GoalPartite.Goal) as goals FROM GoalPartite 
						WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id') 
						AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari)
						GROUP BY GoalPartite.Atleta ORDER BY goals DESC"
					
				);
				
				if(!empty($campionatoPrecedente)) {
				
					$classifica_marcatori_campionato_precedente = $this->Matchgoal->query(
				
							"SELECT 
							(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
							(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
							SUM(GoalPartite.Goal) as goals FROM GoalPartite 
							WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionatoPrecedente') 
							AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari)
							GROUP BY GoalPartite.Atleta ORDER BY goals DESC"
						
					);	
					
					$classifica_marcatori_campionato = array_merge($classifica_marcatori_campionato, $classifica_marcatori_campionato_precedente);
				
				}
					
				$classifica_marcatori_gironeCampionato = $this->Matchgoal->query(
			
						"SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						SUM(GoalPartite.Goal) as goals FROM GoalPartite 
						WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id')
						AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari)
						GROUP BY GoalPartite.Atleta ORDER BY goals DESC"
					
				);			
					
					
				$this->set('campionato', $classifica_marcatori_campionato);
				$this->set('girone', $classifica_marcatori_gironeCampionato);
		
			}
			
			function admin_rankingDiscipline($campionato_id, $girone = '.') {
			
				if (!isset($girone)) $girone = '.';
				$this->layout = "timmybox";
				
				$girone_ = $this->Half->find('first', array(
				
					'conditions' => array(
						
						'Half.Campionato' => $campionato_id,
						'Half.Descrizione' => $girone
					
					)
				
				));
				
				$campionatoPrecedente = $girone_['Campionati']['CampionatoPrecedente'];
				$girone_id = $girone_['Half']['GironeCampionato'];

				$classifica_espulsi = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Espulsione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
				");
				
				$classifica_ammoniti = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Ammonizione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER By Tot DESC
				");
				
				$disciplinari = array();
				
				foreach($classifica_ammoniti as $ammonito) {
				
					$espulsioni = 0;
				
					foreach($classifica_espulsi as $espulso) {
					
						if($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id']) {
						
							$espulsioni = $espulso[0]['Tot'];
						
						}
					
					}
				
					$disciplinari[] = array(
					
						'Squadra' => $ammonito[0]['NomeSquadra'],
						'Atleta_id' => $ammonito[0]['atleta_id'],
						'Atleta' => $ammonito[0]['anagrafica'],
						'Ammonizioni' => $ammonito[0]['Tot'],
						'Espulsioni' => $espulsioni
					
					);
				
				}
				
				$this->set('disciplinari', $disciplinari);
				
				$disciplinari_campionato = array();
				
				$classifica_espulsi_campionato = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
					AND GoalPartite.Espulsione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
				");	
				$classifica_ammoniti_campionato = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
					AND GoalPartite.Ammonizione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER By Tot DESC
				");	
				
				if(!empty($campionatoPrecedente)) {
				
					$classifica_espulsi_campionato_precedente = $this->Ranking->query("
						SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
						COUNT(*) as Tot
						FROM GoalPartite
						WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
						AND GoalPartite.Espulsione = 'Si'
						GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
					");	
					$classifica_ammoniti_campionato_precedente = $this->Ranking->query("
						SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
						COUNT(*) as Tot
						FROM GoalPartite
						WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
						AND GoalPartite.Ammonizione = 'Si'
						GROUP BY GoalPartite.Atleta ORDER By Tot DESC
					");	
					
					$classifica_ammoniti_campionato = array_merge($classifica_ammoniti_campionato, $classifica_ammoniti_campionato_precedente);
					$classifica_espulsi_campionato  = array_merge($classifica_espulsi_campionato , $classifica_ammoniti_campionato_precedente);
				
				}
				
				foreach($classifica_ammoniti_campionato as $ammonito) {
				
					$espulsioni = 0;
				
					foreach($classifica_espulsi_campionato as $espulso) {
					
						if($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id']) {
						
							$espulsioni = $espulso[0]['Tot'];
						
						}
					
					}
				
					$disciplinari_campionato[] = array(
					
						'Squadra' => $ammonito[0]['NomeSquadra'],
						'Atleta_id' => $ammonito[0]['atleta_id'],
						'Atleta' => $ammonito[0]['anagrafica'],
						'Ammonizioni' => $ammonito[0]['Tot'],
						'Espulsioni' => $espulsioni
					
					);
				
				}	

				$this->set('disciplinari_campionato', $disciplinari_campionato);
			
			}
			
			
			
	}
