<?

	class SlidersController extends AppController {
	
			var $name = "Sliders";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Upload','Slider','Order');
						

			
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
			
			function getSlides() {
				
				$this->layout = "ajax";
				
				$slides = $this->Slider->find('all',array('conditions' => array('Slider.disabled' => 0)));
				
				$this->set('slides',$slides);
				
			}

 			function admin_add() {
		
				$this->layout = "ajax";	
				
				if (!empty($this->data)) {
				
					$this->Slider->set($this->data);
					
					if ($this->Slider->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('slider_id',$this->Slider->id) == true) {
						
							$ADD_OK = true;
							
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

				if (empty($this->data)) {
								
				$this->data = $this->Slider->find('first',array('conditions' => array('Slider.id' => $id)));
				
				$this->Slider->set($this->data);
				
				} else {
					
				$this->data['Slider'][$this->Slider->primaryKey] = $id;
										
				$this->Slider->set($this->data);
				
				$ADD_OK = true;
				
					if ($this->__adminUploadFile('slider_id',$id) == true) {
					
						$ADD_OK = false;
						
					}				

					if ($this->Slider->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}	
			
			function admin_index() {
				
			}
			

			
	}
