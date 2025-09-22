<?

	class AnniSportivi extends AppModel {
			
				var $name = 'AnniSportivi';
				var $useTable = 'AnniSportivi';
				var $primaryKey = 'AnnoSportivo';
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'AnnoSportivo' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
							'isUnique' => 
								array(
										'rule' => 'isUnique',
										'message' => $this->getError('DUPLICATE_RECORD')
								),
							'numeric' => 	
								array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
								)		
						),
						
					);
					
				}
				
				function beforeDelete() {
				
					$id = $this->id;
					
					App::Import('Model', 'Campionati');
					$Campionati = new Campionati;
					
					$count = $Campionati->find('count', array(
					
						'conditions' => array(
						
							'Campionati.AnnoSportivo' => $id,
						
						),
					
					));
					
					if($count == 0) {
					
						return true;
					
					} else {
					
						return false;
					
					}
				
				}

	}

?>
