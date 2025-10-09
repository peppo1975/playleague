<?


//file nuovo
//GIUSEPPE 2022-08-23
//  include __DIR__ . '/campis_controller.php';

class ApisController extends AppController
{

    // GIUSEPPE 2023-01-17 - - - - - - - - - - - -
    var $components = array('Email');


    // - - - - - - - - - - - - - - - - - - - - - -

    public function viewState($state)
    {
        $class = "";
        switch ($state) {
            case "L":
                $class = "libero";
                break;
            case "C":
                $class = "campionato";
                break;
            case "P":
                $class = "privato";
                break;
        }

        return $class;
    }


    public function getApiKey()
    {
        $api = new Api();
        return $api->getApiKey();
    }


    public function campi()
    {
        $api = new Api();
        $api->isApiKey();

        $sport = $_POST['sport'];
        $date = $_POST['date'];
        $week = $date['weekpicker'];
        $filter_campi = isset($_POST['filter_campi']) ? $_POST['filter_campi'] : [];

        /*         * *************************************************************** */
        /* fisso in  public function campi() e  public function campiJson() */
        /*         * *************************************************************** */
        $res = $api->campi($sport, $date, $filter_campi);

        $pren = $api->cercaPrenotazioni(array_keys($res), $date);

        $api->mergeCampiPrenotazioni($res, $pren);

        $range_week = $api->intervalloDateWeek($week);
        /*         * *************************************************************** */
        $this->write_file("_res", $res);

        include '../views/campis/admin_prospetto_table.ctp';

        print $html;

        exit();
    }


    public function campiJson()
    {

        $api = new Api();
        $api->isApiKey();

        $data = json_decode(file_get_contents('php://input'), true);

        $sport = $data['sport'];
        $date = $data['date'];
        $week = $date['weekpicker'];

        /*         * *************************************************************** */
        /* fisso in  public function campi() e  public function campiJson() */
        /*         * *************************************************************** */
        $res = $api->campi($sport, $date);

        $pren = $api->cercaPrenotazioni(array_keys($res), $date);

        $api->mergeCampiPrenotazioni($res, $pren);

        $range_week = $api->intervalloDateWeek($week);
        /*         * *************************************************************** */

        header('Content-Type: application/json');

        print json_encode([$res, $pren, $range_week]);

        exit();
    }


    //GIUSEPPE  2023-01-17 -------------------------------------------
    private function stringResponseWeek($dal, $al)
    {
        return "dal: " . $dal . " al: " . $al;
    }


    public function aggiornaBookersNewsLetters()
    {
        $api = new Api();

        $api->aggiornaBookersNewsLetters();

        exit();
    }


    public function admin_sendSmsToBooker()
    {
        include __DIR__ . '/campis_controller.php';

        $campis = new CampisController();

        $cell = $_POST['cell'];
        $smsNote = $_POST['smsNote'];

        $r = $campis->sendSms(
            array(
                'text' => $smsNote,
                'dest' => '39' . $cell
            )
        );

        echo json_encode(["response" => $r]);
        exit();
    }


    //----------------------------------------------------------------  

    function dalalSett($anno_settimana)
    {
        $res = $this->calcolaSettimana($anno_settimana);
        print $this->stringResponseWeek($res[0], $res[1]);
        exit();
    }


    function dalalSettInit($anno_settimana)
    {

        $res = $this->calcolaSettimana($anno_settimana);
        return $this->stringResponseWeek($res[0], $res[1]);
        //        return "dal: " . $res[0] . " <br>al: " . $res[1];
    }


    private function calcolaSettimana($anno_settimana)
    {
        $expl = explode("-", $anno_settimana);
        $anno = $expl[0];
        $Sett = str_replace("W", "", $expl[1]);
        $data = new DateTime();
        $data->setISODate($anno, $Sett, 1);
        $dal = $data->format('d/m/Y');
        $data->setISODate($anno, $Sett, 7);
        $al = $data->format('d/m/Y');
        return [$dal, $al];
    }


    public function numSettimana($date)
    {
        $expl = explode("-", $date);
        $year = $expl[0];
        $month = $expl[1];
        $day = $expl[2];

        $week = date("W", mktime(0, 0, 0, $month, $day, $year));

        print "{$year}-W{$week}"; //2022-W35
        exit();
    }


    public function saveBooking()
    {
        $api = new Api();
        print_r(json_encode($api->saveBooking()));

        //$this->senEmailBooking();

        exit();
    }


    public function editBooking()
    {
        $api = new Api();
        //print_r(json_encode($api->saveBooking()));
        //$this->senEmailBooking();

        $api->editBooking();

        exit();
    }


    //GIUSEPPE 2023-01-17 - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function _sendEmailBooking()
    {
        $api = new Api();

        $dates = [];
        $dates = $_POST['Data'];

        unset($_POST['Data']);

        $this->write_file("_POST_email", $_POST);

        foreach ($dates as $dd) {
            $_POST['Data'] = $dd;

            $this->write_file("_POST_email_2", $_POST);

            $campo_id = $_POST['campo_id'];

            $nome_campo = $api->nomeCampo($_POST);

            $ora_post = $_POST['Ora'];

            $ora_expl = explode(":", $ora_post);

            $ora = $ora_expl[0] . ":" . $ora_expl[1];

            $importo_expl = explode(".", $nome_campo['Importo']);

            $importo = $importo_expl[0] . "," . $importo_expl[1];

            $this->Email->to = $_POST['bookerEmail'];

            $this->set('cognome', $_POST['bookerCognome']);
            $this->set('nome', $_POST['bookerNome']);
            $this->set('email', $_POST['bookerEmail']);
            $this->set('telefono', $_POST['bookerTelefono']);
            $this->set('nome_campo', $nome_campo['Descrizione']);
            $this->set('importo', $importo);
            $this->set('ora', $ora);

            $date = date_create($_POST['Data']);
            //        date_format($date, "Y/m/d");

            $this->set('data', date_format($date, "d/m/Y"));

            /* fixed edit */
            $this->Email->from = "Play League <booking@playleaguesport.it>";

            $this->Email->subject = 'Conferma prenotazione campo';

            $this->Email->template = 'prenotazione_campo';

            $this->Email->send();
            $this->write_file("_POST_email_4", $dd);
            //            $this->sms($_POST, $nome_campo);
        }



        exit();
    }


