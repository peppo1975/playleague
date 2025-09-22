<?


	class Half extends AppModel {
			
				var $name = 'Half';
				var $useTable = 'GironiCampionati';
				var $primaryKey = 'GironeCampionato';
				
				var $belongsTo = array(
				
					'Campionati' => array(
					'className' => 'Campionati',
					'foreignKey' => 'Campionato'
					)
				
				);
				
				var $virtualFields = array(
				
					'DataInizio_it' => "DATE_FORMAT(Half.DataInizio,'%d/%m/%Y')",					
					'inUso' => "(IF((SELECT Campionati.InCorso FROM Campionati WHERE Campionati.Campionato = Half.Campionato) = 'Si',0,1))"
				
				);
				
				function beforeDelete() {
				
					// if($this->field('inUso') == 1) {
					
						// return true;
					
					// } else {
					
						// return false;				
				
					// }
					
					$id = $this->id;
					
					App::Import('Model', 'SquadreCampionati');
					$SquadreCampionati = new SquadreCampionati;
					
					$hasTeam = $SquadreCampionati->find('count', array('conditions' => array(
					
						'SquadreCampionati.GironeCampionato' => $id,
					
					)));
					
					if($hasTeam != 0) return false;
					
					return true;
				
				}
				
				function beforeSave() {
				
					if (!empty( $this->data['Half']['DataInizio'])) {
					
						$this->dmy2ymd($this->data['Half']['DataInizio']);

					
					}
					
					return parent::beforeSave();
				
				}

				
				/*
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
							'isUnique' => 
								array(
										'rule' => 'isUnique',
										'message' => $this->getError('DUPLICATE_RECORD')
								)
						),
						
					);
					
				} 
				*/

	}

?>
