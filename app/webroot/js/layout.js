if (typeof console == "undefined" || typeof console.log == "undefined") var console = { log: function() {} }; 
jQuery.fn.extend({
    live: function (event, callback) {
       if (this.selector) {            
            jQuery(document).on(event, this.selector, callback);
        }
    }
});
function isValidEmail(str) {
	var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
	return emailPattern.test(str);
}

function getCurrentUrl() {

	if (location.pathname.indexOf('/blocchi/') != -1) {
		
		
		var url = $(".categories.contents-box-right-container ul li.selected a").attr('href');
		
		return url;
		
	} 
	
	if (location.pathname.indexOf('/impianti') != -1) {
		
		return '/impianti';
		
	}
	
	if (location.pathname.indexOf('/squadra') != -1) {
	
		return '/maschile/1/0';
		
	}	
	
	return location.pathname;
	
}


$(function() {
	jQuery.fn.extend({
    live: function (event, callback) {
       if (this.selector) {            
            jQuery(document).on(event, this.selector, callback);
        }
    }
});
	// 	Timmy Tooltips
	
	$("*[rel='timmytip']").live('mouseenter',function(e) {
		
		if ($(this).attr('data-tip-id') == undefined) {
			
			$(this).attr('data-tip-title',$(this).attr('title'));
			$(this).removeAttr('title');
			
			$(this).attr('data-tip-id','tip_' + e.timeStamp);
			
		}
		
		if($(this).attr('data-top') == undefined) {
		
			$(this).attr('data-top',0);
		
		}
	
		var timmytip = null;
		
		if ($('body').find('#' + $(this).attr('data-tip-id')).length == 0) {
				
	
				
				timmytip = $("<div></div>").addClass('timmytip')
							.text($(this).attr('data-tip-title'))
							.css('display','none')
							.css('z-index','999')
							.css('position','absolute')
							.css('left',$(this).offset().left+8)
							.css('top',$(this).offset().top-38)
							.attr('id',$(this).attr('data-tip-id'))
							.css('margin-top',$(this).attr('data-top').replace(';',''))
							.appendTo('body')
							.delay(500)
							.fadeIn(300);
							
				$(timmytip).html('<span class="arrow_tooltip"></span>' + $(timmytip).text());
							
		} else {
			
				timmytip = $('#' + $(this).attr('data-tip-id')).html('<span class="arrow_tooltip"></span>' + $(this).attr('data-tip-title')).fadeIn(300);
				timmytip.css('z-index','999')
						.css('position','absolute')
						.css('left',$(this).offset().left+8)
						.css('top',$(this).offset().top-28)
						.css('margin-top',$(this).attr('data-top').replace(';',''))
				
				
		}	
		
		if ($(this).attr('data-tipclass') != undefined) $(timmytip).addClass($(this).attr('data-tipclass'));
		
		var position = $(timmytip).offset().left + $(timmytip).width();
		
		
		if (position > $(window).width()) {
			$(timmytip).css('left','auto');
			$(timmytip).css('right','10px');
		}			
		
	});
	
	$("*[rel='timmytip']").live('mouseleave',function() {
		
		$('#' + $(this).attr('data-tip-id')).remove();
		
	});
	
});


/* General Functions */

	function selectJSON (input,output) {
	
		/* Fills select with JSON values */
		
		$(output).find('option[value!=""]').remove();
		console.log($(output));
		for (var i = 0; i < input.length; i++) {
			$(output).append('<option value="' + input[i].id +'">' + input[i].value + '</option>');
		}
		
	}

/* ---------- */

	function ajaxLoader(opt) {
	
		/* Displays ajax loader (parameters: 'show', 'hide') */
		
		if (opt == 'show') {
			
			$("#ajax-loader").fadeIn(200);
			
		} else {
			
			$("#ajax-loader").fadeOut(200);
			
		}
		
	}


/* ---------- */

/* LATEST NEWS SLIDER */ 

