<?

	class CampiBooking extends AppModel {
			
				var $name = 'CampiBooking';
				var $useTable = 'CampiBooking';
				
				var $belongsTo = array(
				
					'Campi' => array(
						
						'className' => 'Campi',
						'foreignKey' => 'campo_id',
					
					),
				
				);
								
	

	}

?>
