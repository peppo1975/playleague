  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="IT" lang="IT">


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

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/css/skins/default.css">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/css/custom.css">

		<!-- Head Libs -->
		<script src="/vendor/jquery/jquery.min.js"></script>
		<script src="/vendor/modernizr/modernizr.min.js"></script>


		
		</head>
		<body <?if(isset($id_css)):?><?=($id_css != '')? $id_css:'';?><?endif;?>>
		
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
					
					<div class="error-message"><?=$this->Session->flash();?></div>
					<div id="wrapper-contents">

						<?=$content_for_layout;?>
						
						

					</div><!-- close wrapper-contents -->
					
			
			</div><!-- close container -->
			<?=$this->element('/site/footer');?>
		</body>

</html>
