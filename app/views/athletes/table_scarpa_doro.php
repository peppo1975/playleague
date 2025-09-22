<table  class="table-matches table table-bordered table-striped table-condensed" style="width: 100%;">
<!--<table  class="table-matches table table-bordered table-striped table-condensed">-->
    <thead>
        <tr class="table-header">
            <th>Pos.</th>
            <th>Atleta</th>	
            <th>Squadra/e</th>
            <th>Gare</th>
            <th>Goal</th>

        </tr>
    </thead>
    <? // print_r($type_rounds)?>
    <? $position = 1; ?>
    <? foreach ($type_rounds as $index => $athlete_scarpa): ?>
        <? // $position = $index + 1; ?>
        <?
        if ($index > 0)
        {
            if (($type_rounds[$index]['GareGoal'] == $type_rounds[$index - 1]['GareGoal']) && ($type_rounds[$index]['Goal'] == $type_rounds[$index - 1]['Goal']))
            {
                //echo "equal to $position ";
            }
            else
            {
                $position++;
            }
        }
        ?>
        <tr>
            <td style="width: 40px; text-align: right"><?= $position ?></td>
            <td  style="text-align: left"><?= $athlete_scarpa['Nominativo'] ?></td>
            <td   style="text-align: left; text-transform: capitalize;"><?= implode(", ", $athlete_scarpa['Squadra']) ?></td>
            <td style="width: 40px; text-align: right"><?= $athlete_scarpa['GareGoal'] ?></td>    
            <td style="width: 40px; text-align: right; font-weight: bold"><?= $athlete_scarpa['Goal'] ?></td>
        </tr>
        <? if ($position == 50): ?>
            <? break; ?>
        <? endif; ?>
    <? endforeach; ?>
</table>