    public function sendEmailBooking()
    {
        $api = new Api();

        $prenotazione = $_POST['Prenotazione'];

        $info_prenotazione = $api->infoPrenotazione($prenotazione);

        //        $this->write_file("_POST_email", $info_prenotazione);

        $nome_campo = $info_prenotazione['Campo'];
        $booker = $info_prenotazione['Booker'];
        $email = $info_prenotazione['Email'];
        $telefono = $info_prenotazione['Telefono'];
        $giorni = $info_prenotazione['Giorni'];
        $link = "https://" . $_SERVER['HTTP_HOST'] . "/apis/viewBookingCampi?prenotazione={$prenotazione}";

        $this->set('booker', $booker);
        $this->set('email', $email);
        $this->set('telefono', $telefono);
        $this->set('nome_campo', $nome_campo);
        $this->set('prenotazione', $prenotazione);
        $this->set('giorni', $giorni);
        $this->set('link', $link);

        $this->Email->to = $email;
        $this->Email->from = "Play League <booking@playleaguesport.it>";
        $this->Email->subject = 'Conferma prenotazione campo';
        $this->Email->template = 'prenotazione_campo';

        $this->Email->send();

        // SMS

        $this->sms(
            $booker,
            $email,
            $telefono,
            $nome_campo,
            $prenotazione,
            $giorni,
            $link
        );

        exit();
    }


    public function _sms($post = false, $nome_campo = false)
    {

        include_once __DIR__ . '/campis_controller.php';
        $this->write_file("_sms_4", $post);
        //$this->write_file("_nome_campo", $nome_campo);

        $campis = new CampisController();

        if (($post == false) && ($nome_campo == false)) // test
        {
            $post['bookerEmail'] = "peppe@mailinator.com";
            $post['bookerCognome'] = "Lagonigro";
            $post['bookerNome'] = "Giuseppe";
            $post['bookerTelefono'] = "3283630647";

            $post['Data'] = date("Y-m-d");
            $post['Ora'] = "15:30";

            $nome_campo['Importo'] = "300";
            $nome_campo['Descrizione'] = "San Siro";
        }

        $email = $post['bookerEmail'];
        $cognome = $post['bookerCognome'];
        $nome = $post['bookerNome'];
        $telefono = $post['bookerTelefono'];

        $date = date_create($post['Data']);
        $data = date_format($date, "d/m/Y");

        $ora_expl = explode(":", $post['Ora']);
        $ora = $ora_expl[0] . ":" . $ora_expl[1];

        $descrizione_campo = $nome_campo['Descrizione'];
        $prezzo_campo_expl = explode(".", $nome_campo['Importo']);
        $prezzo_campo = $prezzo_campo_expl[0] . "," . $prezzo_campo_expl[1];

        $text = "Play League\nPrenotazione campo {$descrizione_campo} a nome di {$nome} {$cognome} per il giorno {$data} alle ore {$ora}.\nTotale allenamento {$prezzo_campo} euro";

        $r = $campis->sendSms(
            array(
                'text' => $text,
                'dest' => '39' . $telefono
            )
        );

        print_r($r);

        //        exit();
        //        return 0;
    }


    public function sms(
        $booker,
        $email,
        $telefono,
        $nome_campo,
        $prenotazione,
        $giorni,
        $link
    ) {

        include_once __DIR__ . '/campis_controller.php';

        $campis = new CampisController();

        $text = "";
        $to_send = "";
        $info = "\nInfo: " . $link;

        $data = $giorni[0]['Data'];
        $ora = $giorni[0]['Ora'];
        $prezzo_campo = $giorni[0]['Importo'];
        //            $text = "Play League\nPrenotazione campo {$nome_campo} a nome di {$booker} per il giorno {$data} alle ore {$ora}.\nTotale allenamento {$prezzo_campo} euro";

        $text = "Play League\nPrenotazione campo {$nome_campo} a nome di {$booker}";
        $text .= $info;

        if (strlen($text) <= 160) {
            $to_send = $text;
        } else {
            $to_send = "Play League\nPrenotazione campo {$nome_campo}" . $info;
        }

        $r = $campis->sendSms(
            array(
                'text' => $to_send,
                'dest' => '39' . $telefono
            )
        );

        print_r(json_encode(["stateSms" => $r]));
    }


    public function testSendSMS()
    {
        include_once __DIR__ . '/campis_controller.php';
        //        $campis = new CampisController();
        //        $text = "Questo e' un test con 160 caratteri per vedere se è tutto ok
        //                Questo e' un test con 160 caratteri per vedere se è tutto ok
        //                Questo e' un test con 160 caratteri per vedere se è tutto ok ";
        $campis = new CampisController();
        $text = '<a href="tel:393283630647">Chiama</a>';
        $r = $campis->sendSms(
            array(
                'text' => $text,
                'dest' => '393283630647'
            )
        );
        exit();
    }


