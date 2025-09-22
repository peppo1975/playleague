				<script type="text/javascript">
			

				</script>
				<? if(isset($lochash)): ?>
				
				<script type="text/javascript">location.hash = '<?=$lochash;?>';</script>
				
				<? endif; ?>

				<link rel="Shortcut Icon" type="image/x-icon" href="/img/website/favicon.ico" />
				<link rel="stylesheet" type="text/css" href="/css/webfonts.css" media="all" />
				<link rel="stylesheet" type="text/css" href="/css/layout.css" media="screen" />
				
				<? if($layout == "tablet"): ?>
				
					<link href="/css/layout_tablet.css" rel="stylesheet" type="text/css" media="screen" />
					
				<? endif; ?>		
				
				<script type="text/javascript">var layout = "<?=$layout;?>";</script>						
				
				<!--[if lt IE 9.]>		
				<link rel="stylesheet" type="text/css" href="/css/layout_ie_78.css" type="text/css" />                
				<![endif]-->
				<!--[if lt IE 8.]>
				<link rel="stylesheet" type="text/css" href="/css/layout_ie.css" type="text/css" />
				<![endif]-->
				
				<link rel="stylesheet" type="text/css" href="/css/timmybox_web.css" media="screen" />
				<link href="/css/Aristo/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" media="screen" />

				<script type="text/javascript" src="/js/jquery-1.5.1.min.js"></script>
				<script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
				<script type="text/javascript" src="/js/jcarousellite_1.0.1c4.js"></script>
				
				<script type="text/javascript" src="/js/layout.js"></script>
				
				<? if($layout == "tablet"): ?>
				
				<script type="text/javascript" src="/js/jquery.touchme.js"></script>				
				<script type="text/javascript" src="/js/layout_tablet.js"></script>
				
				<? endif; ?>
				
				<script type="text/javascript" src="/js/swfobject.js"></script>
				<script type="text/javascript" src="/js/timmygallery.js"></script>
				<script type="text/javascript" src="/js/timmy.js"></script>
				<script type="text/javascript" src="/js/jwplayer.js"></script>
		
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
				  {lang: 'it'}
				</script>
				
				
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-3962222-32']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();



</script>

<script type="text/javascript">

/**
* Function that tracks a click on an outbound link in Google Analytics.
* This function takes a valid URL string as an argument, and uses that URL string
* as the event label.
*/
var trackOutboundLink = function(url) {
   ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
     function () {
     document.location = url;
     }
   });
}

</script>