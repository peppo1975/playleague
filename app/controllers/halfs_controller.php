<?


class HalfsController extends AppController
{

    var $name = "Halfs";
    var $login_required = true;
    var $helpers = array('Backend');
    var $uses = array('Half', 'Squadre', 'SquadreCampionati', 'Campionati', 'Match', 'Campicampionati', 'Campi', 'Yearbook', 'AnniSportivi', 'TipiAssicurazione');


    function admin_associaSquadre($id_girone, $id_campionato, $nr_squadre)
    {

        $this->layout = "timmybox";

        $this->data = $this->SquadreCampionati->find(
            'all',
            array(
                'conditions' => array(
                    'SquadreCampionati.GironeCampionato' => $id_girone,
                    'SquadreCampionati.Campionato' => $id_campionato
                )
            )
        );

        $campionato = $this->Campionati->findByCampionato($id_campionato);
        $girone = $this->Half->findBygironecampionato($id_girone);

        $this->set('girone', $girone);
        $this->set('campionato', $campionato);
        $this->set('teams', $this->data);
        $this->set('nr_squadre', $nr_squadre);
        $this->set('AnniSportivi', $this->AnniSportivi->find('all', array('order' => array('AnniSportivi.AnnoSportivo DESC'))));
        $this->set('TipiAssicurazione', $this->TipiAssicurazione->find('all', array('order' => array('TipiAssicurazione.Descrizione ASC'))));
    }


