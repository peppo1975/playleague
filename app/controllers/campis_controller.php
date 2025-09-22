<?


function getValueOfCampo($value)
{

    if ($value == 1)
    {

        return 'Si';
    }
    else
    {

        return 'No';
    }
}


class CampisController extends AppController
{

    var $name = "Campis";
    var $login_required = true;
    var $helpers = array('Backend', 'fpdf', 'excel', 'Cksource');
    var $firstModel = 'Campi';
    var $uses = array('Campi', 'CampiOrari', 'Upload', 'CampiBooking', 'Match', 'CampiDisabled', 'ChampCategory', 'Campionati');
    var $components = array('Email');


    function sendSms($options)
    {

        if (!isset($options['mit']))
            $options['mit'] = '';

        $text = trim($options['text']);
        $text = utf8_decode($options['text']);
        $text = substr($text, 0, 160);
        $text = base64_encode($text);

        $buffer = array(
            "authlogin" => Configure::read('options_sms_username'),
            "authpasswd" => Configure::read('options_sms_password'),
            "sender" => base64_encode($options['mit']),
            "body" => $text,
            "destination" => $options['dest'],
            "id_api" => Configure::read('options_sms_api')
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.apisms.it/http/send_sms");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $buffer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);

        debug($ret);

        curl_close($ch); # ritorno dalle api print_r($ret); 

        $retrn = substr_count($ret, '+01 SMS Queued');

        if ($retrn > 0)
            return true;
        else
            return false;
    }


    function admin_index()
    {
        
    }


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


    function admin_switchday($campoid, $switchday)
    {



        $this->autoRender = false;

        $exist = $this->CampiDisabled->find('first', array('conditions' => array(
                'giorno' => $switchday,
                'campo_id' => $campoid
        )));

        if (empty($exist))
        {


            $this->CampiDisabled->create();

            $this->CampiDisabled->set('campo_id', $campoid);

            $this->CampiDisabled->set('giorno', $switchday);

            $this->CampiDisabled->save();

            $disabled = 1;
        }
        else
        {


            $this->CampiDisabled->query('DELETE FROM campi_disableds WHERE id = ' . $exist['CampiDisabled']['id']);

            $disabled = 0;
        }


        $disabled_text[0] = 'Giorno abilitato alle prenotazioni';
        $disabled_text[1] = 'Giorno disabilitato alle prenotazioni';

        $ret = array(
            'text' => $disabled_text[$disabled],
            'img' => '/img/timmyshare/icon_disabled_' . $disabled
        );

        print json_encode(($ret));
        exit;
    }


    function admin_add()
    {

        $this->layout = "ajax";

        $group_id = $this->Auth->user('group_id');
        $this->set('group_id', $group_id);

        if (!empty($this->data))
        {

            $this->Campi->set($this->data);

            if ($this->Campi->save())
            {

                $ADD_OK = true;

                if ($this->__adminUploadFile('campi_id', $this->Campi->id) == true)
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


    function admin_edit($id)
    {
        $this->layout = "ajax";

        $group_id = $this->Auth->user('group_id');
        $this->set('group_id', $group_id);

        $this->set('booking', $this->admin_booking($id));

        $orari = $this->CampiOrari->find('all', array(
            'conditions' => array('CampiOrari.campo_id' => $id),
//            'order' => 'CampiOrari.Giorno ASC',
            'order' => ['CampiOrari.Giorno ASC', 'CampiOrari.Ora ASC'], //GIUSEPPE 2023-04-15
        ));

        $this->set('orari', $orari);

        if (empty($this->data))
        {

            $this->data = $this->Campi->read(null, $id);

            $this->Campi->set($this->data);
        }
        else
        {

            /*              
              Configure::write('debug',2);
              debug($this->data);
             */
            if (isset($_POST['data']['Campi']['check0']))
                $this->data['Campi']['check0'] = 1;
            else
                $this->data['Campi']['check0'] = 0;

            if (isset($_POST['data']['Campi']['check1']))
                $this->data['Campi']['check1'] = 1;
            else
                $this->data['Campi']['check1'] = 0;

            if (isset($_POST['data']['Campi']['check2']))
                $this->data['Campi']['check2'] = 1;
            else
                $this->data['Campi']['check2'] = 0;

            if (isset($_POST['data']['Campi']['check3']))
                $this->data['Campi']['check3'] = 1;
            else
                $this->data['Campi']['check3'] = 0;

            if (isset($_POST['data']['Campi']['check4']))
                $this->data['Campi']['check4'] = 1;
            else
                $this->data['Campi']['check4'] = 0;

            if (isset($_POST['data']['Campi']['check5']))
                $this->data['Campi']['check5'] = 1;
            else
                $this->data['Campi']['check5'] = 0;

            $ADD_OK = true;

            if ($this->__adminUploadFile('campi_id', $id) == true)
            {

                $ADD_OK = false;
            }

            $this->Campi->set($_POST['data']);

            if (isset($_POST['data']['Campi']['Importo']))
            {

                $importo = $_POST['data']['Campi']['Importo'];

                $this->Campi->query("UPDATE Campi SET Importo = '$importo' WHERE Campo = $id");
            }



            if ($this->Campi->save())
            {
                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }


    function admin_orariAdd()
    {

        $this->write_file("_prenotazione", $_POST);

        $this->layout = "ajax";

        // GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - - fatto per mantenere la struttura precedente
        $ora_expl = explode(":", $this->data['CampiOrari']['Ora']['hour']);
        $this->data['CampiOrari']['Ora']['hour'] = $ora_expl[0];
        $this->data['CampiOrari']['Ora']['min'] = $ora_expl[1];
        $durata = $this->data['CampiOrari']['Durata'] = $this->data['CampiOrari']['Ora']['durata'];
        unset($this->data['CampiOrari']['Ora']['durata']);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        $ora = $this->data['CampiOrari']['Ora']['hour'] . ':' . $this->data['CampiOrari']['Ora']['min'] . ':00';
        $oraFine = date('H:i', strtotime("+{$durata} minutes", mktime($this->data['CampiOrari']['Ora']['hour'], $this->data['CampiOrari']['Ora']['min'] - 1, 0, 0, 0, 0)));

        $this->write_file("_prenotazione_ora_fine", $oraFine);

        if (isset($this->data['CampiOrari']['id']))
        {
            $plus = 'AND CampiOrari.id != "' . $this->data['CampiOrari']['id'] . '"';
        }
        else
        {
            $plus = '';
        }

        // GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
        // questa query controlla che l'orario inserito + la durata non vada a sovrapporsi ad altri orari gia inseriti piu avanti // in caso di nuovo inserimento

        $query = "
            
                SELECT 
                        * 
                FROM 
                        CampiOrari 
                WHERE 
                        `campo_id` =  '" . $this->data['CampiOrari']['campo_id'] . "' 
                        
                        AND `Giorno` = '" . $this->data['CampiOrari']['Giorno'] . "'  
                        AND (
                                (
                                        Ora BETWEEN '{$ora}' 
                                        AND '{$oraFine}'
                                ) 
                                OR (
                                        OraFine BETWEEN '{$ora}' 
                                        AND '{$oraFine}'
                                )
                        )
                        {$plus}
            
            ";

        // GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
        $this->write_file("_query__", $query);

        $giaOccupato = $this->Campi->query($query);
        // ---------------------------
//        $giaOccupato = $this->Campi->query(
//                "
//                  
//                  SELECT * FROM CampiOrari 
//                  WHERE CampiOrari.campo_id = '" . $this->data['CampiOrari']['campo_id'] . "'
//                  AND CampiOrari.Giorno = '" . $this->data['CampiOrari']['Giorno'] . "'
//                  " . $plus . "
//                  
//                  AND
//                  
//                  (
//                  
//                  ('$ora' BETWEEN CampiOrari.Ora AND ADDTIME(CampiOrari.Ora,'0:59'))
//                  
//                  OR 
//                  
//                  (ADDTIME('$ora','0:59') BETWEEN CampiOrari.Ora AND ADDTIME(CampiOrari.Ora,'0:59'))
//                  
//                  )
//
//                  "
//        );
//        if (!count($giaOccupato))

        if (!(count($giaOccupato))) // se entrambe le query danno risultato nullo
        {

            $this->write_file("_data__", $this->data);

            $this->CampiOrari->set($this->data);

            if ($this->CampiOrari->save())
            {
                // aggiorna gli orari di fine partita
                $this->editOrariTable();

                $ret = $this->CampiOrari->read(null, $this->CampiOrari->id);
                $error = 0;
            }
            else
            {

                $ret = $this->CampiOrari->invalidFields();
                $error = 1;
            }
        }
        else
        {
            $error = 'occupato';
            $ret = '';
        }


        // aggiorna gli orari di fine partita
        $this->editOrariTable();

        $this->set('result', json_encode(array('data' => $ret, 'error' => $error)));
        $this->render('/backend/ajaxResult');
    }


    private function editOrariTable()
    {
        // aggiorna gli orari di fine partita
        $sqlEditOraFine = "UPDATE `CampiOrari` SET `OraFine` = TIME( ADDTIME( TIME(CampiOrari.Ora), TIME( CONCAT( LPAD( (Durata-1) DIV 60 , 2, '0' ), ':', LPAD( (Durata - 1) % 60, 2, '0' ) ) ))%(TIME('24:00:00')) )";
        $this->select_sql($sqlEditOraFine);
    }


    function _admin_orariAdd()
    {

        $this->write_file("_prenotazione", $_POST);

        $this->layout = "ajax";

// GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
        $ora_expl = explode(":", $this->data['CampiOrari']['Ora']['hour']);
        $this->data['CampiOrari']['Ora']['hour'] = $ora_expl[0];
        $this->data['CampiOrari']['Ora']['min'] = $ora_expl[1];
        $durata = $this->data['CampiOrari']['Durata'] = $this->data['CampiOrari']['Ora']['durata'];
        unset($this->data['CampiOrari']['Ora']['durata']);
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        $ora = $this->data['CampiOrari']['Ora']['hour'] . ':' . $this->data['CampiOrari']['Ora']['min'] . ':00';

        if (isset($this->data['CampiOrari']['id']))
        {

            $plus = 'AND CampiOrari.id != "' . $this->data['CampiOrari']['id'] . '"';
        }
        else
        {
            $plus = '';
        }

// GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
// questa query controlla che l'orario inserito + la durata non vada a sovrapporsi ad altri orari gia inseriti piu avanti // in caso di nuovo inserimento
        $query = "
                    
                            SELECT 
                                    * 
                            FROM 
                                    CampiOrari 
                            WHERE 
                                    CampiOrari.campo_id = '" . $this->data['CampiOrari']['campo_id'] . "' 
                                    AND CampiOrari.Giorno = '" . $this->data['CampiOrari']['Giorno'] . "' 
                                    " . $plus . "
                                    AND (
                                            (
                                                    '$ora' BETWEEN CampiOrari.Ora 
                                                    AND ADDTIME(
                                                            CampiOrari.Ora, 
                                                            CONCAT(
                                                                    (CampiOrari.Durata DIV 60), 
                                                                    ':', 
                                                                    (CampiOrari.Durata % 60)-1
                                                            )
                                                    )
                                            ) 
                                            OR (
                                                    ADDTIME(
                                                            '$ora', 
                                                            CONCAT(
                                                                    (CampiOrari.Durata DIV 60), 
                                                                    ':', 
                                                                    (CampiOrari.Durata % 60)-1
                                                            )
                                                    ) BETWEEN CampiOrari.Ora 
                                                    AND ADDTIME(
                                                            CampiOrari.Ora, 
                                                                  CONCAT(
                                                                    (CampiOrari.Durata DIV 60), 
                                                                    ':', 
                                                                    (CampiOrari.Durata % 60)-1
                                                            )
                                                    )
                                            )
                                    )

                    ";

// GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
// questa query controlla che l'orario inserito + la durata non vada a sovrapporsi ad altri orari gia inseriti piu avanti // in caso di modifica
        $query2 = "          
                            SELECT 
                                    * 
                            FROM 
                                    CampiOrari 
                            WHERE 
                                    CampiOrari.campo_id = '" . $this->data['CampiOrari']['campo_id'] . "' 
                                    AND CampiOrari.Giorno = '" . $this->data['CampiOrari']['Giorno'] . "' 
                                    " . $plus . "
                                    AND (
                                            (
                                                    '$ora' BETWEEN CampiOrari.Ora 
                                                    AND ADDTIME(
                                                            CampiOrari.Ora, 
                                                            CONCAT(
                                                                    ({$durata} DIV 60), 
                                                                    ':', 
                                                                    ({$durata} % 60)-1
                                                            )
                                                    )
                                            ) 
                                            OR (
                                                    ADDTIME(
                                                            '$ora', 
                                                            CONCAT(
                                                                    ({$durata} DIV 60), 
                                                                    ':', 
                                                                    ({$durata} % 60)-1
                                                            )
                                                    ) BETWEEN CampiOrari.Ora 
                                                    AND ADDTIME(
                                                            CampiOrari.Ora, 
                                                                  CONCAT(
                                                                    ({$durata} DIV 60), 
                                                                    ':', 
                                                                    ({$durata} % 60)-1
                                                            )
                                                    )
                                            )
                                    )

                    ";

// GIUSEPPE 2023-02-20 - - - - - - - - - - - - - - - - - - - -
        $this->write_file("_query__", $query);
        $this->write_file("_query_2__", $query2);

        $giaOccupato = $this->Campi->query($query);
        $giaOccupato2 = $this->Campi->query($query2);
// ---------------------------
//        $giaOccupato = $this->Campi->query(
//                "
//                  
//                  SELECT * FROM CampiOrari 
//                  WHERE CampiOrari.campo_id = '" . $this->data['CampiOrari']['campo_id'] . "'
//                  AND CampiOrari.Giorno = '" . $this->data['CampiOrari']['Giorno'] . "'
//                  " . $plus . "
//                  
//                  AND
//                  
//                  (
//                  
//                  ('$ora' BETWEEN CampiOrari.Ora AND ADDTIME(CampiOrari.Ora,'0:59'))
//                  
//                  OR 
//                  
//                  (ADDTIME('$ora','0:59') BETWEEN CampiOrari.Ora AND ADDTIME(CampiOrari.Ora,'0:59'))
//                  
//                  )
//
//                  "
//        );
//        if (!count($giaOccupato))
        if (!(count($giaOccupato) + count($giaOccupato2))) // se entrambe le query danno risultato nullo
        {

            $this->write_file("_data__", $this->data);

            $this->CampiOrari->set($this->data);

            if ($this->CampiOrari->save())
            {

                $ret = $this->CampiOrari->read(null, $this->CampiOrari->id);
                $error = 0;
            }
            else
            {

                $ret = $this->CampiOrari->invalidFields();
                $error = 1;
            }
        }
        else
        {

            $error = 'occupato';
            $ret = '';
        }

        $this->set('result', json_encode(array('data' => $ret, 'error' => $error)));
        $this->render('/backend/ajaxResult');
    }


    function admin_orariDelete($id)
    {

        $this->layout = "ajax";

        if ($this->CampiOrari->delete($id))
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


    function getList()
    {

        $this->layout = null;

        $data = $this->Campi->find('all', array(
            'conditions' => array('Campi.isMidland' => 1, '(SELECT COUNT(*) FROM CampiOrari WHERE CampiOrari.campo_id = Campi.Campo) > 0'),
            'order' => 'Campi.Descrizione ASC',
        ));

        return $data;
    }

    /* Front end */


    function index($campo_id = null)
    {
        $this->title = 'Impianti';
        $this->facebook = true;

        if ($campo_id == null)
        {

            $campo_id = $this->Campi->find('list', array('fields' => array('Campi.Campo'), 'conditions' => array('Campi.disabled' => 0)));
            $detail = 0;
        }
        else
        {

            $this->set('booking', $this->booking($campo_id));
            $detail = 1;

            if (!empty($this->params['url']['widget']))
            {
                $this->layout = 'widget';
                return $this->render('disponibilita_widget');
            }
        }

        $this->layout = "content";

        $data = $this->Campi->find('all', array(
            'conditions' => array(
                'Campi.Campo' => $campo_id,
                'Campi.disabled' => 0,
                'Campi.isMidland' => 1,
            ),
            'order' => 'Campi.Descrizione ASC',
        ));

// Recupera i tornei per i campi
        foreach ($data as &$r)
        {
// debug($r);
            $r['Campionati'] = $this->__getCampionati($r['Campi']['Descrizione']);
        }


//Calcolo citta
        $citta = array();
        foreach ($data as $tmp)
        {
            $string = ucfirst(ereg_replace("[^a-zA-Z]", "", strtolower($tmp['Campi']['Citta'])));
            if (!empty($string))
                $citta[$string] = $tmp['Campi']['Citta'];
        }
        $citta = array_unique($citta);
        asort($citta);

//Tutti i campi
        $campis = $this->Campi->find('all', array(
            'conditions' => array(
                'Campi.Campo' => $this->Campi->find('list', array('fields' => array('Campi.Campo'), 'conditions' => array('Campi.disabled' => 0))),
                'Campi.disabled' => 0,
                'Campi.isMidland' => 1,
            ),
            'order' => 'Campi.Descrizione ASC',
        ));

        $this->set('data', $data);
        $this->set('citta', $citta);
        $this->set('detail', $detail);
        $this->set('campis', $campis);
    }


    private function __getCampionati($descrizioneCampo)
    {
        $categoria = $this->ChampCategory->find('first', [
            'conditions' => ['Nome' => $descrizioneCampo, 'disabled' => 0],
        ]);
        if (empty($categoria))
            return false;

        return array_reverse($categoria['Campionati']);
    }


    public function torneo($campoId, $id)
    {
        $r = $this->Campionati->find('first', ['conditions' => ['Campionato' => $id]]);
// debug($r);

        App::Import('Controller', 'Tennisimpianto');
        $torneo = new TennisimpiantoController;
        $torneo->constructClasses();

        $torneo->campionatoId = $r['Campionati']['Campionato'];
        $torneo->gironeId = $r['Half'][0]['GironeCampionato'];

        $torneo->buildFasiTorneo();

// debug($torneo->fasi);

        $this->layout = "content";
        $this->set(compact('r', 'campoId'));
        $this->set([
            'fasi' => $torneo->fasi,
            'maxFasi' => $torneo->maxFasi,
            'faseAttuale' => $torneo->faseAttuale,
            'squadreList' => $torneo->squadreList,
            'vincitore' => $torneo->vincitore,
        ]);
    }

    /* //GIUSEPPE 2019-03-15 ----------------------------------------------------------------------- */


    public function torneo_tab($id)
    { //GIUSEPPE 2019-03-15
        $girone = "";

        $result = array();

        $count_day = array();

        $res = mysql_query("SELECT * FROM `GironiCampionati` WHERE `Campionato` = $id");
        while ($row = mysql_fetch_assoc($res))
        {
            $girone = $row['GironeCampionato'];
        }

        $giornate = $this->requestAction('sections/read_giornate/' . $id . '/' . $girone); // questo valore lo troviamo nel controller 

        foreach ($giornate as $giornata)
        {

            $giornata['Risultato'] = $this->read_win($giornata);
            $result[$giornata['Calendari']['Giornata']][] = $giornata;
        }

        /* */

        $num_days = count($result);

        $pow = $num_days - 1;

        $init_num = pow(2, $pow);

//        file_put_contents("init_num.txt", $init_num);

        for ($key = 1;
                $key <= $num_days;
                $key++)
        {
            $count_day[$key] = $init_num;

            $init_num = $init_num / 2;
        }

//        file_put_contents("num_days.txt", print_r($num_days, true));
//        file_put_contents("count_day.txt", print_r($count_day, true));
//        file_put_contents("num_res.txt", count($result));

        $this->order_win($result, $count_day);

        $info_result = array();

        $atleti = array();

        foreach ($result as $key_day => $match)
        {
            foreach ($match as $single)
            {
                $info_result[$key_day][$single[0]['Casa_Id']]['punti'] = $single[0]['GoalCasa'];
                $info_result[$key_day][$single[0]['Casa_Id']]['campo'] = $single['Campi']['NomeCampo'];
                $info_result[$key_day][$single[0]['Casa_Id']]['nome'] = $single[0]['SquadraCasa'];
                $info_result[$key_day][$single[0]['Casa_Id']]['data'] = $single[0]['Data'];
                $info_result[$key_day][$single[0]['Casa_Id']]['ora'] = $single['Calendari']['Ora'];
                $info_result[$key_day][$single[0]['Casa_Id']]['set'] = $single[0]['SetCasa'];

                $info_result[$key_day][$single[0]['Trasferta_Id']]['punti'] = $single[0]['GoalTrasferta'];
                $info_result[$key_day][$single[0]['Trasferta_Id']]['campo'] = $single['Campi']['NomeCampo'];
                $info_result[$key_day][$single[0]['Trasferta_Id']]['nome'] = $single[0]['SquadraTrasferta'];
                $info_result[$key_day][$single[0]['Trasferta_Id']]['data'] = $single[0]['Data'];
                $info_result[$key_day][$single[0]['Trasferta_Id']]['ora'] = $single['Calendari']['Ora'];
                $info_result[$key_day][$single[0]['Trasferta_Id']]['set'] = $single[0]['SetTrasferta'];

                $set_array = json_decode($single[0]['SetCasa'], true);
                $athletes = $set_array['athletes'];
                foreach ($athletes as $atleta)
                {
                    $atleti[$atleta] = " (Atleta = $atleta) ";
                }
            }
        }


        $nominativi = $this->search_athletes($atleti);

        $this->set('atleti', $nominativi);
        $this->set('id_teams_rev', $_POST['id_teams_rev']);
        $this->set('info_result', $info_result);
        $this->set('result', $result);
        $this->set('count_day', $count_day);
    }


    private function search_athletes($atleti)
    {
        $res = array();

        $filter = implode(" OR ", $atleti);

        $sql = "SELECT Atleta, CONCAT(Cognome,' ',Nome) as Nominativo FROM Atleti WHERE ($filter)";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result))
        {
            $res[$row['Atleta']] = $row['Nominativo'];
        }

        return $res;
    }


    public function read_for_tab($id)
    {
        $girone = "";

        $result = array();

        $count_day = array();

        $res = mysql_query("SELECT * FROM `GironiCampionati` WHERE `Campionato` = $id");
        while ($row = mysql_fetch_assoc($res))
        {
            $girone = $row['GironeCampionato'];
        }

        $giornate = $this->requestAction('sections/read_giornate/' . $id . '/' . $girone); // questo valore lo troviamo nel controller 

        foreach ($giornate as $giornata)
        {

            $giornata['Risultato'] = $this->read_win($giornata);
            $result[$giornata['Calendari']['Giornata']][] = $giornata;
        }


        $num_days = count($result);

        $pow = $num_days - 1;

        $init_num = pow(2, $pow);

//        file_put_contents("init_num.txt", $init_num);

        for ($key = 1;
                $key <= $num_days;
                $key++)
        {
            $count_day[$key] = $init_num;

            $init_num = $init_num / 2;
        }

        echo json_encode($this->order_win($result, $count_day));

        exit;
    }


    private function order_win($result, $count_day)
    {
        $id_teams = array();

        $count_rev = array_reverse($count_day);

        foreach ($result as $day => $single)
        {

            foreach ($single as $detail)
            {
                $team['casa'] = $detail[0]['Casa_Id'];
                $team['trasferta'] = $detail[0]['Trasferta_Id'];

                $id_teams[$day][] = $team;
            }
        }

        $id_teams_rev = array_reverse($id_teams);

        foreach ($id_teams_rev as $key => $rev)
        {


            if (count($rev) !== $count_rev[$key])
            {

                $plus = $count_rev[$key] - count($rev);
                for ($i = 0;
                        $i < $plus;
                        $i++)
                {
                    $id_teams_rev[$key][] = array("casa" => "", "trasferta" => "");
                }
            }
        }

        return $id_teams_rev;
    }


    private function read_win($giornata)
    {
        $goal_casa = $giornata[0]['GoalCasa'];

        $goal_trasferta = $giornata[0]['GoalTrasferta'];

        $res = array();

        if ($goal_casa > $goal_trasferta)
        {
            $res['Vincente']['Squadra'] = $giornata[0]['SquadraCasa'];
            $res['Vincente']['Punti'] = $giornata[0]['GoalCasa'];

            $res['Perdente']['Squadra'] = $giornata[0]['SquadraTrasferta'];
            $res['Perdente']['Punti'] = $giornata[0]['GoalTrasferta'];
        }
        else
        {
            $res['Vincente']['Squadra'] = $giornata[0]['SquadraTrasferta'];
            $res['Vincente']['Punti'] = $giornata[0]['GoalTrasferta'];

            $res['Perdente']['Squadra'] = $giornata[0]['SquadraCasa'];
            $res['Perdente']['Punti'] = $giornata[0]['GoalCasa'];
        }

        return $res;
    }

    /* //------------------------------------------------------------------------------------------- */


    /* */

    /* BOOKING */


    function booking($campo_id)
    {

        $this->layout = null;

        $campo = $this->Campi->findByCampo($campo_id);

        $now = strtotime(date("Y-m-d h:i:s"));

        $giorni = array();

        $dow_query = "(";

        for ($i = 0;
                $i < 14;
                $i++)
        {

            $giorno['Data_it'] = date("d/m/Y", strtotime("+$i days", $now));
            $giorno['Data'] = date("Y-m-d", strtotime("+$i days", $now));
            $giorno['DayOfWeek'] = date("N", strtotime("+$i days", $now));

            $giorni[] = $giorno;

            $dow_query .= $giorno['DayOfWeek'] . ",";
        }

        $dow_query = substr($dow_query, 0, strlen($dow_query) - 1) . ")";
        $orari = $this->CampiOrari->find('all', array('conditions' =>
            array(
                'CampiOrari.campo_id' => $campo_id,
                'CampiOrari.Giorno IN ' . $dow_query
            ),
            'order' => array('CampiOrari.Ora ASC')
                )
        );

        foreach ($giorni as $i => $giorno)
        {

            $giorno['Orari'] = array();

            foreach ($orari as $orario)
            {

                $tmp['Occupato'] = 0;
                $tmp['Info'] = '';
                if ($orario['CampiOrari']['Giorno'] == $giorno['DayOfWeek'])
                {



                    $bookings = $this->CampiBooking->find('count', array(
                        'conditions' =>
                        array(
                            'CampiBooking.Data' => $giorno['Data'],
                            'CampiBooking.Ora' => $orario['CampiOrari']['Ora'],
                            'CampiBooking.campo_id' => $campo_id
                        )
                    ));

                    if ($bookings > 0)
                        $tmp['Occupato'] = 1;

                    $matches = $this->Match->find('first', array(
                        'conditions' =>
                        array(
                            'Match.Campo' => $campo_id,
                            'DATE_FORMAT(Match.Data,"%Y-%m-%d")' => $giorno['Data'],
                            'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $orario['CampiOrari']['Ora']
                        )
                    ));

                    if (!empty($matches))
                    {

                        $tmp['Occupato'] = 1;

                        $tmp['Info'] = $matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] .
                                "<br />" .
                                "<b>Campionato:</b><br>" . $matches['Campionati']['Nome'] . "<br />" .
                                "<b>Girone:</b><br>" . $matches['Half']['Descrizione'] . "<br />";
                    }

                    $giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'Importo' => $orario['CampiOrari']['Importo'], 'Occupato' => $tmp['Occupato'], 'Info' => $tmp['Info']);
                }
            }


            $giorni[$i] = $giorno;
        }

// $this->set('giorni',$giorni);
// $this->set('campo',$campo);
        $data = array(
            'giorni' => $giorni,
            'campo' => $campo,
        );

        file_put_contents("prenotazioni.txt", print_r($data, true));

        return $data;
    }


    function admin_booking($campo_id)
    {

        $this->layout = null;

        $campo = $this->Campi->findByCampo($campo_id);

        $now = strtotime(date("Y-m-d h:i:s"));

        $giorni = array();

        $dow_query = "(";

//Get next days
        $date_next = strtotime("+6 months");
        $datediff = $date_next - $now;
        $date_next_day = floor($datediff / (60 * 60 * 24));

        for ($i = $date_next_day;
                $i > 0;
                $i--)
        {

            $giorno['Data_it'] = date("d/m/Y", strtotime("-$i days", $now));
            $giorno['Data'] = date("Y-m-d", strtotime("-$i days", $now));
            $giorno['DayOfWeek'] = date("N", strtotime("-$i days", $now));

            $giorni[] = $giorno;

            $dow_query .= $giorno['DayOfWeek'] . ",";
        }


        for ($i = 0;
                $i < $date_next_day;
                $i++)
        {

            $giorno['Data_it'] = date("d/m/Y", strtotime("+$i days", $now));
            $giorno['Data'] = date("Y-m-d", strtotime("+$i days", $now));
            $giorno['DayOfWeek'] = date("N", strtotime("+$i days", $now));

            $giorni[] = $giorno;

            $dow_query .= $giorno['DayOfWeek'] . ",";
        }

        $dow_query = substr($dow_query, 0, strlen($dow_query) - 1) . ")";

        $orari = $this->CampiOrari->find('all', array('conditions' =>
            array(
                'CampiOrari.campo_id' => $campo_id,
                'CampiOrari.Giorno IN ' . $dow_query
            ),
            'order' => array('CampiOrari.Ora ASC')
                )
        );

        foreach ($giorni as $i => $giorno)
        {

            $giorno['Orari'] = array();

            foreach ($orari as $orario)
            {

                $tmp['Occupato'] = 0;
                $tmp['Info'] = '';

                if ($orario['CampiOrari']['Giorno'] == $giorno['DayOfWeek'])
                {

                    $bookings = $this->CampiBooking->find('first', array(
                        'conditions' =>
                        array(
                            'CampiBooking.Data' => $giorno['Data'],
                            'CampiBooking.Ora' => $orario['CampiOrari']['Ora'],
                            'CampiBooking.campo_id' => $campo_id
                        )
                    ));

                    if (is_array($bookings))
                        $tmp['Occupato'] = 1;

                    $matches = $this->Match->find('first', array(
                        'conditions' =>
                        array(
                            'Match.Campo' => $campo_id,
                            'DATE_FORMAT(Match.Data,"%Y-%m-%d")' => $giorno['Data'],
                            'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $orario['CampiOrari']['Ora']
                        )
                    ));

                    if (!empty($matches))
                    {

                        $tmp['Occupato'] = 1;

                        $tmp['Info'] = $matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] . " | " . $matches['Campionati']['Nome'] . " - " . $matches['Half']['Descrizione'];
                    }

                    if (is_array($bookings))
                        $giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'bookerNome' => $bookings['CampiBooking']['bookerNome'], 'bookerCognome' => $bookings['CampiBooking']['bookerCognome'], 'bookerEmail' => $bookings['CampiBooking']['bookerEmail'], 'bookerTelefono' => $bookings['CampiBooking']['bookerTelefono'], 'bookerId' => $bookings['CampiBooking']['id'], 'Importo' => $orario['CampiOrari']['Importo'], 'Occupato' => $tmp['Occupato'], 'Info' => $tmp['Info']);
                    else
                        $giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'Importo' => $orario['CampiOrari']['Importo'], 'Occupato' => $tmp['Occupato'], 'Info' => $tmp['Info']);
                }
            }


            $giorni[$i] = $giorno;
        }

// $this->set('giorni',$giorni);
// $this->set('campo',$campo);
        $data = array(
            'giorni' => $giorni,
            'campo' => $campo,
        );

        return $data;
    }


