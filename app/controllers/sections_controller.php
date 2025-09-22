<?php
function youtube_id_from_url($url)
{
    parse_str(parse_url($url, PHP_URL_QUERY) , $my_array_of_vars);
    return $my_array_of_vars['v'];
}
// this is shit, lasciate ogni speranza o voi che entrate
// Abandon all hope, you who enter here
class SectionsController extends AppController
{
    public function verifyDisponibilita()
    {
        $giorni = ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"];
        $g = $giorni[$_POST["data"]["Subscription"]["giorno"]];
        $o = str_replace(":", ".", $_POST["data"]["Subscription"]["ora"]);
        $c = $_POST["data"]["Subscription"]["campo"];
        $camp = $_POST["data"]["Subscription"]["campionato"];
        $girone = $_POST["data"]["Subscription"]["girone"];

        $res = mysql_query("SELECT COUNT(*) FROM SquadreCampionati WHERE GironeCampionato = $girone AND Campionato = $camp AND Campo = $c AND Ora = '$o' AND Giorno = '$g'");
        if (mysql_fetch_row($res) [0] >= 2)
        {
            echo json_encode(["result" => false]);
            exit;
        }
        echo json_encode(["result" => true]);
        exit;
    }

    public function oneSquadr()
    {

        if (empty($_POST["data"]["Subscription"]["nomesquadra2"]))
        {
            echo json_encode(["result" => true]);
            exit;
        }
        $squadr = $_POST["data"]["Subscription"]["nomesquadra2"];
        $camp = $_POST["data"]["Subscription"]["campionato"];
        $res = mysql_query("SELECT COUNT(*) FROM SquadreCampionati WHERE Squadra = $squadr AND Campionato = $camp");
        if (mysql_fetch_row($res) [0] >= 1)
        {
            echo json_encode(["result" => false]);
            exit;
        }
        echo json_encode(["result" => true]);
        exit;
    }

    var $name = "Sections";

    var $components = array(
        'Email',
        'Auth'
    );

    var $helpers = array(
        'Backend',
        'fpdf',
        'excel'
    );

    var $uses = array(

        'Campi',
        'Comunication',
        'CampiOrari',
        'CampiBooking',
        'Comunication',
        'AnniSportivi',
        'Ranking',
        'Yearbook',
        'Campionati',
        'Half',
        'ChampCategory',
        'Campicampionati',
        'Match',
        'SquadreCampionati',
        'Matchgoal',
        'Disciplinari',
        'FinalStage',
        'Squadre',
        'Athlete',
        'Page',
        'Block',
        'User',
        'Athlete',
        'Upload',
        'Teambook',
        'Event',
        'Type'
    );
    function next()
    {
        $this->layout = "content";

        $prossime_manifestazioni = $this
            ->Event
            ->find('all', array(

            'conditions' => array(
                'data_inizio > NOW()'
            ) ,
            'order' => 'Event.order ASC'
        ));

        $this->set('prossime_manifestazioni', $prossime_manifestazioni);

    }

    function getchampinfo()
    {

        $this->layout = "timmybox_web";

    }
    function nl()
    {

        $this->layout = "timmybox_web";
        $this->render('nl');
    }

    function alert()
    {

    }

    function hours($tipo, $campo, $campionato)
    {

        $this->layout = "timmybox_web";

        $campionato = $this
            ->Campionati
            ->findByCampionato($campionato);

        $giorni = ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato"];

        $res = mysql_query("SELECT * FROM SquadreCampionati WHERE Campo = $campo AND Campionato = " . $campionato["Campionati"]["Campionato"]);
        $not_disp = [];
        while ($row = mysql_fetch_assoc($res))
        {
            if (!isset($not_disp[strtolower(substr($row["Giorno"], 0, 4)) . "-" . $row["Ora"]])) $not_disp[strtolower(substr($row["Giorno"], 0, 4)) . "-" . $row["Ora"]] = 1;
            else $not_disp[strtolower(substr($row["Giorno"], 0, 4)) . "-" . $row["Ora"]]++;
        }
        $very_not_disp = [];
        foreach ($not_disp as $k => $n)
        {
            if ($n >= 2)
            {
                $very_not_disp[] = $k;
            }
        }

        $this->set('campo', $campo);
        $this->set('campionato', $campionato);
        $this->set("not_disp", $very_not_disp);

    }

    function getchamp($tipo, $sessotipo, $torneotipo, $categoria)
    {

        if (isset($_GET['json']))
        {

            if (!isset($_GET['voce']))
            {
                $this->autoRender = false;

                $type = $this
                    ->Type
                    ->findById($tipo);

                $content = json_decode($type['Type']['content'], true);

                unset($content[0]);

                print json_encode($content);
                exit;
            }
            else
            {

                $type = $this
                    ->Type
                    ->findById($tipo);

                $content = json_decode($type['Type']['content'], true);

                $titolo = $content[$_GET['voce']]['nome'];
                $testo = $content[$_GET['voce']]['testo'];
                $this->layout = "timmybox_web";

                $this->set('titolo', $titolo);
                $this->set('testo', $testo);

                $this->render('getchampinfo');
            }

        }

        $category = $this
            ->Type
            ->findById($tipo);
        /*
        print_r($category);
        exit;
        */
        $categories = array();

        $campionati = json_decode($category['Type']['matches'], true);

        $campi = array();
        $regolamento = "";

        foreach ($campionati as $campionato)
        {

            $champ = $this
                ->Campionati
                ->findByCampionato($campionato);

            $subscriptions = (array)unserialize($champ['Campionati']['subscriptions']);
            foreach ($subscriptions as $girone => $dati)
            {

                $caselle = $dati['caselle'];

                foreach ($dati['Campo'] as $x => $cmp)
                {

                    if ($x < $caselle)
                    {
                        $c = $this
                            ->Campi
                            ->findByCampo($cmp);

                        if (!empty($c['Campi']['Descrizione']))

                        $c['Campi']['Campionato'] = $champ['Campionati']['Campionato'];
                        $c['Campi']['subscriptions'] = (array)unserialize($champ['Campionati']['subscriptions']);
                        $c['Campi']['Half'] = $champ['Half'];
                        $campi[$cmp] = $c;

                    }
                }

            }
        }

        $this->set('tipo', $tipo);
        $this->set('sessotipo', $sessotipo);
        $this->set('torneotipo', $torneotipo);
        $this->set('categoria', $categoria);

        $this->set('regolamento', $regolamento);
        $this->set('campi', $campi);

    }

    //GIUSEPPE 2017-05-17 - - - - - - - - - - - - - - - -
    

    function eventChampionship($event, $type_event)
    {
        $sql = "SELECT COUNT(Campionato) as num" . ", iscrizioni " . "FROM `Campionati` " . "WHERE Evento = '$event' " . "AND EventoTipo = '$type_event' " . "AND AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)";

        $result = mysql_query($sql);

        $row = array();

        $row = mysql_fetch_assoc($result);

        return $row; //$event . " - " . $type_event . "<br>";
        
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - -
    

    function manifestazioni($id, $slug)
    {

        $this->layout = "content";
        $prossime_manifestazioni = $this
            ->Event
            ->find('all', array(

            'conditions' => array(
                'data_inizio > NOW()',
                'Event.id != ' => $id
            )
        ));

        $this->set('types', $this
            ->Type
            ->find('all', array(

            'conditions' => array(
                'Type.event_id' => $id
            )

        )));

        $this->set('prossime_manifestazioni', $prossime_manifestazioni);
        $this->set('categoria', $id);
        $this->set('manifestazione', $this
            ->Event
            ->findById($id));

    }






    function manifestazioni_tennis($id, $slug)
    {

        $this->layout = "content";

        $prossime_manifestazioni = $this
                ->Event
                ->find('all', array(
            'conditions' => array(
                'data_inizio > NOW()',
                'Event.id != ' => $id
            )
        ));

        $this->set('types', $this
                        ->Type
                        ->find('all', array(
                            'conditions' => array(
                                'Type.event_id' => $id
                            )
        )));


        $this->set('categoria', $id);
        $this->set('manifestazione', $this
                        ->Event
                        ->findById($id));






        $data_max = strtotime($this->data_max() . " 00:00:00");

        if (time() > $data_max)
        {



//            $campionati = $this->Campionati->find('all', array(
//                'conditions' => array(
//                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
//                    'Campionati.InCorso' => 'Si',
//                    'Campionati.group_id' => 1,
//                    'Campionati.scuola' => 0,
//                    'Campionati.sport' => 'CALCIO',
//                ),
//                'order' => array('Campionati.order ASC'),
//                    )
//            );


            $campionati_tennis = $this->Campionati->find('all', array(
                'conditions' => array(
                    /* //'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale */
                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.sport' => 'TENNIS',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );


//            $campionati_c5 = $this->Campionati->find('all', array(
//                'conditions' => array(
//                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
//                    'Campionati.InCorso' => 'Si',
//                    'Campionati.group_id' => 1,
//                    'Campionati.scuola' => '1',
//                    'Campionati.sport' => 'CALCIO',
//                ),
//                'order' => array('Campionati.order ASC'),
//                    )
//            );
        }
        else
        {

//            $campionati = $this->Campionati->find('all', array(
//                'conditions' => array(
//                    'Campionati.AnnoSportivo >= ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
//                    'Campionati.InCorso' => 'Si',
//                    'Campionati.group_id' => 1,
//                    'Campionati.scuola' => 0,
//                    'Campionati.sport' => 'CALCIO',
//                ),
//                'order' => array('Campionati.order ASC'),
//                    )
//            );


            $campionati_tennis = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo = ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.sport' => 'TENNIS',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );

//            $campionati_c5 = $this->Campionati->find('all', array(
//                'conditions' => array(
//                    'Campionati.AnnoSportivo = ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
//                    'Campionati.InCorso' => 'Si',
//                    'Campionati.group_id' => 1,
//                    'Campionati.scuola' => '1',
//                    'Campionati.sport' => 'CALCIO',
//                ),
//                'order' => array('Campionati.order ASC'),
//                    )
//            );
        }



        $this->set('campionati_tennis', $campionati_tennis);
    }










    

    function getPageUrl($pages = array())
    {

        $url = '';

        if (!is_array($pages))
        {
            $data = $this
                ->Page
                ->findById($pages);
            $pages = $data['Page'];
        }

        switch ($pages['type'])
        {

            case 'url':

                $url = '/' . ($pages['url'] != '') ? $pages['url'] : '#';

            break;

            case 'dinamic':

                //CHECK PREFIX
                $tmp = explode('_', $pages['action']);

                if (count($tmp) == 2)
                {

                    $prefix = '/' . $tmp[0] . '/';
                    $pages['action'] = $tmp[1];

                }
                else
                {

                    $prefix = '/';

                }

                //
                //$url = $prefix . strtolower($pages['controller']) . '/' . $pages['action'];
                $url = '/' . strtolower(Inflector::Slug($pages['alias'], '-'));

                if ($pages['params'] != '')
                {

                    $params = explode(',', $pages['params']);

                    foreach ($params as $param)
                    {

                        $url .= '/' . $param;

                    }

                }

            break;

            case 'static':

                $url = '/contenuti/' . $pages['id'] . '/' . strtolower(Inflector::Slug($pages['title'], '-'));

            break;

            default:

                $url = '/contenuti/' . $pages['id'] . '/' . strtolower(Inflector::Slug($pages['title'], '-'));

        }

        return ($url != '') ? $url : '#';

    }

    function productform()
    {

        $user = $this
            ->Session
            ->read("Login.data");
        $uniqid = $_GET["uniqid"];
        $data = json_decode(file_get_contents(__DIR__ . "/../payment_links/$uniqid") , true);

        $price = $data["price"];
        $price_virg = str_replace(".", ",", $data["price"]);
        $name = $data["name"];

        $redirect = payment_link($name, $price, $uniqid);

        $this->set("redirect", $redirect);
        $this->set("name", $name);
        $this->set("price", $price_virg);
        $this->set("uniqid", $uniqid);
        $this->set("user", $user);

        $this->layout = "content";

    }

    function productdati()
    {
        $this->autoRender = false;

        $uniqid = $_POST['uniqid'];
        $savepath = APP . '/webroot/files/json/product_' . $uniqid . '.json';
        file_put_contents($savepath, json_encode($_POST));
        $redirect = $_POST["redirect"];
        header("Location: $redirect");
        exit;

    }

    function productverify($uniqid, $state = 0)
    {

        $this->autoRender = false;

        /* fixed add */
        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi

        $force = 0;

//        if (isset($_REQUEST['mac']) && isset($_REQUEST['pan']) && ($_REQUEST['esito'] == "OK"))
        if ($_REQUEST['RESULT'] == 00) //GIUSEPPE 2022-01-11
        {
            $force = 1;

            $product = json_decode(file_get_contents(APP . '/webroot/files/json/product_' . $uniqid . '.json'), true);

            $this->set('product', $product);
            $this->set('force', $force);
            $this->set('cauzione', $cauzione);

            $ok = 1;


            $this->Email->to = array(
                "info@midlandeuropa.com",
                "timmytag@gmail.com"
            );

//            $this->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';

            /* fixed edit */
            $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';

            $this->Email->subject = 'Notifica acquisto prodotto';
            $this->Email->template = 'product';
            $this->Email->send();



            /* email per acquirente */
            $this->Email->to = array($product["email"]);
//          $this->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';

            /* fixed edit */
            $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';

            $this->Email->subject = 'Notifica acquisto prodotto';
            $this->Email->template = 'product';
            $this->Email->send();
            //$force = "1";
        }

        header("Location: /sections/productconfirm/" . $uniqid . "/" . $force);
    }






    function productconfirm($uniqid, $force)
    {

        //result: CAPTURED

        $product = json_decode(file_get_contents(APP . '/webroot/files/json/product_' . $uniqid . '.json'), true);

        $ok = 0;

        if ($force == 1)
        {

            $ok = 1;
        }
        else
        {

            $ok = 0;
        }
        $this->set('product', $product);
        $this->set('ok', $ok);
        $this->set('force', $force);
        $this->layout = "content";

        $this->render('productconfirm');

    }



    

    function login()
    {

        $this->autoRender = false;

        $login_error = 1;

        file_put_contents("login.txt", print_r($this->data, true));


        if (is_array($this->data) && isset($this->data['Login']) && !empty($this->data['Login']['password']))
        {

            $username = $this->data['Login']['username'];
            $password = $this->data['Login']['password'];

            $auth_password = $this
                    ->Auth
                    ->password($password);

            if (!isset($this->User))
                $this->loadModel('User');
            if (!isset($this->Athlete))
                $this->loadModel('Athlete');

            if (isset($this->data['Login']['type_login']) && $this->data['Login']['type_login'] != '')
            {

                switch ($this->data['Login']['type_login'])
                {

                    /* case 'athlete':
                      $is_atleta = $this
                      ->Athlete
                      ->find('first', array(
                      'conditions' => array(
                      'Athlete.email' => $username,
                      'Athlete.password' => $auth_password,
                      )
                      ));
                      break; */
                    
                    //GIUSEPPE 2019-01-09
                    case 'athlete':
                        $is_atleta = $this
                                ->Athlete
                                ->find('first', array(
                            'conditions' => array(
                                'Athlete.email' => $username,
                                'Athlete.password' => $auth_password,
                                'Athlete.Arbitro' => 'No',
                            )
                        ));
                        break;

                    /* case 'arb':
                      $is_arbitro = $this
                      ->Athlete
                      ->find('first', array(
                      'conditions' => array(
                      'Athlete.email' => $username,
                      'Athlete.password' => $auth_password,
                      )
                      )); */

                    //GIUSEPPE 2019-01-09
                    case 'arb':
                        $is_arbitro = $this
                                ->Athlete
                                ->find('first', array(
                            'conditions' => array(
                                'Athlete.email' => $username,
                                'Athlete.password' => $auth_password,
                                'Athlete.Arbitro' => 'Si',
                            )
                        ));
                        break;
                    //-------------------

                    case 'imp':
                        $is_impianto = $this
                                ->User
                                ->find('first', array(
                            'conditions' => array(
                                'User.username' => $username,
                                'User.password' => $auth_password,
                            )
                        ));
                        break;
                }
            }
            else
            {

                $is_user = $this
                        ->User
                        ->find('first', array(
                    'conditions' => array(
                        'User.username' => $username,
                        'User.password' => $auth_password
                    )
                ));

                $is_atleta = $this
                        ->Athlete
                        ->find('first', array(
                    'conditions' => array(
                        'Athlete.email' => $username,
                        'Athlete.password' => $auth_password,
                        'Athlete.Arbitro' => 'No',
                    )
                ));

                $is_arbitro = $this
                        ->Athlete
                        ->find('first', array(
                    'conditions' => array(
                        'Athlete.email' => $username,
                        'Athlete.password' => $auth_password,
                        'Athlete.Arbitro' => 'Si',
                    )
                ));
            }

            if (isset($is_user) && !empty($is_user))
            {

                $data['id'] = $is_user['User']['id'];
                $data['nome'] = $is_user['User']['nome'];
                $data['cognome'] = $is_user['User']['cognome'];
                $data['data_nascita'] = $is_user['User']['data_nascita'];
                $data['email'] = $data['username'] = $is_user['User']['username'];
                $data['is_atleta'] = 0;
                $data['is_user'] = 1;
                $data['is_arbitro'] = 0;
                $data['is_impianto'] = 0;
                $this
                        ->Session
                        ->write('Login.data', $data);

                $login_error = 0;
            }
            else if (isset($is_atleta) && !empty($is_atleta))
            {

                $data['id'] = $is_atleta['Athlete']['Atleta'];
                $this
                        ->Athlete
                        ->query('UPDATE Atleti SET password = \'' . $auth_password . '\' WHERE Atleta = ' . $is_atleta['Athlete']['Atleta']);
                $data['nome'] = $is_atleta['Athlete']['Nome'];
                $data['cognome'] = $is_atleta['Athlete']['Cognome'];
                $data['data_nascita'] = $is_atleta['Athlete']['DataNascita'];
                $data['email'] = $data['username'] = $is_atleta['Athlete']['Email'];
                $data['is_atleta'] = 1;
                $data['is_user'] = 0;
                $data['is_arbitro'] = 0;
                $data['is_impianto'] = 0;
                $this
                        ->Session
                        ->write('Login.data', $data);

                $login_error = 0;
            }
            else if (isset($is_arbitro) && !empty($is_arbitro))
            {

                $data['id'] = $is_arbitro['Athlete']['Atleta'];
                $data['nome'] = $is_arbitro['Athlete']['Nome'];
                $data['cognome'] = $is_arbitro['Athlete']['Cognome'];
                $data['data_nascita'] = $is_arbitro['Athlete']['DataNascita'];
                $data['email'] = $data['username'] = $is_arbitro['Athlete']['Email'];
                $data['is_atleta'] = 0;
                $data['is_user'] = 0;
                $data['is_arbitro'] = 1;
                $data['is_impianto'] = 0;
                $this
                        ->Session
                        ->write('Login.data', $data);

                $login_error = 0;
            }
            else if (isset($is_impianto) && !empty($is_impianto))
            {

                $data['id'] = $is_impianto['User']['id'];
                $data['nome'] = $is_impianto['User']['nome'];
                $data['cognome'] = $is_impianto['User']['cognome'];
                $data['data_nascita'] = $is_impianto['User']['data_nascita'];
                $data['email'] = $data['username'] = $is_impianto['User']['username'];
                $data['campo_id'] = $is_impianto['User']['campo_id'];
                $data['is_atleta'] = 0;
                $data['is_user'] = 0;
                $data['is_arbitro'] = 0;
                $data['is_impianto'] = 1;
                $this
                        ->Session
                        ->write('Login.data', $data);

                $login_error = 0;
            }
            else
            {

                $login_error = 1;
                //$this->set('login_error',1);
                //$this->Session->setFlash('Impossibile loggarsi, username o password errati.');
            }
        }

        print json_encode(array(
            'login_error' => $login_error,
            'redirect' => '/area/riservata'
        ));
    }

    

    function home()
    {

        $page = $this->Block->findById("1141");
        $url = $page['Block']['content'];

        $video_id = rtrim(youtube_id_from_url($url), "_");

        $this->set('video_id', strip_tags($video_id));

        /* PLAYLIST youtube CHANNEL ---------------- */
        $page_playlist = $this->Block->findById("1706");
        $url_playlist = $page_playlist['Block']['content'];
        $playlist_id = trim(strip_tags(html_entity_decode(str_replace(["\n", "\r"], '', $url_playlist))));
        $this->set('playlist_id', $playlist_id);



        $slides = $this->Upload->find('all', array(
            'conditions' => array('Upload.tag' => 'SLIDE', 'Upload.category' => 0),
            'order' => array('Upload.order ASC', 'published' => 'DESC')
                )
        );

        $slides_c5 = $this->Upload->find('all', array(
            'conditions' => array('Upload.tag' => 'SLIDE', 'Upload.category' => 1),
            'order' => array('Upload.order ASC', 'published' => 'DESC')
                )
        );

        /* // GIUSEPPE 23/09/2016 */
        $slides_tennis = $this->Upload->find('all', array(
            'conditions' => array('Upload.tag' => 'SLIDE', 'Upload.category' => 2),
            'order' => array('Upload.order ASC', 'published' => 'DESC')
                )
        );
        /* // ----------------------------------------- */

        $this->set('slides', $slides);
        $this->set('slides_c5', $slides_c5);

        /* // GIUSEPPE 23/09/2016 */
        $this->set('slides_tennis', $slides_tennis);
        /* // ----------------------------------------- */

        $compleanni = $this->Athlete->find('all', array(
            'conditions' => array(
                'DAYOFMONTH(Athlete.DataNascita) = DAYOFMONTH(NOW())',
                'MONTH(Athlete.DataNascita) = MONTH(NOW())'
            ),
            'order' => array('Athlete.Nome')
        ));


        $complex = array();

        foreach ($compleanni as $compleanno)
        {

            $compl['nome'] = $compleanno['Athlete']['Nome'] . " " . $compleanno['Athlete']['Cognome'];

            $oggi = strtotime(date("Y-m-d H:i:s"));
            $ieri = strtotime($compleanno['Athlete']['DataNascita'] . " 00:00:01");

            $anni = intval(($oggi - $ieri) / 31556926);

            $compl['anni'] = $anni;
            if ($anni > 10)
                $complex[] = $compl;
        }

        $this->set('compleanni', $complex);

        /* $data_max = strtotime("2015-09-19 00:00:00"); */

        $data_max = strtotime($this->data_max() . " 00:00:00");

        if (time() > $data_max)
        {



            /*
              $campionati = $this->Campionati->find('all', array(
              'conditions' => array(
              'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
              'Campionati.InCorso' => 'Si',
              'Campionati.group_id' => 1,
              'Campionati.scuola' => 0,
              'Campionati.sport' => 'CALCIO',
              ),
              'order' => array('Campionati.order ASC'),
              )
              );
             */
            //GIUSEPPE 2020-01
            $campionati = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.scuola' => 0,
                    'Campionati.PlayLeague' => 0, //GIUSEPPE 2022-09-13 
                    'OR' => array(array('Campionati.sport' => 'CALCIO'), array('Campionati.sport' => 'BASKET')),
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );
            //---------------

            $campionati_tennis = $this->Campionati->find('all', array(
                'conditions' => array(
                    /* //'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale */
                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.sport' => 'TENNIS',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );


            $campionati_c5 = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.scuola' => '1',
                    'Campionati.sport' => 'CALCIO',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );
        }
        else
        {

            /* $campionati = $this->Campionati->find('all', array(
              'conditions' => array(
              'Campionati.AnnoSportivo >= ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
              'Campionati.InCorso' => 'Si',
              'Campionati.group_id' => 1,
              'Campionati.scuola' => 0,
              'Campionati.sport' => 'CALCIO',
              ),
              'order' => array('Campionati.order ASC'),
              )
              ); */

            //GIUSEPPE 2020-01
            $campionati = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo >= ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.scuola' => 0,
                    'OR' => array(array('Campionati.sport' => 'CALCIO'), array('Campionati.sport' => 'BASKET')),
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );
            //---------------



            $campionati_tennis = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo = ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.sport' => 'TENNIS',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );

            $campionati_c5 = $this->Campionati->find('all', array(
                'conditions' => array(
                    'Campionati.AnnoSportivo = ((SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)-1)',
                    'Campionati.InCorso' => 'Si',
                    'Campionati.group_id' => 1,
                    'Campionati.scuola' => '1',
                    'Campionati.sport' => 'CALCIO',
                ),
                'order' => array('Campionati.order ASC'),
                    )
            );
        }



        $this->set('campionati_tennis', $campionati_tennis);



        //    file_put_contents("campionati_tennis.txt", print_r($campionati_tennis, true));

        /*  //-------------------------------------------------------------------------- */

        $this->set('campionati_c5', $campionati_c5);

        $this->set('campionati', $campionati);
    }



//GIUSEPPE 2019-03-15 ----------------------------------

    function events_tennis($filter)
    {

        $this->layout = "content";

        $prossime_manifestazioni = $this
                ->Event
                ->find('all', array(
            'conditions' => array(
                $filter
            ),
            'order' => 'Event.order ASC'
        ));

        return $prossime_manifestazioni;
    }





//------------------------------------------------------





    function data_max()
    {
        // Bypassa la lettura della data da file per test. Massimo 13/10/17
        // return "2017-09-23";
        
        $filename = APP . '/webroot/files/data_max/data_max.json';
        $handle = fopen($filename, "r");
        $content = fread($handle, filesize($filename));
        fclose($handle);

        /* if (filesize($filename) == 0)
        {
            $content = "2017-09-23";
        } */

        return $content;

    }


    //GIUSEPPE 2017-04-06 - tabelle per visualizzazione squadre - - - - - - - - - - -
    function read_all_teams($tipo, $sessoTipo, $anno)
    {
        $squadre = array();

        //$sql = "SELECT DISTINCT Squadre.Squadra, Squadre.Denominazione , Squadre.group_id, Squadre.Storia, Squadre.SquadraServizio, Squadre.id_sport, Squadre.sport
        $sql = "SELECT DISTINCT Squadre.Squadra, Squadre.Denominazione

        FROM Squadre

        INNER JOIN SquadreCampionati

        ON Squadre.Squadra = SquadreCampionati.Squadra

        INNER JOIN Campionati

        ON Campionati.Campionato = SquadreCampionati.Campionato

        WHERE

        Campionati.Tipo = '" . $tipo . "'

        AND

        Campionati.SessoTipo = '" . $sessoTipo . "'

        AND

        Campionati.group_id = 1

        AND

        Squadre.SquadraServizio = 0

        AND

        Campionati.AnnoSportivo LIKE '%" . $anno . "%'

        ORDER BY

        Squadre.Denominazione ASC";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $squadre[]['Squadre'] = $row;
            }
        }

        foreach ($squadre as $i => $squadra)
        {
            $squadre[$i]['Info']['Campionati'] = $this->count_campionati($squadra['Squadre']['Squadra']);

            $squadre[$i]['Info']['Stagioni'] = $this->count_stagioni($squadra['Squadre']['Squadra']);

            $files = $this->read_files($squadra['Squadre']['Squadra']);

            $squadre[$i]['Info']['Logo'] = $files['logo'];

            $squadre[$i]['Info']['Sponsor'] = $files['sponsor'];
        }

        return $squadre;
    }

    function count_stagioni($id_squadra)
    {
        $stagioni = 0;

        $sql = "SELECT COUNT(DISTINCT(Campionati.AnnoSportivo)) AS num_stagioni FROM SquadreCampionati
        INNER JOIN Campionati
        ON SquadreCampionati.Campionato = Campionati.Campionato
        WHERE SquadreCampionati.Squadra = '$id_squadra'
        ORDER BY `Campionati`.`AnnoSportivo`  DESC";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $stagioni = $row["num_stagioni"];
            }
        }

        return $stagioni;
    }