    function admin_searchCampo()
    {

        $this->layout = "ajax";

        $campi = $this->Campi->find('all', array(
            'conditions' =>
                array(
                    'Campi.Descrizione LIKE' => $_GET['term'] . '%'
                ),
            'order' => 'Campi.Descrizione ASC',
            'limit' => '15',
        ));

        $ret = array();

        foreach ($campi as $campo) {

            $tmp['id'] = $campo['Campi']['Campo'];
            $tmp['label'] = $campo['Campi']['Descrizione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


    function admin_associaSquadreAdd($edit, $check_campionato_precedente = '')
    {

        $this->layout = "ajax";

        Configure::Write('debug', 0);

        $this->data = $_POST['SquadreCampionati'];

        $count_campi = $this->SquadreCampionati->find(
            'count',
            array(
                'conditions' => array(
                    'SquadreCampionati.Campionato' => $this->data['Campionato'],
                    'SquadreCampionati.Campo' => $this->data['Campo'],
                    'SquadreCampionati.Ora' => $this->data['Ora'],
                    'SquadreCampionati.Giorno' => $this->data['Giorno'],
                )
            )
        );

        $count_squadre = $this->SquadreCampionati->find(
            'count',
            array(
                'conditions' => array(
                    'SquadreCampionati.Squadra' => $this->data['Squadra'],
                    'SquadreCampionati.Campionato' => $this->data['Campionato']
                )
            )
        );

        //GIUSEPPE 2017-01-26 evita il controllo sulle squadre di tennis .....................

        $q = mysql_query("SELECT sport FROM Squadre WHERE Squadra = '" . $this->data['Squadra'] . "'");

        $r = mysql_fetch_assoc($q);

        $sport = $r['sport']; // filtra sul $sport == "CALCIO"
        // ...................................................................................

        if ($count_campi >= 2)
            $error = 'campo';
        else if ($count_squadre >= 1 && $edit == 'aggiunto' && $sport == "CALCIO")
            $error = 'squadra';
        else
            $error = 'not_error';

        if ($error == 'not_error') {

            if ($edit == 'aggiunto') {

                $this->data = $_POST;
                $this->SquadreCampionati->create();
                $this->SquadreCampionati->set($this->data);
                $this->SquadreCampionati->save();

                $aggiunto = 'aggiunto';
                $last_id = $this->SquadreCampionati->id;

                $last_squadra = $this->SquadreCampionati->findBysquadracampionato($last_id);
                $team_id = $last_squadra['SquadreCampionati']['Squadra'];

                if ($check_campionato_precedente != '') {

                    $data = $this->Yearbook->find('all', array(
                        'conditions' => array(
                            'SquadreCampionati.Campionato' => $check_campionato_precedente,
                            'SquadreCampionati.Squadra' => $team_id,
                        ),
                    ));

                    foreach ($data as $annuario) {

                        $this->Yearbook->create();
                        $annuario['Yearbook']['Annuario'] = '';
                        $annuario['Yearbook']['SquadraCampionato'] = $last_id;
                        $this->Yearbook->set($annuario);
                        $this->Yearbook->save();
                    }
                } else {

                    //Importo giocatori della stagione corrente.
                    $data = $this->Yearbook->find('all', array(
                        'conditions' => array(
                            'SquadreCampionati.Squadra' => $team_id,
                            'Yearbook.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => array('AnniSportivi.AnnoSportivo DESC'), 'limit' => 1))
                        ),
                        'group' => array('Yearbook.Atleta'),
                    ));
                    foreach ($data as $annuario) {

                        $this->Yearbook->create();
                        $annuario['Yearbook']['Annuario'] = '';
                        $annuario['Yearbook']['SquadraCampionato'] = $last_id;
                        $this->Yearbook->set($annuario);
                        $this->Yearbook->save();
                    }

                    /*                     * Check Match* */
                    $matches = $this->Match->find('all', array(
                        'conditions' => array(
                            'Match.Campionato' => $last_squadra['SquadreCampionati']['Campionato'],
                            'Match.GironeCampionato' => $last_squadra['SquadreCampionati']['GironeCampionato']
                        ),
                    ));

                    if (!empty($matches)) {

                        foreach ($matches as $match) {

                            $casa = $match['Match']['Casa'];
                            $tras = $match['Match']['Trasferta'];

                            $isAssociateCasa = $this->SquadreCampionati->find('count', array(
                                'conditions' => array(
                                    'SquadreCampionati.Campionato' => $last_squadra['SquadreCampionati']['Campionato'],
                                    'SquadreCampionati.GironeCampionato' => $last_squadra['SquadreCampionati']['GironeCampionato'],
                                    'SquadreCampionati.SquadraCampionato' => $casa
                                )
                            ));

                            $isAssociateTras = $this->SquadreCampionati->find('count', array(
                                'conditions' => array(
                                    'SquadreCampionati.Campionato' => $last_squadra['SquadreCampionati']['Campionato'],
                                    'SquadreCampionati.GironeCampionato' => $last_squadra['SquadreCampionati']['GironeCampionato'],
                                    'SquadreCampionati.SquadraCampionato' => $tras
                                )
                            ));

                            if ($isAssociateCasa == 0) {

                                $this->Match->read(null, $match['Match']['Calendario']);
                                $this->Match->set('Casa', $last_id);
                                $this->Match->save();
                            } else if ($isAssociateTras == 0) {

                                $this->Match->read(null, $match['Match']['Calendario']);
                                $this->Match->set('Trasferta', $last_id);
                                $this->Match->save();
                            }
                        }
                    }
                    //debug(count($data));
                    //debug($data);
                    //****************************************//	
                }
            } else {

                $this->data = $this->SquadreCampionati->findBysquadracampionato($edit);
                $old_squadra = $this->data['SquadreCampionati']['Squadra'];
                $this->data = $_POST;
                $this->data['SquadreCampionati']['SquadraCampionato'] = $edit;

                $this->SquadreCampionati->set($this->data);

                $count_squadre = $this->SquadreCampionati->find(
                    'count',
                    array(
                        'conditions' => array(
                            'SquadreCampionati.Squadra !=' => $old_squadra,
                            'SquadreCampionati.Squadra' => $this->data['SquadreCampionati']['Squadra'],
                            'SquadreCampionati.Campionato' => $this->data['SquadreCampionati']['Campionato'],
                        )
                    )
                );
                //GIUSEPPE 2017-01-26 aggiunto il filtro per il calcio($sport == "CALCIO"): nel tennis non devo controllare se ci sono iscrizioni di squadre
                if ($count_squadre >= 1 && $sport == "CALCIO")
                    $error = 'squadra';
                else {

                    $oldInfo = $this->SquadreCampionati->findBysquadracampionato($edit);

                    $this->SquadreCampionati->save();
                    $aggiunto = $edit;

                    //if($check_campionato_precedente != '' && $oldInfo['SquadreCampionati']['Squadra'] != $this->data['SquadreCampionati']['Squadra']) {
                    if ($check_campionato_precedente != '') {

                        $dataDelete = $this->Yearbook->find('all', array(
                            'conditions' => array(
                                'SquadreCampionati.SquadraCampionato' => $edit,
                            ),
                        ));
                        foreach ($dataDelete as $d) {
                            $this->Yearbook->query("DELETE FROM Annuario WHERE Annuario.Annuario = " . $d['Yearbook']['Annuario']);
                        }

                        $data = $this->Yearbook->find('all', array(
                            'conditions' => array(
                                'SquadreCampionati.Campionato' => $check_campionato_precedente,
                                'SquadreCampionati.Squadra' => $this->data['SquadreCampionati']['Squadra'],
                            ),
                        ));

                        foreach ($data as $annuario) {

                            $this->Yearbook->create();
                            $annuario['Yearbook']['Annuario'] = '';
                            $annuario['Yearbook']['SquadraCampionato'] = $edit;
                            $this->Yearbook->set($annuario);
                            $this->Yearbook->save();
                        }
                    }
                }
            }
        }

        if (!isset($last_id))
            $last_id = 0;
        if (!isset($aggiunto))
            $aggiunto = 'null';

        $this->set('result', json_encode(array('aggiunto' => $aggiunto, 'last_id' => $last_id, 'error' => $error)));
        $this->render('/backend/ajaxResult');
    }


    function admin_associaSquadreDelete($id)
    {

        $this->layout = 'ajax';

        if ($this->SquadreCampionati->delete($id)) {

            $delete = 1;
        } else {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));

        $this->render("/backend/ajaxResult");
    }


    function admin_associaSquadreAnnuario($squadra_id, $girone_id, $campionato_id)
    {

        $this->layout = "ajax";

        $squadra_campionato = $this->SquadreCampionati->find(
            'first',
            array(
                'conditions' => array(
                    'SquadreCampionati.Squadra' => $squadra_id,
                    'SquadreCampionati.GironeCampionato' => $girone_id,
                    'SquadreCampionati.Campionato' => $campionato_id
                )
            )
        );

        $squadracampionato = $squadra_campionato['SquadreCampionati']['SquadraCampionato'];

        $yearbooks = $this->Yearbook->find(
            'all',
            array(
                'conditions' => array(
                    'Yearbook.SquadraCampionato' => $squadracampionato
                )
            )
        );

        //debug($yearbooks);

        $this->set('result', json_encode($yearbooks));
        $this->render('/backend/ajaxResult');
    }


    function admin_associaSquadreAnnuarioDelete($id)
    {

        $this->layout = "ajax";

        if ($this->Yearbook->delete($id)) {

            $delete = 1;
        } else {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));
        $this->render('/backend/ajaxResult');
    }


    function admin_associaSquadreAnnuarioAdd()
    {

        $this->layout = "ajax";

        $this->data = $_POST;

        /* Controllo se il giocatore gioca gi� nella stessa stagione */

        $SquadraCampionato = $this->SquadreCampionati->findBySquadracampionato($this->data['Yearbook']['SquadraCampionato']);
        $campionato = $SquadraCampionato['SquadreCampionati']['Campionato'];

        $tesseramenti = $this->Yearbook->find('all', array(
            'conditions' => array(
                'Yearbook.AnnoSportivo' => $this->data['Yearbook']['AnnoSportivo'],
                'Yearbook.Atleta' => $this->data['Yearbook']['Atleta'],
            ),
        ));

        $giaGiocato = 0;

        foreach ($tesseramenti as $tessera) {

            if ($tessera['SquadreCampionati']['Campionato'] == $campionato) {

                if ($tessera['Athlete']['Sportivo'] == 'Si' || $tessera['SquadreCampionati']['Squadra'] == $SquadraCampionato['SquadreCampionati']['Squadra'] || $tessera['SquadreCampionati']['SquadraCampionato'] == $SquadraCampionato['SquadreCampionati']['SquadraCampionato']) {

                    $giaGiocato = 1;
                    break;
                }
            }
        }

        if (!$giaGiocato && count($tesseramenti)) {

            $data = $tesseramenti[0];

            $this->data['Yearbook']['Tessera'] = $data['Yearbook']['Tessera'];
            $this->data['Yearbook']['DataVidimazione'] = $data['Yearbook']['DataVidimazione'];
            //$this->data['Yearbook']['AnnoSportivo']    = $data['Yearbook']['AnnoSportivo'];
        }

        if ($giaGiocato) {

            $this->Yearbook->invalidate('AtletaSearch', 'Atleta gi� inserito.');
            return false;
        }

        /* */

        $this->Yearbook->set($this->data);

        if ($this->Yearbook->save()) {

            $add = $this->Yearbook->id;
        } else {

            $add = '';
        }

        $this->set('result', json_encode(array('add' => $add)));
        $this->render('/backend/ajaxResult');
    }


    function admin_deleteAllYearbooks($squadra_campionato)
    {

        $this->layout = "ajax";

        Configure::Write('debug', 2);

        $yearbooks = $this->Yearbook->find('list', array(
            'fields' => array('Yearbook.Annuario', 'Yearbook.Annuario'),
            'conditions' => array(
                'Yearbook.SquadraCampionato' => $squadra_campionato
            )
        ));

        //	debug($yearbooks);

        foreach ($yearbooks as $id) {
            $this->Yearbook->read(null, $id);
            $this->Yearbook->set('SquadraCampionato', '');
            //$Yearbook->set('DataVidimazione', 0);
            $this->Yearbook->save();
        }

        $this->set('result', json_encode(array('remove' => 1)));
        $this->render('/backend/ajaxResult');
    }


    //GIUSEPPE 2023-07-28 -----------------------------------------------
    function admin_associaBas()
    //    function associaBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $getConnectBAS = $this->getConnectBAS();

        $squadra = $post['squadra'];
        $campionato = $post['campionato'];
        $anno_sportivo = 0;

        //GIUSEPPE 2024-05-23----------------------------
        if (isset($post['anno_sportivo'])) {
            $anno_sportivo = $post['anno_sportivo'];
        }
        //-----------------------------------------------

        $isInsertBas = $this->isInsertBas($squadra, $campionato, $anno_sportivo); // controllo se è gia stato inserito
        //        $this->write_file("isInsertBasFile", $isInsertBas);

        if ($isInsertBas['value']) {
            echo json_encode(['response' => 'EXIST_BAS', 'info' => $isInsertBas]);
            exit();
        }


        $isAllFiles = $this->isAllFiles($squadra); //controllo che la squadra abbia tutti i files
        $this->write_file("isAllFiles", $isAllFiles);
        if (!$isAllFiles['value']) {
            echo json_encode(['response' => 'FILES_BAS', 'info' => $isAllFiles]);
            exit();
        }

        $query = "SELECT
                    Squadre.*
                FROM
                    `Squadre`
                WHERE
                    `Squadra` = '{$squadra}'";

        $sq = $this->select_sql($query)[0];
        //        $this->write_file("SQ", $sq);

        $user = [
            "name" => $sq['Denominazione'],
            "constitution_date" => $sq['constitution_date'],
            "email" => $sq['email'],
            "phone" => $sq['phone'],
            "legal_address" => $sq['legal_address'],
            "legal_city" => $sq['legal_city'],
            "association_type" => "BAS",
            "affiliation_type" => "SPORTIVO",
            "disciplines" => [570, 457],
            "insurance_type" => "BASFIA1",
            "fiscal_code" => "A",
            "general_counsel" => [
                "birthday" => $sq['general_counsel_birthday'],
                "firstname" => $sq['general_counsel_lastname'], // sono stati invertiti perchè su hellogest hanno invertito
                "lastname" => $sq['general_counsel_firstname'], // sono stati invertiti perchè su hellogest hanno invertito
                "birthplace" => $sq['general_counsel_birthplace'],
                "gender" => strtolower($sq['general_counsel_gender']),
            ]
        ];

        $sendBas = $this->sendBas($user, $getConnectBAS);

        $this->write_file("sendBas_" . date("Y-m-d_h.i.s"), $sendBas);

        $b = json_decode($sendBas, true);

        if (!isset($b['data']['client_id']) || !isset($b['data']['general_counsel_id'])) {
            echo json_encode(['response' => 'ERROR_BAS', 'info' => $b]);
            exit();
        }

        $client_id = $b['data']['client_id'];
        $general_counsel_id = $b['data']['general_counsel_id'];

        $insertSquadreBas = $this->insertSquadreBas($squadra, $anno_sportivo, $client_id, $general_counsel_id);
        //        $this->write_file("insertSquadreBas", $insertSquadreBas);
        //        exit();
        $fileSend1 = $this->sendFile($getConnectBAS, $client_id, strtolower("MEMORANDUM_ARTICLES_ASSOCIATION"), $sq['MEMORANDUM_ARTICLES_ASSOCIATION']);
        $fileSend2 = $this->sendFile($getConnectBAS, $client_id, strtolower("AFFILIATION_REQUEST"), $sq['AFFILIATION_REQUEST']);
        $fileSend3 = $this->sendFile($getConnectBAS, $client_id, strtolower("PRESIDENT_ID"), $sq['PRESIDENT_ID']);

        if ($fileSend1 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `MEMORANDUM_ARTICLES_ASSOCIATION` = '1'
                            WHERE
                                `SquadreBAS`.`client_id` = '{$client_id}' AND SquadreBAS.general_counsel_id = '{$general_counsel_id}';";

            $this->select_sql($queryEdit);
        }

        if ($fileSend2 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `AFFILIATION_REQUEST` = '1'
                            WHERE
                                `SquadreBAS`.`client_id` = '{$client_id}' AND SquadreBAS.general_counsel_id = '{$general_counsel_id}';";

            $this->select_sql($queryEdit);
        }

        if ($fileSend3 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `PRESIDENT_ID` = '1'
                            WHERE
                                `SquadreBAS`.`client_id` = '{$client_id}' AND SquadreBAS.general_counsel_id = '{$general_counsel_id}';";

            $this->select_sql($queryEdit);
        }

        $newBas = $this->select_sql("SELECT * FROM SquadreBAS WHERE `SquadreBAS`.`client_id` = '{$client_id}' AND SquadreBAS.general_counsel_id = '{$general_counsel_id}';")[0];

        echo json_encode(['response' => 'NEW_BAS', 'info' => $newBas]);

        exit();
    }


