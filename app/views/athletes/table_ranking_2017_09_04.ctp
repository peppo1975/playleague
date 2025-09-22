<div id="results-box">

    <!-- TABELLA CALENDARIO -->

    <table class="table-matches table table-bordered table-striped table-condensed">
        <thead>
            <tr class="table-header">
                <th>Societ&agrave;</th>
                <th>Nominativo</th>			
                <!--<th class="text-center">Goal</th>-->
                <th class="text-center">Punti</th><!-- //GIUSEPPE 2016-12-13 -->
            </tr>
        </thead>

        <? foreach ($ranking as $i => $atleta): ?>
            <?//= json_encode($atleta)." ".$sesso ?>
            <? if ($atleta['Anagrafica'] != "" && $atleta['Sesso'] == $sesso): ?>
                <!--<tr class="<?= (($i + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-id="<?= $atleta['sc']['IdSquadra']; ?>">-->
                
                <tr class="<?= (($i + 1) % 2 == 0) ? 'alternate' : ''; ?>">
                    <td class="text-left"><?= $atleta['squadra']; ?></td>
                    <td class="text-left"><?= $atleta['Anagrafica']; ?></td>
                    <td class="text-center"><?= $atleta['points']; ?></td>

                </tr>
            <? endif; ?>
        <? endforeach; ?>


    </table>

</div><!-- close results-box -->