<style>
    .intro {
        /*background-color: greenyellow;*/
    }
    .pointer {
        cursor: pointer
    }

    .sport-selection,
    .new_hour{
        display: flex;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 10px;
        background: aliceblue;
    }

    .sport-selection{
        max-width: 600px;
    }

    .sport-selection select{
        width: 600px !important;
        padding: 10px;
    }

    .sport-selection select option{
        display: block;
        float: left;
        padding: 7px;
        border: 1px solid #efefef;
        margin: 2px;
        font-size: 0.9em;
    }

    .sport-selection select option:hover{
        border: 1px solid #0019ff;

    }

    .sport-selection select option:focus{
        border: 1px solid #0019ff;
        background: #fffb2545;
    }

    .new_hour{
        max-width: 600px;
    }

    .capis-table-filter h2{
        padding-top: 10px;
        padding-left: 20px;
    }

    #from-to{
        padding: 5px 0;
        font-size: 14px;
    }

    .button-row{
        margin: 15px 0 25px;
        padding-left: 20px;
    }

    .error-input{
        border: 2px solid red;
    }



</style>

<!--date("W", mktime(0, 0, 0, 9, 5, 2020));-->

<!--
mktime(
    int $hour,
    ?int $minute = null,
    ?int $second = null,
    ?int $month = null,
    ?int $day = null,
    ?int $year = null
): int|false
-->

<? $group_id = $_SESSION['User']['group_id'] //GIUSEPPE 2023-07-28 ?>
<div style="margin: 10px 10px 10px 10px">

    <h1>Tabella campi - Prospetto</h1>

    <hr>

    <div class="capis-table-filter">
        <h2>Filtra per tipo di sport</h2>
        <? // print_r($_SESSION) ?>
        <!--check-->
        <div class="row" >


            <div class="w3-col s4 sport-selection" style="display: flex;">
                <label class="checkcontainer pointer">
                    <input type="checkbox" class="type-sport filter-type-soccer-pitch" name="radio" id="is5" checked="">
                    <span class="radiobtn"></span>
                    <strong>Calcio a 5</strong>
                </label>
                <label class="checkcontainer pointer">
                    <input type="checkbox" class="type-sport filter-type-soccer-pitch" name="radio"  id="is7" checked="">
                    <span class="radiobtn"></span>
                    <strong>Calcio a 7</strong>
                </label>
            </div>

        </div>


        <h2>Seleziona data e ora</h2>
        <div class="tab-page tab-selected" data-index="2">
            <!-- <p>oggi é: <?= date("d/m/Y") ?></p> -->

            <div class="new_hour">

                <div class="input select">
                    <label for="CampiOrariGiorno">Seleziona data</label>
                    <button class="week_jump" value="-7"><</button>
                    <input type="date"id="weekpicker"  class="date-interval" week="<?= $week_now ?>" value="<?= $date_now ?>">
                    <button class="week_jump" value="7">></button>
                </div>  
                <div class="input select">
                    <label>Intervallo date selezionato</label>
                    <div id="from-to"><strong><?= $date_interval ?></strong></div>
                </div>
                <div class="input time ">
                    <label for="CampiOrariOraHour">dalle ore</label>
                    <!--<input type="time" id="our-init" class="date-interval" value="<?= $h_now ?>">-->
                    <input type="time" id="our-init" class="date-interval" value="19:00">
                </div>                              
                <div class="input time ">
                    <label for="CampiOrariOraHour">alle ore</label>
                    <!--<input type="time" id="our-end" class="date-interval" value="<?= $h_now_4 ?>"  max="23:59">-->
                    <input type="time" id="our-end" class="date-interval" value="23:59"  max="23:59">
                </div>                              

                <div class="clear"></div>

            </div>

        </div>




        <h2>Filtra per impianto</h2>
        <div class="row" >
            <div class="w3-col s4 sport-selection" style="display: flex;">
                <div class="tab-page tab-selected " data-index="2">
                    <!--<div class=" filtra-campi">-->
                    <select  class="filtra-campi" size="4" multiple>
                    </select>
                    <!--</div>-->
                </div>
            </div>
        </div>

        <div class="col-lg button-row">
            <!--<button id="send-request" class="pointer" disabled="">Cerca</button>-->
            <button id="send-request" class="pointer">Applica filtro</button>
            <button id="reset-request" class="pointer">Reset filtro</button>
        </div>


        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>
        <div class="row" style="margin: 10px 0px 0px 0px">
            <div class="col-lg " id="response" style="width: 100%; float:left">
                <img src="https://media2.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif" alt="alt"/>
            </div>
        </div>

    </div>
