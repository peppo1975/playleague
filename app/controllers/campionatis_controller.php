<?




function truncateFields($value)
{

    if (strlen($value) > 13)
    {

        $values = substr_replace($value, '...', 11, strlen($value));

        return '<span rel="timmytip" title="' . $value . '">' . $values . '</span>';
    }
    else
    {

        $values = $value;

        return '<span>' . $values . '</span>';
    }
}




class CampionatisController extends AppController
{

    var $name = "Campionatis";
    var $login_required = true;
    // var $helpers = array('Backend', 'Cksource');
    var $helpers = array('Backend', 'Cksource', 'fpdf'); //timmytag 2022-10-15
    var $uses = array('Campi', 'ChampCategory', 'AnniSportivi', 'Ranking', 'Yearbook', 'Campionati', 'Half', 'Campicampionati', 'Match', 'SquadreCampionati', 'Matchgoal', 'Disciplinari', 'FinalStage');




    function admin_index()
    {
        
    }


//timmytag 2022-10-15

    public function admin_pdfLiberatoria($campionato)
    {

        $this->layout = "pdf";

        $id_squadre = implode(",", $_POST);

        $query = "SELECT 
                            #*, 
                            Squadre.Squadra,
                            Campionati.Campionato,
                            Atleti.Atleta,
                            Campionati.Nome AS NomeCampionato, 
                            Squadre.Denominazione AS NomeSquadra,
                        Atleti.Cognome AS CognomeAtleta,
                        Atleti.Nome AS NomeAtleta,
                        Atleti.DataNascita AS DataNascitaAtleta
                    FROM 
                            `Campionati` 
                            INNER JOIN SquadreCampionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                            INNER JOIN Annuario ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato 
                            INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra 
                            INNER JOIN Atleti ON Annuario.Atleta = Atleti.Atleta 
                    WHERE 
                            Campionati.Campionato = '{$campionato}' AND Annuario.SquadraCampionato IN ({$id_squadre})";

        $this->write_file("_query_liberatoria", $query);

        $res = $this->select_sql($query);

        $view = [];

        foreach ($res as $value)
        {
            $campionato = $value['Campionato'];
            $squadra = $value['Squadra'];
            $atleta = $value['Atleta'];
            $NomeCampionato = $value['NomeCampionato'];

            $NomeSquadra = $value['NomeSquadra'];
            $CognomeAtleta = $value['CognomeAtleta'];
            $NomeAtleta = $value['NomeAtleta'];
            $DataNascitaAtleta = explode("-", $value['DataNascitaAtleta']);

            $view[$squadra]['NomeSquadra'] = $NomeSquadra;
            $view[$squadra]['Atleti'][$atleta]['Anagrafica'] = "{$CognomeAtleta} {$NomeAtleta}";
            $view[$squadra]['Atleti'][$atleta]['DataNascita'] = sprintf("%s\%s\%s", $DataNascitaAtleta[2], $DataNascitaAtleta[1], $DataNascitaAtleta[0]); //"{$DataNascitaAtleta[2]}\{$DataNascitaAtleta[1]}\{$DataNascitaAtleta[0]}";
        }

        $this->set('view', $view);
    }
    

   public function admin_squadreLiberatoria($campionato)
    {

        $query = "SELECT 
                            #*, 
                            Squadre.Squadra,
                            Campionati.Campionato,
                            Atleti.Atleta,
                            Campionati.Nome AS NomeCampionato, 
                            Squadre.Denominazione AS NomeSquadra,
                            Atleti.Cognome AS CognomeAtleta,
                            Atleti.Nome AS NomeAtleta,
                            Atleti.DataNascita AS DataNascitaAtleta,
                            SquadreCampionati.SquadraCampionato
                    FROM 
                            `Campionati` 
                            INNER JOIN SquadreCampionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                            INNER JOIN Annuario ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato 
                            INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra 
                            INNER JOIN Atleti ON Annuario.Atleta = Atleti.Atleta 
                    WHERE 
                            Campionati.Campionato = '{$campionato}'";

//        $this->write_file("_query_liberatoria", $query);

        $res = $this->select_sql($query);

        $view = [];

        foreach ($res as $value)
        {
            $campionato = $value['Campionato'];
            $squadra = $value['Squadra'];
            $atleta = $value['Atleta'];
            $NomeCampionato = $value['NomeCampionato'];

            $NomeSquadra = $value['NomeSquadra'];
            $CognomeAtleta = $value['CognomeAtleta'];
            $NomeAtleta = $value['NomeAtleta'];
            $DataNascitaAtleta = explode("-", $value['DataNascitaAtleta']);

            $this->set('NomeCampionato', $NomeCampionato);

            $view[$squadra]['NomeSquadra'] = $NomeSquadra;
            $view[$squadra]['SquadraCampionato'] = $value['SquadraCampionato'];
            $view[$squadra]['Atleti'][$atleta]['Anagrafica'] = "{$CognomeAtleta} {$NomeAtleta}";
            $view[$squadra]['Atleti'][$atleta]['DataNascita'] = sprintf("%s\%s\%s", $DataNascitaAtleta[2], $DataNascitaAtleta[1], $DataNascitaAtleta[0]); //"{$DataNascitaAtleta[2]}\{$DataNascitaAtleta[1]}\{$DataNascitaAtleta[0]}";
        }

        $this->set('view', $view);
        $this->set('campionato', $campionato);
        $this->render('admin_squadreLiberatoria');
    }



    // ----------------------


