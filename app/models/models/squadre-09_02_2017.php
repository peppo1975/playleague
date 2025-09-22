<?

	class Squadre extends AppModel {
			
				var $name = 'Squadre';
				var $useTable = 'Squadre';
				var $primaryKey = 'Squadra';
				
				var $hasMany = array(
				
					'Upload' => array(
						'className' => 'Upload',
						'foreignKey' => 'squadra_id',
						'order' => array('Upload.order' => 'ASC')
					),
					'SquadreAlbo' => array(
						'className' => 'SquadreAlbo',
						'foreignKey' => 'Squadra'
					),
				
				);				

				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Denominazione' => array(

							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						)
						
					);
					
				}
				
				function beforeDelete() {
					
					App::Import('Model','Teambook');
					$teambook = new Teambook;
					
					$count_anni = $teambook->find('count', array(
					
						'conditions' => array(
						
							'Teambook.Squadra' => $this->field('Squadra')
							
						),
					
					));
					
					if($count_anni > 0) return false;
					
					return true;
					
				}

	}

?>
