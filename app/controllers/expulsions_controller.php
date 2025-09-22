<?

	class ExpulsionsController extends AppController {
	
			var $name = "Expulsions";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Expulsion');
						
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
				
					$this->Expulsion->set($this->data);
					
					if ($this->Expulsion->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_searchExpulsion() {
			
				$this->layout = "ajax";
			
				$Expulsions = $this->Expulsion->find('all', array(
				
					'conditions' =>
					array(
					
								'Expulsion.Espulsione LIKE' => $_GET['term'] . '%'
					
						),
					
					'order' => 'Expulsion.Espulsione ASC',
					'limit' => '15',
					
				));
				
				$ret = array();
				
				foreach ($Expulsions as $Expulsion) {
					
					$tmp['id'] = $Expulsion['Expulsion']['Espulsione'];
					$tmp['label'] = $Expulsion['Expulsion']['Espulsione'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
			
			}
	
	}
