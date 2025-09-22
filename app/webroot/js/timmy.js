	function timmy_load(pagina, contenitore)
	{
		
		timmyloader('show');
		
							
		if (typeof ajaxLoader == 'function') {
			
			
				ajaxLoader('show');
			
		}
		
		$("#timmybox_container").css('overflow','auto'); 
		
		if ( !pagina || pagina == '') return alert('nessuna pagina impostata!');
		
		if ( !contenitore  ) contenitore = '#timmy_overlay';
				
		if ( $(contenitore).length == 0 ) $('body').prepend('<div id="timmy_overlay"></div>');
		
		$(contenitore).height($(document).height());
		
		$(contenitore).load(pagina, function ()
		{  

			

			 $(contenitore).fadeIn(); 
			$("#timmybox_container").css('margin-left',(($(window).width()/2)-($("#timmybox_container").width()/2)) + 'px');
			if ($("#timmybox_container").height() < $(window).height()) 
			{
				$("#timmybox_container").css('margin-top',$(window).scrollTop()+($(window).height()/2)-($("#timmybox_container").height()/2) + 'px');
			} else {
				$("#timmybox_container").css('margin-top',$(window).scrollTop());
			}	

			timmyloader('hide');
			
					
					if (typeof ajaxLoader == 'function') {
						
						
							ajaxLoader('hide');
						
					}

		});
	}
	
	$("#timmybox_container #timmy_close").live('click',function() {
		
		timmy_close();
		
	});
	
	$(document).keyup(function(e) { if (e.keyCode == 27) timmy_close(); });
	
	function timmy_close(contenitore)
	{
		if ( !contenitore  ) contenitore = '#timmy_overlay';

		$(contenitore).fadeOut(500, function () { $(contenitore).html(''); } );
	}
	
	$("#timmy_overlay").live('click',function(){
	
		timmy_close();
	
	});
	
	$("#timmybox_container").live('click', function(e){
	
		e.stopPropagation();
	
	});
