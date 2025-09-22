<?
//GIUSEPPE  19/11/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$all_teams = array();
$for_rip = array();
//print_r($giornate);
foreach ($giornate as $g)
{

    $all_teams[$g['0']['SquadraCasa']] = $g['0']['SquadraCasa'];
    $all_teams[$g['0']['SquadraTrasferta']] = $g['0']['SquadraTrasferta'];


    if ($g['0']['SquadraCasaServizio'] != "1")
    {
        $for_rip[$g['0']['SquadraCasa']] = $g['0']['SquadraCasa'];
    }


    if ($g['0']['SquadraTrasfertaServizio'] != "1")
    {
        $for_rip[$g['0']['SquadraTrasferta']] = $g['0']['SquadraTrasferta'];
    }
}





function punti($casa_points, $autogoal_casa, $trasferta_points, $autogoal_trasferta, $nameClass)
{
    $result = "";

    $casa_g = 0; /* goal */
    $casa_a = 0; /* autogoal */
    $trasferta_g = 0; /* goal */
    $trasferta_a = 0; /* autogoal */

    if (is_numeric($casa_g))
    {
        $casa_g = $casa_points;
    }

    if (is_numeric($casa_a))
    {
        $casa_a = $autogoal_casa;
    }

    if (is_numeric($trasferta_g))
    {
        $trasferta_g = $trasferta_points;
    }

    if (is_numeric($trasferta_a))
    {
        $trasferta_a = $autogoal_trasferta;
    }

    if ($nameClass == "primary" || $nameClass == "secondary")
    {
        $tot_goals = $casa_g + $casa_a + $trasferta_g + $trasferta_a;

//        if ($tot_goals)
        if (is_numeric($casa_points) || is_numeric($autogoal_casa) || is_numeric($trasferta_points) || is_numeric($autogoal_trasferta))
        {
            if (($tot_goals || ($casa_g + $trasferta_a) . " - " . ($trasferta_g + $casa_a) === "0 - 0"))
            {
                $result = ($casa_g + $trasferta_a) . " - " . ($trasferta_g + $casa_a);
            }
        }
    }


    if ($nameClass == "quaternary")
    {
        $result = ($casa_g + $trasferta_a) . "-" . ($trasferta_g + $casa_a);
    }


    return $result;
}
?>

<ul class="switch-table-menu  pagination pagination-sm">

    <li><a href="javascript:;" style="cursor: default;" onclick="return false;">Giornate:</a></li>

    <? for ($i = 1; $i <= $num_giornate; $i++): ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <!--<li class="switch-giornata calendario <?= ($giornata['Match']['Giornata'] == $nextDay) ? 'active' : ''; ?>" data-giornata-id="<?= $giornata['Match']['Giornata']; ?>"><a href="javascript:;" title="Giornata <?= $giornata['Match']['Giornata']; ?>"><?= $giornata['Match']['Giornata']; ?></a></li>-->
        <li class="switch-giornata calendario <?= ($i == $nextDay) ? 'active' : ''; ?>" data-giornata-id="<?= $i; ?>"><a href="javascript:;" title="Giornata <?= $i; ?>"><?= $i; ?></a></li>

    <? endfor; ?>

</ul>


<div class="clear"></div>