$(function() {
	
	var offset = 3;
	var total = $(".last-news-box").length;
	
	var pages = Math.ceil(parseInt(total) / offset);
	
	console.log ("slider :: pages : " + pages);
	
	$("#right-slider").click(function() {
		
		
		var currentPage = $("#last-news-slider").attr('data-current-page');
		
		if (currentPage == pages) return;
		
		$("#left-slider").removeClass('invisible');
		
		currentPage = parseInt(currentPage);
		
		var offsetY = 0;
		
		$(".last-news-box:lt(" + (offset * currentPage) + ")").each(function() {
			
			
			offsetY += parseInt($(this).outerHeight(true));
					
		});
		
		console.log ("slider :: offsetY : " + offsetY);
		
		$("#last-news-scroll").animate({
			
			'marginTop': -offsetY
			
		},500);
		
		currentPage++;
		
		$("#last-news-slider").attr('data-current-page',currentPage);
		
		if (currentPage == pages) $("#right-slider").addClass('invisible');
		
	});
	
	
	$("#left-slider").click(function() {
		
		
		var currentPage = $("#last-news-slider").attr('data-current-page');
		
		if (currentPage == 1) return;
		
		$("#right-slider").removeClass('invisible');
		
		currentPage = parseInt(currentPage);
		

		currentPage--;
		
		
		$("#last-news-slider").attr('data-current-page',currentPage);
		
		
		var offsetY = 0;
		
		$(".last-news-box:lt(" + (offset * (currentPage-1)) + ")").each(function() {
			
			offsetY += parseInt($(this).outerHeight(true));
					
		});
		
		console.log ("slider :: offsetY : " + offsetY);
		
		$("#last-news-scroll").animate({
			
			'marginTop': -offsetY
			
		},500);
		
		if (currentPage == 1) $("#left-slider").addClass('invisible');
		
	});
	
});


/* ---------- */


$(function() {
	
	
	/* AJAX SELECTS */
	
	$(".select-box li").live('click',function(e) {
		
		e.stopPropagation();
		
		var select = $(this).parents('.select-box');
		
		$(select).find('.select-value').val($(this).attr('data-value')).trigger('change');
		$(select).find('.selected-value').html($(this).html());
		
		$(select).find('.values-of-select').hide();
		
	});
	
	$(".select-box").live('click',function() {
		
		$(this).find('.values-of-select').show();
		
	});
	
	$(".values-of-select").live('mouseenter',function() {
		
		$(this).attr('data-ishover',"true");
		
	});
	

	

	
	/* ---------- */


	/* AJAX RADIO BUTTONS */

		
		$(".switch-button:not(.switch-checkbox) li").live('click',function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(this).parent().find('li:not(.switch-value)').not($(this)).removeClass('selected');
			
			$(this).addClass('selected');
			
			$(this).parent().find('.switch-value input').val($(this).attr('data-value')).trigger('change');
			
		});
		
	
	/* ---------- */
	
	/* AJAX CHECKBOX BUTTONS */
	
		$(".switch-checkbox li").live('click',function(e) {
			
			var inputValue = $(this).find('input').val();
			
			if (inputValue == "true") {
				
				$(this).find('input').val('false');
				$(this).find('.checkbox-unset').addClass('hidden');
				$(this).removeClass('yellow');
				
			} else {
				
				$(this).find('input').val('true');
				$(this).find('.checkbox-unset').removeClass('hidden');
				$(this).addClass('yellow');
				
			}
			
			$(this).find('input').trigger('change');

			return false;
			
		});
	

	/* ---------- */
	

	
	
});


/* FILTERS */