    public function filtraCampi()
    {
        $api = new Api();

        $res = $api->filtraCampi($_POST);

        print json_encode($res);

        exit();
    }


    //GIUSEPPE 2023-01-17  - - - - - - - - - - - - - - - - - - - - 
    public function searchEmailBooking()
    {
        $api = new Api();
        $api->searchEmailBooking($_POST);
        exit();
    }


    public function upgradeBookers()
    {
        $api = new Api();

        $api->upgradeBookers();
    }

    /* date("W", mktime(0, 0, 0, 9, 5, 2020)); */

    /*
      mktime(
      int $hour,
      ?int $minute = null,
      ?int $second = null,
      ?int $month = null,
      ?int $day = null,
      ?int $year = null
      ): int|false
     */


    //    function giornoData($g, $m, $a)
    //    {
    //        $gShort = array('Dom', 'Lun', 'Mart', 'Merc', 'Giov', 'Ven', 'Sab');
    //        $ts = mktime(0, 0, 0, $m, $g, $a);
    //        $gd = getdate($ts);
    //        echo $gShort[$gd['wday']];
    //        exit();
    //    }
    //    private function 
    //echo dalalSett(52, 2016);
    //
    //
    //
    //
    //
    //
    //GIUSEPPE 2022-09-13 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
    public function calendario()
    {
        $api = new Api();
        $api->isApiKey();

        $anno_sportivo = $api->annoSportivo();

        $current = $anno_sportivo['current']['year'];

        $res = $api->calendario($current);

        //        header('Content-Type: application/json');
        //        print json_encode($res);
        echo print_r($res, true);

        exit();
    }


    public function marcatori()
    {
        $api = new Api();
        $api->isApiKey();

        $anno_sportivo = $api->annoSportivo();

        $current = $anno_sportivo['current']['year'];

        $res = $api->marcatori($current);

        echo print_r($res, true);

        exit();
    }


    public function homeView()
    {
        $api = new Api();
        $api->isApiKey();

        ob_start();
        include __DIR__ . "/../webroot/_content/marcatori.json";
        $html = ob_get_clean();
        $marcatori = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/calendario.json";
        $html = ob_get_clean();
        $calendario = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/classifica.json";
        $html = ob_get_clean();
        $classifica = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/menu_tendina.json";
        $html = ob_get_clean();
        $menu_tendina = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/disciplinari.json";
        $html = ob_get_clean();
        $disciplinari = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/sanzioni.json";
        $html = ob_get_clean();
        $sanzioni = json_decode($html, true);

        ob_start();
        include __DIR__ . "/../webroot/_content/bollettini.json";
        $html = ob_get_clean();
        $bollettini = json_decode($html, true);

        $server = $api->getServerName();

        include __DIR__ . "/../views/apis/homeView/homeView.ctp";

        exit();
    }


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    public function homeViewAxios()
    {
        $api = new Api();
        $api->isApiKey();

        ob_start();
        include __DIR__ . "/../webroot/_content/menu_tendina.json";
        $html = ob_get_clean();
        $menu_tendina = json_decode($html, true);

        $server = $api->getServerName();
        $apiKey = $api->getApiKey();
        include __DIR__ . "/../views/apis/homeView/homeViewAxios.ctp";

        exit();
    }


    public function calendariClassifiche()
    {
        $api = new Api();

        ob_start();
        include __DIR__ . "/../webroot/_content/menu_tendina.json";
        $html = ob_get_clean();
        $menu_tendina = json_decode($html, true);

        $server = $api->getServerName();
        $apiKey = $api->getApiKey();
        include __DIR__ . "/../views/apis/homeView/homeViewAxios.ctp";

        exit();
    }

    public function calendariClassifichePageOther()
    {
        $api = new Api();

        ob_start();
        include __DIR__ . "/../webroot/_content/menu_tendina.json";
        $html = ob_get_clean();
        $menu_tendina = json_decode($html, true);

        ob_start();
        $server = $api->getServerName();
        $apiKey = $api->getApiKey();
        include __DIR__ . "/../views/apis/homeView/homeViewAxiosPageOther.ctp";
        $res = ob_get_clean();
        return $res;
    }


    public function homeViewAxiosJquery()
    {
        $api = new Api();
        $api->isApiKey();

        ob_start();
        include __DIR__ . "/../webroot/_content/menu_tendina.json";
        $html = ob_get_clean();
        $menu_tendina = json_decode($html, true);

        $server = $api->getServerName();
        $apiKey = $api->getApiKey();
        include __DIR__ . "/../views/apis/homeView/homeViewAxios_jquery.ctp";

        exit();
    }