</div>

<? require 'admin_prospetto_form_insert.ctp'; ?>
<? require 'admin_prospetto_form_edit.ctp'; ?>
<? require 'admin_prospetto_form_sms.ctp'; ?>
<script>
    document.addEventListener("visibilitychange", function ()
    {
        if (document.hidden)
        {
            console.log("Browser tab is hidden");
        }
        else
        {
            console.log("Browser tab is visible");
            $("#send-request").trigger('click');
        }
    });
</script>
<script>

    $(function ()
    {

//        setInterval(() => {
//
//            $("#send-request").trigger('click');
//
//        }, 5000);

        // admin_prospetto.ctp INIT ------------------------------------------------------------
        var to_send = {};
        var to_prenotazione = {};

        var dateNow = '<?= date("Y-m-d") ?>';
        var alertDataPassata = "Attenzione!<br> Stai inserendo una prenotazione relativa ad una data passata!";
        var togglePayed;

        var _this = {};

        var group_id = parseInt('<?= $group_id ?>'); //GIUSEPPE 2023-07-28 

        to_send['sport'] = {};
        to_send['date'] = {};
        var type_sport = '';
        api_key = '<?= $apiKey ?>';
        url = "/apis/campi/?api_key=" + api_key;

        $(".type-sport").each(function ()
        {
            type_sport = $(this).attr('id');
            to_send['sport'][type_sport] = 1;
        });

        $(".type-sport").change(function ()
        {
            type_sport = $(this).attr('id');

            to_send['sport'][type_sport] = 0;

            if ($(this).attr('checked'))
            {
                to_send['sport'][type_sport] = 1;
            }

            $("#send-request").attr('disabled', false);

            filtraCampi(to_send);


        });

        // al caricamento della pagina
        setTimeout(function ()
        {
            // $("#send-request").trigger('click');

            $(".date-interval").each(function ()
            {
                id = $(this).attr('id');

                to_send['date'][id] = $(this).val();

                if (id == 'weekpicker')
                {
                    to_send['date'][id] = $(this).attr('week');
                }
            });

            filtraCampi(to_send);
        }, 1000);


        $("#weekpicker").change(function ()
        {
            var dateToParse = $(this).val();

            $.post("/apis/numSettimana/" + dateToParse, function (data) // lo trasformo in formato ({anno}-W{settimana}) es: 2022-W34
            {
                console.log(data);
                YW = data
                dalalSett(YW);
                console.log($("#weekpicker").attr('week', YW));
            });

        });



        $("#send-request").click(function ()
        {

            console.log("to_send: ", to_send);

            var x = $(".filtra-campi").val();

            if (x == null)
            {
                delete  to_send['filter_campi'];
            }
            else
            {
                to_send['filter_campi'] = x;
            }



            console.log(x);

            $(".date-interval").each(function ()
            {
                id = $(this).attr('id');

                to_send['date'][id] = $(this).val();

                if (id == 'weekpicker')
                {
                    to_send['date'][id] = $(this).attr('week');
                }
            });

            $.post(url, to_send, function (data)
            {
                $("#response").html("");
                $("#response").html(data);

                tabella = document.getElementsByClassName("tabella");

                togglePayed = document.getElementsByClassName("payed");

                Object.keys(togglePayed).map((i) => {

                    if (group_id === 15)
                    {
                        return 0;
                    }

                    togglePayed[i].addEventListener('click', togglePagato);
                });
//                if
//                

                button_booked = document.getElementsByClassName("button_booked");

                for (var i = 0; i < tabella.length; i++)
                {
                    tabella[i].addEventListener('click', Myf, false);
                }

                for (var i = 0; i < button_booked.length; i++)
                {
                    button_booked[i].addEventListener('click', BtBk, false);
                }

                function BtBk()
                {



                    if (group_id == 15)
                    {
                        return 0;
                    }

                    var window = this.getAttribute('window');

                    var campo_id = this.getAttribute('campo_id');
                    var id_booking = this.getAttribute('id_booking');

                    var ora = this.getAttribute('ora');
                    var data = this.getAttribute('data');
                    var stato = this.getAttribute('stato');
                    var importo = this.getAttribute('importo');

                    var note = this.getAttribute('note');
                    var nome = this.getAttribute('nome');
                    var cognome = this.getAttribute('cognome');
                    var email = this.getAttribute('email');
                    var telefono = this.getAttribute('telefono');
                    var pagato = this.getAttribute('pagato');

                    var data_modal_split = data.split("-");
                    var data_modal = `${data_modal_split[2]}/${data_modal_split[1]}/${data_modal_split[0]}`;

                    var campo_modal = document.getElementById("champ_" + campo_id).innerHTML;


                    if (window == "edit")
                    {


                        document.getElementById("data_modal_edit").innerHTML = data_modal;
                        document.getElementById("campo_modal_edit").innerHTML = campo_modal;
                        document.getElementById("importo_modal_edit").value = importo;
//                        document.getElementById("importo_modal_edit").innerHTML = importo;
                        document.getElementById("ora_modal_edit").innerHTML = ora;

                        document.getElementById("editBookerId").value = id_booking;
                        document.getElementById("editBookerCognome").value = cognome;
                        document.getElementById("editBookerNome").value = nome;
                        document.getElementById("editBookerEmail").value = email;
                        document.getElementById("editBookerTelefono").value = telefono;
                        document.getElementById("editNote").value = note;
                        document.getElementById("editPagato").checked = parseInt(pagato);

                        modalEdit.style.display = "block"; // apre la modale
                        return;

                        if (confirm(`Cancellare la prenotazione\n${campo_modal}\ndel: ${data_modal}\ndelle ore: ${ora}?`))
                        {

                            to_prenotazione['changeState'] = "L";
                            to_prenotazione['id'] = id_booking;

                            $.post("/apis/saveBooking", to_prenotazione, function (data)
                            {
                                console.log(data);
                                to_prenotazione = {};
                                $(".modal_prenotazione").val("");
                                $("#send-request").trigger('click');
                            }, 'json');

                        }
                        else
                        {

                        }

                        return;

                    }

                    if (window == "sms")
                    {

                        document.getElementById("data_modal_sms").innerHTML = data_modal;
                        document.getElementById("campo_modal_sms").innerHTML = campo_modal;
                        document.getElementById("importo_modal_sms").innerHTML = importo;
                        document.getElementById("ora_modal_sms").innerHTML = ora;

                        document.getElementById("editBookerId").value = id_booking;
                        document.getElementById("smsBookerCognome").innerHTML = cognome;
                        document.getElementById("smsBookerNome").innerHTML = nome;
                        document.getElementById("smsBookerEmail").innerHTML = email;
                        document.getElementById("smsBookerTelefono").innerHTML = telefono;
                        document.getElementById("smsBookerTelefonoInput").value = telefono;
//                        document.getElementById("editNote").innerHTML = note;


                        modalSms.style.display = "block"; // apre la modale
                    }

                }

                function Myf()
                {

                    if (group_id == 15)
                    {
                        return 0;
                    }

                    var clearBooker = document.getElementById("clear-booker");
                    clearBooker.click();

                    var campo_id = this.getAttribute('campo_id');
                    var id_booking = this.getAttribute('id_booking');

                    var ora = this.getAttribute('ora');
                    var data = this.getAttribute('data');
                    var stato = this.getAttribute('stato');
                    var importo = this.getAttribute('importo');
//                    to_prenotazione['Importo'] = importo;

                    date_init = data;


                    var note = this.getAttribute('note');
                    var nome = this.getAttribute('nome');
                    var cognome = this.getAttribute('cognome');
                    var email = this.getAttribute('email');
                    var telefono = this.getAttribute('telefono');

                    dateNow > date_init ? document.getElementById("alert-prenotazione").style.display = 'block' : document.getElementById("alert-prenotazione").style.display = 'none';
//                    $(".black-list").hide('fast');
                    document.getElementsByClassName("black-list")[0].style.display = 'none';

                    check_recursive.checked = false;
                    check_recursive_function();
                    date_init_recursive = data;
                    date_select = data;

                    var date_select = new Date(data);
                    var timestamp = date_select.getTime();


                    if (stato == "C" || stato == "P")
                    {
                        return;
                    }

                    _this = this;

                    var data_modal_split = data.split("-");
                    var data_modal = `${data_modal_split[2]}/${data_modal_split[1]}/${data_modal_split[0]}`;

                    var campo_modal = document.getElementById("champ_" + campo_id).innerHTML;

                    if (id_booking == "" && stato == "P")
                    {
                        return;
                    }

                    document.getElementById("data_modal_init").innerHTML = data_modal;
                    to_prenotazione['campo_id'] = campo_id;
                    to_prenotazione['Ora'] = ora;
                    to_prenotazione['Data'] = [];
                    to_prenotazione['Data'][0] = data;
                    to_prenotazione['Stato'] = stato;

                    console.log(`id campo: ${campo_id} ${ora} ${data} stato: ${stato}`);

                    document.getElementById("alert-prenotazione").innerHTML = dateNow > date_init ? alertDataPassata : "";

                    document.getElementById("data_modal").innerHTML = data_modal;
                    document.getElementById("campo_modal").innerHTML = campo_modal;
//                    document.getElementById("importo_modal").innerHTML = importo;
                    document.getElementById("importo_modal").value = importo;
                    document.getElementById("ora_modal").innerHTML = ora;
                    document.getElementById("giorno_settimana").innerHTML = giorni_settimana[date_select.getDay()];
                    document.getElementById("ora_giorno").innerHTML = ora;

                    document.getElementById("weekpicker_to").value = data;
                    document.getElementById("weekpicker_to").setAttribute("min", data);



                    var link = document.getElementById('weekpicker_to');
                    link.setAttribute("campo_id", campo_id);
                    link.setAttribute("ora", ora);


                    boxInfoDateOccupate.style.display = 'none';
                    boxInfoDateLibere.style.display = 'none';
                    modal.style.display = "block"; // apre la modale

                    return true;
                }

            });
        });

        $("#reset-request").click(() => {
            location.reload();
        });



        //GIUSEPPE 2023-01-17 ------------------------------------------------

        $("#send_modal_sms").click(function ()
        {
            cell = $("#smsBookerTelefonoInput").val();
            smsNote = $("#smsNote").val();
            send_sms = {};
            send_sms['cell'] = cell;
            send_sms['smsNote'] = smsNote;

            $.post("/admin/apis/sendSmsToBooker", send_sms, function (data)
            {
                console.log(data);
                var textResponse = "";
                if (data['response'] == true)
                {
                    textResponse = "<div style='color: green' >Messaggio inviato correttamente</div>";
                }
                else
                {
                    textResponse = "<div style='color: red' >Problemi nell' invio dell' SMS</div>";
                }

                $("#smsResponse").html(textResponse);

            }, 'json');

        });


        $(".week_jump").click(function ()
        {
            var days = parseInt($(this).attr('value'));
            var dateToParse = $("#weekpicker").val();

            var result = new Date(dateToParse);
            result.setDate(result.getDate() + days);

            console.log(result.getFullYear());
            console.log(result.getMonth() + 1);
            console.log(result.getDate());

            var Y = result.getFullYear().toString();
            var m = (result.getMonth() + 1).toString();
            var d = result.getDate().toString();

            m = m.length == 1 ? "0" + m : m;
            d = d.length == 1 ? "0" + d : d;

            var newDate = `${Y}-${m}-${d}`;

            $("#weekpicker").val(newDate);

            $("#weekpicker").trigger('change');
        });



        function filtraCampi(campi)
        {

            $.post("/apis/filtraCampi", campi, function (data)
            {
                $(".filtra-campi").children("option").remove();

                console.log(data);

                for (i in data)
                {

                    var campo = data[i]['Campo'];
                    var descrizione = data[i]['Descrizione'];
                    var optionText = creaCheck(campo, descrizione);
                    $(".filtra-campi").append(optionText);
                }
                ;

                $("#send-request").trigger('click');

            }, 'json');
        }


        function creaCheck(campo, descrizione)
        {
            var check = `<option value="${campo}">${descrizione}</option>`;

            return check;
        }

        //--------------------------------------------------------------------

        //--------------

        function dalalSett(YW)
        {
            $.post("/apis/dalalSett/" + YW, function (data)
            {
                console.log(data);
                $("#from-to").html(`<strong>${data}</strong>`);//$`{data}`
                $("#send-request").trigger('click');
            });
        }




        // admin_prospetto.ctp END ------------------------------------------------------------

        $("#send_modal").click(() => {

            $(".black-list").hide();

            let changeState = "";

            let is_valid = true;

            $(".modal_prenotazione").removeClass("error-input");



            $(".modal_prenotazione").each(function ()
            {
                id = $(this).attr('id');
                value = $(this).val()
                to_prenotazione[id] = value;

                if (value == "" && id != "Note")
                {
                    $("#" + id).addClass("error-input");
                    is_valid = false;
                }

                if (id == "bookerEmail")
                {
                    let valid_email = validateEmail(value);

                    if (!valid_email)
                    {
                        $("#" + id).addClass("error-input");
                        is_valid = false;
                    }

                }
            });

            if (!is_valid)
                return 0;

//            alert(JSON.stringify(to_prenotazione));

            if (to_prenotazione['Stato'] == "L")
            {
                changeState = "P";
                to_prenotazione['Importo'] = document.querySelector("#importo_modal").value;
            }

            if (to_prenotazione['Stato'] == "P")
            {
                changeState = "L";
            }

            to_prenotazione['Stato'] == changeState;

            modal.style.display = "none";

            to_prenotazione['changeState'] = changeState;

//            to_prenotazione['recursive'] = is_recursive;
//
            if (is_recursive)
            {
                to_prenotazione['Data'] = date_ricorsive;
            }

//            to_prenotazione['Data'] =  to_prenotazione['Data'][0];

            $.post("/apis/saveBooking", to_prenotazione, function (data)
            {
                console.log(data);
                to_prenotazione = {};
                $(".modal_prenotazione").val("");
                $("#send-request").trigger('click');

                $("#clear-booker").trigger('click');
                $("#clear-booker").hide('fast');




                $.post("/apis/sendEmailBooking", data, function (dataResp)
                {
                    $.post("/apis/aggiornaBookersNewsLetters", function (dataResp)
                    {
                        console.log(dataResp);
                    });
                });

            }, 'json');

        });


        $("#send_modal_edit").click(function ()
        {
            var to_send = {};

            id_booking = document.getElementById("editBookerId").value;
            cognome = document.getElementById("editBookerCognome").value;
            nome = document.getElementById("editBookerNome").value;
            email = document.getElementById("editBookerEmail").value;
            telefono = document.getElementById("editBookerTelefono").value;
            note = document.getElementById("editNote").value;
            pagato = document.getElementById("editPagato").checked ? "1" : "0";

            importo = document.getElementById("importo_modal_edit").value;

            to_send['id_booking'] = id_booking;
            to_send['bookerCognome'] = cognome;
            to_send['bookerNome'] = nome;
            to_send['bookerEmail'] = email;
            to_send['bookerTelefono'] = telefono;
            to_send['Pagato'] = pagato;
            to_send['Note'] = note;
            to_send['Importo'] = importo;


            $.post("/apis/editBooking", to_send, function (data)
            {
                $(".modal_prenotazione_edit").val("");
                span_edit.click();


                $.post("/apis/aggiornaBookersNewsLetters", function (data)
                {
                    console.log(data);
                });

                setTimeout(function ()
                {
                    $("#send-request").trigger('click');
                }, 200);

            }, 'json');

        });

        $("#send_modal_delete").click(function ()
        {
            if (confirm(`Vuoi davvero cancellare la prenotazione?`))
            {

                id_booking = document.getElementById("editBookerId").value

                to_prenotazione['changeState'] = "L";
                to_prenotazione['id'] = id_booking;

                $.post("/apis/saveBooking", to_prenotazione, function (data)
                {
                    console.log(data);
                    to_prenotazione = {};
                    $(".modal_prenotazione").val("");
                    $("#send-request").trigger('click');
                    span_edit.click();
                }, 'json');

            }
            else
            {

            }
        });



//GIUSEPPE 2023-01-17 - - - - - 


        $("#bookerTelefono, #editBookerTelefono").bind('keyup', function (e) // serve per passare da una cella all'altra come se fosse excel
        {
            value = only_numeric($(this).val());
            $(this).val(value);
        });


        /* VERIFICO SE UN VALORE E' NUMERICO  */
        function is_numeric(n)
        {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        function only_numeric(value)
        {

            res = value.split('');
            temp = [];


            res.map(function (val)
            {
                if (is_numeric(val))
                {
                    temp.push(val);
                }
            });

            res = temp.join('');

            return res;
        }

//
//        var listBookers = [
//            {label: "Juventus", category: "North", style:"black-list"},
//            {label: "Inter", category: "North"},
//            {label: "Milan", category: "North"},
//            {label: "Roma", category: "Center"},
//            {label: "Lazio", category: "Center"},
//            {label: "Napoli", category: "South"},
//            {label: "Palermo", category: "South"}
//        ];


        var listBookers = <?= json_encode($listBookers); ?>;

        //https://api.jqueryui.com/autocomplete/#event-change
        $(".tags").autocomplete({
            source: listBookers,
            autofocus: true,
            search: function (event, ui)
            {

                console.log(event);

                setTimeout(() => {
                    ui_corner_all = document.getElementsByClassName("ui-corner-all");

                    for (var i = 0; i < ui_corner_all.length; i++)
                    {

                        var text = ui_corner_all[i].innerText;

                        if (text.includes("→ Blacklist"))
                        {
                            ui_corner_all[i].style.color = 'red';
                        }

                        console.log(ui_corner_all[i]);

                    }
                }, 50);
            },
            select: function (event, ui)
            {
                // ui è il valore selezionato;
                console.log(ui);

                var item = ui.item;

                $(".modal_prenotazione").val("");
                $("#send_modal").show('fast');
                $(".black-list").hide('fast');

                ui.item.value = item['bookerCognome'];
                $("#bookerNome").val(item['bookerNome']);
                $("#bookerEmail").val(item['bookerEmail']);
                $("#bookerTelefono").val(item['bookerTelefono']);

                $(".modal_prenotazione").attr('readonly', true);
                $(".modal_prenotazione").attr('disabled', true);


                $("#Note").attr('readonly', false);
                $("#Note").attr('disabled', false);

                $("#clear-booker").show('fast');

                setTimeout(() => {


                    var blackList = parseInt(item['bookerBlacklist']);
                    if (blackList)
                    {
                        if (confirm("Il noleggiatore selezionato è in blackList!\nDesideri continuare con la prenotazione?"))
                        {
                            $(".black-list").show('fast');
                        }
                        else
                        {
                            $("#clear-booker").trigger('click');
                        }
                    }
                }, 200);


            }
        });





        $("#clear-booker").click(() => {
            $(".modal_prenotazione").val("");
            $(".modal_prenotazione").attr('readonly', false);
            $("#send_modal").show('fast');
            $(".black-list").hide('fast');
            $("#clear-booker").hide('fast');
            $(".modal_prenotazione").attr('disabled', false);
        });

        $("#bookerEmail").keyup(function ()
        {
            var email = $(this).val();
            console.log(email);
//            setTimeout(function ()
//            {
            var url = "/apis/searchEmailBooking";
            $.post(url, {searchEmailBooking: email}, function (data)
            {
                console.log(data);

                if (data['NumEmail'] > 0)
                {
                    $("#bookerEmail").attr('disabled', true);
                    alert("L'indirizzo email digitato è già presente in archivio\nPer continuare la prenotazione inserisci un altro indirizzo email!");
                    $("#bookerEmail").val("");
                    $("#bookerEmail").attr('disabled', false);
                }
                else
                {

                }
            }, 'json');
//            }, 50);



        });

// - - - - - - - - - - - - - -
        function validateEmail(input)
        {

            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (input.match(mailformat))
            {
                return true;
            }
            else
            {
                return false;
            }

        }


    });
</script>

<!--scrip per la gesione della modale-->
<script>

    var modal = document.getElementById("myModal");
    var modalEdit = document.getElementById("myModalEdit");
    var modalSms = document.getElementById("myModalSms");
    var date_init_recursive = "";
    var date_select = "";
    var hour_select = "";
    var minute_select = "";

    var giorni_settimana = ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"];

    var is_recursive = false;
    var date_ricorsive = [];
    var date_init;

// Get the button that opens the modal
    var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];


    var check_recursive = document.getElementById("check_recursive");
    check_recursive.addEventListener('change', check_recursive_function, false);

    var date_recursive = document.getElementById("date_recursive");

    var input_date_recursive = document.getElementById("weekpicker_to");
    input_date_recursive.addEventListener('change', change_input_date_recursive, false);

    var boxInfoDateOccupate = document.getElementById("box-info-date-occupate");
    var boxInfoDateLibere = document.getElementById("box-info-date-libere");
    var infoDateOccupate = document.getElementById("info-date-occupate");
    var infoDateLibere = document.getElementById("info-date-libere");


