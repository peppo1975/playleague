<?
$options_type = array();
$options_type['0'] = 'C5';
$options_type['1'] = 'C7';
$options_type['2'] = 'C11';
$options_type['3'] = '3VS3';
$options_type['4'] = 'SINGOLO';
$options_type['5'] = 'A SQUADRE';

//GIUSEPPE 2020-09-01 ---------------------------

$options_type['6'] = 'PS';
$options_type['7'] = 'XBox';
$options_type['8'] = '2VS2';

//-----------------------------------------------


$options_sesso = array();
$options_sesso['0'] = 'Maschile';
$options_sesso['1'] = 'Femminile';
$options_sesso['2'] = 'Misto';

//GIUSEPPE 2020-09-01 ---------------------------
$options_sesso['3'] = 'FIFA';
$options_sesso['4'] = 'PES';
$options_sesso['5'] = 'Altro';
//-----------------------------------------------
?>
<script type="text/javascript">

    if (typeof $ != "undefined")
    {
        $(function ()
        {



            var edit_girone = 'aggiunto';
            var edit_campo = 'aggiunto';
            var exit = 0;


            $('.formAdd').delegate('.GironeAdd', 'click', function ()
            {

                if (exit != 0)
                    return;

                var error = 0;

                $('.error_description').html('');
                $('.error_team').html('');
                $('.error_date').html('');

                var Descrizione = $("#CampionatiDescrizione").val();
                var NumeroSquadre = $("#CampionatiNumeroSquadre").val();
                var DataInizio = $("#CampionatiDataInizio").val();

                var data_control = DataInizio.split('/');
                var control_date = new Date(data_control[2] + '-' + data_control[1] + '-' + data_control[0]);

                if (Descrizione == '')
                {
                    $('.error_description').html('Campo obbligatorio.');
                    error = 1;
                }
                if (isNaN(NumeroSquadre) || NumeroSquadre == '')
                {
                    $('.error_team').html('Campo numerico.');
                    error = 1;
                }
                if (DataInizio == '')
                {
                    $('.error_date').html('Campo obbligatorio.');
                    error = 1;
                }
                if (control_date.getDay() != 1)
                {
                    $('.error_date').html('Il girone deve iniziare di Lunedi.');
                    error = 1;
                }

                if (error == 1)
                    return false;

                var girone = {Half: {"Descrizione": Descrizione, "NumeroSquadre": NumeroSquadre, "DataInizio": DataInizio}}

                $.post('/admin/campionatis/addgirone/' + edit_girone, girone, function (ret)
                {

                    var indice = $("#table_form").find('tr').length - 1;

                    if (ret.update == 'aggiunto')
                    {

                        $("#table_form").css('display', 'block');
                        $(
                                '<tr class="girone-delete" data-array-index="' + indice + '">' +
                                '<td data-descrizione="' + indice + '">' + Descrizione + '</td>' +
                                '<td data-squadre="' + indice + '">' + NumeroSquadre + '</td>' +
                                '<td data-date="' + indice + '">' + DataInizio + '</td>' +
                                '<td>' +
                                '<a href="javascript:;"  data-array-index="' + indice + '" class="GironeDelete"><img src="/img/timmyshare/icon_delete.png" /></a>' +
                                '<a href="javascript:;"  data-array-index="' + indice + '" class="GironeEdit"><img src="/img/timmyshare/icon_edit.png" /></a>' +
                                '</td>' +
                                '</tr>'

                                ).insertAfter('.append_gironi');

                        $("#CampionatiDescrizione").val('');
                        $("#CampionatiNumeroSquadre").val('');
                        $("#CampionatiDataInizio").val('');

                    }
                    else
                    {

                        $('.girone-delete').find('td[data-descrizione = ' + ret.update + ']').html(Descrizione);
                        $('.girone-delete').find('td[data-squadre = ' + ret.update + ']').html(NumeroSquadre);
                        $('.girone-delete').find('td[data-date = ' + ret.update + ']').html(DataInizio);

                        $('.reset_edit').css('display', 'none');
                        $("#ButtonGironeAdd").val('Aggiungi Girone');

                        $("#CampionatiDescrizione").val('');
                        $("#CampionatiNumeroSquadre").val('');
                        $("#CampionatiDataInizio").val('');

                        edit_girone = 'aggiunto';

                    }

                    exit = 0;

                }, 'json');

            });

            $('.formAdd').delegate('.GironeDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-array-index');

                $.get('/admin/campionatis/deletegirone/' + delete_id, function (ret)
                {

                    $(".girone-delete[data-array-index='" + delete_id + "']").remove();

                    if ($("#table_form").find('tr').length - 1 == 0)
                        $("#table_form").css('display', 'none');

                }, 'json');

            });

            $('.formAdd').delegate('.GironeEdit', 'click', function ()
            {

                var edit_id = $(this).attr('data-array-index');

                edit_girone = edit_id;

                var Descrizione = $(this).closest('tr').find('td[data-descrizione = ' + edit_id + ']').html();
                var NumeroSquadre = $(this).closest('tr').find('td[data-squadre = ' + edit_id + ']').html();
                var DataInizio = $(this).closest('tr').find('td[data-date = ' + edit_id + ']').html();

                $("#CampionatiDescrizione").val(Descrizione);
                $("#CampionatiNumeroSquadre").val(NumeroSquadre);
                $("#CampionatiDataInizio").val(DataInizio);

                $("#ButtonGironeAdd").val('Modifica');
                $('.reset_edit').css('display', 'inline');

            });

            $('.formAdd').delegate('.reset_edit', 'click', function ()
            {

                $("#CampionatiDescrizione").val('');
                $("#CampionatiNumeroSquadre").val('');
                $("#CampionatiDataInizio").val('');

                $('.reset_edit').css('display', 'none');
                $("#ButtonGironeAdd").val('Aggiungi Girone');

                edit_girone = 'aggiunto';

            });

            $('.formAdd').delegate('.CampoAdd', 'click', function ()
            {


                if (error != 0 && error != undefined)
                    return;

                var error = 0;

                $('.error_campo').html('');
                $('.error_giorno').html('');
                $('.error_ora').html('');

                var CampoSearch = $("#CampionatiCampoSearch").val();
                var Campo = $("#CampionatiCampo").val();
                var Giorno = $("#CampionatiGiorno").val();
                var Ora = $("#CampionatiOra").val();

                if (CampoSearch == '')
                {
                    $('.error_campo').html('Campo obbligatorio.');
                    error = 1;
                }
                if (Giorno == '')
                {
                    $('.error_giorno').html('Campo obbligatorio.');
                    error = 1;
                }
                if (Ora == '')
                {
                    $('.error_ora').html('Campo obbligatorio.');
                    error = 1;
                }

                if (error == 1)
                    return false;

                var Campo_array = {Campicampionati: {"Campo": Campo, "CampoSearch": CampoSearch, "Giorno": Giorno, "Ora": Ora}}

                error = 1;

                $.post('/admin/campionatis/addcampo/' + edit_campo, Campo_array, function (ret)
                {

                    var indice = $("#table_form_campi").find('tr').length - 1;

                    if (ret.update == 'aggiunto')
                    {

                        $("#table_form_campi").show();
                        $(
                                '<tr class="campo-delete-row" data-array-index="' + indice + '">' +
                                '<td data-campo="' + indice + '">' + CampoSearch + '</td>' +
                                '<td style="display: none;" data-campo-id="' + indice + '">' + Campo + '</td>' +
                                '<td data-giorno="' + indice + '">' + Giorno + '</td>' +
                                '<td data-ora="' + indice + '">' + Ora + '</td>' +
                                '<td>' +
                                '<a href="javascript:;"  data-array-index="' + indice + '" class="CampoDelete"><img src="/img/timmyshare/icon_delete.png" /></a>' +
                                '<a href="javascript:;"  data-array-index="' + indice + '" class="CampoEdit"><img src="/img/timmyshare/icon_edit.png" /></a>' +
                                '</td>' +
                                '</tr>'

                                ).insertAfter('.append_campi');

                        $("#CampionatiCampoSearch").val('');
                        $("#CampionatiCampo").val('');
                        $("#CampionatiGiorno").val('');
                        $("#CampionatiOra").val('');

                    }
                    else
                    {

                        $('.campo-delete-row').find('td[data-campo = ' + ret.update + ']').html(CampoSearch);
                        $('.campo-delete-row').find('td[data-campo-id = ' + ret.update + ']').html(Campo);
                        $('.campo-delete-row').find('td[data-giorno = ' + ret.update + ']').html(Giorno);
                        $('.campo-delete-row').find('td[data-ora = ' + ret.update + ']').html(Ora);

                        $('.reset_edit_campo').css('display', 'none');
                        $("#ButtonCampoAdd").val('Aggiungi Girone');

                        $("#CampionatiCampo").val('');
                        $("#CampionatiCampoSearch").val('');
                        $("#CampionatiGiorno").val('');
                        $("#CampionatiOra").val('');

                    }

                    error = 0;

                }, 'json');

            });

            $('.formAdd').delegate('.CampoDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-array-index');

                $.get('/admin/campionatis/deletecampo/' + delete_id, function (ret)
                {

                    $(".campo-delete-row[data-array-index='" + delete_id + "']").remove();

                    if ($("#table_form_campi").find('tr').length - 1 == 0)
                        $("#table_form_campi").css('display', 'none');

                }, 'json');

            });

            $('.formAdd').delegate('.CampoEdit', 'click', function ()
            {

                var edit_id = $(this).attr('data-array-index');

                edit_campo = edit_id;

                var Campo = $(this).closest('tr').find('td[data-campo-id = ' + edit_id + ']').html();
                var CampoSearch = $(this).closest('tr').find('td[data-campo = ' + edit_id + ']').html();
                var Giorno = $(this).closest('tr').find('td[data-giorno = ' + edit_id + ']').html();
                var Ora = $(this).closest('tr').find('td[data-ora = ' + edit_id + ']').html();

                $("#CampionatiCampo").val(Campo);
                $("#CampionatiCampoSearch").val(CampoSearch);
                $("#CampionatiGiorno").val(Giorno);
                $("#CampionatiOra").val(Ora);

                $("#ButtonCampoAdd").val('Modifica');
                $('.reset_edit_campo').css('display', 'inline');

            });
            $('.formAdd').delegate('.reset_edit_campo', 'click', function ()
            {

                $("#CampionatiCampo").val('');
                $("#CampionatiCampoSearch").val('');
                $("#CampionatiGiorno").val(1);
                $("#CampionatiOra").val('');

                $('.reset_edit_campo').css('display', 'none');
                $("#ButtonCampoAdd").val('Aggiungi Campo');

                edit_campo = 'aggiunto';

            });

        });

    }

    //GIUSEPPE -------------------------------------------------------

    var type_events;

    //GIUSEPPE 2020-01-18 ----------------------
