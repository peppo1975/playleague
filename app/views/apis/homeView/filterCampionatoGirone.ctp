<? header('Access-Control-Allow-Origin: *'); ?>

<? $to_show_calendar = []; ?>

<?
$max_days = [];
$page_item = [];
$nomi_gironi = [];
?>

<? foreach ($calendario as $key_campionato => $campionato): ?>
    <? foreach ($campionato['Gironi'] as $key_girone => $girone_campionato): ?>

        <?
        $to_show_calendar[$key_campionato][$key_girone] = 1;
        $max_days_array = array_keys($girone_campionato['Giornata']);
        $max_days[$key_campionato][$key_girone] = $max_days_array[count($max_days_array) - 1];
        $nomi_gironi[$key_campionato][$key_girone] = $girone_campionato['Descrizione'];
        ?>
        <? ob_start(); ?>

        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" >Giornata: </a></li>
                <? for ($i = 1; $i <= $max_days[$key_campionato][$key_girone]; $i++): ?>
                                                                                                                                                                                                                    <!--<li class="page-item <?= $giorno == $i ? "active" : "" ?>">-->
                    <li class="page-item <?= "page-item-{$key_campionato}-{$key_girone}" ?> <?= "page-item-{$key_campionato}-{$key_girone}-{$i}" ?>">
                        <a class="page-link" 
                           id="page-link_<?= $i ?>" 
                           value_page="<?= $i ?>" 
                           value_page_girone="<?= $key_girone ?>"
                           value_page_campionato="<?= $key_campionato ?>"

                           >
                               <?= $i ?>
                        </a>
                    </li>
                <? endfor; ?>
            </ul>
        </nav>



        <? // $page_item[$key_campionato][$key_girone] = ob_get_contents(); ?>
        <? $page_item[$key_campionato][$key_girone] = ob_get_clean(); ?>

    <? endforeach; ?>
<? endforeach; ?>

<? // ob_get_clean(); ?>

<? $this->write_file("_page_item.json", json_encode($page_item)); ?>




<? ob_start(); ?>

