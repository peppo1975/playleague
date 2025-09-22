 <?php
 $menu_pd = [
"scuola calcio a 5",
"la scuola",
"prima squadra",
"il calcio a 5",
"Juoniores Regionali",
"modulistica",
"costi",
"juniores",
"news"
];
?> 
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="IT" lang="IT">


		<head>
			<!-- Basic -->
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">	

		<title><?=$title_for_layout;?></title>	

		<?=$this->element('/site/metadata');?>
		<?=$this->element('/site/metadata_facebook');?>
		

		<meta name="author" content="timmytag | web oriented services" />
		<meta name="keywords" content="Play League Sport Firenze Calcio a 5 calcio a 7 tornei">
		<meta name="description" content="Play League Sport Firenze - Calcio a 5, calcio a 7, tornei">	

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

		<?php if(in_array(strtolower(@$data['Page']['Genitore']), $menu_pd)):?>
			<link rel="stylesheet" href="/css/theme-elements-secondary.css">
		<?php else: ?>
			<link rel="stylesheet" href="/css/theme-elements.css">
		<?php endif; ?>

		

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
		<link rel="stylesheet" href="/vendor/nivo-slider/default/default.css" media="screen">

		<?php if(in_array(strtolower(@$data['Page']['Genitore']), $menu_pd)):?>
			<link rel="stylesheet" href="/css/skins/default-secondary.css">
		<?php else: ?>
			<link rel="stylesheet" href="/css/skins/default.css">
		<?php endif; ?>

		<!-- Skin CSS -->
		

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/css/custom.css">

		<!-- Head Libs -->

		<script src="/vendor/jquery/jquery.min.js"></script>
		<script src="/vendor/modernizr/modernizr.min.js"></script>


		<!-- Admin Extension Specific Page Vendor CSS -->

		<link rel="stylesheet" href="/porto_admin/vendor/select2/select2.css" />
		<link rel="stylesheet" href="/porto_admin/vendor/select2/select2-bootstrap.css" />


		<script src="/porto_admin/vendor/select2/select2.js"></script>
		<!-- Admin Extension -->

		<script type="text/javascript">

		// Select2



		// Select2
		(function(theme, $) {

			theme = theme || {};

			theme = 'bootstrap';

			var instanceName = '__select2';

			var PluginSelect2 = function($el, opts) {
				return this.initialize($el, opts);
			};

			PluginSelect2.defaults = {
			};

			PluginSelect2.prototype = {
				initialize: function($el, opts) {
					if ( $el.data( instanceName ) ) {
						return this;
					}

					this.$el = $el;

					this
						.setData()
						.setOptions(opts)
						.build();

					return this;
				},

				setData: function() {
					this.$el.data(instanceName, this);

					return this;
				},

				setOptions: function(opts) {
					this.options = $.extend( true, {}, PluginSelect2.defaults, opts );

					return this;
				},

				build: function() {
					this.$el.select2( this.options );

					return this;
				}
			};

			// expose to scope
			$.extend(theme, {
				PluginSelect2: PluginSelect2
			});

			// jquery plugin
			$.fn.themePluginSelect2 = function(opts) {
				return this.each(function() {
					var $this = $(this);

					if ($this.data(instanceName)) {
						return $this.data(instanceName);
					} else {
						return new PluginSelect2($this, opts);
					}

				});
			}

		}).apply(this, [ window.theme, jQuery ]);

		(function( $ ) {

		        'use strict';

		        if ( $.isFunction($.fn[ 'select2' ]) ) {

		                $(function() {
		                        $('[data-plugin-selectTwo]').each(function() {
		                                var $this = $( this ),
		                                        opts = {};

		                                var pluginOptions = $this.data('plugin-options');
		                                if (pluginOptions)
		                                        opts = pluginOptions;


		                                $this.themePluginSelect2(opts);
		                        });
		                });

		        }

		}).apply(this, [ jQuery ]);

			
		</script>

		</head>
		<body style="opacity: 0" <?if(isset($id_css)):?><?=($id_css != '')? $id_css:'';?><?endif;?>>

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
/*
		a:focus{
			background: #fff !important;
		}
		*/
		</style>

		<div id="ajax-loader" ><img src="/img/ajax-loader.gif" alt=""></div>

			<div id="fb-root"></div>
			<script>
			  window.fbAsyncInit = function() {
				FB.init({
				  appId      : '355067737925363',
				  channelUrl : '//<?=$_SERVER['SERVER_NAME'];?>/channel.php',
				  status     : true,
				  cookie     : true, 
				  xfbml      : true  
				});
			
			  };		
			  (function(d){
				 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				 if (d.getElementById(id)) {return;}
				 js = d.createElement('script'); js.id = id; js.async = true;
				 js.src = "//connect.facebook.net/it_IT/all.js";
				 ref.parentNode.insertBefore(js, ref);
			   }(document));
			</script>		
	
	
			<?=$this->element('/site/ads');?>
			<?=$this->element('/site/header');?>
			<div class="clear"></div>
			
			<div id="container" class="view-page">
					
					<div class="error-message text-center" style="margin-bottom: 0px"><?=$this->Session->flash();?></div>
					<div id="wrapper-contents">

						<?=$content_for_layout;?>
						
						

					</div><!-- close wrapper-contents -->
					
			
			</div><!-- close container -->
			<?=$this->element('/site/footer');?>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1692234494395658',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  var login = <?=json_encode($this->Session->read("Login.data"))?>;
 
</script>

		</body>

</html>
