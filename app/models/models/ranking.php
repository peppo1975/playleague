<?

	class Ranking extends AppModel {
			
				var $name = 'Ranking';
				var $useTable = 'Classifiche';
				var $primaryKey = 'Classifica';
				
				var $virtualFields = array(
				
					'NomeSquadra' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = SquadreCampionati.Squadra)',
					'NomeCampionato' => '(SELECT Campionati.Nome FROM Campionati WHERE Campionati.Campionato = SquadreCampionati.Campionato)',
									
				);
				
				var $belongsTo = array(
				
					'Half' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeCampionato'
					),
					
					'SquadreCampionati' => array(
					'className' => 'SquadreCampionati',
					'foreignKey' => 'SquadraCampionato'
					)
				
				);
				
				function beforeValidate() {
				
					if(!isset($this->data['Ranking']['Classifica'])) {
					
						$unique_check = array(
								'GironeCampionato' => $this->data[$this->name]["GironeCampionato"],
								'SquadraCampionato' => $this->data[$this->name]["SquadraCampionato"]
						);

						if (!$this->isUnique($unique_check)) {
						
							$this->invalidate('NomeSquadra',$this->getError('DUPLICATE_RECORD'));
							
						}
					}
				
				} 
				
				// function beforeSave() {
					
					// if (!empty( $this->data['Match']['Data'])) {
					
						// $this->dmy2ymd($this->data['Match']['Data']);

					
					// }
				
					// return true;
				// }

				
				
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
				
						'SquadraCampionato' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'GironeCampionato' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'Giocate' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Punti' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Vinte' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Perse' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'Nulle' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GiocateCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'VinteCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'PerseCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'NulleCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GiocateFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'VinteFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'PerseFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'NulleFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalFatti' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalSubiti' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalSubitiFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalSubitiCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalFattiFuori' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'GoalFattiCasa' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
						'CoppaDisciplina' => array(
						
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')

						),
					);
					
				} 
				

	}

?>
