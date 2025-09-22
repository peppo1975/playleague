	// TIMMY GALLERY 2.0
	
	

	var timmygallery_max_width  = 1000;
	var timmygallery_max_height = 600;
	var timmygallery_tag = "timmygallery";
	var timmygallery_display_arrows = 1;
	var timmygallery_display_info = 1;
	var timmygallery_display_close = 1;
	var timmygallery_fullscreen = 0;
	
	var timmygallery_contents = new Array();
	var timmygallery_total_div = new Array();
	var timmygallery_timmygallery_id_div = '';
	var timmygallery_descriptions = new Array();
	var timmygallery_videos = new Array();

	var timmygallery_right_arrow = '/img/timmybox/btn_dx.png';
	var timmygallery_left_arrow = '/img/timmybox/btn_sx.png';
	var timmygallery_close_arrow = '/img/timmybox/close.png';
	var timmygallery_blank = '/img/timmybox/blank.gif';
	var timmy_urls = 0;
	var timmygallery_first_time = 1;
	var timmygallery_mousey = 0;
	var timmygallery_wheel = 0;
	var timmygallery_current_index = 0;
	var timmygallery_current_descindex = 0;

	var timmygallery_video_running = 0;
	var timmygallery_old_running = 0;
	
	var timmygallery_assoc_vid = new Array();
	var timmygallery_assoc_img = new Array();
	
	function timmy_set_mousey(e) {
	
	if (timmygallery_wheel == 0) timmygallery_mousey = e.pageY; 
	
	
	}

   
   if (typeof $ != "undefined") {


	$(document).ready(function(e) {
					
	$("#timmy_container").live('click', function(e){
	
		e.stopPropagation();
	
	});	
					
			$(document).bind('mousemove',timmy_set_mousey);

			$("*[rel='" + timmygallery_tag + "']").each(function() {
							

				 var descrizione = '';
				 
				 $(this).removeAttr('onClick');
				 
				 if ($(this).attr('url') != undefined) {
					 
					timmy_urls++;
					 
					 $(this).attr('link',timmygallery_blank);
					 
				 }
				 
				 /*
				 
				 $(this).find('.timmy_description').each(function() {
					
						
						descrizione = $(this).html();
			
						
				 });
				 
				 */
				 
				 timmygallery_descriptions.push($(this).find('.timmy_description').html());

				 var actindex = timmygallery_descriptions.length-1;

						
				

				if  ($(this).attr('url') == undefined) {
					 if ($(this).hasClass('is_video')) {
						timmygallery_videos.push($(this).attr('link'));
						var content_id = timmygallery_videos.length-1;
						var is_video = 1;
					 } else {
					 
						timmygallery_contents.push($(this).attr('link'));
						var content_id = timmygallery_contents.length-1;
						var is_video = 0;
					}
				}
				if($(this).attr('name') !== undefined) {

						 $(this).attr('href','javascript:;');
						 
				}


						if (is_video == 0) {
							
							timmygallery_assoc_img[content_id] = actindex;
							
						} else {
							
							timmygallery_assoc_vid[content_id] = actindex;
							
						}

				 $(this).bind('click',function(e) {
				 
				 /*se le immagini sono raggruppate conto solo il gruppo e non tutto!!!!!!!!*/
				 if($(this).parents('.gruppo').length > 0) timmy_urls = 0; 
				 /**/
					
					if (is_video) timmygallery_video_running = 1;
					else 		  timmygallery_video_running = 0;
					
					timmygallery_contents = new Array();
					timmygallery_id_div = $(this).closest('.gruppo').attr('id');		
					var indice = 0;
					var old = $(this);
					var i = 0;
					

					$("*[rel='timmygallery']").each(function(index) {
	
						
							if($(this).closest('.gruppo').attr('id') == $(old).closest('.gruppo').attr('id') && !$(this).hasClass('is_video')) i++;
							
							if ($(this).attr('link') == $(old).attr('link')) indice = i; 
					
					});
									
				   $("*[rel='timmygallery']").each(function(index) {
				   
					if($(this).closest('.gruppo').attr('id') == timmygallery_id_div && !$(this).hasClass('is_video')) {
					
						timmygallery_contents.push($(this).attr('link'));

					}
										
				   });

					var j = 0;
					var ind = 0;
				   
					$("*[rel='timmygallery']").each(function(index) {
	
						
							if($(this).closest('.gruppo').attr('id') == $(old).closest('.gruppo').attr('id') && $(this).hasClass('is_video')) j++;
							
							if ($(this).attr('link') == $(old).attr('link')) ind = j; 
					
					});
									
				   $("*[rel='timmygallery']").each(function(index) {
				   
					if($(this).closest('.gruppo').attr('id') == timmygallery_id_div && $(this).hasClass('is_video')) {
					
							timmygallery_videos.push($(this).attr('link'));

					}
										
				   });

						if(is_video == 0) { var content_id = indice-1; is_video = 0; }
						else var content_id = ind-1;

					if ($(this).attr('url') != undefined) {
					
					if (typeof ajaxLoader == 'function') {
						
						
							ajaxLoader('show');
						
					}
					
					 $.get($(this).attr('url'),function(data) {

						 descrizione = data;

					
					if (typeof ajaxLoader == 'function') {
						
						
							ajaxLoader('hide');
						
					}
				
						 //timmygallery_descriptions[content_id] = descrizione;
						 
						 timmy_display_text(descrizione);
						 
					 });
					
					} else {
						

						timmy_display_image(content_id,is_video,actindex);
					}
					 
				 });
				
			});
			
	});
	
	
	function timmy_wheel_active() {
		
			timmygallery_wheel = 1;
			return false;
		
	}
	
	function timmy_wheel_deactive() {
		
			timmygallery_wheel = 0;
	}
	
	function timmy_display_text(descr) {
	
				/*HACK per non duplicare il contenuto della timmybox*/
				$("#timmy_overlay").remove();
				/**/
	
				var current_top = $(window).scrollTop();
				var current_w = $(window).width();
				var current_h = $(window).height();
	 
				var html_block = '<div id="timmy_container"><div id="timmy_close" class="button_up"><img src="' + timmygallery_close_arrow + '" /></div>';
		
	 
				$('html').find('body').append('<div id="timmy_overlay"></div>').end().
						  find('#timmy_overlay').html(html_block).fadeIn('fast',function() { 				
											
								timmy_overlay_adjust();
								
								$("#timmy_container").css('min-width', 300);
					
								$("#timmy_container").append(descr);
								$("#timmy_container").css('margin-left', -($("#timmy_container").width()/2));
								$("#timmy_container").css('left','50%');
								$("#timmy_container").css("top",$(window).scrollTop()+$(window).height()/2);
								$("#timmy_container").css('margin-top',-($("#timmy_container").height()/2));
								$("#timmy_container").css('text-align','left');
								$("#timmy_container").fadeIn();


								$("#timmy_close").fadeIn();
								$("#timmy_close").bind('click',timmy_display_close);
				});
			
						
			 
		
	}
	
	
	function timmy_check_click(e) {
			
			var mousex = e.pageX;
			var mousey = e.pageY;

			if (!timmygallery_first_time && !timmygallery_fullscreen) {

								


				var cnt_width = $("#timmy_container").width()+15;
				var cnt_height = $("#timmy_container").height()+15;
				var cnt_x = $("#timmy_container").offset().left;
				var cnt_y = $("#timmy_container").offset().top;
				
				if (mousex < cnt_x-15 || mousex > cnt_x+cnt_width ||
					mousey < cnt_y-15 || mousey > cnt_y+cnt_height)
					timmy_display_close();
				
				
			}
	
		
	}
	
	function timmy_full_overlay() {
		
		$("#timmy_overlay").width($(window).width());
		$("#timmy_overlay").height($(window).height());
		
	}
	
	function timmy_display_close() {
			
			
			
				if (timmygallery_fullscreen) {
				 
					$('body').css('overflow','');

				 }

				$(window).unbind('resize',timmy_full_overlay);
				$('#timmy_overlay .button').unbind('click',timmy_switch);
				$('#timmy_overlay').unbind('click',timmy_check_click);

				$(window).unbind('resize',resize_on_resize);
				$(window).unbind('resize',timmy_locate_arrows);
				$(window).unbind('mousemove',timmy_show_arrows);

				
				$("#timmy_overlay").hide();
				$('#timmy_overlay').remove();
	
				timmygallery_first_time = 1;
	
				if (timmygallery_fullscreen) { 
					$('body').fadeIn('fast');
					$(document).unbind('mousedown',timmy_wheel_active);
					$(document).unbind('mouseup',timmy_wheel_deactive);
					$(document).unbind('mousemove',timmy_wheel);
				}
				else 
					$('body').css('overflow','auto');
					
				if (timmygallery_video_running == 1) { 
					timmygallery_video_running = 0;
					timmygallery_fullscreen = timmygallery_old_running;
				}
	}
	
	function timmy_display_image(index,is_video,desc_index) {
			
			if (is_video == 0) {
				
				if (timmygallery_assoc_img[index] == undefined) {
				
					timmygallery_assoc_img[index] = desc_index;
					
				} else {
				
					desc_index = timmygallery_assoc_img[index];
					
				}
				
			} else {
							
				if (timmygallery_assoc_vid[index] == undefined) {
				
					timmygallery_assoc_vid[index] = desc_index;
					
				} else {
				
					desc_index = timmygallery_assoc_vid[index];
					
				}
								
			}
			
			
				
			timmygallery_current_index = index;


			if (timmygallery_video_running) {
				
					timmygallery_old_running = timmygallery_fullscreen;
					timmygallery_fullscreen = 0;
				
			}
			if (timmygallery_first_time) {
				$(window).bind('resize',timmy_locate_arrows);
				$(window).bind('resize',timmy_full_overlay);
				if (is_video == 0) 
					$(document).bind('mousemove',timmy_show_arrows);
			}

			
		
			if (timmygallery_fullscreen && is_video == 0)  {
				timmy_display_fullscreen(index,desc_index);

			}
			else 						  
				timmy_display_window(index,is_video,desc_index);
			
			

		
	}
	
		
	function timmy_display_fullscreen(index) {
		
		
					$("#timmy_loader").show();

		
			 var html_block = '<div id="timmy_pattern"></div><img id="timmy_img" src="' + timmygallery_contents[index] + '" onLoad="timmy_show()"/><div id="timmy_btn_dx" class="button"><img src="' + timmygallery_right_arrow + '" /></div><div id="timmy_info"></div><div id="timmy_btn_sx" class="button"><img src="' + timmygallery_left_arrow + '" /></div><div id="timmy_close" class="button_up"><img src="' + timmygallery_close_arrow + '" /><div id="timmy_loader"><img src="/img/loader.gif" /></div><div id="timmy_info"></div>';
			
			 if (timmygallery_first_time) {
				 
							var current_top = $(window).scrollTop();
							var current_w = $(window).width();
							var current_h = $(window).height();
				
							$('html').find('body').append('<div style="width: 100%; height: 100%; top: ' + current_top + 'px;" id="timmy_overlay" class="fullscreen_overlay"></div>').end()
									 .find('#timmy_overlay').html("<div id=\"timmy_change\">" + html_block + "</div>").show(1,
									  function() {
										

										timmy_show_extra();
										
										timmy_set_info();

										if (timmygallery_display_arrows) timmy_locate_arrows();
										
										$('#timmy_overlay .button').bind('click',timmy_switch);
											$("#timmy_pattern").width($(window).width());
											$("#timmy_pattern").height($(window).height());										
										$(window).bind('resize',function() {
											$("#timmy_pattern").width($(window).width());
											$("#timmy_pattern").height($(window).height());
										});
										  
									  });
						
						
			 } else {
				 
					$("#timmy_img").remove();

					$("#timmy_overlay").append('<img id="timmy_img" src="' + timmygallery_contents[index] + '" onLoad="timmy_show()"/>');
					timmy_set_info();
			 
			 }
			 
			 $('body').css('overflow','hidden');

			 timmygallery_first_time = 0;
	}
	
	function timmy_set_info(is_video) {
		
		if ($("#timmy_info").is(":visible")) {
					
					var media = "Immagine";
				
					if (timmygallery_video_running == 0) {

						$("#timmy_info").html("<p>" + media + " <b>" + (timmygallery_current_index+1) + "</b> di " + ((!is_video)? timmygallery_contents.length-timmy_urls : timmygallery_videos.length)+ "</p>");
					}
		}
		
	}
	
	function timmy_overlay_adjust() {


	
					$("#timmy_overlay").width($(document).width());
					$("#timmy_overlay").height($(document).height());


	}
	
	function timmy_display_window(index,is_video,desc_index) {
	
			$(window).bind('scroll',timmy_overlay_adjust);
			
			timmygallery_current_descindex = desc_index;
			
			if (is_video == 0) {

				var html_block = '<div id="timmy_container"><div id="timmy_change"><img id="timmy_img" src="' + timmygallery_contents[index] + '" onLoad="timmy_show(0,' + desc_index + ')"/><div id="timmy_btn_dx" class="button"><img src="' + timmygallery_right_arrow + '" /></div><div id="timmy_btn_sx" class="button"><img src="' + timmygallery_left_arrow + '" /></div></div><div id="timmy_descr"></div><div id="timmy_info"></div><div id="timmy_close" class="button_up"><img src="' + timmygallery_close_arrow + '" /></div><div id="timmy_loader"><img src="/img/loader.gif" /></div></div>';
			} else {
				var html_block = '<div id="timmy_container"><div id="timmy_change"><div id="timmy_img" style="width: 640px; height: 380px;"></div><div id="timmy_btn_dx" class="button"><img src="' + timmygallery_right_arrow + '" /></div><div id="timmy_btn_sx" class="button"><img src="' + timmygallery_left_arrow + '" /></div></div><div id="timmy_descr"></div><div id="timmy_info"></div><div id="timmy_close" class="button_up"><img src="' + timmygallery_close_arrow + '" /></div><div id="timmy_loader"><img src="/img/loader.gif" /></div></div>';
			}
			if (timmygallery_first_time) {

				$("#timmy_overlay").remove();
				
							$('html').find('body').append('<div id="timmy_overlay"></div>').end().
									  find('#timmy_overlay').html(html_block);
									  
					
											$("#timmy_container").css('top',$(window).scrollTop()+($(window).height()/2));
											
								
											$("#timmy_container").css('left',($(window).width()+$(window).scrollLeft())/2);

											
									  
							$("#timmy_overlay").fadeIn('fast',function() { 				
											
											timmy_overlay_adjust();
											
				
						

									  }).end();
									  
							$('#timmy_overlay .button').bind('click',timmy_switch);
							$("#timmy_overlay").bind('click',timmy_check_click);

							
			} else {
				
				$("#timmy_img").remove();
				$("#timmy_descr").hide();
				$("#timmy_loader").fadeIn('fast');
				
				if (is_video == 0) {
				
					$("#timmy_change").append('<img id="timmy_img" src="' + timmygallery_contents[index] + '" onLoad="timmy_show(0,' + desc_index + ')"/>');
					
				} else {
					
					$("#timmy_change").append('<div id="timmy_img" style="width: 640px; height: 360px;"></div>');
				
				
				}
				

			
			}
			
			if (is_video) 	
					timmy_show(is_video,desc_index);
			


			timmygallery_first_time = 0;

	}
	
	
	function timmy_resize_fullscreen(element) {
	
		if (!timmygallery_video_running) {
			var img = $(element);
			

			img.css('top', 0);
			img.css('left', 0);

			
			win = $(window);
			img_p = img.width() / img.height();
			win_p = win.width() / win.height();

			if ( img_p > win_p ) img.height(win.height());
			else  				 img.width(win.width());
			
		}
	}
	
	function resize_on_resize() {
		
			timmy_resize_fullscreen("#timmy_img");
			$("#timmy_img").css('margin-top',0);
			$("#timmy_overlay").css('top',$(window).scrollTop());
		
	}
	
	function timmy_show(is_video,desc_index) {
			
			
			
			if (timmygallery_fullscreen && !is_video) {
				
				$(document).bind('mousemove',timmy_wheel);
				$(window).bind('resize',resize_on_resize);
				$(document).bind('mousedown',timmy_wheel_active);
				$(document).bind('mouseup',timmy_wheel_deactive);
				timmy_resize_fullscreen("#timmy_img");
				$("#timmy_img").show();
				$("#timmy_loader").fadeOut();
				
				
			} else {
				
		
				var sizes = timmygallery_resize(
					$("#timmy_img").width(),
					$("#timmy_img").height()
				);
				
				$("#timmy_img").css('width',sizes.width + 'px');
				$("#timmy_img").css('height',sizes.height + 'px');
/*
				if ($.browser.msie && $.browser.version == "7.0") {
					$("#timmy_img").css("margin-left",-$("#timmy_img").width()/2);
				}
				*/
				if (timmygallery_descriptions[desc_index] != "") {
					$("#timmy_descr").width($("#timmy_img").width());				
					$("#timmy_descr").html("<p>" + timmygallery_descriptions[desc_index] + "</p>");
					var descr_height = $("#timmy_descr").height();
					$("#timmy_descr").hide();
				} else {
					var descr_height = 0;
					$("#timmy_descr").hide();
					$("#timmy_descr").html('');
				}

				$('#timmy_container').animate({
					
					
					width: sizes.width,
					height: sizes.height+descr_height,
					marginLeft: "-" + (sizes.width/2) + "px",
					marginTop: "-" + (sizes.height/2)  + "px"
					
				}, 100, 'linear', function() { 
				

					$("#timmy_img").show();
					$("#timmy_close").fadeIn(300);
					$("#timmy_loader").fadeOut();
					
					
					if (is_video) {
						
						var so = new SWFObject('/player.swf','mpl',$("#timmy_img").width(),$("#timmy_img").height(),'10');
						so.addParam('allowscriptaccess','always');
						so.addParam('allowfullscreen','true');
						so.addParam('wmode','opaque');
						so.addVariable('autostart','true');
						so.addVariable('stretching','fill');
					
						if (timmygallery_videos[timmygallery_current_index].indexOf("vimeo") != -1) {

							so.addVariable('type','/vimeo.swf');
						
						}
										
						so.addVariable('repeat','always');
						so.addVariable('file', timmygallery_videos[timmygallery_current_index]);
						so.write('timmy_img');
						
					}
					
					timmy_locate_arrows();
										
					timmy_show_extra();
					


					
					timmy_set_info(is_video);
					
					
				});
				
			}
			
			

			
	}
	
	function timmy_locate_arrows() {
		
				if (timmygallery_fullscreen == 0)
					$("#timmy_change").find('.button').css('top',($("#timmy_img").height()/2)-($("#timmy_btn_sx").height()/2));
				else
					$("#timmy_change").find('.button').css('top',$(window).height()/2);
					
				$("#timmy_btn_sx").css('left','0');
				$("#timmy_btn_dx").css('left','100%');
				$("#timmy_btn_dx").css('margin-left','-' + $("#timmy_btn_dx").width() + 'px');
				
	
	}
	
	
	function timmy_show_arrows(e) {
		
		if (!timmygallery_video_running) {
			if (timmygallery_display_arrows) {
				var mousex = e.pageX;
				var mousey = e.pageY;
				
				if($("#timmy_close").is(':visible')) {
				
					var img_width = $("#timmy_img").width();
					
					if (mousex > $(window).width()/2) {
						
						$("#timmy_btn_sx").fadeOut();
						
						if (timmygallery_current_index < timmygallery_contents.length-1-timmy_urls) {

							$("#timmy_btn_dx").fadeIn();
						}
						
					} else {
						
						$("#timmy_btn_dx").fadeOut();
						
						if (timmygallery_current_index > 0) {
							$("#timmy_btn_sx").fadeIn();
						}
					}
					
				}
					
			}
		}
		
	}
	
	function timmy_show_extra() {
		if (timmygallery_display_close)  {
			$("#timmy_close").fadeIn();
			$("#timmy_close").bind('click',timmy_display_close);
		}
		
		if (timmygallery_display_info) {
			
			$("#timmy_info").fadeIn();

		}
		
		if (!timmygallery_fullscreen) {


				/*if (timmygallery_descriptions[timmygallery_current_index] == "") $("#timmy_descr").hide();
				else {
				*/
								if ($.browser.msie && $.browser.version == "7.0") {
									$("#timmy_descr").css('margin-left',-$("#timmy_descr").width()/2-2);	
								}
							
								$("#timmy_descr").fadeIn();
				//}
				
		}
	}
	
	function timmy_switch(event) {
			
		 var direction = $(this).attr('id').replace('timmy_btn_','');
	
		 if (direction == 'dx') timmy_display_image(timmygallery_current_index+1,0,timmygallery_assoc_img[timmygallery_current_index+1]);
		 else 					timmy_display_image(timmygallery_current_index-1,0,timmygallery_assoc_img[timmygallery_current_index-1]);
		 	 
		 if (timmygallery_current_index+1 == timmygallery_contents.length-timmy_urls) $("#timmy_btn_dx").fadeOut();
		 if (timmygallery_current_index == 0) $("#timmy_btn_sx").fadeOut();

	}
	
	$(document).keyup(function(e) {
	
	if(timmygallery_video_running == 0) {
	 
			if (e.keyCode == 39) {
			
			if(timmygallery_contents != '') {
			
				if (timmygallery_current_index+1 == timmygallery_contents.length) return false;
			
				else timmy_display_image(timmygallery_current_index+1,0,timmygallery_assoc_img[timmygallery_current_index+1]); //dx
			} else return false;
			}
			else if (e.keyCode == 37) {
			
			if(timmygallery_contents != '') {
						
				if (timmygallery_current_index == 0) return false;
				
				else timmy_display_image(timmygallery_current_index-1,0,timmygallery_assoc_img[timmygallery_current_index-1]); //sx
			} else return false;
			
			}
			else if (e.keyCode == 27) timmy_display_close();
			
			}
	 
	 });
	
	function timmy_wheel(e) {
		
		if (timmygallery_wheel == 1) {

			offset = e.pageY - timmygallery_mousey;
		
			var newval = parseInt($("#timmy_img").css('margin-top').replace('px',''))+offset;
			
			if (newval < 0 && newval > $(window).height()-$("#timmy_img").height())	$("#timmy_img").css('margin-top',newval);

			timmygallery_mousey = e.pageY;
		}
	}
	
	function timmygallery_resize(m_width,m_height) {
		
		var data = { width: 0, height: 0, proporzioni: 0 };
		
		data.width = m_width;
		data.height = m_height;
		
		data.proporzioni = m_height/m_width;
		
		while (data.width > timmygallery_max_width || data.height > timmygallery_max_height) {
			
			data.width -= 10;
			data.height -= 10*data.proporzioni
			
		}
		
		data.width = parseInt(data.width);
		data.height = parseInt(data.height);
		
		return data;
		
	}
	
	function toggle_fullscreen(element,t1,t2) {
		
		if (timmygallery_fullscreen) {
			
			$(element).html(t2);
			
			timmygallery_fullscreen = 0;
		} else {
			
			$(element).html(t1);
			
			timmygallery_fullscreen = 1;
			
		}
		
	}
	
	}
