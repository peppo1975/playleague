<?

	class ChampCategoriesController extends AppController {
	
			var $name = "ChampCategories";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Campionati','ChampCategory','Upload');
						
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
				
					$this->ChampCategory->set($this->data);
					
					if ($this->ChampCategory->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}

					/*
					print_r($this->ChampCategory->lastQuery);
					print_r($this->ChampCategory->invalidFields());
					print mysql_error();
					*/
					
				} 
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				
				if (empty($this->data)) {
								
					$this->data = $this->ChampCategory->read(null, $id);

					$this->data['ChampCategory']['data_inizio'] = implode("/",array_reverse(explode("-",$this->data['ChampCategory']['data_inizio'])));

					$this->data['ChampCategory']['data_fine'] = implode("/",array_reverse(explode("-",$this->data['ChampCategory']['data_fine'])));				
				} else {

			if(isset($this->data['ChampCategory']['data_inizio']) && !empty($this->data['ChampCategory']['data_inizio'])) {
			
				$this->data['ChampCategory']['data_inizio'] = $this->dmy2ymd($this->data['ChampCategory']['data_inizio']);

				@mysql_query("UPDATE CampionatiCategorie SET data_inizio = '" . $this->data['ChampCategory']['data_inizio']  . "' WHERE id = " . $id);
			
			}

			if(isset($this->data['ChampCategory']['data_fine']) && !empty($this->data['ChampCategory']['data_fine'])) {
			
				$this->data['ChampCategory']['data_fine'] = $this->dmy2ymd($this->data['ChampCategory']['data_fine']);
							@mysql_query("UPDATE CampionatiCategorie SET data_fine = '" . $this->data['ChampCategory']['data_fine']  . "' WHERE id = " . $id);

			}


					$this->ChampCategory->set($this->data);
				


					$ADD_OK = true;

			

					if ($this->ChampCategory->save()) {
													


			if ($this->__adminUploadFile('cat_id',$id) == true) {
					
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
