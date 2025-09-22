<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>



<?
//GIUSEPPE 2017-02-20 -> filtra la classe e il tipo di tesseramento
//$sport_options['view'] = true;

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$listSport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $listSport[$nameClass];
?>
<?
$data = $this->Session->read('Login.data');

if ($data['is_arbitro'])
{
    $type = 'Athlete';
}
elseif ($data['is_user'])
{
    $type = 'User';
}

$id = $data['id'];

$mesi = array(
    '01' => 'Gennaio',
    '02' => 'Febbraio',
    '03' => 'Marzo',
    '04' => 'Aprile',
    '05' => 'Maggio',
    '06' => 'Giugno',
    '07' => 'Luglio',
    '08' => 'Agosto',
    '09' => 'Settembre',
    '10' => 'Ottobre',
    '11' => 'Novembre',
    '12' => 'Dicembre',
);

$mesi_short = array(
    '01' => 'Gen',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'Mag',
    '06' => 'Giu',
    '07' => 'Lug',
    '08' => 'Ago',
    '09' => 'Set',
    '10' => 'Ott',
    '11' => 'Nov',
    '12' => 'Dic',
);

$options = array(
    0 => 'Nessun voto',
    1 => 'Gravemente insufficiente',
    2 => 'Insufficiente',
    3 => 'Appena sufficiente',
    4 => 'Sufficiente',
    5 => 'Discreto',
    6 => 'Buono',
    7 => 'Ottimo',
);

$end_days = array(
    '01' => '31',
    '02' => '29',
    '03' => '31',
    '04' => '30',
    '05' => '31',
    '06' => '30',
    '07' => '31',
    '08' => '31',
    '09' => '30',
    '10' => '31',
    '11' => '30',
    '12' => '31',
);

//GIUSEPPE 2017-02-23 ................................................................................

function squadra_atleta($id)
{
    $squadre = array();

    $squadra_campionato = array();

    $squadra_campionato[] = "INIZIO"; // metto questo per evitare l'indice 0 come in dice di ricerca: 
    //                      dopo la query otteniamo un array di valori su cui verrrà fatto un array_search;
    //                      i risultati possono essere : NULL oppure indice della chiave trovata.
    //                      ma se la chiace si trova all'indice 0 verrà valutata come NULL per cui le chiavi iniziano da un indice = 1


    $result = array();


    $query = "SELECT 
                Squadre.Denominazione,
                SquadreCampionati.SquadraCampionato
                FROM Annuario 
                INNER JOIN SquadreCampionati
                ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato
                INNER JOIN Squadre
                ON Squadre.Squadra = SquadreCampionati.Squadra
                WHERE Atleta = '$id' AND Squadre.Sport = 'TENNIS' AND AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)";

    $q = mysql_query($query);

    if (mysql_num_rows($q) > 0)
    {

        while ($row = mysql_fetch_assoc($q))
        {
            $squadra_campionato[] = $row['SquadraCampionato'];

            $squadre[] = $row['Denominazione'];
        }
    }
    $result[] = $squadra_campionato;

    $result[] = $squadre;

    return $result;
}

function atleta_from_id($id)
{

    $result = "In sospeso";

    //echo "id -->" . $id;

    if ($id > 0)
    {
        $query = "SELECT CONCAT(cognome,' ',nome) as nome_cognome
                FROM Atleti
                WHERE Atleta = '$id' AND Atleta > 0";

        $q = mysql_query($query);

        $result = mysql_fetch_array($q)[0];
    }

    return $result;
}

