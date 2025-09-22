<?

	class SquadreAlbo extends AppModel {
			
				var $name = 'SquadreAlbo';
				var $useTable = 'SquadreAlbo';

				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Campionato' => array(

							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						),
						'Posizione' => array(

							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						),
						'Squadra' => array(

							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						),												
						
					);
					
				}

	}

?>