<div id="calendario" class="tabelle" style="display: none">

    <? $to_show_calendar = [] ?>
    <? foreach ($calendario as $key_campionato => $campionato): ?>
        <? $italiana = $campionato['Italiana']; ?>
        <? foreach ($campionato['Gironi'] as $key_girone => $girone_campionato): ?>

            <?
            $to_show_calendar[$key_campionato][$key_girone] = 1;

            $squadre = $girone_campionato['Squadre'];
            ?>
            <? $filter_class = "table-calendar" ?>
            <? $filter_id = "calendar_table" ?>



            <? for ($giorno = $max_days[$key_campionato][$key_girone]; $giorno >= 1; $giorno--): ?>

                <? $squadre_riposo = $squadre; ?>

                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($campionato['Gironi']) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <div class="table-block">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Giorno</th>
                                <th>Ora</th>
                                <th>Impianto</th>
                                <th>Partita</th>
                                <th style="text-align:center">Risultato</th>
                                <th>Note</th>
                                <th>Gara</th>
                                <th></th>
                            </tr>

                            <? $giornata = []; ?>
                            <? if (!isset($girone_campionato['Giornata'][$giorno])): ?>
                                <tr><td colspan="7">Nessuna partita in programma</td></tr>
                            <? else: ?>
                                <? $giornata = $girone_campionato['Giornata'][$giorno]; ?>
                                <? $is_complete = true; ?>
                                <? foreach ($giornata['Partita'] as $key_partita => $partita): ?>
                                    <?
                                    if ($italiana == "No")
                                    {
                                        unset($squadre_riposo[$partita['Casa']['Squadra']]);
                                        unset($squadre_riposo[$partita['Trasferta']['Squadra']]);
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $partita['Data'] ?></td>
                                        <td><?= $partita['Ora'] ?></td>
                                        <td><?= $partita['Campo']['Descrizione'] ?></td>
                                        <td><a class="nomeSquadra" id_squadra="<?= $partita['Casa']['Squadra'] ?>" style="cursor: pointer"><?= $partita['Casa']['Denominazione'] ?></a> vs <a class="nomeSquadra" id_squadra="<?= $partita['Trasferta']['Squadra'] ?>" style="cursor: pointer"><?= $partita['Trasferta']['Denominazione'] ?></a></td>
                                        <td style="text-align:center"><?= (string) $partita['Casa']['Risultato'] == '' && (string) $partita['Trasferta']['Risultato'] == '' ? "" : "{$partita['Casa']['Risultato']} - {$partita['Trasferta']['Risultato']}" ?></td>
                                        <td><?= $partita['CausaleRisultato'] == null ? "" : $partita['CausaleRisultato']['Descrizione'] ?></td>
                                        <td><?= $partita['NomeGara'] ?></td>
                                        <td>
                                            <a <?= sprintf('href="https://%s/sections/getNotes/%s/%s/?playLeague"  target="_blank"', $server, $partita['Calendario'], $partita['Casa']['SquadraCampionato']) ?>"><img src="/img/icon-pdf.png" alt="Stampa nota gara" width="16" height="16"></a> vs
                                            <a <?= sprintf('href="https://%s/sections/getNotes/%s/%s/?playLeague"  target="_blank"', $server, $partita['Calendario'], $partita['Trasferta']['SquadraCampionato']) ?>>
                                                <img src="/img/icon-pdf.png" alt="Stampa nota gara" width="16" height="16">
                                            </a>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <!--                                                <a <?= sprintf('href="http://%s/apis/notesNew/%s/%s/%s/"  target="_blank"', $server, $partita['Calendario'], $partita['Casa']['SquadraCampionato'], $partita['Trasferta']['SquadraCampionato']) ?>>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <img src="/img/icon-pdf.png" alt="Stampa nota gara" width="16" height="16">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </a>-->
                                        </td>
                                    </tr>

                                    <?
                                    if ((string) $partita['Casa']['Risultato'] == '' || (string) $partita['Trasferta']['Risultato'] == '')
                                        $is_complete = false;
                                    ?>
                                <? endforeach; ?>
                                <?
                                if ($is_complete == true)
                                {
                                    if ((int) $to_show_calendar[$key_campionato][$key_girone] < (int) $giorno)
                                    {
//                                            print "CREATO - {$key_campionato} {$key_girone} {$giorno}";
                                        $to_show_calendar[$key_campionato][$key_girone] = $giorno;
                                    }
                                }
                                ?>
                            <? endif; ?>
                            <? if ($italiana == "No" && count($squadre_riposo) > 0): ?>
                                <tr class="rest-team"><td colspan="8"><?= "<strong>Riposa:</strong> " . implode(", ", $squadre_riposo); ?></td></tr>
                            <? endif; ?>

                        </table>
                    </div>
                </div>

            <? endfor; ?>


        <? endforeach; ?>
    <? endforeach; ?>
</div>


<div id="classifiche" class="tabelle" style="display: none">

    <!--CLASSIFICHE-->

    <? foreach ($classifica as $key_campionato => $campionato): ?>
        <? $italiana = $campionato['Italiana']; ?>
        <? foreach ($campionato['Gironi'] as $key_girone => $girone_campionato): ?>

            <? if ($italiana == 'Si'): ?>
                <? continue; ?>
            <? endif; ?>

            <? for ($giorno = $max_days[$key_campionato][$key_girone]; $giorno >= 1; $giorno--): ?>

                <? $squadre_riposo = $squadre; ?>

                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($campionato['Gironi']) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <div class="table-block">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Squadra</th>
                                <th style="text-align: center">Pt</th>
                                <th style="text-align: center">PG</th>
                                <th style="text-align: center">V</th>
                                <th style="text-align: center">P</th>
                                <th style="text-align: center">S</th>
                                <th style="text-align: center">GF</th>
                                <th style="text-align: center">GS</th>
                                <th style="text-align: center">DR</th>
                                <th style="text-align: center">Coppa disc.</th>
                            </tr>

                            <? $giornata = []; ?>
                            <? if (!isset($girone_campionato['Giornata'][$giorno])): ?>

                                <tr><td colspan="7">Nessuna partita in programma</td></tr>

                            <? else: ?>
                                <? $giornata = $girone_campionato['Giornata'][$giorno]; ?>

                                <? foreach ($giornata['Classifica'] as $key_classifica => $classifica): ?>
                                    <?
                                    if ($italiana == "No")
                                    {
                                        unset($squadre_riposo[$partita['Casa']['Squadra']]);
                                        unset($squadre_riposo[$partita['Trasferta']['Squadra']]);
                                    }
                                    ?>
                                    <tr>             
                                        <td><?= $classifica['Nome'] ?></td>
                                        <td style="text-align: center"><?= $classifica['Punti'] ?></td>
                                        <td style="text-align: center"><?= $classifica['Giocate'] ?></td>
                                        <td style="text-align: center"><?= $classifica['Vinte'] ?></td>
                                        <td style="text-align: center"><?= $classifica['Nulle'] ?></td>
                                        <td style="text-align: center"><?= $classifica['Perse'] ?></td>
                                        <td style="text-align: center"><?= $classifica['GoalFatti'] ?></td>
                                        <td style="text-align: center"><?= $classifica['GoalSubiti'] ?></td>
                                        <td style="text-align: center"><?= $classifica['DifferenzaReti'] ?></td>
                                        <td style="text-align: center"><?= round(($classifica['CoppaDisciplina'] / $giorno) * 100) ?></td>
                                        <!-- <td><?= round(($classifica['CoppaDisciplina']), 2) ?></td>-->
                                    </tr>
                                <? endforeach; ?>

                            <? endif; ?>

                        </table>
                    </div>
                </div>
            <? endfor; ?>


        <? endforeach; ?>
    <? endforeach; ?>

</div>




<div id="marcatori" class="tabelle"  style="display: none">

    <!--MARCATORI-->
    <? //  print_r($marcatori); ?>

    <? foreach ($marcatori as $key_campionato => $girone): ?>

        <? foreach ($girone['Gironi'] as $key_girone => $atleta): ?>



            <? foreach ($atleta['Giornata'] as $giorno => $giornata): ?>
                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($girone['Gironi']) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Squadra</th>
                            <th>Nominativo</th>
                            <th style="text-align: center">Goal</th>
                        </tr>
                        <? foreach ($giornata as $points => $atleta_group): ?>

                            <? foreach ($atleta_group as $atleta_info): ?>
                                <? $atleta_info_expl = explode("{#}", $atleta_info); ?>
                                <tr>
                                    <td><?= $atleta_info_expl[0] ?></td>
                                    <td><?= $atleta_info_expl[1] ?></td>
                                    <td style="text-align: center"><?= (int) $atleta_info_expl[2] ?></td>
                                </tr>
                            <? endforeach; ?>

                        <? endforeach; ?>
                    </table>
                </div>
            <? endforeach; ?>

        <? endforeach; ?>
    <? endforeach; ?>

</div>





<div id="diffidati" class="tabelle"  style="display: none">

    <!--DIFFIDATI-->

    <? foreach ($disciplinari as $key_campionato => $girone): ?>

        <? foreach ($girone as $key_girone => $giornate): ?>



            <? foreach ($giornate as $giorno => $disciplinare): ?>

                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($girone) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <? // print "{$key_campionato} - {$key_girone} ";print_r($disciplinare)      ?>

                    <? if (isset($disciplinare['Diffidati'])): ?>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Squadra</th>
                                <th>Nominativo</th>
                                <th style="text-align: center">Ammonizioni</th>
                            </tr>
                            <? foreach ($disciplinare['Diffidati'] as $diffidato): ?>
                                <tr>
                                    <td><?= $diffidato['Squadra']; ?></td>
                                    <td><?= $diffidato['Anagrafica']; ?></td>
                                    <td><?= $diffidato['Ammonizioni']; ?></td>
                                </tr>
                            <? endforeach; ?>
                        </table>
                    <? else: ?>
                        <div class="void-message">Non ci sono diffidati <!-- <?= "{$key_campionato} {$key_girone}" ?> --></div>
                    <? endif; ?>
                </div>

            <? endforeach; ?>
        <? endforeach; ?>
    <? endforeach; ?>
</div>



<div id="espulsi" class="tabelle" style="display: none">

    <!--ESPULSI-->

    <? foreach ($disciplinari as $key_campionato => $girone): ?>
        <? foreach ($girone as $key_girone => $giornate): ?>

            <? foreach ($giornate as $giorno => $disciplinare): ?>
                                    <!--<div id="<?= "espulsi_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="espulsi" style="display: none">-->
                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($girone) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <? if (isset($disciplinare['Espulsi'])): ?>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Squadra</th>
                                <th>Nominativo</th>
                                <th>Motivo</th>
                                <th>Periodo</th>

                            </tr>
                            <? foreach ($disciplinare['Espulsi'] as $espulso): ?>
                                <tr>
                                    <td><?= $espulso['Squadra']; ?></td>
                                    <td><?= $espulso['Anagrafica']; ?></td>
                                    <td><?= $espulso['Motivo']; ?></td>
                                    <td><?= $espulso['Periodo']; ?></td>

                                </tr>
                            <? endforeach; ?>
                        </table>
                    <? else: ?>
                        <div class="void-message">Non ci sono espulsi <!-- <?= "{$key_campionato} {$key_girone}" ?> --></div>
                    <? endif; ?>
                </div>
            <? endforeach; ?>
        <? endforeach; ?>
    <? endforeach; ?>
</div>



<div id="squalificati" class="tabelle" style="display: none">

    <!--squalificati-->

    <? foreach ($disciplinari as $key_campionato => $girone): ?>

        <? foreach ($girone as $key_girone => $giornate): ?>



            <? foreach ($giornate as $giorno => $disciplinare): ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--<div id="<?= "squalificati_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="squalificati" style="display: none">-->
                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">

                    <? if (count($girone) > 1): ?>
                    <? endif; ?>
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                    <?= $page_item[$key_campionato][$key_girone]; ?>

                    <? if (isset($disciplinare['Squalificati'])): ?>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Squadra</th>
                                <th>Nominativo</th>
                                <th style="text-align: center">Ammonizioni</th>
                            </tr>
                            <? foreach ($disciplinare['Squalificati'] as $squalificato): ?>
                                <tr>
                                    <td><?= $squalificato['Squadra']; ?></td>
                                    <td><?= $squalificato['Anagrafica']; ?></td>
                                    <td><?= $squalificato['Ammonizioni']; ?></td>
                                </tr>
                            <? endforeach; ?>
                        </table>
                    <? else: ?>
                        <div class="void-message">Non ci sono squalificati <!-- <?= "{$key_campionato} {$key_girone}" ?> --></div>
                    <? endif; ?>
                </div>
            <? endforeach; ?>
        <? endforeach; ?>
    <? endforeach; ?>
</div>


<div id="sanzioni" class="tabelle" style="display: none">

    <!--sanzioni-->

    <? foreach ($disciplinari as $key_campionato => $girone): ?>

        <? foreach ($girone as $key_girone => $disciplinare): ?>
            <div class="sanzioni-block">
                <? if (count($girone) > 1): ?>
                <? endif; ?>
                <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>


                <? if (isset($sanzioni[$key_campionato][$key_girone])): ?>

                    <? foreach ($sanzioni[$key_campionato][$key_girone] as $giorno => $sanzione): ?>

                        <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">



                            <?= $page_item[$key_campionato][$key_girone]; ?>

                            <div id="<?= "sanzione_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="giornate_sanzioni"  style="display: none">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th>Squadra</th>
                                        <th>Disciplinare</th>
                                        <th style="text-align: center">Punti</th>
                                        <th style="text-align: center">Sanzione</th>
                                    </tr>
                                    <? foreach ($sanzione as $key_sanzione => $info_sanzione): ?>
                                        <tr>
                                            <td><?= $info_sanzione['Denominazione'] ?></td>
                                            <td><?= $info_sanzione['Descrizione'] ?></td>
                                            <td><?= $info_sanzione['Punti'] ?></td>
                                            <td><?= $info_sanzione['Sanzione'] ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                </table>

                            </div>
                        </div>
                    <? endforeach; ?>
                <? else: ?>
                    <div class="void-message">Non ci sono sanzioni</div>
                <? endif; ?>
            </div>	
        <? endforeach; ?>

    <? endforeach; ?>
</div>

<div id="bollettini" class="tabelle" style="display: none">

    <? foreach ($bollettini as $key_campionato => $girone): ?>

        <? foreach ($girone as $key_girone => $giornata): ?>


            <? foreach ($giornata as $giorno => $bollettino): ?>
                        <!--  <div id="<?= "bollettini_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="giornate_bollettini"  style="display: none">-->
                <div  class="<?= "table-{$key_campionato}-{$key_girone}" ?>  <?= "table-{$key_campionato}-{$key_girone}-{$giorno}" ?>">
                    <h3>Girone: <?= $nomi_gironi[$key_campionato][$key_girone] ?></h3>
                    <? // print "{$key_campionato} - {$key_girone}";        ?>
                    <?= $page_item[$key_campionato][$key_girone]; ?>
                    <? if ($bollettino != ""): ?>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th style="width:180px;">Data</th>
                                <th>Comunicazione</th>
                            </tr>  
                            <? $index = 1; ?>
                            <? foreach ($bollettino as $key => $single): ?>
                                <tr>
                                    <td style="width:180px;"><?= $single['DataInserimento'] ?></td>
                                    <td><?= $single["Note"] ?></td>
                                </tr>
                                <? $index++ ?>
                            <? endforeach; ?>
                        </table>

                    <? else: ?>
                        <div class="void-message">Non ci sono comunicazioni</div> 
                    <? endif; ?>
                </div>

            <? endforeach; ?>


        <? endforeach; ?>
    <? endforeach; ?>
</div>



<div id="squalificati_a_tempo" class="tabelle" style="display: none">

    <!--ESPULSI-->
    <? // print_r($squalificati_a_tempo); ?>
    <h3>Elenco delle squalifiche a tempo attive</h3>


    <? if (count($squalificati_a_tempo)): ?>
        <div class="table-block">

            <table class="table table-striped table-bordered">
                <tr>
                    <th>Nominativo</th>
                    <th>Squadra</th>
                    <th>Motivo</th>
                    <th>Periodo</th>
                </tr>
                <? foreach ($squalificati_a_tempo as $espulso): ?>
                    <tr>
                        <td><?= $espulso['Cognome'] . " " . $espulso['Nome']; ?></td>
                        <td><?= $espulso['NomeSquadra']; ?></td>
                        <td><?= $espulso['Motivo']; ?></td>
                        <td>Squalificato fino al <?= sprintf("%s", date_format(date_create($espulso['EspulsioneFine']), "d/m/Y")); ?></td>
                    </tr>
                <? endforeach; ?>
            </table>
        <? else: ?>
            <div class="void-message">Non ci sono squalificati</div>
        <? endif; ?>
    </div>

</div>



<? $html = ob_get_clean() ?>
<?
$resp['html'] = $html;
$resp['json'] = ($to_show_calendar);

echo json_encode($resp);
?>