<? header('Access-Control-Allow-Origin: *'); ?>
<? //$json = file_get_contents('php://input');                                              ?>


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

<div class="row" style="padding: 20px;">

    <div class="col-lg-8">
        <label for="cars">Campionati:</label>
        <select class="form-select" aria-label="Default select example" name="campionato" id="campionati_menu" >
            <option value="0"></option>
            <?= implode("", $c_t) ?>
        </select>
    </div>

    <div class="col-lg-4">    
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
                    <option value="-1">--- Tutti i gironi ---</option>
                </select>
            </div>
        <? endforeach; ?>
    </div>

</div>
<!--end menu tendina-->

<br>

<div class="btn-group menu-list" role="group" style="display: none; padding: 20px;">
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

<!--    <input type="radio" class="btn-check menu-calendario" value="squalificati" name="btnradio" id="btnSqualificati" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSqualificati">Squalificati</label>-->

    <input type="radio" class="btn-check menu-calendario" value="sanzioni" name="btnradio" id="btnSanzioni" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSanzioni">Sanzioni a squadre</label>

    <input type="radio" class="btn-check menu-calendario" value="bollettini" name="btnradio" id="btnBollettini" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnBollettini">Comunicazioni</label>
    
    <input type="radio" class="btn-check menu-calendario" value="squalificati_a_tempo" name="btnradio" id="btnSqualificatiAtempo" autocomplete="off">
    <label class="btn btn-outline-primary" for="btnSqualificatiAtempo">Squalificati a tempo</label>
</div>

<div id="response" style="padding: 20px;">

</div>

<div id="loading" style="display: none; padding: 20px;">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
//    var to_show_calendar = {} ;
//    console.log(to_show_calendar);

    var giornata = 1;
    var giornate_gironi = {};

    var server = "<?= $server ?>";
    var apiKey = "<?= $apiKey ?>";
    $(function ()
    {
//        alert("TEST");
        var id_campionato = 0;
        var id_girone = 0;

//        var http_https = server == "www.midlandsport.it" ? "https" : "http";
        var http_https = "https";


        $("#campionati_menu").change(function ()
        {
            id_campionato = $(this).val();
            hide_tabelle('switch_campionato');
            $("#result").html("");

        });


        $(".gironi_menu").change(function ()
        {
            id_girone = $(this).val();

            if (parseInt(id_girone) != 0)
            {
                $("#response").html("");
                $("#loading").show();

                axios.get(`${http_https}://${server}/apis/filterCampionatoGirone/?api_key=${apiKey}&id_campionato=${id_campionato}&id_girone=${id_girone}`)
                        .then(function (response)
                        {
                            console.log(response);

                            $("#response").html(response.data['html']);

                            var to_show_calendar = response.data['json'];

//                            giornata = to_show_calendar[id_campionato][id_girone];
                            giornate_gironi = to_show_calendar[id_campionato];

                            hide_tabelle('switch_girone');

                            page_link = document.getElementsByClassName("page-link");

                            for (var i = 0; i < page_link.length; i++)
                            {
                                page_link[i].addEventListener('click', InfoPartite, false);
                            }



                            $("#loading").hide();

                            $(".tabelle").hide();

                            $("#calendario").show();

                        })
                        .catch(function (error)
                        {
                            console.log(error);
                            hide_tabelle('switch_girone');
                        });
            } else
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

        function InfoPartite()
        {

            var value_page = $(this).attr('value_page');

            giornata = $(this).attr('value_page');
            girone = $(this).attr('value_page_girone');
            campionato = $(this).attr('value_page_campionato');

            giornate_gironi[girone] = giornata;

            seleziona_giornata(campionato, girone);
        }

        function seleziona_giornata(id_campionato, id_girone)
        {
//            var giornata = to_show_calendar[id_campionato][id_girone];
            var campionato = id_campionato;
            var girone = id_girone;

            $(`.table-${campionato}-${girone}`).hide();
            $(`.table-${campionato}-${girone}-${giornate_gironi[girone]}`).show();

            $(`.page-item-${campionato}-${girone}`).removeClass('active');
            $(`.page-item-${campionato}-${girone}-${giornate_gironi[girone]}`).addClass('active');

            // $"page-item-{$key_campionato}-{$key_girone}"

            return true;

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
                    //$(".tabelle").hide();
                    $(".menu-list").show(); // dove c'è scritto calendario, marcatori, classifiche
                    $("#btnCalendario").prop("checked", true); // seleziono calendario

                    Object.keys(giornate_gironi).map((id_girone, index) => {
                        seleziona_giornata(id_campionato, id_girone);

                    });
                    $("#calendario").show();
                    break;

                default:
                    break;
            }
        }

    });
</script>