<?


class AthletesController extends AppController {
	
	var $name = "Athletes";
	var $login_required = true;
	var $login_site     = false;
	var $helpers = array('Backend','Cache','Cksource');
	var $uses = array('Athlete','Upload','Yearbook','Match','Campionati','SquadreCampionati','EmailModel','Spool','AthleteExpense','Lda','Squadre','AnniSportivi','LdaVote');

//GIUSEPPE 2019-11-06
    var $last_year = "";
    var $condition = "";
    var $athlete_id = "";

//GIUSEPPE 2019-11-13
    var $array_tessera_assicurazione = array();

//GIUSEPPE 2019-12-21
    var $only_id_singles = "";
    var $only_atletes_plus = "";

			/*			
			var $cacheAction = array(
				'vote' => '24 hours',
				'vota' => '24 hours',
				'buste' => '24 hours',
			);
			*/
			
			
			function admin_index() {
				
				$group_id = $this->Auth->user('group_id');
				
				$conditions = array();
				
				if ($group_id == 3) {
					
					$conditions = array(
						
						'Athlete.Arbitro' => 'Si',
						'Athlete.ArbitroAttivo' => 1
						
					);
					
				} else {
					
					$conditions = array('0=1');
					
				}
				
				$this->set('conditions',$conditions);				
				
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


	//GIUSEPPE 2017-03-14 inserimento voce 'avatar' nell colonna 'files'.'tag'; in questo modo riesco a vedere l'avatar nelle altre pagine
			function insert_tag($id_athlete)
			{

				$id_file = '0';

				$query = "SELECT MAX(id) FROM `files` WHERE athlete_id = '$id_athlete' ";

		// echo $query;

				$result = mysql_query($query);

				if (mysql_num_rows($result) > 0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$id_file = $row['MAX(id)'];
					}
				}

				$update = "UPDATE files SET tag = 'avatar' WHERE id = '$id_file' ";

				mysql_query($update);
			}



    function admin_add()
    { //GIUSEPPE 2017-02-10 integrato il controllo anagrafico in amministrazione (nella sezione aggiungi nuovo atleta) ---------
        $this->layout = "ajax";

        $group_id = $this->Auth->user('group_id');

        $this->set('group_id', $group_id);

        if (!empty($this->data))
        {

            //echo "<script>console.log(".json_encode($this->data).")</script>";
            // -------------------------------------
            $search_athlete = $this->read_anagrafic($this->data);

            if ($search_athlete == "ATLETA_PRESENTE")
            {
                echo "<script>alert('ATLETA GIA\' PRESENTE NEL SISTEMA')</script>";
            }
            else if ($search_athlete == "EMAIL_PRESENTE")
            {
                echo "<script>alert('EMAIL GIA\' PRESENTE NEL SISTEMA')</script>";
            }
            else if ($search_athlete == "OK")
            {


                if ($this->data['Athlete']['Arbitro'] == 'No')
                {
                    $this->Athlete->unbindValidation('remove', array('Email', 'password'), false);
                }

                $this->Athlete->set($this->data);

                if ($this->Athlete->save())
                {

                    $ADD_OK = true;

                    if ($this->__adminUploadFile('athlete_id', $this->Athlete->id) == true)
                    {
                        $id = $this->Athlete->id;

                        $this->insert_tag($id);

                        $ADD_OK = true;
                    }

                    if ($ADD_OK)
                    {
                        //GIUSEPPE 2023-07-28 ----------------------------------------------  
//                        $this->set('result', 'ADD_OK');
//                        $this->render('/backend/ajaxResult');
                        $this->set('element_id', $this->Athlete->id);
                        //------------------------------------------------------------------
                    }
                }
            }
            // --------------------------------------
        }
    }


	function read_for_entry() //GIUSEPPE 2017-03-22-------------------------
	{
		$data = $_POST;

		//echo $this->Auth->password('1') . "<br>";

		echo $this->read_anagrafic($data);

		exit;
	}


	function insert_for_confirm()
	{
		$data = $_POST;

		//print_r($data);
		//exit;

		$cognome = $data['Athlete']['Cognome'];

		$nome = $data['Athlete']['Nome'];

		$email = strtolower($data['Athlete']['Email']);

		$datanascita = explode("/", $data['Athlete']['DataNascita']);

		$luogonascita = $data['Athlete']['LuogoNascita'];

		$sesso = $data['Athlete']['Sesso'];

		$anno_nascita = $datanascita[2];

		$mese_nascita = $datanascita[1];

		$giorno_nascita = $datanascita[0];


		$anno_corrente = date("Y", time());

		$mese_corrente = date("m", time());

		$giorno_corrente = date("d", time());

		//controllo validità data

		if (checkdate($mese_nascita, $giorno_nascita, $anno_nascita)) //validità data di nascita
		{
			$secondi_giorno = 3600 * 24;

			$secondi_4_anni = ($secondi_giorno * 365.25) * 4;

			$timestamp_datanascita = mktime(0, 0, 0, $mese_nascita, $giorno_nascita, $anno_nascita);

			$timestamp_corrente = mktime(0, 0, 0, $mese_corrente, $giorno_corrente, $anno_corrente);

			$eta_secondi = $timestamp_corrente - $timestamp_datanascita;

			if ($eta_secondi >= $secondi_4_anni)
			{
				
			}
			else
			{
				$result = "NO_ETA_MINIMA";

				echo $result;

				exit;
			}
		}
		else
		{
			$result = "DATA_NASCITA_NON_VALIDA";

			echo $result;

			exit;
		}

		$datanascita_database = $anno_nascita . "-" . $mese_nascita . "-" . $giorno_nascita;

		$password = $this->Auth->password($data['Athlete']['Password']);

		$query = "INSERT INTO AtletiConferma ( Cognome, Nome, Email, DataNascita, LuogoNascita, Sesso, password, DataInserimento) VALUES ('$cognome', '$nome', '$email', '$datanascita_database', '$luogonascita','$sesso', '$password', NOW())";

		//echo $query;
		//exit;

		mysql_query($query) or die(mysql_error());

		// Recupero l'ID	
		$id = mysql_insert_id(); // fuzione php per il recupero dell'id dell'ultimo inserimento

		$id_for_mail = md5($id); //$this->Auth->password($id);

		$query = "UPDATE AtletiConferma SET id_confirm = '$id_for_mail' WHERE id = '$id' ";

		mysql_query($query) or die(mysql_error());

		echo "OK";


		// email ----
		$this->set('md5_id', md5($id));

		$this->set('link', "http://" . $_SERVER['SERVER_NAME']);

		$this->set('anagrafica', $nome . ' ' . $cognome);

		$this->set('user', $email);

		$this->set('pwd', $data['Athlete']['Password']);

		$this->set('activate_function', '/athletes/activate/');

		$this->Email->to = $email;

		$this->Email->subject = 'Play League SSDARL | Registrazione nuovo atleta';

		$this->Email->template = 'user_add_site';

		$this->Email->send();
		// ----------

		exit;
	}


	function activate($id_for_mail)
	{
		
		$row_result = array();

		$query = "SELECT * FROM AtletiConferma WHERE id_confirm = '$id_for_mail'";

	   //echo $query;
		
		$result_query = mysql_query($query) or die(mysql_error());

		if (mysql_num_rows($result_query) == 0)
		{
			$this->redirect('/attivazione/out');
			exit;
		}

		while ($row = mysql_fetch_assoc($result_query))
		{
			$row_result = $row;
		}


		if ($row_result['id'] != "")
		{
			//print_r($row_result);

			$cognome = $row_result['Cognome'];

			$nome = $row_result['Nome'];

			$data_nascita = $row_result['DataNascita'];

			$luogonascita = $row_result['LuogoNascita'];

			$sesso = $row_result['Sesso'];

			$email = $row_result['Email'];

			$password = $row_result['password'];

			$query = "SELECT COUNT(Atleta) FROM Atleti WHERE Cognome = '$cognome' AND Nome = '$nome' AND DataNascita = '$data_nascita'";

			$result_query = mysql_query($query) or die(mysql_error());

			$num = mysql_fetch_array($result_query)[0];

			if ($num == 0)
			{
				//echo $num;

				$query = "SELECT COUNT(Atleta) FROM Atleti WHERE Email = '$email'";

				$result_query = mysql_query($query) or die(mysql_error());

				$num = mysql_fetch_array($result_query)[0];

				if ($num == 0)
				{
					$query_insert = "INSERT INTO Atleti (Cognome, Nome, DataNascita, LuogoNascita, Sesso, Email, password, data_registrazione) "
					. "VALUES ('$cognome', '$nome', '$data_nascita', '$luogonascita', '$sesso', '$email', '$password', NOW())";

					mysql_query($query_insert) or die(mysql_error());


					$query_update = "UPDATE AtletiConferma SET activate='1' WHERE id_confirm = '$id_for_mail'";

					mysql_query($query_update) or die(mysql_error());

					$ok = 1;

					$this->redirect('/attivazione/ok');
				}
				else
				{
					$this->redirect('/attivazione/out');
				}
			}
			else
			{
				$this->redirect('/attivazione/out');
			}
		}

		exit;
	}




	function read_anagrafic($data) //GIUSEPPE 2017-02-10 
	{
			// eseguo due tipi di controlli:
			// - utente gia presente
			// - email gia presente
		
		$result_query = "OK";
		
		$cognome = strtolower($data['Athlete']['Cognome']) ;
		
		$nome = strtolower($data['Athlete']['Nome']) ;
		
		$datanascita = explode("/",$data['Athlete']['DataNascita']);
		
		$datanascita_database = $datanascita[2]."-".$datanascita[1]."-".$datanascita[0];
		
		$email = strtolower($data['Athlete']['Email']);
		
		
			//echo $query;
			//$data['Athlete']['QUERY'] = $query;
			//echo "<script>console.log(".json_encode($data['Athlete']).")</script>";
		
			//CONTROLLO L'ANAGRAFICA
		$query = "SELECT COUNT(Nome) FROM Atleti WHERE LOWER(Cognome)='$cognome' AND LOWER(Nome) = '$nome' AND DataNascita = '$datanascita_database'";
		
		$q = mysql_query($query);
		
		$result = mysql_fetch_array($q)[0];
		
		if($result > 0)
		{
			
			$result_query = "ATLETA_PRESENTE";
			
			return $result_query; 
		}
		
		
			//CONTROLLO LA MAIL
		
		if($email!="")
		{
			$query = "SELECT COUNT(Email) FROM Atleti WHERE LOWER(Email)='$email'";
			
			$q = mysql_query($query);
			
			$result = mysql_fetch_array($q)[0];
			
			if($result > 0)
			{
				$result_query = "EMAIL_PRESENTE";
				
				return $result_query; 
			}
			
		}
		
		return $result_query; 
	}
	
		// ---------------------------------------------------------------------------------------------------------------------------------------------




	
	function admin_getBuste($id) {
		
		$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
		
					//Cerco gare effettuate dall'atleta
		$matches = $this->Match->find('all',
			
			array(
				
				'conditions' => array(
					
					'OR' => array(
						
						'Lda.Arbitro' => $id,
						'Lda.Arbitro2' => $id,
						'Lda.Delegato' => $id,
						'Lda.DelegatoA' => $id,
						
						
					),
					'AND' => array(
						
						'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
						
					),
					
				),
				'order' => array('Match.Data DESC'),
				'fields' => array(
					
					'Match.Calendario',
					'Match.Data',
					'Lda.Arbitro',
					'Lda.Arbitro2',
					'Lda.Delegato',
					'Lda.DelegatoA',
					'Campionati.TariffaArbitro',
					'Campionati.TariffaDelegato',
					'Campionati.TariffaDelegatoA',
					'Campionati.TariffaArbitro2',
					'Match.Risultato',
					
				),
				
			)
			
		);
		
		$riepilogo = array();
		
		foreach($matches as $match) {
			
			$datetime = strtotime($match['Match']['Data']);
			$mese     = date("m", $datetime);
			$anno     = date("Y", $datetime);
			
			$votes = $this->LdaVote->find('all', array(
				
				'conditions' => array(
					'match_id' => $match['Match']['Calendario'],
					'athlete_lda_id' => $id,
				),
				
			));
			
			$votes_send = $this->LdaVote->find('all', array(
				
				'conditions' => array(
					'match_id' => $match['Match']['Calendario'],
					'athlete_id' => $id,
				),
				
			));						
			
			$match['Match']['AnnoPartita'] = $anno;
			$match['LdaVote'] = $votes;
			$match['LdaVoteSend'] = $votes_send;
			
			$riepilogo[$mese][] = $match;
			
		}
		
		$mounth = array();
		$tot_compensi = 0;
		
		Configure::Write('debug',2);
		
		foreach($riepilogo as $k => $matches) {

			$count_vote = 0;
			$count_send = 0;
			$bonus      = 0;
			$compenso   = 0;
			$anno       = '';
			$count_match= 0;
			$count_vote_received = 0;

			foreach($matches as $match) {
				foreach($match['LdaVote'] as $vote) {
					$count_vote_received++;
					$count_vote += $vote['LdaVote']['ranking'];	
				}
				foreach($match['LdaVoteSend'] as $vote) {
					$count_send++;
				}
				
				if($id == $match['Lda']['Arbitro']):   $compenso += $match['Campionati']['TariffaArbitro']; endif;
				if($id == $match['Lda']['Delegato']):  $compenso += $match['Campionati']['TariffaDelegato']; endif;
				if($id == $match['Lda']['DelegatoA']): $compenso += $match['Campionati']['TariffaDelegatoA']; endif;
				if($id == $match['Lda']['Arbitro2']):  $compenso += $match['Campionati']['TariffaArbitro2']; endif;
				
				if($id == $match['Lda']['Arbitro']):   	   $bonus++; 
					elseif($id == $match['Lda']['Arbitro2']):  $bonus++;
						elseif($id == $match['Lda']['DelegatoA']): $bonus+=0.5;
							elseif($id == $match['Lda']['Delegato']):  $bonus+=0.5;	endif;

							if($match['Lda']['Arbitro'] != '' || $match['Lda']['Delegato'] != '' || $match['Lda']['DelegatoA']) { 
								if($match['Match']['Risultato'] != '' && $match['Lda']['Arbitro'] != $match['Lda']['Delegato'] && $match['Lda']['Arbitro'] != $match['Lda']['DelegatoA']) $count_match++; 
							} 

							$anno = $match['Match']['AnnoPartita'];

						}

						//debug($count_match);

						if($count_vote_received > 0) {
							$media_ranking = ceil($count_vote / $count_vote_received);
						}else {
							$media_ranking = 0;
						}

						if(($count_match - $count_send) <= 0) $vote_send = array('class' => 'full-rated', 'label' => 'Votazioni completate');
						else							      $vote_send = array('class' => 'not-rated', 'label' => ($count_match - $count_send) . ' partit'.(($count_match - $count_send == 1)? 'a' : 'e').' da votare');

							/*Calcolo spese*/
							$start_date = $anno . '-' . $k . '-' . '01';
							$end_date   = $anno . '-' . $k . '-' . '31';

							$altreSpese = $this->AthleteExpense->find('all', array(

								'conditions' => array(

									'AthleteExpense.Atleta' 	          => $id,
									'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),

								),
								'recursive' => 0,

							));

							$spese = 0;

							foreach($altreSpese as $spesa) {
								$spesa = $spesa['AthleteExpense'];
								$spese += $spesa['Importo'];
							}						

							$mounth[$k]['NumeroGare']   = count($matches);
							$mounth[$k]['MediaRanking'] = $media_ranking;
							$mounth[$k]['Votazioni']    = $vote_send;
							$mounth[$k]['VoteSend']     = $count_send;
							$mounth[$k]['Bonus']    	= $bonus;
							$mounth[$k]['Compenso']		= '€ ' . ($compenso+$spese);
							$mounth[$k]['Anno']		    = $anno;

							$tot_compensi += $compenso;

						}

						if(empty($matches)) {

							$spese  = $this->AthleteExpense->find('all', array(

								'conditions' => array(

									'AthleteExpense.Atleta' => $id,
									'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo'])

								),
								'recursive' => -1,
								'order' => array('AthleteExpense.Data DESC')

							));

