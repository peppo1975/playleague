<?

	class Event extends AppModel {
			
				var $name = 'Event';
				var $useTable = 'events';

				var $hasMany = array(
				
		
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'event_id',
				'order' => array('Upload.order' => 'ASC')
			
			),
				); 


		var $virtualFields = array(
		
			'published_it' => "DATE_FORMAT(Event.data_inizio,'%d/%m/%Y')",
					
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


		$order = $this->getOrder($this->name);
			
			$this->order = array(
			
				'Event.' . $order['Order']['argument'] => $order['Order']['order_type'],
			
			);	

					
				}
				
				
	}