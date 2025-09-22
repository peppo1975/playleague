<?
//$r = $slides[1];
//$ov = new DateTime($r["Upload"]["over"]);
//$ov = ($r["Upload"]["over"]);
//print_r($ov);
//print_r($slides);

$delay = "9000"; //GIUSEPPE
?>

<div class="slider-wrapper" <? if ($class != 'primary'): ?>style="opacity: 0; position: absolute; top: -9999px;"<? endif; ?> data-menu="<?= $class; ?>">

    <div class="slider-container rev_slider_wrapper">
        <div id="revolutionSlider" class="slider rev_slider" data-plugin-revolution-slider data-plugin-options='{"gridwidth": 1170, "gridheight": 500}'>
        <!--<div id="revolutionSlider" class="slider rev_slider" data-plugin-revolution-slider data-plugin-options='{"gridwidth": 1170, "gridheight": 500, "delay": <?= $delay ?>}' >-->
            <ul>


                <? if (empty($slides)) : ?>
                    <? $slides = []; ?>
                <? endif; ?>

                <? foreach ($slides as $slide): ?>
                    <?
                    $published = new DateTime($slide["Upload"]["published"]);
                    $now = new DateTime();

                    //GIUSEPPE 2018-04-27 --------------------------------------
                    $date_over = $slide["Upload"]["over"];

                    if ($date_over == "0000-00-00 00:00:00")
                    {
                        $date_over = "2100-12-31 00:00:00"; //data casuale molto lontana;
                    }

                    $over = new DateTime($date_over);
                    //echo "<script>console.log('".$date_over."')</script>"
                    //---------------------------------------------------------- 
                    ?>
                    <? // if(!$slide["Upload"]["disabled"] && ($now > $published)):   ?>


                    <? if (!$slide["Upload"]["disabled"] && ($now > $published) && ($now <= $over)): ?>
                        <!-- //GIUSEPPE 2018-04-27 -------------------------------------- -->
                        <!-- ------------------------------------------------------------ -->

                        <li data-transition="fade"<? if (!empty($slide['Upload']['link'])): ?>style="cursor: pointer;" onclick="location.href = '<?= $slide['Upload']['link']; ?>';"<? endif; ?>>


                            <!-- //GIUSEPPE 2018-07-25 inserimento video mp4, youtube, vimeo in slides -->
                            <? if ($slide['Upload']['type'] == "image/jpeg"): ?>

                                <img src="<?= $thumbnail->link(array('path' => $slide['Upload']['path'], 'w' => 1920, 'h' => 500, 'q' => 90)); ?>"  
                                     alt=""
                                     data-bgposition="center center" 
                                     data-bgfit="cover" 
                                     data-bgrepeat="no-repeat" 
                                     class="rev-slidebg">


                                <!-- MP4 -->
                            <? elseif ($slide['Upload']['type'] == "video/mp4"): ?>

                                <video id="video1" width="100%" height="100%" controls autoplay="">
                                    <source src="<?= $slide['Upload']['path'] ?>" type="video/mp4">
                                    Your browser does not support the video tag.      
                                </video>


                                <!-- YOUTUBE -->
                            <? elseif ($slide['Upload']['type'] == "video/youtube"): ?>

                                <iframe id="ytplayer" width="100%" height="100%" src="https://www.youtube.com/embed/<?= $slide['Upload']['path'] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                </iframe>

                         

                                <!-- VIMEO -->
                            <? elseif ($slide['Upload']['type'] == "video/vimeo"): ?>

                                <iframe src="https://player.vimeo.com/video/<?= $slide['Upload']['path'] ?>?color=ffffff&title=0&byline=0&portrait=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
                                </iframe>

                            <? endif; ?>

                            <? if ($slide['Upload']['type'] == "image/jpeg"): ?>

                                <? if ($slide['Upload']['effect'] == 0): ?>

                                    <? if (!substr_count($slide['Upload']['name'], ".jpg")): ?>

                                        <div class="tp-caption main-label"
                                             data-x="135"
                                             data-y="210"
                                             data-start="1500"
                                             data-whitespace="nowrap"                        
                                             data-transform_in="y:[100%];s:500;"
                                             data-transform_out="opacity:0;s:500;"
                                             data-mask_in="x:0px;y:0px;"><?= $slide['Upload']['name']; ?>
                                        </div>

                                    <? endif; ?>

                                    <div class="tp-caption bottom-label"
                                         data-x="185"
                                         data-y="280"
                                         data-start="2000"
                                         data-transform_in="y:[100%];opacity:0;s:500;"><?= $slide['Upload']['description']; ?>
                                    </div>

                                <? else: ?>

                                    <? if (!substr_count($slide['Upload']['name'], ".jpg")): ?>

                                        <div class="tp-caption main-label"
                                             data-x="685"
                                             data-y="190"
                                             data-start="1800" 
                                             data-whitespace="nowrap"                        
                                             data-transform_in="y:[100%];s:500;"
                                             data-transform_out="opacity:0;s:500;"
                                             data-mask_in="x:0px;y:0px;"><?= $slide['Upload']['name']; ?>
                                        </div>

                                    <? endif; ?>

                                    <div class="tp-caption bottom-label"
                                         data-x="685"
                                         data-y="250"
                                         data-start="2000"
                                         data-transform_idle="o:1;"
                                         data-transform_in="y:[100%];z:0;rZ:-35deg;sX:1;sY:1;skX:0;skY:0;s:600;e:Power4.easeInOut;"
                                         data-transform_out="opacity:0;s:500;"
                                         data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                         data-splitin="chars" 
                                         data-splitout="none" 
                                         data-responsive_offset="on" 
                                         data-elementdelay="0.05"
                                         style="line-height: 43px"><?= $slide['Upload']['description']; ?>
                                    </div>

                                <? endif; ?>
                            <? endif; ?>

                            <!-- //------------------------------------------- -->

                        </li>
                    <? endif; ?>

                <? endforeach; ?>

            </ul>
        </div>
    </div>

</div>