							$riepilogo = array();				

							foreach($spese as $spesa) {

								$datetime = strtotime($spesa['AthleteExpense']['Data']);
								$mese     = date("m", $datetime);
								$anno     = date("Y", $datetime);

								$riepilogo[$mese][] = $spesa;

							}

							$mounth = array();
							$tot_compensi = 0;

							foreach($riepilogo as $k => $tmp) {

								/*Calcolo spese*/
								$start_date = $anno . '-' . $k . '-' . '01';
								$end_date   = $anno . '-' . $k . '-' . '31';

								$altreSpese = $this->AthleteExpense->find('all', array(

									'conditions' => array(

										'AthleteExpense.Atleta' 	          => $id,
										'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),

									),
									'recursive' => 0,

								));

								$spese = 0;

								foreach($altreSpese as $spesa) {
									$spesa = $spesa['AthleteExpense'];
									$spese += $spesa['Importo'];
								}	

								$mounth[$k]['NumeroGare']   = 0;
								$mounth[$k]['MediaRanking'] = 0;
								$mounth[$k]['Votazioni']    = 0;
								$mounth[$k]['VoteSend']     = 0;
								$mounth[$k]['Bonus']    	= 0;
								$mounth[$k]['Compenso']		= '€ ' . $spese;
								$mounth[$k]['Anno']		    = $anno;	

								$tot_compensi += $spese;						

							}

						}

					//debug($mounth);

					//debug($tot_compensi);

					//Setto stagione
						$this->set('stagione', $last_year['AnniSportivi']['AnnoSportivo']);		
						$this->set('tot_compensi', $tot_compensi);	


						$this->set('athlete_id', $id);		

					//debug($riepilogo);
						$this->set('mounths', $mounth);

					}



    function admin_edit($id)
    {

        $this->layout = "ajax";

        /* //GIUSEPPE 2019-01-09 */
        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi
        /*         * ******************** */

        $group_id = $this->Auth->user('group_id');
        $this->set('group_id', $group_id);

        $spese = $this->AthleteExpense->find('all', array(
            'conditions' => array(
                'AthleteExpense.Atleta' => $id,
            ),
            'order' => 'AthleteExpense.Data DESC',
        ));

        $this->set('spese', $spese);
        //print_r($spese);


        $this->admin_getBuste($id);

        if (empty($this->data)) // questo lo abbiamo quando apriamo il profilo dell'atleta da admin (non vengono inviati dati)
        {

            $this->data = $this->Athlete->find('first', array('conditions' => array($this->Athlete->primaryKey => $id)));

            /*
              Configure::write('debug',2);
              debug($this->data);
             */

            //GIUSEPPE 2018-12-15 ---------------------------------------------------------
            $certificato_medico = $this->data['Athlete']['ScadenzaCertificatoMedico'];
            $this->data['Athlete']['CertificatoMedico'] = $certificato_medico;
            //-----------------------------------------------------------------------------

            $this->data['Athlete']['ScadenzaDocumento'] = '%' . (!empty($this->data['Athlete']['ScadenzaDocumento'])) ? date("d/m/Y", strtotime($this->data['Athlete']['ScadenzaDocumento'])) : '';
            //GIUSEPPE 2023-07-28  ---------- mi serve il formato Y-m-d
            //           $this->data['Athlete']['DataNascita'] = '%' . (!empty($this->data['Athlete']['DataNascita'])) ? date("d/m/Y", strtotime($this->data['Athlete']['DataNascita'])) : '';
            // ------------------------------  
            $this->data['Athlete']['ScadenzaCertificatoMedico'] = '%' . (!empty($this->data['Athlete']['ScadenzaCertificatoMedico'])) ? date("d/m/Y", strtotime($this->data['Athlete']['ScadenzaCertificatoMedico'])) : '';

            if ($this->data['Athlete']['data_registrazione'] != '0000-00-00 00:00:00')
                $this->set('gia_registrato', 1);

            $this->data['Athlete']['password'] = '';

            $this->data['Athlete']['cazz'] = "mbrazz";

            $this->Athlete->set($this->data);
        }
        else
        {

            $data_old = $this->Athlete->read(null, $id);

            $this->data['Athlete'][$this->Athlete->primaryKey] = $id;

            if ($this->data['Athlete']['Arbitro'] == 'No')
            {
                $this->Athlete->unbindValidation('remove', array('Email', 'password'), false);
            }
            else
            {
                $this->Athlete->unbindValidation('remove', array('password'), false);
            }

            if ($this->data['Athlete']['password'] != '')
            {
                $pwd = $this->data['Athlete']['password'];

                if (strlen($pwd) < 5 || strlen($pwd) > 12)
                {

                    $this->Athlete->invalidate('password', 'Lunghezza password compresa tra 6 e 12 caratteri.');
                    return false;
                }

                $this->data['Athlete']['password'] = $this->Auth->password($this->data['Athlete']['password']);
                $this->data['Athlete']['data_registrazione'] = date('Y-m-d H:i:s');
            }
            else
            {

                $this->data['Athlete']['password'] = $data_old['Athlete']['password'];
            }




            $this->Athlete->set($this->data);

            $ADD_OK = true;

            if ($this->Athlete->save())
            {

                if ($this->__adminUploadFile('athlete_id', $id) == true)
                {

                    $this->insert_tag($id);

                    $ADD_OK = false;
                }


                if (($this->data['Athlete']['Arbitro'] === 'Si') && ($data_old['Athlete']['Arbitro'] === 'No'))
                {
                    $this->set('link', "http://" . $_SERVER['SERVER_NAME']);
                    $this->set('anagrafica', $this->data['Athlete']['Nome'] . ' ' . $this->data['Athlete']['Cognome']);
                    $this->set('user', $this->data['Athlete']['Email']);
                    $this->set('pwd', $pwd);
                    $this->Email->to = $this->data['Athlete']['Email'];
                    $this->Email->subject = 'Play League SSDARL | Registrazione nuovo arbitro';
                    $this->Email->template = 'user_add_arbitro';
                    $this->Email->send();
                }
                elseif ($this->data['Athlete']['password'] !== $data_old['Athlete']['password'])
                {
//                    $this->set('link', "http://" . $_SERVER['SERVER_NAME']);
                    // $this->set('anagrafica', $this->data['Athlete']['Nome'] . ' ' . $this->data['Athlete']['Cognome']);

                    $udata['nome'] = $this->data['Athlete']['Nome'];
                    $udata['cognome'] = $this->data['Athlete']['Cognome'];
                    $udata['username'] = $this->data['Athlete']['Email'];

                    $this->set('User', $udata);

                    $this->set('newpass', $pwd);
                    $this->Email->to = $this->data['Athlete']['Email'];
                    $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';
                    $this->Email->subject = 'Recupero password';
                    $this->Email->template = 'recover_fo';
                    $this->Email->send();
                }


//                if ($this->data['Athlete']['password'] != '' && $this->data['Athlete']['password'] != $data_old['Athlete']['password'])
//                {
//                    $this->set('link', "http://" . $_SERVER['SERVER_NAME']);
//                    $this->set('anagrafica', $this->data['Athlete']['Nome'] . ' ' . $this->data['Athlete']['Cognome']);
//                    $this->set('user', $this->data['Athlete']['Email']);
//                    $this->set('pwd', $pwd);
//                    $this->Email->to = $this->data['Athlete']['Email'];
//                    $this->Email->subject = 'Midland Sport | Registrazione nuovo arbitro';
//                    $this->Email->template = 'user_add_arbitro';
//                    $this->Email->send();
//                }

                $this->data['Athlete']['password'] = '';

                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }




			function admin_unregister($athlete_id) {

				$this->layout = "ajax";

				$this->Athlete->updateAll(

					array('Athlete.password' => null, 'Athlete.data_registrazione' => 0),
					array('Athlete.Atleta' => $athlete_id)

				);

				exit;

			}

			function admin_spesaAdd() {

				$this->layout = "ajax";

				/*
				
				FINIRE
				
				*/
				
				$this->AthleteExpense->set($this->data);
				
				if($this->AthleteExpense->save()) {
					
					$ret   = $this->AthleteExpense->read(null, $this->AthleteExpense->id);
					$error = 0;
					
				} else {
					
					$ret = $this->AthleteExpense->invalidFields();
					$error = 1;
					
				}
				
				$this->set('result', json_encode(array('data' => $ret, 'error' => $error)));
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_spesaDelete($id) {
				
				$this->layout = "ajax";
				
				if($this->AthleteExpense->delete($id)) {
					
					$delete = 1;
					
				} else {
					
					$delete = 0;
					
				}
				
				$this->set('result', json_encode(array('delete' => $delete)));
				$this->render('/backend/ajaxResult');
				
			}

			function admin_searchAthleteIndex() {
				
				$this->layout = "timmybox";
				
			}
			
			function admin_searchAthlete() {
				
				$this->layout = "ajax";
				
				$conditions = array();
				
				if(!empty($this->data['Athlete']['DataNascita']) && $this->data['Athlete']['DataNascita'] != '') $this->dmy2ymd($this->data['Athlete']['DataNascita']);
				
				if(!empty($this->data['Athlete']['ScadenzaCertificatoMedico']) && $this->data['Athlete']['ScadenzaCertificatoMedico'] != '') $this->dmy2ymd($this->data['Athlete']['ScadenzaCertificatoMedico']);
				
				if(!empty($this->data['Athlete']['Atleta']) && isset($this->data['Athlete']['Atleta'])) {
					
					$conditions['Athlete.Atleta'] = $this->data['Athlete']['Atleta'];
					
				} else {
					
					if(!empty($this->data['Athlete']['Cognome'])) $conditions['Athlete.Cognome'] = $this->data['Athlete']['Cognome'];
					if(!empty($this->data['Athlete']['Nome'])) $conditions['Athlete.Nome'] = $this->data['Athlete']['Nome'];
					if(!empty($this->data['Athlete']['Indirizzo'])) $conditions['Athlete.Indirizzo LIKE'] = '%' . $this->data['Athlete']['Indirizzo'] . '%';
					if(!empty($this->data['Athlete']['Cap'])) $conditions['Athlete.Cap LIKE'] = '%' . $this->data['Athlete']['Cap'] . '%';
					if(!empty($this->data['Athlete']['Localita'])) $conditions['Athlete.Localita LIKE'] = '%' . $this->data['Athlete']['Localita'] . '%';
					if(!empty($this->data['Athlete']['Provincia'])) $conditions['Athlete.Provincia LIKE'] = '%' . $this->data['Athlete']['Provincia'] . '%';
					if(!empty($this->data['Athlete']['Telefono'])) $conditions['Athlete.Telefono LIKE'] = '%' . $this->data['Athlete']['Telefono'] . '%';
					if(!empty($this->data['Athlete']['Cellulare'])) $conditions['Athlete.Cellulare LIKE'] = '%' . $this->data['Athlete']['Cellulare'] . '%';
					if(!empty($this->data['Athlete']['Lavoro'])) $conditions['Athlete.Lavoro LIKE'] = '%' . $this->data['Athlete']['Lavoro'] . '%';
					if(!empty($this->data['Athlete']['Fax'])) $conditions['Athlete.Fax LIKE'] = '%' . $this->data['Athlete']['Fax'] . '%';
					if(!empty($this->data['Athlete']['Email'])) $conditions['Athlete.Email LIKE'] = '%' . $this->data['Athlete']['Email'] . '%';
					if(!empty($this->data['Athlete']['LuogoNascita'])) $conditions['Athlete.LuogoNascita LIKE'] = '%' . $this->data['Athlete']['LuogoNascita'] . '%';
					if(!empty($this->data['Athlete']['DataNascita'])) $conditions['Athlete.DataNascita LIKE'] = '%' . $this->data['Athlete']['DataNascita'] . '%';
					if(!empty($this->data['Athlete']['ScadenzaCertificatoMedico'])) $conditions['Athlete.ScadenzaCertificatoMedico LIKE'] = '%' . $this->data['Athlete']['ScadenzaCertificatoMedico'] . '%';
					if(!empty($this->data['Athlete']['TipoDocumento'])) $conditions['Athlete.TipoDocumento LIKE'] = '%' . $this->data['Athlete']['TipoDocumento'] . '%';
					if(!empty($this->data['Athlete']['NumeroDocumento'])) $conditions['Athlete.NumeroDocumento LIKE'] = '%' . $this->data['Athlete']['NumeroDocumento'] . '%';
					if(!empty($this->data['Athlete']['ScadenzaDocumento'])) $conditions['Athlete.ScadenzaDocumento LIKE'] = '%' . $this->data['Athlete']['ScadenzaDocumento'] . '%';
					if(!empty($this->data['Athlete']['Sesso'])) $conditions['Athlete.Sesso LIKE'] = '%' . $this->data['Athlete']['Sesso'] . '%';
					if(!empty($this->data['Athlete']['Responsabile'])) $conditions['Athlete.Responsabile LIKE'] = '%' . $this->data['Athlete']['Responsabile'] . '%';
					if(!empty($this->data['Athlete']['Arbitro'])) $conditions['Athlete.Arbitro LIKE'] = '%' . $this->data['Athlete']['Arbitro'] . '%';
					if(!empty($this->data['Athlete']['Sportivo'])) $conditions['Athlete.Sportivo LIKE'] = '%' . $this->data['Athlete']['Sportivo'] . '%';
					if(!empty($this->data['Athlete']['CodiceFiscale'])) $conditions['Athlete.CodiceFiscale LIKE'] = '%' . $this->data['Athlete']['CodiceFiscale'] . '%';
					
				}
				
				$athletes = $this->Athlete->find('all', 
					
					array(
						
						'conditions' => $conditions,
						'order' => array('Athlete.Cognome ASC','Athlete.Nome ASC'),
					)
				); 
				
				$this->set('result', json_encode($athletes));
				$this->render('/backend/ajaxResult');
				
				
				
			}
			
			function admin_newAthlete() {
				
				$this->layout = "ajax";	
				
				$this->Athlete->set($this->data);
				
				if($this->data['Athlete']['Arbitro'] == 'No') { $this->Athlete->unbindValidation('remove', array('Email') ,false); }
				
				if ($this->Athlete->save()) {

					$add = $this->Athlete->id;
					
				} else {
					
//					$add = $this->Athlete->invalidFields();
					$add = "error";
					
				}
				
				$this->set('result', json_encode(array('add' => $add)));
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_createList($option = null) {
				
				$this->layout = "ajax";
				
				$athletes = $_POST['athletes'];
				
				switch($option) {
					
					case 'yearbook':
					
					$athletes_ = array();
					
					foreach($athletes as $athlete) {
						
						$data = $this->Yearbook->findByAnnuario($athlete);
						
						if (!empty($data['Athlete']['Email']) || !empty($data['Athlete']['Cellulare']))
							$athletes_[] = $data['Athlete']['Atleta'];
						
					}
					
					$athletes = $athletes_;
					
					break;
					
					case 'matches':
					
					$athletes_ = array();
					
					foreach($athletes as $partita) {
						
						$data = $this->Match->findByCalendario($partita);
						
						$yearbooks = $this->Yearbook->find('list', array(
							
							'fields' 	 => array('Yearbook.Annuario'),
							'conditions' => array(
								
								'OR' => array(
									
									array('Yearbook.SquadraCampionato' => $data['Match']['Casa']),
									array('Yearbook.SquadraCampionato' => $data['Match']['Trasferta']),
									
								),
								
							),
							
						));
						
						foreach($yearbooks as $yearbook) {
							
							$data = $this->Yearbook->findByAnnuario($yearbook);
							if (!empty($data['Athlete']['Email']) || !empty($data['Athlete']['Cellulare']))
								$athletes_[] = $data['Athlete']['Atleta'];
							
						}
						
					}
					
					$athletes = $athletes_;
					
					break;
					
					case 'campionatis':
					
					$athletes_ = array();
					
					$squadre_campionato = $this->SquadreCampionati->find('list', array(
						
						'fields'     => array('SquadreCampionati.SquadraCampionato'),
						'conditions' => array(
							
							'SquadreCampionati.Campionato' => $athletes,
							
						),
						
					));
					
					$yearbooks = $this->Yearbook->find('list', array(
						
						'fields'     => array('Yearbook.Annuario'),
						'conditions' => array(
							
							'Yearbook.SquadraCampionato' => $squadre_campionato,
							
						),
						
					));
					
					foreach($yearbooks as $yearbook) {
						
						$data = $this->Yearbook->findByAnnuario($yearbook);
						
						if (!empty($data['Athlete']['Email']) || !empty($data['Athlete']['Cellulare']))
							$athletes_[] = $data['Athlete']['Atleta'];
						
					}	

					$athletes = $athletes_;
					
					break;
					
				}
				
				$list = $this->Session->read('SmsMailList');
				
				if($list == '') $list = array();
				
				$athletes_ = $athletes;
				
				$diff = count(array_diff(array_unique($athletes), $list));
				
				$newList = array_merge($list, $athletes);
				$newList = array_unique($newList);
				
				$this->Session->write('SmsMailList', $newList);
				
				$this->set('result', json_encode(array('update' => 1, 'diff' => $diff)));
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_sendMailSms() {
				
				$this->layout = "timmybox";
				
				$lists = $this->Session->read('SmsMailList');
				
				$data = $this->Athlete->find('all', array(
					
					'fields' => array('Athlete.Atleta','Athlete.reverseAnagrafica','Athlete.Email','Athlete.Cellulare'),
					'conditions' => array(
						
						'Athlete.Atleta' => $lists,
						'OR' => 
						array(
							array('Athlete.Cellulare !=' => ''),
							array('Athlete.Email !=' => '')
						)
					),
					
				));
				
				foreach($data as $k => $d) {
					
					$tmp = $this->Yearbook->find('first', array(
						
						'conditions' => array(
							
							'Yearbook.Atleta' => $d['Athlete']['Atleta'],
							
						),
						'order' => 'Yearbook.AnnoSportivo DESC'
						
					));
					
					$data[$k]['Athlete']['NomeSquadra'] = $tmp['Yearbook']['NomeSquadra'];
					
				}
				
				$this->set('athletes', $data);
				
			}
			
			function admin_sendMailSms_go() {
				
				$this->layout = "ajax";
				
				$lists = $this->Session->read('SmsMailList');
				
				$listSms   = array();
				$listEmail = array();
				$listCount = 0;
				$dontCount = 0;
				
				foreach($lists as $list) {
					
					$data = $this->Athlete->findByAtleta($list);
					
					if(!empty($data['Athlete']['Email'])) {
						
						$listEmail[] = $data['Athlete']['Email'];
						
					}
					
					if(!empty($data['Athlete']['Cellulare'])) {
						
						$listSms[] = $data['Athlete']['Cellulare'];
						
					}
					
				}
				
				if($this->data['SendMailSms']['SendOption'] == 'email') {
					
					// invio mail		
					if(count($listEmail)) {
						
						$this->EmailModel->create();
						
						$this->data['EmailModel']['subject'] = $this->data['SendMailSms']['object'];
						$this->data['EmailModel']['message'] = $this->data['SendMailSms']['text'];
						$this->data['EmailModel']['layout'] = 'comunication';
						
						$this->EmailModel->set($this->data);
						
						if ($this->EmailModel->save()) {
							
							$email_id = $this->EmailModel->id;
							
							foreach ($listEmail as $email) {
								
								$this->Spool->create();
								
								$this->data['Spool']['mail_id'] = $email_id;
								$this->data['Spool']['email'] = $email;
								
								$this->Spool->set($this->data);
								if($this->Spool->save()) $listCount++;
								else					 $dontCount++;
								
								
							}
							
						}
						
					}

				} else {
					
					if(count($listSms)) {
						
					//invia sms
						$this->EmailModel->create();
						
						$this->data['EmailModel']['from']    = 'noreply@playleaguesport.it';
						$this->data['EmailModel']['subject'] = $this->data['SendMailSms']['text'];
						$this->data['EmailModel']['message'] = '';
						$this->data['EmailModel']['layout']  = 'comunication';
						
						$this->EmailModel->set($this->data);
						
						if ($this->EmailModel->save()) {
							
							$email_id = $this->EmailModel->id;
							
							foreach ($listSms as $sms) {
								
								$sms = ereg_replace("[^0-9]", "", $sms);
								
								$this->Spool->create();
								
								$this->data['Spool']['mail_id'] = $email_id;
								$this->data['Spool']['email'] 	= '39' . $sms . '@smsviaemail.it';
								
								$this->Spool->set($this->data);
								
								if($this->Spool->save()) $listCount++;
								else					 $dontCount++;
								
							}
							
						}		

					}
					
				}
				
				$this->set('result', json_encode(array('send' => $listCount, 'dontSend' => $dontCount)));
				$this->render('/backend/ajaxResult');				
				
			}
			
			function admin_checkSession() {
				
				$this->layout = "ajax";
				
				$list = $this->Session->read('SmsMailList');
				
				if($list != '' && $list != array()) {
					
					$check = 1;
					
				} else {
					
					$check = 0;
					
				}
				
				$this->set('result', json_encode(array('check' => $check)));
				$this->render('/backend/ajaxResult');
				
			}
			
			function admin_deleteSmsEmail($id = null) {
				
				$this->layout = "ajax";
				
				$lists = $this->Session->read('SmsMailList');
				
				$delete = 0;
				
				foreach($lists as $key => $list) {
					
					if($list == $id) {
						
						$delete = 1;
						unset($lists[$key]);
						
					}
					
				}
				
				$this->Session->delete('SmsMailList');
				$this->Session->write('SmsMailList', $lists);
				
				$this->set('result', json_encode(array('delete' => $delete)));
				$this->render('/backend/ajaxResult');				
				
			}
			
			function admin_deleteSmsEmailAll() {
				
				$this->layout = "ajax";
				
				$deleteAll = $_POST['athletes']; 
				$lists 	   = $this->Session->read('SmsMailList');
				
				$delete = 0;
				
				foreach($lists as $key => $list) {
					
					if(in_array($list, $deleteAll)) {
						
						unset($lists[$key]);
						$delete = 1;
						
					}
					
				}
				
				$this->Session->delete('SmsMailList');
				$this->Session->write('SmsMailList', $lists);
				
				$this->set('result', json_encode(array('delete' => $delete)));
				$this->render('/backend/ajaxResult');					
				
			}
			
			function admin_deleteSession() {
				
				$this->Session->delete('SmsMailList');
				
				$this->redirect('/admin/athletes/index');
				
			}
			
			function admin_ajaxAthleteSearch($athlete) {
				
				$this->layout = "ajax";
				
				$data = $this->Athlete->findByAtleta($athlete);
				
				$data['Athlete']['DataNascita'] = str_replace('.','/',$data['Athlete']['DataNascita_it']);
				$data['Athlete']['ScadenzaDocumento'] = str_replace('.','/',$data['Athlete']['ScadenzaDocumento_it']);
				$data['Athlete']['ScadenzaCertificatoMedico'] = str_replace('.','/',$data['Athlete']['ScadenzaCertificatoMedico_it']);
				
				$this->set('result', json_encode(array('athlete' => $data)));
				$this->render('/backend/ajaxResult');				
				
			}
			
			/* GESTIONE PROFILO ARBITRO */
			
			function vote() {
				
				Configure::Write('debug', 0);
				
				$this->layout = "content";
				$this->login_site = true;
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					//$file = APP . 'webroot/files/json/vote/vote_lda_'.$this->Session->read('Login.data.id').'_'.date('d_m_Y').'.json';
					
					//$sfide_mensili = json_decode(file_get_contents($file));
					
					
					//GIUSEPPE 2018-07-16 commentare questo $data_max - visualizzazioni voti arbitro (visualizzazione anno sportivo in corso - anche se è statop inserito il nuovo anno sportivo)

		            /* $data_max = strtotime("2015-09-19 00:00:00"); */ // questo è il vecchio $data_max: commentarlo


		            $date_to_max = $this->requestAction('sections/data_max');

		            $data_max = strtotime("$date_to_max 00:00:00");
		            // - - - - - - - - - - - - - - - - - - - - - - - - - -

					if (time() > $data_max) {



						$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					} else {

						$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
						$last_year['AnniSportivi']['AnnoSportivo']--;

					}
					$matches = $this->Match->find('all',
						
						array(
							
							'conditions' => array(
								
								'OR' => array(
									
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
									
									
								),
								'AND' => array(
									
									'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
									
								),
								
							),
							'order' => array('Match.Data ASC'),
							'fields' => array(
								'Match.Calendario',
								'Match.CasaNome',
								'Match.TrasfertaNome',
								'Match.Risultato',
								'Causalresult.Descrizione',
								'Match.Giornata',
								'Match.Data_it',
								'Match.Data',
								'Match.Ora',
								'Match.CountArbitro',
								'Match.CountArbitro2',
								'Campi.Descrizione',
								'Match.Casa',
								'Match.Trasferta',
								'Match.NomeGara',
								'Campi.Campo',
								'Campi.isMidland',
								'Casa.Squadra',
								'Trasferta.Squadra',
								'Match.NomeArbitro',
								'Match.NomeDelegato',
								'Match.NomeDelegatoA',
								'Match.NomeArbitro2',
								'Lda.Arbitro',
								'Lda.Delegato',
								'Lda.DelegatoA',
								'Lda.Arbitro2',
								'Campionati.Italiana',
								'Campi.Descrizione',
								'Campi.latitudine',
								'Campi.longitudine',
								'Campi.Indirizzo',
								'Campi.Citta',
								'Campi.Provincia',
								'Campi.Telefono',
								'Campi.Email',
								
							),
							
						)
						
					);		
					
					//debug($matches);
					
					$sfide_mensili = array();	
					
					foreach($matches as $match) {
						
						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);
						
						$sfide_mensili[$mese][] = $match;	
						
					}						
					
					$this->set('sfide_mensili', $sfide_mensili);
					
				}
				
			}
			
	//Voto atleti
			function vota($sport)
			{
				
				$this->layout = "content";
				$this->login_site = true;

				if ($this->Session->read('Login.data.is_atleta'))
				{

					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

					$data_yearbooks = $this->Yearbook->find('list', array(
						'fields' => array('Yearbook.SquadraCampionato'),
						'conditions' => array(
							'Yearbook.Atleta' => $this->Session->read('Login.data.id'),
							'Yearbook.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo']
						),
					));

					$data_yearbooks = array_merge($data_yearbooks);
					$data_yearbooks = array_unique($data_yearbooks);

					$matches = $this->Match->find('all', array(
						'conditions' => array(
							'OR' => array(
								'Match.Casa' => $data_yearbooks,
								'Match.Trasferta' => $data_yearbooks,
							),
							'AND' => array(
								'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
								'Campionati.sport' => $sport,
							),
						),
						'order' => array('Match.Data ASC'),
						'fields' => array(
							'Match.Calendario',
							'Match.CasaNome',
							'Match.TrasfertaNome',
							'Match.Risultato',
							'Causalresult.Descrizione',
							'Match.Giornata',
							'Match.Data_it',
							'Match.Data',
							'Match.Ora',
							'Campi.Descrizione',
							'Match.Casa',
							'Match.Trasferta',
							'Match.NomeGara',
							'Campi.Campo',
							'Campi.isMidland',
							'Casa.Squadra',
							'Trasferta.Squadra',
							'Match.NomeArbitro',
							'Match.NomeDelegato',
							'Match.NomeDelegatoA',
							'Lda.Arbitro',
							'Lda.Delegato',
							'Lda.DelegatoA',
							'Campionati.Italiana',
							'Campi.Descrizione',
							'Campi.latitudine',
							'Campi.longitudine',
							'Campi.Indirizzo',
							'Campi.Citta',
							'Campi.Provincia',
							'Campi.Telefono',
							'Campi.Email',
						),
					)
				);

					$sfide_mensili = array();

					foreach ($matches as $match)
					{

						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);

						$sfide_mensili[$mese][] = $match;
					}

					$this->set('sfide_mensili', $sfide_mensili);
				}
			}
			




	//GIUSEPPE 2017-02-22 .....................................................................
	//
			function tennis_points()
			{

				$this->layout = "content";
				$this->login_site = true;

				if ($this->Session->read('Login.data.is_atleta'))
				{

					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

					$data_yearbooks = $this->Yearbook->find('list', array(
						'fields' => array('Yearbook.SquadraCampionato'),
						'conditions' => array(
							'Yearbook.Atleta' => $this->Session->read('Login.data.id'),
							'Yearbook.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo']
						),
					));

					$data_yearbooks = array_merge($data_yearbooks);
					$data_yearbooks = array_unique($data_yearbooks);

					$matches = $this->Match->find('all', array(
						'conditions' => array(
							'OR' => array(
								'Match.Casa' => $data_yearbooks,
								'Match.Trasferta' => $data_yearbooks,
							),
							'AND' => array(
								'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
								'Campionati.sport' => 'TENNIS',
							),
						),
						'order' => array('Match.Data ASC'),
						'fields' => array(
							'Match.Calendario',
							'Match.CasaNome',
							'Match.TrasfertaNome',
							'Match.Risultato',
							'Causalresult.Descrizione',
							'Match.Giornata',
							'Match.Data_it',
							'Match.Data',
							'Match.Ora',
							'Campi.Descrizione',
							'Match.Casa',
							'Match.Trasferta',
							'Match.NomeGara',
							'Campi.Campo',
							'Campi.isMidland',
							'Casa.Squadra',
							'Trasferta.Squadra',
							'Match.NomeArbitro',
							'Match.NomeDelegato',
							'Match.NomeDelegatoA',
							'Lda.Arbitro',
							'Lda.Delegato',
							'Lda.DelegatoA',
							'Campionati.Italiana',
							'Campi.Descrizione',
							'Campi.latitudine',
							'Campi.longitudine',
							'Campi.Indirizzo',
							'Campi.Citta',
							'Campi.Provincia',
							'Campi.Telefono',
							'Campi.Email',
						),
					)
				);

					$sfide_mensili = array();

					foreach ($matches as $match)
					{

						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);

						$sfide_mensili[$mese][] = $match;
					}

					$this->set('sfide_mensili', $sfide_mensili);
				}
			}



    ////////////////////////////////////////////////////////////////////////////////
    // GIUSEPPE 2018-06-07 ........................................................
    ////////////////////////////////////////////////////////////////////////////////

    function scarpa_doro()
    {
        $this->layout = "content";

        $all_athletes = array();

        $types = array("0" => "C5", "1" => "C7");
        $genders = array("0" => "M", "1" => "F");

        //GIUSEPPE 2018-07-16 Anno sportivo per visualizzazione scarpa d'oro -----------
        $last_year_array = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

        $last_year = $last_year_array['AnniSportivi']['AnnoSportivo'];

        $data_max = strtotime($this->requestAction('sections/data_max') . " 00:00:00");

        if (time() < $data_max)
        {
            $last_year--;
        }
        // inserito $last_year nella WHERE della query
        //------------------------------------------------------------------------------


        $sql = 'SELECT 

                        GoalPartite.GoalPartita
                        ,GoalPartite.Calendario
                        ,GoalPartite.SquadraCampionato
                        ,GoalPartite.Atleta
                        ,CONCAT(Atleti.Cognome, " ", Atleti.Nome) as Nominativo
                        ,GoalPartite.Goal
                        ,GoalPartite.Autogoal
                        ,SquadreCampionati.Squadra
                        ,Squadre.Denominazione
                        ,SquadreCampionati.Campionato
                        ,Campionati.Nome
                        ,SquadreCampionati.GironeCampionato
                        ,(SELECT GironiCampionati.Descrizione FROM GironiCampionati WHERE GironiCampionati.GironeCampionato = SquadreCampionati.GironeCampionato AND GironiCampionati.Campionato = SquadreCampionati.Campionato) as NomeGirone
                        ,Campionati.AnnoSportivo
                        ,Campionati.Tipo
                        ,Campionati.SessoTipo

                        FROM `GoalPartite`

                        INNER JOIN
                        SquadreCampionati

                        ON
                        SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato


                        INNER JOIN
                        Campionati

                        ON
                        Campionati.Campionato = SquadreCampionati.Campionato

                        INNER JOIN
                        Atleti

                        ON
                        GoalPartite.Atleta = Atleti.Atleta

                        INNER JOIN
                        Squadre

                        ON SquadreCampionati.Squadra = Squadre.Squadra
                        
                   

                        WHERE 

                        Campionati.AnnoSportivo = ' . $last_year . '
                        AND
                        Campionati.sport = "CALCIO"
                        AND
                        Campionati.scuola = 0
                        AND
                        (Campionati.Tipo = 0 OR Campionati.Tipo = 1)
                       
                 
                        ORDER BY `Campionati`.`AnnoSportivo`  DESC';


        $result = mysql_query($sql);


        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //$all_sets[] = json_decode($row['SetTennis'], true);

                $nominativo = $row['Nominativo'];
                $calendario = $row['Calendario'];
                $goal = $row['Goal'];
                $id_squadra = $row['Squadra'];
                $squadra = $row['Denominazione'];
                $gender = $genders[$row['SessoTipo']];
                $type = $types[$row['Tipo']];

                $all_athletes[$gender][$type][$row['Atleta']]['Nominativo'] = $nominativo;
                $all_athletes[$gender][$type][$row['Atleta']]['ID'] = $row['Atleta'];
                $all_athletes[$gender][$type][$row['Atleta']]['Calendario'][$calendario] = "($goal) " . $squadra;
                //$scarpa_oro[$row['Atleta']]['Calendario'][$calendario]['Goal'] = $goal;
                $all_athletes[$gender][$type][$row['Atleta']]['Goal'] += $goal;
                $all_athletes[$gender][$type][$row['Atleta']]['Squadra'][$id_squadra] = "<a href=\"/squadra/dettaglio/$id_squadra/\" target=\"_blank\">" . $squadra . "</a>";
                $all_athletes[$gender][$type][$row['Atleta']]['GareGoal'] = count($all_athletes[$gender][$type][$row['Atleta']]['Calendario']);
            }
        }

        // ordinamento bubble sort da fare in base ai punti e alle gare

        $maschile_c5 = $all_athletes['M']['C5'];
        $maschile_c7 = $all_athletes['M']['C7'];
        $femminile_c5 = $all_athletes['F']['C5'];



        $this->set('maschile_c5', $maschile_c5);
        $this->set('maschile_c7', $maschile_c7);
        $this->set('femminile_c5', $femminile_c5);

