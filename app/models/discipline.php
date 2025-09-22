<?

	class Discipline extends AppModel {
			
				var $name = 'Discipline';
				var $useTable = 'Disciplinare';
				var $primaryKey = 'Disciplinare';
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(

										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
										
						),
						'Punti' => array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
						),
						'Sanzione' => array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
						),
						
					);
					
				} 

	}

?>
