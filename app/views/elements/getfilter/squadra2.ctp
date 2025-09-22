<?
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$sport = array('primary' => 'CALCIO', 'secondary' => 'CALCIO', 'quaternary' => 'TENNIS');

$type_sport = $sport[$nameClass];
?>


<?
//GIUSEPPE 2017-04-03 ranking atleti piu partite (giocate, vinte, perse)- - - - -
if ($type_sport == "TENNIS")
{
    $ranking = $this->requestAction('athletes/ranking_atleti/' . $anno_sportivo)['total']; // questo valore lo troviamo nel controller  
    //file_put_contents("_rank.txt", print_r($ranking, true));
}




function read_singlo_doppio($points)
{
    $stato_risultati = array();

    //print_r($points);

    if (isset($points['win_s']) && ($points['win_s'] > 0) && ($points['win_s'] != ""))
    {
        $stato_risultati[] = $points['win_s'] . "s";
    }
    if (isset($points['win_d']) && ($points['win_d'] > 0) && ($points['win_d'] != ""))
    {
        $stato_risultati[] = $points['win_d'] . "d";
    }

    if (count($stato_risultati))
    {
        echo " (" . implode(", ", $stato_risultati) . ")";
        //echo " → " . implode(",", $stato_risultati);
    }
}




function read_points($ranking, $id)
{
    $points = array();

    $points['ranking'] = 0;

    $points['giocate'] = 0;

    $points['vinte'] = 0;

    $points['perse'] = 0;

    $points['win_d'] = 0;

    $points['win_s'] = 0;

    foreach ($ranking as $single_ranking)
    {
        if ($id == $single_ranking['Atleta'])
        {
//            $points['ranking'] = $single_ranking['points'];

            $points['ranking'] = $single_ranking['points_f_s'] + $single_ranking['points_f_d'];

            $points['giocate'] = $single_ranking['partite']['giocate'];

            $points['vinte'] = $single_ranking['partite']['vinte'];

            $points['perse'] = $single_ranking['partite']['perse'];

            $points['win_d'] = $single_ranking['partite']['win_d'];

            $points['win_s'] = $single_ranking['partite']['win_s'];


            $points['plus_s'] = $single_ranking['plus_s'];

            $points['plus_d'] = $single_ranking['plus_d'];


            break;
        }
    }

    return $points;
}




function image_avatar($id)
{

    $row = array();

    $img['path'] = ""; // nel caso non ci sia nessun avatar, inserisco l'immagine di default

    $img['ext'] = 'png';

    $query = "SELECT COUNT(path) as num_file, ext, path FROM files WHERE athlete_id = '$id'";

    $result = mysql_query($query);

    $row = mysql_fetch_assoc($result);

    if ($row['num_file'] > 0)
    {
        $img['path'] = $row['path'];

        $img['ext'] = $row['ext'];
    }

    return $img;
}
?>


<!--
        <h2><?= $squadra['Squadre']['Denominazione']; ?></h2>
-->


<div class="clear"></div>
<div class="atleta-boxes" style="">
    <div class="row">
        <? foreach ($roster as $k => $atleta): ?>

            <!--
            <? //print_r($atleta);                        ?>
            -->

            <div class="atleta-box col-md-4" style="">
                <blockquote class="with-borders squadra-block" style="cursor: default;">
                    <div class="row">
                        <div class="col-md-5" style="">

                            <?
                            //GIUSEPPE 2017-02-16
                            $info_link = image_avatar($atleta['Athlete']['Atleta']);

                            $link = $info_link['path'];

                            $ext = $info_link['ext'];
                            ?>
                            <div class="text-center">
                                <div class="img-thumbnail" style="width: 100px; height: 62px;">

                                    <div style="width: 90px; height: 90px; <? if (!empty($link)): ?>background-image:url(<?= $thumbnail->link(array('path' => $link, 'w' => '100', 'f' => $ext)); ?>);<? endif; ?> background-size: contain; background-position: center center; background-repeat: no-repeat; text-align: center;" alt="">
                                        <? if (empty($link)): ?>
                                            <i class="fa fa-user fa-4x" style="line-height: 90px;"></i>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div><br />
                            <?
                            if ($type_sport == "CALCIO")
                            {
                                ?>
                                <div class="text-center">
                                    <div class="label label-danger"><?= $atleta['stats']['Espulsioni']; ?></div>
                                    <div class="label label-warning"><?= $atleta['stats']['Ammonizioni']; ?></div>
                                </div>
                            <? } ?>
                        </div>

                        <div class="col-md-7" style="">


                            <p class="atleta-nome text-color-primary"><?= $atleta['Athlete']['Cognome']; ?> <?= $atleta['Athlete']['Nome']; ?></p>  

                            <? if ($type_sport == "CALCIO"): ?>

                                <table class="table table-condensed table-striped table-responsive">
                                    <tr>
                                        <th>Tessera</th><td><?= $atleta['Yearbook']['Tessera']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ruolo</th><td><?= $atleta['Yearbook']['Ruolo']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Reti</th><td><?= $atleta['stats']['Reti']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Presenze</th><td><?= $atleta['stats']['Presenze']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>N° maglia</th><td><?= $atleta['Yearbook']['NumeroMaglia']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Assicurazione</th><td><?= $atleta['TipiAssicurazione']['Simbolo']; ?></td>
                                    </tr>
                                </table>    

                            <? elseif ($type_sport == "TENNIS"): ?>

                                <?
                                //$stato_partite = stato_partite($atleta['Athlete']['Atleta'], $ranking)
                                $points = read_points($ranking, $atleta['Athlete']['Atleta']);
                                ?>

                                <table class="table table-condensed table-striped table-responsive">
                                    <tr>
                                        <!--<th>Ranking</th><td><?= (int) $points['ranking'] + (int) $points['plus_s'] + (int) $points['plus_d']; ?></td>-->
                                        <th>Ranking</th><td><?= sprintf("%.2f", $points['ranking'] + $points['plus_s'] + $points['plus_d']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Gare giocate</th><td><?= $points['giocate']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Gare vinte</th><td>
                                            <?= $points['vinte']; ?>
                                            <?= read_singlo_doppio($points); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gare perse</th><td><?= $points['perse'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tessera</th><td><?= $atleta['Yearbook']['Tessera']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Assicurazione</th><td><?= $atleta['TipiAssicurazione']['Simbolo']; ?></td>
                                    </tr>
                                </table><!-- -->

                            <? endif; ?>

                        </div>
                    </div>
                </blockquote>
            </div>

            <? if (($k + 1) % 3 == 0): ?>


            <? endif; ?>
        <? endforeach; ?>
    </div>
    <div class="row">      
    </div>

    <div class="clear"></div>
</div>