//        $this->set('all_athletes', $result);

    }


		////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////










			
			function buste($page = 1) { 

				$this->layout = "content";
				$this->login_site = true;
				
				//Configure::Write('debug',2);
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));


		        //GIUSEPPE 2018-07-16 data prossimo Anno sportivo - -
	            
	            /* $data_max = strtotime("2015-09-19 00:00:00"); */  // commentare il vecchio $data_max
	            
	            $date_to_max = $this->requestAction('sections/data_max');

	            $data_max = strtotime("$date_to_max 00:00:00");

	            if (time() < $data_max)
	            {
	                $last_year['AnniSportivi']['AnnoSportivo'] --;
	            }
	            // - - - - - - - - - - - - - - - - - - - - - - - - - -
            

					
					//Cerco gare effettuate dall'atleta
					$matches = $this->Match->find('all',
						
						array(
							
							'conditions' => array(
								
								'OR' => array(
									
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
									
									
								),
								'AND' => array(
									
									'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
									
								),
								
							),
							'order' => array('Match.Data DESC'),
							'fields' => array(
								
								'Match.Calendario',
								'Match.Data',
								'Lda.Arbitro',
								'Lda.Arbitro2',
								'Lda.Delegato',
								'Lda.DelegatoA',
								'Campionati.TariffaArbitro',
								'Campionati.TariffaDelegato',
								'Campionati.TariffaDelegatoA',
								'Campionati.TariffaArbitro2',
								'Match.Risultato',
								
							),
							
						)
						
					);

					$riepilogo = array();
					


					foreach($matches as $match) {
						
						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);
						
						$votes = $this->LdaVote->find('all', array(
							
							'conditions' => array(
								'match_id' => $match['Match']['Calendario'],
								'athlete_lda_id' => $this->Session->read('Login.data.id'),
							),
							
						));
						
						$votes_send = $this->LdaVote->find('all', array(
							
							'conditions' => array(
								'match_id' => $match['Match']['Calendario'],
								'athlete_id' => $this->Session->read('Login.data.id'),
							),
							
						));						
						
						$match['Match']['AnnoPartita'] = $anno;
						$match['LdaVote'] = $votes;
						$match['LdaVoteSend'] = $votes_send;
						



						$riepilogo[$mese][] = $match;
						
					}


					// 	$spese  = $this->AthleteExpense->find('all', array(
					
					// 		'conditions' => array(
					
					// 			'AthleteExpense.Atleta' => $this->Session->read('Login.data.id'),
					// 			'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo']),
					// 			'AthleteExpense.Data > ' => ($last_year['AnniSportivi']['AnnoSportivo']-1 . '-09-01'),
					
					
					// 		),
					// 		'recursive' => -1,
					// 		'order' => array('AthleteExpense.Data DESC')
					
					
					for ($i = 1; $i < 12; $i++) {

						$spese  = $this->AthleteExpense->find('all', array(
							
							'conditions' => array(
								
								'AthleteExpense.Atleta' => $this->Session->read('Login.data.id'),
								'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo']),
								'MONTH(AthleteExpense.Data)' => $i,
								'AthleteExpense.Data > ' => ($last_year['AnniSportivi']['AnnoSportivo']-1 . '-09-01'),
								
								
							),
							'recursive' => -1,
							'order' => array('AthleteExpense.Data DESC')
						));

						if (count($spese) && !count($riepilogo[$i])) {
							$datetime = strtotime($spese[0]['AthleteExpense']['Data']);
							$mese     = date("m", $datetime);
							$anno     = date("Y", $datetime);

							$riepilogo[$mese][] = array('Match' => array(

								'AnnoPartita' => $anno,
								'SoloSpeseFisse' => 1,
							));

							
						}
					}

					
					
					$mounth = array();
					$tot_compensi = 0;
					
					foreach($riepilogo as $k => $matches) {

						$count_vote = 0;
						$count_send = 0;
						$bonus      = 0;
						$compenso   = 0;
						$anno       = '';
						$count_match= 0;
						$count_vote_received = 0;

						foreach($matches as $match) {
							foreach($match['LdaVote'] as $vote) {
								$count_vote_received++;
								$count_vote += $vote['LdaVote']['ranking'];	
							}
							foreach($match['LdaVoteSend'] as $vote) {
								$count_send++;
							}
							
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro']):   $compenso += $match['Campionati']['TariffaArbitro']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['Delegato']):  $compenso += $match['Campionati']['TariffaDelegato']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['DelegatoA']): $compenso += $match['Campionati']['TariffaDelegatoA']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro2']):	 $compenso += $match['Campionati']['TariffaArbitro2']; endif;
							
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro']):   	 $bonus++; 
								elseif($this->Session->read('Login.data.id') == $match['Lda']['Arbitro2']):  $bonus++;
									elseif($this->Session->read('Login.data.id') == $match['Lda']['DelegatoA']): $bonus+=0.5;
										elseif($this->Session->read('Login.data.id') == $match['Lda']['Delegato']):  $bonus+=0.5; endif;	

							//if($this->Session->read('Login.data.id') == $match['Lda']['Delegato']) debug('sono delegato');

										if($match['Lda']['Arbitro'] != '' || $match['Lda']['Delegato'] != '' || $match['Lda']['DelegatoA']) { 
											if($match['Match']['Risultato'] != '' && $match['Lda']['Arbitro'] != $match['Lda']['Delegato'] && $match['Lda']['Arbitro'] != $match['Lda']['DelegatoA']) $count_match++; 
										} 

										$anno = $match['Match']['AnnoPartita'];

									}


						//debug($count_match);

									if($count_vote_received > 0) {
										$media_ranking = ceil($count_vote / $count_vote_received);
									}else {
										$media_ranking = 0;
									}

									if(($count_match - $count_send) <= 0) $vote_send = array('class' => 'full-rated', 'label' => 'Votazioni completate');
									else							      $vote_send = array('class' => 'not-rated', 'label' => ($count_match - $count_send) . ' partit'.(($count_match - $count_send == 1)? 'a' : 'e').' da votare');

										/*Calcolo spese*/
										$start_date = $anno . '-' . $k . '-' . '01';
										$end_date   = $anno . '-' . $k . '-' . '31';




						/*$lda = $this->Lda->find('all', array(
							 'conditions' => array(
								'Lda.Data between ? and ?' => array($start_date, $end_date),
								'OR' => array(
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
								)
							 ),
							 'order' => 'Lda.Data DESC'
							)
						 );
						 
						 $bonus    = 0;
						 $compenso = 0;
						 
						 foreach($lda as $tmp) {
							
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Arbitro']):   $bonus += 1; $compenso += $tmp['Campionati']['TariffaArbitro']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Delegato']):  $bonus += 0.5; $compenso += $tmp['Campionati']['TariffaDelegato']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['DelegatoA']): $bonus += 1; $compenso += $tmp['Campionati']['TariffaDelegatoA']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Arbitro2']):  $compenso += $tmp['Campionati']['TariffaArbitro2']; endif;
							
						 }					
						 
						 //debug($compenso);	

						 */						 
						 
						 $altreSpese = $this->AthleteExpense->find('all', array(
						 	
						 	'conditions' => array(
						 		
						 		'AthleteExpense.Atleta' 	          => $this->Session->read('Login.data.id'),
						 		'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
						 		
						 	),
						 	'recursive' => 0,
						 	
						 ));
						 
						 $spese = 0;
						 
						 foreach($altreSpese as $spesa) {
						 	$spesa = $spesa['AthleteExpense'];
						 	$spese += $spesa['Importo'];
						 }
						 
						 
						// $file_media = APP . 'webroot/files/json/buste/'.$k.'_vote_' . date('m_Y') . '__.'.$this->Session->read('Login.data.id').'.json';

						// if(!is_file($file_media))
						// {
						// 	file_put_contents($file_media, $media_ranking);		
						// } else {
						// 	$media = json_decode(file_get_contents($file_media));
						// 	$media_ranking = $media;
						// }
						 

						 $mounth[$k]['NumeroGare']   = count($matches);
						 if ($riepilogo[$k][0]['Match']['SoloSpeseFisse']==1)
						 	$mounth[$k]['NumeroGare'] = 0;

						 $mounth[$k]['MediaRanking'] = $media_ranking;
						 $mounth[$k]['Votazioni']    = $vote_send;
						 $mounth[$k]['VoteSend']     = $count_send;
						 $mounth[$k]['Bonus']    	= $bonus;
						 $mounth[$k]['Compenso']		= '€ ' . ($compenso+$spese);
						 $mounth[$k]['Anno']		    = $anno;

						 $tot_compensi += $compenso;
						 
						}

						
					// if(empty($matches)) {
						
					// 	$spese  = $this->AthleteExpense->find('all', array(
						
					// 		'conditions' => array(
						
					// 			'AthleteExpense.Atleta' => $this->Session->read('Login.data.id'),
					// 			'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo']),
					// 			'AthleteExpense.Data > ' => ($last_year['AnniSportivi']['AnnoSportivo']-1 . '-09-01'),
						
						
					// 		),
					// 		'recursive' => -1,
					// 		'order' => array('AthleteExpense.Data DESC')
						
					// 	));
						
					// 	$riepilogo = array();				
						
					// 	foreach($spese as $spesa) {
						
					// 		$datetime = strtotime($spesa['AthleteExpense']['Data']);
					// 		$mese     = date("m", $datetime);
					// 		$anno     = date("Y", $datetime);
						
					// 		$riepilogo[$mese][] = $spesa;
						
					// 	}
						
					// 	$mounth = array();
					// 	$tot_compensi = 0;
						
					// 	foreach($riepilogo as $k => $tmp) {
						
					// 		/*Calcolo spese*/
					// 		$start_date = $anno . '-' . $k . '-' . '01';
					// 		$end_date   = $anno . '-' . $k . '-' . '31';
						
					// 		$altreSpese = $this->AthleteExpense->find('all', array(
						
					// 			'conditions' => array(
						
					// 				'AthleteExpense.Atleta' 	          => $this->Session->read('Login.data.id'),
					// 				'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
						
					// 			),
					// 			'recursive' => 0,
						
					// 		));
						
					// 		$spese = 0;
						
					// 		foreach($altreSpese as $spesa) {
					// 			$spesa = $spesa['AthleteExpense'];
					// 			$spese += $spesa['Importo'];
					// 		}	
						
					// 		$mounth[$k]['NumeroGare']   = 0;
					// 		$mounth[$k]['MediaRanking'] = 0;
					// 		$mounth[$k]['Votazioni']    = 0;
					// 		$mounth[$k]['VoteSend']     = 0;
					// 		$mounth[$k]['Bonus']    	= 0;
					// 		$mounth[$k]['Compenso']		= '€ ' . $spese;
					// 		$mounth[$k]['Anno']		    = $anno;	
						
					// 		$tot_compensi += $spese;						
						
					// 	}
						
					// }					
						
					//debug($mounth);
						
					//debug($tot_compensi);

					//Setto stagione
						$this->set('stagione', $last_year['AnniSportivi']['AnnoSportivo']);		
						$this->set('tot_compensi', $tot_compensi);
						
						$this->set('athlete_id', $this->Session->read('Login.data.id'));		
						
					//debug($riepilogo);
						$this->set('mounths', $mounth);
						
					}
					
				}








    function ranking_atleti($past_year = '')
    {

        $this->layout = "content";

        $array_result = array();

        $ranking = array();


        //GIUSEPPE 2018-07-16 Anno sportivo per visualizzazione scarpa d'oro -----------
        $last_year_array = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

        $last_year = $last_year_array['AnniSportivi']['AnnoSportivo'];

        $data_max = strtotime($this->requestAction('sections/data_max') . " 00:00:00");

        if (time() < $data_max)
        {
            $last_year--;
        }

        //GIUSEPPE 2019-11-06 -----------------
        if ($past_year != ''):
            $last_year = $past_year;
        endif;

        $this->last_year = $last_year;
        //-------------------------------------
        //------------------------------------------------------------------------------


        $sql = "
                    SELECT 
                            Campionati.Nome, 
                            GoalPartite.SetTennis, 
                            GoalPartite.PuntiRanking, 
                            Squadre.Denominazione, 
                            Squadre.Squadra, 
                            Squadre.atleta_id, 
                            Squadre.atleta2_id, 
                            SquadreCampionati.SquadraCampionato,
                        CampionatiCategorie.fattore_campionato
                    FROM 
                            `Campionati` 
                            INNER JOIN Calendari ON Campionati.Campionato = Calendari.Campionato 
                            INNER JOIN GoalPartite ON GoalPartite.Calendario = Calendari.Calendario 
                            INNER JOIN SquadreCampionati on GoalPartite.SquadraCampionato = SquadreCampionati.SquadraCampionato 
                            INNER JOIN Squadre on Squadre.Squadra = SquadreCampionati.Squadra 
                        INNER JOIN CampionatiCategorie ON Campionati.Categoria = CampionatiCategorie.id
                    WHERE 
                            Campionati.`id_sport` = 1 
                            AND AnnoSportivo = '$last_year' 
                            AND (
                                    GoalPartite.SetTennis <> '' 
                                    OR GoalPartite.PuntiRanking > 0
                            ) 
                    ORDER BY 
                            Squadre.Denominazione ASC
            ";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {

                $array_result[] = $row;
            }
        }

        $ranking = $this->read_atleti_data($array_result);

        //GIUSEPPE 2019-11-06 -----------------
        //$this->tessera_assicurazione();
        //file_put_contents("_array_result.txt", print_r($array_result, true));
        //-------------------------------------



        $this->set('ranking', $ranking);

        return $ranking;

    }






    function read_atleti_data($array_result)
    {

        $id_points = array();

        $points = array("casa_s1" => [1, 3], "casa_s2" => [1, 3], "casa_d1" => [1, 2], "casa_d2" => [1, 2]);

        $vittorie = array("casa_s1" => [0, 1], "casa_s2" => [0, 1], "casa_d1" => [0, 1], "casa_d2" => [0, 1]);

        $sconfitte = array("casa_s1" => [1, 0], "casa_s2" => [1, 0], "casa_d1" => [1, 0], "casa_d2" => [1, 0]);

        $this->puntiAmatoriali = []; // Contiene ID atleta => punteggio ranking

        $id_name = array();

        $final = array();

        foreach ($array_result as $single_result)
        {

            // Tornei classici (Calcolo standard ranking)
            if (!empty($single_result['SetTennis']))
            {


                /* fattore correttivo per le vittorie */
                $fattore_campionato = $single_result['fattore_campionato']; /* è il fattore di vittoria */


                $arry_obj = json_decode($single_result['SetTennis'], true);
                // debug($arry_obj);
                $state_win_1 = $arry_obj['check_win']['s_1_4'];
                $state_win_2 = $arry_obj['check_win']['s_2_4'];
                $state_win_3 = $arry_obj['check_win']['s_3_4'];
                $state_win_4 = $arry_obj['check_win']['s_4_4'];
                $state_win_5 = $arry_obj['check_win']['s_5_4'];
                $state_win_6 = $arry_obj['check_win']['s_6_4'];

                // voglio evitare di conteggiare partite in cui è stato inserito il punteggio ma non il vincitore
                $cond_1 = ($state_win_1 == '0' && $state_win_2 == '1') || ($state_win_1 == '1' && $state_win_2 == '0');
                $cond_2 = ($state_win_3 == '0' && $state_win_4 == '1') || ($state_win_3 == '1' && $state_win_4 == '0');
                $cond_3 = ($state_win_5 == '0' && $state_win_6 == '1') || ($state_win_5 == '1' && $state_win_6 == '0');

                if ($cond_1 && $cond_2 && $cond_3)
                {
                    $index_s1 = $arry_obj['athletes']['casa_s1'];
                    $index_s2 = $arry_obj['athletes']['casa_s2'];
                    $index_d1 = $arry_obj['athletes']['casa_d1'];
                    $index_d2 = $arry_obj['athletes']['casa_d2'];


                    /* //GIUSEPPE 2019-10-30 inserite le variabili 'perse_s' e 'perse_d' per le classifiche singole e doppie  -->(ricorda: vittoria singolo = 3; vittoria doppio = 2, sconfitta = 1) */
                    if (empty($id_points[$index_s1]))
                        $id_points[$index_s1] = ['points' => 0, 'partite' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0, 'perse_s' => 0, 'perse_d' => 0, 'win_s' => 0, 'win_d' => 0]];
                    if (empty($id_points[$index_s2]))
                        $id_points[$index_s2] = ['points' => 0, 'partite' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0, 'perse_s' => 0, 'perse_d' => 0, 'win_s' => 0, 'win_d' => 0]];
                    if (empty($id_points[$index_d1]))
                        $id_points[$index_d1] = ['points' => 0, 'partite' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0, 'perse_s' => 0, 'perse_d' => 0, 'win_s' => 0, 'win_d' => 0]];
                    if (empty($id_points[$index_d2]))
                        $id_points[$index_d2] = ['points' => 0, 'partite' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0, 'perse_s' => 0, 'perse_d' => 0, 'win_s' => 0, 'win_d' => 0]];
                    /* //--------------------------------------------------------- */

                    $result_s1 = $arry_obj['check_win']['s_1_4'];
                    $result_s2 = $arry_obj['check_win']['s_3_4'];
                    $result_dd = $arry_obj['check_win']['s_5_4'];

                    /*
                      $id_points[$index_s1]['points'] += (!empty($points["casa_s1"][$result_s1]) ? $points["casa_s1"][$result_s1] : 0);
                      $id_points[$index_s2]['points'] += (!empty($points["casa_s2"][$result_s2]) ? $points["casa_s2"][$result_s2] : 0);
                      $id_points[$index_d1]['points'] += (!empty($points["casa_d1"][$result_dd]) ? $points["casa_d1"][$result_dd] : 0);
                      $id_points[$index_d2]['points'] += (!empty($points["casa_d2"][$result_dd]) ? $points["casa_d2"][$result_dd] : 0);
                     */

                    /* se c'è vittoria allora moltiplico per il fattore correttivo */

                    $id_points[$index_s1]['points'] += (!empty($points["casa_s1"][$result_s1]) ? $points["casa_s1"][$result_s1] : 0);
                    $id_points[$index_s2]['points'] += (!empty($points["casa_s2"][$result_s2]) ? $points["casa_s2"][$result_s2] : 0);
                    $id_points[$index_d1]['points'] += (!empty($points["casa_d1"][$result_dd]) ? $points["casa_d1"][$result_dd] : 0);
                    $id_points[$index_d2]['points'] += (!empty($points["casa_d2"][$result_dd]) ? $points["casa_d2"][$result_dd] : 0);






                    $id_points[$index_s1]['squadra'] = $single_result["Denominazione"];
                    $id_points[$index_s2]['squadra'] = $single_result["Denominazione"];
                    $id_points[$index_d1]['squadra'] = $single_result["Denominazione"];
                    $id_points[$index_d2]['squadra'] = $single_result["Denominazione"];

                    $id_points[$index_s1]['id_squadra'] = $single_result["Squadra"];
                    $id_points[$index_s2]['id_squadra'] = $single_result["Squadra"];
                    $id_points[$index_d1]['id_squadra'] = $single_result["Squadra"];
                    $id_points[$index_d2]['id_squadra'] = $single_result["Squadra"];

                    $id_points[$index_s1]['id_squadra_campionato'] = $single_result["SquadraCampionato"];
                    $id_points[$index_s2]['id_squadra_campionato'] = $single_result["SquadraCampionato"];
                    $id_points[$index_d1]['id_squadra_campionato'] = $single_result["SquadraCampionato"];
                    $id_points[$index_d2]['id_squadra_campionato'] = $single_result["SquadraCampionato"];



                    //-----------
                    $id_points[$index_s1]['partite']['giocate'] ++;
                    $id_points[$index_s2]['partite']['giocate'] ++;
                    $id_points[$index_d1]['partite']['giocate'] ++;
                    $id_points[$index_d2]['partite']['giocate'] ++;

                    $id_points[$index_s1]['partite']['vinte'] += $vittorie["casa_s1"][$result_s1];
                    $id_points[$index_s2]['partite']['vinte'] += $vittorie["casa_s2"][$result_s2];
                    $id_points[$index_d1]['partite']['vinte'] += $vittorie["casa_d1"][$result_dd];
                    $id_points[$index_d2]['partite']['vinte'] += $vittorie["casa_d2"][$result_dd];

                    $id_points[$index_s1]['partite']['perse'] += $sconfitte["casa_s1"][$result_s1];
                    $id_points[$index_s2]['partite']['perse'] += $sconfitte["casa_s2"][$result_s2];
                    $id_points[$index_d1]['partite']['perse'] += $sconfitte["casa_d1"][$result_dd];
                    $id_points[$index_d2]['partite']['perse'] += $sconfitte["casa_d2"][$result_dd];



                    /* //GIUSEPPE 2019-10-30 -- riempio perse_s e perse_d (perse nel singolo, perse nel doppio) */

                    $id_points[$index_s1]['partite']['perse_s'] += $sconfitte["casa_s1"][$result_s1];
                    $id_points[$index_s2]['partite']['perse_s'] += $sconfitte["casa_s2"][$result_s2];


                    $id_points[$index_d1]['partite']['perse_d'] += $sconfitte["casa_d1"][$result_dd];
                    $id_points[$index_d2]['partite']['perse_d'] += $sconfitte["casa_d2"][$result_dd];

                    /* ---------------------------------------------------------------------------------------- */


                    $id_points[$index_s1]['partite']['win_s'] += ($result_s1);
                    $id_points[$index_s2]['partite']['win_s'] += ($result_s2);
                    $id_points[$index_d1]['partite']['win_d'] += ($result_dd);
                    $id_points[$index_d2]['partite']['win_d'] += ($result_dd);



                    /* //GIUSEPPE 2019-11-20 */
