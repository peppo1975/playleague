

<div class="row">
    <div class="atleta-box col-md-12" style="">

        <div class="row">

            <?
            $link = $images[$atleta['Atleta']]['path'];

            $ext = $images[$atleta['Atleta']]['ext'];
            ?>

            <? if (!empty($link)): ?>
                <div class="text-center">
                    <div class="img-thumbnail" style="width: 100px; height: 100px;">
                        <div style="width: 90px; height: 90px; background-image:url(<?= $thumbnail->link(array('path' => $link, 'w' => '100', 'f' => $ext)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; text-align: center;" alt="">

                        </div>
                    </div>
                </div><br/>
            <? endif; ?>

            <? $plus_singolo = $tessera_assicurazione[$atleta['Atleta']]['PuntiSingoloPlus']; ?>
            <? $plus_doppio = $tessera_assicurazione[$atleta['Atleta']]['PuntiDoppioPlus']; ?>

            <div class="col-md-12">

                <table class="table">
                <!--<table class="table table-condensed table-striped table-responsive">-->
                    <tbody><tr>
                            <!--<th>Ranking</th><td><?= (int) $atleta['points'] + (int) $plus_singolo + (int) $plus_doppio; ?></td>-->
                            <th>Ranking</th><td><?= sprintf("%.2f", $atleta['points_f_s'] + $atleta['points_f_d'] + $plus_singolo + $plus_doppio); ?></td>
                        </tr>
                        <tr>
                            <th>Gare giocate</th><td><?= $atleta['partite']['giocate']; ?></td>
                        </tr>
                        <tr>
                            <th>Gare vinte</th>
                            <td><?= calcola_gare($atleta) ?></td>
                        </tr>
                        <tr>
                            <th>Gare perse</th><td><?= $atleta['partite']['perse']; ?></td>
                        </tr>
                        <tr>
                            <th>Tessera</th><td><?= $tessera_assicurazione[$atleta['Atleta']]['Tessera']; ?></td>
                        </tr>
                        <tr>
                            <th>Assicurazione</th><td><?= $tessera_assicurazione[$atleta['Atleta']]['Assicurazione']; ?></td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>

    </div>
</div>
