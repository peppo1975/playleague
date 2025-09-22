<?
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$type_sport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$type_points = array("CALCIO" => "Goal", "TENNIS" => "Punti");

$sport = $type_sport[$nameClass];

// //GIUSEPPE 2017-04-06 punti campionato tennis -------------------------------------------------------------------------------
$array_final = array();

if ($sport == "TENNIS")
{
    //include(APP . "views/squadres/query_database.ctp");
    //include(APP . "views/squadres/ranking.ctp");

    $ranking = $this->requestAction('athletes/ranking_atleti'); // questo valore lo troviamo nel controller  

    $array_final = replace_this($ranking);
}







//echo json_encode($array_final);
//echo json_encode($array_final);

function replace_this($ranking)// ho tolto il vecchio metodo di calcolo ranking e sto simulando il vecchio risultato
{
    $final = array();

    foreach ($ranking as $single_rank)
    {
        $i['sc']['IdSquadra'] = $single_rank['id_squadra_campionato'];
        $i['s']['NomeSquadra'] = $single_rank['squadra'];
        $i['0']['anagrafica'] = $single_rank['Anagrafica'];
        $i['0']['sesso'] = $single_rank['Sesso'];
        $i['0']['goals'] = $single_rank['points'];
        $i['0']['id_atleta_tennis'] = $single_rank['Atleta'];


        $final[] = $i;
    }

    return $final;

}







function estrai_punti_tennis($giornate, $array_final)
{
    $athletes_giornate = array();

    $total_athletes = array();

    foreach ($giornate as $i => $giornata)
    {

        foreach ($giornata['Matchgoal'] as $points)
        {
            $g = json_decode($points['SetTennis'], true);

            $state_win_1 = $g['check_win']['s_1_4'];
            $state_win_2 = $g['check_win']['s_2_4'];
            $state_win_3 = $g['check_win']['s_3_4'];
            $state_win_4 = $g['check_win']['s_4_4'];
            $state_win_5 = $g['check_win']['s_5_4'];
            $state_win_6 = $g['check_win']['s_6_4'];

            // voglio evitare di conteggiare partite in cui è stato inserito il punteggio ma non il vincitore
            $cond_1 = ($state_win_1 == '0' && $state_win_2 == '1') || ($state_win_1 == '1' && $state_win_2 == '0');
            $cond_2 = ($state_win_3 == '0' && $state_win_4 == '1') || ($state_win_3 == '1' && $state_win_4 == '0');
            $cond_3 = ($state_win_5 == '0' && $state_win_6 == '1') || ($state_win_5 == '1' && $state_win_6 == '0');

            if ($cond_1 && $cond_2 && $cond_3)
            {
                $athletes_giornate[$i][] = $g['athletes']['casa_s1'];
                $athletes_giornate[$i][] = $g['athletes']['casa_s2'];
                $athletes_giornate[$i][] = $g['athletes']['casa_d1'];
                $athletes_giornate[$i][] = $g['athletes']['casa_d2'];


                $total_athletes[] = $g['athletes']['casa_s1'];
                $total_athletes[] = $g['athletes']['casa_s2'];
                $total_athletes[] = $g['athletes']['casa_d1'];
                $total_athletes[] = $g['athletes']['casa_d2'];
            }
        }
    }

    $unique_id = array_unique($total_athletes);

    return crea_associazioni($unique_id, $array_final);

}







function crea_associazioni($athletes, $array_final)
{

    $marcatori = array(); // ho scritto marcatori per comodità .... anche se si tratta di tennisti

    foreach ($athletes as $athlete)
    {
        foreach ($array_final as $value)
        {
            if ($value["0"]['id_atleta_tennis'] == $athlete && $athlete != "0" && $athlete != "")
            {
                //echo $value["sc"]['IdSquadra']." ".$value["s"]['NomeSquadra']." ".$value["0"]['anagrafica']." ".$value["0"]['goals']."<br>";

                $val["sc"]['IdSquadra'] = $value["sc"]['IdSquadra'];
                $val["s"]['NomeSquadra'] = $value["s"]['NomeSquadra'];
                $val["0"]['anagrafica'] = $value["0"]['anagrafica'];
                $val["0"]['goals'] = $value["0"]['goals'];

                $marcatori["1"][] = $val;
            }
        }
    }

    return $marcatori;

}







// ----------------------------------------------------------------------------------------------------------------------
?>

<ul class="switch-table-menu  nav nav-tabs">

    <? foreach ($giornate as $i => $giornata): ?>

        <? if ($i == 0): ?>

            <li class="selected"><a href="javascript:;" title=""><?= $giornata['Campionati']['Nome']; ?> <? if ($giornata['Half']['Descrizione'] != "."): ?> | <?= $giornata['Half']['Descrizione']; ?><? endif; ?></a></li>

        <? endif; ?>

    <? endforeach; ?>

</ul>

<div class="clear"></div>

<div id="results-box">

    <!-- TABELLA CALENDARIO -->

    <? foreach ($giornate as $i => $giornata): ?>

        <table class="table-matches table table-bordered table-striped table-condensed <?= ($giornata['Match']['Giornata'] != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $giornata['Match']['Giornata']; ?>">

            <thead>
                <tr class="table-header">
                    <th>Societ&agrave;</th>
                    <th>Nominativo</th>			
                    <!--<th class="text-center">Goal</th>-->
                    <th class="text-center"><?= $type_points[$type_sport[$nameClass]] ?></th><!-- //GIUSEPPE 2016-12-13 -->
                </tr>
            </thead>

            <!--//GIUSEPPE 2017-04-06 !>
            <? if ($sport == "CALCIO"): ?>
                                                                                                    
                <? $marks = $marcatori[$giornata['Match']['Giornata']]; ?>
                                                                                                    
            <? elseif ($sport == "TENNIS"): ?>
                                                                                                    
                <? $marks = estrai_punti_tennis($giornate, $array_final)[1]; ?>
                                                                                                 
            <? endif; ?>
            <!------!>
            
            <? $i = 0; ?>
            <? //print_r($marks)  ?> 
            <? foreach ($marks as $k => $marcatore): ?>
                                                                                              
                <? if ($marcatore[0]['anagrafica'] != ""): ?>
                                                                                                                                                                                                                    <!--<tr class="<?= (($k + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-id="<?= $marcatore['sc']['IdSquadra']; ?>">-->
                    <tr class="<?= (($s + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-id="<?= $marcatore['sc']['IdSquadra']; ?>">
                        <td class="text-left"><?= $marcatore['s']['NomeSquadra']; ?></td>
                        <td class="text-left"><?= $marcatore[0]['anagrafica']; ?></td>
                        <td class="text-center"><?= $marcatore[0]['goals']; ?></td>
                    </tr>
                    <? $i++; ?>
                <? endif; ?>
            <? endforeach; ?>

        </table>

    <? endforeach; ?>

</div><!-- close results-box -->