//                    $fattore_campionato = 1.3;
                    /* vittorie con fattore MOLTIPLICATIVO di campionato */
                    /* SOMME LE GIORNATE CON IL FATTORE DI CAMPIONATO ES: 1*1.3 */
                    $id_points[$index_s1]['points_win_s'] += ($result_s1) * $fattore_campionato;
                    $id_points[$index_s2]['points_win_s'] += ($result_s2) * $fattore_campionato;
                    $id_points[$index_d1]['points_win_d'] += ($result_dd) * $fattore_campionato;
                    $id_points[$index_d2]['points_win_d'] += ($result_dd) * $fattore_campionato;
                    // --------------------------------------------
                } #eo if cond
            }
            else
            {
                // NEXT step
                // pushare il punteggio del ranking nell'array atleta (sia per atleta che atleta2) con __pushRankingPoints($atletaId, $punti)
                // poi assicurarsi che il tutto funzioni anche con atleti che sono solo qua e non giocano negli altri tornei non amatoriali

                foreach (['atleta_id', 'atleta2_id'] as $field)
                {
                    if (!empty($single_result[$field]))
                        $this->__pushRankingPoints($single_result[$field], $single_result['PuntiRanking']);
                    if (!isset($id_points[$single_result[$field]]))
                    {
                        $id_points[$single_result[$field]] = [// Mette un array vuoto per tracciare la key Atleta
                            'points' => 0,
                            'squadra' => null,
                            'id_squadra' => null,
                            'id_squadra_campionato' => null,
                            'partite' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0, 'win_s' => 0, 'win_d' => 0],
                        ];
                    }
                }
                // debug($single_result);
            } #eo if tipo ranking
        } #eo foreach
        // debug($this->puntiAmatoriali);
        //file_put_contents("_id_points.txt", print_r($id_points, true));

        $id_name = $this->read_atleti_name($id_points);



        $this->tessera_assicurazione();




        foreach ($id_name as $i => $single_id_name)
        {
            $id = $single_id_name['Atleta'];

            $anagrafica = $single_id_name['Anagrafica'];

            $sesso = $single_id_name['Sesso'];



            $single_id_name['squadra'] = $id_points[$id]['squadra'];

            $single_id_name['id_squadra'] = $id_points[$id]['id_squadra'];

            $single_id_name['id_squadra_campionato'] = $id_points[$id]['id_squadra_campionato'];

            $single_id_name['points'] = $id_points[$id]['points'];


            /* //GIUSEPPE 2019-10-30 -- riempio perse_s e perse_d (perse nel singolo, perse nel doppio) */

            $single_id_name['points_s'] = ((int) $id_points[$id]['partite']['win_s'] * 3) + (int) $id_points[$id]['partite']['perse_s'];
            $single_id_name['points_d'] = ((int) $id_points[$id]['partite']['win_d'] * 2) + (int) $id_points[$id]['partite']['perse_d'];


            /* //GIUSEPPE 2019-11-20 ----- */
            /* PUNTI CON FATTORE MOLTIPLICATIVO IN BASE ALL'IMPORTANZA DELLA COMPETIZIONE */
            if (!isset($id_points[$id]['points_win_s']))
                $id_points[$id]['points_win_s'] = 0;

            if (!isset($id_points[$id]['points_win_d']))
                $id_points[$id]['points_win_d'] = 0;

            $single_id_name['points_f_s'] = sprintf("%f", ($id_points[$id]['points_win_s'] * 3) + $id_points[$id]['partite']['perse_s']);
            $single_id_name['points_f_d'] = sprintf("%f", ($id_points[$id]['points_win_d'] * 2) + $id_points[$id]['partite']['perse_d']);
            $single_id_name['points_f_ranking'] = sprintf("%f", $single_id_name['points_f_s'] + $single_id_name['points_f_d']);
            /* ----------------------------- */


            /* //GIUSEPPE 2019-11-13 -- riempio plus singolo e doppio */

            $single_id_name['plus_s'] = (int) $this->array_tessera_assicurazione[$id]['PuntiSingoloPlus'];
            $single_id_name['plus_d'] = (int) $this->array_tessera_assicurazione[$id]['PuntiDoppioPlus'];
            /* ---------------------------------------------------------------------------------------- */




            $single_id_name['partite'] = $id_points[$id]['partite'];

            if (!empty($this->puntiAmatoriali[$id]))
            {
                $single_id_name += $this->puntiAmatoriali[$id];
                $single_id_name['points'] += $this->puntiAmatoriali[$id]['points_ama'];

                foreach (['giocate', 'vinte', 'perse'] as $key)
                    $single_id_name['partite'][$key] += $this->puntiAmatoriali[$id]['partite_ama'][$key];
            }

            // debug($single_id_name);

            $id_name[$i] = $single_id_name;
        }




        /*  foreach ($only_plus_athletes as $key => $plus_athlete)
          {
          $id_name[] = $plus_athlete;
          // print_r($plus_athlete);
          } */
