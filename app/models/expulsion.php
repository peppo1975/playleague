<?

	class Expulsion extends AppModel {
			
				var $name = 'Expulsion';
				var $useTable = 'Espulsioni';
				var $primaryKey = 'Espulsione';
				
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Espulsione' => array(
								
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

	}

?>