//    var typeSport = {CALCIO: 0, TENNIS: 1, BASKET: 2, eSPORT: 3, PADEL: 4};
    var typeSport = <?= json_encode($type_sport) ?>;
    //------------------------------------------

    //GIUSEPPE 2020-09-01 ----------------------
    var options_type = <?= json_encode($options_type) ?>;
    var options_type_obj = {};

    var options_sesso = <?= json_encode($options_sesso) ?>;
    var options_sesso_obj = {};
    //------------------------------------------

    $(document).ready(function ()
    {

        //GIUSEPPE 2020-09-01 ---------------------------------
        setTimeout(function ()
        {
            $("#CampionatiSport0").trigger('click');
        }, 500);

        for (i in options_type)
        {
            name = options_type[i]
            options_type_obj[name] = i;
        }

        for (i in options_sesso)
        {
            name = options_sesso[i]
            options_sesso_obj[name] = i;
        }

        //-----------------------------------------------------


        type_events = <?= json_encode($tipo_manifestazione, true) ?>;

    });


    // GIUSEPPE 2017-05-16 ---------------------------------

    $("#CampionatiManifestazione").change(function ()
    {

        var id_event = $("#CampionatiManifestazione").val();

        read_type_event(id_event, type_events)

    });

    function read_type_event(id_event, type_events)
    {
        $("#CampionatiTipologiaManifestazione").find('option').remove();

        $("#CampionatiTipologiaManifestazione").append('<option value="0"></option>');

        var id;
        //console.log(type_events);
        for (i in type_events)
        {


            if (parseInt(type_events[i].event_id) === parseInt(id_event))
            {
                var nome = type_events[i].Nome;
                //console.log(nome);
                id = type_events[i].id;

                var info_type = read_content(JSON.parse(type_events[i].content)).join(", ");

                $("#CampionatiTipologiaManifestazione").append('<option value="' + id + '">' + nome + ' → ' + info_type + '</option>');
            }
        }

        function read_content(content)
        {
            // console.log(content[0]);

            var array_result = [];

            var type = {"c5f": ["", "C5 F"], "c5m": ["", "C5 M"], "c7": ["", "C7 M"], "c7f": ["", "C7 F"], "c11": ["", "C11"]}

            var type_soccer = content[0]; // è il seguente json di esempio {"c5f":"0","c5m":"0","c7f":"1","c7":"0","c11":"0"}

            var symbols = Object.keys(type_soccer); // elenca in un array le proprietà dell'oggetto json "type_soccer"

            for (i in symbols)
            {
                if (parseInt(type_soccer[symbols[i]]) != 0)
                {
                    array_result.push(type[symbols[i]][type_soccer[symbols[i]]])
                }
            }

            return array_result;
        }
    }

    // -----------------------------------------------------


    // GIUSEPPE 04/10/2016 ----------------------------------------------------

    $('#CampionatiSport0').click(function ()
    { // radio button calcio

        $('#finestre_calcio').show();

        type_champions(typeSport.CALCIO);

        readCategory(typeSport.CALCIO);

    });



    $('#CampionatiSport1').click(function ()
    { // radio button tennis

        $('#finestre_calcio').hide();

        type_champions(typeSport.TENNIS);

        readCategory(typeSport.TENNIS);

    });


