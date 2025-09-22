<?

function isAdmin($value) {
	
	return ($value == 0)? "No" : "Si";
	
} 

function truncateField($value) {
	
	if(strlen($value) > 36) {
		
		$values = substr_replace($value, '...', 37, strlen($value));
		
		return '<span rel="timmytip" title="'.$value.'">'.$values.'</span>';
		
	} else {
		
		$values = $value;
		
		return '<span>'.$values.'</span>';
		
	}
	
}

class YearbooksController extends AppController {
	
	var $name = "Yearbooks";
	var $login_required = true;
	var $helpers = array('Backend');
	var $uses = array('Yearbook','Athlete','Squadre','TipiAssicurazione','SquadreCampionati','Campionati','AnniSportivi');
	
	function admin_index() {
		
		
		
	}
	
	function admin_searchAnnuarioByIdAndAnno($atleta, $anno) {
		
		$annuario = $this->Yearbook->find('first', array(
			
			'conditions' => array(
				
				'Yearbook.Atleta' => $atleta,
				'Yearbook.AnnoSportivo' => $anno
				
				)
			
			));
		
		if(!empty($this->params['requested'])) {
			
			return $annuario;
			
		}
		
	}
	
	function admin_filters() {
		
		$this->layout = "ajax";
		
		if (!empty($this->data)) {
			
			$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
			$this->set('result','RELOAD_OK');
			$this->render('/backend/ajaxResult');
			
		}
		
	}
	
	
	function admin_tesseraGen($AnnoSportivo) {
		
		$this->layout = "ajax";
		
		$tessera = $this->Yearbook->find('count',
			
			array(
				
				'conditions' => array(
					
					'Yearbook.AnnoSportivo' => $AnnoSportivo,
					
					)
				
				)
			
			);
		
		$tessera = substr($AnnoSportivo,-2,2) . str_pad($tessera+1, 6, "0", STR_PAD_LEFT);
		
		$this->set('result',json_encode(array('tessera' => $tessera)));
		$this->render('/backend/ajaxResult');
		
	}
	
	function admin_tesseraGen_old($AnnoSportivo) {
		
		$this->layout = "ajax";
		
		$tessera = $this->Yearbook->find('first',
			
			array(
				
				'conditions' => array(
					
					'Yearbook.AnnoSportivo' => $AnnoSportivo
					
					),
				'order' => array('Yearbook.Annuario' => 'DESC')
				
				)
			
			
			);
		
		
		$tessera = substr($AnnoSportivo,-2,2) . str_pad($tessera['Yearbook']['Annuario']+1, 4, "0", STR_PAD_LEFT);
		
		$this->set('result',json_encode(array('tessera' => $tessera)));
		$this->render('/backend/ajaxResult');
		
	}			
	



	function admin_searchAtleta()
	{

		$this->layout = "ajax";

		$term = $_GET['term'];

		$query = "SELECT DISTINCT  Cognome, Nome FROM Atleti WHERE CONCAT(Cognome,' ',Nome) LIKE '$term%' ORDER BY CONCAT(Cognome,' ',Nome) ASC LIMIT 15";

		$result = mysql_query($query);

		$ret = array();

		if (mysql_num_rows($result) > 0 && $_GET['term'] != "" && $_GET['term'] != " " && $_GET['term'] != "  ")
		{
			//foreach ($atleti as $atleta) 

			while ($row = mysql_fetch_assoc($result))
			{

				$tmp['id'] = $row['Atleta'];
				$tmp['label'] = $row['Cognome'] . " " . $row['Nome'];

				$ret[] = $tmp;
			}
		}

		$this->set('result', json_encode($ret));

		$this->render('/backend/ajaxResult');
	}

