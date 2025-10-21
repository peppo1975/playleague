<?


//file nuovo
//GIUSEPPE 2022-08-23

class Api extends AppModel
{

    // valori per attivare il controller
    var $name = 'Athletes';
    var $useTable = 'Atleti';
    var $primaryKey = 'Atleta';
    // valori per attivare il controller

    private $api_key = "b621-386594c0895e";
    private $db;
    private $playLeague = "1";
    protected $goal_partite = []; //GIUSEPPE 2022-09-13
    protected $squadre_campionati_array = []; //GIUSEPPE 2022-09-13
    protected $atleti_array = []; //GIUSEPPE 2022-09-13
    protected $array_calendario = [];

    /**
     * Array delle consonanti
     */
    protected $_consonanti = array(
        'B',
        'C',
        'D',
        'F',
        'G',
        'H',
        'J',
        'K',
        'L',
        'M',
        'N',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    );

    /**
     * Array delle vocali
     */
    protected $_vocali = array(
        'A',
        'E',
        'I',
        'O',
        'U'
    );

    /**
     * Array per il calcolo della lettera del mese
     * Al numero del mese corrisponde una lettera
     */
    protected $_mesi = array(
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'H',
        7 => 'L',
        8 => 'M',
        9 => 'P',
        10 => 'R',
        11 => 'S',
        12 => 'T'
    );

    /**
     * CIFRA CONTROLLO
     */
    protected $_pari = array(
        '0' => 0,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
        'E' => 4,
        'F' => 5,
        'G' => 6,
        'H' => 7,
        'I' => 8,
        'J' => 9,
        'K' => 10,
        'L' => 11,
        'M' => 12,
        'N' => 13,
        'O' => 14,
        'P' => 15,
        'Q' => 16,
        'R' => 17,
        'S' => 18,
        'T' => 19,
        'U' => 20,
        'V' => 21,
        'W' => 22,
        'X' => 23,
        'Y' => 24,
        'Z' => 25
    );

    /**
     * CIFRA CONTROLLO
     */
    protected $_dispari = array(
        '0' => 1,
        '1' => 0,
        '2' => 5,
        '3' => 7,
        '4' => 9,
        '5' => 13,
        '6' => 15,
        '7' => 17,
        '8' => 19,
        '9' => 21,
        'A' => 1,
        'B' => 0,
        'C' => 5,
        'D' => 7,
        'E' => 9,
        'F' => 13,
        'G' => 15,
        'H' => 17,
        'I' => 19,
        'J' => 21,
        'K' => 2,
        'L' => 4,
        'M' => 18,
        'N' => 20,
        'O' => 11,
        'P' => 3,
        'Q' => 6,
        'R' => 8,
        'S' => 12,
        'T' => 14,
        'U' => 16,
        'V' => 10,
        'W' => 22,
        'X' => 25,
        'Y' => 24,
        'Z' => 23
    );

    /**
     * CIFRA CONTROLLO
     */
    protected $_controllo = array(
        '0' => 'A',
        '1' => 'B',
        '2' => 'C',
        '3' => 'D',
        '4' => 'E',
        '5' => 'F',
        '6' => 'G',
        '7' => 'H',
        '8' => 'I',
        '9' => 'J',
        '10' => 'K',
        '11' => 'L',
        '12' => 'M',
        '13' => 'N',
        '14' => 'O',
        '15' => 'P',
        '16' => 'Q',
        '17' => 'R',
        '18' => 'S',
        '19' => 'T',
        '20' => 'U',
        '21' => 'V',
        '22' => 'W',
        '23' => 'X',
        '24' => 'Y',
        '25' => 'Z'
    );


    function __construct()
    {
        parent::__construct();

        $this->db = new Controller();
    }


    public function isApiKey()
    {
        if (!isset($_GET['api_key'])) {
            exit();
        }

        $api_key = $_GET['api_key'];

        if ($api_key != $this->api_key) {
            exit();
        }
    }


    public function getApiKey()
    {
        return $this->api_key;
    }


    public function campi($sport, $date, $filter_campi)
    {

        $array_filter = [];
        $filter = "";
        $array_filter_campi = "";

        //GIUSEPPE 2023-07-28 ----------------------------
        $idUente = $_SESSION['User']['id'];
        $grop_id = $_SESSION['User']['group_id'];
        //-------------------------------------------------

        foreach ($sport as $key => $value) {
            if ($value == 0)
                continue;

            $array_filter[] = "{$key} = '1'";
        }

        $filter = implode(" OR ", $array_filter);

        $our_init = $date['our-init'];
        $our_end = $date['our-end'];

        if (count($filter_campi)) {
            $implode_campi = implode(", ", $filter_campi);
            $array_filter_campi = "Campi.Campo IN({$implode_campi})  AND ";
        }


        $query = "
            
            SELECT 
                    Campi.Campo, 
                    Campi.Descrizione, 
                    Campi.is5, 
                    Campi.is7, 
                    CampiOrari.Giorno, 
                    CampiOrari.Ora,
                    CampiOrari.Importo
            FROM 
                    `Campi` 
                    INNER JOIN CampiOrari ON CampiOrari.campo_id = Campi.Campo 
            WHERE 
                 {$array_filter_campi}  ({$filter}) AND (CampiOrari.Ora >= '{$our_init}' AND CampiOrari.Ora <= '{$our_end}')
            ORDER BY Campi.Descrizione ASC,CampiOrari.Ora ASC
            
            ";

        if ($grop_id == 15) {
            $query = "
            
            SELECT 
                    Campi.Campo, 
                    Campi.Descrizione, 
                    Campi.is5, 
                    Campi.is7, 
                    CampiOrari.Giorno, 
                    CampiOrari.Ora,
                    CampiOrari.Importo
            FROM 
                    `Campi` 
                    INNER JOIN CampiOrari ON CampiOrari.campo_id = Campi.Campo
                    INNER JOIN CampiUtenti ON CampiUtenti.Campo = Campi.Campo
            WHERE 
                 {$array_filter_campi}  ({$filter}) AND (CampiOrari.Ora >= '{$our_init}' AND CampiOrari.Ora <= '{$our_end}') AND CampiUtenti.Utente = '{$idUente}'
            ORDER BY Campi.Descrizione ASC,CampiOrari.Ora ASC
            ";
        }

        $res = $this->db->select_sql($query);

        $this->db->write_file("_select_campi_main", $query);

        $this->creaStruttura($res);

        return $res;
    }


    private function creaStruttura(&$res) // puntatore
    {

        $arrStruct = [];

        foreach ($res as $id => $value) {

            $arrStruct[$value['Campo']]['Descrizione'] = $value['Descrizione'];
            $arrStruct[$value['Campo']]['is5'] = $value['is5'];
            $arrStruct[$value['Campo']]['is7'] = $value['is7'];
            $arrStruct[$value['Campo']]['Giorni'] = [1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => [], 7 => []];
            //            $arrStruct[$value['Campo']]['GiorniDate'] = $giorni_date;
        }

        foreach ($res as $id => $value) {
            $arrStruct[$value['Campo']]['Giorni'][$value['Giorno']][$value['Ora']]['stato'] = "L";
            $arrStruct[$value['Campo']]['Giorni'][$value['Giorno']][$value['Ora']]['text'] = "libero";
            $arrStruct[$value['Campo']]['Giorni'][$value['Giorno']][$value['Ora']]['values'] = "";
            $arrStruct[$value['Campo']]['Giorni'][$value['Giorno']][$value['Ora']]['importo'] = $value['Importo'];
        }

        $res = $arrStruct;
    }


    public function cercaPrenotazioni($array_id, $date_interval)
    {
        $res = [];

        $our_init = $date_interval['our-init'];
        $our_end = $date_interval['our-end'];
        $weekpicker = $date_interval['weekpicker'];

        $dal_al = $this->calcolaSettimanaTimeStamp($weekpicker);
        $dal = $dal_al[0];
        $al = $dal_al[1];
        $filter_campi = implode(", ", $array_id);

        $queryCampionati = " 
                            SELECT 
                                    Calendari.Calendario, 
                                    Calendari.Campionato, 
                                    Calendari.Casa,
                                    Calendari.Trasferta,
                                    Calendari.Data, 
                                    Calendari.Ora,
                                    Calendari.Campo,
                                    Campionati.Nome AS NomeCampionato
                             FROM 
                                     `Calendari` 
                                     INNER JOIN Campi ON Calendari.campo = Campi.Campo 
                                     INNER JOIN Campionati ON Calendari.Campionato = Campionati.Campionato
                             WHERE 
                                     Calendari.Campo IN ({$filter_campi}) 
                                     AND 
                                     (Calendari.Data >= '{$dal}'  AND Calendari.Data <= '{$al}')
                                     AND
                                     (Calendari.Ora >= '{$our_init}' AND Calendari.Ora <= '{$our_end}')
                            ";
        //        $this->db->write_file("_query_prenotazioni_campionati", $queryCampionati);

        $prenotazioniCampionati = $this->db->select_sql($queryCampionati);

        $this->arrayCalendarioCampionati($prenotazioniCampionati);

        $this->strutturaPrenotazioni($prenotazioniCampionati);

        $queryPrivati = "
                            SELECT 
                                    CampiBooking.*, 
                                    CampiBooking.Prenotazione as CP,
                                    (SELECT COUNT(CampiBooking.Prenotazione) FROM CampiBooking WHERE CampiBooking.Prenotazione=CP) as MULTI,
                                    campo_id as Campo 
                            FROM 
                                    `CampiBooking` 
                            WHERE 
                                    campo_id IN ({$filter_campi})
                                    AND 
                                    (Data >= '{$dal}' AND Data <= '{$al}')   
                                    AND
                                    (CampiBooking.Ora >= '{$our_init}' AND CampiBooking.Ora <= '{$our_end}')
                                        
                        ";

        $this->db->write_file("_query_prenotazioni_privati", $queryPrivati);

        $prenotazioniPrivati = $this->db->select_sql($queryPrivati);

        $this->strutturaPrenotazioni($prenotazioniPrivati);

        $res['Campionati'] = $prenotazioniCampionati;
        $res['Privati'] = $prenotazioniPrivati;

        return $res;
    }


    private function strutturaPrenotazioni(&$elenco_prenotazioni)
    {
        $res = [];
        foreach ($elenco_prenotazioni as $value) {
            $campo = $value['Campo'];
            $data = $value['Data'];
            $ora = $value['Ora'];

            $giorno_settimana = $this->giornoData($data);

            $res[$campo][$giorno_settimana]['Data'] = $data;
            $res[$campo][$giorno_settimana]['Ora'][$ora] = $value;
        }
        $elenco_prenotazioni = $res;
    }


    private function arrayCalendarioCampionati(&$elenco_prenotazioni)
    {
        $res = [];
        $final = [];

        foreach ($elenco_prenotazioni as $value) {
            $calendario = $value['Calendario'];
            $res[$calendario] = true;
        }

        $keys = array_keys($res);

        $keys_filter = implode(", ", $keys);

        $query = "SELECT 
                        GoalPartite.Calendario, 
                        GoalPartite.SquadraCampionato,
                        SquadreCampionati.Squadra,
                        Squadre.Denominazione
                    FROM 
                        GoalPartite 
                        INNER JOIN SquadreCampionati ON GoalPartite.SquadraCampionato = SquadreCampionati.SquadraCampionato 
                        INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra
                    WHERE 
                            `Calendario` IN ($keys_filter) 
                    GROUP BY 
                            GoalPartite.`SquadraCampionato` 
                    ORDER BY 
                            `GoalPartite`.`Calendario` ASC";

        $elenco = $this->db->select_sql($query);

        foreach ($elenco as $value) {
            $calendario = $value['Calendario'];
            $squadra = $value['SquadraCampionato'];
            $final[$calendario][$squadra] = $value['Denominazione'];
        }

        foreach ($elenco_prenotazioni as $key => $value) {
            $calendario = $value['Calendario'];
            $elenco_prenotazioni[$key]['Squadre'] = $final[$calendario];
        }


        //        $this->db->write_file("_elenco_calendari_prenotazioni_campionati", $elenco_prenotazioni);
        //        $this->db->write_file("_elenco_calendari", $keys_filter);
        //        $this->db->write_file("_elenco_calendari_query", $query);
        //        $this->db->write_file("_elenco_calendari_elenco", $elenco);
        //        $this->db->write_file("_elenco_calendari_elenco_ordinato", $final);

        return $final;
    }


