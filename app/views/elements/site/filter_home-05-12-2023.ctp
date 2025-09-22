<?
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller
?>



<? if ($classPage['Name'] !== 'quaternary'): ?>
    <section class="filter-<?= @$class; ?> section section-with-divider calendari parallax section section-text-light section-parallax section-center" style="background-image: url(img/custom-header-bg-new.jpg); background-position: 0% -161.5px;"  data-stellar-background-ratio="0.5">




        <div class="container">

            <div class="row">

                <div class="col-lg-12"> 
                    <h2>
                        Calendari e classifiche
                        <span>Tutti i calendari di gioco, risultati, classifiche e disciplinari</span>
                    </h2>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="input <?= $classPage['Name']; ?>" >
                                <label>Seleziona torneo*</label>
                                <select name="campionato_id" id="campionato_id" class="form-control flt">
                                    <option value="">Seleziona torneo...</option>
                                    <? if ($classPage['Name'] == "primary" && !empty($campionati))://if ($class == "primary"): ?>
                                        <? foreach ($campionati as $campionato): ?>
                                            <option value="<?= $campionato['Campionati']['Campionato']; ?>"><?= $campionato['Campionati']['Nome']; ?></option>
                                        <? endforeach; ?>
                                    <? elseif ($classPage['Name'] == "secondary" && !empty($campionati_c5)): ?>
                                        <? foreach ($campionati_c5 as $campionato): ?>adadad
                                            <option value="<?= $campionato['Campionati']['Campionato']; ?>"><?= $campionato['Campionati']['Nome']; ?></option>
                                        <? endforeach; ?>

                                        <!-- non serve più -->
                                    <? elseif ($classPage['Name'] == "quaternary" && !empty($campionati_tennis)): ?>
                                        <? // file_put_contents("campionati_tennis.txt", print_r($campionati_tennis, true)); ?>    
                                        <? foreach ($campionati_tennis as $campionato): ?>
                                            <option value="<?= $campionato['Campionati']['Campionato']; ?>"><?= $campionato['Campionati']['Nome']; ?></option>
                                        <? endforeach; ?>
                                        <!-- ------------- -->

                                    <? endif; ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-4">
                            <div class="input">
                                <label>Seleziona girone*</label>
                                <select name="girone_id" id="girone_id" class="select-girone form-control flt">

                                    <option value="">Seleziona girone...</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input">
                                <label>Seleziona squadra</label>
                                <select name="squadra_id" id="squadra_id" class="select-squadre form-control flt">

                                    <option value="">Seleziona squadra...</option>

                                </select>

                            </div>
                        </div>
                    </div>
                    <!-- //GIUSEPPE 2019-03-15 ---------------------------------- -->


                    <div class="row">

                        <?= $this->element('site/filter_table') ?>

                    </div>
                </div>


            </div>
        </div>



    </section>
<? else: ?> <!-- TENNIS -->
    <section class="section section-with-divider calendari parallax section section-text-light section-parallax section-center filter-tennis" data-stellar-background-ratio="0.5">
        <div class="container">

            <div class="row">

                <div class="col-lg-12"> 

                    <h2 style="">CALENDARI E CLASSIFICHE</h2>






                    <? // session_start() ?>
                    <? // $_SESSION['campionati_tennis'] = $campionati_tennis ?>
                    <? // Session::write('campionati_tennis', $campionati_tennis);?>

                    <?
                    $arr_id_events = array();

                    foreach ($campionati_tennis as $campionato_tennis)
                    {
                        if ($campionato_tennis['Campionati']['Evento'] > 0)
                            $arr_id_events[] = sprintf("(id = '%s')", $campionato_tennis['Campionati']['Evento']);
                    }

                    $filter = "(" . implode(" OR ", $arr_id_events) . ")";
                    ?>
                    <? $prossime_manifestazioni = $this->requestAction('sections/events_tennis/' . $filter); ?>



                    <div class="owl-carousel owl-theme full-width" data-plugin-options='{"items": 5, "margin":10, "loop": false, "nav": true, "dots": false}'>

                        <? foreach ($prossime_manifestazioni as $manifestazioni): ?>

                            <div>
                                <!-- //GIUSEPPE 2022-21-02-->
                                <?
                                $linkEvento = "";
                                $targhetEvento = "";

                                if ((int) $manifestazioni['Event']['IsExternLink'] == 0)
                                {
                                    $linkEvento = sprintf("/sections/manifestazioni_tennis/%s/?s", $manifestazioni['Event']['id'], Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"));
                                }
                                else
                                {
                                    $linkEvento = $manifestazioni['Event']['ExternLink'];
                                    $targhetEvento = 'target = "_blank"';
                                }
                                ?>
                                <!-- ************************ -->

                                <!--<a href="/sections/manifestazioni_tennis/<?= $manifestazioni['Event']['id']; ?>/<?= Inflector::slug(strtolower($manifestazioni['Event']['Nome']), "-"); ?>">-->

                                <!-- //GIUSEPPE 2022-21-02-->
                                <a href="<?= $linkEvento ?>" <?= $targhetEvento ?>>
                                    <!-- ************************ -->
                                    <span class="thumb-info thumb-info-centered-info thumb-info-no-borders" style="border-radius: 5px;">
                                        <span class="thumb-info-wrapper">

                                            <div class="img-responsive img-thumbnail" style="background-image: url(<?= $thumbnail->link(array('path' => $manifestazioni['Upload'][0]['path'], 'w' => 447)); ?>); background-size: contain; background-position: center center; background-repeat: no-repeat; margin: 10px; background-color: #fff; border-color: #fff;"> 

                                                <img src="/img/project-bg.png" class="img-responsive" alt="">

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


                <div class="row">

                    <?= $this->element('site/filter_table') ?>

                </div>
            </div>


        </div>
    </div>







    </section>  
<? endif; ?>