$(function() {
	
	$(".nota-gara").live('click',function() {
		
		
		location.href = '/sections/getNotes/' + $(this).attr('data-match-id') + '/' + $("*[name='squadra_id']").val();
		
	});
	
	$(document).ready(function(){
	
		if(location.pathname == '/') {
		
			var info       = location.hash.replace('#','').split('-');
			
			var info_champ = (info[0] != undefined)? info[0].split('=') : 0;
			var info_half  = (info[1] != undefined)? info[1].split('=') : 0;
			var info_tab   = (info[2] != undefined)? info[2].split('=') : 0;
			
			var champ      = (info_champ[0] == "campionato")?info_champ[1]:undefined;
			var half       = (info_half[0] == "girone")?info_half[1]:undefined;
			var tab        = (info_tab[0] == "tab")?info_tab[1]:undefined;
			
			/*
			//console.log('Campionato: ' + champ);
			//console.log('Girone: ' + half);
			//console.log('Tab: ' + tab);
			*/
			
			/** CONDIVISIONE FACEBOOK
			
		   var atch = {
			name: 'Nuovo commento di prova',
			href: '',
			caption: '{*actor*} ha condiviso:',
			description: $('#social-textarea').val(),
			//media: [{ type: 'image', src: $("#productMainImage").find('img').attr('src'), href: ''}]
			};
		
			  FB.ui({
					method: 'stream.publish',
					attachment: atch,
					href: location.href + "?rnd=" + Math.random()
			  });			

			*/
			
			if(champ != undefined && half != undefined) {

				$("*[name='campionato_id']").parents('.content-select').find('.values-of-select').find('li[data-value='+champ+']').trigger('click');
				
				triggerHalf = setInterval(function(){
				
					if($("*[name='girone_id']").parents('.content-select').find('.values-of-select').find('li[data-value='+half+']').length > 0) {
					
						$("*[name='girone_id']").parents('.content-select').find('.values-of-select').find('li[data-value='+half+']').trigger('click');
						clearInterval(triggerHalf);
						
					}
				
				},300);
				/*
				triggerTab = setInterval(function(){
				
					if(tab == 'squadra')
						tab = 'calendario';
				
					if($('.switch-filters').find('li[data-value="'+tab+'"]').length > 0) {
					
						$('.switch-filters').find('li[data-value="'+tab+'"]').trigger('click');
						clearInterval(triggerTab);
					
					}
				
				},300);
				*/
			
			}
			
		}
	
	});	
	
	// var imexecuting = 0;
	// function getFilter(champ_id,half_id,type,noTrigger, cb, appendTo) {

	// 	//console.log("- getFilter executed - noTrigger = " + noTrigger);
		

	// 	if (imexecuting == 1) 
	// 		return;
	// 	imexecuting = 1;
	// 	ajaxLoader('show');


	// 	if (champ_id == undefined) 
	// 		champ_id = $("*[name='campionati_id']").val()

		
	// 	if ($("[name='squadra_id']").val() != 0) 
	// 		squadra_id = $("*[name='squadra_id']").val();
	// 	else
	// 		squarda_id = 0;
		
	// 	if (champ_id == "" && $("[name='campionati_id']").val() == "") 
	// 	{
	// 		ajaxLoader('hide');
	// 		return;
	// 	}

	// 	$.get("/sections/getFilter/" + champ_id + "/" + half_id + "/" + type + "/" + squadra_id,function(data) {
	// 		var $data = data;
	// 		if(appendTo)
	// 		{
	// 			$(appendTo).html($data).css('opacity',0).slideDown(300,function() {
				
	// 			$(this).animate({'opacity': 1},500);
				
	// 			if ($("*[name='squadra_id']").val() != 0)
	// 			if (noTrigger == undefined)
	// 			$("*[name='squadra_id']").trigger('change');
				
	// 			location.hash = '#campionato='+champ_id+'-girone='+half_id+'-tab='+type;


				
	// 		});
	// 		} else {
	// 			$(".table-container").html($data).css('opacity',0).slideDown(300,function() {
	// 				$(this).animate({'opacity': 1},500);
					
	// 				if ($("*[name='squadra_id']").val() != 0)
	// 				if (noTrigger == undefined)
	// 					$("*[name='squadra_id']").trigger('change');
					
	// 				location.hash = '#campionato='+champ_id+'-girone='+half_id+'-tab='+type;
	// 			});
	// 		}
			
	// 		ajaxLoader('hide');
	// 		imexecuting = 0;
	// 		if(cb)
	// 			cb();
			
	// 	},'html');
	// }
	
	
	$("*[name='filter_select']").live('change',function() {
		
			//console.log("- Filter Select Changed");
				/*
				if($("*[name='campionato_id']").val() == undefined) {
					return;
				}
				*/
			if($("*[name='campionato_id']").val() == undefined) var val = $("*[name='campionati_id']").val();
			else 													var val = $("*[name='campionato_id']").val();

			getFilter(val,$("*[name='girone_id']").val(),$("*[name='filter_select']").val());
			
	
	});
	
	$("select[name='campionato_id']").live('change',function() {
		
		ajaxLoader('show');
		
		$(".select-squadre").addClass('hidden');
		$("#team-button").addClass('hidden');
		$(".switch-filters").addClass('hidden');
		
		$(".table-container").removeAttr('style');
		
		$(".select-girone").find('.selected-value').html('SELEZIONA GIRONE DI RIFERIMENTO...');
		$(".select-girone").find('.select-value').val(0);
	
		/*
	
		$.get("/sections/getSquadreFromGirone/" + $(this).val(),function(data) {
			
			$(".select-squadre").find('.selected-value').html('SELEZIONA SQUADRA DI APPARTENENZA...');
			$(".select-squadre").find('.select-value').val(0);
			$("#team-button").addClass('hidden');
			
			selectJSON(data,$(".select-squadre").find('.values-of-select ul'));
		
			
			$(".select-squadre").removeClass('hidden');
			$(".switch-filters").removeClass('hidden');
	
			getFilter($("*[name='campionato_id']").val(),$("*[name='filter_select']").val());
	
			
		},'json');
		
		*/
		
		$.get("/sections/getGironiFromCampionato/" + $(this).val(),function(data) {
				
				selectJSON(data,$(".select-girone").find('select'));
				
				$(".filter-gironi").removeClass('hidden');
				
				ajaxLoader('hide');

				
			
		},'json');
	
		
	});
	
	$("*[name='girone_id']").change(function() {
		
		var me = $(this);
		
		$.get("/sections/getSquadreFromGirone/" + $(this).val(),function(data) {
			
			$(".select-squadre").find('.selected-value').html('SELEZIONA SQUADRA DI APPARTENENZA...');
			$(".select-squadre").find('.select-value').val(0);
			$("#team-button").addClass('hidden');
			
			selectJSON(data,$(".select-squadre").find('.values-of-select ul'));
		
			
			$(".select-squadre").removeClass('hidden');
			$(".switch-filters").removeClass('hidden');
	
			if($('*[name="filter_select"]').val() != 'squadra')
				tab = $('*[name="filter_select"]').val();
			else
				tab = 'calendario';
	
			getFilter($("*[name='campionato_id']").val(),$(me).val(),tab);

			if(tab == 'calendario') {

				t = setTimeout(function(){
					$('.select-squadre').find('.checkbox-unset:visible').trigger('click');
					$('.switch-button').find('li').removeClass('selected');
					$('.switch-button').find('li[data-value="calendario"]').addClass('selected');				
				},500);			
	
			}
			
		},'json');
		
	});
	
	$("*[name='squadra_id']").live('change',function() {
		
			//console.log("- Squadra ID changed");
	
			$("#team-button").removeClass('hidden');
			$(this).parent().parent().find('.checkbox-unset').removeClass('hidden');
			var squadra = $(this).val();
			
			$("*[name='filter_team']").attr('value','true');
			
			if ($("*[name='filter_team']").val() != 'false') {
				
				$(".table-matches tr").removeClass('selected');
				
				$(".table-matches tr[data-casa-id=" + squadra + "]").addClass('selected');
				
				$(".table-matches tr[data-trasferta-id='" + squadra + "']").addClass('selected');
				
				$(".search-opponent").removeClass('hidden');
				$(".search-opponent li").removeClass('hidden');
				$(".search-opponent li[data-squadra-id=" + squadra + "]").addClass('hidden');
				
				if ($("#team-button").hasClass('selected')) {
				
				
						getFilter($("*[name='campionato_id']").val(),$("*[name='girone_id']").val(),$("*[name='filter_select']").val(),1);
				
					
				}
			}		
	});
	
	$(".switch-giornata").live('click',function() {
		
		var giornata_id = $(this).attr('data-giornata-id');
		
		$(".table-matches").addClass('hidden');
		$(".table-matches[data-giornata-id='" + giornata_id +"']").removeClass('hidden');
		
		$(".other-info-row").addClass('hidden');
		$(".other-info-row[data-giornata-id='" + giornata_id + "']").removeClass('hidden');
		
		$(".match-comunication").addClass('hidden');
		$(".match-comunication[data-giornata-id='" + giornata_id + "']").removeClass('hidden');
		
		$(".switch-giornata").removeClass('active');
		$(".switch-giornata[data-giornata-id='" + giornata_id + "']").addClass('active');
		
	});
	
	$(".checkbox-unset").live('click',function(e) {
	
			e.stopPropagation();
			e.preventDefault();
		
			$("*[name='filter_team']").attr('value','false').trigger('change');
			$("*[name='squadra_id']").attr('value',0);
			$(".select-squadre").find('.selected-value').text('Seleziona squadra di appartenenza...');
			$(this).addClass('hidden');
			$("#team-button").addClass('hidden');
	
			$(".search-opponent").addClass('hidden');
			$(".match-results-menu").addClass('hidden');
	
	});
	
	$("*[name='filter_team']").change(function() {
		
		if ($(this).val() == 'false') $(".table-matches tr").removeClass('selected');
		else {
			$("*[name='squadra_id']").trigger('change');
		}
		
	});
	
	// HIDDEN
	$("*[name='avversario_id']").live('change',function() {
		
		ajaxLoader('show');
		
		$.post('/sections/getOpponent/' + $(this).val() + '/' + $("*[name='squadra_id']").val(),function(data) {
				
				$(".match-results-menu li").remove();
				$(".match-results-menu").removeClass('hidden');
				for (var i = 0; i < data.length; i++) {
					
					$(".match-results-menu").append('<li class="switch-giornata" data-giornata-id="' + data[i] + '"><a href="javascript:;" title="">Giornata ' + data[i] + '</a></li>');	
				}
		
				ajaxLoader('hide');
				
		},'json');
		
	});
	
/* Filtro dettaglio squadre */


$("select[name='anno_id']").live('change',function() {


	ajaxLoader('show');	

	$.get('/squadres/getChampFromYear/' + $(this).attr('data-squadra') + '/' + $(this).val(), function(data){
					$(".filter-campionato").find('select').prop('disabled',false).removeAttr('disabled').prop("disabled",null);

			$(".filter-campionato").find('select').html('<option value="">Seleziona torneo...</option>');
				selectJSON(data,$(".filter-campionato").find('select'));
		
				$(".filter-campionato").removeClass('hidden');
				
				$("*[name='campionati_id']").next('.values-of-select').find('li:eq(0)').click();
				
				ajaxLoader('hide');		
	
	},'json');

});

$("select[name='campionati_id']").live('change',function() {

	ajaxLoader('show');	

	var me = $(this);
	
	$.get('/squadres/getSquadraCampionatoFromCampionato/' + $(this).val() + '/' + $("select[name='anno_id']").attr('data-squadra'), function(data){
		
				ajaxLoader('hide');		
				
				$("*[name='squadra_id']").val(data.squadra);
				$("*[name='girone_id']").val(data.girone);
				
				$("*[name='filter_select']").val('calendario');
				
				$('.switch-filters').find('li[data-value="calendario"]').removeClass('selected');
				$('.switch-filters').find('li[data-value="squadra"]').addClass('selected');
				
				getFilter($(me).val(),$("*[name='girone_id']").val(),$("*[name='filter_select']").val(), undefined, function(){
					hideSquadrav2();
					
					if($("[name=avversario_id]").length)
						$("[name=avversario_id]").select2();
				}, "#tabsNavigationSimpleIcons3 .table-container");
				
				$(".switch-filters").removeClass('hidden');
				

	
	},'json');

});	
	
});

