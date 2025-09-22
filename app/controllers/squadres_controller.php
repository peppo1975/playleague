<?php

class SquadresController extends AppController
{

    var $name = "Squadres";
    var $login_required = true;
    var $helpers = array('Backend', 'fpdf', 'Cksource');
    var $uses = array('Squadre', 'ChampCategory', 'Athlete', 'Match', 'SquadreCampionati', 'Ranking', 'Half', 'Yearbook', 'AnniSportivi', 'Upload', 'Campionati', 'SquadreAlbo');
    public $cacheAction = array(
        'albo_doro' => 48000
    );
    function admin_index()
    {
    }

    function coccarde()
    {

        $this->layout = "ajax";
    }

    function albo_doro()
    {

        $this->layout = "content";

        //Anno sportivo corrente
        $anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
        $anno = $anno[key($anno)];

        //Campionati anno sportivo corrente
        $campionati_tmp = $this->Campionati->find('list', array(
            'fields' => array(
                'Campionati.Campionato',
                'Campionati.Nome',
                'Campionati.Tipo',
            ),
            'conditions' => array(
                'Campionati.group_id' => 1,
                'Campionati.SquadraCampionato_id',
                'Campionati.Categoria !=' => 0,
                'Campionati.Tipo <= 3'
            ),
            'order' => array('Campionati.AnnoSportivo' => 'ASC', 'Campionati.Tipo ASC', 'Campionati.SessoTipo ASC', 'Campionati.Nome ASC'),
        ));



        //GIUSEPPE 2020-01-30 --------------------------
        unset($campionati_tmp[3]); //tolgo il basket
        //----------------------------------------------
        //debug($campionati_tmp);

        $campionati = array();

        $tipo_arr = array(0 => 'Calcio a 5', 1 => 'Calcio a 7', '2' => 'Calcio a 11');
        $sessoTipo = array(0 => 'Maschile', 1 => 'Femminile', 2 => 'Misto');


        foreach ($campionati_tmp as $tipo => $campionati_tipo_tmp) {
            foreach ($campionati_tipo_tmp as $id_campionato => $campionato_tmp) {

                $campionato = $this->Campionati->find('first', array(
                    'fields' => array(
                        'Campionati.Campionato',
                        'Campionati.AnnoSportivo',
                        'Campionati.Nome',
                        'Campionati.Tipo',
                        'Campionati.SessoTipo',
                        'Campionati.SquadraCampionato_id',
                        'Campionati.Categoria',
                    ),
                    'conditions' => array(
                        'Campionati.Campionato' => $id_campionato,
                        'Campionati.Tipo <= 3'
                    ),
                    'recursive' => -1,
                ));

                $champCategory = $this->ChampCategory->find('first', array(
                    'fields' => array(
                        'ChampCategory.id',
                        'ChampCategory.Nome',
                    ),
                    'conditions' => array(
                        'ChampCategory.id' => $campionato['Campionati']['Categoria']
                    ),
                    'recursive' => -1,
                ));

                //debug($campionato['Campionati']['SquadraCampionato_id']);

                $squadra = $this->SquadreCampionati->findBySquadracampionato($campionato['Campionati']['SquadraCampionato_id']);

                //$campionati[$tipo.'|'.$tipo_arr[$tipo]][$campionato['Campionati']['AnnoSportivo']][$campionato['Campionati']['SessoTipo'].'|'.$sessoTipo[$campionato['Campionati']['SessoTipo']]][] = $campionato;  
                //$campionati[$tipo_arr[$tipo]][$sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][$campionato['Campionati']['AnnoSportivo']][$id_campionato] = $campionato;
                $campionati[$tipo_arr[$tipo] . ' ' . $sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][($campionato['Campionati']['AnnoSportivo'] - 1) . '/' . $campionato['Campionati']['AnnoSportivo']][] = $squadra['Squadre']['Denominazione'];
            }
        }

        $this->set('albo', $campionati);

        //debug($campionati);
    }



    function albo_doro_tennis() //GIUSEPPE 2017-05-02
    {

        $this->layout = "content";

        //Anno sportivo corrente
        $anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
        $anno = $anno[key($anno)];

        //Campionati anno sportivo corrente
        $campionati_tmp = $this->Campionati->find('list', array(
            'fields' => array(
                'Campionati.Campionato',
                'Campionati.Nome',
                'Campionati.Tipo',
            ),
            'conditions' => array(
                'Campionati.group_id' => 1,
                'Campionati.SquadraCampionato_id',
                'Campionati.Categoria !=' => 0,
                'Campionati.Tipo = 5'
            ),
            'order' => array('Campionati.AnnoSportivo' => 'ASC', 'Campionati.Tipo ASC', 'Campionati.SessoTipo ASC', 'Campionati.Nome ASC'),
        ));

        //debug($campionati_tmp);

        $campionati = array();

        $tipo_arr = array(5 => 'Squadre');
        $sessoTipo = array(0 => 'Maschili', 1 => 'Femminili', '2' => 'Miste');

        foreach ($campionati_tmp as $tipo => $campionati_tipo_tmp) {
            foreach ($campionati_tipo_tmp as $id_campionato => $campionato_tmp) {

                $campionato = $this->Campionati->find('first', array(
                    'fields' => array(
                        'Campionati.Campionato',
                        'Campionati.AnnoSportivo',
                        'Campionati.Nome',
                        'Campionati.Tipo',
                        'Campionati.SessoTipo',
                        'Campionati.SquadraCampionato_id',
                        'Campionati.Categoria',
                    ),
                    'conditions' => array(
                        'Campionati.Campionato' => $id_campionato,
                        'Campionati.Tipo = 5'
                    ),
                    'recursive' => -1,
                ));

                $champCategory = $this->ChampCategory->find('first', array(
                    'fields' => array(
                        'ChampCategory.id',
                        'ChampCategory.Nome',
                    ),
                    'conditions' => array(
                        'ChampCategory.id' => $campionato['Campionati']['Categoria']
                    ),
                    'recursive' => -1,
                ));

                //debug($campionato['Campionati']['SquadraCampionato_id']);

                $squadra = $this->SquadreCampionati->findBySquadracampionato($campionato['Campionati']['SquadraCampionato_id']);

                //$campionati[$tipo.'|'.$tipo_arr[$tipo]][$campionato['Campionati']['AnnoSportivo']][$campionato['Campionati']['SessoTipo'].'|'.$sessoTipo[$campionato['Campionati']['SessoTipo']]][] = $campionato;	
                //$campionati[$tipo_arr[$tipo]][$sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][$campionato['Campionati']['AnnoSportivo']][$id_campionato] = $campionato;
                $campionati[$tipo_arr[$tipo] . ' ' . $sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][($campionato['Campionati']['AnnoSportivo'] - 1) . '/' . $campionato['Campionati']['AnnoSportivo']][] = $squadra['Squadre']['Denominazione'];
            }
        }

        $this->set('albo', $campionati);
    }



    //GIUSEPPE 2020-01-31--------------------------------
    function albo_doro_basket()
    {

        $this->layout = "content";

        $campionati = array();

        $sql = "SELECT 
                        * ,
                    CampionatiCategorie.Nome as NomeCategoria,
                    Campionati.AnnoSportivo as AnnoCampionato,
                    Squadre.Denominazione as NomeSquadra
                FROM 
                        `CampionatiCategorie` 
                        INNER JOIN Campionati ON Campionati.Categoria = CampionatiCategorie.id 
                        INNER JOIN SquadreCampionati ON Campionati.SquadraCampionato_id = SquadreCampionati.SquadraCampionato 
                        INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra 
                WHERE 
                        CampionatiCategorie.sport = 'BASKET' 
                ORDER BY 
                        Campionati.AnnoSportivo DESC";

        $result = mysql_query($sql);


        if (mysql_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysql_fetch_assoc($result)) {
                $anno = $row['AnnoCampionato'];

                $stagione = sprintf("%s/%s", (int) $anno - 1, $anno);

                $campionati['Maschile'][$row['NomeCategoria']][$stagione] = $row['NomeSquadra'];
            }
        }

