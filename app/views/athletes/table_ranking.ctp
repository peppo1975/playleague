<!-- //GIUSEPPE 2019-10-05 -->
<? $single_double = array('points_f_s' => 'single', 'points_f_d' => 'double') ?>


 

<div id="results-box">

    <!-- TABELLA CALENDARIO -->
    <? $num_sesso = 0; ?>
    <table class="table-matches table table-bordered table-striped table-condensed">
        <thead>
            <tr class="table-header">
                <th>Societ&agrave;</th>
                <th>Nominativo</th>         
                <th class="text-center">Punti</th><!-- //GIUSEPPE 2016-12-13 -->
            </tr>
        </thead>

        <? foreach ($ranking[$single_double[$tipo]] as $i => $atleta): ?>
            <? //= json_encode($atleta)." ".$sesso ?>


            <? ob_start() ?>
            <? if ((int) $atleta['partite']['giocate'] > 0): ?>
                <? include('info_atleta_tennis.ctp') ?>
            <? else: ?>
                Solo punti plus
            <? endif; ?>

            <!-- //GIUSEPPE 2019-11-11 -->
            <?
            $plus = 0;
            if ($tipo == "points_f_s"):
//                $plus = $plus_singolo; /* valore presente in "info_atleta_tennis.ctp" */
                $plus = $atleta['plus_s']; /* valore presente in "info_atleta_tennis.ctp" */
            endif;
            if ($tipo == "points_f_d"):
//                $plus = $plus_doppio; /* valore presente in "info_atleta_tennis.ctp" */
                $plus = $atleta['plus_d']; /* valore presente in "info_atleta_tennis.ctp" */
            endif;
            ?>


            <!-- ********************* -->

            <? $html = ob_get_clean(); ?>

            <? if ($atleta['Anagrafica'] != "" && $atleta['Sesso'] == $sesso && (int) $atleta[$tipo] > 0): ?>

                <? $num_sesso ++; ?>
                <!-- $tipo = points_s or points_d -->
                <tr class="<?= (($i + 1) % 2 == 0) ? 'alternate' : ''; ?> "  >
                    <td class="text-left"><?= $atleta['squadra']; ?></td>
                    <td class="text-left "  ><a class="popup-marker" id="<?= $atleta['Atleta']; ?>" data-toggle="popover" title="<?= $atleta['Anagrafica']; ?>"   data-content='<?= ($html) ?>'><?= $atleta['Anagrafica']; ?></a></td>
                    <!--<td class="text-center"><?= (int) $atleta[$tipo] + (int) $plus ?></td>-->
                    <td class="text-center"><?= sprintf("%.2f", $atleta[$tipo] + $plus) ?></td>
                </tr>


            <? endif; ?>

        <? endforeach; ?>


        <? if ($num_sesso == 0): ?>
            <tr class="<?= (($i + 1) % 2 == 0) ? 'alternate' : ''; ?>">
                <td class="text-left"><?= "graduatoria non presente"; ?></td>
                <td class="text-left"><?= "graduatoria non presente"; ?></td>
                <td class="text-center"><?= "graduatoria non presente"; ?></td>
            </tr>
        <? endif; ?>

    </table>

</div><!-- close results-box -->

