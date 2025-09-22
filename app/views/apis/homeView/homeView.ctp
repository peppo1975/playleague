<? // print_r($server)                                                                                                                  ?>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        /*width: 100%;*/
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    /*    tr:nth-child(even) {
            background-color: #dddddd;
        }*/
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" >
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>

<?
//$marcatori
//$calendario
//$menu_tendina
$c_t = [];
$g_t = [];
?>

<? foreach ($menu_tendina['Campionato'] as $key_campionato => $campionato): ?>
    <!--creo elenco campionati-->
    <? ob_start(); ?>
    <option value="<?= $key_campionato ?>"><?= $campionato['Nome'] ?></option>
    <? $c_t[] = ob_get_clean() ?>
    <!--creo elenco gironi-->
    <? foreach ($campionato['GironeCampionato'] as $key_girone_campionato => $girone_campionato): ?>
        <? ob_start(); ?>
        <option value="<?= $key_girone_campionato ?>" class="gironi c_<?= $key_campionato ?>"  >
            <?= $girone_campionato['Nome'] ?>
        </option>
        <? $g_t[$key_campionato][] = ob_get_clean() ?>
    <? endforeach; ?>
<? endforeach; ?>


<!--menu tendina-->

<div class="row">

    <div class="col-lg-8 campionati-select">
        <label for="cars">Campionati:</label>
        <select class="form-select" aria-label="Default select example" name="campionato" id="campionati_menu" >
            <option value="0"></option>
            <?= implode("", $c_t) ?>
        </select>
    </div>

    <div class="col-lg-4 gironi-select">    
        <label for="cars">Gironi:</label>
        <div class="c_0 all_gironi">
            <select class="form-select gironi_menu" aria-label="Default select example" name="gironi">
                <option value="0"></option>
            </select>
        </div>
        <? foreach ($g_t as $key_campionato => $list_gironi): ?>
            <div class="c_<?= $key_campionato ?> all_gironi" style="display: none">
                <select class="form-select gironi_menu" aria-label="Default select example" name="gironi">
                    <option value="0"></option>
                    <?= implode("", $list_gironi) ?>
                </select>
            </div>
        <? endforeach; ?>
    </div>

</div>

<!--end menu tendina-->

<br>

<div class="btn-group menu-list" role="group" style="display: none">
    <input type="radio" class="btn-check menu-calendario" value="calendario" name="btnradio" id="btnCalendario" checked="">
    <label class="btn btn-outline-primary" for="btnCalendario">Calendario</label>


    <input type="radio" class="btn-check menu-calendario" value="classifiche" name="btnradio" id="btnClassifiche" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnClassifiche">Classifica</label>

    <input type="radio" class="btn-check menu-calendario" value="marcatori" name="btnradio" id="btnMarcatori" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnMarcatori">Marcatori</label>


    <input type="radio" class="btn-check menu-calendario" value="diffidati" name="btnradio" id="btnDiffidati" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnDiffidati">Diffidati</label>

    <input type="radio" class="btn-check menu-calendario" value="espulsi" name="btnradio" id="btnEspulsi" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnEspulsi">Espulsi</label>

    <input type="radio" class="btn-check menu-calendario" value="squalificati" name="btnradio" id="btnSqualificati" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSqualificati">Squalificati</label>

    <input type="radio" class="btn-check menu-calendario" value="sanzioni" name="btnradio" id="btnSanzioni" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSanzioni">Sanzioni</label>

    <input type="radio" class="btn-check menu-calendario" value="bollettini" name="btnradio" id="btnBollettini" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnBollettini">Comunicazioni</label>
</div>