//News autoscroll

// Newsletter script

// ICON AGREE

$(function() {
	
	$('#newsletter-subscription .checkbox-privacy img').live('click',(function() {
		
		var img_value = new Array;
		
		img_value[0] = '/img/website/bg-checkbox.png';
		img_value[1] = '/img/website/bg-checkbox-selected.png'
		
		var myValue = $(this).attr('data-value');
		
		if (myValue == 0) myValue = 1;
		else
		{
			myValue = 0;
		}
		
		$(this).attr('data-value',myValue);
		$(this).attr('src',img_value[myValue]);
		
		
	}));
	
});

$("#newsletter-subscription input[class=text]").live('click', function(){

	$(this).val('');

});

$("#newsletter-subscription input[class=text]").live('focusout', function() {

	if($(this).val() == '') $(this).val('indirizzo email...');

});

$("#newsletter-subscription .submit").live('click', function(){

	$('.ok-message').empty();
	$('.error-message').empty();

	var input = $("#newsletter-subscription input[class=text]");
	var agree = $('#newsletter-subscription .checkbox-privacy').children("img").attr('data-value');
	var email = input.val();
	
	if(email == '') {
	
		$('.error-message').fadeOut('fast', function() {
			
			$('.error-message').html('Campo email obbligatorio');
			$('.error-message').fadeIn('fast');
		
		});
		return false;
	
	}
	
	if(agree == 1) {
	
		$.get('/newsletter_users/addUser/' + email, function(ret) {
		
			if(ret.aggiunto != 1) { 
				
				$('.error-message').fadeOut('fast', function() {
					
					$('.error-message').html(ret.aggiunto.email);
					$('.error-message').fadeIn('fast');
				
				});
				input.val('');
				
			}
			else { 
			
				$('.error-message').hide();
				$('.ok-message').html('Utente registrato con successo.'); 
				$('.ok-message').show();
				//$('.ok-message').fadeOut('slow');
				
			}
		
		}, 'json');
	
	} else {
		
		$('.error-message').fadeOut('fast', function() {
			
			$('.error-message').html('prestare il consenso al trattamento dati');
			$('.error-message').fadeIn('fast');
		
		});
	
	}

});

