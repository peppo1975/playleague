<?
	class BrComment extends AppModel {
	
		var $name = 'BrComment';
				
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(BrComment.created,'%d/%m/%Y %H:%i:%s')",
			'modified_it' => "DATE_FORMAT(BrComment.modified,'%d/%m/%Y %H:%i:%s')",
			//'img_evidenza' => "(SELECT files.path FROM files WHERE files.id = BrComment.id AND BrComment.file_id != 0)",
					
		);
				
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),	
				'user_id' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'report_id' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'content' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),				
				
			);
			
		}

	}
?>