<div id="calendario" class="tabelle">

    <? $to_show_calendar = [] ?>
    <? foreach ($calendario as $key_campionato => $campionato): ?>
        <? $italiana = $campionato['Italiana']; ?>
        <? foreach ($campionato['Gironi'] as $key_girone => $girone_campionato): ?>
            <div id="camp_<?= $key_campionato ?>_gir_<?= $key_girone ?>" class="tabella_calendario" style="display: none">

                <?
                $to_show_calendar[$key_campionato][$key_girone] = 1;

                $key_first = 0;
                $max_days = 0;
                $max_days_array = array_keys($girone_campionato['Giornata']);
                $max_days = $max_days_array[count($max_days_array) - 1];
                $squadre = $girone_campionato['Squadre'];
                ?>

                <? for ($giorno = $max_days; $giorno >= 1; $giorno--): ?>

                    <!-- <br>Giornata <?= $giorno ?>  -->

                    <? $squadre_riposo = $squadre; ?>

                    <div class="table-calendar-<?= "{$key_campionato}_{$key_girone} table-calendar" ?>" id="<?= "calendar_table_{$key_campionato}_{$key_girone}_{$giorno}" ?>" <?= $max_days == $giorno ? '' : 'style="display: none"' ?> >

                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" >Giornata: </a></li>
                                <? for ($i = 1; $i <= $max_days; $i++): ?>
                                    <li class="page-item <?= $giorno == $i ? "active" : "" ?>">
                                        <a class="page-link" 
                                           id="page-link_<?= $i ?>" 
                                           value_page="<?= $i ?>" 
                                           filter_id="<?= "calendar_table_{$key_campionato}_{$key_girone}_{$i}" ?>" 
                                           filter_class="<?= "table-calendar-{$key_campionato}_{$key_girone}" ?>"
                                           >
                                               <?= $i ?>
                                        </a>
                                    </li>
                                <? endfor; ?>
                            </ul>
                        </nav>

                        <div class="table-block">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Giorno</th>
                                    <th>Ora</th>
                                    <th>Impianto</th>
                                    <th>Partita</th>
                                    <th>Risultato</th>
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
                                            <!--<td><a <?= $partita['Campo']['isMidland'] ? sprintf('href="http://%s/impianti/%s" target="_blank"', $server, $partita['Campo']['id']) : "" ?>><?= $partita['Campo']['Descrizione'] ?></a></td>-->
                                            <td><?= $partita['Campo']['Descrizione'] ?></td>
                                            <td><?= $partita['Casa']['Denominazione'] ?> vs <?= $partita['Trasferta']['Denominazione'] ?></td>
                                            <td><?= (string) $partita['Casa']['Risultato'] == '' && (string) $partita['Trasferta']['Risultato'] == '' ? "" : "{$partita['Casa']['Risultato']} - {$partita['Trasferta']['Risultato']}" ?></td>
                                            <td><?= $partita['CausaleRisultato'] == null ? "" : $partita['CausaleRisultato']['Descrizione'] ?></td>
                                            <td><?= $partita['NomeGara'] ?></td>
                                            <td>
                                                <a <?= sprintf('href="http://%s/sections/getNotes/%s/%s/?playLeague"  target="_blank"', $server, $partita['Calendario'], $partita['Casa']['SquadraCampionato']) ?>"><img src="/img/icon-pdf.png" alt="Stampa nota gara" width="16" height="16"></a> vs
                                                <a <?= sprintf('href="http://%s/sections/getNotes/%s/%s/?playLeague"  target="_blank"', $server, $partita['Calendario'], $partita['Trasferta']['SquadraCampionato']) ?>>
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
            </div>

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
            <div id="class_camp_<?= $key_campionato ?>_gir_<?= $key_girone ?>" class="tabella_classifica" style="display: none">

                <?
                $key_first = 0;
                $max_days = 0;
                $max_days_array = array_keys($girone_campionato['Giornata']);
                $max_days = $max_days_array[count($max_days_array) - 1];
                $squadre = $girone_campionato['Squadre'];
                ?>

                <? for ($giorno = $max_days; $giorno >= 1; $giorno--): ?>

                    <!-- <br>Giornata <?= $giorno ?>  -->

                    <? $squadre_riposo = $squadre; ?>

                    <div class="table-classifica-<?= "{$key_campionato}_{$key_girone}" ?> table-classifica" id="<?= "classifica_table_{$key_campionato}_{$key_girone}_{$giorno}" ?>" <?= $max_days == $giorno ? '' : 'style="display: none"' ?> >

                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" >Giornata: </a></li>
                                <? for ($i = 1; $i <= $max_days; $i++): ?>
                                    <li class="page-item <?= $giorno == $i ? "active" : "" ?>">
                                        <a class="page-link" 
                                           id="page-link_<?= $i ?>" 
                                           value_page="<?= $i ?>" 
                                           filter_id="<?= "classifica_table_{$key_campionato}_{$key_girone}_{$i}" ?>" 
                                           filter_class="<?= "table-classifica-{$key_campionato}_{$key_girone}" ?>"
                                           >
                                               <?= $i ?>
                                        </a>
                                    </li>
                                <? endfor; ?>
                            </ul>
                        </nav>

                        <div class="table-block">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Squadra</th>
                                    <th>Punti</th>
                                    <th>Giocate</th>
                                    <th>Vinte</th>
                                    <th>Perse</th>
                                    <th>Nulle</th>
                                    <th>Goal Fatti</th>
                                    <th>Goal Subiti</th>
                                    <th>Coppa Disc.</th>
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
                                            <td><?= $classifica['Punti'] ?></td>
                                            <td><?= $classifica['Giocate'] ?></td>
                                            <td><?= $classifica['Vinte'] ?></td>
                                            <td><?= $classifica['Perse'] ?></td>
                                            <td><?= $classifica['Nulle'] ?></td>
                                            <td><?= $classifica['GoalFatti'] ?></td>
                                            <td><?= $classifica['GoalSubiti'] ?></td>
                                            <td><?= $classifica['CoppaDisciplina'] ?></td>
                                        </tr>
                                    <? endforeach; ?>

                                <? endif; ?>

                            </table>
                        </div>
                    </div>
                <? endfor; ?>
            </div>

        <? endforeach; ?>
    <? endforeach; ?>

