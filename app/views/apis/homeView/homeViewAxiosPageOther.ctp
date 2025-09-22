<? header('Access-Control-Allow-Origin: *'); ?>
<? //$json = file_get_contents('php://input');                                                                                                     ?>


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

    .activeTab
    {
        color: white;
        background-color: #028bce;
    }

    /*    tr:nth-child(even) {
            background-color: #dddddd;
        }*/
</style>

<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" >-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script><!---->

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

    <div class="col-lg-8 gironi-select">
        <label for="cars" style="font-weight: bold; font-size: 18px;">Campionato</label>
        <select class="form-control flt" aria-label="Default select example" name="campionato" id="campionati_menu" >
            <option value="0"></option>
            <?= implode("", $c_t) ?>
        </select>
    </div>

    <div class="col-lg-4 campionati-select">    
        <label for="cars" style="font-weight: bold; font-size: 18px;">Girone</label>
        <div class="c_0 all_gironi">
            <select class="form-control flt  gironi_menu" aria-label="Default select example" name="gironi">
                <option value="0"></option>
            </select>
        </div>
        <? foreach ($g_t as $key_campionato => $list_gironi): ?>
            <div class="c_<?= $key_campionato ?> all_gironi" style="display: none">
                <select class="form-control flt  gironi_menu" aria-label="Default select example" name="gironi">
                    <option value="0"></option>
                    <?= implode("", $list_gironi) ?>
                    <option value="-1">--- Tutti i gironi ---</option>
                </select>
            </div>
        <? endforeach; ?>
    </div>

</div>
<!--end menu tendina-->

<br>

<div class="btn-group menu-list" role="group" style="display: none">

    <!-- Inserire il nome del campionato selezionato -->
    <h3 class="campionato-name" id="labelNomeCampionato">Nome del campionato selezionato</h3> 
    <!-- ////////////////////////////////////////   -->

    <input type="radio" class="btn-check menu-calendario" value="calendario" name="btnradio" id="btnCalendario" checked="">
    <label class="btn btn-outline-primary first-tab tab-home" id="label-calendario" for="btnCalendario">Calendario</label>


    <input type="radio" class="btn-check menu-calendario" value="classifiche" name="btnradio" id="btnClassifiche" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-classifiche" for="btnClassifiche">Classifica</label>

    <input type="radio" class="btn-check menu-calendario" value="marcatori" name="btnradio" id="btnMarcatori" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-marcatori" for="btnMarcatori">Marcatori</label>


    <input type="radio" class="btn-check menu-calendario" value="diffidati" name="btnradio" id="btnDiffidati" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-diffidati" for="btnDiffidati">Diffidati</label>

    <input type="radio" class="btn-check menu-calendario" value="espulsi" name="btnradio" id="btnEspulsi" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-espulsi" for="btnEspulsi">Espulsi</label>

<!--    <input type="radio" class="btn-check menu-calendario" value="squalificati" name="btnradio" id="btnSqualificati" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSqualificati">Squalificati</label>-->

    <input type="radio" class="btn-check menu-calendario" value="sanzioni" name="btnradio" id="btnSanzioni" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-sanzioni" for="btnSanzioni">Sanzioni a squadre</label>

    <input type="radio" class="btn-check menu-calendario" value="bollettini" name="btnradio" id="btnBollettini" autocomplete="off">
    <label class="btn btn-outline-primary tab-home" id="label-bollettini" for="btnBollettini">Comunicazioni</label>

    <input type="radio" class="btn-check menu-calendario" value="squalificati_a_tempo" name="btnradio" id="btnSqualificatiAtempo" autocomplete="off">
    <label class="btn btn-outline-primary last-tab tab-home" id="label-squalificati_a_tempo" for="btnSqualificatiAtempo">Squalificati a tempo</label>
</div>

<div id="response">

</div>

