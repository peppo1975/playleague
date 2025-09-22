<?
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$cauzione = $this->requestAction('sections/readDeposit/' . $nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)

$squadra = "";
?>

<style type="text/css">

    blockquote {
        font-size: 16px;
    }
    blockquote span {
        font-weight: bold;

    }

</style>
<link rel="stylesheet" type="text/css" href="/porto_admin/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>
<script type="text/javascript">


    $(document).ready(function ()
    {

        // PRIMO STEP

        $("#nextstep2").prop('disabled', true);
        setInterval(function ()
        {

            $(".tesserati").text($(".athlete-box:visible section").length);
            var euro = 0;
            $("body").find(".athletes-container #TipoAssicurazione").each(function ()
            {
                if ($(this).val() != "")
                    euro += parseFloat($(this).val());
            });
            if ($(".cauzione").length > 0)
            {

                //euro = parseFloat(euro) + 150;

                //$(".send_deposito").val(150);


                //GIUSEPPE 24/10/2016

                var cauzione = <?= $cauzione; ?>;
                euro = parseFloat(euro) + cauzione;
                $(".send_deposito").val(parseInt(cauzione));
                //---------------------------------------
            }

            $(".send_tesserati").val($("#TipoAssicurazione:visible").length);
            $(".send_totale").val(euro);
            $(".euro").text(euro); /* .text(euro + ",00") * parseFloat(this.value).toFixed(2) */


        }, 1000);
        $(".resptype").live('change', function ()
        {

            var val = $(this).val();
            if (val == 1)
            {

                $(this).closest('.athlete-box').find('.anagrafica-box').hide();
                $(this).closest('.athlete-box').find('.respname').show();
            }
            else
            {

                $(this).closest('.athlete-box').find('.anagrafica-box').show();
                $(this).closest('.athlete-box').find('.respname').hide();
            }

        });
        //GIUSEPPE
        // QUI CLICCO "Aggiungi nuovo atleta"
        function insertAthlete()
        {

            //
            var box = $(".athlete-box:first").clone();
            $("section.panel:visible").addClass('panel-collapsed');
            box.show().appendTo($(".athletes-container"));
        }
        ;
        $("#nextstep2").click(function ()
        {

            insertAthlete()

        });
        $("#nextstep2").trigger('click');
        //

    });
    $(".removeme").live('click', function ()
    {

        if (confirm("Rimuovere il tesserato selezionato?"))
        {
            $(this).closest('.athlete-box').remove();
        }

    });</script>