</div>


<div id="marcatori" class="tabelle" style="display: none">

    <!--MARCATORI-->

    <? foreach ($marcatori as $key_campionato => $girone): ?>
        <? foreach ($girone as $key_girone => $atleta): ?>
            <div id="<?= "goalscorer_{$key_campionato}_{$key_girone}" ?>" class="goalscorer" style="display: none">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Squadra</th>
                        <th>Nominativo</th>
                        <th style="text-align: center">Goal</th>
                    </tr>
                    <? foreach ($atleta['Atleta'] as $atleta_info): ?>
                        <tr>
                            <td><?= $atleta_info['SquadraCampionatoDenominazione'] ?></td>
                            <td><?= $atleta_info['Anagrafica'] ?></td>
                            <td style="text-align: center"><?= $atleta_info['Goal'] ?></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
        <? endforeach; ?>
    <? endforeach; ?>

</div>



<div id="diffidati" class="tabelle" style="display: none">

    <!--DIFFIDATI-->

    <? foreach ($disciplinari as $key_campionato => $girone): ?>
        <? foreach ($girone as $key_girone => $giornate): ?>
            <? foreach ($giornate as $key_giornata => $disciplinare): ?>

                <div id="<?= "diffidati_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="diffidati" style="display: none">
                    <? // print "{$key_campionato} - {$key_girone} ";print_r($disciplinare) ?>
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
            <? foreach ($giornate as $key_giornata => $disciplinare): ?>
                <div id="<?= "espulsi_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="espulsi" style="display: none">
                    <? if (isset($disciplinare['Espulsi'])): ?>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Squadra</th>
                                <th>Nominativo</th>
                                <th style="text-align: center">Periodo</th>
                            </tr>
                            <? foreach ($disciplinare['Espulsi'] as $espulso): ?>
                                <tr>
                                    <td><?= $espulso['Squadra']; ?></td>
                                    <td><?= $espulso['Anagrafica']; ?></td>
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
            <? foreach ($giornate as $key_giornata => $disciplinare): ?>
                <div id="<?= "squalificati_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="squalificati" style="display: none">
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
            <div id="<?= "sanzioni_{$key_campionato}_{$key_girone}" ?>" class="sanzioni" style="display: none">

                <? if (isset($sanzioni[$key_campionato][$key_girone])): ?>

                    <? foreach ($sanzioni[$key_campionato][$key_girone] as $key_giornata => $sanzione): ?>

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

                    <? endforeach; ?>
                <? else: ?>
                    <div class="void-message">Non ci sono sanzioni <!-- <?= "{$key_campionato} {$key_girone}" ?> --></div>
                <? endif; ?>


            </div>
        <? endforeach; ?>
    <? endforeach; ?>
</div>



