<?

	class BannersRowsController extends AppController {
	
		var $name = "BannersRows";
		var $helpers = array('Backend','Javascript','Cksource');
		var $uses = array('Upload','Banner','BannersRow');
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
				
					$this->BannersRow->set($this->data);
					
					if ($this->BannersRow->save()) {
						
						$ADD_OK = true;
													
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
								
					$this->data = $this->BannersRow->find('first',array('conditions' => array('BannersRow.id' => $id)));
				
				} else {
										
				$this->BannersRow->set($this->data);
				
					if ($this->BannersRow->save()) {
					
						$ADD_OK = true;		
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}	
	
	}
