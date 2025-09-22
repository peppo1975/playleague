<?

function getValue($value) {
	
	if($value == 1) return 'Configurazione predefinita';
	return '';
	
}

	class NewsletterConfigsController extends AppController {
	
			var $name = "NewsletterConfigs";
			var $helpers = array('Backend');
			var $uses = array('NewsletterConfig','NewsletterAccount');
			
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
				
				$accounts = $this->NewsletterAccount->find('list', array('fields' => array('NewsletterAccount.username')));
						
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
				
				$accounts = $this->NewsletterAccount->find('list', array('fields' => array('NewsletterAccount.username')));
				$this->set('accounts', $accounts);
								
				if (!empty($this->data)) {
				
					$this->NewsletterConfig->set($this->data);
					
					if ($this->NewsletterConfig->save()) {
						
						$ADD_OK = true;
						
						if($this->data['NewsletterConfig']['is_default'] == 1) {
			
						    $this->NewsletterConfig->updateAll(
						    array('NewsletterConfig.is_default' => 0),
						    array('NewsletterConfig.id !=' => $this->NewsletterConfig->id)
						    );
							
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
				
				$accounts = $this->NewsletterAccount->find('list', array('fields' => array('NewsletterAccount.username')));
				$this->set('accounts', $accounts);
				
				if (empty($this->data)) {
								
					$this->data = $this->NewsletterConfig->find('first',array('conditions' => array('NewsletterConfig.id' => $id)));
					$this->NewsletterConfig->set($this->data);
				
				} else {
										
				$this->NewsletterConfig->set($this->data);
				
				if($this->data['NewsletterConfig']['is_default'] == 1) {
				    $this->NewsletterConfig->updateAll(
				    array('NewsletterConfig.is_default' => 0),
				    array('NewsletterConfig.id !=' => $id)
				    );
				}						
				
				$ADD_OK = true;

					if ($this->NewsletterConfig->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}
										
	}
	
?>