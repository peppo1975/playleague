<?

	class TypesController extends AppController {
	
			var $name = "Types";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Campionati','Type','Upload','Event','Campionati');
						
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
								$this->set('events',$this->Event->find('list',array('fields' => array('Event.id','Event.Nome'))));

				if (!empty($this->data)) {
					$this->data['Type']['content'] = json_encode($this->data['Type']['content'],TRUE);

					$this->Type->set($this->data);
					
					if ($this->Type->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}

					/*
					print_r($this->Type->lastQuery);
					print_r($this->Type->invalidFields());
					print mysql_error();
					*/
					
				} 
				
			}


			function admin_edit($id) {
			
				$this->layout = "ajax";

				$campionati = ($this->Campionati->find('list',array(

					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array('Campionati.iscrizioni' => 1)

				)));
				
				$this->set('campionati',$campionati);

				$this->set('events',$this->Event->find('list',array('fields' => array('Event.id','Event.Nome'))));

				if (empty($this->data)) {
								
					$this->data = $this->Type->read(null, $id);

	$this->data['Type']['content'] = json_decode($this->data['Type']['content'],TRUE);

	$this->data['Type']['matches'] = json_decode($this->data['Type']['matches'],TRUE);


			} else {
	$this->data['Type']['content'] = json_encode($this->data['Type']['content'],TRUE);

	$this->data['Type']['matches'] = json_encode($this->data['Type']['matches'],TRUE);

					$this->Type->set($this->data);
				


					$ADD_OK = true;

			

					if ($this->Type->save()) {
													


			if ($this->__adminUploadFile('Type_id',$id) == true) {
					
						$ADD_OK = false;
						
					}			

						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}
	
	}