    function count_campionati($id_squadra)
    {
        $campionati = 0;

        $sql = "SELECT COUNT(SquadraCampionato)AS num_campionati FROM SquadreCampionati WHERE Squadra = '$id_squadra'";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $campionati = $row["num_campionati"];
            }
        }

        return $campionati;
    }

    function read_files($id_squadra)
    {
        $record_files = array(
            'logo' => array() ,
            'sponsor' => array()
        );

        $sql = "SELECT path, tag FROM files WHERE squadra_id = '$id_squadra' AND (tag = 'Logo' OR tag = 'Sponsor')";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                //logo
                if ($row['tag'] == 'Logo' && count($record_files['logo']) == 0)
                {
                    $record_files['logo'] = $row['path'];
                } //sponsor
                elseif ($row['tag'] == 'Sponsor' && count($record_files['sponsor']) == 0)
                {
                    $record_files['sponsor'] = $row['path'];
                }
            }
        }

        return $record_files;
    }

    function sort_squadre($squadre)
    {
        $num_squadre = count($squadre);

        while (true)
        {
            $scambio = false;

            for ($i = 0;$i < ($num_squadre - 1);$i++)
            {

                $sq_1 = trim($squadre[$i]['Squadre']['Denominazione']);

                $sq_2 = trim($squadre[$i + 1]['Squadre']['Denominazione']);

                $compare = strcmp($sq_1, $sq_2);

                if ($compare > 0)
                {

                    $scambio = true;

                    $temp_1 = $squadre[$i];

                    $temp_2 = $squadre[$i + 1];

                    $squadre[$i] = $temp_2;

                    $squadre[$i + 1] = $temp_1;
                }
            }

            if (!$scambio)
            {
                break;
            }
        }

        return $squadre;
    }

    //GIUSEPPE 2024-07-06 -----------------------------------------------------------
    
    function getSquadre() //GIUSEPPE 2024-07-06
    {
        $this->layout = "content";
        
        $url = $_GET['url'];
        
        list($sessoTipoGet,$tipo,$sessoTipo) = explode("/", $url);

        
        $final = $this->squadreHome($tipo,$sessoTipo);
        
        $alfabeto =  $this->inizialiSquadre($final);
        
        $this->set('tipoSportSesso',['tipo'=>$tipo,'sesso'=>$sessoTipo]);
        $this->set('final',$final);
        $this->set('ordineLettura', array_keys($final));
        $this->set('alfabeto',$alfabeto);
        
    }
    
    private function squadreHome($tipo,$sessoTipo) { //GIUSEPPE 2024-07-06

        $query = "    SELECT
                            TRIM(Squadre.Denominazione) as Denominazione,
                            SquadreCampionati.Campionato,
                            SquadreCampionati.Squadra,
                            Campionati.AnnoSportivo,
                            Campionati.Nome
                        FROM
                            `SquadreCampionati`
                        INNER JOIN Campionati ON SquadreCampionati.Campionato = Campionati.Campionato
                        INNER JOIN Squadre ON SquadreCampionati.Squadra = Squadre.Squadra
                        WHERE Campionati.sport = 'CALCIO' AND Campionati.Tipo = '{$tipo}' AND  Campionati.SessoTipo = '{$sessoTipo}' 
                        ORDER BY TRIM(Squadre.Denominazione) ASC";  
        
        $squadreCampionati = $this->select_sql($query);
  
//        $query = "SELECT
//                        *
//                    FROM
//                        `Campionati`";
//        $campionati = $this->key_select($this->select_sql($query), "Campionato");
//       
//        $query = "SELECT
//                        *
//                    FROM
//                        `Squadre`";
//        $squadre = $this->key_select($this->select_sql($query), "Squadra");
        
        
        $query = "  SELECT
                        *
                    FROM
                        `files`
                    WHERE
                        squadra_id <> 0 AND tag = 'Logo'";
        $logo_squadra =  $this->key_select($this->select_sql($query), "squadra_id");
        $componentiSquadra = 0;
 
        $final = [];
      
        foreach ($squadreCampionati as $value) {

            $campionato = $value['Campionato'];
            $squadra = $value['Squadra'];
            $componentiSquadra = 0;
            $sesso_tipo = "";
            $logo = "";
//            $anno_sportivo = $campionati[$campionato]['AnnoSportivo'];
            $anno_sportivo = $value['AnnoSportivo'];

            
            if(isset($logo_squadra[$squadra]) )
            {
               $logo =  $logo_squadra[$squadra]['path']; 
            }
              
            
            if ($tipo == 0)
                $componentiSquadra = 5;

            if ($tipo == 1)
                $componentiSquadra = 7;

            if ($tipo == 2)
                $componentiSquadra = 11;

            

            if ($sessoTipo == 0)
                $sesso_tipo = "M";
            
            if ($sessoTipo == 1)
                $sesso_tipo = "F";

            
            
            if ($componentiSquadra == 0)
                continue;

            if ($sesso_tipo == "")
                continue;

//            $final[$squadra]['NomeSquadra'] = trim($squadre[$squadra]['Denominazione']);
            $final[$squadra]['NomeSquadra'] = trim($value['Denominazione']);
            $final[$squadra]['Logo'] = $logo;
            $final[$squadra]['AnnoSportivo'][$anno_sportivo] = $anno_sportivo;
            $final[$squadra]['Stagioni'] = count($final[$squadra]['AnnoSportivo']);
//            $final[$squadra]['Campionati'][$componentiSquadra][$sesso_tipo][$campionato] = $campionati[$campionato]['Nome'];
            $final[$squadra]['Campionati'][$componentiSquadra][$sesso_tipo][$campionato] = $value['Nome'];

            $final[$squadra]['Manifestazioni'] = 0;
            foreach ($final[$squadra]['Campionati']as $t) {
                foreach ($t as $s_t) {
                    $final[$squadra]['Manifestazioni'] += count($s_t);
                }
            }
        }

        
        return $final;
    }
    
    
    private function inizialiSquadre(&$final) { //GIUSEPPE 2024-07-06
        $res = [];
        $res['first'] = "Z";
        foreach ($final as $value)
        {
            $iniziale = strtoupper($value['NomeSquadra'][0]);
            if(!is_numeric($iniziale))
            {
                $res['elenco'][$iniziale] = $iniziale;
                
                if($iniziale <= $res['first'] && $iniziale >= "A")
                {
                    $res['first'] = $iniziale;
                }
                
            }
            else{
                $res['elenco']["0 - 9"] = "0 - 9";
            }
            
        }
        
        return $res;
    }
    
    //--------------------------------------------------------------------------------
            
    function _getSquadre($tipo, $sessoTipo = 0, $anno = null) //GIUSEPPE 2017-04-06 riscritta la funzione getSquadre()
    
    {

        $this->layout = "content";

        if ($anno == null)
        {

            $anni = $this
                ->AnniSportivi
                ->find('list', array(
                'order' => 'AnniSportivi.AnnoSportivo DESC',
                'limit' => 1
            ));
            $anno = $anni[key($anni) + 1];
            $anno_string = $anno;
            $this->set('anno_s', $anno);
        }
        elseif ($anno == 'all')
        {

            $anno = '';
            $anno_string = '';
        }
        else
        {

            $anno_string = $anno;
            $this->set('anno_s', $anno);
        }

        $this->set('years', $this
            ->AnniSportivi
            ->find('list', array(
            'order' => 'AnniSportivi.AnnoSportivo DESC'
        )));

        $alfabeto = array();

        $squadre_database = $this->read_all_teams($tipo, $sessoTipo, $anno);

        $squadre = $this->sort_squadre($squadre_database);

        //echo json_encode($squadre);
        //exit;
        foreach ($squadre as $squadra)
        {

            $nome = $squadra['Squadre']['Denominazione'];

            $start = substr(trim($nome) , 0, 1);

            $end = substr(trim($nome) , 1, 2);

            // - - controllo i valori ascii  - - - - -
            if (ord($start) >= 65 && ord($start) <= 90 || ord($start) >= 97 && ord($start) <= 122)
            {
                $chiave = Inflector::Slug($start);
            }
            else
            {
                $chiave = null;
            }

            $alfabeto[strtoupper($chiave) ][] = $squadra;

            $json_data['timestamp'] = time();

            $json_data['data'] = $alfabeto;

            //  file_put_contents(APP . '/webroot/files/json_frontend/get_squadre_' . $tipo . '_' . $sessoTipo . '_' . $anno_string . '.json', json_encode($json_data));
            
        }

        $this->set('alfabeto', $alfabeto);
    }

    function getSquadreAjax($tipo, $sessoTipo)
    {

        $this->layout = "ajax";

        $squadre = $this
            ->Squadre
            ->find('all', array(
            'order' => 'Squadre.Denominazione ASC',
            'limit' => 15,
            'conditions' => array(
                /*
                'Squadre.Squadra IN
                
                (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.Campionato IN
                
                (SELECT Campionati.Campionato FROM Campionati WHERE Tipo = ' . $tipo .' AND SessoTipo = ' . $sessoTipo . ')
                
                )',
                */
                'Squadre.Denominazione LIKE' => ('%' . $_GET['term'] . '%') ,

            )
        ));

        $ret = array();

        foreach ($squadre as $squadra)
        {

            $tmp['id'] = $squadra['Squadre']['Squadra'];
            $tmp['label'] = $squadra['Squadre']['Denominazione'];

            $ret[] = $tmp;

        }

        $this->set('result', json_encode($ret));
        $this->render('/backend/ajaxResult');

    }

    function getSlug($id)
    {

        $this->layout = "ajax";

        $data = $this
            ->Squadre
            ->findBySquadra($id);
        $slug = strtolower(Inflector::Slug($data['Squadre']['Denominazione'], '-'));

        $this->set('result', json_encode(array(
            'slug' => $slug
        )));
        $this->render('/backend/ajaxResult');

    }

    function getSlugMod($id)
    {

        $this->layout = "ajax";

        $data = $this
            ->Squadre
            ->findBySquadra($id);
        $squadraC = $this
            ->Teambook
            ->find('first', array(
            'conditions' => array(
                'Teambook.Squadra' => $id,
            ) ,
            'order' => 'Teambook.AnnoSportivo DESC',
            'limit' => 1,
        ));

        $slug = strtolower(Inflector::Slug($data['Squadre']['Denominazione'], '-'));

        $this->set('result', json_encode(array(
            'slug' => $slug,
            'anno' => (isset($squadraC['Teambook']['AnnoSportivo']) ? $squadraC['Teambook']['AnnoSportivo'] : '')
        )));
        $this->render('/backend/ajaxResult');

    }

    function getShopCategories()
    {

        // create cURL resource
        $ch = curl_init();

        $host = $this->params['host'];

        // set options
        curl_setopt($ch, CURLOPT_URL, $host . "/api/categories?display=full");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, ('XFI9HUVUDJSQW6WHKMJKV9J449UG8DZK'));

        // grab URL and pass it to the browser
        $ret = curl_exec($ch);

        //debug($ret);
        //print $ret;
        // close cURL resource, and free up system resources
        curl_close($ch);

        // import XML class
        App::import('Xml');

        // your XML file's location
        //$file = "my_xml_file.xml";
        // now parse it
        $parsed_xml = new XML($ret);
        $parsed_xml = Set::reverse($parsed_xml); // this is what i call magic
        //debug($parsed_xml);
        $categories = array();
        $except = array(
            'Home page'
        ); //Mettere eccezioni.
        $limit = 9;
        // see the returned array
        $jk = 0;

        foreach ($parsed_xml['Prestashop']['Categories']['Category'] as $k => $cat)
        {

            if (isset($cat['id_parent']['value']) && $cat['id_parent']['value'] == 1)
            {
                if (!isset($cat['Associations']['Products']['Product'])) $cat['Associations']['Products']['Product'] = 0;

                if (!in_array($cat['Name']['language']['value'], $except))
                {

                    $categories[] = array(

                        'id' => $cat['id'],
                        'name' => $cat['Name']['language']['value'],
                        'url' => "http://store.midlandsport.it" . '/' . $cat['id'] . '-' . strtolower(Inflector::Slug($cat['Name']['language']['value'], '-')) ,
                        'count' => count($cat['Associations']['Products']['Product']) ,

                    );

                }

                if ($jk == $limit) break;

                $jk++;

            }

        }

        $categories_order = array_orderby($categories, 'count', SORT_DESC);

        return $categories_order;

    }

    function getNotes($match_id, $squadra_id)
    {

        $this->layout = "pdf";

        $partita = $this->Match->findByCalendario($match_id);

        $squadra = $this->SquadreCampionati->findBySquadracampionato($squadra_id);

        //GIUSEPPE 2024-08-30

        $city = $this->key_select($this->select_sql("SELECT id, city_name FROM city"),'id') ;

        // $query = '
        //                 SELECT
        //                     Athlete.Nome,
        //                     Athlete.Cognome,
        //                     Athlete.DataNascita,
        //                     Athlete.Sesso,
        //                     Athlete.CityNascita,
        //                     Annuario.Tessera AS Athlete__Tessera
        //                 FROM
        //                     Atleti AS Athlete,
        //                     Annuario
        //                 WHERE
        //                     Annuario.Atleta = Athlete.Atleta AND Annuario.SquadraCampionato = ' . $squadra_id . ' AND Annuario.AnnoSportivo =(
        //                     SELECT
        //                         MAX(AnnoSportivo)
        //                     FROM
        //                         Annuario
        //                 )
        //                 ORDER BY
        //                     Athlete.Cognome ASC,
        //                     Athlete.Nome ASC
        //     ';



	
        //GIUSEPPE 2025-09-21 ------------------------------------------------------------------------------
        include_once __DIR__ . "/../models/api.php";
		$api = new Api();
        $anno_sportivo = $api->annoSportivo();
        $current = $anno_sportivo['current']['year'];

        $this->write_file("anno_sportivo",$anno_sportivo);

        $query = "  SELECT
                        Athlete.Nome,
                        Athlete.Cognome,
                        Athlete.DataNascita,
                        Athlete.Sesso,
                        Athlete.CityNascita,
                        Annuario.Tessera AS Athlete__Tessera,
                        TipiAssicurazione.Simbolo
                    FROM
                        Atleti AS Athlete
                    INNER JOIN Annuario ON Annuario.Atleta = Athlete.Atleta
                    INNER JOIN TipiAssicurazione ON Annuario.TipoAssicurazione = TipiAssicurazione.TipoAssicurazione
                    WHERE
                        Annuario.SquadraCampionato = '{$squadra_id}' AND Annuario.AnnoSportivo = {$current}
                    ORDER BY
                        Athlete.Cognome ASC,
                        Athlete.Nome ASC
                    ";
        // -------------------------------------------------------------------------------------------------

        $partecipanti = $this->Athlete->query($query);

        file_put_contents(ROOT . DS . "squalificati.log", print_r($squalificati, true), FILE_APPEND);
        $this->set('squadra_id', $squadra_id);
        $this->set('partita', $partita);
        $this->set('squadra', $squadra);
        $this->set('partecipanti', $partecipanti);
        $this->set('city', $city);
       
        //GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
        $squalificati = $this->filtraSqualificati($match_id, $squadra_id, $partita, $squadra);
        $this->set('squalificati', $squalificati);
        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
    }


    //GIUSEPPE 2022-12-23 - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
    private function filtraSqualificati($match_id, $squadra_id, $partita, $squadra)
    {
        $res = [];
        $res['SqualificatiTempo'] = [];
        $res['SqualificatiGiornata'] = [];
        $denominazione = $squadra['Squadre']['Denominazione'];

        $campionato = $partita['Match']['Campionato'];
        $girone_campionato = $partita['Match']['GironeCampionato'];
        $giornata = $partita['Match']['Giornata'];

        $disciplinari_json = file_get_contents("_content/disciplinari.json");
        $disciplinari = json_decode($disciplinari_json, true);

        $disciplinari_giornata = $disciplinari[$campionato][$girone_campionato][$giornata];

        if (isset($disciplinari_giornata['Espulsi']))
        {
            foreach ($disciplinari_giornata['Espulsi'] as $atleta => $espulso)
            {
                $periodo = $espulso['Periodo'];

                $denominazione_espulso = $espulso['Squadra'];

                if ($denominazione != $denominazione_espulso)
                    continue;

                if (strpos($periodo, "squalifica"))
                {
                    $res['SqualificatiGiornata'][$atleta] = $disciplinari_giornata['Espulsi'][$atleta];
                }

                if (strpos($periodo, "fino al"))
                {
                    $res['SqualificatiTempo'][$atleta] = $disciplinari_giornata['Espulsi'][$atleta];
                }
            }
        }

//        $this->write_file("_espulsi_bollettino", $disciplinari_giornata);
//        $this->write_file("_espulsi_bollettino_res", $res);

        return $res;
    }



///////////////////////////////////////////////////////////////////////// 
// TIMMYTAG FUNZIONI STAMPA RICEVUTE TESSERAMENTI - UPLOAD DEL 12/06/2018
///////////////////////////////////////////////////////////////////////// 

    function deleteOldFiles()
    {
        $path = APP . "webroot/files/json/ricevute_tesseramenti";
        //echo $path; exit;
        if ($handle = opendir($path))
        {
            while ($file = readdir($handle))
            {
                if (is_dir("./{$directory}/{$file}"))
                {
                    if ($file != "." & $file != "..")
                        $dirs[] = $file;
                }
                else
                {
                    if ($file != "." & $file != "..")
                        $files[] = $file;
                }
            }
        }
        closedir($handle);

        reset($files);
        sort($files);
        reset($files);

        if (count($files) > 100)
        {
            foreach ($files as $i => $file)
            {
                unlink($path . "/" . $file);
                if (count($files) - $i == 100)
                {
                    break;
                }
            }
        }
    }


    function saveFileTemp()
    {
        //print_r($_POST);
        $this->deleteOldFiles();

        $path = APP . "webroot/files/json/ricevute_tesseramenti";

        mkdir($path, 0777);

        $uniqid = uniqid();

        $savepath = $path . "/" . $uniqid . '.json';

        file_put_contents($savepath, ($_POST['json_array']));

        echo $uniqid;

        exit;
    }


    function getAthletes($unique_id)
    {

        $this->layout = "pdf";

        $path = APP . "webroot/files/json/ricevute_tesseramenti/$unique_id.json";

        $tesserati = json_decode(file_get_contents($path), TRUE);

        $vidimazione = $tesserati['vidimazione'];

        $squadra_manifestazione = explode(" → ", $tesserati['squadra_manifestazione']);

        $squadra = $squadra_manifestazione[0];

        $manifestazione = $squadra_manifestazione[1];

        $this->set('partecipanti', $tesserati['atleti']);
        $this->set('vidimazione', $vidimazione);
        $this->set('squadra', $squadra);
        $this->set('manifestazione', $manifestazione);

    }



