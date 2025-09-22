<?

	class MenusController extends AppController {
	
		var $name = "Menus";
		var $helpers = array('Backend','Javascript','Cksource');	
		var $login_required = true;
		var $uses = array('Upload','Menu','Page','PageMenu');
		
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
				
					$this->Menu->set($this->data);
					
					if ($this->Menu->save()) {
						
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
				
				$this->set('pages_total', $this->getPages($id, 'menu'));
				$this->set('pages', $this->getPages($id, 'all'));
				
				if (empty($this->data)) {
								
					$this->data = $this->Menu->find('first',array('conditions' => array('Menu.id' => $id)));
				
				} else {
										
					$this->Menu->set($this->data);
				
					if ($this->Menu->save()) {
					
						$ADD_OK = true;		
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}
			
			function getPages($id, $params) {
			
				switch($params) {
				
					case 'menu':
						$list_id = $this->PageMenu->find('list', array(
						
							'fields'     => array('PageMenu.page_id'),
							'conditions' => array(
							
								'PageMenu.menu_id' => $id,
								'PageMenu.parent_id' => 0,
							
							)
						
						));					
						$pages = $this->Page->find('list', array(
						
							'conditions' => array(
							
								'Page.disabled' => 0,
								'Page.id'  => $list_id,
							
							),
							'order' => 'Page.title ASC',
						
						));	
					break;
					
					case 'all':
						$list_id = $this->PageMenu->find('list', array(
						
							'fields'     => array('PageMenu.page_id'),
							'conditions' => array(
							
								'PageMenu.menu_id' => $id,
								'PageMenu.parent_id' => 0,
								
							)
						
						));		
						
						$pages = $this->Page->find('all', array(
						
							'conditions' => array(
							
								'Page.disabled'   => 0,
								'NOT' => array('Page.id' => $list_id),
							
							),
							'order' => 'Page.title ASC',
						
						));
					break;
				
				}

				return $pages;
			
			}
			
			function admin_getChild($id = null) {
			
				$this->layout = "ajax";

				$list_id = $this->PageMenu->find('list', array(
				
					'fields'     => array('PageMenu.page_id'),
					'conditions' => array(
						
						'PageMenu.parent_id' => $id,
					
					),
				
				));
				
				$children = $this->Page->find('list', array(
				
					'fields'     => array('Page.id','Page.title'),
					'conditions' => array(
						
						'Page.disabled' => 0,
						'Page.id' => $list_id,
					
					),
				
				));
				
				$this->set('result', json_encode(array('children' => $children)));
				$this->render('/backend/ajaxResult');
				
			}		

			function admin_getChildren($id = null) {
			
				$this->layout = "ajax";

				// $list_id = $this->PageMenu->find('list', array(
				
					// 'fields'     => array('PageMenu.page_id'),
					// 'conditions' => array(
						
						// 'PageMenu.parent_id !=' => $id,
						// 'PageMenu.page_id !=  ' => $id,
					
					// ),
				
				// ));
				
				$children = $this->Page->find('list', array(
				
					'fields'     => array('Page.id','Page.title'),
					'conditions' => array(
						
						'Page.disabled' => 0,
						'Page.id !=   ' => $id,
					
					),
				
				));
				
				$this->set('result', json_encode(array('children' => $children)));
				$this->render('/backend/ajaxResult');
				
			}				

			// function admin_getChild($id = null) {
			
				// $this->layout = "ajax";

				// $menus = $this->Menu->find('list', array(
				
					// 'fields'     => array('Menu.Node', 'Menu.title'),
					// 'conditions' => array('AND' => array(
					
						// 'Menu.id !=' => $id,
						// 'Menu.parent_id !=' => $id,
						
						// )
					
					// ),
				
				// ));
				
				// $this->set('result', json_encode(array($menus)));
				// $this->render('/backend/ajaxResult');
				
			// }

			/*AJAX MENU*/
			
			function admin_addChild() {
			
				$this->layout = "ajax";
				
				$this->PageMenu->create();
				$this->PageMenu->set('menu_id', $this->data['Menu']['id']);
				$this->PageMenu->set('page_id', $this->data['Menu']['page']);
				
				if($this->PageMenu->save()) {
				
					$add = $this->data['Menu']['page'];
				
				} else {
				
					$add = 0;
				
				}

				$this->set('result', json_encode(array('add' => $add)));
				$this->render('/backend/ajaxResult');
			
			}
			
			function admin_addChilds() {
			
				$this->layout = "ajax";
				
				//debug($this->data);
				
				// $data = $this->PageMenu->find('first', array(
				
					// 'conditions' => array(
					
						// 'PageMenu.menu_id' => $this->data['AddChild']['menu_id'],
						// 'PageMenu.page_id' => $this->data['AddChild']['child'],
					
					// ),
				
				// ));
				
				// if(!empty($data)) {
				
					// $data['PageMenu']['parent_id'] = $this->data['AddChild']['parent'];
					// $this->PageMenu->set($data);
					
					// if($this->PageMenu->save()) {
					
						// $add = $this->Page->find('list', array(
						
							// 'fields'     => array('Page.id','Page.title'),
							// 'conditions' => array(
							
								// 'Page.id' => $data['PageMenu']['page_id'],
								
							// ),
						
						// ));
					
					// } else {
					
						// $add = 0;
					
					// }
					
				//} else {
				
					$this->PageMenu->create();
					$this->PageMenu->set('page_id', $this->data['AddChild']['child']);
					$this->PageMenu->set('menu_id', $this->data['AddChild']['menu_id']);
					$this->PageMenu->set('parent_id', $this->data['AddChild']['parent']);
					
					if($this->PageMenu->save()) {
					
						$add = $this->Page->find('list', array(
						
							'fields'     => array('Page.id','Page.title'),
							'conditions' => array(
							
								'Page.id' => $this->data['AddChild']['child'],
								
							),
						
						));
					
					} else {
					
						$add = 0;
					
					}
				
				//}
				
				$this->set('result', json_encode(array('add' => $add)));
				$this->render('/backend/ajaxResult');
			
			}
			
			function getMenu() {
			
				//$this->layout = null;
				
				   App::import('Xml');

					//your XML file's location
					$file = APP . "/vars/menu.xml";

					//now parse it
					$parsed_xml =& new XML($file);
					$parsed_xml = Set::reverse($parsed_xml); // this is what i call magic

					//see the returned array
					return $parsed_xml;
			
			}
	
	}
