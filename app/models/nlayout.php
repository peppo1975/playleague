<?
	class Nlayout extends AppModel {
	
		var $name = 'Nlayout';
		var $useTable = 'layouts';
		
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
			
			$this->virtualFields = array(
			
				'created_it' => "DATE_FORMAT(Nlayout.created,'%d/%m/%Y %H:%i:%s')",
				'modified_it' => "DATE_FORMAT(Nlayout.modified,'%d/%m/%Y %H:%i:%s')",
						'published_it' => "DATE_FORMAT(Nlayout.published,'%d/%m/%Y')",

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
		
			if (!empty($this->data['Nlayout']['published']) && $this->data['Nlayout']['published'] != '00/00/0000') {
			
				$this->dmy2ymd($this->data['Nlayout']['published']);

			
			}
			return parent::beforeSave();
		
		}
	
	}
	
?>