function read_point($array_info_points, $squadra_casa, $match)
{

    $row1 = "";

    $row2 = "";

    $squadra1 = "";

    $squadra2 = "";

    $result = array();



    foreach ($array_info_points as $info_points)
    {

        //if (array_search($info_points['SquadraCampionato'], $team[0]) > 0)
        if ($info_points['NomeSquadra'] == $squadra_casa)
        {
            $to_array = json_decode($info_points['SetTennis'], true);

            if ($match == "sing1")
            {
                $squadra1 = atleta_from_id($to_array['athletes']['casa_s1']);

                $squadra2 = atleta_from_id($to_array['athletes']['trasferta_s1']);

                $row1 = $to_array['points']['s_1_1'] . " / " . $to_array['points']['s_1_2'] . " / " . $to_array['points']['s_1_3'];

                $row2 = $to_array['points']['s_2_1'] . " / " . $to_array['points']['s_2_2'] . " / " . $to_array['points']['s_2_3'];
            }
            elseif ($match == "sing2")
            {
                $squadra1 = atleta_from_id($to_array['athletes']['casa_s2']);

                $squadra2 = atleta_from_id($to_array['athletes']['trasferta_s2']);

                $row1 = $to_array['points']['s_3_1'] . " / " . $to_array['points']['s_3_2'] . " / " . $to_array['points']['s_3_3'];

                $row2 = $to_array['points']['s_4_1'] . " / " . $to_array['points']['s_4_2'] . " / " . $to_array['points']['s_4_3'];
            }
            elseif ($match == "doppio")
            {

                $squadra1 = atleta_from_id($to_array['athletes']['casa_d1']) . " - " . atleta_from_id($to_array['athletes']['casa_d2']);

                $squadra2 = atleta_from_id($to_array['athletes']['trasferta_d1']) . " - " . atleta_from_id($to_array['athletes']['trasferta_d2']);

                $row1 = $to_array['points']['s_5_1'] . " / " . $to_array['points']['s_5_2'] . " / " . $to_array['points']['s_5_3'];

                $row2 = $to_array['points']['s_6_1'] . " / " . $to_array['points']['s_6_2'] . " / " . $to_array['points']['s_6_3'];
            }


            if (strlen($squadra1) > 3)
            {

                $result['squadra_1'] = $squadra1;

                $result['points_1'] = $row1;

                //$result['view'] = true;
            }


            if (strlen($squadra2) > 3)
            {
                //echo $squadra2 . "<br>";
                // echo $row2;

                $result['squadra_2'] = $squadra2;

                $result['points_2'] = $row2;

                //$result['view'] = true;
            }
        }
    }



    return $result;
}

function unix_timestamp_match($data, $ora)
{
    $array_data = explode("/", $data);

    $array_ora = explode(".", $ora);

    return mktime($array_ora[0], $array_ora[1], 0, $array_data[1], $array_data[0], $array_data[2]);
}

//....................................................................................................
?>

<script type="text/javascript">

    var mesi_num = {"Gen": "01", "Feb": "02", "Mar": "03", "Apr": "04", "Mag": "05", "Giu": "06", "Lug": "07", "Ago": "08", "Set": "09", "Ott": "10", "Nov": "11", "Dic": "12"};

    $(function ()
    {
        $(".table-matches").delegate('.vote', 'click', function ()
        {

            $(".btn-success").attr("disabled", true);

            var obj = $(this);
            var type = obj.attr('data-type');
            var athlete = obj.attr('data-id');
            var allow = obj.parents('tr').attr('vote-allow');
            var match = obj.parents('tr').attr('data-id');

            timmy_load('/matches/page_insertpoints/' + match);

        });



        $('.switch-giornata').bind('click', function ()
        {

            location.hash = $(this).attr('data-giornata-id');

        });

    });

    $(document).ready(function ()
    {
        $(".btn-success").attr("disabled", false);

        var loc_hash = location.hash.replace('#', '');

        $('.switch-giornata[data-giornata-id="' + loc_hash + '"]').trigger('click');

        giornata();

        $(".switch-giornata").click(function ()
        {
            $(".switch-giornata").removeClass('active');

            $(this).addClass('active');

            var giornata_id = $(this).attr('data-giornata-id');

            $(".table-matches").addClass('hidden');

            $(".table-matches[data-giornata-id=" + giornata_id + "]").removeClass('hidden');

        });

    });

    function giornata()
    {


        var mese_riferimento = '<?= date('m', time()) ?>';

        var page_now = location.href;

        var res = page_now.split("#"); // MI SERVE SE ESEGUO IL RELOAD DI UNA TABELLA NON DEL MESE IN CORSO

        var if_giornata = false;

        if (res.length === 2)
        {
            mese_riferimento = res[1];

            if_giornata = true;

            $(" .switch-giornata").each(function (index)
            { // lo uso per rendere active il tab del nome mese

                //console.log("----> " + mesi_num[$(this)["0"].innerText]);

                if (parseInt(mese_riferimento) == parseInt(mesi_num[$(this)["0"].innerText]))
                {
                    $(".switch-giornata").removeClass('active');

                    $(this).addClass('active');

                }

            });
        }
        else
        {

            $(" .switch-giornata").each(function (index)
            { // lo uso per rendere active il tab del nome mese

                console.log("----> " + $(" .switch-giornata").length + " indice " + index);

                if (parseInt(mese_riferimento) == parseInt(mesi_num[$(this)["0"].innerText]))
                {
                    if_giornata = true;
                }

                if (index == $(" .switch-giornata").length - 1 && if_giornata == false) // se siamo qualche mese in avanti e la tabella si ferma prima, selezione l'ultimo mese
                {
                    $(this).addClass('active');

                    $(".table-matches").addClass('hidden');

                    $(".table-matches[data-giornata-id='" + mesi_num[$(this)["0"].innerText] + "']").removeClass('hidden');

                    console.log("riferimento --> " + mesi_num[$(this)["0"].innerText]);


                }

            });

        }

        if (if_giornata == true)
        {
            var conta = <?= count($sfide_mensili) ?>;

            if (conta > 0)
            {

                $(".table-matches").addClass('hidden');

                $(".table-matches[data-giornata-id=" + mese_riferimento + "]").removeClass('hidden');
            }
        }



    }