// camelize

	function camelize(string) {
	
		return string.replace (/(?:^|[-_])(\w)/g, function (_, c) {
		  return c ? c.toUpperCase () : '';		
		  
		});
	
	}
	
/* BOOKING */

$(function() {

	$("*[name='campo_id']").live('change',function() {
		
		$(".verify-rent").removeClass('hidden');
		
	});

	$('.verify-rent').live('click',function() {
		
		var campo 	    = $("*[name='campo_id']").val();
		var campo_title = $('.values-of-select').find('li[data-value='+campo+']').attr('data-title');
		
		location.href = '/impianti/' + campo + '/' + campo_title;
		
	});

});


/* MENU */

$("#main-menu > ul > li").live('mouseenter',function(e) {
	
	
	//console.log(e.timeStamp + ' exc: 1');
	
	//$("#main-menu ul li").not($(this)).removeClass('selected').find('a:eq(0)').removeClass('current');
	
	$("#main-menu ul li:not(.first-selected)").removeClass('selected').find('a:eq(0)').removeClass('current');
	
	$("#main-menu ul").css('display','');
	
	$(this).addClass('selected');
	$(this).find('ul:first').css('display','block');
});

$("#main-menu > ul > li > ul").live('mouseenter',function(e) {
	
	
	
	//console.log(e.timeStamp + ' exc: 2');
	
	$("#main-menu ul li:not(.first-selected)").removeClass('selected');
	
	$(this).closest('li').addClass('selected');
	
	$("#main-menu ul").css('display','');
	$("#main-menu li").not($(this).closest('li')).removeClass('selected');
	

	$(this).css('display','block');
	
});
/*
$("#main-menu > ul > li > ul").live('mouseleave',function(e) {
	
	
	//console.log(e.timeStamp + ' exc: 3');		
	$(this).css('display','block');

	var me = $(this);

	t = setTimeout(function() {
		
	if (!$(me).is(':hover')) {
		
		var counter = 0;
		
				$("#main-menu ul > li > ul > li > ul > li > a").each(function() {
					
					
					if (getCurrentUrl() == $(this).attr('href')) {
						
						$(this).closest('ul').closest('li').find('a:first').addClass('current').closest('ul').css('display','block').closest('li').addClass('selected');
						
						counter = counter+1;
					
						
						//console.log($(this).parents().length);
					}
					
				});
				
		if (counter == 0) {
			

			
		}

		
	}
	
	},100);

	
});
*/

