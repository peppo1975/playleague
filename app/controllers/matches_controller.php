<?


function countArbitroNew($id_calendario)
{

    App::Import('Model', 'Match');
    $Match = new Match;

    $data_oggi = $Match->find('first', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Match.Campionato',
            'Match.NomeArbitro',
            'Lda.Arbitro',
            'Match.Casa',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Match.Calendario' => $id_calendario
        ),
        'recursive' => 0
    ));

//debug('Arbitro: ' . $data_oggi['Match']['NomeArbitro']);
//debug('ID Casa: ' . $data_oggi['Match']['Casa']);
//debug('ID Tras: ' . $data_oggi['Match']['Trasferta']);

    $data_prec = $Match->find('all', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Lda.Arbitro',
            'Match.Casa',
            'Match.NomeArbitro',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Lda.Arbitro' => $data_oggi['Lda']['Arbitro'],
            'Match.Campionato' => $data_oggi['Match']['Campionato'],
            'AND' => array(
                array('Match.Giornata <' => $data_oggi['Match']['Giornata']),
                array('Match.Giornata >=' => $data_oggi['Match']['Giornata'] - 2),
            ),
            'OR' => array(
                'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
                'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
            )
        ),
        'recursive' => 0
    ));

    $data_prec_total = $Match->find('all', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Lda.Arbitro',
            'Match.Casa',
            'Match.NomeArbitro',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Lda.Arbitro' => $data_oggi['Lda']['Arbitro'],
            'Match.Giornata <' => $data_oggi['Match']['Giornata'],
            'Match.Campionato' => $data_oggi['Match']['Campionato'],
            'OR' => array(
                'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
                'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
            )
        ),
        'recursive' => 0
    ));

    /* Conteggio Casa, trasferta */

    $casa = 0;
    $tras = 0;

    foreach ($data_prec as $t)
    {

        if ($t['Match']['Casa'] == $data_oggi['Match']['Casa'])
            $casa++;
        else
            $tras++;
    }

    $casa_tot = 0;
    $tras_tot = 0;

    foreach ($data_prec_total as $t)
    {

        if ($t['Match']['Casa'] == $data_oggi['Match']['Casa'])
            $casa_tot++;
        else
            $tras_tot++;
    }

    if ($casa > 0)
        $casa++;
    if ($tras > 0)
        $tras++;

    /* -- */

    if (!empty($data_prec) && $data_oggi['Lda']['Arbitro'] > 0)
    {

        if (count($data_prec) >= 2)
        {
            $class = "arbitro-yellow";
            $title_casa = 'Casa: ' . $casa;
            $title_tras = 'Trasferta: ' . $tras;
        }
        if (count($data_prec) >= 3)
        {
            $class = "arbitro-red";
            $title_casa = 'Casa: ' . $casa;
            $title_tras = 'Trasferta: ' . $tras;
        }

        if (isset($data_prec_total) && !empty($data_prec_total))
        {
            if (count($data_prec_total) >= 7)
            {
                $class = "arbitro-black";
                $title_casa = 'Casa: ' . $casa_tot;
                $title_tras = 'Trasferta: ' . $tras_tot;
            }
        }

        return '<div class="arbitro-info" rel="timmytip" title="' . $data_oggi['Match']['NomeArbitro'] . ' ' . $title_casa . ' ' . $title_tras . '" data-class="' . $class . '">' . substr($data_oggi['Match']['NomeArbitro'], 0, 15) . '</div>';
    }
    else
    {

        return '<div class="arbitro-info" rel="timmytip" title="' . $data_oggi['Match']['NomeArbitro'] . '">' . substr($data_oggi['Match']['NomeArbitro'], 0, 15) . '</div>';
    }
}


function countArbitro2New($id_calendario)
{

    App::Import('Model', 'Match');
    $Match = new Match;

    $data_oggi = $Match->find('first', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Match.Campionato',
            'Match.NomeArbitro2',
            'Lda.Arbitro2',
            'Match.Casa',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Match.Calendario' => $id_calendario
        ),
        'recursive' => 0
    ));

//debug('Arbitro: ' . $data_oggi['Match']['NomeArbitro']);
//debug('ID Casa: ' . $data_oggi['Match']['Casa']);
//debug('ID Tras: ' . $data_oggi['Match']['Trasferta']);

    $data_prec = $Match->find('all', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Lda.Arbitro2',
            'Match.Casa',
            'Match.NomeArbitro2',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Lda.Arbitro' => $data_oggi['Lda']['Arbitro2'],
            'Match.Campionato' => $data_oggi['Match']['Campionato'],
            'AND' => array(
                array('Match.Giornata <' => $data_oggi['Match']['Giornata']),
                array('Match.Giornata >=' => $data_oggi['Match']['Giornata'] - 2),
            ),
            'OR' => array(
                'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
                'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
            )
        ),
        'recursive' => 0
    ));

    $data_prec_total = $Match->find('all', array(
        'fields' => array(
            'Match.Calendario',
            'Match.Giornata',
            'Lda.Arbitro2',
            'Match.Casa',
            'Match.NomeArbitro2',
            'Match.Trasferta'
        ),
        'conditions' => array(
            'Lda.Arbitro' => $data_oggi['Lda']['Arbitro2'],
            'Match.Giornata <' => $data_oggi['Match']['Giornata'],
            'Match.Campionato' => $data_oggi['Match']['Campionato'],
            'OR' => array(
                'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
                'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
            )
        ),
        'recursive' => 0
    ));

    /* Conteggio Casa, trasferta */

    $casa = 0;
    $tras = 0;

    foreach ($data_prec as $t)
    {

        if ($t['Match']['Casa'] == $data_oggi['Match']['Casa'])
            $casa++;
        else
            $tras++;
    }

    $casa_tot = 0;
    $tras_tot = 0;

    foreach ($data_prec_total as $t)
    {

        if ($t['Match']['Casa'] == $data_oggi['Match']['Casa'])
            $casa_tot++;
        else
            $tras_tot++;
    }

    if ($casa > 0)
        $casa++;
    if ($tras > 0)
        $tras++;

    /* -- */

//debug($data_prec);

    if (!empty($data_prec) && $data_oggi['Lda']['Arbitro2'] > 0)
    {

//debug('Numero volte: ' . count($data_prec));

        if (count($data_prec) >= 2)
        {
            $class = "arbitro-yellow";
            $title_casa = 'Casa: ' . $casa;
            $title_tras = 'Trasferta: ' . $tras;
        }
        if (count($data_prec) >= 3)
        {
            $class = "arbitro-red";
            $title_casa = 'Casa: ' . $casa;
            $title_tras = 'Trasferta: ' . $tras;
        }

        if (isset($data_prec_total) && !empty($data_prec_total))
        {
            if (count($data_prec_total) >= 7)
            {
                $class = "arbitro-black";
                $title_casa = 'Casa: ' . $casa_tot;
                $title_tras = 'Trasferta: ' . $tras_tot;
            }
        }

        return '<div class="arbitro-info" rel="timmytip" title="' . $data_oggi['Match']['NomeArbitro2'] . ' ' . $title_casa . ' ' . $title_tras . '" data-class="' . $class . '">' . substr($data_oggi['Match']['NomeArbitro2'], 0, 15) . '</div>';
    }
    else
    {

        return '<div class="arbitro-info" rel="timmytip" title="' . $data_oggi['Match']['NomeArbitro2'] . '">' . substr($data_oggi['Match']['NomeArbitro2'], 0, 15) . '</div>';
    }
}


function findArbitro($value)
{

    if ($value == '')
        return "";

    App::Import('Model', 'Athlete');

    $arbitro = new Athlete();

    $dati = $arbitro->find('first', array('conditions' => array('Athlete.Atleta' => $value)));

    return $dati['Athlete']['Cognome'] . " " . $dati['Athlete']['Nome'];
}


function svuota(&$arr)
{

    foreach ($arr as $chiave => $valore)
    {

        $arr[$chiave] = '';
    }
}


function getDay($value)
{

    $days = array('Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato'); //$days = array('Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato','Domenica'); //GIUSEPPE 14/10/2016

    $day = getdate(strtotime($value));

    if ($value == '0000-00-00 00:00:00')
        return 'null';  //print_r(" ".($day['wday']-1)." "); print_r($day);

    return $days[$day['wday']]; //return $days[$day['wday']-1];
}


function getGoalCasa($value)
{

    $risultato = explode('-', $value);

    return (isset($risultato[0])) ? $risultato[0] : '';
}


function getGoalTrasferta($value)
{

    $risultato = explode('-', $value);

    return (isset($risultato[1])) ? $risultato[1] : '';
}


function checkArbitro($value)
{

    $dati = explode("|", $value);

    $class = "arbitro-info";

    $count = isset($dati[1]) ? $dati[1] : 0;

    if ($count >= 2)
        $class = "arbitro-yellow";
    if ($count >= 3)
        $class = "arbitro-red";
    if ($count >= 7)
        $class = "arbitro-black";

    if (strlen($dati[0]) > 8)
    {

        $title = $dati[0];

        $dati[0] = substr_replace($dati[0], '...', 8, strlen($dati[0]));
    }

    return isset($dati[1]) ? "<div class=\"arbitro-info\" rel=\"timmytip\" title=\"{$dati[0]} Casa: {$dati[2]}, Trasferta: {$dati[3]}\" data-class=\"$class\">" . $dati[0] . "</div>" : '';
}


function generateRoundRobinPairings($squadre, $ar = 0)
{

    $num_players = count($squadre);

    $giornate = array();

    $num_players = ($num_players > 0) ? (int) $num_players : 4;

    $num_players = ($num_players % 2 == 0) ? $num_players : $num_players + 1;

    for ($round = 1; $round < $num_players; $round++)
    {

        $players_done = array();

        for ($player = 1; $player < $num_players; $player++)
        {
            if (!in_array($player, $players_done))
            {

                $opponent = $round - $player;
                $opponent += ($opponent < 0) ? $num_players : 1;

                if ($opponent != $player)
                {

                    if ($player % 2 == $opponent % 2)
                    {
                        if ($player < $opponent)
                        {


                            $giornate[$round][] = array($opponent - 1, $player - 1);
                        }
                        else
                        {

                            $giornate[$round][] = array($player - 1, $opponent - 1);
                        }
                    }
                    else
                    {
                        if ($player < $opponent)
                        {

                            $giornate[$round][] = array($player - 1, $opponent - 1);
                        }
                        else
                        {

                            $giornate[$round][] = array($opponent - 1, $player - 1);
                        }
                    }

                    $players_done[] = $player;
                    $players_done[] = $opponent;
                }
            }
        }

        if ($round % 2 == 0)
        {
            $opponent = ($round + $num_players) / 2;

            $giornate[$round][] = array($num_players - 1, $opponent - 1);
        }
        else
        {
            $opponent = ($round + 1) / 2;

            $giornate[$round][] = array($opponent - 1, $num_players - 1);
        }
    }
    if ($ar == 0)
        return $giornate;
    else
    {

        $tmp = $giornate;

        foreach ($tmp as $round => $partite)
        {

            foreach ($partite as $partita)
            {

                $casa = $partita[1];
                $trasferta = $partita[0];

                $giornate[count($tmp) + $round][] = array($casa, $trasferta);
            }
        }

        return $giornate;
    }
}


function truncateFieldz($value)
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


class MatchesController extends AppController
{

    var $name = "Matches";
    var $login_required = true;
//    var $helpers = array('Backend');
// GIUSEPPE 2022-10-15
    var $helpers = array(
        'Backend',
        'fpdf',
        'excel'
    );
    var $uses = array('Match', 'Campionati', 'Campicampionati', 'Ranking', 'Yearbook', 'Disciplinari', 'Discipline', 'Half', 'Squadre', 'Campi', 'Causalresult', 'AnniSportivi', 'SquadreCampionati', 'Athlete', 'Lda', 'Matchgoal', 'Notgame', 'EmailModel', 'Spool');


    function admin_bollettini()
    {

        $_campionati = $this->Campionati->find('list', array(
            'fields' => array('Campionati.Campionato', 'Campionati.Nome'),
            'conditions' => array(
                'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"), date("Y") + 2),
                //'Campionati.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
                'Campionati.InUso' => 'Si',
            ),
            'order' => array('Campionati.Nome DESC')
        ));

        $_campionati['default'] = 'Scegliere un campionato...';

        $_campionati = array_reverse($_campionati, true);

        $this->set('campionati', $_campionati);
    }


//SubscriptionCampionatoId
    function subscriptiongetgirone($campionato_id)
    {


        $this->autoRender = false;
        $gironi = $this->Half->find('list', array(
            'conditions' => array(
                'Half.Campionato' => $campionato_id
            ),
            'fields' => array('GironeCampionato', 'Descrizione')
        ));

        print json_encode($gironi);
    }


    function subscription($squadra_id)
    {




        $squadres = $this->Squadre->find('list', array('fields' => array('Squadra', 'Denominazione')));
        $campionati = $this->Campionati->find('list', array('conditions' => array('iscrizioni' => 1), 'fields' => array('Campionato', 'Nome')));
        $campis = $this->Campi->find('list', array('fields' => array('Campo', 'Descrizione')));

// $squadre_campionati = $this->SquadreCampionati->find('all',array('conditions'=>array('SquadreCampionati.Squadra'=>($squadra_id))));
        $squadrecampionati = array();
        $q = mysql_query("SELECT * FROM SquadreCampionati WHERE Squadra = $squadra_id");

        while ($ret = mysql_fetch_assoc($q))
        {
            $squadrecampionati[] = $ret['SquadraCampionato'];
        }

        $squadrecampionati = "(" . implode(",", $squadrecampionati) . ")";

        $qq = mysql_query("SELECT * FROM Atleti,Annuario WHERE Atleti.Atleta = Annuario.Atleta AND SquadraCampionato IN $squadrecampionati ORDER BY Cognome, Nome");

        $responsabili = array();

        while ($ret = mysql_fetch_assoc($qq))
        {

            $responsabili[$ret['Atleta']] = $ret['Cognome'] . " " . $ret['Nome'];
        }
//print 'Athlete.Atleta IN (SELECT Atleta FROM Annuario WHERE SquadraCampionato IN ' . $squadrecampionati . ')';
        /*
          $responsabili = $this->Athlete->find('all',array('conditions'=>

          'Athlete.Atleta IN (SELECT Atleta FROM Annuario WHERE SquadraCampionato IN ' . $squadrecampionati . ')'

          ));
         */

        $this->set('responsabili', $responsabili);
        $this->set('squadres', $squadres);
        $this->set('squadra_id', $squadra_id);
        $this->set('campionati', $campionati);
        $this->set('campis', $campis);

        $this->layout = "content";
        $this->title = "Iscrizione campionati";
    }


    function admin_checkdate()
    {

        $this->autoRender = false;

        $data = $_POST['data'];

        if (strlen($data) > 4)
        {

            $data = explode('/', $data);

            $data = $data[2] . '-' . $data[1] . '-' . $data[0];

            $count = $this->Notgame->find('count', array(
                'conditions' => array(
                    'CAST(Notgame.Data as DATE) = \'' . $data . '\''
                )
            ));

            print $count;

            return;
        }
        print 0;
        return;
    }


    function admin_index()
    {

        $group_id = $this->Auth->user('group_id');

        $conditions = array();

//        if ($group_id == 3)
//        {
//
//
//            $from = date("Y-m-d");
//            $to = date("Y-m-d", strtotime("+40 days"));
//
//            $conditions = array(
//                'AND' => array(
//                    array(
//                        'Match.Data >=' => $from,
//                        'Match.Data <=' => $to
//                    )
//                )
//            );
//        }
//GIUSEPPE 2022-10-15 ----------------------
        if ($group_id == 3)
        {
            $from = "{$this->init_matches()}";

            $conditions = array(
                'Match.Data >=' => $from
            );
        }
// -----------------------------------------

        $this->set('conditions', $conditions);
    }


//GIUSEPPE 2022-10-15 ----------------------
    private function init_matches()
    {
        include_once __DIR__ . "/../models/api.php";

        $api = new Api();

        $anno_sportivo = $api->annoSportivo();

        $init = $anno_sportivo['current']['init'];

        $init_timestamp = explode("-", $init);

        return "{$init_timestamp[2]}-{$init_timestamp[1]}-{$init_timestamp[0]}";
    }


