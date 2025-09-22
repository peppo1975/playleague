<?
//GIUSEPPE  18/10/2016 -> filtra la classe

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];
$type_sport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");
?>
<link rel="stylesheet" type="text/css" href="/porto_admin/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<!--//GIUSEPPE -> libreria per data -->
<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>

<style type="text/css">
    /*
    select {
    
    border: 1px solid #ccc;
    padding: 5px;
    font-size: 12px;
    width: 310px;
    
    }
    
    textarea {
    
    width: 768px;
    }
    */

    .select2-container { width: 100% !important; }


    .resp {
        margin-top: 10px;
        padding-bottom: 10px;
        border-bottom:  1px dotted #ccc;

    }



    .resp .input {

        margin-bottom: 10px;
    }
    .resp .comune {

        width: 170px;
    }
    .resp .pv {

        width: 30px;
    }
</style>

<? if (!$this->Session->read('Login.data')): ?>


    <div style="" class="alert alert-danger text-center">

        Effettua il login per accedere alla funzione iscrizioni.

    </div>



<? else: ?>

    <script type="text/javascript">

        var form_obj = {};

        var obj_i = {};

        var sport = {"CALCIO": 0, "TENNIS": 1};

        $(document).ready(function ()
        {

            $(".esistente").hide();

            tipo_sport = sport.<?= $type_sport[$nameClass] ?>; // lo rendo enumerativo, in modo da evitare il confronto tra stringhe

            if (tipo_sport == sport.TENNIS)
            {
                $("#resp_1").hide();
                $("#resp_2").hide();
            }

            var campionati_json = <?= json_encode($campionatijson); ?>;
            var ret_campi = <?= json_encode($campij); ?>;
            var giorni = <?= json_encode($giorni); ?>;
            var prefill = <?= json_encode($prefill); ?>;

            var row;

            for (var z = 0; z < prefill.length; z++)
            {

                row = prefill[z];

                if ($("#" + row.key).length > 0)
                    $("#" + row.key).val(row.value);

            }


            $(".input.required").each(function ()
            {

                $(this).find('input:visible, select:visible').attr('required', 'required');
                $(this).find('input:hidden, select:hidden').attr('required', '');

            });




            setInterval(function ()
            {


                $(".input.required").each(function ()
                {
                    $(this).find('input:hidden, select:hidden').attr('required', '');
                    $(this).find('input:visible, select:visible').attr('required', 'required');
                });

            }, 2000);

            function resetAll()
            {

                switch (tipo_sport)
                {
                    case sport.CALCIO:
                        $("#SubscriptionGirone").html('<option value="">Seleziona prima un campionato...</option>');
                        $("#SubscriptionCampo").html('<option value="">Seleziona prima un girone...</option>');
                        $("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un campo...</option>');
                        $("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;

                    case sport.TENNIS:
                        $("#SubscriptionGirone").html('<option value="">Seleziona prima un campionato...</option>');
                        //$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;

                }


            }

            function resetAll2()
            {
                switch (tipo_sport)
                {
                    case sport.CALCIO:
                        $("#SubscriptionCampo").html('<option value="">Seleziona prima un girone...</option>');
                        $("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un campo...</option>');
                        $("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;

                    case sport.TENNIS:
                        //$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;
                }


            }
            function resetAll3()
            {
                switch (tipo_sport)
                {
                    case sport.CALCIO:
                        $("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un impianto...</option>');
                        $("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;

                    case sport.TENNIS:
                        //$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');
                        break;
                }



            }
            function resetAll4()
            {


                $("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');

            }


            function fillCampi(girone_id)
            {
                var campionato_id = $("#SubscriptionCampionato").val();
                if (girone_id == "" || girone_id == 0)
                {

                    //resetAll2();
                    //return;


                }

                resetAll2();

                if (tipo_sport == sport.CALCIO)
                {
                    $("#SubscriptionCampo").html('<option value="" selected>Seleziona impianto</option>');


                    for (var i = 0; i < campionati_json[campionato_id][girone_id].Campo.length - 1; i++)
                    {

                        if ($("#SubscriptionCampo").find('option[value=' + campionati_json[campionato_id][girone_id].Campo[i] + ']').length == 0)
                        {

                            $("#SubscriptionCampo").append('<option value="' + campionati_json[campionato_id][girone_id].Campo[i] + '">' + ret_campi[campionati_json[campionato_id][girone_id].Campo[i]] + '</option>');

                        }

                    }
                }

            }

            function fillGiorni(campo_id)
            {
                if (tipo_sport == sport.CALCIO)
                {
                    if (campo_id == "" || campo_id == 0)
                    {

                        resetAll3();
                        return;


                    }

                    //console.log(campionati_json[campionato_id]);
                    $("#SubscriptionGiorno").html('<option value="" selected>Seleziona giorno</option>');

                    var campionato_id = $("#SubscriptionCampionato").val();

                    var girone_id = $("#SubscriptionGirone").val();

                    var campo_id = $("#SubscriptionCampo").val();

                    for (var i = 0; i < campionati_json[campionato_id][girone_id].Giorno.length - 1; i++)
                    {
                        if (campionati_json[campionato_id][girone_id].Campo[i] == campo_id && $("#SubscriptionGiorno").find('option[value=' + campionati_json[campionato_id][girone_id].Giorno[i] + ']').length == 0)
                        {
                            $("#SubscriptionGiorno").append('<option value="' + campionati_json[campionato_id][girone_id].Giorno[i] + '">' + giorni[campionati_json[campionato_id][girone_id].Giorno[i]] + '</option>');
                        }
                    }
                }
            }

            function fillOra(giorno_id)
            {

                if (tipo_sport == sport.CALCIO)
                {
                    if (giorno_id == "" || giorno_id == 0)
                    {

                        resetAll4();
                        return;


                    }

                    resetAll4();

                    $("#SubscriptionOra").html('<option value="" selected>Seleziona Ora</option>');

                    var campionato_id = $("#SubscriptionCampionato").val();
                    var girone_id = $("#SubscriptionGirone").val();
                    var campo_id = $("#SubscriptionCampo").val();

                    for (var i = 0; i < campionati_json[campionato_id][girone_id].Orario.length - 1; i++)
                    {



                        if (campionati_json[campionato_id][girone_id].Giorno[i] == giorno_id && campionati_json[campionato_id][girone_id].Campo[i] == campo_id && $("#SubscriptionOra").find('option[value="' + campionati_json[campionato_id][girone_id].Orario[i] + '"]').length == 0)
                        {

                            $("#SubscriptionOra").append('<option value="' + campionati_json[campionato_id][girone_id].Orario[i] + '">' + campionati_json[campionato_id][girone_id].Orario[i] + '</option>');

                        }

                    }
                }


            }

            function fillGironi(campionato_id)
            {

                if (campionato_id == "" || campionato_id == 0)
                {

                    resetAll();
                    return;
                }

                /*$("#SubscriptionGirone").html('<option value="">Seleziona girone</option>');
                     
                 for (var i = 0; i < campionati_json[campionato_id].gironi.length; i++)
                 {
                     
                 $("#SubscriptionGirone").append('<option value="' + campionati_json[campionato_id].gironi[i].id + '">' + campionati_json[campionato_id].gironi[i].nome + '</option>');
                     
                 }*/


                resetAll();


                $("#SubscriptionGirone  option").remove();

                var stringGirone = '<option value="0">Seleziona girone</option>'

                for (var i = 0; i < campionati_json[campionato_id].gironi.length; i++)
                {

                    //$("#SubscriptionGirone").append('<option value="' + campionati_json[campionato_id].gironi[i].id + '">' + campionati_json[campionato_id].gironi[i].nome + '</option>');

                    stringGirone += '<option value="' + campionati_json[campionato_id].gironi[i].id + '">' + campionati_json[campionato_id].gironi[i].nome + '</option>';
                }

                $("#SubscriptionGirone").append(stringGirone);

            }

            $("#SubscriptionCampionato").change(function ()
            {

                fillGironi($(this).val());

            });
            $("#SubscriptionGirone").change(function ()
            {
                //GIUSEPPE
                resetAll2();

                fillCampi($(this).val());

            });

            $("#SubscriptionCampo").change(function ()
            {

                fillGiorni($(this).val());

            });

            $("#SubscriptionGiorno").change(function ()
            {

                fillOra($(this).val());

            });

            $("#SubscriptionSelezione").change(function ()
            {


                if ($(this).val() == 0)
                {

                    $(".nuovasquadra").show();
                    $(".esistente").hide();
                    //$(".esistente2").hide();
                }
                else if ($(this).val() == 1)
                {

                    $(".nuovasquadra").hide();
                    $(".esistente").show();
                }
            });

        });



        $(function ()
        {
            $('.autocomplete_resp').on('keyup.autocomplete focus.autocomplete', function ()
            {
                console.log("ok");

                var fields = ["Nome", "Cognome", "DataNascita_it"];

                var url = $(this).attr('data-url');
                var dest = $(this).attr('data-dest');

                var me = $(this);

                $(this).autocomplete({source: url,
                    minLength: 0,
                    delay: 50,
                    search: function ()
                    {

                        $('body').find('#' + dest).removeAttr('value');

                        $('body').find('#' + dest).trigger('change');

                        timmyloader('show');

                    },
                    open: function ()
                    {
                        timmyloader('hide');
                    },
                    change: function (event, ui)
                    {
                        // if(!ui.item && selected)
                        // {
                        // 	$(".ats .form-group").css("display", "block");
                        // 	$(".ats input[type=text]:not(#Nome)").val("");
                        // 	selected = false;
                        // }
                    },
                    select: function (event, ui)
                    {
                        //GIUSEPPE -----------------------------------------------------------------------------------------------

                        // selected = true;
                        var form = me.closest('form');

                        $.get('/subscriptions/atleta/' + ui.item.id, function (ret)
                        {

                            if (form_obj['id_responsabile_0'] == ret["Atleta"] || form_obj['id_responsabile_1'] == ret["Atleta"] || form_obj['id_responsabile_2'] == ret["Atleta"])
                            {
                                alert("Responsabile gia selezionato");
                                form.find('#Cognome_' + ui.item.id_input).val("");
                                return;
                            }

                            for (key in ret)
                            {

                                form_obj["id_responsabile_" + ui.item.id_input] = ret["Atleta"];

                                if (key == 'DataNascita_it')
                                {
                                    var born = ret[key];

                                    born = born.replace(".", "/");

                                    born = born.replace(".", "/"); // se ne metto solo un cambia, esegue il replace solo su un "."

                                    ret[key] = born;

                                }

                                form.find('#' + key + '_' + ui.item.id_input).val(ret[key]);

                                if (fields.indexOf(key) == -1 && (ret[key] != "" && ret[key] != null))
                                {
                                    form.find('#' + key + '_' + ui.item.id_input).attr('type', 'password');

                                }

                                if (form.find('#' + key + '_' + ui.item.id_input).val() != "")
                                {
                                    //form.find('#' + key).attr('readonly','readonly');
                                    form.find('#' + key + '_' + ui.item.id_input).prop('disabled', true);

                                }

                            }

                        }, 'json');

                        //--------------------------------------------------------------------------------------------------------


                    }

                });

            });
        });

        //GIUSEPPE ------------------------------------
        function reset_text_box(id_resp)
        {

            var id_box = "_" + id_resp.toString();

            var keys = Object.keys(form_obj);

            form_obj["id_responsabile_" + id_resp] = "NEW";

            for (i in keys)
            {

                var str_str = keys[i].indexOf(id_box);

                if (str_str >= 0)
                {
                    $("#" + keys[i]).prop('disabled', false);

                    $("#" + keys[i]).val("");

                    $("#" + keys[i]).attr('type', 'text');
                }
            }


        }


        function hide_resp(id_resp)
        {
            $("#resp_" + id_resp).hide();

            $("#add_resp").show();
        }
        //---------------------------------------------



    </script>
    <!--
    <?=
    $this->Form->create('Subscription', array('url' => '/subscriptions/tesseramenti?step=3', 'id' => 'subForm', 'autocomplete' => 'off',
        'class' => 'form-horizontal',
        'inputDefaults' => array(
            'format' => array('before', 'between', 'label',
                'input', 'error', 'after'),
            'class' => 'form-control',
            'div' => array('class' => 'form-group'),
            'label' => array('class' => 'control-label'),
            'between' => '<div class="col-lg-12">',
            'after' => '</div>',
            'error' => array('attributes' => array('wrap' => 'span',
                    'class' => 'text-danger'
    )))));
    ?>
    -->
    <?
    //$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
    //$nameClass = $classPage["Name"];


    switch ($nameClass)
    {
        case "primary":
            require_once("add_calcio.ctp");
            break;


        case "secondary":
            require_once("add_calcio.ctp");
            break;

        case "quaternary":
            require_once("add_tennis.ctp");
            break;
    }
    ?>


    <div class="alert alert-info">

        Il Presidente e tutto il Consiglio Direttivo (vice presidente e segretario) della Società in predicato, che si propone come partecipante alla suddetta manifestazione, dichiarano a nome
        di tutti i componenti della squadra di: <br />
        <ol>
            <li>accettare lo statuto e tutti i regolamenti MIDLAND per la stagione sportiva in predicato; </li>
            <li>di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle
                manifestazioni” consegnato al momento dell’iscrizione; </li>
            <li>che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva; </li>
            <li>che tutti i tesserati della squadra sono in possesso della certificazione medica valida per il periodo di svolgimento della manifestazione sportiva MIDLAND.</li> 
        </ol>
        Il Presidente e tutto il Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro
        ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. Eventuali disdette saranno accettate (con restituzione del deposito cauzionale) entro 7 giorni
        dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari) <br />
        * Campi riservati a preferenze, non vincolanti, esprimibili in relazione solo ai Campionati invernali, in relazione alle disponibilità pubblicate <br />
        ** Segnalazioni del tutto non vincolanti atte alla massima miglioria dei servizi di ogni manifestazione


    </p>
    </div>





    <?= $this->Form->end(); ?>

    <script type="text/javascript">

        $(document).ready(function ()
        {

            //GIUSEPPE ----------------------------------------

            var array_resp = <?= json_encode($reponsabili); ?>

            for (i in array_resp)
            {
                $('#DataNascita_it_' + i).mask('00/00/0000');

                $('#ScadenzaDocumento_' + i).mask('00/00/0000');
            }


            var cookie_resp = getCookie('varResp'); // controllo se ci sono cookie (nel caso io torna indietro)

            var id_responsabili = ["id_responsabile_0", "id_responsabile_1", "id_responsabile_2"];

            var content_form = decodeURIComponent($("#cristomorto").serialize()); //leggo i nomi delle finestre e li trasformo in id

            var list_form = content_form.split("&");

            var content_i;

            var name_window = "";

            for (i in list_form)
            {
                content_i = list_form[i].split("=");

                form_obj[compareString(content_i[0])] = content_i[1];

            }


            for (i in id_responsabili)
            {
                form_obj[id_responsabili[i]] = "NEW";
            }





            function getCookie(cname) //cookie
            {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++)
                {
                    var c = ca[i];
                    while (c.charAt(0) == ' ')
                    {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0)
                    {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }




            // -------------------------------------------------

            // check email

            // auto hidden 
            $(".resp-select").change(function ()
            {
                if ($(this).val() == "0")
                {
                    $(this).closest(".panel").find(".exists").css("display", "none");
                    $(this).closest(".panel").find(".not-exists").css("display", "block");

                }
                else
                {
                    $(this).closest(".panel").find(".not-exists").css("display", "none");
                    $(this).closest(".panel").find(".exists").css("display", "block");
                }
            });

            $(".resp-select").change();

            $("input, select").bind('focus', function ()
            {

                $(this).closest('.form-group').removeClass('has-error');

            });


            $("#add_resp").click(function ()
            {
                if ($('#resp_1').is(':hidden'))
                {
                    $('#resp_1').show();
                }
                else if ($('#resp_2').is(':hidden'))
                {
                    $('#resp_2').show();
                    $("#add_resp").hide();
                }

            });


            $("#validate").click(function ()
            {
                //if(tipo_sport==sport.CALCIO)

                switch (tipo_sport)
                {
                    case sport.CALCIO:

                        $.post("/sections/oneSquadr", $("#cristomorto").serialize(), function (data)
                        {
                            data = JSON.parse(data);
                            //if (data.result == true)
                            {
                                $.post("/sections/verifyDisponibilita", $("#cristomorto").serialize(), function (data)
                                {

                                    data = JSON.parse(data);

                                    if (data.result == true)
                                    {
                                        proceed();

                                    }
                                    else
                                    {
                                        alert("Ci dispiace ma la combinazione campo, ora, giorno non è disponibile!");
                                    }
                                });
                            }
                            //else
                            //{
                            //	alert("Ci dispiace una squadra può iscriversi solo una volta!");
                            //}

                        });
                        break;

                    case sport.TENNIS:
                        proceed();
                        break;
                }

            });


            // GIUSEPPE 13/10/2016--------------------------------

            function setCookie(cname, cvalue, exmin) // DA VEDERE IN SEGUITO
            {
                var d = new Date();
                d.setTime(d.getTime() + (exmin * 60 * 1000));
                var expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; " + expires;
            }


            function compareString(text)
            {
                // sostituisce il nome con l'id
                var objToCompare;

                switch (tipo_sport)
                {
                    case sport.CALCIO:

                        objToCompare =
                                {
                                    "data[Subscription][Sesso_0]": "SubscriptionSesso0",
                                    "data[Subscription][Sesso_1]": "SubscriptionSesso1",
                                    "data[Subscription][Sesso_2]": "SubscriptionSesso2",
                                    "data[Subscription][TipoDocumento_0]": "SubscriptionTipoDocumento0",
                                    "data[Subscription][TipoDocumento_1]": "SubscriptionTipoDocumento1",
                                    "data[Subscription][TipoDocumento_2]": "SubscriptionTipoDocumento2",
                                    "data[Subscription][campionato]": "SubscriptionCampionato",
                                    "data[Subscription][campo]": "SubscriptionCampo",
                                    "data[Subscription][giorno]": "SubscriptionGiorno",
                                    "data[Subscription][girone]": "SubscriptionGirone",
                                    "data[Subscription][nomesquadra2]": "SubscriptionNomesquadra2",
                                    "data[Subscription][nomesquadra]": "SubscriptionNomesquadra",
                                    "data[Subscription][ora]": "SubscriptionOra",
                                    "data[Subscription][segnalazioni]": "SubscriptionSegnalazioni",
                                    "data[Subscription][selezione]": "SubscriptionSelezione"
                                }

                        break;



                    case sport.TENNIS:

                        objToCompare =
                                {
                                    "data[Subscription][Sesso_0]": "SubscriptionSesso0",
                                    "data[Subscription][Sesso_1]": "SubscriptionSesso1",
                                    "data[Subscription][Sesso_2]": "SubscriptionSesso2",
                                    "data[Subscription][TipoDocumento_0]": "SubscriptionTipoDocumento0",
                                    "data[Subscription][TipoDocumento_1]": "SubscriptionTipoDocumento1",
                                    "data[Subscription][TipoDocumento_2]": "SubscriptionTipoDocumento2",
                                    "data[Subscription][campionato]": "SubscriptionCampionato",
                                    "data[Subscription][campo]": "SubscriptionCampo",
                                    "data[Subscription][giorno]": "SubscriptionGiorno",
                                    "data[Subscription][nomesquadra2]": "SubscriptionNomesquadra2",
                                    "data[Subscription][nomesquadra]": "SubscriptionNomesquadra",
                                    "data[Subscription][ora]": "SubscriptionOra",
                                    "data[Subscription][segnalazioni]": "SubscriptionSegnalazioni",
                                    "data[Subscription][selezione]": "SubscriptionSelezione"
                                }

                        break;
                }


                var result = objToCompare[text];

                if (result === undefined)
                {
                    result = text;
                }

                return result;
            }

            function dependens(objectJson)
            {

                var selection_squadra = objectJson["SubscriptionSelezione"];

                switch (parseInt(selection_squadra))
                {
                    case 0:
                        delete objectJson["SubscriptionNomesquadra2"];
                        break;

                    case 1:
                        delete objectJson["SubscriptionNomesquadra"];
                        break;
                }


                if (objectJson["SubscriptionSegnalazioni"] == "")
                {
                    delete objectJson["SubscriptionSegnalazioni"];
                }

            }


            function read_text_window()
            {

                form_obj['SubscriptionNomesquadra'] = $("#SubscriptionNomesquadra").val();

                form_obj['SubscriptionNomesquadra2'] = $("#SubscriptionNomesquadra2").val();


                var keys = Object.keys(form_obj); // ho l'elenco di tutte le proprità di form_obj''

                for (i in keys)
                {

                    if ($('#' + keys[i]).val() != undefined)
                    {
                        form_obj[keys[i]] = $('#' + keys[i]).val().toLowerCase();
                    }

                }

                //console.log(form_obj);
            }



            function control_values() // controllo che non ci siano finestre vuote;
            {

                var keys = Object.keys(form_obj);

                var complete_fields = true;


                switch (tipo_sport)
                {


                    case sport.CALCIO:

                        for (i in keys)
                        {
                            var id_textBox = "#" + [keys[i]];

                            $(id_textBox).closest('.form-group').removeClass("has-error");

                            if (form_obj[keys[i]] === "")
                            {
                                complete_fields = false;

                                $(id_textBox).closest('.form-group').addClass("has-error");
                            }

                        }



                        break;




                    case sport.TENNIS:

                        for (i = 0; i < 6; i++) // i primi 6 sono generali
                        {


                            var id_textBox = "#" + [keys[i]];

                            $(id_textBox).closest('.form-group').removeClass("has-error");

                            if (form_obj[keys[i]] === "")
                            {
                                complete_fields = false;

                                $(id_textBox).closest('.form-group').addClass("has-error");
                            }
                        }

                        $(".responsabili").each(
                                function (index)
                                {
                                    if ($(this).is(':visible'))
                                    {
                                        //alert($(this).find("#Cognome_" + index).val());
                                        for (i in keys)
                                        {
                                            var id_textBox = "#" + [keys[i]];
                                            if (id_textBox.endsWith("_" + index))
                                            {
                                                $(id_textBox).closest('.form-group').removeClass("has-error");

                                                if (form_obj[keys[i]] === "")
                                                {
                                                    complete_fields = false;

                                                    $(id_textBox).closest('.form-group').addClass("has-error");
                                                }
                                            }

                                        }
                                    }
                                });

                        break;
                }

                return complete_fields;
            }



            function compare_email()// controllo che non ci siano mail uguali;
            {

                var result = true;

                switch (tipo_sport)
                {
                    case sport.CALCIO:

                        var responsible = ["Presidente", "Vice presidende", "Segretario"];

                        //var result = true;

                        for (i = 0; i < (responsible.length - 1); i++)
                        {
                            for (j = i + 1; j < (responsible.length); j++)
                            {
                                var email_i = form_obj["Email_" + i.toString()];

                                var email_j = form_obj["Email_" + j.toString()];

                                if (email_i.localeCompare(email_j) == 0)
                                {
                                    alert(" - Email " + responsible[i] + "\n - Email " + responsible[j] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }
                            }
                        }
                        break;



                    case sport.TENNIS:

                        var responsible = ["Responsabile 1", "Responsabile 2", "Responsabile 3"];

                        var arr_mail = [];

                        $(".responsabili").each(
                                function (index)
                                {
                                    if ($(this).is(':visible'))
                                    {
                                        arr_mail.push([form_obj["Email_" + index.toString()], index]);
                                    }
                                });

                        switch (arr_mail.length)
                        {
                            case 1:
                                //non ci sono elementi da controllare
                                break;

                            case 2:
                                // il controllo è su due responsabili
                                var email_1 = arr_mail[0][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var email_2 = arr_mail[1][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI

                                if (email_1.localeCompare(email_2) == 0)
                                {
                                    alert(" - Email " + responsible[arr_mail[0][1]] + "\n - Email " + responsible[arr_mail[1][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                break;

                            case 3:
                                // il controllo è su tre responsabili (uso un altro approccio rispotto al caclio)
                                var email_1 = arr_mail[0][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var email_2 = arr_mail[1][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var email_3 = arr_mail[2][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI

                                if (email_1.localeCompare(email_2) == 0) // email_1 email_2
                                {
                                    alert(" - Email " + responsible[arr_mail[0][1]] + "\n - Email " + responsible[arr_mail[1][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                if (email_1.localeCompare(email_3) == 0) // email_1 email_3
                                {
                                    alert(" - Email " + responsible[arr_mail[0][1]] + "\n - Email " + responsible[arr_mail[2][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                if (email_2.localeCompare(email_3) == 0) // email_1 email_3
                                {
                                    alert(" - Email " + responsible[arr_mail[1][1]] + "\n - Email " + responsible[arr_mail[2][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                break;
                        }

                        break;

                }


                return result;
            }


            function compare_CF()// controllo che non ci siano CF uguali;
            {

                var result = true;

                switch (tipo_sport)
                {
                    case sport.CALCIO:

                        var responsible = ["Presidente", "Vice presidende", "Segretario"];

                        for (i = 0; i < (responsible.length - 1); i++)
                        {
                            for (j = i + 1; j < (responsible.length); j++)
                            {
                                var email_i = form_obj["CodiceFiscale_" + i.toString()];

                                var email_j = form_obj["CodiceFiscale_" + j.toString()];

                                if (email_i.localeCompare(email_j) == 0)
                                {
                                    alert("Cod Fiscale " + responsible[i] + " e \nCod Fiscale " + responsible[j] + "\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }
                            }
                        }

                        break;


                    case sport.TENNIS:

                        var responsible = ["Responsabile 1", "Responsabile 2", "Responsabile 3"];

                        var arr_CF = [];

                        $(".responsabili").each(
                                function (index)
                                {
                                    if ($(this).is(':visible'))
                                    {
                                        arr_CF.push([form_obj["CodiceFiscale_" + index.toString()], index]);
                                    }
                                });

                        switch (arr_CF.length)
                        {
                            case 1:
                                //non ci sono elementi da controllare
                                break;

                            case 2:
                                // il controllo è su due responsabili
                                var CF_1 = arr_CF[0][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var CF_2 = arr_CF[1][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI

                                if (CF_1.localeCompare(CF_2) == 0)
                                {
                                    alert(" - Cod Fiscale " + responsible[arr_CF[0][1]] + "\n - Cod Fiscale " + responsible[arr_CF[1][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                break;

                            case 3:
                                // il controllo è su tre responsabili (uso un altro approccio rispotto al caclio)
                                var CF_1 = arr_CF[0][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var CF_2 = arr_CF[1][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI
                                var CF_3 = arr_CF[2][0].replace(' ', ''); // MI ASSICURO CHE NON CI SIANO EVENTUALI SPAZI VUOTI

                                if (CF_1.localeCompare(CF_2) == 0) // CF_1 CF_2
                                {
                                    alert(" - Cod Fiscale " + responsible[arr_CF[0][1]] + "\n - Cod Fiscale " + responsible[arr_CF[1][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                if (CF_1.localeCompare(CF_3) == 0) // CF_1 CF_3
                                {
                                    alert(" - Cod Fiscale " + responsible[arr_CF[0][1]] + "\n - Cod Fiscale " + responsible[arr_CF[2][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                if (CF_2.localeCompare(CF_3) == 0) // CF_1 CF_3
                                {
                                    alert(" - Cod Fiscale " + responsible[arr_CF[1][1]] + "\n - Cod Fiscale " + responsible[arr_CF[2][1]] + "\n\nSONO UGUALI");

                                    result = false;

                                    return result;
                                }

                                break;
                        }

                        break;

                }



                return result;
            }


            function proceed()
            {

                read_text_window();

                dependens(form_obj); // controlla se la squadra è nuova o gia esistente

                if (control_values()) //controlla che i valori siano tutti A POSTO
                {
                    if (compare_email() && compare_CF())
                    {

                        setCookie('varResp', '', -1); // cancella eventuali cookie gia presenti

                        setCookie('varResp', JSON.stringify(form_obj), 15); // durata 15 minuti

                        $("#ajax-loader").fadeIn(200);

                        //console.log(form_obj);

                        var id_operation;

                        $.post('/sections/iscrizionedati', {Subscription: form_obj}, function (data)
                        {
                            //console.log(data);

                            id_operation = data.id;

                            if (data.id == -1)
                            {
                                $("#ajax-loader").fadeOut(200);

                                alert("La squadra da te creata esiste gia'");
                            }
                            else if (data.id == -2)
                            {
                                if (tipo_sport == sport.CALCIO)
                                {
                                    $("#ajax-loader").fadeOut(200);

                                    alert("La squadra è gia iscritta a questo campionato'");
                                }
                                if (tipo_sport == sport.TENNIS)
                                {
                                    // PER IL TENNIS I CAMPIONATI FUNZIONANO DIVERSAMENTE
                                    // location.href = '/subscriptions/tesseramenti?step=3&verifyid=' + data.id;
                                    // console.log("tess_tenn_pres");
                                }

                            }
                            else
                            {
                                //GIUSEPPE 2017-02-10 ---- controllo dopo il click dei dati inseriti (controllo anagrafico in caso di nuovo inserimento, e email in caso di nuova mail)
                                var atleti_obj = [];

                                var k;

                                for (k = 0; k < 3; k++)
                                {
                                    if (form_obj["Cognome_" + k] != '')
                                    {

                                        var single_atleta = {"Atleta": "", "Cognome": "", "Nome": "", "Email": "", "DataNascita": "", "Iscrizione": "true"};

                                        single_atleta["Cognome"] = form_obj["Cognome_" + k];

                                        single_atleta["Nome"] = form_obj["Nome_" + k];

                                        single_atleta["Email"] = form_obj["Email_" + k];

                                        single_atleta["DataNascita"] = form_obj["DataNascita_it_" + k];

                                        if (form_obj["id_responsabile_" + k] == "NEW")
                                        {
                                            single_atleta["Atleta"] = "";
                                        }
                                        else
                                        {
                                            single_atleta["Atleta"] = form_obj["id_responsabile_" + k];
                                        }

                                        atleti_obj.push(single_atleta);
                                    }

                                }
                                ;

                                //console.log(atleti_obj);

                                $.post('/subscriptions/controlathlete', {atleti: atleti_obj}, function (data_athlete) // eseguo i controlli: se tesserati, se non tesserati ma esistenti nel database, se email è già esistente
                                {

                                    if (data_athlete.length > 0)
                                    {
                                        $("#ajax-loader").fadeOut(200);

                                        var text_res = "ATTENZIONE !!!\n\n";

                                        for (i in data_athlete)
                                        {
                                            text_res += data_athlete[i] + "\n....................................\n\n";
                                        }
                                        alert(text_res);
                                    }
                                    else
                                    {
                                        var page_next = '/subscriptions/tesseramenti?step=3&verifyid=' + id_operation;

                                        location.href = page_next;
                                    }

                                }, 'json');

                            }

                        }, 'json');

                    }

                }

            }
            ;

            //---------------------------------------------------

            var validateMail = true;

            $('.email').live('keyup', function (event, data)
            {

                var id_class = $(this).attr("id");

                var me = $(this);

                var form = me.closest('form');

                validateMail = false;

                var emailWindow = $("#" + id_class).val();

                console.log(emailWindow);

                validateMail = emailCheck(emailWindow); //mentre scrivo la mail, viene verificata, in background, la sua validità. Se questa risulta esatta, parte, sempre in background, il controllo nel database

                if (validateMail)
                {
                    $.get("/sections/searchmail/" + emailWindow, function (data, status)
                    {

                        if (data > 0)
                        {
                            //Testo OK
                            alert("ATTENZIONE\n\nL'indirizzo email \n\n - - - " +
                                    emailWindow + " - - -\n\nrisulta già registrato nel nostro database.\nTi preghiamo di inserire un altro indirizzo email.");
                            //$("#" + id_class).val('');
                        }
                        data = 0;
                    });

                    validateMail = true;
                }
                ;
            })


            // GIUSEPPE 23/12/2016 --------------------------------------------
            // controlla la validità delle email
            function emailCheck(emailStr)
            {
                var emailPat = /^(.+)@(.+)$/;
                var specialChars = "\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";
                var validChars = "[^\\s" + specialChars + "]";
                var quotedUser = "(\"[^\"]*\")";
                var ipDomainPat = /^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
                var atom = validChars + "+";
                var word = "(" + atom + "|" + quotedUser + ")";
                var userPat = new RegExp("^" + word + "(\\." + word + ")*$");
                var domainPat = new RegExp("^" + atom + "(\\." + atom + ")*$");
                var matchArray = emailStr.match(emailPat);
                if (matchArray == null)
                {
                    console.log("L'email sembra essere sbagliata: (controlla @ e .)");
                    return false;
                }
                var user = matchArray[1];
                var domain = matchArray[2];
                if (user.match(userPat) == null)
                {
                    console.log("La parte dell'email prima di '@' non sembra essere valida!");
                    return false;
                }
                var IPArray = domain.match(ipDomainPat);
                if (IPArray != null)
                {
                    for (var i = 1; i <= 4; i++)
                    {
                        if (IPArray[i] > 255)
                        {
                            console.log("L'IP di destinazione non è valido!");
                            return false;
                        }
                    }
                    return true;
                }
                var domainArray = domain.match(domainPat);
                if (domainArray == null)
                {
                    console.log("La parte dell'email dopo '@' non sembra essere valida!");
                    return false;
                }
                var atomPat = new RegExp(atom, "g");
                var domArr = domain.match(atomPat);
                var len = domArr.length;
                if (domArr[domArr.length - 1].length < 2 ||
                        domArr[domArr.length - 1].length > 6)
                {
                    console.log("Il dominio di primo livello (es: .com e .it) non sembra essere valido!");
                    return false;
                }
                if (len < 2)
                {
                    var errStr = "L'indirizzo manca del dominio!";
                    console.log(errStr);
                    return false;
                }
                console.log("email OK")
                validateMail = true;

                return true;
            }
            // ----------------------------------------------------------------

        });

    </script>


<? endif; ?>

<!--
        
        <script type="text/javascript">
        $(document).ready(function(){
        $("#genera").click(function() {
        $("#UserPassword").val('');
        $("#UserPasswordConfirm").val('');
        $.get("/admin/users/generatepwd",function(ret) {
        $("#UserPassword").val(ret.pwd);
        $("#UserPasswordConfirm").val(ret.pwd);
        },'json');
        });
        });
        
        $(function(){
        
        $("#formSignupAthlete").submit(function(){
        
        var data = $(this).serialize();
        
        $('.error-message').remove();
        var error = 0;
        $('#formSignupAthlete .required').each(function(){
        var obj = $(this);
        if(obj.find('input').val() == '') {
        obj.append('<div class="error-message">Campo obbligatorio.</div>');
        error = 1;
        }
        });
        if(error == 1) return false;	
        
        ajaxLoader('show');
        
        $.post('/users/checkTessera', data, function(ret){
        $('.athlete_info').html(ret);
        ajaxLoader('hide');
        },'html');
        
        return false;
        
        });
        
        });
        
        </script>
        
        <div class="wrapper-box">
        <div class="wrapper-box-top"></div>
        <div class="wrapper-box-contents">
        
        <div class="contents-box" id="bg-retino">
        <h1>Modulo registrazione atleti</h1>
        <div class="clear"></div>
        
<?= $this->element("/backend/add_edit_scripts"); ?>
<?= $this->Form->create('User', array('url' => '/registrazione/atleti', 'id' => 'formSignupAthlete')); ?>
    
    <div class="input">
<?= $this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome', 'div' => false)); ?>	
    </div>
    <div class="input">
<?= $this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome', 'div' => false)); ?>
    </div>
    <div class="input">
<?= $this->Form->input('Tessera', array('type' => 'text', 'label' => 'Inserisci numero tessera.', 'div' => false)); ?>
    </div>
    <div class="input">
<?= $this->Form->input('signup_code', array('type' => 'text', 'label' => 'Inserisci codice controllo.', 'div' => false)); ?>
    </div>				
    
    <div class="input">
    <label>&nbsp;</label>
<?= $this->Form->submit('Controlla', array('type' => 'submit', 'div' => false, 'id' => 'controlla')); ?>
    </div>		
    
<?= $this->Form->end(); ?>
    
    <div class="athlete_info">
    
    </div>
    
    </div>
    </div>
    <div class="wrapper-box-bottom"></div>
    </div>
    
-->														