//        file_put_contents("_id_name.txt", print_r($id_name, true));
        //-------------------------------------------------------------

        $final = $this->order_bubble_sort($id_name);


        //funzione che inserisca i soli punti plus senza partite ------
        $this->only_plus_points();

//        file_put_contents("_only_atletes_plus.txt", print_r($this->only_atletes_plus, true));

        foreach ($this->only_atletes_plus as $only_plus)
        {
            $final['single'][] = $only_plus;
        }

        $final_ordered = $this->order_bubble_sort_singles($final);
        
//        file_put_contents("_final_ordered.txt", print_r($final_ordered, true));

        return $final_ordered;

    }






    private function only_plus_points()
    {

        $last_year = $this->last_year;

        $res = array();
//
//        $sql = "SELECT 
//                           CONCAT(Atleti.Cognome, ' ', Atleti.Nome) as Anagrafica, 
//                           Atleti.Sesso, 
//                           Atleti.Atleta, 
//                           Squadre.Denominazione as squadra, 
//                           Squadre.Squadra as id_squadra, 
//                           SquadreCampionati.SquadraCampionato as id_squadra_campionato, 
//                           PuntiPlus.SingoloPlus as plus_s, 
//                           CampionatiCategorie.fattore_campionato 
//                   FROM 
//                           `PuntiPlus` 
//                           INNER JOIN Annuario ON Annuario.Annuario = PuntiPlus.Annuario 
//                           INNER JOIN SquadreCampionati ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato 
//                           INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra 
//                           INNER JOIN Atleti ON Atleti.Atleta = Annuario.Atleta 
//                           INNER JOIN Campionati ON SquadreCampionati.Campionato = Campionati.Campionato 
//                           INNER JOIN CampionatiCategorie on CampionatiCategorie.id = Campionati.Categoria 
//
//                     WHERE
//                     Annuario.AnnoSportivo = '$last_year'
//                    AND ($only_plus) 
//                       ORDER BY 
//                           CONCAT(Atleti.Cognome, Atleti.Nome) ASC";

        $sql = "SELECT 
                           CONCAT(Atleti.Cognome, ' ', Atleti.Nome) as Anagrafica, 
                           Atleti.Sesso, 
                           Atleti.Atleta, 
                           Squadre.Denominazione as squadra, 
                           Squadre.Squadra as id_squadra, 
                           SquadreCampionati.SquadraCampionato as id_squadra_campionato, 
                           PuntiPlus.SingoloPlus as plus_s, 
                           CampionatiCategorie.fattore_campionato 
                   FROM 
                           `PuntiPlus` 
                           INNER JOIN Annuario ON Annuario.Annuario = PuntiPlus.Annuario 
                           INNER JOIN SquadreCampionati ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato 
                           INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra 
                           INNER JOIN Atleti ON Atleti.Atleta = Annuario.Atleta 
                           INNER JOIN Campionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                           INNER JOIN CampionatiCategorie on CampionatiCategorie.id = Campionati.Categoria 

                     WHERE
                     Annuario.AnnoSportivo = '$last_year'
                    AND PuntiPlus.SingoloPlus > 0
                       ORDER BY 
                           CONCAT(Atleti.Cognome, Atleti.Nome) ASC";

        $result = mysql_query($sql);

//        file_put_contents("_sql_only_plus.txt", $sql);



        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                // $array_result[] = $row;

                $this->only_atletes_plus[$row['Atleta']] = $row;

                $res[$row['Atleta']]['Anagrafica'] = $row['Anagrafica'];
                $res[$row['Atleta']]['Sesso'] = $row['Sesso'];
                $res[$row['Atleta']]['Atleta'] = $row['Atleta'];
                $res[$row['Atleta']]['squadra'] = $row['squadra'];
                $res[$row['Atleta']]['id_squadra'] = $row['id_squadra'];
                $res[$row['Atleta']]['id_squadra_campionato'] = $row['id_squadra_campionato'];
                $res[$row['Atleta']]['points'] = 0;
                $res[$row['Atleta']]['points_s'] = 0;
                $res[$row['Atleta']]['points_d'] = 0;

                $res[$row['Atleta']]['points_f_s'] += (int) $row['plus_s'];
                $res[$row['Atleta']]['points_f_d'] = 0;
                $res[$row['Atleta']]['points_f_ranking'] = 0;
                $res[$row['Atleta']]['plus_s'] = 0;
                $res[$row['Atleta']]['plus_d'] = 0;



                $res[$row['Atleta']]['partite']['giocate'] = 0;
                $res[$row['Atleta']]['partite']['vinte'] = 0;
                $res[$row['Atleta']]['partite']['perse'] = 0;
                $res[$row['Atleta']]['partite']['perse_s'] = 0;
                $res[$row['Atleta']]['partite']['perse_d'] = 0;
                $res[$row['Atleta']]['partite']['win_s'] = 1;
                $res[$row['Atleta']]['partite']['win_d'] = 0;


                $this->only_atletes_plus[$row['Atleta']] = $res[$row['Atleta']];
            }
        }


