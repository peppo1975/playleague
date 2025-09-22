<?
	App::Import('Helper', 'Text');
	$news_type = Configure::read('option_news_type');
	$news_type_1 = Configure::read('option_news_type_1');
	$news_scuola = Configure::read('option_scuola');
	$news_tennis = Configure::read('option_tennis');
	//$this->Text = new TextHelper();
?>

<?
//GIUSEPPE
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // mi restituisce primary, secondary o quaternary
$news = $news_scuola;
//$last  = $this->requestAction('/bloks/getBlockNewsLast/' . $news_type);
// 

$articoli = $this->requestAction('/blocks/getBlockNews/' . $classPage['Name']);
//GIUSEPPE solo COMMENTO : restituisce un array



$ultima_ora = array();  // Campionati/Tornei
$campionati = array(); // Campionati/Tornei
$news_scuola_articles = []; //scuola calcio 
$news_scuola = "News MGS"; //scuola calcio  

$news_tennis_articles = []; //tennis    
$news_tennis = "News Tennis"; //tennis

switch ($classPage['Name'])
{
    case 'primary':
        // $ultima_ora = $articoli[$news_type_1]; // Campionati/Tornei
        $campionati = $articoli[$news_type]; // Campionati/Tornei
        break;

    case 'secondary':
        $news_scuola_articles = $articoli[$news_scuola]; //scuola calcio
        break;

    case 'quaternary':
        $news_tennis_articles = $articoli[$news_tennis]; //tennis
//         $ultima_ora = $articoli[$news_type_1]; // Campionati/Tornei
//        $campionati = $articoli[$news_type]; // Campionati/Tornei
        break;
}

//print_r($news_tennis_articles);
/*  $utlima_ora = array();  // Campionati/Tornei
  $campionati = array(); // Campionati/Tornei



  $news_scuola_articles = []; //scuola calcio
  $news_scuola = "News MGS";    //scuola calcio

  if (isset($articoli[$news_type_1])) $ultima_ora     = $articoli[$news_type_1]; // Campionati/Tornei
  if (isset($articoli[$news_type])) $campionati    = $articoli[$news_type]; // Campionati/Tornei
  if (isset($articoli[$news_scuola]))   $news_scuola_articles    = $articoli[$news_scuola]; //scuola calcio */ /*    */

// GIUSEPPE : test miei
/* echo "news_type ".$news_type."<br>" ;
  echo "news_type_1 ".$news_type_1."<br>" ;
  echo  $this->requestAction('/server/className/'.$_SERVER["SERVER_NAME"])."</br>";
  echo $_SERVER["SERVER_NAME"]; */
?>