<div id="loading" style="display: none">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="100px" height="100px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <g transform="rotate(0 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(30 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(60 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(90 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(120 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(150 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(180 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(210 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(240 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(270 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(300 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s" repeatCount="indefinite"></animate>
    </rect>
    </g><g transform="rotate(330 50 50)">
    <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#a4fe71">
    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate>
    </rect>
    </g>
    </svg>
</div>



<!--end menu tendina-->

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
//    var to_show_calendar = {} ;
//    console.log(to_show_calendar);

    var giornata = 1;
    var giornate_gironi = {};

    var server = "<?= $server ?>";
    var apiKey = "<?= $apiKey ?>";


    var id_campionato = 0;
    var id_girone = 0;

//        var http_https = server == "www.midlandsport.it" ? "https" : "http";
    var http_https = "https";

    var class_tabelle = document.getElementsByClassName("tabelle");
    var class_all_gironi = document.getElementsByClassName("all_gironi");
    var class_gironi_menu = document.getElementsByClassName("gironi_menu");
    var class_menu_list = document.getElementsByClassName("menu-list");

    var c_campionato;
    var btnCalendario = document.getElementById("btnCalendario");
    var response = document.getElementById("response");
    var loading = document.getElementById("loading");

    var calendario = document.getElementById("calendario");

    var campionati_menu = document.getElementById("campionati_menu");
    campionati_menu.addEventListener('change', (e) => {
        console.log(e);
        id_campionato = e.srcElement.value;
        hide_tabelle('switch_campionato');

        //-------------------------------------------
        var selectedIndex = e.srcElement.options.selectedIndex;
        var textSelected = e.srcElement[selectedIndex].innerText;
        var labelNomeCampionato = document.getElementById('labelNomeCampionato');
        labelNomeCampionato.innerHTML = textSelected;
    });



    var class_menu_calendario = document.getElementsByClassName("menu-calendario");
    var classTabHome = document.getElementsByClassName('tab-home');
    Object.keys(class_menu_calendario).forEach((j) => {

        class_menu_calendario[j].addEventListener('click', (i) => {
            console.log(i.srcElement.value);

            var id = i.srcElement.value;
            var label = document.getElementById('label-' + id);

            Object.keys(class_tabelle).forEach((k) => {
                class_tabelle[k].style.display = 'none';
            });
            document.getElementById(id).style.display = 'block';

            Object.keys(classTabHome).forEach((l) => {
                classTabHome[l].style.backgroundColor = null;
                classTabHome[l].style.color = null;
            });

            label.style.backgroundColor = '#028bce';
            label.style.color = 'white';


        });
    });




    Object.keys(class_gironi_menu).forEach((i) => {
        class_gironi_menu[i].addEventListener('change', (e) => {
            id_girone = e.srcElement.value;

            if (parseInt(id_girone) != 0)
            {
                response.innerHTML = "";
                loading.style.display = 'block';



                axios.get(`${http_https}://${server}/apis/filterCampionatoGirone/?api_key=${apiKey}&id_campionato=${id_campionato}&id_girone=${id_girone}`)
                        .then(function (res)
                        {
                            console.log(res);

//                            $("#response").html(response.data['html']);
                            response.innerHTML = res.data['html'];

                            var to_show_calendar = res.data['json'];

//                            giornata = to_show_calendar[id_campionato][id_girone];
                            giornate_gironi = to_show_calendar[id_campionato];

                            hide_tabelle('switch_girone');

                            page_link = document.getElementsByClassName("page-link");

                            for (var i = 0; i < page_link.length; i++)
                            {
                                page_link[i].addEventListener('click', InfoPartite, false);
                            }



//                            $("#loading").hide();
                            loading.style.display = 'none';

//                            $(".tabelle").hide();
                            Object.keys(class_tabelle).forEach((i) => {
                                class_tabelle[i].style.display = 'none';
                            });

//                            $("#calendario").show();
                            var calendario = document.getElementById("calendario");
                            var btnCalendario = document.getElementById('btnCalendario');
                            calendario.style.display = 'block';
                            btnCalendario.dispatchEvent(new Event('click'));

                            var nomeSquadra = document.getElementsByClassName('nomeSquadra');
                            Object.keys(nomeSquadra).forEach((i) => {
                                nomeSquadra[i].addEventListener('click', (e) => {
                                    var id_squadra = e.srcElement.getAttribute('id_squadra');
//                                    alert();
                                    window.open('squadra/dettaglio/' + id_squadra, '_blank');

                                });
                            });
                        })
                        .catch(function (error)
                        {
                            console.log(error);
                            hide_tabelle('switch_girone');
                        });



            }
            else
            {
                $(".tabelle, .menu-list").hide();

                Object.keys(class_tabelle).forEach((i) => {
                    class_tabelle[i].style.display = 'none';
                });
                Object.keys(class_menu_list).forEach((i) => {
                    class_menu_list[i].style.display = 'none';
                });
            }
        });
    });



    function InfoPartite(e)
    {

        console.log(this.getAttribute('value_page'));

//        var value_page = $(this).attr('value_page');
        var value_page = this.getAttribute('value_page')

//        giornata = $(this).attr('value_page');
        giornata = this.getAttribute('value_page');

//        girone = $(this).attr('value_page_girone');
        girone = this.getAttribute('value_page_girone');

//        campionato = $(this).attr('value_page_campionato');
        campionato = this.getAttribute('value_page_campionato');

        giornate_gironi[girone] = giornata;

        seleziona_giornata(campionato, girone);
    }



    function seleziona_giornata(id_campionato, id_girone)
    {
//            var giornata = to_show_calendar[id_campionato][id_girone];
        var campionato = id_campionato;
        var girone = id_girone;

//        $(`.table-${campionato}-${girone}`).hide();
        var table_campionato_girone = document.getElementsByClassName(`table-${campionato}-${girone}`);
        Object.keys(table_campionato_girone).forEach((i) => {
            table_campionato_girone[i].style.display = 'none';
        });


//        $(`.table-${campionato}-${girone}-${giornate_gironi[girone]}`).show();
        var table_campionato_girone_giornate = document.getElementsByClassName(`table-${campionato}-${girone}-${giornate_gironi[girone]}`);
        Object.keys(table_campionato_girone_giornate).forEach((i) => {
            table_campionato_girone_giornate[i].style.display = 'block';
            table_campionato_girone_giornate[i].classList.add("tabella-girone");
        });

//        $(`.page-item-${campionato}-${girone}`).removeClass('active');
        var page_item_campionato_girone = document.getElementsByClassName(`page-item-${campionato}-${girone}`);
        Object.keys(page_item_campionato_girone).forEach((i) => {
            page_item_campionato_girone[i].classList.remove("active");
        });

//        $(`.page-item-${campionato}-${girone}-${giornate_gironi[girone]}`).addClass('active');
        var page_item_campionato_girone_giornate = document.getElementsByClassName(`page-item-${campionato}-${girone}-${giornate_gironi[girone]}`);
        Object.keys(page_item_campionato_girone_giornate).forEach((i) => {
            page_item_campionato_girone_giornate[i].classList.add("active");
        });

        return true;

    }



    function hide_tabelle(type)
    {
        switch (type)
        {
            case 'switch_campionato':
//                $(".tabelle").hide();// hide tutte le tabelle

                Object.keys(class_tabelle).forEach((i) => {
                    class_tabelle[i].style.display = 'none';
                });

//                $(".all_gironi").hide();// elenco a discesa gironi

                Object.keys(class_all_gironi).forEach((i) => {
                    class_all_gironi[i].style.display = 'none';
                });

//                $(".c_" + id_campionato).show(); // elenco a discesa gironi
                c_campionato = document.getElementsByClassName("c_" + id_campionato);
                Object.keys(c_campionato).forEach((i) => {
                    c_campionato[i].style.display = 'block';
                });

//                $(".gironi_menu ").val(0); // elenco a discesa gironi
                Object.keys(class_gironi_menu).forEach((i) => {
                    class_gironi_menu[i].value = 0;
                });


//                $(".menu-list").hide(); // dove c'è scritto calendario, marcatori, classifiche

                Object.keys(class_menu_list).forEach((i) => {
                    class_menu_list[i].style.display = 'none';
                });

                break;

            case 'switch_girone':
//                $(".menu-list").show(); // dove c'è scritto calendario, marcatori, classifiche
                Object.keys(class_menu_list).forEach((i) => {
                    class_menu_list[i].style.display = 'inline-block';
                });


//                $("#btnCalendario").prop("checked", true); // seleziono calendario
                btnCalendario.checked = true;

                Object.keys(giornate_gironi).map((id_girone, index) => {
                    seleziona_giornata(id_campionato, id_girone);

                });

                var calendario = document.getElementById("calendario");
                calendario.style.display = 'block';
//                $("#calendario").show();
                break;

            default:
                break;
        }
    }

</script>