    public function filterCampionatoGirone()
    {
        header('Access-Control-Allow-Origin: *');
        $api = new Api();

        $id_campionato = $_GET['id_campionato'];
        $id_girone = $_GET['id_girone'];
        $server = $api->getServerName();
        $apiKey = $api->getApiKey();

        if ($id_girone > 0):


            ob_start();
            include __DIR__ . "/../webroot/_content/calendario.json";
            $html = ob_get_clean();
            $calendario_html = json_decode($html, true);
            $calendario[$id_campionato] = $calendario_html[$id_campionato];
            $calendario[$id_campionato]['Gironi'] = [];
            $calendario[$id_campionato]['Gironi'][$id_girone] = $calendario_html[$id_campionato]['Gironi'][$id_girone];

            ob_start();
            include __DIR__ . "/../webroot/_content/classifica.json";
            $html = ob_get_clean();
            $classifica_html = json_decode($html, true);
            $classifica_html[$id_campionato] = $classifica_html[$id_campionato];
            $classifica[$id_campionato]['Gironi'] = [];
            $classifica[$id_campionato]['Gironi'][$id_girone] = $classifica_html[$id_campionato]['Gironi'][$id_girone];

            ob_start();
            include __DIR__ . "/../webroot/_content/marcatori.json";
            $html = ob_get_clean();
            $marcatori_html = json_decode($html, true);
            $marcatori[$id_campionato]['Gironi'] = [];
            $marcatori[$id_campionato]['Gironi'][$id_girone] = $marcatori_html[$id_campionato]['Gironi'][$id_girone];

            ob_start();
            include __DIR__ . "/../webroot/_content/disciplinari.json";
            $html = ob_get_clean();
            $disciplinari_html = json_decode($html, true);
            $disciplinari[$id_campionato][$id_girone] = $disciplinari_html[$id_campionato][$id_girone];

            ob_start();
            include __DIR__ . "/../webroot/_content/bollettini.json";
            $html = ob_get_clean();
            $bollettini_html = json_decode($html, true);
            $bollettini[$id_campionato][$id_girone] = $bollettini_html[$id_campionato][$id_girone];

        elseif ($id_girone == -1):


            ob_start();
            include __DIR__ . "/../webroot/_content/calendario.json";
            $html = ob_get_clean();
            $calendario_html = json_decode($html, true);
            $calendario[$id_campionato] = $calendario_html[$id_campionato];

            ob_start();
            include __DIR__ . "/../webroot/_content/classifica.json";
            $html = ob_get_clean();
            $classifica_html = json_decode($html, true);
            $classifica[$id_campionato] = $classifica_html[$id_campionato];

            ob_start();
            include __DIR__ . "/../webroot/_content/marcatori.json";
            $html = ob_get_clean();
            $marcatori_html = json_decode($html, true);
            $marcatori[$id_campionato] = $marcatori_html[$id_campionato];

            ob_start();
            include __DIR__ . "/../webroot/_content/disciplinari.json";
            $html = ob_get_clean();
            $disciplinari_html = json_decode($html, true);
            $disciplinari[$id_campionato] = $disciplinari_html[$id_campionato];

            ob_start();
            include __DIR__ . "/../webroot/_content/bollettini.json";
            $html = ob_get_clean();
            $bollettini_html = json_decode($html, true);
            $bollettini[$id_campionato] = $bollettini_html[$id_campionato];

        endif;

        ob_start();
        include __DIR__ . "/../webroot/_content/squalificatiATempo.json";
        $html = ob_get_clean();
        $squalificati_a_tempo = json_decode($html, true);

        include __DIR__ . "/../views/apis/homeView/filterCampionatoGirone.ctp";

        exit();
    }


    // prenotazione campi --------------------------------------------------------


    public function viewBookingCampi()
    {
        $api = new Api();
        //        $api->isApiKey();

        if (!isset($_GET['prenotazione'])) {
            exit();
        }

        ob_start();

        $prenotazione = $_GET['prenotazione'];

        $info_prenotazione = $api->infoPrenotazione($prenotazione);

        if (count($info_prenotazione) == 0) {
            exit();
        }

        include __DIR__ . "/../views/apis/bookingCampi/prenotazione.ctp";

        $html = ob_get_clean();

        echo $html;
        exit();
    }


    public function editImportCampiBooking()
    {
        $json = file_get_contents('php://input');
        if ($json == "") {
        } else {
            $_post = json_decode($json, true);
            //            print_r($_post);
            if (isset($_post['edit_page_prenotazione'])) {
                foreach ($_post['prezzo'] as $id => $importo) {
                    $query = "UPDATE `CampiBooking` SET `Importo` = '{$importo}' WHERE `CampiBooking`.`id` = '{$id}'";
                    $this->my_query($query);
                }
                foreach ($_post['pagato'] as $id => $pagato) {
                    $query = "UPDATE `CampiBooking` SET `Pagato` = '{$pagato}' WHERE `CampiBooking`.`id` = '{$id}'";
                    $this->my_query($query);
                }
                foreach ($_post['elimina'] as $id => $elimina) {
                    if ($elimina == true) {
                        //                    $query = "UPDATE `CampiBooking` SET `Pagato` = '{$pagato}' WHERE `CampiBooking`.`id` = '{$id}'";
                        $query = "DELETE FROM CampiBooking WHERE `CampiBooking`.`id` = '{$id}'";
                        $this->my_query($query);
                    }
                }
            }
        }
        exit();
    }


    public function admin_togglePagato()
    {
        $json = file_get_contents('php://input');
        if ($json == "") {
        } else {
            $_post = json_decode($json, true);
            $id = $_post['id'];
            $pagato = $_post['pagato'];
            //            print_r($_post);
            $query = "UPDATE `CampiBooking` SET `Pagato` = '{$pagato}' WHERE `id` = '{$id}'";
            $this->my_query($query);
        }
        exit();
    }


    // elenco città ------------------------------------


    public function cities()
    {

        $api = new Api();
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($api->cities($data));
        exit();
    }


    // generazione codice fiscale ------------------------------------


