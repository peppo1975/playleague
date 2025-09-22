<?

	class NewsletterGroupsController extends AppController {
	
			var $name = "NewsletterGroups";
			var $helpers = array('Backend');
			var $uses = array('NewsletterGroup','NewsletterUser','NewsletterGroupUser');
			
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
				
					$this->NewsletterGroup->set($this->data);
					
					if ($this->NewsletterGroup->save()) {
						
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
				
				Configure::Write('debug',2);
				
				$this->NewsletterUser->bindModel(array('hasOne' => array('NewsletterGroupUser' => array('foreignKey' => 'newsletter_user_id'))));
				$users = $this->NewsletterUser->find('all', array(
					
					'fields' => array('NewsletterUser.email','NewsletterUser.surname','NewsletterUser.name'),
					'conditions' => array(
						'NewsletterGroupUser.newsletter_group_id' => $id,
					),
					'order' => array('NewsletterUser.surname ASC'),
					'recursive' => 0
				));	
				$this->set('users', $users);			
				
				
				if (empty($this->data)) {
								
					$this->data = $this->NewsletterGroup->find('first',array('conditions' => array('NewsletterGroup.id' => $id)));
					$this->NewsletterGroup->set($this->data);
				
				} else {
										
				$this->NewsletterGroup->set($this->data);
				
				$ADD_OK = true;

					if ($this->NewsletterGroup->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}	
										
	}
	
?>