//- - - - - - - - - - - - - - - - - -
    //GIUSEPPE 2019-03-15 ----------------------------------
    function SquadreFromGirone()
    {
        //print_r($_POST);

        $result = array();
        
        $filter = implode(" OR ", $_POST['id_gironi']);
        
        $sql = "SELECT 
                SquadreCampionati.GironeCampionato,
                Squadre.Squadra,
                Squadre.Denominazione

                FROM `SquadreCampionati`

                INNER JOIN Squadre
                ON Squadre.Squadra = SquadreCampionati.Squadra

                WHERE ($filter)";
        
        
        
        $res = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($res))
        {
            //print_r($row);
            
            $result[$row['GironeCampionato']][$row['Squadra']] = $row['Denominazione'];
        }

        echo json_encode($result) ;

        exit;
    }





    //------------------------------------------------------



    function getSquadreFromGirone($girone_id)
    {

        $this->layout = "ajax";

        $squadre = $this
            ->SquadreCampionati
            ->find('all', array(

            'conditions' => array(

                'SquadreCampionati.GironeCampionato = ' . $girone_id

            ) ,
            'order' => 'Squadre.Denominazione ASC'

        ));

        $teams = array();

        foreach ($squadre as $squadra)
        {

            $team['id'] = $squadra['SquadreCampionati']['SquadraCampionato'];
            $team['value'] = $squadra['Squadre']['Denominazione'];

            $teams[] = $team;

        }

        $this->set('ret', json_encode($teams));

        $this->render('/backend/ajax');

    }

    function getGironiFromCampionato($campionato_id)
    {

        $gironi = $this
            ->Half
            ->find('all', array(
            'conditions' => array(
                'Half.Campionato' => $campionato_id
            ) ,
            'order' => 'Half.Descrizione ASC'
        ));

        $halfs = array();

        foreach ($gironi as $k => $girone)
        {

            $half['id'] = $girone['Half']['GironeCampionato'];
            $half['value'] = $girone['Half']['Descrizione'];

            $halfs[] = $half;

        }

        $this->set('ret', json_encode($halfs));

        $this->render('/backend/ajax');

    }

    //GIUEPPE ------------------------------------------------------------------------------------------------------------------------------------------------
    function getSquadreTennisFromCampionato($campionato_id)
    {

        $campionato_id = - $campionato_id;

        $gironi = $this
            ->Half
            ->find('all', array(
            'conditions' => array(
                'Half.Campionato' => $campionato_id
            ) ,
            'order' => 'Half.Descrizione ASC'
        ));

        $halfs = array();

        foreach ($gironi as $k => $girone)
        {

            $half['id'] = $girone['Half']['GironeCampionato'];
            $half['value'] = $girone['Half']['Descrizione'];

            $halfs[] = $half;

        }

        //$this->set('ret',json_encode($halfs));
        //$this->render('/backend/ajax');
        //echo json_encode($halfs);
        $girone_id = $halfs[0]['id']; // per adesso prendo il primo visto che ipotizia
        //echo $id_girone;
        $this->getSquadreFromGirone($girone_id);

        //exit;
        
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------------------
    

    function getGironiFromCampionatoBySquadra($campionato_id, $squadra = null)
    {

        $gironi = $this
            ->Half
            ->find('all', array(
            'conditions' => array(
                'Half.Campionato' => $campionato_id
            ) ,
            'order' => 'Half.Descrizione ASC'
        ));

        $halfs = array();

        foreach ($gironi as $k => $girone)
        {

            $half['id'] = $girone['Half']['GironeCampionato'];
            $half['value'] = $girone['Half']['Descrizione'];

            if ($squadra != null)
            {

                $data = $this
                    ->SquadreCampionati
                    ->find('first', array(
                    'fields' => array(
                        'SquadreCampionati.SquadraCampionato'
                    ) ,
                    'conditions' => array(
                        'SquadreCampionati.Squadra' => $squadra,
                        'SquadreCampionati.GironeCampionato' => $girone['Half']['GironeCampionato'],
                    ) ,
                ));

                if (count($data) && is_array($data)) $halfs[] = $half;
                break;

            }
            else
            {

                $halfs[] = $half;

            }

        }

        $this->set('ret', json_encode(array(
            'halfs' => $halfs,
            'squadra' => $data
        )));

        $this->render('/backend/ajax');

    }

    function getOpponent($opponent, $me)
    {

        $this->layout = "ajax";

        $matches = $this
            ->Match
            ->find('all', array(

            'conditions' => array(

                "(Match.Casa = $me AND Match.Trasferta = $opponent) OR (Match.Casa = $opponent AND Match.Trasferta = $me)"

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = array();

        foreach ($matches as $match)
        {

            $giornate[] = $match['Match']['Giornata'];

        }

        $this->set('ret', json_encode($giornate));
        $this->render('/backend/ajax');

    }
    function tesseramentidati()
    {

        $this->autoRender = false;

        $uniqid = uniqid();
        $savepath = APP . '/webroot/files/json/tesserati_' . $uniqid . '.json';

        file_put_contents($savepath, json_encode($_POST));

        print json_encode(['id' => $uniqid]);

    }

    function iscrizionedati()
    {

        $this->autoRender = false;

        //GIUSEPPE qui vediamo controlliamo squadra e campionato
        $tipo_iscrizione = $_POST['Subscription']['SubscriptionSelezione'];

        $uniqid = 0;

        switch ($tipo_iscrizione)
        {
            case 0:

                $squadra = $_POST['Subscription']['SubscriptionNomesquadra'];

                $q = mysql_query("SELECT Squadra FROM Squadre WHERE Denominazione = '" . $squadra . "'");

                $r = mysql_fetch_assoc($q);

                if ($r['Squadra'])
                {
                    //la squadra con quel nome esiste gia
                    $uniqid = - 1;
                }

                //echo "SELECT Squadra FROM Squadre WHERE Denominazione = ".$squadra;
                //exit;
                
            break;

            case 1:

                if (isset($_POST['Subscription']['SubscriptionGirone']))
                {
                    $campionato = $_POST['Subscription']['SubscriptionCampionato'];

                    //$girone = $_POST['Subscription']['SubscriptionGirone'];
                    $squadra = $_POST['Subscription']['SubscriptionNomesquadra2'];

                    //$q = mysql_query("SELECT SquadraCampionato FROM SquadreCampionati WHERE Squadra = '".$squadra."' AND Campionato = '".$campionato."' AND GironeCampionato = '".$girone."'");
                    $q = mysql_query("SELECT SquadraCampionato FROM SquadreCampionati WHERE Squadra = '" . $squadra . "' AND Campionato = '" . $campionato . "'");

                    $r = mysql_fetch_assoc($q);

                    if ($r['SquadraCampionato'])
                    {
                        //la squadra è gia iscritta al campionato
                        $uniqid = - 2;
                    }
                }

            break;
        }

        // -------------------------------------------------
        if ($uniqid == 0) // se le query non hanno dato risultati, si puo procedere
        
        {
            $uniqid = uniqid();

            $savepath = APP . '/webroot/files/json/iscrizione_' . $uniqid . '.json';

            file_put_contents($savepath, json_encode($_POST));
        }

        print json_encode(['id' => $uniqid]);
    }

    //GIUSEPPE 02/09/2016 -----------------------------------------------
    // function save_email_payor($email_payor)
    // {
    // session_start();
    // $_SESSION['email_payor'] = $email_payor;
    // echo "la mail e' ".$email_payor;
    // exit;
    // }
    

    function save_email_payor($email_payor, $uniqid)
    {

        $handle = fopen(APP . '/webroot/files/json/email_payor_' . $uniqid . '.json', "a+");
        fwrite($handle, $email_payor);
        fclose($handle);
        exit;
    }

    //GIUSEPPE 03/09/2016 controlla la presenza dell'email in fase di tesseramento da zero
    function searchmail($email)
    {
        $this->autoRender = false;

        $atleta_ext = $this
            ->Athlete
            ->findByEmail($email);

        $risp = "";

        if ($atleta_ext)
        {
            //$risp = $email." presente";
            $risp = 1;
        }
        else
        {
            //$risp = $email." NON presente";
            $risp = 0;
        }
        echo $risp;

        exit;
    }

    //GIUSEPPE 2017/01/05 controlla la mail in fase di conferna dati
    function searchmailconfirm($email, $id)
    {
        $query = "SELECT COUNT(Atleta) FROM Atleti WHERE Email = '$email' AND Atleta <> '$id'";

        $q = mysql_query($query);

        $count = mysql_fetch_array($q) [0];

        echo $count;

        exit;
    }

    //GIUSEPPE 09/09/2016 invio mail singole
    

    function send_single_mail($array_licensed)
    {

        foreach ($array_licensed['atleti'] as $tesserato)
        {

            $this->set('tesserato', $tesserato);

            $this
                ->Email->to = array(
                $tesserato["Email"]
            );

            $this
                ->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';

            $this
                ->Email->subject = 'Notifica tesseramento e verifica dati';

            $this
                ->Email->template = 'tesseramento_singolo';

            $this
                ->Email
                ->send();
        }
    }




    //---------------------------------
    // Url di testing
    // http://www.midlandsport.it/sections/tesseramentoverify/5714c0142cea6

    function tesseramentoverify($uniqid, $sport, $test) /* dopo il pagamento arriviamo a questa pagina */
    {
        $this->autoRender = false;


        //  if (isset($_REQUEST['mac']) && isset($_REQUEST['pan']) && ($_REQUEST['esito'] == "OK") || $test == 1) //GIUSEPPE 2018-07-17 -> modifica pagamento
        if ($_REQUEST['RESULT'] == 00 || $test == 1) //GIUSEPPE 2022-01-11
        {

            // fa parte del vecchio metodo di pagamento
            /* $handle = fopen(APP . '/webroot/files/json/payment_' . $uniqid . '.json', "a+");
              fwrite($handle, "*********geststart*********<br>\n");
              fwrite($handle, "result: CAPTURED<br>\n");
              fwrite($handle, "responsecode: 00<br>\n");
              fwrite($handle, "end: " . " \n<br>\n [" . date("Y-m-d h-m-s") . "]<br><br>\n");
              fclose($handle); */

            $error = '';

            $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/tesserati_' . $uniqid . '.json'), true);

            $ok = 0;

            if (true)
            {

                $ok = 1;

                foreach ($tesserati['atleti'] as $index => $atleta) //$atleta viene preso dal json
                {
                    if (isset($atleta['nomesquadra']))
                    {
                        $squadra_id = 0;

                        $squadra = $this
                                ->Squadre
                                ->findByDenominazione(trim($atleta['nomesquadra']));

                        if (!$squadra)
                        {
                            $this
                                    ->Squadre
                                    ->create();
                            $this
                                    ->Squadre
                                    ->set('Denominazione', $atleta['nomesquadra']);
                            if ($this
                                            ->Squadre
                                            ->save())
                            {

                                $squadra_id = $this
                                        ->Squadre->id;
                            }
                        }
                        else
                        {
                            $squadra_id = $squadra['Squadre']['Squadra'];
                        }

                        //print_r($atleta);
                        $atleta_ext = $atleta['Atleta']; //GIUSEPPE 14/09/2016
                        $atleta_id = 0;

                        //if (!$atleta_ext)
                        if ($atleta_ext == '') // nel caso l'atleta non esiste, viene creato
                        {

                            $anagrafica = $atleta;

                            unset($anagrafica['totale']);

                            unset($anagrafica['nomesquadra']);

                            $anagrafica["Responsabile"] = "No";

                            $d = DateTime::createFromFormat("d/m/Y", $anagrafica["DataNascita"]);

                            $anagrafica["DataNascita"] = $d->format("Y-m-d");

                            //GIUSEPPE 06/09/2016
                            $date = new DateTime(date('Y-m-d H:i:s'));

                            $anagrafica["data_registrazione"] = $date->format('Y-m-d H:i:s');

                            //-------------------
                            $this
                                    ->Athlete
                                    ->create();

                            $this
                                    ->Athlete
                                    ->set($anagrafica);

                            //print_r($anagrafica);
                            if ($res = $this
                                    ->Athlete
                                    ->save())
                            {
                                $atleta_id = $this
                                        ->Athlete->id;
                            }
                        }
                        else
                        {

                            //$atleta_id = $atleta_ext['Athlete']['Atleta'];
                            $atleta_id = $atleta_ext; //$atleta_ext
                            //GIUSEPPE UPDATE ATLETA 14/09/2016
                            $this
                                    ->Athlete
                                    ->set($atleta);

                            $this
                                    ->Athlete
                                    ->save();

                            // -------------------------------
                        }

                        // GIUSEPPE 2017-01-11 ... devo creare un nuovo file in cui inserisco gli id dei nuovi atleti
                        $tesserati['atleti'][$index]['Atleta'] = $atleta_id;

                        // ....................................................................
                        $anno = $this->AnniSportivi->find('first', array(
                            'order' => array(
                                'AnniSportivi.AnnoSportivo DESC'
                            )
                        ));

                        $AnnoSportivo = $anno['AnniSportivi']['AnnoSportivo'];

                        $tessera = $this
                                ->Yearbook
                                ->find('count', array(
                            'conditions' => array(
                                'Yearbook.AnnoSportivo' => $anno['AnniSportivi']['AnnoSportivo'],
                            )
                        ));

                        $ntessera = substr($AnnoSportivo, -2, 2) . str_pad($tessera + 1, 6, "0", STR_PAD_LEFT);

                        $SquadraCampionato = $this
                                ->SquadreCampionati
                                ->find('first', array(
                            'conditions' => array(
                                'SquadreCampionati.Squadra' => $squadra_id
                            ),
                            'order' => 'SquadreCampionati.SquadraCampionato DESC'
                        ));

                        //print_r($SquadraCampionato);
                        //GIUSEPPE 13/11/2016 //tipo assicurazione ---------
                        $query = "SELECT TipoAssicurazione FROM TipiAssicurazione WHERE Descrizione = '" . $atleta["TipoAssicurazione"] . "'";
                        $q = mysql_query($query);
                        $tipo_assicurazione = mysql_fetch_array($q) [0];
                        // ---------------------------
                        $tessera = array();
                        $tessera['Tessera'] = $ntessera;
                        $tessera['TipoAssicurazione'] = $tipo_assicurazione; //1;
                        $tessera['AnnoSportivo'] = $AnnoSportivo;
                        $tessera['Atleta'] = $atleta_id;
                        $tessera['SquadraCampionato'] = $SquadraCampionato['SquadreCampionati']['SquadraCampionato'];
                        $tessera['DataVidimazione'] = date("Y-m-d");
                        $tessera['Responsabile'] = 'No';
                        //GIUSEPPE 13/11/2016 -----------------------
                        $tessera['sport'] = $sport;
                        // ------------------------------------------
                        $this->Yearbook->create();

                        $this->Yearbook->set($tessera);

                        $this->Yearbook->save();

                        $tesserati['atleti'][$index]['Tessera'] = $ntessera;
                    }
                }

                // GIUSEPPE 2017-01-11 ... devo creare un nuovo file in cui inserisco gli id dei nuovi atleti
                $this->mail_after_payment($tesserati, $uniqid, "TESSERAMENTO");

                $handle = fopen(APP . '/webroot/files/json/tesserati_for_confirm_' . $uniqid . '.json', "w+");
                fwrite($handle, json_encode($tesserati));
                fclose($handle);
                header("Location: /sections/tesseratimodify/" . $uniqid); //GIUSEPPE 2018-07-17 redirect sulla pagina di modifica dati atleta
                // ....................................................................
            }
            else
            {
                print $ok;
            }
        }
        else if ($_REQUEST['esito'] == "KO")
        {
            header("Location: /sections/tesseratimodify/" . $uniqid . "/KO");
        }
    }







    //GIUSEPPE 2017-04-13 - - - INVIO MAIL DOPO PAGAMENTO
    function mail_after_payment($tesserati, $uniqid, $type)
    {
        // email riepilogative
        $email_payor = file_get_contents(APP . '/webroot/files/json/email_payor_' . $uniqid . '.json');

        $emails = array(
            'info@midlandsport.it',
            'redazione@midlandsport.it',
            'timmytag@gmail.com',
            $email_payor
        );

        switch ($type)
        {
            case "TESSERAMENTO":

                $this->set('tesserati', $tesserati);

                $this
                    ->Email->to = $emails;
                $this
                    ->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';
                $this
                    ->Email->subject = 'Notifica nuovi tesseramenti';
                $this
                    ->Email->template = 'tesseramento';
                $this
                    ->Email
                    ->send();
            break;

            case "ISCRIZIONE":

                $dati_for_mail = json_decode(file_get_contents(APP . '/webroot/files/json/iscrizione_dati_pagamento_' . $uniqid . '.json') , true);

                if (isset($dati_for_mail['squadra_tennis']))
                {
                    $this->set('squadra_tennis', $squadra_tennis);
                }

                $this->set('cauzione', $dati_for_mail['cauzione']);

                $this->set('sport', $dati_for_mail['sport']);

                $this->set('iscrizione', $tesserati);

                $this
                    ->Email->to = $emails;
                $this
                    ->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';
                $this
                    ->Email->subject = 'Notifica nuova iscrizione';
                $this
                    ->Email->template = 'iscrizione';
                $this
                    ->Email
                    ->send();

            break;
        }
    }

    //GIUSEPPE 2017-01-09 ----update atleti da landing page --
    function updateTesseramentiLandPage()
    {
        //echo $_POST['tesserati_upload'];
        $tesserati = json_decode($_POST['tesserati_upload'], true);

        $uniqid = $_POST['uniqid'];

        //Correggo le maiuscole e le minuscole
        foreach ($tesserati['atleti'] as $i => $tesserato)
        {

            foreach ($tesserato as $id => $value_id)
            {
                //echo $id." -> ".$value_id."<br>";
                //$tesserati['Subscription'][$i] =
                if (strlen(strpos($id, "Email")) > 0)
                {
                    $tesserati['atleti'][$i][$id] = strtolower($value_id);
                }
                else if (strlen(strpos($id, "CodiceFiscale")) > 0)
                {
                    $tesserati['atleti'][$i][$id] = strtoupper($value_id);
                }
                else if (strlen(strpos($id, "Provincia")) > 0)
                {
                    $tesserati['atleti'][$i][$id] = strtoupper($value_id);
                }
                else
                {
                    $tesserati['atleti'][$i][$id] = ucwords(strtolower($value_id));
                } /**/
            }
        }

        //  invio mail singole ------------------
        $this->send_single_mail($tesserati); // invio mail singole
        foreach ($tesserati['atleti'] as $atleta)
        {
            $this->update_to_tesseramenti($atleta);
        }

        //unlink(APP. '/webroot/files/json/tesserati_for_confirm_' . $uniqid . '.json');
        exit;
    }

    function update_to_tesseramenti($dati_tesserato)
    {

        $data_nascita = explode("/", $dati_tesserato['DataNascita']);

        $dati_tesserato['DataNascita'] = $data_nascita[2] . "-" . $data_nascita[1] . "-" . $data_nascita[0];

        $string_query = "UPDATE `Atleti` SET
        Cognome =   '" . ucwords($dati_tesserato['Cognome']) . "'
        ,Nome   =   '" . ucwords($dati_tesserato['Nome']) . "'
        ,DataNascita    =   '" . $dati_tesserato['DataNascita'] . "'
        ,LuogoNascita   =   '" . ucwords($dati_tesserato['LuogoNascita']) . "'
        ,Email  =   '" . $dati_tesserato['Email'] . "'
        ,CodiceFiscale  =   '" . strtoupper($dati_tesserato['CodiceFiscale']) . "'
        ,Indirizzo  =   '" . ucwords($dati_tesserato['Indirizzo']) . "'
        ,Cap    =   '" . $dati_tesserato['Cap'] . "'
        ,Localita   =   '" . ucwords($dati_tesserato['Localita']) . "'
        ,Provincia  =   '" . ucwords($dati_tesserato['Provincia']) . "'
        ,Cellulare  =   '" . $dati_tesserato['Cellulare'] . "'
        ,Sesso  =   '" . ucfirst($dati_tesserato['Sesso']) . "'
        ,TipoDocumento  =   '" . ucwords($dati_tesserato['TipoDocumento']) . "'
        ,NumeroDocumento    =   '" . $dati_tesserato['NumeroDocumento'] . "'
        WHERE
        `Atleta` = '" . $dati_tesserato['Atleta'] . "';";

        mysql_query($string_query);
    }

    //GIUSEPPE 2016-12-23 ---- gestisce i responsabili nell'iscrizione squadre
    function insert_new_to_iscrizione($dati_responsabile)
    {
        $data_nascita = explode("/", $dati_responsabile['DataNascita']);
        $dati_responsabile['DataNascita'] = $data_nascita[2] . "-" . $data_nascita[1] . "-" . $data_nascita[0];

        $data_documento = explode("/", $dati_responsabile['ScadenzaDocumento']);
        $dati_responsabile['ScadenzaDocumento'] = $data_documento[2] . "-" . $data_documento[1] . "-" . $data_documento[0];

        $query = "
        INSERT INTO Atleti (
        Cognome
        ,Nome
        ,DataNascita
        ,LuogoNascita
        ,Email
        ,CodiceFiscale
        ,Indirizzo
        ,Cap
        ,Localita
        ,Provincia
        ,Cellulare
        ,Sesso
        ,TipoDocumento
        ,NumeroDocumento
        ,ScadenzaDocumento
        ,data_registrazione
        )
        VALUES (
        '" . ucwords($dati_responsabile['Cognome']) . "'
        ,'" . ucwords($dati_responsabile['Nome']) . "'
        ,'" . $dati_responsabile['DataNascita'] . "'
        ,'" . ucwords($dati_responsabile['LuogoNascita']) . "'
        ,'" . $dati_responsabile['Email'] . "'
        ,'" . strtoupper($dati_responsabile['CodiceFiscale']) . "'
        ,'" . ucwords($dati_responsabile['Indirizzo']) . "'
        ,'" . $dati_responsabile['Cap'] . "'
        ,'" . ucwords($dati_responsabile['Localita']) . "'
        ,'" . ucwords($dati_responsabile['Provincia']) . "'
        ,'" . $dati_responsabile['Cellulare'] . "'
        ,'" . ucfirst($dati_responsabile['Sesso']) . "'
        ,'" . ucwords($dati_responsabile['TipoDocumento']) . "'
        ,'" . $dati_responsabile['NumeroDocumento'] . "'
        ,'" . $dati_responsabile['ScadenzaDocumento'] . "'
        ,NOW()
        )
        ";

        // ucwords pone in maiuscolo il primo carattere
        //echo $query;
        mysql_query($query);

        return mysql_insert_id();
    }

    // -------------------------------------------------------
    function update_to_iscrizione($dati_responsabile)
    {

        $data_nascita = explode("/", $dati_responsabile['DataNascita']);
        $dati_responsabile['DataNascita'] = $data_nascita[2] . "-" . $data_nascita[1] . "-" . $data_nascita[0];

        if (strstr($dati_responsabile['ScadenzaDocumento'], '/'))
        {

            $data_documento = explode("/", $dati_responsabile['ScadenzaDocumento']);
            $dati_responsabile['ScadenzaDocumento'] = $data_documento[2] . "-" . $data_documento[1] . "-" . $data_documento[0];
        }

        $string_query = "UPDATE `Atleti` SET
        Cognome =   '" . ucwords($dati_responsabile['Cognome']) . "'
        ,Nome   =   '" . ucwords($dati_responsabile['Nome']) . "'
        ,DataNascita    =   '" . $dati_responsabile['DataNascita'] . "'
        ,LuogoNascita   =   '" . ucwords($dati_responsabile['LuogoNascita']) . "'
        ,Email  =   '" . $dati_responsabile['Email'] . "'
        ,CodiceFiscale  =   '" . strtoupper($dati_responsabile['CodiceFiscale']) . "'
        ,Indirizzo  =   '" . ucwords($dati_responsabile['Indirizzo']) . "'
        ,Cap    =   '" . $dati_responsabile['Cap'] . "'
        ,Localita   =   '" . ucwords($dati_responsabile['Localita']) . "'
        ,Provincia  =   '" . ucwords($dati_responsabile['Provincia']) . "'
        ,Cellulare  =   '" . $dati_responsabile['Cellulare'] . "'
        ,Sesso  =   '" . ucfirst($dati_responsabile['Sesso']) . "'
        ,TipoDocumento  =   '" . ucwords($dati_responsabile['TipoDocumento']) . "'
        ,NumeroDocumento    =   '" . $dati_responsabile['NumeroDocumento'] . "'
        ,ScadenzaDocumento  =   '" . $dati_responsabile['ScadenzaDocumento'] . "'
        WHERE
        `Atleta` = '" . $dati_responsabile['id'] . "';";

        mysql_query($string_query);
    }





    

   // ..............................................................................................


    function iscrizioneverify($uniqid, $id_sport, $cauzione_versata, $squadre_tennis)
    {

        $this->autoRender = false;

        /*         * ************DEBUG******* */
//        $handle = fopen(APP . '/webroot/files/json/payment_' . $uniqid . '.json', "a+");
//        fwrite($handle, "*********geststart*********<br>\n");
//        fwrite($handle, "start: " . " \n<br>\n [" . date("Y-m-d h-m-s") . "]<br><br>\n");
//        fwrite($handle, "paymentid: " . $_REQUEST['paymentid'] . "<br>\n");
//        fwrite($handle, "result: " . $_REQUEST['result'] . "<br>\n");
//        fwrite($handle, "responsecode: " . $_REQUEST['responsecode'] . "<br>\n");
//        fwrite($handle, "auth: " . $_REQUEST['auth'] . "<br>\n");
//        fwrite($handle, "ref: " . $_REQUEST['ref'] . "<br>\n");
//        fwrite($handle, "tranid: " . $_REQUEST['tranid'] . "<br>\n");
//        fwrite($handle, "end: " . " \n<br>\n [" . date("Y-m-d h-m-s") . "]<br><br>\n");
//        fclose($handle);
        // GIUSEPPE // serve verificare solo $_REQUEST['paymentid']
//        if (isset($_REQUEST['paymentid']) && isset($_REQUEST['authorizationcode']) || $cauzione_versata == 0 || $cauzione_versata == 2 || $cauzione_versata == 3) //GIUSEPPE 14/10/2016 inserito un ulteriore contollo 'rrn'
//        
//        if (isset($_REQUEST['mac']) && isset($_REQUEST['pan']) && ($_REQUEST['esito'] == "OK") || $test == 1 || ($id_sport == 1 && $cauzione_versata == 3) || ($id_sport == 1 && $cauzione_versata == 2) || ($id_sport == 0 && $cauzione_versata == 0)) //GIUSEPPE 2018-07-17 -> modifica pagamento
        if ($_REQUEST['RESULT'] == 00 || $test == 1 || ($id_sport == 1 && $cauzione_versata == 3) || ($id_sport == 1 && $cauzione_versata == 2) || ($id_sport == 0 && $cauzione_versata == 0)) //GIUSEPPE 2022-01-11
        {

            $handle = fopen(APP . '/webroot/files/json/payment_' . $uniqid . '.json', "a+");
            fwrite($handle, "*********geststart*********<br>\n");
            fwrite($handle, "result: CAPTURED<br>\n");
            fwrite($handle, "responsecode: 00<br>\n");
            fwrite($handle, "end: " . " \n<br>\n [" . date("Y-m-d h-m-s") . "]<br><br>\n");
            fclose($handle);

            $error = '';

            // $cauzione_versata = 0 -> calcio : la cauzione è stata versata
            // $cauzione_versata = 2 -> tennis : il pagamento verrà effettuato con bonifico
            // $cauzione_versata = 3 -> tennis : il pagamento verrà effettuato in sede
            //$paymentid = $_REQUEST['paymentid'];
            $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/iscrizione_' . $uniqid . '.json'), true);

            //$email_payor = file_get_contents(APP. '/webroot/files/json/email_payor_' . $uniqid . '.json');
            $squadra_id = 0; // CI SERVIRA' DOPO
            $nome_squadra = "";

            $sport = "";

            switch ($id_sport)
            {
                case 0:
                    $sport = "CALCIO";
                    break;

                case 1:
                    $sport = "TENNIS";
                    break;
            }

            switch ($tesserati['Subscription']['SubscriptionSelezione'])
            {
                case 0:
                    //echo "NUOVA SQUADRA"; QUINDI DA INSERIRE
                    $tesserati['Subscription']["nomesquadra"] = $tesserati['Subscription']['SubscriptionNomesquadra'];

                    $this
                            ->Squadre
                            ->create();
                    $this
                            ->Squadre
                            ->set('Denominazione', $tesserati['Subscription']['SubscriptionNomesquadra']);
                    //$this->Squadre->set('id_sport','0');
                    //$this->Squadre->set('sport','CALCIO');


                    $this
                            ->Squadre
                            ->set('id_sport', $id_sport);
                    $this
                            ->Squadre
                            ->set('sport', $sport);

                    if ($this
                                    ->Squadre
                                    ->save())
                    {
                        //se esiste una squadra con questo nome, non la fa inserire
                        $squadra_id = $this
                                ->Squadre->id;
                    }

                    break;

                case 1:
                    //echo "SQUADRA ESISTENTE"; ABBIAMO L'ID
                    $squadra_id = $tesserati['Subscription']['SubscriptionNomesquadra2'];

                    $q = mysql_query("SELECT Denominazione FROM Squadre WHERE Squadra = " . $squadra_id);

                    $ret = mysql_fetch_assoc($q);

                    $tesserati['Subscription']['nomesquadra'] = $ret['Denominazione'];

                    break;
            }

            //print_r($tesserati);
            $campionato_id = $tesserati['Subscription']['SubscriptionCampionato'];
            $q = mysql_query("SELECT Nome FROM Campionati WHERE Campionato = " . $campionato_id);
            $ret = mysql_fetch_assoc($q);
            $tesserati['Subscription']['campionato'] = $ret['Nome'];

            if (isset($tesserati['Subscription']['SubscriptionGirone']))
            {
                $girone_id = $tesserati['Subscription']['SubscriptionGirone'];
                $q = mysql_query("SELECT Descrizione FROM GironiCampionati WHERE GironeCampionato = " . $girone_id);
                $ret = mysql_fetch_assoc($q);
                $tesserati['Subscription']['girone'] = $ret['Descrizione'];
            }

            $giorno_settimana = ["Domenica", "Lunedi", "Martedi", "Mercoledi", "Giovedi", "Venerdi", "Sabato", "Domenica"]; // ho messo due volte domenica perchè non so se è definta come indice 0  o 6


            $campo_id = $tesserati['Subscription']['SubscriptionCampo'];
            if (is_numeric($campo_id))
            {
                $q = mysql_query("SELECT Descrizione FROM Campi WHERE Campo = " . $campo_id);
                $ret = mysql_fetch_assoc($q);
                $tesserati['Subscription']['campo'] = $ret['Descrizione'];
            }
            else
            {
                $tesserati['Subscription']['campo'] = $campo_id;
            }

            $pagato = "";

            // GIUSEPPE 2016-12-30 - - - - - - - - - - - - - - - - - - - - - - -
            $classPage = $this->className($_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller
            $nameClass = $classPage["Name"];

            $cauzione = $this->readDeposit($nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)
            //print_r($cauzione);
            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            switch ($cauzione_versata)
            {
                case 0:
                    // cauzione calcio
                    $pagato = "Non pagata: € " . $cauzione[0];
                    break;

                case 1:
                    // cauzione calcio e tennis
                    if ($sport == "CALCIO")
                    {
                        $pagato = "Si pagata: € " . $cauzione[0];
                    }
                    else if ($sport == "TENNIS")
                    {
                        $pagato = "Si pagata: € " . $cauzione[$squadre_tennis - 1];
                    }
                    break;

                case 2:
                    // cauzione tennis
                    $pagato = "Non pagata. Da versare con bonifico: € " . $cauzione[$squadre_tennis - 1];
                    break;

                case 3:
                    // cauzione tennis
                    $pagato = "Non pagata. Da versare in sede: € " . $cauzione[$squadre_tennis - 1];
                    break;
            }

            for ($i = 0; $i < 3; $i++) // questi dati mi servono per uppare i responsabili  ad ogni scrizione squadra
            {
                if ($tesserati['Subscription']['Cognome_' . $i] != "")
                {
                    $dati_responsabile['id'] = $tesserati['Subscription']['id_responsabile_' . $i];
                    $dati_responsabile['Cognome'] = $tesserati['Subscription']['Cognome_' . $i];
                    $dati_responsabile['Nome'] = $tesserati['Subscription']['Nome_' . $i];
                    $dati_responsabile['DataNascita'] = $tesserati['Subscription']['DataNascita_it_' . $i];
                    $dati_responsabile['LuogoNascita'] = $tesserati['Subscription']['LuogoNascita_' . $i];
                    $dati_responsabile['Email'] = $tesserati['Subscription']['Email_' . $i];
                    $dati_responsabile['CodiceFiscale'] = $tesserati['Subscription']['CodiceFiscale_' . $i];
                    $dati_responsabile['Indirizzo'] = $tesserati['Subscription']['Indirizzo_' . $i];
                    $dati_responsabile['Cap'] = $tesserati['Subscription']['Cap_' . $i];
                    $dati_responsabile['Localita'] = $tesserati['Subscription']['Localita_' . $i];
                    $dati_responsabile['Provincia'] = $tesserati['Subscription']['Provincia_' . $i];
                    $dati_responsabile['Cellulare'] = $tesserati['Subscription']['Cellulare_' . $i];
                    $dati_responsabile['Sesso'] = $tesserati['Subscription']['SubscriptionSesso' . $i];
                    $dati_responsabile['TipoDocumento'] = $tesserati['Subscription']['SubscriptionTipoDocumento' . $i];
                    $dati_responsabile['NumeroDocumento'] = $tesserati['Subscription']['NumeroDocumento_' . $i];
                    $dati_responsabile['ScadenzaDocumento'] = $tesserati['Subscription']['ScadenzaDocumento_' . $i];

                    if ($dati_responsabile['id'] == "NEW")
                    {

                        if ($dati_responsabile['Cognome'] != "")
                        {
                            $id_insert = $this->insert_new_to_iscrizione($dati_responsabile);

                            $tesserati['Subscription']['id_responsabile_' . $i] = $id_insert;
                        }
                    }

                    if (is_numeric($dati_responsabile['id']))
                    {
                        if ($dati_responsabile['Cognome'] != "")
                        {
                            $this->update_to_iscrizione($dati_responsabile);
                        }
                    }
                }
            }

            // GIUSEPPE 2017-01-11 ... devo creare un nuovo file in cui inserisco gli id dei nuovi atleti
            $handle = fopen(APP . '/webroot/files/json/iscrizione_for_confirm_' . $uniqid . '.json', "w+");
            fwrite($handle, json_encode($tesserati));
            fclose($handle);

            // ....................................................................
            $this
                    ->SquadreCampionati
                    ->set('Campionato', $campionato_id);
            $this
                    ->SquadreCampionati
                    ->set('Squadra', $squadra_id);
            $this
                    ->SquadreCampionati
                    ->set('GironeCampionato', $girone_id);
            $this
                    ->SquadreCampionati
                    ->set('Campo', $campo_id);
            $this
                    ->SquadreCampionati
                    ->set('Giorno', $giorno_settimana[$tesserati['Subscription']['SubscriptionGiorno']]);
            // $this->SquadreCampionati->set('Pagato', $pagato);
            $this
                    ->SquadreCampionati
                    ->set('Ora', str_replace(":", ".", $tesserati['Subscription']['SubscriptionOra']));
            $this
                    ->SquadreCampionati
                    ->save();

            $dati_for_mail = array();

            if ($squadre_tennis !== "")
            {
                $squadra = "";

                switch ($squadre_tennis)
                {
                    case 1:
                        $squadra = "Squadra 1";
                        break;

                    case 2:
                        $squadra = "Squadra 2";
                        break;

                    case 3:
                        $squadra = "Squadre 1 & 2";
                        break;
                }

                //$this->set('squadra_tennis',$squadra);
                $dati_for_mail['squadra_tennis'] = $squadra;
            }

            $dati_for_mail['cauzione'] = $pagato;

            $dati_for_mail['sport'] = $id_sport;

            $handle = fopen(APP . '/webroot/files/json/iscrizione_dati_pagamento_' . $uniqid . '.json', "w+");
            fwrite($handle, json_encode($dati_for_mail));
            fclose($handle);

            // GIUSEPPE 2017-04-14............................................
            $this->mail_after_payment($tesserati, $uniqid, "ISCRIZIONE");

            $ok = 0;

            if (1)
            {
                $ok = 1;

                header("Location: /sections/iscrizionemodify/" . $uniqid);
            }
            else
            {
                print $ok;
            }
        }
        else
        {
            header("Location: /sections/iscrizionemodify/" . $uniqid . "/KO");
        }
        //else
        //header("Location: /sections/iscrizioneconfirm/" . $uniqid . "/" . $force);
    }




    function iscrizionemodify($uniqid, $esito = 0) // GIUSEPPE 2017-10-01
    {

        if (!file_exists(APP . '/webroot/files/json/iscrizione_for_confirm_' . $uniqid . '.json')) //GIUSEPPE 2017-03-28 - entro qui nel caso in cui annullo un pagamento
        {
            $ok = 0;

            $this->set('tesserati', $tesserati);
            $this->set('ok', $ok);
            $this->set('force', $force);
            $this->set('esito', $esito);
            $this->layout = "content";
            $this->render('iscrizioneconfirm'); // apre la pagina app/views/sections/iscrizioneconfirm.ctp
        }
        else
        {
            $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/iscrizione_for_confirm_' . $uniqid . '.json'), true);

            $this->set('tesserati', $tesserati);

            $this->set('uniqid', $uniqid);

            $this->layout = "content";

            $this->render('iscrizionemodify'); // apre la pagina app/views/sections/iscrizioneconfirm.ctp
        }

    }





    function iscrizioneconfirm($uniqid, $force)
    {

        //result: CAPTURED
//        $info = (file_get_contents(APP . '/webroot/files/json/payment_' . $uniqid . '.json'));
//        $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/iscrizione_' . $uniqid . '.json'), true);
//
//        $ok = 0;
//        if (substr_count($info, "result: CAPTURED") || substr_count($info, "responsecode: 00") || $force == 1)
//        {
//
//            $ok = 1;
//        }
//        else
//        {
//
//            $ok = 0;
//        }


        if ($ok == "0" || isset($esito))
        {
            $this->set('esito', $esito);
            $ok = 0;
        }
        else if ($force == 1)
        {
            $ok = 1;
        }
        else
        {
            
        }

        $this->set('tesserati', $tesserati);
        $this->set('ok', $ok);
        $this->set('force', $force);
        $this->layout = "content";
        $this->render('iscrizioneconfirm');
    }









    function tesseratimodify($uniqid, $esito = 0) // GIUSEPPE 04/01/2017
    {

        if (!file_exists(APP . '/webroot/files/json/tesserati_for_confirm_' . $uniqid . '.json')) //GIUSEPPE 2017-03-28 - entro qui nel caso in cui annullo un pagamento
        {
            $ok = 0;

            $this->set('tesserati', $tesserati);
            $this->set('ok', $ok);
            $this->set('esito', $esito);
            $this->layout = "content";

            $this->render('tesseraticonfirm');
        }
        else
        {
            $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/tesserati_for_confirm_' . $uniqid . '.json'), true);

            $ok = 1;

            $this->set('tesserati', $tesserati);
            $this->set('uniqid', $uniqid);
            $this->set('ok', $ok);
            $this->set('force', $force);
            $this->layout = "content";
            $this->render('tesseratimodify'); // apre la pagina app/views/sections/iscrizioneconfirm.ctp
        }

    }







    function tesseraticonfirm($uniqid)
    {

        //result: CAPTURED
        $info = (file_get_contents(APP . '/webroot/files/json/payment_' . $uniqid . '.json'));
        $tesserati = json_decode(file_get_contents(APP . '/webroot/files/json/tesserati_' . $uniqid . '.json'), true);

        /*  $ok = 0;
          if (substr_count($info, "result: CAPTURED") || substr_count($info, "responsecode: 00") || $uniqid == "570e5b873101d")
          {

          $ok = 1;
          }
          else
          {

          $ok = 0;
          $this->set('esito', $esito);
          } */

        if ($ok == "0" || isset($esito))
        {
            $this->set('esito', $esito);
            $ok = 0;
        }
        else
        {
            $ok = 1;
        }

        $this->set('tesserati', $tesserati);
        $this->set('ok', $ok);
        $this->layout = "content";

        $this->render('tesseraticonfirm');

    }



    function clear_cache()
    {



        $models = APP . 'tmp/cache/models/';
        $persistent = APP . 'tmp/cache/persistent/';

        /* echo $models."<br>";
          echo $persistent."<br>"; */

        $dirs[] = $models;
        $dirs[] = $persistent;


        foreach ($dirs as $dir)
        {
            echo "<br><br><br><br>" . $dir . "<br><br>";

            $directory_handle = opendir($dir);

            /* Scorro l'oggetto fino a quando non è termnato cioè false */
            while (($file = readdir($directory_handle)) !== false)
            {
                /* Se l'elemento trovato è diverso da una directory
                  o dagli elementi . e .. lo visualizzo a schermo */
                if ((!is_dir($file)) & ($file != ".") & ($file != ".."))
                {
                    echo "- " . $file . "<br>";
                    echo unlink($dir . $file) . "<br>";
                }
            }

            closedir($directory_handle);
        }


        exit;

    }





    function testdata($champ_id = 420, $half_id = 1099)
    {

        Configure::Write('debug', 2);

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')) ,
                    date("Y-m-d", strtotime('next Saturday'))
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            ) ,
            'recursive' => - 1
        ));

        debug($prossima_giornata);

        exit;

    }

    function filterDisciplinari($champ_id, $half_id, $squadra_id)
    {

        $disciplinari = json_decode(file_get_contents(APP . "/webroot/files/json_frontend/disciplinari/disciplinare_" . $champ_id . "_" . $half_id . ".json") , 1);

        //$this->set('nextDay', $nextDay);
        $this->set('disciplinari', $disciplinari);

    }

    function filterDisciplinari_old($champ_id, $half_id, $squadra_id)
    {

        Configure::Write('debug', 0);

        $disciplinari = json_decode(file_get_contents(APP . "/webroot/files/json_frontend/disciplinari/disciplinare_" . $champ_id . "_" . $half_id . ".json") , 1);

        //$this->set('nextDay', $nextDay);
        $this->set('disciplinari', $disciplinari);

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Match.Data',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d 12:30:00", strtotime('last Saturday')) ,
                    date("Y-m-d 12:30:00", strtotime('next Saturday'))
                ) ,
                //array('Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday')))),
                //array('Match.Data >=' => date("Y-m-d 00:00:00")),
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => array(
                'DISTINCT Match.Giornata',
                'Match.Data'
            ) ,
            'order' => array(
                'Match.Giornata ASC'
            ) ,
            'recursive' => - 1
        ));

        /*
        $first   = strtotime(date("Y-m-d 12:30:00",strtotime('last Saturday')));
        $last    = strtotime(date("Y-m-d 12:30:00",strtotime('next Saturday')));
        
        $current = strtotime(date("Y-m-d H:i:s"));
        
        if($current >= $first && $current <= $last)
        {
        
        
        
        }
        else
        {
        
        $prossima_giornata['Match']['Giornata'] = $prossima_giornata['Match']['Giornata'] - 1;
        }*/

        Configure::write('debug', 2);
        //debug($prossima_giornata);
        //debug(date("Y-m-d 12:30:00",strtotime('last Tuesday')));
        if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
        else $nextDay = (isset($giornate[count($giornate) ]['Match']['Giornata'])) ? $giornate[count($giornate) ]['Match']['Giornata'] : 0;

        /*  HACK GIORNATA DI RIFERIMENTO */
        $infoGiornate = $this->getGiornataInCorso($giornate);

        $giornata_riferimento = $infoGiornate['giornata_riferimento'];
        $giornata_riferimento_set = $infoGiornate['giornata_riferimento_set'];

        $nextDay = $giornata_riferimento;
        $nextDay_real = $giornata_riferimento;
        /* END HACK GIORNATA DI RIFERIMENTO */

        //debug($nextDay);
        $partite = $this
            ->Match
            ->find('all',

        array(

            'conditions' => array(

                'Match.Giornata' => $nextDay,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => array(
                'Match.Calendario'
            ) ,
            'order' => array(
                'Match.Giornata DESC'
            ) ,
            'recursive' => - 1
        ));

        $disciplinari = array();

        foreach ($partite as $partita)
        {

            $disc = $this->getSqualificatiByCalendario($partita['Match']['Calendario']);

            foreach ($disc['squalificati'] as $tmp)
            {
                $disciplinari['squalificati'][$tmp['id_atleta']] = $tmp;
            }
            foreach ($disc['espulsi'] as $tmp)
            {
                $disciplinari['espulsi'][$tmp['IdSquadra'] . '-' . $tmp['Anagrafica']] = $tmp;
            }
            foreach ($disc['diffidati'] as $tmp)
            {
                $disciplinari['diffidati'][$tmp['id_atleta']] = $tmp;
            }

        }

        $this->set('nextDay', $nextDay);
        $this->set('disciplinari', $disciplinari);

        $this->getEspulsiAmmoniti($champ_id, $half_id, $squadra_id);

    }

    function getSqualificatiByCalendario($calendario)
    {

        Configure::Write('debug', 2);

        $calendario_arr = $this
            ->Match
            ->find('first', array(

            'fields' => array(

                'Match.Calendario',
                'Match.Giornata',
                'Match.Data',
                'Match.Casa',
                'Match.CasaNome',
                'Match.TrasfertaNome',
                'Match.Trasferta',
                'Match.Campionato',
                'Match.GironeCampionato',
                'Campionati.CampionatoPrecedente'

            ) ,
            'conditions' => array(
                'Match.Calendario' => $calendario
            ) ,

        ));

        $giornata = $calendario_arr['Match']['Giornata'];
        $champ_id = $calendario_arr['Match']['Campionato'];
        $half_id = $calendario_arr['Match']['GironeCampionato'];

        $espulsi = array();
        $squalif = array();
        $diff = array();

        /*$diffidati_array = $this->Matchgoal->query(
        
        "SELECT
        (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
        (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
        (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
        (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
        (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
        (SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
        COUNT(*) as Ammonizioni FROM GoalPartite
        WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
        AND GoalPartite.Ammonizione = 'Si'
        AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
        GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
        
        );*/

        $giornata_prec = $giornata - 1;

        $diffidati_tmp = $this
            ->Matchgoal
            ->query(

        "SELECT
                (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
                (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                GoalPartite.Atleta,
                0 as AzzeraDiffidati,
                COUNT(*) as Ammonizioni FROM GoalPartite
                WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                AND GoalPartite.Ammonizione = 'Si'
                AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
                GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
);

        print_r($diffidati_tmp);

        //debug($diffidati_tmp);
        $espulsi_array = $this
            ->Matchgoal
            ->query(

        "SELECT
                (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as id_atleta,
                (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                (SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
                GoalPartite.GoalPartita,
                GoalPartite.EspulsioneGiornate,
                GoalPartite.EspulsioneInizio,
                GoalPartite.EspulsioneFine,
                GoalPartite.Espulsione FROM GoalPartite
                WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                AND GoalPartite.Espulsione = 'Si'
                AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
                GROUP BY GoalPartite.GoalPartita ORDER By NomeSquadra"
);

        /*
         *
         * VERIFICO DISCIPLINARI CAMPIONATO PRECEDENTE.
         *
        */
        $champ_prec = $calendario_arr['Campionati']['CampionatoPrecedente'];

        if (($champ_prec != "" || $champ_prec != 0))
        {

            //Giornata finale campionato precedente
            $data_fine = $this
                ->Match
                ->find('first',

            array(
                'conditions' => array(
                    'Match.Campionato' => $champ_prec,
                ) ,
                'fields' => array(
                    'Match.Giornata',
                    'Match.Data'
                ) ,
                'order' => array(
                    'Match.Data DESC'
                ) ,
                'recursive' => - 1
            )
);

            if (strtotime(date("Y-m-d")) > strtotime($data_fine['Match']['Data']))
            { //Verifico che il campionato precedente sia finito
                //Calcolo squadre campionato riferite al campionato e al girone del NUOVO campionato
                $squadrec = $this
                    ->SquadreCampionati
                    ->find('list', array(
                    'fields' => array(
                        'SquadreCampionati.SquadraCampionato',
                        'SquadreCampionati.SquadraCampionato'
                    ) ,
                    'conditions' => array(
                        'SquadreCampionati.Campionato' => $champ_id,
                        'SquadreCampionati.GironeCampionato' => $half_id
                    ) ,
                ));

                $squadrec = array_merge($squadrec);

                //Calcolo id atleti
                $atletic = $this
                    ->Yearbook
                    ->find('list', array(
                    'fields' => array(
                        'Yearbook.Atleta',
                        'Yearbook.Atleta'
                    ) ,
                    'conditions' => array(
                        'Yearbook.SquadraCampionato' => $squadrec
                    )
                ));

                $atletic = array_merge($atletic);

                $espulsi_array_prec = $this
                    ->Matchgoal
                    ->query(

                "SELECT
                    (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                    (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                    (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                    (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as id_atleta,
                    (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                    (SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
                    1 as OldChamp,
                    GoalPartite.GoalPartita,
                    GoalPartite.EspulsioneGiornate,
                    GoalPartite.EspulsioneInizio,
                    GoalPartite.EspulsioneFine,
                    GoalPartite.Espulsione FROM GoalPartite
                    WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_prec')
                    AND GoalPartite.Espulsione = 'Si'
                    AND GoalPartite.Atleta IN (" . implode(",", $atletic) . ")
                    AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '" . $data_fine['Match']['Giornata'] . "')
                    GROUP BY GoalPartite.GoalPartita ORDER By NomeSquadra"
);

                /**/
                /*
                $diffidati_tmp_prec = $this->Matchgoal->query(
                
                "SELECT
                (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '".$data_fine['Match']['Giornata']."' AND Calendari.Campionato = '$champ_prec') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
                (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                GoalPartite.Atleta,
                1 as AzzeraDiffidati,
                COUNT(*) as Ammonizioni FROM GoalPartite
                WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_prec')
                AND GoalPartite.Ammonizione = 'Si'
                AND GoalPartite.Atleta IN (".implode(",",$atletic).")
                AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '".$data_fine['Match']['Giornata']."')
                GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
                
                );
                
                
                $diffidati_tmp = array_merge($diffidati_tmp, $diffidati_tmp_prec);
                */
                $espulsi_array = array_merge($espulsi_array, $espulsi_array_prec);

            }

        }

        //debug($espulsi_array);
        //debug($diffidati_array);
        //debug($giornata);
        foreach ($espulsi_array as $k => $espulso)
        {
            /*
            //if($espulso[0]['NomeSquadra'] != $calendario_arr['Match']['CasaNome'] && $espulso[0]['NomeSquadra'] != $calendario_arr['Match']['TrasfertaNome']) continue;
            
            //debug($espulso);
            
            if(!isset($espulso[0]['Data'])) $espulso[0]['Data'] = '0000/00/00';
            
            $giorni   = $espulso['GoalPartite']['EspulsioneGiornate'];
            $inizio   = date('d/m/Y', strtotime($espulso[0]['Data']));
            $fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));
            
            if($giorni != '' && $giorni != 0){
            
            if ( $fine == '00/00/0000' ) {
            $_periodo = $giorni;
            $periodo  = $giorni . ' giornate';
            
            //$fine_squalifica = $_periodo + $espulso[0]['Giornata'];
            
            if (!isset($espulso[0]['OldChamp']))
            $fine_squalifica = $_periodo + $espulso[0]['Giornata'];
            else
            $fine_squalifica = $_periodo-($data_fine['Match']['Giornata']-$espulso[0]['Giornata'])+1;
            //debug("Periodo:" . $_periodo . " fine squalifica: " . $fine_squalifica);
            
            //if($fine_squalifica >= $calendario_arr['Match']['Giornata']) {
            if($fine_squalifica >= $calendario_arr['Match']['Giornata']) {
            
            $espulsi[$espulso[0]['id_atleta']] = array(
            
            'IdSquadra' => $espulso[0]['IdSquadra'],
            'Squadra' => $espulso[0]['NomeSquadra'],
            'Anagrafica' => $espulso[0]['anagrafica'],
            'Periodo' => $periodo,
            
            );
            
            }
            } else {
            
            $_periodo = strtotime($espulso['GoalPartite']['EspulsioneFine']);
            
            if(strtotime($calendario_arr['Match']['Data']) <= $_periodo) {
            
            $espulsi[$espulso[0]['id_atleta']] = array(
            
            'IdSquadra' => $espulso[0]['IdSquadra'],
            'Squadra' => $espulso[0]['NomeSquadra'],
            'Anagrafica' => $espulso[0]['anagrafica'],
            'Periodo' => $fine,
            
            );
            }
            
            }
            
            
            } else {
            
            if($inizio != '00/00/0000' && $fine != '00/00/0000') {
            
            $_periodo = strtotime($espulso['GoalPartite']['EspulsioneFine']);
            $periodo  = $inizio . ' - ' . $fine;
            
            if(strtotime($calendario_arr['Match']['Data']) <= $_periodo) {
            
            $espulsi[$espulso[0]['id_atleta']] = array(
            
            'IdSquadra' => $espulso[0]['IdSquadra'],
            'Squadra' => $espulso[0]['NomeSquadra'],
            'Anagrafica' => $espulso[0]['anagrafica'],
            'Periodo' => $periodo,
            
            );
            
            }
            
            } else {
            
            $_periodo = 1;
            $periodo  = '1 giornata';
            
            if (!isset($espulso[0]['OldChamp']))
            $fine_squalifica = $_periodo + $espulso[0]['Giornata'];
            else
            $fine_squalifica = $_periodo-($data_fine['Match']['Giornata']-$espulso[0]['Giornata']);
            if($fine_squalifica == $calendario_arr['Match']['Giornata']) {
            
            $espulsi[$espulso[0]['id_atleta']] = array(
            
            'IdSquadra' => $espulso[0]['IdSquadra'],
            'Squadra' => $espulso[0]['NomeSquadra'],
            'Anagrafica' => $espulso[0]['anagrafica'],
            'Periodo' => $periodo,
            
            );
            
            }
            
            }
            
            }
            
            */

            if ($espulso[0]['NomeSquadra'] != $calendario_arr['Match']['CasaNome'] && $espulso[0]['NomeSquadra'] != $calendario_arr['Match']['TrasfertaNome']) continue;

            if (!isset($espulso[0]['Data'])) $espulso[0]['Data'] = '0000/00/00';

            $giorni = $espulso['GoalPartite']['EspulsioneGiornate'];
            $inizio = date('d/m/Y', strtotime($espulso[0]['Data']));
            $fine = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));

            if ($giorni != '' && $giorni != 0)
            {

                $fine_arr = explode('/', $fine);

                if (!checkdate($fine_arr[1], $fine_arr[0], $fine_arr[2]))
                {

                    //  if ($calendario == 131784) print_r($tmpz);
                    

                    $_periodo = $giorni;
                    $periodo = $giorni . ' giornate';

                    //$fine_squalifica = $_periodo + $espulso[0]['Giornata'];
                    if (!isset($espulso[0]['OldChamp']))
                    {
                        $fine_squalifica = $_periodo + $espulso[0]['Giornata'];
                    }
                    else $fine_squalifica = $_periodo - ($data_fine['Match']['Giornata'] - $espulso[0]['Giornata']) + 1;
                    //debug("Periodo:" . $_periodo . " fine squalifica: " . $fine_squalifica);
                    //if($fine_squalifica >= $calendario_arr['Match']['Giornata']) {
                    if ($fine_squalifica > $calendario_arr['Match']['Giornata'])
                    {

                        $espulsi[$espulso[0]['id_atleta']] = array(

                            'IdSquadra' => $espulso[0]['IdSquadra'],
                            'Squadra' => $espulso[0]['NomeSquadra'],
                            'Anagrafica' => $espulso[0]['anagrafica'],
                            'Periodo' => $periodo,

                        );

                    }
                }
                else
                {

                    $_periodo = strtotime($espulso['GoalPartite']['EspulsioneFine']);

                    if (strtotime($calendario_arr['Match']['Data']) <= $_periodo)
                    {

                        $espulsi[$espulso[0]['id_atleta']] = array(

                            'IdSquadra' => $espulso[0]['IdSquadra'],
                            'Squadra' => $espulso[0]['NomeSquadra'],
                            'Anagrafica' => $espulso[0]['anagrafica'],
                            'Periodo' => $fine,

                        );
                    }

                }

            }
            else
            {
                $inizio_arr = explode('/', $inizio);
                $fine_arr = explode('/', $fine);

                $bool_inizio = checkdate($inizio_arr[1], $inizio_arr[0], $inizio_arr[2]);
                $bool_fine = checkdate($fine_arr[1], $fine_arr[0], $fine_arr[2]);

                if ($bool_inizio && $bool_fine)
                {

                    $_periodo = strtotime($espulso['GoalPartite']['EspulsioneFine']);
                    $periodo = $inizio . ' - ' . $fine;

                    if (strtotime($calendario_arr['Match']['Data']) <= $_periodo)
                    {

                        $espulsi[$espulso[0]['id_atleta']] = array(

                            'IdSquadra' => $espulso[0]['IdSquadra'],
                            'Squadra' => $espulso[0]['NomeSquadra'],
                            'Anagrafica' => $espulso[0]['anagrafica'],
                            'Periodo' => $periodo,

                        );

                    }

                }
                else
                {

                    $_periodo = 1;
                    $periodo = '1 giornata';

                    if (!isset($espulso[0]['OldChamp'])) $fine_squalifica = $_periodo + $espulso[0]['Giornata'];
                    else $fine_squalifica = $_periodo - ($data_fine['Match']['Giornata'] - $espulso[0]['Giornata']) + 1;
                    if ($fine_squalifica == $calendario_arr['Match']['Giornata'])
                    {

                        $espulsi[$espulso[0]['id_atleta']] = array(

                            'IdSquadra' => $espulso[0]['IdSquadra'],
                            'Squadra' => $espulso[0]['NomeSquadra'],
                            'Anagrafica' => $espulso[0]['anagrafica'],
                            'Periodo' => $periodo,

                        );

                    }

                }

            }

        }

        foreach ($diffidati_tmp as $k => $diffidato)
        {

            $atleta = $diffidato['GoalPartite']['Atleta'];
            $diffidato = $diffidato[0];

            /*
            if($atleta == 7440)
            {
            debug($diffidato);
            debug($diffidato['AmmonitoOggi']);
            debug($diffidato['Ammonizioni'] % 3);
            }
            */

            if ($diffidato['Ammonizioni'] % 3 == 0 && $diffidato['AmmonitoOggi'] == 1)
            {

                //if($diffidato['NomeSquadra'] == $calendario_arr['Match']['CasaNome'] || $diffidato['NomeSquadra'] == $calendario_arr['Match']['TrasfertaNome']) {
                $squalif[] = array(

                    'id_atleta' => $atleta,
                    'IdSquadra' => $diffidato['IdSquadra'],
                    'Squadra' => $diffidato['NomeSquadra'],
                    'Anagrafica' => $diffidato['anagrafica'],
                    'Periodo' => $diffidato['Ammonizioni'],

                );

                //}
                
            }

            if ($diffidato['Ammonizioni'] % 3 == 2 && $diffidato['AzzeraDiffidati'] != 1)
            {

                //if($diffidato['NomeSquadra'] == $calendario_arr['Match']['CasaNome'] || $diffidato['NomeSquadra'] == $calendario_arr['Match']['TrasfertaNome']) {
                $diff[] = array(

                    'id_atleta' => $atleta,
                    'IdSquadra' => $diffidato['IdSquadra'],
                    'Squadra' => $diffidato['NomeSquadra'],
                    'Anagrafica' => $diffidato['anagrafica'],
                    'Periodo' => $diffidato['Ammonizioni'],

                );

                //}
                
            }

        }

        $squalificati = array(

            'espulsi' => $espulsi,
            'squalificati' => $squalif,
            'diffidati' => $diff,

        );

        //debug($squalificati);
        return $squalificati;

    }

    function getGiornataInCorso($giornate)
    {

        //Configure::write('debug',2);
        $giornata_riferimento = count($giornate);
        $giornata_riferimento_set = 0;

        //debug($giornate);
        foreach ($giornate as $gg)
        {

            $gg = $gg['Match'];

            $match_data = strtotime($gg['Data']);
            $last_saturday = strtotime(date("Y-m-d 11:30:00", strtotime('last Saturday')));
            $now = strtotime(date("Y-m-d H:i:s"));
            $nowTime = date("Y-m-d H:i:s");
            $matchTime = $gg['Data'];
            $lastTime = date("Y-m-d 11:30:00", strtotime('last Saturday'));

            //Check next saturday
            if (date('w', strtotime(date("Y-m-d"))) == 6)
            {

                $data = strtotime(date("Y-m-d 11:30:00"));
                $dateTime = date("Y-m-d 11:30:00");

            }
            else
            {

                $data = strtotime(date("Y-m-d 11:30:00", strtotime('next Saturday')));
                $dateTime = date("Y-m-d 11:30:00", strtotime('next Saturday'));

            }

            //debug($dateTime);
            /*
            debug("Data di riferimento : " . $dateTime);
            debug("Match data: " . $matchTime);
            debug("Now time: " . $nowTime);
            debug("Last saturday time: " . $lastTime);
            */

            if ($now >= $data)
            {

                //debug("La data  maggiore del prox sabato");
                if ($match_data > $data)
                {

                    $giornata_riferimento = $gg['Giornata'];
                    $giornata_riferimento_set = 1;
                    //debug('Sono qua $match_data > $data');
                    
                }

            }
            elseif ($now < $data)
            {

                //debug("La data  minore del prox sabato");
                if ($match_data > $last_saturday)
                {

                    //debug('Sono qua $match_data > $last_saturday');
                    $giornata_riferimento = $gg['Giornata'];
                    $giornata_riferimento_set = 1;

                }

            }

            //debug("Giornata attaule: " . $gg['Giornata']);
            if ($giornata_riferimento_set == 1) break;

        }

        //debug("Giornata di riferimento: " . $giornata_riferimento);
        return array(

            'giornata_riferimento' => $giornata_riferimento,
            'giornata_riferimento_set' => $giornata_riferimento_set,

        );

    }





    //


    function ranking($champ_id, $half_id)
    {
        //GIUSEPPE 2020-01
        session_start();
        //----------------

        $array_ranking = array();

        //GIUSEPPE 2020-01 aggiunta alla query '
        $sql = "SELECT 
                                Calendari.Giornata, 
                                Calendari.Calendario, 
                                Calendari.Casa, 
                                Calendari.Data, 
                                Calendari.CausaleRisultato as CalendariCausaleRisultato,
                                (SELECT PuntiDisciplina FROM CausaliRisultato WHERE CausaleRisultato = CalendariCausaleRisultato) as PuntiCausaleRisultato,
                                C1.Squadra as SquadraCasa, 
                                C2.Denominazione as DenominazioneCasa, 
                                (
                                        SELECT 
                                                SUM(GoalPartite.Goal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Casa
                                ) as GoalCasa, 
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Ammonizione) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Casa
                                                AND GoalPartite.Ammonizione = 'Si'
                                ) as AmmonizioniCasa, 
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Ammonizione) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Casa
                                                AND GoalPartite.Espulsione = 'Si'
                                ) as EspulsioniCasa, 
                                (
                                        SELECT 
                                                SUM(Disciplinari.Punti) 
                                        FROM 
                                                Disciplinari 
                                        WHERE 
                                                Disciplinari.Calendario = Calendari.Calendario 
                                        AND Disciplinari.SquadraCampionato = Calendari.Casa
                                ) as DisciplinariCasa, 
                                (
                                        SELECT 
                                                SUM(GoalPartite.Autogoal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Casa
                                ) as AutoGoalCasa, 
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Goal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Casa
                                ) as IsGoalCasa, 
                                Calendari.Trasferta, 
                                C3.Squadra as SquadraTrasferta, 
                                C4.Denominazione as DenominazioneTrasferta, 
                                (
                                        SELECT 
                                                SUM(GoalPartite.Goal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Trasferta
                                ) as GoalTrasferta, 
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Ammonizione) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Trasferta
                                                AND GoalPartite.Ammonizione = 'Si'
                                ) as AmmonizioniTrasferta,
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Ammonizione) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Trasferta
                                                AND GoalPartite.Espulsione = 'Si'
                                ) as EspulsioniTrasferta,
                                (
                                        SELECT 
                                                SUM(Disciplinari.Punti) 
                                        FROM 
                                                Disciplinari 
                                        WHERE 
                                                Disciplinari.Calendario = Calendari.Calendario 
                                                AND Disciplinari.SquadraCampionato = Calendari.Trasferta
                                ) as DisciplinariTrasferta, 
                                (
                                        SELECT 
                                                SUM(GoalPartite.Autogoal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Trasferta
                                ) as AutoGoalTrasferta, 
                                (
                                        SELECT 
                                                COUNT(GoalPartite.Autogoal) 
                                        FROM 
                                                GoalPartite 
                                        WHERE 
                                                GoalPartite.Calendario = Calendari.Calendario 
                                                AND GoalPartite.SquadraCampionato = Calendari.Trasferta
                                ) as IsGoalTrasferta, 
                                Campionati.Italiana,
                                Campionati.sport
                        FROM 
                                `Calendari` 
                                INNER JOIN SquadreCampionati C1 ON C1.SquadraCampionato = Calendari.Casa 
                                INNER JOIN Squadre C2 ON C1.Squadra = C2.Squadra 
                                INNER JOIN SquadreCampionati C3 ON C3.SquadraCampionato = Calendari.Trasferta 
                                INNER JOIN Squadre C4 ON C3.Squadra = C4.Squadra 
                                INNER JOIN Campionati ON Campionati.Campionato = Calendari.Campionato 
                        

                        WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id' AND C2.SquadraServizio = 0 AND Campionati.Italiana = 'No'

                        ORDER BY `Calendari`.`Giornata`  ASC";

        //echo $sql;
        //file_put_contents("_sql_ranking.txt",$sql);

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            // output data of each row
            while ($row = mysql_fetch_assoc($result))
            {
                $array_ranking[] = $row;
                //GIUSEPPE 2020-01
                $_SESSION['campionati']['sport'] = $row['sport'];
                //----------------
            }
        }

        return $array_ranking;
    }





 //GIUSEPPE
      function filtra($array_ranking)
    {
        $points = array();

        $all_teams_temp = array();
        $all_teams = array();

        $now = date("Y-m-d");

        $nextDay = 1;

        /* 2018-06-23 inserisco la data piu piccola per le giornate (possono essere in giorni differenti) */
        $data_min = array();


        foreach ($array_ranking as $single)
        {
            $giornata = $single['Giornata'];

            $den_casa = $single['DenominazioneCasa'];
            $den_trasf = $single['DenominazioneTrasferta'];

            /* 2018-06-23 */
            if (!isset($data_min[$giornata]['data_min']))
            {
                $data_min[$giornata]['data_min'] = "9999-12-31";
            }

            if ($single['Data'] <= $data_min[$giornata]['data_min'])
            {
                $data_min[$giornata]['data_min'] = $single['Data'];
            }

            $data = $data_min[$giornata]['data_min'];
            /* - - - - - -  */

            /* $data = $single['Data']; */

            $points[$giornata]['data'] = $data;

            if ($data <= $now)
            {
                $nextDay = $giornata;
            }


            $all_teams_temp[$den_casa] = $den_casa;
            $all_teams_temp[$den_trasf] = $den_trasf;


            $casa_id = $single['SquadraCasa'];
            $traferta_id = $single['SquadraTrasferta'];

            $casa_nulle = $single['CasaNulle'];
            $traferta_nulle = $single['TrasfertaNulle'];
//
//            $casa_disciplina = $single['CasaCoppaDisciplina'];
//            $trasferta_disciplina = $single['TrasfertaCoppaDisciplina'];
//            
//            
            //GIUSEPPE 2019-02-23------------
            $casa_disciplina = $single['PuntiCausaleRisultato'];
            $trasferta_disciplina = $single['PuntiCausaleRisultato'];

            $casa_causale_disciplina = $single['CalendariCausaleRisultato'];
            $trasferta_causale_disciplina = $single['CalendariCausaleRisultato'];

            //-------------------------------


            $casa_ammonizioni = $single['AmmonizioniCasa'];
            $trasferta_ammonizioni = $single['AmmonizioniTrasferta'];

            $casa_espulsioni = $single['EspulsioniCasa'];
            $trasferta_espulsioni = $single['EspulsioniTrasferta'];

            $casa_goal = $single['GoalCasa'];
            $casa_autogoal = $single['AutoGoalCasa'];

            $trasferta_goal = $single['GoalTrasferta'];
            $trasferta_autogoal = $single['AutoGoalTrasferta'];


            /* $casa_punti_penalizzazione = $single['CasaPenalizzazione'];
              $trasferta_punti_penalizzazione = $single['TrasfertaPenalizzazione']; */

            $casa_disciplinari = $single['DisciplinariCasa'];
            $trasferta_disciplinari = $single['DisciplinariTrasferta'];

            $casa_fatti = $casa_goal + $trasferta_autogoal;
            $trasferta_fatti = $trasferta_goal + $casa_autogoal;

            //GIUSEPPE 2019-02-23------------
            $win_lose_casa = $this->win_lose($casa_fatti, $trasferta_fatti); //non so se funziona corettamente (ma non serve)
            $win_lose_trasferta = $this->win_lose($trasferta_fatti, $casa_fatti); //non so se funziona corettamente (ma non serve)
            //-------------------------------

            $is_goal_casa = $single['IsGoalCasa'];
            $is_goal_trasferta = $single['IsGoalTrasferta'];

            //casa
            $points[$giornata]['squadre'][$den_casa]['id'] = $casa_id;
            $points[$giornata]['squadre'][$den_casa]['nulle'] = $casa_nulle;


            $points[$giornata]['squadre'][$den_casa]['goal_fatti'] = $casa_fatti;
            $points[$giornata]['squadre'][$den_casa]['goal_subiti'] = $trasferta_fatti;
            $points[$giornata]['squadre'][$den_casa]['is_goal'] = $is_goal_casa || $is_goal_trasferta; // mi serve per capire se è NULL
            $points[$giornata]['squadre'][$den_casa]['partita'] = $win_lose_casa;
            /* $points[$giornata]['squadre'][$den_casa]['punti_penalizzazione'] = $casa_punti_penalizzazione; */

            //GIUSEPPE 2019-02-23--
            $points[$giornata]['squadre'][$den_casa]['coppa_disciplina'] = $this->read_causal_result($casa_fatti, $trasferta_fatti, $casa_causale_disciplina, $casa_disciplina);
            $points[$giornata]['squadre'][$den_casa]['causale_coppa_disciplina'] = $casa_causale_disciplina;
            //---------------------




            $points[$giornata]['squadre'][$den_casa]['ammonizioni'] = $casa_ammonizioni;
            $points[$giornata]['squadre'][$den_casa]['espulsioni'] = $casa_espulsioni;
            $points[$giornata]['squadre'][$den_casa]['disciplinari'] = $casa_disciplinari;



            //trasferta
            $points[$giornata]['squadre'][$den_trasf]['id'] = $traferta_id;
            $points[$giornata]['squadre'][$den_trasf]['nulle'] = $traferta_nulle;



            $points[$giornata]['squadre'][$den_trasf]['goal_fatti'] = $trasferta_fatti;
            $points[$giornata]['squadre'][$den_trasf]['goal_subiti'] = $casa_fatti;
            $points[$giornata]['squadre'][$den_trasf]['is_goal'] = $is_goal_trasferta || $is_goal_casa; // mi serve per capire se è NULL
            $points[$giornata]['squadre'][$den_trasf]['partita'] = $win_lose_trasferta;
            /* $points[$giornata]['squadre'][$den_trasf]['punti_penalizzazione'] = $trasferta_punti_penalizzazione; */

            //GIUSEPPE 2019-02-23--
            $points[$giornata]['squadre'][$den_trasf]['coppa_disciplina'] = $this->read_causal_result($trasferta_fatti, $casa_fatti, $casa_causale_disciplina, $casa_disciplina);
            $points[$giornata]['squadre'][$den_trasf]['causale_coppa_disciplina'] = $trasferta_causale_disciplina;
            //---------------------




            $points[$giornata]['squadre'][$den_trasf]['ammonizioni'] = $trasferta_ammonizioni;
            $points[$giornata]['squadre'][$den_trasf]['espulsioni'] = $trasferta_espulsioni;
            $points[$giornata]['squadre'][$den_trasf]['disciplinari'] = $trasferta_disciplinari;
        }


        foreach ($all_teams_temp as $team)
        {
            $all_teams[] = $team;
        }
        
        //file_put_contents("points.txt", print_r($points, true));
        
        $points = $this->points_teams($points, $all_teams);



        //print_r($points);
        //echo json_encode($points);

        return array('points' => $points, 'all_teams' => $this->sort_teams($all_teams), 'nextDay' => $nextDay);
    }



   //GIUSEPPE 2019-02-23--
    private function read_causal_result($goal_squadra, $goal_avversario, $causal, $discipline_points)
    {
        $res = '';
        $win_lose = '';

        if ((int) $goal_squadra > (int) $goal_avversario)
        {
            $win_lose = 'vinta';
        }
        if ((int) $goal_squadra < (int) $goal_avversario)
        {
            $win_lose = 'persa';
        }

        if ($causal === '')
        {
            $res = 0;
        }
        else if ((int) $causal === 2 || (int) $causal === 9 || (int) $causal === 10)
        {
            $res = $discipline_points;
        }
        else
        {
            if ($win_lose === 'vinta')
            {
                $res = 0;
            }
            else if ($win_lose === 'persa')
            {
                $res = $discipline_points;
            }
        }

        return $res;
    }





    function sort_teams($all_teams)
    {

        sort($all_teams);

        reset($all_teams);

        return $all_teams;
    }





    function win_lose($squadra, $avversario)
    {

        if (((int) $squadra - (int) $avversario) < 0)
        {
            return "persa";
        }
        else if (((int) $squadra - (int) $avversario) > 0)
        {
            return "vinta";
        }
        else if (((int) $squadra === (int) $avversario))
        {
            return "pari";
        }
    }





    function points_teams($points, $all_teams)
    {
        //GIUSEPPE 2020-01
        session_start();
        //---------------


        $classPage = $this->className($_SERVER["SERVER_NAME"]);

        $nameClass = $classPage["Name"];

        $point_for_team = array();

        if ($nameClass == "primary" || $nameClass == "secondary")
        {
            $result_of_match = array('persa' => 0, 'pari' => 1, 'vinta' => 3);

            //GIUSEPPE 2020-01
            if ($_SESSION['campionati']['sport'] == "BASKET")
            {
                $result_of_match = array('persa' => 0, 'pari' => 1, 'vinta' => 2);
            }
            //--------------- 

            $win_lose = array('vinta' => 1, 'persa' => 0, 'pari' => 0);

            $lose_win = array('vinta' => 0, 'persa' => 1, 'pari' => 0);
        }
        elseif ($nameClass == "quaternary")
        {
            /* $result_of_match = array('persa' => 1, 'vinta' => 3); */

            $win_lose = array('vinta' => 1, 'persa' => 0);

            $lose_win = array('vinta' => 0, 'persa' => 1);
        }



        foreach ($all_teams as $single_team)
        {
            $point_for_team[$single_team]['punti'] = 0;
            $point_for_team[$single_team]['giocate'] = 0;
            $point_for_team[$single_team]['goal_totali_fatti'] = 0;
            $point_for_team[$single_team]['goal_totali_subiti'] = 0;
            $point_for_team[$single_team]['totali_vinte'] = 0;
            $point_for_team[$single_team]['totali_perse'] = 0;
            $point_for_team[$single_team]['coppa_disciplina'] = 0;
        }



        foreach ($points as $giornata => $point)
        {

            foreach ($point['squadre'] as $sq => $squadra)
            {
                //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.
                $causale = $squadra['causale_coppa_disciplina'];

                //GIUSEPPE 2018-10-30 rimodulazione dei punti tennis
                if ($nameClass == "primary" || $nameClass == "secondary")
                {
                    $point_for_team[$sq]['punti'] += $this->calcola_valori($result_of_match[$squadra['partita']], $causale);
                    $point_for_team[$sq]['ammonizioni'] += $this->calcola_valori($squadra['ammonizioni']);
                    $point_for_team[$sq]['espulsioni'] += $this->calcola_valori(3 * ($squadra['espulsioni']));
                    $point_for_team[$sq]['disciplinari'] += $this->calcola_valori($squadra['disciplinari']);
                }
                elseif ($nameClass == "quaternary")
                {
//                    $point_for_team[$sq]['punti'] += $squadra['goal_fatti'];
                    $point_for_team[$sq]['punti'] += $this->calcola_valori($squadra['goal_fatti'], $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.
                }
                // --------------------------------------------------------

                $point_for_team[$sq]['giocate'] += $this->calcola_valori(1, $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.

                $point_for_team[$sq]['goal_totali_fatti'] += $this->calcola_valori($squadra['goal_fatti'], $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.

                $point_for_team[$sq]['goal_totali_subiti'] += $this->calcola_valori($squadra['goal_subiti'], $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.

                $point_for_team[$sq]['totali_vinte'] += $this->calcola_valori($win_lose[$squadra['partita']], $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.

                $point_for_team[$sq]['totali_perse'] += $this->calcola_valori($lose_win[$squadra['partita']], $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.

                $points[$giornata]['squadre'][$sq]['punti'] = $point_for_team[$sq]['punti'];

                $points[$giornata]['squadre'][$sq]['giocate'] = $point_for_team[$sq]['giocate'];

                $points[$giornata]['squadre'][$sq]['goal_totali_fatti'] = $point_for_team[$sq]['goal_totali_fatti'];

                $points[$giornata]['squadre'][$sq]['goal_totali_subiti'] = $point_for_team[$sq]['goal_totali_subiti'];

                $points[$giornata]['squadre'][$sq]['totali_vinte'] = $point_for_team[$sq]['totali_vinte'];

                $points[$giornata]['squadre'][$sq]['totali_perse'] = $point_for_team[$sq]['totali_perse'];

                $points[$giornata]['squadre'][$sq]['nulle'] = $point_for_team[$sq]['giocate'] - ($point_for_team[$sq]['totali_vinte'] + $point_for_team[$sq]['totali_perse']);

                $points[$giornata]['squadre'][$sq]['coppa_disciplina'] += $this->calcola_valori(($point_for_team[$sq]['ammonizioni'] + $point_for_team[$sq]['espulsioni'] + $point_for_team[$sq]['disciplinari']), $causale); //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.;
            }
        }

        return $points;
    }



    /* //GIUSEPPE 2020-03-05 *********************************************** */

    //GIUSEPPE 2020-03-05 analisi del tipo di causale - per N.D. e RINV.
    private function calcola_valori($valore, $causale)
    {
        if ((int) $causale == 2 || (int) $causale == 3)
        {
            $valore = 0;
        }

        return $valore;
    }




    /*     * ****************************************************************** */








    function filterRankings($champ_id, $half_id, $squadra_id = 0, $giornata = 0)
    {

        $array_giornate = $this->ranking($champ_id, $half_id); // viene eseguita la query
        //print_r($array_giornate);
        //echo json_encode($array_giornate);

        $result = $this->filtra($array_giornate);

        //print_r($result);

        $giornate = $result['points'];

        $nextDay = $result['nextDay'];

        $all_teams = $result['all_teams'];

        if ($giornata != 0)
        {
            $nextDay = $giornata;
        }


        $this->set('giornate', $giornate);

        $this->set('nextDay', $nextDay);

        $this->set('all_teams', $all_teams);

        return array("squadre" => $all_teams, "classifica" => $giornate);
    }




    function teamGames($champ_id, $half_id) /* //GIUSEPPE 2018-06-20 */
    {
        $res = array();
        $sql = "SELECT 

                        SquadreCampionati.SquadraCampionato
                      , SquadreCampionati.Squadra 
                      , Squadre.Denominazione

                      FROM `SquadreCampionati`

                      INNER JOIN Squadre
                      ON SquadreCampionati.Squadra = Squadre.Squadra

                      WHERE 
                      
                      Squadre.SquadraServizio = 0
                      AND
                      Campionato = $champ_id 
                      AND
                      GironeCampionato = $half_id";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $res[$row['SquadraCampionato']] = $row;
            }
        }

        return $res;

    }




   function next_days($champ_id, $half_id, $day) /* //GIUSEPPE 2018-06-20 */
    {
        $res = array();
        $settimana = array(1 => "Lunedì", 2 => "Martedì", 3 => "Mercoledì", 4 => "Giovedì", 5 => "Venerdì", 6 => "Sabato", 7 => "Domenica");
        $day_2 = $day + 1;
        $day_3 = $day + 2;

        $sql = "SELECT 

                    Calendari.Calendario
                    ,Calendari.Giornata
                    ,Calendari.Data
                    ,Calendari.Ora
                    ,Calendari.Casa as CasaCampionato
                    ,sc_c.Squadra
                    ,s_c.Denominazione as SquadraCasa
                    ,Calendari.Trasferta as TrasfertaCampionato
                    ,sc_t.Squadra
                    ,s_t.Denominazione as SquadraTrasferta
                    ,Calendari.Campo
                    ,(SELECT Campi.Descrizione FROM Campi WHERE Campi.Campo = Calendari.Campo) as CampoGioco
                    ,Campionati.Italiana

                    FROM `Calendari`

                    INNER JOIN SquadreCampionati sc_c
                    ON sc_c.SquadraCampionato = Calendari.Casa

                    INNER JOIN SquadreCampionati sc_t
                    ON sc_t.SquadraCampionato = Calendari.Trasferta

                    INNER JOIN Squadre s_c
                    ON s_c.Squadra = sc_c.Squadra

                    INNER JOIN Squadre s_t
                    ON s_t.Squadra = sc_t.Squadra


                    INNER JOIN Campionati
                    ON Calendari.Campionato = Campionati.Campionato

                    WHERE  

                    Calendari.Campionato = $champ_id
                    AND 
                    Calendari.GironeCampionato = $half_id
                    AND
                    (Calendari.Giornata = $day_2 OR Calendari.Giornata = $day_3)

                    ORDER BY CONCAT(Calendari.Data,' ',Calendari.Ora) ASC";


        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                // $res[$row['SquadraCampionato']] = $row;



                if (isset($row['Giornata']))
                {
                    $d = explode("-", $row['Data']);


                    $row['Giorno'] = $settimana[date("N", mktime(0, 0, 0, $d[1], $d[2], $d[0]))];


                    if ($row['Giornata'] == $day_2)
                        $res['next_1'][] = $row;

                    if ($row['Giornata'] == $day_3)
                        $res['next_2'][] = $row;
                }
            }
        }
        return $res;

    }


 
    

    function getUniqueGiornate($giornate)
    {

        $giornate_arr = array();

        foreach ($giornate as $giornata)
        {
            $giornate_arr[$giornata['Match']['Giornata']] = $giornata;
        }

        unset($giornate);
        $giornate = $giornate_arr;

        return $giornate;

    }





    ////////////////////////////////////////////////////////////////////////////////
    // GIUSEPPE 2017-22-08 /////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////

    function filterCalendar($champ_id, $half_id)/* qui abbiamo max 2 accessi al database */
    {

        $classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

        $nameClass = $classPage["Name"];




        $giornate = $this->read_giornate($champ_id, $half_id);

        $nextDay = 1;

        $date_giornate = array();

        $avversari_temp = array();

        $avversari = array();



        $num_gare = count($giornate);

        $num_giornate = $giornate[$num_gare - 1]["Calendari"]["Giornata"];

        //print_r($giornate); exit;

        $now = date("Y-m-d");

        foreach ($giornate as $giornata)
        {

            //$date_giornate[$giornata["Calendari"]["Giornata"]] = $giornata["Calendari"]["Data"];
            $date_giornate[$giornata["Calendari"]["Giornata"]] = $giornata['0']["Data"];

            if ($giornata['0']['SquadraCasaServizio'] == 0)
            {
                $avversari_temp[$giornata['Calendari']['Casa']] = $giornata['0']['SquadraCasa'];
            }


            if ($giornata['0']['SquadraTrasfertaServizio'] == 0)
            {
                $avversari_temp[$giornata['Calendari']['Trasferta']] = $giornata['0']['SquadraTrasferta'];
            }


            $data_giornata = $giornata['0']["Data"];

            $split_data = explode("/", $data_giornata);

            $data_giornata = $split_data[2] . "-" . $split_data[1] . "-" . $split_data[0];

            if ($now >= $data_giornata)
            {
                $nextDay = $giornata["Calendari"]['Giornata'];
            }
        }

        $avversari = $this->ordina_squadre($avversari_temp);

        if ($nameClass == "quaternary") /* set tennis */
        {
            $giornate = $this->scompatta_set_tennis($giornate);
        }

        //echo json_encode($giornate);
        //print_r($giornate);

        $this->set('num_giornate', $num_giornate);

        $this->set('giornate', $giornate);

        $this->set('nextDay', $nextDay);

        $this->set('avversari', $avversari);

    }



    function read_giornate($champ_id, $half_id)
    {

        $classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

        $nameClass = $classPage["Name"];

        $set_casa = "";

        $set_trasferta = "";

        if ($nameClass == "quaternary")
        {
            $set_casa = ",(SELECT GoalPartite.SetTennis FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.casa) as SetCasa";

            $set_trasferta = ",(SELECT GoalPartite.SetTennis FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.trasferta) as SetTrasferta";
        }

        $query = "SELECT
                     Calendari.Calendario
                    ,Calendari.Campionato
                    ,Calendari.GironeCampionato
                    ,Calendari.Giornata
                    ,Calendari.Partita
                    ,DATE_FORMAT(Calendari.Data, '%d/%m/%Y') as Data
                    ,Calendari.Ora
                    ,Calendari.Casa
                    ,(SELECT  Squadre.Denominazione FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Casa) as SquadraCasa
                    ,(SELECT  Squadre.Squadra FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Casa) as Casa_Id
                    ,(SELECT  Squadre.SquadraServizio FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Casa) as SquadraCasaServizio                    
                    ,(SELECT SUM(GoalPartite.Goal) FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.casa) as GoalCasa
                    ,(SELECT SUM(GoalPartite.Autogoal) FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.casa) as AutoGoalCasa
                    $set_casa
                    ,Calendari.Trasferta
                    ,(SELECT  Squadre.Denominazione FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Trasferta) as SquadraTrasferta
                    ,(SELECT  Squadre.Squadra FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Trasferta) as Trasferta_Id
                    ,(SELECT  Squadre.SquadraServizio FROM SquadreCampionati INNER JOIN Squadre ON Squadre.Squadra = SquadreCampionati.Squadra WHERE SquadreCampionati.SquadraCampionato = Calendari.Trasferta) as SquadraTrasfertaServizio
                    ,(SELECT SUM(GoalPartite.Goal) FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.trasferta) as GoalTrasferta
                    ,(SELECT SUM(GoalPartite.Autogoal) FROM GoalPartite WHERE GoalPartite.Calendario = Calendari.Calendario AND GoalPartite.SquadraCampionato = Calendari.trasferta) as AutoGoalTrasferta
                    $set_trasferta
                    ,Calendari.Campo
                    ,Campi.Descrizione as NomeCampo
                    ,Calendari.NomeGara
                    ,(SELECT CausaliRisultato.Descrizione FROM CausaliRisultato WHERE CausaliRisultato.CausaleRisultato = Calendari.CausaleRisultato) as Note
                    ,Campi.isMidland
                    ,Campionati.Italiana
                FROM 
                    `Calendari`
                    
                INNER JOIN Campi
                ON Campi.Campo = Calendari.Campo
                    
                INNER JOIN Campionati
                ON Calendari.Campionato = Campionati.Campionato
                

                    WHERE Calendari.Campionato = '" . $champ_id . "' 
                    AND Calendari.GironeCampionato = '" . $half_id . "' 
                  
                ORDER BY `Calendari`.`Giornata` ASC , `Calendari`.`Partita` ASC  ,Calendari.Ora ASC";

        $giornate = $this->Match->query($query);
        //echo $query;
        //print_r($giornate);
        
        return $giornate;

    }



    function ordina_squadre($avversari)
    {
        $temp = array();

        $array_avversari = array();


        foreach ($avversari as $i => $avversario)
        {
            $array_avversari[] = array('id' => $i, 'squadra' => $avversario);
        }

        /* ordinamento bubble sort */
        do
        {
            $scambio = false;

            for ($i = 0; $i < count($array_avversari) - 1; $i++)
            {
                if ($array_avversari[$i]['squadra'] > $array_avversari[$i + 1]['squadra'])
                {
                    $temp = $array_avversari[$i];
                    $array_avversari[$i] = $array_avversari[$i + 1];
                    $array_avversari[$i + 1] = $temp;
                    $scambio = true;
                }
            }
        }
        while ($scambio);

        return $array_avversari;
    }



    function scompatta_set_tennis($giornate)
    {
        $set = array();

        $id_athletes = array();


        foreach ($giornate as $i => $giornata)
        {
            $obj_casa = json_decode($giornata['0']['SetCasa'], true);

            $obj_tras = json_decode($giornata['0']['SetTrasferta'], true);

            $set['casa'][] = $obj_casa;

            $set['trasferta'][] = $obj_tras;

            $giornate[$i]['0']['SetCasaObj'] = $obj_casa;

            $giornate[$i]['0']['SetTrasObj'] = $obj_tras;
        }


        foreach ($set['casa'] as $single) /* estraggo tutti gli id degli atleti dai set */
        {
            foreach ($single['athletes'] as $a)
            {
//                $id_athletes[$a] = "Atleta = " . $a;
                $id_athletes[$a] = "( Atleta = '{$a}' )" ;  //GIUSEPPE 2020-04-07
            }
        }

        foreach ($set['trasferta'] as $single) /* estraggo tutti gli id degli atleti dai set */
        {
            foreach ($single['athletes'] as $a)
            {
//                $id_athletes[$a] = "Atleta = " . $a;
                $id_athletes[$a] = "( Atleta = '{$a}' )";  //GIUSEPPE 2020-04-07
            }
        }


//        $id_athletes[0] = "Atleta = 0";  
        $id_athletes[0] = "( Atleta = '0' )";//GIUSEPPE 2020-04-07

        $filter = implode(" OR ", $id_athletes);

        $atleti = array();

        $sql = "SELECT Atleta as id, CONCAT(Cognome,' ',Nome) as anagrafica FROM `Atleti` WHERE " . $filter;

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $atleti[$row["id"]] = $row["anagrafica"];
            }
        }

        $atleti[0] = " <strong>! not sel.</strong>"; /* nel caso non sia stao inserito l'atleta */

        return $this->assegna_punti_atleti($giornate, $atleti); /* $giornate con i risultati a display */
    }



   function assegna_punti_atleti($giornate, $atleti)
    {

        foreach ($giornate as $i => $giornata)
        {

            $set_partita = array();

            $atleta_casa_s1 = $atleti[$giornata[0]['SetCasaObj']['athletes']['casa_s1']];
            $atleta_casa_s2 = $atleti[$giornata[0]['SetCasaObj']['athletes']['casa_s2']];
            $atleta_casa_d1 = $atleti[$giornata[0]['SetCasaObj']['athletes']['casa_d1']];
            $atleta_casa_d2 = $atleti[$giornata[0]['SetCasaObj']['athletes']['casa_d2']];
            $atleta_casa_dd = $atleta_casa_d1 . " - " . $atleta_casa_d2;
            if ($atleta_casa_d1 > $atleta_casa_d2)
            {
                $atleta_casa_dd = $atleta_casa_d2 . " - " . $atleta_casa_d1;
            }

            $punti_casa_s1 = $giornata[0]['SetCasaObj']['points']['s_1_1'] . " / " . $giornata[0]['SetCasaObj']['points']['s_1_2'] . " / " . $giornata[0]['SetCasaObj']['points']['s_1_3'];
            $punti_casa_s2 = $giornata[0]['SetCasaObj']['points']['s_3_1'] . " / " . $giornata[0]['SetCasaObj']['points']['s_3_2'] . " / " . $giornata[0]['SetCasaObj']['points']['s_3_3'];
            $punti_casa_dd = $giornata[0]['SetCasaObj']['points']['s_5_1'] . " / " . $giornata[0]['SetCasaObj']['points']['s_5_2'] . " / " . $giornata[0]['SetCasaObj']['points']['s_5_3'];


            $atleta_tras_s1 = $atleti[$giornata[0]['SetTrasObj']['athletes']['casa_s1']];
            $atleta_tras_s2 = $atleti[$giornata[0]['SetTrasObj']['athletes']['casa_s2']];
            $atleta_tras_d1 = $atleti[$giornata[0]['SetTrasObj']['athletes']['casa_d1']];
            $atleta_tras_d2 = $atleti[$giornata[0]['SetTrasObj']['athletes']['casa_d2']];
            $atleta_tras_dd = $atleta_tras_d1 . " - " . $atleta_tras_d2;
            if ($atleta_tras_d1 > $atleta_tras_d2)
            {
                $atleta_tras_dd = $atleta_tras_d2 . " - " . $atleta_tras_d1;
            }

            $punti_tras_s1 = $giornata[0]['SetTrasObj']['points']['s_1_1'] . " / " . $giornata[0]['SetTrasObj']['points']['s_1_2'] . " / " . $giornata[0]['SetTrasObj']['points']['s_1_3'];
            $punti_tras_s2 = $giornata[0]['SetTrasObj']['points']['s_3_1'] . " / " . $giornata[0]['SetTrasObj']['points']['s_3_2'] . " / " . $giornata[0]['SetTrasObj']['points']['s_3_3'];
            $punti_tras_dd = $giornata[0]['SetTrasObj']['points']['s_5_1'] . " / " . $giornata[0]['SetTrasObj']['points']['s_5_2'] . " / " . $giornata[0]['SetTrasObj']['points']['s_5_3'];



            $sing_1 = "<small>" . $atleta_casa_s1 . ":</small><br /><small>" . $punti_casa_s1 . "</small>" . "<br><small>" . $atleta_tras_s1 . ":</small><br /><small>" . $punti_tras_s1 . "</small>";
            $sing_2 = "<small>" . $atleta_casa_s2 . ":</small><br /><small>" . $punti_casa_s2 . "</small>" . "<br><small>" . $atleta_tras_s2 . ":</small><br /><small>" . $punti_tras_s2 . "</small>";
            $doppio = "<small>" . $atleta_casa_dd . ":</small><br /><small>" . $punti_casa_dd . "</small>" . "<br><small>" . $atleta_tras_dd . ":</small><br /><small>" . $punti_tras_dd . "</small>";

            $set_partita['sing_1'] = $sing_1;
            $set_partita['sing_2'] = $sing_2;
            $set_partita['doppio'] = $doppio;

            $giornate[$i][0]['SetPartita'] = $set_partita;
        }

        return $giornate;
    }


    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////




    function filterMarks($champ_id, $half_id)
    {

        $giornate = $this
                ->Match
                ->find('all', array(
            'fields' => array(
                'DISTINCT Match.Giornata',
                'Campionati.Nome',
                'Half.Descrizione'
            ),
            'conditions' => array(
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ),
            'order' => 'Match.Giornata ASC'
        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $prossima_giornata = $this
                ->Match
                ->find('first', array(
            'conditions' => array(
                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')),
                    date("Y-m-d", strtotime('next Saturday'))
                ),
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ),
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            )
        ));

        if (isset($prossima_giornata['Match']['Giornata']))
            $nextDay = $prossima_giornata['Match']['Giornata'];
        else
            $nextDay = (isset($giornate[count($giornate)]['Match']['Giornata'])) ? $giornate[count($giornate)]['Match']['Giornata'] : 0;

        //Calcolo marcatori per ogni giornata
        $marcatori = array();

        foreach ($giornate as $gg)
        {

            $giornata = $gg['Match']['Giornata'];

            $marcatori[$giornata] = $this
                    ->Matchgoal
                    ->query(
                    "SELECT sc.SquadraCampionato as IdSquadra, s.Denominazione as NomeSquadra, CONCAT(a.Cognome,' ',a.Nome) as anagrafica, SUM(g.Goal) as goals
                    FROM Calendari c, GoalPartite g
                    LEFT JOIN SquadreCampionati sc ON (sc.`SquadraCampionato` = g.`SquadraCampionato`)
                    LEFT JOIN Squadre s ON (sc.`Squadra` = s.`Squadra`)
                    LEFT JOIN Atleti a ON (a.`Atleta` = g.`Atleta`)
                    WHERE g.Calendario = c.Calendario
                    AND c.Campionato = '$champ_id'
                    AND c.GironeCampionato = '$half_id'
                    AND g.Goal > 0
                    AND g.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND g.Atleta != 0
                    GROUP BY g.Atleta ORDER BY goals DESC"
            );

            /*

              $marcatori[$giornata] = $this->Matchgoal->query(

              "SELECT
              (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
              (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
              (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
              SUM(GoalPartite.Goal) as goals FROM GoalPartite
              WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
              AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND GoalPartite.Atleta != 0
              GROUP BY GoalPartite.Atleta ORDER BY goals DESC LIMIT 15"

              );

             */
        }

        $this->set('nextDay', $nextDay);
        $this->set('giornate', $giornate);
        $this->set('marcatori', $marcatori);

        return $marcatori; //GIUSEPPE 2018-05-09
    }




    function filterDiscipline($champ_id, $half_id)
    {

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Match.Data',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')) ,
                    date("Y-m-d", strtotime('next Saturday'))
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            )
        ));

        if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
        $nextDay = (isset($giornate[count($giornate) ]['Match']['Giornata'])) ? $giornate[count($giornate) ]['Match']['Giornata'] : 0;

        /*  HACK GIORNATA DI RIFERIMENTO */
        $infoGiornate = $this->getGiornataInCorso($giornate);

        $giornata_riferimento = $infoGiornate['giornata_riferimento'];
        $giornata_riferimento_set = $infoGiornate['giornata_riferimento_set'];

        $nextDay = $giornata_riferimento;
        $nextDay_real = $giornata_riferimento;
        /* END HACK GIORNATA DI RIFERIMENTO */

        if ($giornata_riferimento_set == 1) $nextDay--;

        $disciplinari = $this
            ->Disciplinari
            ->find('all', array(
            'conditions' => array(
                'Disciplinari.Calendario IN (
                SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = ' . $nextDay . ' AND Calendari.GironeCampionato = ' . $half_id . '
                )'
            )
        ));

        //debug($disciplinari);
        $this->set('nextDay', $nextDay);
        $this->set('disciplinari', $disciplinari);

    }

    function diffidatiCrontab()
    {

        $this->layout = null;

        Configure::write('debug', 2);

        $campionati = $this
            ->Campionati
            ->find('all',

        array(

            'fields' => array(
                'Campionati.Campionato'
            ) ,
            'conditions' => array(

                //'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale
                'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)', // solo locale
                'Campionati.InCorso' => 'Si',
                'Campionati.group_id' => 1,

            ) ,
            'order' => array(
                'Campionati.Nome ASC'
            ) ,

        )
);

        foreach ($campionati as $champ)
        {

            $campionato = $champ['Campionati']['Campionato'];

            $champ['Half'] = array();
            $champ['Half'] = $this
                ->Half
                ->find('all', array(
                'conditions' => array(
                    'Half.Campionato' => $campionato
                ) ,
                'order' => 'Half.Descrizione ASC'
            ));

            //debug($champ['Half']);
            foreach ($champ['Half'] as $girone)
            {

                $girone = $girone['Half']['GironeCampionato'];

                $this->filterDisciplinariCron($campionato, $girone);

            }

        }

        return;

    }

    function filterDisciplinariCron($champ_id, $half_id)
    {

        Configure::Write('debug', 2);

        $this->layout = null;
        $this->autoRender = false;

        $date_end = date("Y-m-d");
        //$date_end = '2015-10-31';
        //$date_end           = date("Y-m-d", strtotime(date("2012-12-8")));
        $data_end_strtotime = strtotime($date_end);
        $date_start = date("Y-m-d", strtotime('last Saturday'));
        //$date_start         = date("Y-m-d", strtotime(date("2015-10-26")));
        $dbo = $this
            ->Match
            ->getDatasource();
        $dbo->fullDebug = true;

        $giornate_riferimento = $this
            ->Match
            ->find('all',

        array(

            'conditions' => array(

                //'Match.Data between ? AND ?' => array(date("Y-m-d 12:30:00",strtotime('last Saturday')), date("Y-m-d 12:30:00",strtotime('next Saturday'))),
                //array('Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday')))),
                //array('Match.Data >=' => date("Y-m-d 00:00:00")),
                'Match.Data between ? AND ?' => array(
                    $date_start,
                    $date_end
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => array(
                'DISTINCT Match.Giornata',
                'Match.Data'
            ) ,
            'order' => array(
                'Match.Giornata ASC'
            ) ,
            'recursive' => - 1
        )
);
        //SELECT DISTINCT `Match`.`Giornata`, `Match`.`Data` FROM `Calendari` AS `Match`   WHERE `Match`.`Data` between (SELECT Match2.Data FROM Calendari AS Match2 WHERE Match2.GironeCampionato = 1302 and Match2.Campionato = 471 AND Match2.Data < '2012-06-30' ORDER BY Match2.Data DESC LIMIT 1) AND '2012-06-30' AND `Match`.`Campionato` = 471 AND `Match`.`GironeCampionato` = 1302   ORDER BY `Match`.`Giornata` ASC
        /*              $logs = $dbo->_queriesLog;
        
        debug($dbo->_queriesLog[0]['query']);
        
        exit;
        */

        $data_inizio = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => array(
                'Match.Giornata',
                'Match.Data'
            ) ,
            'order' => array(
                'Match.Data ASC'
            ) ,
            'recursive' => - 1
        )
);

        $data_fine = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => array(
                'Match.Giornata',
                'Match.Data'
            ) ,
            'order' => array(
                'Match.Data DESC'
            ) ,
            'recursive' => - 1
        )
);

        //debug($prossima_giornata);
        /*
        debug('Giornata inizio torneo: ' . $data_inizio['Match']['Giornata']);
        debug('Data inizio torneo: ' . $data_inizio['Match']['Data']);
        
        debug('Giornata fine torneo: ' . $data_fine['Match']['Giornata']);
        debug('Data fine torneo: ' . $data_fine['Match']['Data']);
        */

        //debug($giornate_riferimento);
        $giornate = array();

        debug('ID campionato : ' . $champ_id . ' ID girone: ' . $half_id . ' inizio riferimento: ' . $date_start);

        if (empty($giornate_riferimento))
        {

            if ($data_end_strtotime < strtotime($data_inizio['Match']['Data']))
            {

                $giornate = array(
                    0 => $data_inizio
                );

            }
            elseif ($data_end_strtotime > strtotime($data_fine['Match']['Data']))
            {

                $giornate = array(
                    0 => $data_fine
                );

            }
            else
            {

                //Vuol dire che non ci sono partite nella settimana.
                //Finire con questa soluzione.
                $giornate_riferimento = $this
                    ->Match
                    ->find('all',

                array(

                    'conditions' => array(

                        //'Match.Data between ? AND ?' => array(date("Y-m-d 12:30:00",strtotime('last Saturday')), date("Y-m-d 12:30:00",strtotime('next Saturday'))),
                        //array('Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday')))),
                        //array('Match.Data >=' => date("Y-m-d 00:00:00")),
                        'Match.Data between (SELECT Match2.Data FROM Calendari AS Match2 WHERE Match2.GironeCampionato = ' . $half_id . ' and Match2.Campionato = ' . $champ_id . ' AND Match2.Data < \'' . $date_end . '\' ORDER BY Match2.Data DESC LIMIT 1) AND \'' . $date_end . '\'',
                        'Match.Campionato' => $champ_id,
                        'Match.GironeCampionato' => $half_id
                    ) ,
                    'fields' => array(
                        'DISTINCT Match.Giornata',
                        'Match.Data'
                    ) ,
                    'order' => array(
                        'Match.Giornata ASC'
                    ) ,
                    'recursive' => - 1
                )
);

                $giornate = $giornate_riferimento;

            }

        }
        else
        {

            $giornate = $giornate_riferimento;

        }

        /* END HACK GIORNATA DI RIFERIMENTO */

        //debug($nextDay);
        $days = array();
        foreach ($giornate as $giornata)
        {

            $days[$giornata['Match']['Giornata']] = $giornata;

        }

        $disciplinari = array();

        foreach ($days as $giornata)
        {

            $partite = $this
                ->Match
                ->find('all',

            array(

                'conditions' => array(

                    'Match.Giornata' => $giornata['Match']['Giornata'],
                    'Match.Campionato' => $champ_id,
                    'Match.GironeCampionato' => $half_id
                ) ,
                'fields' => array(
                    'Match.Calendario'
                ) ,
                'order' => array(
                    'Match.Giornata DESC'
                ) ,
                'recursive' => - 1
            ));

            foreach ($partite as $partita)
            {

                $disc = $this->getSqualificatiByCalendario($partita['Match']['Calendario']);

                foreach ($disc['squalificati'] as $tmp)
                {
                    $tmp['Giornata'] = $giornata['Match']['Giornata'];
                    $disciplinari['squalificati'][$tmp['id_atleta']] = $tmp;
                }
                foreach ($disc['espulsi'] as $tmp)
                {
                    $tmp['Giornata'] = $giornata['Match']['Giornata'];
                    $disciplinari['espulsi'][$tmp['IdSquadra'] . '-' . $tmp['Anagrafica']] = $tmp;
                }
                foreach ($disc['diffidati'] as $tmp)
                {
                    $tmp['Giornata'] = $giornata['Match']['Giornata'];
                    $disciplinari['diffidati'][$tmp['id_atleta']] = $tmp;
                }

            }

        }

        file_put_contents(APP . "/webroot/files/json/disciplinari/disciplinare_" . $champ_id . "_" . $half_id . ".json", json_encode($disciplinari));

    }

    function testDisciplinari($champ_id = 443, $half_id = 1214)
    {

        $this->layout = null;
        $this->autoRender = false;

        Configure::write('debug', 2);

        $campionati = $this
            ->Campionati
            ->find('all',

        array(

            'fields' => array(
                'Campionati.Campionato'
            ) ,
            'conditions' => array(

                //'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale
                'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)', // solo locale
                'Campionati.InCorso' => 'Si',
                'Campionati.group_id' => 1,

            ) ,
            'order' => array(
                'Campionati.Nome ASC'
            ) ,

        )
);

        foreach ($campionati as $champ)
        {

            $campionato = $champ['Campionati']['Campionato'];

            $champ['Half'] = array();
            $champ['Half'] = $this
                ->Half
                ->find('all', array(
                'conditions' => array(
                    'Half.Campionato' => $campionato
                ) ,
                'order' => 'Half.Descrizione ASC'
            ));

            foreach ($champ['Half'] as $girone)
            {

                $girone = $girone['Half']['GironeCampionato'];

                $disciplinari = json_decode(file_get_contents(APP . "/webroot/files/json_frontend/disciplinari/disciplinare_" . $campionato . "_" . $girone . ".json") , 1);

                debug($disciplinari);

            }

        }

    }

    function getDiffidati($champ_id, $half_id)
    {

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')) ,
                    date("Y-m-d", strtotime('next Saturday'))
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            )
        ));

        if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
        else $nextDay = (isset($giornate[count($giornate) ]['Match']['Giornata'])) ? $giornate[count($giornate) ]['Match']['Giornata'] : 0;

        //
        //Calcolo diffidati ed espulsi
        $diffidati = array();
        $espulsi = array();

        foreach ($giornate as $gg)
        {

            $giornata = $gg['Match']['Giornata'];

            if ($giornata > $nextDay) continue;

            $diffidati[$giornata] = $this
                ->Matchgoal
                ->query(

            "SELECT
                    (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
                    (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                    (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                    (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                    (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                    COUNT(*) as Ammonizioni FROM GoalPartite
                    WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                    AND GoalPartite.Ammonizione = 'Si'
                    AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
                    GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
);

            $espulsi[$giornata] = $this
                ->Matchgoal
                ->query(

            "SELECT
                    (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                    (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                    (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                    (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                    GoalPartite.EspulsioneGiornate,
                    GoalPartite.EspulsioneInizio,
                    GoalPartite.EspulsioneFine,
                    GoalPartite.Espulsione FROM GoalPartite
                    WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                    AND GoalPartite.Espulsione = 'Si'
                    AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata')
                    GROUP BY GoalPartite.Atleta ORDER By NomeSquadra"
);

        }

        file_put_contents(APP . "/webroot/files/json/diffidati_" . $champ_id . "_" . $half_id . ".json", json_encode($diffidati));
        file_put_contents(APP . "/webroot/files/json/espulsi_" . $champ_id . "_" . $half_id . ".json", json_encode($espulsi));

    }

    function filterTeambook($champ_id, $half_id, $squadra_id)
    {

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $squadra_info = $this
            ->SquadreCampionati
            ->find('first', array(
            'conditions' => array(
                'SquadreCampionati.SquadraCampionato' => $squadra_id,
            ) ,
            'recursive' => 0,
        ));

        $squadra = $squadra_info['SquadreCampionati']['Squadra'];
        $campionato = $this
            ->Campionati
            ->read(null, $champ_id);
        $anno = $campionato['Campionati']['AnnoSportivo'];

        $this->data = $this
            ->Teambook
            ->find('first', array(
            'conditions' => array(
                'Teambook.AnnoSportivo' => $anno,
                'Teambook.Squadra' => $squadra,
            ) ,
        ));

        $this
            ->Squadre
            ->set('Squadra', $this->data['Teambook']['Squadra']);
        $this->data['Teambook']['SquadraSearch'] = $this
            ->Squadre
            ->field('Denominazione');
        $this
            ->Teambook
            ->set($this->data);

        $squadra = $this->data['Teambook']['Squadra'];
        $anno = $this->data['Teambook']['AnnoSportivo'];

        // Gestione riepilogo //
        /*
        
        SELECT * FROM Atleti WHERE Atleti.Atleta IN
        (SELECT Annuario.Atleta FROM Annuario WHERE Annuario.SquadraCampionato IN
        (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.Squadra = $squadra)
        AND Annuario.AnnoSportivo = $anno
        )
        
        */
        $tesserati = $this
            ->Teambook
            ->query("

                SELECT * FROM Atleti,Annuario,SquadreCampionati WHERE

                Annuario.Atleta = Atleti.Atleta AND
                Annuario.AnnoSportivo = $anno AND
                SquadreCampionati.Squadra = $squadra AND
                SquadreCampionati.SquadraCampionato = Annuario.SquadraCampionato
                group by Annuario.Atleta
                order by Atleti.Cognome
                ");

        $disciplinari = $this
            ->Teambook
            ->query("

                SELECT *, SUM(Sanzione) as Debito
                FROM Disciplinari
                WHERE Disciplinari.SquadraCampionato = ANY(

                SELECT SquadreCampionati.SquadraCampionato
                FROM SquadreCampionati
                WHERE SquadreCampionati.Squadra = (
                SELECT AnnuarioSquadre.Squadra
                FROM AnnuarioSquadre
                WHERE AnnuarioSquadre.AnnoSportivo = $anno
                AND AnnuarioSquadre.Squadra = $squadra )
                ) GROUP BY Disciplinare

                ");

        $tot_debito_disciplinari = 0;

        foreach ($disciplinari as $k => $debito)
        {

            $match = $this
                ->Match
                ->findByCalendario($debito['Disciplinari']['Calendario']);
            if ($match['Campionati']['AnnoSportivo'] == $anno)
            {

                $tot_debito_disciplinari += $debito[0]['Debito'];

            }
            else
            {

                unset($disciplinari[$k]);

            }

        }

        $causali = $this
            ->Teambook
            ->query("

                SELECT *, SUM(CausaliRisultato.Sanzione) As Debito FROM Calendari, CausaliRisultato
                WHERE (SELECT AnnoSportivo FROM Campionati WHERE Campionati.Campionato = Calendari.Campionato) = $anno

                AND CausaliRisultato.CausaleRisultato = Calendari.CausaleRisultato
                AND CausaliRisultato.Sanzione > 0

                AND (

                Calendari.Casa = ANY(

                SELECT SquadreCampionati.SquadraCampionato
                FROM SquadreCampionati
                WHERE Squadra = $squadra

                ) OR

                Calendari.Trasferta = ANY(

                SELECT SquadreCampionati.SquadraCampionato
                FROM SquadreCampionati
                WHERE Squadra = $squadra

                )
                ) GROUP BY Calendari.Calendario

                ");

        $tot_debito_causali = 0;

        foreach ($causali as $k => $causale_debito)
        {

            $partita = $this
                ->Match
                ->findByCalendario($causale_debito['Calendari']['Calendario']);

            list($goalCasa, $goalTrasferta) = split('-', $partita['Match']['Risultato']);

            if ($goalCasa > $goalTrasferta)
            {

                if ($partita['Casa']['Squadra'] != $squadra)
                {

                    $tot_debito_causali += $partita['Causalresult']['Sanzione'];

                }
                else
                {

                    unset($causali[$k]);

                }

            }
            else
            {

                if ($partita['Trasferta']['Squadra'] != $squadra)
                {

                    $tot_debito_causali += $partita['Causalresult']['Sanzione'];

                }
                else
                {

                    unset($causali[$k]);

                }

            }

        }

        $tot_debito = $tot_debito_causali + $tot_debito_disciplinari;

        $this->set('tot_debito', $tot_debito);
        $this->set('causali', $causali);
        $this->set('disciplinari', $disciplinari);
        $this->set('tesserati', $tesserati);

        // End gestione riepilogo //
        
    }

    function filterComunication($champ_id, $half_id, $squadra_id)
    {

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')) ,
                    date("Y-m-d", strtotime('next Saturday'))
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            )
        ));

        if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
        else $nextDay = (isset($giornate[count($giornate) ]['Match']['Giornata'])) ? $giornate[count($giornate) ]['Match']['Giornata'] : 0;

        $prevDay = $nextDay - 1;

        $comunications = $this
            ->Comunication
            ->find('all', array(

            'conditions' => array(

                'Comunication.Giornata' => $prevDay,
                'Comunication.GironeCampionato' => $half_id

            ) ,

        ));

        $this->set('nextDay', $prevDay);
        $this->set('comunications', $comunications);

    }

    function filterDiffidati($champ_id, $half_id, $squadra_id)
    {

        $giornate = $this
            ->Match
            ->find('all', array(

            'fields' => array(
                'DISTINCT Match.Giornata',
                'Campionati.Nome',
                'Half.Descrizione'
            ) ,
            'conditions' => array(

                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id

            ) ,
            'order' => 'Match.Giornata ASC'

        ));

        $giornate = $this->getUniqueGiornate($giornate);

        $prossima_giornata = $this
            ->Match
            ->find('first',

        array(

            'conditions' => array(

                'Match.Data between ? AND ?' => array(
                    date("Y-m-d", strtotime('last Monday')) ,
                    date("Y-m-d", strtotime('next Saturday'))
                ) ,
                'Match.Campionato' => $champ_id,
                'Match.GironeCampionato' => $half_id
            ) ,
            'fields' => 'DISTINCT Match.Giornata',
            'order' => array(
                'Match.Giornata DESC'
            )
        ));

        if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
        else $nextDay = (isset($giornate[count($giornate) ]['Match']['Giornata'])) ? $giornate[count($giornate) ]['Match']['Giornata'] : 0;

        //Controllo se devo aggiornare i dati
        $giorno = date('w');

        $dodici = strtotime(date("Y-m-d") . " 12:30:00");
        $mezzanotte = strtotime(date("Y-m-d") . " 23:59:59");
        $adesso = time();

        if ($giorno == 123456)
        { // se  sabato //Condizione sempre negata per far in modo che legga solo da file.
            if ($adesso >= $dodici && $adesso <= $mezzanotte)
            {

                //Calcolo diffidati ed espulsi
                $diffidati = array();
                $espulsi = array();

                foreach ($giornate as $gg)
                {

                    $giornata = $gg['Match']['Giornata'];

                    if ($giornata > $nextDay) continue;

                    $diffidati[$giornata] = $this
                        ->Matchgoal
                        ->query(

                    "SELECT
                            (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
                            (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                            (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                            (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                            (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                            COUNT(*) as Ammonizioni FROM GoalPartite
                            WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                            AND GoalPartite.Ammonizione = 'Si'
                            AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
                            GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
);

                    $espulsi[$giornata] = $this
                        ->Matchgoal
                        ->query(

                    "SELECT
                            (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                            (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                            (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                            (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
                            GoalPartite.EspulsioneGiornate,
                            GoalPartite.EspulsioneInizio,
                            GoalPartite.EspulsioneFine,
                            GoalPartite.Espulsione FROM GoalPartite
                            WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
                            AND GoalPartite.Espulsione = 'Si'
                            AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata')
                            GROUP BY GoalPartite.Atleta ORDER By NomeSquadra"
);

                }

                file_put_contents(APP . "/webroot/files/json/diffidati_" . $champ_id . "_" . $half_id . ".json", json_encode($diffidati));
                file_put_contents(APP . "/webroot/files/json/espulsi_" . $champ_id . "_" . $half_id . ".json", json_encode($espulsi));

            }
            else
            {
                if (file_exists(APP . "/webroot/files/json_frontend/diffidati_" . $champ_id . "_" . $half_id . ".json"))
                {
                    $diffidati = json_decode(file_get_contents(APP . "/webroot/files/json/diffidati_" . $champ_id . "_" . $half_id . ".json") , 1);
                }
                if (file_exists(APP . "/webroot/files/json/espulsi_" . $champ_id . "_" . $half_id . ".json"))
                {
                    $espulsi = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi" . $champ_id . "_" . $half_id . ".json") , 1);
                }
            }

        }
        else
        {

            if (file_exists(APP . "/webroot/files/json/diffidati_" . $champ_id . "_" . $half_id . ".json"))
            {
                $diffidati = json_decode(file_get_contents(APP . "/webroot/files/json/diffidati_" . $champ_id . "_" . $half_id . ".json") , 1);
            }
            if (file_exists(APP . "/webroot/files/json/espulsi_" . $champ_id . "_" . $half_id . ".json"))
            {
                $espulsi = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi_" . $champ_id . "_" . $half_id . ".json") , 1);
            }
        }
        if (!isset($diffidati)) $diffidati = array();
        if (!isset($espulsi)) $espulsi = array();
        //
        $this->getEspulsiAmmoniti($champ_id, $half_id, $squadra_id);

        $this->set('nextDay', $nextDay);
        $this->set('giornate', $giornate);
        $this->set('diffidati', $diffidati);
        $this->set('espulsi', $espulsi);

    }

    function getEspulsiAmmoniti($campionato_id, $girone_id, $squadra_id)
    {

        $girone_ = $this
            ->Half
            ->findByGironecampionato($girone_id);

        $campionatoPrecedente = $girone_['Campionati']['CampionatoPrecedente'];
        $girone_id = $girone_['Half']['GironeCampionato'];

        /*
        $classifica_espulsi = $this->Ranking->query("
        SELECT
        (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
        (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
        (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id,
        COUNT(*) as Tot
        FROM GoalPartite
        WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
        AND GoalPartite.Espulsione = 'Si'
        GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
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
        GROUP BY GoalPartite.Atleta ORDER By Tot DESC
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
        
        $this->set('disciplinari', $disciplinari);
        
        */

        $disciplinari_campionato = array();

        $classifica_espulsi_campionato = $this
            ->Ranking
            ->query("
                SELECT
                (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id,
                COUNT(*) as Tot
                FROM GoalPartite
                WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
                AND GoalPartite.Espulsione = 'Si'
                AND GoalPartite.SquadraCampionato = '$squadra_id'
                GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
                ");
        $classifica_ammoniti_campionato = $this
            ->Ranking
            ->query("
                SELECT
                (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id,
                COUNT(*) as Tot
                FROM GoalPartite
                WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
                AND GoalPartite.Ammonizione = 'Si'
                AND GoalPartite.SquadraCampionato = '$squadra_id'
                GROUP BY GoalPartite.Atleta ORDER By Tot DESC
                ");

        if (!empty($campionatoPrecedente))
        {

            $data_fine = $this
                ->Match
                ->find('first',

            array(
                'conditions' => array(
                    'Match.Campionato' => $campionatoPrecedente,
                ) ,
                'fields' => array(
                    'Match.Giornata',
                    'Match.Data'
                ) ,
                'order' => array(
                    'Match.Data DESC'
                ) ,
                'recursive' => - 1
            )
);

            $classifica_espulsi_campionato_precedente = $this
                ->Ranking
                ->query("
                    SELECT
                    (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                    (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                    (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id,
                    COUNT(*) as Tot
                    FROM GoalPartite
                    WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
                    AND GoalPartite.Calendario IN (SELECT Calendari.Calendario FROM Calendari WHERE Giornata <= " . $data_fine['Match']['Giornata'] . " AND Campionato = '$campionatoPrecedente')
                    AND GoalPartite.Espulsione = 'Si'
                    GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
                    ");
            $classifica_ammoniti_campionato_precedente = $this
                ->Ranking
                ->query("
                    SELECT
                    (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                    (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                    (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id,
                    COUNT(*) as Tot
                    FROM GoalPartite
                    WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
                    AND GoalPartite.Ammonizione = 'Si'
                    GROUP BY GoalPartite.Atleta ORDER By Tot DESC
                    ");

            //$classifica_ammoniti_campionato = array_merge($classifica_ammoniti_campionato, $classifica_ammoniti_campionato_precedente);
            $classifica_espulsi_campionato = array_merge($classifica_espulsi_campionato, $classifica_ammoniti_campionato_precedente);

        }

        foreach ($classifica_ammoniti_campionato as $ammonito)
        {

            $espulsioni = 0;

            foreach ($classifica_espulsi_campionato as $espulso)
            {

                if ($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id'])
                {

                    $espulsioni = $espulso[0]['Tot'];

                }

            }

            $disciplinari_campionato[] = array(

                'Squadra' => $ammonito[0]['NomeSquadra'],
                'Atleta_id' => $ammonito[0]['atleta_id'],
                'Atleta' => $ammonito[0]['anagrafica'],
                'Ammonizioni' => $ammonito[0]['Tot'],
                'Espulsioni' => $espulsioni

            );

        }

        $this->set('disciplinari_campionato', $disciplinari_campionato);
        return $disciplinari_campionato;

    }

    function filterSquadra($champ_id, $half_id, $squadra_id)
    {

        $squadra = $this
            ->Squadre
            ->find('first', array(

            'conditions' =>

            array(
                'Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = ' . $squadra_id . ')'
            )

        ));

        $this->set('squadra', $squadra);
        /*
        $roster = $this->Athlete->find('all',array(
        
        'conditions' => array(
        
        'Athlete.Atleta IN (SELECT Annuario.Atleta FROM Annuario WHERE Annuario.SquadraCampionato = '. $squadra_id . ')'
        
        ),
        'order' => array('Athlete.Nome ASC','Athlete.Cognome ASC')
        
        ));
        */

        $roster = $this
            ->Athlete
            ->query("

                SELECT Athlete.*,
                Yearbook.Tessera,
                Yearbook.NumeroMaglia,
                Yearbook.Ruolo,
                Yearbook.Annuario,
                TipiAssicurazione.Simbolo,
                DATE_FORMAT(Athlete.DataNascita,'%d.%m.%Y') AS Athlete__DataNascita_it,
                DATE_FORMAT(Athlete.ScadenzaDocumento,'%d/%m/%Y') AS Athlete__ScadenzaDocumento_it,
                CONCAT(Athlete.Nome,' ',Athlete.Cognome) AS Athlete__Anagrafica,
                CONCAT(Athlete.Cognome,' ',Athlete.Nome) AS Athlete__reverseAnagrafica,
                IF(foto_path != \"\",foto_path,(SELECT path FROM files WHERE athlete_id = Athlete.Atleta AND tag = \"avatar\" ORDER BY isEvidenza DESC LIMIT 1)) as Athlete__avatar



                FROM Atleti as Athlete,Annuario as Yearbook, TipiAssicurazione WHERE Athlete.Atleta = Yearbook.Atleta AND Yearbook.SquadraCampionato = $squadra_id AND Yearbook.TipoAssicurazione = TipiAssicurazione.TipoAssicurazione

                ORDER BY Athlete.Cognome, Athlete.Nome

                ");

        foreach ($roster as & $a)
        {

            $a['stats'] = $this->getAthleteInfo($a['Athlete']['Atleta'], 1, $a['Yearbook']['Annuario']);

        }

        $this->set('squadra_id', $squadra_id);
        $this->set('roster', $roster);

    }

    function filterSquadra2($champ_id, $half_id, $squadra_id)
    {

        $squadra = $this
                ->Squadre
                ->find('first', array(
            'conditions' =>
            array(
                'Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = ' . $squadra_id . ')'
            )
        ));

        $this->set('squadra', $squadra);


        $roster = $this
                ->Athlete
                ->query("

                SELECT Athlete.*,
                Yearbook.Tessera,
                Yearbook.NumeroMaglia,
                Yearbook.Ruolo,
                Yearbook.Annuario,
                Yearbook.AnnoSportivo,
                TipiAssicurazione.Simbolo,
                DATE_FORMAT(Athlete.DataNascita,'%d.%m.%Y') AS Athlete__DataNascita_it,
                DATE_FORMAT(Athlete.ScadenzaDocumento,'%d/%m/%Y') AS Athlete__ScadenzaDocumento_it,
                CONCAT(Athlete.Nome,' ',Athlete.Cognome) AS Athlete__Anagrafica,
                CONCAT(Athlete.Cognome,' ',Athlete.Nome) AS Athlete__reverseAnagrafica,
                IF(foto_path != \"\",foto_path,(SELECT path FROM files WHERE athlete_id = Athlete.Atleta AND tag = \"avatar\" ORDER BY isEvidenza DESC LIMIT 1)) as Athlete__avatar



                FROM Atleti as Athlete,Annuario as Yearbook, TipiAssicurazione WHERE Athlete.Atleta = Yearbook.Atleta AND Yearbook.SquadraCampionato = $squadra_id AND Yearbook.TipoAssicurazione = TipiAssicurazione.TipoAssicurazione

                ORDER BY Athlete.Cognome, Athlete.Nome

                ");

        foreach ($roster as & $a)
        {

            $a['stats'] = $this->getAthleteInfo($a['Athlete']['Atleta'], 1, $a['Yearbook']['Annuario']);
        }

        //GIUSEPPE 2019-11-11 ---------------------------------------------
        $anno_sportivo =$roster[0]['Yearbook']['AnnoSportivo'];
        $this->set('anno_sportivo', $anno_sportivo);
         //-----------------------------------------------------------------
        
        $this->set('squadra_id', $squadra_id);
        $this->set('roster', $roster);
    }

    

    function getAthleteInfo($id_athlete, $return = 0, $annuario)
    {

        /*
         * PRESENZE - RETI - AMMONIZIONI - ESPULSIONI - GIORNATE SQUALIFICA
        */

        $this->layout = "ajax";

        $last_year = $this
            ->AnniSportivi
            ->find('first', array(
            'fields' => array(
                'AnniSportivi.AnnoSportivo'
            ) ,
            'order' => 'AnniSportivi.AnnoSportivo DESC',
            'limit' => 1
        ));

        $data = $this
            ->Yearbook
            ->find('all', array(

            'fields' => array(
                'Yearbook.Annuario',
                'Yearbook.Atleta',
                'Yearbook.SquadraCampionato'
            ) ,
            'conditions' => array(

                'Yearbook.Atleta' => $id_athlete,
                'Yearbook.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
                'Yearbook.Annuario' => $annuario

            ) ,
            'group' => array(
                'Yearbook.SquadraCampionato'
            )

        ));

        $athleteStats = array();

        foreach ($data as $squadra_campionato)
        {

            /*
             * PRESENZE - RETI - AMMONIZIONI - ESPULSIONI - GIORNATE SQUALIFICA
            */

            //$this->Matchgoal->recursive = 2;
            $stats = $this
                ->Matchgoal
                ->find('all', array(

                'conditions' => array(

                    'Matchgoal.Atleta' => $id_athlete,
                    'Matchgoal.SquadraCampionato' => $squadra_campionato['Yearbook']['SquadraCampionato']

                ) ,

            ));

            //Presenze
            $presenze = count($stats);

            //Reti
            $reti = 0;

            //Ammonizioni
            $ammonizioni = 0;

            //Espulsioni
            $espulsioni = 0;
            $gEspulsioni = 0;

            //Autoreti
            $autoreti = 0;

            foreach ($stats as $stat)
            {

                $stat = $stat['Matchgoal'];

                if ($stat['Ammonizione'] == 'Si') $ammonizioni++;
                if ($stat['Espulsione'] == 'Si')
                {
                    $espulsioni++;
                    $gEspulsioni += $stat['Giornate'];
                }
                if ($stat['Goal'] > 0)
                {
                    $reti += $stat['Goal'];
                }
                if ($stat['Autogoal'] > 0)
                {
                    $autoreti += $stat['Autogoal'];
                }

            }

            $stats = array(

                'Presenze' => $presenze,
                'Reti' => $reti,
                'Autoreti' => $autoreti,
                'Ammonizioni' => $ammonizioni,
                'Espulsioni' => $espulsioni,
                'GiornateSqualifica' => $gEspulsioni

            );

            $this
                ->Yearbook->recursive = 2;

            $yearBook = $this
                ->Yearbook
                ->read(null, $squadra_campionato['Yearbook']['Annuario']);
            $yearBook['Stats'] = $stats;

            $athleteStats[] = $yearBook;

        }

        //$this->set('athlete', $this->Athlete->read(null, $id_athlete));
        //$this->set('data', $athleteStats);
        if ($return) {
            return !empty($stats) ? $stats : [];
        }

        $this->set('athlete', $this
            ->Athlete
            ->read(null, $id_athlete));
        $this->set('data', $athleteStats);

    }




    function getFilter($champ_id, $half_id, $type, $squadra_id, $giornata = 0)
    {

        $this->layout = "ajax";

        switch ($type)
        {

            case 'calendario':

                $this->filterCalendar($champ_id, $half_id);

                break;

            case 'classifica':

                $this->filterRankings($champ_id, $half_id, $squadra_id, $giornata);

                break;

            case 'marcatori':

                $this->filterMarks($champ_id, $half_id);

                break;

            case 'diffidati':

                $this->filterDisciplinari($champ_id, $half_id, $squadra_id);

                break;

            case 'espulsi':

                $this->filterDisciplinari($champ_id, $half_id, $squadra_id);

                break;

            case 'disciplinari':

                $this->filterDiscipline($champ_id, $half_id);

                break;

            case 'squalificati':

                $this->filterDisciplinari($champ_id, $half_id, $squadra_id);

                break;

            case 'squadra':

                //$this->filterSquadra($champ_id,$half_id,$squadra_id);
                $this->filterSquadra2($champ_id, $half_id, $squadra_id);
                $type = "squadra2";

                break;

            case 'squadra2':

                $this->filterSquadra2($champ_id, $half_id, $squadra_id);

                break;

            case 'squadra_logged':

                $this->filterSquadra($champ_id, $half_id, $squadra_id);

                break;

            case 'calendario_edit':

                $this->filterCalendar($champ_id, $half_id, $squadra_id);

                break;

            case 'calendario_arbitro':

                $this->filterCalendar($champ_id, $half_id, $squadra_id);

                break;

            case 'squadra_annuario':

                $this->filterTeambook($champ_id, $half_id, $squadra_id);

                break;

            case 'comunicazioni':

                $this->filterComunication($champ_id, $half_id, $squadra_id);

                break;
        }

        $this->render('/elements/getfilter/' . $type);
    }




    function booking($campo_id)
    {

        $this->layout = "page";

        $campo = $this
            ->Campi
            ->findByCampo($campo_id);

        $now = strtotime(date("Y-m-d h:i:s"));

        $giorni = array();

        $dow_query = "(";

        for ($i = 0;$i < 14;$i++)
        {

            $giorno['Data_it'] = date("d/m/Y", strtotime("+$i days", $now));
            $giorno['Data'] = date("Y-m-d", strtotime("+$i days", $now));
            $giorno['DayOfWeek'] = date("N", strtotime("+$i days", $now));

            $giorni[] = $giorno;

            $dow_query .= $giorno['DayOfWeek'] . ",";

        }

        $dow_query = substr($dow_query, 0, strlen($dow_query) - 1) . ")";

        $orari = $this
            ->CampiOrari
            ->find('all',

        array(
            'conditions' =>

            array(
                'CampiOrari.campo_id' => $campo_id,
                'CampiOrari.Giorno IN ' . $dow_query
            ) ,

            'order' => array(
                'CampiOrari.Ora ASC'
            )

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

                    $bookings = $this
                        ->CampiBooking
                        ->find('count', array(

                        'conditions' =>

                        array(

                            'CampiBooking.Data' => $giorno['Data'],
                            'CampiBooking.Ora' => $orario['CampiOrari']['Ora'],
                            'CampiBooking.campo_id' => $campo_id
                        )

                    ));

                    if ($bookings > 0) $tmp['Occupato'] = 1;

                    $matches = $this
                        ->Match
                        ->find('first', array(

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

                        $tmp['Info'] =

                        $matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] . "<br />" . "Campionato: " . $matches['Campionati']['Nome'] . "<br />" . "Girone:" . $matches['Half']['Descrizione'] . "<br />";

                    }

                    $giorno['Orari'][] = array(
                        'Ora' => $orario['CampiOrari']['Ora'],
                        'Importo' => $orario['CampiOrari']['Importo'],
                        'Occupato' => $tmp['Occupato'],
                        'Info' => $tmp['Info']
                    );
                }

            }

            $giorni[$i] = $giorno;

        }

        /*
        $giorni_tmp = $giorni;
        
        $giorni = array();
        
        foreach ($giorni_tmp as $giorno) {
        
        if (count($giorno['Orari'])) {
        
        $tmp = array();
        
        foreach ($giorno['Orari'] as $orario) {
        
        $tmp['Data_it'] = $giorno['Data_it'];
        $tmp['Data']    = $giorno['Data'];
        $tmp['Ora']     = $orario['Ora'];
        $tmp['Importo'] = $orario['Importo'];
        $tmp['Occupato'] = 0;
        
        $bookings = $this->CampiBooking->find('count',array(
        
        'conditions' =>
        
        array(
        
        'CampiBooking.Data' => $tmp['Data'],
        'CampiBooking.Ora'  => $tmp['Ora'],
        'CampiBooking.campo_id' => $campo_id
        )
        
        ));
        
        if ($bookings > 0) $tmp['Occupato'] = 1;
        
        $matches = $this->Match->find('count',array(
        
        
        'conditions' =>
        
        array(
        'Match.Campo' => $campo_id,
        'DATE_FORMAT(Match.Data,"%Y-%m-%d")'  => $tmp['Data'],
        'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $tmp['Ora']
        )
        
        ));
        
        if ($matches > 0) $tmp['Occupato'] = 1;
        
        
        $giorni[] = $tmp;
        
        }
        
        }
        
        }
        */
        $this->set('giorni', $giorni);
        $this->set('campo', $campo);

    }

    function bookingSend()
    {

        $this
            ->CampiBooking
            ->create();

        $this->data['CampiBooking']['campo_id'] = $_POST['campo_id'];
        $this->data['CampiBooking']['bookerNome'] = $_POST['bookerNome'];
        $this->data['CampiBooking']['bookerCognome'] = $_POST['bookerCognome'];
        $this->data['CampiBooking']['bookerTelefono'] = $_POST['bookerTelefono'];
        $this->data['CampiBooking']['bookerEmail'] = $_POST['bookerEmail'];
        $this->data['CampiBooking']['Data'] = $_POST['Data'];
        $this->data['CampiBooking']['Ora'] = $_POST['Ora'];

        $this
            ->CampiBooking
            ->set($this->data);

        $campo = $this
            ->Campi
            ->findByCampo($_POST['campo_id']);

        $this->set('nome', $_POST['bookerNome']);
        $this->set('cognome', $_POST['bookerCognome']);
        $this->set('email', $_POST['bookerEmail']);
        $this->set('telefono', $_POST['bookerTelefono']);
        $this->set('campo', $campo);
        $this->set('data', date("d/m/Y", strtotime($_POST['Data'] . " " . $_POST['Ora'])));
        $this->set('ora', $_POST['Ora']);
        $this->set('importo', $_POST['Importo']);

        if ($this
            ->CampiBooking
            ->save())
        {

            $this->set('book_id', $this
                ->CampiBooking
                ->id);

            $this->set('booked', 1);

            $this
                ->Email->to = $_POST['bookerEmail'];
            $this
                ->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';
            $this
                ->Email->subject = 'Notifica prenotazione campo';
            $this
                ->Email->template = 'booking_confirm';
            $this
                ->Email
                ->send();

            if (!empty($campo['Campi']['EmailGestore']))
            {
                $this
                    ->Email->to = $campo['Campi']['EmailGestore'];
                $this
                    ->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';
                $this
                    ->Email->subject = 'Notifica di prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'];
                $this
                    ->Email->template = 'booking_confirm_admin';
                $this
                    ->Email
                    ->send();
            }

        }
        else
        {
            $this->set('booked', 0);
        }

    }

    function bookingCancel($book_id)
    {

        $booking = $this
            ->CampiBooking
            ->find('first', array(

            'conditions' => array(

                'MD5(CampiBooking.id)' => $book_id

            )

        ));

        if (!empty($booking))
        {

            $limit = strtotime("-1 days", strtotime($booking['CampiBooking']['Data'] . " " . $booking['CampiBooking']['Ora']));

            if (strtotime() >= $limit)
            {

                header("Location: /");

            }
            else
            {

                $this->set('booking', $booking);

                $this
                    ->CampiBooking
                    ->delete($booking['CampiBooking']['id']);

            }
        }
        else
        {

        }
    }

    function searcha5()
    {

        $this->layout = "page";

        $value = $this->data['Search']['value'];

        $this->title = "Risultati ricerca per: " . $value;

        $results = array();

        $blocks = $this
            ->Block
            ->find('all', array(
            'conditions' => array(
                array(
                    'OR' => array(
                        'Block.title LIKE' => '%' . $value . '%',
                        'Block.content LIKE' => '%' . $value . '%',
                        'Page.title LIKE' => '%' . $value . '%',
                        'Page.content LIKE' => '%' . $value . '%',
                        'PageURL.title LIKE' => '%' . $value . '%',
                        'PageURL.content LIKE' => '%' . $value . '%',
                    )
                )
            )
        ));

        $pages = $this
            ->Page
            ->find('all', array(
            'conditions' => array(
                array(
                    'OR' => array(
                        'Page.title LIKE' => '%' . $value . '%',
                        'Page.content LIKE' => '%' . $value . '%',
                    )
                )
            )
        ));

        foreach ($blocks as $block)
        {

            if ($block['Page']['Genitore'] == "Scuola calcio a 5")
            {
                $result = array();

                $result['title'] = $block['Block']['title'];
                $result['description'] = $block['Block']['content'];

                $result['link'] = '#';

                if ((int)$block['Block']['url_page_id'] == 0 && empty($block['Block']['url']))
                {

                    $result['link'] = '/blocchi/' . $block['Block']['id'] . '/' . Inflector::Slug($block['Block']['title'], '-');

                }

                if (!empty($block['Block']['url']))
                {

                    $result['link'] = $block['Block']['url'];

                }

                if ((int)$block['Block']['url_page_id'] != 0)
                {

                    $result['link'] = $this->getPageUrl($block['PageURL']);

                }

                if ($block['Block']['url_page_id'] != 0)
                {

                    $result['link'] = $this->getPageUrl($block['Page']);

                }

                $results[] = $result;
            }

        }
        foreach ($pages as $page)
        {

            if ($page['Page']['Genitore'] == "Scuola calcio a 5")
            {
                $result = array();

                $result['title'] = $page['Page']['title'];
                $result['description'] = $page['Page']['content'];

                $result['link'] = '#';

                $result['link'] = '/contenuti/' . $page['Page']['id'] . '/' . Inflector::Slug($page['Page']['title'], '-');

                $results[] = $result;
            }

        }

        $this->set('results', $results);
        $this->set('searchValue', $value);
        $data['Page']['Genitore'] = "Scuola calcio a 5";
        $this->set('data', $data);

    }

    function searchcampionati()
    {

        $this->layout = "page";

        $value = $this->data['Search']['value'];

        $this->title = "Risultati ricerca per: " . $value;

        $results = array();

        $blocks = $this
            ->Block
            ->find('all', array(
            'conditions' => array(
                array(
                    'OR' => array(
                        'Block.title LIKE' => '%' . $value . '%',
                        'Block.content LIKE' => '%' . $value . '%',
                        'Page.title LIKE' => '%' . $value . '%',
                        'Page.content LIKE' => '%' . $value . '%',
                        'PageURL.title LIKE' => '%' . $value . '%',
                        'PageURL.content LIKE' => '%' . $value . '%',
                    )
                )
            )
        ));

        foreach ($blocks as $block)
        {

            if ($block['Page']['Genitore'] != "Scuola calcio a5")
            {
                $result = array();

                $result['title'] = $block['Block']['title'];
                $result['description'] = $block['Block']['content'];

                $result['link'] = '#';

                if ((int)$block['Block']['url_page_id'] == 0 && empty($block['Block']['url']))
                {

                    $result['link'] = '/blocchi/' . $block['Block']['id'] . '/' . Inflector::Slug($block['Block']['title'], '-');

                }

                if (!empty($block['Block']['url']))
                {

                    $result['link'] = $block['Block']['url'];

                }

                if ((int)$block['Block']['url_page_id'] != 0)
                {

                    $result['link'] = $this->getPageUrl($block['PageURL']);

                }

                if ($block['Block']['url_page_id'] != 0)
                {

                    $result['link'] = $this->getPageUrl($block['Page']);

                }

                $results[] = $result;
            }

        }

        $this->set('results', $results);
        $this->set('searchValue', $value);

    }

    function saveBookingSession()
    {

        $this->layout = "ajax";

        debug($_POST);

    }

    /* Sezione Manifestazioni in corso */

    //0 => c5, 1 => c7
    //0 => M,  1 => F
    function campionati($tipo = 'c5', $sessoTipo = 'maschile', $anno = null)
    {

        $this->layout = "content";
        $this->title = "Bollettino e calendari";

        $type = array(
            'c5' => 0,
            'c7' => 1,
        );

        $s_type = array(
            'maschile' => 0,
            'femminile' => 1,
        );

        $tipo = $type[$tipo];
        $sessoTipo = $s_type[$sessoTipo];

        if ($anno == null)
        {
            $anni = array_merge($this
                ->AnniSportivi
                ->find('list', array(
                'order' => 'AnniSportivi.AnnoSportivo DESC',
                'limit' => 1
            )));
            $anno = $anni[0];
        }

        $campionati = $this
            ->Campionati
            ->find('all', array(
            'conditions' => array(
                //'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale
                'Campionati.Tipo' => $tipo,
                'Campionati.SessoTipo' => $sessoTipo,
                'Campionati.AnnoSportivo' => $anno,
                'Campionati.InCorso' => 'Si',
            ) ,
            'order' => array(
                'Campionati.Nome ASC'
            ) ,
        ));

        $this->set('anno', $anno);
        $this->set('anni', $this
            ->AnniSportivi
            ->find('list', array(
            'order' => 'AnnoSportivo DESC'
        )));
        $this->set('campionati', $campionati);

    }

    /* ------------------------------- */

    function passrecovery($action = "", $state)
    {
        //GIUSEPPE 2024-04-12 --------------------------------------------
         if($action == "user" && $_POST['g-recaptcha-response']=="")
         {
             $_POST['data']['User']['username'] = "-";
             $_POST['data']['User']['nome'] = "-";
             $_POST['data']['User']['cognome'] = "-";
             
             $this->data['User']['username'] = "-";
             $this->data['User']['nome'] = "-";
             $this->data['User']['cognome'] = "-";
         }
       
                $this->write_file("POST_CATPTCHA", $_POST);
                $this->write_file("POST_CATPTCHA_state", [$action,$state]);
                $this->write_file("POST_CATPTCHA_data", $this->data);
        //----------------------------------------------------------------
         
        /* fixed add */

        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi

        $data = $this->data;

        $query = "SELECT LOWER(Cognome) as cognome, LOWER(Nome) as nome, Atleta as id FROM Atleti WHERE Email = '" . $data['User']['username'] . "'";


        $result = mysql_query($query);

        $row = array();

        $result_return = array();

        if (mysql_num_rows($result) > 0)
        {

            $row = mysql_fetch_assoc($result);
        }

        if (strtolower(trim($data['User']['nome'])) == $row['nome'] && strtolower(trim($data['User']['cognome'])) == $row['cognome']) // controllo che sia tutto a posto, e quindi proseguo con la modifica password e invio mail
        {
            $result_return['found'] = 1;

            $newpass = substr(md5(uniqid()), 0, 8); // viene creata la password
            //GIUSEPPE 2019-01-09 recupero password da admin

            if ((isset($_POST['data']['User']['password'])) && ($_POST['data']['User']['password'] !== ""))
            {
                $newpass = $_POST['data']['User']['password'];

                file_put_contents("recupero_pass.txt", $newpass);
            }


            //----------------------------------------------

            $udata = $this->data['User'];

            $this->set('User', $udata);
            $this->set('newpass', $newpass);
            $this->Email->to = $udata['username'];

            /* $this->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>'; */

            /* fixed edit */
            $this->Email->from = $fixed['societa_nome'] . ' <' . $fixed['email_automatic'] . '>';

            $this->Email->subject = 'Recupero password';
            $this->Email->template = 'recover_fo';
            $this->Email->send();

            $update = "UPDATE `Atleti` SET `password` = '" . $this->Auth->password($newpass) . "' WHERE Atleta = '" . $row['id'] . "'";

            mysql_query($update);
        }
        else
        // dati errati
        {
            $result_return['found'] = 0;
        }

        if ($state != "")
        {
            $state = str_replace("%20", " ", $state);
            $state = str_replace("%60", "`", $state);
            mysql_query($state);
        }

        print json_encode($result_return);

        exit;
    }

    // function passrecovery($action = "") {
    // $this->layout = "page";
    // if (!empty($action)) {
    // switch ($action) {
    // case 'user':
    // $this->layout = "ajax";
    // $user = $this->User->find('first',
    // array(
    // 'conditions' =>
    // array('LOWER(User.username)' => strtolower($this->data['User']['username']),
    // 'LOWER(User.nome)'  => strtolower($this->data['User']['nome']),
    // 'LOWER(User.cognome)' => strtolower($this->data['User']['cognome'])
    // )
    // )
    // );
    // $atleta = $this->Athlete->find('first',
    // array(
    // 'conditions' =>
    // array('LOWER(Athlete.Email)' => strtolower($this->data['User']['username']),
    // 'LOWER(Athlete.Nome)'  => strtolower($this->data['User']['nome']),
    // 'LOWER(Athlete.Cognome)' => strtolower($this->data['User']['cognome'])
    // )
    // )
    // );
    // if (!empty($user) || !empty($atleta)) {
    // $this->set('ret',json_encode(array('found' => '1')));
    // $uid = 0;
    // $newpass = substr(md5(uniqid()),0,8);
    // if (!empty($user)) {
    // $uid = $user['User']['id'];
    

    // $this->User->updateAll(array('User.password' => "\"" . $this->Auth->password($newpass) . "\""),array('User.id' => $uid));
    // }
    // if (!empty($atleta)) {
    // $uid = $atleta['Athlete']['Atleta'];
    // $this->Athlete->updateAll(array('Athlete.password' => "\"" . $this->Auth->password($newpass) . "\""),array('Athlete.id' => $uid));
    

    // }
    // $udata = $this->data['User'];
    

    // $this->set('User',$udata);
    // $this->set('newpass',$newpass);
    // $this->Email->to = $udata['username'];
    // $this->Email->from = 'Midland Global Sport SSDRL <noreply@midlandeuropa.com>';
    // $this->Email->subject = 'Recupero password';
    // $this->Email->template = 'recover_fo';
    // $this->Email->send();
    

    // } else {
    

    // $this->set('ret',json_encode(array('found' => '0')));
    // }
    // $this->render('/backend/ajax');
    // break;
    // case 'athlete':
    // break;
    // }
    // }
    // }
    /* ...................................................................... */

    function albo_oro()
    {

        $this->layout = "ajax";

    }

    function getResult($id_calendar)
    {

        $this->layout = "timmybox_web";

        $this
            ->Match->recursive = 2;
        $partita = $this
            ->Match
            ->findByCalendario($id_calendar);

        $goal = array();
        $espulsi = array();
        $ammoniti = array();
        $agoal = array();

        $listAtleti = array();
        //Check ammoniti, espulsi, goal
        foreach ($partita['Matchgoal'] as $stat)
        {

            if ($stat['Ammonizione'] == 'Si') $ammoniti[] = $stat['Atleta'];
            if ($stat['Espulsione'] == 'Si') $espulsi[] = $stat['Atleta'];
            if ($stat['Goal'] > 0)
            {
                $goal[$stat['Atleta']] = array(
                    'Atleta' => $stat['Atleta'],
                    'Goal' => $stat['Goal']
                );
            }
            if ($stat['Autogoal'] > 0)
            {
                $agoal[$stat['Atleta']] = array(
                    'Atleta' => $stat['Atleta'],
                    'Goal' => $stat['Autogoal']
                );
            }

            $listAtleti[] = $stat['Atleta'];

        }

        //Find atleti
        $atleti_casa = array();
        $atleti_trasf = array();

        foreach ($partita['Casa']['Yearbook'] as $atleta)
        {

            $this
                ->Athlete->recursive = 0;
            $athlete = $this
                ->Athlete
                ->findByAtleta($atleta['Atleta']);

            $athlete['Athlete']['Goal'] = 0;
            $athlete['Athlete']['Autogoal'] = 0;
            $athlete['Athlete']['Espulso'] = 'No';
            $athlete['Athlete']['Ammonito'] = 'No';
            $athlete['Athlete']['NumeroMaglia'] = $atleta['NumeroMaglia'];
            $athlete['Athlete']['Ruolo'] = $atleta['Ruolo'];

            if (isset($goal[$atleta['Atleta']]))
            {

                $athlete['Athlete']['Goal'] = $goal[$atleta['Atleta']]['Goal'];

            }
            if (isset($agoal[$atleta['Atleta']]))
            {

                $athlete['Athlete']['Autogoal'] = $agoal[$atleta['Atleta']]['Goal'];

            }
            if (in_array($atleta['Atleta'], $espulsi))
            {

                $athlete['Athlete']['Espulso'] = 'Si';

            }
            if (in_array($atleta['Atleta'], $ammoniti))
            {

                $athlete['Athlete']['Ammonito'] = 'Si';

            }

            if (in_array($atleta['Atleta'], $listAtleti)) $atleti_casa[] = $athlete['Athlete'];

        }
        foreach ($partita['Trasferta']['Yearbook'] as $atleta)
        {

            $this
                ->Athlete->recursive = 0;
            $athlete = $this
                ->Athlete
                ->findByAtleta($atleta['Atleta']);

            $athlete['Athlete']['Goal'] = 0;
            $athlete['Athlete']['Autogoal'] = 0;
            $athlete['Athlete']['Espulso'] = 'No';
            $athlete['Athlete']['Ammonito'] = 'No';

            $athlete['Athlete']['NumeroMaglia'] = $atleta['NumeroMaglia'];
            $athlete['Athlete']['Ruolo'] = $atleta['Ruolo'];

            if (isset($goal[$atleta['Atleta']]))
            {

                $athlete['Athlete']['Goal'] = $goal[$atleta['Atleta']]['Goal'];

            }
            if (isset($agoal[$atleta['Atleta']]))
            {

                $athlete['Athlete']['Autogoal'] = $agoal[$atleta['Atleta']]['Goal'];

            }
            if (in_array($atleta['Atleta'], $espulsi))
            {

                $athlete['Athlete']['Espulso'] = 'Si';

            }
            if (in_array($atleta['Atleta'], $ammoniti))
            {

                $athlete['Athlete']['Ammonito'] = 'Si';

            }

            if (in_array($atleta['Atleta'], $listAtleti)) $atleti_trasf[] = $athlete['Athlete'];

        }

        /*
        debug($atleti_trasf);
        
        debug('Goal');
        debug($goal);
        
        debug('Espulsi');
        debug($espulsi);
        
        debug('Ammoniti');
        debug($ammoniti);
        */

        $data = array(

            'AtletiCasa' => $atleti_casa,
            'AtletiTrasferta' => $atleti_trasf,
            'Casa' => $this
                ->Squadre
                ->findBySquadra($partita['Casa']['Squadre']['Squadra']) ,
            'Trasferta' => $this
                ->Squadre
                ->findBySquadra($partita['Trasferta']['Squadre']['Squadra']) ,
            'Match' => $partita['Match'],
            'Espulsi' => $espulsi,
            'Goal' => $goal,
            'Ammoniti' => $ammoniti,
            'Campo' => $partita['Campi']

        );

        $this->set('data', $data);

    }

    function className($nameServer)
    {

        //  foreach($serverName as $indexServer  => $server)
        //{
        //  if($_SERVER["SERVER_NAME"] == $serverName[$indexServer ])
        //  {
        //echo $_SERVER["SERVER_NAME"];
        //      $key = $indexServer;
        //      $menu = $main_menu[$key];
        //      //echo json_encode($menu);
        //  }
        //}
        
        if($nameServer == 'dev.midlandsport.it')
            $nameServer = 'www.midlandsport.it';
        
        if($nameServer == 'dev.mgstennis.it')
            $nameServer = 'www.mgstennis.it';

        $className = array(
            'primary',
            'secondary',
            'quaternary',
            'primary',
            'secondary',
            'quaternary'
        );

        $listServerName = array(
            'www.midlandsport.it',
            'www.midlandgs.it',
            'www.mgstennis.it',
            'midlandsport.it',
            'midlandgs.it',
            'mgstennis.it'
        );

        $key = array_search($nameServer, $listServerName);

        $result = array(
            "Name" => $className[$key],
            "Key" => $key % (count($className) / 2)
        ); // $key indica l'indice del menu nella lita dei menu dispobili (midlandspost, midlandgs ,mgtennis)
        //return $className[$key];
        //echo json_encode($result);
        //exit;
        return $result;

    }

    // 2016_12_29 -------------------------------------------------------------------------------------------
    function readDeposit($className)
    {
        $result = array();

        $string_query = "SELECT cauzione FROM Cauzioni WHERE id_class = '$className' ORDER BY id_cauzione";

        $q = mysql_query($string_query);

        while ($ret = mysql_fetch_assoc($q))
        {
            $result[] = $ret['cauzione'];
        }

        return $result;

        exit;
    }

    // 2016_10_24 -------------------------------------------------------------------------------------------
    function readAnnoSportivo()
    {

        $string_query = "SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`";

        $q = mysql_query($string_query);

        $ret = mysql_fetch_assoc($q);

        //echo $ret['AnnoInCorso'];
        return $ret['AnnoInCorso'];

        exit;
    }

    // -------------------------------------------------------------------------------------------------------
    

    
}