    //GIUSEPPE 2024-09-06 -----------------------------------------------
    function admin_rinnovaBas()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $getConnectBAS = $this->getConnectBAS();

        $squadra = $post['squadra'];
        $client_id = $post['client_id'];
        $general_counsel_id = $post['general_counsel_id'];

        $isAllFiles = $this->isAllFiles($squadra); //controllo che la squadra abbia tutti i files

        $this->write_file("isAllFiles", $isAllFiles);

        if (!$isAllFiles['value']) {
            echo json_encode(['response' => 'FILES_BAS', 'info' => $isAllFiles]);
            exit();
        }

        $query = "SELECT
                    Squadre.*
                FROM
                    `Squadre`
                WHERE
                    `Squadra` = '{$squadra}'";

        $sq = $this->select_sql($query)[0];

        $annoSportivo = $this->annoSportivo();
        $inizio_no_datetime = $annoSportivo['current']['init'];
        list($d, $m, $Y) = explode("-", $inizio_no_datetime);

        $inizio = "{$Y}-{$m}-{$d}";

        //echo $inizio;
        $load_memorandum = date("Y-m-d", filectime($sq['MEMORANDUM_ARTICLES_ASSOCIATION']));
        $load_affiliation = date("Y-m-d", filectime($sq['AFFILIATION_REQUEST']));
        $load_president = date("Y-m-d", filectime($sq['PRESIDENT_ID']));


