<?

	class TipiAssicurazione extends AppModel {
			
				var $name = 'TipiAssicurazione';
				var $useTable = 'TipiAssicurazione';
				var $primaryKey = 'TipoAssicurazione';

				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(

										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
										
						),
						'Costo' => array(

										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
										
						),

						
					);
					
				}

	}

?>
