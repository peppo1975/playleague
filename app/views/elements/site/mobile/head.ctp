	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        
    <link rel="stylesheet" href="/css/webfont.css" />
    
	<link rel="stylesheet"  href="/js/mobile/jquery.mobile/jquery.mobile-1.2.0.css" />        
	<link rel="stylesheet"  href="/js/mobile/jquery.mobile/jquery.mobile.midland-theme.css" />
	
	<link rel="stylesheet" href="/css/layout-mobile.css" />

	<link rel="stylesheet" href="/css/layout-mobile-php.css" />
	
	<link rel="stylesheet" href="/css/mobile/photoswipe.css" />	
	
	<script src="/js/mobile/jquery.mobile/jquery.js"></script>

	<script src="/js/mobile/jquery.mobile/jquery.mobile-1.2.0.js"></script>

	<script src="/js/mobile/timmy-lazy.js"></script>
	
	<script src="/js/mobile/layout.js"></script>
    
	<?=$scripts_for_layout;?>
	
    
    <script src="/js/mobile/klass.min.js"></script>
    <script src="/js/mobile/code.photoswipe-3.0.4.min.js"></script>
	
	<script>
        
        $(function() {
        
                $(".home-button li").click(function() {
                
                    $(this).addClass('clicked');
                        
                });
                
                
                
		if(location.pathname != '/mobile') {
                    $(".lang-menu").hide();
                    $("#headline h1").show();
                    
                }
                    
        });
        
$(document).bind("mobileinit", function() { $.mobile.ajaxEnabled = false; $.mobile.ignoreContentEnabled = true; });        
	
	$(document).ajaxStart(function(){
		$.mobile.showPageLoadingMsg();
	 });
	$(document).ajaxStop(function(){
	   $.mobile.hidePageLoadingMsg();
	 });		
	
	$(document).bind("pagechange", function(event, data){
		
		$('html,body').find('iframe').each(function(){
		
			var me = $(this);
			
			var height = me.width()/16*9;
			
			me.css('height',height);
		
		});		
		
		$('body').trigger('loadimages');
		$(document).trigger('imageslider');
		
		$(".home-button li").removeClass('clicked');
                $(".lang-menu li").removeClass('clicked');
                $(".main-menu li").removeClass('clicked');
                $(".back-button li").removeClass('clicked');
                
                
		if(location.pathname == '/mobile')
			{
                                $("#headline h1").hide();
				$('.back-button').css('display', 'none');
                                $(".lang-menu").show();
			}
			
		else
			{
                            
				$('.back-button').css('display', 'block');
                                $(".lang-menu").hide();
                                $("#headline h1").show();
			}
			
		
	});
	
	$(function(){
		
		var orientation = jQuery.event.special.orientationchange.orientation();
		
		$('body').removeClass($('body').attr('data-orientation'));
		
		$('body').attr('data-orientation', orientation);
		$('body').addClass(orientation);				
		
	});
	
	$(document).unbind("orientationchange").bind("orientationchange", function(event, data){
		
		$('body').removeClass($('body').attr('data-orientation'));
		
		$('body').attr('data-orientation', event.orientation);
		$('body').addClass(event.orientation);
		
		$('html,body').find('iframe').each(function(){
		
			var me = $(this);
			
			var height = me.width()/16*9;
			
			me.css('height',height);
		
		});
		
	});		
	
	</script>