//        file_put_contents("_only_id_plus.txt", print_r($this->only_atletes_plus, true));
//        file_put_contents("_only_id_singles.txt", print_r($this->only_id_singles, true));

        foreach ($this->only_atletes_plus as $key => $sing)
        {

            if (isset($this->only_id_singles[$key]))
            {
                unset($this->only_atletes_plus[(int) $key]);
            }
        }



        // file_put_contents("_only_id_plus_clear.txt", print_r($this->only_id_plus, true));
        // return $res;

    }




	private function __pushRankingPoints($atletaId, $punti)
				{
					if(!isset($this->puntiAmatoriali[$atletaId])){
						$this->puntiAmatoriali[$atletaId] = [
							'points_ama' => 0, 
							'partite_ama' => ['giocate' => 0, 'vinte' => 0, 'perse' => 0]
						];
					}
					$this->puntiAmatoriali[$atletaId]['points_ama'] += $punti;
					if($punti > 0)
					{
						$keyEsito = $punti == 1 ? 'perse' : 'vinte';
						$this->puntiAmatoriali[$atletaId]['partite_ama']['giocate']++;
						$this->puntiAmatoriali[$atletaId]['partite_ama'][$keyEsito]++;
					}
					return;
	}





//GIUSEPPE 2019-11-06 ---------------------------------

    function tessera_assicurazione()
    {
        $anno_sportivo = $this->last_year;
        $condition = $this->condition;
        $array_result = array();
        $tessera_atleta = array();

        $tessere = array();

        $sql = "SELECT 
                        * 
                FROM 
                        `Annuario` 
                        INNER JOIN TipiAssicurazione ON TipiAssicurazione.TipoAssicurazione = Annuario.TipoAssicurazione 
                WHERE 
                        AnnoSportivo = '$anno_sportivo' 
                        AND ($condition)    
                ";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $array_result[$row['Atleta']]['Assicurazione'] = $row['Simbolo'];
                $array_result[$row['Atleta']]['Tessera'] = $row['Tessera'];
                $array_result[$row['Atleta']]['PuntiSingoloPlus'] = 0;
                $array_result[$row['Atleta']]['PuntiDoppioPlus'] = 0;

                $tessera_atleta[$row['Tessera']] = $row['Atleta'];

                $tessere[] = sprintf("(Tessera = '%s')", $row['Tessera']);
            }
        }

        $filter_tessere = implode(" OR ", $tessere);




        $sql = "
            SELECT 
                    * 
            FROM 
                `PuntiPlus`
               WHERE ($filter_tessere);
            ";


        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {

                $atleta = $tessera_atleta[$row['Tessera']];

                $array_result[$atleta]['PuntiSingoloPlus'] += $row['SingoloPlus'];
                $array_result[$atleta]['PuntiDoppioPlus'] += $row['DoppioPlus'];
            }
        }

      /*  file_put_contents("_tessera_assicurazione.txt", print_r($array_result, true));
          file_put_contents("_tessera_assicurazione_sql.txt", print_r($sql, true));
          file_put_contents("_tessera_atleta.txt", print_r($tessera_atleta, true)); */
        /* file_put_contents("_tessera_atleta_sql.txt", print_r($sql, true)); */



        $images = array();

        $athlete_id = $this->athlete_id;

        $sql = "SELECT 
                        
                        ext, 
                        path,
                        athlete_id 
                FROM 
                        files 
                WHERE ($athlete_id)
                AND athlete_id > 0
                ";


        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $images[$row['athlete_id']] = $row;
            }
        }

        /*
          file_put_contents("_images.txt", print_r($images, true));
          file_put_contents("_images_sql.txt", $sql);
         */
        $this->array_tessera_assicurazione = $array_result;
        $this->set('tessera_assicurazione', $array_result);
        $this->set('images', $images);
    }




