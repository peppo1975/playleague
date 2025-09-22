<?

	class FreeHoursController extends AppController {
	
			var $name = "FreeHours";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('FreeHour','Campi','Athlete','Yearbook','Athlete');
						
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
				
					$this->Athlete->set($this->data);
					
					if($this->Athlete->save()) {

						if($this->data['Athlete']['Atleta'] == '') {
						
							$last_atleta = $this->Athlete->id;
						
						} else {

							$last_atleta = $this->data['Athlete']['Atleta'];
							
						}
						
						$this->data['FreeHour']['AtletaSearch'] = $last_atleta;
						$this->data['FreeHour']['Atleta'] = $last_atleta;
						
						$this->FreeHour->set($this->data);
						
						if ($this->FreeHour->save()) {
							
							$ADD_OK = true;
								
							if ($ADD_OK) {
										
								$this->set('result','ADD_OK');
								$this->render('/backend/ajaxResult');
							
							}
							
						} 
					
					}
					
				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";

				if (empty($this->data)) {
								
					$this->data = $this->FreeHour->read(null, $id);
					
					$this->data['FreeHour']['CampoSearch'] = $this->FreeHour->field('NomeCampo');
					$this->data['FreeHour']['Data']        = $this->FreeHour->field('Data_it');
					$this->data['FreeHour']['AtletaSearch']= $this->FreeHour->field('Nominativo');
				
				} else {
										
					$this->Athlete->set($this->data);
					
					if($this->Athlete->save()) {

						if($this->data['Athlete']['Atleta'] == '') {
						
							$last_atleta = $this->Athlete->id;
						
						} else {

							$last_atleta = $this->data['Athlete']['Atleta'];
							
						}
						
						$this->data['FreeHour']['AtletaSearch'] = $last_atleta;
						$this->data['FreeHour']['Atleta'] = $last_atleta;
						
						$this->FreeHour->set($this->data);
						
						if ($this->FreeHour->save()) {
							
							$ADD_OK = true;
								
							if ($ADD_OK) {
										
								$this->set('result','ADD_OK');
								$this->render('/backend/ajaxResult');
							
							}
							
						}
					
					} else {
					
						$this->data = $this->FreeHour->read(null, $id);
						
						$this->data['FreeHour']['CampoSearch'] = $this->FreeHour->field('NomeCampo');
						$this->data['FreeHour']['Data']        = $this->FreeHour->field('Data_it');
						$this->data['FreeHour']['AtletaSearch']= $this->FreeHour->field('Nominativo');
						
					}
					
				}
			
			}	
			
			function admin_searchTessera() {
			
				$this->layout = "ajax";
				
				$annuari = $this->Yearbook->find('all', array(
				
					'conditions' => array(
					
						'Yearbook.Tessera LIKE' => $_GET['term'] . '%', 
					
					),
					'order' => 'Yearbook.Tessera ASC',
					'limit' => '15'
				
				)); 
				
				$ret = array();
				
				foreach ($annuari as $annuario) {
					
					$tmp['id'] = $annuario['Athlete']['Atleta'];
					$tmp['label'] = $annuario['Athlete']['reverseAnagrafica'];
					
					$ret[] = $tmp;
				
				}
				
				$this->set('result',json_encode($ret));
				
				$this->render('/backend/ajaxResult');
				
			}

			function admin_infoAthlete($atleta) {
			
				$this->layout = "ajax";
				
				$atleta = $this->Athlete->findByAtleta($atleta);
				
				$this->set('result', json_encode($atleta));
				$this->render('/backend/ajaxResult');
			
			}
			
	}