    public function generateCF()
    {

        $api = new Api();
        $data = json_decode(file_get_contents('php://input'), true);
        //        echo file_get_contents('php://input');
        echo json_encode($api->generateCF($data));
        exit();
    }


    // ricerca AtletiBAS

    public function atletaBAS()
    {
        $api = new Api();
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($api->atletaBAS($data));
        exit();
    }


    // ------------------------------------------------------------------
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // for test - - - - - - - -- - - - - - - - - - - - - - - - - - - - - - - - -
    public function renameJSON()
    {

        //        header('Content-Type: application/json');   

        $array = [
            "marcatori",
            "calendario",
            "classifica",
            "menu_tendina",
            "disciplinari",
            "sanzioni",
            "bollettini"
        ];

        foreach ($array as $value) {

            ob_start();
            print file_get_contents("https://www.midlandsport.it/_content/{$value}.json");

            $html = ob_get_clean();

            file_put_contents("_content/{$value}.json", $html);
        }
        exit();
    }


    // for test - - - - - - - -- - - - - - - - - - - - - - - - - - - - - - - - -
    public function renameJSONFrontend()
    {
        ob_start();
        print file_get_contents("https://www.midlandsport.it/_content/menu_tendina.json");

        $html = ob_get_clean();

        $campionato = json_decode($html, true);

        foreach ($campionato['Campionato'] as $key_campionato => $girone) {

            foreach ($girone['GironeCampionato'] as $key_girone => $nome_girone) {
                $file = "disciplinare_{$key_campionato}_{$key_girone}.json";

                print $file . "<br>";

                //C:\xampp\htdocs\midland\app\webroot\files\json_frontend\disciplinari\disciplinare_1000_2468.json

                ob_start();

                print file_get_contents("https://www.midlandsport.it/files/json_frontend/disciplinari/{$file}");

                $html = ob_get_clean();

                file_put_contents("files/json_frontend/disciplinari{$file}", $html);
            }
        }

        exit();
    }


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


    public function forCronCalendario()
    {
        $api = new Api();

        $api->isApiKey();

        $anno_sportivo = $api->annoSportivo();

        $current = $anno_sportivo['current']['year'];

        $cal = $api->calendario($current);
        $bol = $api->bollettini($cal['CalendarioHome'], $current);
        $mar = $api->marcatori($cal['CalendarioHome'], $current);
        $cla = $api->classifica($cal['CalendarioHome']);
        $dis = $api->disciplinari($cal['CalendarioHome']);
        $sqt = $api->squalificatiATempo($current);

        // print_r(['Cal' => $cal, 'Mar' => $mar]);
        exit();
    }


    public function notesNew($calendario, $casa, $trasferta) // stampa notegara nel calendario in maniera unita (per adesso inutilizzato)
    {
        //D:\PROGRAMMI\UwAmp\www\midland\app\controllers\prints_controller.php ->     function admin_notesnew()

        $squadre[] = $casa;
        $squadre[] = $trasferta;
        $urls = [];
        $cmd = "";

        foreach ($squadre as $partita) {

            $playLeage = "/?playLeague"; //GIUSEPPE 2022-09-13

            $filename = "tmp_note_" . $calendario . "_" . uniqid() . ".pdf";

            $cmd = "wget " . FULL_ABSOLUTE_URL . "/sections/getNotes/" . $calendario . "/" . $partita . $playLeage . " -O /var/www/vhosts/timmytag.it/midland2023.timmytag.it/midland2015cake2/app/webroot/files/pdf/" . $filename;

            $urls[] = APP . '/webroot/files/pdf/' . $filename;

            system($cmd);
        }

        $pdf = "note_gara_" . date("d_m_Y") . "_" . uniqid() . ".pdf";
        $str = implode(" ", $urls);
        $cmd = "pdftk $str cat output " . APP . '/webroot/files/pdf/' . $pdf;
        system($cmd);
        header("location: " . FULL_ABSOLUTE_URL . "/files/pdf/" . $pdf);
        exit;
    }


    public function deleteAll()
    {
        echo $dir . "<br>";
        print_r($_GET['dir']);
        print "<br>";
        echo "PRIMA:<br>";

        $dir = "_content";

        if (isset($_GET['dir'])) {
            $dir = $_GET['dir'];
        }

        $cerca = __DIR__ . "/../webroot/{$dir}/*";
        print "<br>Cerca in: {$cerca} ----<br>";

        print_r(glob(__DIR__ . "/../webroot/{$dir}/*"));

        //https://www.w3schools.com/php/func_array_map.asp

        array_map(
            function ($dir) {
                unlink($dir);
            },
            glob(__DIR__ . "/../webroot/{$dir}/*")
        );

        echo "-----------------------------------------------------<br>";
        echo "-----------------------------------------------------<br>";
        echo "DOPO:<br>";
        print_r(glob(__DIR__ . "/../webroot/{$dir}/*"));

        exit();
    }


    public function deleteCache()
    {

        $cont = __DIR__ . "/../tmp/cache/models/*";
        print_r(glob($cont));
        array_map(
            function ($dir) {
                unlink($dir);
            },
            glob($cont)
        );

        echo "-----------------------------------------------------<br>";
        echo "-----------------------------------------------------<br>";
        echo "DOPO:<br>";
        print_r(glob($cont));

        $cont = __DIR__ . "/../tmp/cache/persistent/*";
        print_r(glob($cont));
        array_map(
            function ($dir) {
                unlink($dir);
            },
            glob($cont)
        );

        echo "-----------------------------------------------------<br>";
        echo "-----------------------------------------------------<br>";
        echo "DOPO:<br>";
        print_r(glob($cont));

        exit();
    }


