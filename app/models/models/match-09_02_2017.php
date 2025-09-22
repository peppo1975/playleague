<?


	class Match extends AppModel {
			
				var $name = 'Match';
				var $useTable = 'Calendari';
				var $primaryKey = 'Calendario';
								
				var $virtualFields = array(
				
					'inUso' => "(IF((SELECT Campionati.InCorso FROM Campionati WHERE Campionati.Campionato = Match.Campionato) = 'Si',0,1))",
					'CasaNome' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = Casa.Squadra)',
					'TrasfertaNome' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = Trasferta.Squadra)',
					'Data_it' => "DATE_FORMAT(Match.Data,'%d/%m/%Y')",
					'Data2' => "Match.Data",
					'Data3' => "Match.Data",
					'NomeArbitro' => '(SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.Arbitro)',
					'CountArbitro' => '
						CONCAT((SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.Arbitro),\'|\',
						
								IF (
								
									 (
										(@arbitro1casa :=
										(SELECT COUNT(*) FROM LDA as Lda2 WHERE Lda2.Campionato = Lda.Campionato AND (Lda2.Arbitro = Lda.Arbitro  OR Lda2.Arbitro2 = Lda.Arbitro) AND 
										
												(
													(Lda.Casa = Lda2.Casa OR Lda.Casa = Lda2.Trasferta)							
												)
												
										)
										) > 
										
										
										(@arbitro1trasferta :=
										(SELECT COUNT(*) FROM LDA as Lda2 WHERE Lda2.Campionato = Lda.Campionato AND (Lda2.Arbitro = Lda.Arbitro  OR Lda2.Arbitro2 = Lda.Arbitro) AND 
										
												(
													(Lda.Trasferta = Lda2.Casa OR Lda.Casa = Lda2.Trasferta)						
												)
												
										)
										) 
									
									),@arbitro1casa,@arbitro1trasferta
								
								),
								
								\'|\',@arbitro1casa,\'|\',@arbitro1trasferta
								
						)
					
					',
					

					
					'NomeArbitro2' => '(SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.Arbitro2)',

					

					'CountArbitro2' => '
					
							CONCAT((SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.Arbitro2),\'|\',
								IF (
								
									 (
										(@arbitro2casa :=
										(SELECT COUNT(*) FROM LDA as Lda2 WHERE Lda2.Campionato = Lda.Campionato AND  (Lda2.Arbitro = Lda.Arbitro2  OR Lda2.Arbitro2 = Lda.Arbitro2) AND 
										
												(
													(Lda.Casa = Lda2.Casa OR Lda.Casa = Lda2.Trasferta)						
												)
												
										)
										) > 
										
										
										(@arbitro2trasferta :=
										(SELECT COUNT(*) FROM LDA as Lda2 WHERE Lda2.Campionato = Lda.Campionato AND (Lda2.Arbitro = Lda.Arbitro2  OR Lda2.Arbitro2 = Lda.Arbitro2)  AND 
										
												(
													(Lda.Trasferta = Lda2.Casa OR Lda.Casa = Lda2.Trasferta)						
												)
												
										)
										) 
									
									),@arbitro2casa,@arbitro2trasferta
								
								),
								
								\'|\',@arbitro2casa,\'|\',@arbitro2trasferta
								
							)
					
					',

					'NomeDelegato' => '(SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.Delegato)',
					'NomeDelegatoA' => '(SELECT CONCAT(Atleti.Cognome," ",Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Lda.DelegatoA)',
					
					'Risultato' => '
					
						IF (
						Match.Data <= NOW() AND Match.Data != "0000-00-00 00:00:00" AND Match.CausaleRisultato NOT IN(2,3,8,9,10,12,14) OR (SELECT COUNT(*) FROM GoalPartite WHERE Match.Calendario = GoalPartite.Calendario) > 0,
						CONCAT(
						
						
							(	
							
								IF ((@casa := 
									(
									IF (
										(@goals := (SELECT SUM(GoalPartite.Goal) FROM GoalPartite WHERE Match.Calendario = GoalPartite.Calendario AND Match.Casa = GoalPartite.SquadraCampionato))
										
										IS NULL,0,@goals
										)
									
									+
									
									IF (
										(@autogoals := (SELECT SUM(GoalPartite.Autogoal) FROM GoalPartite WHERE Match.Calendario = GoalPartite.Calendario AND Match.Trasferta = GoalPartite.SquadraCampionato))
										
										IS NULL,0,@autogoals
										)
									)
									) IS NULL,0,@casa)
							)
							
							
							,"-",
							(
							
								IF ((@trasferta := 
									(
									IF (
										(@goals := (SELECT SUM(GoalPartite.Goal) FROM GoalPartite WHERE Match.Calendario = GoalPartite.Calendario AND Match.Trasferta = GoalPartite.SquadraCampionato))
										
										IS NULL,0,@goals
										)
									
									+
									
									IF (
										(@autogoals := (SELECT SUM(GoalPartite.Autogoal) FROM GoalPartite WHERE Match.Calendario = GoalPartite.Calendario AND Match.Casa = GoalPartite.SquadraCampionato))
										
										IS NULL,0,@autogoals
										)
									)
								
								) IS NULL,0,@trasferta)
								
							)

						),\'\')
					
					'
									
				);
				
				function beforeDelete() {
								
					if($this->field('inUso') == 1) {
					
						return true;
					
					} else {
					
						return false;				
				
					}
				
				}
				
				var $hasMany = array(
				
					'Matchgoal' => array(
						'className' => 'Matchgoal',
						'foreignKey' => 'Calendario'
					)
				
				);				
				
				var $belongsTo = array(
				
					'Campionati' => array(
					'className' => 'Campionati',
					'foreignKey' => 'Campionato'
					),
					
					'Casa' => array(
					'className' => 'SquadreCampionati',
					'foreignKey' => 'Casa',
					'fields' => array('SquadraCampionato','Squadra','Campionato','GironeCampionato','Campo','Ora','Giorno','Pagato'),
					),
					
					'Trasferta' => array(
					'className' => 'SquadreCampionati',
					'foreignKey' => 'Trasferta',
					'fields' => array('SquadraCampionato','Squadra','Campionato','GironeCampionato','Campo','Ora','Giorno','Pagato'),
					),
					
					'Half' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeCampionato'
					),
					
					'Campi' => array(
					'className' => 'Campi',
					'foreignKey' => 'Campo'
					),
					
					'Causalresult' => array(
					'className' => 'Causalresult',
					'foreignKey' => 'CausaleRisultato'
					),
					'Lda' => array(
					'className' => 'Lda',
					'foreignKey' => 'lda_id'
					)

				
				);


				function beforeValidate() {
					
					if (!empty( $this->data['Match']['Data'])) {
					
						$this->dmy2ymd($this->data['Match']['Data']);
						
				
					
					}
					
					return true;
				}

				function beforeSave() {
				
					if(isset($this->data) && !empty($this->data) && isset($this->data['Match']['Data'])) {
				
						App::Import('Model', 'Notgame');
						$Notgame = new Notgame;
					
						$notgames = $Notgame->find('list', array('fields' => array('Notgame.Data')));
						$notgames = array_merge($notgames);
						/*
						if(in_array($this->data['Match']['Data'], $notgames)) {
						
							$this->invalidate('Data','Data in giorno di non gioco.');
							return false;
						
						}
						*/
						
						$conditions = array(
						
							'Match.Campo' => $this->data['Match']['Campo'],
							'Match.Data'  => $this->data['Match']['Data'],
							'Match.Ora'   => $this->data['Match']['Ora'],
						
						);
						
						if(isset($this->data['Match']['Calendario']) && $this->data['Match']['Calendario'] != '') {
						
							$conditions['Match.Calendario !='] = $this->data['Match']['Calendario'];
						
						}
						
						$count = $this->find('count', array('conditions' => array($conditions)));

						if($count > 0 && $this->data['Match']['Campo'] != 'null' && $this->data['Match']['Ora'] != 'null') {
						
							$this->invalidate('Campo','Campo già occupato');
							$this->invalidate('Data','Data già occupata');
							$this->invalidate('Ora','Ora già occupata');
							$this->data['Lda']['Arbitro']  = $this->data['Match']['Arbitro'];
							$this->data['Lda']['Arbitro2'] = $this->data['Match']['Arbitro2'];
							$this->data['Lda']['Delegato'] = $this->data['Match']['Delegato'];
							$this->data['Lda']['DelegatoA']= $this->data['Match']['DelegatoA'];							
						
							return false;
						
						}
						
						App::Import('Model', 'CampiBooking');
						$CampiBooking = new CampiBooking;	
						
						$ora_booking  = str_replace('.',':',$this->data['Match']['Ora']);
						
						$count_booking = $CampiBooking->find('count', array(
						
							'conditions' => array(
								'CampiBooking.campo_id' => $this->data['Match']['Campo'],
								'CampiBooking.Data' 	=> $this->data['Match']['Data'],
								'CampiBooking.Ora'  	=> $ora_booking . ':00'
							)
						
						));	
						
						if($count_booking > 0) {
						
							$this->invalidate('Campo','Campo già occupato dalle prenotazioni online');
							$this->invalidate('Data','Data già occupata dalle prenotazioni online');
							$this->invalidate('Ora','Ora già occupata dalle prenotazioni online');
							$this->data['Lda']['Arbitro']  = $this->data['Match']['Arbitro'];
							$this->data['Lda']['Arbitro2'] = $this->data['Match']['Arbitro2'];
							$this->data['Lda']['Delegato'] = $this->data['Match']['Delegato'];
							$this->data['Lda']['DelegatoA']= $this->data['Match']['DelegatoA'];							
						
							return false;
						
						}							
					
					}
					
					return parent::beforeSave();
				
				}
				
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
				
						'Data' => array(
						
										'rule' => 'date',
										'message' => $this->getError('INVALID_DATE')

						),
						'Ora' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'CampionatoSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'GironeSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'SquadraCasaSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'SquadraTrasfertaSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'Giornata' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'Partita' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'CampoSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						
					);
					
				} 
				

	}

?>
