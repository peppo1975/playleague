<?

	class DisciplinesController extends AppController {
	
			var $name = "Disciplines";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Discipline');
						
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
				
					$this->Discipline->set($this->data);
					
					if ($this->Discipline->save()) {
						
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
								
					$this->data = $this->Discipline->find('first',array('conditions' => array('Discipline.Disciplinare' => $id)));
					$this->Discipline->set($this->data);
				
				} else {
										
				$this->Discipline->set($this->data);
				
				$ADD_OK = true;

					if ($this->Discipline->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}	
	
	}
