<?
	class Stream extends AppModel {
	
		var $name = 'Stream';
		var $useTable = 'streams';
		
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(Stream.created,'%d/%m/%Y')",
			'modified_it' => "DATE_FORMAT(Stream.modified,'%d/%m/%Y')",
		
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
		
		// function afterSave() {
		
			// $this->setMetadata($this->name);
		
		// }
		
	}
?>