<section class="main-home">
    <section class="section section-default section-footer">
        <div class="container">
            <div class="row">
                 <? if ($classPage['Name'] == 'primary'): ?>

                   <!--  <div class="col-md-12 news-redazione"> -->
                        <h2>News</h2> <!--News dalla redazione-->
						<!--<h2><?= $news_type ?></h2>-->
                        <div class="row mt-xlg">
                            <div class="col-md-12">
                            <div class="owl-carousel owl-theme show-nav-title top-border" data-plugin-options='{"responsive": {"0": {"items": 1}, "479": {"items": 1}, "768": {"items": 2}, "979": {"items": 3}, "1199": {"items": 4}}, "items": 4, "margin": 10, "loop": false, "nav": true, "dots": false}'>

                                    <? foreach ($campionati as $camp): ?>
                                        <?
                                        //print_r($camp);
                                        //echo "<br><br><br>";
                                        //GIUSEPPE 2018-04-30 ---------------------------------------------------------------

                                        if (isset($camp['News']['over']))
                                        {

                                            $over = $camp['News']['over'];

                                            $now = date("Y-m-d H:i:s");

                                            if ($over != "0000-00-00 00:00:00")
                                            {
                                                //echo $over."<br>";

                                                if ($now > $over)
                                                {
                                                    continue;
                                                }
                                            }
                                        }
                                        //-----------------------------------------------------------------------------------
                                        ?>
                                        <div>
                                            <div class="recent-posts">
                                                <article class="post">
                                                    <div class="mr-lg mb-sm">
                                                        <div>
                                                            <div class="img-thumbnail" style="display: block">
                                                                <? //253x99       ?>
                                                                <? if (empty($camp['News']['img_evidenza'])): ?>
                                                                    <img class="img-responsive" src="img//news-placeholder.jpg" alt="">
                                                                <? else: ?>
                                                                    <img class="img-responsive" src="<?= $thumbnail->link(array('path' => $camp['News']['img_evidenza'], 'w' => 253, 'h' => 158, 'zc' => 1)); ?>" alt="">

                                                                <? endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="date">
                                                            <span class="day"><?= date("d", strtotime($camp['News']['published'])); ?></span>
                                                            <span class="month"><?= date("m", strtotime($camp['News']['published'])); ?></span>
                                                        </div>
                                                        <h4><a href="/news/<?= $camp['News']['id']; ?>/<?= Inflector::Slug($camp['News']['title'], '-'); ?>"><?
                                                                echo $this->Text->truncate(
                                                                        ($camp['News']['title']), 50, array(
                                                                    'ending' => '...',
                                                                    'exact' => false
                                                                        )
                                                                );
                                                                ?>
                                                            </a></h4>
                                                        <p>         
                                                            <?
                                                            echo $this->Text->truncate(
                                                                    strip_tags($camp['News']['content']), 160, array(
                                                                'ending' => '...',
                                                                'exact' => false
                                                                    )
                                                            );
                                                            ?><br><a href="/news/<?= $camp['News']['id']; ?>/<?= Inflector::Slug($camp['News']['title'], '-'); ?>" class="cyan-a">Leggi tutto</i></a></p>
                                                </article>
                                            </div>
                                        </div>

                                    <? endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <!--</div>-->


                <? elseif ($classPage['Name'] == 'secondary') : ?>          
                    <!-- <div class="col-md-9 news-redazione-scuolaa5" style="display: none">-->

                    <h2><?= $news ?></h2>
                    <div class="row mt-xlg">
                        <div class="col-md-12">
                            <div class="owl-carousel owl-theme show-nav-title top-border" data-plugin-options='{"responsive": {"0": {"items": 1}, "479": {"items": 1}, "768": {"items": 2}, "979": {"items": 3}, "1199": {"items": 4}}, "items": 4, "margin": 10, "loop": false, "nav": true, "dots": false}'>

                                <? foreach ($news_scuola_articles as $scuola): ?>

                                    <?
                                    //GIUSEPPE 2018-09-05 ---------------------------------------------------------------
                                    
                                    if (isset($scuola['News']['over']))
                                    {

                                        $over = $scuola['News']['over'];

                                        $now = date("Y-m-d H:i:s");

                                        if ($over != "0000-00-00 00:00:00")
                                        {
                                            //echo $over."<br>";

                                            if ($now > $over)
                                            {
                                                continue;
                                            }
                                        }
                                    }
                                    
                                    //-----------------------------------------------------------------------------------
                                    ?>

                                    <div>
                                        <div class="recent-posts">
                                            <article class="post">
                                                <div class="mr-lg mb-sm">
                                                    <div>
                                                        <div class="img-thumbnail" style="display: block">
        <? //253x99        ?>
        <? if (empty($scuola['News']['img_evidenza'])): ?>
                                                                <img class="img-responsive" src="img//news-placeholder.jpg" alt="">
                                                            <? else: ?>
                                                                <img class="img-responsive" src="<?= $thumbnail->link(array('path' => $scuola['News']['img_evidenza'], 'w' => 253, 'h' => 158, 'zc' => 1)); ?>" alt="">

                                                            <? endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="date">
                                                        <span class="day"><?= date("d", strtotime($scuola['News']['published'])); ?></span>
                                                        <span class="month"><?= date("m", strtotime($scuola['News']['published'])); ?></span>
                                                    </div>
                                                    <h4><a href="/blocchi/<?= $scuola['News']['id']; ?>/<?= Inflector::Slug($scuola['News']['title'], '-'); ?>"><?
                                                    echo $this->Text->truncate(
                                                            ($scuola['News']['title']), 50, array(
                                                        'ending' => '...',
                                                        'exact' => false
                                                            )
                                                    );
                                                            ?>
                                                        </a></h4>
                                                    <p>         
                                                            <?
                                                            echo $this->Text->truncate(
                                                                    strip_tags($scuola['News']['content']), 160, array(
                                                                'ending' => '...',
                                                                'exact' => false
                                                                    )
                                                            );
                                                            ?>
                                                        <br><a href="/blocchi/<?= $scuola['News']['id']; ?>/<?= Inflector::Slug($scuola['News']['title'], '-'); ?>" class="cyan-a">Leggi tutto</i></a></p>
                                            </article>
                                        </div>
                                    </div>

    <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <!--</div>-->