        // la data del caricamento file è >= della data di inizio ->  "SI" (sta per SI inviare file) altrimenti scrivo "NO" (sta per NO inviare file)
        $MEMORANDUM_ARTICLES_ASSOCIATION_doc = $load_memorandum >= $inizio ? "SI" : "NO";
        $AFFILIATION_REQUEST_doc = $load_affiliation >= $inizio ? "SI" : "NO";
        $PRESIDENT_ID_doc = $load_president >= $inizio ? "SI" : "NO";

        $user = [
            "name" => $sq['Denominazione'],
            "constitution_date" => $sq['constitution_date'],
            "email" => $sq['email'],
            "phone" => $sq['phone'],
            "legal_address" => $sq['legal_address'],
            "legal_city" => $sq['legal_city'],
            "association_type" => "BAS",
            "affiliation_type" => "SPORTIVO",
            "disciplines" => [570, 457],
            "insurance_type" => "BASFIA1",
            "fiscal_code" => "A",
            "general_counsel" => [
                "birthday" => $sq['general_counsel_birthday'],
                "firstname" => $sq['general_counsel_lastname'], // sono stati invertiti perchè su hellogest hanno invertito
                "lastname" => $sq['general_counsel_firstname'], // sono stati invertiti perchè su hellogest hanno invertito
                "birthplace" => $sq['general_counsel_birthplace'],
                "gender" => strtolower($sq['general_counsel_gender']),
            ]
        ];

