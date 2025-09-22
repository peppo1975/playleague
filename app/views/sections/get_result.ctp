<?
$data['Casa']['UploadLogo'] = array();

//echo json_encode($data);

foreach ($data['Casa']['Upload'] as $up)
{
    if ($up['tag'] == 'Logo')
        $data['Casa']['UploadLogo'] = $up;
}

$data['Trasferta']['UploadLogo'] = array();

foreach ($data['Trasferta']['Upload'] as $up)
{
    if ($up['tag'] == 'Logo')
        $data['Trasferta']['UploadLogo'] = $up;
}



//GIUSEPPE 2017-10-03 --------------------------------
$totals = goal_autogoal($data);

$goals = $totals['goals'];

$num_rows = $totals['rows'];



function goal_autogoal($data)
{
    $goals = array();

    $points_casa_trasf = array();

    $points_casa_trasf['Casa'] = $data['AtletiCasa'];

    $points_casa_trasf['Trasferta'] = $data['AtletiTrasferta'];

    $count_row = 0;

    foreach ($points_casa_trasf as $ind => $points)
    {
        $temp = array();

        foreach ($points as $item)
        {
            if ($item['Goal'] || $item['Autogoal'])
            {
                $atleta = array();

                $id = $item['Atleta'];

                $anagrafica = $item['Anagrafica'];

                $goal = $item['Goal'];

                $autogoal = $item['Autogoal'];


                $atleta['Atleta'] = $id;

                $atleta['Anagrafica'] = $anagrafica;

                $atleta['Goal'] = $goal;

                $atleta['Autogoal'] = $autogoal;

                $temp[$ind][$id] = $atleta;
            }
        }

        foreach ($temp[$ind] as $single)
        {
            $goals[$ind][] = $single;
        }

        if ($count_row < count($goals[$ind]))
        {
            $count_row = count($goals[$ind]);
        }
    }

    return array('goals' => $goals, 'rows' => $count_row);

}





function view_markers($markers, $i)
{
    $anagrafica = $markers[$i]['Anagrafica'];

    $goal = "";

    $autogoal = "";

    if ($markers[$i]['Goal'] > 0)
    {
        $goal = "(" . $markers[$i]['Goal'] . ")";
    }
    if ($markers[$i]['Autogoal'] > 0)
    {
        $autogoal = "<b>(a:" . $markers[$i]['Autogoal'] . ")";
    }




    return $anagrafica . " " . $goal . " " . $autogoal;

}

//----------------------------------------------------
?>

<div class="booking-data">

    <h4 class="modal-title" id="defaultModalLabel">
        <? if (isset($data['Casa']['UploadLogo']['path']) && $data['Casa']['UploadLogo']['path']): ?><img class="img-thumbnail" src="<?= $thumbnail->link(array('path' => $data['Casa']['UploadLogo']['path'], 'remote' => 1, 'w' => 39, 'f' => 'png', 'zc' => 0, 'h' => 33)); ?>" alt="<?= $data['Match']['CasaNome']; ?>" />&nbsp;<? else: ?><span style="" class="img-thumbnail text-center"><i class="fa fa-shield" style="width: 26px; height: 32px; color: #777; vertical-align: center; display: block; line-height: 34px"></i></span>&nbsp;<? endif; ?><?= $data['Match']['CasaNome']; ?> vs  <?= $data['Match']['TrasfertaNome']; ?><? if (isset($data['Trasferta']['UploadLogo']['path']) && $data['Trasferta']['UploadLogo']['path'] != ''): ?>&nbsp;<img class="img-thumbnail" src="<?= $thumbnail->link(array('path' => $data['Trasferta']['UploadLogo']['path'], 'remote' => 1, 'f' => 'png', 'w' => 39, 'h' => 33, 'zc' => 0)); ?>" alt="<?= $data['Match']['TrasfertaNome']; ?>" /><? else: ?>&nbsp;<span style="" class="img-thumbnail text-center"><i class="fa fa-shield" style="width: 26px; height: 32px; color: #777; vertical-align: center; display: block; line-height: 34px"></i></span><? endif; ?>											
    </h4>
    <table class="table table-bordered table-striped table-condensed"> 
        <thead>
            <tr>
                <th class="text-left">Data</th>
                <th class="text-left">Home</th>
                <th class="text-left">Visitors</th>
                <th class="text-center">Risultato</th>
            </tr>
        </thead>
        <tr>
            <td><?= $data['Match']['Data_it']; ?> - ore <?= $data['Match']['Ora']; ?></td>
            <td><?= $data['Match']['CasaNome']; ?></td>
            <td><?= $data['Match']['TrasfertaNome']; ?></td>	
            <td class="text-center"><?= str_replace("-", " - ", $data['Match']['Risultato']); ?></td>										
        </tr>

    </table>

    <table class="table table-bordered table-striped table-condensed">

        <thead>
            <tr>
                <th class="text-left">Marcatori <?= $data['Match']['CasaNome']; ?></th>
                <th class="text-left">Marcatori <?= $data['Match']['TrasfertaNome']; ?></th>
            </tr>
        </thead>

       <!-- //GIUSEPPE 2017-10-03 -------------------------------- -->
        <? for ($i = 0; $i < $num_rows; $i++): ?>

            <tr>
                <td class="text-left"><?= view_markers($goals['Casa'], $i) ?></td>
                <td class="text-left"><?= view_markers($goals['Trasferta'], $i) ?></td>
            </tr>

        <? endfor; ?>
         <!--   //---------------------------------------------------- -->

    </table>

</div>