        $this->set('albo', $campionati);
    }



    //GIUSEPPE 2017-06-10 - - - - - - - - - - - - - - - - -
    function read_all_sets()
    {
        $all_sets = array();

        $sql = "SELECT GoalPartite.SetTennis FROM `Campionati`
  	INNER JOIN SquadreCampionati
  	ON Campionati.Campionato = SquadreCampionati.Campionato
  	INNER JOIN GoalPartite
  	ON GoalPartite.SquadraCampionato = SquadreCampionati.SquadraCampionato
  	INNER JOIN Squadre
  	ON Squadre.Squadra = SquadreCampionati.Squadra
  	WHERE
  	Campionati.AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `Campionati`)
  	AND
  	Squadre.id_sport = '1'";

        $result = mysql_query($sql);


        if (mysql_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysql_fetch_assoc($result)) {
                $all_sets[] = json_decode($row['SetTennis'], true);
            }
        }

        return $all_sets;
    }





    /* //GIUSEPPE 2018-06-08------------------------  */


    function ranking_squadre()
    {
        $this->layout = "content";

        //        $this->set('ranking_teams', $this->ranking_teams_calculation());

        /* non commentare o cancellare questa creazione directory */
        $dir_save = APP . "webroot/files/ranking_teams";

        if (!is_dir($dir_save))
            mkdir($dir_save);

        $ranking_teams = unserialize(file_get_contents($dir_save . "/ranking_order.txt"));

        $this->set('ranking_teams', $ranking_teams);
    }








    /*

      function del_file()
      {

      $dir_save = APP . "webroot/files/ranking_teams";

      if (is_dir($dir_save))
      {
      $dir = scandir($dir_save);

      foreach ($dir as $file)
      {
      if ($file != "." && $file != "..")
      unlink("$dir_save/$file");
      }

      // rmdir($dir_save);
      }

      //exit;

      }
     */


    function ranking_teams_calculation($back, $max)
    {

        $this->layout = "content";

        $time1 = time();

        $all_teams = array();

        $all_calendar = array();

        $all_athletes = array();

        $now_athletes = array();

        $types = array("0" => "C5", "1" => "C7");

        $genders = array("0" => "M", "1" => "F");


        //GIUSEPPE 2018-07-16 Anno sportivo per visualizzazione scarpa d'oro -----------
        $last_year_array = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

        $year = $last_year_array['AnniSportivi']['AnnoSportivo'];

        $data_max = strtotime($this->requestAction('sections/data_max') . " 00:00:00");

        if (time() < $data_max) {
            $year--;
        }

        $last_year = strval($year - $back);

        echo $last_year;

        //------------------------------------------------------------------------------
        //------------------------------------------------------------------------------
        // Questa query ci serve per le squadre
        // GIUSEPPE 2020-03-05 aggiunto CausaleRisultato
        $sql = 'SELECT 
                        GoalPartite.GoalPartita, 
                        GoalPartite.Calendario, 
                        GoalPartite.SquadraCampionato, 
                        GoalPartite.Goal, 
                        GoalPartite.Autogoal, 
                        SquadreCampionati.Squadra, 
                        Squadre.Denominazione, 
                        SquadreCampionati.Campionato, 
                        Campionati.Nome, 
                        Campionati.Tipo, 
                        Campionati.SessoTipo, 
                        Campionati.Italiana, 
                        SquadreCampionati.GironeCampionato, 
                        (
                                SELECT 
                                        GironiCampionati.Descrizione 
                                FROM 
                                        GironiCampionati 
                                WHERE 
                                        GironiCampionati.GironeCampionato = SquadreCampionati.GironeCampionato 
                                        AND GironiCampionati.Campionato = SquadreCampionati.Campionato
                        ) as NomeGirone, 
                        (
                                SELECT 
                                        Calendari.CausaleRisultato 
                                FROM 
                                        Calendari 
                                WHERE 
                                        Calendari.Calendario = GoalPartite.Calendario
                        ) as CausaleRisultato, 
                        Campionati.AnnoSportivo 
                       

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
                        Squadre

                        ON SquadreCampionati.Squadra = Squadre.Squadra
                        
                   

                        WHERE 

                        Campionati.sport = "CALCIO"
                        AND
                        Campionati.scuola = 0
                        AND
                        Campionati.AnnoSportivo  <= "' . $last_year . '"
                 
                        ORDER BY `Campionati`.`AnnoSportivo`  DESC';




        $result = mysql_query($sql);


        if (mysql_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysql_fetch_assoc($result)) {
                //$all[] = $row;

                $anno_sportivo = $row['AnnoSportivo'];
                $squadra_id = $row['Squadra'];
                $squadra = $row['Denominazione'];
                $calendario = $row['Calendario'];
                $goal = $row['Goal'];
                $autogoal = $row['Autogoal'];
                $tipo = $types[$row['Tipo']];
                $sesso = $genders[$row['SessoTipo']];
                $italiana = $row['Italiana'];

                // GIUSEPPE 2020-03-05
                $CausaleRisultato = $row['CausaleRisultato'];


                // GIUSEPPE 2020-03-05
                if ((int) $CausaleRisultato == 2 || (int) $CausaleRisultato == 3) {
                    continue;
                }



                $all_calendar[$calendario]['Squadra'][$squadra_id]['Goal'] += $goal;
                $all_calendar[$calendario]['Squadra'][$squadra_id]['Autogoal'] += $autogoal;

                $all_calendar[$calendario]['Squadra'][$squadra_id]['Girone'] = $row['GironeCampionato'];
                $all_calendar[$calendario]['Squadra'][$squadra_id]['Italiana'] = $italiana;

                //                $all_calendar[$calendario]['Squadra'][$squadra_id]['CausaleRisultato'] = $CausaleRisultato; // GIUSEPPE 2020-03-05

                $all_teams[$squadra_id]['Denominazione'] = $squadra;
                $all_teams[$squadra_id]['AnnoSportivo'][$anno_sportivo] = $anno_sportivo;
                $all_teams[$squadra_id]['NumeroStagioni'] = count($all_teams[$squadra_id]['AnnoSportivo']);

                $all_teams[$squadra_id]['Tipo'] = $tipo;
                $all_teams[$squadra_id]['Sesso'] = $sesso;
                $all_teams[$squadra_id]['Campionato'][$row['Campionato']] = $row['Campionato'];

                $all_teams[$squadra_id]['NumeroTornei'] = count($all_teams[$squadra_id]['Campionato']);

                if (!isset($all_teams[$squadra_id]['LastYear'])) {
                    $all_teams[$squadra_id]['LastYear'] = $row['AnnoSportivo'];
                } else {
                    if ($all_teams[$squadra_id]['LastYear'] <= $row['AnnoSportivo']) {
                        $all_teams[$squadra_id]['LastYear'] = $row['AnnoSportivo'];
                    }
                }
            }
        }
        //------------------------------------------------------------------------------
        // Questa query ci serve per gli atleti
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
                        ,Campionati.Tipo
                        ,Campionati.SessoTipo
                        ,SquadreCampionati.GironeCampionato
                        ,(SELECT GironiCampionati.Descrizione FROM GironiCampionati WHERE GironiCampionati.GironeCampionato = SquadreCampionati.GironeCampionato AND GironiCampionati.Campionato = SquadreCampionati.Campionato) as NomeGirone
                        ,Campionati.AnnoSportivo
                       

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

                        Campionati.sport = "CALCIO"
                        AND
                        Campionati.scuola = 0
                        AND 
                        Campionati.AnnoSportivo <= "' . $last_year . '"
                        
                 
                        ORDER BY `Campionati`.`AnnoSportivo`  DESC';




        $result = mysql_query($sql);


        if (mysql_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysql_fetch_assoc($result)) {
                //$all[] = $row;

                $squadra_id = $row['Squadra'];
                $squadra = $row['Denominazione'];
                $calendario = $row['Calendario'];

                $all_athletes[$row['Atleta']]['Nominativo'] = $row['Nominativo'];
                $all_athletes[$row['Atleta']]['Calendario'][$calendario]['Squadra'] = $squadra_id;

                if ($row['AnnoSportivo'] == $last_year) {
                    $now_athletes['Squadra'][$row['Squadra']]['Atleta'][$row['Atleta']]['Nominativo'] = $row['Nominativo'];
                    $now_athletes['Squadra'][$row['Squadra']]['Denominazione'] = $squadra;
                }
            }
        }

        $this->vincite_tornei($all_teams, $all_calendar, $all_athletes, $now_athletes, $last_year, $back);


        exit;
        /*
          $time2 = time();
          $tot = $time2 - $time1;
          print "[*] Generato Ranking squadre in: $tot secondi<br>";
          exit;

         * 
         *          */
    }









    private function vincite_tornei($all_teams, $all_calendar, $all_athletes, $now_athletes, $last_year, $back, $max)
    {

        /* non commentare o cancellare questa creazione directory */
        $dir_save = APP . "webroot/files/ranking_teams";

        if (!is_dir($dir_save))
            mkdir($dir_save);

        /*         * ************************************************************************* */
        /* $file = 'all_teams.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($all_teams, true)); */
        /*         * ************************************************************************* */




        $half_win = array(); /* qui inserirò , per ogni squadra, i punti classifica delle squadre in base al girone */
        //$half_win_italiana = array(); /* qui inserirò , per ogni squadra, i punti classifica delle squadre in base al girone Italiana*/

        foreach ($all_calendar as $key_calendar => $result) /* assegno alle squadre le vincite delle giornate del calendario */ {
            $key_s = array_keys($result['Squadra']);

            $team_1_goal = $result['Squadra'][$key_s[0]]['Goal'] + $result['Squadra'][$key_s[1]]['Autogoal'];

            $team_2_goal = $result['Squadra'][$key_s[1]]['Goal'] + $result['Squadra'][$key_s[0]]['Autogoal'];

            $all_calendar[$key_calendar]['Squadra'][$key_s[0]]['Tot'] = $team_1_goal;
            $all_calendar[$key_calendar]['Squadra'][$key_s[1]]['Tot'] = $team_2_goal;

            $all_calendar[$key_calendar]['Squadra']['Win'] = "*";
            $all_calendar[$key_calendar]['Squadra']['Lose'] = "*";
            $all_calendar[$key_calendar]['Squadra']['Draw'] = "*";

            if ($team_1_goal > $team_2_goal) /* vince la squadra 1 */ {
                $all_calendar[$key_calendar]['Squadra']['Win'] = $key_s[0];
                $all_calendar[$key_calendar]['Squadra']['Lose'] = $key_s[1];

                $all_calendar[$key_calendar]['ResultTeam'][$key_s[0]] = 0;
                $all_calendar[$key_calendar]['ResultTeam'][$key_s[1]] = 0;

                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[0]] = 3;
                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[1]] = -1;

                $half_win[$result['Squadra'][$key_s[0]]['Girone']][$key_s[0]] += 3;
            }
            if ($team_1_goal < $team_2_goal) /* vince la squadra 2 */ {
                $all_calendar[$key_calendar]['Squadra']['Win'] = $key_s[1];
                $all_calendar[$key_calendar]['Squadra']['Lose'] = $key_s[0];

                $all_calendar[$key_calendar]['ResultTeam'][$key_s[0]] = 0;
                $all_calendar[$key_calendar]['ResultTeam'][$key_s[1]] = 0;

                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[0]] = -1;
                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[1]] = 3;

                $half_win[$result['Squadra'][$key_s[1]]['Girone']][$key_s[1]] += 3;
            }
            if ($team_1_goal == $team_2_goal) /* Pareggio */ {
                $all_calendar[$key_calendar]['Squadra']['Draw'] = array($key_s[0], $key_s[1]);

                $all_calendar[$key_calendar]['ResultTeam'][$key_s[0]] = 0;
                $all_calendar[$key_calendar]['ResultTeam'][$key_s[1]] = 0;

                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[0]] = 1;
                $all_calendar[$key_calendar]['ResultIndividual'][$key_s[1]] = 1;

                $half_win[$result['Squadra'][$key_s[0]]['Girone']][$key_s[0]] += 1;
                $half_win[$result['Squadra'][$key_s[1]]['Girone']][$key_s[1]] += 1;
            }
        }





        /*         * ************************************************************************* */
        /*  $file = 'all_half.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($half_win, true));


          $file = 'all_calendar.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($all_calendar, true)); */
        /*         * ************************************************************************* */




        $half_max = array();
        $half_team_win = array();

        foreach ($half_win as $key_half => $win_half) {
            $max = max($win_half);
            $key = array_search($max, $win_half);
            $half_max[$key_half][$key] = $max;

            $half_team_win[$key] += 10; /* vincitrice del girone */
        }




        /*         * ************************************************************************* */
        /* $file = 'half_max.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($half_max, true));

          $file = 'half_team_win.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($half_team_win, true)); */
        /*         * ************************************************************************* */





        foreach ($all_teams as $key_team => $info_team) {
            foreach ($info_team['Calendario'] as $key_calendar => $calendar) {
                $squadra_individuale = array();

                $squadra_individuale['Squadra'] = $all_calendar[$key_calendar]['ResultTeam'][$key_team];
                //$squadra_individuale['Individuale'] = $all_calendar[$key_calendar]['ResultIndividual'][$key_team];
                $all_teams[$key_team]['Calendario'][$key_calendar] = $squadra_individuale;
            }
        }



        /*         * ************************************************************************* */
        /* $file = 'all_teams.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($all_teams, true)); */
        /*         * ************************************************************************* */




        $points = array();

        foreach ($all_teams as $key_team => $squadra) {
            if ($squadra['LastYear'] == $last_year) {
                $points[$key_team]['Denominazione'] = $squadra['Denominazione'];
                $points[$key_team]['NumeroStagioni'] = $squadra['NumeroStagioni'];
                $points[$key_team]['NumeroTornei'] = $squadra['NumeroTornei'];
                $points[$key_team]['Tipo'] = $squadra['Tipo'];
                $points[$key_team]['Sesso'] = $squadra['Sesso'];
                $points[$key_team]['PuntiSquadra'] = 0;
                /* $points[$key_team]['PuntiIndividuali'] = 0; */
                foreach ($squadra['Calendario'] as $key_calendar => $results) {
                    $points[$key_team]['PuntiSquadra'] += $results['Squadra'];
                }

                $points[$key_team]['PuntiSquadra'] += $half_team_win[$key_team];
            }
        }





        /*         * ************************************************************************* */
        /* $file = 'points.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($points, true)); */
        /*         * ************************************************************************* */



        foreach ($all_athletes as $key_athlete => $single_athlete) {
            $all_athletes[$key_athlete]['TotalPoints'] = 0;
            $all_athletes[$key_athlete]['PartiteGiocate'] = count($all_athletes[$key_athlete]['Calendario']);

            foreach ($single_athlete['Calendario'] as $key_calendario => $calendario) {
                $team = $calendario['Squadra'];

                $points_individual = $all_calendar[$key_calendario]['ResultIndividual'][$team];

                $all_athletes[$key_athlete]['Calendario'][$key_calendario]['Points'] = $points_individual;

                unset($all_athletes[$key_athlete]['Calendario'][$key_calendario]);

                $all_athletes[$key_athlete]['TotalPoints'] += $points_individual;
            }

            unset($all_athletes[$key_athlete]['Calendario']);
        }




        foreach ($now_athletes['Squadra'] as $key_team => $atleti_squadra) {
            foreach ($atleti_squadra['Atleta'] as $key_atleta => $atleta) {
                $points_athlete = $all_athletes[$key_atleta]['TotalPoints'];
                $match = $all_athletes[$key_atleta]['PartiteGiocate'];
                $now_athletes['Squadra'][$key_team]['Atleta'][$key_atleta]['Punti'] = $points_athlete;
                $now_athletes['Squadra'][$key_team]['Atleta'][$key_atleta]['Partite'] = $match;
            }

            $now_athletes['Squadra'][$key_team]['Componenti'] = count($now_athletes['Squadra'][$key_team]['Atleta']);
        }





        foreach ($now_athletes['Squadra'] as $key_team => $team) {
            $componenti = 1; /* valore default */

            if (isset($team['Componenti']))
                $componenti = $team['Componenti'];

            $points[$key_team]['Componenti'] = $componenti;

            $points[$key_team]['PunteggiIndividuali'] = 0;
            foreach ($team['Atleta'] as $single_athlete) {
                $points[$key_team]['PunteggiIndividuali'] += ($single_athlete["Punti"] + $single_athlete["Partite"]);
            }
        }




        /* sorting points */

        $ranking_order = array();

        foreach ($points as $key_team => $team) {

            $num_componenti = $team["Componenti"];


            $punti_ranking = round(($team["NumeroStagioni"] + $team["NumeroTornei"] + $team["PuntiSquadra"]) + ($team["PunteggiIndividuali"] / $num_componenti), 2);

            $points[$key_team]['PuntiRanking'] = $punti_ranking;
            $points[$key_team]['Squadra'] = $key_team;

            $team['PuntiRanking'] = $punti_ranking;
            $team['Squadra'] = $key_team;
            $ranking_order[$key_team] = $team;
        }




        /*         * ************************************************************************* */
        /* $file = 'ranking_squadre.txt';

          if (file_exists($dir_save . "/" . $file))
          unlink($dir_save . "/" . $file);

          file_put_contents($dir_save . "/" . $file, print_r($ranking_order, true)); */
        /*         * ************************************************************************* */





        if ($back == $max) {
            if (file_exists($dir_save . "/temp.txt")) {
                unlink($dir_save . "/temp.txt");
            }
        }




        $temp = array();

        if (file_exists($dir_save . "/temp.txt")) {
            $temp = unserialize(file_get_contents($dir_save . "/temp.txt"));

            foreach ($ranking_order as $key_rank => $rank) {
                $temp[$key_rank] = $rank;
            }

            unlink($dir_save . "/temp.txt");

            file_put_contents($dir_save . "/temp.txt", serialize($temp));
        } else {
            file_put_contents($dir_save . "/temp.txt", serialize($ranking_order));
        }




        if ($back == "0" || $back == 0) {

            $ranking_order = array();

            foreach ($temp as $single) {
                $ranking_order[] = $single;
            }




            $len_array_to_order = count($ranking_order);

            do {
                $temp = array();

                $switch = false;
                for ($i = 0; $i < ($len_array_to_order - 1); $i++) {
                    $index_1 = $ranking_order[$i];
                    $index_2 = $ranking_order[$i + 1];

                    if ($index_1['PuntiRanking'] < $index_2['PuntiRanking']) {
                        $switch = true;
                        $ranking_order[$i] = $index_2;
                        $ranking_order[$i + 1] = $index_1;
                    }
                }

                if (!$switch)
                    break;
            } while (true);

            //            echo " FINE *** " . count($ranking_order) . " elementi";
            echo " FINE *** ";

            /* Qui c'è il ranking */
            $file = 'ranking_order.txt';

            if (file_exists($dir_save . "/" . $file))
                unlink($dir_save . "/" . $file);

            file_put_contents($dir_save . "/" . $file, serialize($ranking_order));


            unlink($dir_save . "/temp.txt");
        }
    }
















    function admin_filters()
    {

        $this->layout = "ajax";

        if (!empty($this->data)) {

            $this->Session->write($this->name . ".searchFilters", $this->data['searchFilters']);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }
    }

    function admin_searchSquadra()
    {

        $this->layout = "ajax";

        $squadre = $this->Squadre->find('all', array(

            'conditions' =>
                array(
                    'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
                ),
            'order' => 'Squadre.Denominazione ASC',
            'limit' => '15'

        ));

        $ret = array();

        foreach ($squadre as $squadra) {

            $tmp['id'] = $squadra['Squadre']['Squadra'];
            $tmp['label'] = $squadra['Squadre']['Denominazione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }

    function admin_searchSquadraCampionato()
    {

        $this->layout = "ajax";

        $squadrec = $this->SquadreCampionati->find('all', array(

            'conditions' =>
                array(
                    'Squadre.Denominazione LIKE' => $_GET['term'] . '%'


                ),
            'limit' => '15'

        ));

        $ret = array();

        foreach ($squadrec as $squadrac) {

            $tmp['id'] = $squadrac['SquadreCampionati']['SquadraCampionato'];
            $tmp['label'] = $squadrac['Squadre']['Denominazione'] . " " . $squadrac['Campionati']['Nome'] . " " . $squadrac['Campionati']['AnnoSportivo'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }

    function admin_search()
    {

        $this->layout = "ajax";

        if (!empty($this->data)) {

            $this->Session->write($this->name . ".searchData", $this->data);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }

        if ($this->Session->check($this->name . ".searchData", $this->data)) {

            $this->data = $this->Session->read($this->name . ".searchData");
        }
    }


    //GIUSEPPE 03/10/2016 -----------------------------

    function read_champ_cat_database()
    {
        $res = mysql_query("SELECT * FROM TipoSport");

        $arraySport = array();

        while ($row = mysql_fetch_assoc($res)) {

            $arraySport[] = $row['sport'];
        }


        return $arraySport;
    }


    //------------------------------------------------

    function admin_add()
    {

        $this->layout = "ajax";

        if (!empty($this->data)) {


            //GIUSEPPE 03/10/2016 -----------------------------

            $sport_id = $this->data['Squadre']['sport'];

            $result = $this->read_champ_cat_database();

            $this->data['Squadre']['sport'] = $result[$sport_id];

            $this->data['Squadre']['id_sport'] = $sport_id;

            //print_r($this->data);
            //------------------------------------------------



            $this->Squadre->set($this->data);

            if ($this->Squadre->save()) {

                $ADD_OK = true;

                if ($this->__adminUploadFile('squadra_id', $this->Squadre->id) == true) {

                    $ADD_OK = true;
                }


                if ($ADD_OK) {

                    //GIUSEPPE 2023-07-28 ----------------------------------------------  
                    //                    $this->set('result', 'Squadra aggiunta correttamente!');
                    //                    $this->render('/backend/ajaxResult');

                    $this->set('element_id', $this->Squadre->id);
                    //------------------------------------------------------------------
                }
            }
        }
    }


    function admin_edit($id)
    {

        $this->layout = "ajax";

        if (empty($this->data)) {

            $this->data = $this->Squadre->find('first', array('conditions' => array($this->Squadre->primaryKey => $id)));

            $this->Squadre->set($this->data);
        } else {

            //GIUSEPPE 03/10/2016 -----------------------------
            //print_r($this->data);
            // GIUSEPPE 2023-07-28 ------------------------------------

            $this->smistaFileBas($this->data, $id);
            // --------------------------------------------------------

            $sport_id = $this->data['Squadre']['sport'];

            $result = $this->read_champ_cat_database();

            $this->data['Squadre']['sport'] = $result[$sport_id];

            $this->data['Squadre']['id_sport'] = $sport_id;

            //echo "----------------------------------------";
            //print_r($this->data);
            //------------------------------------------------

            $this->data['Squadre'][$this->Squadre->primaryKey] = $id;

            $this->Squadre->set($this->data);

            $ADD_OK = true;

            if ($this->__adminUploadFile('squadra_id', $id) == true) {

                $ADD_OK = false;
            }

            if ($this->Squadre->save()) {

                if ($ADD_OK) {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }


    // GIUSEPPE 2023-07-28 ------------------------------------
    private function smistaFileBas(&$data, $id)
    {

        $MEMORANDUM_ARTICLES_ASSOCIATION = $data['Squadre']['MEMORANDUM_ARTICLES_ASSOCIATION'];
        $AFFILIATION_REQUEST = $data['Squadre']['AFFILIATION_REQUEST'];
        $PRESIDENT_ID = $data['Squadre']['PRESIDENT_ID'];

        $squadra = $this->select_sql("SELECT * FROM Squadre WHERE Squadra = '{$id}'")[0];

        if (!file_exists("./files/BAS")) {
            mkdir("./files/BAS", 0775, true);
        }

        if (!file_exists("./files/BAS/{$id}")) {
            mkdir("./files/BAS/{$id}", 0775, true);
        }

        if ($MEMORANDUM_ARTICLES_ASSOCIATION['name'] !== "") {
            $name_tmp = $MEMORANDUM_ARTICLES_ASSOCIATION['tmp_name'];
            //            $name = explode("/", $name_tmp)[2];
            $name = "{$id}_MEMORANDUM";
            unlink("./files/BAS/{$id}/{$name}.pdf");
            copy($name_tmp, "./files/BAS/{$id}/{$name}.pdf");
            $data['Squadre']['MEMORANDUM_ARTICLES_ASSOCIATION'] = "./files/BAS/{$id}/{$name}.pdf";
        } else {
            $data['Squadre']['MEMORANDUM_ARTICLES_ASSOCIATION'] = $squadra['MEMORANDUM_ARTICLES_ASSOCIATION'];
        }



        if ($AFFILIATION_REQUEST['name'] !== "") {
            $name_tmp = $AFFILIATION_REQUEST['tmp_name'];
            //            $name = explode("/", $name_tmp)[2];
            $name = "{$id}_AFFILIATION";
            unlink("./files/BAS/{$id}/{$name}.pdf");
            copy($name_tmp, "./files/BAS/{$id}/{$name}.pdf");
            $data['Squadre']['AFFILIATION_REQUEST'] = "./files/BAS/{$id}/{$name}.pdf";
        } else {
            $data['Squadre']['AFFILIATION_REQUEST'] = $squadra['AFFILIATION_REQUEST'];
        }



        if ($PRESIDENT_ID['name'] !== "") {
            $name_tmp = $PRESIDENT_ID['tmp_name'];
            $name = explode("/", $name_tmp)[2];
            $name = "{$id}_PRESIDENT";

            unlink("./files/BAS/{$id}/{$name}.pdf");
            copy($name_tmp, "./files/BAS/{$id}/{$name}.pdf");
            $data['Squadre']['PRESIDENT_ID'] = "./files/BAS/{$id}/{$name}.pdf";
        } else {
            $data['Squadre']['PRESIDENT_ID'] = $squadra['PRESIDENT_ID'];
        }
    }

    // --------------------------------------------------------


    function admin_almanacco_index()
    {

        $this->layout = "timmybox";

        //Anno sportivo corrente
        $anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
        $anno = $anno[key($anno)];

        //Campionati anno sportivo corrente
        $campionati = $this->Campionati->find('list', array(

            'fields' => array(
                'Campionati.Campionato',
                'Campionati.Nome',

            ),
            'conditions' => array(

                'Campionati.AnnoSportivo' => $anno,

            ),
            'order' => 'Campionati.Nome ASC'

        ));

        $this->set('campionati', $campionati);
    }

    function admin_almanacco()
    {

        $this->layout = 'ajax';

        $campionati_arr = array();
        foreach ($this->data['Campionati'] as $camp) {
            $campionati_arr[] = $camp;
        }

        //Anno sportivo corrente
        $anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
        $anno = $anno[key($anno)];

        //debug($anno);

        //Campionati anno sportivo corrente
        $campionati = $this->Campionati->find('all', array(

            'fields' => array(
                'Campionati.Campionato',
                'Campionati.Nome',

            ),
            'conditions' => array(

                'Campionati.AnnoSportivo' => $anno,
                'Campionati.Campionato' => $campionati_arr,

            ),

        ));

        //debug($campionati);

        $squadre = array();
        $almanacco = array();

        foreach ($campionati as $campionato) {

            $gironi = $campionato['Half'];
            foreach ($gironi as $girone) {

                $squadre_campionato = $this->SquadreCampionati->find('all', array(
                    'conditions' => array(
                        'SquadreCampionati.GironeCampionato' => $girone['GironeCampionato'],
                    ),
                    'order' => 'SquadraNome ASC',
                ));

                foreach ($squadre_campionato as $squadra_campionato) {

                    $almanacco[$campionato['Campionati']['Campionato']]['InfoCampionato'] = $campionato['Campionati'];
                    $almanacco[$campionato['Campionati']['Campionato']]['Gironi'][$girone['GironeCampionato']]['InfoGirone'] = $girone;

                    //Dati squadra
                    $data = $this->Squadre->findBySquadra($squadra_campionato['Squadre']['Squadra']);

                    //Ripartizione upload

                    $uploads = array();
                    foreach ($data['Upload'] as $upload) {
                        if ($upload['tag'] == '')
                            $upload['tag'] = 'Gallery';
                        $uploads[$upload['tag']][] = $upload;
                    }

                    unset($data['Upload']);

                    $data['Upload'] = $uploads;

                    //

                    //Rosa squadra						

                    $data['Atleti'] = $this->Yearbook->find('all', array(
                        'conditions' => array(
                            'Yearbook.SquadraCampionato' => $squadra_campionato['SquadreCampionati']['SquadraCampionato'],
                            'Yearbook.AnnoSportivo' => $anno,
                        ),
                    ));

                    $almanacco[$campionato['Campionati']['Campionato']]['Gironi'][$girone['GironeCampionato']]['Squadre'][$squadra_campionato['Squadre']['Squadra']] = $data;
                }
            }
        }

        $this->set('almanacco', $almanacco); //sostituire $almanacco_prova con $almanacco. Prova : 1 solo campionato.
        $this->set('anno', $anno);
    }

    /*
     * TAB
     * 
     * 1 = Squadra
     * 2 = Albo d'oro
     * 3 = Statistiche
     * 4 = Galleria
     * tab = edit = modalità modifica
     * 
     */

    function teams_edit($squadra, $tab = 1)
    {

        $this->layout = "content";

        $this->login_site = true;

        $data_users = $this->Session->read('Login.data');

        if ($data_users['is_atleta']) {

            $data = $this->Yearbook->find('count', array(
                'conditions' => array(
                    'Yearbook.Atleta' => $data_users['id'],
                    'Yearbook.isAdmin' => 1,
                    'SquadreCampionati.Squadra' => $squadra,
                ),
                'group' => array('SquadreCampionati.Squadra'),
            ));

            if ($data == 0)
                $this->redirect('/area/riservata');
        } else {

            $this->redirect('/area/riservata');
        }

        $squadre_campionati = $this->SquadreCampionati->find('all', array(

            'fields' => array('Campionati.AnnoSportivo'),
            'conditions' => array(
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'order' => 'Campionati.AnnoSportivo DESC',

        ));

        $elements = array(

            1 => 'squadra_edit',
            2 => 'squadra_upload',

        );

        if (!isset($elements[$tab]))
            $elements[$tab] = 'squadra_edit';

        $this->set('element', $elements[$tab]);

        $this->set('squadra', $this->Squadre->findBySquadra($squadra));
        $this->set('anni', Set::combine($squadre_campionati, '{n}.Campionati.AnnoSportivo', '{n}.Campionati.AnnoSportivo'));

        $data = $this->Yearbook->find('all', array(
            'conditions' => array(
                'Yearbook.Atleta' => $this->Session->read('Login.data.id'),
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'group' => array('SquadreCampionati.Squadra'),
        ));


        if (empty($data))
            $this->redirect('/area/riservata');
    }

    function teams($squadra, $tab = 1)
    {

        $this->layout = "content";

        $this->login_site = true;

        $data_users = $this->Session->read('Login.data');

        if ($data_users['is_atleta']) {

            $data = $this->Yearbook->find('count', array(
                'conditions' => array(
                    'Yearbook.Atleta' => $data_users['id'],
                    'Yearbook.isAdmin' => 1,
                    'SquadreCampionati.Squadra' => $squadra,
                ),
                'group' => array('SquadreCampionati.Squadra'),
            ));

            if ($data == 0)
                $this->redirect('/area/riservata');
        } else {

            $this->redirect('/area/riservata');
        }

        $squadre_campionati = $this->SquadreCampionati->find('all', array(

            'fields' => array('Campionati.AnnoSportivo'),
            'conditions' => array(
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'order' => 'Campionati.AnnoSportivo DESC',

        ));

        $team = $this->Squadre->findBySquadra($squadra);

        $uploads = array();
        foreach ($team['Upload'] as $upload) {
            if ($upload['tag'] == '')
                $upload['tag'] = 'Gallery';
            $uploads[$upload['tag']][] = $upload;
        }

        $this->set('squadra', $team);
        $this->set('anni', Set::combine($squadre_campionati, '{n}.Campionati.AnnoSportivo', '{n}.Campionati.AnnoSportivo'));

        $data = $this->Yearbook->find('all', array(
            'conditions' => array(
                'Yearbook.Atleta' => $this->Session->read('Login.data.id'),
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'group' => array('SquadreCampionati.Squadra'),
        ));

        $elements = array(

            1 => 'squadra',
            2 => 'albo-trofei',
            3 => 'statistiche',
            4 => 'galleria',

        );

        if (!isset($elements[$tab]))
            $tab = 1;

        switch ($tab) {

            case 1:

                if (empty($team['Squadre']['Storia']) && empty($uploads['Squadra']))
                    $tab = 3;

                break;

            case 2:

                if (empty($team['SquadreAlbo']) && empty($uploads['Trofeo'])) {
                    if (empty($team['Squadre']['Storia']) && empty($uploads['Squadra']))
                        $tab = 3;
                    else
                        $tab = 1;
                }


                break;

            case 3:
                $tab = 3;
                break;

            case 4:

                if (empty($uploads['Gallery'])) {
                    if (empty($team['Squadre']['Storia']) && empty($uploads['Squadra']))
                        $tab = 3;
                    else
                        $tab = 1;
                }


                break;
        }

        $this->set('element', $elements[$tab]);

        if (empty($data))
            $this->redirect('/area/riservata');
    }

    function saveStory($squadra)
    {

        $this->layout = "ajax";

        $this->login_site = true;

        //debug($_POST['storia']);

        if ($this->Squadre->updateAll(array('Squadre.Storia' => "\"" . $_POST['storia'] . "\""), array('Squadre.Squadra' => $squadra))) {

            $add = 1;
        } else {

            $add = 0;
        }

        $this->set('result', json_encode(array('add' => $add)));
        $this->render('/backend/ajaxResult');
    }

    function saveUpload($id)
    {

        $this->layout = "ajax";

        $this->login_site = true;

        $exts = array('image/jpeg', 'image/png');
        $dim = '512000';

        if ($this->__adminUploadFile('squadra_id', $id, array('exts' => $exts, 'maxsize' => $dim)) == true) {

            $this->redirect('/squadres/teams_edit/' . $id . '/2#edit');
        } else {

            $this->redirect('/squadres/teams_edit/' . $id . '/2#error');
        }
    }


    function roster($squadra, $anno = "2016")
    {

        $this->autoRender = false;
        $squadre_campionati = $this->SquadreCampionati->find('all', array(

            'fields' => array('Campionati.AnnoSportivo'),
            'conditions' => array(
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'order' => 'Campionati.AnnoSportivo DESC',

        ));

        $squadra = $this->Squadre->findBySquadra($squadra);


        $stagioni = $this->SquadreCampionati->find('all', array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra']), 'order' => 'Campionati.AnnoSportivo DESC'));
        $first_stagione = $stagioni[0]['SquadreCampionati'];

        foreach ($stagioni as $stagione) {

            if ($stagione['SquadreCampionati']['AnnoCampionato'] == $anno)
                $first_stagione = $stagione['SquadreCampionati'];
        }

        $roster = $this->requestAction("/sections/getFilter/" . $first_stagione['Campionato'] . "/" . $first_stagione['GironeCampionato'] . "/squadra/" . $first_stagione['SquadraCampionato']);
        print $roster;
    }

    function teams_detail($squadra, $nome_squadra = null, $tab = 1)
    {

        $this->layout = "content";

        $squadre_campionati = $this->SquadreCampionati->find('all', array(

            'fields' => array('Campionati.AnnoSportivo'),
            'conditions' => array(
                'SquadreCampionati.Squadra' => $squadra,
            ),
            'order' => 'Campionati.AnnoSportivo DESC',

        ));

        $squadra = $this->Squadre->findBySquadra($squadra);

        $uploads = array();
        foreach ($squadra['Upload'] as $upload) {
            if ($upload['tag'] == '')
                $upload['tag'] = 'Gallery';
            $uploads[$upload['tag']][] = $upload;
        }

        $this->set('anni', Set::combine($squadre_campionati, '{n}.Campionati.AnnoSportivo', '{n}.Campionati.AnnoSportivo'));

        //Check if tab not have information

        $elements = array(

            1 => 'squadra_site',
            2 => 'albo-trofei_site',
            3 => 'statistiche_site',
            4 => 'galleria_site',

        );


        $campionati = $this->SquadreCampionati->find('count', array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra'])));

        $squadra['Info']['Campionati'] = $campionati;


        $stagioni = $this->SquadreCampionati->find('all', array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra']), 'order' => 'Campionati.AnnoSportivo DESC'));
        $first_stagione = $stagioni[0]['SquadreCampionati'];
        //					print_r($stagioni);

        $tmp = array();
        foreach ($stagioni as $stagione) {
            $tmp[$stagione['Campionati']['AnnoSportivo']] = $stagione['Campionati']['AnnoSportivo'];
        }
        unset($stagioni);
        $stagioni = $tmp;

        $string_stagioni = '';

        $stagioni = array_merge($stagioni);

        $squadra['Info']['StagioniList'] = $stagioni;

        $squadra['Info']['Stagioni'] = count($stagioni);
        $this->set('squadra', $squadra);



        $roster = $this->requestAction("/sections/getFilter/" . $first_stagione['Campionato'] . "/" . $first_stagione['GironeCampionato'] . "/squadra/" . $first_stagione['SquadraCampionato']);


        $this->set('roster', $roster);
        //				if(!isset($elements[$tab])) $tab = 1;				

        switch ($tab) {

            case 1:

                if (empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
                    $tab = 3;

                break;

            case 2:

                if (empty($squadra['SquadreAlbo']) && empty($uploads['Trofeo'])) {
                    if (empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
                        $tab = 3;
                    else
                        $tab = 1;
                }


                break;

            case 3:
                $tab = 3;
                break;

            case 4:

                if (empty($uploads['Gallery'])) {
                    if (empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
                        $tab = 3;
                    else
                        $tab = 1;
                }


                break;
        }

        //Back button option
        if (isset($_GET['option'])) {
            $params = explode('-', $_GET['option']);
            $link = '/lista';
            foreach ($params as $p) {
                $link .= '/' . $p;
            }
            $tipo = (isset($params[0]) ? $params[0] : 0);
            $sesso = (isset($params[1]) ? $params[1] : 0);
        } else {
            $link = '/lista/0/0';
            $tipo = 0;
            $sesso = 0;
        }




        $this->set('tipo', $tipo);
        $this->set('sesso', $sesso);
        $this->set('back', $link);
        $this->set('element', $elements[$tab]);
    }

    function brendiCrist($id_squadra, $id_campionato)
    {
        $this->autoRender = false;
        $campionati = $this->SquadreCampionati->find('first', array('conditions' => array('SquadreCampionati.Squadra' => $id_squadra, 'SquadreCampionati.Campionato' => $id_campionato)));
        $squadra_campionato = $campionati['SquadreCampionati']['SquadraCampionato'];
        $girone = $campionati['Half']['GironeCampionato'];
        print $this->requestAction("/sections/getFilter/" . $id_campionato . "/" . $girone . "/squadra/" . $squadra_campionato);
        exit;
    }

    function getChampFromYear($squadra, $anno)
    {

        $this->layout = "ajax";

        $squadre_campionati = $this->SquadreCampionati->find('all', array(

            'fields' => array('Campionati.Campionato', 'Campionati.Nome', 'Campionati.AnnoSportivo', 'Campionati.InCorso'),
            'conditions' => array(
                'SquadreCampionati.Squadra' => $squadra,
                'Campionati.AnnoSportivo' => $anno,
                'Campionati.group_id' => 1,
            ),
            'order' => 'Campionati.AnnoSportivo DESC',

        ));

        $anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
        $anno = $anno[key($anno)];

        foreach ($squadre_campionati as $id => $camp) {
            if ($camp['Campionati']['AnnoSportivo'] == $anno && $camp['Campionati']['InCorso'] != 'Si') {
                unset($squadre_campionati[$id]);
            }
        }

        $result = Set::combine($squadre_campionati, '{n}.Campionati.Campionato', '{n}.Campionati.Nome');

        $ret = array();

        foreach ($result as $id => $nome) {

            $ret[] = array(

                'id' => $id,
                'value' => $nome,

            );
        }

        $this->set('result', json_encode($ret));
        $this->render('/backend/ajaxResult');
    }





    function getSquadraCampionatoFromCampionato($campionato, $squadra)
    {

        $this->layout = "ajax";

        $squadre_campionati = $this->SquadreCampionati->find('first', array(

            'fields' => array('SquadreCampionati.SquadraCampionato', 'SquadreCampionati.GironeCampionato'),
            'conditions' => array(
                'SquadreCampionati.Campionato' => $campionato,
                'SquadreCampionati.Squadra' => $squadra,
            ),

        ));

        $this->set('result', json_encode(array(

            'squadra' => $squadre_campionati['SquadreCampionati']['SquadraCampionato'],
            'girone' => $squadre_campionati['SquadreCampionati']['GironeCampionato'],

        )));
        $this->render('/backend/ajaxResult');
    }

    //Albo d'oro

    function newAlbo()
    {

        $this->layout = "ajax";

        $data = $_POST;

        if (isset($data['id'])) {
            $this->SquadreAlbo->read(null, $data['id']);
            $this->SquadreAlbo->set('Campionato', $data['Campionato']);
            $this->SquadreAlbo->set('Posizione', $data['Posizione']);
            $this->SquadreAlbo->set('Squadra', $data['Squadra']);
            if ($this->SquadreAlbo->save()) {
                $ok = 0;
            } else {
                $ok = 1;
            }
        } else {
            $this->SquadreAlbo->create();
            $this->SquadreAlbo->set('Campionato', $data['Campionato']);
            $this->SquadreAlbo->set('Posizione', $data['Posizione']);
            $this->SquadreAlbo->set('Squadra', $data['Squadra']);
            if ($this->SquadreAlbo->save()) {
                $ok = 0;
            } else {
                $ok = 1;
            }
        }

        $this->set('result', json_encode(array('error' => $ok)));
        $this->render('/backend/ajaxResult');
    }

    function deleteAlbo($albo_delete)
    {

        $this->layout = "ajax";

        if ($this->SquadreAlbo->delete($albo_delete)) {
            $ok = 1;
        } else {
            $ok = 0;
        }

        $this->set('result', json_encode(array('delete' => $ok)));
        $this->render('/backend/ajaxResult');
    }

    function updateAlboTr($squadra_id)
    {
        $this->layout = "ajax";
        $this->set('squadra', $this->Squadre->findBySquadra($squadra_id));
        $this->render('/squadres/update_albo');
    }

    function edit_yearbook()
    {

        $this->layout = "ajax";

        $data = $_POST['data'];

        foreach ($data as $yearbook => $dati) {
            $this->Yearbook->read(null, $yearbook);
            $this->Yearbook->set('Ruolo', $dati['ruolo']);
            $this->Yearbook->set('NumeroMaglia', $dati['maglia']);
            $this->Yearbook->save();
        }

        $this->set('result', json_encode(array('delete' => '1')));
        $this->render('/backend/ajaxResult');
    }

    // $squadre_campionati = $this->SquadreCampionati->find('all', array(

    // 'fields' => array('Campionati.Campionato','Campionati.AnnoSportivo','SquadreCampionati.SquadraCampionato','Half.GironeCampionato'),
    // 'conditions' => array(
    // 'SquadreCampionati.Squadra' => $squadra,
    // ),
    // 'order' => 'Campionati.AnnoSportivo DESC',

    // ));	

    // GIUSEPPE 2024-05-10 --------------------------------------------
    function admin_id_squadre_id_campionati()
    {
        $this->render('admin_id_squadre_id_campionati');
    }


    function admin_searchSquadre()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $valueSearch = $post['valueSearch'];

        $query = "(SELECT *, Squadra as ID, Denominazione as Nome FROM Squadre WHERE sport = 'CALCIO' AND Denominazione LIKE '%{$valueSearch}%' ORDER BY Denominazione ASC)";
        $res = $this->select_sql($query);

        echo json_encode($res);

        exit();
    }

    // GIUSEPPE 2024-05-23 --------------------------------------------
    function admin_squadre_bas()
    {
        $this->render('admin_squadre_bas');
    }

    function searchSquadreBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);



        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $squadre_group = [];
        $res = [];

        //$typeFile = ["MEMORANDUM_ARTICLES_ASSOCIATION", "AFFILIATION_REQUEST", "PRESIDENT_ID"]; //GIUSEPPE 2024-08-31 --------------------------------------------------------

        $anno_sportivo = $api->annoSportivo();
        $anno = $anno_sportivo['current']['year'];

        $denominazione = $post['denominazione'];
        $manifestazione = $post['manifestazione'];
        $campionatoFilter = "";

        if ($manifestazione !== "0") { //GIUSEPPE 2024-07-04 --------------------------------------------------------
            $campionatoFilter = " AND Campionati.Campionato = '{$manifestazione}'";
        }

        $query = "  SELECT
                        Squadre.*,
                        Squadre.Squadra AS ID,
                        Squadre.Denominazione AS Nome,
                        Campionati.Campionato
                    FROM
                        Squadre
                    INNER JOIN SquadreCampionati ON SquadreCampionati.Squadra = Squadre.Squadra
                    INNER JOIN Campionati ON SquadreCampionati.Campionato = Campionati.Campionato
                    WHERE
                        Campionati.AnnoSportivo = '{$anno}' AND Campionati.PlayLeague = '1' AND Denominazione LIKE '%{$denominazione}%'
                        {$campionatoFilter}  
                    GROUP BY
                        Squadre.Squadra
                    ORDER BY
                        Squadre.Denominazione ASC";

        $resAll = $this->key_select($this->select_sql($query), 'ID');

        $this->write_file("querySquadreBasFilter", $query);

        $this->write_file("key_squadre", array_keys($resAll));

        //GIUSEPPE 2024-07-04 --------------------------------------------------------
        $analizzaIscrizioniSquadreBAS = $this->analizzaIscrizioniSquadreBAS(array_keys($resAll), $anno);
        $res['renew_bas'] = $analizzaIscrizioniSquadreBAS;
        //----------------------------------------------------------------------------

        foreach ($resAll as &$value) {
            $squadre_group[] = $value['ID']; // id delle squadre iscritte ai campionati

            $value['BAS'] = 0;
            $value['AnnoSportivo'] = $anno;




            //GIUSEPPE 2024-08-31 --------------------------------------------------------
            $squadra = $value['ID'];

            $value['stato'] = ['tipo' => 'new', 'client_id' => 0, 'general_counsel_id' => 0]; //rinnovo - nuova iscrizione

            $docs = [
                'MEMORANDUM' => 'MEMORANDUM_ARTICLES_ASSOCIATION',
                'AFFILIATION' => 'AFFILIATION_REQUEST',
                'PRESIDENT' => 'PRESIDENT_ID'
            ];

            $dir = getcwd();

            // analizza la data di upload del file
            foreach ($docs as $doc => $type_doc) {
                $file = "/files/BAS/{$squadra}/{$squadra}_{$doc}.pdf";
                $name = "{$squadra}_{$doc}.pdf";
                $fileDir = $dir . $file;
                $timestamp = filectime($fileDir);
                $res['file_pdf'][$squadra][$type_doc]['date'] = $timestamp == "" ? "" : gmdate("d\\\m\\\Y", $timestamp);
                $res['file_pdf'][$squadra][$type_doc]['file'] = $timestamp == "" ? "" : $file;
                $res['file_pdf'][$squadra][$type_doc]['name'] = $timestamp == "" ? "" : $name;
            }



            //----------------------------------------------------------------------------
        }




        $queryBas = "   SELECT
                            Squadre.*,
                            Squadre.Squadra AS ID,
                            Squadre.Denominazione AS Nome
                        FROM
                            `Squadre`
                        INNER JOIN SquadreBAS ON Squadre.Squadra = SquadreBAS.Squadra
                        WHERE
                            SquadreBAS.AnnoSportivo = '{$anno}'
                        GROUP BY
                        Squadre.Squadra";

        $resBas = $this->key_select($this->select_sql($queryBas), 'ID');



        foreach ($resBas as $key => $v) {
            if (isset($resAll[$key])) {
                $resAll[$key]['BAS'] = 1;
            }
        }

        $res['squadre'] = $resAll;
        $res['group'] = $squadre_group;
        $res['squadre_atleti'] = $this->analizzaSquadreAtleti($squadre_group, $anno);

        echo json_encode($res);

        exit();
    }

    private function analizzaIscrizioniSquadreBAS($keys, $anno) //GIUSEPPE 2024-08-31 --------------------------------------------------------
    {

        $filter = implode(",", $keys);
        $query = "SELECT Squadra, client_id, general_counsel_id FROM `SquadreBAS` WHERE `AnnoSportivo` < '{$anno}' AND Squadra IN ({$filter}) AND client_id > 0";
        $res = $this->key_select($this->select_sql($query), 'Squadra');
        // $this->write_file("analizzaIscrizioniSquadreBAS",$result);
        // $this->write_file("analizzaIscrizioniSquadreBASquery",$query);
        return $res;
    }

    private function analizzaSquadreAtleti($squadre_group, $anno_sportivo)
    {
        $group = implode(", ", $squadre_group);

        $query = "  SELECT
                        SquadraCampionato
                    FROM
                        `SquadreCampionati`
                    INNER JOIN Campionati ON Campionati.Campionato = SquadreCampionati.Campionato

                    WHERE SquadreCampionati.Squadra IN ({$group}) AND Campionati.AnnoSportivo = '{$anno_sportivo}'";

        //        return $query;

        $SquadreCampionati = $this->key_select($this->select_sql($query), 'SquadraCampionato');

        $filter = implode(",", array_keys($SquadreCampionati));

        $goalPartiteQuery = "   SELECT
                                    Atleti.Atleta,
                                    Atleti.Cognome,
                                    Atleti.Nome,
                                    Annuario.SquadraCampionato
                                FROM
                                    `Annuario`
                                INNER JOIN Atleti ON Atleti.Atleta = Annuario.Atleta
                                WHERE
                                    `SquadraCampionato` IN({$filter})
                                        GROUP BY Atleta";

        $goalPartiteAtleti = $this->key_select($this->select_sql($goalPartiteQuery), 'Atleta');

        foreach ($goalPartiteAtleti as &$valueA) {
            $valueA['BAS'] = 0;
            $valueA['squadra'] = $squadra;
            $valueA['anno_sportivo'] = $anno_sportivo;
        }

        $queryAtletiBas = "SELECT * FROM `AtletiBAS` WHERE AtletiBAS.AnnoSportivo = '{$anno_sportivo}' AND AtletiBAS.Squadra IN ({$group})";
        $AtletiBas = $this->select_sql($queryAtletiBas);

        foreach ($AtletiBas as $value) {
            $atleta = $value['Atleta'];
            if (isset($goalPartiteAtleti[$atleta])) {
                $goalPartiteAtleti[$atleta]['BAS'] = 1;

                if ($value['card_id'] == 0) {
                    $goalPartiteAtleti[$atleta]['BAS'] = -1;
                }
            }
        }

        return [
            'Atleti' => $goalPartiteAtleti,
            'Squadra' => [
                "Denominazione" => $squadraInfo['Denominazione'],
                "ID" => $squadraInfo['Squadra'],
                "AnnoSportivo" => $anno_sportivo
            ]
        ];
    }


    public function visualizzaAtletiBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $squadra = $post['squadra'];
        $anno_sportivo = $post['anno_sportivo'];
        $campionato = $post['campionato'];

        $querySquadre = "SELECT * FROM Squadre WHERE Squadra = '{$squadra}'";
        $squadraInfo = $this->select_sql($querySquadre)[0];

        $query = "  SELECT
                        SquadraCampionato
                    FROM
                        `SquadreCampionati`
                    INNER JOIN Campionati ON Campionati.Campionato = SquadreCampionati.Campionato

                    WHERE SquadreCampionati.Squadra = '{$squadra}' AND Campionati.AnnoSportivo = '{$anno_sportivo}' AND Campionati.Campionato = '{$campionato}'";

        $SquadreCampionati = $this->key_select($this->select_sql($query), 'SquadraCampionato');

        $filter = implode(",", array_keys($SquadreCampionati));

        $goalPartiteQuery = "   SELECT
                                    Atleti.Atleta,
                                    Atleti.Cognome,
                                    Atleti.Nome
                                FROM
                                    `Annuario`
                                INNER JOIN Atleti ON Atleti.Atleta = Annuario.Atleta
                                WHERE
                                    `SquadraCampionato` IN({$filter})
                                        GROUP BY Atleta";

        $goalPartiteAtleti = $this->key_select($this->select_sql($goalPartiteQuery), 'Atleta');

        foreach ($goalPartiteAtleti as &$valueA) {
            $valueA['BAS'] = 0;
            $valueA['squadra'] = $squadra;
            $valueA['anno_sportivo'] = $anno_sportivo;
        }

        $queryAtletiBas = "SELECT * FROM `AtletiBAS` WHERE AtletiBAS.AnnoSportivo = '{$anno_sportivo}' AND AtletiBAS.Squadra = '{$squadra}'";
        $AtletiBas = $this->select_sql($queryAtletiBas);

        foreach ($AtletiBas as $value) {
            $atleta = $value['Atleta'];
            if (isset($goalPartiteAtleti[$atleta])) {
                $goalPartiteAtleti[$atleta]['BAS'] = 1;

                if ($value['card_id'] == 0) {
                    $goalPartiteAtleti[$atleta]['BAS'] = -1;
                }
            }
        }

        echo json_encode(['Atleti' => $goalPartiteAtleti, 'Squadra' => ["Denominazione" => $squadraInfo['Denominazione'], "ID" => $squadraInfo['Squadra'], "AnnoSportivo" => $anno_sportivo]]);

        exit();
    }


    function controllaAtletiMaiInseriti() //contolla che non ci siano atleti mai inseriti nella bas
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $elenco = $post['elenco']; // array squadre
        $squadre = $elenco['squadre'];
        $campionati = $elenco['campionati'];
        $s = implode(",", $squadre);
        $c = implode(",", $campionati);
        $s = [];
        foreach ($squadre as $key => $squadra) {
            $campionato = $campionati[$key];
            $stringa = " ( Squadra = '{$squadra}' AND Campionato = '{$campionato}' )";
            $s[] = $stringa;
        }

        $filter = implode(" OR ", $s);

        // cerco tutte le SquadreCampionato
        $query = "SELECT SquadraCampionato, Squadra, Campionato FROM SquadreCampionati WHERE ({$filter}) GROUP BY Squadra, Campionato";
        $res = $this->key_select($this->select_sql($query), 'SquadraCampionato');


        // cerco gli atleti in queste SquadreCampionato
        $SquadreCampionati = array_keys($res);
        $filterSquadreCampionati = implode(',',$SquadreCampionati);
        $query = "  SELECT
                        Atleta,
                        Annuario.SquadraCampionato,
                        SquadreCampionati.Squadra
                    FROM
                        Annuario
                    INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
                    WHERE
                        Annuario.SquadraCampionato IN({$filterSquadreCampionati})";

        $res = $this->key_select($this->select_sql($query),'Atleta');


        // vedo tutti gli atleti inseriti nella bas
        $query = "  SELECT
                        Atleta
                    FROM
                        `AtletiBAS`
                    GROUP BY
                        Atleta
                    ORDER BY
                        `AtletiBAS`.`AnnoSportivo`
                    DESC";
        $atletiBas = $this->select_sql($query);
        foreach ($atletiBas as $key => $value) {
            $atleta = $value['Atleta'];
            unset($res[$atleta]);
        }

        echo json_encode($res);

        exit();
    }

    function contaNoBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $listaSquadre = implode(",", $post['listaSquadre']);
        $anno_sportivo = $post['anno_sportivo'];

        $query = "  SELECT
                            Squadra
                        FROM
                            `AtletiBAS`
                        WHERE
                            AtletiBAS.AnnoSportivo = '{$anno_sportivo}' AND AtletiBAS.Squadra IN ({$listaSquadre}) AND card_id = '0' 
                        GROUP BY Squadra";

        $res = $this->select_sql($query);
        //$res['query'] = $query;
        echo json_encode($res);
        exit();
    }

    function inserisciAtletiBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $atleti = $post['Atleti'];

        //        print_r($atleti);

        foreach ($atleti as $atletaInfo) {
            $atleta = $atletaInfo['Atleta'];
            $squadra = $atletaInfo['squadra'];
            $anno_sportivo = $atletaInfo['anno_sportivo'];

            $query = "  SELECT
                            COUNT(id) AS num
                        FROM
                            AtletiBAS
                        WHERE
                            Atleta = '{$atleta}' AND AnnoSportivo = '{$anno_sportivo}' AND Squadra = '{$squadra}'";

            $queryInfo = $this->select_sql($query)[0];

            if ($queryInfo['num'] == 0) {
                $values['Atleta'] = $atleta;
                $values['AnnoSportivo'] = $anno_sportivo;
                $values['Squadra'] = $squadra;
                $this->insert_into("AtletiBAS", $values);
            }
            //print_r($queryInfo);
        }

        echo json_encode($post);
        exit();
    }

    //GIUSEPPE 2024-07-04 --------------------------------------------------------

    function manifestazioniBas()
    {

        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $anno_sportivo = $api->annoSportivo();
        $anno = $anno_sportivo['current']['year'];

        $query = "SELECT * FROM `Campionati` WHERE AnnoSportivo = '{$anno}' AND sport = 'CALCIO' AND PlayLeague = '1' ORDER BY Nome ASC";

        $res = $this->select_sql($query);

        echo json_encode($res);

        exit();
    }


    //GIUSEPPE 2024-08-31 --------------------------------------------------------

    function analizzaRinnovo()
    {
        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $json = file_get_contents('php://input');

        $post = json_decode($json, true);

        $anno_sportivo = $api->annoSportivo();

        $anno = $anno_sportivo['current']['year'];

        $squadra = $post['squadra'];

        $res = $this->cercaBasSquadraAnno($anno, $squadra);
        $res['AnnoSportivo'] = $anno;
        echo json_encode($res);

        exit();
    }

    private function cercaBasSquadraAnno($anno, $squadra)
    {
        $res['renewal'] = false;

        $query = "SELECT
									*
								FROM
									`SquadreBAS`
								WHERE
									Squadra = '{$squadra}'";

        $rowExist = $this->key_select($this->select_sql($query), 'AnnoSportivo');

        if (count($rowExist) > 0) {
            if (!isset($rowExist[$anno])) {
                $res['renewal'] = true;
                $res['anno'] = $anno;
            }
        }

        return $res;
    }


    function mettiInListaRinnovo()
    {
        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $json = file_get_contents('php://input');

        $post = json_decode($json, true);

        $post['result'] = 0;

        //echo $json;

        $squadra = $post['squadra'];
        $anno = $post['anno'];

        // controllo che non sia gia stata associata o messa in lista
        $query = "  SELECT
									COUNT(id) AS numList
								FROM
									`SquadreBAS`
								WHERE
									Squadra = '{$squadra}' AND AnnoSportivo = '{$anno}'
								LIMIT 1";

        $numList = $this->select_sql($query)[0]['numList'];

        if ($numList > 0) {
            $post['result'] = 1;
            echo json_encode($post);
            exit();
        }
        // seleziono la squadra
        $query = "  SELECT
									*
								FROM
									`SquadreBAS`
								WHERE
									Squadra = '{$squadra}' AND AnnoSportivo < '{$anno}'
								ORDER BY
									AnnoSportivo
								DESC
								LIMIT 1";

        $basOld = $this->select_sql($query);

        if (count($basOld) > 0) {
            $values['Squadra'] = $squadra;
            $values['AnnoSportivo'] = $anno;
            $values['client_id'] = $basOld[0]['client_id'];
            $values['general_counsel_id'] = -$basOld[0]['general_counsel_id'];
            $res = $this->insert_into('SquadreBAS', $values, true);
            $post['result'] = $res['last_id'];
        }


        echo json_encode($post);
        exit();
    }

    // END MOD 2024-08-31 --------------------------------------------------------	

    function infoFileLoaded()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $squadra = $post['Squadra'];

        $docs = [
            'MEMORANDUM' => 'MEMORANDUM_ARTICLES_ASSOCIATION',
            'AFFILIATION' => 'AFFILIATION_REQUEST',
            'PRESIDENT' => 'PRESIDENT_ID'
        ];
        $res = [];
        $dir = getcwd();

        foreach ($docs as $doc => $type_doc) {
            $file = "/files/BAS/{$squadra}/{$squadra}_{$doc}.pdf";
            $name = "{$squadra}_{$doc}.pdf";
            $fileDir = $dir . $file;
            $timestamp = filectime($fileDir);
            $res[$type_doc]['date'] = $timestamp == "" ? "" : gmdate("d\\\m\\\Y", $timestamp);
            $res[$type_doc]['file'] = $timestamp == "" ? "" : $file;
            $res[$type_doc]['name'] = $timestamp == "" ? "" : $name;
        }

        echo json_encode($res);

        exit();
    }
}