<div class="container">
    <div class="col-md-9">
        <div class="athlete-box" style="display: none;">

            <div class="anagrafica-box">

                <script type="text/javascript">

                    // GIUSEPPE

                    function leggiCookie(nomeCookie)
                    {
                        if (document.cookie.length > 0)
                        {
                            var inizio = document.cookie.indexOf(nomeCookie + "=");
                            if (inizio != -1)
                            {
                                inizio = inizio + nomeCookie.length + 1;
                                var fine = document.cookie.indexOf(";", inizio);
                                if (fine == -1)
                                    fine = document.cookie.length;
                                return unescape(document.cookie.substring(inizio, fine));
                            }
                            else
                            {
                                return "";
                            }
                        }
                        return "";
                    }

                    function getCookie(cname)
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


                    var globalArrayKey = new Array();
                    /* ................. */

                    //GIUSEPPE 2016-12-27 //
                    var index_to_clear = -1;
                    //

                    $(document).ready(function ()
                    {

                        // AGGIUNTA ATLETA

                        atleta_id = null;
                        selected = false;
                        // AUTOCOMPLETAMENTO CON KEYUP

                        $('.autoComplete2').live('keyup.autocomplete, focus.autocomplete', function ()
                        {
                            var fields = ["Nome", "Cognome", "DataNascita"];
                            var url = $(this).attr('data-url');
                            var dest = $(this).attr('data-dest');
                            var me = $(this);
                            var auc =
                                    {
                                        source: url,
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
                                            if (!ui.item && selected)
                                            {
                                                $(".ats .form-group").css("display", "block");
                                                $(".ats input[type=text]:not(#Nome)").val("");
                                                selected = false;
                                            }
                                        },
                                        select: function (event, ui)
                                        {

                                            var form = me.closest('form');
                                            // inserimento nelle finestre

                                            $.get('/subscriptions/atleta/' + ui.item.id, function (ret)
                                            {
                                                var keys = "";
                                                for (key in ret)
                                                {

                                                    if (form.find('#' + key).length > 0)
                                                    {

                                                        keys += key + " ";
                                                        //console.log(keys);

                                                        //GIUSEPPE
                                                        if (key == "DataNascita")
                                                        {
                                                            form.find('#' + key).val(ret["DataNascita_it"].replace(/\./g, "/"));
                                                        }
                                                        else if (fields.indexOf(key) == -1 && (ret[key] != "" && ret[key] != null))
                                                        {
                                                            form.find('#' + key).attr('type', 'password');
                                                            form.find('#' + key).val(ret[key]);

                                                            if (key === 'CodiceFiscale')
                                                            {
                                                                // alert(ret[key]);

                                                                if (ret[key].length < 16)
                                                                {
                                                                    form.find('#' + key).attr('type', 'text');
                                                                    form.find('#' + key).val('');
                                                                }
                                                            }

                                                            if (key === 'DataNascita')
                                                            {
                                                                // alert(ret[key]);

                                                                if (ret[key].length < 10)
                                                                {
                                                                    form.find('#' + key).attr('type', 'text');
                                                                    form.find('#' + key).val('');
                                                                }
                                                            }

                                                            if (key == 'Email')
                                                            {
                                                                var controlla_email = form.find('#' + key).val();
                                                                var check_mail = emailCheck(controlla_email);
                                                                if (check_mail == false)
                                                                {
                                                                    form.find('#' + key).attr('type', 'text');
                                                                    form.find('#' + key).val('');
                                                                }
                                                            }

                                                        }
                                                        else
                                                        {
                                                            form.find('#' + key).val(ret[key]);
                                                        }

                                                        if (form.find('#' + key).val() != "")
                                                        {
                                                            //form.find('#' + key).attr('readonly','readonly');
                                                            form.find('#' + key).prop('disabled', true);
                                                        }

                                                    }


                                                    form.find('#Atleta').hide();
                                                    //form.find('#Atleta').closest(".form-group").css("display", "none");
                                                }

                                                //GIUSEPPE
                                                if (form.find('#NumeroDocumento').val() == "" || form.find('#NumeroDocumento').val() == null)
                                                {
                                                    form.find('#TipoDocumento').prop('disabled', false);
                                                }

                                                //............................................................................


                                                // //GIUSEPPE controlla se l'atleta è tesserato 03/09/2016
                                                if (ui.item.id)
                                                {

                                                    //$.get("/subscriptions/istesserat/" + ui.item.id, function (data) // 13/11/2016
                                                    $.get("/subscriptions/istesserat/" + ui.item.id + "/<?= $_GET['sport'] ?>", function (data) // 13/11/2016
                                                    {
                                                        // //console.log(data); 
                                                        if (parseInt(data.count) >= 1)
                                                        {

                                                            //Testo OK
                                                            alert("ATTENZIONE!\n\n L'atleta " + form.find('#Cognome').val() + " " + form.find('#Nome').val() +
                                                                    "\n nato il " + form.find('#DataNascita').val() + "\n risulta già tesserato " + data.sport + "\n per la stagione sportiva in corso.");
                                                            for (key in ret) // ripulisce le finestre
                                                            {
                                                                form.find('#' + key).prop('disabled', false);
                                                                form.find('#' + key).val("");
                                                                form.find('#' + key).attr('type', 'text');
                                                            }
                                                            form.find('#Atleta').hide();
                                                            // //
                                                        }
                                                    },'json');
                                                    // ------------------
                                                }

                                                //............................................................................

                                            }, 'json');
                                        }

                                    };
                            $(this).autocomplete(auc);
                        });
                        // GIUSEPPE 04/09/2016 ------------
                        //$('.autoComplete2').live('keyup.autocomplete, focus.autocomplete', function ()
                        var validateMail = true;
                        $('.email').live('keyup', function (event, data)
                        {

                            var me = $(this);
                            var form = me.closest('form');
                            validateMail = false;
                            var emailWindow = form.find('#Email').val();
                            //var emailWindow = $(this).find('#Email')["context"]["value"];//$(this).val();
                            console.log(emailWindow);
                            validateMail = emailCheck(emailWindow);
                            if (validateMail)
                            {
                                $.get("/sections/searchmail/" + emailWindow, function (data, status)
                                {
                                    if (data > 0)
                                    {
                                        //Testo OK
                                        alert("ATTENZIONE\n\nL'indirizzo email " +
                                                emailWindow + " risulta già registrato nel nostro database. Ti preghiamo di inserire un altro indirizzo email.");
                                        form.find('#Email').val('');
                                    }
                                    data = 0;
                                });
                                validateMail = true;
                            }
                            ;
                        })

                        // --------------------------------


                        // GIUSEPPE 2017-20-07 ---------------------------------------------------------

                        $("#Cognome").live('keyup', function (event, data)
                        {

                            controlla_cognome_nome_datanascita($(this));
                        });
                        $("#Nome").live('keyup', function (event, data)
                        {

                            controlla_cognome_nome_datanascita($(this));
                        });
                        $("#DataNascita").live('keyup', function (event, data)
                        {

                            controlla_cognome_nome_datanascita($(this));
                        });
                        function controlla_cognome_nome_datanascita(me)
                        {
                            var form = me.closest('form');
                            cognome = form.find('#Cognome').val();
                            nome = form.find('#Nome').val();
                            data_nascita = form.find('#DataNascita').val();
                            $.post("/subscriptions/readAnagrafici", {cognome: cognome, nome: nome, data: data_nascita}, function (data)
                            {
                                if (data > 0)
                                {
                                    alert("ATTENZIONE! \n\n L'atleta \n\n - " + cognome + " " + nome + "\n\n - nato il " + data_nascita + "\n\n risulta gia inserito nel nostro sistema");
                                    form.find('#Cognome').val("");
                                    form.find('#Nome').val("");
                                    form.find('#DataNascita').val("");
                                }

                            });
                        }


                        // -----------------------------------------------------------------------------

                        var formNew = $(".athletes-container .anagrafica-box .panel");
                        $(".panel-action-toggle").live('click', function ()
                        {
                            $(this).closest('.panel').toggleClass('panel-collapsed');
                        });
                        $(".athletes-container .panel .panel-action-dismiss").live('click', function ()
                        {

                            if ($(".anagrafica-box .panel").length > 1)
                            {

                                $(this).closest('.panel').remove();
                            }

                        });
                        //GIUSEPPE

                        // 06/09/2016
<?php

function ifcookie() // rendo visibili i cookie solo se clicco sul tasto "torna indietro"
{
    if (isset($_GET['k']))
    {
        echo "true";
    }
    else
    {
        echo "false";
    }
}
?>

                        if (getCookie('varAtl') != "" && 1 <?php //ifcookie()                        ?>) //lettura cookie per i dati tesserazioni in memoria
                        {
                            var cookieBack = getCookie('varAtl');
                            var jsonCookie = JSON.parse(cookieBack);
                            for (i = 0; i < jsonCookie.length; i++)
                            {
                                var listOfPropertyJs = Object.getOwnPropertyNames(jsonCookie[i]); // elenca le proprietà di Js (

                                $(".athletes-container .anagrafica-box .panel").each(function (index)
                                {
                                    if (i == index)
                                    {
                                        for (j = 0; j < listOfPropertyJs.length; j++)
                                        {
                                            /*$(".athletes-container")*/

                                            $(this).find("#" + listOfPropertyJs[j]).val(jsonCookie[i][listOfPropertyJs[j]].value);
                                            if (listOfPropertyJs[j] == "DataNascita")
                                            {
                                                var splitDate = jsonCookie[i][listOfPropertyJs[j]].value.split("-");
                                                if (splitDate[0].length == 4)
                                                {
                                                    $(this).find("#" + listOfPropertyJs[j]).val(splitDate[2] + "/" + splitDate[1] + "/" + splitDate[0]);
                                                }
                                            }

                                            if (jsonCookie[i][listOfPropertyJs[j]].visible == false)
                                            {
                                                $(this).find("#" + listOfPropertyJs[j]).attr('type', 'password');
                                            }

                                            if (jsonCookie[i][listOfPropertyJs[j]].readOnly == true)
                                            {
                                                //$(this).find("#"+listOfPropertyJs[j]).attr('readonly','readonly'); // 29/08/2016
                                                $(this).find("#" + listOfPropertyJs[j]).prop('disabled', true);
                                            }

                                            $(this).find('#Atleta').hide();
                                        }

                                        if (i < jsonCookie.length - 1)
                                        {
                                            $("#nextstep2").trigger('click');
                                        }

                                    }

                                });
                                //console.log(cookieBack);
                            }

                        }
                        // GIUSEPPE 13 08 2016
                        var arrayElements = ["Atleta", "nomesquadra", "Nome", "Cognome", "Email", "DataNascita", "LuogoNascita", "CodiceFiscale", "Indirizzo", "Cap", "Localita", "Provincia", "Telefono", "Cellulare", "Sesso", "TipoDocumento", "NumeroDocumento", "TipoAssicurazione"];
                        var datiObbligatori = ["nomesquadra", "Nome", "Cognome", "Email", "DataNascita", "LuogoNascita", "CodiceFiscale", "NumeroDocumento", "TipoAssicurazione"];
                        //
                        setInterval(function ()
                        {
                            //GIUSEPPE
                            if ($(".athletes-container .anagrafica-box .panel").length == 0)
                            {
                                $("#nextstep2").trigger('click');
                            }

                            // 13 08 2016
                            // var arrayVariables = [];
                            // var arrayCondition = [];

                            var variable;
                            var condition;
                            //
                            //console.log("index to clear :" + index_to_clear);

                            $(".athletes-container .anagrafica-box .panel").each(function (index)
                            {

                                console.log($(".athletes-container .anagrafica-box .panel").length);
                                //GIUSEPPE

                                if (index_to_clear >= 0) //azione del tasto SVUOTA
                                {

                                    if (index == index_to_clear)
                                    {
                                        for (i = 0; i < arrayElements.length; i++)
                                        {

                                            if (arrayElements[i].localeCompare("TipoAssicurazione") != 0) // se cancello il contenuto di TipoAsicurazione mi da "Nan" sul riepilogo a destra
                                            {
                                                $(this).find('#' + arrayElements[i]).val("");
                                                $(this).find("#" + arrayElements[i]).prop('disabled', false);
                                            }
                                        }
                                        index_to_clear = -1;
                                    }

                                }

                                var globalKey = {};
                                if (globalArrayKey.length > $(".athletes-container .anagrafica-box .panel").length) // nel caso un elemento venga cancellato
                                {
                                    globalArrayKey = [];
                                }


                                // 13 08 2016
                                for (i = 0; i < arrayElements.length; i++)
                                {
                                    variable = $(this).find('#' + arrayElements[i]).val();
                                    condition = $(this).find('#' + arrayElements[i]).is(":text");
                                    readOnly = $(this).find('#' + arrayElements[i]).is('[disabled]');
                                    globalKey[arrayElements[i]] = {value: variable, visible: condition, readOnly: readOnly};
                                }
                                //

                                {
                                    globalArrayKey[index] = globalKey; // l'array viene sempre aggiornato
                                }

                                // 09/09/2016 // verifica  duplicati nella stessa registrazione (email e utenti gia registrati)

                                for (var indexcontrol in globalArrayKey)
                                {
                                    if (indexcontrol != index)
                                    {

                                        var atleta_ref = parseInt(globalKey["Atleta"].value);
                                        if (atleta_ref != "") // cerca stesso atleta nell'iscrizione e nel tesseramento
                                        {
                                            var atleta_i = parseInt(globalArrayKey[indexcontrol]["Atleta"].value);
                                            if (atleta_ref == atleta_i)
                                            {
                                                //Testo OK
                                                alert("ATTENZIONE\n\nL'atleta selezionato è gia stato inserito in questa registrazione.");
                                                globalKey["Atleta"].value = "";
                                                globalKey["Email"].value = "";
                                                for (i = 0; i < arrayElements.length; i++)
                                                {

                                                    if (arrayElements[i].localeCompare("TipoAssicurazione") != 0) // se cancello il contenuto di TipoAsicurazione mi da "Nan" sul riepilogo a destra
                                                    {
                                                        $(this).find('#' + arrayElements[i]).val("");
                                                        $(this).find("#" + arrayElements[i]).prop('disabled', false);
                                                    }

                                                }


                                            }
                                        }



                                        var email_ref = globalKey["Email"].value;
                                        var email_i = globalArrayKey[indexcontrol]["Email"].value;
                                        var emailCompare = email_ref.localeCompare(email_i);
                                        if (emailCompare == 0)
                                        {
                                            //Testo OK
                                            alert("ATTENZIONE\n\nL'indirizzo email inserito è gia stato utilizzato in questa registrazione.");
                                            $(this).find('#Email').val("");
                                            globalKey["Email"].value = "";
                                        }



                                        //console.log(indexcontrol + "-> index " + index );
                                    }
                                }
                                //13 08 2016 // verifica dati obbligatori
                                var viewButtons = true;
                                var dati_obbligatori_vuoti = false;
                                var eta_minima = true;
                                for (i = 0; i < datiObbligatori.length; i++)
                                {
                                    if (globalKey[datiObbligatori[i]].value == "")
                                    {
                                        $(this).find('#' + datiObbligatori[i]).attr('type', 'text'); // 19/08/2016 // questi due comandi li uso nel caso

                                        globalKey[datiObbligatori[i]].visible = true; // voglia cambiare un campo non visibile per riscriverne un altro: passo da password a text (a patto che venga prima cancellato utto il testo)

                                        viewButtons = false;
                                        dati_obbligatori_vuoti = true

                                    }

                                    if (globalKey["DataNascita"].value.length != 10)
                                    {
                                        viewButtons = false;
                                        $(this).find("#mess_DN").html('');
                                    }
                                    else // se la data di nascita è scritta tutta
                                    {
                                        /* controllo la data di nascita */
                                        var num_millis_day = 3600 * 24 * 1000;
                                        var min_anni = 365 + 365 + 365 + 366;
                                        var d = new Date();
                                        var mydata = $(this).find("#DataNascita").val();
                                        var spl = mydata.split("/");
                                        var utcNowMidnight = Date.UTC(parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())); // unix timestamp della mezzanotte di oggi

                                        //console.log("NOW -> " + utcNowMidnight);

                                        var utc1 = Date.UTC(parseInt(spl[2]), parseInt(spl[1]) - 1, parseInt(spl[0])); // unix timestamp della mezzanotte della data di nascita

                                        var differenza = parseInt(utcNowMidnight) - parseInt(utc1); // differenza in millisecondi

                                        var giorni = differenza / num_millis_day // differenza in giorni

                                        $(this).find("#mess_DN").html("età " + Math.floor(giorni / 365.25));
                                        if (differenza >= 0)
                                        {
                                            if (giorni >= min_anni)
                                            {
                                                $(this).find("#mess_DN").html("età " + Math.floor(giorni / 365.25));
                                            }
                                            else
                                            {
                                                $(this).find("#mess_DN").html("età " + Math.floor(giorni / 365.25) + "<br>età minima: 4 anni");
                                                viewButtons = false;
                                                eta_minima = false;
                                            }

                                        }
                                        else
                                        {

                                            $(this).find("#mess_DN").html("data di nascita non valida");
                                            viewButtons = false;
                                            eta_minima = false;
                                        }



                                    }
                                    ;
                                    //$(this).find("#mess_CF").html(globalKey["CodiceFiscale"].value.length);

                                    if (globalKey["CodiceFiscale"].value.length != 16 && globalKey["CodiceFiscale"].value.length != 0)
                                    {
                                        $(this).find("#mess_CF").html("mancano " + (16 - parseInt(globalKey["CodiceFiscale"].value.length)) + " caratteri");
                                        viewButtons = false;
                                    }
                                    else if (globalKey["CodiceFiscale"].value.length == 16)
                                    {
                                        $(this).find("#mess_CF").html("OK");
                                    }
                                    else if (globalKey["CodiceFiscale"].value.length == 0)
                                    {
                                        $(this).find("#mess_CF").html("");
                                    }





                                }

                                //

                                // //viewButtons = nome != "" && cognome != "" && nomesquadra != "" && mail != "" && datanascita != "" && luogonascita != "" && codicefiscale != "" && numerodocumento != "" && tipoassicurazione != "";

                                if (viewButtons && validateMail) // qui il tasto "nuovo atleta viene attivato"
                                {
                                    $("#nextstep2").prop('disabled', false);
                                    $(this).find('.panel-action-toggle').show(); // se il panel collassa, si riesce ad andare avanti anche con dati mancanti. in questo modo il panel collassa solo quando ci sono tutti i dati, se manca qualche dato il panel non collassa

                                    next_step["next"] = true;
                                }
                                else  // qui il tasto "nuovo atleta viene DISATTIVATO"
                                {
                                    $("#nextstep2").prop('disabled', true);
                                    $(this).find('.panel-action-toggle').hide();
                                    if (dati_obbligatori_vuoti == false)
                                    {
                                        next_step["next"] = false;
                                        if (globalKey["DataNascita"].value.length != 10)
                                        {
                                            next_step["error_message"] = "La DATA DI NASCITA non è valida";
                                        }
                                        else
                                        {
                                            if (eta_minima == false)
                                            {
                                                next_step["error_message"] = "Non ci sono i requisiti minimi di età ";
                                            }
                                        }
                                        ;
                                        if (!validateMail)
                                        {
                                            next_step["error_message"] = "L'indirizzo EMAIL inserito non è valido";
                                        }
                                        ;
                                        if (globalKey["CodiceFiscale"].value.length != 16)
                                        {
                                            next_step["error_message"] = "Il codice fiscale deve essere composto da\n16 caratteri alfanumerici";
                                        }
                                        ;
                                    }
                                    else
                                    {
                                        next_step["next"] = true;
                                    }


                                }

                                // nome = arrayVariables[1];
                                // cognome = arrayVariables[2];
                                nome = globalKey["Nome"].value;
                                cognome = globalKey["Cognome"].value;
                                ;
                                //

                                if (nome != "" || cognome != "")
                                {

                                    $(this).find('.panel-title').html('Atleta: <strong>' + cognome + "</strong> " + nome);
                                }
                                else
                                {
                                    $(this).find('.panel-title').html('Atleta ' + (index + 1));
                                }

                                if (index == $(".athletes-container .anagrafica-box .panel").length - 1)
                                {
                                    $(this).find('#nome_barra').html('<div class="btn btn-default"  onclick="fix_index_for_clear(\'' + index + '\')">SVUOTA</div>');
                                }
                                else
                                {
                                    $(this).find('#nome_barra').html('');
                                }


                                //GIUSEPPE
                                // qui riempo nel caso la finestra "Tipo Documento" è vuota 
                                if ($(this).find('#TipoDocumento').val() == null)
                                {

                                    $(this).find('#TipoDocumento').val("Carta Identità");
                                }

                            });
                        }, 500);
                    });
                    //GIUSEPPE 2016-12-27 "mi serve per impostare index_to_clear!=-1. In questo modo verranno ripulite le findestre indicate da index_to_clear"
                    function fix_index_for_clear(index_clear)
                    {
                        index_to_clear = index_clear;
                        //alert("fengul" + index_to_clear);
                    }

                    // GIUSEPPE 03/09/2016 --------------------------------------------
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
                </script>




                <section class="panel">


                    <header class="panel-heading">

                        <div class="panel-actions">

                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>

                            <div id="nome_barra" class="left-element" style="float:left;">svuota</div>
                            <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss="" style=" margin: 5px 0 0 10px;"></a>

                            <!-- //GIUSEPPE 
                            <a href="#" id="reselected" class="panel-action panel-action-dismiss" data-panel-dismiss="">-->

                        </div>

                        <h2 class="panel-title">Atleta</h2>

                    </header>

                    <div class="panel-body ats">
                        <form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">
                            <input type="hidden" name="Atleta" id="Atleta" value=""> <!--//GIUSEPPE -->

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDisabled"><strong>Squadra<sup>*</sup></strong></label>
                                <div class="col-md-6">


                                    <?= $this->Form->input('nomesquadra', array('type' => 'select', 'empty' => 'Seleziona una squadra...', 'class' => 'form-control', 'label' => false, 'options' => $squadres, 'div' => false)); ?>


                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Cognome:<sup>*</sup></strong></label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Cognome', array('label' => false,
                                        'class' => 'form-control autoComplete2',
                                        'autoComplete' => 'off', 'required' => 'required',
                                        'data-url' => '/subscriptions/find/',
                                        'data-dest' => 'Cognome'
                                    ));
                                    ?>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Nome:<sup>*</sup></strong></label>
                                <div class="col-md-6">
                                    <?=
                                    $this->Form->input('Nome', array('label' => false,
                                        'class' => 'form-control',
                                        'required' => 'required'));
                                    ?>

                                </div>

                            </div>



                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>E-mail:<sup>*</sup></strong></label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Email', array('label' => false,
                                        'class' => 'form-control email',
                                        'required' => 'required'));
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Data di nascita:<sup>*</sup></strong></label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('DataNascita', array('label' => false,
                                        'class' => 'datanasc form-control',
                                        'required' => 'required'));
                                    ?>

                                </div>
                                <div id="mess_DN" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Luogo di nascita:<sup>*</sup></strong></label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('LuogoNascita', array('label' => false,
                                        'class' => 'form-control',
                                        'required' => 'required'));
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Codice Fiscale:<sup>*</sup></strong></label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('CodiceFiscale', array('label' => false,
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'maxlength' => '16'));
                                    ?>

                                </div><div id="mess_CF" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Indirizzo:</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Indirizzo', array('label' => false,
                                        'class' => 'form-control',
                                        'required' => 'required'));
                                    ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">CAP:</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Cap', array('label' => false,
                                        'class' => 'form-control',
                                        'required' => 'required'));
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Localit&agrave;:</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Localita', array('label' => false,
                                        'class' => 'form-control'));
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Provincia:</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Provincia', array('label' => false,
                                        'class' => 'form-control',
                                        'maxlength' => '2',
                                        'style'=> 'text-transform:uppercase'
                                        ));
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Telefono cellulare</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Cellulare', array('label' => false,
                                        'class' => 'form-control'));
                                    ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Sesso</label>
                                <div class="col-md-6">

                                    <?=
                                    $this->Form->input('Sesso', array(
                                        'label' => false,
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => array('Maschio' => 'Maschio', 'Femmina' => 'Femmina'),
                                    ));
                                    ?>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Tipo Documento:</label>
                                <div class="col-md-6">


                                    <?=
                                    $this->Form->input('TipoDocumento', array(
                                        'label' => false,
                                        'class' => 'form-control',
                                        'options' => array(
                                            'Carta Identità' => 'Carta Identità',
                                            'Patente' => 'Patente',
                                            'Passaporto' => 'Passaporto'
                                        )
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Numero Documento:<sup>*</sup></strong></label>
                                <div class="col-md-6">


                                    <?=
                                    $this->Form->input('NumeroDocumento', array('label' => false,
                                        'class' => 'form-control'));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"><strong>Assicurazione:<sup>*</sup></strong></label>
                                <div class="col-md-6">


                                    <?
                                    $options1 = array();

                                    foreach ($TipiAssicurazione as $TipoAssicurazione)
                                    {
                                        $options1[$TipoAssicurazione['TipiAssicurazione']['Costo']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
                                    }
                                    ?>
                                    <?=
                                    $this->Form->input('TipoAssicurazione', array('type' => 'select',
                                        'label' => false,
                                        'class' => 'form-control',
                                        'options' => $options1));
                                    ?>
                                </div>
                            </div>


                        </form>

                    </div>
                </section>



            </div>
        </div>
        <div class="athletes-container">
        </div>
        <div class="clear"></div>




        <div class="panel-footer">
            <ul class="pager">

                <li class="next" id="validate">
                    <a href="#" id="nextstep" class="btn btn-success">Conferma e vai al pagamento <i class="fa fa-angle-right"></i></a>
                </li>
            </ul>
        </div>



        </form>

    </div>


    <div class="col-md-3">
        <div class="pin-wrapper" style="height: 223px;"><aside class="sidebar" id="sidebar" data-plugin-sticky="" data-plugin-options="{&quot;minWidth&quot;: 991, &quot;containerSelector&quot;: &quot;.container&quot;, &quot;padding&quot;: {&quot;top&quot;: 110}}" style="width: 263px;">





                <div id="resume-box">
                    <p>
                        Atleti da tesserare: <span class="tesserati" style="font-weight: bold;">0,00</span>
                    </p>
                    <? if (isset($_GET['c']) && $_GET['c'] == 1): ?>
                        <p>
                                <!--Deposito cauzionale squadra: <span class="cauzione" style="font-weight: bold;">150,00 &euro;</span>-->
                            Deposito cauzionale squadra: <span class="cauzione" style="font-weight: bold;"><?= $cauzione; ?> &euro;</span
                        </p>	
                    <? endif; ?>

                    <p>
                        Totale importo da pagare: <span class="euro" style="font-weight: bold;">0,00</span> &euro;
                    </p>
                </div>
                <?= $this->Form->button('Aggiungi nuovo atleta', array('type' => 'button', 'div' => false, 'class' => 'btn btn-lg btn-info', 'id' => 'nextstep2')); //echo "<script>$(\"#nextstep2\").hide();</script>"   ?>
                <input type="hidden" name="totale" value="0" class="send_totale" />
                <input type="hidden" name="tesserati" value="0" class="send_tesserati" />
                <input type="hidden" name="deposito" value="0" class="send_deposito" /><br />


                <ul class="list list-icons list-icons-sm" style="padding-left: 0px; list-style-type: none; font-size: 12px;">
                    <li><i class="fa fa-caret-right"></i>Seleziona la squadra in cui inserire l'atleta o gli atleti da iscrivere;</li>
                    <li><i class="fa fa-caret-right"></i>Digita il <strong>Cognome</strong> nell'apposito campo e verifica nel database la presenza dell'atleta che vuoi iscrivere;
                        <ul>
                            <li>Se presente, seleziona l'atleta verificando che i dati in chiaro siano corretti;</li>
                            <li>Se non presente, inserisci i dati richiesti;</li>

                            <? if ($_GET['sport'] == "TENNIS"): ?> 
                                <li>Per i tesseramenti relativi ai tornei INDIVIDUALI, selezionare nella casella SQUADRA, la voce squadra 0 TESSERAMENTO PER TORNEI INDIVIDUALI</li>
                            <? endif; ?>
                        </ul>
                    </li>
                    <li><i class="fa fa-caret-right"></i>Completa la procedura inserendo uno o più atleti;</li>
                    <li><i class="fa fa-caret-right"></i>Procedi con il pagamento.</li>
                    <li style="color: #A94447;">I campi contrassegnati dall'asterisco (<sup>*</sup>) sono obbligatori.</li>
                </ul>

            </aside></div>

    </div>

</div>
<script type="text/javascript">

    //PAGAMENTO

    stoppy = false;
    //GIUSEPPE
    function scriviCookie(nomeCookie, valoreCookie, durataCookie)
    {
        var scadenza = new Date();
        var adesso = new Date();
        scadenza.setTime(adesso.getTime() + (parseInt(durataCookie) * 60000));
        document.cookie = nomeCookie + '=' + escape(valoreCookie) + '; expires=' + scadenza.toGMTString() + '; path=/';
    }


    function setCookie(cname, cvalue, exmin)
    {
        var d = new Date();
        d.setTime(d.getTime() + (exmin * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }
    //

    next_step = {"next": true, "error_message": ""};
    $(document).ready(function ()
    {
        $('.datanasc').mask('00/00/0000');
        $("input, select").bind('focus', function ()
        {

            $(this).closest('.form-group').removeClass('has-error');
        });

        $("#nextstep").click(function ()
        {
            if (next_step["next"] == false)
            {
                //alert("C'è una " + next_step["tipo_dato"] + " non è valida" );

                alert(next_step["error_message"]);

                return;
            }

            at_least = 0;
            $(".form-group").removeClass('.has-error');
            $("sup:visible").each(function ()
            {
                var form_group = $(this).closest('.form-group');
                var inputs = $(form_group).find('input, select');
                var has_error = 0;
                inputs.each(function ()
                {

                    if ($.trim($(this).val()) == "")
                    {
                        at_least = 1;
                        has_error = 1;
                    }

                });
                if (has_error == 1)
                    form_group.addClass('has-error');
            });
            if (at_least == 1)
                $(window).scrollTop($(".has-error:first").offset().top);
            else
            {

                if (false && atleta_id)
                {
                    $.get("/subscriptions/isTesserat/" + atleta_id, function (data)
                    {
                        if (data > 1)
                        {
                            alert("ATTENZIONE\n\nL'atleta selezionato risulta già tesserato.");
                            return false;
                        }
                        else
                        {
                            var senddata = new Object;
                            senddata["atleti"] = new Array;
                            $(".anagrafica-box form").each(function ()
                            {
                                if ($(this).find(".email").val() != "")
                                {
                                    var dati = new Object;
                                    $(this).find('input, select').each(function ()
                                    {

                                        var name = $(this).attr('name');
                                        name = name.replace('data[', '').replace(']', '');
                                        if ($(this).is('input'))
                                            dati[name] = $(this).val();
                                        else
                                        {
                                            dati[name] = $(this).find('option:selected').text();
                                        }
                                    });
                                    dati["totale"] = $(".euro").text().replace(",", ".");
                                    senddata["atleti"].push(dati);
                                }

                            });
                            $.post('/sections/tesseramentidati', $.param(senddata), function (data)
                            {

                                location.href = '/subscriptions/tesseramenti?step=5&d=<?= $_GET['d']; ?>&c=<?= (int) $_GET['c']; ?>&verifyid=' + data.id + '&totale=' + $(".euro").text().replace(",", ".");
                            }, 'json');
                        }
                    });
                }
                else
                {

                    var senddata = new Object;
                    senddata["atleti"] = new Array;
                    $(".anagrafica-box form").each(function ()
                    {

                        if ($(this).find(".email").val() != "")
                        {

                            var dati = new Object;
                            
                            var id = $(this).find("#Atleta").val();
                            
                            // GIUSEPPE : viene evitato il controllo del tesseramento, lo faccio prima di arrivare qui
                            $(this).find('input, select').each(function ()
                            {

                                var name = $(this).attr('name');
                                
                                name = name.replace('data[', '').replace(']', '');
                                
                                if ($(this).is('input'))
                                    
                                    dati[name] = $(this).val();
                                
                                else
                                {
                                    dati[name] = $(this).find('option:selected').text();
                                }
                            });
                            dati["totale"] = $(".euro").text().replace(",", ".");
                            dati['sport'] = '<?= $_GET['sport'] ?>';
                            senddata["atleti"].push(dati);
                        }


                    });
                    $("#ajax-loader").fadeIn(200);
                    setTimeout(function ()
                    {
                        if (!stoppy)
                        {
                            //GIUSEPPE

                            setCookie('varAtl', '', -1); // cancella eventuali cookie gia presenti

                            setCookie('varAtl', JSON.stringify(globalArrayKey), 15); // durata 15 minuti
                            //
                            sendData(senddata);
                        }

                    }, 1000);
                }

            }

        });
    });
    function sendData(d)
    {
        //console.log(d);

        $.post('/subscriptions/controlathlete', $.param(d), function (data) // eseguo i controlli: se tesserati, se non tesserati ma esistenti nel database, se email è già esistente
        {
            //
            //console.log(' --...> ' + data.length);

            if (data.length > 0)
            {
                $("#ajax-loader").fadeOut(200);
                var text_res = "ATTENZIONE !!!\n\n";
                for (i in data)
                {
                    text_res += data[i] + "\n....................................\n\n";
                }
                alert(text_res);
            }
            else
            {
                $.post('/sections/tesseramentidati', $.param(d), function (data)
                {

                    // GIUSEPPE: data è un un oggetto con solo id generato dalla funzione "tesseramentidati()" in "sections_controller.php" 
                    location.href = '/subscriptions/tesseramenti?step=5&d=<?= $_GET['d']; ?>&c=<?= (int) $_GET['c']; ?>&verifyid=' + data.id + '&totale=' + $(".euro").text().replace(",", ".");
                }, 'json');
            }

        }, 'json');
    }

</script>
