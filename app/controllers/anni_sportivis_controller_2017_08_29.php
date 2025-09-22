<?

	class AnniSportivisController extends AppController {
	
			var $name = "AnniSportivis";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('AnniSportivi');
						
			function admin_index() {
				
					
				
			}
			
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write(ucfirst(Inflector::underscore($this->name)) . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
						
				if (!empty($this->data)) {
					
					$this->Session->write(ucfirst(Inflector::underscore($this->name)) . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check(ucfirst(Inflector::underscore($this->name)) . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read(ucfirst(Inflector::underscore($this->name)) . ".searchData");
					
				} 
			
			}

 			function admin_add() {
			
				$this->layout = "ajax";	
				
				if (!empty($this->data)) {
				
					$this->AnniSportivi->set($this->data);
					
					if ($this->AnniSportivi->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_edit() {
			
				$this->layout = "ajax";
			
			}	
	}
