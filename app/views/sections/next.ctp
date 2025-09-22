<!-- //GIUSEPPE 2019-03-15 -->
<?
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

if ($nameClass == "primary" || $nameClass == "secondary")
    $id_sport = "0";
?>
<!-- --------------------- -->

<div id="main" role="main">
    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul style="margin-bottom: 0" class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Prossime manifestazioni</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="main-custom" style="margin-bottom: 50px; margin-top: 50px;">
    <div class="container">
        <div class="col-md-12">

            <h2 class="text-center">Prossime manifestazioni</h2>
            <hr>

            <p class="lead text-center">
                Scegli tra tutte le manifestazioni in programma il Campionato o il Torneo che più ti piace, scopri le specifiche e procedi direttamente all'iscrizione con la procedura on line!
            </p>

        </div>
    </div>

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


		@media only screen and (min-width: 768px) {
			.next-events-desktop {display:block !important;}
			.next-events-tablet {display:none !important;}
			.next-events-mobile {display:none !important;}
		}

		@media only screen and (max-width: 768px) {
			.next-events-tablet {display:block !important;}
			.next-events-desktop {display:none !important;}
			.next-events-mobile {display:none !important;}
		}

		@media only screen and (max-width: 420px) {
			.next-events-mobile {display:block !important;}
			.next-events-desktop {display:none !important;}
			.next-events-tablet {display:none !important;}
		}
                   

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



    <!-- VISUALIZZA NEXT EVENTS OTTIMIZZATI PER IL DESKTOP ----------------- -->
    <div class="owl-carousel owl-theme full-width  next-events-desktop" data-plugin-options='{"items": 5, "loop": false, "nav": true, "dots": false}'>

        <? foreach ($prossime_manifestazioni as $manifestazioni): ?>

            <!-- //GIUSEPPE 2019-03-15 -->
            <? if ($manifestazioni['Event']['id_sport'] !== $id_sport): ?>
                <? continue; ?>
            <? endif; ?>
            <!-- --------------------- -->

            <div>
                <a href="/sections/manifestazioni/<?= $manifestazioni['Event']['id']; ?>/<?= Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"); ?>">
                    <span class="thumb-info thumb-info-centered-info thumb-info-no-borders">
                        <span class="thumb-info-wrapper">

                            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin: 10px;"> 
                                <img src="/img/project-bg.png" class="img-responsive" alt="">



                                <span style="" class="champ-info"><b>Termine iscrizioni:</b> <?= date("d/m/Y", strtotime($manifestazioni['Event']['data_fine'])); ?><br /><b>Inizio torneo:</b> <?= $manifestazioni['Event']['published_it']; ?></span>

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



    <!-- VISUALIZZA NEXT EVENTS OTTIMIZZATI PER IL TABLET ----------------- -->
    <div class="owl-carousel owl-theme full-width  next-events-tablet" data-plugin-options='{"items": 3, "loop": false, "nav": true, "dots": false}'>

        <? foreach ($prossime_manifestazioni as $manifestazioni): ?>

            <!-- //GIUSEPPE 2019-03-15 -->
            <? if ($manifestazioni['Event']['id_sport'] !== $id_sport): ?>
                <? continue; ?>
            <? endif; ?>
            <!-- --------------------- -->

            <div>
                <a href="/sections/manifestazioni/<?= $manifestazioni['Event']['id']; ?>/<?= Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"); ?>">
                    <span class="thumb-info thumb-info-centered-info thumb-info-no-borders">
                        <span class="thumb-info-wrapper">

                            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin: 10px;"> 
                                <img src="/img/project-bg.png" class="img-responsive" alt="">



                                <span style="" class="champ-info"><b>Termine iscrizioni:</b> <?= date("d/m/Y", strtotime($manifestazioni['Event']['data_fine'])); ?><br /><b>Inizio torneo:</b> <?= $manifestazioni['Event']['published_it']; ?></span>

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


    <!-- VISUALIZZA NEXT EVENTS OTTIMIZZATI PER LO SMARTPHONE ----------------- -->
    <div class="owl-carousel owl-theme full-width  next-events-mobile" data-plugin-options='{"items": 2, "loop": false, "nav": true, "dots": false}'>

        <? foreach ($prossime_manifestazioni as $manifestazioni): ?>

            <!-- //GIUSEPPE 2019-03-15 -->
            <? if ($manifestazioni['Event']['id_sport'] !== $id_sport): ?>
                <? continue; ?>
            <? endif; ?>
            <!-- --------------------- -->

            <div>
                <a href="/sections/manifestazioni/<?= $manifestazioni['Event']['id']; ?>/<?= Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"); ?>">
                    <span class="thumb-info thumb-info-centered-info thumb-info-no-borders">
                        <span class="thumb-info-wrapper">

                            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin: 10px;"> 
                                <img src="/img/project-bg.png" class="img-responsive" alt="">



                                <span style="" class="champ-info"><b>Termine iscrizioni:</b> <?= date("d/m/Y", strtotime($manifestazioni['Event']['data_fine'])); ?><br /><b>Inizio torneo:</b> <?= $manifestazioni['Event']['published_it']; ?></span>

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
</div>