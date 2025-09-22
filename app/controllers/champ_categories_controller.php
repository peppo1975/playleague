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
		
		
		//GIUSEPPE 03/10/2016 -----------------------------
		
		function read_champ_cat_database()
		{
			$res = mysql_query("SELECT * FROM TipoSport");
			
			$arraySport = array();
			
			while($row = mysql_fetch_assoc($res))
			{
				
				$arraySport[] = $row['sport'];
				
			}
			
			//print_r($arraySport);
			//exit;
			return $arraySport;
		}
		
		function test_query()
		{
			//$res = mysql_query("ALTER TABLE `Campionati` CHANGE `sport` `sport` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
			
			$res = mysql_query("SELECT MAX(id) FROM CampionatiCategorie");
			$row = mysql_fetch_assoc($res);
			print_r($row);
			
			exit;
		}
		
		//GIUSEPPE 03/10/2016 -----------------------------
		
		function admin_add() {
			
			$this->layout = "ajax";	
			
			
			
			//GIUSEPPE 03/10/2016 -----------------------------
			
			if(isset($this->data['ChampCategory']['sport']))
			{
			
			$sport_id = $this->data['ChampCategory']['sport'];
			
			
			$result = $this->read_champ_cat_database();
			
			$this->data['ChampCategory']['sport'] = $result[$sport_id];
			
			$this->data['ChampCategory']['id_sport'] = $sport_id;
			
			//print_r($this->data);
			}
			
		
			
			// -----------------------------------------
			
			if (!empty($this->data)) {
				
				$this->ChampCategory->set($this->data);
				
				if ($this->ChampCategory->save()) {
					
					$ADD_OK = true;
					
					if ($ADD_OK) {
						
						$this->set('result','ADD_OK');
						$this->render('/backend/ajaxResult');
						
					}
					
				}
				
				/* print_r($this->ChampCategory->lastQuery);
				print_r($this->ChampCategory->invalidFields());
				print mysql_error();*/
				
				} 
				
				}
				
				
				
				
				function admin_edit($id) {
				
				$this->layout = "ajax";
				
				if (empty($this->data)) {
				
				$this->data = $this->ChampCategory->read(null, $id);
				
				$this->data['ChampCategory']['data_inizio'] = implode("/",array_reverse(explode("-",$this->data['ChampCategory']['data_inizio'])));
				
				$this->data['ChampCategory']['data_fine'] = implode("/",array_reverse(explode("-",$this->data['ChampCategory']['data_fine'])));				
				} 
				else 
				{
				
				if(isset($this->data['ChampCategory']['data_inizio']) && !empty($this->data['ChampCategory']['data_inizio'])) {
				
				$this->data['ChampCategory']['data_inizio'] = $this->dmy2ymd($this->data['ChampCategory']['data_inizio']);
				
				@mysql_query("UPDATE CampionatiCategorie SET data_inizio = '" . $this->data['ChampCategory']['data_inizio']  . "' WHERE id = " . $id);
				
				}
				
				if(isset($this->data['ChampCategory']['data_fine']) && !empty($this->data['ChampCategory']['data_fine'])) {
				
				$this->data['ChampCategory']['data_fine'] = $this->dmy2ymd($this->data['ChampCategory']['data_fine']);
				
				@mysql_query("UPDATE CampionatiCategorie SET data_fine = '" . $this->data['ChampCategory']['data_fine']  . "' WHERE id = " . $id);
				
				}
				
				
				//GIUSEPPE 03/10/2016 -----------------------------
				
				if(isset($this->data['ChampCategory']['sport']))
				{
				
				$sport_id = $this->data['ChampCategory']['sport'];
				
				$result = $this->read_champ_cat_database();
				
				$this->data['ChampCategory']['sport'] = $result[$sport_id];
				
				$this->data['ChampCategory']['id_sport'] = $sport_id;
				
				}
				// -----------------------------------------
				
				
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
								