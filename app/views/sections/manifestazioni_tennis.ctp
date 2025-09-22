<script type="text/javascript" src="/js/layout.js"></script>
<script type="text/javascript">


    $(document).ready(function ()
    {


        $("#input_campionato").hide();

        $("#input_girone").hide();




        /* SELEZIONO TORNEO */
        $(".manifestazione_tennis").click(function ()
        {

            var is_italiana = $(this).attr("is_italiana");

            var tabellone = is_italiana.localeCompare("Si");

            var id = $(this).attr("id");

            hide_result_table();

            var campionato = $(this).attr("id");

            var nome_campionato = $(this).html();

            $(".popover").hide();

            $(".result_tab").hide();

            if (tabellone === 0)
            {

                ajaxLoader('show');
//                window.open("/impianti/torneo/0/" + $(this).attr("id"), '_blank');
                $.post("/campis/read_for_tab/" + id, function (data)
                {
//                    
                    var id_teams_rev = JSON.parse(data);
                    //console.log(id_teams_rev);
                    //console.log(data);

                    ordered = order(id_teams_rev);

                    console.log(ordered);

                    $.post("/campis/torneo_tab/" + id, {id_teams_rev: ordered}, function (data)
                    {

                        $(".result_tab").show();
                        $(".response_tab").html(data);
                        $(".gironi").hide();
                        $(".result_table").hide();
                        $("#nome_tab").html(nome_campionato);
                        ajaxLoader('hide');
                    });
                });
                return;
            }

            //console.log($(this));
            $("#input_campionato").val(campionato);

            ajaxLoader('show');

            $.get("/sections/getGironiFromCampionato/" + campionato, function (data)
            {
                hide_gironi();

                $(".gironi").show();

                $("#nome_campionato").html(nome_campionato);

                console.log(data);

                var id_gironi = read_id_gironi(data);

                //read_team_gironi(id_gironi);

                var result = {};

                $.post("/sections/SquadreFromGirone/", {id_gironi: id_gironi}, function (data_squadre)
                {


                    result = JSON.parse(data_squadre);

                    console.log(result);



                    for (i in data)
                    {
                        $(".girone_" + i).show();
                        $(".nome_girone_" + i).html(data[i]['value']);
                        $(".nome_girone_" + i).attr('id', data[i]['id']);


                        var id_girone = data[i]['id'];
                        $(".table_" + i).attr('id', "squadre_girone_" + id_girone);

                        $("#squadre_girone_" + id_girone + " tbody tr").each(function ()
                        {
                            $(this).remove();
                        });

                        for (j in result[id_girone])
                        {
                            var id_team = j.toString();

                            var nome_squadra = result[id_girone][j];

                            $("#squadre_girone_" + id_girone + " tbody").append('<tr><td><a href = "/squadra/dettaglio/' + id_team + '" target="_blank">' + nome_squadra + '</a></td></tr>')
                        }


                    }

                    //selectJSON(data, $(".select-girone"));

                    ajaxLoader('hide');

                });

            }, 'json');
        });


        /* SELEZIONO GIRONE */
        $(".lista_gironi").click(function ()
        {

            var girone = $(this).attr("id");

            $("#input_girone").val(girone);

            ajaxLoader('show');

            //reset($(".select-squadre"));

            var page_get = "/sections/getSquadreFromGirone/" + girone;

            $.get(page_get, function (data)
            {
                showTable();
                selectJSON(data, $(".select-squadre"));
                ajaxLoader('hide');



            }, 'json');


            $(".switch-filters").removeClass('hidden');

            if ($('[name="filter_select"]').val() != 'squadra')
                tab = $('[name="filter_select"]').val();
            else
                tab = 'calendario';

            getFilter($("[name=campionato_id]").val(), $("[name='girone_id']").val(), tab);

            if (tab == 'calendario')
            {

                t = setTimeout(function ()
                {
                    $('.select-squadre').find('.checkbox-unset:visible').trigger('click');
                    $('.switch-button').find('li').removeClass('active');
                    $('.switch-button').find('li[data-value="calendario"]').addClass('active');
                }, 500);

            }
            show_result_table();

            $("#girone_selezionato").html("Girone: " + $(this).html());
        });




        // Cambio di tabella
//        $(document).on('click', ".switch-filters li", function (e)
        $(".switch-filters li").click(function (e)
        {
            //console.log($(this));

            /*find_tab_classifica($(this));*/

            var $target = $(e.target).parent();
            $target.parent().find('li:not(.switch-value)').not($target).removeClass('active');

            $target.addClass('active');

            tab = $target.data("value");
            //if (typeof home != "undefined")
            getFilter($("[name=campionato_id]").val(), $("[name='girone_id']").val(), tab);

        });


    });


    function hide_gironi()
    {
        for (i = 0; i <= 100; i++)
        {
            $(".girone_" + i).hide();
        }
    }

    function hide_result_table()
    {
        $("#nome_girone").hide();
        $(".result_table").hide();
    }

    function show_result_table()
    {
        $("#nome_girone").show();
        $(".result_table").show()
    }


    function read_id_gironi(data)
    {
        var id_gironi = [];
        for (i in data)
        {
            var girone = data[i]['id'];
            id_gironi.push("( SquadreCampionati.GironeCampionato = " + girone + " )");
        }

        //console.log(id_gironi);

        return id_gironi;
    }


    function order(id_teams_rev)
    {


        array_final = create_vect(id_teams_rev.length);
        array_final[0] = id_teams_rev[0];

        for (key_day in array_final)
        {

            if (parseInt(key_day) === id_teams_rev.length - 1)
                break;

            var day_rev = array_final[key_day];

            for ($key_match in day_rev)
            {
                match = day_rev[$key_match];
                //               console.log(match);

                var casa = match['casa'];
                var trasferta = match['trasferta'];
                var temp = {};
                var change_casa = false;
                var change_trasferta = false;
                for (key_match_prev in id_teams_rev[parseInt(key_day) + 1])
                {
                    var prev_day = id_teams_rev[parseInt(key_day) + 1][key_match_prev];



                    if (is_find(casa, prev_day))
                    {
                        array_final[parseInt(key_day) + 1][parseInt($key_match) * 2] = prev_day;
                    }
                    if (is_find(trasferta, prev_day))
                    {
                        array_final[parseInt(key_day) + 1][parseInt($key_match) * 2 + 1] = prev_day;
                    }

                }

            }

        }

        return array_final;
    }


    function create_vect(n)
    {

        var vect = [];

        for (i = 0; i < n; i++)
        {
            vect[i] = [];
            var sub = Math.pow(2, i);
            for (j = 0; j < sub; j++)
            {
                vect[i][j] = []
                vect[i][j] = {"casa": "", "trasferta": ""};
            }

        }
        return vect;
    }

    function is_find(val, prev_day)
    {
        for (k in prev_day)
        {
            if (val === prev_day[k])
                return true;
        }

        return false;
    }

