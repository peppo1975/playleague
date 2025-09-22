<?
	class Block extends AppModel {
	
		var $name = 'Block';
		
		var $virtualFields = array(
		
			'img_evidenza' => "(SELECT path FROM files WHERE files.id = Block.file_id)",
			'name_evidenza' => "(SELECT name FROM files WHERE files.id = Block.file_id)",
			'descrizione_evidenza' => "(SELECT description FROM files WHERE files.id = Block.file_id)",
			'created_it' => "DATE_FORMAT(Block.created,'%d/%m/%Y %H:%i:%s')",
			'created_form' => "DATE_FORMAT(Block.created,'%d/%m/%Y')",
			'modified_it' => "DATE_FORMAT(Block.modified,'%d/%m/%Y %H:%i:%s')",
			'published_it' => "DATE_FORMAT(Block.published,'%d/%m/%Y')",
			 // Data fine news dalla redazione e ultim'ora 04/05/2018
        	'over_it' => "DATE_FORMAT(Block.over,'%d/%m/%Y')",
			'type_it'    => "IF(Block.type = 0, 'Mostra tutto','Mostra anteprima')",
			'mother_page' => "SELECT title FROM pages WHERE pages.id = Block.page_id",
			'page_url'   => "IF((SELECT title FROM pages WHERE pages.id = Block.url_page_id) != '', (SELECT title FROM pages WHERE pages.id = Block.url_page_id), 'Nessuno')",
					
		);
		
		var $hasMany = array(
		
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'block_id',
				'order' => array('Upload.order' => 'ASC')
			
			),
			'UploadYt' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'block_id',
				'conditions' => array('UploadYt.group' => 'youtube'),
				'order' => array('UploadYt.order' => 'ASC')
			
			),			
		
		);
		
		var $belongsTo = array(
		
			'Page' => array(
				'className' => 'Page',
				'foreignKey' => 'page_id'
			),
			
			'PageURL' => array(
			
				'className' => 'Page',
				'foreignKey' => 'url_page_id'
			
			)
		
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
			
			$order = $this->getOrder($this->name);
			
			$this->order = array(
			
				'Block.' . $order['Order']['argument'] => $order['Order']['order_type'],
			
			);					
			
		} 
		/*---------------------------------------------------------
			IN CASO DI RALLENTAMENTI COMMENTARE QUESTA FUNZIONE
		----------------------------------------------------------*/
		public function afterFind($results, $primary = false)
		{
			if(!empty($_SERVER["REQUEST_URI"]) && !substr_count($_SERVER["REQUEST_URI"], "admin"))
			{
				foreach($results as $k => $res)
				{
					if(isset($res["Block"]["content"]))
					{
						$results[$k]["Block"]["content"] = doShortCode($res["Block"]["content"]);
					}
				}
				return $results;
			}
			return $results;
		}
		/*---------------------------------------------------------
		----------------------------------------------------------*/

		function beforeSave() {

			// Data fine news dalla redazione e ultim'ora 04/05/2018
        	if (!empty($this->data['Block']['over']) && $this->data['Block']['over'] != '00/00/0000'){
	            $this->dmy2ymd($this->data['Block']['over']);        
	            $this->data['Block']['over'] .= " 23:59:59";
        	}
		
			if (!empty($this->data['Block']['published']) && $this->data['Block']['published'] != '00/00/0000') {
			
				$this->dmy2ymd($this->data['Block']['published']);
				$this->data['Block']['disabled']  = 0;
			
			}
			// else {
			
				// $this->data['Block']['published'] = '';
				// $this->data['Block']['disabled']  = 1;
			
			// }
			
			if(isset($this->data['Block']['created']) && !empty($this->data['Block']['created'])) {
			
				$this->dmy2ymd($this->data['Block']['created']);
			
			}

			if($this->data['Block']['url'] = 'http://') $this->data['Block']['url'] = '';
			
			return parent::beforeSave();
		
		}
	
	}
?>
