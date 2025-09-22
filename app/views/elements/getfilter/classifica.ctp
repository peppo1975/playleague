<?
//GIUSEPPE  20/11/2016 -> filtra la classe

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$last = array();

//GIUSEPPE 2020-01
session_start();
//----------------

foreach ($all_teams as $k => $team)
{
    $last[$team]['nulle'] = "0";
    $last[$team]['punti'] = "0";
    $last[$team]['giocate'] = "0";
    $last[$team]['totali_vinte'] = "0";
    $last[$team]['totali_perse'] = "0";
    $last[$team]['goal_totali_fatti'] = "0";
    $last[$team]['goal_totali_subiti'] = "0";
    $last[$team]['coppa_disciplina'] = "0";
}

$day_ref = saturday_year(); // ultimo sabato di riferimento
?>

<ul class="switch-table-menu  pagination pagination-sm">

    <li><a href="javascript:;" style="cursor: default;" onclick="return false;">Giornate:</a></li>

    <? $id = 0; ?>
    <? foreach ($giornate as $i => $giornata): ?>

        <? $id = $i ?>
        <? if ($giornata['data'] > $day_ref) : ?>
            <? $id = $i - 1; ?>      
            <? break; ?>
        <? endif; ?>

        <li class="switch-giornata classifica" id="id_<?= $i; ?>" data-giornata-id="<?= $i; ?>"><a href="javascript:;" title="Giornata <?= $i; ?>"><?= $i; ?></a></li>

    <? endforeach; ?>

    <!-- //GIUSEPPE 2018-07-11 -->    
    <? $nextDay = $id ?>

    <script>
        $("#id_<?= $id ?>").addClass('active');
    </script>
</ul>




<div class="clear"></div>                                   

