<?

	class RightsController extends AppController {
	
			var $name = "Rights";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Right','Group');
			var $components = array('ControllerList');
			
			function createRight($group_id) {
			
				Configure::Write('debug',2);
			
				$this->layout = "ajax";
				
				$controllers = $this->ControllerList->get();
				
				foreach($controllers as $controller => $actions) {
				
					foreach($actions as $action) {
					
						$resource = $controller . '|' . $action;
						$prefix   = str_replace('admin_', '', $action);					
						
						if($prefix == $action) {
						
							$enable = 1;
							
						} else {
						
							$enable = 0;
						
						}
						
						
						$this->Right->create();
						$this->Right->set('group_id', $group_id);
						$this->Right->set('resource', $resource);
						$this->Right->set('allow', $enable);
						
						if($this->Right->save()) {
						
							debug('Permesso con autorizzazione: ' . $enable . ' per il controller: ' . $controller . ' azione: ' . $action . ' creato');
						
						}
						//debug($controller . ' ' . $action . ' ' . $enable);
					
					}
				
				}
				
				exit;
			
			}
						
			function admin_index() {
			}
			
			function admin_getController() {
			
				$list = array();
				
				$controllers = $this->ControllerList->get();
				ksort($controllers);
				
				foreach($controllers as $controller => $action) {
				
					$list[$controller] = $controller;
				
				}
				
				$groups = $this->Group->find('list', array(
				
					'fields' => array('Group.id','Group.nome'),
					'conditions' => array(
					
						'Group.nome !=' => 'Amministratore'
					
					),
				
				));
				
				$this->set('groups', $groups);
				$this->set('controllers', $list);			
			
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
				
				$this->admin_getController();
				
				if (!empty($this->data)) {
				
					if(!empty($this->data['Right']['action'])) {
					
						$this->data['Right']['resource'] = $this->data['Right']['resource'].'|'.$this->data['Right']['action'];
					
					}

					$count = $this->Right->find('list', array(
					
						'fields'     => array('Right.id'),
						'conditions' => array(
						
							'Right.group_id' => $this->data['Right']['group_id'],
							'Right.resource' => $this->data['Right']['resource'],
						
						),
					
					));
					
					if(count($count)) {
					
						foreach($count as $id) {
					
							$this->Right->delete($id);
						
						}
					
					}
				
					$this->Right->set($this->data);
					
					if($this->Right->save()) {

							
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
				
				$this->admin_getController();
				
				if (empty($this->data)) {
								
					$this->data = $this->Right->read(null, $id);
					
					$split = explode('|',$this->data['Right']['resource']);
					
					$this->data['Right']['resource'] = $split[0];					
					$this->data['Right']['action']   = (isset($split[1]))? $split[1]:'';
					
					App::Import('Controller', 'Pages');
					$Pages = new PagesController;
					$Pages->constructClasses();
					
					$this->set('actions', $Pages->getAction($split[0]));
				
				} else {
				
					if(!empty($this->data['Right']['action'])) {
					
						$this->data['Right']['resource'] = $this->data['Right']['resource'].'|'.$this->data['Right']['action'];
					
					}						
										
					$this->Right->set($this->data);
					
					if($this->Right->save()) {

							$ADD_OK = true;
								
							if ($ADD_OK) {
										
								$this->set('result','ADD_OK');
								$this->render('/backend/ajaxResult');
							
							}
					
					} 
				
				}
			
			}	
			
	}
