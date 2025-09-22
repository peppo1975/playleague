<?
	class BrCategory extends AppModel {
	
		var $name = 'BrCategory';
				
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(BrCategory.created,'%d/%m/%Y %H:%i:%s')",
			'modified_it' => "DATE_FORMAT(BrCategory.modified,'%d/%m/%Y %H:%i:%s')",
			//'img_evidenza' => "(SELECT files.path FROM files WHERE files.id = BrCategory.id AND BrCategory.file_id != 0)",
					
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
