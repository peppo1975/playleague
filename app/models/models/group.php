<?

	class Group extends AppModel {
			
				var $name = 'Group';
				var $actsAs = array(
					'Containable'
				);
				var $hasMany = array(
				
					'Right' => array(
						'className' => 'Right',
						'foreignKey' => 'group_id'
					)
					
				);
			

	
	}

?>