    function admin_countGiornate($p = null, $girone)
    {

        $this->layout = "ajax";

        Configure::Write('debug', 2);

        $giornate = $this->Match->find('first', array(
            'conditions' => array(
                'Match.GironeCampionato' => $girone
            ),
            'order' => 'Match.Giornata DESC'
        ));

        $this->set('result', json_encode(array('casa' => $giornate['Match']['Giornata'], 'trasferta' => $giornate['Match']['Giornata'])));
        $this->render('/backend/ajaxResult');
    }




    function admin_infoHalf($campionato = null, $casa = null, $trasferta = null)
    {

        $this->layout = "ajax";

        $casa = $this->Half->findByGironecampionato($casa);
        $trasferta = $this->Half->findByGironecampionato($trasferta);

        $this->set('result', json_encode(array('casa' => $casa['Half']['NumeroSquadre'], 'trasferta' => $trasferta['Half']['NumeroSquadre'])));
        $this->render('/backend/ajaxResult');
    }




    function admin_addFinalStage()
    {

        $this->layout = "ajax";

        $this->data = array(
            'FinalStage' => $_POST
        );

        if (!isset($this->data['FinalStage']['id']))
        {

            $this->FinalStage->create();
        }
        else
        {

            $this->FinalStage->read(null, $this->data['FinalStage']['id']);
        }

        $this->FinalStage->set($this->data);

        if ($this->FinalStage->save())
        {

            $save = $this->FinalStage->findById($this->FinalStage->id);
        }
        else
        {

            $save = $this->FinalStage->invalidFields();
        }

        $this->set('result', json_encode(array('save' => $save)));
        $this->render('/backend/ajaxResult');
    }




    function admin_editFinalStage($id)
    {

        $this->layout = "ajax";

        $data = $this->FinalStage->findById($id);

        if ($data['FinalStage']['Girone'] == 0)
        {

            $a_gironi = array(
                'ID' => $data['FinalStage']['id'],
                'FinalStageGironeCasa' => $data['FinalStage']['GironeCasa'],
                'FinalStageGironeTrasferta' => $data['FinalStage']['GironeTrasferta'],
                'FinalStagePosizioneCasa' => $data['FinalStage']['PosizioneCasa'],
                'FinalStagePosizioneTrasferta' => $data['FinalStage']['PosizioneTrasferta'],
                'FinalStageCampoSearch' => $data['FinalStage']['NomeCampo'],
                'FinalStageCampo' => $data['FinalStage']['Campo'],
                'FinalStageData' => $data['FinalStage']['Data_it'],
                'FinalStageOra' => $data['FinalStage']['Ora']
            );
        }
        else
        {

            $a_gare = array(
                'ID' => $data['FinalStage']['id'],
                'FinalStageGirone' => $data['FinalStage']['Girone'],
                'FinalStageGaraCasa' => $data['FinalStage']['GaraCasa'],
                'FinalStageGaraTrasferta' => $data['FinalStage']['GaraTrasferta'],
                'FinalStageCampoSearch' => $data['FinalStage']['NomeCampo'],
                'FinalStageCampo' => $data['FinalStage']['Campo'],
                'FinalStageData' => $data['FinalStage']['Data_it'],
                'FinalStageOra' => $data['FinalStage']['Ora']
            );
        }

        $this->set('result', json_encode(array('a_gare' => ((isset($a_gare)) ? $a_gare : ''), 'a_gironi' => ((isset($a_gironi)) ? $a_gironi : ''))));
        $this->render('/backend/ajaxResult');
    }




