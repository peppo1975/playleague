<?

	class LdaWallsController extends AppController {
	
		var $name = "LdaWalls";
		var $helpers = array('Backend','Javascript','Cksource');
		var $uses = array('LdaWall','Upload');
		var $login_required = true;
		
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
				
					$this->LdaWall->set($this->data);
					
					if ($this->LdaWall->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('lda_wall_id',$this->LdaWall->id) == true) {
						
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
								
					$this->data = $this->LdaWall->find('first',array('conditions' => array('LdaWall.id' => $id)));
					$this->data['LdaWall']['published'] = ($this->data['LdaWall']['published_it'] != '00/00/0000')? $this->data['LdaWall']['published_it'] : '';
				
				} else {
										
				$this->LdaWall->set($this->data);
				
					if ($this->LdaWall->save()) {
					
						$ADD_OK = true;		
						
						if ($this->__adminUploadFile('lda_wall_id',$id) == true) {
						
							$ADD_OK = true;
							
						}							
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}	
			
			function index() {
				
				$this->layout = "content";
				$this->login_site = true;
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					$this->set('messages',$this->getMessage());
					
				}
				
			}	
			
			function getMessage() {
				
				return $this->LdaWall->find('all', array(
				
					'conditions' => array(
						'LdaWall.disabled' => 0,
						'LdaWall.published <= NOW()',
					),	
					'order' => array('LdaWall.published DESC')
				
				));
				
			}		
			
}