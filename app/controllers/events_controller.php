<?

	class EventsController extends AppController {
	
			var $name = "Events";
			var $login_required = true;
			var $helpers = array('Backend','Cksource');
			var $uses = array('Campionati','Event','Upload');
						
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
				
					$this->Event->set($this->data);
					
					if ($this->Event->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}

					/*
					print_r($this->Event->lastQuery);
					print_r($this->Event->invalidFields());
					print mysql_error();
					*/
					
				} 
				
			}
			


     function admin_edit($id)
    {

        //GIUSEPPE 2020-09-01 ---------------------------------

//        $query = "SELECT * FROM `TipoSport`";
        
        /* uso questo per ora */
        $query = "SELECT 
                            * 
                    FROM 
                            `TipoSport` 
                    WHERE 
                            (
                                    sport = 'CALCIO' 
                                    OR sport = 'TENNIS'
                            )";

        $sport = $this->key_select($this->select_sql($query), 'id');

        foreach ($sport as $key => $value)
        {
            $sport[$key] = $value['sport'];
        }

        $this->set('sport', $sport);

        //-----------------------------------------------------

        $this->layout = "ajax";

        if (empty($this->data))
        {

            $this->data = $this->Event->read(null, $id);

            $this->data['Event']['data_inizio'] = implode("/", array_reverse(explode("-", $this->data['Event']['data_inizio'])));

            $this->data['Event']['data_fine'] = implode("/", array_reverse(explode("-", $this->data['Event']['data_fine'])));
        }
        else
        {

            if (isset($this->data['Event']['data_inizio']) && !empty($this->data['Event']['data_inizio']))
            {

                $this->data['Event']['data_inizio'] = $this->dmy2ymd($this->data['Event']['data_inizio']);

                @mysql_query("UPDATE events SET data_inizio = '" . $this->data['Event']['data_inizio'] . "' WHERE id = " . $id);
            }

            if (isset($this->data['Event']['data_fine']) && !empty($this->data['Event']['data_fine']))
            {

                $this->data['Event']['data_fine'] = $this->dmy2ymd($this->data['Event']['data_fine']);
                @mysql_query("UPDATE events SET data_fine = '" . $this->data['Event']['data_fine'] . "' WHERE id = " . $id);
            }


            //GIUSEPPE 2019-03-15 -------------------

           // $sport = array("0" => "CALCIO", "1" => "TENNIS");
            $update = sprintf("UPDATE events SET id_sport = '%s', sport = '%s' WHERE id = '%s'", $this->data['Event']['id_sport'], $sport[$this->data['Event']['id_sport']], $id);

//            @mysql_query("UPDATE events SET id_sport = '" . $this->data['Event']['id_sport'] . "' WHERE id = " . $id);
            @mysql_query($update);
            //---------------------------------------

            $this->Event->set($this->data);



            $ADD_OK = true;



            if ($this->Event->save())
            {



                if ($this->__adminUploadFile('event_id', $id) == true)
                {

                    $ADD_OK = false;
                }

                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }




	
	}