    function admin_deleteFinalStage($id)
    {

        $this->layout = "ajax";

        if ($this->FinalStage->delete($id))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));
        $this->render('/backend/ajaxResult');
    }




    function admin_gareFinalStage($girone)
    {

        $this->layout = "ajax";

        $gare = $this->Match->find('all', array(
            'fields' => array('Match.Calendario', 'Match.Giornata', 'Match.Partita', 'Match.NomeGara'),
            'conditions' => array(
                'Match.GironeCampionato' => $girone,
            ),
            'order' => 'Match.Giornata ASC'
        ));

        $ret = array();

        foreach ($gare as $gara)
        {

            $ret[$gara['Match']['Calendario']] = ($gara['Match']['NomeGara'] != '') ? $gara['Match']['NomeGara'] : 'Giornata ' . $gara['Match']['Giornata'] . ' - Partita ' . $gara['Match']['Partita'];
        }

        $this->set('result', json_encode($ret));
        $this->render('/backend/ajaxResult');
    }




    /* generazione fasi finali */




    function admin_finalStage($campionato, $precedente)
    {

        $this->layout = "ajax";

        Configure::Write('debug', 0);

        $finals = $this->FinalStage->find('all', array(
            'conditions' => array(
                'FinalStage.Campionato' => $precedente,
            ),
        ));

        $isGare = 0;
        $isGironi = 0;

        //Estrapolo gironi di destinazione

        $tmp = $this->FinalStage->find('all', array(
            'fields' => array('DISTINCT(FinalStage.Destinazione)'),
            'conditions' => array(
                'FinalStage.Campionato' => $precedente,
            ),
        ));

        $dest = array();

        foreach ($tmp as $t)
        {

            $temp = $this->FinalStage->find('count', array(
                'conditions' => array(
                    'FinalStage.Campionato' => $precedente,
                    'FinalStage.Destinazione' => $t['FinalStage']['Destinazione'],
                ),
            ));

            $dest[$t['FinalStage']['Destinazione']] = array(
                'Descrizione' => $t['FinalStage']['Destinazione'],
                'NumeroSquadre' => $temp * 2,
            );
        }

        //debug($dest);
        //exit;
        //Controllo il criterio

        foreach ($finals as $final)
        {

            if ($final['FinalStage']['GaraCasa'] == 0 && $final['FinalStage']['GaraTrasferta'] == 0)
            {

                $isGironi = 1;
            }
            else
            {

                $isGare = 1;
            }

            break;
        }

        //Conto numero squadre e creo array con le squadre da importare

        $squadre = array();
        $n_squadre = 0;

        if ($isGare)
        {

            foreach ($finals as $final)
            {

                $n_squadre += 2;

                $casa = $this->Match->findByCalendario($final['FinalStage']['GaraCasa']);
                $trasferta = $this->Match->findByCalendario($final['FinalStage']['GaraTrasferta']);

                $risultato_casa = explode('-', $casa['Match']['Risultato']);
                $risultato_trasferta = explode('-', $trasferta['Match']['Risultato']);

                if ($risultato_casa[0] > $risultato_casa[1])
                {

                    $squadra_casa = $casa['Match']['Casa'];
                }
                else
                {

                    $squadra_casa = $casa['Match']['Trasferta'];
                }

                if ($risultato_trasferta[0] > $risultato_trasferta[1])
                {

                    $squadra_trasferta = $trasferta['Match']['Casa'];
                }
                else
                {

                    $squadra_trasferta = $trasferta['Match']['Trasferta'];
                }

                $squadre[] = array(
                    'Casa' => $squadra_casa,
                    'Trasferta' => $squadra_trasferta,
                    'Girone' => $final['FinalStage']['Destinazione'],
                );
            }
        }

        if ($isGironi)
        {

            foreach ($finals as $final)
            {

                $n_squadre += 2;

                $classifica_casa = $this->Ranking->find('all', array(
                    'conditions' => array(
                        'Ranking.GironeCampionato' => $final['FinalStage']['GironeCasa'],
                    ),
                    'order' => 'Ranking.Punti DESC',
                    'limit' => 1,
                    'offset' => $final['FinalStage']['PosizioneCasa'] - 1,
                ));

                $classifica_trasferta = $this->Ranking->find('all', array(
                    'conditions' => array(
                        'Ranking.GironeCampionato' => $final['FinalStage']['GironeTrasferta'],
                    ),
                    'order' => 'Ranking.Punti DESC',
                    'limit' => 1,
                    'offset' => $final['FinalStage']['PosizioneTrasferta'] - 1,
                ));

                if (!count($classifica_casa) || !count($classifica_trasferta))
                {

                    $error = 1;
                }
                else
                    $error = 0;

                if (!$error)
                {

                    $squadre[] = array(
                        'Casa' => $classifica_casa[0]['Ranking']['SquadraCampionato'],
                        'Trasferta' => $classifica_trasferta[0]['Ranking']['SquadraCampionato'],
                        'Girone' => $final['FinalStage']['Destinazione'],
                    );
                }
            }
        }

        if (!$error)
        {

            //ottenuto l'array delle squadre vado ad esportare i dati

            foreach ($squadre as $squadra)
            {

                $exists = $this->Half->find('count', array(
                    'conditions' => array(
                        'Half.Campionato' => $campionato,
                        'Half.Descrizione' => $dest[$squadra['Girone']]['Descrizione'],
                    )
                ));

                if ($exists == 0)
                {

                    $this->Half->create();
                    $this->Half->set('Campionato', $campionato);
                    $this->Half->set('Descrizione', $dest[$squadra['Girone']]['Descrizione']);
                    $this->Half->set('NumeroSquadre', $dest[$squadra['Girone']]['NumeroSquadre']);
                    $this->Half->save();

                    $girone = $this->Half->id;
                }
                else
                {

                    $data = $this->Half->find('first', array(
                        'conditions' => array(
                            'Half.Campionato' => $campionato,
                            'Half.Descrizione' => $dest[$squadra['Girone']]['Descrizione'],
                        )
                    ));

                    $data['Half']['NumeroSquadre'] = $dest[$squadra['Girone']]['NumeroSquadre'];
                    $this->Half->set($data);
                    $this->Half->save();
                    $girone = $data['Half']['GironeCampionato'];
                }

                $data_casa = $this->SquadreCampionati->read(null, $squadra['Casa']);
                $data_trasferta = $this->SquadreCampionati->read(null, $squadra['Trasferta']);

                /* Casa */

                $data_casa['SquadreCampionati']['SquadraCampionato'] = '';
                $data_casa['SquadreCampionati']['Campionato'] = $campionato;
                $data_casa['SquadreCampionati']['GironeCampionato'] = $girone;
                $data_casa['SquadreCampionati']['Campo'] = '';
                $data_casa['SquadreCampionati']['Data'] = '';
                $data_casa['SquadreCampionati']['Ora'] = '';

                $this->SquadreCampionati->create();
                $this->SquadreCampionati->set($data_casa);
                $this->SquadreCampionati->save();

                $last_casa = $this->SquadreCampionati->id;

                $annuario_casa = $this->Yearbook->find('all', array(
                    'conditions' => array(
                        'Yearbook.SquadraCampionato' => $squadra['Casa'],
                    ),
                ));

                if (!empty($annuario_casa))
                {

                    foreach ($annuario_casa as $annuario)
                    {

                        $annuario['Yearbook']['Annuario'] = '';
                        $annuario['Yearbook']['SquadraCampionato'] = $last_casa;

                        if ($annuario['Yearbook']['TipoAssicurazione'] == '')
                            $annuario['Yearbook']['TipoAssicurazione'] = 1;

                        $this->Yearbook->create();
                        $this->Yearbook->set($annuario);
                        $this->Yearbook->save();
                    }
                }

                /* Trasferta */

                $data_trasferta['SquadreCampionati']['SquadraCampionato'] = '';
                $data_trasferta['SquadreCampionati']['Campionato'] = $campionato;
                $data_trasferta['SquadreCampionati']['GironeCampionato'] = $girone;
                $data_trasferta['SquadreCampionati']['Campo'] = '';
                $data_trasferta['SquadreCampionati']['Data'] = '';
                $data_trasferta['SquadreCampionati']['Ora'] = '';

                $this->SquadreCampionati->create();
                $this->SquadreCampionati->set($data_trasferta);
                $this->SquadreCampionati->save();

                $last_trasferta = $this->SquadreCampionati->id;

                $annuario_trasferta = $this->Yearbook->find('all', array(
                    'conditions' => array(
                        'Yearbook.SquadraCampionato' => $squadra['Trasferta'],
                    ),
                ));

                if (!empty($annuario_trasferta))
                {

                    foreach ($annuario_trasferta as $annuario)
                    {

                        $annuario['Yearbook']['Annuario'] = '';
                        $annuario['Yearbook']['SquadraCampionato'] = $last_trasferta;

                        if ($annuario['Yearbook']['TipoAssicurazione'] == '')
                            $annuario['Yearbook']['TipoAssicurazione'] = 1;

                        $this->Yearbook->create();
                        $this->Yearbook->set($annuario);
                        $this->Yearbook->save();
                    }
                }
            }
        }

        $this->set('result', json_encode(array('ok' => 1, 'error' => $error)));
        $this->render('/backend/ajaxResult');
    }




    function admin_searchCampionato()
    {

        $this->layout = "ajax";

        $campionatis = $this->Campionati->find('all', array(
            'conditions' =>
            array(
                'Campionati.Nome LIKE' => $_GET['term'] . '%',
            ),
            'order' => 'Campionati.Nome ASC',
            'limit' => '15',
        ));

        $ret = array();

        foreach ($campionatis as $campionato)
        {

            $tmp['id'] = $campionato['Campionati']['Campionato'];
            $tmp['label'] = $campionato['Campionati']['Nome'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }




    /* generazione fasi finali end */




    function admin_filters()
    {

        $this->layout = "ajax";

        if (!empty($this->data))
        {

            $this->Session->write($this->name . ".searchFilters", $this->data['searchFilters']);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }
    }




    function admin_search()
    {

        $this->layout = "ajax";

        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));

        if (!empty($this->data))
        {

            $this->Session->write($this->name . ".searchData", $this->data);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }

        if ($this->Session->check($this->name . ".searchData", $this->data))
        {

            $this->data = $this->Session->read($this->name . ".searchData");
        }
    }




    //timmytag 03/10/2016 -----------------------------

    function read_champ_cat_database()
    {
        $res = mysql_query("SELECT * FROM TipoSport");

        $arraySport = array();

        while ($row = mysql_fetch_assoc($res))
        {

            $arraySport[] = $row['sport'];
        }


        return $arraySport;
    }




    function admin_switch($id_sport)
    {

        //$this->set('result', 'test '.$id_sport.' volte'/*json_encode(array('delete' => 1))*/);
        //$this->render('/backend/ajaxResult');
        ////timmytag 2020-01-19 - - - - - - - - - - - - - - - - - - - - - - - - - - MI SERVE PER AVERE L'ELENCO IN ORDINE ALFABETICO
        //$this->set('result', json_encode($this->ChampCategory->find('list', array('fields' => array('ChampCategory.id', 'ChampCategory.Nome'), 'order' => 'ChampCategory.Nome ASC', 'conditions' => array('ChampCategory.id_sport' => $id_sport))))); //default calcio

        $res = array();

        $v = $this->ChampCategory->find('list', array('fields' => array('ChampCategory.id', 'ChampCategory.Nome'), 'order' => 'ChampCategory.Nome ASC', 'conditions' => array('ChampCategory.id_sport' => $id_sport)));

        foreach ($v as $key => $val)
        {
            $temp = array();
            $temp['id'] = $key;
            $temp['name'] = $val;
            $res[] = $temp;
        }

        $this->set('result', json_encode($res)); //default calcio
        //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 


        $this->render('/backend/ajaxResult');
    }




    /**/




    // --------------