<div id="results-box">

    <!-- TABELLA CALENDARIO -->

    <? foreach ($giornate as $i => $giornata): ?>

        <? // if ($giornata['Campionati']['Italiana'] == 'Si') break;     ?>
        <? if ($i > $nextDay) break; ?>

        <table class="table-matches table table-bordered table-striped table-condensed <?= ($i != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $i; ?>">
            <thead>
                <tr class="table-header">
                    <? if ($nameClass == "primary" || $nameClass == "secondary"): //GIUSEPPE 20/11/2016        ?>

                        <th class="text-left">Societ&agrave;</th>

                        <th class="text-center">Punti</th>

                        <th class="text-center">Giocate</th>

                        <th class="text-center">Vinte</th>

                        <th class="text-center">Perse</th>


                        <!-- //GIUSEPPE 2020-01 -->
                        <? if ($_SESSION['campionati']['sport'] == "CALCIO"): ?>

                            <th class="text-center">Nulle</th>

                            <th class="text-center">Goal Fatti</th>

                            <th class="text-center">Goal Subiti</th>

                            <th class="text-center">Coppa Disc.</th>

                        <? elseif ($_SESSION['campionati']['sport'] == "BASKET"): ?>

                            <th class="text-center">Punti Fatti</th>

                            <th class="text-center">Punti Subiti</th>

                        <? endif; ?>
                        <!--************************-->


                    <? elseif ($nameClass == "quaternary"): ?>

                        <th class="text-left">Societ&agrave;</th>

                        <th class="text-center">Punti</th>

                        <th class="text-center">Giocate</th>

                        <th class="text-center">Vinte</th>

                        <th class="text-center">Perse</th>

                    <? endif; ?>

                </tr>
            </thead>

            <? foreach ($all_teams as $k => $team): ?>
                <!-- //qui devo ordinare le squadre in base al punteggio-->
                <!--se una squadra non è presente nella giornata, inserisco gli ultimi dati delle giornate precedenti-->
                <? if (isset($giornata['squadre'][$team]) && $giornata['squadre'][$team]['is_goal'] > 0): ?>
                    <? $last[$team] = $giornata['squadre'][$team] ?>
                <? else: ?>
                    <? $giornata['squadre'][$team] = $last[$team]; ?>
                <? endif; ?>

            <? endforeach; ?>

            <!--devo creare l'array "$all_teams" in ordine di punteggi-->
            <?
            $team_order = order_day($i, $giornata);
            ?>

            <? // foreach ($all_teams as $k => $team):   ?>
            <? foreach ($team_order as $k => $team): ?>

                <tr class="<?= (($k + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-id="<?= $team; ?>">

                    <? if ($nameClass == "primary" || $nameClass == "secondary"): //GIUSEPPE 20/11/2016       ?>

                        <?
                        if ($giornata['squadre'][$team]['coppa_disciplina'] == "")
                            $giornata['squadre'][$team]['coppa_disciplina'] = "0";

                        if ($giornata['squadre'][$team]['nulle'] == "")
                            $giornata['squadre'][$team]['nulle'] = "0";
                        ?>

                        <td class="text-left"><a href="/dettaglio/squadra/<?= $giornata['squadre'][$team]['id'] ?>/<?= strtolower(Inflector::Slug($team, '-')); ?>" title=""><?= $team; ?></a></td>

                        <td><?= $giornata['squadre'][$team]['punti']; ?></td>

                        <td><?= $giornata['squadre'][$team]['giocate']; ?></td>

                        <td><?= $giornata['squadre'][$team]['totali_vinte']; ?></td>

                        <td><?= $giornata['squadre'][$team]['totali_perse']; ?></td>

                        <!-- //GIUSEPPE 2020-01 -->
                        <? if ($_SESSION['campionati']['sport'] == "CALCIO"): ?>
                            <td><?= $giornata['squadre'][$team]['nulle']; ?></td>

                            <td><?= $giornata['squadre'][$team]['goal_totali_fatti']; ?></td>

                            <td><?= $giornata['squadre'][$team]['goal_totali_subiti']; ?></td>

                            <td><?= $giornata['squadre'][$team]['coppa_disciplina']; ?></td>

                        <? elseif ($_SESSION['campionati']['sport'] == "BASKET"): ?>

                            <td><?= $giornata['squadre'][$team]['goal_totali_fatti']; ?></td>

                            <td><?= $giornata['squadre'][$team]['goal_totali_subiti']; ?></td>
                            
                        <? endif; ?>

                        <!--************************-->


                    <? elseif ($nameClass == "quaternary"): ?>

                        <td class="text-left"><a href="/dettaglio/squadra/<?= $giornata['squadre'][$team]['id'] ?>/<?= strtolower(Inflector::Slug($team, '-')); ?>" title=""><?= $team; ?></a></td>

                        <td><?= $giornata['squadre'][$team]['punti']; ?></td>

                        <td><?= $giornata['squadre'][$team]['giocate']; ?></td>

                        <td><?= $giornata['squadre'][$team]['totali_vinte']; ?></td>

                        <td><?= $giornata['squadre'][$team]['totali_perse']; ?></td>

                    <? endif; ?>

                </tr>

            <? endforeach; ?>


        </table>

    <? endforeach; ?>

</div><!-- close results-box -->

<?




function order_day($i, $giornata)
{
    //print_r($giornata);
    $array_indexed = array();

    foreach ($giornata['squadre'] as $key_team => $values)
    {
        $diff_goals = $values['goal_totali_fatti'] - $values['goal_totali_subiti'];

        $array_indexed[] = array(
            "team" => $key_team,
            "cup_discipline" => $values['coppa_disciplina'],
            "goals" => $values['goal_totali_fatti'],
            "diff_goals" => $diff_goals,
            "points" => $values['punti'],
        );
    }


    $num_teams = count($array_indexed);


    //NAME - ordine crescente
    do // bubble sort name
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['cup_discipline'] > $array_indexed[$index + 1]['cup_discipline'])
            {
                $switch = true;

                /* $temp['team'] = $array_indexed[$index]['team'];
                  $temp['points'] = $array_indexed[$index]['points'];

                  $array_indexed[$index]['team'] = $array_indexed[$index + 1]['team'];
                  $array_indexed[$index]['points'] = $array_indexed[$index + 1]['points'];

                  $array_indexed[$index + 1]['team'] = $temp['team'];
                  $array_indexed[$index + 1]['points'] = $temp['points']; */

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);







    //COPPA DISCIPLINA - ordine crescente
    do // bubble sort coppa_disc
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['cup_discipline'] > $array_indexed[$index + 1]['cup_discipline'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);










    //GOAL TOTALI - ordine decrescente
    do // bubble sort goals
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['goal_totali_fatti'] < $array_indexed[$index + 1]['goal_totali_fatti'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);











    //DIFFERENZA RETI - ordine decrescente
    do // bubble sort goals
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['diff_goals'] < $array_indexed[$index + 1]['diff_goals'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);










    //PUNTI - ordine decrescente
    do // bubble sort points
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['points'] < $array_indexed[$index + 1]['points'])
            {
                $switch = true;

                /* $temp['team'] = $array_indexed[$index]['team'];
                  $temp['points'] = $array_indexed[$index]['points'];

                  $array_indexed[$index]['team'] = $array_indexed[$index + 1]['team'];
                  $array_indexed[$index]['points'] = $array_indexed[$index + 1]['points'];

                  $array_indexed[$index + 1]['team'] = $temp['team'];
                  $array_indexed[$index + 1]['points'] = $temp['points']; */

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);



    $result = array();

    foreach ($array_indexed as $index => $team_points)
    {
        $result[] = $team_points['team'];
    }
    return $result;
}