</script>

<link rel="stylesheet" href="/vendor/theme.admin.extension.css">

<link rel="stylesheet" href="/vendor/theme.extension.css">
<div id="main" role="main">
    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul style="margin-bottom: 0" class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <!--<li class=""><a href="/manifestazioni_tennis">Prossime manifestazioni</a></li>-->
                        <li class="active"><?= $manifestazione['Event']['Nome']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .thumb-info-hide-wrapper-bg {
        margin-bottom: 15px;
    }
    .img-thumbnail {
        background-color: #fff;
    }


    .select2-container {
        width: 100%;
    }

    .results-box {
        margin-top: 30px;
    }

    .panel-tornei {

        margin-top: 10px;

    }

    .panel {

        border: 1px solid #ddd;
    }
    .champ-separator {

        border-top: 1px dotted #DDD;
        margin: 0;
        padding: 15px 10px;
        display: block;


    }

    .owl-carousel {
        margin-bottom: 50px;
    }

    .manifestazione_tennis{
        cursor: pointer;
    }

    .manifestazione_tennis{
        cursor: pointer;
    }

    .lista_gironi{
        cursor: pointer;
    }

    .panel-title:hover
    {
        color: blue;
        /*background-color: yellow;*/
    }
    /* .panel-heading:hover
     {
         color: blue;
        background-color: yellow;
     }*/


</style>