//timmytag 2019-03-15 nuova funzione

    function events_tennis() //timmytag 2019-03-15
    {

        $sql = "SELECT id, Nome FROM events WHERE disabled = '0' AND sport = 'TENNIS' ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[$row["id"]] = $row["Nome"];
            }
        }

        return $list;
    }




// ------------------------------------



    function events()
    {
        //timmytag 2019-03-15 modificata query per calcio
        $sql = "SELECT id, Nome FROM events WHERE disabled = '0' AND sport = 'CALCIO' AND data_inizio > NOW() ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[$row["id"]] = $row["Nome"];
            }
        }

        return $list;
    }




    //timmytag 2020-02-01--------------------------------------
    function events_basket()
    {
        //timmytag 2019-03-15 modificata query per calcio
        $sql = "SELECT id, Nome FROM events WHERE disabled = '0' AND sport = 'BASKET' AND data_inizio > NOW() ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[$row["id"]] = $row["Nome"];
            }
        }

        return $list;
    }




    //---------------------------------------------------------
    //timmytag 2020-02-01--------------------------------------
    function events_esport()
    {
        //timmytag 2019-03-15 modificata query per calcio
        $sql = "SELECT id, Nome FROM events WHERE disabled = '0' AND sport = 'eSPORT' AND data_inizio > NOW() ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[$row["id"]] = $row["Nome"];
            }
        }

        return $list;
    }