<? elseif ($classPage['Name'] == 'quaternary'): ?>
                    <!-- <div class="col-md-9 news-redazione-scuolaa5" style="display: none">-->

                    <h2><?= $news_tennis ?></h2>
                    <div class="row mt-xlg">
                        <div class="col-md-12">
                            <div class="owl-carousel owl-theme show-nav-title top-border" data-plugin-options='{"responsive": {"0": {"items": 1}, "479": {"items": 1}, "768": {"items": 2}, "979": {"items": 3}, "1199": {"items": 4}}, "items": 4, "margin": 10, "loop": false, "nav": true, "dots": false}'>

    <? foreach ($news_tennis_articles as $tennis): ?>
        <?
        //GIUSEPPE 2018-09-05 ---------------------------------------------------------------
        if (isset($tennis['News']['over']))
        {

            $over = $tennis['News']['over'];

            $now = date("Y-m-d H:i:s");

            if ($over != "0000-00-00 00:00:00")
            {
                //echo $over."<br>";

                if ($now > $over)
                {
                    continue;
                }
            }
        }
        //-----------------------------------------------------------------------------------
        ?>
                                    <div>
                                        <div class = "recent-posts">
                                            <article class = "post">
                                                <div class = "mr-lg mb-sm">
                                                    <div>
                                                        <div class = "img-thumbnail" style = "display: block">
        <? //253x99       
        ?>
        <? if (empty($tennis['News']['img_evidenza'])): ?>
                                                                <img class="img-responsive" src="img//news-placeholder.jpg" alt="">
                                                            <? else: ?>
                                                                <img class="img-responsive" src="<?= $thumbnail->link(array('path' => $tennis['News']['img_evidenza'], 'w' => 253, 'h' => 158, 'zc' => 1)); ?>" alt="">

                                                            <? endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="date">
                                                        <span class="day"><?= date("d", strtotime($tennis['News']['published'])); ?></span>
                                                        <span class="month"><?= date("m", strtotime($tennis['News']['published'])); ?></span>
                                                    </div>
                                                    <h4><a href="/blocchi/<?= $tennis['News']['id']; ?>/<?= Inflector::Slug($tennis['News']['title'], '-'); ?>"><?
                                                            echo $this->Text->truncate(
                                                                    ($tennis['News']['title']), 50, array(
                                                                'ending' => '...',
                                                                'exact' => false
                                                                    )
                                                            );
                                                            ?>
                                                        </a></h4>
                                                    <p>         
                                                            <?
                                                            echo $this->Text->truncate(
                                                                    strip_tags($tennis['News']['content']), 160, array(
                                                                'ending' => '...',
                                                                'exact' => false
                                                                    )
                                                            );
                                                            ?>
                                                        <br><a href="/blocchi/<?= $tennis['News']['id']; ?>/<?= Inflector::Slug($tennis['News']['title'], '-'); ?>" class="cyan-a">Leggi tutto</i></a></p>
                                            </article>
                                        </div>
                                    </div>

    <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <!--</div>-->
<? endif ?>
            </div>
        </div>
    </section>
</section>
</div><!-- close first-box -->
<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->