    // ---------------------------------------------------------------------------------
    // è una funzione che uso solo per inserire i prezzi dei campi nella prenotazione di campi booking (usarla una volta)
    public function associaCampiOrariCampiBooking()
    {


        $query = "SELECT * FROM CampiOrari";
        $CampiOrari = $this->select_sql($query);

        $query = "SELECT * FROM CampiBooking";
        $CampiBooking = $this->select_sql($query);

        $ordinaCampi = $this->ordinaCampi($CampiOrari);

        $this->editImportiCampiBookingCiclo($CampiBooking, $ordinaCampi);

        //        $this->write_file("_CampiOrari", $CampiOrari);
        //        $this->write_file("_CampiOrariOrdered", $ordinaCampi);
        //        $this->write_file("_CampiBooking", $CampiBooking);

        exit();
    }


    private function ordinaCampi($CampiOrari)
    {
        $res = [];
        foreach ($CampiOrari as $row) {
            $campo_id = $row['campo_id'];
            $Giorno = $row['Giorno'];
            $Ora = $row['Ora'];

            $res[$campo_id][$Giorno][$Ora] = $row;
        }
        return $res;
    }


    private function editImportiCampiBookingCiclo($CampiBooking, $ordinaCampi)
    {
        //        $date = date_create($value['Data']);
        //            $data = date_format($date, "d/m/Y");

        foreach ($CampiBooking as $row) {
            $id = $row['id'];
            $campo_id = $row['campo_id'];
            $Ora = $row['Ora'];
            $Prenotazione = $row['Prenotazione'] == "" ? "pren-" . rand(100, 999) . rand(100, 999) . rand(100, 999) . uniqid() : $row['Prenotazione'];

            $date = date_create($row['Data']);
            $dayOfWeek = date_format($date, "w");
            $dayOfWeek = $dayOfWeek == 0 ? "7" : $dayOfWeek;
            $dayOfWeekName = date_format($date, "l");

            if (!isset($ordinaCampi[$campo_id][$dayOfWeek][$Ora]['Importo'])) {
                continue;
            }

            $Importo = $ordinaCampi[$campo_id][$dayOfWeek][$Ora]['Importo'];

            echo "[{$campo_id}][{$dayOfWeek}][{$Ora}] --- ";
            echo "{$row['Data']} : {$dayOfWeek} : {$dayOfWeekName} ----> {$Importo} ---> {$Prenotazione}<br>";

            $query = "UPDATE `CampiBooking` SET `Importo` = '{$Importo}', `Prenotazione` = '{$Prenotazione}' WHERE `CampiBooking`.`id` = '{$id}'";

            //UPDATE `MidlandDev2016`.`CampiBooking` SET `Importo` = '190.00', `Prenotazione` = '6488aa01a286e90113135ww' WHERE `CampiBooking`.`id` = 9510

            $this->my_query($query);
        }

        echo "END!!!!!!!!";
    }


    public function clearCache()
    {
        $directories = array();

        $directories[] = "../webroot/_content";
        $directories[] = "../tmp/cache/models";
        $directories[] = "../tmp/cache/persistent";
        $directories[] = "../tmp/cache/views";

        foreach ($directories as $key => $directory) {
            $comand = sprintf("rm -fr '%s/%s'", __DIR__, $directory);
            exec($comand); // cancella directory con tutto il contenuto;

            mkdir($directory);

            print "<br> pulita directory: {$directory}";
        }

        exit();
    }


    public function testHelloGest()
    {

        $serverSendData = "https://hellogest.com/api/client/create";

        $user = '{
                    "name": "test uno",
                    "constitution_date": "2022-02-02",
                    "email": "aaa@gmail.com",
                    "phone": "32323232",
                    "legal_address": "via rossi 6",
                    "legal_city": 288,
                    "association_type": "BAS",
                    "affiliation_type": "SOLARE",
                    "disciplines": [570, 457],
                    "insurance_type": "BASFIA1",

                    "general_counsel": {
                        "birthday": "1957-12-12",
                        "firstname": "Rossi",
                        "lastname": "Mario",
                        "birthplace": 288,
                        "gender": "m"
                        }
                }
';

        //        echo $user;
        $userArray = json_decode($user, true);
        echo "<br>";
        print_r($userArray);
        $data_string = json_encode($userArray);
        echo "<br>";
        echo "<br>";