$("#main-menu > ul").live('mouseleave',function(e) {
	
	
	//console.log(e.timeStamp + ' exc: 4');
	
	var me = $(this);
	
	t = setTimeout(function() {
	
	if (!$(me).is(':hover')) {
		
		var counter = 0;
		var count2  = 0;
		
		$("#main-menu a").each(function() {
			

			if (getCurrentUrl() == $(this).attr('href')) {
				
				$("#main-menu *").removeClass('selected').removeClass('current').css('display','');
				
				if ($(this).parents().length == 13) {
				
				$(this).closest('ul').closest('li').find('a:first').addClass('current').closest('ul').css('display','none').closest('li').addClass('selected').find('a:first').addClass('current');
				
				counter = counter+1;
				
				} else if ($(this).parents().length == 11) {
			
					$(this).closest('li').find('a:first').addClass('current').closest('ul').closest('li').addClass('selected').find('a:first').addClass('current');
				
			 
				} else if ($(this).parents().length == 9) {
					
				$(this).addClass('current').find('a:first').addClass('current').closest('li').addClass('selected');
				//$(this).addClass('current').closest('li').addClass('selected');
			
				}
				
				count2 = 1;
				
			}
			
		});
		
		if(count2 == 0) {
			
			$('#main-menu').find('li').removeClass('selected');
			$('#main-menu').find('li').find('ul').css('display', 'none');
			
		}
		
	}
				
	},1000);
	
});