        $sendBas = $this->renewBas($user, $client_id, $getConnectBAS);
        $this->write_file("bas_update_json", $sendBas);
        // $this->write_file("sendBas_" . date("Y-m-d_h.i.s") . ".json", $sendBas);

        // log renew bas ----------------------------------------------
        //mkdir('log_renew_bas');
        //chdir('log_renew_bas');
        //$this->write_file('renew_BAS_' . $user['name'] . "_" . date("Y-m-d_h.i.s"), ['response_renew' => $sendBas, 'user' => $user, 'client_id' => $client_id]);
        // -------------------------------------------------------------

        $b = json_decode($sendBas, true);
        // if (($b['message'] !== "Rinnovo effettuato") && ($b['message'] !== "Presidente già tesserato")) {
        if ($b == NULL) {
            echo json_encode(['response' => 'ERROR_BAS', 'info' => $b]);
            exit();
        }



        $bas_id = $this->renewSquadreBas($post);

        $fileSend1 = true;
        $fileSend2 = true;
        $fileSend3 = true;

        if ($MEMORANDUM_ARTICLES_ASSOCIATION_doc == "SI") {
            $fileSend1 = $this->sendFile($getConnectBAS, $client_id, strtolower("MEMORANDUM_ARTICLES_ASSOCIATION"), $sq['MEMORANDUM_ARTICLES_ASSOCIATION']);
        }

