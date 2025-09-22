<?
	class BrZone extends AppModel {
	
		var $name = 'BrZone';
				
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(BrZone.created,'%d/%m/%Y %H:%i:%s')",
			'modified_it' => "DATE_FORMAT(BrZone.modified,'%d/%m/%Y %H:%i:%s')",
			//'img_evidenza' => "(SELECT files.path FROM files WHERE files.id = BrCategory.id AND BrCategory.file_id != 0)",
					
		);
		
		var $hasMany = array(
		
			'BrReport' => array(
			
				'className' => 'BrReport',
				'foreignKey' => 'zone_id',
			
			),
		
		);
				
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),		
				
			);
			
		}

	}
?>
