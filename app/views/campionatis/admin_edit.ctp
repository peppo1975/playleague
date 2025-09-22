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
//print_r($this->data);
//print_r($_SESSION);

$Tipo = $this->data['Campionati']['Tipo'];
$SessoTipo = $this->data['Campionati']['SessoTipo'];
$id_sport = $this->data['Campionati']['id_sport'];
$Campionato = $this->data['Campionati']['Campionato'];
$Nome = $this->data['Campionati']['Nome'];

/*
  [Campionati][Tipo] => 5
  [Campionati][SessoTipo] => 2
  [Campionati][id_sport] => 1
 */
?>
<script type="text/javascript">

    var idCampionatoManifestazione;

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
                //var GironeCampionato = $("#CampionatiGironeCampionato").val();
                var Campionato = $("#CampionatiCampionato").val();
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
                var girone = {Half: {"Campionato": Campionato, "Descrizione": Descrizione, "NumeroSquadre": NumeroSquadre, "DataInizio": DataInizio}}

                exit = 1;
                $.post('/admin/campionatis/editGirone/' + edit_girone, girone, function (ret)
                {

                    if (ret.update == 'aggiunto')
                    {

                        $(
                                '<tr class="girone-delete" data-id="' + ret.last_id + '">' +
                                '<td data-descrizione="' + ret.last_id + '">' + Descrizione + '</td>' +
                                '<td data-squadre="' + ret.last_id + '">' + NumeroSquadre + '</td>' +
                                '<td data-date="' + ret.last_id + '">' + DataInizio + '</td>' +
                                '<td data-opzioni="' + ret.last_id + '">' +
                                '<a href="javascript:;"  data-edit="' + ret.last_id + '" class="GironeEdit"><img src="/img/timmyshare/icon_edit.png" /></a>' +
                                '<a href="javascript:;" data-associa-squadra="' + ret.last_id + '" class="GironeAssociaSquadra"><img src="/img/timmyshare/icon_group.png" /></a>' +
                                '<a href="javascript:;"  data-delete="' + ret.last_id + '" class="GironeDelete"><img src="/img/timmyshare/icon_delete.png" /></a>' +
                                '</td>' +
                                '</tr>'

                                ).insertAfter('.append_gironi');
                        $("#CampionatiDescrizione").val('');
                        $("#CampionatiNumeroSquadre").val('');
                        $("#CampionatiDataInizio").val('');
                        $("#CampionatiGironeCampionato").val('');
                    }
                    else
                    {

                        $('.girone-delete').find('td[data-descrizione = ' + ret.update + ']').html(Descrizione);
                        $('.girone-delete').find('td[data-squadre = ' + ret.update + ']').html(NumeroSquadre);
                        $('.girone-delete').find('td[data-date = ' + ret.update + ']').html(DataInizio);
                        $('.reset_edit').css('display', 'none');
                        $("#ButtonGironeAdd").val('Aggiungi girone');
                        $("#CampionatiDescrizione").val('');
                        $("#CampionatiNumeroSquadre").val('');
                        $("#CampionatiDataInizio").val('');
                        $("#CampionatiGironeCampionato").val('');
                        edit_girone = 'aggiunto';
                    }

                    exit = 0;
                }, 'json');
            });
            $('.formAdd').delegate('.GironeDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-delete');
                if (confirm('Eliminare record?'))
                {

                    $.get('/admin/campionatis/editGironeDelete/' + delete_id, function (ret)
                    {

                        if (ret.delete == 1)
                        {

                            $(".girone-delete[data-id='" + delete_id + "']").remove();
                        }
                        else
                        {

                            alert('Impossibile cancellare, eliminare prima le squadre.');
                        }

                    }, 'json');
                }

            });
            $('.formAdd').delegate('.GironeEdit', 'click', function ()
            {

                var edit_id = $(this).attr('data-edit');
                edit_girone = edit_id;
                var Descrizione = $(this).closest('tr').find('td[data-descrizione = ' + edit_id + ']').html();
                var NumeroSquadre = $(this).closest('tr').find('td[data-squadre = ' + edit_id + ']').html();
                var DataInizio = $(this).closest('tr').find('td[data-date = ' + edit_id + ']').html();
                $("#CampionatiGironeCampionato").val(edit_id);
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
                $("#ButtonGironeAdd").val('Aggiungi girone');
                edit_girone = 'aggiunto';
            });
            $('.formAdd').delegate('.CampoAdd', 'click', function ()
            {

                var error = 0;
                $('.error_campo').html('');
                $('.error_giorno').html('');
                $('.error_ora').html('');
                var Campionato = $("#CampionatiCampionato").val();
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
                var Campo_array = {Campicampionati: {"Campionato": Campionato, "Campo": Campo, "CampoSearch": CampoSearch, "Giorno": Giorno, "Ora": Ora}}

                $.post('/admin/campionatis/editCampo/' + edit_campo, Campo_array, function (ret)
                {

                    var indice = $("#table_form_campi").find('tr').length - 1;
                    if (ret.update == 'aggiunto')
                    {

                        $(
                                '<tr class="campo-delete" data-array-index="' + ret.last_id + '">' +
                                '<td data-campo="' + ret.last_id + '">' + CampoSearch + '</td>' +
                                '<td style="display: none;" data-campo-id="' + ret.last_id + '">' + Campo + '</td>' +
                                '<td data-giorno="' + ret.last_id + '">' + Giorno + '</td>' +
                                '<td data-ora="' + ret.last_id + '">' + Ora + '</td>' +
                                '<td>' +
                                '<a href="javascript:;"  data-array-index="' + ret.last_id + '" class="CampoEdit"><img src="/img/timmyshare/icon_edit.png" /></a>' +
                                '<a href="javascript:;"  data-array-index="' + ret.last_id + '" class="CampoDelete"><img src="/img/timmyshare/icon_delete.png" /></a>' +
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

                        $('.campo-delete').find('td[data-campo = ' + ret.update + ']').html(CampoSearch);
                        $('.campo-delete').find('td[data-campo-id = ' + ret.update + ']').html(Campo);
                        $('.campo-delete').find('td[data-giorno = ' + ret.update + ']').html(Giorno);
                        $('.campo-delete').find('td[data-ora = ' + ret.update + ']').html(Ora);
                        $('.reset_edit_campo').css('display', 'none');
                        $("#ButtonCampoAdd").val('Aggiungi girone');
                        $("#CampionatiCampoCampionato").val('');
                        $("#CampionatiCampo").val('');
                        $("#CampionatiCampoSearch").val('');
                        $("#CampionatiGiorno").val('');
                        $("#CampionatiOra").val('');
                        edit_campo = 'aggiunto';
                    }

                }, 'json');
            });
            $('.formAdd').delegate('.CampoDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-delete');
                if (delete_id == undefined)
                    delete_id = $(this).attr('data-array-index');
                if (confirm('Eliminare record?'))
                {

                    $.get('/admin/campionatis/editCampoDelete/' + delete_id, function (ret)
                    {

                        $(".campo-delete[data-id='" + delete_id + "'],.campo-delete[data-array-index='" + delete_id + "']").remove();
                    }, 'json');
                }

            });
            $('.CampoEdit').live('click', function ()
            {

                var edit_id = $(this).attr('data-edit');
                edit_campo = edit_id;
                var Campo = $(this).closest('tr').find('td[data-campo-id=' + edit_id + ']').html();
                var CampoSearch = $(this).closest('tr').find('td[data-campo=' + edit_id + ']').html();
                var Giorno = $(this).closest('tr').find('td[data-giorno=' + edit_id + ']').html();
                var Ora = $(this).closest('tr').find('td[data-ora=' + edit_id + ']').html();
                $("#CampionatiCampoCampionato").val(edit_id);
                $("#CampionatiCampo").val(Campo);
                $("#CampionatiCampoSearch").val(CampoSearch);
                $("#CampionatiGiorno").val(Giorno);
                $("#CampionatiOra").val(Ora);
                $("#ButtonCampoAdd").val('Modifica');
                $('.reset_edit_campo').css('display', 'inline');
                return;
            });
            $('.formAdd').delegate('.reset_edit_campo', 'click', function ()
            {

                $("#CampionatiCampoCampionato").val('');
                $("#CampionatiCampo").val('');
                $("#CampionatiCampoSearch").val('');
                $("#CampionatiGiorno").val(1);
                $("#CampionatiOra").val('');
                $('.reset_edit_campo').css('display', 'none');
                $("#ButtonCampoAdd").val('Aggiungi Campo');
                edit_campo = 'aggiunto';
            });
            $('.formAdd').delegate('.GironeAssociaSquadra', 'click', function ()
            {

                //alert("fengul"); 

                var Girone_id = $(this).attr('data-associa-squadra');
                var NumeroSquadre = $('.girone-delete').find('td[data-squadre = ' + Girone_id + ']').html();
                var Campionato_id = $("#CampionatiCampionato").val();
                timmy_load('/admin/halfs/associaSquadre/' + Girone_id + '/' + Campionato_id + '/' + NumeroSquadre);
            });
        });
    }






    // GIUSEPPE 2017-01-31 ----------------------------------------------------------------


    //GIUSEPPE 2020-01-18 ----------------------