//timmytag 2010-09-01 
    function events_padel()
    {

        $sql = "SELECT id, Nome FROM events WHERE disabled = '0' AND sport = 'PADEL' AND data_inizio > NOW() ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[$row["id"]] = $row["Nome"];
            }
        }

        return $list;
    }




    //---------------------------------------------------------




    function type_events()
    {
        $sql = "SELECT * FROM types WHERE disabled = '0' ORDER BY Nome ASC";

        $result = mysql_query($sql);

        $list = array("0" => "");

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //echo "id: " . $row["id"] . " - Name: " . $row["Nome"] . "<br>";

                $list[] = $row;
            }
        }

        return $list;
    }




    // --------------
    //timmytag 2020-09-01 -----------------------------------

    function type_sport()
    {
        //timmytag 2020-09-01 ---------------------------------

        $type_sport = array();

        $query = "SELECT * FROM `TipoSport`";

        $sport = $this->key_select($this->select_sql($query), 'id');

        foreach ($sport as $key => $value)
        {
            $type_sport[$value['sport']] = $key;
        }

        $this->set('type_sport', $type_sport);

        //-----------------------------------------------------
    }




    //-------------------------------------------------------

    function admin_add()
    {


        //timmytag 2020-09-01 -----------------------------------
        $this->type_sport();
        //-------------------------------------------------------

        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));

        //timmytag 2017-05-15 - - - - - - - - - - - - 

        $manifestazioni = $this->events();

        $manifestazioni_tennis = $this->events_tennis(); //

        $tipo_manifestazione = $this->type_events();

        $this->set('manifestazioni', $manifestazioni);

        $this->set('manifestazioni_tennis', $manifestazioni_tennis); //


        $this->set('tipo_manifestazione', $tipo_manifestazione);

        //- - - - - - - - - - - - - - - - - - - - - -

        if (!empty($this->data))
        {
            $this->set('categories', $this->ChampCategory->find('list', array('fields' => array('ChampCategory.id', 'ChampCategory.Nome'), 'order' => 'ChampCategory.Nome ASC', 'conditions' => array('ChampCategory.id_sport' => $this->data['Campionati']['sport'])))); //default calcio
        }
        else
        {
            $this->set('categories', $this->ChampCategory->find('list', array('fields' => array('ChampCategory.id', 'ChampCategory.Nome'), 'order' => 'ChampCategory.Nome ASC', 'conditions' => array('ChampCategory.id_sport' => 0)))); //default calcio
        }

        //-------------------------------------------------------

        $this->layout = "ajax";

        if (!empty($this->data))
        {
            //timmytag ----------------------------------------------------------------------

            $sport_id = $this->data['Campionati']['sport'];

            $result = $this->read_champ_cat_database();

            $this->data['Campionati']['sport'] = $result[$sport_id];

            $this->data['Campionati']['id_sport'] = $sport_id;

            if ($this->data['Campionati']['id_sport'] == 1)
            {
                $this->data['Campionati']['TariffaArbitro'] = 0;
                $this->data['Campionati']['TariffaArbitro2'] = 0;
                $this->data['Campionati']['TariffaDelegato'] = 0;
                $this->data['Campionati']['TariffaDelegatoA'] = 0;

                $this->data['Campionati']['scuola'] = 2;
            }

            //-------------------------------------------------------------------------------

            $this->Campionati->set($this->data);

            //print_r($this->data); exit;

            if ($this->Campionati->save())
            {
                //timmytag 2017-04-28 - il sesso lo aggiorno dopo l'inserimeto (al salvataggio non viene letto l'id sesso = 2)

                $campionato_id = $this->Campionati->id;

                $sesso_tipo = $this->data['Campionati']['SessoTipo'];

                //$evento_id = $this->data['Campionati']['Manifestazione'];

                $evento_id = str_replace("\"", "", $this->data['Campionati']['Manifestazione']); //timmytag 2019-03-15

                $tipo_evento_id = $this->data['Campionati']['TipologiaManifestazione'];

                $this->editSessoCampionato($campionato_id, $sesso_tipo);

                $this->editEventoCampionato($campionato_id, $evento_id, $tipo_evento_id);

                // - - - - - - - - - - - - - - - - - - - - - - - -

                if ($this->Session->check('gironi'))
                {
                    $last_id = $this->Campionati->id;
                    $gironi = $this->Session->read('gironi');

                    foreach ($gironi as $girone)
                    {

                        $this->data = $girone;
                        $this->data['Half']['Campionato'] = $last_id;
                        $this->Half->create();
                        $this->Half->set($this->data);
                        $this->Half->save();
                    }

                    $this->Session->delete('gironi');
                }

                if ($this->Session->check('campi'))
                {

                    $last_id = $this->Campionati->id;
                    $campi = $this->Session->read('campi');

                    foreach ($campi as $campo)
                    {

                        $this->data = $campo;
                        $this->data['Campicampionati']['Campionato'] = $last_id;
                        $this->Campicampionati->create();
                        $this->Campicampionati->set($this->data);
                        $this->Campicampionati->save();
                    }

                    $this->Session->delete('campi');
                }

                $ADD_OK = true;

                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }




    function admin_addgirone($edit)
    {

        $this->layout = "ajax";

        // $this->Session->delete('gironi');

        if ($edit == 'aggiunto')
        {

            $girone = $_POST;
            $gironi = $this->Session->read('gironi');
            $gironi[] = $girone;
            $this->Session->write('gironi', $gironi);

            $update = 'aggiunto';
        }
        else
        {

            $gironi = $this->Session->read('gironi');
            unset($gironi[$edit]);
            $gironi[$edit] = $_POST;
            $this->Session->write('gironi', $gironi);

            $update = $edit;
        }

        $this->set('result', json_encode(array('update' => $update)));
        $this->render('/backend/ajaxResult');
    }




    function admin_addcampo($edit)
    {

        $this->layout = "ajax";

        // $this->Session->delete('gironi');

        if ($edit == 'aggiunto')
        {

            $campo = $_POST;
            $campi = $this->Session->read('campi');
            $campi[] = $campo;
            $this->Session->write('campi', $campi);

            $update = 'aggiunto';
        }
        else
        {

            $campi = $this->Session->read('campi');
            unset($campi[$edit]);
            $campi[$edit] = $_POST;
            $this->Session->write('campi', $campi);

            $update = $edit;
        }

        $this->set('result', json_encode(array('update' => $update)));
        $this->render('/backend/ajaxResult');
    }




    function admin_deletegirone($id)
    {

        $this->layout = "ajax";

        $gironi = $this->Session->read('gironi');
        unset($gironi[$id]);
        $this->Session->write('gironi', $gironi);

        $this->set('result', json_encode(array('delete' => 1)));
        $this->render('/backend/ajaxResult');
    }




    function admin_deletecampo($id)
    {

        $this->layout = "ajax";

        $campi = $this->Session->read('campi');
        unset($campi[$id]);
        $this->Session->write('campi', $campi);

        $this->set('result', json_encode(array('delete' => 1)));
        $this->render('/backend/ajaxResult');
    }




    /* Function FinalStage */




    function admin_findGirone($id = null, $campionato)
    {

        $this->layout = "ajax";

        $halfs_total = $this->Half->find('list', array(
            'fields' => array('Half.GironeCampionato', 'Half.Descrizione'),
            'conditions' => array('Half.Campionato' => $campionato)
                )
        );

        $halfs = $this->Half->find('list', array(
            'fields' => array('Half.GironeCampionato', 'Half.Descrizione'),
            'conditions' => array('Half.Campionato' => $campionato, 'Half.GironeCampionato !=' => $id)
                )
        );

        $diff = array_diff($halfs_total, $halfs);

        debug($diff);

        $this->set('result', json_encode($halfs));
        $this->render('/backend/ajaxResult');
    }




    /**/




    //timmytag 2017-01-31 update tipologia campionato -------------

    function admin_editTipologiaCampionato($id, $tipo)
    {


        $query = "UPDATE `Campionati` SET `Tipo` = '$tipo' WHERE `Campionati`.`Campionato` = $id;";

        mysql_query($query);

        exit;
    }




    function admin_readTipologiaCampionato($id)
    {
        $query = "SELECT Tipo FROM `Campionati` WHERE `Campionato` = " . $id;

        //echo  $query;

        $result = mysql_query($query);

        $row = mysql_fetch_assoc($result);

        echo $row['Tipo'];

        exit;
    }




    // ------------------------------------------------------------
    //timmytag 2017-04-26 update sesso campionato -------------
    //    function admin_editSessoCampionato($id, $tipo)
    //    {
    //
    //        $query = "UPDATE `Campionati` SET `SessoTipo` = '$tipo' WHERE `Campionati`.`Campionato` = $id;";
    //
    //        mysql_query($query);
    //
    //        exit;
    //    }


    function editSessoCampionato($id, $tipo)
    {
        $query = "UPDATE `Campionati` SET `SessoTipo` = '$tipo' WHERE `Campionati`.`Campionato` = $id;";

        mysql_query($query);
    }




    function editEventoCampionato($id, $id_evento, $id_tipo)
    {
        $query = "UPDATE `Campionati` SET `Evento` = '$id_evento', `EventoTipo` = '$id_tipo' WHERE `Campionati`.`Campionato` = $id;";

        mysql_query($query);
    }




    // ------------------------------------------------------------
    /* //timmytag 2020-09-01 */
    function admin_duplica_campionato()
    {
        $Campionato = $_POST['Campionato'];

        $query = "SELECT * FROM `Campionati` WHERE Campionato = '{$Campionato}'";

        $res = $this->select_sql($query);

        $campionato = $res[0];

        /* cerco i gironi associati al campionato */
        $id_campionato = $campionato['Campionato'];

        $query = "
                    SELECT 
                            * 
                    FROM 
                            `GironiCampionati` 
                    WHERE 
                            `Campionato` = '{$id_campionato}'";

        $gironi = $this->select_sql($query);


        $query = "SELECT 
                        * 
                FROM 
                        `SquadreCampionati` 
                WHERE 
                        `Campionato` = '{$id_campionato}'";

        $squadre_campionati = $this->select_sql($query);


        unset($campionato['Campionato']);

        $campionato['Nome'] .= " - COPIA";
        $campionato['created'] = date("Y:m:d H:i:s");
        $campionato['modified'] = $campionato['created'];

        $table = "Campionati";

        $res = $this->insert_into($table, $campionato, true);

        $last_id_campionato = $res['last_id'];

        $new_gironi = $this->duplica_gironi($last_id_campionato, $gironi);

        $this->duplica_squadre_campionati($last_id_campionato, $new_gironi, $squadre_campionati);

        $_SESSION['Campionati']['ElementSelected'] = $res['last_id'];

        die();
    }




    /* //timmytag 2020-09-01 */




    private function duplica_gironi($last_id_campionato, $gironi)
    {


        $res = array();

        foreach ($gironi as $girone)
        {
            $girone_campionato = $girone['GironeCampionato'];
            $res[$girone_campionato] = array();

            unset($girone['GironeCampionato']);
            unset($girone['DataInizio']);
            $girone['Campionato'] = $last_id_campionato;

            $table = 'GironiCampionati';

            $res[$girone_campionato] = $this->insert_into($table, $girone, true);
        }

//        $this->write_file("_new_gironi", $res);

        return $res;
    }




    /* //timmytag 2020-09-01 */




    private function duplica_squadre_campionati($last_id_campionato, $new_gironi, $squadre_campionati)
    {
        foreach ($squadre_campionati as $key => $squadra_campionato)
        {
            unset($squadra_campionato['SquadraCampionato']);

            $girone_campionato = $squadra_campionato['GironeCampionato'];
            $squadra_campionato['GironeCampionato'] = $new_gironi[$girone_campionato]['last_id'];
            $squadra_campionato['Campionato'] = $last_id_campionato;
            $squadre_campionati[$key] = $squadra_campionato;

            $table = 'SquadreCampionati';
            $this->insert_into($table, $squadra_campionato);
        }


//        $this->write_file("_new_squadre_campionati.txt", $squadre_campionati);
    }




    /*     * **************************** */




    function admin_edit($id)
    {

        //timmytag 2020-09-01 -----------------------------------
        $this->type_sport();
        //-------------------------------------------------------
        //timmytag 2017-05-15 - - - - - - - - - - - - 

        $manifestazioni = $this->events();

        $manifestazioni_tennis = $this->events_tennis(); // //timmytag 2019-03-15
        //
        //timmytag 2020-02-01 -----------------------------------
        $manifestazioni_basket = $this->events_basket();
        //-------------------------------------------------------
        //timmytag 2020-09-01 -----------------------------------
        $manifestazioni_esport = $this->events_esport();
        $manifestazioni_padel = $this->events_padel();
        //-------------------------------------------------------


        $tipo_manifestazione = $this->type_events();

        $this->set('manifestazioni', $manifestazioni);

        $this->set('manifestazioni_tennis', $manifestazioni_tennis); // //timmytag 2019-03-15

        $this->set('manifestazioni_basket', $manifestazioni_basket); // //timmytag 2020-02-01 

        $this->set('manifestazioni_esport', $manifestazioni_esport); // //timmytag 2020-09-01

        $this->set('manifestazioni_padel', $manifestazioni_padel); // //timmytag 2020-09-01

        $this->set('tipo_manifestazione', $tipo_manifestazione);

        //- - - - - - - - - - - - - - - - - - - - - -

        $this->layout = "ajax";

        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));

        $this->set('categories', $this->ChampCategory->find('list', array('fields' => array('ChampCategory.id', 'ChampCategory.Nome'), 'order' => 'ChampCategory.Nome ASC')));

        $this->set('gironi', $this->Half->find('list', array('fields' => array('Half.GironeCampionato', 'Half.Descrizione'), 'conditions' => array('Half.Campionato' => $id), 'order' => 'Half.Descrizione ASC')));

        $this->set('finals', $this->FinalStage->find('all', array('conditions' => array('FinalStage.Campionato' => $id))));

        $this->set('squadre', $this->SquadreCampionati->find('list', array('fields' => array('SquadreCampionati.SquadraCampionato', 'SquadraNome'), 'conditions' => array('SquadreCampionati.Campionato' => $id))));

        if (empty($this->data))
        {

            $this->data = $this->Campionati->find('first', array('conditions' => array('Campionati.Campionato' => $id)));
            $this->data['Campionati']['CampionatoSearch'] = $this->data['Campionati']['NomeCampionatoPrecedente'];

            $this->data['Campionati']['content'] = json_decode($this->data['Campionati']['content'], TRUE);

            $this->Campionati->set($this->data);

            $this->set('halfs', $this->data['Half']);
            $this->set('campi', $this->data['Campicampionati']);

//            print_r($this->data);
        }
        else
        {
            //timmytag ----------------------------------------------------------------------



            $sport_id = $this->data['Campionati']['sport'];

            $result = $this->read_champ_cat_database();

            $this->data['Campionati']['sport'] = $result[$sport_id];

            $this->data['Campionati']['id_sport'] = $sport_id;

            //$this->data['Campionati']['id_sport'] = $this->data['Campionati']['sport'];

            if ($this->data['Campionati']['id_sport'] == 1)
            {

                $this->data['Campionati']['TariffaArbitro'] = 0;
                $this->data['Campionati']['TariffaArbitro2'] = 0;
                $this->data['Campionati']['TariffaDelegato'] = 0;
                $this->data['Campionati']['TariffaDelegatoA'] = 0;

                $this->data['Campionati']['scuola'] = 2;
            }



            //-------------------------------------------------------------------------------


            $this->data['Campionati']['content'] = json_encode(@$this->data['Campionati']['content'], TRUE);

            $this->Campionati->set($this->data);

            $ADD_OK = true;

            if ($this->Campionati->save())
            {

                $sesso_tipo = $this->data['Campionati']['SessoTipo'];

                $this->editSessoCampionato($id, $sesso_tipo);


                $evento_id = str_replace("\"", "", $this->data['Campionati']['Manifestazione']);  //timmytag 2019-03-15

                $tipo_evento_id = $this->data['Campionati']['TipologiaManifestazione'];

                $this->editEventoCampionato($id, $evento_id, $tipo_evento_id);


                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }




    function admin_editGirone($edit)
    {

        $this->layout = "ajax";

        if ($edit == 'aggiunto')
        {

            $this->data = $_POST;
            $this->Half->create();
            $this->Half->set($this->data);
            $this->Half->save();

            $update = 'aggiunto';

            $last_id = $this->Half->id;
        }
        else
        {

            $this->data = $this->Half->findBygironecampionato($edit);
            $this->data = $_POST;
            $this->data['Half']['GironeCampionato'] = $edit;

            $this->Half->set($this->data);
            $this->Half->save();
            $update = $edit;

            $last_id = $edit;
        }

        $this->set('result', json_encode(array('update' => $update, 'last_id' => $last_id)));
        $this->render('/backend/ajaxResult');
    }




    function admin_editGironeDelete($id)
    {

        $this->layout = "ajax";

        if ($this->Half->delete($id))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));
        $this->render('/backend/ajaxResult');
    }




    function admin_editCampo($edit)
    {

        $this->layout = "ajax";

        if ($edit == 'aggiunto')
        {

            $this->data = $_POST;
            $this->Campicampionati->create();
            $this->Campicampionati->set($this->data);
            $this->Campicampionati->save();

            $update = 'aggiunto';

            $last_id = $this->Campicampionati->id;
        }
        else
        {

            $this->data = $this->Campicampionati->findBycampocampionato($edit);
            $this->data = $_POST;
            $this->data['Campicampionati']['CampoCampionato'] = $edit;

            $this->Campicampionati->set($this->data);
            $this->Campicampionati->save();
            $update = $edit;

            $last_id = $edit;
        }

        $this->set('result', json_encode(array('update' => $update, 'last_id' => $last_id)));
        $this->render('/backend/ajaxResult');
    }




    function admin_editCampoDelete($id)
    {

        $this->layout = "ajax";

        if ($this->Campicampionati->delete($id))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));
        $this->render('/backend/ajaxResult');
    }




    function admin_deleteMatches($id)
    {

        $this->layout = "ajax";

        if ($this->Match->deleteAll(array('Match.Campionato' => $id)))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('del' => $delete)));
        $this->render('/backend/ajaxResult');
    }


    //timmytag 2023-01-22 - - - - - - - -  - -
    function admin_stampaAffiliazione()
    {
        $this->layout = "pdf";

        $idCampionato = $_GET['Campionato'];

        $query = "  SELECT 
                            Squadre.Denominazione, 
                            Squadre.Squadra, 
                            SquadreCampionati.SquadraCampionato, 
                            Campionati.sport, 
                            Campionati.AnnoSportivo 
                    FROM 
                            Campionati 
                            INNER JOIN SquadreCampionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                            INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra 
                    WHERE 
                            Campionati.Campionato = '{$idCampionato}' 
                    ORDER BY 
                            Squadre.Denominazione ASC";

        $squadre_campionato = $this->key_select($this->select_sql($query), "Squadra");

        $atleti_annuario = $this->adminSquadreAnnuario($squadre_campionato);

        $responsabili = $this->trovaAtletiAnnuarioSquadre($squadre_campionato, $atleti_annuario);

        $this->set('responsabili', $responsabili);

    }

    //timmytag 2023-01-22 - - - - - - - -  - -
    private function adminSquadreAnnuario($arrayQuery)
    {
        $keys_Squadra_campionato = [];
        $anno_sportivo = "";
        
        foreach ($arrayQuery as $value)
        {
            $keys_Squadra_campionato[] = $value['SquadraCampionato'];
            $anno_sportivo = $value['AnnoSportivo'];
        }

        $filterSquadraCampionato = implode(", ", $keys_Squadra_campionato);

        $queryAnnuario = "  SELECT 
                                    Annuario.Annuario, 
                                    Annuario.AnnoSportivo, 
                                    Annuario.SquadraCampionato, 
                                    Atleti.* 
                            FROM 
                                `Annuario` 
                                INNER JOIN Atleti ON
                                Atleti.Atleta = Annuario.Atleta
                            WHERE 
                                    AnnoSportivo = '{$anno_sportivo}' 
                                    AND SquadraCampionato IN ({$filterSquadraCampionato}) 
                                    AND Annuario.isAdmin = '1'
                            ORDER BY Annuario.Annuario DESC        
                                    ";

        $res = $this->key_select($this->select_sql($queryAnnuario), 'SquadraCampionato');

        return $res;
    }

    //timmytag 2023-01-22 - - - - - - - -  - -
    private function trovaAtletiAnnuarioSquadre($squadre_campionato, $atleti_annuario)
    {

        $res = [];

        foreach ($squadre_campionato as $value)
        {

            $squadraCampionato = $value['SquadraCampionato'];
            $squadra = $value['Squadra'];

            $res[$squadra] = $value;

            if (isset($atleti_annuario[$squadraCampionato]))
            {
                $res[$squadra] = array_merge($value, $atleti_annuario[$squadraCampionato]);
            }
        }

        return $res;

        // - - - - - - - - - - - - - - - - - 
    }
	
    // GIUSEPPE 2024-05-10 --------------------------------------------
    function admin_searchCampionati()
    {

        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $anno_sportivo = $api->annoSportivo();

        $anno = $anno_sportivo['current']['year'];

        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $valueSearch = $post['valueSearch'];

        $query = "SELECT *, Campionato AS ID FROM `Campionati` WHERE PlayLeague = '1' AND AnnoSportivo = '{$anno}' AND Nome LIKE '%{$valueSearch}%' ORDER BY Nome ASC";
        $res = $this->select_sql($query);

//        if (count($res) == 0)
//        {
//            $res[] = array("ID" => "----", "Nome" => "----");
//        }

        echo json_encode($res);

        exit();
    }


}
