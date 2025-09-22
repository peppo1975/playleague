$(function(){
	
	/*
	 * .timmy-lazy
	 */
	
	$('body').live('loadimages', function(){
	
		var timmy_lazy = $('img.timmy-lazy');
		
		timmy_lazy.each(function(){
			        
			 var img = $(this);
			 
			 if($.browser.msie) {
	 
				 img.css('visibility','hidden');
	 			img.attr('data-src', img.attr('src'));
	 			img.attr('src','');			 
				 
			 } else {
				 
	 			img.css('opacity',0);
	 			img.attr('data-src', img.attr('src'));
	 			img.attr('src','');
	 
			 }
			 
			var image = new Image();
			image.onload = function() { // always fires the event.
			
				if($.browser.msie) {
				
					img.attr('src',img.attr('data-src')).delay(150).css('visibility','visible');
				
				} else {
					
					img.attr('src',img.attr('data-src')).delay(150).animate({ 'opacity' : 1 }, 900);
					
				}
				
			};
			image.src = img.attr('data-src');
			
		});
	
	});

	$('body').trigger('loadimages');

});