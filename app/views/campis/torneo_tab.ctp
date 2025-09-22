<?
$id_teams = array_reverse($id_teams_rev);


//GIUSEPPE 2019-12-29 -----------------------------------------------

$max_id = count($id_teams) - 1;

$num_result = count($id_teams[$max_id]);

//$stop = false;

do
{
    $empty = array();

    if ((int) $num_result > 1)
    {
        $num_result = $num_result / 2;

        for ($i = 0; $i < $num_result; $i++)
        {
            $empty[$i]['casa'] = "1";
            $empty[$i]['trasferta'] = "2";
        }

        $id_teams[] = $empty;
    }
    else
    {
        //$stop = true;
        break;
    }
}
while (true);
//while (!$stop);

// ------------------------------------------------------------------
?>

<style>
    .game{
        cursor: pointer;
    }

    .popover{
        max-width:1000px;
    }

    .glyphicon-remove
    {
        cursor: pointer; 
    }
</style>

<script>
    $(function ()
    {
//        $('[data-toggle="popover"]').popover({html: true, container: 'body'})

        $('.popup-marker').popover({
            html: true,
            trigger: 'manual',
            container: 'body'
        }).click(function (e)
        {
            $('[data-toggle="popover"]').popover('hide');
            $(this).popover('toggle');

            e.preventDefault();

        });


    });

    function close_popover()
    {
        $('[data-toggle="popover"]').popover('hide');

    }
</script>
<? // print_r($atleti)    ?>