<div id="main-custom" class="container" style="margin-bottom: 0px;">

    <div class="row">
        <div class="col-md-3"><input name="campionato_id" id="input_campionato"></div>
        <div class="col-md-3"><input name="girone_id" id="input_girone"></div>
    </div>









    <!-- TITOLO MANIFESTAZIONE -->
    <div class="row">
        <div class="col-md-12">
            <div class="portfolio-title">
                <div class="row">
                    <div class="portfolio-nav-all col-md-1">
                        <a href="/" data-tooltip="" data-original-title="Torna alla home"><i class="fa fa-th"></i></a>
                    </div>
                    <div class="col-md-10 center">
                        <h2 class="mb-none"><?= $manifestazione['Event']['Nome']; ?></h2>
                    </div>
                </div>
            </div>

            <hr class="tall">
        </div>
    </div>



       <style>


            .torneo-main{
                padding: 10px 0;
                border: 1px solid #eee;
                border-radius: 5px;
            }

            .panel.panel-campionati{
                border: none;
            }

            .panel-campionati .panel-heading{
                background: #f6f6f6;
                border-radius: 5px;
                border: 1px solid #DADADA;
                padding: 18px;
                position: relative;
                margin-bottom: 2px;
            }

            .panel-campionati .panel-heading:hover{
                background: #333;
            }

            .panel-campionati .panel-heading:hover .panel-title{
                color: #fff;
            }

            .main-content-torneo{
                margin-bottom:50px;
            }

            .campionato-title{
                text-align: center;
            }

            .col-md-12.gironi,
            .col-md-12.result_tab{
                margin-top: 40px;
            }

            .panel-tornei .panel-heading{
                text-align: center;
            }

            .panel-tornei .panel-heading:hover .panel-title{
                color: #33353F !important;
            }

            .gironi-list {
                padding: 20px;
            }

            .girone-header {
              background: #c6ca21;
              border-radius: 5px;
              border-bottom: none;
              border: 1px solid #CCC;
              text-align: center;
              padding: 10px;
            }
            
            .girone-header:hover{
              background: #333;
            }

            .girone-header h4,
            .girone-header:hover h4{
                color: #fff !important;
                margin-bottom: 0px;
            }

            .girone-selezionato{
                padding: 10px;
                text-align: center;
                border-radius: 0px;
                border-bottom: 1px solid #DADADA;

            }

            .girone-selezionato:hover .panel-title{
                color: #33353F;
            }

            .filter-container.tabs,
            #tournament.torneo-tennis{
                    margin-bottom: 100px !important;
            }

            .border-bottom{
                    padding-bottom: 10px;
                    background: #fafafa;
                    padding-top: 10px;
                    border-radius: 5px;
                    border: 1px solid #DADADA;
            }

            .table>tbody>tr>td{
                border-top: none !important;
                border-bottom: 1px solid #ddd !important;
            }

            .table>tbody>tr>td a{
                color: #333;
            }


    </style> 






    <!-- CONTENUTI EXTRA-->
    <div class="row">
        <!--<h2 class="panel-title"><strong><div id="nome_girone"></div></strong></h2>-->
        <div class="col-md-3">

        </div>
        <div class="col-md-12" style="align-content: center">
            <?= $manifestazione['Event']['content'] ?>
        </div>
    </div>



    <div class="row main-content-torneo">
        
     <div class="col-md-12 torneo-main">
        <div class="col-md-3">

            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazione['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin-top: 10px;"> 
                <img src="/img/project-bg.png" class="img-responsive" alt="">
            </div>

        </div>

        <div class="col-md-9">

            <div style="clear: both;"></div>

            <section class="panel panel-campionati">

                <? // file_put_contents("campionati_tennis.txt", print_r($campionati_tennis,true)) ?>
                <? foreach ($campionati_tennis as $campionato): ?>
                    <? if ($campionato['Campionati']['Evento'] === $categoria): ?>

                        <header class="panel-heading">
                            <h2 class="panel-title"><div class="manifestazione_tennis" id="<?= $campionato['Campionati']['Campionato'] ?>" is_italiana="<?= $campionato['Campionati']['Italiana'] ?>"><?= $campionato['Campionati']['Nome'] ?></div></h2>
                        </header>

                    <? endif; ?>
                <? endforeach; ?>



            </section>

        </div>
    </div>




        <div class="col-md-12 gironi" hidden="">

            <h2 class="mb-none campionato-title"><div id="nome_campionato"></div></h2>

            <section class="panel panel-tornei">

                <header class="panel-heading">
                    <h2 class="panel-title">Seleziona il girone di interesse</h2>
                </header>
                <div class="row gironi-list">
                    <? for ($i = 0; $i <= 100; $i++): ?>

                        <div class="col-md-3 girone_<?= $i ?>" hidden="">

                            <header class="panel-heading girone-header">
                                <h4 class="girone-list-title">
                                    <div class="lista_gironi nome_girone_<?= $i ?>" id=""></div>
                                </h4>
                            </header>
                            <table class="table table_<?= $i ?>">
                                <tbody>
                                </tbody>
                            </table>
                        </div>


                    <? endfor; ?>
                </div> 

            </section>

            <h2 class="panel-title"><div id="nome_girone"></div></h2>
        </div>

        <div class="col-md-12 result_tab" hidden="">
            <h2 class="mb-none campionato-title border-bottom"><div id="nome_tab"></div></h2>
        </div>


    </div>



    <div class="row result_table" hidden="">

        <div class="col-md-12 girone-selezionato" style="align-content: center">
            <h2 class="panel-title"><div id="girone_selezionato"></div></h2>
        </div>

        <div class="col-md-12" style="align-content: center">
            <?= $this->element('site/filter_table') ?>
        </div>
    </div>


    <div class="row result_tab" hidden="">

        <div class="response_tab">

        </div>
    </div>


</div>







