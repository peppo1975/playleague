<script type="text/javascript" src="/js/layout.js"></script>
<script type="text/javascript">


    $(document).ready(function () {


    });


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
                        <li class=""><a href="/prossime-manifestazioni">Prossime manifestazioni</a></li>
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
</style>

<div id="main-custom" class="container" style="margin-bottom: 0px;">


    <div class="row">
        <div class="col-md-12">
            <div class="portfolio-title">
                <div class="row">
                    <div class="portfolio-nav-all col-md-1">
                        <a href="/prossime-manifestazioni" data-tooltip="" data-original-title="Torna alle prossime manifestazioni"><i class="fa fa-th"></i></a>
                    </div>
                    <div class="col-md-10 center">
                        <h2 class="mb-none"><?= $manifestazione['Event']['Nome']; ?></h2>
                    </div>
                    <!--
                            <div class="portfolio-nav col-md-1">
                            <a href="portfolio-single-project.html" class="portfolio-nav-prev" data-tooltip="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="portfolio-single-project.html" class="portfolio-nav-next" data-tooltip="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a>
                            </div>
                    -->
                </div>
            </div>

            <hr class="tall">
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">



            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazione['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin-top: 10px;"> 
                <img src="/img/project-bg.png" class="img-responsive" alt="">


            </div>


        </div>

        <div class="col-md-9">




            <div style="clear: both;"></div>



            <section class="panel panel-tornei">
                <header class="panel-heading">

                    <h2 class="panel-title">Specifiche manifestazione</h2>
                </header>

                <? $stripper = strip_tags($manifestazione['Event']['content']); ?>
                <? if (!empty($stripper)): ?>

                    <div style="background-color: #fff; padding: 20px; padding-bottom: 0px; border-bottom: 1px solid #eff2f7">
                        <?= $manifestazione['Event']['content']; ?>
                    </div>

                <? endif; ?>


                <div class="panel-body">

                    <?
                    $tipo = array('c5f' => 'Calcio a 5 Femminile', 'c7' => 'Calcio a 7', 'c7f' => 'Calcio a 7 Femminile', 'c5m' => 'Calcio a 5', 'c11' => 'Calcio a 11');

                    $select = array();

                    //echo json_encode($types);
                    //print_r($types);

                    foreach ($types as $i => $type)
                    {

                        //GIUSEPPE 2017-05-16 controllo a quali campionati sono associati gli eventi
                        // controllo se nella tabella campionati è stato associato l' evento, e se e lo stato dell'iscrizione al campionato 
                        // avrò ad esempio: "Array ( [num] => 1 [iscrizioni] => 0 )";  [num] => 1 indica che l'evento è stato associato, [iscrizioni] => 0 indica che le iscrizioni sono chiuse
                        // in realtà servirebbe anche solo "[iscrizioni] => ..." perche se ho 0 o 1 so che c'è una associazione, se ho NULL non c'è nessuna associazione. Ma mi tengo [num] => ... perchè
                        // magari potrebbe servirmi in futuroF

                        $state_event = $this->requestAction('sections/eventChampionship/' . $type['Type']['event_id'] . '/' . $type['Type']['id']); // questo valore lo troviamo nel controller 

                        $type['Type']['iscrizioni_online'] = $state_event['num'];

                        switch ($state_event['num'])
                        {
                            case "0":

                                $type['Type']['stato_iscrizioni_online'] = "0";
                                break;

                            case "1":

                                $type['Type']['stato_iscrizioni_online'] = $state_event['iscrizioni'];
                                break;
                        }

                        // ---------------------------------------------------------------------------

                        $content = json_decode($type['Type']['content'], TRUE);

                        $type = array_merge($type, $content[0]);

                        $types[$i] = $type;
                    }

                    //echo json_encode($types);

                    foreach ($types as $champ)
                    {

                        foreach ($tipo as $key => $tp)
                        {

                            if ($champ[$key] == 1)
                                $select[$key] = $tp;
                        }
                    }

                    $torneoTipo = array();
                    $options = array();
                    foreach ($select as $key => $value)
                    {

                        $torneoTipo[$key] = array();

                        foreach ($types as $champ)
                        {


                            if ($champ[$key] == 1)
                            {

                                $torneoTipo[$key][] = $champ['Type']['id'];

                                $options[$champ['Type']['id']] = $champ['Type']['Nome'];
                            }
                        }
                    }



                    //$options = array('1' => 'STANDARD TOP','2' => 'UNDER 21','3' => 'OVER/EASY','4' => 'ONLY PLAY');


                    ksort($select);
                    ?>


                    <script type="text/javascript">



                        //GIUSEPPE 2017-05-16 - - - - - - - - - - - - - - - 

                        function estrapola_stato_evento(array_json)
                        {
                            var result = new Object();

                            for (i in array_json)
                            {
                                //console.log(array_json[i]['Type']['Nome']);

                                var index = array_json[i]['Type']['id'];

                                var iscrizioni = array_json[i]['Type']['iscrizioni_online'];

                                var stato_iscrizioni = array_json[i]['Type']['stato_iscrizioni_online'];

                                var temp = new Object();

                                temp = {"iscrizioni_online": iscrizioni, "stato_iscrizioni_online": stato_iscrizioni};

                                result[index] = temp;

                            }

                            //console.log(result);

                            return result;
                        }

                        //- - - - - - - - - - - - - - - - - - - - - - - - -






                        $(document).ready(function () {

                            var nomi = <?= json_encode($options); ?>;
                            var tipologia = <?= json_encode($torneoTipo); ?>;

                            //GIUSEPPE 2017-05-16 - - - - - - - - - - - - - - - 

                            var types = <?= json_encode($types); ?>;

                            var state_types = estrapola_stato_evento(types); //

                            //- - - - - - - - - - - - - - - - - - - - - - - - - 

                            $(".itm").click(function () {

                                //alert('test click');

                                console.log(state_types);
                                $(".itm").removeClass("active");
                                $(this).addClass("active");
                                var tipo = tipologia[$(this).data("value")];
                                // GIUSEPPE ...............
                                var countItem = [];
                                var tipoForCount = [];
                                var exitFor;
                                // .........................

                                // GIUSEPPE ...............
                                tipoForCount = tipo;
                                ////2017-07-08
                                var type_offset = 0;

                                for (var indexArr in tipoForCount)
                                {
                                    countItem.push(0);
                                    type_offset++;
                                }

                                // .........................


                                $(".results-box-1").show();
                                $(".pricing-table").html('');
                                $(".results-box").html('');
                                //2017-07-08
                                var offset = {1: "6", 2: "4", 3: "2", 4: "infinity"};
                                var type_class = offset[type_offset];
                                // - - - - -
                                /*var offset = 0;
                                 offset = Math.floor(12 / ((tipo.length) * (4 - tipo.length)));
                                 console.log("length = " + tipo.length);*/
                                for (var i = 0; i < tipo.length; i++)
                                {
                                    //select.append('<option value="' + tipo[i] + '">' + nomi[tipo[i]] + '</option>');
                                    console.log("i =" + i);
                                    console.log("tipo = " + tipo[i]);


                                    //GIUSEPPE 2017-05-16 - - - - - - - - - - - - - - - 

                                    var clone;

                                    if (state_types[tipo[i]]['iscrizioni_online'] === "1")
                                    {
                                        if (state_types[tipo[i]]['stato_iscrizioni_online'] === "1")
                                        {
                                            clone = $(".cloner:first").clone();
                                        }
                                        else if (state_types[tipo[i]]['stato_iscrizioni_online'] === "0")
                                        {
                                            clone = $(".iscr_close:first").clone();
                                        }
                                    }
                                    else if (state_types[tipo[i]]['iscrizioni_online'] === "0")
                                    {
                                        clone = $(".iscr_no_open:first").clone();
                                    }

                                    //- - - - - - - - - - - - - - - - - - - - - - - - - 


                                    // var clone = $(".cloner:first").clone();

                                    clone.show();
                                    clone.find('h3').text(nomi[tipo[i]]);
                                    clone.find('[data-id]').attr('data-id', tipo[i]);
                                    if (i == 0)
                                    {
//                                        clone.addClass('col-md-offset-' + offset);
                                        clone.addClass('col-md-offset-' + type_class);
                                    }


                                    clone.appendTo($(".pricing-table"));
                                    $(".pricing-table").find('.cloner').each(function () {


                                        var cur_id = $(this).find('.btn-swt').attr('data-id');
                                        var tipo = $(".itm.active").data("value").replace("-", "/");
                                        var torneo = cur_id;
                                        var me = $(this);
                                        $.get('/sections/getchamp/' + cur_id + '/?json=1', function (ret) {

                                            //console.log(ret);

                                            for (z in ret)
                                            {
                                                /* ........................................... */
                                                // GIUSEPPE
                                                // se vede piu volte cur_id==5 esce dal ciclo
                                                exitFor = 0;
                                                if (z == 1)
                                                {

                                                    for (var indexArr in tipoForCount)
                                                    {
                                                        if (cur_id == tipoForCount[indexArr])
                                                        {
                                                            countItem[indexArr]++;
                                                            if (countItem[indexArr] > 1)
                                                            {
                                                                exitFor = 1;
                                                                break;
                                                            }

                                                        }
                                                    }
                                                    if (exitFor == 1)
                                                    {
                                                        break;
                                                    }

                                                }

                                                /* ........................................... */
                                                var current = ret[z];
                                                if (current.nome != "")
                                                {

                                                    if (current.testo != "")
                                                    {
                                                        var url = '/sections/getchamp/' + cur_id + '/?json=1&voce=' + z;
                                                        $(me).find('.other-infos').append('<li onclick="timmy_load(\'' + url + '\');" style="cursor: pointer;"><b>' + current.nome + '</b> ' + current.valore + ' <span class="pull-right"><i class="fa fa-info-circle"></i></span></li>');
                                                    }
                                                    else
                                                    {

                                                        $(me).find('.other-infos').append('<li><b>' + current.nome + '</b> ' + current.valore + '</li>');
                                                    }
                                                }
                                            }

                                        }, 'json');
                                    });
                                }
                            });
                            $(".btn-swt[data-id]").live('click', function () {


                                $(".plan").removeClass('most-popular');
                                $(this).closest('.plan').addClass('most-popular');
                                var id = $(this).attr('data-id');
                                if (id != "")
                                {


                                    ajaxLoader('show');
                                    var tipo = $(".itm.active").data("value").replace("-", "/");
                                    var torneo = id;
                                    $.get('/sections/getchamp/' + torneo, function (ret) {

                                        //var testJS = JSON.parse(ret);
                                        $(".results-box").html(ret);
                                        $("img.lazy").lazyload({
                                            threshold: 1500
                                        });
                                        $("html, body").animate({

                                            'scrollTop': $(".results-box").offset().top - 150
                                        }, 500);
                                        ajaxLoader('hide');
                                    }, 'html');
                                }

                            });
                        });

                    </script>

                    <script type="text/javascript">
                        /*
                         
                         $(".accordion-toggle").unbind('click').live('click',function(e) {
                         
                         e.stopPropagation();
                         e.preventDefault();
                         var href = $(this).attr('href');
                         
                         $(".accordion-body").hide();
                         
                         $(href).show();
                         
                         setTimeout(function() {
                         
                         
                         //$(href).show();
                         
                         },500);
                         
                         });
                         */
                    </script>

                    <form class="form-horizontal form-bordered" action="#">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Inizio torneo:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>

                                    <input type="text" readonly value="<?= $manifestazione['Event']['published_it']; ?>" class="form-control">
                                </div>
                            </div>

                            <label class="col-md-3 control-label">Termine iscrizioni:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly value="<?= date("d/m/Y", strtotime($manifestazione['Event']['data_fine'])); ?>" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Seleziona:</label>
                            <div class="col-md-9 tipologia-sport">
                                <div class="list-group">

                                    <? foreach ($select as $key => $val): ?>
                                        <a href="javascript:;;" data-value="<?= $key; ?>" class="itm list-group-item">
                                            <?= $val; ?>
                                        </a>
                                    <? endforeach; ?>
                                </div>

                            </div>
                        </div>

                        <!--                        
                                                        <div class="form-group">
                                                        <label class="col-md-3 control-label">Tipologia torneo:</label>
                                                        <div class="col-md-9 tipologia-torneo">
                                                        <select class="form-control" data-plugin-selectTwo id="ms_example2" autocomplete="off" disabled>
                                                        <option value="" selected>Seleziona tipologia torneo...</option>
                        <? foreach ($select as $key => $val): ?>
                                                                                                                                                                                                                                                                        <option value="<?= $key; ?>"><?= $val; ?></option>
                        <? endforeach; ?>
                                                        
                                                        </select>
                                                        </div>
                                                        </div>
                                                        
                        -->





                    </form>



                </div>
            </section>



        </div>
    </div>
</div>

<div class="col-md-3 cloner" style="display: none;">
    <div class="plan">
        <h3></h3>
        <a class="btn btn-lg btn-primary btn-swt" href="javascript:;" data-id="">Seleziona</a>
        <ul class="other-infos">



<!--                    <li onclick="timmy_load('/sections/getchampinfo');" style="cursor: pointer;"><b>Iscrizione:</b> 15,00 € <span class="pull-right"><i class="fa fa-info-circle"></i></span></li>
        <li><b>Tesseramenti:</b> 15,00 €</li>
        <li><b>Quota campo:</b> 5,00 €</li>
        <li><b>Cauzione:</b> 50,00 €</li>
            -->
        </ul>
    </div>
</div>








<div class="col-md-3 iscr_close" style="display: none;">
    <div class="plan">
        <h3></h3>
        <a class="btn btn-lg btn-primary" href="javascript:;" data-id="">ISCRIZIONI CHIUSE</a>
        <ul class="other-infos">

        </ul>
    </div>
</div>


<div class="col-md-3 iscr_no_open" style="display: none;">
    <div class="plan">
        <h3></h3>
        <a class="btn btn-lg btn-primary" href="javascript:;" data-id="">ISCRIZIONI ONLINE<br>NON ANCORA APERTE</a>
        <ul class="other-infos">

        </ul>
    </div>
</div>






<script type="text/javascript">



</script>
<div class="results-box-1" style="display: none;">



    <section class="parallax section section-parallax mt-none mb-none" data-stellar-background-ratio="0.5" style="background-color: #ddd; background-position: 0% -103.5px;">
        <div class="container">
            <div class="row">
                <div class="pricing-table spaced no-borders">


                </div>

            </div>
        </div>
    </section>


</div>


<div class="container">
    <div class="results-box">

    </div>
</div>



<hr class="tall">

</div>


<h4 class="mb-md text-uppercase text-center">Altre manifestazioni</h4>

<style type="text/css">


    .owl-item .img-thumbnail {
        background-color: #fff;
    }

    .thumb-info .thumb-info-wrapper::after {

        margin: 11px 10px 15px 11px;
        border-radius: 3px !important;
    }

    .owl-item .img-responsive {
        position: relative;
    }

    .owl-item .img-responsive .champ-info {


        position: absolute;
        bottom: 0px;
        width: 98%;
        text-align: center;
        font-size: 13px;
        color: #000;
        padding: 10px 5px;
        background-color: rgba(255,255,255,0.75);
        margin-left: 1px;

    }

    .owl-item .btn {
        text-transform: none;
        margin-top: 20px;
    }

    .thumb-info .thumb-info-title{  font-size: 20px;
                                    font-weight: 300;}

</style>
<!--
        <div class="owl-carousel owl-theme stage-margin" data-plugin-options='{"items": 6, "margin": 10, "loop": false, "nav": true, "dots": false, "stagePadding": 40}'>
        <div>
        
<? foreach ($prossime_manifestazioni as $manifestazioni): ?>
                                                                                                                                                                                                            
                                                                                                                                                                                                                        <h4 class="heading-primary">
    <?= $manifestazioni['Event']['Nome']; ?>
                                                                                                                                                                                                                        </h4>
                                                                                                                                                                                                            
                                                                                                                                                                                                                        <img class="img-thumbnail" alt="" class="img-responsive img-rounded" src="<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 168, 'h' => 168, 'zc' => 1)); ?>" alt="" />
                                                                                                                                                                                                                        </div>
<? endforeach; ?>
        
        
        </div>
        
-->



<div class="owl-carousel owl-theme full-width" data-plugin-options='{"items": 5, "loop": false, "nav": true, "dots": false}'>

    <? foreach ($prossime_manifestazioni as $manifestazioni): ?>

        <div>
            <a href="/sections/manifestazioni/<?= $manifestazioni['Event']['id']; ?>/<?= Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"); ?>">
                <span class="thumb-info thumb-info-centered-info thumb-info-no-borders">
                    <span class="thumb-info-wrapper">

                        <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin: 10px;"> 
                            <img src="/img/project-bg.png" class="img-responsive" alt="">



                            <span style="" class="champ-info"><b>Termine iscrizioni:</b> <?= $manifestazioni['Event']['published_it']; ?><br /><b>Inizio torneo:</b> <?= date("d/m/Y", strtotime($manifestazioni['Event']['data_fine'])); ?></span>

                        </div>
                        <span class="thumb-info-title">
                            <span class="thumb-info-inner"><?= $manifestazioni['Event']['Nome']; ?></span>

                            <span class="btn btn-info" style="">Pi&ugrave; informazioni</span>


                        </span>
                        <!--
                                <span class="thumb-info-action">
                                <span class="thumb-info-action-icon"><i class="fa fa-link"></i></span>
                        </span>-->
                    </span>
                </span>
            </a>
        </div>

    <? endforeach; ?>

</div>							