	// function admin_searchAtleta() {
	// $this->layout = "ajax";
	// $atleti = $this->Athlete->find('all',array(
	// 'fields' => array('Athlete.atleta','Athlete.Anagrafica','Athlete.reverseAnagrafica'),
	// 'conditions' =>
	// array(
	// array('OR' => 
	// array(
	// 'Athlete.Anagrafica LIKE' => $_GET['term'] . '%',
	// 'Athlete.reverseAnagrafica LIKE' => $_GET['term'] . '%'
	// )
	// )
	// ),
	// 'order' => 'Athlete.reverseAnagrafica ASC',
	// 'limit' => '15'
	// ));
	// $ret = array();
	// foreach ($atleti as $atleta) {
	// $tmp['id'] = $atleta['Athlete']['Atleta'];
	// $tmp['label'] = $atleta['Athlete']['reverseAnagrafica'];
	// $ret[] = $tmp;
	// }
	// $this->set('result',json_encode($ret));
	// $this->render('/backend/ajaxResult');
	// }



	
	function admin_searchSquadraCampionato($AnnoSportivo = null) {
		
		$this->layout = "ajax";
		
		if($AnnoSportivo != null) {
			
			$conditions = array(
				'Squadre.Denominazione LIKE' => $_GET['term'] . '%',
				'Campionati.AnnoSportivo' => $AnnoSportivo,
				
				);	
			
		} else {
			
			$conditions = array(
				'Squadre.Denominazione LIKE' => $_GET['term'] . '%',
				
				);					
			
		}
		
		$squadrec = $this->SquadreCampionati->find('all',array(
			
			'conditions' => $conditions,
			'order' => array('Campionati.Nome ASC','Campionati.AnnoSportivo DESC'),
			'limit' => '15'
			
			));
		
		$ret = array();
		
		foreach ($squadrec as $squadrac) {
			
			$tmp['id'] = $squadrac['SquadreCampionati']['SquadraCampionato'];

			///////////////////////////////////////////////////////////////////////// 
			// TIMMYTAG FUNZIONI STAMPA RICEVUTE TESSERAMENTI - UPLOAD DEL 12/06/2018
			///////////////////////////////////////////////////////////////////////// 

			$tmp['label'] = $squadrac['Squadre']['Denominazione'] . " → " . $squadrac['Campionati']['Nome'];
			

			///////////////////////////////////////////////////////////////////////// 
			///////////////////////////////////////////////////////////////////////// 
			///////////////////////////////////////////////////////////////////////// 
                        
			$ret[] = $tmp;
			
		}
		
		$this->set('result',json_encode($ret));
		
		$this->render('/backend/ajaxResult');
		
	}
	
	function admin_search() {
		
		$this->layout = "ajax";	
		
		$this->set('AnniSportivi',$this->AnniSportivi->find('all', array( 'order' => array('AnniSportivi.AnnoSportivo DESC'))));
		$this->set('TipiAssicurazione',$this->TipiAssicurazione->find('all', array( 'order' => array('TipiAssicurazione.Descrizione ASC'))));
		
		if (!empty($this->data)) {
			
			if(isset($this->data['Athlete']['DataNascita']) && $this->data['Athlete']['DataNascita'] != '') {
				
				$this->dmy2ymd($this->data['Athlete']['DataNascita']);
				
			}
			
			$this->Session->write($this->name . ".searchData",$this->data);
			$this->set('result','RELOAD_OK');
			$this->render('/backend/ajaxResult');
			
		}
		
		if ($this->Session->check($this->name . ".searchData",$this->data)) {
			
			$this->data = $this->Session->read($this->name . ".searchData");
			
		} 
		
	}