<div role="main" class="main">



    <div id="tournament" class="torneo-tennis" style="width: 1100px; margin: 0px auto 40px;">
        <? $match = ""; ?>
        <? $vincitore = ""; ?>
        <? $last_day = ""; ?>

        <? foreach ($id_teams as $day => $num_team): ?>
            <? $last_day = $day + 1; ?>
            <? $match = count($num_team) ?>
            <ul class="round round-<?= $day + 1 ?>">

                <? foreach ($num_team as $teams): ?>
                    <?
                    $nome_casa = str_replace('"', '&quot;', $info_result[$day + 1][$teams['casa']]['nome']);
                    $punti_casa = $info_result[$day + 1][$teams['casa']]['punti'];
                    $set_casa = json_decode($info_result[$day + 1][$teams['casa']]['set'], true);
                    $sing_1_casa = sprintf("%s / %s / %s", $set_casa['points']["s_1_1"], $set_casa['points']["s_1_2"], $set_casa['points']["s_1_3"]);
                    $sing_2_casa = sprintf("%s / %s / %s", $set_casa['points']["s_3_1"], $set_casa['points']["s_3_2"], $set_casa['points']["s_3_3"]);
                    $doppio_casa = sprintf("%s / %s / %s", $set_casa['points']["s_5_1"], $set_casa['points']["s_5_2"], $set_casa['points']["s_5_3"]);
                    $atleta_casa_s_1 = $atleti[$set_casa['athletes']['casa_s1']];
                    $atleta_casa_s_2 = $atleti[$set_casa['athletes']['casa_s2']];
                    $atleta_casa_d = sprintf("%s - %s", $atleti[$set_casa['athletes']['casa_d1']], $atleti[$set_casa['athletes']['casa_d2']]);


                    $nome_trasferta = str_replace('"', '&quot;', $info_result[$day + 1][$teams['trasferta']]['nome']);
                    $punti_trasferta = $info_result[$day + 1][$teams['trasferta']]['punti'];
                    $set_trasferta = json_decode($info_result[$day + 1][$teams['trasferta']]['set'], true);
                    $sing_1_trasferta = sprintf("%s / %s / %s", $set_trasferta['points']["s_1_1"], $set_trasferta['points']["s_1_2"], $set_trasferta['points']["s_1_3"]);
                    $sing_2_trasferta = sprintf("%s / %s / %s", $set_trasferta['points']["s_3_1"], $set_trasferta['points']["s_3_2"], $set_trasferta['points']["s_3_3"]);
                    $doppio_trasferta = sprintf("%s / %s / %s", $set_trasferta['points']["s_5_1"], $set_trasferta['points']["s_5_2"], $set_trasferta['points']["s_5_3"]);
                    $atleta_trasferta_s_1 = $atleti[$set_casa['athletes']['trasferta_s1']];
                    $atleta_trasferta_s_2 = $atleti[$set_casa['athletes']['trasferta_s2']];
                    $atleta_trasferta_d = sprintf("%s - %s", $atleti[$set_casa['athletes']['trasferta_d1']], $atleti[$set_casa['athletes']['trasferta_d2']]);



                    if ($atleta_casa_s_1 == "")
                    {
                        $atleta_casa_s_1 = "-";
                    }


                    if ($atleta_casa_s_2 == "")
                        $atleta_casa_s_2 = "-";

                    if ($atleta_trasferta_s_1 == "")
                        $atleta_trasferta_s_1 = "-";

                    if ($atleta_trasferta_s_2 == "")
                        $atleta_trasferta_s_2 = "-";



                    $data = $info_result[$day + 1][$teams['casa']]['data'];
                    $ora = $info_result[$day + 1][$teams['casa']]['ora'];
                    $campo = $info_result[$day + 1][$teams['casa']]['campo'];


                    $winner_casa = "";
                    $winner_trasferta = "";

                    $game_top = "";
                    $game_bottom = "";
                    $style = "border-right: 1px solid #fff;";
                    ?>



                    <? ob_start() ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sing. 1</th>
                                <th scope="col">Sing. 2</th>
                                <th scope="col">Doppio</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $atleta_casa_s_1 ?><br><?= $sing_1_casa ?></td>
                                <td><?= $atleta_casa_s_2 ?><br><?= $sing_2_casa ?></td>
                                <td><?= $atleta_casa_d ?><br><?= $doppio_casa ?></td>
                            </tr>
                            <tr>
                                <td><?= $atleta_trasferta_s_1 ?><br><?= $sing_1_trasferta ?></td>
                                <td><?= $atleta_trasferta_s_2 ?><br><?= $sing_2_trasferta ?></td>
                                <td><?= $atleta_trasferta_d ?><br><?= $doppio_trasferta ?></td>

                            </tr>
                        </tbody>
                    </table>

                    <? $t_gara = str_replace('"', '&quot;', ob_get_clean()) ?>





                    <? ob_start() ?>

                    <table style="width: 100%">
                        <tr>
                            <td><strong><?= $nome_casa ?> <small><strong>vs</strong></small> <?= $nome_trasferta ?></strong></td>
                            <td style="text-align: right" class="close_sel"> 
                                <!--<button type="button" class="btn btn-default btn-sm" onclick="close_popover()">-->
                                <span class="glyphicon glyphicon-remove" aria-hidden="true" onclick="close_popover()"></span>
                                <!--</button>-->
                            </td>
                        </tr>
                        <tr>
                            <td>Giornata <?= $day + 1 ?> → (<?= $punti_casa ?> - <?= $punti_trasferta ?>)</td>
                            <td></td>
                        </tr>
                    </table>

                    <? $header_gara = str_replace('"', '&quot;', ob_get_clean()) ?>





                    <?
                    $vincitore = "";

                    if ($teams['casa'] !== "" && $teams['trasferta'] !== "")
                    {
                        $style = "";

                        $game_top = "game-top";
                        $game_bottom = "game-bottom";



                        if ($punti_casa > $punti_trasferta)
                        {
                            $winner_casa = "winner";
                            $vincitore = $nome_casa;
                        }
                        elseif ($punti_casa < $punti_trasferta)
                        {
                            $winner_trasferta = "winner";
                            $vincitore = $nome_trasferta;
                        }
                    }
                    else
                    {
                        $t_gara = "";
                    }

                    $header_end = $header_gara;
                    ?>

                    <li class="spacer">&nbsp;</li>


                    <li  data-toggle="popover"   title="<?= $header_gara ?>" data-content="<?= $t_gara ?>"  data-placement="top"   class="popup-marker game <?= $game_top ?> <?= $winner_casa ?>">
                        <span><?= $nome_casa ?></span> <b><?= $punti_casa ?></b>
                    </li>

                    <li class="game game-spacer" style="<?= $style ?>">
                        <p class="data-partita"><?= $data . " " . $ora ?></p>

                        <p class="punti-set"><?= $campo ?></p>
                    </li>


                    <li data-toggle="popover"  title="<?= $header_gara ?>" data-content="<?= $t_gara ?>"  data-placement="bottom"   class="popup-marker game <?= $game_bottom ?> <?= $winner_trasferta ?>">
                        <span><?= $nome_trasferta ?></span> <b><?= $punti_trasferta ?></b>
                    </li>

                <? endforeach; ?>



                <li class="spacer">&nbsp;</li>
            </ul>

        <? endforeach; ?>



        <? ob_start() ?>
        <table style="width: 100%">
            <tr>
                <td><strong><?= $nome_casa ?> <small><strong>vs</strong></small> <?= $nome_trasferta ?></strong></td>
                <td style="text-align: right" class="close_sel"> 
                    <!--                    <button type="button" class="btn btn-default btn-sm" onclick="close_popover()">-->
                    <span class="glyphicon glyphicon-remove" aria-hidden="true" onclick="close_popover()"></span>
                    <!--</button>-->
                </td>
            </tr>
            <tr>
                <td>Giornata <?= $last_day ?> → (<?= $punti_casa ?> - <?= $punti_trasferta ?>)</td>
                <td></td>
            </tr>
        </table>
        <? $header_end = str_replace('"', '&quot;', ob_get_clean()) ?>


        <? if ($match === 1): ?>
            <?
//            if (((int) $punti_casa == 0 && (int) $punti_trasferta == 0) || ($punti_casa == "" && $punti_trasferta == ""))
//                $vincitore = "";
            ?>
            <ul class="round round-final">

                <li class="spacer">&nbsp;</li>

                <li   data-toggle="popover"   title="<?= $header_end ?>" data-content="<?= $t_gara ?>"  data-placement="top"    class="popup-marker game game-top winner"><span><?= $vincitore ?></span></li>
                <li class="spacer">&nbsp;</li>
            </ul>
        <? endif; ?>

    </div>



    <div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->						