    function bookingSend()
    {

        $this->layout = "ajax";

        $this->CampiBooking->create();

        unset($this->data);

        $this->data['CampiBooking']['campo_id'] = $_POST['campo_id'];
        $this->data['CampiBooking']['bookerNome'] = $_POST['bookerNome'];
        $this->data['CampiBooking']['bookerCognome'] = $_POST['bookerCognome'];
        $this->data['CampiBooking']['bookerTelefono'] = $_POST['bookerTelefono'];
        $this->data['CampiBooking']['bookerEmail'] = $_POST['bookerEmail'];
        $this->data['CampiBooking']['Data'] = $_POST['Data'];
        $this->data['CampiBooking']['Ora'] = $_POST['Ora'];

        $this->CampiBooking->set($this->data);

        $campo = $this->Campi->findByCampo($_POST['campo_id']);

        $this->set('nome', $_POST['bookerNome']);
        $this->set('cognome', $_POST['bookerCognome']);
        $this->set('email', $_POST['bookerEmail']);
        $this->set('telefono', $_POST['bookerTelefono']);
        $this->set('campo', $campo);
        $this->set('data', date("d/m/Y", strtotime($_POST['Data'] . " " . $_POST['Ora'])));
        $this->set('ora', $_POST['Ora']);
        $this->set('importo', $_POST['Importo']);

        $this->set('data_real', $_POST['Data']);

        if ($this->CampiBooking->save())
        {

            $this->set('book_id', $this->CampiBooking->id);

            $this->set('booked', 1);

            $this->Email->to = $_POST['bookerEmail'];
            $this->Email->subject = 'Midland Sport | Prenotazione campo';
            $this->Email->template = 'booking_confirm';
            $this->Email->send();

            if (!empty($campo['Campi']['EmailGestore']))
            {


                if (!empty($campo['Campi']['EmailGestore2']))
                {


                    $this->Email->to = array($campo['Campi']['EmailGestore'], $campo['Campi']['EmailGestore2']);
                }
                else
                {
                    $this->Email->to = array($campo['Campi']['EmailGestore']);
                }
                $this->Email->subject = 'Midland Sport | Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'];
                $this->Email->template = 'booking_confirm_admin';
                $this->Email->send();
            }



            if (!empty($campo['Campi']['CellulareGestore']))
            {

//$campo['Campi']['CellulareGestore']="3274432619";
                $this->sendSms(
                        array('text' => 'Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'] . " " . date("d/m/Y H:i", strtotime($_POST['Data'] . " " . $_POST['Ora'])),
                            'dest' => '39' . $campo['Campi']['CellulareGestore']
                        )
                );
            }

