<?

	class Notgame extends AppModel {
			
				var $name = 'Notgame';
				var $useTable = 'GiorniNonGioco';
				var $primaryKey = 'GiornoNonGioco';
				
				var $virtualFields = array(
				
					'Data_it' => "DATE_FORMAT(Notgame.Data,'%d/%m/%Y')",
	
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Data' => array(
								
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
				
				function beforeSave() {
					
					if (!empty( $this->data['Notgame']['Data'])) {
					
						$this->dmy2ymd($this->data['Notgame']['Data']);

					
					}
				
					return parent::beforeSave();
				}

	}

?>
