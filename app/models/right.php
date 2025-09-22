<?

	class Right extends AppModel {
		
		
				var $name = 'Right';
				var $belongsTo = array(
					'Group' => array(
					'className' => 'Group',
					'foreignKey' => 'group_id'
					)
				); 
	
	}

?>