            if (!empty($campo['Campi']['CellulareGestore2']))
            {


//$campo['Campi']['CellulareGestore2']="3274432619";
                $this->sendSms(
                        array('text' => 'Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'] . " " . date("d/m/Y H:i", strtotime($_POST['Data'] . " " . $_POST['Ora'])),
                            'dest' => '39' . $campo['Campi']['CellulareGestore2']
                        )
                );
            }
        }
        else
        {
            $this->set('booked', 0);
        }
    }


    function admin_bookingSend()
    {

        $this->layout = "ajax";
        $this->autoRender = false;

        $this->CampiBooking->create();

        $info = $this->Session->read('BookingData');

        $data_real = explode('/', $info['data']);

        $_POST['Data'] = $data_real[2] . "-" . $data_real[1] . "-" . $data_real[0];
        $_POST['Ora'] = $info['ora_real'];
        $_POST['campo_id'] = $info['campo'];

        unset($this->data);

        $this->data['CampiBooking']['campo_id'] = $_POST['campo_id'];
        $this->data['CampiBooking']['bookerNome'] = $_POST['bookerNome'];
        $this->data['CampiBooking']['bookerCognome'] = $_POST['bookerCognome'];
        $this->data['CampiBooking']['bookerTelefono'] = $_POST['bookerTelefono'];
        $this->data['CampiBooking']['bookerEmail'] = $_POST['bookerEmail'];
        $this->data['CampiBooking']['Data'] = $_POST['Data'];
        $this->data['CampiBooking']['Ora'] = $_POST['Ora'];

        $this->CampiBooking->set($this->data);

        $campo = $this->Campi->findByCampo($_POST['campo_id']);

        $this->set('nome', $_POST['bookerNome']);
        $this->set('cognome', $_POST['bookerCognome']);
        $this->set('email', $_POST['bookerEmail']);
        $this->set('telefono', $_POST['bookerTelefono']);
        $this->set('campo', $campo);
        $this->set('data', date("d/m/Y", strtotime($_POST['Data'] . " " . $_POST['Ora'])));
        $this->set('ora', $_POST['Ora']);
        $this->set('importo', $_POST['Importo']);

        $this->set('data_real', $_POST['Data']);

        if ($this->CampiBooking->save())
        {

            $this->set('book_id', $this->CampiBooking->id);

            $this->set('booked', 1);

            if (!empty($campo['Campi']['CellulareGestore']))
            {

//$campo['Campi']['CellulareGestore']="3274432619";
                $this->sendSms(
                        array('text' => 'Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'] . " " . date("d/m/Y H:i", strtotime($_POST['Data'] . " " . $_POST['Ora'])),
                            'dest' => '39' . $campo['Campi']['CellulareGestore']
                        )
                );
            }

            if (!empty($campo['Campi']['CellulareGestore2']))
            {


//$campo['Campi']['CellulareGestore2']="3274432619";
                $this->sendSms(
                        array('text' => 'Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'] . " " . date("d/m/Y H:i", strtotime($_POST['Data'] . " " . $_POST['Ora'])),
                            'dest' => '39' . $campo['Campi']['CellulareGestore2']
                        )
                );
            }


            $this->Email->to = $_POST['bookerEmail'];
            $this->Email->subject = 'Midland Sport | Prenotazione campo';
            $this->Email->template = 'booking_confirm';
            $this->Email->send();

            if (!empty($campo['Campi']['EmailGestore']))
            {
                $this->Email->to = $campo['Campi']['EmailGestore'];
                $this->Email->subject = 'Midland Sport | Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'];
                $this->Email->template = 'booking_confirm_admin';
                $this->Email->send();
            }


            print json_encode(array(
                'data' => $info['data'],
                'ora' => $info['ora_real']
            ));
        }
        else
        {
            $this->set('booked', 0);

            print mysql_error();

            print_r($this->CampiBooking->invalidFields());
        }
    }


    function bookingCancel($book_id)
    {

        Configure::Write('debug', 0);

        require_once APP . 'libs/Mobile_Detect.php';
        $detect = new Mobile_Detect();

        $layout = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');

        if ($layout == "mobile")
            $this->redirect('/mobile/bookingCancel/' . $book_id);

        $this->layout = "content";

        $booking = $this->CampiBooking->find('first', array(
            'conditions' => array(
                'MD5(CampiBooking.id)' => $book_id
            )
        ));

        if (!empty($booking))
            $this->set('booking', $booking);

        if ($this->CampiBooking->delete($booking['CampiBooking']['id']))
        {

            $campo = $this->Campi->findByCampo($booking['CampiBooking']['campo_id']);

            $this->set('nome', $booking['CampiBooking']['bookerNome']);
            $this->set('cognome', $booking['CampiBooking']['bookerCognome']);
            $this->set('email', $booking['CampiBooking']['bookerEmail']);
            $this->set('telefono', $booking['CampiBooking']['bookerTelefono']);
            $this->set('campo', $campo);
            $this->set('data', date("d/m/Y", strtotime($booking['CampiBooking']['Data'] . " " . $booking['CampiBooking']['Ora'])));
            $this->set('ora', $booking['CampiBooking']['Ora']);

            $this->set('data_real', $booking['CampiBooking']['Data']);

            $this->Email->to = $booking['CampiBooking']['bookerEmail'];
            $this->Email->subject = 'Midland Sport | Disdetta campo';
            $this->Email->template = 'booking_delete';
            $this->Email->send();
            /*
              if (!empty($campo['Campi']['EmailGestore'])) {
              $this->Email->to = $campo['Campi']['EmailGestore'];
              $this->Email->subject = 'Midland Sport | Disdetta campo da parte di ' . $booking['CampiBooking']['bookerNome'] . " " . $booking['CampiBooking']['bookerCognome'];
              $this->Email->template = 'booking_delete_admin';
              $this->Email->send();
              }

             */
        }
    }


    function admin_deleteBooking($book_id)
    {

        $this->autoRender = false;

        $booking = $this->CampiBooking->find('first', array(
            'conditions' => array(
                'CampiBooking.id' => $book_id
            )
        ));

        $deleted = 1;

        if ($this->CampiBooking->delete($booking['CampiBooking']['id']) && 1 == 0)
        {

            $campo = $this->Campi->findByCampo($booking['CampiBooking']['campo_id']);

            $this->set('nome', $booking['CampiBooking']['bookerNome']);
            $this->set('cognome', $booking['CampiBooking']['bookerCognome']);
            $this->set('email', $booking['CampiBooking']['bookerEmail']);
            $this->set('telefono', $booking['CampiBooking']['bookerTelefono']);
            $this->set('campo', $campo);
            $this->set('data', date("d/m/Y", strtotime($booking['CampiBooking']['Data'] . " " . $booking['CampiBooking']['Ora'])));
            $this->set('ora', $booking['CampiBooking']['Ora']);

            $this->set('data_real', $booking['CampiBooking']['Data']);

            $this->Email->to = $booking['CampiBooking']['bookerEmail'];
            $this->Email->subject = 'Midland Sport | Disdetta campo';
            $this->Email->template = 'booking_delete';
            $this->Email->send();

            if (!empty($campo['Campi']['EmailGestore']))
            {
                $this->Email->to = $campo['Campi']['EmailGestore'];
                $this->Email->subject = 'Midland Sport | Disdetta campo da parte di ' . $booking['CampiBooking']['bookerNome'] . " " . $booking['CampiBooking']['bookerCognome'];
                $this->Email->template = 'booking_delete_admin';
                $this->Email->send();
            }

            $deleted = 1;
        }

        die(json_encode(array('deleted' => $deleted)));
    }


    function saveBookingSession()
    {

        $this->layout = "ajax";

        $this->Session->delete('BookingData');
        $this->Session->write('BookingData', $_POST);

        exit;
    }


    function saveMapsSession()
    {

        $this->layout = "ajax";

        $this->Session->delete('MapsSession');
        $this->Session->write('MapsSession', $_POST);

        exit;
    }


    function booking_timmy()
    {
        $this->layout = "timmybox_web";
        $data = $this->Session->read('BookingData');

        $campo = $this->Campi->findByCampo($data['campo']);

        $this->set('campo', $campo);
    }


    function admin_booking_timmy()
    {
        $this->layout = "timmybox_web";
        $data = $this->Session->read('BookingData');

        $campo = $this->Campi->findByCampo($data['campo']);

        $this->set('campo', $campo);
    }


    function maps()
    {
        $this->layout = "timmybox_web";
    }


    function admin_exportHour($id_campo, $start = "", $end = "")
    {


        $this->set('booking', $this->admin_booking($id_campo));

        $data = $this->Campi->read(null, $id_campo);

        $this->set('nome', Inflector::Slug($data['Campi']['Descrizione']));

        if ($start != "" && $end != "")
        {
            $this->set('start', $start);
            $this->set('end', $end);
        }
        else
        {
            
        }
    }

    /* */