<div id="results-box">

    <!-- TABELLA CALENDARIO -->

    <? for ($i = 1; $i <= $num_giornate; $i++): ?>

        <? $d = $for_rip ?>
                                                                                                                                                                                                                                                                                    <!--<table class="table-matches  table table-bordered table-striped table-condensed <?= ($giornata['Match']['Giornata'] != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $giornata['Match']['Giornata']; ?>">-->
        <table class="table-matches  table table-bordered table-striped table-condensed <?= ($i != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $i; ?>">
            <thead>
                <tr class="table-header">
                    <th class="text-center">Giorno</th>
                    <th class="text-center">Ora</th>

                    <? if ($nameClass == "primary" || $nameClass == "secondary"): ?><!--//GIUSEPPE 2016/11/22 -->

                        <th>Impianto</th>
                        <th>Partita</th>
                        <th class="text-center">Ris.</th>
                        <th>Note</th>
                        <th>Gara</th>
                        <th>&nbsp;</th>

                    <? elseif ($nameClass == "quaternary"): ?>

                        <th>Impianto</th>
                        <th>Partita</th>
                        <th>Sing. 1</th>
                        <th>Sing. 2</th>
                        <th>Doppio</th>
                        <th>Ris.</th>
                        <th>Gara</th>
                        <th>Note</th>

                    <? endif; ?>

                </tr>
            </thead>

            <? foreach ($giornate as $k => $match): ?>
                <? // print_r($match) ?>
                <? if ($match["Calendari"]["Giornata"] == $i): ?>
                    <tr class="<?= ( ($k + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-id="<?= $match['Calendari']['Casa']; ?>" data-trasferta-id="<?= $match['Calendari']['Trasferta']; ?>">
                        <td><span class="number"><?= $match['0']['Data']; ?></span></td>
                        <td><span class="number"><?= $match['Calendari']['Ora']; ?></span></td>
                        <td class="text-left">
                            <?
                            if ($match['Campi']['isMidland'] == 1 && isset($match['Campi']['isMidland']))
                                $campo_link = '/impianti/' . $match['Calendari']['Campo'] . '/' . strtolower(Inflector::Slug($match['Campi']['NomeCampo'], '-'));
                            else
                                $campo_link = '';
                            ?>
                            <? if ($campo_link != ''): ?>
                                <a href="<?= $campo_link; ?>" title="<?= $match['Campi']['NomeCampo']; ?>">
                                    <?= $match['Campi']['NomeCampo']; ?>
                                </a>
                            <? else: ?>
                                <?= $match['Campi']['NomeCampo']; ?>
                            <? endif; ?>
                        </td>

                        <td class="text-left"><a href="/squadra/dettaglio/<?= $match['0']['Casa_Id']; ?>/<?= strtolower(Inflector::Slug($match['0']['SquadraCasa'], '-')); ?>" title="<?= $match['0']['SquadraCasa']; ?>"><?= $match['0']['SquadraCasa']; ?></a> <small><strong>vs</strong></small> <a href="/squadra/dettaglio/<?= $match['0']['Trasferta_Id']; ?>/<?= strtolower(Inflector::Slug($match['0']['SquadraTrasferta'], '-')); ?>" title="<?= $match['0']['SquadraTrasferta']; ?>"><?= $match['0']['SquadraTrasferta']; ?></td>

                        <?
                        if (isset($d[$match['0']['SquadraTrasferta']]))
                        {
                            unset($d[$match['0']['SquadraTrasferta']]);
                            //unset($d[$match['0']['SquadraCasa']]);
                        }
                        if (isset($d[$match['0']['SquadraCasa']]))
                        {
                            //unset($d[$match['0']['SquadraTrasferta']]);
                            unset($d[$match['0']['SquadraCasa']]);
                        }
                        ?>
                        <? if ($nameClass == "primary" || $nameClass == "secondary"): ?>

                            <td  class="text-center">

                                <span class="number"><!--//GIUSEPPE 2016/11/15 -->

                                    <? $risultato = punti($match['0']['GoalCasa'], $match['0']['AutoGoalCasa'], $match['0']['GoalTrasferta'], $match['0']['AutoGoalTrasferta'], $nameClass) ?>

                                    <? if ($risultato != ""): ?>

                                        <a href="javascript:;" onclick="timmy_load('/sections/getResult/<?= $match['Calendari']['Calendario']; ?>');"><?= $risultato; ?></a>

                                    <? endif; ?>

                                </span>
                            </td>

                        <? elseif ($nameClass == "quaternary"): ?>

                            <? $risultato = punti($match['0']['GoalCasa'], $match['0']['AutoGoalCasa'], $match['0']['GoalTrasferta'], $match['0']['AutoGoalTrasferta'], $nameClass) ?>

                            <td  class="text-left">
                                <span class="number"><!--//GIUSEPPE 2017/23/08 SING 1 -->
                                    <? if ($risultato != "0-0"): ?>
                                        <?= $match[0]['SetPartita']['sing_1']; ?>
                                    <? endif; ?>
                                </span>
                            </td>

                            <td  class="text-left">

                                <span class="number"><!--//GIUSEPPE 2017/23/08 SING 2 -->
                                    <? if ($risultato != "0-0"): ?>
                                        <?= $match[0]['SetPartita']['sing_2']; ?>
                                    <? endif; ?>
                                </span>
                            </td>

                            <td  class="text-left">
                                <span class="number"><!--//GIUSEPPE 2017/23/08 DOPPIO -->
                                    <? if ($risultato != "0-0"): ?>
                                        <?= $match[0]['SetPartita']['doppio']; ?>
                                    <? endif; ?>
                                    <br>
                                </span>
                            </td>
                            <td  class="text-center">
                                <? if ($risultato != "0-0"): ?>
                                    <?= $risultato ?>    
                                <? endif; ?>
                            </td>
                            <td  class="text-center">
                                <?= $match['Calendari']['NomeGara'] ?>
                            </td>

                        <? endif; // riferito alla classe primary secondary quaternary                           ?>


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <!--<td class="text-left"><?= $match['Causalresult']['Descrizione']; ?></td>-->
                        <td class="text-left"><?= $match['0']['Note']; ?></td>

                        <? if ($nameClass == "primary" || $nameClass == "secondary"): ?>

                            <td class="text-left"><?= $match['Calendari']['NomeGara']; ?></td> <!---->

                            <!-- -->
                            <td class="last-column">

                                <? if ($i <= $nextDay): ?>

                                    <a href="javascript:;" class="nota-gara" data-match-id="<?= $match['Calendari']['Calendario']; ?>" title="Stampa nota gara" rel="timmytip"><img src="/img/icon-pdf.png" width="16" height="16" alt="Stampa nota gara" /></a>

                                <? endif; ?>

                            </td>

                        <? endif; ?>

                    </tr>
                <? endif; ?>
            <? endforeach; ?>

        </table>

        <? //$riposo = $riposi[$giornata['Match']['Giornata']]           ?>

        <? //if (count($riposo) && $giornata['Campionati']['Italiana'] == 'No'):        ?>
        <? //if (!substr_count($riposo[0][0]['NomeSquadra'], "Vincente")):        ?>
                                                                                                                                                                     <!--<div class="other-info-row <?= ($giornata['Match']['Giornata'] != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $giornata['Match']['Giornata']; ?>">-->
                                                                                                                                                                         <!--<p class="alert alert-info alert-sm">-->
        <!--<b>Riposa:</b>--> 
        <? //= $riposo[0][0]['NomeSquadra'];            ?>                                       
        <!--</p>-->
        <!--</div>-->
        <? // endif;          ?>

        <? // endif;            ?>



        <? if ($match['Campionati']['Italiana'] == 'No'): ?>
            <? if (count($d) > 0): ?>
                <div >  <!-- //GIUSEPPE : i riposi per adesso lo lascio hidden: escono voalri tipo "perdente gara A, vincente gara B... ecc" --> 
                    <? if (count($for_rip)): ?>
                        <div class="other-info-row <?= ($i != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $i; ?>">
                            <p class="alert alert-info alert-sm">
                                <b>Riposa:</b> <?= implode(",", $d) ?>                                      
                            </p>
                        </div>
                    <? endif; ?>  
                </div>
            <? endif; ?>
        <? endif; ?>




    <? endfor; ?>

    <div class="other-function-row">

        <div class="left">

            <? // foreach ($giornate as $i => $giornata):               ?>
            <?
            for ($i = 1; $i <= $num_giornate; $i++):
                ?>

                <div class="match-comunication <?= ($giornata['Match']['Giornata'] != $nextDay) ? 'hidden' : ''; ?>" data-giornata-id="<?= $giornata['Match']['Giornata']; ?>">

                    <? if (!empty($comunicazioni[$giornata['Match']['Giornata']])): ?>
                        <h3>Comunicazioni</h3>
                        <p><?= $comunicazioni[$giornata['Match']['Giornata']]['Comunication']['Note']; ?></p>
                    <? endif; ?>

                </div>

                <? // endforeach;                  ?>                                
            <? endfor; ?>                               


        </div>
        <div class="row">

            <div class="search-opponent hidden">

                <div class="col-lg-3">
                    <div class="select-box middle-select">
                        <div class="content-select">
                            <span class="selected-value"></span>


                            <div class="values-of-select">
                                <label class="text-left" style="display: block;"><b>Sfide dirette</b></label>
                                <select class="form-control" name="avversario_id">
                                    <option value="0">Seleziona avversario...</option>

                                    <? foreach ($avversari as $avversario): ?>

                                        <option data-squadra-id="<?= $avversario['id'] ?>" value="<?= $avversario['id'] ?>"><?= $avversario['squadra']; ?></option>

                                    <? endforeach; ?>

                                </select>
                            </div>
                        </div>
                        <div class="close-select"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-lg-9 text-right">
                    <ul class="match-results-menu hidden pagination pagination-sm">
                        <!--                        <li><a href="#" title="Giornata 02">Giornata 02</a></li>
                                                <li><a href="#" title="Giornata 12">Giornata 12</a></li>
                                                <li><a href="#" title="Giornata 15">Giornata 15</a></li> -->
                    </ul>
                </div>
            </div>

        </div>
        <div class="clear"></div>                                   
    </div>


</div><!-- close results-box -->