    public function admin_pdfMatchesList()
    {

        session_start();

        if (isset($_POST['pdf_matches_list']))
        {
            $_SESSION['pdf_matches_list'] = [];
            $_SESSION['pdf_matches_list'] = $_POST['pdf_matches_list'];
            print_r($_SESSION['pdf_matches_list']);
            exit();
        }
        else
        {
            if (isset($_SESSION['pdf_matches_list']))
            {
                $this->creaGiornoData();
                $array = $this->sortPdf("td_data_timestamp");

//                $array = $this->sortPdf("td_h");
//                $array = $this->sortPdf("td_manifestazione");
//                $array = $this->sortPdf("td_girone");
//
//                $array = $this->sortPdf("td_campo");


                $this->layout = "pdf";
                $this->set('pdf_matches_list', $_SESSION['pdf_matches_list']);
            }
        }
    }


    private function sortPdf($key, $order = 'ASC')
    {
        $temp = [];
        $len = count($_SESSION['pdf_matches_list']);
        $array = $_SESSION['pdf_matches_list'];
        $switch = false;
        do
        {
            $switch = false;
            for ($i = 0; $i < $len - 1; $i++)
            {
                $temp = [];
//                $val1 = $array[$i]['td_data_timestamp'].$array[$i]['td_h'].$array[$i]['td_manifestazione'].$array[$i]['td_girone'].$array[$i]['td_campo'];
//                $val2 = $array[$i + 1]['td_data_timestamp'].$array[$i + 1]['td_h'].$array[$i + 1]['td_manifestazione'].$array[$i + 1]['td_girone'].$array[$i + 1]['td_campo'];
                $val1 = $array[$i]['td_data_timestamp'] . $array[$i]['td_campo'] . $array[$i]['td_h'];
                $val2 = $array[$i + 1]['td_data_timestamp'] . $array[$i + 1]['td_campo'] . $array[$i + 1]['td_h'];

                $question;

                if ($order == 'ASC')
                {
                    $question = $val1 > $val2;
                }
                if ($order == 'DESC')
                {
                    $question = $val1 < $val2;
                }

                if ($question)
                {
                    $temp = $array[$i];
                    $array[$i] = $array[$i + 1];
                    $array[$i + 1] = $temp;
                    $switch = true;
                }
            }
        }
        while ($switch);

        $_SESSION['pdf_matches_list'] = $array;

        return $array;
    }


    private function creaGiornoData()
    {

        session_start();

        $array = $_SESSION['pdf_matches_list'];

        foreach ($array as $key => $value)
        {
            $expl_arr_ = explode("/", $value['td_data']);
            $value['td_data_timestamp'] = "{$expl_arr_[2]}-{$expl_arr_[1]}-{$expl_arr_[0]}";
            $array[$key] = $value;
        }

        unset($_SESSION['pdf_matches_list']);
        $_SESSION['pdf_matches_list'] = $array;
    }


// -------------------------------

    function admin_searchCalendarioById($id)
    {

        $this->login_required = false;
        $calendario = $this->Match->findByCalendario($id);

        if (!empty($this->params['requested']))
        {
            return $calendario;
        }
    }


    function admin_goalSearchAthleteByTeam($squadra, $calendario = null)
    {

        $this->layout = "ajax";

        /* $atleti = $this->Yearbook->find('list', array(

          'conditions' => array(
          'Yearbook.SquadraCampionato IN

          (

          SELECT SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.Squadra IN

          (SELECT SquadreCampionati2.Squadra FROM SquadreCampionati as SquadreCampionati2 WHERE SquadreCampionati2.SquadraCampionato = ' . $squadra . ')

          AND

          (Yearbook.AnnoSportivo = (SELECT MAX(AnnoSportivo) FROM AnniSportivi))

          )'
          ),
          'fields' => array('Yearbook.Atleta','Yearbook.Atleta'),
          )
          ); */

//debug($calendario);

        $calendario_arr = $this->Match->find('first', array(
            'fields' => array(
                'Match.Calendario',
                'Match.Giornata',
                'Match.Data',
                'Match.Casa',
                'Match.Trasferta',
            ),
            'conditions' => array('Match.Calendario' => $calendario),
        ));

        $atleti = $this->Yearbook->find('list', array(
            'conditions' => array(
                'Yearbook.SquadraCampionato' => $squadra,
            ),
            'fields' => array('Yearbook.Atleta', 'Yearbook.Atleta'),
                )
        );

        $squadra_campionato = $this->SquadreCampionati->find('first', array(
            'conditions' => array(
                'SquadreCampionati.SquadraCampionato' => $squadra,
            ),
        ));

//debug($squadra_campionato);
//(Yearbook.AnnoSportivo BETWEEN ' . (date("Y")-1) . ' AND ' . (date("Y")+1) . ')
//Configure::Write('debug',2);

        foreach ($atleti as $atleta => $key)
        {

            $find_athlete = $this->Athlete->find('first', array('conditions' => array('Athlete.Atleta' => $key)));
            $new_key = $find_athlete['Athlete']['Cognome'] . ' ' . $find_athlete['Athlete']['Nome'];

            $atleti[$atleta] = $new_key;

            $matchgoal = $this->Matchgoal->find('first', array(
                'conditions' => array(
                    'Matchgoal.SquadraCampionato' => $squadra,
                    'Matchgoal.Atleta' => $key,
                    'Matchgoal.Espulsione' => 'Si',
                ),
            ));

            if (isset($matchgoal) && !empty($matchgoal))
            {

                $calendario_espulsione = $this->Match->findByCalendario($matchgoal['Matchgoal']['Calendario']);

                if ($matchgoal['Matchgoal']['EspulsioneGiornate'] != '')
                {
                    $ultima_giornata_squalifica = $matchgoal['Matchgoal']['EspulsioneGiornate'] + $calendario_espulsione['Match']['Giornata'];

                    if ($calendario_arr['Match']['Giornata'] <= $ultima_giornata_squalifica && $calendario_arr['Match']['Giornata'] >= $calendario_espulsione['Match']['Giornata'] && $calendario_arr['Match']['Calendario'] != $matchgoal['Matchgoal']['Calendario'])
                    {
//unset($atleti[$atleta]);
                    }
                }
                elseif ($matchgoal['Matchgoal']['EspulsioneFine'] != '')
                {
                    $fine_espulsione = strtotime($matchgoal['Matchgoal']['EspulsioneFine']);
                    $partita_attuale = strtotime($calendario_arr['Match']['Data']);
                    if ($partita_attuale <= $fine_espulsione && $calendario_arr['Match']['Calendario'] != $matchgoal['Matchgoal']['Calendario'])
                    {
//unset($atleti[$atleta]);
                    }
                }
            }
        }

        $squalificati = $this->getSqualificatiByCalendario($calendario_arr['Match']['Calendario']);

        $non_giocano = array();

        foreach ($squalificati['espulsi'] as $espulso)
        {
            $non_giocano[] = $espulso['Anagrafica'];
        }
        foreach ($squalificati['squalificati'] as $squalificato)
        {
            $non_giocano[] = $squalificato['Anagrafica'];
        }


        asort($atleti);

        foreach ($atleti as $k => $atleta)
        {

            if (in_array($atleta, $non_giocano))
            {
                unset($atleti[$k]);
            }
        }




        if (isset($_GET['tt']))
        {

            $atleti2 = array();
            foreach ($atleti as $id => $atleta)
            {

                $atleti2[] = array(
                    'id' => $id,
                    'nome' => $atleta
                );
            }
            $atleti = $atleti2;
        }


        $this->set('result', json_encode($atleti));
        $this->render('/backend/ajaxResult');
    }


//GIUSEPPE 2017-02-27 ..........................................................

    function searchTeam($squadra, $calendario)
    {
        $this->admin_goalSearchAthleteByTeam($squadra, $calendario = null);
    }


//..............................................................................







    function getSqualificatiByCalendario($calendario)
    {

        Configure::Write('debug', 0);

        $calendario_arr = $this->Match->find('first', array(
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
            ),
            'conditions' => array('Match.Calendario' => $calendario),
        ));

        $giornata = $calendario_arr['Match']['Giornata'];
        $champ_id = $calendario_arr['Match']['Campionato'];
        $half_id = $calendario_arr['Match']['GironeCampionato'];
        $espulsi = array();
        $squalificati = array();