//GIUSEPPE 2022-08-23 
    function admin_prospetto()
    {
        $apiKey = $this->requestAction('apis/getApiKey');
        $this->set('apiKey', $apiKey);

//$date = strtotime("+1 hour", strtotime(date("Y-m-d")));

        $h_now = date("H:00"); // ora presente

        $date = strtotime("+4 hour", strtotime(date("H:00"))); // ora presente + 4
        $h_now_4 = date("H:i", $date);

        if ($h_now_4 < $h_now)
        {
            $h_now_4 = "23:59";
        }

        $this->set("h_now", $h_now);
        $this->set("h_now_4", $h_now_4);

        $week_now = sprintf("%s-W%s", date("Y"), date("W"));
        $date_now = date("Y-m-d");

        if (isset($_GET['referenceDate']))
        {
            $mktime = strtotime($_GET['referenceDate']);
            $date_now = $_GET['referenceDate'];
            //echo date("W",$mktime);
            $week_now = sprintf("%s-W%s", date("Y", $mktime), date("W", $mktime));
        }

        $this->set("week_now", $week_now);
        $this->set("date_now", $date_now);
        $date_interval = $this->requestAction("/apis/dalalSettInit/{$week_now}");
        $this->set("date_interval", $date_interval);

//GIUSEPPE 2023-01-17  - - - - - - - - - - - - - - - - - - - - 
        $this->set('listBookers', $this->listBookers());
//- - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - 

        $this->render('admin_prospetto');
    }


