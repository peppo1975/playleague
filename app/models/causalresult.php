<?

	class Causalresult extends AppModel {
			
				var $name = 'Causalresult';
				var $useTable = 'CausaliRisultato';
				var $primaryKey = 'CausaleRisultato';
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(

										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
										
						),
						'Sanzione' => array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
						),
						'PuntiDisciplina' => array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
						),
						
					);
					
				} 

	}

?>
