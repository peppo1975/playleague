<?

	class ComunicationsController extends AppController {
	
		var $name = "Comunications";
		var $helpers = array('Backend','Javascript','Cksource');
		var $components = array('ControllerList');
		var $uses = array('Upload','Comunication','Half','Match','Campionati','AnniSportivi');
		var $login_required = true;
		
			function admin_index() {
				
				$anni = $this->AnniSportivi->find('first', array('order' => array('AnniSportivi.AnnoSportivo DESC'), 'limit' => 1));
				$anno = $anni['AnniSportivi']['AnnoSportivo'];
				$this->set('anno', $anno);
				
			}
			
			function admin_findHalf($campionato = null) {
			
				$this->layout = "ajax";
				
				$halfs = $this->Half->find('list', array(
					
					'fields'     => array('Half.GironeCampionato', 'Half.Descrizione'),
					'conditions' => array(
					
						'Half.Campionato' => $campionato,
					
					),
					'order' => 'Half.Descrizione ASC',
				
				));
				
				$this->set('result', json_encode($halfs));
				$this->render('/backend/ajaxResult');
			
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
				
				$campionati = $this->Campionati->find('list', array(
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array(
						'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),					
					),
					'order' => array('Campionati.AnnoSportivo ASC', 'Campionati.Nome DESC')
				
				));
				
				$this->set('campionati', $campionati);					
						
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
				
				$campionati = $this->Campionati->find('list', array(
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array(
						'Campionati.AnnoSportivo > 2013'				
					),
					'order' => array('Campionati.AnnoSportivo DESC', 'Campionati.Nome DESC')
				
				));
				
				$this->set('campionati', $campionati);
										
				if (!empty($this->data)) {
				
					$this->Comunication->set($this->data);
					
					if ($this->Comunication->save()) {
						
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
					$campionati = $this->Campionati->find('list', array(
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array(
						'Campionati.AnnoSportivo > 2013'				
					),
					'order' => array('Campionati.AnnoSportivo DESC', 'Campionati.Nome DESC')
				
				));
				
				
				$this->set('campionati', $campionati);				

				if (empty($this->data)) {
								
					$this->data = $this->Comunication->read(null, $id);
				
				} else {
										
				$this->Comunication->set($this->data);
				
					if ($this->Comunication->save()) {
					
						$ADD_OK = true;		
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}	
	
	}