    public function mergeCampiPrenotazioni(&$campi, &$prenotazioni)
    {
        $campionati = $prenotazioni['Campionati'];
        $privati = $prenotazioni['Privati'];

        // merge campionati ---------------------------------------------
        foreach ($campionati as $id_campo => $value_campo) {

            foreach ($value_campo as $giorno_settimana => $orari) {
                foreach ($orari['Ora'] as $ora => $info_ora) {
                    $ora = str_replace(".", ":", $ora);

                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora . ":00"]['stato'] = "C";
                    //                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora . ":00"]['text'] = sprintf("Campionato:<br>&nbsp;<strong>%s</strong> <br>&nbsp; - %s <br>&nbsp;&nbsp;vs<br>&nbsp; - %s", $info_ora['NomeCampionato'], $info_ora['Squadre'][$info_ora['Casa']], $info_ora['Squadre'][$info_ora['Trasferta']]);
                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora . ":00"]['text'] = "Campionato";
                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora . ":00"]['values'] = ["NomeCampionato" => $info_ora['NomeCampionato'], "SquadraCasa" => $info_ora['Squadre'][$info_ora['Casa']], "SquadraTrasferta" => $info_ora['Squadre'][$info_ora['Trasferta']]];
                }
            }
        }


        // merge privati ---------------------------------------------
        foreach ($privati as $id_campo => $value_campo) {

            foreach ($value_campo as $giorno_settimana => $orari) {
                foreach ($orari['Ora'] as $ora => $info_ora) {
                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora]['stato'] = "P";
                    //                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora]['text'] = sprintf("Privato:<br>&nbsp;%s %s<br>&nbsp;(%s)", $info_ora['bookerNome'], $info_ora['bookerCognome'], $info_ora['bookerEmail']);
                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora]['text'] = "Privato:";
                    $campi[$id_campo]['Giorni'][$giorno_settimana][$ora]['values'] = [
                        "bookerNome" => $info_ora['bookerNome'],
                        "bookerCognome" => $info_ora['bookerCognome'],
                        "bookerEmail" => $info_ora['bookerEmail'],
                        "bookerTelefono" => $info_ora['bookerTelefono'],
                        "id" => $info_ora['id'],
                        "Note" => $info_ora['Note'],
                        "Importo" => $info_ora['Importo'],
                        "Prenotazione" => $info_ora['Prenotazione'],
                        "MULTI" => $info_ora['MULTI'],
                        "Pagato" => $info_ora['Pagato']
                    ];
                }
            }
        }
    }


    private function orderBubbleSort($arrStruct)
    {

        $temp = "";
        $exit = false;

        $len = sizeof($arrStruct);
        do {
            $exit = true;
            for ($i = 0; $i < $len - 1; $i++) {
                if ($arrStruct[$i] > $arrStruct[$i + 1]) {
                    $exit = false;
                    $temp = $arrStruct[$i];
                    $arrStruct[$i] = $arrStruct[$i + 1];
                    $arrStruct[$i + 1] = $temp;
                }
            }
        } while (!$exit);

        return $arrStruct;
    }


    public function calcolaSettimanaTimeStamp($anno_settimana)
    {
        $expl = explode("-", $anno_settimana);
        $anno = $expl[0];
        $Sett = str_replace("W", "", $expl[1]);

        $data = new DateTime();
        $data->setISODate($anno, $Sett, 1);
        $dal = $data->format('Y-m-d');
        $data->setISODate($anno, $Sett, 7);
        $al = $data->format('Y-m-d');
        return [$dal, $al];
    }


    public function giornoData($Ymd)
    {
        $gShort = array('Dom', 'Lun', 'Mart', 'Merc', 'Giov', 'Ven', 'Sab');
        $expl = explode("-", $Ymd);
        //        $ts = mktime(0, 0, 0, $m, $g, $a);
        $ts = mktime(0, 0, 0, $expl[1], $expl[2], $expl[0]);
        $gd = getdate($ts);

        $res = "";

        switch ($gd['wday']) {
            case 0:
                $res = $gd['wday'] + 7;
                break;

            default:
                $res = $gd['wday'];
                break;
        }

        //        return $gd['wday'];
        return $res;
    }


    public function intervalloDateWeek($anno_settimana)
    {
        $expl = explode("-", $anno_settimana);
        $anno = $expl[0];
        $Sett = str_replace("W", "", $expl[1]);

        $res = [];

        $data = new DateTime();

        for ($i = 1; $i <= 7; $i++) {
            $data->setISODate($anno, $Sett, $i);
            $res['regular'][$i] = $data->format('d/m/Y');
            $res['timestamp'][$i] = $data->format('Y-m-d');
        }

        return $res;
    }


    public function saveBooking()
    {
        $this->db->write_file("_booker_insert", $_POST);
        $cangheState = $_POST['changeState'];
        unset($_POST['Stato']);
        unset($_POST['changeState']);

        $toBooking = $_POST;

        //        $this->db->write_file("_booker_insert", $_POST);

        $res = [];

        switch ($cangheState) {
            case "L":

                $toBooking["risposta"] = "da cancellare";
                $id = $toBooking['id'];
                $query = "DELETE FROM `CampiBooking` WHERE `CampiBooking`.`id` = '{$id}'";
                $this->db->my_query($query);
                break;

            case "P":
                $toBooking["created"] = date("Y-m-d H:i:s");
                $toBooking["modified"] = date("Y-m-d H:i:s");

                $bookerEmail = $toBooking['bookerEmail'];

                $query = "SELECT COUNT(Booker) AS NumBooker, Booker FROM Bookers WHERE Email = '{$bookerEmail}' ";

                $result = $this->db->select_sql($query)[0];

                //$toBooking["result"] = $result;

                if ($result['NumBooker'] == 0) {

                    /*
                      bookerCognome: "sdfsdf"
                      bookerEmail: "f@l.it"
                      bookerNome: "sdfsf"
                      bookerTelefono: "12314"

                     */

                    // nuovo inserimento
                    $toInsert['Cognome'] = $toBooking["bookerCognome"];
                    $toInsert['Nome'] = $toBooking["bookerNome"];
                    $toInsert['Email'] = $toBooking["bookerEmail"];
                    $toInsert['Telefono'] = $toBooking["bookerTelefono"];

                    $last_id = $this->db->insert_into("Bookers", $toInsert, true)['last_id'];

                    $toBooking['Booker'] = $last_id;
                    //                    
                    // poi select e prendo l'id
                }
                if ($result['NumBooker'] == 1) {
                    // prendo l'id

                    $toBooking['Booker'] = $result['Booker'];
                }


                $this->db->write_file("_insertBooking", $toBooking);

                //uinquid per id prenotazione (utile per le ricorsività)
                $prenotazione = uniqid() . rand(1000, 9999) . rand(1000, 9999);
                $toBooking['Prenotazione'] = $prenotazione;

                foreach ($_POST['Data'] as $date) {
                    $toBooking['Data'] = $date;
                    $this->db->insert_into("CampiBooking", $toBooking);
                }


                $toBooking["risposta"] = "da prenotare";
                $toBooking['Data'] = $_POST['Data'];

                break;
        }

        return $toBooking;
    }


    public function editBooking()
    {
        $id = $_POST['id_booking'];
        unset($_POST['id_booking']);

        $f = [];
        foreach ($_POST as $key => $value) {
            $f[] = "`{$key}` = '{$value}'";
        }

        $filter = implode(", ", $f);

        $query = "
            UPDATE 
                    `CampiBooking` 
            SET 
               {$filter}
            WHERE 
                    `CampiBooking`.`id` = '{$id}'
            
            ";

        $this->db->my_query($query);

        $this->db->write_file("_query_edit", $query);
    }


    //GIUSEPPE 2023-01-17  - - - - - - - - - - - - - - - - - - - - 


    public function nomeCampo($post)
    {
        $campo_id = $_POST['campo_id'];
        $ora = $_POST['Ora'];
        $data = $_POST['Data'];
        $dayOfWeek = date('w', strtotime($data)) == 0 ? "7" : date('w', strtotime($data));

        $query = "SELECT Descrizione FROM Campi WHERE Campo = '{$campo_id}'";

        $nome_campo = $this->db->select_sql($query)[0];

        $query = "SELECT  Importo FROM CampiOrari WHERE campo_id = '{$campo_id}' AND Giorno = '{$dayOfWeek}' AND Ora = '{$ora}'";
        $nome_campo['Importo'] = $this->db->select_sql($query)[0]['Importo'];

        $this->db->write_file("_CampiOrari_query", $query);

        //$nome_campo['Descrizione'];

        return $nome_campo;
    }


    public function searchEmailBooking($post)
    {
        $res = [];
        $searchEmailBooking = trim($post['searchEmailBooking']);
        $res['searchEmailBooking'] = $searchEmailBooking;

        $query = "SELECT COUNT(Email) as NumEmail FROM Bookers WHERE Email = '{$searchEmailBooking}'";

        $res['NumEmail'] = $this->db->select_sql($query)[0]['NumEmail'];
        $res['query'] = $query;

        print json_encode($res);
        exit();
    }


    public function upgradeBookers()
    {
        $query = "
                    SELECT 
                            * 
                    FROM 
                            `CampiBooking` 
                    GROUP BY 
                            bookerEmail 
                    
            ";

        $booker_all = $this->db->select_sql($query);

        // per evitare di inserire due volte una mail , ho creatouna chiave unica per email

        foreach ($booker_all as $booker) {
            $values['email'] = trim($booker['bookerEmail']);
            $values['nome'] = trim($booker['bookerNome']);
            $values['cognome'] = trim($booker['bookerCognome']);
            $values['telefono'] = trim($booker['bookerTelefono']);

            $res = $this->db->insert_into("Bookers", $values, true);

            print_r($res);
            print "<br>";

            $id = $res['last_id'];

            $query_edit = "UPDATE `CampiBooking` SET `Booker` = '{$id}' WHERE `bookerEmail` = '{$values['email']}'";

            $this->db->my_query($query_edit);
        }

        exit();
    }


    public function filtraCampi($post)
    {
        $campi = [];

        $sport = $post['sport'];
        $date = $post['date'];

        //GIUSEPPE 2023-07-28 ----------------------------
        $idUente = $_SESSION['User']['id'];
        $grop_id = $_SESSION['User']['group_id'];
        //-------------------------------------------------

        $array_filter = [];
        $filter = "";
        foreach ($sport as $key => $value) {
            if ($value == 0)
                continue;

            $array_filter[] = "{$key} = '1'";
        }

        $filter = implode(" OR ", $array_filter);

        $our_init = $date['our-init'];

        $our_end = $date['our-end'];

        $inner_join = $this->campiUtente();

        $query = "
            
            SELECT 
                    Campi.Campo, 
                    Campi.Descrizione
                    #Campi.is5, 
                    #Campi.is7, 
                    #CampiOrari.Giorno, 
                    #CampiOrari.Ora,
                    #CampiOrari.Importo
            FROM 
                    `Campi` 
                    INNER JOIN CampiOrari ON CampiOrari.campo_id = Campi.Campo 
            WHERE 
                    ({$filter}) AND (CampiOrari.Ora >= '{$our_init}' AND CampiOrari.Ora <= '{$our_end}')
            GROUP BY Campi.Campo
            ORDER BY Campi.Descrizione ASC
            ";

        if ($grop_id == 15) {
            $query = "
            
                SELECT 
                        Campi.Campo, 
                        Campi.Descrizione
                        #Campi.is5, 
                        #Campi.is7, 
                        #CampiOrari.Giorno, 
                        #CampiOrari.Ora,
                        #CampiOrari.Importo
                FROM 
                        `Campi` 
                        INNER JOIN CampiOrari ON CampiOrari.campo_id = Campi.Campo 
                INNER JOIN CampiUtenti ON CampiUtenti.Campo = Campi.Campo
                WHERE 
                        ({$filter}) AND (CampiOrari.Ora >= '{$our_init}' AND CampiOrari.Ora <= '{$our_end}') AND CampiUtenti.Utente = '{$idUente}'
                GROUP BY Campi.Campo
                ORDER BY Campi.Descrizione ASC
            ";
        }

        $campi = $this->db->select_sql($query);

        $this->db->write_file("_select_campi", $query);
        $this->db->write_file("_session", $_SESSION);

        return $campi;
    }


    public function aggiornaBookersNewsLetters()
    {
        // ATTENZIONE DA `newsletters_users` non cancello gli utenti perchè un utente potrebbe essere anche altro rispetto al booker (es: atleta, allenatore..)
        // quindi viene cancellato solo dalla tabella `Bookers` e dalla tabella di associazione `newsletters_groups_users`
        $name = "Noleggiatori campi";

        $id_news_letters = $this->db->select_sql("SELECT id FROM `newsletters_groups` WHERE title = LOWER('{$name}')")[0]['id']; // selezione l'id del gruppo newsletter
        $bookers = $this->db->select_sql("SELECT  TRIM(LOWER(Bookers.Email)) AS email FROM  Bookers"); // selezione gli id dei bookers

        $array_for_filter = [];
        $array_for_search_duplicate = [];

        foreach ($bookers as $booker) {
            if ($booker['email'] != "") {
                $array_for_filter[] = "'" . $booker['email'] . "'"; // serve per cercare nella tabella `newsletters_users`
                $array_for_search_duplicate[$booker['email']] = true; // serve per confrontare
            }
        }

        $in = implode(", ", $array_for_filter);
        echo "<br>Name group: '{$name}'";
        echo "<br> query: SELECT id FROM `newsletters_groups` WHERE title = LOWER('{$name}')";
        echo "<br>Id Newsletters: '{$id_news_letters}'<br>";
        echo count($bookers) . " - bookers<br>";
        echo count($array_for_filter) . " - for filter<br>";
        echo count($array_for_search_duplicate) . " - for duplicate<br>";

        // cerco le email dei bookers che sono state memorizzare fin'ora in `newsletters_users`
        $inserted_list = $this->db->select_sql("SELECT TRIM(LOWER(email)) as email FROM `newsletters_users` WHERE TRIM(LOWER(email)) IN ({$in})");
        echo count($inserted_list) . " - newsletters_users<br>";

        foreach ($inserted_list as $inserted) {
            unset($array_for_search_duplicate[$inserted['email']]); // tolgo le email gia presenti in `newsletters_users` in modo da lasciare quelle che non sono state ancora inserite;
        }

        echo count($array_for_search_duplicate) . " - not present after filter<br>";

        $last_id_insert = [];
        foreach ($array_for_search_duplicate as $key => $l) // adesso le email non ancora inserite, le inserisco in `newsletters_users`
        {
            $values['email'] = $key;

            $last_id_insert[] = $this->db->insert_into("newsletters_users", $values, true)['last_id']; // inserisco i rimanenti
        }


        // rieseguo la ricerca per prendere gli id
        $inserted_list_id = $this->db->select_sql("SELECT id FROM `newsletters_users` WHERE TRIM(LOWER(email)) IN ({$in})");
        echo count($inserted_list_id) . " - newsletters_users now (after new insert)<br>";

        echo "<br>last insert array id<br>";
        print_r($last_id_insert);
        echo "<br>";

        $arry_id = []; //ARRAY DEGLI ID PRESENTI IN newsletters_users che derivano da bookers

        foreach ($inserted_list_id as $value) {
            $id = $value['id'];
            $arry_id[$id] = $id;
        }

        $in_filter = implode(", ", $arry_id);

        //        print "<br><br><br>in_filter<br>{$in_filter}<br><br>";
        // qui vediamo quelli da eliminare (in caso qualche booker sia stato cancellato dalla tabella Bookers
        $query = "SELECT newsletter_user_id FROM newsletters_groups_users WHERE newsletter_group_id = '{$id_news_letters}' AND newsletter_user_id NOT IN ({$in_filter})";
        $list_bookers_deleted = $this->db->select_sql($query);
        //        print "<br>" . $query . "<br>";

        print "<br>newsletters_groups_users.id eliminati<br>";
        print_r($list_bookers_deleted);
        print "<br>";

        $filter_to_delete = [];
        foreach ($list_bookers_deleted as $deleted) {
            $id = $deleted['newsletter_user_id'];
            $filter_to_delete[$id] = $id;
        }

        $filter_to_delete_impl = implode(", ", $filter_to_delete);

        $query = "DELETE FROM `newsletters_groups_users` WHERE `newsletters_groups_users`.`newsletter_user_id` IN ({$filter_to_delete_impl})";
        $this->db->my_query($query); // elimina gli id che non ci sono piu
        echo "<br>query newsletters_groups_users to delete = ";

        $list_bookers_present = [];
        // qui vediamo quelli che sono gia presenti 
        $query = "SELECT newsletter_user_id FROM newsletters_groups_users WHERE newsletter_group_id = '{$id_news_letters}' AND newsletter_user_id IN ({$in_filter})";
        $list_bookers_present = $this->db->select_sql($query);

        foreach ($list_bookers_present as $value) {
            $id = $value['newsletter_user_id'];

            unset($last_id_insert[$id]);
        }


        $last = [];
        foreach ($last_id_insert as $value) {
            $values = [];
            // $query = "INSERT INTO `newsletters_groups_users` (`newsletter_group_id`, `newsletter_user_id`) VALUES ( '{$id_news_letters}', '{$value}')";
            $values['newsletter_group_id'] = $id_news_letters;
            $values['newsletter_user_id'] = $value;
            $last[] = $this->db->insert_into('newsletters_groups_users', $values, true)['last_id'];
        }
        print "<br>newsletters_groups_users.id aggiunti<br>";
        print_r($last);

        //print $query;


        exit();
    }


    public function infoPrenotazione($prenotazione)
    {
        $res = [];
        $query = "  SELECT 
                            CampiBooking.*, Campi.Descrizione
                    FROM 
                            CampiBooking 
                            INNER JOIN Campi on CampiBooking.campo_id = Campi.Campo 
                    WHERE 
                            Prenotazione = '{$prenotazione}'
                            ORDER BY CONCAT(CampiBooking.Data,CampiBooking.Ora) ASC";

        $this->db->write_file("_campi_query_", $query);
        $p = $this->db->select_sql($query);

        foreach ($p as $value) {
            $res['Booker'] = $value['bookerNome'] . " " . $value['bookerCognome'];
            $res['Email'] = $value['bookerEmail'];
            $res['Telefono'] = $value['bookerTelefono'];
            $res['Campo'] = $value['Descrizione'];

            $date = date_create($value['Data']);
            $data = date_format($date, "d/m/Y");

            $our = date_create($value['Ora']);
            $ora = date_format($our, "H:i");

            //            $importo_expl = explode(".", $value['Importo']);
            //            $importo = sprintf("%s,%s", $importo_expl[0], $importo_expl[1]);

            $importo = str_replace(".", ",", $value['Importo']);
            $id = $value['id'];
            $pagato = $value['Pagato'];

            $res['Giorni'][] = ["Data" => $data, "Ora" => $ora, "Importo" => $importo, "Pagato" => $pagato, "id" => $id];
        }

        return $res;
    }


    // ------------------------------------------------------------------------------------------------------


    private function scorriAtleti()
    {
        $r =  $this->db->select_sql("SELECT * FROM AtletiBAS");
        $res = [];
        foreach ($r as $key => $value) {
            $squadra = $value['Squadra'];
            $atleta =  $value['Atleta'];
            $annoSportivo = $value['AnnoSportivo'];
            $res[$atleta][$squadra]['Anni'][$annoSportivo][] = $value;
            $res[$atleta][$squadra]['ContaAnni'] = count($res[$atleta][$squadra]['Anni']);
        }

        $this->db->write_file('scorriAtleti', $res);

        return $res;
    }


    public function atletiBASMassivi()
    {

        $response = [];
        $responseAtletiBAS = [];
        $responseSquadreBAS = [];
        $atletiBAS = [];
        $res = [];

        $anno = $this->annoSportivo()['current']['year'];

        // -----------------------------------------------------------------------------
        $scorriAtleti = $this->scorriAtleti();
        //        $query = "SELECT * FROM SquadreBAS WHERE AnnoSportivo = 2024";
        $query = "SELECT * FROM SquadreBAS WHERE AnnoSportivo = {$anno}";
        //
        $responseSquadreBAS = $this->db->key_select($this->db->select_sql($query), 'Squadra');

        // -----------------------------------------------------------------------------

        $filterNonTesserati = "";

        if (isset($_GET['nonTesserati'])) {
            $filterNonTesserati = " AND subscriber_id = '0'";
        }

        //        $query = "SELECT * FROM AtletiBAS WHERE AnnoSportivo = 2024 {$filterNonTesserati}";
        $query = "SELECT * FROM AtletiBAS WHERE AnnoSportivo = {$anno} {$filterNonTesserati}";

        $responseAtletiBAS = $this->db->select_sql($query);

        foreach ($responseAtletiBAS as $responseAtleti) {
            $squadra = $responseAtleti['Squadra'];
            $atleta = $responseAtleti['Atleta'];
            $atletiBAS[$squadra][$atleta] = $responseAtleti;
        }

        $this->db->write_file("#atletiBAS", $atletiBAS);
        

        $presidentiSquadre = $this->presidentiSquadre($anno); //GIUSEPE 2025-10-13 -----------------------------------------------------------------------------


        // -----------------------------------------------------------------------------

        $query = "  SELECT
                        Atleti.Atleta,
                        Atleti.Cognome,
                        Atleti.Nome,
                        Atleti.CityNascita,
                        Atleti.DataNascita,
                        Atleti.Sesso,
                        Squadre.Squadra,
                        Squadre.Denominazione
                    FROM
                        `SquadreCampionati`
                    INNER JOIN Campionati ON SquadreCampionati.Campionato = Campionati.Campionato
                    INNER JOIN Annuario ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato
                    INNER JOIN Atleti ON Annuario.Atleta = Atleti.Atleta
                    INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra
                    WHERE
                        Campionati.AnnoSportivo = {$anno} AND Campionati.PlayLeague = 1";

        $response = $this->db->select_sql($query);

        foreach ($response as $value) {
            $squadra = $value['Squadra'];
            $atleta = $value['Atleta'];
            $client_id = -1;
            $general_counsel_id = -1;

            $value['contaAnniTesseramenti'] = 0;

            if (isset($scorriAtleti[$atleta][$squadra])) {
                $value['contaAnniTesseramenti'] = $scorriAtleti[$atleta][$squadra]['ContaAnni'];
            }

            if (isset($atletiBAS[$squadra][$atleta])) {
                $value['BAS'] = $atletiBAS[$squadra][$atleta];
            } else {
                continue;
            }


            if (isset($responseSquadreBAS[$squadra])) {
                $client_id = $responseSquadreBAS[$squadra]['client_id'];
                $general_counsel_id = $responseSquadreBAS[$squadra]['general_counsel_id'];
            }

            //            $res[$squadra][] = $value;
            $atleta = $value['Atleta'];

            $res[$squadra]['nome'] = $value['Denominazione'];
            $res[$squadra]['client_id'] = $client_id;
            $res[$squadra]['general_counsel_id'] = $general_counsel_id;
            $res[$squadra]['presidente'] = $presidentiSquadre[$squadra];
            $res[$squadra]['atleti'][$atleta] = $value;
        }


        $this->db->write_file("#daTesserare", $res);
        return $res;
    }

    //GIUSEPE 2025-10-13 -----------------------------------------------------------------------------
    private function presidentiSquadre($anno)
    {
        $res = [];

        $query = "
                    SELECT
                        #SquadreBAS.*,
                        SquadreBAS.Squadra,
                        SquadreBAS.general_counsel_id,
                        UPPER(Squadre.general_counsel_cf) AS general_counsel_cf,
                        Squadre.general_counsel_firstname,
                        Squadre.general_counsel_lastname,
                        UPPER(Atleti.CodiceFiscale) AS CF,
                        Atleti.Atleta
                    FROM
                        SquadreBAS
                    INNER JOIN Squadre ON SquadreBAS.Squadra = Squadre.Squadra
                    LEFT JOIN Atleti ON UPPER(Atleti.CodiceFiscale) = UPPER(Squadre.general_counsel_cf)
                    WHERE
                        AnnoSportivo = {$anno}
                    ORDER BY
                        Atleti.Atleta ASC
            ";
        $response = $this->db->select_sql($query);
        foreach ($response as $val) {

            $squadra = $val['Squadra'];
            $res[$squadra] = $val;

        }

        $this->db->write_file("#presidentiSquadre",$res);

        return $res;
    }
    //------------------------------------------------------------------------------------------------


    //GIUSEPPE 2022-09-13 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }


    public function atleti()
    {
        $query = "SELECT * FROM `Atleti`";
        $select = $this->db->key_select($this->db->select_sql($query), 'Atleta');
        return $select;
    }


    public function annoSportivo()
    {
        $current = [];
        $new = [];
        $res = ["current" => &$current, "new" => &$new];

        $sql = "SELECT 
                        * 
                FROM 
                        `AnniSportivi` 
                WHERE AnnoSportivo >= '2018'
                ORDER BY 
                        AnnoSportivo DESC";

        $res_query = $this->db->select_sql($sql);

        $res_query = $this->db->key_select($res_query, 'AnnoSportivo');

        $year_now = date("Y");

        $date_now = date("Y-m-d");

        $max_year = 0;

        foreach ($res_query as $year => $value) {
            $data_inizio_expl = explode("-", $value['DataInizio']);

            $date_init = implode("-", array_reverse($data_inizio_expl));

            $ys = ['year' => $year, 'init' => $date_init];

            $if = $date_init <= $date_now;

            if ((int) $max_year <= (int) $year) {
                $max_year = $year;
            }


            if ((string) $date_init > (string) $date_now) {
                $new = $ys;
            }
        }

        $index_current = $max_year;

        if (count($new) > 0) {
            $index_current = $max_year - 1;
        }

        $current['year'] = $index_current;
        $current['init'] = $res_query[$index_current]['DataInizio'];

        return $res;
    }


    public function campiCampionati($anno_sportivo)
    {
        $query = "
            
                    SELECT 
                        SquadreCampionati.SquadraCampionato,
                        SquadreCampionati.Squadra,
                        Campionati.Campionato,
                        Squadre.Denominazione
                    FROM 
                        `Campionati` 
                        INNER JOIN SquadreCampionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                        INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra
                    WHERE 
                            Campionati.AnnoSportivo = '{$anno_sportivo}'           
                    AND     Campionati.PlayLeague = '{$this->playLeague}'
            ";

        $res = $this->db->select_sql($query);

        return $this->db->key_select($res, 'SquadraCampionato');
    }


    public function squadreCampionati($anno_sportivo)
    {
        $query = "
            
                    SELECT 
                        SquadreCampionati.SquadraCampionato,
                        SquadreCampionati.Squadra,
                        Campionati.Campionato,
                        Squadre.Denominazione,
                        Squadre.SquadraServizio
                    FROM 
                            `Campionati` 
                            INNER JOIN SquadreCampionati ON SquadreCampionati.Campionato = Campionati.Campionato 
                        INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra
                    WHERE 
                            Campionati.AnnoSportivo = '{$anno_sportivo}'            
                    AND     Campionati.PlayLeague = '{$this->playLeague}'
            ";

        $res = $this->db->select_sql($query);

        $this->squadre_campionati_array = $this->db->key_select($res, 'SquadraCampionato');

        //        return $this->db->key_select($res, 'SquadraCampionato');
        return $this->squadre_campionati_array;
    }


    public function calendario($anno_sportivo)
    {
        $selected = [];

        $res = [];

        $arr = [];

        $calendario_key = [];

        $causali_risultato = $this->causaliRisultato();

        $squadre_campionati = $this->squadreCampionati($anno_sportivo);

        $menu_tendina = [];

        $query = "  SELECT 
                            Campionati.Campionato, 
                            Campionati.Nome, 
                            Campionati.order, 
                            Campionati.Italiana,
                            GironiCampionati.GironeCampionato, 
                            GironiCampionati.Descrizione as DescrizioneGirone, 
                            GironiCampionati.NumeroSquadre, 
                            Calendari.Calendario, 
                            Calendari.Giornata, 
                            Calendari.GironeCampionato as GironeCalendario, 
                            Calendari.Campionato as CampionatoCalendario, 
                            Calendari.Partita, 
                            Calendari.Casa, 
                            Calendari.Trasferta, 
                            DATE_FORMAT(Calendari.Data, '%Y-%m-%d') as Data,
                            DATE_FORMAT(Calendari.Data, '%Y-%m-%d 00:00:00') as DataTimeStamp, 
                            Calendari.Ora, 
                            Calendari.Campo, 
                            Calendari.CausaleRisultato,
                            Calendari.NomeGara,
                            Calendari.PartitaValida,
                            Campi.Descrizione,
                            Campi.isMidland,
                            Campi.is5,
                            Campi.is7
  
                    FROM 
                            `Campionati` 
                            INNER JOIN GironiCampionati ON GironiCampionati.Campionato = Campionati.Campionato 
                            INNER JOIN Calendari ON Calendari.GironeCampionato = GironiCampionati.GironeCampionato 
                            INNER JOIN Campi ON Calendari.Campo = Campi.Campo 
        
                    WHERE 
                            Campionati.`AnnoSportivo` = '{$anno_sportivo}' 
                            AND Campionati.InCorso = 'Si' 
                            AND Campionati.group_id = '1' 
                            AND Campionati.scuola = '0' 
                            AND Campionati.sport = 'CALCIO'
                            AND (Campi.is5 = '1' OR Campi.is7 = '1')
                            AND Campionati.PlayLeague = '{$this->playLeague}'
                    ORDER by 
                            Campionati.order ASC, 
                            GironiCampionati.GironeCampionato ASC,
                            Calendari.Giornata ASC, 
                            Calendari.Partita ASC";

        $res = $this->db->select_sql($query);

        // print_r($query); 

        foreach ($res as $key => $value) {
            $campionato = $value['Campionato'];
            $girone_campionato = $value['GironeCampionato'];

            $date = explode("-", $value['Data']); //GIUSEPPE 2024-05-08

            $girone = ['GironeCampionato' => $value['GironeCampionato'], 'Descrizione' => $value['DescrizioneGirone'], 'NumeroSquadre' => $value['NumeroSquadre']];
            $giornata = [
                'Casa' => [
                    'SquadraCampionato' => $value['Casa'],
                    'Squadra' => $squadre_campionati[$value['Casa']]['Squadra'],
                    'Denominazione' => $squadre_campionati[$value['Casa']]['Denominazione'],
                    'SquadraServizio' => $squadre_campionati[$value['Casa']]['SquadraServizio'],
                    'Goal' => '',
                    'AutoGoal' => '',
                    'Risultato' => '',
                ],
                'Trasferta' => [
                    'SquadraCampionato' => $value['Trasferta'],
                    'Squadra' => $squadre_campionati[$value['Trasferta']]['Squadra'],
                    'Denominazione' => $squadre_campionati[$value['Trasferta']]['Denominazione'],
                    'SquadraServizio' => $squadre_campionati[$value['Trasferta']]['SquadraServizio'],
                    'Goal' => '',
                    'AutoGoal' => '',
                    'Risultato' => '',
                ],
                //                'Data' => $value['Data'], //GIUSEPPE 2024-05-08
                'Data' => sprintf("%s/%s/%s", $date[2], $date[1], $date[0]), //GIUSEPPE 2024-05-08
                'DataTimeStamp' => $value['DataTimeStamp'],
                'Ora' => $value['Ora'],
                'NomeGara' => $value['NomeGara'],
                'Campo' => [
                    'id' => $value['Campo'],
                    'Descrizione' => $value['Descrizione'],
                    'isMidland' => $value['isMidland'],
                    'is5' => $value['is5'],
                    'is7' => $value['is7'],
                ],
                $value['Casa'] => 'Casa',
                $value['Trasferta'] => 'Trasferta',
                'CausaleRisultato' => $causali_risultato[$value['CausaleRisultato']],
                'Calendario' => $value['Calendario'],
                'PartitaValida' => $value['PartitaValida']
            ];

            if (!isset($selected[$campionato])) {

                $campionato_array = [
                    'Campionato' => $value['Campionato'],
                    'Nome' => $value['Nome'],
                    'Italiana' => $value['Italiana'],
                    'order' => $value['order']
                ];

                $selected[$campionato] = $campionato_array;
            }

            if (!isset($selected[$campionato]['Gironi'][$girone_campionato])) {
                $selected[$campionato]['Gironi'][$girone_campionato] = $girone;
            }

            $selected[$campionato]['Gironi'][$girone_campionato]['Squadre'][$squadre_campionati[$value['Casa']]['Squadra']] = $squadre_campionati[$value['Casa']]['Denominazione'];
            $selected[$campionato]['Gironi'][$girone_campionato]['Squadre'][$squadre_campionati[$value['Trasferta']]['Squadra']] = $squadre_campionati[$value['Trasferta']]['Denominazione'];
            $selected[$campionato]['Gironi'][$girone_campionato]['NumeroSquadre'] = count($selected[$campionato]['Gironi'][$girone_campionato]['Squadre']);

            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$value['Giornata']]['Partita'][$value['Partita']] = $giornata;

            $calendario_key[$value['Calendario']] = $value['Calendario'];
            $calendario = array_keys($calendario_key);

            $menu_tendina['Campionato'][$campionato]['Nome'] = $value['Nome'];
            $menu_tendina['Campionato'][$campionato]['GironeCampionato'][$girone_campionato]['Nome'] = $value['DescrizioneGirone'];
        }

        $array_calendario = $this->goalPartiteCalendario($calendario);
        $this->array_calendario = $array_calendario;
        $this->db->write_file("array_calendario", $this->array_calendario);
        $this->assegnaGoal($selected, $array_calendario);
        $this->sanzioni($calendario);

        /*
          $this->goalPartiteCalendario($calendario);
          return ['selected' => $selected, 'array_keys' => array_keys($selected), 'SquadreCampionati' => $squadre_campionati];
          return ['selected' => $selected, 'array_calendario' => $array_calendario];
         */

        $this->db->write_file("calendario.json", json_encode($selected));
        $this->db->write_file("menu_tendina.json", json_encode($menu_tendina));

        return ['CalendarioHome' => $selected];
    }

    private function goalPartiteCalendario($calendario)
    {
        $in_calendario = implode(", ", $calendario);

        $query = "SELECT 
                        Calendari.Calendario,  
                        Calendari.Campionato, 
                        Calendari.GironeCampionato,   
                        GoalPartite.SquadraCampionato, 
                        SquadreCampionati.Squadra,
                        GoalPartite.Goal, 
                        GoalPartite.Autogoal, 
                        GoalPartite.Ammonizione,
                        GoalPartite.Espulsione,
                        Calendari.Giornata, 
                        Calendari.Partita,
                        (SELECT SUM(Punti) FROM Disciplinari WHERE Calendario = Calendari.Calendario AND SquadraCampionato = GoalPartite.SquadraCampionato) as DisciplinareInserita
                FROM 
                        GoalPartite 
                        INNER JOIN Calendari ON Calendari.Calendario = GoalPartite.Calendario 
                        INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato 
                WHERE 
                      
                        GoalPartite.Calendario IN ({$in_calendario}) ";
        //GoalPartite.Calendario IN ({$in_calendario}) AND GoalPartite.SquadraCampionato = '25192'"; //test

        $res = $this->db->select_sql($query);
        print_r("<br>goalPartiteCalendario: " . count($res) . "<br>");
        //        print_r("<br>" . $query . "<br>");
        //        $this->db->write_file("query_goal_partite", $query);
        return $res;
    }


    private function sanzioni($calendario)
    {
        $in_calendario = implode(", ", $calendario);

        $res = [];

        $query = "
                SELECT
                    Calendari.Calendario,
                    Calendari.Giornata,
                    Calendari.Campionato,
                    Calendari.GironeCampionato,
                    Disciplinari.SquadraCampionato,
                    Disciplinari.Disciplinare,
                    Disciplinari.Descrizione,
                    Disciplinari.Punti,
                    Disciplinari.Sanzione,
                    SquadreCampionati.Squadra,
                    Squadre.Denominazione
                FROM 
                        GoalPartite 
                        INNER JOIN Calendari ON Calendari.Calendario = GoalPartite.Calendario 
                        INNER JOIN Disciplinari ON Disciplinari.Calendario = Calendari.Calendario 
                        INNER JOIN SquadreCampionati ON Disciplinari.SquadraCampionato = SquadreCampionati.SquadraCampionato
                        INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra
                WHERE
                        GoalPartite.Calendario IN ({$in_calendario})
                GROUP BY 
                    Disciplinari.Disciplinare
                        ";

        //$this->db->write_file("sanzioniQuery", $query);
        $db_res = $this->db->select_sql($query);

        foreach ($db_res as $key => $value) {
            $campionato = $value['Campionato'];
            $girone = $value['GironeCampionato'];
            $giornata = $value['Giornata'];
            $disciplinare = $value['Disciplinare'];
            $res[$campionato][$girone][$giornata][$disciplinare] = $value;
        }

        $this->db->write_file("sanzioni.json", json_encode($res));
    }


    private function assegnaGoal(&$selected, &$array_calendario)
    {
        $not = ['Casa' => 'Trasferta', 'Trasferta' => 'Casa'];

        foreach ($array_calendario as $value) {
            $campionato = $value['Campionato'];
            $girone_campionato = $value['GironeCampionato'];
            $squadra_campionato = $value['SquadraCampionato'];
            $goal = $value['Goal'];
            $autogoal = $value['Autogoal'];
            $giornata = $value['Giornata'];
            $partita = $value['Partita'];
            //            $ammonizione = $value['Ammonizione'];
            $ammonizione = 0;
            //            $espulsione = $value['Espulsione'];
            $espulsione = 0;

            //            $disciplinare_inserita = $value['DisciplinareInserita'];
            $disciplinare_inserita = 0;

            switch ($value['Ammonizione']) {
                case 'Si':
                    $ammonizione = 1;
                    break;
            }

            switch ($value['Espulsione']) {
                case 'Si':
                    $espulsione = 3;
                    break;
            }

            $casa_trasferta = $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$squadra_campionato];

            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['Goal'] += $goal;
            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['AutoGoal'] += $autogoal;

            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['Ammonizione'] += $ammonizione;
            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['Espulsione'] += $espulsione;
            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['DisciplinareInserita'] += $disciplinare_inserita;

            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$casa_trasferta]['Risultato'] += $goal;
            $selected[$campionato]['Gironi'][$girone_campionato]['Giornata'][$giornata]['Partita'][$partita][$not[$casa_trasferta]]['Risultato'] += $autogoal;
        }
    }


    private function causaliRisultato()
    {
        $query = "SELECT * FROM `CausaliRisultato`";

        return $this->db->key_select($this->db->select_sql($query), 'CausaleRisultato');
    }


    public function marcatori($calendario, $anno_sportivo)
    {
        $query = "
                    SELECT 
                        Campionati.Campionato
                    FROM Campionati
                    WHERE 
                        Campionati.AnnoSportivo = '{$anno_sportivo}' 
                    AND Campionati.PlayLeague = '{$this->playLeague}'";

        $select_campionati = $this->db->key_select($this->db->select_sql($query), "Campionato");
        $key_campionati = array_keys($select_campionati);
        $key_campionati_IN = implode(", ", $key_campionati);

        $query = "SELECT 
                        `Calendario`, 
                        `Campionato`, 
                        `GironeCampionato` 
                FROM 
                        `Calendari` 
                WHERE 
                        Campionato IN ({$key_campionati_IN}) ";

        $select_calendario = $this->db->key_select($this->db->select_sql($query), 'Calendario');

        $key_calendario = array_keys($select_calendario);
        $key_calendario_IN = implode(", ", $key_calendario);

        $query = "
                    SELECT 
                            GoalPartite.* ,Calendari.Giornata
                    FROM 
                            `GoalPartite` 
                            INNER JOIN Calendari ON Calendari.Calendario = GoalPartite.Calendario
                    WHERE 
                            GoalPartite.Calendario IN ($key_calendario_IN)";

        //        $this->db->write_file("_query_goal_partite", $query);

        $select_goal_partite = $this->db->select_sql($query);

        $this->goal_partite = $select_goal_partite;

        $this->ordinaGoalPartite($select_goal_partite, $select_calendario, $calendario, $anno_sportivo);

        //$this->_ordinaGoalPartite($select_goal_partite, $select_calendario, $anno_sportivo);

        $this->db->write_file("marcatori.json", json_encode($select_goal_partite));

        return ['marcatori' => $select_goal_partite];
    }


    // ----------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------------

    private function ordinaGoalPartite(&$select_goal_partite, &$select_calendario, $calendario_home, $anno_sportivo)
    {
        $atleti = $this->atleti();
        $this->atleti_array = $atleti;
        $squadre_campionati = $this->squadreCampionati($anno_sportivo);

        $temp = [];

        // CREO UNA COPIA DEL CALENDARIO ED ELIMINO LE INFORMAZIONI CHE NON MI INTERESSANO, COMPRESE LE PARTITE, MA LASCIO LE GIORNATE
        foreach ($calendario_home as $key_campionato => $campionato) {
            unset($calendario_home[$key_campionato]['Campionato']);
            unset($calendario_home[$key_campionato]['Italiana']);
            unset($calendario_home[$key_campionato]['order']);

            foreach ($campionato['Gironi'] as $key_girone => $girone) {
                unset($calendario_home[$key_campionato]['Gironi'][$key_girone]['GironeCampionato']);

                foreach ($girone['Giornata'] as $key_giornata => &$giornata) {
                    $calendario_home[$key_campionato]['Gironi'][$key_girone]['Giornata'][$key_giornata] = [];
                }
            }
        }

        //        $this->db->write_file("_struttura_1.json", json_encode($calendario_home));


        foreach ($select_goal_partite as $value) // ASSEGNO I PUNTI AI CALCIATORI
        {
            $calendario = $value['Calendario'];

            $campionato = $select_calendario[$calendario]['Campionato'];
            $girone_campionato = $select_calendario[$calendario]['GironeCampionato'];

            $giornata = $value['Giornata'];

            $anagrafica = sprintf("%s %s", $atleti[$value['Atleta']]['Cognome'], $atleti[$value['Atleta']]['Nome']);
            $squadra = $squadre_campionati[$value['SquadraCampionato']]['Denominazione'];

            if ($campionato == "" || $girone_campionato == "")
                continue;
            if ($value['Atleta'] == 0 || $value['Atleta'] == "")
                continue;



            if (!isset($temp[$campionato][$girone_campionato][$value['Atleta']])) {
                $temp[$campionato][$girone_campionato][$value['Atleta']]['Anagrafica'] = $anagrafica;
                $temp[$campionato][$girone_campionato][$value['Atleta']]['Squadra'] = $squadra;
                $temp[$campionato][$girone_campionato][$value['Atleta']]['TOT'] = 0;
            }


            if (!isset($temp[$campionato][$girone_campionato][$value['Atleta']]['Giornata'][$giornata])) {
                $temp[$campionato][$girone_campionato][$value['Atleta']]['Giornata'][$giornata] = 0;
            }



            $temp[$campionato][$girone_campionato][$value['Atleta']]['Giornata'][$giornata] += $value['Goal'];
            $temp[$campionato][$girone_campionato][$value['Atleta']]['TOT'] += $value['Goal'];
        }


        $this->db->write_file("__atleta_giornate_3_1.json", json_encode($temp));

        foreach ($temp as $key_campionato => &$campionato) //INSERISCO I GOL NELLE RISPETTIVE GIORNATE
        {
            foreach ($campionato as $key_girone => &$girone) {
                foreach ($girone as $key_atleta => &$atleta) {
                    $tot = 0;
                    foreach ($atleta['Giornata'] as $giornata => $goal) {
                        $tot += $goal;
                        $atleta['GiornataC'][$giornata] = $tot;
                        $atleta['GiornataForOrder'][$giornata] = sprintf("%s{#}%s{#}%03d", $atleta['Squadra'], $atleta['Anagrafica'], $tot); //INSEIRSCO GOL, NOME SQUADRA E ANAGRAFICA IN UN UNICA STRINGA PER PROCEDERE POI ALL'ORDINAMENTO

                        if (isset($calendario_home[$key_campionato]['Gironi'][$key_girone]['Giornata'][$giornata]))
                            $calendario_home[$key_campionato]['Gironi'][$key_girone]['Giornata'][$giornata][$key_atleta] = $atleta['GiornataForOrder'][$giornata];
                    }
                }
            }
        }

        //        $this->db->write_file("__atleta_giornate_3_4.json", json_encode($temp));
        //        $this->db->write_file("_struttura_2.json", json_encode($calendario_home));
        //RIEMPO LE GIORNATE SUCCESSIVE: AD ESEMPIO SE UN CALCIATORE HA SEGNATO SOLO NELLA PRIMA GIORNATA, DEVE ESSERE COMUNQUE VISIBILE ANCHE NELLE SUCCESIVE
        foreach ($calendario_home as $key_campionato => &$campionato) {
            foreach ($campionato['Gironi'] as $key_girone => &$girone) {
                $to_add = [];
                foreach ($girone['Giornata'] as $key_giornata => &$giornata) {
                    // $giornata = count($giornata);
                    if (count($giornata) > 0) {
                        foreach ($to_add as $key_atleta => $value_atleta) {
                            if (!isset($giornata[$key_atleta]))
                                $giornata[$key_atleta] = $value_atleta;
                        }
                        $to_add = $giornata;
                    }
                }
            }
        }

        //        $this->db->write_file("_struttura_3.json", json_encode($calendario_home));
        // RAGGRUPPO LE STRINGE PER GIORNATA E NUM GOL E POI (NUM GOL DECRESCENTI E LISTA DI STRINGHE IN ORDINE CRESCENTE)
        foreach ($calendario_home as $key_campionato => &$campionato) {
            foreach ($campionato['Gironi'] as $key_girone => &$girone) {
                $to_add = [];
                foreach ($girone['Giornata'] as $key_giornata => &$giornata) {
                    // $giornata = count($giornata);
                    if (count($giornata) > 0) {
                        foreach ($giornata as $key_atleta => $value_atleta) {
                            $value_expl = explode("{#}", $value_atleta);
                            if ($value_expl[2] == "000")
                                continue;

                            $to_add[$value_expl[2]][] = $value_atleta;
                        }

                        $k = array_keys($to_add);

                        rsort($k);

                        $add_2 = [];
                        foreach ($k as $points) {
                            $z = $to_add[$points];
                            $this->bubble_sort($z);
                            $add_2[$points] = $z;
                        }

                        $giornata = $add_2;
                    }

                    $to_add = [];
                }
            }
        }

        //        $this->db->write_file("_struttura_4.json", json_encode($calendario_home));

        $select_goal_partite = $calendario_home;
    }


    // ----------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------------

    private function _ordinaGoalPartite(&$select_goal_partite, &$select_calendario, $anno_sportivo)
    {
        $temp = [];
        $ind = 0;

        $squadre_campionati = $this->squadreCampionati($anno_sportivo);
        $atleti = $this->atleti();
        $this->atleti_array = $atleti;

        foreach ($select_goal_partite as $value) {
            $calendario = $value['Calendario'];

            $campionato = $select_calendario[$calendario]['Campionato'];
            $girone_campionato = $select_calendario[$calendario]['GironeCampionato'];

            $giornata = $value['Giornata'];

            if ($campionato == "" || $girone_campionato == "")
                continue;
            if ($value['Atleta'] == 0 || $value['Atleta'] == "")
                continue;

            if (!isset($temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']])) {
                $temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']] = [
                    'Goal' => 0,
                    'SquadraCampionato' => $value['SquadraCampionato'],
                    'SquadraCampionatoDenominazione' => $squadre_campionati[$value['SquadraCampionato']]['Denominazione'],
                    'Anagrafica' => sprintf("%s %s", $atleti[$value['Atleta']]['Cognome'], $atleti[$value['Atleta']]['Nome']),
                    'Atleta' => $value['Atleta'],
                ];
            }


            $temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']]['Goal'] += $value['Goal'];
            $temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']]['Giornata'][$giornata] = $temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']]['Goal'];

            if ($temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']]['Goal'] == 0) {
                unset($temp[$campionato][$girone_campionato]['Atleta'][$value['Atleta']]);
            }
        }

        $this->db->write_file("__atleta_giornate.json", json_encode($temp));

        $temp_2 = [];
        foreach ($temp as $key_campionato => $campionato) {
            foreach ($campionato as $key_girone => $girone) {
                foreach ($girone['Atleta'] as $key_atleta => $atleta) {
                    foreach ($atleta['Giornata'] as $key_giornata => $goal) {
                        $atleta['Goal'] = $goal;

                        $temp_2[$key_campionato][$key_girone][$key_giornata][$key_atleta] = $atleta;
                    }
                }
            }
        }

        $this->db->write_file("__atleta_giornate_2.json", json_encode($temp_2));

        foreach ($temp as $key_campionato => $campionato) {
            foreach ($campionato as $key_girone => $girone) {
                $atleta_array = [];

                foreach ($girone['Atleta'] as $atleta) {
                    $atleta_array[] = $atleta;
                }

                // metto in ordine di goal;
                $this->bubble_sort($atleta_array, 'Anagrafica', 'ASC');
                $this->bubble_sort($atleta_array, 'SquadraCampionatoDenominazione', 'ASC');
                $this->bubble_sort($atleta_array, 'Goal', 'DESC');

                $temp[$key_campionato][$key_girone]['Atleta'] = $atleta_array;
            }
        }

        $this->ordinaGironi($temp);

        $select_goal_partite = $temp;
    }


    private function ordinaGironi(&$temp)
    {
        $temp1 = [];

        foreach ($temp as $key_campionato => $gironi) {
            $keys = array_keys($gironi);

            sort($keys);

            foreach ($keys as $key) {
                $temp1[$key_campionato][$key] = $temp[$key_campionato][$key];
            }
        }

        $temp = $temp1;
    }


    private function bubble_sort(&$array, $index = false, $order = 'ASC')
    {
        // se false è il valore stesso del foreach
        // altrimenti l'indice del value
        // order ASC; DESC

        $exit = true;

        $len = count($array);

        do {
            $exit = true;
            for ($i = 0; $i < $len - 1; $i++) {
                $t = $array[$i];
                $t_1 = $array[$i + 1];
                $res = false;
                $temp;

                if ($index) {
                    $t = $array[$i][$index];
                    $t_1 = $array[$i + 1][$index];
                }

                switch ($order) {
                    case 'ASC':
                        $res = $t > $t_1;
                        break;

                    case 'DESC':
                        $res = $t < $t_1;
                        break;
                }

                if ($res) {
                    $temp = $array[$i];
                    $array[$i] = $array[$i + 1];
                    $array[$i + 1] = $temp;
                    $exit = false;
                }
            }
        } while (!$exit);
    }


    public function classifica($calendario)
    {
        $res = [];

        $this->db->write_file("_calendario_2", $calendario);
        $this->db->write_file("_calendario_my", $calendario);

        $array_disciplinari = $this->creaArrayDisciplinari();

        $causali_risultato = $this->causaliRisultato();

        $giocate = [];
        $pareggi_classifica = [];
        $gruppi_presati = [];

        foreach ($calendario as $key_campionato => $campionato) {
            foreach ($campionato['Gironi'] as $key_girone => $girone) {
                foreach ($girone['Squadre'] as $squadra => $nome_squadra) {
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Nome'] = $nome_squadra;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Punti'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Giocate'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Vinte'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Perse'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['Nulle'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['GoalFatti'] = 0;
                    $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$squadra]['GoalSubiti'] = 0;
                }

                foreach ($girone['Giornata'] as $key_giornata => $giornata) {

                    //                    $disc = [];

                    foreach ($giornata['Partita'] as $key_partita => $partita) {
                        $casa_squadra = $partita['Casa']['Squadra'];
                        $trasferta_squadra = $partita['Trasferta']['Squadra'];

                        $casa_goal = $partita['Casa']['Goal'];
                        $trasferta_goal = $partita['Trasferta']['Goal'];

                        $casa_autogoal = $partita['Casa']['AutoGoal'];
                        $trasferta_autogoal = $partita['Trasferta']['AutoGoal'];

                        $casa_risultato = $partita['Casa']['Risultato'];
                        $trasferta_risultato = $partita['Trasferta']['Risultato'];

                        $casa_espulsione = $partita['Casa']['Espulsione'];
                        $trasferta_espulsione = $partita['Trasferta']['Espulsione'];

                        $casa_DisciplinareInserita = $partita['Casa']['DisciplinareInserita'];
                        $trasferta_DisciplinareInserita = $partita['Trasferta']['DisciplinareInserita'];

                        //                         $casa_DisciplinareInserita = 0;
                        //                         $trasferta_DisciplinareInserita = 0;

                        $casa_ammonizione = $partita['Casa']['Ammonizione'];
                        $trasferta_ammonizione = $partita['Trasferta']['Ammonizione'];

                        // punti disciplinari li inserisco dopo questo foreach perchè sono ridondanti nell'array

                        $res[$key_campionato][$key_girone][$key_girone][$key_giornata][$key_partita][$casa_squadra] = $casa_DisciplinareInserita;
                        $res[$key_campionato][$key_girone][$key_girone][$key_giornata][$key_partita][$trasferta_squadra] = $trasferta_DisciplinareInserita;

                        $CausaleRisultato = $partita['CausaleRisultato'];
                        $CausRisCasa = 0;
                        $CausRisTrasferta = 0;

                        if ($CausaleRisultato != null) {
                            $risCasa = $partita['Casa']['Risultato'];
                            $risTrasferta = $partita['Trasferta']['Risultato'];

                            $puntiDisciplina = $CausaleRisultato['PuntiDisciplina'];

                            $CausaleRisultatoID = $CausaleRisultato['CausaleRisultato'];

                            if ($CausaleRisultatoID == 9 || $CausaleRisultatoID == 10) {
                                $CausRisCasa = $puntiDisciplina;
                                $CausRisTrasferta = $puntiDisciplina;
                            } else {
                                if ($risCasa < $risTrasferta) {
                                    $CausRisCasa = $puntiDisciplina;
                                } else {
                                    $CausRisTrasferta = $puntiDisciplina;
                                }
                            }
                        }


                        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

                        if (!is_int($casa_goal) && !is_int($trasferta_goal) && !is_int($casa_autogoal) && !is_int($trasferta_autogoal)) {
                            continue;
                        }



                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Giocate'] += 1;
                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Giocate'] += 1;

                        // $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['CoppaDisciplina'] += $casa_espulsione + $casa_ammonizione + $casa_DisciplinareInserita;
                        // $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['CoppaDisciplina'] += $trasferta_espulsione + $trasferta_ammonizione + $trasferta_DisciplinareInserita;

                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['CoppaDisciplina'] += $casa_espulsione + $casa_ammonizione + $CausRisCasa;
                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['CoppaDisciplina'] += $trasferta_espulsione + $trasferta_ammonizione + $CausRisTrasferta;


                        // 2025-02-18 -- validare partite
                        $partita_valida = $partita['PartitaValida'];
                        if ($casa_risultato > $trasferta_risultato) {
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Punti'] += 3 * $partita_valida; // 2025-02-18 -- validare partite
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Vinte'] += 1;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalFatti'] += $casa_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalSubiti'] += $trasferta_risultato;

                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Perse'] += 1;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalFatti'] += $trasferta_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalSubiti'] += $casa_risultato;
                        }
                        if ($casa_risultato < $trasferta_risultato) {
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Punti'] += 3 * $partita_valida; // 2025-02-18 -- validare partite
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Vinte'] += 1;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalFatti'] += $trasferta_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalSubiti'] += $casa_risultato;

                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Perse'] += 1;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalFatti'] += $casa_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalSubiti'] += $trasferta_risultato;
                        }

                        if ($casa_risultato == $trasferta_risultato) {
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Punti'] += 1 * $partita_valida; // 2025-02-18 -- validare partite
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Punti'] += 1 * $partita_valida; // 2025-02-18 -- validare partite
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Nulle'] += 1;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Nulle'] += 1;

                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalFatti'] += $casa_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalSubiti'] += $trasferta_risultato;

                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalFatti'] += $trasferta_risultato;
                            $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalSubiti'] += $casa_risultato;
                        }


                        $casa_goal_fatti = $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalFatti'];
                        $casa_goal_subiti = $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['GoalSubiti'];
                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['DifferenzaReti'] = $casa_goal_fatti - $casa_goal_subiti;

                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$casa_squadra]['Squadra'] = $casa_squadra;

                        $trasferta_goal_fatti = $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalFatti'];
                        $trasferta_goal_subiti = $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['GoalSubiti'];
                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['DifferenzaReti'] = $trasferta_goal_fatti - $trasferta_goal_subiti;

                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$trasferta_squadra]['Squadra'] = $trasferta_squadra;

                        $giocate[$key_campionato][$key_girone]['squadra'][$casa_squadra]['vs'][$trasferta_squadra]['day'][$key_giornata] = $casa_risultato - $trasferta_risultato;
                        $giocate[$key_campionato][$key_girone]['squadra'][$trasferta_squadra]['vs'][$casa_squadra]['day'][$key_giornata] = $trasferta_risultato - $casa_risultato;
                    }


                    //                    $squadreDisciplinari = $array_disciplinari[$key_campionato][$key_girone][$key_giornata] 

                    $squadreDisciplinari = $array_disciplinari[$key_campionato][$key_girone][$key_giornata];

                    foreach ($squadreDisciplinari as $key_squadra => $punti_disciplina) {
                        $calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'][$key_squadra]['CoppaDisciplina'] += $punti_disciplina;
                    }

                    $to_sort = [];

                    foreach ($calendario[$key_campionato]['Gironi'][$key_girone]['SquadrePunti'] as $squadre_punti) {
                        $to_sort[] = $squadre_punti;
                    }

                    $this->bubble_sort($to_sort, 'GoalFatti', 'DESC');
                    $this->bubble_sort($to_sort, 'DifferenzaReti', 'DESC');
                    $this->bubble_sort($to_sort, 'CoppaDisciplina', 'ASC');
                    $this->bubble_sort($to_sort, 'Punti', 'DESC');

                    //                    $this->pesi($to_sort, ['Punti', 'CoppaDisciplina', 'DifferenzaReti', 'GoalFatti'], [8, 4, 2, 1], $key_campionato, $key_girone, $key_giornata, $pareggi_classifica);
                    $this->pesi($to_sort, ['Punti', 'CoppaDisciplina'], [100, 10], $key_campionato, $key_girone, $key_giornata, $pareggi_classifica, $gruppi_pesati);

                    $calendario[$key_campionato]['Gironi'][$key_girone]['Giornata'][$key_giornata]['Classifica'] = $to_sort;
                }
            }
        }

        //        print_r($res);

        $this->db->write_file("giocate.json", json_encode($giocate));

        $this->db->write_file("pareggi_classifica.json", json_encode($pareggi_classifica));

        $this->db->write_file("gruppi_pesati.json", json_encode($gruppi_pesati));

        //scontri diretti
        $this->scontriDiretti($giocate, $pareggi_classifica, $gruppi_pesati, $calendario);

        $this->db->write_file("__disciplinari_array", $res);

        $this->db->write_file("classifica.json", json_encode($calendario));
    }


    private function pesi(&$to_sort, $index_array, $pesi_array, $key_campionato, $key_girone, $key_giornata, &$pareggi_classifica, &$gruppi_pesati)
    {
        //$this->pesi($to_sort, ['Punti', 'CoppaDisciplina', 'DifferenzaReti', 'GoalFatti'], [8, 4, 2, 1]);

        foreach ($to_sort as &$value) {
            foreach ($index_array as $key => $index) {
                $peso = $pesi_array[$key];

                $value['Pesati'] += $value[$index] * $peso;
                $value['Campionato'] = $key_campionato;
                $value['Girone'] = $key_girone;
                $value['Giornata'] = $key_giornata;
            }
        }

        $r = count($to_sort);
        $res = [];
        for ($i = 0; $i < $r - 1; $i++) {
            $peso1 = $to_sort[$i]['Pesati'];
            $peso2 = $to_sort[$i + 1]['Pesati'];
            $squadra1 = $to_sort[$i]['Squadra'];
            $squadra2 = $to_sort[$i + 1]['Squadra'];

            if ($peso1 == $peso2) {

                if (
                    ($to_sort[$i]['Punti'] == $to_sort[$i + 1]['Punti']) &&
                    ($to_sort[$i]['CoppaDisciplina'] == $to_sort[$i + 1]['CoppaDisciplina']) &&
                    ($key_giornata > 1)
                ) {
                    $res[$key_campionato][$key_girone][$key_giornata][$squadra1 . "-" . $squadra2] = [$to_sort[$i], $to_sort[$i + 1]];
                    $pareggi_classifica[$key_campionato][$key_girone][$key_giornata][$squadra1 . "-" . $squadra2] = array("res" => [$to_sort[$i], $to_sort[$i + 1]], "i" => $i);

                    $gruppi_pesati[$key_campionato][$key_girone][$key_giornata][$peso1][$squadra1] = true;
                    $gruppi_pesati[$key_campionato][$key_girone][$key_giornata][$peso1][$squadra2] = true;
                }
            }
        }
    }


    private function scontriDiretti($giocate, $pareggi_classifica, $gruppi_pesati, &$calendario)
    {

        foreach ($pareggi_classifica as $key_campionato => $campionato) {
            foreach ($campionato as $key_girone => $girone) {
                foreach ($girone as $key_giornata => $giornata) {
                    foreach ($giornata as $key_squadre => $partite) {
                        // es $key_squadre : "3994-5541"
                        // splitto questi due valori
                        //
                        $valid = $this->controllaNumeroSquadreScontriDiretti($key_campionato, $key_girone, $key_giornata, $partite['res'], $gruppi_pesati);

                        if (!$valid)
                            continue;


                        $res = $this->cercaPartiteGiocate($key_campionato, $key_girone, $key_giornata, $key_squadre, $giocate);

                        $index_i = $partite['i'];

                        if ($res['vinte'] < $res['perse']) {
                            $sq = explode("-", $key_squadre);
                            print_r("<br>SCAMBIO SCONTRI DIRETTI<br>");
                            print "------------------------------------<br>";
                            print $calendario[$key_campionato]['Nome'] . "<br>";
                            print $calendario[$key_campionato]['Gironi'][$key_girone]['Descrizione'] . "<br>";
                            print "Giornata: {$key_giornata}" . "<br>";
                            print $calendario[$key_campionato]['Gironi'][$key_girone]['Squadre'][$sq[0]] . " ← " . $calendario[$key_campionato]['Gironi'][$key_girone]['Squadre'][$sq[1]] . "<br>";
                            print "Diventa<br>";
                            print $calendario[$key_campionato]['Gironi'][$key_girone]['Squadre'][$sq[1]] . " → " . $calendario[$key_campionato]['Gironi'][$key_girone]['Squadre'][$sq[0]] . "<br>";
                            print "<br><br>";

                            $temp = $calendario[$key_campionato]["Gironi"][$key_girone]["Giornata"][$key_giornata]["Classifica"][$index_i];
                            $calendario[$key_campionato]["Gironi"][$key_girone]["Giornata"][$key_giornata]["Classifica"][$index_i] = $calendario[$key_campionato]["Gironi"][$key_girone]["Giornata"][$key_giornata]["Classifica"][$index_i + 1];
                            $calendario[$key_campionato]["Gironi"][$key_girone]["Giornata"][$key_giornata]["Classifica"][$index_i + 1] = $temp;
                        } else {
                        }
                    }
                }
            }
        }
    }


    private function controllaNumeroSquadreScontriDiretti($key_campionato, $key_girone, $key_giornata, $partite_res, $gruppi_pesati)
    {
        $quadra_1 = $partite_res[0]['Squadra'];
        $quadra_2 = $partite_res[1]['Squadra'];
        $pesati = $partite_res[0]['Pesati'];  // è indifferente 0 o 1
        $valid = false;

        if (
            isset($gruppi_pesati[$key_campionato][$key_girone][$key_giornata][$pesati][$quadra_1]) &&
            isset($gruppi_pesati[$key_campionato][$key_girone][$key_giornata][$pesati][$quadra_2])
        ) {
            //controllo che non ci siano piu di due squadre;
            $n = count($gruppi_pesati[$key_campionato][$key_girone][$key_giornata][$pesati]);

            if ($n == 2) {
                //                print "<br>squadre = 2<br>";
                $valid = true;
            }
        }

        return $valid;
    }


    private function cercaPartiteGiocate($key_campionato, $key_girone, $key_giornata, $key_squadre, $giocate)
    {
        // es $key_squadre : "3994-5541"
        // splitto questi due valori
        //giocate.json
        $squadre = explode("-", $key_squadre);

        $res = ["vinte" => 0, "perse" => 0, "pareggiate" => 0];

        $valid = false;

        //        print_r("<br><br>Giornata classifica: {$key_giornata}<br>");

        if (isset($giocate[$key_campionato][$key_girone]['squadra'][$squadre[0]]['vs'][$squadre[1]])) {
            $day = $giocate[$key_campionato][$key_girone]['squadra'][$squadre[0]]['vs'][$squadre[1]]['day'];

            //            print_r(" --cmp:{$key_campionato}-gir:{$key_girone}-g:{$key_giornata}--sq:{$key_squadre}--giorno-giocata:{$d}:{$r} <br>");
            //            print_r(" --cmp:{$key_campionato}-gir:{$key_girone}-g:{$key_giornata}--sq:{$key_squadre}<br>");

            foreach ($day as $d => $r) {
                if ($d > $key_giornata) {
                    //  print_r("&emsp; &emsp; --giorno-giocata:{$d}:{$r}<br>");
                    continue;
                }

                if ($r > 0) {
                    $res["vinte"]++;
                    $valid = true;
                } elseif ($r < 0) {
                    $res["perse"]++;
                    $valid = true;
                } elseif ($r == 0) {
                    $res["pareggiate"]++;
                    $valid = true;
                }

                //                print_r("<div style='background-color:yellow'>&emsp; &emsp; --giorno-giocata:{$d}:{$r}</div><br>");
            }
        }

        if (!$valid)
            $res = null;


        //        if($key_squadre == "5671-1724")
        //        {
        //            print_r($res);
        //        }

        return $res;
    }


    private function creaArrayDisciplinari()
    {
        $res = [];
        foreach ($this->array_calendario as $riga_calendario) {
            $campionato = $riga_calendario['Campionato'];
            $girone = $riga_calendario['GironeCampionato'];
            $giornata = $riga_calendario['Giornata'];
            $squadraCampionato = $riga_calendario['SquadraCampionato'];
            $squadra = $riga_calendario['Squadra'];
            $DisciplinareInserita = $riga_calendario['DisciplinareInserita'];

            $DisciplinareInserita = $DisciplinareInserita == (null || "") ? 0 : $DisciplinareInserita;

            $res[$campionato][$girone][$giornata][$squadra] = $DisciplinareInserita;
        }

        //        $this->db->write_file("creaArrayDisciplinari", $res);
        return $res;
    }


    public function disciplinari(&$calendario)
    {
        $res = [];

        $assegna_calendario = [];

        foreach ($calendario as $key_campionato => $campionato) {
            foreach ($campionato['Gironi'] as $key_girone => $girone) {

                foreach ($girone['Giornata'] as $key_day => $giornata) {
                    $res[$key_campionato][$key_girone][$key_day] = ["DATE" => []];

                    foreach ($giornata['Partita'] as $key_partita => $partita) {
                        $res[$key_campionato][$key_girone][$key_day]["DATE"][$partita['DataTimeStamp']] = [];

                        $id_calendario = $partita['Calendario'];

                        $assegna_calendario[$id_calendario] = ['Campionato' => $key_campionato, 'Girone' => $key_girone, 'Giornata' => $key_day, 'Partita' => $key_partita];
                    }
                }
            }
        }


        foreach ($this->goal_partite as $goal_partita) {
            $calendario = $goal_partita['Calendario'];

            $ammonizione = $goal_partita['Ammonizione'];

            $espulsione = $goal_partita['Espulsione'];

            $assegnazione = $assegna_calendario[$calendario];

            if ($ammonizione == 'Si' || $espulsione == 'Si') {
                $assegnazione = $assegna_calendario[$calendario];

                $giornata = $assegnazione['Giornata'];

                if ($ammonizione == 'Si') {
                    $res[$assegnazione['Campionato']][$assegnazione['Girone']][$giornata]['DB']['Ammonizioni'][] = $goal_partita;
                }


                if ($espulsione == 'Si') {
                    $res[$assegnazione['Campionato']][$assegnazione['Girone']][$giornata]['DB']['Espulsioni'][] = $goal_partita;
                }
            }
        }

        $this->calcolaEspulsioni($res);

        $this->calcolaAmmonizioni($res);

        //        $this->db->write_file("_squadre_campionati_array", $this->squadre_campionati_array);
        //        $this->db->write_file("_select_goal_partite", $this->goal_partite);
        //        $this->db->write_file("_assegna_calendario", $assegna_calendario);

        $this->db->write_file("disciplinari.json", json_encode($res));
    }


    private function calcolaEspulsioni(&$res)
    {
        foreach ($res as $key_campionato => $campionato) //campionato
        {
            foreach ($campionato as $key_girone => $girone) //girone
            {
                $espulsioni_data = [];

                foreach ($girone as $key_day => $giornata) //giornata
                {

                    if (count($espulsioni_data) > 0) {
                        foreach ($espulsioni_data as $espulsione) {
                            $atleta = $espulsione['Atleta'];

                            if ($date_now <= $espulsione_fine) {
                                $periodo = "";

                                $res[$key_campionato][$key_girone][$key_day]['Espulsi'][$atleta] = $this->assegnaEspulsione($espulsione, $periodo);
                            }
                        }
                    }

                    if (isset($giornata['DB']['Espulsioni'])) //espulsioni
                    {

                        foreach ($giornata['DB']['Espulsioni'] as $espulsione) {
                            $atleta = $espulsione['Atleta'];

                            $giornate_espulsione = $espulsione['EspulsioneGiornate'];

                            if ((string) $giornate_espulsione == "") // non sono segnate le giornate di ma la data di fine espulsione
                            {
                                $espulsione_fine = $espulsione['EspulsioneFine'];

                                if ($espulsione_fine !== "0000-00-00 00:00:00" || $espulsione_fine !== "") {

                                    $this->calcolaScadenzaEspulsione($res, $key_campionato, $key_girone, $key_day, $espulsione, $espulsione_fine);
                                }
                            } else {

                                for ($g = 1; $g <= $giornate_espulsione; $g++) {
                                    if (isset($res[$key_campionato][$key_girone][$key_day + $g])) {
                                        $calcola = $giornate_espulsione - $g + 1;

                                        $periodo = (int) $calcola == 1 ? "{$calcola} turno di squalifica" : "{$calcola} turni di squalifica";

                                        $espulsione['Periodo'] = $periodo;

                                        $res[$key_campionato][$key_girone][$key_day + $g]['Espulsi'][$atleta] = $this->assegnaEspulsione($espulsione);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    private function calcolaScadenzaEspulsione(&$res, $key_campionato, $key_girone, $key_day, $espulsione, $espulsione_fine)
    {
        $inizio_timestamp = explode("-", $this->infoCalendario($espulsione['Calendario'])['Data']);

        $inizio = sprintf("%s/%s/%s", $inizio_timestamp[2], $inizio_timestamp[1], $inizio_timestamp[0]);

        $fine_timestamp = explode("-", str_replace(" 00:00:00", "", $espulsione['EspulsioneFine']));

        $fine = sprintf("%s/%s/%s", $fine_timestamp[2], $fine_timestamp[1], $fine_timestamp[0]);

        $periodo = "Dal " . $inizio . " fino al " . $fine;

        $espulsione['Periodo'] = $periodo;

        foreach ($res[$key_campionato][$key_girone] as $key_giornata => $giornata) //campionato
        {
            if ($key_giornata < $key_day) {
                continue;
            }

            // qui sappiamo di sicuro che c'è una espulsione a tempo
            $date = $giornata['DATE']; // è un array con le chiavi rappresentate dalla data es: -- "DATE": { "2022-11-07": [],"2022-11-08": []  }; -- ogni giornata puo avere piu date

            $key_date = max(array_keys($date));

            //            print "{$key_date} - {$espulsione_fine} ---|| {$espulsione['Periodo']}<br>";

            $atleta = $espulsione['Atleta'];

            if ($key_date <= $espulsione_fine) {
                $res[$key_campionato][$key_girone][$key_giornata]['Espulsi'][$atleta] = $this->assegnaEspulsione($espulsione);
            }
        }
    }


    private function assegnaEspulsione($espulsione)
    {
        $res = [];
        $atleta = $espulsione['Atleta'];
        $atleta_info = $this->atleti_array[$atleta];
        $anagrafica = $atleta_info['Cognome'] . " " . $atleta_info['Nome'];

        $squadra_campionato = $espulsione['SquadraCampionato'];

        $res['Anagrafica'] = $anagrafica;
        $res['Periodo'] = $espulsione['Periodo'];
        $res['Squadra'] = $this->squadre_campionati_array[$squadra_campionato]['Denominazione'];
        $res['Motivo'] = $espulsione['Motivo'];

        return $res;
    }


    private function calcolaAmmonizioni(&$res)
    {
        foreach ($res as $key_campionato => $campionato) {
            foreach ($campionato as $key_girone => $girone) {
                $ammonizioni_data = [];

                foreach ($girone as $key_day => $giornata) {

                    if (count($ammonizioni_data) > 0) {
                        $res[$key_campionato][$key_girone][$key_day]['Ammoniti'] = $ammonizioni_data;
                    }

                    if (isset($giornata['DB']['Ammonizioni'])) {

                        foreach ($giornata['DB']['Ammonizioni'] as $ammonizione) {
                            $atleta = $ammonizione['Atleta'];

                            if (isset($ammonizioni_data[$atleta])) {
                                $ammonizioni_data[$atleta]['Ammonizioni'] += 1;
                            } else { // compongo l'altleta
                                $ammonizioni_data[$atleta] = $this->assegnaAmmonizione($ammonizione);
                            }

                            $ammonizioni_data[$atleta]['Stato'] = $this->leggiAmmonizione($ammonizioni_data[$atleta]);

                            if ($ammonizioni_data[$atleta]['Stato'] == "Diffidato") {
                                $res[$key_campionato][$key_girone][$key_day]['Diffidati'][$atleta] = $ammonizioni_data[$atleta];

                                $res[$key_campionato][$key_girone][$key_day]['Squalificati'][$atleta]['Ammonizioni'] = 2; // capire se far vedere tutte le ammonizione o le 2 per la diffida
                            }
                            if ($ammonizioni_data[$atleta]['Stato'] == "Squalificato") {
                                $res[$key_campionato][$key_girone][$key_day]['Squalificati'][$atleta] = $ammonizioni_data[$atleta];

                                $res[$key_campionato][$key_girone][$key_day]['Squalificati'][$atleta]['Ammonizioni'] = 3; // capire se far vedere tutte le ammonizione o le 3 per la squalifica
                            }
                        }

                        $res[$key_campionato][$key_girone][$key_day]['Ammoniti'] = $ammonizioni_data;
                    }
                }
            }
        }
    }


    private function assegnaAmmonizione($ammonizione)
    {
        $res = [];
        $atleta = $ammonizione['Atleta'];
        $anagrafica = $this->atleti_array[$atleta]['Cognome'] . " " . $this->atleti_array[$atleta]['Nome'];
        $squadra_campionato = $ammonizione['SquadraCampionato'];

        $res['Anagrafica'] = $anagrafica;
        $res['Squadra'] = $this->squadre_campionati_array[$squadra_campionato]['Denominazione'];
        $res['Ammonizioni'] = 1;
        //        $res['Stato'] = "";

        return $res;
    }


    private function leggiAmmonizione($ammonizione)
    {
        $res = [];

        if ($ammonizione['Ammonizioni'] == 1) {
            $res = "Ammonito";
        }

        if (3 % $ammonizione['Ammonizioni'] == 1) {
            $res = "Diffidato";
        }
        if (3 % $ammonizione['Ammonizioni'] == 0 && $ammonizione['Ammonizioni'] > 1) {
            $res = "Squalificato";
        }

        return $res;
    }


    private function infoCalendario($id_calendario)
    {
        $query = "SELECT * FROM `Calendari` WHERE Calendario = '{$id_calendario}'";

        return $this->db->select_sql($query)[0];
    }


    public function bollettini(&$calendario, $current)
    {
        $res = [];

        $this->db->write_file("bollettini_cal.json", json_encode($calendario));

        foreach ($calendario as $key_campionato => $campionato) {
            foreach ($campionato['Gironi'] as $key_girone => $girone) {

                foreach ($girone['Giornata'] as $key_giornata => $giornata) {
                    $res[$key_campionato][$key_girone][$key_giornata] = "";
                }
            }
        }


        $query = "SELECT 
                        Campionati.Campionato, 
                        Bollettini.* 
                FROM 
                        `Bollettini` 
                        INNER JOIN GironiCampionati ON GironiCampionati.GironeCampionato = Bollettini.GironeCampionato 
                        INNER JOIN Campionati ON GironiCampionati.Campionato = Campionati.Campionato 
                WHERE 
                        Campionati.AnnoSportivo = '{$current}'";

        $bollettini = $this->db->select_sql($query);

        foreach ($bollettini as $bollettino) {
            $campionato = $bollettino['Campionato'];
            $girone = $bollettino['GironeCampionato'];
            $giornata = $bollettino['Giornata'];
            $note = $bollettino['Note'];
            $data_inserimento = date_format(date_create($bollettino['DataInserimento']), "d/m/Y");
            $id = $bollettino['Bollettino'];
            $res[$campionato][$girone][$giornata][$id] = ["Note" => $note, "DataInserimento" => $data_inserimento];
        }

        $this->db->write_file("bollettini.json", json_encode($res));
    }


    public function squalificatiATempo($current)
    {
        $now = date("Y-m-d 00:00:00");

        $sql = "SELECT 
                        GoalPartite.GoalPartita,
                        Campionati.Nome as Nomecampionato, 
                        GironiCampionati.Descrizione as NomeGirone, 
                        GoalPartite.Atleta, 
                        Atleti.Cognome, 
                        Atleti.Nome, 
                        Squadre.Denominazione as NomeSquadra, 
                        GoalPartite.Motivo,
                        Calendari.Campionato, 
                        Calendari.GironeCampionato, 
                        SquadreCampionati.Squadra, 
                        GoalPartite.Espulsione, 
                        GoalPartite.EspulsioneInizio, 
                        GoalPartite.EspulsioneFine 
                FROM 
                        `Campionati` 
                        INNER JOIN Calendari ON Calendari.Campionato = Campionati.Campionato 
                        INNER JOIN GironiCampionati ON GironiCampionati.GironeCampionato = Calendari.GironeCampionato 
                        INNER JOIN GoalPartite ON GoalPartite.Calendario = Calendari.Calendario 
                        INNER JOIN Atleti on GoalPartite.Atleta = Atleti.Atleta 
                        INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato 
                        INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra 
                WHERE 
                        Campionati.AnnoSportivo = '{$current}' 
                        AND Campionati.PlayLeague = '1' 
                        AND GoalPartite.Espulsione = 'Si' 
                        AND GoalPartite.EspulsioneFine IS NOT NULL 
                        AND GoalPartite.EspulsioneFine >= '{$now}' 
                ORDER BY 
                        GoalPartite.EspulsioneFine ASC, 
                        #Campionati.Nome ASC, 
                        #GironiCampionati.Descrizione ASC,
                        CONCAT(Atleti.Cognome, Atleti.Nome) ASC,
                        Squadre.Denominazione ASC
                        ";

        //        $this->db->write_file("_query_squalificati_a_tempo", $sql);

        $res = $this->db->select_sql($sql);

        $this->db->write_file("squalificatiATempo.json", json_encode($res));
    }


    public function cities($data)
    {
        $query = "";

        if (isset($data['city_name'])) {
            $city = strtolower($data['city_name']);

            $query = "SELECT * FROM city WHERE LOWER(city_name) LIKE '{$city}%' ORDER BY city_name ASC";
        }
        if (isset($data['id'])) {
            $id = $data['id'];

            $query = "SELECT * FROM city WHERE id = '{$id}' ORDER BY city_name ASC";
        }

        $res = $this->db->select_sql($query);

        return $this->db->key_select($res, 'id');
    }


    public function _associaCittaAtletiPresenti()
    {


        $query = "SELECT * FROM Atleti GROUP BY Atleti.LuogoNascita";

        $atletiForBas = $this->db->select_sql($query);

        $n_ = 0;
        foreach ($atletiForBas as $atleta) {
            $luogoNascita = $atleta['LuogoNascita'];

            $q = "SELECT city.*, COUNT(id) as n FROM city WHERE city_name LIKE '{$luogoNascita}'";

            $city = $this->db->select_sql($q);

            if ($city[0]['n'] > 0) // citta con nomi leggibili
            {
                $n_++;
                echo $atleta['LuogoNascita'] . "    - id: " . $city[0]['id'] . "<br>";

                $city_id = $city[0]['id'];
                $query_edit = "UPDATE `Atleti` SET `CityNascita` = '{$city_id}' WHERE `LuogoNascita` LIKE '{$luogoNascita}'";
                //                $this->db->my_query($query_edit);
                echo $query_edit . "<br><br>";
            }
        }

        echo "----------------------------------<br>";
        echo "totale esistenti: {$n_}<br>";
        echo "----------------------------------<br>";
        echo "----------------------------------<br>";

        $query = "  SELECT
                        Atleti.*
                    FROM
                        `Annuario`
                    INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
                    INNER JOIN SquadreBAS ON SquadreBAS.Squadra = SquadreCampionati.Squadra
                    INNER JOIN Atleti ON Annuario.Atleta = Atleti.Atleta 
                    WHERE Annuario.AnnoSportivo = '2024'
                    GROUP BY Atleti.LuogoNascita";

        $atletiForBas = $this->db->select_sql($query);

        $n_ = 0;
        foreach ($atletiForBas as $atleta) {
            $luogoNascita = $atleta['LuogoNascita'];

            $q = "SELECT city.*, COUNT(id) as n FROM city WHERE city_name LIKE '{$luogoNascita}'";

            $city = $this->db->select_sql($q);

            if ($city[0]['n'] == 0) // citta con nomi leggibili
            {
                $n_++;

                //                $city_id = $city[0]['id'];
                //                $query_edit = "UPDATE `Atleti` SET `CityNascita` = '{$city_id}' WHERE `LuogoNascita` LIKE '{$luogoNascita}'";
                //                $this->db->my_query($query_edit);

                echo $atleta['LuogoNascita'] . " " . $city[0]['id'] . "<br>";
                //                echo $query_edit . "<br><br>";
            }
        }

        echo "----------------------------------<br>";
        echo "totale da integrare: {$n_}<br>";
    }


    public function associaCittaAtletiPresentiDB(&$post) //questi sono sugli atleti associati alle squadre bas
    {

        $luogoNascitaTrim = addslashes(strtoupper(trim($post['LuogoNascita']))); //tolgo gli spazi e metto tutto maiuscolo
        $luogoNascita = addslashes($post['LuogoNascita']);

        $q = "SELECT city.*, COUNT(id) as n FROM city WHERE city_name LIKE '{$luogoNascita}'";

        $city = $this->db->select_sql($q);

        if ($city[0]['n'] > 0) // citta con nomi leggibili
        {

            $city_id = $city[0]['id'];
            $query_edit = "UPDATE `Atleti` SET `CityNascita` = '{$city_id}', `LuogoNascita` = '{$luogoNascitaTrim}' WHERE `LuogoNascita` LIKE '{$luogoNascita}'";
            $this->db->my_query($query_edit);

            $post['presente'] = true;
        }

        if ($city[0]['n'] == 0) // citta con nomi non leggibili
        {
            $post['presente'] = false;
        }
    }


    public function associaCittaAtletiPresenti() // raggruppo le città presenti nel db
    {
        $query = "SELECT LuogoNascita FROM Atleti GROUP BY Atleti.LuogoNascita";

        $atleti = $this->db->select_sql($query);

        return ["CITY_ALL" => $atleti];
    }


    public function cittaNonAssociateAll() //queste sono le città non associate degli atleti associati alle squadre bas
    {
        $res = [];

        $allCity = [];

        $query = "  SELECT
                        Atleti.LuogoNascita,
                        Atleti.Cognome,
                        Atleti.Nome,
                        Atleti.Email,
                        Atleti.Telefono,
                        Atleti.Cellulare,
                        DATE_FORMAT(Atleti.DataNascita, '%d/%m/%Y') AS DataNascitaFormat
                    FROM
                        `Atleti`
                    WHERE
                        CityNascita = 0
                    ORDER BY
                        LuogoNascita ASC";

        $allCityNoFilter = $this->db->select_sql($query);

        foreach ($allCityNoFilter as $atleta) {
            $luogoNascita = $atleta['LuogoNascita'];

            $allCity[$luogoNascita][] = $atleta;
        }

        foreach ($allCity as $luogoNascita => $atleta) {
            //            $luogoNascita = $atleta['LuogoNascita'];

            $luogoNascita = addslashes($luogoNascita);

            $q = "SELECT city.*, COUNT(id) as n FROM city WHERE city_name LIKE '{$luogoNascita}'";

            $city = $this->db->select_sql($q);

            if ($city[0]['n'] == 0) // citta con nomi non leggibili
            {
                $res[$luogoNascita][] = $atleta;
            }
        }

        $this->db->write_file("##atletiForBas", $allCity);

        return $res;
    }


    public function associaCittaAtletiPresentiBAS() //queste sono le città non associate degli atleti associati alle squadre bas
    {

        $res = [];

        $atletiForBas = [];

        $query = "  SELECT
                        Atleti.*, DATE_FORMAT(Atleti.DataNascita, '%d/%m/%Y') as DataNascitaFormat
                    FROM
                        `Annuario`
                    INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
                    INNER JOIN SquadreBAS ON SquadreBAS.Squadra = SquadreCampionati.Squadra
                    INNER JOIN Atleti ON Annuario.Atleta = Atleti.Atleta 
                    WHERE Annuario.AnnoSportivo = '2024' ORDER BY Atleti.LuogoNascita ASC
                    #GROUP BY Atleti.LuogoNascita";

        $atletiForBasNoFilter = $this->db->select_sql($query);

        foreach ($atletiForBasNoFilter as $atleta) {
            $luogoNascita = $atleta['LuogoNascita'];

            $atletiForBas[$luogoNascita][] = $atleta;
        }

        foreach ($atletiForBas as $luogoNascita => $atleta) {
            //            $luogoNascita = $atleta['LuogoNascita'];

            $luogoNascita = addslashes($luogoNascita);

            $q = "SELECT city.*, COUNT(id) as n FROM city WHERE city_name LIKE '{$luogoNascita}'";

            $city = $this->db->select_sql($q);

            if ($city[0]['n'] == 0) // citta con nomi non leggibili
            {
                $res[$luogoNascita][] = $atleta;
            }
        }

        $this->db->write_file("##atletiForBas", $atletiForBas);

        return $res;
    }


    public function salvaCittaBasNonAssociate($post)
    {
        $res = [];
        foreach ($post as $city_id => $value) {
            $luogoNascita = addslashes($value['nome']);
            $luogoNascitaErrato = addslashes($value['nome_iniziale']);
            $query_edit = "UPDATE `Atleti` SET `CityNascita` = '{$city_id}', `LuogoNascita` = '{$luogoNascita}' WHERE `LuogoNascita` LIKE '{$luogoNascitaErrato}'";
            $this->db->my_query($query_edit);
            $res[] = $query_edit;
        }

        return $res;
    }


    public function tesseraAtletaBAS($post)
    {
        if (!isset($post['response']['data']))
            return false;

        // $anno_sportivo = "2024";
        $anno_sportivo = $this->annoSportivo()['current']['year'];

        $squadra = $post['squadra'];
        $atleta = $post['atleta'];

        $subscriber_id = $post['response']['data']['subscriber_id'];
        $card_id = $post['response']['data']['card_id'];

        $query = "SELECT * FROM `AtletiBAS` WHERE `AnnoSportivo` = '{$anno_sportivo}' AND `Atleta` = {$atleta} AND `Squadra` = {$squadra} ORDER BY `id` DESC";
        $ab = $this->db->select_sql($query);

        if (count($ab) > 0) {
            $query = "UPDATE
                            `AtletiBAS`
                        SET
                            `subscriber_id` = '{$subscriber_id}',
                            `card_id` = '{$card_id}'
                        WHERE
                            Atleta = '{$atleta}' AND Squadra = '{$squadra}' AND AnnoSportivo = '{$anno_sportivo}'";

            $this->db->my_query($query);
        } else {
            $values['subscriber_id'] = $subscriber_id;
            $values['card_id'] = $card_id;
            $values['AnnoSportivo'] = "2024";
            $values['Atleta'] = $atleta;
            $values['Squadra'] = $squadra;

            if (is_numeric($card_id)) {
                $values['data_tesseramento'] = date("Y-m-d h:i:s");
            }

            $this->db->insert_into("AtletiBAS", $values);
        }
    }


    // CODICE FISCALE ------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------

    public function generateCF($data)
    {
        $res['cognome'] = $this->_calcolaCognome($data['general_counsel_firstname']);
        $res['nome'] = $this->_calcolaNome($data['general_counsel_lastname']);
        $res['nascita'] = $this->_calcolaDataNascita($data['general_counsel_birthday'], $data['general_counsel_gender']);
        $res['code_city'] = $data['general_counsel_birthplace_city_code'];
        $codice = implode("", $res);
        $res['controllo'] = $this->_calcolaCifraControllo($codice);
        $res['complete'] = implode("", $res);
        return $res;

        //        return $data;
    }


    public function atletaBAS($data)
    {
        $Atleta = $data['Atleta'];

        $query = "SELECT *, COUNT(id) AS numValues FROM AtletiBAS WHERE Atleta = '{$Atleta}' ORDER BY AnnoSportivo DESC LIMIT 1";

        $res = $this->db->select_sql($query)[0];

        $data['query'] = $query;
        $data['res'] = $res;

        return $data;
    }



    // GIUSEPPE 2025-09-23 ---------------------------------------------------------
    // controllo che l'assicurazione abbia id 1 o 11
    public function cercaAssicurazione($atleta, $squadra)
    {
        $res = [
            "assicurazione" => 0,
            "invia" => false,
            "insurance" => ""
        ];

        $anno_sportivo_tot = $this->annoSportivo();

        $anno_sportivo = $anno_sportivo_tot["current"]["year"];

        $query = "      SELECT
                            Annuario.TipoAssicurazione
                        FROM
                            `Annuario`
                        INNER JOIN SquadreCampionati ON SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
                        INNER JOIN Campionati ON Campionati.Campionato = SquadreCampionati.Campionato
                        WHERE
                            Campionati.AnnoSportivo = '{$anno_sportivo}' AND Annuario.Atleta = {$atleta} AND SquadreCampionati.Squadra = '{$squadra}'
                        GROUP BY
                            Annuario.TipoAssicurazione
                        ORDER BY
                            Annuario.`Annuario`
                        DESC";



        $res_query = $this->db->select_sql($query);

        if (count($res_query) > 0) {
            $assicurazione = $res_query[0]['TipoAssicurazione'];

            $res["assicurazione"] = $assicurazione;

            if ($assicurazione == 1) {
                $res["insurance"] = "BASFIA2";
                $res["invia"] = true;
            }

            if ($assicurazione == 11) {
                $res["insurance"] = "BASFIA1";
                $res["invia"] = true;
            }
        }

        return $res;
    }
    // -----------------------------------------------------------------------------


    protected function _calcolaCognome($string)
    {
        $cognome = $this->_sanitize($string);

        // Se il cognome inserito e' piu' corto di 3 lettere
        // si aggiungono tante X quanti sono i caratteri
        // mancanti.

        $code = "";

        if (strlen($cognome) < 3) {
            return $this->_addMissingX($cognome);
        }

        $cognome_cons = $this->_getConsonanti($cognome);

        // Per il calcolo del cognome si prendono le prime
        // 3 consonanti. 
        for ($i = 0; $i < 3; $i++) {
            if (array_key_exists($i, $cognome_cons)) {
                $code .= $cognome_cons[$i];
            }
        }

        // Se le consonanti non bastano, vengono prese
        // le vocali nell'ordine in cui compaiono.
        if (strlen($code) < 3) {
            $cognome_voc = $this->_getVocali($cognome);
            while (strlen($code) < 3) {
                $code .= array_shift($cognome_voc);
            }
        }

        return $code;
    }


    protected function _calcolaNome($string)
    {
        $nome = $this->_sanitize($string);

        // Se il nome inserito e' piu' corto di 3 lettere
        // si aggiungono tante X quanti sono i caratteri
        // mancanti.
        if (strlen($nome) < 3) {
            return $this->_addMissingX($nome);
        }

        $nome_cons = $this->_getConsonanti($nome);

        // Se le consonanti contenute nel nome sono minori 
        // o uguali a 3 vengono considerate nell'ordine in cui
        // compaiono.
        if (count($nome_cons) <= 3) {
            $code = implode('', $nome_cons);
        } else {
            // Se invece abbiamo almeno 4 consonanti, prendiamo
            // la prima, la terza e la quarta.
            for ($i = 0; $i < 4; $i++) {
                if ($i == 1)
                    continue;
                if (!empty($nome_cons[$i])) {
                    $code .= $nome_cons[$i];
                }
            }
        }

        // Se compaiono meno di 3 consonanti nel nome, si
        // utilizzano le vocali, nell'ordine in cui compaiono
        // nel nome.
        if (strlen($code) < 3) {
            $nome_voc = $this->_getVocali($nome);
            while (strlen($code) < 3) {
                $code .= array_shift($nome_voc);
            }
        }

        return $code;
    }


    protected function _calcolaDataNascita($data, $sesso)
    {
        $dn = explode("-", $data);

        $giorno = (int) @$dn[2];
        $mese = (int) @$dn[1];
        $anno = (int) @$dn[0];

        // Le ultime due cifre dell'anno di nascita
        $aa = substr($anno, -2);

        // La lettera corrispondente al mese di nascita
        $mm = $this->_mesi[$mese];

        // Il giorno viene calcolato a seconda del sesso
        // del soggetto di cui si calcola il codice:
        // se e' Maschio si mette il giorno reale, se e' 
        // Femmina viene aggiungo 40 a questo numero.
        $gg = (strtoupper($sesso) == 'M') ? $giorno : ($giorno + 40);

        // Bug #1: Thanks to Luca 
        if (strlen($gg) < 2)
            $gg = '0' . $gg;


        return $aa . $mm . $gg;
    }


    protected function _calcolaCifraControllo($codice)
    {
        $code = str_split($codice);
        $sum = 0;

        for ($i = 1; $i <= count($code); $i++) {
            $cifra = $code[$i - 1];
            $sum += ($i % 2) ? $this->_dispari[$cifra] : $this->_pari[$cifra];
        }

        $sum %= 26;

        return $this->_controllo[$sum];
    }


    protected function _addMissingX($string)
    {
        $code = $string;
        while (strlen($code) < 3) {
            $code .= 'X';
        }
        return $code;
    }


    protected function _sanitize($string, $toupper = true)
    {
        $result = preg_replace('/[^A-Za-z]*/', '', $string);
        return ($toupper) ? strtoupper($result) : $result;
    }


    /**
     * Ritorna un array con le vocali di una data stringa
     */
    protected function _getVocali($string)
    {
        return $this->_getLettere($string, $this->_vocali);
    }


    /**
     * Ritorna un array con le consonanti di una data stringa
     */
    protected function _getConsonanti($string)
    {
        return $this->_getLettere($string, $this->_consonanti);
    }


    protected function _getLettere($string, array $haystack)
    {
        $letters = array();
        foreach (str_split($string) as $needle) {
            if (in_array($needle, $haystack)) {
                $letters[] = $needle;
            }
        }
        return $letters;
    }

    // ---------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------
}
