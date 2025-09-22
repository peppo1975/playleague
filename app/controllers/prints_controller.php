<?

	class PrintsController extends AppController {
	
			var $name = "Prints";
			var $login_required = false;
			var $helpers = array('Backend','fpdf','excel');
			var $uses = array('Matchgoal','Athlete','AthleteExpense','SquadreCampionati','Match','Ranking','Disciplinari','Discipline','Half','Squadre','Campionati','AnniSportivi','Yearbook','Lda','Comunication','Match','Newsletter');
				
			public $components = array('Mpdf.Mpdf');

			public function setCampionati() {
			
				$_campionati = $this->Campionati->find('list', array(
					'fields' => array('Campionati.Campionato','Campionati.Nome'),
					'conditions' => array(
						'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),
						//'Campionati.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
						'Campionati.InUso' => 'Si',					
					),
					'order' => array('Campionati.Nome DESC')
				
				));
				
				$_campionati['default'] = 'Scegliere un campionato...';
				
				$_campionati = array_reverse($_campionati, true);
				
				$this->set('campionati',$_campionati);
			
			}




    /* //GIUSEPPE 2018-05-09 -------------------------------------------------------------------------------------------------- */





   /* //GIUSEPPE 2018-05-09 -------------------------------------------------------------------------------------------------- */


    function admin_searchgiornatenew($in = "", $out = "")
    {

        //$this->autoRender = false;

        $datain = "";
        $dataout = "";

        if ($in != "" && $out != "") /* se la funzione è richiamata dal cron */
        {
            $datain = $in;
            $dataout = $out;
        }
        else if (isset($_POST))
        {
            $datain = $_POST['datain'];
            $dataout = $_POST['dataout'];
        }

        if ($datain == "")
            $datain = "01/01/0001";

        if ($dataout == "")
            $dataout = "01/01/0001";

        $datain = explode('/', $datain);
        $datain = array_reverse($datain);
        $datain = implode("-", $datain);

        $dataout = explode('/', $dataout);
        $dataout = array_reverse($dataout);
        $dataout = implode("-", $dataout);


        /* TROVO PRIMA LE GIORNATE PRESENTI IN QUELL'INTERVALLO DI DATE (LA STESSA GIORNATA PUO ESSERE GIOCATA IN PIU DATE)
         * E POI FILTRO GLI INCONTRI IN BASE ALLE GIORNATE, COSI ASSOCIO LE DATE (ALLE GIORNATE) CHE PER LA STESSA GIORNATA POSSO ESSERE DIVERSE (ANCHE DI MESI)
         * 
         */
        $giornate_gironi_campionati = array();
        $sql = "SELECT * FROM `Calendari` WHERE `Data` BETWEEN '$datain' AND '$dataout'";
        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $temp = array();
                $temp[] = "cal.Campionato = " . $row['Campionato'];
                $temp[] = "cal.GironeCampionato = " . $row['GironeCampionato'];
                $temp[] = "cal.Giornata = " . $row['Giornata'];

                $and = implode(" AND ", $temp);

                $giornate_gironi_campionati[] = "($and)";
            }
        }


        $filter_giornate = implode(" OR ", $giornate_gironi_campionati);


        $sql = "
        SELECT  
        
        cal.Calendario,
        cal.Campionato,  
        
        
        
        cal.Casa,
        casa.Denominazione as SquadraCasa,
        (SELECT SUM(Goal) FROM `GoalPartite` WHERE GoalPartite.Calendario = cal.Calendario AND GoalPartite.SquadraCampionato = sq_casa.SquadraCampionato) as GoalCasa,
        (SELECT SUM(Autogoal) FROM `GoalPartite` WHERE GoalPartite.Calendario = cal.Calendario AND GoalPartite.SquadraCampionato = sq_casa.SquadraCampionato) as AutoGoalCasa,
        
        cal.Trasferta,
        tras.Denominazione as SquadraTrasferta,
        (SELECT SUM(Goal) FROM `GoalPartite` WHERE GoalPartite.Calendario = cal.Calendario AND GoalPartite.SquadraCampionato = sq_tras.SquadraCampionato) as GoalTrasferta,
        (SELECT SUM(Autogoal) FROM `GoalPartite` WHERE GoalPartite.Calendario = cal.Calendario AND GoalPartite.SquadraCampionato = sq_tras.SquadraCampionato) as AutoGoalTrasferta,

        (SELECT Descrizione FROM CausaliRisultato WHERE CausaleRisultato = cal.CausaleRisultato) as CausaleRisultato,

        Campionati.Nome,
        Campionati.Italiana,
        
        cal.GironeCampionato,
        
        GironiCampionati.Descrizione,
        
        cal.Giornata,
        CONCAT(cal.data, ' : ',cal.Ora) as data,
        (SELECT COUNT(Disciplinare) FROM Disciplinari WHERE Calendario = cal.Calendario) as Disciplinare,
        (SELECT Note FROM Bollettini WHERE GironeCampionato =  cal.GironeCampionato AND Giornata = cal.Giornata) as Comunicazioni
        
        FROM `Calendari`  cal
        
        INNER JOIN Campionati  
        ON cal.Campionato = Campionati.Campionato 
        
        INNER JOIN GironiCampionati
        ON cal.GironeCampionato = GironiCampionati.GironeCampionato
        
        INNER JOIN SquadreCampionati sq_casa
        ON cal.Casa = sq_casa.SquadraCampionato
        
        INNER JOIN Squadre casa
        ON sq_casa.Squadra = casa.Squadra
        
        
        INNER JOIN SquadreCampionati sq_tras
        ON cal.Trasferta = sq_tras.SquadraCampionato
        
        INNER JOIN Squadre tras
        ON sq_tras.Squadra = tras.Squadra

        
        WHERE  
        
        ($filter_giornate)
        AND Campionati.scuola = 0  
        AND Campionati.InUso = 'Si'  
        AND casa.SquadraServizio = 0
        AND tras.SquadraServizio = 0
        

        ORDER BY  Campionati.Nome ASC, GironiCampionati.Descrizione ASC, cal.Giornata ASC, cal.Calendario ASC";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {

                $array_disciplinari = array();

                if ($row['Disciplinare'] != "0")
                {
                    $sql_disciplinare = "SELECT Disciplinare,SquadraCampionato,Descrizione,Sanzione FROM Disciplinari WHERE Calendario = " . $row['Calendario'];

                    $sub_result = mysql_query($sql_disciplinare);

                    if (mysql_num_rows($sub_result) > 0)
                    {
                        // output data of each row
                        while ($sub_row = mysql_fetch_assoc($sub_result))
                        {
                            $array_disciplinari[] = $sub_row;
                        }
                    }
                }


                $goal_casa = (int) $row['GoalCasa'] + (int) $row['AutoGoalTrasferta'];

                $goal_trasferta = (int) $row['GoalTrasferta'] + (int) $row['AutoGoalCasa'];

                $partita = array();

                $partita['Squadre'] = $row['SquadraCasa'] . " vs " . $row['SquadraTrasferta'];
                $partita['Punti'] = $goal_casa . " - " . $goal_trasferta;
                $partita['CausaleRisultato'] = $row['CausaleRisultato'];
                $partita['Calendario'] = $row['Calendario'];
                $partita['SquadreCampionato'][$row['Casa']] = $row['SquadraCasa'];
                $partita['SquadreCampionato'][$row['Trasferta']] = $row['SquadraTrasferta'];
                $partita['Disciplinari'] = $array_disciplinari;
                /* $partita['GoalPartite'] = $goal_partite; */

                $squadre_campionati[] = $row['Casa'];
                $squadre_campionati[] = $row['Trasferta'];


                $champs[$row['Campionato']]['NomeCampionato'] = $row['Nome'];
                $champs[$row['Campionato']]['Italiana'] = $row['Italiana'];

                $champs[$row['Campionato']][$row['GironeCampionato']]['Giornata'][$row['Giornata']] = $row['Giornata'];

                $champs[$row['Campionato']][$row['GironeCampionato']]['NomeGirone'] = $row['Descrizione'];

                $champs[$row['Campionato']][$row['GironeCampionato']][$row['Giornata']]['Date'][$row['data']][] = $partita; // in una stessa data e ora ci possono essere piu partite (in campi diversi)

                $champs[$row['Campionato']][$row['GironeCampionato']][$row['Giornata']]['Comunicazioni'] = $row['Comunicazioni'];


                ksort($champs[$row['Campionato']][$row['GironeCampionato']][$row['Giornata']]['Date']);
            }
        }



        /* $file = 'people0.txt';

          if (file_exists($file))
          unlink($file);

          file_put_contents($file, print_r($champs, true)); */



        /* se la funzione è richiamata dal cron, salva su file  $json */
        if ($in != "" && $out != "")
        {
            $json = serialize($champs);

//            $dir = APP . "webroot/files/pdf/champ_bulletin.json";
            $dir = APP . "webroot/files/pdf/champ_bulletin.txt";

            if (file_exists($dir))
            {
                unlink($dir);
            }

            file_put_contents($dir, $json);

//            return $json;
            return $json;
        }


        $json = json_encode($champs, 4000);


        if ($json)
            echo $json;
        else
            echo json_last_error_msg();
        exit;

    }





    /* //GIUSEPPE 2018-05-09 - - - - - - - - - - - - - - - - - - - - - */

    function admin_bullettins_new($dir = "")
    {        
        $classifica_json = json_decode(file_get_contents("_content/classifica.json"),true);
        
        $array_points = array();
        $array_total = array();

        if (isset($_POST))
        {
            $array_points = $_POST['array_points'];
            $array_total = $_POST;
        }
        if ($dir != "") /* se la funzione è richiamata dal cron, apre il file webroot/files/pdf/champ_bulletin.json */
        {
//            $array_points = $this->tranform_array(json_decode(file_get_contents($dir), true));
            $array_points = $this->tranform_array(unserialize(file_get_contents($dir)));
            $array_total['array_points'] = $array_points;
        }


        $c_h = array();

        $all_keys_teams = array();

        //devo estrarre i campionati e i gironi
        foreach ($array_points as $key_champ => $champ_half)
        {

            foreach ($champ_half as $key_half => $half)
            {
                if ($key_half != "NomeCampionato")
                {
                    $c_h[$key_champ][$key_half] = $key_champ . "-" . $key_half;

                    foreach ($half as $key_day => $day)
                    {
                        if ($key_day == "NomeGirone")
                        {
                            continue;
                        }

                        $array_total['array_points'][$key_champ][$key_half][$key_day]['Comunicazioni'] = $this->note_day($key_half, $key_day);

                        foreach ($day['Date'] as $date)
                        {
                            foreach ($date as $detail)
                            {


                                $key_teams = array_keys($detail['SquadreCampionato']);

                                $all_keys_teams[$key_teams[0]] = $detail['SquadreCampionato'][$key_teams[0]];

                                $all_keys_teams[$key_teams[1]] = $detail['SquadreCampionato'][$key_teams[1]];
                            }
                        }

                        // 2 giornate successive

                        $next_days = $this->requestAction("sections/next_days/$key_champ/$key_half/$key_day");

                        $array_total['array_points']['Global']['NextDays'][$key_champ][$key_half][$key_day] = $next_days;
                    }
                }
            }
        }



        $array_total['array_points']['Global']['AmmonizioniEspulsioni'] = $this->ammonizioni_espuslioni($all_keys_teams);

        foreach ($c_h as $key_champ => $half_result)
        {
            foreach ($half_result as $key_half => $s)
            {
                if ($key_half == "NomeCampionato")
                    continue;

                $all_teams = $this->requestAction("sections/filterRankings/$key_champ/$key_half");

                $marcatori = $this->requestAction("sections/filterMarks/$key_champ/$key_half");

                /* // GIUSEPPE 2018-06-20 ------ */
                $teams_champ_half = $this->requestAction("sections/teamGames/$key_champ/$key_half");
                /* // ---------------------------- */

                $array_total['array_points']['Global']['Classifica'][$key_champ][$key_half] = $this->insert_teams_start($all_teams);

                $array_total['array_points']['Global']['Marcatori'][$key_champ][$key_half] = $marcatori;

                /* // GIUSEPPE 2018-06-20 ------ */
                $array_total['array_points']['Global']['AllTeamsChampHalf'][$key_champ][$key_half] = $teams_champ_half;
                /* // ---------------------------- */
            }
        }

        $name_file = 'bollettini_' . date("d") . "_" . date("m") . "_" . date("Y") . '_' . uniqid() . '.pdf';

        $export = "pdf";

        $this->set('export', $export);
        $this->set('campionati', $array_total['array_points']);
        $this->set('name_file', $name_file);

        //GIUSEPPE 2023-03-23 -----------------------------------------------
        $this->set("classifica_json", $classifica_json);
        //-------------------------------------------------------------------
        
        //GIUSEPPE 2022-10-15 -----------------------------------------------
        $this->set('squalificatiATempo', $this->squalificatiATempo());
        //-------------------------------------------------------------------

        $file = 'people1.txt';

        if (file_exists($file))
            unlink($file);

        file_put_contents($file, print_r($array_total, true));


        if ($dir != "") /* se la funzione è richiamata dal cron genera il pdf senza aprirlo */
        {

            $this->autoRender = false;

            $this->render("admin_bullettins_new");

            $file = APP . "webroot/files/pdf/name_file_bulletin.json";

            if (file_exists($file))
                unlink($file);

            file_put_contents($file, FULL_ABSOLUTE_URL . '/files/pdf/' . $name_file);
        }
        else
        {
            $this->layout = 'pdf';

            print '/files/pdf/' . $name_file;
        }
    }


    // GIUSEPPE 2022-10-15 ---------------------------------------------
    private function squalificatiATempo()
    {
        ob_start();
        include __DIR__ . "/../webroot/_content/squalificatiATempo.json";
        $html = ob_get_clean();
        $squalificati_a_tempo = json_decode($html, true);

        return $squalificati_a_tempo;
    }


    // -----------------------------------------------------------------





    /* //GIUSEPPE 2018-06-20 */


    private function note_day($half, $day)
    {
        $nota = "null";

        $sql = "SELECT * FROM `Bollettini` WHERE GironeCampionato = $half AND Giornata = $day";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $nota = $row['Note'];
            }
        }

        return $nota;

    }



    /* //GIUSEPPE 2018-06-20 */


    private function insert_teams_start($all_teams)
    {

        $last = array();

        foreach ($all_teams['squadre'] as $denominazione)
        {
            $last[$denominazione]['id'] = "0";
            $last[$denominazione]['nulle'] = "0";
            $last[$denominazione]['coppa_disciplina'] = "0";
            $last[$denominazione]['goal_fatti'] = "0";
            $last[$denominazione]['goal_subiti'] = "0";
            $last[$denominazione]['is_goal'] = "0";
            $last[$denominazione]['partita'] = "0";
            $last[$denominazione]['punti_penalizzazione'] = "0";
            $last[$denominazione]['punti'] = "0";
            $last[$denominazione]['giocate'] = "0";
            $last[$denominazione]['goal_totali_fatti'] = "0";
            $last[$denominazione]['goal_totali_subiti'] = "0";
            $last[$denominazione]['totali_vinte'] = "0";
            $last[$denominazione]['totali_perse'] = "0";
            $last[$denominazione]['giocate'] = "0";
            $last[$denominazione]['goal_totali_fatti'] = "0";
            $last[$denominazione]['goal_totali_subiti'] = "0";
            $last[$denominazione]['totali_vinte'] = "0";
            $last[$denominazione]['totali_perse'] = "0";
        }


        foreach ($all_teams['classifica'] as $key_day => $day)
        {
            $teams = $all_teams['squadre'];

            foreach ($day['squadre'] as $key_team => $single_team)
            {
                $last[$key_team] = $single_team;
                $index = array_search($key_team, $teams);
                unset($teams[$index]);
            }

            foreach ($teams as $remaing_team)
            {
                $all_teams['classifica'][$key_day]['squadre'][$remaing_team] = $last[$remaing_team];
            }
        }


        /* $file = 'people2.txt';

          if (file_exists($file))
          unlink($file);

          file_put_contents($file, print_r($all_teams, true)); */

        return $all_teams;

    }




    /* //GIUSEPPE 2018-06-16 -- trasformo in file generato dalla query di  admin_searchgiornatenew nel formato per la stampa pdf (ho fatto le stesse trasformazioni che eseguo dalla pagina di generazione manuale del pdf) */


    private function tranform_array($array_points)
    {
        $res = array();

        foreach ($array_points as $key_champ => $round)
        {

            foreach ($round as $key_round => $day)
            {
                if ($key_round == "NomeCampionato")
                    continue;

                if ($key_round == "Italiana")
                    continue;


                foreach ($day['Giornata'] as $day_index)
                {
                    $comunicazioni = $round[$key_round][$day_index]['Comunicazioni'];

                    $res[$key_champ][$key_round][$day_index]['Date'] = $round[$key_round][$day_index]['Date'];

                    if ($comunicazioni == "")
                    {
                        $comunicazioni = 'null';
                    }

                    $res[$key_champ][$key_round][$day_index]['Comunicazioni'] = $comunicazioni;
                }

                $res[$key_champ][$key_round]['NomeGirone'] = $array_points[$key_champ][$key_round]['NomeGirone'];
            }

            $res[$key_champ]['NomeCampionato'] = $array_points[$key_champ]['NomeCampionato'];
            $res[$key_champ]['Italiana'] = $array_points[$key_champ]['Italiana'];
        }
        return $res;

    }





    /* //GIUSEPPE 2018-05-09 - - - - - - - - - - - - - - - - - - - - - */





    function ammonizioni_espuslioni($all_keys_teams)
    {
        $k = array_keys($all_keys_teams);
        $for_mysql = array();

        foreach ($k as $keys)
        {
            $for_mysql[] = "Gp.SquadraCampionato = '$keys'";
        }


        $filter = implode(" OR ", $for_mysql);


        $goal_partite = array();

        $sql_goal_partite = "
          SELECT
          Gp.GoalPartita
          , Gp.Calendario
          , Gp.SquadraCampionato
          , Gp.Atleta
          , Gp.Goal
          , Gp.AutoGoal
          , Gp.Ammonizione
          , Gp.Espulsione
          , Gp.EspulsioneGiornate
          , CONCAT(DAY(Gp.EspulsioneFine),'/',MONTH(Gp.EspulsioneFine),'/',YEAR(Gp.EspulsioneFine)) as EspulsioneFine
          , CONCAT(DAY(Gp.created),'/',MONTH(Gp.created),'/',YEAR(Gp.created)) as EspulsioneInizio
          , Gp.Motivo
          , Atl.Nome
          , Atl.Cognome

          FROM GoalPartite Gp

          INNER JOIN Atleti Atl
          ON Gp.Atleta = Atl.Atleta
          WHERE ($filter) AND (Ammonizione = 'Si' OR Espulsione = 'Si') ORDER BY Gp.Calendario ASC";


        $file = "test_sql.txt";

        if (file_exists($file))
            unlink($file);


        file_put_contents($file, $sql_goal_partite);


        $goal_partite_result = mysql_query($sql_goal_partite);

        if (mysql_num_rows($goal_partite_result) > 0)
        {
            while ($gp_row = mysql_fetch_assoc($goal_partite_result))
            {

                $goal_partite[] = $gp_row;
            }
        }

        $file = "goal_partite.txt";

        if (file_exists($file))
            unlink($file);


        file_put_contents($file, print_r($goal_partite, true));

        $amm_esp = array();
        $amm = array();
        foreach ($goal_partite as $single_day)
        {

            $amm_esp['Atleti'][$single_day['Atleta']]['Name'] = $single_day['Cognome'] . " " . $single_day['Nome'][0] . ".";

            $amm_esp['Atleti'][$single_day['Atleta']]['SquadraCampionato'] = $single_day['SquadraCampionato'];


            if ($single_day['Ammonizione'] == "Si")
            {
                if (!isset($amm[$single_day['Atleta']]['Ammonizioni']))
                {
                    $amm[$single_day['Atleta']]['Ammonizioni'] = 0;
                }
                $amm[$single_day['Atleta']]['Ammonizioni'] ++;


                $amm_esp['Atleti'][$single_day['Atleta']]['Calendario'][$single_day['Calendario']]['Ammonizioni'] = $amm[$single_day['Atleta']]['Ammonizioni'];

                $amm_esp['CalendarioAtleti']['Ammoniti'][$single_day['Calendario']][$single_day['Atleta']] = $single_day['Atleta'];

                $atleta_ammonizioni = array();

                $atleta_ammonizioni['ID'] = $single_day['Atleta'];

                $atleta_ammonizioni['Calendario'] = $single_day['Calendario'];

                $amm_esp['CalendarioAtleti']['AmmonitiTotali']['SquadraCampionato'][$single_day['SquadraCampionato']]['Atleta'][$single_day['Atleta']]['Calendario'][] = $single_day['Calendario'];

                $amm_esp['Atleti'][$single_day['Atleta']]['TotaleAmmonizioni'] = $amm[$single_day['Atleta']]['Ammonizioni'];
            }

            if ($single_day['Espulsione'] == "Si")
            {
                $amm_esp['Atleti'][$single_day['Atleta']]['Calendario'][$single_day['Calendario']]['Espulsione'] = "Si";

                $amm_esp['Atleti'][$single_day['Atleta']]['Calendario'][$single_day['Calendario']]['Inizio'] = $single_day['EspulsioneInizio'];

                $amm_esp['Atleti'][$single_day['Atleta']]['Calendario'][$single_day['Calendario']]['Fine'] = $single_day['EspulsioneFine'];

                $amm_esp['Atleti'][$single_day['Atleta']]['Calendario'][$single_day['Calendario']]['Giornate'] = $single_day['EspulsioneGiornate'];

                $amm_esp['CalendarioAtleti']['Espulsi'][$single_day['Calendario']][$single_day['Atleta']] = $single_day['Atleta'];
            }
        }


/* //        $file = "ammonizioni.txt";
        $file = "ammonizioni.json";

        if (file_exists($file))
            unlink($file);


//        file_put_contents($file, print_r($amm_esp, true));
        file_put_contents($file, json_encode($amm_esp)); */

        return $amm_esp;
    }





    /* //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */




			

			function admin_searchgiornate() {


					$this->autoRender = false;


					$datain = $_POST['datain'];
					$dataout = $_POST['dataout'];

					$datain = explode('/',$datain);
					$datain = array_reverse($datain);
					$datain = implode("-", $datain);

					$dataout = explode('/',$dataout);
					$dataout = array_reverse($dataout);
					$dataout = implode("-", $dataout);

	
					$campionati = $this->Campionati->find('all',array(


						'conditions' => array(


							'(SELECT COUNT(*) FROM Calendari WHERE Calendari.Campionato = Campionati.Campionato AND data >= \'' . $datain . '\' AND data <= \'' . $dataout . '\') > 0'

						)

					));

					$camps = array();

					foreach ($campionati as $campionato) {

						$camps[]=$campionato['Campionati']['Campionato'];

					}

					print json_encode(($camps));
					exit;

			}








            

     /* // GIUSEPPE 2018-05-15 - - - - - - - - - - - - - - - - - -  */





    function bollettino($group = 0)
    {

        $this->autoRender = false;

        $data_attuale = date("Y-m-d");

        $monday = strtotime("last Monday", strtotime($data_attuale));
        $saturday = strtotime("+5 days", $monday);


        $datain = date("Y-m-d", $monday);
        $dataout = date("Y-m-d", $saturday);


        /* //GIUSEPPE 2018-10-11 */
        /* ATLETI CALCIO GIOCATO SETTIMANA */
        $users = $this->read_atleti_settimana($datain, $dataout);
        $group_insert = $this->insert_new_user($users['email']);
        $all_id = $this->read_id_user($group_insert);
        $id_to_group = $this->edit_group($all_id);
        /* ------------------------------- */



        $datain_pdf = date("d/m/Y", $monday);
        $dataout_pdf = date("d/m/Y", $saturday);

        /* //GIUSEPPE  2018-07-16 ***************************************** */
        /* $this->requestAction('squadres/ranking_teams_calculation'); /* genera il ranking squadre */
        /*         * *************************************************************** */

//        print "[-] Generazione bollettino, in onore a Luca Orioli per i servizi alla patria\r\n";
        print "[-] Generazione bollettino<br>";
        print "[*] Lunedi: $datain<br>";
        print "[*] Sabato: $dataout<br>";

        $file = ROOT . "/bollettino.log";
        $to_write = "[-] Generazione bollettino \r\n[*] Lunedi: $datain \r\n[*] Sabato: $dataout";
        $this->write_log($to_write, $file, "w+"); /**/

        $dir = APP . "webroot/files/pdf/champ_bulletin.txt";

        $this->admin_searchgiornatenew($datain_pdf, $dataout_pdf);

        $this->admin_bullettins_new($dir);

        $merge = file_get_contents(APP . "webroot/files/pdf/name_file_bulletin.json");

        print "[*] FILE PDF: $merge <br>";

        $to_write = "\r\n[*] FILE PDF: $merge";
        $this->write_log($to_write, $file, "a+"); /* */

        if (true)
        {

            // devo mettere $merge che sarebbe il nome del file
            print "[*] Trovo la newsletter:<br>";

            $to_write = "\r\n[*] Trovo la newsletter:";
            $this->write_log($to_write, $file, "a+"); /*  */

            $newsletter = $this->Newsletter->find('first', array(
                'conditions' => array(
                    'Newsletter.title LIKE \'%' . "COMUNICATO UFFICIALE" . '%\''
                )
            ));

            $title = $newsletter['Newsletter']['title'];

            $numbers = explode(" ", $title);

            $numero = 0;
            foreach ($numbers as $number)
            {

                if (is_numeric($number))
                {
                    $numero = $number;
                    break;
                }
            }

            $numero++;

            print "[*] Numero bollettino: " . $numero . "<br>";
            print "[*] Data generazione: " . date("d-m-Y H:i:s");

            $to_write = "\r\n[*] Numero bollettino: " . $numero . "\r\n[*] Data generazione: " . date("d-m-Y H:i:s");
            ;
            $this->write_log($to_write, $file, "a+");   /* */

            $this->Newsletter->set('id', $newsletter['Newsletter']['id']);
            $this->Newsletter->set($newsletter['Newsletter']);
            $this->Newsletter->set('title', "COMUNICATO UFFICIALE N. $numero DEL " . date("d/m/Y", strtotime($dataout)));
            $this->Newsletter->set('content', '<p>
                                    Il bollettino N. ' . $numero . ' DEL ' . date("d/m/Y", strtotime($dataout)) . ' &egrave; disponibile al <a href="' . $merge . '">seguente indirizzo</a></p>
                                '
            );

            $this->Newsletter->save();

            $group_id = 16; /* è il gruppo a cui è associata la newsletters dei bollettini */

            if ($group != 0)
            {
                $group_id = $group;
            }


            $queueData = array(
                'groups' => [$group_id],
                'newsletters' => [$newsletter['Newsletter']['id']],
            );

            App::import('Controller', 'Newsletters');
            $Newsletters = new NewslettersController;
            $Newsletters->constructClasses();
            $Newsletters->admin_send_message($queueData);
            exit;
        }
    }




    //GIUSEPPE 2018-06-26  complila il log del cron 

    private function write_log($to_write, $file, $description)
    {
        $fp = fopen($file, $description);
        fwrite($fp, $to_write);
        fclose($fp);
    }