$(function() {
// $("#main-menu ul li ul li a").each(function() {
	
	
	// if (location.pathname == $(this).attr('href')) {
		
		// $(this).addClass('selected');
		
		// $(this).closest('ul').closest('li').addClass('selected');
		
		// $(this).closest('ul').css('display','block');
		
		// $(this).addClass('current');
	// }
	
// });
	
	
	var counter = 0;

	$("#main-menu a").each(function() {


	if (getCurrentUrl() == $(this).attr('href')) {
	
		$("#main-menu *").removeClass('selected').removeClass('current').css('display','');
		
		//console.log($(this).parents().length);
		
		if ($(this).parents().length == 13) {
		
		$(this).closest('ul').closest('li').find('a:first').addClass('current').closest('ul').closest('li').addClass('selected').addClass('first-selected').find('a:first').addClass('current');
		
		counter = counter+1;
		
		} else if ($(this).parents().length == 11) {

		$(this).closest('li').find('a:first').addClass('current').closest('ul').closest('li').addClass('selected').addClass('first-selected').find('a:first').addClass('current');
		
	 
		} else if ($(this).parents().length == 9) {
			
		//$(this).addClass('current').closest('li').addClass('selected').find('ul:first').css('display','block');
		$(this).addClass('current').closest('li').addClass('selected').addClass('first-selected');

		}
		
	}

	});

});


/* SEARCH */
 
$(".searchValue").live('click',function() {
	
	if ($(this).val() == $(this).attr('data-default')) {
		
		 $(this).val('');
	}
}); 

$(".searchValue").live('keydown',function() {
	
	if ($.trim($(this).val()) == '' || $(this).val() == $(this).attr('data-default')) { 
		
		$(this).closest('form').find('input[type="submit"]').css('opacity',0);
		
	} else {
		
		$(this).closest('form').find('input[type="submit"]').css('opacity',1);
		
	}
	
});

$(".searchValue").live('blur',function() {
	
	
	if ($.trim($(this).val()) == '' || $(this).val() == $(this).attr('data-default')) { 
		
		$(this).closest('form').find('input[type="submit"]').css('opacity',0);
		
	} else {
		
		$(this).closest('form').find('input[type="submit"]').css('opacity',1);
		
	}
	
	if ($.trim($(this).val()) == '') $(this).val($(this).attr('data-default'));

}); 

$(function() {
	
	$("#searchForm").submit(function() {
		
		var searchValue = $(this).find('.searchValue');
		
		if ($.trim($(searchValue).val()) == '' || $(searchValue).val() == $(searchValue).attr('data-default')) {
			
			return false;
			
		}
		
	});
	
	$('.reset-search').click(function(){
	
		location.href = '/';
	
	});
	
});

// autocomplete

$('.autoComplete').live('keyup.autocomplete, focus.autocomplete', function() {
	 
	
	var url = $(this).attr('data-url');
	var dest = $(this).attr('data-dest');
	
	
	$(this).autocomplete({ source : url,
		minLength: 0,
		delay: 50,
		search: function() {

			$('body').find('#' + dest).removeAttr('value');
	
			$('body').find('#' + dest).trigger('change');
			
			timmyloader('show');
			
		},
		
		open: function() {
			
			timmyloader('hide');
			
		},
		
		select: function(event,ui) {
			
			$('body').find('#' + dest).val(ui.item.id);
			$('body').find('#' + dest).trigger('change');
		}
	
	 }); 

});

function timmyloader(opt) {
		
		
	var me = $("#timmyloader");
		
	if (opt == 'show') {

		$(me).fadeIn(300)
			 .css('top',125)
			 .css('left',$(window).scrollLeft()+($(window).width()/2)-($(me).width()/2));
		
		
	} else {
		
		$(me).fadeOut();
		
	}
	
}

// HEADER GRAPHIC

/*
$(function() {
	
	$("#header-graphic").css('left','50%').css('margin-left',-$("#header-graphic").width()/2);
	
	
});
*/