function order_array(&$array_indexed, $index)
{
    $temp['team'] = $array_indexed[$index]['team'];
    $temp['points'] = $array_indexed[$index]['points'];
    $temp['cup_discipline'] = $array_indexed[$index]['cup_discipline'];
    $temp['goal_totali_fatti'] = $array_indexed[$index]['goal_totali_fatti'];
    $temp['diff_goals'] = $array_indexed[$index]['diff_goals'];

    $array_indexed[$index]['team'] = $array_indexed[$index + 1]['team'];
    $array_indexed[$index]['points'] = $array_indexed[$index + 1]['points'];
    $array_indexed[$index]['cup_discipline'] = $array_indexed[$index + 1]['cup_discipline'];
    $array_indexed[$index]['goal_totali_fatti'] = $array_indexed[$index + 1]['goal_totali_fatti'];
    $array_indexed[$index]['diff_goals'] = $array_indexed[$index + 1]['diff_goals'];

    $array_indexed[$index + 1]['team'] = $temp['team'];
    $array_indexed[$index + 1]['points'] = $temp['points'];
    $array_indexed[$index + 1]['cup_discipline'] = $temp['cup_discipline'];
    $array_indexed[$index + 1]['goal_totali_fatti'] = $temp['goal_totali_fatti'];
    $array_indexed[$index + 1]['diff_goals'] = $temp['diff_goals'];
}




function saturday_year($date_day) /* mi calcolo tutti i sabati dell'anno in corso */
{


    $year = date("Y");

    $now = date("Y-m-d H:i:s");

    $saturday = array();


    /* ---------------------------------------------------------------- */
    /* all 'inizio dell' anno devo far riferimento all'ultimo sabato (che puo trovarsi nell'anno precedente) */

    $first_day_year = "$year-01-01";

    $weekday_first = date('l', strtotime($first_day_year));

    if ($weekday_first !== "Saturday")
    {
        $last_saturday = strtotime("last Saturday", strtotime($now));

        $last_saturday_data = date("Y-m-d", $last_saturday);

        $saturday[] = "$last_saturday_data 12:30:00";
    }

    /* ---------------------------------------------------------------- */

    $day_week = "Saturday";

    $hour_start = "12:30:00";

    $day_ref = "";

    $num_days = 0;


    for ($i = 1; $i <= 12; $i++)
    {
        $num_days += cal_days_in_month(CAL_GREGORIAN, $i, $year);
    }

    for ($i = 1; $i <= $num_days; $i++)
    {
        $mese = mktime(0, 0, 0, 1, $i, $year);

        $giorno_settimana = date("l", $mese);

        if ($giorno_settimana == $day_week)
        {
            $saturday[] = date("Y-m-d", $mese) . " $hour_start";
        }
    }

    /* print_r($saturday); */

    /*
     * devo prendere il sabato di riferimento (quello <= alla data odierna)
     * e lo userò come riferimento per le date:
     * tutte le giornate precedenti a quel sabato, verranno visualizzate 
     */

    foreach ($saturday as $day)
    {
        if ($now >= $day)
        {
            $day_ref = $day;
        }
        else
        {
            break;
        }
    }

    return $day_ref;
}
?>