    function admin_add()
    {

        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));
        $this->set('TipiAssicurazione', $this->TipiAssicurazione->find('all', array('order' => array('TipiAssicurazione.Descrizione ASC'))));

        $this->layout = "ajax";

        if (!empty($this->data))
        {

            //Cancello sessione.

            $this->Session->delete('yearbooksInsert');

            /* Controllo se il giocatore gioca già nella stessa stagione */

            $SquadraCampionato = $this->SquadreCampionati->findBySquadracampionato($this->data['Yearbook']['SquadraCampionato']);
            $campionato = $SquadraCampionato['SquadreCampionati']['Campionato'];

            $tesseramenti = $this->Yearbook->find('all', array(
                'conditions' => array(
                    'Yearbook.AnnoSportivo' => $this->data['Yearbook']['AnnoSportivo'],
                    'Yearbook.Atleta' => $this->data['Yearbook']['Atleta'],
                ),
            ));

            $giaGiocato = 0;

            foreach ($tesseramenti as $tessera)
            {

                if ($tessera['SquadreCampionati']['Campionato'] == $campionato)
                {

                    if ($tessera['Athlete']['Sportivo'] == 'Si' || $tessera['SquadreCampionati']['Squadra'] == $SquadraCampionato['SquadreCampionati']['Squadra'] || $tessera['SquadreCampionati']['SquadraCampionato'] == $SquadraCampionato['SquadreCampionati']['SquadraCampionato'])
                    {

                        $giaGiocato = 1;
                        break;
                    }
                }
            }

            if (!$giaGiocato && count($tesseramenti))
            {

                $data = $tesseramenti[0];

                $this->data['Yearbook']['Tessera'] = $data['Yearbook']['Tessera'];
                $this->data['Yearbook']['DataVidimazione'] = $data['Yearbook']['DataVidimazione'];
                //$this->data['Yearbook']['AnnoSportivo']    = $data['Yearbook']['AnnoSportivo'];
            }

            if ($giaGiocato)
            {

                $this->Yearbook->invalidate('AtletaSearch', 'Atleta già inserito nel campionato.');
                return false;
            }

            /* */

            $this->Yearbook->set($this->data);

            //GIUSEPPE 2023-07-28 -------------------------------------------
            $YearBookSave = $this->Yearbook->save();
            $this->write_file("_yearbooksave", $YearBookSave);

            if ($YearBookSave)
            {

                $this->insertBas($YearBookSave['Yearbook']);

                $ADD_OK = true;

                if ($ADD_OK)
                {

                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
            //---------------------------------------------------------------
//            if ($this->Yearbook->save())
//            {
//
//                $ADD_OK = true;
//
//                if ($ADD_OK)
//                {
//
//                    $this->set('result', 'ADD_OK');
//                    $this->render('/backend/ajaxResult');
//                }
//            }
        }
        else
        {

            if ($this->Session->check('yearbooksInsert'))
            {

                $this->data = $this->Session->read('yearbooksInsert');
            }
        }
    }


    //GIUSEPPE 2023-07-28 -------------------------------------------
    private function insertBas($YearBook)
//    public function insertBas($Tessera, $SquadraCampionato, $AnnoSportivo, $Atleta)
    {

        App::import('Controller', 'Halfs'); // mention at top
        $Halfs = new HalfsController;

        $Tessera = $YearBook['Tessera'];

        $SquadraCampionato = $YearBook['SquadraCampionato'];

        $AnnoSportivo = $YearBook['AnnoSportivo'];

        $Atleta = $YearBook['Atleta'];

        $Squadra = $this->trovaSquadra($SquadraCampionato);

        $subscriber_id = $this->ifAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra);

        //$card_id = "0"; //2023-09-19

        if ($subscriber_id > 0)
        {
            $this->updateYearBookBAS($subscriber_id, $Tessera);

            return 0;
        }

        $client_id = "";

        // cerco l'id bas della squadra

        $query = "  SELECT
                        SquadreBAS.*
                    FROM
                        `SquadreBAS`
                    INNER JOIN SquadreCampionati ON SquadreCampionati.Squadra = SquadreBAS.Squadra
                    WHERE
                        SquadreCampionati.SquadraCampionato = '{$SquadraCampionato}' AND SquadreBAS.AnnoSportivo = '{$AnnoSportivo}'";

//        echo $query;

        $SquadrBAS = $this->select_sql($query);

        if (count($SquadrBAS) > 0)
        {
//            print_r($SquadrBAS);

            $client_id = $SquadrBAS[0]['client_id'];

            //cerco info atleta
            $query_atleta = "SELECT * FROM Atleti WHERE Atleta = '{$Atleta}'";

            $atleta_query = $this->select_sql($query_atleta);

            if (count($atleta_query) > 0)
            {
                $atleta_info = $atleta_query[0];
//                print_r($atleta_info);
                $atleta_bas = [];
                $atleta_bas['birthday'] = $atleta_info['DataNascita'];
                $atleta_bas['firstname'] = $atleta_info['Nome'];
                $atleta_bas['lastname'] = $atleta_info['Cognome'];
                $atleta_bas['birthplace'] = $atleta_info['CityNascita'];
                $atleta_bas['gender'] = strtolower($atleta_info['Sesso'][0]);
                $atleta_bas['insurance'] = "BASFIA1";

                $res_json = $Halfs->sendBasAthlete($atleta_bas, $client_id);

                $res = json_decode($res_json, true);

                $subscriber_id = $res['data']['subscriber_id'];

                $this->write_file("BAS_arrayAthlete", $res);

                $card_id = $res['data']['card_id']; //2023-09-19
//                 
//                $this->addAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra, $subscriber_id);
                $this->addAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra, $subscriber_id, $card_id); //2023-09-19

                $this->updateYearBookBAS($subscriber_id, $card_id, $Tessera);
            }
        }

        return 0;

        //cerco le 