// When the user clicks on <span> (x), close the modal
    span.onclick = function ()
    {
        modal.style.display = "none";
        //$(".modal_prenotazione").val("");
        var valuesPrenotazione = document.getElementsByClassName("modal_prenotazione");

        var clearBooker = document.getElementById("clear-booker");
        clearBooker.click();

        for (var i = 0; i < valuesPrenotazione.length; i++)
        {
            valuesPrenotazione[i].value = "";
        }

        var element = document.getElementsByClassName("modal_prenotazione");

        for (var i = 0; i < element.length; i++)
        {
            element[i].classList.remove("error-input");
        }

    }

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event)
    {
        var element = document.getElementsByClassName("modal_prenotazione");

        var isExistError = document.getElementsByClassName("error-input");

        if (isExistError.length > 0)
        {
            element.classList.remove("error-input");
        }

        if (event.target == modal)
        {
            modal.style.display = "none";

            //$(".modal_prenotazione").val("");
            let valuesPrenotazione = document.getElementsByClassName("modal_prenotazione");

            for (var i = 0; i < valuesPrenotazione.length; i++)
            {
                valuesPrenotazione[i].value = "";
            }

            var element = document.getElementsByClassName("modal_prenotazione");

            for (var i = 0; i < element.length; i++)
            {
                element[i].classList.remove("error-input");
            }
        }

        /* ************************************************************************** */

        var element_edit = document.getElementsByClassName("modal_prenotazione_edit");


        var isExistErrorEdit = document.getElementsByClassName("error-input");
        if (isExistErrorEdit.length > 0)
        {
            element_edit.classList.remove("error-input");
        }



        if (event.target == modalEdit)
        {
            modalEdit.style.display = "none";


            //$(".modal_prenotazione").val("");
            let valuesPrenotazione = document.getElementsByClassName("modal_prenotazione_edit");

            for (var i = 0; i < valuesPrenotazione.length; i++)
            {
                valuesPrenotazione[i].value = "";
            }


            var element = document.getElementsByClassName("modal_prenotazione_edit");

            for (var i = 0; i < element.length; i++)
            {
                element[i].classList.remove("error-input");
            }
        }
    }



    var span_edit = document.getElementsByClassName("close-edit")[0];

    span_edit.onclick = function ()
    {
        modalEdit.style.display = "none";
        //$(".modal_prenotazione").val("");
        let valuesPrenotazione = document.getElementsByClassName("modal_prenotazione_edit");

        for (var i = 0; i < valuesPrenotazione.length; i++)
        {
            valuesPrenotazione[i].value = "";
        }

        var element = document.getElementsByClassName("modal_prenotazione_edit");

        for (var i = 0; i < element.length; i++)
        {
            element[i].classList.remove("error-input");
        }

    }



    var span_sms = document.getElementsByClassName("close-sms");

    for (var i = 0; i < span_sms.length; i++)
    {
        span_sms[i].addEventListener('click', close_sms, false);
    }

    function close_sms()
    {
        modalSms.style.display = "none";
        //$(".modal_prenotazione").val("");
        var valuesPrenotazione = document.getElementsByClassName("modal_prenotazione_sms");

        for (var i = 0; i < valuesPrenotazione.length; i++)
        {
            valuesPrenotazione[i].value = "";
        }

        var element = document.getElementsByClassName("modal_prenotazione_sms");

        for (var i = 0; i < element.length; i++)
        {
            element[i].classList.remove("error-input");
        }

    }

    function check_recursive_function()
    {

//        var data = new Date(date_select);
//        var timestamp = data.getTime();
//
//        alert(giorni_settimana[data.getDay()]);

        date_ricorsive = [];

        input_date_recursive.value = date_init;

        is_recursive = check_recursive.checked;

        boxInfoDateOccupate.style.display = 'none';

        boxInfoDateLibere.style.display = 'none';

        if (is_recursive)
        {
            date_recursive.style.display = 'block';
        }
        else
        {
            date_recursive.style.display = 'none';
        }
    }


    function change_input_date_recursive()
    {
        //alert(this.value);

        var to_send = {};

        var campo_id = this.getAttribute("campo_id");
        var ora = this.getAttribute("ora");

        to_send['date_init_recursive'] = date_init_recursive;
        to_send['date_end_recursive'] = this.value;
        to_send['campo_id'] = campo_id;
        to_send['ora'] = ora;

        //    alert(to_prenotazione['campo_id']);
        const xhr = new XMLHttpRequest();
//      xhr.open("POST", "https://jsonplaceholder.typicode.com/todos");
        xhr.open("POST", "/admin/campis/analizeRecursiveDate");
        xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

        const body = JSON.stringify(to_send);



        boxInfoDateOccupate.style.display = 'none';
        boxInfoDateLibere.style.display = 'none';

        xhr.onload = () => {

            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var response = JSON.parse(xhr.responseText);
                console.log(response);

                date_occupate = response.date_occupate;
                date_libere = response.date_libere;

                date_ricorsive = response.date_libere_timestamp;

                n_libere = response.date_libere_n;
                n_occupate = response.date_occupate_n;
                n_totali = response.date_totali_n;
                //date = response.date;

                if (parseInt(n_occupate) > 0)
                {
                    // alert("Attenzione ci sono " + n + " date occupate:\n" + date.join('\n'));
                    var text = "Attenzione ci sono " + n_occupate + " date occupate su " + n_totali + ":<br>" + date_occupate.join('<br>');
                    boxInfoDateOccupate.style.display = 'block';
                    if (n_occupate == 1)
                    {
                        text = "Attenzione c'è " + n_occupate + " data occupata su " + n_totali + ":<br>" + date_occupate.join('<br>');
                    }
                    infoDateOccupate.innerHTML = text;
                }

                if (parseInt(n_occupate) == 0)
                {

                }
                if (parseInt(n_libere) == 0)
                {

                }
                else
                {
                    boxInfoDateLibere.style.display = 'block';
                    var text = date_libere.join(', ');
                    infoDateLibere.innerHTML = text;
                }



            }
            else
            {
                console.log(`Error: ${xhr.status}`);
            }
        };

        xhr.send(body);

    }


    function togglePagato()
    {


        if (group_id == 15)
        {
            return 0;
        }

//pagato, id_booking
        var to_send = {};
        to_send['pagato'] = this.getAttribute("pagato");
        to_send['id'] = this.getAttribute("id_booking");

//        this.src = "/img/timmyshare/preloader.gif";
        this.src = "/files/icons/load-icon-png-7963.png";
//        this.src = "https://media2.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif";
        console.log(this);


        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/apis/togglePagato");
        xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        const body = JSON.stringify(to_send);
        xhr.onload = () => {

            if (xhr.readyState == 4 && xhr.status == 200)
            {
                $("#send-request").trigger('click');
            }
            else
            {
                console.log(`Error: ${xhr.status}`);
            }
        };

        xhr.send(body);
    }

</script>


<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>-->