</script>
<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="active">Partite tennis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-9">

                <h2>Partite tennis</h2>

                <? $squadre_atleta = squadra_atleta($this->Session->read('Login.data.id')) ?>

                <? $squadre_unique = array_unique($squadre_atleta[1]) ?>

                <?= implode(" - ", $squadre_unique) ?>

                <? //= $this->Session->read('Login.data.id'); // print_r($sfide_mensili);?>

                <div class="table-container container-table-profile">

                    <? if (count($sfide_mensili)): ?>

                        <ul class="switch-table-menu pagination pagination-sm">

                            <?
                            end($sfide_mensili);
                            $first = key($sfide_mensili);
                            ?>

                            <? foreach ($sfide_mensili as $mese => $matches): ?>

                                <li class="switch-giornata <? if ($mese == date('n', time())): ?>active<? endif; ?>" data-giornata-id="<?= $mese; ?>"><a href="javascript:;" title="<?= $mesi[$mese]; ?>"><?= $mesi_short[$mese]; ?></a></li>

                            <? endforeach; ?>

                        </ul>	

                        <div class="clear"></div>

                        <div id="results-box">

                            <? foreach ($sfide_mensili as $k => $matches): ?>

                                <table class="table table-bordered table-striped table-condensed table-matches <? if ($k != $first): ?>hidden<? endif; ?>" data-giornata-id="<?= $k; ?>">	

                                    <thead>
                                    <th>Giorno</th>
                                    <th>Ora</th>
                                    <th>Impianto</th>
                                    <th>Partita</th>
                                    <th>Sing.1</th>
                                    <th>Sing.2</th>
                                    <th>Doppio</th>
                                    <th>Ris.</th>
                                    <th>Note</th>
                                    <th>Gara</th>
                                    </thead>
                                    <? $j = 0; ?>
                                    <? foreach ($matches as $k => $match): ?>

                                        <? //= json_encode($match['Matchgoal'])  ?>

                                        <? $set_1 = read_point($match['Matchgoal'], $match['Match']['CasaNome'], "sing1"); ?>

                                        <? $set_2 = read_point($match['Matchgoal'], $match['Match']['CasaNome'], "sing2"); ?>

                                        <? $doppio = read_point($match['Matchgoal'], $match['Match']['CasaNome'], "doppio"); ?>

                                        <? //print_r($match['Matchgoal'])  ?>

                                        <?
                                        if (!empty($match['Match']['Risultato']))
                                        {
                                            $vote_allow = 1;
                                        }
                                        else
                                        {
                                            $vote_allow = 0;
                                        }
                                        ?>					

                                        <tr class="<?= (($j + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-squadra-id="<?= $match['Casa']['Squadra']; ?>" data-trasferta-squadra-id="<?= $match['Trasferta']['Squadra']; ?>" data-casa-id="<?= $match['Match']['Casa']; ?>" data-trasferta-id="<?= $match['Match']['Trasferta']; ?>" vote-allow="<?= $vote_allow; ?>" data-id="<?= $match['Match']['Calendario']; ?>">
                                            <td><span class="number"><?= $match['Match']['Data_it']; ?></span></td>
                                            <td><span class="number"><?= $match['Match']['Ora']; ?></span></td>
                                            <td>

                                                <? if ($match['Campi']['latitudine'] != '' && $match['Campi']['longitudine'] != '' && empty($match['Match']['Risultato'])): ?>
                                                    <script type="text/javascript">
                                                        $(function ()
                                                        {
                                                            $('.open_maps').unbind('click').bind('click', function ()
                                                            {
                                                                $.post('/campis/saveMapsSession', {
                                                                    'Nome': '<?= $match['Campi']['Descrizione']; ?>',
                                                                    'latitudine': '<?= $match['Campi']['latitudine']; ?>',
                                                                    'longitudine': '<?= $match['Campi']['longitudine']; ?>',
                                                                    'indirizzo': '<?= $match['Campi']['Indirizzo']; ?>',
                                                                    'citta': '<?= $match['Campi']['Citta']; ?>',
                                                                    'provincia': '<?= $match['Campi']['Provincia']; ?>',
                                                                    'telefono': '<?= $match['Campi']['Telefono']; ?>',
                                                                    'email': '<?= $match['Campi']['Email']; ?>'
                                                                }, function ()
                                                                {

                                                                    var uniqid = Math.random();

                                                                    timmy_load('/campis/maps?midland=' + uniqid);

                                                                });
                                                            });

                                                        });
                                                    </script>				
                                                    <a class="open_maps" href="javascript:;" rel="timmytip" title="<?= $match['Campi']['Descrizione']; ?>">
                                                        <?= $match['Campi']['Descrizione']; ?>
                                                    </a>
                                                <? else: ?>
                                                    <?= $match['Campi']['Descrizione']; ?>
                                                <? endif; ?>
                                            </td>
                                            <td nowrap><?= $match['Match']['CasaNome']; ?> <small><strong>vs</strong></small> <?= $match['Match']['TrasfertaNome']; ?></td>
                                            <td nowrap> 

                                                <? if ($match['Match']['Risultato'] != "0-0" && $match['Match']['Risultato'] != ""): ?>
                                                    <?= $set_1['squadra_1']; ?>
                                                    <br>
                                                    <?= $set_1['points_1']; ?>
                                                    <br>
                                                    <?= $set_1['squadra_2']; ?>
                                                    <br>
                                                    <?= $set_1['points_2']; ?>
                                                <? else: ?>
                                                    <? if (time() >= unix_timestamp_match($match['Match']['Data_it'], $match['Match']['Ora']) && ($match['Match']['Risultato'] == "0-0" || $match['Match']['Risultato'] == ""))://il tasto di inserimento punti diventa disponibile all'ora di inizio partita  ?>
                                                        <button type="button" class="btn btn-success btn-sm mr-xs mb-sm not-rate vote" href="javascript:;">punteggi</button>
                                                    <? endif; ?>
                                                <? endif; ?>
                                            </td>


                                            <td nowrap> 
                                                <? if ($match['Match']['Risultato'] != "0-0" && $match['Match']['Risultato'] != ""): ?>
                                                    <?= $set_2['squadra_1']; ?>
                                                    <br>
                                                    <?= $set_2['points_1']; ?>
                                                    <br>
                                                    <?= $set_2['squadra_2']; ?>
                                                    <br>
                                                    <?= $set_2['points_2']; ?>
                                                <? endif; ?>
                                            </td>


                                            <td nowrap>
                                                <? if ($match['Match']['Risultato'] != "0-0" && $match['Match']['Risultato'] != ""): ?>
                                                    <?= $doppio['squadra_1']; ?>
                                                    <br>
                                                    <?= $doppio['points_1']; ?>
                                                    <br>
                                                    <?= $doppio['squadra_2']; ?>
                                                    <br>
                                                    <?= $doppio['points_2']; ?>
                                                <? endif; ?>
                                            </td>


                                            <td><span class="number">
                                                    <? if ($match['Match']['Risultato'] != "0-0" && $match['Match']['Risultato'] != ""): ?>
                                                        <?= $match['Match']['Risultato']; ?>
                                                    <? endif; ?>
                                                </span></td>
                                            <td><?= $match['Causalresult']['Descrizione']; ?></td>
                                            <td><?= $match['Match']['NomeGara']; ?></td>

                                        </tr>

                                        <? $j++; ?>

                                    <? endforeach; ?>

                                </table>

                            <? endforeach; ?>	

                        </div>	

                    <? else: ?>
                        <div class="alert alert-danger">
                            Nessuna gara nella stagione corrente.
                        </div>
                    <? endif; ?>	


                </div>
            </div>

            <div class="col-md-3">
                <aside class="sidebar">
                    <h4 class="heading-primary">Gestione account</h4>
                    <ul class="nav nav-list narrow">
                        <li><a href="/gestione/profilo/<?= $this->Session->read('Login.data.id'); ?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
<!--                        <li><a href="/gestione/vota/<?= $sport ?>" title="Votazioni">Votazioni</a></li>
                        <li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>-->
                        <li class="active"><a href="/gestione/tennis_points" title="Partite">Partite tennis</a></li>

                    </ul>
                </aside>
            </div>
        </div>
    </div>