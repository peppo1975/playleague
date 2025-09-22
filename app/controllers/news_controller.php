<?

function checkLastHour($value) {

	$out = '';
	
	if($value == 0) {
	
		$out = 'No';
	
	} else {
	
		$out = 'Si';
	
	}
	
	return $out;

}

	class NewsController extends AppController {
	
		var $name = "News";
		var $helpers = array('Backend','Javascript','Cksource','Text');
		var $uses = array('Upload','News','Order');
		var $login_required = true;
		
			function beforeFilter() {
			
				parent::beforeFilter();

			}		
		
			function home() {

				
			}
		
			function admin_index() {
				
					
				
			}
						
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function getNews() {
				
				$data = $this->News->find('all', array(
				
					'conditions' => array(
					
						'News.disabled' => 0,
						'News.published <= NOW()',
					
					),
					'order' => 'News.published DESC',
					'limit' => 15
				
				));
				
				return $data;
				
			}
			
			function getNewsLastHour() {
				
				$data = $this->News->find('all', array(
				
					'conditions' => array(
					
						'News.disabled' => 0,
						'News.isLastHour' => 1,
						'News.published <= NOW()',
					
					),
					'order' => 'News.published DESC',
					'limit' => 6,
				
				));
				
				return $data;
				
			}			
			
			function jsonNews($id) {
				
				$this->layout = "ajax";
				
				$lang = $this->Session->read('lang');
					
				$new = $this->News->findById($id);
					
				$news['title'] = ($lang == "it")? $new['News']['title'] : $new['News']['title_' . $lang];
				$new['News']['content'] = strip_tags($new['News']['content']);
				$news['content'] = ($lang == "it")? $new['News']['content'] : $new['News']['content_' . $lang];
				
				$this->set('result',json_encode($news));
				
				$this->render("/backend/ajaxResult");
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
						
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
			
			}

 			function admin_add() {
			
				$this->layout = "ajax";	

				if (!empty($this->data)) {
				
					$this->News->set($this->data);
					
					if ($this->News->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('news_id',$this->News->id) == true) {
						
							$ADD_OK = true;
							
						}						
													
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				if (empty($this->data)) {
								
					$this->data = $this->News->find('first', array('conditions' => array('News.id' => $id)));
										
					if (!empty($this->data['News']['published'])) $this->data['News']['published'] = date("d/m/Y",strtotime($this->data['News']['published']));
												
					$this->News->set($this->data);
				
				} else {
										
				$this->News->set($this->data);
				
					if ($this->News->save()) {
					
						$ADD_OK = true;	

						if ($this->__adminUploadFile('news_id',$id) == true) {
						
							$ADD_OK = false;
							
						}							
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}	

			function read($id = null) {
			
				$this->layout = "page";
				
				$data = $this->News->findById($id);
				$this->set('page', $data);
			
			}
	
	}
