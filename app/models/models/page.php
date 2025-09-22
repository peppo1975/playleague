<?
	class Page extends AppModel {
	
		var $name = 'Page';
		var $actsAs = array('Tree');
		
		var $hasMany = array(
		
			'Upload' => array(
			
				'className' => 'Upload',
				'foreignKey' => 'page_id',
				'order' => array('Upload.order' => 'ASC')
			
			),
			'Block' => array(
			
				'className' => 'Block',
				'foreignKey' => 'page_id',
			
			),
		
		);
		
		
		var $hasAndBelongsToMany = array(
	
			'Menu' =>
		
				array(
				
					'className' => 'Menu',
					'joinTable' => 'pages_as_menus',
					'foreignKey' => 'page_id',
					'associationForeignKey' => 'menu_id',
					'unique' => true,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
					'deleteQuery' => '',
					'insertQuery' => ''
				)
		
		);		
				
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(Page.created,'%d/%m/%Y %H:%i:%s')",
			'Genitore'   => "(SELECT pagine.title FROM pages as pagine WHERE pagine.id = Page.parent_id LIMIT 1)",
			'modified_it' => "DATE_FORMAT(Page.modified,'%d/%m/%Y %H:%i:%s')",
			'published_it' => "DATE_FORMAT(Page.published,'%d/%m/%Y')",
			'mother_page' => "(SELECT Page2.title FROM pages AS Page2 WHERE Page2.id = Page.page_id)",
			'img_evidenza' => "(SELECT files.path FROM files WHERE files.id = Page.id AND Page.file_id != 0)",
			'type_it'     => "IF((Page.type = 'static'),'Pagina statica',IF((Page.type = 'dinamic'),'Pagina dinamica','Pagina esterna'))",
					
		);
			
		function afterFind($data) {
		
			require_once APP . 'libs/Mobile_Detect.php';
			$detect = new Mobile_Detect();
			
			$layout = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');		
			
			if($layout == "mobile") {
			
				if(!empty($data)) {
			
					foreach($data as &$d) {
				
						if(isset($d['Page']['title']) && isset($d['Page']['title_mobile'])) {
					
							if($d['Page']['title_mobile'] != "")
								$d['Page']['title'] = $d['Page']['title_mobile'];
					
						}
				
					}
			
				}
			
			}
			
			return $data;
			
		}			
				
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'type' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),				
				
			);
			
			$order = $this->getOrder($this->name);
			
			$this->order = array(
			
				$order['Order']['argument'] => $order['Order']['order_type'],
			
			);
		
		}
		
		function beforeSave() {
		
			if(isset($this->data['Page']['type']) && !empty($this->data)) {
		
			switch($this->data['Page']['type']) {
			
				case 'static':
				
					$this->data['Page']['controller'] = '';
					$this->data['Page']['action'] 	  = '';
					$this->data['Page']['params'] 	  = '';
					$this->data['Page']['url']		  = '';
				
				break;
				
				case 'dinamic':
					
					$this->data['Page']['content'] = '';
					$this->data['Page']['url'] 	   = '';
					
					$exit = true;
					
					if(empty($this->data['Page']['controller'])) {
					
						$exit = false;
						$this->invalidate('controller',$this->getError('REQUIRED_FIELD'));
					
					}
					if(empty($this->data['Page']['action'])) {
					
						$exit = false;
						$this->invalidate('action',$this->getError('REQUIRED_FIELD'));
					
					}					
					
				break;
				
				case 'url':
				
					$this->data['Page']['controller'] = '';
					$this->data['Page']['action'] 	  = '';
					$this->data['Page']['params'] 	  = '';	
					$this->data['Page']['content'] = '';					
				
				break;
			
			}
			
			if($this->data['Page']['alias'] == '') {
			
				$this->data['Page']['alias'] = $this->data['Page']['title'];
			
			}
			
			if(isset($this->data['Page']['published']) && !empty($this->data['Page']['published'])) {
			
				$this->dmy2ymd($this->data['Page']['published']);
			
			}
			
			}
				
			return (isset($exit))? $exit:true;
		
		}
		
		function afterSave() {
		
			if(isset($this->data['Metadata'])) {
		
				$this->setMetadata($this->name);
			
			}
					
		}

	}
?>