        if ($AFFILIATION_REQUEST_doc == "SI") {
            $fileSend2 = $this->sendFile($getConnectBAS, $client_id, strtolower("AFFILIATION_REQUEST"), $sq['AFFILIATION_REQUEST']);
        }

        if ($PRESIDENT_ID_doc == "SI") {
            $fileSend3 = $this->sendFile($getConnectBAS, $client_id, strtolower("PRESIDENT_ID"), $sq['PRESIDENT_ID']);
        }

        $this->write_file("files_send", [$fileSend1, $fileSend2, $fileSend3]);

        if ($fileSend1 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `MEMORANDUM_ARTICLES_ASSOCIATION` = '1'
                            WHERE
                                `SquadreBAS`.`id` = '{$bas_id}';";

            $this->select_sql($queryEdit);
        }


        if ($fileSend2 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `AFFILIATION_REQUEST` = '1'
                            WHERE
                                `SquadreBAS`.`id` = '{$bas_id}';";

            $this->select_sql($queryEdit);
        }

        if ($fileSend3 != NULL) {
            $queryEdit = "  UPDATE
                                `SquadreBAS`
                            SET
                                `PRESIDENT_ID` = '1'
                            WHERE
                                      `SquadreBAS`.`id` = '{$bas_id}';";

            $this->select_sql($queryEdit);
        }

        if ($fileSend1 == NULL || $fileSend2 == NULL || $fileSend3 == NULL) { {
                $queryEdit = "  UPDATE
                                    `SquadreBAS`
                                SET
                                    `general_counsel_id` = - SquadreBAS.general_counsel_id
                                WHERE
                                    `SquadreBAS`.`id` = '{$bas_id}'";

                $this->select_sql($queryEdit);
            }
        }


        $newBas = $this->select_sql("SELECT * FROM SquadreBAS WHERE `SquadreBAS`.`id` = '{$bas_id}'")[0];

        echo json_encode(['response' => 'RENEW_BAS', 'info' => $newBas]);