        $diffidati_array = $this->Matchgoal->query(
                "SELECT 
					(SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
					(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as id_atleta,
					(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
					(SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
					COUNT(*) as Ammonizioni FROM GoalPartite
					WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
					AND GoalPartite.Ammonizione = 'Si'
					AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata < '$giornata')
					GROUP BY GoalPartite.GoalPartita ORDER BY Ammonizioni DESC"
        );

        $espulsi_array = $this->Matchgoal->query(
                "SELECT 
					(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
					(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
					(SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as id_atleta,
					
					GoalPartite.GoalPartita,
					GoalPartite.EspulsioneGiornate,
					GoalPartite.EspulsioneInizio,
					GoalPartite.EspulsioneFine,
					GoalPartite.Espulsione FROM GoalPartite
					WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
					AND GoalPartite.Espulsione = 'Si'
					AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata < '$giornata')
					GROUP BY GoalPartite.GoalPartita ORDER By NomeSquadra"
        );

        /*
         *
         * VERIFICO DISCIPLINARI CAMPIONATO PRECEDENTE.
         *  
         */
        $champ_prec = $calendario_arr['Campionati']['CampionatoPrecedente'];

        if ($champ_prec != "" || $champ_prec != 0)
        {

//Giornata finale campionato precedente 
            $data_fine = $this->Match->find('first', array(
                'conditions' => array(
                    'Match.Campionato' => $champ_prec,
                ),
                'fields' => array('Match.Giornata', 'Match.Data'),
                'order' => array('Match.Data DESC'),
                'recursive' => -1
                    )
            );

            if (strtotime(date("Y-m-d")) > strtotime($data_fine['Match']['Data']))
            { //Verifico che il campionato precedente sia finito					
//Calcolo squadre campionato riferite al campionato e al girone del NUOVO campionato 
                $squadrec = $this->SquadreCampionati->find('list', array(
                    'fields' => array('SquadreCampionati.SquadraCampionato', 'SquadreCampionati.SquadraCampionato'),
                    'conditions' => array(
                        'SquadreCampionati.Campionato' => $champ_id,
                        'SquadreCampionati.GironeCampionato' => $half_id
                    ),
                ));

                $squadrec = array_merge($squadrec);

//Calcolo id atleti 
                $atletic = $this->Yearbook->find('list', array(
                    'fields' => array('Yearbook.Atleta', 'Yearbook.Atleta'),
                    'conditions' => array(
                        'Yearbook.SquadraCampionato' => $squadrec
                    )
                ));

                $atletic = array_merge($atletic);

                $espulsi_array_prec = $this->Matchgoal->query(
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
                  $diffidati_array_prec = $this->Matchgoal->query(

                  "SELECT
                  (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '".$data_fine['Match']['Giornata']."' AND Calendari.Campionato = '$champ_prec') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
                  (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
                  (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
                  (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
                  GoalPartite.Atleta,
                  COUNT(*) as Ammonizioni FROM GoalPartite
                  WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_prec')
                  AND GoalPartite.Ammonizione = 'Si'
                  AND GoalPartite.Atleta IN (".implode(",",$atletic).")
                  AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '".$data_fine['Match']['Giornata']."')
                  GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"

                  );
                 */
//$diffidati_array = array_merge($diffidati_array, $diffidati_array_prec);	
                $espulsi_array = array_merge($espulsi_array, $espulsi_array_prec);
            }
        }

//debug($espulsi_array);
//debug($diffidati_array);

        foreach ($espulsi_array as $k => $espulso)
        {

            if ($espulso[0]['NomeSquadra'] != $calendario_arr['Match']['CasaNome'] && $espulso[0]['NomeSquadra'] != $calendario_arr['Match']['TrasfertaNome'])
                continue;

            if (!isset($espulso[0]['Data']))
                $espulso[0]['Data'] = '0000/00/00';

            $giorni = $espulso['GoalPartite']['EspulsioneGiornate'];
            $inizio = date('d/m/Y', strtotime($espulso[0]['Data']));
            $fine = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));
            /* 				
              if($giorni != '' && $giorni != 0){

              $_periodo = $giorni;
              $periodo  = $giorni . ' giornate';

              $fine_squalifica = $_periodo + $espulso[0]['Giornata'];

              //debug("Periodo:" . $_periodo . " fine squalifica: " . $fine_squalifica);

              if($fine_squalifica >= $calendario_arr['Match']['Giornata']) {

              $espulsi[] = array(

              'Squadra' => $espulso[0]['NomeSquadra'],
              'Anagrafica' => $espulso[0]['anagrafica'],
              'Periodo' => $periodo,

              );

              }


              } else {

              if($inizio != '00/00/0000' && $fine != '00/00/0000') {

              $_periodo = strtotime($espulso['GoalPartite']['EspulsioneFine']);
              $periodo  = $inizio . ' - ' . $fine;

              if(strtotime($calendario_arr['Match']['Data']) <= $_periodo) {

              $espulsi[] = array(

              'Squadra' => $espulso[0]['NomeSquadra'],
              'Anagrafica' => $espulso[0]['anagrafica'],
              'Periodo' => $periodo,

              );

              }

              } else {

              $_periodo = 1;
              $periodo  = '1 giornata';

              $fine_squalifica = $_periodo + $espulso[0]['Giornata'];

              if($fine_squalifica == $calendario_arr['Match']['Giornata']) {

              $espulsi[] = array(

              'Squadra' => $espulso[0]['NomeSquadra'],
              'Anagrafica' => $espulso[0]['anagrafica'],
              'Periodo' => $periodo,

              );

              }

              }

              }
             */
            /*
              if(!isset($espulso[0]['Data'])) $espulso[0]['Data'] = '0000/00/00';

              $giorni   = $espulso['GoalPartite']['EspulsioneGiornate'];
              $inizio   = date('d/m/Y', strtotime($espulso[0]['Data']));
              $fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));
             */
            if ($giorni != '' && $giorni != 0)
            {

                $fine_arr = explode('/', $fine);

                if (!checkdate($fine_arr[1], $fine_arr[0], $fine_arr[2]))
                {


//	if ($calendario == 131784) print_r($tmpz);		


                    $_periodo = $giorni;
                    $periodo = $giorni . ' giornate';

//$fine_squalifica = $_periodo + $espulso[0]['Giornata'];

                    if (!isset($espulso[0]['OldChamp']))
                    {
                        $fine_squalifica = $_periodo + $espulso[0]['Giornata'] + 1;
                    }
                    else
                        $fine_squalifica = $_periodo - ($data_fine['Match']['Giornata'] - $espulso[0]['Giornata']) + 1;
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

                    if (!isset($espulso[0]['OldChamp']))
                        $fine_squalifica = $_periodo + $espulso[0]['Giornata'];
                    else
                        $fine_squalifica = $_periodo - ($data_fine['Match']['Giornata'] - $espulso[0]['Giornata']) + 1;
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
        /*
          $diffidati_array = $this->Matchgoal->query(

          "SELECT
          (SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
          (SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
          (SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
          (SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
          (SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as id_atleta,
          (SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
          (SELECT Calendari.Giornata FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Giornata,
          COUNT(*) as Ammonizioni FROM GoalPartite
          WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id')
          AND GoalPartite.Ammonizione = 'Si'
          AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata < '$giornata')
          GROUP BY GoalPartite.GoalPartita ORDER BY Ammonizioni DESC"

          );
         */
        $giornata = $calendario_arr['Match']['Giornata'];
        $giornata_1 = $giornata--;
        $diffidati_array = $this->Matchgoal->query(
                "SELECT 
					(SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE (Calendari.Giornata = '{$giornata}') AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
					(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					GoalPartite.Atleta,
					0 as AzzeraDiffidati,	
					COUNT(*) as Ammonizioni FROM GoalPartite
					WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id') 
					AND GoalPartite.Ammonizione = 'Si'
					AND GoalPartite.Calendario IN (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
					GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
        );

        foreach ($diffidati_array as $k => $diffidato)
        {

            $diffidato = $diffidato[0];

//debug($diffidato);

            if (($diffidato['Ammonizioni'] % 3 == 0 && $diffidato['AmmonitoOggi'] == 1))
            {

                if ($diffidato['NomeSquadra'] != $calendario_arr['Match']['CasaNome'] && $diffidato['NomeSquadra'] != $calendario_arr['Match']['TrasfertaNome'])
                    continue;

                if (substr_count($diffidato['anagrafica'], "Carbonari") && $giornata == 9)
                    continue;

//print $diffidato['Atleta'];

                $squalificati[] = array(
                    'Squadra' => $diffidato['NomeSquadra'],
                    'Anagrafica' => $diffidato['anagrafica'],
                    'Periodo' => $diffidato['Ammonizioni'],
                );
            }
        }
//print $half_id . "_" . $champ_id;
//print_r($squalificati);

        $squalificati = array(
            'espulsi' => $espulsi,
            'squalificati' => $squalificati,
        );

//debug($squalificati);

        /* $atleti_casa = $this->Yearbook->find('list', array(

          'conditions' => array(
          'Yearbook.SquadraCampionato' => $calendario_arr['Match']['Casa'],
          ),
          'fields' => array('Yearbook.Atleta','Yearbook.SquadraCampionato'),
          )
          );

          $atleti_trasferta = $this->Yearbook->find('list', array(

          'conditions' => array(
          'Yearbook.SquadraCampionato' => $calendario_arr['Match']['Trasferta'],
          ),
          'fields' => array('Yearbook.Atleta','Yearbook.SquadraCampionato'),
          )
          );

          $squadra_campionato_casa = $this->SquadreCampionati->find('first', array(

          'conditions' => array(
          'SquadreCampionati.SquadraCampionato' => $calendario_arr['Match']['Casa'],
          ),

          ));

          $squadra_campionato_trasferta = $this->SquadreCampionati->find('first', array(

          'conditions' => array(
          'SquadreCampionati.SquadraCampionato' => $calendario_arr['Match']['Trasferta'],
          ),

          ));

          $atleti = $atleti_casa + $atleti_trasferta;

          $atleti_out = array();

          foreach($atleti as $atleta => $squadra_c) {

          $find_athlete = $this->Athlete->find('first', array('conditions' => array('Athlete.Atleta' => $atleta)));
          $new_key = $find_athlete['Athlete']['Cognome'] . ' ' . $find_athlete['Athlete']['Nome'];

          $matchgoal = $this->Matchgoal->find('first', array(

          'conditions' => array(
          'Matchgoal.SquadraCampionato' => $squadra_c,
          'Matchgoal.Atleta'			  => $atleta,
          'Matchgoal.Espulsione'		  => 'Si',
          ),

          ));

          if(isset($matchgoal) && !empty($matchgoal)) {

          debug($matchgoal);

          $calendario_espulsione = $this->Match->findByCalendario($matchgoal['Matchgoal']['Calendario']);
          if($matchgoal['Matchgoal']['EspulsioneGiornate'] != '') {
          $ultima_giornata_squalifica = $matchgoal['Matchgoal']['EspulsioneGiornate'] + $calendario_espulsione['Match']['Giornata'];
          if($calendario_arr['Match']['Giornata'] <= $ultima_giornata_squalifica && $calendario_arr['Match']['Calendario'] != $matchgoal['Matchgoal']['Calendario']) {
          $atleti_out[$squadra_c][$atleta]['Nome'] = $new_key;
          if($squadra_c == $squadra_campionato_casa['SquadreCampionati']['SquadraCampionato']) {
          $atleti_out[$squadra_c][$atleta]['Squadra'] = $squadra_campionato_casa['Squadre']['Denominazione'];
          } elseif($squadra_c == $squadra_campionato_trasferta['SquadreCampionati']['SquadraCampionato']) {
          $atleti_out[$squadra_c][$atleta]['Squadra'] = $squadra_campionato_trasferta['Squadre']['Denominazione'];
          }
          }
          } elseif($matchgoal['Matchgoal']['EspulsioneFine'] != '') {
          $fine_espulsione = strtotime($matchgoal['Matchgoal']['EspulsioneFine']);
          $partita_attuale = strtotime($calendario_arr['Match']['Data']);
          if($partita_attuale <= $fine_espulsione && $calendario_arr['Match']['Calendario'] != $matchgoal['Matchgoal']['Calendario']) {
          $atleti_out[$squadra_c][$atleta]['Nome'] = $new_key;
          if($squadra_c == $squadra_campionato_casa['SquadreCampionati']['SquadraCampionato']) {
          $atleti_out[$squadra_c][$atleta]['Squadra'] = $squadra_campionato_casa['Squadre']['Denominazione'];
          } elseif($squadra_c == $squadra_campionato_trasferta['SquadreCampionati']['SquadraCampionato']) {
          $atleti_out[$squadra_c][$atleta]['Squadra'] = $squadra_campionato_trasferta['Squadre']['Denominazione'];
          }
          }
          }

          }

          }

          //asort($atleti);
         * 
         */

//return $squalificati;


        return $squalificati;
    }


    function admin_searchArbitro()
    {

        $this->layout = "ajax";

        $arbitri = $this->Athlete->find('all', array(
            'conditions' =>
            array(
                array('OR' =>
                    array(
                        'Athlete.Anagrafica LIKE' => $_GET['term'] . '%',
                        'Athlete.reverseAnagrafica LIKE' => $_GET['term'] . '%'
                    ),
                    'Athlete.Arbitro' => 'Si'
                )
            ),
            'order' => 'Athlete.reverseAnagrafica ASC',
            'limit' => '15'
        ));

        $ret = array();

        foreach ($arbitri as $atleta)
        {

            $tmp['id'] = $atleta['Athlete']['Atleta'];
            $tmp['label'] = $atleta['Athlete']['reverseAnagrafica'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


    function admin_searchAtleta()
    {

        $this->layout = "ajax";

        $atleti = $this->Athlete->find('all', array(
            'conditions' =>
            array(
                array('OR' =>
                    array(
                        'Athlete.Anagrafica LIKE' => $_GET['term'] . '%',
                        'Athlete.reverseAnagrafica LIKE' => $_GET['term'] . '%'
                    ),
                )
            ),
            'order' => 'Athlete.reverseAnagrafica ASC',
            'limit' => '15'
        ));

        $ret = array();

        foreach ($atleti as $atleta)
        {

            $tmp['id'] = $atleta['Athlete']['Atleta'];
            $tmp['label'] = $atleta['Athlete']['reverseAnagrafica'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


//GIUSEPPE 10/11/2016 ---------------------------------------------------------
    function admin_searchCampionato()
    {

        $this->layout = "ajax";

        $campionatis = $this->Campionati->find('all', array(
            'conditions' =>
            array(
                'Campionati.Nome LIKE' => $_GET['term'] . '%',
            /* 'Campionati.id_sport' => $_GET['id_sport'], */
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


//-----------------------------------------------------------------------------


    function admin_searchCampionatoUso()
    {

        $this->layout = "ajax";

        $campionatis = $this->Campionati->find('all', array(
            'conditions' =>
            array(
                'AND' => array(
                    'Campionati.Nome LIKE' => $_GET['term'] . '%',
                    'Campionati.InUso' => 'Si',
                )
            ),
            'order' => 'Campionati.AnnoSportivo DESC',
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


    function admin_searchDisciplinare()
    {

        $this->layout = "ajax";

        $disciplinari = $this->Discipline->find('all', array(
            'conditions' =>
            array(
                array('OR' =>
                    array(
                        'Discipline.Descrizione LIKE' => $_GET['term'] . '%',
                        'Discipline.Sanzione LIKE' => $_GET['term'] . '%',
                        'Discipline.Punti LIKE' => $_GET['term'] . '%'
                    ),
                )
            ),
            'order' => 'Discipline.Descrizione ASC',
            'limit' => '15'
        ));

        $ret = array();

        foreach ($disciplinari as $disciplinare)
        {

            $tmp['id'] = $disciplinare['Discipline']['Disciplinare'];
            $tmp['label'] = $disciplinare['Discipline']['Descrizione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


    function admin_findDisciplinare($id)
    {

        $this->layout = "ajax";

        $disciplinare = $this->Discipline->find('first', array(
            'conditions' => array(
                'Discipline.Disciplinare' => $id
            )
        ));

        $this->set('result', json_encode($disciplinare));

        $this->render('/backend/ajaxResult');
    }


    public function getSquadreFromGirone($girone, $squadre)
    {

        $ret_squadre = array();

        foreach ($squadre as $squadra)
        {

            if ($squadra['Half']['GironeCampionato'] == $girone['Half']['GironeCampionato'])
                $ret_squadre[] = $squadra;
        }

        return $ret_squadre;
    }


    public function getPartitaUniqueId($id, $partite)
    {

        foreach ($partite as $partita)
        {

            if ($partita['unique_id'] == $id)
                return false;
        }

        return true;
    }

    /* public function setCampionati() {

      $_campionati = $this->Campionati->find('list', array(
      'fields' => array('Campionati.Campionato','Campionati.Nome'),
      'conditions' => array(
      'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),
      '(SELECT COUNT(*) FROM Calendari WHERE Calendari.Campionato = Campionati.Campionato) = 0',
      'Campionati.Italiana' => 'No'
      ),
      'order' => array('Campionati.AnnoSportivo ASC', 'Campionati.Nome DESC')

      ));

      $_campionati['default'] = 'Scegliere un campionato...';

      $_campionati = array_reverse($_campionati, true);

      $this->set('campionati',$_campionati);

      } */


    public function setCampionati()
    {

        $_campionati = $this->Campionati->find('list', array(
            'fields' => array('Campionati.Campionato', 'Campionati.Nome'),
            'conditions' => array(
//'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),
                'Campionati.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
            ),
            'order' => array('Campionati.Nome DESC')
        ));

        $_campionati['default'] = 'Scegliere un campionato...';

        $_campionati = array_reverse($_campionati, true);

        $this->set('campionati', $_campionati);
    }


    function admin_refresh()
    {

        Configure::write('debug', 0);

        $day_offsets['Lunedi'] = 0;
        $day_offsets['Martedi'] = 1;
        $day_offsets['Mercoledi'] = 2;
        $day_offsets['Giovedi'] = 3;
        $day_offsets['Venerdi'] = 4;
        $day_offsets['Sabato'] = 5;
        $day_offsets['Domenica'] = 6;

//$this->setCampionati();

        $_campionati = $this->Campionati->find('list', array(
            'fields' => array('Campionati.Campionato', 'Campionati.Nome'),
            'conditions' => array(
//'Campionati.AnnoSportivo BETWEEN ? AND ?' => array(date("Y"),date("Y")+2),
                'Campionati.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
                'Campionati.countGare' => 0,
            ),
            'order' => array('Campionati.Nome DESC')
        ));

        $_campionati['default'] = 'Scegliere un campionato...';

        $_campionati = array_reverse($_campionati, true);

        $this->set('campionati', $_campionati);

        if (empty($this->data))
        {

            $this->layout = "timmybox";
        }
        else
        {

            $this->layout = 'ajax';

            $squadre = $this->SquadreCampionati->find('all', array(
                'conditions' =>
                array(
                    'SquadreCampionati.Campionato' => $this->data['Match']['Campionato']
                ),
                'order' => 'RAND()'
            ));

            $gironi = $this->Half->find('all', array('conditions' =>
                array(
                    'Half.Campionato' => $this->data['Match']['Campionato']
                )
                    )
            );

            /* controllo prima di generare */

            $error = 0;

            foreach ($gironi as $girone)
            {

                $nr_squadre = $girone['Half']['NumeroSquadre'];
                $id_girone = $girone['Half']['GironeCampionato'];
                $campionato = $this->data['Match']['Campionato'];

                $data = $this->SquadreCampionati->find('count', array(
                    'conditions' => array(
                        'SquadreCampionati.Campionato' => $campionato,
                        'SquadreCampionati.GironeCampionato' => $id_girone
                    ),
                ));

                if ($data != $nr_squadre)
                    $error = 1;
            }

            $isGenerate = $this->Match->find('count', array(
                'conditions' => array(
                    'Match.Campionato' => $this->data['Match']['Campionato'],
                )
            ));

            if ($isGenerate > 0)
                $error = 1;

            if ($error != 1)
            { // se non ci sono errori
                $matches = array();

                foreach ($gironi as $girone)
                {

                    $squadre_girone = $this->getSquadreFromGirone($girone, $squadre);

                    if ($this->data['Match']['Tipologia'] == 'AR')
                        $giornate = generateRoundRobinPairings($squadre_girone, 1);
                    else
                        $giornate = generateRoundRobinPairings($squadre_girone, 0);

                    $week_offset = 0;
                    $p = 0;
                    foreach ($giornate as $giornata => $partite)
                    {

                        foreach ($partite as $partita)
                        {

                            if (isset($squadre_girone[$partita[0]]) && isset($squadre_girone[$partita[1]]))
                            {

                                $casa = $squadre_girone[$partita[0]];
                                $trasferta = $squadre_girone[$partita[1]];

                                $match['Data'] = $girone['Half']['DataInizio'];

                                $giorno = str_replace("ì", "i", $casa['SquadreCampionati']['Giorno']);

                                $match['Giorno'] = $giorno;
                                /*
                                  print $giorno;
                                  exit; */

                                $time = strtotime('+ ' . ($giornata + $week_offset - 1) . ' week', strtotime($match['Data']));

                                $time = strtotime('+ ' . ($day_offsets[$match['Giorno']]) . ' day', $time);

                                $match['Data'] = date('Y-m-d', $time);

                                $match['Ora'] = $casa['SquadreCampionati']['Ora'];
                                $match['Campo'] = $casa['SquadreCampionati']['Campo'];

                                $match['Giornata'] = $giornata;

                                $match['Casa'] = $casa['SquadreCampionati']['SquadraCampionato'];
                                $match['Trasferta'] = $trasferta['SquadreCampionati']['SquadraCampionato'];

                                $match['Campionato'] = $this->data['Match']['Campionato'];
                                $match['GironeCampionato'] = $casa['SquadreCampionati']['GironeCampionato'];

                                $day_offset = 0;

                                $weekofyear = date("W", $time);
                                $weekofyear--;

                                while ($this->Notgame->find('count', array('conditions' => array('Data' => $match['Data']))) > 0)
                                {

                                    if ($match['Data'] == '2014-01-06')
                                        $weekofyear = 2;

//var_dump("$p) " . $match['Data'] . "  in un giorno di non gioco");

                                    $noweek = $this->Notgame->find('count', array('conditions' => array('WEEK(Data) = ' . ($weekofyear))));

                                    $wk = mysql_query("SELECT WEEK('" . $match['Data'] . "') as wk");
                                    $wk = mysql_fetch_assoc($wk);
                                    $wk = $wk['wk'];

//var_dump("wk: $wk $weekofyear ($noweek)"); 

                                    if ($noweek > 4)
                                    {
                                        $week_offset++;
//var_dump("$p) " . $match['Data'] . "  in un giorno di non gioco, sposto la settimana");
                                    }
                                    else
                                    {
                                        $day_offset++;

//var_dump("$p) " . $match['Data'] . " non settimana di non gioco, sposto avanti di 1 giorno");
                                    }

                                    $time = strtotime('+ ' . ($giornata + $week_offset - 1) . ' week', strtotime($girone['Half']['DataInizio']));

                                    $time = strtotime('+ ' . ($day_offsets[$match['Giorno']] + $day_offset) . ' day', $time);

                                    $match['Data'] = date('Y-m-d', $time);

//var_dump("$p) " . $match['Data'] . " <-- risultato");
                                }
                                /*
                                  if ($this->Notgame->find('count',array('conditions' => array('Data' => $match['Data']))) > 0)
                                  $match['Data']			 = '0000-00-00';
                                  else */
                                $match['Festivo'] = 'N';

                                if (!empty($match['Casa']) && !empty($match))
                                    $matches[] = $match;


                                $p++;
                            }
                        }
                    }
                }


                $matches = array_orderby($matches, 'Data', SORT_ASC, 'Ora', SORT_ASC);

                foreach ($matches as $i => $match)
                {

                    $matches[$i]['Partita'] = $i + 1;
                }


                $okResult = true;

                foreach ($matches as $match)
                {

                    $this->Match->create();

                    /* Controllo data, ora, campo */
                    $data_count = $this->Match->find('count', array(
                        'conditions' => array(
                            'Match.Data' => $match['Data'],
                            'Match.Ora' => $match['Ora'],
                            'Match.Campo' => $match['Campo'],
                        )
                    ));

                    if ($data_count != 0)
                    {

                        $match['Ora'] = 'null';
                        $match['Campo'] = '0';
                    }
                    /**/

                    $this->data = $match;

                    $this->Match->set($this->data);

                    $this->Match->unbindValidation('remove', array('Data'), false);

                    if (!$this->Match->save())
                    {
                        $okResult = false;
                    }
                }
            }

            $this->set('result', json_encode(array('result' => (isset($okResult)) ? $okResult : false)));

            $this->render('/backend/ajaxResult');
        }
    }

    /*
      function admin_refresh() {

      $day_offsets['Lunedi'] = 0;
      $day_offsets['Martedi'] = 1;
      $day_offsets['Mercoledi'] = 2;
      $day_offsets['Giovedi'] = 3;
      $day_offsets['Venerdi'] = 4;
      $day_offsets['Sabato'] = 5;
      $day_offsets['Domenica'] = 6;

      $this->setCampionati();

      if (empty($this->data)) {

      $this->layout = "timmybox";

      } else {

      $this->layout = 'ajax';

      $squadre = $this->SquadreCampionati->find('all',array(

      'conditions' =>
      array(
      'SquadreCampionati.Campionato' => $this->data['Match']['Campionato']
      ),
      'order' => 'RAND()'

      ));

      $gironi = $this->Half->find('all',

      array('conditions' =>

      array(
      'Half.Campionato' => $this->data['Match']['Campionato']
      )

      )

      );

      $andata 	= array();
      $ritorno 	= array();
      $giornate_play = array();
      foreach ($gironi as $girone) {


      $ritorno_start = '1980-01-01 00:00:00';

      $squadre_girone = $this->getSquadreFromGirone($girone,$squadre);

      // ANDATA

      foreach ($squadre_girone as $squadra) {

      $giornata = 1;

      $alterna = 'Casa';
      $n_alterna = 'Trasferta';


      foreach ($squadre_girone as $avversario) {



      if ($squadra['Squadre']['Squadra'] != $avversario['Squadre']['Squadra']) {


      $partita['unique_id'] 		 =  0;
      $partita['Giornata'] 		 =  $giornata;
      $partita['Campionato']		 =  $this->data['Match']['Campionato'];

      $partita[$alterna] 			 = 	$squadra['SquadreCampionati']['SquadraCampionato'];
      $partita[$n_alterna] 		 =	$avversario['SquadreCampionati']['SquadraCampionato'];

      $info[$alterna] = $squadra;
      $info[$n_alterna] = $avversario;

      $partita['Data'] = $girone['Half']['DataInizio'];

      $giorno = str_replace("","i",$info['Casa']['SquadreCampionati']['Giorno']);

      $partita['Giorno'] = $giorno;
      //$partita['Giorno_Offset'] = $day_offsets[$partita['Giorno']];

      $time = strtotime('+ ' . ($giornata-1) .  ' week', strtotime($partita['Data']));

      $time = strtotime('+ ' . ($day_offsets[$partita['Giorno']]) . ' day',$time);

      $partita['Data'] = date('Y-m-d', $time);

      if (strtotime($partita['Data'] . " 00:00:00") > strtotime($ritorno_start)) $ritorno_start = $partita['Data'] . " 00:00:00";

      if ($squadra['Squadre']['Squadra'] < $avversario['Squadre']['Squadra']) {

      $partita['unique_id'] = $squadra['Squadre']['Squadra'] . "-" . $avversario['Squadre']['Squadra'];

      } else {
      $partita['unique_id'] = $avversario['Squadre']['Squadra'] . "-" . $squadra['Squadre']['Squadra'];
      }

      if ($this->getPartitaUniqueId($partita['unique_id'],$andata) == true) {

      $partita['GironeCampionato'] =  $squadra['SquadreCampionati']['GironeCampionato'];
      $partita['Campo']		 	 = 	$info['Casa']['SquadreCampionati']['Campo'];
      $partita['Ora'] 			 = 	$info['Casa']['SquadreCampionati']['Ora'];

      if ($this->Notgame->find('count',array('conditions' => array('Data' => $partita['Data']))) > 0)
      $partita['Festivo']			 = 'S';
      else
      $partita['Festivo']			 = 'N';


      $giornata++;

      if ($alterna == 'Casa') $alterna = 'Trasferta';
      else $alterna = 'Casa';

      if ($alterna == 'Casa') $n_alterna = 'Trasferta';
      else 					$n_alterna = 'Casa';

      $already_played = false;

      if (!isset($giornate_play[$giornata])) $giornate_play[$giornata] = array();



      for ($g = 0; $g < count($giornate_play[$giornata]);$g++) {

      if ($giornate_play[$giornata][$g] == $avversario['Squadre']['Squadra']  || $giornate_play[$giornata][$g] == $squadra['Squadre']['Squadra'])
      $already_played = true;

      }
      if ($already_played == false) {

      $andata[] = $partita;

      $giornate_play[$giornata][] = $avversario['Squadre']['Squadra'];
      $giornate_play[$giornata][] = $squadra['Squadre']['Squadra'];

      }

      }

      }

      }

      $last_andata = $giornata;

      if ($this->data['Match']['Tipologia'] == 'AR') {

      foreach ($squadre_girone as $avversario) {

      $giornata = $last_andata;


      $alterna = 'Trasferta';
      $n_alterna = 'Casa';


      if ($squadra['Squadre']['Squadra'] != $avversario['Squadre']['Squadra']) {





      $partita['unique_id'] 		 =  0;
      $partita['Giornata'] 		 =  $giornata;
      $partita['Campionato']		 =  $this->data['Match']['Campionato'];

      $partita[$alterna] 			 = 	$squadra['SquadreCampionati']['SquadraCampionato'];
      $partita[$n_alterna] 		 =	$avversario['SquadreCampionati']['SquadraCampionato'];

      $info[$alterna] = $squadra;
      $info[$n_alterna] = $avversario;


      $ritorno_time = strtotime($ritorno_start);
      $ritorno_time = strtotime('next monday',$ritorno_time);

      $partita['Data'] = date("Y-m-d H:i:s",$ritorno_time);

      $giorno = str_replace("","i",$info['Casa']['SquadreCampionati']['Giorno']);

      $partita['Giorno'] = $giorno;
      //$partita['Giorno_Offset'] = $day_offsets[$partita['Giorno']];

      $time = strtotime('+ ' . ($giornata-1) .  ' week', strtotime($partita['Data']));

      $time = strtotime('+ ' . ($day_offsets[$partita['Giorno']]) . ' day',$time);

      $partita['Data'] = date('Y-m-d', $time);

      if ($squadra['Squadre']['Squadra'] < $avversario['Squadre']['Squadra']) {

      $partita['unique_id'] = $squadra['Squadre']['Squadra'] . "-" . $avversario['Squadre']['Squadra'];

      } else {
      $partita['unique_id'] = $avversario['Squadre']['Squadra'] . "-" . $squadra['Squadre']['Squadra'];
      }

      if ($this->getPartitaUniqueId($partita['unique_id'],$ritorno) == true) {

      $partita['GironeCampionato'] =  $squadra['SquadreCampionati']['GironeCampionato'];
      $partita['Campo']		 	 = 	$info['Casa']['SquadreCampionati']['Campo'];
      $partita['Ora'] 			 = 	$info['Casa']['SquadreCampionati']['Ora'];

      if ($this->Notgame->find('count',array('conditions' => array('Data' => $partita['Data']))) > 0)
      $partita['Festivo']			 = 'S';
      else
      $partita['Festivo']			 = 'N';


      $giornata++;

      if ($alterna == 'Casa') $alterna = 'Trasferta';
      else $alterna = 'Casa';

      if ($alterna == 'Casa') $n_alterna = 'Trasferta';
      else 					$n_alterna = 'Casa';

      $already_played = false;

      if (!isset($giornate_play[$giornata])) $giornate_play[$giornata] = array();
      for ($g = 0; $g < count($giornate_play[$giornata]);$g++) {

      if ($giornate_play[$giornata][$g] == $avversario['Squadre']['Squadra']  || $giornate_play[$giornata][$g] == $squadra['Squadre']['Squadra'])
      $already_played = true;

      }
      if ($already_played == false) {

      $ritorno[] = $partita;


      $giornate_play[$giornata][] = $avversario['Squadre']['Squadra'];
      $giornate_play[$giornata][] = $squadra['Squadre']['Squadra'];

      }

      }

      }

      }

      }



      }



      }


      $partite = array_merge($andata,$ritorno);

      $okResult = true;

      foreach ($partite as $partita) {

      $this->Match->create();

      $this->data = $partita;

      $this->Match->set($this->data);

      if (!$this->Match->save()) $okResult = false;

      }




      $this->set('result',json_encode(array('result' => $okResult)));

      $this->render('/backend/ajaxResult');


      }

      }
     */


    function admin_searchGirone($id_campionato)
    {

        $this->layout = "ajax";

        $halfs = $this->Half->find('all', array(
            'conditions' =>
            array(
                'Half.Campionato' => $id_campionato,
                'Half.Descrizione LIKE' => $_GET['term'] . '%',
            ),
            'order' => 'Half.Descrizione ASC',
            'limit' => '15',
        ));

        $ret = array();

        foreach ($halfs as $half)
        {

            $tmp['id'] = $half['Half']['GironeCampionato'];
            $tmp['label'] = $half['Half']['Descrizione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


// function admin_searchSquadra() {
// $this->layout = "ajax";
// $squadre = $this->Squadre->find('all',array(
// 'conditions' =>
// array(
// 'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
// ),
// 'limit' => '15'
// ));
// $ret = array();
// foreach ($squadre as $squadra) {
// $tmp['id'] = $squadra['Squadre']['Squadra'];
// $tmp['label'] = $squadra['Squadre']['Denominazione'];
// $ret[] = $tmp;
// }
// $this->set('result',json_encode($ret));
// $this->render('/backend/ajaxResult');
// }


    function admin_searchSquadra($id_sport)
    { //GIUSEPPE 11/10/2016 inserito filtro per id_sport
        $this->layout = "ajax";

        $squadre = $this->Squadre->find('all', array(
            'conditions' =>
            array(
                'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
                , 'Squadre.id_sport' => $id_sport
            ),
            'limit' => '15'
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


    function admin_searchSquadraCampionato($id_campionato, $id_girone)
    {

        $this->layout = "ajax";

        $squadrec = $this->SquadreCampionati->find('all', array(
            'conditions' =>
            array(
                'Campionati.Campionato' => $id_campionato,
                'Half.GironeCampionato' => $id_girone,
                'Squadre.Denominazione LIKE' => $_GET['term'] . '%'
            ),
            'limit' => '15'
        ));

        $ret = array();

        foreach ($squadrec as $squadrac)
        {

            $tmp['id'] = $squadrac['SquadreCampionati']['SquadraCampionato'];
            $tmp['label'] = $squadrac['Squadre']['Denominazione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


    function admin_searchCampo()
    {

        $this->layout = "ajax";

        $campi = $this->Campi->find('all', array(
            'conditions' =>
            array(
                'Campi.Descrizione LIKE' => $_GET['term'] . '%',
            ),
            'order' => 'Campi.Descrizione ASC',
            'limit' => '15',
        ));

        $ret = array();

        foreach ($campi as $campo)
        {

            $tmp['id'] = $campo['Campi']['Campo'];
            $tmp['label'] = $campo['Campi']['Descrizione'];

            $ret[] = $tmp;
        }

        $this->set('result', json_encode($ret));

        $this->render('/backend/ajaxResult');
    }


    function admin_searchCampoByCampionato($campionato = null)
    {

        $this->layout = "ajax";

        $campi = $this->SquadreCampionati->find('all', array(
            'conditions' => array(
                'SquadreCampionati.Campionato' => $campionato,
            //'Campi.Descrizione LIKE'	   => $_GET['term'] . '%', 
            ),
        ));

        $ret = array();

        $campionato = $this->Campicampionati->find('all', array(
            'conditions' => array(
                'Campicampionati.Campionato' => $campionato,
            //'Campicampionati.NomeCampo LIKE'   => $_GET['term'] . '%', 
            ),
        ));

        foreach ($campi as $campo)
        {

            $tmp['id'] = $campo['Campi']['Campo'];
            $tmp['label'] = $campo['Campi']['Descrizione'];

            $ret[$campo['Campi']['Campo']] = $tmp;
        }

        if (count($campionato))
        {

            foreach ($campionato as $campo)
            {

                $tmp['id'] = $campo['Campicampionati']['Campo'];
                $tmp['label'] = $campo['Campicampionati']['NomeCampo'];

                $ret[$campo['Campicampionati']['Campo']] = $tmp;
            }
        }

        $ret[0] = array('id' => 0, 'label' => '');

        $ret = array_merge($ret);

        $ret = array_orderby($ret, 'label', SORT_ASC);

        $this->set('result', json_encode($ret));
        $this->render('/backend/ajaxResult');
    }


    function admin_getOre($campionato = null)
    {

        $this->layout = "ajax";

        $preferenze = $this->SquadreCampionati->find('all', array(
            'conditions' => array(
                'SquadreCampionati.Campionato' => $campionato,
                'SquadreCampionati.Ora LIKE' => $_GET['term'] . '%',
            ),
        ));

        $supplementari = $this->Campicampionati->find('all', array(
            'conditions' => array(
                'Campicampionati.Campionato' => $campionato,
                'Campicampionati.Ora LIKE' => $_GET['term'] . '%',
            ),
        ));

        $ore = array();

        foreach ($preferenze as $pref)
        {

            $tmp['id'] = $pref['SquadreCampionati']['Ora'];
            $tmp['label'] = $pref['SquadreCampionati']['Ora'];

            $ore[$pref['SquadreCampionati']['Ora']] = $tmp;
        }

        foreach ($supplementari as $pref)
        {

            $tmp['id'] = $pref['Campicampionati']['Ora'];
            $tmp['label'] = $pref['Campicampionati']['Ora'];

            $ore[$pref['Campicampionati']['Ora']] = $tmp;
        }

        $hour = array();

        foreach ($ore as $k => $ora)
        {

            $hour[] = $ora;
        }

        $this->set('result', json_encode($hour));
        $this->render('/backend/ajaxResult');
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

            $data = array();

            $this->Session->write($this->name . ".searchData", $this->data);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }

        if ($this->Session->check($this->name . ".searchData", $this->data))
        {

            $this->data = $this->Session->read($this->name . ".searchData");
        }
    }


    function admin_add()
    {

        $this->layout = "ajax";
        $this->set('causali', $this->Causalresult->find('all'));

        if (!empty($this->data))
        {

            if (!empty($this->data['Match']['Data']))
                $this->dmy2ymd($this->data['Match']['Data']);

            $Data = $this->data['Match']['Data'];

            $this->Match->set($this->data);

            $campionato = ($this->data['Match']['Campionato'] != '') ? $this->data['Match']['Campionato'] : 0;
            $arbitro = ($this->data['Match']['Arbitro'] != '') ? $this->data['Match']['Arbitro'] : 0;
            $casa = ($this->data['Match']['Casa'] != '') ? $this->data['Match']['Casa'] : 0;
            $trasferta = ($this->data['Match']['Trasferta'] != '') ? $this->data['Match']['Trasferta'] : 0;

            $countCasa = $this->Match->query("
					SELECT COUNT(*) as tot FROM LDA as Lda 
					WHERE Lda.Campionato = $campionato 
					AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
					AND (Lda.Casa = $casa OR Lda.Trasferta = $casa)
					");

            $countTrasferta = $this->Match->query("
					SELECT COUNT(*) as tot FROM LDA as Lda 
					WHERE Lda.Campionato = $campionato 
					AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
					AND (Lda.Casa = $trasferta OR Lda.Trasferta = $trasferta)
					");

            $partiteTrasferta = $countTrasferta[0][0]['tot'];
            $partiteCasa = $countCasa[0][0]['tot'];

            if (($partiteCasa > 3 || $partiteTrasferta > 3) && $arbitro != 0)
            {

                $this->Match->invalidate('ArbitroSearch', 'L\' Arbitro ha gi arbitrato una delle squadre pi di 3 volte.');
            }
            else
            {
                /* Controllo combinazioni DATA/CAMPO/ORA */

                $tmp_data = $this->data['Match']['Data'];
                $this->dmy2ymd($tmp_data);
                $days = array('Domenica', 'Luned', 'Marted', 'Mercoled', 'Gioved', 'Venerd', 'Sabato');

                $time = strtotime($tmp_data);
                $tmp_d = date("w", $time);

                $giorno = $days[$tmp_d];

                $data_pref = $this->SquadreCampionati->find('all', array(
                    'conditions' => array(
                        'SquadreCampionati.Campionato' => $campionato,
                    ),
                ));

                $tot_pref = array();
                foreach ($data_pref as $pref)
                {
                    $tot_pref[] = $pref['SquadreCampionati']['Giorno'] . '|' . $pref['SquadreCampionati']['Ora'] . '|' . $pref['SquadreCampionati']['Campo'];
                }
                $camp_data = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                foreach ($camp_data['Campicampionati'] as $tmp)
                {
                    $tot_pref[] = $tmp['Giorno'] . '|' . $tmp['Ora'] . '|' . $tmp['Campo'];
                }

                $combinazione = $giorno . '|' . $this->data['Match']['Ora'] . '|' . $this->data['Match']['Campo'];
                // $this->controllaOrarioCampo($this);

                if ($this->Match->save())
                {

                    $this->Lda->set('Arbitro', $this->data['Match']['Arbitro']);
                    $importo_arbitro = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);

                    $this->Lda->set('Arbitro2', $this->data['Match']['Arbitro2']);
                    $importo_arbitro2 = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoArbitro2', $importo_arbitro2['Campionati']['TariffaArbitro2']);

                    $this->Lda->set('Delegato', $this->data['Match']['Delegato']);
                    $importo_delegato = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoDelegato', $importo_delegato['Campionati']['TariffaDelegato']);

                    $this->Lda->set('DelegatoA', $this->data['Match']['DelegatoA']);
                    $importo_delegatoA = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoDelegato', $importo_delegatoA['Campionati']['TariffaDelegatoA']);

                    $this->Lda->set('Campionato', $this->data['Match']['Campionato']);
                    $this->Lda->set('Data', $Data);
                    $this->Lda->set('Ora', $this->data['Match']['Ora']);
                    $this->Lda->set('Casa', $this->data['Match']['Casa']);
                    $this->Lda->set('Trasferta', $this->data['Match']['Trasferta']);
                    $this->Lda->set('Campo', $this->data['Match']['Campo']);

                    $this->Lda->save();

                    $last_lda = $this->Lda->id;
                    $this->Match->set('lda_id', $last_lda);
                    $this->Match->save();

                    $ADD_OK = true;

                    if ($ADD_OK)
                    {

                        $this->set('result', 'ADD_OK');
                        $this->render('/backend/ajaxResult');
                    }
                }
            }
        }
    }


    //GIUSEPPE 2023-06-24 ----------------------------
    private function controllaOrarioCampo($this_)
    {
        $values = $this_->data['Match'];
//        print_r($values);
        $data = $values['Data'];
        $ora = str_replace(".", ":", $values['Ora']) . ":00";
        $campo = $values['Campo'];

        $dayOfWeek = date('w', strtotime($data)) == 0 ? "7" : date('w', strtotime($data));
        $query = "SELECT COUNT(Ora), Ora as exist FROM CampiOrari WHERE campo_id = '{$campo}' AND Ora = '{$ora}' AND Giorno = '{$dayOfWeek}'";
        $num = $this->select_sql($query)[0]['exist'];

        
        $queryCampo = "SELECT Descrizione FROM Campi WHERE Campo = '{$campo}'";
        $nomeCampo = $this->select_sql($queryCampo)[0]['Descrizione'];
//        $nomeCampo = "";
        if ($num == 0)
        {
            $orariArray = [];
            $this->orarioPrima($campo, $ora, $dayOfWeek, $orariArray);
            $this->orarioDopo($campo, $ora, $dayOfWeek, $orariArray);

            if (count($orariArray) == 0)
            {
                $this_->Match->invalidate('Ora', "Ora non valida per '{$nomeCampo}'");
            }
            if (count($orariArray) == 1)
            {
                $orario = $orariArray[0];
                $this_->Match->invalidate('Ora', "Orario disponibile per '{$nomeCampo}': {$orario}");
            }
            if (count($orariArray) == 2)
            {
                $orario = implode(", ", $orariArray);
                $this_->Match->invalidate('Ora', "Orari disponibili '{$nomeCampo}': {$orario}");
            }
        }
    }


    private function orarioPrima($campo, $ora, $dayOfWeek, &$orariArray)
    {
        $query = "SELECT  Ora FROM CampiOrari WHERE campo_id = '{$campo}' AND Ora < '{$ora}' AND Giorno = '{$dayOfWeek}' ORDER BY Ora DESC LIMIT 1 ";
        $num = $this->select_sql($query);
        $this->write_file("_orarioPrima", $query);
        if (count($num) > 0)
            $orariArray[] = date('H:i', strtotime($num[0]['Ora']));
    }


    private function orarioDopo($campo, $ora, $dayOfWeek, &$orariArray)
    {
        $query = "SELECT  Ora FROM CampiOrari WHERE campo_id = '{$campo}' AND Ora > '{$ora}' AND Giorno = '{$dayOfWeek}' ORDER BY Ora ASC LIMIT 1 ";
        $num = $this->select_sql($query);
        $this->write_file("_orarioDopo", $query);
        if (count($num) > 0)
            $orariArray[] = date('H:i', strtotime($num[0]['Ora']));
    }


    // -----------------------------------------------

    function _admin_add()
    {

        $this->layout = "ajax";
        $this->set('causali', $this->Causalresult->find('all'));

        if (!empty($this->data))
        {

            if (!empty($this->data['Match']['Data']))
                $this->dmy2ymd($this->data['Match']['Data']);

            $Data = $this->data['Match']['Data'];

            $this->Match->set($this->data);

            $campionato = ($this->data['Match']['Campionato'] != '') ? $this->data['Match']['Campionato'] : 0;
            $arbitro = ($this->data['Match']['Arbitro'] != '') ? $this->data['Match']['Arbitro'] : 0;
            $casa = ($this->data['Match']['Casa'] != '') ? $this->data['Match']['Casa'] : 0;
            $trasferta = ($this->data['Match']['Trasferta'] != '') ? $this->data['Match']['Trasferta'] : 0;

            $countCasa = $this->Match->query("
					SELECT COUNT(*) as tot FROM LDA as Lda 
					WHERE Lda.Campionato = $campionato 
					AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
					AND (Lda.Casa = $casa OR Lda.Trasferta = $casa)
					");

            $countTrasferta = $this->Match->query("
					SELECT COUNT(*) as tot FROM LDA as Lda 
					WHERE Lda.Campionato = $campionato 
					AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
					AND (Lda.Casa = $trasferta OR Lda.Trasferta = $trasferta)
					");

            $partiteTrasferta = $countTrasferta[0][0]['tot'];
            $partiteCasa = $countCasa[0][0]['tot'];

            if (($partiteCasa > 3 || $partiteTrasferta > 3) && $arbitro != 0)
            {

                $this->Match->invalidate('ArbitroSearch', 'L\' Arbitro ha gi arbitrato una delle squadre pi di 3 volte.');
            }
            else
            {

                /* Controllo combinazioni DATA/CAMPO/ORA */

                $tmp_data = $this->data['Match']['Data'];
                $this->dmy2ymd($tmp_data);
                $days = array('Domenica', 'Luned', 'Marted', 'Mercoled', 'Gioved', 'Venerd', 'Sabato');

                $time = strtotime($tmp_data);
                $tmp_d = date("w", $time);

                $giorno = $days[$tmp_d];

                $data_pref = $this->SquadreCampionati->find('all', array(
                    'conditions' => array(
//'SquadreCampionati.GironeCampionato' => $this->data['Match']['GironeCampionato'],
                        'SquadreCampionati.Campionato' => $campionato,
                    ),
                ));

                $tot_pref = array();
                foreach ($data_pref as $pref)
                {
                    $tot_pref[] = $pref['SquadreCampionati']['Giorno'] . '|' . $pref['SquadreCampionati']['Ora'] . '|' . $pref['SquadreCampionati']['Campo'];
                }
                $camp_data = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                foreach ($camp_data['Campicampionati'] as $tmp)
                {
                    $tot_pref[] = $tmp['Giorno'] . '|' . $tmp['Ora'] . '|' . $tmp['Campo'];
                }

                $combinazione = $giorno . '|' . $this->data['Match']['Ora'] . '|' . $this->data['Match']['Campo'];
                /*
                  if(!in_array($combinazione, $tot_pref)) {
                  $this->Match->invalidate('Campo', 'Campo non corretto.');
                  $this->Match->invalidate('Ora', 'Orario non corretto.');
                  $this->Match->invalidate('Data', 'Data non corretta.');
                  $this->data['Lda']['Arbitro'] = $this->data['Match']['Arbitro'];
                  $this->data['Lda']['Arbitro2'] = $this->data['Match']['Arbitro2'];
                  $this->data['Lda']['Delegato'] = $this->data['Match']['Delegato'];
                  $this->data['Lda']['DelegatoA'] = $this->data['Match']['DelegatoA'];
                  return false;
                  }
                 */
                /* ------------------------------------- */

                if ($this->Match->save())
                {

                    $this->Lda->set('Arbitro', $this->data['Match']['Arbitro']);
                    $importo_arbitro = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoArbitro', $importo_arbitro['Campionati']['TariffaArbitro']);
//$this->Lda->set('PagatoArbitro', $PagatoArbitro);	

                    $this->Lda->set('Arbitro2', $this->data['Match']['Arbitro2']);
                    $importo_arbitro2 = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoArbitro2', $importo_arbitro2['Campionati']['TariffaArbitro2']);
//$this->Lda->set('PagatoArbitro2',$PagatoArbitro2);

                    $this->Lda->set('Delegato', $this->data['Match']['Delegato']);
                    $importo_delegato = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoDelegato', $importo_delegato['Campionati']['TariffaDelegato']);
//$this->Lda->set('PagatoDelegato',$PagatoDelegato);

                    $this->Lda->set('DelegatoA', $this->data['Match']['DelegatoA']);
                    $importo_delegatoA = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
                    $this->Lda->set('ImportoDelegato', $importo_delegatoA['Campionati']['TariffaDelegatoA']);
//$this->Lda->set('PagatoDelegatoA',$PagatoDelegatoA);

                    $this->Lda->set('Campionato', $this->data['Match']['Campionato']);
                    $this->Lda->set('Data', $Data);
                    $this->Lda->set('Ora', $this->data['Match']['Ora']);
                    $this->Lda->set('Casa', $this->data['Match']['Casa']);
                    $this->Lda->set('Trasferta', $this->data['Match']['Trasferta']);
                    $this->Lda->set('Campo', $this->data['Match']['Campo']);

                    $this->Lda->save();

                    $last_lda = $this->Lda->id;
                    $this->Match->set('lda_id', $last_lda);
                    $this->Match->save();

                    $ADD_OK = true;

                    if ($ADD_OK)
                    {

                        $this->set('result', 'ADD_OK');
                        $this->render('/backend/ajaxResult');
                    }
                }
            }
        }
    }


    function admin_edit($id)
    {

        $this->layout = "ajax";

//Configure::Write('debug',2);
//GIUSEPPE -- 10/11/2016
//$id -> è l'id di calendario

        $this->set('causali', $this->Causalresult->find('all'));
        $this->set('id_campionato', $id);

        $this->set('squalificati', $this->getSqualificatiByCalendario($id)); //"getSqualificatiByCalendario" questa è una funzione che troviamo in questa pagina

        $group_id = $this->Auth->user('group_id');

        $this->set('group_id', $group_id);
        /* Goal e dati calendario */

        $calendario = $this->Match->findByCalendario($id); // Match lo troviamo in "models/match.php"
        $data = $this->Matchgoal->find('all', array('conditions' => array('Matchgoal.Calendario' => $id), 'order' => array('Matchgoal.SquadraCampionato ASC', 'Athlete.Cognome ASC', 'Athlete.Nome ASC')));
        $this->set('goals', $data);
        $this->set('calendario', $calendario);

        $data = $this->Disciplinari->find('all', array(
            'conditions' => array(
                'Disciplinari.SquadraCampionato' => array($calendario['Match']['Casa'], $calendario['Match']['Trasferta']),
                'Disciplinari.Calendario' => $id
            ),
            'order' => 'Disciplinari.SquadraCampionato ASC'
                )
        );

        $this->set('disciplinari', $data);

        /* Fine Goal e dati calendario */

        if (empty($this->data))
        {

            $this->data = $this->Match->find('first', array('conditions' => array('Match.Calendario' => $id)));

            $this->data['Match']['Data'] = $this->data['Match']['Data_it'];
            $this->data['Match']['CampionatoSearch'] = $this->data['Campionati']['Nome'];
            $this->data['Match']['GironeSearch'] = $this->data['Half']['Descrizione'];
            $this->data['Match']['SquadraCasaSearch'] = $this->data['Match']['CasaNome'];
            $this->data['Match']['SquadraTrasfertaSearch'] = $this->data['Match']['TrasfertaNome'];
            $this->data['Match']['CampoSearch'] = $this->data['Campi']['Descrizione'];

            $this->data['Match']['LDA'] = $this->data['Lda']['LDA'];

            $this->data['Match']['Arbitro'] = $this->data['Lda']['Arbitro'];
            $this->data['Match']['ArbitroSearch'] = $this->data['Match']['NomeArbitro'];
            $this->data['Match']['Arbitro2'] = $this->data['Lda']['Arbitro2'];
            $this->data['Match']['Arbitro2Search'] = $this->data['Match']['NomeArbitro2'];
            $this->data['Match']['Delegato'] = $this->data['Lda']['Delegato'];
            $this->data['Match']['DelegatoSearch'] = $this->data['Match']['NomeDelegato'];
            $this->data['Match']['DelegatoA'] = $this->data['Lda']['DelegatoA'];
            $this->data['Match']['DelegatoASearch'] = $this->data['Match']['NomeDelegatoA'];

            $this->Match->set($this->data);
        }
        else
        {

            $this->Match->set($this->data);

            $campionato = $this->data['Match']['Campionato'];
            $arbitro = $this->data['Match']['Arbitro'];
            $casa = $this->data['Match']['Casa'];
            $trasferta = $this->data['Match']['Trasferta'];

            if ($arbitro != '')
            {

                $countCasa = $this->Match->query("
						SELECT COUNT(*) as tot FROM LDA as Lda 
						WHERE Lda.Campionato = $campionato 
						AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
						AND (Lda.Casa = $casa OR Lda.Trasferta = $casa)
						");

                $countTrasferta = $this->Match->query("
						SELECT COUNT(*) as tot FROM LDA as Lda 
						WHERE Lda.Campionato = $campionato 
						AND (Lda.Arbitro = $arbitro OR Lda.Arbitro2 = $arbitro)
						AND (Lda.Casa = $trasferta OR Lda.Trasferta = $trasferta)
						");

                $partiteTrasferta = $countTrasferta[0][0]['tot'];
                $partiteCasa = $countCasa[0][0]['tot'];
            }
            else
            {
                $partiteCasa = 0;
                $partiteTrasferta = 0;
            }

            if ($partiteCasa >= 3 || $partiteTrasferta >= 3)
            {

                $this->set('ArbitroSearchError', 'L\' Arbitro ha gi arbitrato una delle squadre pi di 3 volte.');

//return false;
            }

            $tmp_data = $this->data['Match']['Data'];
            $this->dmy2ymd($tmp_data);

            $days = array('Domenica', 'Luned', 'Marted', 'Mercoled', 'Gioved', 'Venerd', 'Sabato');

            $time = strtotime($tmp_data);
            $tmp_d = date("w", $time);

            $giorno = $days[$tmp_d];

            $data_pref = $this->SquadreCampionati->find('all', array(
                'conditions' => array(
//'SquadreCampionati.GironeCampionato' => $this->data['Match']['GironeCampionato'],
                    'SquadreCampionati.Campionato' => $campionato,
                ),
            ));

            $tot_pref = array();
            foreach ($data_pref as $pref)
            {
                $tot_pref[] = substr($pref['SquadreCampionati']['Giorno'], 0, 4) . '|' . $pref['SquadreCampionati']['Ora'] . '|' . $pref['SquadreCampionati']['Campo'];
            }
            $camp_data = $this->Campionati->findByCampionato($this->data['Match']['Campionato']);
            foreach ($camp_data['Campicampionati'] as $tmp)
            {
                $tot_pref[] = substr($tmp['Giorno'], 0, 4) . '|' . $tmp['Ora'] . '|' . $tmp['Campo'];
            }

            $combinazione = substr($giorno, 0, 4) . '|' . $this->data['Match']['Ora'] . '|' . $this->data['Match']['Campo'];

            if (!in_array($combinazione, $tot_pref))
            {
                $this->Match->invalidate('Campo', 'Campo non corretto.');
                $this->Match->invalidate('Ora', 'Orario non corretto.');
                $this->Match->invalidate('Data', 'Data non corretta.');
                $this->data['Lda']['Arbitro'] = $this->data['Match']['Arbitro'];
                $this->data['Lda']['Arbitro2'] = $this->data['Match']['Arbitro2'];
                $this->data['Lda']['Delegato'] = $this->data['Match']['Delegato'];
                $this->data['Lda']['DelegatoA'] = $this->data['Match']['DelegatoA'];
                return false;
            }

            $this->Match->unbindValidation('remove', array('ArbitroSearch'), false);

            /* ------------------------------------- */

            if ($this->Match->save())
            {

                $lda_id = $this->data['Match']['LDA'];

                if ($lda_id != '')
                {

                    $this->data = array_merge($this->data, $this->Lda->find('first', array('conditions' => array('LDA' => $lda_id))));
                }
                else
                {

                    $this->data = array_merge($this->data, $this->Lda->find('first'));
                    svuota($this->data['Lda']);
                }

                $this->data['Lda']['Arbitro'] = $this->data['Match']['Arbitro'];

                $this->data['Lda']['Arbitro2'] = $this->data['Match']['Arbitro2'];

                $this->data['Lda']['Delegato'] = $this->data['Match']['Delegato'];

                $this->data['Lda']['DelegatoA'] = $this->data['Match']['DelegatoA'];

                $this->data['Lda']['Campionato'] = $this->data['Match']['Campionato'];
                $this->data['Lda']['Data'] = $this->data['Match']['Data'];
                if (!empty($this->data['Lda']['Data']))
                    $this->dmy2ymd($this->data['Lda']['Data']);
                $this->data['Lda']['Ora'] = $this->data['Match']['Ora'];
                $this->data['Lda']['Casa'] = $this->data['Match']['Casa'];
                $this->data['Lda']['Trasferta'] = $this->data['Match']['Trasferta'];
                $this->data['Lda']['Campo'] = $this->data['Match']['Campo'];

                $this->Lda->set($this->data);
                $this->Lda->save();

                if ($lda_id == '')
                {

                    $last_lda = $this->Lda->id;
                    $this->Match->set('lda_id', $last_lda);
                    $this->Match->save();
                }

                $data = $this->Disciplinari->find('all', array(
                    'conditions' => array(
                        'Disciplinari.SquadraCampionato' => array($this->data['Match']['Casa'], $this->data['Match']['Trasferta']),
                        'Disciplinari.Calendario' => $this->data['Match']['Calendario'],
                    ),
                    'order' => 'Disciplinari.SquadraCampionato ASC'
                        )
                );

                $this->set('disciplinari', $data);
            }
            else
            {

//debug($this->Match->invalidFields());
            }



//}
        }
    }


//GIUSEPPE 10/11/2016-------------------------------------------------
    function admin_idsport($id)
    {
        $q = mysql_query("SELECT id_sport FROM `Campionati` WHERE Campionato = " . $id);

//print_r(mysql_fetch_array($q)) ;

        echo mysql_fetch_array($q)[0];

        exit;
    }


// 11/11/2016 ------------------------
    function admin_tennispoint($id_calendario, $id_squadra_campionato_casa, $id_squadra_campionato_trasferta)
    {

        $id_squadre = [$id_squadra_campionato_casa, $id_squadra_campionato_trasferta];

        foreach ($id_squadre as $id_squadra) //creo due righe : la squadra di casa e la squadra di trasferta
        {
            $query = "SELECT COUNT(SetTennis) FROM `GoalPartite` WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra;

//echo $query."<br>";

            $q = mysql_query($query); //controllo se la riga esiste

            $result = mysql_fetch_array($q);

            if ($result[0] == 0) // la riga non esiste e la creo
            {
                $val_points = '
					{
						"points" : {
							"s_1_1":"0"
							,"s_1_2":"0"
							,"s_1_3":"0"
							,"s_2_1":"0"
							,"s_2_2":"0"
							,"s_2_3":"0"
							,"s_3_1":"0"
							,"s_3_2":"0"
							,"s_3_3":"0"
							,"s_4_1":"0"
							,"s_4_2":"0"
							,"s_4_3":"0"
							,"s_5_1":"0"
							,"s_5_2":"0"
							,"s_5_3":"0"
							,"s_6_1":"0"
							,"s_6_2":"0"
							,"s_6_3":"0"
						}
						,"check_win" : 
						{
							"s_1_4" : "0"
							,"s_2_4" : "0"
							,"s_3_4" : "0"
							,"s_4_4" : "0"
							,"s_5_4" : "0"
							,"s_6_4" : "0"
						}
						,"athletes":
						{
							"casa_s1" : "0"
							,"casa_s2" : "0"
							,"casa_d1" : "0"
							,"casa_d2" : "0"
							,"trasferta_s1" : "0"
							,"trasferta_s2" : "0"
							,"trasferta_d1" : "0"
							,"trasferta_d2" : "0"
						}
					}';

                $query_insert = "INSERT INTO GoalPartite (Calendario, SquadraCampionato, group_id, SetTennis)
					VALUES ('$id_calendario', '$id_squadra', '1','$val_points')";

                mysql_query($query_insert);
            }
        }

        $query_read = "SELECT SetTennis FROM `GoalPartite` WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra_campionato_casa;

        $q = mysql_query($query_read);

        $result = mysql_fetch_array($q)[0];

        echo $result;

        exit;
    }


// 2017-02-24 ------------------------

    function page_insertpoints($match)
    {

        $this->layout = "timmybox_web";

        $this->set('match', $this->Match->findByCalendario($match));
    }


//GIUSEPPE 2017-02-27 ..........................................................

    function tennispoint($id_calendario, $id_squadra_campionato_casa, $id_squadra_campionato_trasferta)
    {

        $this->admin_tennispoint($id_calendario, $id_squadra_campionato_casa, $id_squadra_campionato_trasferta);
    }


//..............................................................................
//function admin_insertpoint($id_calendario,$id_squadra_campionato,$json) {

    function admin_insertpoint()
    { //esegue l'update dei punteggi
        $id_calendario = $_POST['id_calendario'];

        $id_squadra_campionato_casa = $_POST['id_squadra_campionato_casa'];

        $id_squadra_campionato_trasferta = $_POST['id_squadra_campionato_trasferta'];

        $json_data = $_POST['json_data'];

// UPDATE RISULTATI PARTITE -------------------------------------------
        $json_object = json_decode($json_data, true);

        $point_casa = $json_object["check_win"]["s_1_4"] + $json_object["check_win"]["s_3_4"] + $json_object["check_win"]["s_5_4"];

        $point_trasferta = $json_object["check_win"]["s_2_4"] + $json_object["check_win"]["s_4_4"] + $json_object["check_win"]["s_6_4"];
//---------------------------------------------------------------------


        $json_transfert = $_POST['json_transfert'];

//$query_update = "UPDATE `GoalPartite` SET `SetTennis` = '".$json_data."' WHERE Calendario = " . $id_calendario. " AND SquadraCampionato = ".$id_squadra_campionato_casa;

        $query_update = "UPDATE `GoalPartite` SET `SetTennis` = '" . $json_data . "' ,`Goal` = '" . $point_casa . "' ,created = NOW() WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra_campionato_casa;

        mysql_query($query_update);

// update della squadra avversaria -----------------------------
//json_transfert
//$query_update_transfert = "UPDATE `GoalPartite` SET `SetTennis` = '".$json_transfert."' WHERE Calendario = " . $id_calendario. " AND SquadraCampionato = ".$id_squadra_campionato_trasferta;

        $query_update_transfert = "UPDATE `GoalPartite` SET `SetTennis` = '" . $json_transfert . "' ,`Goal` = '" . $point_trasferta . "' ,created = NOW() WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra_campionato_trasferta;

        mysql_query($query_update_transfert);

// -------------------------------------------------------------

        echo $point_casa . "-" . $point_trasferta; //$query_update;

        exit;
    }


//---------------------------------------------------------------------
//GIUSEPPE 2017-02-27 -------------------------------------------------
    function insertpoint()
    {
        $this->admin_insertpoint();
    }


//---------------------------------------------------------------------
//
//GIUSEPPE 2017-03-06 -------------------------------------------------

    function admin_resetpoints()
    {
        $id_calendario = $_POST['id_calendario'];

        $id_squadra_campionato_casa = $_POST['id_squadra_campionato_casa'];

        $id_squadra_campionato_trasferta = $_POST['id_squadra_campionato_trasferta'];

        $val_points = '{
				"points" : 
				{
					"s_1_1":"0"
					,"s_1_2":"0"
					,"s_1_3":"0"
					,"s_2_1":"0"
					,"s_2_2":"0"
					,"s_2_3":"0"
					,"s_3_1":"0"
					,"s_3_2":"0"
					,"s_3_3":"0"
					,"s_4_1":"0"
					,"s_4_2":"0"
					,"s_4_3":"0"
					,"s_5_1":"0"
					,"s_5_2":"0"
					,"s_5_3":"0"
					,"s_6_1":"0"
					,"s_6_2":"0"
					,"s_6_3":"0"
				}
				,"check_win" : 
				{
					"s_1_4" : "0"
					,"s_2_4" : "0"
					,"s_3_4" : "0"
					,"s_4_4" : "0"
					,"s_5_4" : "0"
					,"s_6_4" : "0"
				}
				,"athletes":
				{
					"casa_s1" : "0"
					,"casa_s2" : "0"
					,"casa_d1" : "0"
					,"casa_d2" : "0"
					,"trasferta_s1" : "0"
					,"trasferta_s2" : "0"
					,"trasferta_d1" : "0"
					,"trasferta_d2" : "0"
				}
			}';

//nel caso venga premuto reset punti prima che vengano create le righe
        $query = "SELECT COUNT(SetTennis) FROM `GoalPartite` WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra_campionato_casa;

        $q = mysql_query($query); //controllo se la riga esiste

        $result = mysql_fetch_array($q);

        if ($result[0] == 0) // la riga non esiste e la creo
        {
            $query_insert = "INSERT INTO GoalPartite (Calendario, SquadraCampionato, group_id, SetTennis)
			VALUES ('$id_calendario', '$id_squadra_campionato_casa', '1','$val_points')";

            mysql_query($query_insert);
        }




        $query = "SELECT COUNT(SetTennis) FROM `GoalPartite` WHERE Calendario = " . $id_calendario . " AND SquadraCampionato = " . $id_squadra_campionato_trasferta;

        $q = mysql_query($query); //controllo se la riga esiste

        $result = mysql_fetch_array($q);

        if ($result[0] == 0) // la riga non esiste e la creo
        {
            $query_insert = "INSERT INTO GoalPartite (Calendario, SquadraCampionato, group_id, SetTennis)
			VALUES ('$id_calendario', '$id_squadra_campionato_trasferta', '1','$val_points')";

            mysql_query($query_insert);
        }


        $query_update = "UPDATE `GoalPartite` SET `SetTennis` = '$val_points' ,`Goal` = '0' ,created = NOW() WHERE Calendario = '" . $id_calendario . "' AND SquadraCampionato = '" . $id_squadra_campionato_casa . "'";

        mysql_query($query_update);

        $query_update_transfert = "UPDATE `GoalPartite` SET `SetTennis` = '$val_points' ,`Goal` = '0' ,created = NOW() WHERE Calendario = '" . $id_calendario . "' AND SquadraCampionato = '" . $id_squadra_campionato_trasferta . "'";

        mysql_query($query_update_transfert);

        echo "cancellazione OK";

        exit;
    }


//---------------------------------------------------------------------








    function admin_goal($id)
    {

        $this->layout = "timmybox";

        $calendario = $this->Match->findByCalendario($id);

        $data = $this->Matchgoal->find('all', array('conditions' => array('Matchgoal.Calendario' => $id)));

        if (!empty($this->params['requested']))
        {

            return $data;
            return $calendario;
        }
        else
        {

            $this->set('goals', $data);
            $this->set('calendario', $calendario);
        }
    }


    function admin_goaldelete($id)
    {

        $this->layout = 'ajax';

        if ($this->Matchgoal->delete($id))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));

        $this->render("/backend/ajaxResult");
    }

    /* Function add/edit goal */


    function admin_getRisultato($calendario)
    {

        $this->layout = "ajax";

        $data = $this->Match->find('first', array(
            'fields' => array('Match.Calendario', 'Match.Risultato'),
            'conditions' => array(
                'Match.Calendario' => $calendario,
            ),
        ));

        $this->set('result', json_encode(array('risultato' => $data['Match']['Risultato'])));
        $this->render("/backend/ajaxResult");
    }


    function admin_goaladd()
    {

        $this->layout = "ajax";

        Configure::Write('debug', 0);

        $this->data = $_POST;

        if (!empty($this->data))
        {

            if (!empty($this->data['Matchgoal']['EspulsioneInizio']))
                $this->dmy2ymd($this->data['Matchgoal']['EspulsioneInizio']);
            if (!empty($this->data['Matchgoal']['EspulsioneFine']))
                $this->dmy2ymd($this->data['Matchgoal']['EspulsioneFine']);

            $this->Matchgoal->set($this->data);

            if ($this->Matchgoal->save())
            {

                $last = $this->Matchgoal->id;

                $aggiunto = $last;
                $errori = '';
            }
            else
            {

                $aggiunto = 0;
                $errori = $this->Matchgoal->invalidFields();
            }

            $this->set('result', json_encode(array('aggiunto' => $aggiunto, 'errori' => $errori)));
            $this->render('/backend/ajaxResult');
        }
    }


    function admin_disciplinareAdd()
    {

        $this->layout = "ajax";

        $this->Disciplinari->set($this->data);

        if ($this->Disciplinari->save())
        {

            $last_id = $this->Disciplinari->id;
            $add = $this->Disciplinari->findByDisciplinare($last_id);
            $error = 0;
        }
        else
        {

            $add = $this->Disciplinari->invalidFields();
            $error = 1;
        }

        $this->set('result', json_encode(array('add' => $add, 'error' => $error)));
        $this->render('/backend/ajaxResult');
    }


    function admin_disciplinareDelete($id)
    {

        $this->layout = 'ajax';

        if ($this->Disciplinari->delete($id))
        {

            $delete = 1;
        }
        else
        {

            $delete = 0;
        }

        $this->set('result', json_encode(array('delete' => $delete)));

        $this->render("/backend/ajaxResult");
    }


//Comunicazioni Lda

    function admin_sendLdaIndex()
    {

        $this->layout = "timmybox";
    }


    function admin_sendLda()
    {

        $this->layout = "ajax";

        $matches = $_POST['Match'];

        /* fixed add */
        $fixed = $this->requestAction('fixeds/read_all_fixed'); //GIUSEPPE 2018-08-28 -- richiama la tabella dei contenuti fissi

        $isEmail = $_POST['Data']['ComldaIsEmail'];
        $isSms = $_POST['Data']['ComldaIsSms'];
        $isDelegato = $_POST['Data']['ComldaIsDelegato'];
        $isArbitro = $_POST['Data']['ComldaIsArbitro'];

        $data = $this->Match->find('all', array(
            'conditions' => array(
                'Match.Calendario' => $matches,
            ),
            'order' => 'Match.Data ASC, Match.Ora ASC'
        ));

        $atleti = array();

        foreach ($data as $match)
        {

            if ($isArbitro)
            {
                if ($match['Lda']['Arbitro'] != '')
                    $atleti[] = $match['Lda']['Arbitro'];
                if ($match['Lda']['Arbitro2'] != '')
                    $atleti[] = $match['Lda']['Arbitro2'];
            }
            if ($isDelegato)
            {
                if ($match['Lda']['Delegato'] != '')
                    $atleti[] = $match['Lda']['Delegato'];
                if ($match['Lda']['DelegatoA'] != '')
                    $atleti[] = $match['Lda']['DelegatoA'];
            }
        }

        $atleti = array_merge(array_unique($atleti));

//debug($atleti);
        $comunications = array();

        foreach ($atleti as $atleta)
        {

            foreach ($data as $match)
            {

                if ($isArbitro)
                {

                    if ($match['Lda']['Arbitro'] == $atleta || $match['Lda']['Arbitro2'] == $atleta)
                    {

                        $comunications[$atleta][] = $match;
                    }
                }

                if ($isDelegato)
                {

                    if ($match['Lda']['Delegato'] == $atleta || $match['Lda']['DelegatoA'] == $atleta)
                    {

                        $comunications[$atleta][] = $match;
                    }
                }
            }
        }

        if (count($comunications))
        {

            foreach ($comunications as $atleta => $comunicazioni)
            {

                $athlete = $this->Athlete->findByAtleta($atleta);

                $msg_body = "";
                $msg_sms = array();
                $space = " ";
                $days = array("1" => "Luned", "2" => "Marted", "3" => "Mercoled", "4" => "Gioved", "5" => "Venerd", "6" => "Sabato", "7" => "Domenica",);
                $days_short = array("1" => "lun", "2" => "mar", "3" => "mer", "4" => "gio", "5" => "ven", "6" => "sab", "7" => "dom",);

                foreach ($comunicazioni as $k => $comunication)
                {

//Genero messaggio email

                    $day = strtotime($comunication['Match']['Data']);

                    unset($mansioni);
                    $mansioni = array();
                    $mansioni_sms = array();

                    if ($athlete['Athlete']['Atleta'] == $comunication['Lda']['Arbitro'])
                    {
                        $mansioni[] = 'Arbitro';
                        $mansioni_sms['A'] = 'A';
                    }
                    if ($athlete['Athlete']['Atleta'] == $comunication['Lda']['Arbitro2'])
                    {
                        $mansioni[] = 'Arbitro2';
                        $mansioni_sms['A'] = 'A';
                    }
                    if ($athlete['Athlete']['Atleta'] == $comunication['Lda']['Delegato'])
                    {
                        $mansioni[] = 'Delegato';
                        $mansioni_sms['D'] = 'D';
                    }
                    if ($athlete['Athlete']['Atleta'] == $comunication['Lda']['DelegatoA'])
                    {
                        $mansioni[] = 'Delegato arbitro';
                        $mansioni_sms['D'] = 'D';
                    }

                    $msg_body .= "
					Gentile operatore,<br />
					ecco di seguito le designazioni del periodo, per qualsiasi comunicazione contattare il designatore nel pi breve tempo possibile,<br />
					cordiali saluti<br /><br />
					
					Il responsabile LDA<br /><br />									
					";
                    $msg_body .= ($k + 1) . ")";
                    $msg_body .= $space . $comunication['Match']['Data_it'];
                    $msg_body .= $space . $days[date("w", $day)];
                    $msg_body .= $space . $comunication['Match']['Ora'];
                    $msg_body .= $space . $comunication['Match']['CasaNome'];
                    $msg_body .= $space . "- " . $comunication['Match']['TrasfertaNome'];
                    $msg_body .= $space . $comunication['Campi']['Descrizione'];

                    if (count($mansioni) > 1)
                    {

                        $separator = ' /';

                        foreach ($mansioni as $k => $mansione)
                        {

                            $msg_body .= $space . $mansione;
                            if ($k + 1 != count($mansioni))
                            {
                                $msg_body .= $separator;
                            }
                        }
                    }

                    $msg_body .= "<br />";

                    $array_key = md5($days_short[date("w", $day)]) . md5($space . date("d", $day) . '/' . date("m", $day) . ',') . md5($space . 'h.' . $comunication['Match']['Ora']) . md5($atleta);

                    $msg_sms[$comunication['Match']['Data']][$comunication['Campi']['Descrizione']][$array_key] = array(
                        'Giorno' => $days_short[date("w", $day)],
                        'Day' => $space . date("d", $day) . '/' . date("m", $day) . ',',
                        'Ora' => $space . 'h.' . $comunication['Match']['Ora'],
                        'athlete' => $atleta,
                    );
                }

//debug($msg_body);

                if ($athlete['Athlete']['Email'] != '' && $isEmail)
                { //Invio email
                    $this->EmailModel->create();

                    $this->data['EmailModel']['subject'] = 'Comunicazione LDA per il signor ' . $athlete['Athlete']['Anagrafica'];
                    $this->data['EmailModel']['message'] = $msg_body;
                    $this->data['EmailModel']['layout'] = 'comunication';

                    $this->EmailModel->set($this->data);

                    if ($this->EmailModel->save())
                    {

                        $email_id = $this->EmailModel->id;

                        $this->Spool->create();

                        $this->data['Spool']['mail_id'] = $email_id;
                        $this->data['Spool']['email'] = $athlete['Athlete']['Email'];

                        $this->Spool->set($this->data);
                        $this->Spool->save();
                    }
                }

//debug($msg_sms);

                if ($athlete['Athlete']['Cellulare'] != '' && $isSms)
                {

//print_r($athlete['Athlete']);
//Invia sms, con testo ridotto (limite 160 caratteri)
//lun 09, h.20.30 palanovoli c5x3

                    $testo_sms = '';
                    $mansioni_sms_text = implode('/', $mansioni_sms);

                    foreach ($msg_sms as $data => $campi)
                    {

                        foreach ($campi as $campo => $partite)
                        {

                            $count = count($msg_sms[$data][$campo]);
                            $partita = $msg_sms[$data][$campo][key($msg_sms[$data][$campo])];

                            $testo_sms .= ', ' . $partita['Giorno'];
                            $testo_sms .= $space . $partita['Day'];
                            $testo_sms .= $space . $partita['Ora'];
                            $testo_sms .= $space . $campo;
                            $testo_sms .= $space . 'x' . $count . $space . $mansioni_sms_text;
                        }
                    }

//Procedura invio sms con il seguente testo.

                    $testo_sms = substr_replace($testo_sms, '', 0, 2);

//print('Testo sms: ' . $testo_sms);
//print "<br />";
//continue;
//debug('Caratteri: ' . strlen($testo_sms));

                    $this->EmailModel->create();

//                    $this->data['EmailModel']['from'] = 'noreply@midlandeuropa.com';

                    /* fixed edit */
                    $this->data['EmailModel']['from'] = $fixed['email_automatic'];

                    $this->data['EmailModel']['subject'] = $testo_sms;
                    $this->data['EmailModel']['message'] = '';
                    $this->data['EmailModel']['layout'] = 'comunication';

                    $this->EmailModel->set($this->data);

                    if ($this->EmailModel->save())
                    {

                        $sms = $sms = ereg_replace("[^0-9]", "", $athlete['Athlete']['Cellulare']);

                        $email_id = $this->EmailModel->id;

                        $this->Spool->create();

                        $this->data['Spool']['mail_id'] = $email_id;
                        $this->data['Spool']['email'] = '39' . $sms . '@smsviaemail.it';

                        $this->Spool->set($this->data);
                        $this->Spool->save();
                    }
                }
            }

            $error = 0;
        }
        else
        {

            $error = 1;
        }

        $this->set('result', json_encode($this->read_mail_sms_to_sent()));
//$this->set('result', json_encode(array('ok' => $error)));
        $this->render("/backend/ajaxResult");
    }

    /* */


//GIUSEPPE 2017-06-03 - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    function read_mail_sms_to_sent()
    {
        $sql_all = "SELECT id FROM timmy_spools WHERE sent = 0"; //seleziono tutti i messaggi in coda

        $result_all = mysql_query($sql_all);

        $all_sent = mysql_num_rows($result_all);

        $sql_sms = "SELECT id FROM timmy_spools WHERE sent = 0 AND `email` LIKE '%@smsviaemail.it' "; //seleziono tutti i messaggi in coda

        $result_sms = mysql_query($sql_sms);

        $sms_sent = mysql_num_rows($result_sms);

        $result = array();

        $result['sms'] = $sms_sent;

        $result['email'] = $all_sent - $sms_sent;

        return $result;

//echo "all : " . $all_sent . "<br>SMS : " . $sms_sent . "<br>EMAIL : " . ($all_sent - $sms_sent);
//exit;
    }


    function admin_forum_export()
    {

        $this->layout = "timmybox";

        $this->setCampionati();
    }


    function admin_forum_export_go()
    {

        $this->layout = "ajax";

        debug($_POST['data']);

        $data = $this->Match->find('all', array());

        $this->set('result', json_encode($result));
        $this->render('/backend/ajaxResult');
    }


    function admin_getAnagrafica()
    {

        $this->layout = "ajax";

        $champ_id = $this->data['PrintAnagrafica']['Campionato'];
        $half_id = $this->data['Print']['Gironi'][0];

        $squadre_campionato = $this->SquadreCampionati->find('list', array(
            'fields' => array('SquadreCampionati.SquadraCampionato', 'SquadreCampionati.Squadra'),
            'conditions' => array(
                'SquadreCampionati.Campionato' => $champ_id,
                'SquadreCampionati.GironeCampionato' => $half_id,
            ),
        ));

        $squadre_anagrafica = array();

        foreach ($squadre_campionato as $squadra_campionato => $squadra)
        {

            $yearbooks = $this->Yearbook->find('all', array(
                'conditions' => array(
                    'Yearbook.SquadraCampionato' => $squadra_campionato,
                ),
                'order' => 'Yearbook.NomeSquadra ASC',
            ));

            $squadre_anagrafica[$squadra_campionato]['Info']['NomeSquadra'] = $yearbooks[0]['Yearbook']['NomeSquadra'];
            $squadre_anagrafica[$squadra_campionato]['rosa'] = $yearbooks;
        }

        $this->set('result', json_encode(array('anagrafica' => $squadre_anagrafica, 'data' => $this->data)));
        $this->render('/backend/ajaxResult');
    }


    function admin_getDisciplinari()
    {

        $this->layout = "ajax";

//Calcolo diffidati ed espulsi

        $diffidati = array();
        $espulsi = array();

        $giornata = $this->data['Print']['Giornate'][0];
        $champ_id = $this->data['PrintDisciplinari']['Campionato'];
        $half_id = $this->data['Print']['Gironi'][0];

        $diffidati = $this->Matchgoal->query(
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

        $espulsi = $this->Matchgoal->query(
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

        $this->set('result', json_encode(array('diffidati' => $diffidati, 'espulsi' => $espulsi, 'data' => $this->data)));
        $this->render('/backend/ajaxResult');
    }


    function admin_getMarcatori()
    {

        $this->layout = "ajax";

        $giornata = $this->data['Print']['Giornate'][0];
        $champ_id = $this->data['PrintMarcatori']['Campionato'];
        $half_id = $this->data['Print']['Gironi'][0];

        $marcatori = $this->Matchgoal->query(
                "SELECT sc.SquadraCampionato as IdSquadra, s.Denominazione as NomeSquadra, CONCAT(a.Cognome,' ',a.Nome) as anagrafica, SUM(g.Goal) as goals
				FROM Calendari c, GoalPartite g
				LEFT JOIN SquadreCampionati sc ON (sc.`SquadraCampionato` = g.`SquadraCampionato`)
				LEFT JOIN Squadre s ON (sc.`Squadra` = s.`Squadra`)
				LEFT JOIN Atleti a ON (a.`Atleta` = g.`Atleta`)
				WHERE g.Calendario = c.Calendario 
				AND c.Campionato = '$champ_id'
				AND c.GironeCampionato = '$half_id' 
				AND g.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND g.Atleta != 0
				GROUP BY g.Atleta ORDER BY goals DESC LIMIT 15"
        );

        $this->set('result', json_encode(array('marcatori' => $marcatori, 'data' => $this->data)));
        $this->render('/backend/ajaxResult');
    }


    function admin_getCalendar()
    {

        $this->layout = "ajax";

        $id_campionato = $this->data['PrintCalendarioRisultati']['Campionato'];
        $girone = $this->data['Print']['Gironi'][0];

        $campionato = $this->Campionati->findByCampionato($id_campionato);
        $nome_campionato = $campionato['Campionati']['Nome'];

        $calendari = array();

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

        /* foreach($giornate as $giornata) {

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

          } */

        $calendario = $data;

        $this->set('result', json_encode(array('calendario' => $calendario, 'data' => $this->data, 'giornate' => $giornate, 'nome_campionato' => $nome_campionato)));
        $this->render('/backend/ajaxResult');
    }


    function admin_getMatchesInfo($model)
    {

        $this->layout = "ajax";

//Giornate
        $gare = $this->Match->find('all', array(
            'conditions' => array(
                'Match.Campionato' => $this->data[$model]['Campionato'],
                'Match.GironeCampionato' => $this->data['Print']['Gironi'][0],
                'Match.Giornata' => $this->data['Print']['Giornate'][0],
            ),
            'order' => array('Match.Data ASC'),
        ));

// Generazione classifica //

        $campionato = $this->data[$model]['Campionato'];
        $girone = $this->data['Print']['Gironi'][0];
        $arr_class = array();
        $giornata = $this->data['Print']['Giornate'][0];

        $squadre = $this->SquadreCampionati->find('all', array(
            'conditions' =>
            array(
                'Campionati.Campionato' => $campionato,
                'Half.GironeCampionato' => $girone
            )
                )
        );

        $partite = array();

        $classifiche = array();

        foreach ($squadre as $squadra)
        {

            $classifica = array();

            $id_classifica = $this->Ranking->find('first', array(
                'conditions' =>
                array(
                    'Ranking.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
                    'Ranking.GironeCampionato' => $girone
                )
                    )
            );

            if (!empty($id_classifica))
            {
                $classifica['Classifica'] = $id_classifica['Ranking']['Classifica'];
            }
            else
            {
                $classifica['Classifica'] = null;
            }

            $classifica['SquadraCampionato'] = $squadra['SquadreCampionati']['SquadraCampionato'];
            $classifica['InfoSquadra'] = $this->SquadreCampionati->find('first', array('conditions' => array('SquadraCampionato' => $classifica['SquadraCampionato'])));
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

            $partite = $this->Match->find('all', array(
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

            foreach ($partite as $partita)
            {

                $casa_fuori = 'Fuori';
                $fuori_casa = 'Casa';
                $risultato['Casa'] = 0;
                $risultato['Fuori'] = 0;

                if ($partita['Match']['Casa'] == $squadra['SquadreCampionati']['SquadraCampionato'])
                    $casa_fuori = 'Casa';

                $disciplinari = $this->Disciplinari->find('all', array(
                    'conditions' => array(
                        'SquadreCampionati.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
                        'Disciplinari.Calendario' => $partita['Match']['Calendario']
                    )
                ));

//pr ($partita['Causalresult']);

                foreach ($disciplinari as $disciplinare)
                {

                    $classifica['CoppaDisciplina'] += $disciplinare['Disciplinari']['Punti'];
                }



                if ($partita['Causalresult']['Descrizione'] != 'Recupero' && substr($partita['Causalresult']['Descrizione'], 0, strlen('N.D.')) != 'N.D.' && $partita['Causalresult']['Descrizione'] != 'In attesa decisioni G.S.')
                {

                    $classifica['Giocate']++;
                    $classifica['Giocate' . $casa_fuori]++;

                    if ($partita['Causalresult']['Descrizione'] != 'Gara non omologabile.')
                    {

                        $goals = $this->Matchgoal->find('all', array(
                            'conditions' =>
                            array(
                                'Matchgoal.Calendario' => $partita['Match']['Calendario'],
                            )
                        ));

                        foreach ($goals as $goal)
                        {

                            if ($casa_fuori == 'Casa')
                                $fuori_casa = 'Fuori';
                            else
                                $fuori_casa = 'Casa';

                            if ($squadra['SquadreCampionati']['SquadraCampionato'] == $goal['Matchgoal']['SquadraCampionato'])
                            {

                                $classifica['GoalFatti'] += $goal['Matchgoal']['Goal'];
                                $classifica['GoalSubiti'] += $goal['Matchgoal']['Autogoal'];
                                $classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
                                $classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];

                                $risultato[$casa_fuori] += $goal['Matchgoal']['Goal'];
                                $risultato[$fuori_casa] += $goal['Matchgoal']['Autogoal'];

                                if ($goal['Matchgoal']['Ammonizione'] == 'Si')
                                    $classifica['CoppaDisciplina']++;
                                if ($goal['Matchgoal']['Espulsione'] == 'Si')
                                    $classifica['CoppaDisciplina'] += 3;
                            }
                            else
                            {

                                $classifica['GoalFatti'] += $goal['Matchgoal']['Autogoal'];
                                $classifica['GoalSubiti'] += $goal['Matchgoal']['Goal'];
                                $classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
                                $classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Goal'];

                                $risultato[$fuori_casa] += $goal['Matchgoal']['Goal'];
                                $risultato[$casa_fuori] += $goal['Matchgoal']['Autogoal'];
                            }
                        }
                    }
                }
                else
                {

                    if ($partita['Causalresult']['CausaleRisultato'] != 'N.D.')
                    {

                        $classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
                    }
                }

                if ($risultato[$casa_fuori] == $risultato[$fuori_casa])
                {

                    $classifica['Nulle']++;
                    $classifica['Nulle' . $casa_fuori]++;
                    $classifica['Punti']++;
                }

                if ($risultato[$casa_fuori] > $risultato[$fuori_casa])
                {

                    $classifica['Punti'] += 3;
                    $classifica['Vinte' . $casa_fuori]++;
                    $classifica['Vinte']++;
                }

                if ($risultato[$casa_fuori] < $risultato[$fuori_casa])
                {

                    $classifica['Perse' . $casa_fuori]++;
                    $classifica['Perse']++;

                    if (substr($partita['Causalresult']['Descrizione'], 0, strlen('TAV')) == 'TAV')
                    {

                        $classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
                    }
                }
            }

// Tolgo penalizzazione

            $classifica['Punti'] = $classifica['Punti'] - (isset($id_classifica['Ranking']['PuntiPenalizzazione']) ? $id_classifica['Ranking']['PuntiPenalizzazione'] : 0);

            $classifiche[] = $classifica;
        }

        $c_classifica = array_orderby($classifiche, 'Punti', SORT_DESC);

        $this->set('result', json_encode(array('gare' => $gare, 'classifica' => $c_classifica, 'data' => $this->data)));
        $this->render('/backend/ajaxResult');
    }


//GIUSEPPE 2022-10-15 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
    public function admin_load_calendario()
    {
        $this->render('admin_load_calendario');
    }


    public function loadExcelCalendar()
    {
        $file_name = $_FILES['file']['tmp_name'];
        $res = $this->readExcel($file_name, 10, 10);

        $this->write_file("_testLOAD_EXCEL", $res);

        $analize = $this->analizzaExcel($res);

        header('Content-Type: application/json');
        print json_encode($analize);

        exit();
    }


    private function analizzaExcel(&$array)
    {
        $g = "";
        $cw = [];

        $analizza = $this->cercaValori($array);
        $this->write_file("_testTitle", $analizza);

        foreach ($array as $c => $column)
        {
            foreach ($column as $r => $row)
            {
                $cw[$r][$c] = $row; // ordino per riga-colonna
            }
        }


        $response['exist'] = true;

        ob_start();
        include __DIR__ . "/../views/matches/admin_load_calendario_table.ctp";
        $g = ob_get_clean();

        $this->write_file("_testOrderedEXCEL", $cw);

        $response['insert'] = $this->cercaDoppioni($analizza);

        $res = ['table' => $g, 'response' => $response];

        session_start();
        $_SESSION['insert_matches_to_excel'] = $analizza;

        return $res;
    }


    private function cercaValori($array)
    {
        $res = [];

        foreach ($array as $key => $value)
        {
            $title = $value[1];

            $res[$title] = $value;
            $res[$title]['col_excel'] = $key;
            unset($res[$title][1]);
        }

        $twin = $this->analizzaValori($res);

        return ['ordinati' => $res, 'twin' => $twin];
    }


    private function analizzaValori(&$array)
    {

        $twin = []; // array che segue la struttura dell'excel estrapolato

        $campionati = &$array['Manifestazione'];
        $gironi = &$array['Girone'];
        $giornate = &$array['G.ta'];
        $data = &$array['Data'];
        $ora = &$array['H'];
        $casa = &$array['Sq.Casa'];
        $trasferta = &$array['Sq.Tras.'];
        $campo = &$array['Campo'];

        foreach ($campionati as $key => $value)
        {
            if ($key == "col_excel")
                continue;

//CAMPIONATI
            $c = $this->cercaCampionati($value); // id campionato
            $twin[$campionati['col_excel']][$key] = $c;

            if ($c == 0)
            {
                $twin[$gironi['col_excel']][$key] = -1;
            }
            else //campionati e gironi sono collegati
            {
//GIRONI
                $nomeGirone = $gironi[$key];
                $g = $this->cercaGironi($c, $nomeGirone);
                $twin[$gironi['col_excel']][$key] = $g;
            }

//GIORNATE - DATA - ORA
            $twin[$giornate['col_excel']][$key] = $giornate[$key];
            $twin[$data['col_excel']][$key] = $data[$key];
            $twin[$ora['col_excel']][$key] = $ora[$key];

//SQUADRA CASA
            $nomeCasa = $casa[$key];
            $sc = $this->cercaSquadra($nomeCasa);
            $twin[$casa['col_excel']][$key] = $sc;

//SQUADRA TRASFERTA
            $nomeTrasferta = $trasferta[$key];
            $st = $this->cercaSquadra($nomeTrasferta);
            $twin[$trasferta['col_excel']][$key] = $st;

//CAMPO
            $nomeCampo = $campo[$key];
            $ca = $this->cercaCampo($nomeCampo);
            $twin[$campo['col_excel']][$key] = $ca;
        }

        return $twin;
    }


    private function cercaCampionati($campionato)
    {
        $query = "SELECT * FROM Campionati WHERE Nome = '{$campionato}'";
        $c = $this->select_sql($query);
        $res = 0;
        if (count($c) == 1)
        {
            $res = $c[0]['Campionato'];
        }

        return $res;
    }


    private function cercaGironi($c, $nomeGirone)
    {
        $nomeGirone = strtolower(addslashes($nomeGirone));
        $query = "SELECT * FROM `GironiCampionati` WHERE Campionato='{$c}' AND LOWER(Descrizione) = '{$nomeGirone}'";
        $g = $this->select_sql($query);
        $res = 0;
        if (count($g) == 1)
        {
            $res = $g[0]['GironeCampionato'];
        }

        return $res;
    }


    private function cercaSquadra($squadra)
    {
        $squadra = strtolower(addslashes($squadra));
        $query = "SELECT * FROM `Squadre` WHERE LOWER(Denominazione) = '{$squadra}'";

        $s = $this->select_sql($query);
        $res = 0;
        if (count($s) == 1)
        {
            $res = $s[0]['Squadra'];
        }

        return $res;
    }


    private function cercaCampo($nome_campo)
    {
        $nome_campo = strtolower(addslashes($nome_campo));
        $query = "SELECT * FROM `Campi` WHERE LOWER(Descrizione) = '{$nome_campo}'";
        $nc = $this->select_sql($query);
        $res = 0;
        if (count($nc) == 1)
        {
            $res = $nc[0]['Campo'];
        }

        return $res;
    }


    private function cercaDoppioni($array)
    {
        $ordinati = $array['ordinati'];

        $col_data = $ordinati['Data']['col_excel'];
        $col_ora = $ordinati['H']['col_excel'];
        $col_campo = $ordinati['Campo']['col_excel'];

        $twin = $array['twin'];

        $data = $twin[$col_data];
        $ora = $twin[$col_ora];
        $campo = $twin[$col_campo];

        $res = [];
        $res['inserimento'] = true;
        foreach ($data as $key => $data_value)
        {
            $date_expl = explode("/", $data_value);
            $date = $date_expl[2] . "-" . $date_expl[1] . "-" . $date_expl[0];

            $ora_value = $ora[$key];
            $ora_db = str_replace(':', '.', $ora_value);

            $campo_value = $campo[$key];
            $campo_nome = addslashes($ordinati['Campo'][$key]);

            $res['analizza'][$key]['data_ora_campo_sql'] = "{$date} - {$ora_db} - {$campo_value}";
            $res['analizza'][$key]['data_ora_campo'] = "il {$data_value} - {$ora_value} - {$campo_nome}, ha già una prenotazione";

            $query = "SELECT COUNT(Calendario) as Rows FROM Calendari WHERE Data = '{$date}' AND Ora = '{$ora_db}' AND Campo = '{$campo_value}'";
            $res['analizza'][$key]['query'] = $query;
            $pren = $res['analizza'][$key]['prenotazioni'] = $this->select_sql($query)[0]['Rows'];

            if ($pren > 0)
            {
                $res['inserimento'] = false;
            }
        }

        $this->write_file("_cerca_prenotazioni.json", json_encode($res));

        return $res;
    }


    public function saveValuesExcel()
    {
        session_start();
        $analizza = $_SESSION['insert_matches_to_excel'];

        $ordinati = $analizza['ordinati'];

        $twin = $analizza['twin'];

        print_r($analizza);

        $campionati = $twin[$ordinati['Manifestazione']['col_excel']];
        $gironi = $twin[$ordinati['Girone']['col_excel']];
        $giornate = $twin[$ordinati['G.ta']['col_excel']];
        $data = $twin[$ordinati['Data']['col_excel']];
        $ora = $twin[$ordinati['H']['col_excel']];
        $casa = $twin[$ordinati['Sq.Casa']['col_excel']];
        $trasferta = $twin[$ordinati['Sq.Tras.']['col_excel']];
        $campo = $twin[$ordinati['Campo']['col_excel']];

// modifico le date
        foreach ($data as &$value)
        {
            $data_expl = explode("/", $value);
            $value = $data_expl[2] . "-" . $data_expl[1] . "-" . $data_expl[0];
        }


// modifico l ora
        foreach ($ora as &$value)
        {
            $value = str_replace(':', '.', $value);
        }


// cerco le squadre campionato casa
        foreach ($casa as $key => &$value)
        {
            $c = $campionati[$key];
            $g = $gironi[$key];
            $value = $this->cercaSquadreCampionati($c, $g, $value);
        }


// cerco le squadre campionato casa
        foreach ($trasferta as $key => &$value)
        {
            $c = $campionati[$key];
            $g = $gironi[$key];
            $value = $this->cercaSquadreCampionati($c, $g, $value);
        }


// creo le query
        $part = [];
        foreach ($campionati as $key => $c)
        {
            $g = $gironi[$key];
            $gio = $giornate[$key];
            $part[$gio][] = $gio;
            $partita = count($part[$gio]);

            $d = $data[$key];
            $h = $ora[$key];

            $cs = $casa[$key];
            $tr = $trasferta[$key];

            $cm = $campo[$key];

            $values['Campionato'] = $c;
            $values['GironeCampionato'] = $g;
            $values['Giornata'] = $gio;
            $values['Partita'] = $partita;
            $values['Data'] = $d;
            $values['Ora'] = $h;
            $values['Casa'] = $cs;
            $values['Trasferta'] = $tr;
            $values['Campo'] = $cm;
            $values['group_id'] = "1";

            $this->insert_into("Calendari", $values);
        }

        unset($_SESSION['insert_matches_to_excel']);

        file_get_contents("/apis/forCronCalendario/?api_key=b621-386594c0895e");

        exit();
    }


    private function cercaSquadreCampionati($c, $g, $squadra)
    {
        $query = "SELECT *,COUNT(SquadraCampionato) as N FROM `SquadreCampionati` WHERE Campionato = '{$c}' AND GironeCampionato = '{$g}' AND Squadra = '{$squadra}'";
        $res = $this->select_sql($query)[0];

        return $res['SquadraCampionato'];
    }

}