//GIUSEPPE 2018-10-11 --- Atleti calcio giocato settimana

    private function read_atleti_settimana($monday, $saturday)
    {
        // $conn = $this->conn;

        $res = array();

        $sql = "SELECT DISTINCT Atleti.Email FROM `Calendari`

                            INNER JOIN Campionati
                            ON Calendari.Campionato = Campionati.Campionato

                            INNER JOIN GoalPartite
                            ON Calendari.Calendario = GoalPartite.Calendario

                            INNER JOIN Atleti
                            ON Atleti.Atleta = GoalPartite.Atleta

                            WHERE 

                            Campionati.id_sport = 0
                            AND 
                            Campionati.scuola = 0
                            AND
                            Calendari.data >= '$monday'
                            AND
                            Calendari.Data <= '$saturday'
                            AND
                            Atleti.Email <> ''";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $res[] = $row['Email'];
            }
        }

        return array("num_rows" => $result->num_rows, "email" => $res);
    }





    private function insert_new_user($users)
    {



        $groups = 10;

        $index = 0;

        $group_insert = array();

        $groups_users = array();

        foreach ($users as $key => $user)
        {

            if (($key % $groups) == 0)
            {
                $index++;
                $groups_users[$index] = array();
            }

            $group_insert[$index][] = "('$user')";

            $groups_users[$index][] = $user;
        }

        foreach ($group_insert as $group) // inserisce eventuali nuove mail
        {
            $sql = "INSERT IGNORE INTO newsletters_users (email) VALUES " . implode(", ", $group);

            $result = mysql_query($sql);
        }

        return $groups_users;
    }





    private function read_id_user($group_insert)
    {

        $all_id = array();

        $num_row = array();

        foreach ($group_insert as $key_group => $group)
        {

            $to_glue = array();

            foreach ($group as $user)
            {
                $to_glue[] = "email = '$user'";
            }

            $filter = implode(" OR ", $to_glue);

            $sql = "SELECT id, email FROM newsletters_users WHERE ($filter)";

            $result = mysql_query($sql);

            $num_row[$key_group]['rows'] = $result->num_rows;

            $num_row[$key_group]['query'] = $sql;

            if (mysql_num_rows($result) > 0)
            {
                // output data of each row
                while ($row = mysql_fetch_assoc($result))
                {

                    $all_id[] = $row["id"];
                }
            }
        }

        return $all_id;
    }





    private function edit_group($all_id)
    {

        //raggruppo 100 alla volta

        $groups = 100;

        $id_to_group = array();
        $id_to_group_mysql = array();

        $index = 0;

        foreach ($all_id as $key_id => $id)
        {

            if (($key_id % $groups) == 0)
            {
                $index++;
                $id_to_group[$index] = array();
            }

            $id_to_group[$index][] = $id;
            $id_to_group_mysql[$index][] = "('0','16','$id')";
        }

        // elimino i 'newsletter_group_id = 16' in 'newsletters_groups_users' e ne inserisco altri con questi ultimi 

        $sql = "DELETE FROM `newsletters_groups_users` WHERE `newsletter_group_id` = 16";

        $result = mysql_query($sql);


        // inserisco in nuovi id

        foreach ($id_to_group_mysql as $single_group)
        {
            $filter = implode(", ", $single_group);

            $sql = "INSERT INTO newsletters_groups_users ( `disabled`, `newsletter_group_id`, `newsletter_user_id`) VALUES " . $filter;

            $result = mysql_query($sql);
        }

        return $id_to_group;
    }




    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


    function old_bollettino()
    {

        $this->autoRender = false;


        $data_attuale = date("Y-m-d");

        $monday = strtotime("last Monday", strtotime($data_attuale));
        $saturday = strtotime("+5 days", $monday);

        $datain = date("Y-m-d", $monday);
        $dataout = date("Y-m-d", $saturday);


        print "[-] Generazione bollettino, in onore a Luca Orioli per i servizi alla patria\r\n";
        print "[*] Lunedi: $datain\r\n";
        print "[*] Sabato: $dataout\r\n";


        $campionati = $this->Campionati->find('all', array(
            'conditions' => array(
                '(SELECT COUNT(*) FROM Calendari WHERE Calendari.Campionato = Campionati.Campionato AND data >= \'' . $datain . '\' AND data <= \'' . $dataout . '\') > 0',
                "Campionati.scuola != 1",
                "Campionati.sport = 'CALCIO'",
            )
        ));

        $camps = array();

        foreach ($campionati as $campionato)
        {

            $camps[] = $campionato['Campionati']['Campionato'];
        }


        $generazione = [];
        foreach ($camps as $champ)
        {

            print "[!] Ottengo le giornate di $champ:\r\n";

            $gen = [];
            $gen['champ'] = $champ;

            $sel = $this->Match->find('first', array(
                'conditions' => array(
                    'Match.Campionato' => $champ,
                    'Match.Data >= ' => $datain,
                    'Match.Data <= ' => $dataout,
                ),
                'order' => 'Match.Giornata ASC'
                    )
            );

            if (isset($sel['Match']['Giornata']))
            {

                print "[!] Giornata giocata questa settimana per $champ: giornata " . $sel['Match']['Giornata'] . "\r\n";

                $gen['giornata'] = $sel['Match']['Giornata'];


                print "[*] Ottengo i gironi di $champ:\r\n";



                $halfs = $this->Half->find('all', array(
                    'conditions' => array(
                        'Half.Campionato' => $champ
                    ),
                    'order' => 'Half.Campionato ASC'
                        )
                );

                if (count($halfs))
                {


                    $gen['gironi'] = [];

                    foreach ($halfs as $half)
                    {

                        $gen['gironi'][] = $half['Half']['GironeCampionato'];
                    }
                }

                $generazione[] = $gen;
            }
        }

        $pdf_to_merge = [];

        foreach ($generazione as $gen)
        {


            foreach ($gen['gironi'] as $girone)
            {

                $postdata = http_build_query(
                        array(
                            'campionato' => $gen['champ'],
                            'girone' => $girone,
                            'stampa' => 1,
                            'exp' => 'pdf',
                            'giornata' => $gen['giornata']
                        )
                );

                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );

                $context = stream_context_create($opts);

                $result = file_get_contents(FULL_ABSOLUTE_URL . '/admin/prints/bullettins2/', false, $context);

                print "[!] Generato: $result\r\n";

                $pdf_to_merge[] = $result;
            }
        }


        if (count($pdf_to_merge))
        {


            print "[*] Genero il merge del pdf finale:\r\n";


            $postdata = http_build_query(
                    array(
                        'data' => json_encode($pdf_to_merge)
                    )
            );

            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );

            $context = stream_context_create($opts);

            $merge = file_get_contents(FULL_ABSOLUTE_URL . '/admin/prints/merge/', false, $context);

            $merge = FULL_ABSOLUTE_URL . $merge;

            print "[EPIC WIN] PDF: $merge\r\n";



            print "[*] Trovo la newsletter:\r\n";

            $newsletter = $this->Newsletter->find('first', array(
                'conditions' => array(
                    'Newsletter.title LIKE \'%' . "COMUNICATO UFFICIALE" . '%\''
                )
            ));



            $title = $newsletter['Newsletter']['title'];

            $numbers = explode(" ", $title);

            $numero = 0;
            foreach ($numbers as $number)
            {

                if (is_numeric($number))
                {
                    $numero = $number;
                    break;
                }
            }

            $numero++;

            print "\r\n[*] Numero bollettino: " . $numero;

            $this->Newsletter->set('id', $newsletter['Newsletter']['id']);
            $this->Newsletter->set($newsletter['Newsletter']);
            $this->Newsletter->set('title', "COMUNICATO UFFICIALE N. $numero DEL " . date("d/m/Y", strtotime($dataout)));
            $this->Newsletter->set('content', '<p>
                                    Il bollettino N. ' . $numero . ' DEL ' . date("d/m/Y", strtotime($dataout)) . ' &egrave; disponibile al <a href="' . $merge . '">seguente indirizzo</a></p>
                                '
            );

            $this->Newsletter->save();

            require ROOT . "/vendor/autoload.php";
            libxml_use_internal_errors(TRUE);

            $bakfile = file_get_contents(ROOT . "/app/views/elements/email/html/newsletter/51_grafica_bollettino_2016.ctp");

            $qp = qp($bakfile);

            $link = $qp->find('h1 a')->attr('href');

            $bakfile = str_replace($link, $merge, $bakfile);

            // Salvo in entrambi i file dato che non è chiaro quando viene usato uno e quando l'altro
            file_put_contents(ROOT . "/app/views/elements/email/html/newsletter/51_grafica_bollettino_2016.ctp", $bakfile);
            file_put_contents(ROOT . "/app/views/elements/email/html/51_grafica_bollettino_2016.ctp", $bakfile);


            $queueData = array(
                'groups' => [16],
                'newsletters' => [$newsletter['Newsletter']['id']],
            );

            App::import('Controller', 'Newsletters');
            $Newsletters = new NewslettersController;
            $Newsletters->constructClasses();
            $Newsletters->admin_send_message($queueData);
            ECHO "<BR>QUI<BR>";
        }

    }











			function admin_index() {

				$this->layout = "timmybox";
				$this->set('anni', $this->AnniSportivi->find('list', array('order' => array('AnniSportivi.AnnoSportivo' => 'DESC'))));
				$this->setCampionati();
			
			}

			function admin_pdf() {
			
				$this->layout = "pdf";
			
			}
			
			function admin_setOptions($options_pdf) {
			
				$this->layout = "ajax";
				$this->Session->write('options_pdf', $options_pdf);
			
			}
			
			function admin_findHalf() {
			
				$id = $this->params['pass'][0];
				
				$half = $this->Half->findBygironecampionato($id);
				
				$half_name = $half['Half']['Descrizione'];
				
				if (!empty($this->params['requested'])) {
				
					return $half_name;
				
				}
			
			}
			
			function admin_findTeam($SquadraCampionato) {
			
				$SquadraCampionato = $this->params['pass'][0];
				
				$team = $this->Squadre->query("SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = '$SquadraCampionato')");

				$team_name = $team[0]['Squadre']['Denominazione'];
				
				if (!empty($this->params['requested'])) {
				
					return $team_name;
				
				}
			
			}
			
			function admin_recepit() {
				
				$this->layout = "pdf";
				
				$matches = $_POST['matches'];
				
				$partite = array();
				
				$check = 0;
				
				foreach ($matches as $match) {
					
					$partita = $this->Match->findByCalendario($match);
					
					if($partita['Casa']['Pagato'] == 'No' || $partita['Trasferta']['Pagato'] == 'No') $check = 1;
					
					$partita['CasaInfo'] = $this->Squadre->findBySquadra($partita['Casa']['Squadra']);
					$partita['TrasfertaInfo'] = $this->Squadre->findBySquadra($partita['Trasferta']['Squadra']);
										
					$partite[] = $partita;
					
				}
				
				$this->set('matches',$partite);
				$this->set('check', $check);
				
			}
			


			function admin_bullettins2() {

					session_write_close();
					$this->autoRender = false;

//					print_r($_POST);

					$data = array();
					$data[] = $_POST;

					//$data = json_decode($data,TRUE);


					$urls = array();


					foreach ($data as $cmp) {


						$champ["id"] = $cmp['campionato'];
						$champ["giornate"] = $cmp['giornata'];
						$champ["gironi"] = $cmp['girone'];
						$champ['stampa'] = $cmp['stampa'];
						$champ['export'] = $cmp['exp'];

					



						//$dat = json_encode($champ);

						$postdata = http_build_query(
						$champ
						);

						$opts = array('http' =>
						    array(
						        'method'  => 'POST',
						        'timeout' => '9999',
						        'header'  => 'Content-type: application/x-www-form-urlencoded',
						        'content' => $postdata
						    )
						);

						$context  = stream_context_create($opts);

						$url = FULL_ABSOLUTE_URL."/admin/prints/bullettins3/";


						$ret = file_get_contents($url,false,$context);
						$ret = str_replace("\r\n", "", $ret);
						$ret = str_replace("\n", "", $ret);
						$ret = str_replace(" ","",$ret);
						$ret = str_replace("\t", "", $ret);

						$lol = uniqid();
						//rename(APP . '/webroot/' . $ret, APP . '/webroot/' . $lol.$ret);
						
						print $ret;
						exit;

						$urls[]= APP . '/webroot/' . $ret;

					}


					$pdf = "bollettini_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
					$str = implode(" ",$urls);
					$cmd = "pdftk $str cat output " . APP . '/webroot/files/pdf/' . $pdf;
					system($cmd);
					print "/files/pdf/" . $pdf;
					exit;

			}


			function admin_merge() {

				$this->autoRender = false;


				$files = json_decode($_POST['data'],TRUE);


				$urls = array();

				foreach ($files as $file) {

					$urls[] = APP . '/webroot/' . $file;

				}

					$pdf = "bollettini_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
					$str = implode(" ",$urls);
					$cmd = "pdftk $str cat output " . APP . '/webroot/files/pdf/' . $pdf;

					system($cmd);
					print "/files/pdf/" . $pdf;
					exit;

			}

			function admin_bullettins3() {


				Configure::Write('debug',2);
				$this->autoRender = false;
				$this->login_required = false;
				/*
				$data = $_SERVER['REQUEST_URI'];
				$data = str_replace("/admin/prints/bullettins3/", "", $data);
				$data = str_replace("/admin/prints/bullettins3/", "", $data);
				$data = urldecode($data);
				*/


				$champ = $_POST;


				if (is_array($champ['gironi']))
				$this->data['Print']['Gironi']=$champ['gironi'];
			else
				$this->data['Print']['Gironi'][]=$champ['gironi'];
				$this->data['Print']['Giornate'][]=$champ['giornate'];
				$this->data['Print']['Stampa'] = $champ['stampa'];
						//	$this->data['Print']['Stampa'] = 1;
				$this->data['Print']['Export'] = $champ['export'];


				$campionato_id = $champ['id'];

				$this->admin_bullettins($champ['id']);

				$this->render("admin_bullettins");


			}



			function admin_bullettins($campionato_id) {

				
				$this->login_required = false;

				Configure::Write('debug',2);
			
				$stampa 	 = $this->data['Print']['Stampa'];

				$oldata = $this->data;

				$gironi_arr	 = $this->data['Print']['Gironi'];
				$giornate	 = $this->data['Print']['Giornate'];
				$campionato	 = $campionato_id;
				$export		 = $this->data['Print']['Export'];
				
				$export = "pdf";

				$this->set('export', $export);
												
				$infochamp = $this->Campionati->find('first',array('conditions' => array('Campionato' => $campionato)));
												
				$this->set('campionato',$infochamp);
					
				if($export == 'pdf') $this->layout = 'pdf';
					else if($export == 'xls') $this->layout = 'xls';

				$this->layout = "pdf";
				

				$gironi = array();
		
				#foreach($giornate as $giornata) $giornate[] = $giornata;
				foreach($gironi_arr as $girone) { 
				
					$girone = $this->Half->find('first',array('conditions' => array('GironeCampionato' => $girone)));
				
					$gironi[] = $girone;
				
				}
				$n_giornate = count($giornate);
				$n_gironi = count($gironi);
				
				$this->set('gironi', $gironi);
				$this->set('giornate', $giornate);
				
				$arr_gare = array();
				$arr_gare_prossima_giornata = array();
				$arr_gare_prossima_giornata2 = array();
				$arr_classifica_marcatori = array();
				$arr_diffidati = array();
				$arr_espulsi = array();
				$arr_classifiche = array();
				$arr_disciplinari = array();
				$arr_comunicazioni = array();
				$arr_riposo = array();
				$arr_riposo_prox = array();
				$arr_riposo_prox2 = array();
				$i = 0;
				
				while($i < $n_giornate) {
					
					$giornata = $giornate[$i];					
					
					$j = 0;
										
					while($j < $n_gironi) {
						
						$girone_id = $gironi[$j]['Half']['GironeCampionato'];
						
						$classifica_marcatori = $this->Matchgoal->query(
					
								"SELECT 

								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
								SUM(GoalPartite.Goal) as goals FROM GoalPartite 
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id') 
								AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND GoalPartite.Atleta != 0
								GROUP BY GoalPartite.Atleta ORDER BY goals DESC LIMIT 5"
							
						);
							
						$riposo = $this->SquadreCampionati->query("
						
							SELECT (SELECT Squadre.Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra) as NomeSquadra
									
									FROM SquadreCampionati WHERE
									
									SquadreCampionati.Campionato = '$campionato_id' AND
									SquadreCampionati.GironeCampionato = '$girone_id' AND
									SquadreCampionati.SquadraCampionato NOT IN
									
									(
									
									 SELECT Casa as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata
									 
									  UNION
									  
									 SELECT Trasferta as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata
									
									)
						
						");
						
						$giornata_prox = $giornata + 1;
						$riposo_prox = $this->SquadreCampionati->query("
						
							SELECT (SELECT Squadre.Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra) as NomeSquadra
									
									FROM SquadreCampionati WHERE
									
									SquadreCampionati.Campionato = '$campionato_id' AND
									SquadreCampionati.GironeCampionato = '$girone_id' AND
									SquadreCampionati.SquadraCampionato NOT IN
									
									(
									
									 SELECT Casa as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata_prox
									 
									  UNION
									  
									 SELECT Trasferta as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata_prox
									
									)
						
						");
						
						$giornata_prox_2 = $giornata + 2;
						$riposo_prox2   = $this->SquadreCampionati->query("
						
							SELECT (SELECT Squadre.Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra) as NomeSquadra
									
									FROM SquadreCampionati WHERE
									
									SquadreCampionati.Campionato = '$campionato_id' AND
									SquadreCampionati.GironeCampionato = '$girone_id' AND
									SquadreCampionati.SquadraCampionato NOT IN
									
									(
									
									 SELECT Casa as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata_prox_2
									 
									  UNION
									  
									 SELECT Trasferta as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id' AND Calendari.Giornata = $giornata_prox_2
									
									)
						
						");												
							
							$gare = $this->Match->find('all', array(
							
								'conditions' => array(
								
										'Match.Campionato' => $campionato_id, 
										'Match.GironeCampionato' => $girone_id,
										'Match.Giornata' => $giornata,
										//'Match.Risultato !=' => ''  
										
									)
								)
								
							);
							
							$comunicazioni = $this->Comunication->find('all',array(
							
								'conditions' => array(
								
										'Comunication.GironeCampionato' => $girone_id,
										'Comunication.Giornata' => $giornata
								
								)
							
							));
							
							$gare_prossima_giornata = $this->Match->find('all', array(
							
								'conditions' => array(
								
										'Match.Campionato' => $campionato_id, 
										'Match.GironeCampionato' => $girone_id,
										'Match.Giornata' => $giornata + 1,
										//'Match.Risultato !=' => ''  
										
									)
								)
								
							);
							
							$gare_prossima_giornata2 = $this->Match->find('all', array(
							
								'conditions' => array(
								
										'Match.Campionato' => $campionato_id, 
										'Match.GironeCampionato' => $girone_id,
										'Match.Giornata' => $giornata + 2,
										//'Match.Risultato !=' => ''  
										
									)
								)
								
							);
							// chiedere a luca conteggio ammonizioni //
							$giornata_1 = $giornata+2;
							$giornata1  = $giornata-1;
						
							/*
							$diffidati = $this->Matchgoal->query(
							
								"SELECT 
								(SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '{$giornata_1}' AND Calendari.Campionato = '$campionato_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
								COUNT(*) as Ammonizioni FROM GoalPartite
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id') 
								AND GoalPartite.Ammonizione = 'Si'
								AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '{$giornata_1}')
								GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
							
							);
							*/
							
/** modded by welo */	
							
							$diffidati = $this->Matchgoal->query(
				
								"SELECT 
								(SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE (Calendari.Giornata = '{$giornata}') AND Calendari.Campionato = '$campionato_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
								(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
								GoalPartite.Atleta,
								0 as AzzeraDiffidati,	
								COUNT(*) as Ammonizioni FROM GoalPartite
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id') 
								AND GoalPartite.Ammonizione = 'Si'
								AND GoalPartite.Calendario IN (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata < '{$giornata_1}')
								GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
			
							);						
			
							
							$espulsi = $this->Matchgoal->query(
							
								"SELECT 

								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
								(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data, 
								GoalPartite.EspulsioneGiornate,
								GoalPartite.EspulsioneInizio,
								GoalPartite.EspulsioneFine,
								GoalPartite.Espulsione FROM GoalPartite
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id') 
								AND GoalPartite.Espulsione = 'Si'
								AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata')
								GROUP BY GoalPartite.Atleta ORDER By NomeSquadra"
							
							);
							
							// Generazione classifica //
							
							$campionato = $campionato_id;
							$girone 	= $girone_id;
							
							$squadre = $this->SquadreCampionati->find('all', array(
							
								'conditions' => 
									array(
											'Campionati.Campionato' =>  $campionato,
											'Half.GironeCampionato' => $girone
										  )
								)
							);

							
							$partite = array();
							
							$classifiche = array();
							
							foreach ($squadre as $squadra) {
								
								$classifica = array();
								
								$id_classifica = $this->Ranking->find('first', array(
								
									'conditions' => 
										array(
										'Ranking.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
										'Ranking.GironeCampionato' => $girone
										)
									)
									
								);
								
								if (!empty($id_classifica)) {
									$classifica['Classifica'] = $id_classifica['Ranking']['Classifica'];
								} else {
									$classifica['Classifica'] = null;
								}
								
								$classifica['SquadraCampionato'] = $squadra['SquadreCampionati']['SquadraCampionato'];
								$classifica['InfoSquadra'] = $this->SquadreCampionati->find('first',array('conditions' => array('SquadraCampionato' => $classifica['SquadraCampionato'])));
								$classifica['GironeCampionato'] = $girone;
								$classifica['Giocate'] = 0;
								$classifica['Punti'] = 0;
								$classifica['Vinte'] = 0;
								$classifica['Perse'] = 0;
								$classifica['Nulle'] = 0;
								$classifica['GiocateCasa'] = 0;
								$classifica['VinteCasa'] = 0;
								$classifica['PerseCasa'] = 0;
								$classifica['NulleCasa'] = 0;
								$classifica['GiocateFuori'] = 0;
								$classifica['VinteFuori'] = 0;
								$classifica['PerseFuori'] = 0;
								$classifica['NulleFuori'] = 0;
								$classifica['GoalFatti'] = 0;
								$classifica['GoalSubiti'] = 0;
								$classifica['GoalSubitiFuori'] = 0;
								$classifica['GoalSubitiCasa'] = 0;
								$classifica['GoalFattiFuori'] = 0;
								$classifica['GoalFattiCasa'] = 0;
								$classifica['CoppaDisciplina'] = 0;
								
								$partite = $this->Match->find('all',array(
								
									'conditions' => 
										array(
									
										'OR' => 
												array('Match.Casa' => $squadra['SquadreCampionati']['SquadraCampionato'],
													  'Match.Trasferta' => $squadra['SquadreCampionati']['SquadraCampionato']
												),
										
										'Match.Campionato' => $campionato,
										'Match.GironeCampionato' => $girone,
										'Match.Giornata <=' => $giornata,
										)
								
								));
							
								foreach ($partite as $partita) {
									
									$casa_fuori = 'Fuori';
									$fuori_casa = 'Casa';
									$risultato['Casa'] = 0;
									$risultato['Fuori'] = 0;
									
									if ($partita['Match']['Casa'] == $squadra['SquadreCampionati']['SquadraCampionato']) $casa_fuori = 'Casa';
							
									$disciplinari = $this->Disciplinari->find('all',array(
										
										'conditions' => array(
										
											'SquadreCampionati.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
											'Disciplinari.Calendario' => $partita['Match']['Calendario']
										
										)
									
									));
									
									//pr ($partita['Causalresult']);
									
									foreach ($disciplinari as $disciplinare) {
										
										$classifica['CoppaDisciplina'] += $disciplinare['Disciplinari']['Punti'];
										
									}
							
									
							
									if ($partita['Causalresult']['Descrizione'] != 'Recupero' && substr($partita['Causalresult']['Descrizione'],0,strlen('N.D.')) != 'N.D.' && $partita['Causalresult']['Descrizione'] != 'In attesa decisioni G.S.' && $partita['Causalresult']['Descrizione'] != 'Gara non omologabile.' && $partita['Causalresult']['Descrizione'] != 'Risultato non omologabile' && $partita['Causalresult']['Descrizione'] != 'RINV.') {
										
										$classifica['Giocate']++;
										$classifica['Giocate' . $casa_fuori]++;
									
										$goals = $this->Matchgoal->find('all',array(
										
											'conditions' => 
											
													array(
														
														'Matchgoal.Calendario' => $partita['Match']['Calendario'],
														
													)
										));
							
										foreach ($goals as $goal) {
											
											if ($casa_fuori == 'Casa')  $fuori_casa = 'Fuori';
											else 						$fuori_casa = 'Casa';
											
											if ($squadra['SquadreCampionati']['SquadraCampionato'] == $goal['Matchgoal']['SquadraCampionato']) {
												
													$classifica['GoalFatti'] += $goal['Matchgoal']['Goal'];
													$classifica['GoalSubiti'] += $goal['Matchgoal']['Autogoal'];
													$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
													$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
													
													$risultato[$casa_fuori] += $goal['Matchgoal']['Goal'];
													$risultato[$fuori_casa] += $goal['Matchgoal']['Autogoal'];
													
													if ($goal['Matchgoal']['Ammonizione'] == 'Si') $classifica['CoppaDisciplina']++;
													if ($goal['Matchgoal']['Espulsione']  == 'Si')  $classifica['CoppaDisciplina']+=3;
													
													
											} else {
												
													$classifica['GoalFatti'] += $goal['Matchgoal']['Autogoal'];
													$classifica['GoalSubiti'] += $goal['Matchgoal']['Goal'];
													$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
													$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
											
													$risultato[$fuori_casa] += $goal['Matchgoal']['Goal'];
													$risultato[$casa_fuori] += $goal['Matchgoal']['Autogoal'];
											}
							
											
										}
										
										if ($risultato[$casa_fuori] == $risultato[$fuori_casa]) {
												
												$classifica['Nulle']++;
												$classifica['Nulle' . $casa_fuori]++;
												$classifica['Punti']++;
												
										}
										
										if ($risultato[$casa_fuori] > $risultato[$fuori_casa]) {
											
												$classifica['Punti'] += 3;
												$classifica['Vinte' . $casa_fuori]++;
												$classifica['Vinte']++;
											
										}
										
										if ($risultato[$casa_fuori] < $risultato[$fuori_casa]) {
											
												$classifica['Perse' . $casa_fuori]++;
												$classifica['Perse']++;
											
												if (substr($partita['Causalresult']['Descrizione'],0,strlen('TAV')) == 'TAV') {
													
													$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
													
												} 
											
										}										
										
							
									} else {
										
										$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
										
										/*if ($partita['Causalresult']['CausaleRisultato'] != 'N.D.') {
											
											
											
										}*/
										
									}
									

								
								}
								
								// Tolgo penalizzazione
								
								$classifica['Punti'] = $classifica['Punti'] - (isset($id_classifica['Ranking']['PuntiPenalizzazione'])? $id_classifica['Ranking']['PuntiPenalizzazione']:0);								
								
								$classifiche[] = $classifica;
																						
							}
							
							$disciplinari = $this->Disciplinari->find('all',
							
								array('conditions' => array(
								
									'Disciplinari.Calendario IN (
										SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = ' . $giornata . ' AND Calendari.GironeCampionato = ' . $girone . '
									 )'
								
								))
							
							);

							$arr_classifiche[$giornata][$girone_id] = $classifiche;
							$arr_gare[$giornata][$girone_id] = $gare;
							$arr_gare_prossima_giornata[$giornata][$girone_id] = $gare_prossima_giornata;
							$arr_gare_prossima_giornata2[$giornata][$girone_id] = $gare_prossima_giornata2;
							$arr_classifica_marcatori[$giornata][$girone_id] = $classifica_marcatori;
							$arr_diffidati[$giornata][$girone_id] = $diffidati;
							$arr_espulsi[$giornata][$girone_id] = $espulsi;
							$arr_disciplinari[$giornata][$girone_id] = $disciplinari;
							$arr_riposo[$giornata][$girone_id] = $riposo;
							$arr_riposo_prox[$giornata][$girone_id] = $riposo_prox;
							$arr_riposo_prox2[$giornata][$girone_id] = $riposo_prox2;
							$arr_comunicazioni[$giornata][$girone_id] = $comunicazioni;
											
						$j++;
					
					}

					$i++;
					
				}
							
					$this->set('gare', $arr_gare);
					$this->set('gare_prossima_giornata', $arr_gare_prossima_giornata);
					$this->set('gare_prossima_giornata2', $arr_gare_prossima_giornata2);
					$this->set('classifica_marcatori', $arr_classifica_marcatori);
					$this->set('diffidati', $arr_diffidati);
					$this->set('espulsi', $arr_espulsi);
					$this->set('classifiche', $arr_classifiche);
					$this->set('disciplinari',$arr_disciplinari);
					$this->set('riposo',$arr_riposo);
					$this->set('riposo_prox',$arr_riposo_prox);
					$this->set('riposo_prox2',$arr_riposo_prox2);
					$this->set('comunicazioni',$arr_comunicazioni);
					
																				
			}
			
			function admin_getDay($Campionato,$da = "",$a = "") {
			
				$this->layout = "ajax";
				
				$max_day = $this->Match->find('first', array(
				
						'conditions' => array(
						
							'Match.Campionato' => $Campionato
							
						), 
						'order' => 'Match.Giornata DESC'
					)
				);
				

				if (!empty($da)) {


					$da = explode("-",$da);
					$da = array_reverse($da);
					$da = implode("-", $da);

				}


				if (!empty($a)) {


					$a = explode("-",$a);
					$a = array_reverse($a);
					$a = implode("-", $a);

				}


				$selecteds = array();


				if (!empty($da) && !empty($a)) {


						$sel = $this->Match->find('all', array(
						
								'conditions' => array(
								
									'Match.Campionato' => $Campionato,
									'Match.Data >= ' => $da,
									'Match.Data <= ' => $a,
									
								), 
								'order' => 'Match.Giornata ASC'
							)
						);

						
						foreach ($sel as $s) {

							$selecteds[]=$s['Match']['Giornata'];

						}


				}

				$this->set('result', json_encode(array('selecteds' => $selecteds,'find' => $max_day['Match']['Giornata'])));
				$this->render('/backend/ajaxResult');
			
			}
			
			function admin_getHalf($Campionato) {
			
				$this->layout = "ajax";
				
				$halfs = $this->Half->find('all', array(
				
						'conditions' => array(
						
							'Half.Campionato' => $Campionato
						
						),
						'order' => 'Half.Campionato ASC'
				
					)
					
				);
				
				$this->set('result', json_encode($halfs));
				$this->render('/backend/ajaxResult');
			
			} 
			
			function admin_calendars_index() {
			
				$this->layout = "timmybox";
				
				$this->setCampionati();
			
			}
			
			function admin_calendars($id_campionato) {
			
				if($this->data['calendarPrint']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['calendarPrint']['Export'] == 'xls') $this->layout = "xls";
				 
				$campionato = $this->Campionati->findByCampionato($id_campionato);
				 
				$nome_campionato = $campionato['Campionati']['Nome'];
				$gironi = $this->data['calendarPrint']['Gironi'];
				//$stampa = $this->data['calendarPrint']['Stampa'];
				$export = $this->data['calendarPrint']['Export'];
												 				
				$calendari = array();
				
				foreach($gironi as $girone) {
				
					$data = $this->Match->find('all', array(
										
							'conditions' => array(
							
								'Match.Campionato' => $id_campionato,
								'Match.GironeCampionato' => $girone,
							
							),
							'order' => array('Match.Giornata ASC', 'Match.Data ASC', 'Match.Partita ASC')
					
						)
					
					);
					
					$giornate = $this->Match->find('list', array(
						'fields' => 'Match.Giornata', 
						'group' => 'Match.Giornata',
						'order' => 'Match.Giornata ASC',
					));
					
					foreach($giornate as $giornata) {
										
						$riposo = $this->SquadreCampionati->query("
						
							SELECT (SELECT Squadre.Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra) as NomeSquadra
									
									FROM SquadreCampionati WHERE
									
									SquadreCampionati.Campionato = '$id_campionato' AND
									SquadreCampionati.GironeCampionato = '$girone' AND
									SquadreCampionati.SquadraCampionato NOT IN
									
									(
									
									 SELECT Casa as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$id_campionato' AND Calendari.GironeCampionato = '$girone' AND Calendari.Giornata = $giornata
									 
									  UNION
									  
									 SELECT Trasferta as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$id_campionato' AND Calendari.GironeCampionato = '$girone' AND Calendari.Giornata = $giornata
									
									)
						
						");
						
						$arr_riposo[$girone][$giornata] = $riposo;
					
					}
															
					$calendari[$girone] = $data;
					
				}
																				
				$this->set('calendari', $calendari);
				$this->set('riposo', $arr_riposo);
				$this->set('export', $export);
				$this->set('nome_campionato', $nome_campionato);
				$this->set('campionato', $campionato);
				
			}
			

	
			function admin_notes() {
			



					$this->autoRender = false;

//					print_r($_POST);

					$data = $_POST['data'];

					$data = json_decode($data,TRUE);


					$urls = array();


					$squadre = array();

					foreach ($data as $cmp) {


						$champ["id"] = $cmp[0];
						$champ["giornate"] = $cmp[1];
						$champ["gironi"] = $cmp[2];
						$champ['stampa'] = $cmp[3];
						$champ['export'] = $cmp[4];


						$partite = $this->Match->find('all',array(

							'conditions' => array(

								'Match.Giornata IN (' . implode(",",($champ['giornate'])) . ')',
								'Match.GironeCampionato IN (' . implode(",",($champ['gironi'])) . ')'

							),
							'order' => 'Match.NomeDelegato ASC, Match.NomeDelegatoA, Match.Data ASC, Match.Campo ASC, Match.Ora ASC'

						));


						foreach ($partite as $partita) {


							if (empty($partita['Match']['NomeDelegatoA'])) $partita['Match']['NomeDelegatoA'] = 'zzzzzz';
							if (empty($partita['Match']['NomeDelegato'])) $partita['Match']['NomeDelegato'] = '!!!!!!';
							
							$squadre[]=array(

								'id' => $partita['Match']['Calendario'],
								'team' => $partita['Match']['Casa'],
								'delegatoa' => $partita['Match']['NomeDelegatoA'],
								'delegato' => $partita['Match']['NomeDelegato'],
								'data' => $partita['Match']['Data'],
								'campo' => $partita['Match']['Campo'],
								'ora' => $partita['Match']['Ora']
							);
							$squadre[]=array(

								'id' => $partita['Match']['Calendario'],
								'team' => $partita['Match']['Trasferta'],
								'delegatoa' => $partita['Match']['NomeDelegatoA'],
								'delegato' => $partita['Match']['NomeDelegato'],
								'data' => $partita['Match']['Data'],
								'campo' => $partita['Match']['Campo'],
								'ora' => $partita['Match']['Ora']
							);


						}



					}

					$squadre = Set::sort($squadre, '{n}.delegatoa', 'asc','{n}.delegato','asc','{n}.data','asc','{n}.campo','asc','{n}.ora','asc');


					foreach ($squadre as $partita) {


						$filename = "tmp_note_" . $partita['id'] . "_" . uniqid() . ".pdf";
						$cmd="wget ".FULL_ABSOLUTE_URL."/sections/getNotes/".$partita["id"]."/".$partita["team"]." -O /var/www/vhosts/playleaguesport.it/cake-version/app/webroot/files/pdf/".$filename;


						//print $cmd . "\r\n";
						$urls[]=APP . '/webroot/files/pdf/' . $filename;


						system($cmd);

					}



					$pdf = "note_gara_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
					$str = implode(" ",$urls);
					$cmd = "pdftk $str cat output " . APP . '/webroot/files/pdf/' . $pdf;
					system($cmd);
					print "/files/pdf/" . $pdf;
					exit;
					
			
				
			}
			

    function admin_notesnew()
    {

        $this->autoRender = false;

        $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT']; //GIUSEPPE 2023-07-25

        $data = $_POST['ids'];

        $data = explode(",", $data);

        $urls = array();
        $urls_ = array();

        $cmdarray = [];

        $squadre = array();

        foreach ($data as $cmp)
        {


            $champ["id"] = $cmp;

            $partite = $this->Match->find('all', array(
                'conditions' => array(
                    'Match.Calendario = ' . $cmp
                )
            ));

            foreach ($partite as $partita)
            {


                if (empty($partita['Match']['NomeDelegatoA']))
                    $partita['Match']['NomeDelegatoA'] = 'zzzzzz';
                if (empty($partita['Match']['NomeDelegato']))
                    $partita['Match']['NomeDelegato'] = '!!!!!!';

                $squadre[] = array(
                    'id' => $partita['Match']['Calendario'],
                    'team' => $partita['Match']['Casa'],
                    'delegatoa' => $partita['Match']['NomeDelegatoA'],
                    'delegato' => $partita['Match']['NomeDelegato'],
                    'data' => $partita['Match']['Data'],
                    'campo' => $partita['Match']['Campo'],
                    'ora' => $partita['Match']['Ora'],
                    'playLeague' => $partita['Campionati']['PlayLeague'], //GIUSEPPE 2022-09-13
                );
                $squadre[] = array(
                    'id' => $partita['Match']['Calendario'],
                    'team' => $partita['Match']['Trasferta'],
                    'delegatoa' => $partita['Match']['NomeDelegatoA'],
                    'delegato' => $partita['Match']['NomeDelegato'],
                    'data' => $partita['Match']['Data'],
                    'campo' => $partita['Match']['Campo'],
                    'ora' => $partita['Match']['Ora'],
                    'playLeague' => $partita['Campionati']['PlayLeague'], //GIUSEPPE 2022-09-13
                );
            }
        }

//        file_put_contents("_partite___", print_r($partite,true));
        $this->write_file("_partite___", $partite);

        foreach ($squadre as $partita)
        {

            $playLeage = ((int) $partita['playLeague'] == 1) ? "/?playLeague" : ""; //GIUSEPPE 2022-09-13

            $filename = "tmp_note_" . $partita['id'] . "_" . uniqid() . ".pdf";
//            $cmd = "wget " . FULL_ABSOLUTE_URL . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . " -O /var/www/timmytag/midland2015cake2/app/webroot/files/pdf/" . $filename;
//            $cmd = "wget " . FULL_ABSOLUTE_URL . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . $playLeage . " -O /home/ovh/www/in_hosting/midlandDev2016/app/webroot/files/pdf/" . $filename;
//            $cmd = "wget " . FULL_ABSOLUTE_URL . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . $playLeage . " -O {$DOCUMENT_ROOT}/files/pdf/" . $filename;
//            $cmd = "wget " . FULL_ABSOLUTE_URL . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . $playLeage . " -O " . APP . "webroot/files/pdf/" . $filename;
         
            $cmd = "wget --no-check-certificate " . $_SERVER['HTTP_ORIGIN'] . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . $playLeage . " -O " . APP . "webroot/files/pdf/" . $filename;

            //print $cmd . "\r\n";
            $urls[] = APP . 'webroot/files/pdf/' . $filename;
            $urls[] = APP . 'webroot/files/pdf/' . $filename; //GIUSEPPE 2024-01-09 ogni squadra viene stampata due volte

            //GIUSEPPE 2023-09-18 temporaneo --------------------------------
            $urls_[] = $_SERVER['HTTP_ORIGIN'] . "/sections/getNotes/" . $partita["id"] . "/" . $partita["team"] . $playLeage;
//
//            $cmdarray[] = $cmd;
            //---------------------------------------------------------------    
            system($cmd);   //GIUSEPPE 2023-09-18 temporaneo
        }


        //GIUSEPPE 2023-09-18 --------------------------------
//        $pdf = "note_gara_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
//        $str = implode(" ", $urls);
//        $cmd = "pdftk $str cat output " . APP . 'webroot/files/pdf/' . $pdf;
        //system($cmd);
        //print "/files/pdf/" . $pdf;  
        
        
        //GIUSEPPE 2023-09-18 --------------------------------
//        print json_encode($urls_);
        $pdf = $this->mergePDF($urls);
        print "/files/pdf/" . $pdf;
        //---------------------------------------------------------------   
        exit;
    }


    private function mergePDF($urls) //GIUSEPPE 2023-09-18 --------------------------------
    {

        require('../webroot/my_pages/fpdf_merge.php');
        $merge = new FPDF_Merge();

        foreach ($urls as $url)
        {
            $merge->add($url);
        }

//        $merge->add(APP . 'webroot/files/pdf/tmp_note_189956_6508302bd9d40.pdf');
//        $merge->add(APP . 'webroot/files/pdf/tmp_note_189956_6508302d4d5af.pdf');
//        $merge->output(APP . 'webroot/files/pdf/test_merge.pdf');

        $pdf = "note_gara_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
        
        $merge->output(APP . "webroot/files/pdf/" . $pdf);

        foreach ($urls as $url)
        {
           unlink($url);
        }

        return $pdf;
    }



    

			function admin_responsible_index() {
			
				$this->layout = "timmybox";
				
				$anni = $this->AnniSportivi->find('list', array('order' => 'AnniSportivi.AnnoSportivo DESC'));
				
				$this->set('AnniSportivi', $anni);
			
			}
			
			function admin_responsible() {
			
				if($this->data['Print']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['Print']['Export'] == 'xls') $this->layout = "xls";
				
				$anno = $this->data['Print']['AnnoSportivo'];
				
				$data = $this->Yearbook->find('all', array(
				
						'conditions' => array(
						
							'Yearbook.Responsabile' => 'Si',
							'Yearbook.AnnoSportivo' => $anno
						
						),
						'group' => 'Yearbook.Tessera'
					
					)
				
				);
				
				$this->set('data', $data);
				$this->set('export', $this->data['Print']['Export']);
							
			}
			
			function admin_single_lda_index() {
			
				$this->layout = "timmybox";
						
			}
			
			function admin_single_lda() {
				
				/* Se richiamo la funzione dalla pagina personale degli LDA */
				if(isset($_POST['datas'])):
				$this->login_required = false;
				$this->data['singleLdaPrint']['Export']  = 'pdf';
				$this->data['singleLdaPrint']['DataIn']  = $_POST['datas']['start'];
				$this->data['singleLdaPrint']['DataOut'] = $_POST['datas']['end'];
				$this->data['singleLdaPrint']['Atleta']  = $_POST['datas']['athlete'];
				endif; 
				/* --------------------------------------------------------- */
				
				if($this->data['singleLdaPrint']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['singleLdaPrint']['Export'] == 'xls') $this->layout = "xls";
				 
				 $this->set('export', $this->data['singleLdaPrint']['Export']);
					
				$start_date = $this->data['singleLdaPrint']['DataIn'];
				$end_date = $this->data['singleLdaPrint']['DataOut'];
				$id = $this->data['singleLdaPrint']['Atleta'];
				
				$this->dmy2ymd($start_date);
				$this->dmy2ymd($end_date);
			
				$lda = $this->Lda->find('all', array(

					 'conditions' => array(
					 
						'Lda.Data between ? and ?' => array($start_date, $end_date),
						'OR' => array(
							
							'Lda.Arbitro' => $id,
							'Lda.Arbitro2' => $id,
							'Lda.Delegato' => $id,
							'Lda.DelegatoA' => $id,
						
						)
						
					 ),
					 'order' => 'Lda.Data ASC'
				 
					)
				 
				 );
				 
				 $lda_new = array();
				 
				 foreach($lda as $k => $tmp) {
				 	
				 	$tmp['Lda']['ImportoArbitro']   = $lda[$k]['Campionati']['TariffaArbitro'];
				 	$tmp['Lda']['ImportoArbitro2']  = $lda[$k]['Campionati']['TariffaArbitro2'];
				 	$tmp['Lda']['ImportoDelegato']  = $lda[$k]['Campionati']['TariffaDelegato'];
				 	$tmp['Lda']['ImportoDelegatoA'] = $lda[$k]['Campionati']['TariffaDelegatoA'];
				 	
				 	$tmp_match = $this->Match->find('count', array('conditions' => array('Match.lda_id' => $tmp['Lda']['LDA'])));
				 	
				 	if($tmp_match > 0) 
				 		$lda_new[$tmp['Lda']['Campionato'].$tmp['Lda']['Data'].$tmp['Lda']['Ora'].$tmp['Lda']['Casa'].$tmp['Lda']['Trasferta'].$tmp['Lda']['Campo']] = $tmp;
				 	
				 }
				 
				 unset($lda);
				 $lda = array();
				 
				 foreach($lda_new as $tmp) { $lda[] = $tmp; }
				 
				$altreSpese = $this->AthleteExpense->find('all', array(
				
					'conditions' => array(
					
						'AthleteExpense.Atleta' 	          => $id,
						'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
					
					),
				
				));
				 
				 $this->set('lda', $lda);
				 $this->set('altreSpese', $altreSpese);
				 $this->set('athlete_id', $id);				 
				 $this->set('athlete', $this->Athlete->findByAtleta($id));
				 $this->set('start_date', $start_date);
				 $this->set('end_date' , $end_date);
			
			}
			
			function single_lda() {
				
				/* Se richiamo la funzione dalla pagina personale degli LDA */
				if(isset($_POST['datas'])):
				$this->login_required = false;
				$this->data['singleLdaPrint']['Export']  = 'pdf';
				$this->data['singleLdaPrint']['DataIn']  = $_POST['datas']['start'];
				$this->data['singleLdaPrint']['DataOut'] = $_POST['datas']['end'];
				$this->data['singleLdaPrint']['Atleta']  = $_POST['datas']['athlete'];
				endif; 
				/* --------------------------------------------------------- */
				
				if($this->data['singleLdaPrint']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['singleLdaPrint']['Export'] == 'xls') $this->layout = "xls";
				 
				 $this->set('export', $this->data['singleLdaPrint']['Export']);
					
				$start_date = $this->data['singleLdaPrint']['DataIn'];
				$end_date = $this->data['singleLdaPrint']['DataOut'];
				$id = $this->data['singleLdaPrint']['Atleta'];
				
				$this->dmy2ymd($start_date);
				$this->dmy2ymd($end_date);
			
				$lda = $this->Lda->find('all', array(

					 'conditions' => array(
					 
						'Lda.Data between ? and ?' => array($start_date, $end_date),
						'OR' => array(
							
							'Lda.Arbitro' => $id,
							'Lda.Arbitro2' => $id,
							'Lda.Delegato' => $id,
							'Lda.DelegatoA' => $id,
						
						)
						
					 ),
					 'order' => 'Lda.Data ASC'
				 
					)
				 
				 );
				 
				 $lda_new = array();
				 
				 foreach($lda as $k => $tmp) {
				 	
				 	$tmp['Lda']['ImportoArbitro']   = $lda[$k]['Campionati']['TariffaArbitro'];
				 	$tmp['Lda']['ImportoArbitro2']  = $lda[$k]['Campionati']['TariffaArbitro2'];
				 	$tmp['Lda']['ImportoDelegato']  = $lda[$k]['Campionati']['TariffaDelegato'];
				 	$tmp['Lda']['ImportoDelegatoA'] = $lda[$k]['Campionati']['TariffaDelegatoA'];
				 	
				 	$tmp_match = $this->Match->find('count', array('conditions' => array('Match.lda_id' => $tmp['Lda']['LDA'])));
				 	
				 	if($tmp_match > 0) 
				 		$lda_new[$tmp['Lda']['Campionato'].$tmp['Lda']['Data'].$tmp['Lda']['Ora'].$tmp['Lda']['Casa'].$tmp['Lda']['Trasferta'].$tmp['Lda']['Campo']] = $tmp;
				 	
				 }
				 
				 unset($lda);
				 $lda = array();
				 
				 foreach($lda_new as $tmp) { $lda[] = $tmp; }
				 
				$altreSpese = $this->AthleteExpense->find('all', array(
				
					'conditions' => array(
					
						'AthleteExpense.Atleta' 	          => $id,
						'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
					
					),
				
				));
				 
				 $this->set('lda', $lda);
				 $this->set('altreSpese', $altreSpese);
				 $this->set('athlete_id', $id);				 
				 $this->set('athlete', $this->Athlete->findByAtleta($id));
				 $this->set('start_date', $start_date);
				 $this->set('end_date' , $end_date);
			
			}			
			
			function admin_general_lda_index() {
			
				$this->layout = "timmybox";
						
			}
			
			function admin_general_lda() {
			
				if($this->data['generalLdaPrint']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['generalLdaPrint']['Export'] == 'xls') $this->layout = "xls";
							
				$this->set('export', $this->data['generalLdaPrint']['Export']);
				$start_date = $this->data['generalLdaPrint']['DataIn'];
				$end_date = $this->data['generalLdaPrint']['DataOut'];
				
				$this->dmy2ymd($start_date);
				$this->dmy2ymd($end_date);
				
				$lda = $this->Lda->query("
						SELECT Arbitro, SUM(Tot) As Tot2 FROM
						(
							SELECT Arbitro, SUM(ImportoArbitro) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Arbitro != ''
								GROUP BY Arbitro
							UNION 
								SELECT Delegato as Arbitro, SUM(ImportoDelegato) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Delegato != ''
								GROUP BY Delegato
							UNION 
								SELECT DelegatoA as Arbitro, SUM(ImportoDelegatoA) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND DelegatoA != ''
								GROUP BY DelegatoA
							UNION 
								SELECT Arbitro2 as Arbitro, SUM(ImportoArbitro2) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Arbitro2 != ''
								GROUP BY Arbitro2
							UNION
								SELECT Atleta as Arbitro, SUM(Importo) as Tot
								FROM AtletiSpese
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Importo >= 0
								GROUP BY Atleta								
						) as tab GROUP BY Arbitro
				");
				
				$new_lda = array();
				
				foreach($lda as $key => $val) {
				
					$arbitro = $this->Athlete->findByAtleta($val['tab']['Arbitro']);
					$anagrafica = $arbitro['Athlete']['reverseAnagrafica'];
					
					$tmp = $this->Lda->find('all', array(

						 'conditions' => array(
						 
							'Lda.Data between ? and ?' => array($start_date, $end_date),
						 
							'OR' => array(
								
								'Lda.Arbitro'   => $val['tab']['Arbitro'],
								'Lda.Arbitro2'  => $val['tab']['Arbitro'],
								'Lda.Delegato'  => $val['tab']['Arbitro'],
								'Lda.DelegatoA' => $val['tab']['Arbitro'],
							
							)
							
						 ),
						 'order' => 'Lda.Data ASC'
					 
						)
					 
					 );	
					 
					 $totale = 0;
					 
					 $lda_new = array();
					 
					 foreach($tmp as $k => $l) {
					 	
					 	$l['Lda']['ImportoArbitro']   = $tmp[$k]['Campionati']['TariffaArbitro'];
					 	$l['Lda']['ImportoArbitro2']  = $tmp[$k]['Campionati']['TariffaArbitro2'];
					 	$l['ImportoDelegato']         = $tmp[$k]['Campionati']['TariffaDelegato'];
					 	$l['Lda']['ImportoDelegatoA'] = $tmp[$k]['Campionati']['TariffaDelegatoA'];
					 	
					 	$tmp_match = $this->Match->find('count', array('conditions' => array('Match.lda_id' => $l['Lda']['LDA'])));
					 	
					 	if($tmp_match > 0) 
					 		$lda_new[$l['Lda']['Campionato'].$l['Lda']['Data'].$l['Lda']['Ora'].$l['Lda']['Casa'].$l['Lda']['Trasferta'].$l['Lda']['Campo']] = $l;
					 	
					 }
					 
					 unset($tmp);
					 $tmp = array();
					 
					 foreach($lda_new as $k => $l) {
					 	
					 	if($l['Lda']['Arbitro']   == $val['tab']['Arbitro'])  $totale += $l['Campionati']['TariffaArbitro'];
					 	if($l['Lda']['Arbitro2']  == $val['tab']['Arbitro'])  $totale += $l['Campionati']['TariffaArbitro2'];
					 	if($l['Lda']['Delegato']  == $val['tab']['Arbitro'])  $totale += $l['Campionati']['TariffaDelegato'];
					 	if($l['Lda']['DelegatoA'] == $val['tab']['Arbitro'])  $totale += $l['Campionati']['TariffaDelegatoA'];
					 	
					 }						
				
					$altreSpese= array();
					$altreSpese= $this->AthleteExpense->find('all', array(
					
						'conditions' => array(
						
							'AthleteExpense.Atleta' 	  => $val['tab']['Arbitro'],
							'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
							'AthleteExpense.Importo >' => 0
						
						),
					
					));	
					
					//print_r($altreSpese);
					
					if(!empty($altreSpese)) {
					
						foreach($altreSpese as $spesa) {
							$totale += $spesa['AthleteExpense']['Importo'];
						}
					
					}			
				
					$new_lda[] = array(
					
						'Arbitro_id' => $val['tab']['Arbitro'],
						'Arbitro'    => $anagrafica,
						'Totale'     => $totale
					
					);

				}		
				
				 $this->set('lda', $new_lda);			 
				 $this->set('start_date', $start_date);
				 $this->set('end_date' , $end_date);
			
			}
			
			function admin_ldaMounth() {
			
				ini_set("max_execution_time",90000);			
			
				if($this->data['ldaMounth']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['ldaMounth']['Export'] == 'xls') $this->layout = "xls";
							
				$this->set('export', $this->data['ldaMounth']['Export']);
				$start_date = $this->data['ldaMounth']['DataIn'];
				$end_date = $this->data['ldaMounth']['DataOut'];
				
				$this->dmy2ymd($start_date);
				$this->dmy2ymd($end_date);
				
				$lda = $this->Lda->query("
						SELECT Arbitro, SUM(Tot) As Tot2 FROM
						(
							SELECT Arbitro, SUM(ImportoArbitro) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Arbitro != ''
								GROUP BY Arbitro
							UNION 
								SELECT Delegato as Arbitro, SUM(ImportoDelegato) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Delegato != ''
								GROUP BY Delegato
							UNION 
								SELECT DelegatoA as Arbitro, SUM(ImportoDelegatoA) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND DelegatoA != ''
								GROUP BY DelegatoA
							UNION 
								SELECT Arbitro2 as Arbitro, SUM(ImportoArbitro2) as Tot
								FROM LDA
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								AND Arbitro2 != ''
								GROUP BY Arbitro2
							UNION
								SELECT Atleta as Arbitro, SUM(Importo) as Tot
								FROM AtletiSpese
								WHERE DATA BETWEEN '$start_date' AND '$end_date'
								GROUP BY Atleta
						) as tab GROUP BY Arbitro
				");
				
			
				
				$new_lda = array();
				
				foreach($lda as $key => $val) {
				
					$data = $this->Athlete->read(null, $val['tab']['Arbitro']);
					$new_lda[] = array(
					
						'id' => $val['tab']['Arbitro'],
						'anagrafica' => $data['Athlete']['reverseAnagrafica'],
					
					);

				}
				
				$new_lda = array_orderby($new_lda, 'anagrafica', SORT_ASC);
				
				$tot_lda    = array();
				$altreSpese = array();
				
				foreach($new_lda as $athlete_lda) {
					
					$lda = $athlete_lda['id'];
				
					$tot_lda[$lda] = $this->Lda->find('all', array(

						 'conditions' => array(
						 
							'Lda.Data between ? and ?' => array($start_date, $end_date),
						 
							'OR' => array(
								
								'Lda.Arbitro' => $lda,
								'Lda.Arbitro2' => $lda,
								'Lda.Delegato' => $lda,
								'Lda.DelegatoA' => $lda,
							
							)
							
						 ),
						 'order' => 'Lda.Data ASC'
					 
						)
					 
					 );	
					 
					$lda_new = array();					 
					 
					 foreach($tot_lda[$lda] as $k => $tmp) {
					 	
					 	$tmp['Lda']['ImportoArbitro']   = $tot_lda[$lda][$k]['Campionati']['TariffaArbitro'];
					 	$tmp['Lda']['ImportoArbitro2']  = $tot_lda[$lda][$k]['Campionati']['TariffaArbitro2'];
					 	$tmp['Lda']['ImportoDelegato']  = $tot_lda[$lda][$k]['Campionati']['TariffaDelegato'];
					 	$tmp['Lda']['ImportoDelegatoA'] = $tot_lda[$lda][$k]['Campionati']['TariffaDelegatoA'];
					 	
					 	$tmp_match = $this->Match->find('count', array('conditions' => array('Match.lda_id' => $tmp['Lda']['LDA'])));
					 	
					 	if($tmp_match > 0) 
					 		$lda_new[$tmp['Lda']['Campionato'].$tmp['Lda']['Data'].$tmp['Lda']['Ora'].$tmp['Lda']['Casa'].$tmp['Lda']['Trasferta'].$tmp['Lda']['Campo']] = $tmp;
					 	
					 }	
				
				 unset($tot_lda[$lda]);
				 $tot_lda[$lda] = array();					 	
				 
				 foreach($lda_new as $tmp) { $tot_lda[$lda][] = $tmp; }					 			 
					 
					$altreSpese[$lda] = $this->AthleteExpense->find('all', array(
					
						'conditions' => array(
						
							'AthleteExpense.Atleta' 	  => $lda,
							'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
						
						),
					
					));
					 

					
				}
				/*
				print count($tot_lda);
				print_r($tot_lda);
				exit;*/
				
				$this->set('tot_lda', $tot_lda);
				$this->set('altreSpese', $altreSpese);
				$this->set('start_date', $start_date);
				$this->set('end_date' , $end_date);	
				$this->set('export', $this->data['ldaMounth']['Export']);	
				
				
			
			}
			
			function admin_getAthlete($id) {
			
				$this->layout = "ajax";
				
				$data = $this->Athlete->findByAtleta($id);
				
				return $data;
			
			}
			
			function admin_rank_index($campionato, $girone) {
			
				$this->layout = "timmybox";
				
				$campionato_ = $this->Campionati->findByCampionato($campionato);
				
				$this->set('campionato', $campionato_['Campionati']['Nome']);
				$this->set('campionato_id', $campionato);
				$this->set('girone', $girone);
				
			}
			
			function admin_rank() {
			
				if($this->data['Ranking']['Export'] == 'pdf') $this->layout = "pdf";
				 else if($this->data['Ranking']['Export'] == 'xls') $this->layout = "xls";
				 				 
				$girone = $this->data['Ranking']['Girone'];
				$campionato = $this->data['Ranking']['Campionato'];
				$campionato_id = $this->data['Ranking']['Campionato_id'];
				
				$girone_ = $this->Half->find('first', array(
				
					'conditions' => array(
						
						'Half.Campionato' => $campionato_id,
						'Half.Descrizione' => $girone
					
					)
				
				));
				
				$girone_id = $girone_['Half']['GironeCampionato'];
				
				/* MARCATORI */
									
				$classifica_marcatori_gironeCampionato = $this->Matchgoal->query(
			
						"SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						SUM(GoalPartite.Goal) as goals FROM GoalPartite 
						WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$campionato_id' AND Calendari.GironeCampionato = '$girone_id')
						AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari)
						GROUP BY GoalPartite.Atleta ORDER BY goals DESC LIMIT 10"
					
					);	

				/* Disciplinari */
				
				$classifica_espulsi = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Espulsione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER BY Tot DESC LIMIT 10
				");
				
				$classifica_ammoniti = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Ammonizione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER By Tot DESC LIMIT 10
				");
				
				$disciplinari = array();
				
				foreach($classifica_ammoniti as $ammonito) {
				
					$espulsioni = 0;
				
					foreach($classifica_espulsi as $espulso) {
					
						if($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id']) {
						
							$espulsioni = $espulso[0]['Tot'];
						
						}
					
					}
				
					$disciplinari[] = array(
					
						'Squadra' => $ammonito[0]['NomeSquadra'],
						'Atleta_id' => $ammonito[0]['atleta_id'],
						'Atleta' => $ammonito[0]['anagrafica'],
						'Ammonizioni' => $ammonito[0]['Tot'],
						'Espulsioni' => $espulsioni
					
					);
				
				}
				
				/* Classifica */
					
				$classifica = $this->Ranking->find('all', array(
				
					'conditions' => array(
					
						'Ranking.GironeCampionato' => $girone_id
						
					),
					'order' => array('Ranking.Punti DESC')
				
				));
					
				$this->set('campionato', $campionato);
				$this->set('girone', $girone);
				$this->set('marcatori', $classifica_marcatori_gironeCampionato);
				$this->set('disciplinari', $disciplinari);
				$this->set('classifica', $classifica);
				$this->set('export' , $this->data['Ranking']['Export']);
			
			}
			
			function admin_yearLabel($id = null) {
			
				$this->layout = "pdf";
				
				$data = $this->Yearbook->findByAnnuario($id);
								
				$this->set('annuario', $data);
			
			}
			
			function admin_athleteLabel($id = null) {
			
				$this->layout = "pdf";
				
				$data = $this->Athlete->findByAtleta($id);
								
				$this->set('atleta', $data);
			
			}
			
			function admin_label_full() {
			
				$this->layout = "timmybox";
			
			}
			
			function admin_label_full_go() {
			
				$this->layout = "pdf";
				
				$labels = $_POST['labels'];
				
				$athletes = array();
				
				foreach($labels as $label) {
				
					$athletes[] = $this->Athlete->find('first', array(
					
						'fields' 	 => array('Athlete.reverseAnagrafica','Athlete.Indirizzo','Athlete.Cap','Athlete.Localita','Athlete.Provincia'),
						'conditions' => array(
						
							'Athlete.Atleta' => $label,
						
						),
					
					));
					
				}
				
				$this->set('athletes', $athletes);
			
			}
			
			function admin_label_full_year_go() {
			
				$this->layout = "pdf";
				
				$labels = $_POST['labels'];
				
				$athletes = array();
				
				foreach($labels as $label) {
				
					$athletes[] = $this->Yearbook->find('first', array(
					
						
						'conditions' => array(
						
							'Yearbook.Annuario' => $label,
						
						),
					
					));
					
				}
				
				$this->set('athletes', $athletes);				
			
			}
			
			function admin_certifications($annoSportivo) {
				
				ini_set("max_execution_time",30000);	
				
				//$this->layout = "pdf";
				//$this->layout = "ajax";
				
				//$anno_sportivo = (isset($this->data['Certification']['anno']))? $this->data['Certification']['anno'] : 2010;
				$anno_sportivo = (int)$annoSportivo;
				
				$this->Yearbook->recursive = 0;
				
				$athletes = $this->Athlete->find('all', array(
				
					'fields'     => array(
						'Athlete.Atleta',
						'Athlete.Nome',
						'Athlete.Cognome',
						'Athlete.Provincia',
						'Athlete.Indirizzo',
						'Athlete.CodiceFiscale',
						'Athlete.DataNascita',
						'Athlete.LuogoNascita',
						'Athlete.Localita',
						'Athlete.Cap'
					),
					'conditions' => array('Athlete.Arbitro' => 'Si'),
					//'group'		 => 'Yearbook.Atleta',
					'order'      => array('Athlete.Cognome' => 'ASC')
				
				));
				
				/* Make sure the controller doesn't auto render. */
				$this->autoRender = false;
				 
				$this->Mpdf->init();	
				 
				$date = array(
				
					'1'  => array('name' => 'Gennaio', 'start' => $anno_sportivo . '-01-01', 'end' => $anno_sportivo . '-01-31'),
					'2'  => array('name' => 'Febbraio', 'start' => $anno_sportivo . '-02-01', 'end' => $anno_sportivo . '-02-29'),
					'3'  => array('name' => 'Marzo', 'start' => $anno_sportivo . '-03-01', 'end' => $anno_sportivo . '-03-31'),
					'4'  => array('name' => 'Aprile', 'start' => $anno_sportivo . '-04-01', 'end' => $anno_sportivo . '-04-31'),
					'5'  => array('name' => 'Maggio', 'start' => $anno_sportivo . '-05-01', 'end' => $anno_sportivo . '-05-31'),
					'6'  => array('name' => 'Giugno', 'start' => $anno_sportivo . '-06-01', 'end' => $anno_sportivo . '-06-31'),
					'7'  => array('name' => 'Luglio', 'start' => $anno_sportivo . '-07-01', 'end' => $anno_sportivo . '-07-31'),
				
					'8'  => array('name' => 'Agosto', 'start' => $anno_sportivo . '-08-01', 'end' => $anno_sportivo . '-08-31'),
					'9'  => array('name' => 'Settembre', 'start' => $anno_sportivo . '-09-01', 'end' => $anno_sportivo . '-09-31'),
					'10' => array('name' => 'Ottobre', 'start' => $anno_sportivo . '-10-01', 'end' => $anno_sportivo . '-10-31'),
					'11' => array('name' => 'Novembre', 'start' => $anno_sportivo . '-11-01', 'end' => $anno_sportivo . '-11-31'),
					'12' => array('name' => 'Dicembre', 'start' => $anno_sportivo . '-12-01', 'end' => $anno_sportivo . '-12-31'),
				
				);					 

	

					 
				foreach($athletes as $athlete) {
		
					//debug($athlete['Athlete']['Atleta']);
					$compensi = array();

					foreach($date as $data) {
						
						$compensi[$data['name']] = $this->getCertificationCompensi($data['start'], $data['end'], $athlete['Athlete']['Atleta']);	
						
					}

					$view = new View($this, false);
					$view->viewPath = 'elements/admin';
					$view->layout = "ajax"; 
	
					$view->set('anno_sportivo', $anno_sportivo);
					$view->set('athlete', $athlete);
					$view->set('compensi', $compensi);
					
					$totale = 0;

					foreach ($compensi as $compenso){
						$totale+=$compenso;
					}


					if ($totale > 0) {
					$view_output = $view->render('certifications');		
					$this->Mpdf->pdf->AddPage();
					$this->Mpdf->pdf->WriteHTML($view_output);			
					}					
			
				} 
				
				$this->Mpdf->setFilename('certificazioni_' . $anno_sportivo . '.pdf');
				$this->Mpdf->setOutput('D');
				
				//die(json_encode(array('link' => 'files/pdf/certificazioni_' . $anno_sportivo . '.pdf')));				
			
				
			}
			
			private function getCertificationCompensi($start_date, $end_date, $id) {
			
				$lda = $this->Lda->find('all', array(

					 'conditions' => array(
					 
						'Lda.Data between ? and ?' => array($start_date, $end_date),
						'OR' => array(
							
							'Lda.Arbitro' => $id,
							'Lda.Arbitro2' => $id,
							'Lda.Delegato' => $id,
							'Lda.DelegatoA' => $id,
						
						)
						
					 ),
					 'order' => 'Lda.Data ASC'
				 
					)
				 
				 );
				 
				 
				 
				 $lda_new = array();
				 
				 foreach($lda as $k => $tmp) {
				 	
				 	$tmp['Lda']['ImportoArbitro']   = $lda[$k]['Campionati']['TariffaArbitro'];
				 	$tmp['Lda']['ImportoArbitro2']  = $lda[$k]['Campionati']['TariffaArbitro2'];
				 	$tmp['Lda']['ImportoDelegato']  = $lda[$k]['Campionati']['TariffaDelegato'];
				 	$tmp['Lda']['ImportoDelegatoA'] = $lda[$k]['Campionati']['TariffaDelegatoA'];
				 	
				 	$tmp_match = $this->Match->find('count', array('conditions' => array('Match.lda_id' => $tmp['Lda']['LDA'])));
				 	$lda_new[$tmp['Lda']['Campionato'].$tmp['Lda']['Data'].$tmp['Lda']['Ora'].$tmp['Lda']['Casa'].$tmp['Lda']['Trasferta'].$tmp['Lda']['Campo']] = $tmp;
				 	
				 }
				 
				 unset($lda);
				 $lda = array();
				 
				 foreach($lda_new as $tmp) { $lda[] = $tmp; }
				 
				 $tot_compensi = 0;
				 
				 foreach($lda as $t) {
				 	
				 	if($t['Lda']['Arbitro']   == $id) $tot_compensi += $t['Lda']['ImportoArbitro'];
				 	if($t['Lda']['Arbitro2']  == $id) $tot_compensi += $t['Lda']['ImportoArbitro2'];
				 	if($t['Lda']['Delegato']  == $id) $tot_compensi += $t['Lda']['ImportoDelegato'];
				 	if($t['Lda']['DelegatoA'] == $id) $tot_compensi += $t['Lda']['ImportoDelegatoA'];
				 	
				 }
				 
				$altreSpese = $this->AthleteExpense->find('all', array(
				
					'conditions' => array(
					
						'AthleteExpense.Atleta' 	          => $id,
						'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
					
					),
				
				));		
				
				foreach($altreSpese as $spesa) {
					
					$spesa 		   = $spesa['AthleteExpense'];
					
					if($spesa['Importo'] > 0)
						$tot_compensi += $spesa['Importo'];
				}					
						
				return $tot_compensi;
				
			}
			
	}