//        exit();
    }


    private function ifAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra)
    {
        $subscriber_id = 0;

        $query = "SELECT * FROM `AtletiBAS` WHERE Atleta = '{$Atleta}' AND AnnoSportivo = '{$AnnoSportivo}' AND Tessera = '{$Tessera}' AND Squadra = '{$Squadra}'";

        $res = $this->select_sql($query);

        if (count($res) > 0)
        {
            $info_atleta = $res[0];

            $subscriber_id = $info_atleta['subscriber_id'];
        }


        return $subscriber_id;
    }


    private function updateYearBookBAS($subscriber_id, $card_id, $Tessera)
    {
        $queryEdit = "UPDATE `Annuario` SET `subscriber_id` = '{$subscriber_id}', `card_id` = '{$card_id}' WHERE `Annuario`.`Tessera` = '{$Tessera}';";

        $this->my_query($queryEdit);

        return 0;
    }


//
//    private function addAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra, $subscriber_id)
//    {
//        $values = [];
//        $values['Atleta'] = $Atleta;
//        $values['AnnoSportivo'] = $AnnoSportivo;
//        $values['Tessera'] = $Tessera;
//        $values['Squadra'] = $Squadra;
//        $values['subscriber_id'] = $subscriber_id;
//        $res = $this->insert_into("AtletiBAS", $values, true);
//
//        return $res;
//    }

    private function addAthleteBAS($Atleta, $AnnoSportivo, $Tessera, $Squadra, $subscriber_id, $card_id)
    {
        $values = [];
        $values['Atleta'] = $Atleta;
        $values['AnnoSportivo'] = $AnnoSportivo;
        $values['Tessera'] = $Tessera;
        $values['Squadra'] = $Squadra;
        $values['subscriber_id'] = $subscriber_id;
        $values['card_id'] = $card_id;
        $res = $this->insert_into("AtletiBAS", $values, true);

        return $res;
    }


    private function trovaSquadra($SquadraCampionato)
    {
        $query = "SELECT * FROM `SquadreCampionati` WHERE SquadraCampionato = '{$SquadraCampionato}'";

        $res = $this->select_sql($query);

        return $res[0]['Squadra'];
    }


    //---------------------------------------------------------------



    function admin_edit($id)
    {

        $this->layout = "ajax";
        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));
        $this->set('TipiAssicurazione', $this->TipiAssicurazione->find('all', array('order' => array('TipiAssicurazione.Descrizione ASC'))));

        if (empty($this->data))
        {

            $this->data = $this->Yearbook->read(null, $id);

            $this->data['Yearbook']['DataVidimazione'] = (!empty($this->data['Yearbook']['DataVidimazione'])) ? date("d/m/Y", strtotime($this->data['Yearbook']['DataVidimazione'])) : '';
            $this->Athlete->set('Atleta', $this->data['Yearbook']['Atleta']);
            $this->data['Yearbook']['AtletaSearch'] = $this->Athlete->field('Anagrafica');

            $this->data['Yearbook']['NomeSquadraCampionato'] = $this->data['Yearbook']['NomeSquadra'];
            $this->TipiAssicurazione->set('TipoAssicurazione', $this->data['Yearbook']['TipoAssicurazione']);
            $this->data['Yearbook']['NomeTipoAssicurazione'] = $this->TipiAssicurazione->field('Descrizione');
            
            //GIUSEPPE 2023-07-28 ---------------------------------------------------------------------------
            $bas = $this->select_sql("SELECT subscriber_id,card_id FROM Annuario WHERE Annuario = '{$id}'")[0];
            $this->data['Yearbook']['card_id'] = $bas['card_id'];
            //-----------------------------------------------------------------------------------------------
        }
        else
        {

            $this->data['Yearbook'][$this->Yearbook->primaryKey] = $id;

            $data_old = $this->Yearbook->read(null, $id);

            /* Controllo se il giocatore gioca già nella stessa stagione */

            $SquadraCampionato = $this->SquadreCampionati->findBySquadracampionato($this->data['Yearbook']['SquadraCampionato']);
            $campionato = $SquadraCampionato['SquadreCampionati']['Campionato'];

            $tesseramenti = $this->Yearbook->find('all', array(
                'conditions' => array(
                    'Yearbook.AnnoSportivo' => $this->data['Yearbook']['AnnoSportivo'],
                    'Yearbook.Atleta' => $this->data['Yearbook']['Atleta'],
                ),
            ));

            $giaGiocato = 0;
            foreach ($tesseramenti as $tessera)
            {
                if ($tessera['SquadreCampionati']['Campionato'] == $campionato && $data_old['Yearbook']['SquadraCampionato'] != $tessera['SquadreCampionati']['SquadraCampionato'])
                {
                    if ($tessera['Athlete']['Sportivo'] == 'Si' || $tessera['SquadreCampionati']['Squadra'] == $SquadraCampionato['SquadreCampionati']['Squadra'] || $tessera['SquadreCampionati']['SquadraCampionato'] == $SquadraCampionato['SquadreCampionati']['SquadraCampionato'])
                    {
                        $giaGiocato = 1;
                        break;
                    }
                }
            }

            if (!$giaGiocato && count($tesseramenti))
            {
                $data = $tesseramenti[0];
                $this->data['Yearbook']['Tessera'] = $data['Yearbook']['Tessera'];
                $this->data['Yearbook']['DataVidimazione'] = $data['Yearbook']['DataVidimazione'];
                //$this->data['Yearbook']['AnnoSportivo']    = $data['Yearbook']['AnnoSportivo'];
            }

            if ($giaGiocato)
            {
                $this->Yearbook->invalidate('AtletaSearch', 'Atleta già inserito nel campionato.');
                return false;
            }

            /* */

            $this->Yearbook->set($this->data);

            //Signup code
            $old_data = $this->Yearbook->findByAnnuario($id);

            if (!empty($this->data['Yearbook']['signup_code']))
            {
                if ($old_data['Yearbook']['signup_code'] != $this->data['Yearbook']['signup_code'])
                {
                    //Invio mail con signup code
                    $this->set('link', "http://" . $_SERVER['SERVER_NAME']);
                    $this->set('anagrafica', $old_data['Athlete']['Nome'] . ' ' . $old_data['Athlete']['Cognome']);
                    $this->set('user', $old_data['Athlete']['Email']);
                    $this->set('pwd', $this->data['Yearbook']['signup_code']);
                    $this->set('squadra', $old_data['Yearbook']['NomeSquadra']);
                    $this->Email->to = $old_data['Athlete']['Email'];
                    $this->Email->subject = 'Midland Sport | Registrazione nuovo capitano squadra';
                    $this->Email->template = 'user_add_capitano';
                    $this->Email->send();
                }
            }

            $ADD_OK = true;

            if ($this->Yearbook->save())
            {

                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }

        /* //GIUSEPPE 2019-11-11 -------------------------------- */
        $tessera = $this->data['Yearbook']['Tessera'];
        $this->data['Yearbook']['Plus'] = $this->read_plus_points($tessera);
        /* //---------------------------------------------------- */
    }

	
    

	function admin_saveSession() {
		
		$this->layout = "ajax";
		$this->Session->write('yearbooksInsert', $this->data);
		
		exit;
		
	}
	
	function admin_resetSession() {
		
		$this->layout = "ajax";
		$this->Session->delete('yearbooksInsert');
		
		exit;
		
	}
	
	function admin_checkYearbook($athlete_id, $year) {
		
		$this->layout = "ajax";
		
		/* Controllo se il giocatore gioca già nella stessa stagione */
		
		$tesseramenti = $this->Yearbook->find('first', array(
			
			'conditions' => array(
				'Yearbook.AnnoSportivo'         => $year,
				'Yearbook.Atleta' 			    => $athlete_id,							
				),
			'group' => 'Yearbook.Atleta'
			
			));
		
		if(empty($tesseramenti)) { $null = 1; }
		else					 { $null = 0; $tesseramenti['Yearbook']['DataVidimazione'] = $tesseramenti['Yearbook']['DataVidimazione_it']; }
		
		/* */				
		
		$this->set('result',json_encode(array('return' => $tesseramenti, 'null' => $null)));
		$this->render('/backend/ajaxResult');				
		
	}


    //GIUSEPPE 2019-11-11 --------------------------------------

    function admin_addPlus()
    {
        $this->layout = "ajax";

        $keys_expl = [];
        $values_expl = [];

        foreach ($_POST as $key => $value)
        {
            $keys_expl[] = $key;
            $values_expl[] = sprintf("'%s'", $value);
        }

        $keys = implode(",", $keys_expl);
        $values = implode(",", $values_expl);

        $query_insert = "
                   INSERT INTO `PuntiPlus` ( $keys ) 
                    VALUES 
                            ( $values );";

        mysql_query($query_insert) or die(mysql_error());

        $this->set('result', 'ADD_OK');
        $this->render('/backend/ajaxResult');
    }







    private function read_plus_points($tessera) /**/
    {

        $res = array();

        $sql = "
                    SELECT 
                            * 
                    FROM 
                            `PuntiPlus` 
                            INNER JOIN Annuario ON Annuario.Annuario = PuntiPlus.Annuario 
                            INNER JOIN SquadreCampionati ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato 
                            INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra 
                    WHERE 
                            Annuario.Tessera = '$tessera'
                                
                    ORDER BY PuntiPlus.Annuario ASC

                                ";

        $result = mysql_query($sql) or die(mysql_error());

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $res[$row['PuntoPlus']] = $row;
            }
        }
        else
        {
            
        }

        return $res;
    }







    function admin_delPlus()
    {
//        $this->layout = "ajax";

        $id = $_POST['id'];

        $sql = "
                   DELETE FROM 
                            `PuntiPlus` 
                    WHERE 
                            `PuntiPlus`.`PuntoPlus` = '$id';";

        mysql_query($sql) or die(mysql_error());


        $this->set('result', 'ADD_OK');
        $this->render('/backend/ajaxResult');
    }







    //----------------------------------------------------------
	
}
