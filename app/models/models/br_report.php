<?
	class BrReport extends AppModel {
	
		var $name = 'BrReport';
		
		var $hasMany = array(
		
			'BrComment' => array(
			
				'className' => 'BrComment',
				'foreignKey' => 'report_id'
			
			),
			
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'brreport_id',
			
			),			
			
		);
		
		var $belongsTo = array(
		
			'BrZone' => array(
			
				'className' => 'BrZone',
				'foreignKey' => 'zone_id'
			
			),
			'BrCategory' => array(
			
				'className' => 'BrCategory',
				'foreignKey' => 'category_id'
			
			),			
		
		);	
				
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(BrReport.created,'%d/%m/%Y %H:%i:%s')",
			'modified_it' => "DATE_FORMAT(BrReport.modified,'%d/%m/%Y %H:%i:%s')",
			'img_evidenza' => "(SELECT files.path FROM files WHERE files.id = BrReport.id AND BrReport.file_id != 0)",
			'author'      => "SELECT CONCAT(cognome,' ',nome) FROM users WHERE users.id = BrReport.user_id",
					
		);
				
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(

				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),			
				'priority' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'category_id' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'zone_id' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'type_request' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),			
				
			);
			
		}

	}
?>
