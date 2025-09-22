<?

	class LdaWall extends AppModel {
			
				var $name = 'LdaWall';
				var $useTable = 'LDA_wall';
				
				var $virtualFields = array(
				
					'created_it' => "DATE_FORMAT(LdaWall.created,'%d/%m/%Y %H:%i:%s')",
					'modified_it' => "DATE_FORMAT(LdaWall.modified,'%d/%m/%Y %H:%i:%s')",
					'published_it' => "DATE_FORMAT(LdaWall.published,'%d/%m/%Y')",
				
				);
				
				var $hasMany = array(
				
					'Upload' => array(
					
						'className' => 'Upload',
						'foreignKey' => 'lda_wall_id',
						'order' => array('Upload.order' => 'ASC')
					
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
				
				function beforeSave() {
				
					if (!empty($this->data['LdaWall']['published']) && $this->data['LdaWall']['published'] != '00/00/0000') {
					
						$this->dmy2ymd($this->data['LdaWall']['published']);
					
					}
					return parent::beforeSave();
				
				}				
			
	}