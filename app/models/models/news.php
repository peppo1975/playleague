<?
	class News extends AppModel {
	
		var $name = 'News';
		
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(News.created,'%d/%m/%Y %H:%i:%s')",
			'modified_it' => "DATE_FORMAT(News.modified,'%d/%m/%Y %H:%i:%s')",
			'published_it' => "DATE_FORMAT(News.published,'%d.%m.%Y')",

		);
				
	
		var $hasMany = array(
		
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'news_id',
				'order' => array('Upload.order' => 'ASC')
			
			),
		
		);

								
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$order = $this->getOrder($this->name);
     
			$this->order = array(
     
				$order['Order']['argument'] => $order['Order']['order_type'],
     
			);
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
		
				
			);
			
		}
		
		function beforeValidate() {
					
			if (!empty( $this->data['News']['published'])) {
			
				$this->dmy2ymd($this->data['News']['published']);

			}
			
			return true;		
			
		}
		
	}