//GIUSEPPE 2023-01-17  - - - - - - - - - - - - - - - - - - - - 
    private function listBookers() // elenco a discesa nella prenotazione del campo
    {
        $res = [];

        $query = "SELECT 
                            * 
                    FROM 
                            `Bookers` 
                    ORDER BY 
                            CONCAT(Bookers.Cognome, Bookers.Nome) ASC";

        $bookers = $this->select_sql($query);

// {label: "Palermo", category: "South"}

        foreach ($bookers as $booker)
        {
            $blacklist = $booker['Blacklist'] == "1" ? " → Blacklist" : "";
            $b['label'] = "{$booker['Cognome']} {$booker['Nome']} - {$booker['Email']} {$blacklist}";
            $b['bookerCognome'] = "{$booker['Cognome']}";
            $b['bookerNome'] = "{$booker['Nome']}";
            $b['bookerEmail'] = "{$booker['Email']}";
            $b['bookerTelefono'] = "{$booker['Telefono']}";
            $b['bookerBlacklist'] = "{$booker['Blacklist']}";
            $b['bookerId'] = "{$booker['Booker']}";
            $res[] = $b;
        }

        return $res;
    }


    function admin_bookers() // pagina elenco bookers
    {
        $apiKey = $this->requestAction('apis/getApiKey');

        $this->set('apiKey', $apiKey);

        $this->render('admin_bookers');
    }


    function admin_tableBookers()
    {
        $filter = "";
        $blackList = "";

        if (isset($_POST['filter']))
        {
            $filter = trim($_POST['filter']);
        }

        if (isset($_POST['blackList']))
        {

            if ($_POST['blackList'] == true)
            {
                $blackList = " AND Blacklist = '1'";
            }
        }

        $this->write_file("_blackList", $_POST);

        $query = "SELECT 
                            * 
                    FROM 
                            `Bookers` 
                            
                    WHERE (`Cognome` LIKE '%{$filter}%' OR Nome LIKE '%{$filter}%' OR Email LIKE '%{$filter}%')
                    {$blackList}
                    ORDER BY 
                            CONCAT(Bookers.Cognome , ' ' ,Bookers.Nome) ASC";

        $this->write_file("_query_booker", $query);

        $listBookers = $this->select_sql($query);

        include '../views/campis/admin_bookers_table.ctp';

        print $html;

        exit();
    }


    function admin_editBooker()
    {
//$this->autoRender = false;


        $editBooker = $_POST['editBooker'];

        $s['data'] = $editBooker;

        $booker = $editBooker['Booker'];
        unset($editBooker['Booker']);

        $to_change = [];

        foreach ($editBooker as $key => $value)
        {
            $value = addslashes($value);
            $to_change[] = "`{$key}` = '{$value}'";
        }

        $columns = implode(", ", $to_change);

        $query = " UPDATE `Bookers`  SET     {$columns}      WHERE       `Booker` = '{$booker}'  ";

        $s['query'] = $query;
        $s['response'] = $this->my_query($query);

        print json_encode($s);

        exit();
    }


    function admin_deleteBooker()
    {
        $deleteBooker = $_POST['deleteBooker'];

        $query = "DELETE FROM `Bookers` WHERE `Bookers`.`Booker` = '{$deleteBooker}'";

        $res = $this->my_query($query);

        $_POST['res'] = $res;

        echo json_encode($_POST);

        exit();
    }


    function admin_analizeRecursiveDate() //GIUSEPPE 2023-04-15
    {
        $res = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $date_end_recursive = $post['date_end_recursive'];
        $date_init_recursive = $post['date_init_recursive'];
        $campo_id = $post['campo_id'];
        $ora = $post['ora'];

        /*
          date_end_recursive: "2023-06-04"
          date_init_recursive: "2023-04-26"

         */

        $date = $date_init_recursive;

        $filter_date = [];
        do
        {
//if ($date <= $date_end_recursive)
//echo $date . "; ";
            $filter_date[] = "'{$date}'";

            $addtime = strtotime("+7 days", strtotime($date));
            $date_format = date("Y-m-d", $addtime);

            $date = $date_format;
        }
        while ($date <= $date_end_recursive);

//        echo $json;
//        $filter_date['date_totali'] = $filter_date;
//        $filter_date['date_totali_n'] = count($filter_date['date_totali']) ;
//        $filter_date['date_occupate'] = $this->controllaDisponibilta($filter_date, $campo_id, $ora);
        $res = $this->controllaDisponibilta($filter_date, $campo_id, $ora);

//        echo json_encode($filter_date);
        echo json_encode($res);
        exit();
    }


    private function controllaDisponibilta($filter_date, $campo_id, $ora)
    {

        $interval = implode(", ", $filter_date);
        $sql = "SELECT * FROM `CampiBooking` WHERE campo_id = '{$campo_id}' AND Ora = '{$ora}' AND Data IN ({$interval})";

        $res_query = $this->select_sql($sql);
        $res['date_occupate'] = [];
        $res['date_occupate_n'] = count($res_query);
        $res['date'] = "";
        $res['sql'] = $sql;

        if (count($res_query) > 0)
        {
            foreach ($res_query as $key => $value)
            {
                $d = explode("-", $value['Data']);
                $res['date_occupate'][] = $d[2] . "\\" . $d[1] . "\\" . $d[0];
                $res['date_occupate_timestamp'][] = "'{$value['Data']}'";
            }
        }


        $ora = str_replace(":", ".", $ora);

        // campi occupati dai campionati
        $sql = "SELECT 
                        * 
                FROM 
                        `Calendari` 
                WHERE 
                        Campo = '{$campo_id}' 
                        AND Ora = '{$ora}' 
                        AND Data IN ({$interval})";

        $res_query_campionati = $this->select_sql($sql);

        $res['date_occupate_n'] += count($res_query_campionati);

        if (count($res_query_campionati) > 0)
        {
            foreach ($res_query_campionati as $key => $value)
            {
                $d = explode("-", $value['Data']);
                $res['date_occupate'][] = $d[2] . "\\" . $d[1] . "\\" . $d[0];
                $res['date_occupate_timestamp'][] = "'{$value['Data']}'";
            }
        }

        $res['date_totali_timestamp'] = $filter_date;
        $res['date_totali_n'] = count($filter_date);

//        $res['date_libere_timestamp'] = $this->estraiDateLibere($filter_date, $res['date_occupate_timestamp']);
        $calcola_liberi = $this->estraiDateLibere($filter_date, $res['date_occupate_timestamp']);
        $res = array_merge($res, $calcola_liberi);
        return $res;
    }


    private function estraiDateLibere($date_totali, $date_occupate)
    {
//        $this->write_file("_date_totali", $date_totali);
//        $this->write_file("_date_ocuupate", $date_occupate);
        $res = [];
        foreach ($date_totali as $data)
        {
            if (!in_array($data, $date_occupate))
            {
                $data = str_replace("'","", $data);
                $res['date_libere_timestamp'][] = $data;
                $expl = explode("-", $data);
                $res['date_libere'][] = sprintf("%s\\%s\\%s", $expl[2], $expl[1], $expl[0]);
            }
        }
        $res['date_libere_n'] = count($res['date_libere_timestamp']);

//        $this->write_file("_date_libere", $res);

        return $res;
    }

    //- - -- - - - - - - - - - - - - - - - - - - - - - - - - - - -
}


