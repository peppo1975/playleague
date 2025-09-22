<?
	class Newsletter extends AppModel {
	
		var $name = 'Newsletter';
		
		var $hasMany = array(
		
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'newsletter_id'
			
			),
		
		);
		
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
			
			$this->virtualFields = array(
			
				'created_it' => "DATE_FORMAT(Newsletter.created,'%d/%m/%Y %H:%i:%s')",
				'modified_it' => "DATE_FORMAT(Newsletter.modified,'%d/%m/%Y %H:%i:%s')",
						'published_it' => "DATE_FORMAT(Newsletter.published,'%d/%m/%Y')",

			);			
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				
			);
			
		}
	
		function beforeSave() {
		
			if (!empty($this->data['Newsletter']['published']) && $this->data['Newsletter']['published'] != '00/00/0000') {
			
				$this->dmy2ymd($this->data['Newsletter']['published']);

			
			}
			return parent::beforeSave();
		
		}
	
	}
	
?>