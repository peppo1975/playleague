<?

	class Matchgoal extends AppModel {
			
				var $name = 'Matchgoal';
				var $useTable = 'GoalPartite';
				var $primaryKey = 'GoalPartita';
				
				 var $virtualFields = array(
				
					'NomeSquadra' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Matchgoal.SquadraCampionato))',
					'NomeCampionato' => '(SELECT Campionati.Nome FROM Campionati WHERE Campionati.Campionato = (SELECT SquadreCampionati.Campionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Matchgoal.SquadraCampionato))',
					'EspulsioneInizio_it' => "DATE_FORMAT(EspulsioneInizio,'%d/%m/%Y')",
					'EspulsioneFine_it' => "DATE_FORMAT(EspulsioneFine,'%d/%m/%Y')",
					'Data' => '(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = Matchgoal.Calendario)',
					'AnnoSportivo' => '(SELECT YEAR(Calendari.Data) FROM Calendari WHERE Calendari.Calendario = Matchgoal.Calendario)',
					'Data_it' => '(SELECT DATE_FORMAT(Calendari.Data, "%d/%m/%Y") FROM Calendari WHERE Calendari.Calendario = Matchgoal.Calendario)',
					'DatiAtleta' => "(SELECT CONCAT(Atleti.Cognome,' ', Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = Matchgoal.Atleta)",
									
				);
				
				var $belongsTo = array(
									
					'SquadreCampionati' => array(
					'className' => 'SquadreCampionati',
					'foreignKey' => 'SquadraCampionato'
					),
					
					'Athlete' => array(
					'className' => 'Athlete',
					'foreignKey' => 'Atleta'
					),
					
				);
							
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
				
						'Calendario' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'SquadraCampionato' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						// 'Atleta' => array(
						
						
							// 'isUnique' =>
								// array(
										// 'rule' => 'isUnique',
										// 'message' => $this->getError('DUPLICATE_RECORD')
								// ),
								
							// 'notEmpty' => 
								// array(
										// 'rule' => 'notEmpty',
										// 'message' => $this->getError('REQUIRED_FIELD')
								// ),
						
						
						// ),
						'Goal' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Autogoal' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Ammonizione' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'Espulsione' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						
					);
					
				} 
				
				function beforeSave() {
					
					if (!empty( $this->data['Matchgoal']['EspulsioneInizio'])) {
					
						$this->dmy2ymd($this->data['Matchgoal']['EspulsioneInizio']);

					
					}
					
					if (!empty( $this->data['Matchgoal']['EspulsioneFine'])) {
					
						$this->dmy2ymd($this->data['Matchgoal']['EspulsioneFine']);

					
					}
					
					
					return parent::beforeSave();
				}				
				

	}

?>