$nome_casa = $info_result[$day + 1][$teams['casa']]['nome'];
$punti_casa = $info_result[$day + 1][$teams['casa']]['punti'];

$nome_trasferta = $info_result[$day + 1][$teams['trasferta']]['nome'];
$punti_trasferta = $info_result[$day + 1][$teams['trasferta']]['punti'];

$data = $info_result[$day + 1][$teams['casa']]['data'];
$ora = $info_result[$day + 1][$teams['casa']]['ora'];
$campo = $info_result[$day + 1][$teams['casa']]['campo'];

$winner_casa = "";
$winner_trasferta = "";

$game_top = "";
$game_bottom = "";

if (isset($result[$day][$i]))
{
    $casa = $result[$day][$i][0]['SquadraCasa'];
    $punti_casa = $result[$day][$i][0]['GoalCasa'];

    $trasferta = $result[$day][$i][0]['SquadraTrasferta'];
    $punti_trasferta = $result[$day][$i][0]['GoalTrasferta'];

    if ((int) $punti_casa > (int) $punti_trasferta)
        $winner_casa = "winner";
    else
        $winner_trasferta = "winner";


    $data = $result[$day][$i][0]['Data'];
    $ora = $result[$day][$i]['Calendari']['Ora'];

    $campo = $result[$day][$i]['Campi']['NomeCampo'];

    $game_top = "game-top";
    $game_bottom = "game-bottom";
}