        $token = $this->auth();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $serverSendData);

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

        print_r($response);

        echo PHP_EOL;

        exit();
    }


    public function auth()
    {


        $serverURL = $this->getConnectBAS()['url'] . "/oauth/token";

        //        $serverURL = "https://hellogestuat.herokuapp.com/oauth/token";

        $cl = curl_init();

        curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($cl, CURLOPT_URL, $serverURL);

        curl_setopt($cl, CURLOPT_POST, true);
        curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cl, CURLOPT_POSTFIELDS, array(
            "grant_type" => "client_credentials",
            "client_id" => $this->getConnectBAS()['client_id'],
            "client_secret" => $this->getConnectBAS()['client_secret']

            //            "client_secret" => "8Tx57YyG9HRkpFAo4k2kVz0EfYQI0YHNy9quChFf"
        ));

        $auth_response = curl_exec($cl);

        if ($auth_response === false) {
            echo "Failed to authenticate\n";
            var_dump(curl_getinfo($cl));
            curl_close($cl);
            exit();
        }
        curl_close($cl);

        return json_decode($auth_response, true);

        exit();
    }


    public function getConnectBAS()
    {

        // $res['url'] = "https://www.hellogest.com";
        // $res['client_secret'] = "0gqViuxEraQxUDPwlWlrLhoftJEK4d0XvX7iMGGd";

        $res['url'] = "https://hellogestuat.herokuapp.com";
        $res['client_secret'] = "8Tx57YyG9HRkpFAo4k2kVz0EfYQI0YHNy9quChFf";

        $res['client_id'] = "4";

        return $res;
    }


    public function salvaBasDoc()
    {
        //controllo che siano presenti i documenti bas e li inserisco nel db alla relativa
        //squadra campionato


        $cont = __DIR__ . "/../webroot/files/BAS";

        $dirAll = scandir($cont);

        foreach ($dirAll as $dir) {
            if ($dir == "." || $dir == "..")
                continue;

            echo $dir . "<br>";
            $cont = __DIR__ . "/../webroot/files/BAS/{$dir}/*.pdf";

            $allFiles = glob($cont);

            $to_edit = [];

            if (count($allFiles) == 0)
                continue;

            foreach ($allFiles as $file) //elenco dei file
            {
                echo $file . "<br>";
                //                $s = ""
                $file = str_replace(__DIR__ . "/../webroot", ".", $file);
                echo $file . "<br>";

                list(, $name) = explode("_", $file);
                list($col,) = explode(".", $name);
                echo $name . "<br>";
                echo $col . "<br>";

                switch ($col) {
                    case "AFFILIATION":
                        $to_edit['MEMORANDUM_ARTICLES_ASSOCIATION'] = $file;
                        break;

                    case "MEMORANDUM":
                        $to_edit['AFFILIATION_REQUEST'] = $file;
                        break;

                    case "PRESIDENT":
                        $to_edit['PRESIDENT_ID'] = $file;
                        break;
                }


                echo "<br>";
            }

            echo "<br>";

            print_r($to_edit);
            $this->edit_sql("Squadre", $to_edit, "Squadra", $dir);
            echo '<br>';
            echo "<br>";
        }

        exit();

        array_map(function ($dir) {

            $cont = __DIR__ . "/../webroot/files/BAS/{$dir}/*.pdf";

            array_map(
                function ($dir2) {
                    echo $dir . "<br>";
                    print_r($dir2);
                    echo "<br>";
                },
                glob($cont)
            );
        }, $dirAll);

        exit;
    }


    public function associaCittaAtletiPresenti()
    {
        $api = new Api();

        $res = $api->associaCittaAtletiPresenti();

        include __DIR__ . "/../views/apis/associaCittaAtletiPresenti/associaCittaAtletiPresenti.ctp";

        exit();
    }


    public function associaCittaAtletiPresentiBAS()
    {
        $api = new Api();

        $res = $api->associaCittaAtletiPresentiBAS();

        echo json_encode($res);

        exit();
    }


    public function cittaNonAssociateAll()
    {
        $api = new Api();

        $res = $api->cittaNonAssociateAll();

        echo json_encode($res);

        exit();
    }


    public function associaCittaAtletiPresentiDB()
    {
        $json = file_get_contents('php://input');

        $post = json_decode($json, true);

        $api = new Api();

        $api->associaCittaAtletiPresentiDB($post);

        echo json_encode($post);

        exit();
    }


    public function salvaCittaBasNonAssociate()
    {
        $json = file_get_contents('php://input');

        $post = json_decode($json, true);

        $api = new Api();

        $res = $api->salvaCittaBasNonAssociate($post);

        echo json_encode($res);

        exit();
    }


    public function atletiBASMassivi()
    {

        $api = new Api();

        $res = [];

        $res = $api->atletiBASMassivi();

        $this->write_file("_atleti_annuario", $res);

        include __DIR__ . "/../views/apis/ateltiBASMassivi/ateltiBASMassivi.ctp";

        exit();
    }


    public function atletiBASMassiviTest()
    {
        include __DIR__ . "/../views/apis/ateltiBASMassivi/atletiBASMassiviTest.ctp";

        exit();
    }
    public function tesseraAtletaBAS()
    {


        $json = file_get_contents('php://input');
        $post = json_decode($json, true);



        // controllo se è nuovo o da rinnovare
        // echo $json;
        $atleta = $post['atleta'];
        $squadra = $post['squadra'];

        $api = new Api();

        // GIUSEPPE 2025-09-23 ---------------------------------------------------------
        // controllo che l'assicurazione abbia id 1 o 11
        $assic = $api->cercaAssicurazione($atleta, $squadra);

        if ($assic["invia"] == false) {
            $post['errore_assicurazione'] = true;
            echo json_encode($post);
            exit();
        }
        // -----------------------------------------------------------------------------


        $query = "SELECT * FROM AtletiBAS WHERE Atleta = '{$atleta}' AND Squadra = '{$squadra}'";
        $res = $this->key_select($this->select_sql($query), 'AnnoSportivo');


        if (count($res) > 1) {
            $this->renewAtletaBas($post, $res);
            exit();
        }

        if ($post['card_id'] == 0 && $post['subscriber_id'] > 0) {
            // $this->write_file("")
            $this->renewAtletaBas($post, $res);
            exit();
        }

        // - - - - - - - - - - - - - - - - - -


        $client_id = $post['client_id'];
        $serverSendData = $this->getConnectBAS()['url'] . "/api/client/{$client_id}/subscriber";



        $atleta_bas['birthday'] = $post['data_nascita'];
        $atleta_bas['firstname'] = $post['nome'];
        $atleta_bas['lastname'] = $post['cognome'];
        $atleta_bas['birthplace'] = $post['city'];
        $atleta_bas['gender'] = $post['sesso'];

        // $atleta_bas['insurance'] = "BASFIA1";
        $atleta_bas['insurance'] = $assic["insurance"]; // GIUSEPPE 2025-09-23 ---------------------------------------------------------
        $this->logBas($atleta_bas); // GIUSEPPE 2025-09-23 ---------------------------------------------------------


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

        $post['token'] = $token;
        $post['response'] = json_decode($response, true);
        $post['link'] = $serverSendData;
        $post['atleta_bas'] = $atleta_bas;

        $api->tesseraAtletaBAS($post);

        $json = json_encode($post);

        echo $json;
        exit();
    }



    public function renewAtletaBas($post, $res)
    {

        //ob_start();

        $api = new Api();
        $client_id = "";
        $subscriber_id = "";
        $atleta_bas = [];





        // GIUSEPPE 2025-09-23 ---------------------------------------------------------
        // controllo che l'assicurazione abbia id 1 o 11

        $atleta = $post['atleta'];
        $squadra = $post['squadra'];

        $assic = $api->cercaAssicurazione($atleta, $squadra);

        if ($assic["invia"] == false) {
            $post['errore_assicurazione'] = true;
            echo json_encode($post);
            exit();
        }
        // -----------------------------------------------------------------------------



        $anno = $api->annoSportivo()['current']['year'];

        $idAtletaBasRenew = $res[$anno]['id'];

        unset($res[$anno]); // mi serve sapere l'ultimo subscriber id

        $last_year = max(array_keys($res));



        //echo json_encode([$res, $post, $last_year, $idAtletaBasRenew]);

        $client_id = $post['client_id'];
        $subscriber_id = $res[$last_year]['subscriber_id'];

        if ($post['subscriber_id'] > 0 && $post['card_id'] == 0) { // se il subscriber_id > 0 e card_id == 0 bisogna ripetere con un rinnovo // bug di hellogest
            $subscriber_id = $post['subscriber_id'];
        }

        $atleta_bas['birthday'] = $post['data_nascita'];
        $atleta_bas['firstname'] = $post['nome']; //"Matteo";
        $atleta_bas['lastname'] = $post['cognome']; //"Rebechi";
        $atleta_bas['birthplace'] = $post['city']; //"2889";
        $atleta_bas['gender'] = $post['sesso']; //"m";
        // $atleta_bas['insurance'] = "BASFIA1";
        $atleta_bas['insurance'] = $assic["insurance"]; // GIUSEPPE 2025-09-23 ---------------------------------------------------------

        $this->logBas($atleta_bas); // GIUSEPPE 2025-09-23 ---------------------------------------------------------

        $serverSendData = $this->getConnectBAS()['url'] . "/api/client/{$client_id}/subscriber/{$subscriber_id}/renew";
        //$this->write_file('link', $serverSendData);

        $data_string = json_encode($atleta_bas);

        //$this->write_file("renew_bas/{$client_id}/{$subscriber_id}_", $atleta_bas);

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

        $this->write_file("renew_bas/{$client_id}/{$subscriber_id}", ["LINK" => $serverSendData, "RESPONSE" => $response]);

        $post['token'] = $token;
        $post['response'] = json_decode($response, true);
        $post['link'] = $serverSendData;
        $post['atleta_bas'] = $atleta_bas;

        $r = json_decode($response, true);

        if (isset($r['data']['subscriber_id'])) {

            $subscriber_id = $r['data']['subscriber_id'];
            $card_id = $r['data']['card_id'];

            $query = "UPDATE
                        `AtletiBAS`
                    SET
                        `subscriber_id` = '{$subscriber_id}',
                        `card_id` = '{$card_id}'
                    WHERE
                        id = '{$idAtletaBasRenew}' ";

            $this->my_query($query);
        }


        $json = json_encode($post);

        echo json_encode($post);

        //{"data":{"subscriber_id":368509,"card_id":"114604"}} --- response of renew

        //$html = ob_get_clean();

        //$risposta['html'] = $html;

        //echo json_encode($risposta);

        exit();
    }

    // GIUSEPPE 2025-09-23 ---------------------------------------------------------
    // public function logBas()
    private function logBas($atleta_bas)
    {

        // $json = file_get_contents('php://input');
        // $atleta_bas = json_decode($json, true);

        $dir = "../webroot/_content/log_bas";
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $file = date("Y-m-d");
        file_put_contents("{$dir}/{$file}", print_r($atleta_bas, true), FILE_APPEND | LOCK_EX);
    }
    // -----------------------------------------------------------------------------


    /*


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

      curl_setopt($ch, CURLOPT_HTTPHEADER,
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


     *      */


    // --------------------------------------------------------------------

    public function unoCento()
    {
        $count = 0;
        for ($i = 1; $i <= 100; $i++) {
            $num = sprintf("%s", $i);
            $len = strlen($num);
            //
            //            echo " " . $num[0] . "<br>";
            $count_sub = 0;
            for ($index = 0; $index < $len; $index++) {
                if ($num[$index] == "9") {
                    $count++;
                    $count_sub++;
                }
                //                echo $num[$index];
            }

            echo sprintf("%s - num volte %d<br>", $num, $count_sub);

            echo "<br>";
        }

        echo $count;
        exit();
    }
}
