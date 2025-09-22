<? $fixed_index = $this->requestAction('fixeds/read_fixed/max_ranking_squadre'); ?>
<? $min_position = $fixed_index['max_ranking_squadre'] ?>

<table  class="table-matches table table-bordered table-striped table-condensed" style="width: 100%;">
<!--<table  class="table-matches table table-bordered table-striped table-condensed">-->
    <thead>
        <tr class="table-header">

            <th>Pos</th>

            <th>Squadra</th>

            <th>Punti</th>

        </tr>
    </thead>
    <? //   print_r($type_rounds)?>

    <?
    $partial = array();
    foreach ($ranking_teams as $index => $team)
    {
        if (($team['Tipo'] . $team['Sesso'] != $type_rounds['Tipo'] . $type_rounds['Sesso']))
            continue;

        $punti_ranking = number_format(round($team['PuntiRanking'], 2), 2, '.', '');

        $team['PuntiRanking'] = $punti_ranking;

        $partial[] = $team;
    }
    ?>

    <? $position[$type_rounds['Tipo']][$type_rounds['Sesso']] = 1; ?>

    <? foreach ($partial as $index => $team): ?>

        <?
        /* if (($team['Tipo'] . $team['Sesso'] != $type_rounds['Tipo'] . $type_rounds['Sesso']))
          continue;


          if (!isset($team['PunteggiIndividuali']))
          continue; */


        if (isset($partial[$index - 1]['PuntiRanking']))
        {
            if (($partial[$index]['PuntiRanking'] == $partial[$index - 1]['PuntiRanking']))
            {
                //echo "equal to $position ";
            }
            else
            {
                $position[$team['Tipo']][$team['Sesso']] ++;
            }
        }


        // non vado oltre la posizione 300
        if ($position[$team['Tipo']][$team['Sesso']] >= ($min_position + 1))
        {
            continue;
        }
        ?>
        <tr>

            <td style="width: 40px; text-align: right"><?= $position[$team['Tipo']][$team['Sesso']] ?></td>

            <td   style="text-align: left; text-transform: capitalize;"><a href="/squadra/dettaglio/<?= $team['Squadra'] ?>/" target="_blank"><?= $team['Denominazione'] ?></a></td>

            <td style="width: 40px; text-align: right; font-weight: bold"><?= $team['PuntiRanking'] ?></td>
        </tr>
    <? endforeach; ?>
</table>