        exit();
    }

    //GIUSEPPE 2024-08-31 --------------------------------------------------------
    private function annoSportivo()
    {
        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        return $api->annoSportivo();
    }
    //----------------------------------------------------------------------------
    private function isInsertBas($squadra, $campionato, &$anno_sportivo) // controllo se è gia stata inserita nel bas  (per l'anno sportivo della manifestazione)
    {

        $res = [];
        $res['info'] = "";
        //GIUSEPPE 2024-05-23----------------------------
        $champ = [];

        if ($campionato == false) {
            $champ[] = "PLAYLEAGUE"; //serve per riempire l'array $champ 
        } else {
            // estraggo l'anno sportivo dal campionato
            $query = "SELECT *  FROM `Campionati` WHERE `Campionato` = '{$campionato}' AND PlayLeague = '1'";
            $champ = $this->select_sql($query);
        }
        //-----------------------------------------------
        //        // estraggo l'anno sportivo dal campionato
        //        $query = "SELECT *  FROM `Campionati` WHERE `Campionato` = '{$campionato}' AND PlayLeague = '1'";
        //        $champ = $this->select_sql($query);

        if (count($champ) > 0) // è un campionato playleague
        {
            $anno_sportivo = $anno_sportivo == 0 ? $this->select_sql($query)[0]['AnnoSportivo'] : $anno_sportivo;
            $this->write_file("isInsertBasCampionati", $query);

            // vedo se è presente in SquadreBAS
            $query = "SELECT *  FROM SquadreBAS WHERE `Squadra` = '{$squadra}' AND `AnnoSportivo` = '{$anno_sportivo}'";
            $present = $this->select_sql($query);
            $this->write_file("isInsertBas", $query);

            if (count($present) > 0) {
                $res['info'] = "Bas presente";
                $res['value'] = true;
            } else {
                $res['info'] = "Inserire BAS";
                $res['value'] = false;
            }
        } else {
            $res['info'] = "Non playleague";
            $res['value'] = true;
        }

        return $res;
    }


    private function isAllFiles($squadra)
    {
        $res = ['value' => true, 'info' => ''];
        $query = "SELECT * FROM Squadre WHERE Squadra = '{$squadra}'";
        $team = $this->select_sql($query)[0];

        $files = ["MEMORANDUM_ARTICLES_ASSOCIATION" => "STATUTO", "AFFILIATION_REQUEST" => "RICHIESTA DI AFFILIAZIONE", "PRESIDENT_ID" => "DOCUMENTO D'IDENTITA' RESPONSABILE"];

        foreach ($files as $key => $file) {
            if ($team[$key] == "") {
                $res['value'] = false;
                $res['errors'][] = $file;
            }
        }

        return $res;
    }


    private function insertSquadreBas($squadra, $anno_sportivo, $client_id, $general_counsel_id)
    {
        $toInsert['AnnoSportivo'] = $anno_sportivo;
        $toInsert['Squadra'] = $squadra;
        $toInsert['client_id'] = $client_id;
        $toInsert['general_counsel_id'] = $general_counsel_id;

        $res = $this->insert_into("SquadreBAS", $toInsert);

        return $res;
    }


    //GIUSEPPE 2024-08-31 --------------------------------------------------------
    private function renewSquadreBas($post)
    {

        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $anno_sportivo = $api->annoSportivo();
        $anno = $anno_sportivo['current']['year'];

        $post['AnnoSportivo'] = $anno;

        $res = $this->insert_into('SquadreBAS', $post, true);

        // $q = "SELECT * FROM SquadreBAS WHERE id='{$bas_id}'";
        // $infoBasToRenew = $this->select_sql($q)[0];
        // $this->write_file("infoBasToRenew", $q);
        // $general_counsel_id = $infoBasToRenew['general_counsel_id'] * (-1);

        // $queryEdit = "  UPDATE
        //             `SquadreBAS`
        //             SET
        //                 `general_counsel_id` = '{$general_counsel_id}'
        //             WHERE
        //                 `SquadreBAS`.`id` = '{$bas_id}';";

        // $this->write_file("query_edit_bas", $queryEdit);

        // $res = $this->my_query($queryEdit);



        // return $infoBasToRenew;
        return $res['last_id'];
    }


    private function renewBas($user, $client_id, $getConnectBAS)
    {
        //        $getConnectBAS = $this->getConnectBAS();
        //        $serverSendData = $this->getConnectBAS()['url'] . "/api/client/create";
        $serverSendData = $getConnectBAS['url'] . "/api/client/{$client_id}/renew";

        $userArray = $user;

        $data_string = json_encode($userArray);

        //        $this->write_file("data_string_bas", $data_string);
        //        $this->write_file("data_string_bas_connect_bas", $getConnectBAS);

        $token = $this->auth($getConnectBAS);

        //      $this->write_file("data_string_bas_token", $token);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $serverSendData);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string),
                "Authorization: Bearer " . $token['access_token']
            )
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $response = curl_exec($ch);

        //        $this->write_file("BAS_sendBas", $response);

        return $response;
    }
    //---------------------------------------------------------------------------


    private function sendBas($user, $getConnectBAS)
    {
        //        $getConnectBAS = $this->getConnectBAS();
        //        $serverSendData = $this->getConnectBAS()['url'] . "/api/client/create";
        $serverSendData = $getConnectBAS['url'] . "/api/client/create";

        $userArray = $user;

        $data_string = json_encode($userArray);

        //        $this->write_file("data_string_bas", $data_string);
        //        $this->write_file("data_string_bas_connect_bas", $getConnectBAS);

        $token = $this->auth($getConnectBAS);

        //      $this->write_file("data_string_bas_token", $token);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $serverSendData);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string),
                "Authorization: Bearer " . $token['access_token']
            )
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $response = curl_exec($ch);

        //        $this->write_file("BAS_sendBas", $response);

        return $response;
    }


    public function sendFile($getConnectBAS, $client_id, $type, $file_name_with_full_path)
    {
        $this->write_file("_735", $file_name_with_full_path);
        //        $getConnectBAS = $this->getConnectBAS();

        $this->write_file("_738", $getConnectBAS);
        //        $target_url = $this->getConnectBAS()['url'] . "/api/client/{$client_id}/document";
        $target_url = $getConnectBAS['url'] . "/api/client/{$client_id}/document";

        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($file_name_with_full_path);
        } else { // 
            $cFile = '@' . realpath($file_name_with_full_path);
        }

        $token = $this->auth($getConnectBAS);

        $post = array('type' => $type, 'date' => date("Y-m-d"), 'document' => $cFile);

        $o = ob_start();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $target_url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type: multipart/form-data;",
                "Authorization: Bearer " . $token['access_token']
            )
        );

        $result = curl_exec($ch);

        curl_close($ch);

        $html = ob_get_clean();

        // chdir("files/BAS/");
        // $this->write_file("BAS_sendFile_{$type}", $html);


        return json_decode($html, true);
    }


    public function sendBasAthlete($atleta_bas, $client_id)
    {

        $serverSendData = $this->getConnectBAS()['url'] . "/api/client/{$client_id}/subscriber";

        //        $atleta_bas_json = json_encode($atleta_bas);
        //        echo $serverURL;
        //
        //        echo $atleta_bas_json;

        $data_string = json_encode($atleta_bas);

        $token = $this->auth();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $serverSendData);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string),
                "Authorization: Bearer " . $token['access_token']
            )
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $response = curl_exec($ch);

        $this->write_file("BAS_sendBasAthlete", $response);

        return $response;
    }


    private function auth($getConnectBAS)
    {
        //        $serverURL = "https://www.hellogest.com/oauth/token";

        $serverURL = $getConnectBAS['url'] . "/oauth/token";

        $cl = curl_init();

        curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($cl, CURLOPT_URL, $serverURL);

        curl_setopt($cl, CURLOPT_POST, true);

        /* uncomment this line if you don't have the required SSL certificates */
        curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($cl, CURLOPT_POSTFIELDS, array(
            "grant_type" => "client_credentials",
            "client_id" => $getConnectBAS['client_id'],
            "client_secret" => $getConnectBAS['client_secret']
        ));

        $auth_response = curl_exec($cl);

        if ($auth_response === false) {
            echo "Failed to authenticate\n";
            var_dump(curl_getinfo($cl));
            curl_close($cl);
            return NULL;
        }

        curl_close($cl);

        return json_decode($auth_response, true);
    }


    public function getConnectBAS()
    {

        include 'apis_controller.php';
        //        
        $apis = new ApisController();
        //
        $res = $apis->getConnectBAS();

        return $res;
    }

    //-------------------------------------------------------------------
}
