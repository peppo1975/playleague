<?

class MatchgoalsController extends AppController {
	
	var $name = "Matchgoals";
	var $login_required = true;
	var $helpers = array('Backend');
	var $uses = array('Matchgoal','AnniSportivi');
	
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
		
		$this->set('AnniSportivi',$this->AnniSportivi->find('list', array( 'order' => array('AnniSportivi.AnnoSportivo DESC'))));
		
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
			
			$this->Matchgoal->set($this->data);
			
			if ($this->Matchgoal->save()) {
				
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
		
		if (!empty($this->data)) {
			
			$this->Matchgoal->set($this->data);
			
			if ($this->Matchgoal->save()) {
				
				$ADD_OK = true;
				
				if ($ADD_OK) {
					
					$this->set('result','ADD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
		} else {
			
			$this->data = $this->Matchgoal->read(null, $id);
			$this->data['Matchgoal']['Data'] = $this->data['Matchgoal']['Data_it'];
			$this->data['Matchgoal']['EspulsioneInizio'] = $this->data['Matchgoal']['EspulsioneInizio_it'];
			$this->data['Matchgoal']['EspulsioneFine'] = $this->data['Matchgoal']['EspulsioneFine_it'];
			
		}
		
	}
	
}