//-----------------------------------------------------




 
    function read_atleti_name($id_points)
    {
        $array_of_id = array(); // mi serve per creare la stringa delle condizioni della query

        $array_athlete_id = array(); /* mi serve per gli avatar nella tabella files, il nome della colonna è athlete_id */

        //$array_not_id = array(); //GIUSEPPE 2019-14-12 (mi serve per cercare gli atleti che hanno solo i punteggi plus e non hanno ancora giocato una partita)

        $array_result = array();

        foreach ($id_points as $id => $single_id)
        {

            $array_of_id[] = "Atleta = '$id'";

            $array_athlete_id[] = "athlete_id = '$id'";

//            $array_not_id[] = "(Atleti.Atleta <> '$id')"; //GIUSEPPE 2019-14-12 (mi serve per cercare gli atleti che hanno solo i punteggi plus e non hanno ancora giocato una partita)
        }

        $condition = implode(" OR ", $array_of_id);

        $athlete_id = implode(" OR ", $array_athlete_id);

//        $only_plus = implode(" OR ", $array_not_id); //GIUSEPPE 2019-14-12 (mi serve per cercare gli atleti che hanno solo i punteggi plus e non hanno ancora giocato una partita)
        //GIUSEPPE 2019-11-06 ---------------------------------------------------------

        $this->condition = $condition;

        $this->athlete_id = $athlete_id;

//        $this->only_plus = $only_plus; //GIUSEPPE 2019-14-12 (mi serve per cercare gli atleti che hanno solo i punteggi plus e non hanno ancora giocato una partita)

        /*
          file_put_contents("_last_year.txt", $this->last_year);
          file_put_contents("_condition.txt", $this->condition);
          file_put_contents("_athlete_id.txt", $this->athlete_id);
         */
        //-----------------------------------------------------------------------------


        $sql = "SELECT CONCAT(Cognome,' ' ,Nome) as Anagrafica, Sesso, Atleta FROM Atleti WHERE " . $condition . " ORDER BY CONCAT(Cognome,' ' ,Nome) ASC";

        // file_put_contents("_read_atleti_name.txt", ($sql));

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $array_result[] = $row;
            }
        }

        return $array_result;

    }







    function order_bubble_sort($id_name)
    {

        $single = array();
        $double = array();

        $single = $id_name;
        $double = $id_name;




        while (true)
        {
            $temp;

            $scambio = false;

            for ($i = 0; $i < count($single) - 1; $i++)
            {/* trattandosi di decimali, moltiplico per 100 in modo da eliminare la virgola e fare i calcoli sui valori interi */
                if (( $single[$i]['points_f_s'] + $single[$i]['plus_s']) * 100 < ($single[$i + 1]['points_f_s'] + $single[$i + 1]['plus_s']) * 100)
                {
                    $temp = $single[$i + 1];
                    $single[$i + 1] = $single[$i];
                    $single[$i] = $temp;
                    $scambio = true;
                }

                if ($single[$i]['points_s'] > 0)
                    $this->only_id_singles[$single[$i]['Atleta']] = true;
            }

            if (!$scambio)
                break;
        }

        while (true)
        {
            $temp;

            $scambio = false;

            for ($i = 0; $i < count($double) - 1; $i++)
            {/* trattandosi di decimali, moltiplico per 100 in modo da eliminare la virgola e fare i calcoli sui valori interi */
                if (( $double[$i]['points_f_d'] + $double[$i]['plus_d']) * 100 < ( $double[$i + 1]['points_f_d'] + $double[$i + 1]['plus_d']) * 100)
                {
                    $temp = $double[$i + 1];
                    $double[$i + 1] = $double[$i];
                    $double[$i] = $temp;
                    $scambio = true;
                }
            }

            if (!$scambio)
                break;
        }


        while (true)
        {
            /* punti ranking totale (senza fattore di moltiplicativo e senza punti plus) */
            $temp;

            $scambio = false;

            for ($i = 0; $i < count($id_name) - 1; $i++)
            {
                if ((int) $id_name[$i]['points'] < $id_name[$i + 1]['points'])
                {
                    $temp = $id_name[$i + 1];
                    $id_name[$i + 1] = $id_name[$i];
                    $id_name[$i] = $temp;
                    $scambio = true;
                }
            }

            if (!$scambio)
                break;
        }

//        file_put_contents("_only_id_singles", print_r($this->only_id_singles, true));

        return array('single' => $single, 'double' => $double, 'total' => $id_name);

    }



    function order_bubble_sort_singles($final)
    {


        while (true)
        {
            $temp;

            $scambio = false;

            for ($i = 0; $i < count($final['single']) - 1; $i++)
            {/* trattandosi di decimali, moltiplico per 100 in modo da eliminare la virgola e fare i calcoli sui valori interi */
                if (( $final['single'][$i]['points_f_s'] + $final['single'][$i]['plus_s']) * 100 < ($final['single'][$i + 1]['points_f_s'] + $final['single'][$i + 1]['plus_s']) * 100)
                {
                    $temp = $final['single'][$i + 1];
                    $final['single'][$i + 1] = $final['single'][$i];
                    $final['single'][$i] = $temp;
                    $scambio = true;
                }

            }

            if (!$scambio)
                break;
        }



        //file_put_contents("_only_id_singles", print_r($this->only_id_singles, true));

        return $final;

    }




	/**
	 * Controlla i cerificati medici scaduti dei giocatori scuola calcio
	 * @return void 
	 */
	public function cronCertificatiScaduti()
	{

		$this->autoRender = false;
		$res = ['tot' => 0, 'ok' => 0];

		$in7gg  = date('Y-m-d', strtotime('+7 days'));
		$in30gg = date('Y-m-d', strtotime('+30 days'));

		// debug($in7gg); debug($in30gg); 

		$all = $this->Athlete->find('all', array(
			'conditions' => array(
				'ScuolaCalcio' 	          => 1,
				'OR' => [
					['ScadenzaCertificatoMedico' => $in7gg],
					['ScadenzaCertificatoMedico' => $in30gg],
				]

			),
			'recursive' => -1,
		));


		if(empty($all))
			return;

		foreach($all as $r)
		{
			// debug($r);
			$res['tot']++;

			if(empty($r['Athlete']['Email']))
				continue;

			$giorni = $r['Athlete']['ScadenzaCertificatoMedico'] == $in7gg ? 7 : 30;

			$this->EmailModel->create();

			$this->data['EmailModel']['from']    = 'noreply@info@playleaguesport.it';
			$this->data['EmailModel']['subject'] = "Avviso scadenza certificato medico";
			$this->data['EmailModel']['message'] = "Buongiorno,
vi segnaliamo che il certificato di idoneità sportiva che è depositato presso la sede Play League SSDARL risulta in scadenza tra <b>$giorni giorni</b>, si prega di provvedere a fissare in tempo utile una nuova visita ed entro tale scadenza far pervenire il nuovo certificato.
<b>Si ricorda che dopo la scadenza il bambino non potrà prendere parte all'attività sportiva.</b>
Per qualsiasi info contattare lo 055 4630649 o scrivere a <u>info@playleaguesport.it</u>
Grazie per la collaborazione.
La segreteria
			";
			$this->data['EmailModel']['layout']  = 'comunication';

			$this->EmailModel->set($this->data);

			if ($this->EmailModel->save()) {

				$this->Spool->create();

				$this->data['Spool']['mail_id'] = $this->EmailModel->id;
				$this->data['Spool']['email'] 	= $r['Athlete']['Email'];

				$this->Spool->set($this->data);
				$this->Spool->save();

				$res['ok']++;

			}

		}

		die("Email in coda $res[ok]");
	}




	
	
     //GIUSEPPE 2018-12-15 ALERT CERTIFICATO MEDICO -------------------
    //  -- inserimento nel db lista certificati con scadenza ------------------
    public function medical_certificate_date() //richiamato dal cron
    {
        $day_30 = date('Y-m-d', strtotime("+30 day"));

        $day_7 = date('Y-m-d', strtotime("+7 day"));

        echo $day_30 . " : 1 mese<br>" . $day_7 . " : 1 week<br>";

        $res = array();

        $res_for_admin = array();

        $not_mail = array();

        // 30 giorni e 7 giorni
        $sql = "SELECT * FROM `Atleti` WHERE (ScadenzaCertificatoMedico = '$day_30') OR (ScadenzaCertificatoMedico = '$day_7') AND Email <> '' ORDER BY ScadenzaCertificatoMedico ASC";

        //echo $sql; exit;

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $Mail = explode("#", $row['Email'])[0];

                if ($this->chkEmail($Mail)) // controllo la validità della mail
                {
                    $res[] = sprintf("('%d', '%s' ,'%s' ,'%s', '%s', '%s')", $row['Atleta'], addslashes($row['Cognome'] . " " . $row['Nome']), $row['DataNascita'], $Mail, $row['ScadenzaCertificatoMedico'], "MEDICO");

                    $res_for_admin[$row['ScadenzaCertificatoMedico']][$row['Atleta']]['Nominativo'] = $row['Cognome'] . " " . $row['Nome'];
                    $res_for_admin[$row['ScadenzaCertificatoMedico']][$row['Atleta']]['Nascita'] = $row['DataNascita'];
                    $res_for_admin[$row['ScadenzaCertificatoMedico']][$row['Atleta']]['Email'] = $row['Email'];
                }
                else
                {
                    $not_mail[] = $Mail; // email non valide
                }
            }
        }

        echo mysql_num_rows($result) . " record trovati<br>";

        echo count($res) . " email valide<br>";

        //print_r($res);

        $groups = 100;

        $arr_temp = array();

        foreach ($res as $k => $record)
        {
            $key = $k + 1;

            $arr_temp[] = $record;

            if ($key > 0)
            {
                if (($key % ($groups)) > 0) // i < 100
                {
                    
                }
                else if (($key % ($groups )) == 0) // i = 100
                {

                    $this->insert_to_send($arr_temp);

                    $arr_temp = array();
                }
            }
        }

        if (count($res) > 0)
        {
            $this->insert_to_send($arr_temp);

            $this->mail_for_admin($res_for_admin);
        }

        exit;
    }





    private function mail_for_admin($res_for_admin)
    {


        /* fixed add */
        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi

        print_r($fixed['email_midlandsport']);

        $this->set('res_for_admin', $res_for_admin);

        $emails = array(
            /* $fixed['email_midlandsport'], */
            "info@playleaguesport.it",
            "noreply@playleaguesport.it"
        );

        $this->Email->to = $emails;

        $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';

        $this->Email->subject = $fixed['societa_nome'] . ' -  ' . strip_tags($fixed['oggetto_emil_avviso_scad_cert']);

        $this->Email->template = 'alert_certificato_medico_admin';

        $this->Email->send();

        $path = 'alert_mail.txt';

        if (is_file($path))
        {
            unlink($path);
        }

        file_put_contents($path, print_r($res_for_admin, TRUE));
    }





    private function insert_to_send($arr_temp)
    {
        $to_insert = implode(", ", $arr_temp);

        $sql = "INSERT INTO AlertCertificati (Atleta, Nominativo, DataNascita ,Email, Scadenza, TipoCertificato) VALUES $to_insert";

        $result = mysql_query($sql);

        if ($result)
        {
            //echo "INSERT OK<br>";
        }
        else
        {
            echo " -------------- INSERT KO<br>";
        }
    }





    private function chkEmail($email)
    {
        // elimino spazi, "a capo" e altro alle estremità della stringa
        $email = trim($email);

        // se la stringa è vuota sicuramente non è una mail
        if (!$email)
        {
            return false;
        }

        // controllo che ci sia una sola @ nella stringa
        $num_at = count(explode('@', $email)) - 1;
        if ($num_at != 1)
        {
            return false;
        }

        // controllo la presenza di ulteriori caratteri "pericolosi":
        if (strpos($email, ';') || strpos($email, ',') || strpos($email, ' '))
        {
            return false;
        }

        // la stringa rispetta il formato classico di una mail?
        if (!preg_match('/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email))
        {
            return false;
        }

        return true;
    }





    // -- invio email ---
    public function read_to_send()
    {


        $start = time();

        $res = array();

        $sql = "SELECT 
                        * 
                FROM 
                        `AlertCertificati` 
                WHERE 
                        Send = 0 
                        AND TipoCertificato = 'MEDICO'
                        ";


        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $res['Atleta'][$row['Atleta']]['Email'] = $row['Email'];
                $res['Atleta'][$row['Atleta']]['Nominativo'] = $row['Nominativo'];
                $res['Atleta'][$row['Atleta']]['DataNascita'] = $row['DataNascita'];
                $res['Atleta'][$row['Atleta']]['Documento'][$row['TipoCertificato']][$row['Scadenza']] = $row['Scadenza'];
            }
        }


        echo count($res['Atleta']);

        /* fixed add */
        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi

        /* fixed add -- continua -- */
        $this->set('messaggio', $fixed['alert_medico']);

        $this->set('telefono', $fixed['societa_telefono']);

        $this->set('email', $fixed['email_midlandsport']);
        /* end fixed add */

        $index = 0;

        foreach ($res['Atleta'] as $atleta => $info)
        {


            $data_scadenza = array_keys($info['Documento']['MEDICO'])[0];

            $now = date('Y-m-d');

            $sc = explode("-", $data_scadenza);

            $nw = explode("-", $now);

            $data1 = gregoriantojd($sc[1], $sc[2], $sc[0]);

            $data2 = gregoriantojd($nw[1], $nw[2], $nw[0]);

            $days = $data1 - $data2;

            $this->set('nominativo', $info['Nominativo']);

            $this->set('data_nascita', $info['DataNascita']);

            $this->set('data_scadenza', $data_scadenza);

            $this->set('giorni', $days);

            $emails = array(
                $info['Email']
            );

            $this->Email->to = $emails;
//            $this->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';

            /* fixed edit */
            $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';
            $this->Email->subject = $fixed['societa_nome'] . ' -  ' . strip_tags($fixed['oggetto_emil_avviso_scad_cert']);
            $this->Email->template = 'alert_certificato_medico';
            if ($this->Email->send())
            {
                echo "<br>" . $info['Email'];

                $this->emails_sent($atleta);

                $index++;
            }

            sleep(1);

            if ($index === 100)
            {
                break;
            }
        }

        echo "<br>OK";

        $stop = time();

        echo "<br>tempo = " . ($stop - $start) . " secondi";

        exit;
    }





    private function emails_sent($atleta)
    {

        $sql = "UPDATE `AlertCertificati` SET `Send` = '1' WHERE Atleta = '$atleta';";

        mysql_query($sql);
    }




