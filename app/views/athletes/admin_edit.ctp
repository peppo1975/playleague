
<?= $this->element("/backend/edit_scripts"); ?>

<?
//GIUSEPPE 2018-12-15 -------------------
$now = date("Y-m-d");
$certificato_medico_expl = explode("/", $this->data['Athlete']['ScadenzaCertificatoMedico']);
$certificato_medico = sprintf("%s-%s-%s", $certificato_medico_expl[2], $certificato_medico_expl[1], $certificato_medico_expl[0]);
//---------------------------------------
?>



<script type="text/javascript">
    if (typeof $ != "undefined")
    {

//        $("#genera").click(function () {
//           
//            $.get("/admin/users/generatepwd", function (ret) {
//                $("#AthletePassword").val(ret.pwd);
//            }, 'json');
//        });


    }

</script>   

<?= $this->Form->create('Athlete', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Anagrafica atleta: <span><?= $this->data['Athlete']['Cognome']; ?> <?= $this->data['Athlete']['Nome']; ?> - <?= $this->data['Athlete']['NumeroDocumento']; ?></span></h2>
    <ul>

        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<div class="clear"></div>   
<div class="tab-container">

    <ul class="tab-selector">

        <li data-index="1" class="selected" ><a href="javascript:;">Anagrafica</a></li>
        <? if ($this->data['Athlete']['Arbitro'] == 'Si'): ?>

            <li data-index="2"><a href="javascript:;">Spese</a></li>
            <li data-index="3"><a href="javascript:;">Riepilogo voti</a></li>   

        <? endif; ?>

    </ul>

    <div data-index="1" class="tab-page tab-selected">

        <input type="hidden" name="modded" value="false" />



        <!-- //GIUSEPPE 2023-07-28 ******************** -->
        <h3>BAS</h3>
        <div>
            <strong>subscriber_id: </strong><a id="subscriber_id"></a> 
        </div>

        <div>
            <strong>anno sportivo: </strong><a id="anno_sportivo"></a> 
        </div>
        <hr>
        <div class="clear"></div>
        <!-- ****************************************** -->



        <?= $this->Form->input('Cognome'); ?>
        <?= $this->Form->input('Nome'); ?>

        <div class="clear"></div>

        <!-- stampa etichetta -->

        <ul class="tab-menu">
            <li>
                <a rel="timmytip" href="/admin/prints/athleteLabel/<?= $this->data['Athlete']['Atleta']; ?>" title="Stampa etichetta">
                    <img src="/img/timmyshare/icon_print.png">
                </a>
            </li>
        </ul>

        <!-- fine stampa etichetta -->

        <?= $this->Form->input('Indirizzo'); ?>
        <?= $this->Form->input('Cap'); ?>

        <? //= $this->Form->input('Localita'); ?>

        <!-- //GIUSEPPE 2023-07-28 ******************** -->
        <div class="autocomplete">
            <?= $this->Form->input('Localita'); ?>
        </div>
        <!-- ****************************************** -->

        <?= $this->Form->input('Provincia'); ?>

        <div class="clear"></div>

        <?= $this->Form->input('Telefono'); ?>
        <?= $this->Form->input('Cellulare'); ?>
        <?= $this->Form->input('Lavoro', array('label' => 'Telefono lavoro')); ?>
        <?= $this->Form->input('Email', array('class' => 'big')); ?>
        <?= $this->Form->input('Fax'); ?>   
        <?= $this->Form->input('CodiceFiscale'); ?>

        <div class="clear"></div>

        <?= $this->Form->input('password'); ?>

        <? //if (isset($gia_registrato) && $gia_registrato) :?> 

        <script type="text/javascript">

            $(function ()
            {

                //GIUSEPPE 2018-12-15 --------------------
                setInterval(read_scadenza, 1000);
                //----------------------------------------

                /* non serve più */
                /*$('.unregister').live('click', function () {
                 
                 $.get('/admin/athletes/unregister/' + $(this).attr('data-id'), function () {
                 location.reload();
                 })
                 
                 });*/



                /* //GIUSEPPE 2019-01-09 */


                $('.unregister').hide();

                $("#genera_new").click(function ()
                {

                    $("#resp_pass label").html("&ensp;");

                    var email = $("#AthleteEmail").val();
                    var cognome = $("#AthleteCognome").val();
                    var nome = $("#AthleteNome").val();
                    var password = $("#AthletePassword").val();

                    $.post("/sections/passrecovery/user", {data: {User: {username: email, nome: nome, cognome: cognome, password: password}}}, function (ret)
                    {
                        console.log(ret);
                        if (ret.found === 1)
                        {
                            $("#resp_pass label").html("Password generata con successo");
                        }

                    }, 'json');
                });
                /* ******************** */

            });
            //GIUSEPPE 2018-12-15 --------------------
            function read_scadenza()
            {
                var scadenza_input = $("#AthleteScadenzaCertificatoMedico").val();
                var arr_scadenza = scadenza_input.split('/');
                var scadenza = arr_scadenza[2] + "-" + arr_scadenza[1] + "-" + arr_scadenza[0];
                var now = '<?= $now ?>';
                //console.log(scadenza + " " + now);

                if (now >= scadenza)
                {
                    $("#AthleteScadenzaCertificatoMedico").addClass("to-renew");
                }
                else
                {
                    $("#AthleteScadenzaCertificatoMedico").removeClass("to-renew");
                }

            }
            //----------------------------------------

        </script>

        <div class="input" hidden="">
            <a href="javascript:;" class="unregister" data-id="<?= $this->data['Athlete']['Atleta']; ?>" title="Utente gia registrato">Utente gia registrato(Clicca per eliminarlo)</a>
        </div>

        <!-- //GIUSEPPE 2019-01-09 --> 
        <div class="input">
            <label>&nbsp;</label>
            <?= $this->Form->submit('Genera password', array('type' => 'button', 'div' => false, 'id' => 'genera_new')); ?>


            <div id="resp_pass">
                <label>&nbsp;</label>
            </div>

        </div>
        <!-- --------------------- --> 

        <? // else: ?>

        <!--            <div class="input">
                        <label>&nbsp;</label>
        <? //= $this->Form->submit('Genera password', array('type' => 'button', 'div' => false, 'id' => 'genera_new'));  ?>
        
                         //GIUSEPPE 2019-01-09  
                        <div id="resp_pass">
                            <label>&nbsp;</label>
                        </div>
                         ---------------------  
                    </div>  -->


        <? // endif; ?>

        <div class="clear"></div>

        <!-- //GIUSEPPE 2023-07-28 *********************************************************** -->

        <? //= $this->Form->input('LuogoNascita', array('label' => 'Luogo di nascita')); ?>
        <? //= $this->Form->input('DataNascita', array('label' => 'Data di nascita', 'type' => 'text', 'class' => 'datePicker')); ?>   

        <div class="autocomplete">
            <?= $this->Form->input('LuogoNascita', array('label' => 'Luogo di nascita')); ?>
        </div>



        <div class="input text required">
            <label for="AthleteDataNascita">Data di nascita</label>
            <input name="data[Athlete][DataNascita]" type="date"  value="<?= $this->data['Athlete']['DataNascita'] ?>" id="AthleteDataNascita" required="">
        </div>



        <div class="input text required">
            <label for="AthleteCityNascita">id Luogo di nascita (automatico)</label>
            <input name="data[Athlete][CityNascita]" type="number" maxlength="11" value="<?= $this->data['Athlete']['CityNascita'] ?>" id="AthleteCityNascita" required data-readonly>
        </div>


        <? //= $this->Form->input('CityNascita', array('label' => 'id città nascita (automatica)', 'required' => 'true', 'readonly' => 'true')); ?>

        <!-- ********************************************************************************* -->

        <?=
        $this->Form->input('Sesso', array(
            'type' => 'radio',
            'options' => array('Maschio' => 'M', 'Femmina' => 'F'),
        ));
        ?>

        <div class="clear"></div>

        <?=
        $this->Form->input('TipoDocumento', array(
            'label' => 'Tipo documento',
            'options' => array(
                'Carta Identità' => 'Carta Identità',
                'Patente' => 'Patente',
                'Passaporto' => 'Passaporto'
            )
        ));
        ?>

        <?= $this->Form->input('NumeroDocumento', array('label' => 'Num. documento')); ?>

        <?= $this->Form->input('ScadenzaDocumento', array('label' => 'Scadenza documento', 'type' => 'text', 'class' => 'datePicker')); ?>


        <div class="clear"></div>

        <!-- //GIUSEPPE 2018-12-15 ------------------- -->
        <? if ($now >= $certificato_medico): ?>
            <?= $this->Form->input('ScadenzaCertificatoMedico', array('label' => 'Scadenza cert. medico', 'type' => 'text', 'class' => 'datePicker to-renew')); ?>
        <? else: ?>
            <?= $this->Form->input('ScadenzaCertificatoMedico', array('label' => 'Scadenza cert. medico', 'type' => 'text', 'class' => 'datePicker')); ?>
        <? endif; ?>
        <!-- //--------------------------------------- -->

        <? //= $this->Form->input('ScadenzaCertificatoMedico', array('label' => 'Scadenza cert. medico', 'type' => 'text', 'class' => 'datePicker'));  ?>

        <?=
        $this->Form->input('Responsabile', array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>      

        <?=
        $this->Form->input('Arbitro', array(
            'type' => 'radio',
            'default' => 'No',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>  

        <?=
        $this->Form->input('ArbitroAttivo', array(
            'legend' => 'Arbitro attivo',
            'type' => 'radio',
            'options' => array(1 => 'Si', 0 => 'No'),
        ));
        ?>                  

        <?=
        $this->Form->input('Sportivo', array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>  


        <?=
        $this->Form->input('Delegato', array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>      

        <?=
        $this->Form->input('Allenatore', array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>

        <!--//GIUSEPPE 2020-11-20 ------------------------------ -->

        <?=
        $this->Form->input('ScuolaCalcio', array(
            'type' => 'radio',
            'options' => array('1' => 'Si', '0' => 'No'),
        ));
        ?>  
        <!-- ---------------------------------------------------- -->

        <? if ($group_id == 5): ?>

            <div class="clear"></div>

            <div style="width: 600px;">

                <?= $this->element('/backend/ckeditor', array('name' => 'Note', 'title' => 'Note')); ?>


            </div>
            <div class="clear"></div>

        <? endif; ?>                

        <? if ($layout != "tablet"): ?>

            <div id="formUploadContainer">

                <?= $backend->getFiles('athlete_id', $this->params['pass'][0]); ?>

            </div>      

        <? endif; ?>

    </div><!--End anagrafica -->

    <? if ($this->data['Athlete']['Arbitro'] == 'Si'): ?>

        <div data-index="2" class="tab-page">

            <div class="speseForm">

                <script type="text/javascript">
                    if (typeof $ != "undefined")
                    {
                        $(function ()
                        {


                            $('.speseForm').delegate("#spesaAdd", "click", function ()
                            {

                                $('.error-message').remove();

                                var data = $("input.spesaClass, textarea.spesaClass").serialize();

                                $.post('/admin/athletes/spesaAdd', data, function (ret)
                                {

                                    if (ret.error == 0)
                                    {

                                        if ($("#AthleteExpenseAtletaSpesa").val() == undefined)
                                        { //ADD

                                            var tr = $('<tr>').attr('data-id', ret.data.AthleteExpense['AtletaSpesa']);
                                            var add_edit = 'aggiunta';

                                        }
                                        else
                                        {//EDIT

                                            var tr = $('.speseForm').find('tr[data-id=' + $("#AthleteExpenseAtletaSpesa").val() + ']').empty();
                                            var add_edit = 'modificata';

                                        }

                                        var field = ["Data_it", "Importo", "Descrizione"];

                                        for (i = 0; i < field.length; i++)
                                        {

                                            var td = $('<td>').text(ret.data.AthleteExpense[field[i]]);

                                            tr.append(td);

                                        }

                                        /*td opzioni*/

                                        var td_option = $('<td>').html(
                                                '<a href="javascript:;" class="orariEdit">' +
                                                '<img src="/img/timmyshare/icon_edit.png" />' +
                                                '</a>' +
                                                '&nbsp;' +
                                                '<a href="javascript:;" class="orariDelete">' +
                                                '<img src="/img/timmyshare/icon_delete.png" />' +
                                                '</a>'
                                                );
                                        tr.append(td_option);

                                        alert('Spesa ' + add_edit + ' con successo.');

                                        //$("#speseTable").append(tr);

                                        tr.insertAfter($("#speseTable").find('tr:first'));

                                        reset();

                                    }
                                    else
                                    {

                                        for (field in ret.data)
                                        {

                                            var error = $('<div>').addClass('error-message').text(ret.data[field]);
                                            $("#AthleteExpense" + field).parent('div').append(error);

                                        }

                                    }

                                }, 'json');

                            });

                            //Delete

                            $('.speseForm').delegate(".spesaDelete", "click", function ()
                            {

                                var tr = $(this).parents('tr');
                                var delete_id = tr.attr('data-id');

                                if (confirm("Sei sicuro di voler eliminare?"))
                                {

                                    $.get('/admin/athletes/spesaDelete/' + delete_id, function (ret)
                                    {

                                        if (ret.delete == 1)
                                        {

                                            alert('Spesa eliminata con successo.');
                                            tr.remove();

                                        }
                                        else
                                        {

                                            alert('Impossibile eliminare spesa');

                                        }

                                    }, 'json');

                                }

                            });

                            //Edit Form

                            $('.speseForm').delegate(".spesaEdit", "click", function ()
                            {

                                $('.inputShort').remove();

                                var tr = $(this).parents('tr');
                                var edit_id = tr.attr('data-id');

                                tr.find('td:not(:last)').each(function (index)
                                {

                                    var td = $(this);

                                    var col = td.prevAll().length;
                                    var headerObj = td.parents('table').find('th').eq(col);

                                    $("#AthleteExpense" + headerObj.text()).val(td.text());

                                });

                                //Input id spesa
                                var input_edit = $('<input value="' + edit_id + '">').addClass('hidden')
                                        .addClass('spesaClass')
                                        .addClass('editStatus')
                                        .addClass('inputShort')
                                        .attr('id', 'AthleteExpenseAtletaSpesa')
                                        .attr('name', 'data[AthleteExpense][AtletaSpesa]');

                                $('.speseForm').prepend(input_edit);

                                //Input reset
                                var submit = $('.speseForm').find('div.submit');
                                var reset = submit.clone();
                                reset.addClass('input').addClass('inputShort').removeClass('submit');
                                reset.find('input').attr('id', 'spesaReset')
                                        .addClass('editStatus')
                                        .val('annulla');

                                reset.insertAfter(submit);

                                //Button edit

                                $("#spesaAdd").val('modifica');

                            });

                            //Reset

                            function reset()
                            {

                                $("#AthleteExpenseData").val('');
                                $("#AthleteExpenseImporto").val('');
                                $("#AthleteExpenseDescrizione").val('');
                                $('.editStatus').remove();

                                $("#spesaAdd").val('aggiungi');

                            }

                            $('.speseForm').delegate("#spesaReset", "click", function ()
                            {

                                reset();

                            });

                        });
                    }
                </script>

                <!--
                
                        Gestione spese
                
                -->

                <?
                //debug($spese);
                ?>

                <?= $this->Form->input('AthleteExpense.Atleta', array('type' => 'text', 'class' => 'hidden spesaClass', 'div' => 'false', 'label' => false, 'value' => $this->data['Athlete']['Atleta'])); ?>

                <?= $this->Form->input('AthleteExpense.Data', array('type' => 'text', 'class' => 'datePicker spesaClass', 'div' => true)); ?>

                <?= $this->Form->input('AthleteExpense.Importo', array('type' => 'text', 'label' => 'Importo (Inserire negativo se è un anticipo)', 'class' => 'spesaClass', 'div' => true)); ?>

                <div class="clear"></div>

                <?= $this->Form->input('AthleteExpense.Descrizione', array('type' => 'textarea', 'class' => 'spesaClass', 'div' => true)); ?>

                <div class="clear"></div>

                <?= $this->Form->submit('aggiungi', array('type' => 'button', 'id' => 'spesaAdd', 'div' => true)); ?>

                <div class="clear"></div>

                <table id="speseTable" class="form_table form_table_full">

                    <tr>
                        <th>Data</th>
                        <th>Importo</th>
                        <th>Descrizione</th>
                        <th>Opzioni</th>
                    </tr>

                    <? foreach ($spese as $spesa): ?>

                        <tr data-id="<?= $spesa['AthleteExpense']['AtletaSpesa']; ?>">
                            <td><?= $spesa['AthleteExpense']['Data_it']; ?></td>
                            <td><?= $spesa['AthleteExpense']['Importo']; ?></td>
                            <td><?= $spesa['AthleteExpense']['Descrizione']; ?></td>
                            <td>
                                <a href="javascript:;" class="spesaEdit">
                                    <img src="/img/timmyshare/icon_edit.png" />
                                </a>                            
                                <a href="javascript:;" class="spesaDelete">
                                    <img src="/img/timmyshare/icon_delete.png" />
                                </a>                                
                            </td>                       
                        </tr>

                    <? endforeach; ?>

                </table>

            </div><!--End speseForm-->      

        </div><!--End spese --> 

        <div class="tab-page" data-index="3">

            <?
            $mesi = array(
                '01' => 'Gennaio',
                '02' => 'Febbraio',
                '03' => 'Marzo',
                '04' => 'Aprile',
                '05' => 'Maggio',
                '06' => 'Giugno',
                '07' => 'Luglio',
                '08' => 'Agosto',
                '09' => 'Settembre',
                '10' => 'Ottobre',
                '11' => 'Novembre',
                '12' => 'Dicembre',
            );

            $options = array(
                0 => 'Nessun voto',
                1 => 'Gravemente insufficiente',
                2 => 'Insufficiente',
                3 => 'Non sufficiente',
                4 => 'Quasi sufficiente',
                5 => 'Sufficiente',
                6 => 'Discreto',
                7 => 'Buono',
                8 => 'Molto buono',
                9 => 'Ottimo'
            );

            $end_days = array(
                '01' => '31',
                '02' => '29',
                '03' => '31',
                '04' => '30',
                '05' => '31',
                '06' => '30',
                '07' => '31',
                '08' => '31',
                '09' => '30',
                '10' => '31',
                '11' => '30',
                '12' => '31',
            );
            ?>  

            <script type="text/javascript">

                if (typeof $ != "undefined")
                {

                    $(function ()
                    {

                        $(".ldaPrint").click(function ()
                        {

                            var start = $(this).attr('data-start');
                            var end = $(this).attr('data-end');
                            var year = $(this).attr('data-year');
                            var mounth = $(this).attr('data-mounth');
                            var athlete = $(this).attr('data-id');

                            var start_date = start + '/' + mounth + '/' + year;
                            var end_date = end + '/' + mounth + '/' + year;

                            data = {"start": start_date, "end": end_date, "athlete": athlete}

                            $.post('/prints/single_lda/', {"datas": data}, function (ret)
                            {

                                //                                location.href = '/' + ret.link;
                                window.open('/' + ret.link, "_blank"); //GIUSEPPE 2022-12-23

                            }, 'json');

                        });

                    });

                }

            </script>

            <table class="table-matches form_table">

                <?
                $tot_media = 0;
                $tot_gare = 0;
                $tot_vote = 0;
                $tot_bonus = 0;
                $tot_compenso = 0;
                $cont = 0;
                $count_mounths = 0;

                foreach ($mounths as $mese => $mounth):
                    $tot_media += $mounth['MediaRanking'];
                    $tot_gare += $mounth['NumeroGare'];
                    $tot_vote += $mounth['VoteSend'];
                    $tot_bonus += $mounth['Bonus'];
                    $tot_compenso += $mounth['Compenso'];
                    $cont++;
                    if ($mounth['MediaRanking'] > 0)
                        $count_mounths++;
                endforeach;
                ?>          

                <tr class="table-header">
                    <th>TOTALE RANKING PERSONALE</th>
                    <th>TOT GARE</th>
                    <th>TOT VOTI</th>
                    <th>TOT BONUS</th>
                    <th>TOT COMPENSI</th>
                </tr>   

                <tr>
                    <td><?= $options[@ceil($tot_media / $count_mounths)]; ?></th>
                    <td><?= $tot_gare; ?></th>
                    <td><?= $tot_vote; ?></th>
                    <td><?= $tot_bonus; ?></th>
                    <td><?= "€ " . $tot_compensi; ?></th>
                </tr>

            </table>    

            <? if (count($mounths)): ?>

                <table class="table-matches form_table">

                    <tr class="table-header">
                        <th>MESE</th>
                        <th>RANKING</th>
                        <th>NUM GARE</th>
                        <th>VOTAZIONI</th>
                        <th>BONUS</th>
                        <th>COMPENSI</th>
                        <th>BUSTA PAGA</th>
                    </tr>

                    <? foreach ($mounths as $mese => $mounth): ?>

                        <tr class="<?= (($cont + 1) % 2 == 0) ? 'alternate' : ''; ?>">
                            <td><?= $mesi[$mese]; ?></td>
                            <td><?= $options[$mounth['MediaRanking']]; ?></td>
                            <td><?= $mounth['NumeroGare']; ?></td>
                            <td><? if ($mounth['Votazioni']['class'] != 'not-rated'): ?> 
                                    <span class="<?= $mounth['Votazioni']['class']; ?>"><?= $mounth['Votazioni']['label']; ?></span> 
                                <? else: ?>
                                    <?= $mounth['Votazioni']['label']; ?> <? endif; ?>
                            </td>
                            <td><?= $mounth['Bonus']; ?></td>
                            <td><?= $mounth['Compenso']; ?></td>
                            <td><a href="javascript:;" data-id="<?= $athlete_id; ?>" data-mounth="<?= $mese; ?>" data-start="01" data-end="<?= $end_days[$mese]; ?>" data-year="<?= $mounth['Anno']; ?>" class="ldaPrint" title="Stampa busta paga di <?= $mesi[$mese]; ?>" rel="timmytip"><img alt="stampa" src="/img/icon-pdf.png"/></a></td> 
                        </tr>       

                        <?
                        $tot_media += $mounth['MediaRanking'];
                        $tot_gare += $mounth['NumeroGare'];
                        $tot_vote += $mounth['VoteSend'];
                        $tot_bonus += $mounth['Bonus'];
                        $tot_compenso += $mounth['Compenso'];
                        $cont++;
                        ?>  

                    <? endforeach; ?>

                </table>

            <? endif; ?>            

        </div>

    <? endif; ?>    

</div>

<?= $this->Form->end(); ?>

<!--GIUSEPPE 2023-07-28 *******************-->
<style>
    /*    * {
            box-sizing: border-box;
        }*/

    /*    body {
            font: 16px Arial;
        }*/

    /*the container must be positioned relative:*/
    .autocomplete {
        position: relative;
        display: inline-block;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;

        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    /*when hovering an item:*/
    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
        background-color: DodgerBlue !important;
        color: #ffffff;
    }
</style>


<!--input read only required ID CITTA -->
<style>
    input[data-readonly] {
        pointer-events: none;
    }

</style>

<script>

    var Atleta = '<?= $this->data['Athlete']['Atleta'] ?>';

    console.log(Atleta);

    atletaBAS(Atleta);

    var AthleteLuogoNascita = document.getElementById("AthleteLuogoNascita");
    AthleteLuogoNascita.addEventListener('keyup', richiamaCity);


    var AthleteLocalita = document.getElementById("AthleteLocalita");
    AthleteLocalita.addEventListener('keyup', richiamaCity);

    async function atletaBAS(Atleta)
    {
        const info = await httpPost("/apis/atletaBAS", {Atleta});
        await scriviInfoBas(info);
    }

    function scriviInfoBas(info)
    {
        var subscriber_id = document.getElementById("subscriber_id");
        var anno_sportivo = document.getElementById("anno_sportivo");

        if (parseInt(info['res']['numValues']) == 0)
        {
            subscriber_id.innerHTML = "-";
            anno_sportivo.innerHTML = "-";
        }
        else
        {
            subscriber_id.innerHTML = info['res']['subscriber_id'];
            anno_sportivo.innerHTML = info['res']['AnnoSportivo'];
        }
    }

    async function richiamaCity(e)
    {
//                                    document.getElementById("AthleteCityNascita").value = "";
        const countries = await httpPost("/apis/cities", {"city_name": e.srcElement.value});
        await analizza(countries, e);
    }


// -------------------------------------------------------------------------------
    function analizza(arr, e)
    {
        var a, b, i, val = e.srcElement.value;
        var id_input = e.srcElement.id;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val)
        {
            return false;
        }

        switch (id_input)
        {
            case "AthleteLuogoNascita":
                document.getElementById("AthleteCityNascita").value = "";
                break;
        }

        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", e.srcElement.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        e.srcElement.parentNode.appendChild(a);
        /*for each item in the array...*/
//        for (i = 0; i < Object.keys(arr).length; i++)
        Object.keys(arr).map((i) => {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].city_name.substr(0, val.length).toUpperCase() == val.toUpperCase())
            {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].city_name.substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].city_name.substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' id='" + i + "' value='" + arr[i].city_name + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) // quando clicco sull'elenco città
                {
                    /*insert the value for the autocomplete text field:*/
                    var nome = this.getElementsByTagName("input")[0].value;
                    var nom = this.getElementsByTagName("input");
                    var index = this.getElementsByTagName("input")[0].id;
                    console.log(id_input + " of select");

                    switch (id_input)
                    {
                        case "AthleteLuogoNascita":
                            document.getElementById("AthleteCityNascita").value = index;
                            break;
                    }

                    document.getElementById(id_input).value = this.getElementsByTagName("input")[0].value;


                    console.log(arr[index]);

                    /*close the list of autocompleted values,
                     (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        })

    }


    function closeAllLists(elmnt)
    {
        /*close all autocomplete lists in the document,
         except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++)
        {
            if (elmnt != x[i] && elmnt != document.getElementById("AthleteLuogoNascita"))
            {
                x[i].parentNode.removeChild(x[i]);
            }

//                                        if (elmnt != x[i] && elmnt != document.getElementById("SquadreGeneralCounselBirthplace"))
//                                        {
//                                            x[i].parentNode.removeChild(x[i]);
//                                        }
        }
    }


    function addActive(x)
    {
        /*a function to classify an item as "active":*/
        if (!x)
            return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length)
            currentFocus = 0;
        if (currentFocus < 0)
            currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x)
    {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++)
        {
            x[i].classList.remove("autocomplete-active");
        }
    }


    document.addEventListener("click", function (e)
    {
        closeAllLists(e.target);
    });

    // -------------------------------------------------------------------------
    function httpPost(link, to_send)
    {
        return new Promise((resolve, reject) => {

            //            var link = "/apis/cities";
            //            var to_send = {id};
            const xhr = new XMLHttpRequest();
            xhr.open("POST", link);
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            const body = JSON.stringify(to_send);
            xhr.send(body);
            xhr.onload = () => {

                if (xhr.readyState == 4 && xhr.status == 200)
                {
                    var arr = JSON.parse(xhr.response);

                    resolve(arr);
                }
                else
                {
                    reject(new Error(xhr.statusText));
                }
            };
        });
    }
</script>
