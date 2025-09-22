
		/*
		$(document).bind('touchstart', function(e) {
	

			
			
			
		});	
		*/
		
		$(document).bind('touchend', function(e) {

			if($(e.target).closest('.login-form').length == 0)
			{
	
				t = setTimeout(function(){
				
					$('.login-form').fadeOut('fast');
				
				},2);
				
			}
			
			if($(e.target).closest('.signup-select').length == 0) {
			
				t = setTimeout(function(){
				
					$('.signup-select').fadeOut('fast');
					
				},2);			
			
			}

			if($(e.target).closest('.select-filter').length == 0 && $(e.target).closest('.select-box').length == 0) {
			
				t = setTimeout(function(){
				
					$('.values-of-select').hide();
					
				},2);			
			
			}

			if($(e.target).closest('#timmy_container').length == 0 && $(e.target).closest('#timmybox_container').length == 0) {
			
				t = setTimeout(function(){
				
					$('#timmy_overlay').remove();
					
				},2);			
			
			}
		
		});		
	
		//Galleries gestures
		
		$(function(){
			
			$('.gallery-container').touchme({
				
				isDetectHorizontalMovement:true,
				isDetectVecticalMovement:false,
				isDetectDiagonalMovement:false,
				wipeRight: function(target) {
				
					$('.image-gallery-left-btn').trigger('click');
					
				},
						
				wipeLeft: function() {

					$('.image-gallery-right-btn').trigger('click');					
					
				},
				
			});		
			
		});