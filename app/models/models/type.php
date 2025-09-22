<?

	class Type extends AppModel {
			
				var $name = 'Type';
				var $useTable = 'types';

				
		var $belongsTo = array(
		
			'Event' => array(
				'className' => 'Event',
				'foreignKey' => 'event_id'
			),
			
		);
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
						'Nome' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								)
						),
						
						
					);
					
				}
				
				
	}