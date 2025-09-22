<?

	function getValueAccount($value) {

		if($value == 1) {
		
			$ret = "<input id=\"checkbox_".$value."\" type=\"checkbox\" class=\"getValue\" value=\"$value\" checked=\"checked\" />";
		
		} else {
		
			$ret = "<input id=\"checkbox_".$value."\" type=\"checkbox\" class=\"getValue\" value=\"$value\" />";
		
		}
		
		return $ret;
	
	}
	
	function getDefault($value) {

		if($value == 1) {
		
			$ret = "<input id=\"checkbox_".$value."\" type=\"checkbox\" class=\"getDefault\" value=\"$value\" checked=\"checked\" />";
		
		} else {
		
			$ret = "<input id=\"checkbox_".$value."\" type=\"checkbox\" class=\"getDefault\" value=\"$value\" />";
		
		}
		
		return $ret;
	
	}

	class NewsletterAccountsController extends AppController {
	
			var $name = "NewsletterAccounts";
			var $helpers = array('Backend');
			var $uses = array('NewsletterAccount');
			
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
				
					$this->NewsletterAccount->set($this->data);
					
					if ($this->NewsletterAccount->save()) {
						
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
								
					$this->data = $this->NewsletterAccount->find('first',array('conditions' => array('NewsletterAccount.id' => $id)));
					//$this->data['NewsletterAccount']['password'] = '';
					$this->NewsletterAccount->set($this->data);
				
				} else {
										
				$this->NewsletterAccount->set($this->data);
				
				$ADD_OK = true;

					if ($this->NewsletterAccount->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}
			
			function admin_setValue($id,$field) {
			
				$this->layout = "ajax";
				
				$this->data = $this->NewsletterAccount->findById($id);

				if($this->data['NewsletterAccount'][$field] == 1) {
				
					$this->data['NewsletterAccount'][$field] = 0;
				
				} else if($this->data['NewsletterAccount'][$field] == 0) {
				
					$this->data['NewsletterAccount'][$field] = 1;
				
				}
				
				$value = $this->data['NewsletterAccount'][$field];
				
				$this->NewsletterAccount->set($this->data);
				
				$this->NewsletterAccount->save();
				
				$this->set('result', json_encode(array('set' => $value)));
				$this->render('/backend/ajaxResult');
			
			}
			
			function admin_setDefault($id) {
			
				$this->layout = "ajax";
				
				$this->data = $this->NewsletterAccount->findById($id);

				if($this->data['NewsletterAccount']['default'] == 1) {
				
					$this->data['NewsletterAccount']['default'] = 0;
				
				} else if($this->data['NewsletterAccount']['default'] == 0) {
				
					$this->data['NewsletterAccount']['default'] = 1;
					
					$this->NewsletterAccount->updateAll(
					
						array('NewsletterAccount.default' => 0), 
						array('NewsletterAccount.id !=' => $id)
					
					);
					
					$this->NewsletterAccount->save();
				
				}
				
				$value = $this->data['NewsletterAccount']['default'];
				
				$this->NewsletterAccount->set($this->data);				
				$this->NewsletterAccount->save();
			
				
				$this->set('result', json_encode(array('set' => $value)));
				$this->render('/backend/ajaxResult');
				
			}
										
	}
	
?>