// SIGNUP

$(".signup-open").live('click',function() {
	
	if ($(".signup-select").is(':visible')) $(".signup-select").fadeOut(200);
	else $(".signup-select").fadeIn(200);
	
});

$(document).click(function() {
	
	var signup = $(".signup-select").closest('li');
	
	//if (!signup.is(':hover')) signup.find('.signup-select').fadeOut(200);
	
});

$(".signup-submit").live('click',function() {
	
	var url = $("*[name='signup-option']:checked").attr('value');
	
	location.href = url;
	
});


// LOGIN 

$(".login-open").live('click',function() {
	
	if ($(".login-form").is(':visible')) $(".login-form").fadeOut(200);
	else $(".login-form").fadeIn(200);
	
});

$(document).click(function() {
	
	var signup = $(".login-form").closest('li');
	
	//if (!signup.is(':hover')) signup.find('.login-form').fadeOut(200);
	
});

$("#loginForm").live('submit',function() {
	
	var username = $(this).find('.login_username');
	var password = $(this).find('.login_password');
	
	if ($.trim(username.val()) == '') {
		
		username.closest('div.input').find('.error-message').text('Campo obbligatorio').show();
		
		return false;
		
	}
	
	if (!isValidEmail(username.val())) {
		
		username.closest('div.input').find('.error-message').text('Inserire un indirizzo e-mail valido').show();
		
		return false;
		
	}	

	if ($.trim(password.val()) == '') {
		
		password.closest('div.input').find('.error-message').text('Campo obbligatorio').show();
		
		return false;
		
	}
	
	

});

/* UI - TABS */

$(".ui-tabs-switcher li a").live('click',function() {
	
	var index = $(this).attr('data-index');
	
	location.hash = index;
	
	$(this).closest('.ui-tabs-container').find(".ui-tabs-switcher li a[data-index!=" + index + "]").parent().removeClass('selected');
	
	$(this).closest('.ui-tabs-container').find(".ui-tabs-switcher li:has(a[data-index=" + index + "])").addClass('selected');
	
	$(this).closest('.ui-tabs-container').find('.ui-tab[data-index!=' + index + ']').removeClass('selected');
	
	$(this).closest('.ui-tabs-container').find('.ui-tab[data-index=' + index + ']').addClass('selected');
	
});

/* PASSWORD RECOVERY */

$("#recoverUser").live('submit',function() {
	
	$(this).find('.error-message').html('&nbsp;');
	
	var goOn = true;
	
	$(this).find('div.required').each(function() {
		
		var input = $(this).find('input.text').val();
		
		
		if ($.trim(input) == '') {
	
			$(this).find('.error-message').text('Campo obbligatorio').show();
		
			goOn = false;
	
		}
	
	});
	
	var email = $(this).find('*[name="data[User][username]"]');
	
	if (!isValidEmail(email.val())) {
		
		email.closest('div.input').find('.error-message').text('Inserire un indirizzo e-mail valido').show();
		
		goOn = false;
		
	} 
	
	if (goOn == false) return false;
	
	var goTo = $(this).attr('action');
	
	ajaxLoader('show');
	
	var form = $(this);
	
	$.post(goTo,$(this).serialize(),function(data) {
		
		ajaxLoader('hide');
		
		
		if (data.found == 1) {
		var nome = form.find('*[name="data[User][nome]"]').val();
		var cognome = form.find('*[name="data[User][cognome]"]').val();
		
		$("#recoverUser").html(
		'<h3>Grazie ' + nome + " " + cognome + ",</h3><br />" +
		'La sua procedura di recupero password è completata, riceverà al più presto le nuove credenziali di accesso via e-mail'
		);
		
		} else {
		
		$("#recoverUser").html(
		'<h3>Siamo spiacenti,</h3><br />' +
		'Non è stata trovata alcuna corrispondenza con i dati da lei inseriti'
		);
		
			
		}
	},'json');
	
	return false;
	
});

$(function(){
$(".confirm_password").live('paste', function(){return false;});
});


$(document).click(function() {
		
	//$(".values-of-select[data-ishover='true']:not(:hover)").hide().removeAttr('data-ishover');
	
});

$(document).ready(function(){
/*
	t = setTimeout(function(){
	
		$("#container").find('#flashMessage').parent('div').slideUp('slow');
	
	},'5000');


*/

});