<div id="bollettini" class="tabelle"  style="display: none">
    <? foreach ($bollettini as $key_campionato => $girone): ?>
        <? foreach ($girone as $key_girone => $giornata): ?>

            <div id="<?= "bollettini_{$key_campionato}_{$key_girone}" ?>" class="bollettini" style="display: none">
                <? foreach ($giornata as $key_giornata => $bollettino): ?>
                    <div id="<?= "bollettini_{$key_campionato}_{$key_girone}_{$key_giornata}" ?>" class="giornate_bollettini"  style="display: none">
                        <? // print "{$key_campionato} - {$key_girone}"; ?>
                        <? if ($bollettino != ""): ?>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>Note</th>
                                </tr>  
                                <? $index = 1; ?>
                                <? foreach ($bollettino as $key => $single): ?>
                                    <tr>
                                        <td><?= $index ?></td>
                                        <td><?= $single ?></td>
                                    </tr>
                                    <? $index++ ?>
                                <? endforeach; ?>
                            </table>

                        <? else: ?>
                            <div class="void-message">Non ci sono comunicazioni</div> 
                        <? endif; ?>
                    </div>

                <? endforeach; ?>
            </div>

        <? endforeach; ?>
    <? endforeach; ?>
    <? if (count($bollettini) == 0): ?>
        <div class="void-message">Non ci sono comunicazioni</div> 
    <? endif; ?>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var to_show_calendar = <?= json_encode($to_show_calendar) ?>;
    console.log(to_show_calendar);
    $(function ()
    {
//        alert("TEST");
        var id_campionato = 0;
        var id_girone = 0;


        $("#campionati_menu").change(function ()
        {
            id_campionato = $(this).val();
            hide_tabelle('switch_campionato');

        });



        $(".gironi_menu").change(function ()
        {
            id_girone = $(this).val();

            if (parseInt(id_girone) > 0)
                hide_tabelle('switch_girone');
            else
            {
                $(".tabelle, .menu-list").hide();

            }

        });



        $(".menu-calendario").on("click", function ()
        {
            id = $("input:checked").val();
            $(".tabelle").hide();
            $("#" + id).show();
        });



        $(".page-link").click(function ()
        {
            var value_page = $(this).attr('value_page');
            var filter_id = $(this).attr('filter_id');
            var filter_class = $(this).attr('filter_class');
            console.log(value_page, filter_id, filter_class);
            $("." + filter_class).hide();
            $("#" + filter_id).show();
        });



        function seleziona_giornata(id_campionato, id_girone)
        {
            var giornata = to_show_calendar[id_campionato][id_girone];

            var filter_id_calendar = "calendar_table_" + id_campionato + "_" + id_girone + "_" + String(giornata);
            var filter_class_calendar = "table-calendar-" + id_campionato + "_" + id_girone;
            $("." + filter_class_calendar).hide();
            $("#" + filter_id_calendar).show();

            var filter_id_classifica = "classifica_table_" + id_campionato + "_" + id_girone + "_" + giornata;
            var filter_class_classifica = "table-classifica-" + id_campionato + "_" + id_girone;
            //$("." + filter_class_classifica).hide();
            $("#" + filter_id_classifica).show();


            $(".giornate_sanzioni").hide();
            $(".giornate_bolletini").hide();

            $("#sanzione_" + id_campionato + "_" + id_girone + "_" + giornata).show();
            $("#bollettini_" + id_campionato + "_" + id_girone + "_" + giornata).show();


            $(".espulsi").hide();
            $(".diffidati").hide();
            $(".squalificati").hide();

            $("#espulsi_" + id_campionato + "_" + id_girone + "_" + giornata).show();
            $("#diffidati_" + id_campionato + "_" + id_girone + "_" + giornata).show();
            $("#squalificati_" + id_campionato + "_" + id_girone + "_" + giornata).show();


        }



        function hide_tabelle(type)
        {
            switch (type)
            {

                case 'switch_campionato':

                    $(".tabelle").hide();// hide tutte le tabelle
                    $(".all_gironi").hide();// elenco a discesa gironi
                    $(".c_" + id_campionato).show(); // elenco a discesa gironi
                    $(".gironi_menu ").val(0); // elenco a discesa gironi

                    $(".menu-list").hide(); // dove c'è scritto calendario, marcatori, classifiche

                    break;

                case 'switch_girone':

                    $(".tabelle").hide();
                    $(".table-calendar").hide();
                    $(".menu-list").show(); // dove c'è scritto calendario, marcatori, classifiche
                    $("#btnCalendario").prop("checked", true); // seleziono calendario

                    $("#calendario").show();
                    $("#camp_" + id_campionato + "_gir_" + id_girone).show();

                    $(".goalscorer").hide();
                    $("#goalscorer_" + id_campionato + "_" + id_girone).show();

                    $(".diffidati").hide();
                    $("#diffidati_" + id_campionato + "_" + id_girone).show();

                    $(".espulsi").hide();
                    $("#espulsi_" + id_campionato + "_" + id_girone).show();

                    $(".squalificati").hide();
                    $("#squalificati_" + id_campionato + "_" + id_girone).show();

                    $(".sanzioni").hide();
                    $("#sanzioni_" + id_campionato + "_" + id_girone).show();

                    $(".bollettini").hide();
                    $("#bollettini_" + id_campionato + "_" + id_girone).show();


                    $("#tabella_classifica").hide();
                    $(".table-classifica").hide();
                    $("#class_camp_" + id_campionato + "_gir_" + id_girone).show();

                    seleziona_giornata(id_campionato, id_girone);

                    break;

                default:

                    break;
            }
        }

    });
</script>