//    var typeSport = {CALCIO: 0, TENNIS: 1, BASKET: 2, eSPORT: 3, PADEL: 4};

    //GIUSEPPE 2020-09-01 ----------------------
    var typeSport = <?= json_encode($type_sport) ?>;
    var options_type = <?= json_encode($options_type) ?>;
    var options_type_obj = {};

    var options_sesso = <?= json_encode($options_sesso) ?>;
    var options_sesso_obj = {};



    //------------------------------------------

    $(document).ready(function ()
    {

//GIUSEPPE 2020-09-01--------------------------------

        var Tipo = "<?= $Tipo ?>";
        var SessoTipo = "<?= $SessoTipo ?>";
        var id_sport = <?= $id_sport ?>;

        console.log('qui');





        setTimeout(function ()
        {
            type_champions(parseInt(id_sport));
            document.getElementById("CampionatiTipo").value = Tipo;
            document.getElementById("CampionatiSessoTipo").value = SessoTipo;
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
//---------------------------------------------------


        var id = $("#CampionatiCampionato").val();

        $.get('/admin/campionatis/readTipologiaCampionato/' + id, function (ret)
        {

            //console.log('qui');

            $("#CampionatiTipoCalcio").val(ret);

            $("#CampionatiTipoTennis").val(ret);

        });

        //GIUSEPPE 2017-05-16 - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        type_events = <?= json_encode($tipo_manifestazione, true) ?>;

        var id_event = <?= !empty($this->data['Campionati']['Evento']) ? $this->data['Campionati']['Evento'] : 0 ?>;

        read_type_event(id_event, type_events);

        $("#CampionatiManifestazione").val(<?= !empty($this->data['Campionati']['Evento']) ? $this->data['Campionati']['Evento'] : '' ?>);

        $("#CampionatiTipologiaManifestazione").val(<?= @$this->data['Campionati']['EventoTipo'] ?>);





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

            for (i in type_events)
            {
                if (parseInt(type_events[i].event_id) === parseInt(id_event))
                {
                    var nome = type_events[i].Nome;

                    id = type_events[i].id;

                    var info_type = read_content(JSON.parse(type_events[i].content)).join(", ");

                    $("#CampionatiTipologiaManifestazione").append('<option value="' + id + '">' + nome + ' → ' + info_type + '</option>');
                }
            }
        }


        function read_content(content)
        {
            // console.log(content[0]);

            var array_result = [];

//            var type = {"c5f": ["", "C5 F"], "c5m": ["", "C5 M"], "c7": ["", "C7 M"], "c7f": ["", "C7 F"], "c11": ["", "C11"]}

            var type = {"c5f": ["", "C5 F"], "c5m": ["", "C5 M"], "c7": ["", "C7 M"], "c7f": ["", "C7 F"], "c11": ["", "C11"], "t": ["", "T"]};

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

        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


    });



    $("#CampionatiTipoCalcio").change(function ()
    {

        var id = $("#CampionatiCampionato").val();

        var type = $("#CampionatiTipoCalcio").val();

        //alert(id + " - " + type);

        $.get('/admin/campionatis/editTipologiaCampionato/' + id + "/" + type, function (ret)
        {

        });
    });


    $("#CampionatiTipoTennis").change(function ()
    {

        var id = $("#CampionatiCampionato").val();

        var type = $("#CampionatiTipoTennis").val();

        var sesso = $("#CampionatiSessoTipo").val();

        //GIUSEPPE 2017-04-24 aggiunta voce "Misto" a sesso squadre tennis - - - - - - -

        if ($("#CampionatiTipoTennis").val() == '4')
        {
            $("#CampionatiSessoTipo option[value=2]").remove();

        }
        else if ($("#CampionatiTipoTennis").val() == '5')
        {
            $("#CampionatiSessoTipo").append('<option value="2">Misto</option>');
        }

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        $.get('/admin/campionatis/editTipologiaCampionato/' + id + "/" + type, function (ret)
        {

        });
    });

    // GIUSEPPE 04/10/2016 ----------------------------------------------------------------

    $('#CampionatiSport0').click(function () //CALCIO
    { // radio button calcio
        calcio_mask();
        readCategory(typeSport.CALCIO.toString());
        selectorTab(typeSport.CALCIO);

        $('#finestre_calcio').show();


        //GIUSEPPE 2020-09-01--------------------------------------
        type_champions(typeSport.CALCIO);
        //---------------------------------------------------------

    });


    $('#CampionatiSport1').click(function () //TENNIS
    { // radio button tennis
        tennis_mask();
        readCategory(typeSport.TENNIS.toString());
        selectorTab(typeSport.TENNIS);

        $('#finestre_calcio').hide();


        //GIUSEPPE 2020-09-01--------------------------------------
        type_champions(typeSport.TENNIS);
        //---------------------------------------------------------

    });


    //GIUSEPPE 2019-01-18 - - - - - - - - - - - - - - - - - -                                    

    $('#CampionatiSport2').click(function () //BASKET
    { // radio button BASKET
        calcio_mask();
        readCategory(typeSport.BASKET.toString());
        selectorTab(typeSport.BASKET);

        $('#finestre_calcio').show();


        //GIUSEPPE 2020-09-01--------------------------------------
        type_champions(typeSport.BASKET);
        //---------------------------------------------------------

    });


    //GIUSEPPE 2019-09-01 - - - - - - - - - - - - - - - - - -                                    

    $('#CampionatiSport3').click(function () //eSPORT
    { // radio button eSPORT
        calcio_mask();
        readCategory(typeSport.eSPORT.toString());
        selectorTab(typeSport.eSPORT);

        $('#finestre_calcio').show();


        //GIUSEPPE 2020-09-01--------------------------------------
        type_champions(typeSport.eSPORT);
        //---------------------------------------------------------

    });

    //GIUSEPPE 2019-09-01 - - - - - - - - - - - - - - - - - -                                    

    $('#CampionatiSport4').click(function () //PADEL
    { // radio button PADEL
        calcio_mask();
        readCategory(typeSport.PADEL.toString());
        selectorTab(typeSport.PADEL);

        $('#finestre_calcio').show();


        //GIUSEPPE 2020-09-01--------------------------------------
        type_champions(typeSport.PADEL);
        //---------------------------------------------------------

    });


//GIUSEPPE 2020-09-01 - - - - - - - - - - - - - - - - - -


    $(".duplica_campionato").click(function ()
    {
        Nome = "<?= $Nome ?>";

        Campionato = "<?= $Campionato ?>";

        var link = "/admin/campionatis/duplica_campionato";

        var variabili = {};

        var r = confirm("Creare\n\n'" + Nome + " - COPIA'?");

        variabili['Campionato'] = Campionato;

        if (r == true)
        {
            //txt = "You pressed OK!";
            $.post(link, variabili, function (data)
            {
                console.log(data);

                res = JSON.parse(data);

                if (res['insert'])
                {
                    location.reload();
                }
                else
                {
                    alert('Spiacente\nNon è possibile creare il duplicato\n' + res['message']);
                }
            });

        }
        else
        {
            txt = "You pressed Cancel!";
        }

    });

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


            case typeSport.eSPORT:
                esport();
                break;

            case typeSport.PADEL:
                padel();
                break;


        }


        function calcio()
        {

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

            //$("#CampionatiManifestazione").html('');

        }


        function tennis()
        {



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

            //$("#CampionatiManifestazione").html('');

        }


        function basket()
        {

            value = options_type_obj['3VS3'];
            $("#CampionatiTipo").append('<option value="' + value + '">3VS3</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');


            //$("#CampionatiManifestazione").html('');





        }

        /* //GIUSEPPE 2020-09-01 ------------------------------------- */

        function esport()
        {

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


            //$("#CampionatiManifestazione").html('');

        }

        function padel()
        {

            value = options_type_obj['2VS2'];
            $("#CampionatiTipo").append('<option value="' + value + '">2VS2</option>');



            value = options_sesso_obj['Maschile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Maschile</option>');

            value = options_sesso_obj['Femminile'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Femminile</option>');

            value = options_sesso_obj['Misto'];
            $("#CampionatiSessoTipo").append('<option value="' + value + '">Misto</option>');


            //$("#CampionatiManifestazione").html('');

        }
        /* ----------------------------------------------------------- */



        function remove_all()
        {
            $("#CampionatiSessoTipo option").remove();

            $("#CampionatiTipo option").remove();
        }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - -




    function tennis_mask()
    {

        $("#finestre_calcio").hide();
        $("#CampionatiScuola").hide();
        $("#CampionatiCampionatoSearch").hide();
        $("#CampionatiTariffaArbitro").hide();
        $("#CampionatiTariffaArbitro2").hide();
        $("#CampionatiTariffaDelegato").hide();
        $("#CampionatiTariffaDelegatoA").hide();
        $("#tipologia_calcio").hide();
        $("#tipologia_tennis").show();
        $("#CampionatiDescrizioneTorneoGroup").show();



        $("#CampionatiManifestazione").html('');

<? $id = !empty($this->data['Campionati']['Evento']) ? $this->data['Campionati']['Evento'] : '';  //GIUSEPPE 2019-03-15                                                       ?>
<? if ($id == ''): ?>
            IdCampionatomanifestazione = -1;
<? else : ?>
            IdCampionatomanifestazione = <?= $id ?>;
<? endif; ?>



<? foreach ($manifestazioni_tennis as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>
            if (parseInt(IdCampionatomanifestazione) === <?= $key ?>)
            {
                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot; selected=&quot;selected&quot;><?= $manifestazione ?></option>");
            }
            else
            {
                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");
            }

<? endforeach; ?>

    }


    function calcio_mask()
    {
        $("#finestre_calcio").show();
        $("#CampionatiScuola").show();
        $("#CampionatiCampionatoSearch").show();
        $("#CampionatiTariffaArbitro").show();
        $("#CampionatiTariffaArbitro2").show();
        $("#CampionatiTariffaDelegato").show();
        $("#CampionatiTariffaDelegatoA").show();
        $("#tipologia_calcio").show();
        $("#tipologia_tennis").hide();
        $("#CampionatiDescrizioneTorneoGroup").hide();



        $("#CampionatiManifestazione").html('');

<? $id = !empty($this->data['Campionati']['Evento']) ? $this->data['Campionati']['Evento'] : '';  //GIUSEPPE 2019-03-15                                                       ?>
<? if ($id == ''): ?>
            IdCampionatomanifestazione = -1;
<? else : ?>
            IdCampionatomanifestazione = <?= $id ?>;
<? endif; ?>

<? foreach ($manifestazioni as $key => $nome_manifestazione): ?>
    <? $manifestazione = str_replace("\"", "&quot;", $nome_manifestazione) ?>

            if (parseInt(IdCampionatomanifestazione) === <?= $key ?>)
            {
                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot; selected=&quot;selected&quot;><?= $manifestazione ?></option>");
            }
            else
            {
                $("#CampionatiManifestazione").append("<option value=&quot;<?= (int) $key ?>&quot;><?= $manifestazione ?></option>");
            }

<? endforeach; ?>




    }

    function readCategory(id_sport, id_categoria)
    {
        $.get('/admin/campionatis/switch/' + id_sport, function (ret)
        {

            // parserizzo la stringa in json e ricavo le proprieta.
            // quindi costruisco la struttura da inserire nella listbox della Categoria

            var arrayCategory = JSON.parse(ret);
            console.log(arrayCategory);
            var stringToCategory = "<option value=''> </option>";
            //console.log(ret);

            for (var prop in arrayCategory)
            { // qui ricavo le proprieta dell'oggetto json

//                stringToCategory += "<option value='" + prop + "'>" + arrayCategory[prop] + "</option>"
                stringToCategory += "<option value='" + arrayCategory[prop]['id'] + "'>" + arrayCategory[prop]['name'] + "</option>"

            }

            $("#CampionatiCategoria  option").remove();
            $("#CampionatiCategoria").append(stringToCategory);
            $("#CampionatiCategoria option[value=" + id_categoria + "]").attr('selected', 'selected');
        });
    }

    var selector_sport = ["tab_selector_calcio", "tab_selector_tennis", "tab_selector_basket", "tab_selector_esport", "tab_selector_padel"];

    function selectorTab(id_sport)
    {

        for (i in selector_sport)
        {
            $("#" + selector_sport[i]).hide();
        }

        $("#" + selector_sport[id_sport]).show()

    }





    $().ready(function ()
    {
        //alert('test');

        if ($('#CampionatiSport1:checked', '#CampionatiEditForm').val() == 1) /* visualizzazione tennis */
        {

            console.log('Tennis');
            tennis_mask();
            selectorTab(1);
            $('#finestre_calcio').hide();
        }
        else /* visualizzazione altri tranne tennis */
        {
            console.log('Calcio');
            calcio_mask();
            selectorTab(0);
            $('#finestre_calcio').show();
        }
    });




    // ------------------------------------------------------------------------------------


    //GIUSEPPE 2020-09-01 - - - - - - - - - - - - - - - - - -


    $(".pdf_liberatoria").click(function ()
    {

            Campionato = "<?= $Campionato ?>";

            //alert(Campionato);

            //$.post("/admin/campionatis/")

//            window.open('/admin/campionatis/pdfLiberatoria/' + Campionato, '_blank');
            window.open('/admin/campionatis/squadreLiberatoria/' + Campionato, '_blank');

        });

        <!-- ---------------------------------------------------------------- --!

</script>
<!--//GIUSEPPE 2020-09-01 ----------------- --> 
<style>
        .duplica_campionato{
            color: blue;
        }
        .duplica_campionato:hover{

            cursor: pointer;
            font-weight:bold;
            text-decoration:underline;
        }
    </style>
    <!-- -------------------------------------- --> 

    <?= $this->element("/backend/edit_scripts"); ?>

    <?= $this->element('/backend/tab_scripts'); ?>

    <?= $this->Form->create('Campionati', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

    <div class="form_header">

        <h2>Modifica campionato: <span><?= $this->data['Campionati']['Nome']; ?></span></h2>

        <ul>
            <!--//GIUSEPPE 2020-09-01 ----------------- --> 
            <li class="duplica_campionato"><button>duplica campionato</button></li>
            <!-- -------------------------------------- -->   
            <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
            <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
            <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>

        </ul>
        <div class="clear"></div>

    </div><!-- close form_header -->
    <div class="clear"></div>   
    <div class="tab-container">



        <? //GIUSEPPE 04/10/2016 ----------------------------------------------------------------            ?>

        <ul class="tab-selector" id="tab_selector_calcio">

            <li data-index="1" class="selected" ><a href="javascript:;">Campionato</a></li>
            <? if ($layout != 'tablet'): ?>
                <li data-index="2"><a href="javascript:;">Campi supplementari</a></li>
                <li data-index="3"><a href="javascript:;">Gironi</a></li>
                <li data-index="4"><a href="javascript:;">Fasi finali</a></li>      
                <li data-index="5"><a href="javascript:;">Vincitore</a></li>        
            <? endif; ?>
        </ul>

        <ul class="tab-selector" id="tab_selector_tennis">

            <li data-index="1" class="selected" ><a href="javascript:;">Campionato</a></li>
            <? if ($layout != 'tablet'): ?>
                <li data-index="3"><a href="javascript:;">Gironi</a></li>   
                <li data-index="5"><a href="javascript:;">Vincitore</a></li>        
            <? endif; ?>
        </ul>

        <!-- //GIUSEPPE 2020-01-18 --------------------------------------- -->
        <ul class="tab-selector" id="tab_selector_basket">

            <li data-index="1" class="selected" ><a href="javascript:;">Campionato</a></li>
            <? if ($layout != 'tablet'): ?>
                <li data-index="2"><a href="javascript:;">Campi supplementari</a></li>
                <li data-index="3"><a href="javascript:;">Gironi</a></li>
                <li data-index="4"><a href="javascript:;">Fasi finali</a></li>      
                <li data-index="5"><a href="javascript:;">Vincitore</a></li>        
            <? endif; ?>
        </ul>

        <? //------------------------------------------------------------------------------------             ?>

        <!-- //GIUSEPPE 2020-09-01 --------------------------------------- -->
        <ul class="tab-selector" id="tab_selector_esport">

            <li data-index="1" class="selected" ><a href="javascript:;">Campionato</a></li>
            <? if ($layout != 'tablet'): ?>
                <li data-index="2"><a href="javascript:;">Campi supplementari</a></li>
                <li data-index="3"><a href="javascript:;">Gironi</a></li>
                <li data-index="4"><a href="javascript:;">Fasi finali</a></li>      
                <li data-index="5"><a href="javascript:;">Vincitore</a></li>        
            <? endif; ?>
        </ul> 

        <? //------------------------------------------------------------------------------------             ?>

        <!-- //GIUSEPPE 2020-09-01 --------------------------------------- -->
        <ul class="tab-selector" id="tab_selector_padel">

            <li data-index="1" class="selected" ><a href="javascript:;">Campionato</a></li>
            <? if ($layout != 'tablet'): ?>
                <li data-index="2"><a href="javascript:;">Campi supplementari</a></li>
                <li data-index="3"><a href="javascript:;">Gironi</a></li>
                <li data-index="4"><a href="javascript:;">Fasi finali</a></li>      
                <li data-index="5"><a href="javascript:;">Vincitore</a></li>        
            <? endif; ?>
        </ul>

        <? //------------------------------------------------------------------------------------             ?>

        <? if (!empty($this->data['Campionati']['CampionatoPrecedente'])): ?>
            <ul class="tab-menu">
                <li>
                    <input id="FinalStageGenerate" type="button" value="Genera fasi finali" />              
                </li>
            </ul>
        <? endif; ?>        

        <div data-index="1" class="tab-page tab-selected">

            <?php
            //GIUSEPPE 04/10/2016 -------------------

            $arrayRadio = array();

            $res = mysql_query("SELECT * FROM TipoSport WHERE 1");

            while ($row = mysql_fetch_assoc($res))
            {
                $arrayRadio[] = $row['sport'];
            }
            ?>



            <?
            if (isset($this->data['Campionati']['sport']))
            {

                print($this->Form->radio('sport', $arrayRadio, array('value' => $this->data['Campionati']['id_sport'])));

                print("<script>selectorTab(" . $this->data['Campionati']['id_sport'] . ")</script>");

                print( "<script>readCategory(" . $this->data['Campionati']['id_sport'] . "," . $this->data['Campionati']['Categoria'] . ")</script>");

                switch ($this->data['Campionati']['id_sport'])
                {
                    case 0:
                        //echo "<script>$('#CampionatiSport0').trigger('click');</script>";
                        print( "<script>calcio_mask()</script>");
                        break;

                    case 1:
                        print( "<script>tennis_mask()</script>");
                        break;
                }
            }
            else
            {
                print($this->Form->radio('sport', $arrayRadio, array('value' => 0))); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")";
            }
            ?>




            <?= $this->Form->input('Campionato'); ?>
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
            <div id="CampionatiDescrizioneTorneoGroup">
                <?= $this->Form->input('descrizione_torneo', array('class' => 'big', 'rows' => 2)); ?>

            </div>

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
                'options' => array('Si' => 'Si', 'No' => 'No')
            ));
            ?>

            <?=
            $this->Form->input('iscrizioni', array(
                'label' => 'Stato iscrizioni',
                'type' => 'select',
                'options' => array('0' => 'Chiuse', '1' => 'Aperte'),
            ));
            ?>

            <?=
            $this->Form->input('scuola', array(
                'label' => 'Campionato Scuola C5',
                'type' => 'select',
                'options' => array('0' => 'No', '1' => 'Si'),
            ));
            ?>

            <?=
            $this->Form->input('Italiana', array(
                'label' => 'Italiana',
                'type' => 'radio',
                'options' => array('Si' => 'Si', 'No' => 'No'),
            ));
            ?>

            <!-- //GIUSEPPE 2022-09-13 -->
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

                <? //= $this->Form->input('Manifestazione', array('label' => 'Manifestazione', 'type' => 'select', 'default' => '0', 'options' => $manifestazioni));            ?>

                <?= $this->Form->input('TipologiaManifestazione', array('label' => 'Tipologia Manifestazione', 'type' => 'select', 'default' => '0')); ?>

                <div class="clear"></div>

                <!-- ------------------------------------------------------------------------------------------- -->



                <?= $this->Form->input('CampionatoSearch', array('label' => 'Campionato Precedente', 'class' => 'big autoComplete', 'readonly' => false, 'data-url' => '/admin/campionatis/searchCampionato', 'data-dest' => 'CampionatiCampionatoPrecedente')); ?>
                <?= $this->Form->input('CampionatoPrecedente', array('type' => 'hidden')); ?>

                <div class="clear"></div>

                <?= $this->Form->input('TariffaArbitro', array('label' => 'Tariffa Arbitro')); ?>
                <?= $this->Form->input('TariffaArbitro2', array('label' => 'Tariffa Arbitro Singolo')); ?>
                <?= $this->Form->input('TariffaDelegato', array('label' => 'Tariffa Delegato')); ?>
                <?= $this->Form->input('TariffaDelegatoA', array('label' => 'Tariffa Delegato Singolo')); ?>
            </div>
            <div class="clear"></div>
            <h3>Opzioni</h3>





            <? //GIUSEPPE 2020-09-01 -----------------------------------------------------------------          ?>

            <div class="number input text" id="tipologia">
                <?= $this->Form->input('Tipo', array('label' => 'Tipologia campionato', 'type' => 'select', 'options' => $options_type, 'div' => false)); ?>
            </div>

            <div class="number input text">
                <?= $this->Form->input('SessoTipo', array('label' => 'Tipologia sesso', 'type' => 'select', 'options' => $options_sesso, 'div' => false)); ?>
            </div>

            <? //-------------------------------------------------------------------------------------           ?>

            <div class="clear"></div>


            <!--//GIUSEPPE 2022-10-15 ----------------- --> 
            <h3>Liberatoria</h3>
            <!--<button class="pdf_liberatoria">PDF LIBERATORIA</button>-->
            <div class="pdf_liberatoria"><a href="#">STAMPA LIBERATORIA</a></div>
            <!-- -------------------------------------- -->

        </div>


        <div class="tab-page" data-index="2">

            <? //debug($campi);               ?>

            <h3> Campi </h3>

            <table class="form_table form_table_full" id="table_form_campi">
                <tr class="append_campi">
                    <th>Campo</th>
                    <th>Giorno</th>
                    <th>Ora</th>
                    <th>Opzioni</th>
                </tr>
                <? foreach ($campi as $campo): ?>

                    <tr class="campo-delete" data-id="<?= $campo['CampoCampionato']; ?>">

                        <td data-campo="<?= $campo['CampoCampionato']; ?>"><?= $campo['NomeCampo']; ?></td>
                        <td style="display: none;" data-campo-id="<?= $campo['CampoCampionato']; ?>"><?= $campo['Campo']; ?></td>
                        <td data-giorno="<?= $campo['CampoCampionato']; ?>"><?= $campo['Giorno']; ?></td>
                        <td data-ora="<?= $campo['CampoCampionato']; ?>"><?= $campo['Ora']; ?></td>
                        <td data-opzioni="<?= $campo['CampoCampionato']; ?>">
                            <a href="javascript:;" data-edit="<?= $campo['CampoCampionato']; ?>" class="CampoEdit"><img src="/img/timmyshare/icon_edit.png" /></a>
                            <a href="javascript:;" data-delete="<?= $campo['CampoCampionato']; ?>" class="CampoDelete"><img src="/img/timmyshare/icon_delete.png" /></a>
                        </td>

                    </tr>

                <? endforeach; ?>       

            </table>
            <div class="girone_add" style="background-color: #DDDFEC; width: 600px; height: 50px; border-radius: 5px; padding: 10px;">
                <div class="description input text">
                    <?= $this->Form->input('Campionato', array('type' => 'hidden', 'value' => $this->data['Campionati']['Campionato'])); ?>
                    <?= $this->Form->input('CampoCampionato', array('type' => 'hidden')); ?>
                    <?= $this->Form->input('CampoSearch', array('label' => 'Campo', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchCampo', 'data-dest' => 'CampionatiCampo')); ?>
                    <?= $this->Form->input('Campo', array('type' => 'hidden', 'div' => false)); ?>
                    <div class="error_campo error-message"></div>
                </div>
                <div class="number input text">
                    <?
                    $options = array();
                    $options['Lunedì'] = 'Lunedì';
                    $options['Martedì'] = 'Martedì';
                    $options['Mercoledi'] = 'Mercoledi';
                    $options['Giovedì'] = 'Giovedì';
                    $options['Venerdì'] = 'Venerdì';
                    $options['Sabato'] = 'Sabato';
                    $options['Domenica'] = 'Domenica';
                    ?>
                    <?= $this->Form->input('Giorno', array('label' => 'Giorno', 'type' => 'select', 'options' => $options, 'div' => false, 'empty' => true)); ?>
                    <div class="error_giorno error-message"></div>
                </div>
                <div class="date input text">
                    <?= $this->Form->input('Ora', array('label' => 'Ora', 'class' => 'control_ora', 'div' => false)); ?>
                    <div class="error_ora error-message"></div>
                </div>
                <div class="button input">
                    <label>&nbsp;</label>
                    <?= $this->Form->submit('Aggiungi campo', array('type' => 'button', 'class' => 'CampoAdd', 'id' => 'ButtonCampoAdd', 'div' => false)); ?>
                    <a style="display: none;" href="javascript:;" class="reset_edit_campo"><img src="/img/timmyshare/icon_reset_quick_search.png" /></a>
                </div>
            </div>

        </div>

        <div class="tab-page" data-index="3">

            <script type="text/javascript">
            
            $(function ()
            {

                $('#table_form tr').die('click').live('click', function ()
        {

                var me = $(this);
                me.css('background-color', '#A7F993');
                $('#table_form').find('tr').not(me).css('background', '');
            });
                });
                </script>

        <h3> Gironi                    </h3>

                    <table class="form_table form_table_full" id="table_form">
                            <tr class="append_gironi">
                                <th>Descrizione</th>
                                <th>Nr Squadre</th>
                                <th>Data inizio</th>
                                <th>Opzioni</th>
                            </tr>

                            <? foreach ($halfs as $half): ?>

                                <tr class="girone-delete" data-id="<?= $half['GironeCampionato']; ?>">

                                    <td data-descrizione="<?= $half['GironeCampionato']; ?>"><?= $half['Descrizione']; ?></td>
                                    <td data-squadre="<?= $half['GironeCampionato']; ?>"><?= $half['NumeroSquadre']; ?></td>
                                    <td data-date="<?= $half['GironeCampionato']; ?>"><?= $half['DataInizio_it']; ?></td>
                                    <td data-opzioni="<?= $half['GironeCampionato']; ?>">
                                        <a href="javascript:;" data-edit="<?= $half['GironeCampionato']; ?>" class="GironeEdit"><img src="/img/timmyshare/icon_edit.png" /></a>
                                        <a href="javascript:;" data-associa-squadra="<?= $half['GironeCampionato']; ?>" class="GironeAssociaSquadra"><img src="/img/timmyshare/icon_group.png" /></a>
                                        <a href="javascript:;" data-delete="<?= $half['GironeCampionato']; ?>" class="GironeDelete"><img src="/img/timmyshare/icon_delete.png" /></a>
                                    </td>

                                </tr>

                            <? endforeach; ?> 

                        </table>

                        <div class="girone_add">
                            <div class="description input text">
                                <?= $this->Form->input('GironeCampionato', array('type' => 'hidden')); ?>
                                <?= $this->Form->input('Campionato', array('type' => 'hidden', 'value' => $this->data['Campionati']['Campionato'])); ?>
                                <?= $this->Form->input('Descrizione', array('label' => 'Descrizione', 'div' => false)); ?>
                                <div class="error_description error-message"></div>
                            </div>
                            <div class="number input text">
                                <?= $this->Form->input('NumeroSquadre', array('label' => 'Nr. Squadre', 'div' => false)); ?>
                                <div class="error_team error-message"></div>
                            </div>
                            <div class="date input text">
                                <?= $this->Form->input('DataInizio', array('label' => 'Data Inizio', 'class' => 'datePicker', 'div' => false)); ?>
                                <div class="error_date error-message"></div>
                            </div>
                            <div class="button input">
                                <label>&nbsp;</label>
                                <?= $this->Form->submit('Aggiungi girone', array('type' => 'button', 'class' => 'GironeAdd', 'id' => 'ButtonGironeAdd', 'div' => false)); ?>
                                <a style="display: none;" href="javascript:;" class="reset_edit"><img src="/img/timmyshare/icon_reset_quick_search.png" /></a>
                            </div>
                        </div>
                </div>

                <div class="tab-page" data-index="4">

                    <script type="text/javascript">
                    if (typeof $ != "undefined")
                    
                {
                        $(function ()
                        {

                            function infoGare(girone, opt)
                            {

                                var tip_gare = $('.tip_gare');
                                //var div_input  = $('<div>').addClass('input').addClass('select').addClass('gare_opt');
                                //var div_input1 = div_input.clone();
                                //var div_clear  = $('<div>').addClass('clear').addClass('gare_opt');
                                var div_clear1 = $('<div>').addClass('clear').addClass('gare_opt');
                                var div_clear2 = $('<div>').addClass('clear').addClass('gare_opt');
                                $.get('/admin/campionatis/gareFinalStage/' + girone, function (ret)
                                {

                                    for (var key in ret)
                                    {

                                        $("#FinalStageGaraCasa").append($('<option>').attr('value', key).text(ret[key]));
                                        $("#FinalStageGaraTrasferta").append($('<option>').attr('value', key).text(ret[key]));
                                }

                                tip_gare.append(div_clear1);

                                /*Destinazione*/

                                var destinazione = $('<input type="text" />').addClass('finalGare')
                                        .attr('id', 'FinalStageDestinazione')
                                        .val('A');
                                var div_destinazione = $('<div>').addClass('input').addClass('text').addClass('gare_opt').append(destinazione);
                                var label_destinazione = $('<label>').attr('for', 'FinalStageDestinazione').text('Girone destinazione');
                                div_destinazione.prepend(label_destinazione);
                                tip_gare.append(div_destinazione);
                                tip_gare.append($('<div>').addClass('clear').addClass('gare_opt'));
                                /*fineDestinazione*/

                                var submit = $('<input type="button" />').addClass('FinalStageSubmitGare').attr('value', 'Aggiungi').attr('id', 'FinalStageSubmitGare');
                                var div_submit = $('<div>').addClass('gare_opt').addClass('input').addClass('button').append(submit);
                                tip_gare.append(div_submit);
                                if (opt != undefined)
                                {

                                    $('.tip_gare').prepend($('<input type="hidden" />').addClass('finalStage').addClass('gare_opt').attr('id', 'FinalStageId').attr('name', 'data[FinalStage][Id]').val(opt.ID));
                                    for (field in opt)
                                    {

                                        var obj = $("#" + field);
                                        obj.val(opt[field]);
                                    }

                                    var div_reset = $('<div>').addClass('input').addClass('gare_opt');
                                    div_reset.append($('<input type="button" />').addClass('resetEditGare').val('Annulla'));
                                    $('.tip_gare').append(div_reset);
                                }

                            }, 'json');
                        }

                        function infoHalf(opt)
                        {

                            $.get('/admin/campionatis/infoHalf/' + $("#CampionatiCampionato").val() + '/' + $("#FinalStageGironeCasa").val() + '/' + $("#FinalStageGironeTrasferta").val(), function (ret)
                            {

                                var div_casa = $('<div>').addClass('input')
                                        .addClass('select')
                                        .addClass('trasferta')
                                        .append($("<select>").attr('id', 'FinalStagePosizioneCasa').addClass('finalStage'));
                                $('.tip_gironi').append(div_casa);
                                div_casa.prepend($('<label>').attr('for', 'FinalStagePosizioneCasa').text('Classificata girone casa'));
                                for (i = 1; i <= ret.casa; i++)
                                {

                                    $("#FinalStagePosizioneCasa").append($('<option>').attr('value', i).text(i));
                                }

                                var div_trasferta = $('<div>').addClass('input')
                                        .addClass('select')
                                        .addClass('trasferta')
                                        .append($("<select>").attr('id', 'FinalStagePosizioneTrasferta').addClass('finalStage'));
                                $('.tip_gironi').append(div_trasferta);
                                div_trasferta.prepend($('<label>').attr('for', 'FinalStagePosizioneTrasferta').text('Classificata girone trasferta'));
                                for (i = 1; i <= ret.trasferta; i++)
                                {

                                    $("#FinalStagePosizioneTrasferta").append($('<option>').attr('value', i).text(i));
                                }

                                $('.tip_gironi').append($('<div>').addClass('clear').addClass('trasferta'));


                                /*Destinazione*/

                                var destinazione = $('<input type="text" />').addClass('finalStage')
                                        .attr('id', 'FinalStageDestinazione')
                                        .val('A');
                                var div_destinazione = $('<div>').addClass('input').addClass('text').addClass('trasferta').append(destinazione);
                                var label_destinazione = $('<label>').attr('for', 'FinalStageDestinazione').text('Girone destinazione');
                                div_destinazione.prepend(label_destinazione);
                                $('.tip_gironi').append(div_destinazione);
                                $('.tip_gironi').append($('<div>').addClass('clear').addClass('trasferta'));
                                /*fineDestinazione*/

                                var submit = $('<input type="button" />').addClass('FinalStageSubmit').attr('value', 'Aggiungi').attr('id', 'FinalStageSubmit');
                                var div_submit = $('<div>').addClass('trasferta').addClass('input').addClass('button').append(submit);
                                $('.tip_gironi').append(div_submit);
                                if (opt != undefined)
                                {

                                    $('.tip_gironi').prepend($('<input type="hidden" />').addClass('finalStage').addClass('trasferta').attr('id', 'FinalStageId').attr('name', 'data[FinalStage][Id]').val(opt.ID));
                                    for (field in opt)
                                    {

                                        var obj = $("#" + field);
                                        obj.val(opt[field]);
                                    }

                                    var div_reset = $('<div>').addClass('input').addClass('trasferta');
                                    div_reset.append($('<input type="button" />').addClass('resetEditGironi').val('Annulla'));
                                    $('.tip_gironi').append(div_reset);
                                }

                            }, 'json');
                        }

                        // Radio option 

                        var element_gironi = $("#FinalStageGironi").find('tr[data-id]').length;
                        var element_gare = $("#FinalStageGare").find('tr[data-id]').length;
                        if (element_gironi > 0)
                        {

                            $(".tip_option[value='gironi']").attr('checked', 'checked');
                            cheing($(".tip_option[value='gironi']"));
                        }
                        else if (element_gare > 0)
                        {

                            $(".tip_option[value='gare']").attr('checked', 'checked');
                            cheing($(".tip_option[value='gare']"));
                        }
                        $('.formAdd').delegate('#FinalStageOptionGare', 'click', function ()
                        {

                            var element_gironi = $("#FinalStageGironi").find('tr[data-id]').length;
                            if (element_gironi > 0)
                            {

                                alert('Impossibile creare fasi finali con questo criterio');
                                return false;
                            }

                        });
                        $('.formAdd').delegate('#FinalStageOptionGironi', 'click', function ()
                        {

                            //var nGironi = $("#table_form tr.girone-delete").length;

                            var element_gare = $("#FinalStageGare").find('tr[data-id]').length;
                            if (element_gare > 0)
                            {

                                alert('Impossibile creare fasi finali con questo criterio');
                                return false;
                            }

                        });
                        //Gironi

                        function cheing(obj)
                        {

                            if (obj == undefined)
                                var option = $(this).val();
                            else
                                var option = $(obj).val();
                            $('.option').hide();
                            $('.opt_' + option).show();
                        }

                        $('.formAdd').delegate('.tip_option', 'change', function ()
                        {

                            cheing(this);
                        });
                        $('.formAdd').delegate('#FinalStageGironeCasa', 'change', function ()
                        {

                            var value = $(this).val();
                            var campionato = $("#CampionatiCampionato").val();
                            var obj = $(this);
                            $('.trasferta').remove();
                            var select = $('.tip_gironi').children('.select').clone().addClass('trasferta');
                            select.children('label').attr('for', 'FinalStageGironeTrasferta');
                            select.children('label').text('Girone trasferta');
                            select.children('select').attr('id', 'FinalStageGironeTrasferta').addClass('finalStage');
                            //select.find('option[value='+ value +']').remove();

                            select.insertAfter(obj.parent('.select'));
                            $('.tip_gironi').append($('<div>').addClass('clear').addClass('trasferta'));
                        });
                        $('.formAdd').delegate('#FinalStageGironeTrasferta', 'change', function ()
                        {

                            infoHalf();
                        });
                        $('.formAdd').delegate('#FinalStageSubmit', 'click', function ()
                        {

                            var data = 'Campionato=' + $("#CampionatiCampionato").val() + '&';
                            $('.finalStage').each(function (index)
                            {

                                var id = $(this).attr('id').replace('FinalStage', '');
                                data += id + '=' + $(this).val() + '&';
                            });
                            if ($("#FinalStageId").val() != undefined)
                            {

                                data += "id=" + $("#FinalStageId").val() + "&";
                            }

                            $.post('/admin/campionatis/addFinalStage', data, function (ret)
                            {

                                if ($("#FinalStageId").val() != undefined)
                                {

                                    $("#FinalStageGironi").find('tr[data-id=' + $("#FinalStageId").val() + ']').remove();
                                }

                                var tr = ["NomeGironeCasa", "PosizioneCasa", "NomeGironeTrasferta", "PosizioneTrasferta", "Destinazione"];
                                var new_tr = $('<tr>').attr('data-id', ret.save.FinalStage["id"]);
                                $("#FinalStageGironi").append(new_tr);
                                $('.table_gironi').show();
                                for (i = 0; i < tr.length; i++)
                                {

                                    new_tr.append($('<td>').text(ret.save.FinalStage[tr[i]]));
                                }

                                var bt_edit = $('<a>').addClass('finalEdit').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_edit.png'));
                                var bt_delete = $('<a>').addClass('finalDelete').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_delete.png'));
                                new_tr.append($('<td>').append(bt_edit).append('&nbsp;').append(bt_delete));
                                $("#FinalStageGironeCasa").trigger('change');
                            }, 'json');
                        });
                        $('.formAdd').delegate('.resetEditGironi', 'click', function ()
                        {

                            $("#FinalStageGironeCasa").change();
                        });
                        $('.formAdd').delegate('.resetEditGare', 'click', function ()
                        {

                            $("#FinalStageGirone").val('');
                            $('.gare_opt').remove();
                        });
                        $('.formAdd').delegate('.finalEdit', 'click', function ()
                        {

                            var obj = $(this);
                            var delete_id = $(this).parents('tr').attr('data-id');
                            $.get('/admin/campionatis/editFinalStage/' + delete_id, function (data)
                            {

                                if (data.a_gare != '')
                                {

                                    $("#FinalStageGirone").val(data.a_gare.FinalStageGirone);
                                    $("#FinalStageGirone").trigger('change', [data.a_gare]);
                                    //infoGare(data.a_gare.FinalStageGirone, data.a_gare);

                                }
                                else
                                {

                                    for (field in data.a_gironi)
                                    {

                                        var obj = $("#" + field);
                                        obj.val(data.a_gironi[field]);
                                        if (field != 'FinalStageGironeTrasferta')
                                        {

                                            obj.change();
                                        }
                                        else
                                        {

                                            infoHalf(data.a_gironi);
                                            return false;
                                        }

                                    }

                                }

                            }, 'json');
                        });
                        $('.formAdd').delegate('.finalDelete', 'click', function ()
                        {

                            var obj = $(this);
                            var delete_id = $(this).parents('tr').attr('data-id');
                            if (confirm('Sei sicuro di voler eliminare?'))
                            {

                                $.get('/admin/campionatis/deleteFinalStage/' + delete_id, function (ret)
                                {

                                    if (ret.delete == 1)
                                    {

                                        alert('Record eliminato con successo.');
                                        obj.parents('tr').remove();
                                    }
                                    else
                                    {

                                        alert('Impossibile eliminare');
                                    }

                                }, 'json');
                            }

                        });
                        //Gare

                        $('.formAdd').delegate('#FinalStageGirone', 'change', function (e, opt)
                        {

                            $('.gare_opt').remove();
                            var girone = $(this).val();
                            var tip_gare = $('.tip_gare');
                            var div_input = $('<div>').addClass('input').addClass('select').addClass('gare_opt');
                            var div_input1 = div_input.clone();
                            var div_clear = $('<div>').addClass('clear').addClass('gare_opt');
                            var div_clear1 = $('<div>').addClass('clear').addClass('gare_opt');
                            var div_clear2 = $('<div>').addClass('clear').addClass('gare_opt');
                            tip_gare.append(div_clear);
                            tip_gare.append(div_input.append($('<select>').addClass('finalGare').attr('id', 'FinalStageGaraCasa')));
                            tip_gare.append(div_input1.append($('<select>').addClass('finalGare').attr('id', 'FinalStageGaraTrasferta')));
                            $("#FinalStageGaraCasa").parent('div').prepend($('<label>').attr('for', 'FinalStageGaraCasa').text('Vincente prima gara'));
                            $("#FinalStageGaraTrasferta").parent('div').prepend($('<label>').attr('for', 'FinalStageGaraTrasferta').text('Vincente seconda gara'));
                            infoGare(girone, opt);
                        });
                        $('.formAdd').delegate('#FinalStageSubmitGare', 'click', function ()
                        {

                            var data = 'Campionato=' + $("#CampionatiCampionato").val() + '&';
                            $('.finalGare').each(function (index)
                            {

                                var id = $(this).attr('id').replace('FinalStage', '');
                                data += id + '=' + $(this).val() + '&';
                            });
                            if ($("#FinalStageId").val() != undefined)
                            {

                                data += "id=" + $("#FinalStageId").val() + "&";
                            }

                            $.post('/admin/campionatis/addFinalStage', data, function (ret)
                            {

                                if ($("#FinalStageId").val() != undefined)
                                {

                                    $("#FinalStageGare").find('tr[data-id=' + $("#FinalStageId").val() + ']').remove();
                                }

                                var tr = ["NomeGirone", "NomeGaraCasa", "NomeGaraTrasferta", "Destinazione"];
                                var new_tr = $('<tr>').attr('data-id', ret.save.FinalStage["id"]);
                                $("#FinalStageGare").append(new_tr);
                                for (i = 0; i < tr.length; i++)
                                {

                                    new_tr.append($('<td>').text(ret.save.FinalStage[tr[i]]));
                                }

                                var bt_delete = $('<a>').addClass('finalDelete').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_delete.png'));
                                var bt_edit = $('<a>').addClass('finalEdit').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_edit.png'));
                                new_tr.append($('<td>').append(bt_edit).append('&nbsp;').append(bt_delete));
                                $('.gare_opt').remove();
                                $("#FinalStageGirone").val('');
                            }, 'json');
                        });
                    });
                    }
        
                        </script>   

                        <?=
                        $this->Form->input('FinalStage.option', array(
                            'type' => 'radio',
                            'legend' => 'Modalit\E0 partita',
                            'options' => array(
                                'gironi' => 'Gironi',
                                'gare' => 'Gare',
                            ),
                            'class' => 'tip_option',
                        ));
                        ?>

                        <div class="clear"></div>

                        <div class="opt_gironi option">
                            <div class="tip_gironi">

                                <?= $this->Form->input('FinalStage.GironeCasa', array('type' => 'select', 'class' => 'finalStage', 'label' => 'Girone casa', 'options' => $gironi, 'empty' => 'Scegli un girone...')); ?>

                            </div>
                            <div class="clear"></div>

                            <div class="table_gironi">      
                                <table id="FinalStageGironi" class="form_table form_table_full">
                                    <tr>
                                        <th>Girone casa</th>
                                        <th>Classificata girone casa</th>
                                        <th>Girone trasferta</th>
                                        <th>Classificata girone trasferta</th>
                                        <th>Girone destinazione</th>
                                        <th>Opzioni</th>
                                    </tr>
                                    <? if (count($finals)): ?>
                                        <? foreach ($finals as $final): ?>
                                            <? if ($final['FinalStage']['GironeCasa'] != 0 && $final['FinalStage']['GironeTrasferta'] != 0): ?>
                                                <tr data-id="<?= $final['FinalStage']['id']; ?>">
                                                    <td><?= $final['FinalStage']['NomeGironeCasa']; ?></td>
                                                    <td><?= $final['FinalStage']['PosizioneCasa']; ?></td>
                                                    <td><?= $final['FinalStage']['NomeGironeTrasferta']; ?></td>
                                                    <td><?= $final['FinalStage']['PosizioneTrasferta']; ?></td>
                                                    <td><?= $final['FinalStage']['Destinazione']; ?></td>
                                                    <td>
                                                        <a href="javascript:;" class="finalEdit">
                                                            <img src="/img/timmyshare/icon_edit.png" />
                                                        </a>                                
                                                        <a href="javascript:;" class="finalDelete">
                                                            <img src="/img/timmyshare/icon_delete.png" />
                                                        </a>                                
                                                    </td>                       
                                                </tr>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                    <? endif; ?>
                                </table>            
                            </div>
                        </div>
                        <div class="opt_gare option">
                            <div class="tip_gare">

                                <?= $this->Form->input('FinalStage.Girone', array('type' => 'select', 'class' => 'finalGare', 'label' => 'Girone', 'options' => $gironi, 'empty' => 'Scegli un girone...')); ?>

                            </div>
                            <div class="clear"></div>

                            <div class="table_gare">        
                                <table id="FinalStageGare" class="form_table form_table_full">
                                    <tr>
                                        <th>Girone</th>
                                        <th>Prima gara</th>
                                        <th>Seconda gara</th>
                                        <th>Girone destinazione</th>
                                        <th>Opzioni</th>
                                    </tr>
                                    <? if (count($finals)): ?>
                                        <? foreach ($finals as $final): ?>
                                            <? if ($final['FinalStage']['Girone'] != 0): ?>
                                                <tr data-id="<?= $final['FinalStage']['id']; ?>">
                                                    <td><?= $final['FinalStage']['NomeGirone']; ?></td>
                                                    <td><?= $final['FinalStage']['NomeGaraCasa']; ?></td>
                                                    <td><?= $final['FinalStage']['NomeGaraTrasferta']; ?></td>
                                                    <td><?= $final['FinalStage']['Destinazione']; ?></td>
                                                    <td>
                                                        <a href="javascript:;" class="finalEdit">
                                                            <img src="/img/timmyshare/icon_edit.png" />
                                                        </a>                            
                                                        <a href="javascript:;" class="finalDelete">
                                                            <img src="/img/timmyshare/icon_delete.png" />
                                                        </a>                                
                                                    </td>                       
                                                </tr>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                    <? endif; ?>
                                </table>            
                            </div>          
                        </div>

                    </div>  


                    <div class="tab-page" data-index="5">

                        <?= $this->Form->input('SquadraCampionato_id', array('type' => 'select', 'empty' => 'Seleziona una squadra...', 'options' => $squadre)); ?>

                    </div>


                    <?= $this->Form->end(); ?>

                </div>