//GIUSEPPE 2020-01-18 - - - - - - - - - - - - - - - - - -

    $("#CampionatiSport2").click(function ()
    {
        $('#finestre_calcio').show();

        //console.log("CampionatiSport2");

        type_champions(typeSport.BASKET);

        readCategory(typeSport.BASKET);
    });

//GIUSEPPE 2020-09-11 - - - - - - - - - - - - - - - - - -

    $("#CampionatiSport3").click(function ()
    {
        //console.log("CampionatiSport3");

        $('#finestre_calcio').show();

        type_champions(typeSport.eSPORT);

        readCategory(typeSport.eSPORT);
    });


    $("#CampionatiSport4").click(function ()
    {
        //console.log("CampionatiSport4");

        $('#finestre_calcio').show();

        type_champions(typeSport.PADEL);

        readCategory(typeSport.PADEL);
    });

//GIUSEPPE 2017-04-27 - - - - - - - - - - - - - - - - - -

    function type_champions(type_sport)
    {
        remove_all();

        switch (type_sport)
        {
            case typeSport.CALCIO:
                calcio();
                break;

            case typeSport.TENNIS:
                tennis();
                break;

            case typeSport.BASKET:
                basket();
                break;

                /* //GIUSEPPE 2020-09-01  */
            case typeSport.eSPORT:
                esport();
                break;

            case typeSport.PADEL:
                padel();
                break;

                /* //-------------------- */
        }


        function calcio()
        {


            //remove_all();

            value = options_type_obj['C5'];
            $("#CampionatiTipo").append('<option value="' + value + '">C5</option>');

            value = options_type_obj['C7'];
            $("#CampionatiTipo").append('<option value="' + value + '">C7</option>');

            value = options_type_obj['C11'];
            $("#CampionatiTipo").append('<option value="' + value + '">C11</option>');

            value = options_type_obj['3VS3'];
            $("#CampionatiTipo").append('<option value="' + value + '">3VS3</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');

            $("#CampionatiManifestazione").html('');


<? foreach ($manifestazioni as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");

<? endforeach; ?>


        }


        function tennis()
        {

            //remove_all();

            value = options_type_obj['SINGOLO'];
            $("#CampionatiTipo").append('<option value="' + value + '">SINGOLO</option>');

            value = options_type_obj['A SQUADRE'];
            $("#CampionatiTipo").append('<option value="' + value + '">A SQUADRE</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');

            value = options_sesso_obj['Misto'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Misto</option>');

            $("#CampionatiManifestazione").html('');

<? foreach ($manifestazioni_tennis as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");

<? endforeach; ?>

        }





        function basket()
        {

            //remove_all();


            value = options_type_obj['3VS3'];
            $("#CampionatiTipo").append('<option value="' + value + '">3VS3</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');


            $("#CampionatiManifestazione").html('');


<? foreach ($manifestazioni_basket as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");

<? endforeach; ?>


        }

        /* //GIUSEPPE 2020-09-01 ------------------------------------- */

        function esport()
        {

            //remove_all();

            value = options_type_obj['PS'];
            $("#CampionatiTipo").append('<option value="' + value + '">PS</option>');


            value = options_type_obj['XBox'];
            $("#CampionatiTipo").append('<option value="' + value + '">XBox</option>');



            value = options_sesso_obj['FIFA'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">FIFA</option>');

            value = options_sesso_obj['PES'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">PES</option>');

            value = options_sesso_obj['Altro'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Altro</option>');


            $("#CampionatiManifestazione").html('');


<? foreach ($manifestazioni_basket as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");

<? endforeach; ?>


        }

        function padel()
        {

            //remove_all();

            value = options_type_obj['2VS2'];
            $("#CampionatiTipo").append('<option value="' + value + '">2VS2</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');

            value = options_sesso_obj['Misto'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Misto</option>');


            $("#CampionatiManifestazione").html('');


<? foreach ($manifestazioni_basket as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");

<? endforeach; ?>


        }
        /* ----------------------------------------------------------- */



        function remove_all()
        {

            $("#CampionatiSessoTipo option").remove();

            $("#CampionatiTipo option").remove();
        }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - -

    $("#CampionatiTipo").change(function ()
    {
        //console.log($('#CampionatiTipo').val() + " - - - - - - - - ");

        var num_options_sesso = $('#CampionatiSessoTipo option').length;

        if ($('#CampionatiTipo').val() == '5' && num_options_sesso == 2)
        {
            $("#CampionatiSessoTipo").append('<option value="2">Misto</option>');
        }
        else if ($('#CampionatiTipo').val() != '5')
        {
            $("#CampionatiSessoTipo option[value=2]").remove();
        }
    });



    function readCategory(id_sport)
    {
        $.get('/admin/campionatis/switch/' + id_sport, function (ret)
        {

            // parserizzo la stringa in json e ricavo le proprietà.
            // quindi costruisco la struttura da inserire nella listbox della Categoria

            var arrayCategory = JSON.parse(ret);

            var stringToCategory = "<option value=''> </option>";

            console.log(ret);

            //GIUSEPPE 2020-01-18 - - - - - - - - - - - - - - - - - -
            for (prop in arrayCategory)
            { // qui ricavo le proprietà dell'oggetto json

                id = arrayCategory[prop]['id'];
                name = arrayCategory[prop]['name'];

                stringToCategory += "<option value='" + id + "'>" + name + "</option>"

            }
            //- - - - - - - - - - - - - - - - - - - - - - - - - - - -

            //console.log(stringToCategory);

            $("#CampionatiCategoria  option").remove();

            $("#CampionatiCategoria").append(stringToCategory);

        });
    }

    //----------------------------------------------------------------
</script>


<? $this->Session->delete('gironi'); ?>

<? $this->Session->delete('campi'); ?>

<?= $this->Form->create('Campionati', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Aggiungi nuovo campionato</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->


<div class="clear"></div>   
<div class="t">


    <? //GIUSEPPE --------------------------------------------     ?>

    <ul class="tab-selector" >

        <li data-index="1" class="selected"><a href="javascript:;">Campionato</a></li>

    </ul>
    <? //----------------------------------------     ?>

    <div class="tab-page tab-selected" data-index="1">


        <?php
//GIUSEPPE 04/10/2016 -------------------

        $arrayRadio = array();

        $res = mysql_query("SELECT * FROM TipoSport WHERE 1");

        while ($row = mysql_fetch_assoc($res))
        {
            $arrayRadio[] = $row['sport'];
        }

        if (isset($this->data['Campionati']['sport']))
        {
            echo $this->Form->radio('sport', $arrayRadio, array('value' => $this->data['Campionati']['id_sport']));

            switch ($this->data['Campionati']['id_sport'])
            {
                case 0:
                    //echo "<script>$('#CampionatiSport0').trigger('click');</script>";
                    break;

                case 1:
                    echo "<script>removeTariffe()</script>";
                    break;
            }
        }
        else
        {
            echo $this->Form->radio('sport', $arrayRadio, array('value' => 0)); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")";
        }
        ?>


        <? //print_r($this->Form)//echo "---".$this->data['Campionati']['sport']."---"// ------------------------------------   ?>


        <?= $this->Form->input('Nome', array('class' => 'big')); ?>

        <?= $this->Form->input('Categoria', array('type' => 'select', 'options' => $categories, 'empty' => true)); ?>

        <?
        $options = array();

        foreach ($AnniSportivi as $AnnoSportivo)
        {
            $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
        }
        ?>

        <?= $this->Form->input('AnnoSportivo', array('type' => 'select', 'default' => '1', 'options' => $options)); ?>



        <div class="clear"></div>   

        <?=
        $this->Form->input('InCorso', array(
            'label' => 'In Corso',
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>

        <?=
        $this->Form->input('InUso', array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>

        <?=
        $this->Form->input('Italiana', array(
            'label' => 'Italiana',
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
        ?>

        <!-- //Inserimento flag PlayLeague 2022-09-13 -->
        <?=
        $this->Form->input('PlayLeague', array(
            'label' => 'PlayLeague',
            'type' => 'select',
            'options' => array('0' => 'No', '1' => 'Si'),
        ));
        ?>
        <!-- ##################### -->

        <div class="clear"></div>

        <?= $this->Form->input('Manifestazione', array('label' => 'Manifestazione', 'type' => 'select', 'default' => '0', 'options' => $manifestazioni)); ?>

        <div id="finestre_calcio">


            <!-- //GIUSEPPE 2017-05-15 --------------------------------------------------------------------- -->

            <? //= $this->Form->input('Manifestazione', array('label' => 'Manifestazione', 'type' => 'select', 'default' => '0', 'options' => $manifestazioni));     ?>

            <?= $this->Form->input('TipologiaManifestazione', array('label' => 'Tipologia Manifestazione', 'type' => 'select', 'default' => '0')); ?>

            <div class="clear"></div>

            <!-- ------------------------------------------------------------------------------------------- -->


            <?= $this->Form->input('CampionatoSearch', array('label' => 'Campionato Precedente', 'class' => 'big autoComplete', 'data-url' => '/admin/campionatis/searchCampionato', 'data-dest' => 'CampionatiCampionatoPrecedente')); ?>

            <?= $this->Form->input('CampionatoPrecedente', array('type' => 'hidden')); ?>

            <div class="clear"></div>

            <?= $this->Form->input('TariffaArbitro', array('label' => 'Tariffa Arbitro')); ?>
            <?= $this->Form->input('TariffaArbitro2', array('label' => 'Tariffa Arbitro Singolo')); ?>
            <?= $this->Form->input('TariffaDelegato', array('label' => 'Tariffa Delegato')); ?>
            <?= $this->Form->input('TariffaDelegatoA', array('label' => 'Tariffa Delegato Singolo')); ?>
        </div>
        <div class="clear"></div>
        <h3>Opzioni</h3>

        <? //GIUSEPPE ----------------------------------------------------------------------------     ?>
        <div class="number input text" id="tipologia">
            <?= $this->Form->input('Tipo', array('label' => 'Tipologia campionato', 'type' => 'select', 'options' => $options_type, 'div' => false)); ?>
        </div>

        <? //-------------------------------------------------------------------------------------     ?>

        <div class="number input text">
            <?= $this->Form->input('SessoTipo', array('label' => 'Tipologia sesso', 'type' => 'select', 'options' => $options_sesso, 'div' => false)); ?>
        </div>


    </div>

    <div class="tab-page" data-index="2">

        <h3> Campi </h3>

        <table class="form_table form_table_full" id="table_form_campi" style="display: none">
            <tr class="append_campi">
                <th>Campo</th>
                <th>Giorno</th>
                <th>Ora</th>
                <th>Opzioni</th>
            </tr>
        </table>
        <div class="girone_add">
            <div class="description input text">
                <?= $this->Form->input('CampoSearch', array('label' => 'Campo', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchCampo', 'data-dest' => 'CampionatiCampo')); ?>
                <?= $this->Form->input('Campo', array('type' => 'hidden', 'div' => false)); ?>
                <div class="error_campo error-message"></div>
            </div>
            <div class="number input text">
                <?
                $options = array();
                $options['Lunedì'] = 'Lunedì';
                $options['Martedì'] = 'Martedì';
                $options['Mercoledì'] = 'Mercoledì';
                $options['Giovedì'] = 'Giovedì';
                $options['Venerdì'] = 'Venerdì';
                $options['Sabato'] = 'Sabato';
                $options['Domenica'] = 'Domenica';
                ?>
                <?= $this->Form->input('Giorno', array('label' => 'Giorno', 'type' => 'select', 'options' => $options, 'div' => false)); ?>
                <div class="error_giorno error-message"></div>
            </div>
            <div class="date input text">
                <?= $this->Form->input('Ora', array('label' => 'Ora', 'class' => 'control_ora', 'div' => false)); ?>
                <div class="error_ora error-message"></div>
            </div>
            <div class="button input">
                <label>&nbsp;</label>
                <?= $this->Form->submit('Aggiungi Campo', array('type' => 'button', 'class' => 'CampoAdd', 'id' => 'ButtonCampoAdd', 'div' => false)); ?>
                <a style="display: none;" href="javascript:;" class="reset_edit_campo"><img src="/img/timmyshare/icon_reset_quick_search.png" /></a>
            </div>
        </div>

    </div>

    <div class="tab-page" data-index="3">

        <h3> Gironi </h3>

        <table class="form_table form_table_full" id="table_form" style="display: none">
            <tr class="append_gironi">
                <th>Descrizione</th>
                <th>Nr Squadre</th>
                <th>Data inizio</th>
                <th>Opzioni</th>
            </tr>
        </table>

        <div class="girone_add">
            <div class="description input text">
                <?= $this->Form->input('Descrizione', array('label' => 'Descrizione', 'div' => false)); ?>
                <div class="error_description error-message"></div>
            </div>
            <div class="number input text">
                <?= $this->Form->input('NumeroSquadre', array('label' => 'Nr. Squadre', 'div' => false)); ?>
                <div class="error_team error-message"></div>
            </div>
            <div class="date input text">
                <?= $this->Form->input('DataInizio', array('label' => 'Data Inizio', 'type' => 'text', 'class' => 'datePicker', 'div' => false)); ?>
                <div class="error_date error-message"></div>
            </div>
            <div class="button input">
                <label>&nbsp;</label>
                <?= $this->Form->submit('Aggiungi Girone', array('type' => 'button', 'class' => 'GironeAdd', 'id' => 'ButtonGironeAdd', 'div' => false)); ?>
                <a style="display: none;" href="javascript:;" class="reset_edit"><img src="/img/timmyshare/icon_reset_quick_search.png" /></a>
            </div>
        </div>
    </div>

    <?= $this->Form->end(); ?>

</div>
