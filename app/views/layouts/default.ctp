<?php
//GIUSEPPE  2017-05-27 -> filtra la classe

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$sport = array("primary" => "CALCIO", "secondary" => "SCUOLA_CALCIO_5", "quaternary" => "TENNIS");

$type_sport = $sport[$nameClass];
//echo "- - - -".$type_sport;
/*
  use PHPHtmlParser\Dom;

  $dom = new Dom;


  $dom->loadFromUrl("http://store.midlandsport.it/");
  $header_links = $dom->find("#header_links");
 */

?>

<?
//GIUSEPPE
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // mi restituisce primary, secondary o quaternary
?>

<!DOCTYPE html>
<html>
    <head>

        <!-- Basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">	
        <? if ($type_sport == "CALCIO"): ?>
            <title>Play League Sport Firenze - Calcio a 5, calcio a 7, tornei</title>	
            <meta name="description" content="Play League Sport Firenze - Calcio a 5, calcio a 7, tornei">
        <? elseif ($type_sport == "SCUOLA_CALCIO_5"): ?>
            <title>Play League Sport Firenze - Calcio a 5, calcio a 7, tornei</title>
            <meta name="description" content="Play League Sport Firenze - Calcio a 5, calcio a 7, tornei">
        <? elseif ($type_sport == "TENNIS"): ?>
            <title>Play League Sport Firenze - Calcio a 5, calcio a 7, tornei</title>
            <meta name="description" content="Play League Sport Firenze - Calcio a 5, calcio a 7, tornei">
        <? endif; ?>

        <meta name="author" content="timmytag | web oriented services" />
        <meta name="keywords" content="Play League Sport Firenze, alcio a 5, calcio a 7, tornei">
        							
        <!-- Favicon -->
        <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/vendor/simple-line-icons/css/simple-line-icons.min.css">
        <link rel="stylesheet" href="/vendor/owl.carousel/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="/vendor/owl.carousel/assets/owl.theme.default.min.css">
        <link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.min.css">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="/css/theme.css">
        <link rel="stylesheet" href="/css/theme-elements.css">
        <link rel="stylesheet" href="/css/theme-blog.css">
        <link rel="stylesheet" href="/css/theme-shop.css">
        <link rel="stylesheet" href="/css/theme-animate.css">

        <!-- Current Page CSS -->
        <link rel="stylesheet" href="/vendor/rs-plugin/css/settings.css" media="screen">
        <link rel="stylesheet" href="/vendor/rs-plugin/css/layers.css" media="screen">
        <link rel="stylesheet" href="/vendor/rs-plugin/css/navigation.css" media="screen">
        <link rel="stylesheet" href="/vendor/circle-flip-slideshow/css/component.css" media="screen">

        <!-- Skin CSS -->
        <link rel="stylesheet" href="/css/skins/default.css">

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="/css/custom.css">

        <!-- Head Libs -->
        <script src="/vendor/modernizr/modernizr.min.js"></script>



        <!-- Admin Extension Specific Page Vendor CSS -->

        <script src="/vendor/jquery/jquery.min.js"></script>

        <link rel="stylesheet" href="/porto_admin/vendor/select2/select2.css" />
        <link rel="stylesheet" href="/porto_admin/vendor/select2/select2-bootstrap.css" />
        <!-- Admin Extension Specific Page Vendor -->
        <link rel="stylesheet" href="/porto_admin/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />

        <script src="/porto_admin/vendor/select2/select2.js"></script>


        <!-- Admin Extension -->

        <script type="text/javascript">

            // Select2



            // Select2
            (function (theme, $) {

                theme = theme || {};

                theme = 'bootstrap';

                var instanceName = '__select2';

                var PluginSelect2 = function ($el, opts) {
                    return this.initialize($el, opts);
                };

                PluginSelect2.defaults = {
                };

                PluginSelect2.prototype = {
                    initialize: function ($el, opts) {
                        if ($el.data(instanceName))
                        {
                            return this;
                        }

                        this.$el = $el;

                        this
                                .setData()
                                .setOptions(opts)
                                .build();

                        return this;
                    },

                    setData: function () {
                        this.$el.data(instanceName, this);

                        return this;
                    },

                    setOptions: function (opts) {
                        this.options = $.extend(true, {}, PluginSelect2.defaults, opts);

                        return this;
                    },

                    build: function () {
                        this.$el.select2(this.options);

                        return this;
                    }
                };

                // expose to scope
                $.extend(theme, {
                    PluginSelect2: PluginSelect2
                });

                // jquery plugin
                $.fn.themePluginSelect2 = function (opts) {
                    return this.each(function () {
                        var $this = $(this);

                        if ($this.data(instanceName))
                        {
                            return $this.data(instanceName);
                        }
                        else
                        {
                            return new PluginSelect2($this, opts);
                        }

                    });
                }

            }).apply(this, [window.theme, jQuery]);

            (function ($) {

                'use strict';

                if ($.isFunction($.fn[ 'select2' ]))
                {

                    $(function () {
                        $('[data-plugin-selectTwo]').each(function () {
                            var $this = $(this),
                                    opts = {};

                            var pluginOptions = $this.data('plugin-options');
                            if (pluginOptions)
                                opts = pluginOptions;


                            $this.themePluginSelect2(opts);
                        });
                    });

                }

            }).apply(this, [jQuery]);


        </script>



    </head>
    <body style="opacity: <?=APP_ENV != 'prod' ? 1 : 0?>">
        <style>
            .table.table-bordered{
                background: #fff;
            }
            .yellow a{
                background: #FFF5A1
            }


            #ajax-loader {
                display: none;
                background-color: rgba(0,0,0,0.3);

                width: 100%;
                height: 100%;

                position: fixed;

                z-index: 1000;

                display: none;

            }

            #ajax-loader img {

                position: fixed;
                top: 50%;
                left: 50%;
                margin-left: -8px;
                margin-top: -5.5px;

            }

            a:focus{
                background: #fff !important;
            }
        </style>

        <div id="ajax-loader" ><img src="/img/ajax-loader.gif" alt=""></div>

        <div class="body">


            <?= $this->element('/site/ads'); ?>
            <?=$this->element('/site/header', ["home" =>true, "classPage" => $classPage]);?>

            <div role="main" class="main">

                <? if ($classPage['Name'] == "primary"): //if (count($slides)): ?>

                    <?= $this->element('site/slides', array('class' => 'primary', 'slides' => $slides)); ?>

                    <? //endif; ?>

                <? elseif ($classPage['Name'] == "secondary")://if (count($slides_c5)): ?>

                    <?= $this->element('site/slides', array('class' => 'secondary', 'slides' => $slides_c5)); ?>

                <? elseif ($classPage['Name'] == "quaternary")://if (count($slides_c5)): ?>

                    <?= $this->element('site/slides', array('class' => 'quaternary', 'slides' => $slides_tennis)); //GIUSEPPE?>

                <? endif; ?>


                <?= $this->element('site/filter_home', array('campionati' => $campionati, 'class' => 'primary')); ?>

                <? if ($classPage['Name'] == "primary"): //if (count($slides)): ?>
                    <?= $this->element('/site/prenotazioni'); ?>
                    <? //endif; ?>

                <? elseif ($classPage['Name'] == "quaternary"): ?>
                    <?= $this->element('/site/prenotazioni-tennis'); ?>
                <? endif; ?>

                <!-- FINE BLOCCO GIUSEPPE -->


                <?=$this->element('/site/news', ['classPage' => $classPage]);?>


                <? if ($classPage['Name'] == "primary"): //if (count($slides)): ?>
                <section class="section social">
                    <div class="container">
                            <div class="featured-boxes featured-boxes-style-4">
                                    <div class="row">
                                            <div class="col-md-6 hidden-xs hidden-sm">
                                                    <div class="featured-box featured-box-primary featured-box-effect-6" style="height: 194px;">
                                                            <div class="box-content">
                                                                    
                                                                    <div class="row">
                                                                            <div class="col-md-12">
                                                                                    <i class="icon-featured fa fa-tv pull-left mr-lg"></i> <h2 class="align-left">Midland Playlist</h2>
                                                                            </div>
                                                                    </div>
                                                                    
                                                              
                                                                    
                                                            </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="featured-box featured-box-primary featured-box-effect-6" style="height: 194px;">
                                                            <div class="box-content">
                                                                    <div class="col-md-12">
                                                                            <i class="icon-featured fa fa-youtube pull-left mr-lg"></i> <h2 class="align-left">Midland YouTube Channel</h2>
                                                                    </div>
                                                                    
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                            </div>
                            
                            
                    </div>
                </section>
                <? endif; ?>



            </div>
            <script>
                home = true;
            </script>
            <?=$this->element('/site/footer', ['classPage' => $classPage]);?>
    </body>
</html>						