//GIUSEPPE 2020-09-01 ---------------------------------------




    public function admin_read_atleti_xlsx()
    {

        // print_r($_FILES);

        $res = array();

        $res['upload'] = "KO";

        $exe = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
//        $exe = pathinfo("Atleti.xlsx", PATHINFO_EXTENSION);

        if ($exe !== 'xlsx')
        {
            $res['message'] = "Devi inserire un file xlsx";
        }
        else
        {

            $res['upload'] = "OK";

//            $res['array'] = $this->read_xlsx($_FILES['file']['tmp_name']);
            $res['array'] = $this->read_orizzontal_xlsx($_FILES['file']['tmp_name']);

            $res['tabella'] = $this->insert_atleti_xlsx($res['array']);

        }

        include __DIR__ . '/../views/athletes/admin_add_table.ctp';

        $res['table'] = $html;

        unset($res['array']);

        print_r(json_encode($res));

        exit;
    }




    private function insert_atleti_xlsx($array)
    {
        $res = array();
        foreach ($array as $atleta):
            
            $controlla = $this->controlla_atleti_xlsx($atleta);
        
            if (count($controlla))
            {
                foreach ($controlla as $key => $value)
                {
                    $res['non_inseriti'][$key][] = $value;
                }
            }
            else
            {
                /* inserimento */
                $data_nascita = $this->parse_date($this->parse_date_excel_to_timestamp($atleta['DataNascita']));
                $atleta['DataNascita'] = $this->parse_date_excel_to_timestamp($atleta['DataNascita']);
                $atleta['ScadenzaDocumento'] = $this->parse_date_excel_to_timestamp($atleta['ScadenzaDocumento']);
                $atleta['ScadenzaCertificatoMedico'] = $this->parse_date_excel_to_timestamp($atleta['ScadenzaCertificatoMedico']);
                
                if ($atleta['ArbitroAttivo'] === "No")
                    $atleta['ArbitroAttivo'] = 0;
                if ($atleta['ArbitroAttivo'] === "Si")
                    $atleta['ArbitroAttivo'] = 1;
                
                $this->insert_into("Atleti", $atleta);
                $res['inseriti'][] = sprintf("%s %s - %s", $atleta['Cognome'], $atleta['Nome'], $data_nascita);
            }

        endforeach;

        return $res;
    }




    private function controlla_atleti_xlsx($atleta)
    {

        /* controlla esistenza atleta ************************** */
        $concat = strtolower(sprintf("%s%s%s", addslashes($atleta['Cognome']), addslashes($atleta['Nome']), $this->parse_date_excel_to_timestamp($atleta['DataNascita'])));

        $res = array();

        $query = "SELECT 
                            `Cognome`,`Nome`,`DataNascita` 
                    FROM 
                            `Atleti` 
                    WHERE 
                            LOWER(
                                    CONCAT(Cognome, Nome, DataNascita)
                            ) = '{$concat}'";

        $persona = $this->select_sql($query);

        if (count($persona))
        {

            $res['persona_presente'] = $persona[0];

//            $res['persona_presente']['DataNascita'] = $this->parse_date_excel_to_timestamp($persona[0]['DataNascita']);

            return $res;
        }



        /* controlla esistenza email ************************** */
        $email = strtolower($atleta['Email']);

        $query = "  SELECT 
                            `Cognome`, 
                            `Nome`, 
                            `Email` 
                    FROM 
                            `Atleti` 
                    WHERE 
                            LOWER(Email) = '{$email}' AND Email <> ''";

        $persona = $this->select_sql($query);

        if (count($persona))
        {
            $persona = array();
            $persona['Cognome'] = $atleta['Cognome'];
            $persona['Nome'] = $atleta['Nome'];
            $persona['Email'] = $atleta['Email'];
            $res['email_presente'] = $persona;

            return $res;
        }

        /* controlla esistenza codice fiscale ************************** */
        $codicefiscale = strtolower($atleta['CodiceFiscale']);

        $query = "
            SELECT 
                    `CodiceFiscale` 
            FROM 
                    `Atleti` 
            WHERE 
                    LOWER(`CodiceFiscale`) = '{$codicefiscale}' 
                    AND `CodiceFiscale` <> ''
            ";

        $persona = $this->select_sql($query);

        if (count($persona))
        {
            $persona = array();
            $persona['Cognome'] = $atleta['Cognome'];
            $persona['Nome'] = $atleta['Nome'];
            $persona['CodiceFiscale'] = $atleta['CodiceFiscale'];
            $res['codice_fiscale_presente'] = $persona;

            return $res;
        }

        /* controlla esistenza documento ************************** */
        $documento = strtolower(sprintf("%s%s", $atleta['TipoDocumento'], $atleta['NumeroDocumento']));

        $query = "
            SELECT 
                    `Atleta` 
            FROM 
                    `Atleti` 
            WHERE 
                    LOWER(
                            CONCAT(
                                    `TipoDocumento`, `NumeroDocumento`
                            )
                    ) = '{$documento}' 
                    AND LOWER(
                            CONCAT(
                                    `TipoDocumento`, `NumeroDocumento`
                            )
                    ) <> ''
            ";

        $persona = $this->select_sql($query);

        if (count($persona))
        {
            $persona = array();
            $persona['Cognome'] = $atleta['Cognome'];
            $persona['Nome'] = $atleta['Nome'];
            $persona['TipoDocumento'] = $atleta['TipoDocumento'];
            $persona['NumeroDocumento'] = $atleta['NumeroDocumento'];
            $res['documento_presente'] = $persona;

            return $res;
        }

        return $res;
    }

//-----------------------------------------------------------
	// GIUSEPPE 2024-05-26 --------------------------------------------------------------------------
				function admin_load_atleti_campionati_xlsx()
				{

					// print_r($_FILES);

					$res = array();

					$res = $_POST;

				//        $girone = $_POST['girone'];

					$res['upload'] = "KO";

					$exe = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

					if ($exe !== 'xlsx')
					{
						$res['message'] = "Devi inserire un file xlsx";
					}
					else
					{

						$res['upload'] = "OK";

						$res['array'] = $this->read_orizzontal_xlsx($_FILES['file']['tmp_name']);

						$this->write_file("_arrayAtleti", json_encode($res));

						$this->analizzaCitta($res);
						$this->analizzaAtleti($res);
						$this->analizzaCampionato($res);
					}


					print_r(json_encode($res));

					exit;
				}


				private function analizzaCitta(&$res)
				{
					//$array = $res['array'];

					$elencoCitta = $this->select_sql("SELECT * FROM city");

					foreach ($res['array'] as &$value)
					{
						$citta = $value['LuogoNascita'];
						$value['LuogoNascitaId'] = $this->cercaCitta($elencoCitta, $citta);
					}
				}


				private function cercaCitta(&$elencoCitta, &$citta)
				{
					$index = 0;
					$id = 0;
					do
					{
						$cittaCerca = strtolower($citta);
						if ($cittaCerca == strtolower($elencoCitta[$index]['city_name']))
						{
							$id = $elencoCitta[$index]['id'];
						}

						$index++;
						if (!isset($elencoCitta[$index]))
						{
							$id = -1;
						}
					}
					while ($id == 0);

					return $id;
				}


					private function analizzaAtleti(&$res)
					{
						foreach ($res['array'] as $key => &$infoAtleta)
						{

							$luogoNascitaId = $infoAtleta['LuogoNascitaId'];
							$infoAtleta['AtletaId'] = 0;

							if ($luogoNascitaId == -1)
							{
								continue;
							}

							$dateRif = "1900-01-00"; // per excel

							$data_nascita_giorni = $infoAtleta['DataNascita'] - 1; //dal 1900-01-01

							$data_nascita = date("Y-m-d", strtotime($dateRif . '+ ' . $data_nascita_giorni . ' days'));

							$dateNow = new DateTime(date("Y-m-d"));

							$dateBorn = new DateTime($data_nascita);

							$diff = $dateNow->diff($dateBorn);

							$anni_eta = $diff->y;

							$infoAtleta['errData'] = false;

							$infoAtleta['DataNascita'] = $data_nascita;

							if ($anni_eta >= 99 || $anni_eta < 15)
							{
								$infoAtleta['errData'] = true;
								$infoAtleta['errDataInfo'] = [$anni_eta, $anno_nascita, $data_nascita];
								continue;
							}

							$infoAtleta['AtletaId'] = $this->cercaAtleta($infoAtleta);
						}
					}


					private function cercaAtleta(&$infoAtleta)
					{
						$atleta = 0;
						$sesso = ["M" => "Maschio", "m" => "Maschio", "F" => "Femmina", "f" => "femmina"];

				//        $dataExpl = explode("/", $infoAtleta['DataNascita']);
				//        $data_nascita = date("Y-m-d", mktime(0, 0, 0, $dataExpl[1], $dataExpl[0], $dataExpl[2]));

						$cognome = strtolower($infoAtleta['Cognome']);
						$nome = strtolower($infoAtleta['Nome']);

						$data_nascita = date("Y-m-d", strtotime(str_replace(['/', '\\'], "-", $infoAtleta['DataNascita'])));

						$luogoNascitaId = $infoAtleta['LuogoNascitaId'];

						$infoAtleta['DataNascitaTimestamp'] = $data_nascita; //aggiungo informazione all'array atleti

						$query = "SELECT *, COUNT(Atleta) AS NumRows FROM Atleti WHERE LOWER(Cognome) = '{$cognome}' AND LOWER(Nome) = '{$nome}' AND DataNascita = '{$data_nascita}' AND CityNascita = '{$luogoNascitaId}'";

						$resSelect = $this->select_sql($query)[0];

						if ($resSelect['NumRows'] > 0)
						{
							$atleta = (int) $resSelect['Atleta'];
						}
						else
						{
							$table = "Atleti";

							$arrayValues['Cognome'] = $infoAtleta['Cognome'];
							$arrayValues['Nome'] = $infoAtleta['Nome'];
							$arrayValues['DataNascita'] = $infoAtleta['DataNascitaTimestamp'];
							$arrayValues['LuogoNascita'] = $infoAtleta['LuogoNascita'];
							$arrayValues['CityNascita'] = $infoAtleta['LuogoNascitaId'];
							$arrayValues['CodiceFiscale'] = $infoAtleta['CodiceFiscale'];
							$arrayValues['Email'] = $infoAtleta['Email'];
							$arrayValues['Cellulare'] = $infoAtleta['Cellulare'];
							$arrayValues['Sesso'] = $sesso[$infoAtleta['Sesso']];
							$arrayValues['Sportivo'] = "Si";
							$this->write_file("_values", $arrayValues);

							$respInsert = $this->insert_into($table, $arrayValues, true);

							$atleta = $respInsert['last_id'];
						}

						return $atleta;
					}


    private function analizzaCampionato(&$res)
    {
        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $anno_sportivo = $api->annoSportivo();

        $anno = $anno_sportivo['current']['year'];

        $campionato = [];
        $squadra = [];
		//GIUSEPPE 2025-09-21 ------------------------------------------------------------------------------
		$tipi_assicurazione = $this->tipiAssicurazione();
		//--------------------------------------------------------------------------------------------------

        foreach ($res['array'] as &$infoAtleta)
        {
            if ($infoAtleta['IdManifestazione'] == "")
            {
                continue;
            }

            $campionato[$infoAtleta['IdSquadra']] = $infoAtleta['IdManifestazione'];

            $squadra[$infoAtleta['IdSquadra']] = $infoAtleta['IdSquadra'];
            $infoAtleta['AnnoSportivo'] = $anno;

            $infoAtleta['Annuario'] = 0; // se non viene aggiornato, segnerà errore nella pagina dell' upload
        }



        $res['gare'] = ['Campionato' => $campionato, 'Squadra' => $squadra];
        $squadre = implode(',', $squadra);
        $campionati = implode(',', $campionato);

//        $querySquadreCampionato = "SELECT * FROM `SquadreCampionati` WHERE Campionato = '{$campionato}'  AND Squadra IN({$squadre})";
        $querySquadreCampionato = "SELECT * FROM `SquadreCampionati` WHERE Campionato IN({$campionati})  AND Squadra IN({$squadre})";

        $squadreCampionati = $this->select_sql($querySquadreCampionato);
        $res['squadreCampionati'] = $squadreCampionati;
        $res['squadreCampionatiQuery'] = $querySquadreCampionato;

        $scElenco = [];
        foreach ($squadreCampionati as $sc)
        {
//            $scElenco[$sc['Squadra']] = $sc['SquadraCampionato'];
            $scElenco[$sc['Campionato']][$sc['Squadra']] = $sc['SquadraCampionato'];
        }


        foreach ($res['array'] as &$infoAtleta)
        {
            if ($infoAtleta['IdManifestazione'] == "")
            {
                continue;
            }

//            $infoAtleta['SquadraCampionato'] = $scElenco[$infoAtleta['IdSquadra']];
            $infoAtleta['SquadraCampionato'] = $scElenco[$infoAtleta['IdManifestazione']][$infoAtleta['IdSquadra']];

            $AnnoSportivo = $infoAtleta['AnnoSportivo'];
            $Atleta = $infoAtleta['AtletaId'];
            $SquadraCampionato = $infoAtleta['SquadraCampionato'];

            if (!isset($scElenco[$infoAtleta['IdManifestazione']][$infoAtleta['IdSquadra']]))
            {
                $infoAtleta['Annuario'] = -1;
                continue;
            }

            $queryAnnuario = "SELECT *, COUNT(Annuario) AS NumRows FROM Annuario WHERE Atleta = '{$Atleta}' AND SquadraCampionato = '{$SquadraCampionato}'";
            $resAnnuario = $this->select_sql($queryAnnuario)[0];

            if ($resAnnuario['NumRows'] == 0 && $Atleta !== 0 && $Atleta !== null)
            {
                $toInsert['AnnoSportivo'] = $AnnoSportivo;
                $toInsert['Atleta'] = $Atleta;
                $toInsert['Tessera'] = sprintf("%s%s%s%s", substr($AnnoSportivo, 2), rand(), rand(), rand());
                $toInsert['SquadraCampionato'] = $SquadraCampionato;
                //$toInsert['TipoAssicurazione'] = 1;

				//GIUSEPPE 2025-09-21 ------------------------------------------------------------------------------
				$toInsert['TipoAssicurazione'] = $tipi_assicurazione[$infoAtleta['TipoAssicurazione']]['TipoAssicurazione'];
				//--------------------------------------------------------------------------------------------------
                
				$toInsert['DataVidimazione'] = date("Y-m-d");
                $toInsert['group_id'] = 1;
                $respInsert = $this->insert_into("Annuario", $toInsert, true);
                $infoAtleta['Annuario'] = $respInsert['last_id'];
            }

            if ($resAnnuario['NumRows'] > 0)
            {
                $infoAtleta['Annuario'] = (int) $resAnnuario['Annuario'];
            }
        }
    }

	//GIUSEPPE 2025-09-21 ------------------------------------------------------------------------------
	private function tipiAssicurazione()
	{
		$query = "	SELECT
						TipoAssicurazione,
						Simbolo
					FROM
						`TipiAssicurazione`";

		$res = $this->key_select($this->select_sql($query),"Simbolo");

		return $res;
	}
	//--------------------------------------------------------------------------------------------------

				/* ------------------------ */
}