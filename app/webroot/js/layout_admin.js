 //Hack I7,8,9
if (typeof console == "undefined" || typeof console.log == "undefined") var console = { log: function() {} }; 

$.ajaxSetup({
	cache: false
});

$(function(){


	
	$(document).ajaxStart(function(){
	   timmyloader('show');
	 });
	$(document).ajaxStop(function(){
	   timmyloader('hide');
	 });	 
	 

});

// Live streaming script //

$('.index_streams a[class=index-disabled-switch]').live('click', function(){

	var disabled = $(this).attr('data-disabled');
	var id = $(this).attr('data-id');
	var old = $(this);
		
	if(disabled == 1) { 
	
		// se abilitato

		$.get('/admin/streams/setStream/' + id, function(ret){
		
			$('.index-disabled-switch').each(function(index){
		
				if($(this).attr('data-id') != old.attr('data-id')) $(this).children('img').attr('src','/img/timmyshare/icon_disabled_1.gif');
		
			});
		
		}, 'json');
	
	}
	
});

//Permission


$(function(){

	$('.admin-navigation').find('li:not(.logo, .dashboard)').each(function(){
	
		var obj = $(this);

		obj.children('ul.submenu').each(function(){
		
			if($(this).find('li').length == 0) obj.remove();
		
		});
		
	});

	$(".block_box").each(function() {
		
		if ($(this).find('li').length == 0) { $(this).remove();
		
			$(".calendari").removeClass('calendari');
		
		}
	});

});

// camelize

	function camelize(string) {
	
		return string.replace (/(?:^|[-_])(\w)/g, function (_, c) {
		  return c ? c.toUpperCase () : '';		
		  
		});
	
	}

//Default ADS
$("#checkbox_default").live('click', function() {

	var data_ajax = $(this).closest('tr').attr('data-ajax');
	
	var tr_id = data_ajax.split('/');
	
	var check_id = tr_id[4];
	
	$(this).attr('data-id',check_id);
	
	if($(this).val() == 0) $(this).val(1);
	else if($(this).val() == 1) $(this).val(0);
	
	if ($(this).val() == 0) var new_value = 0;
	else if($(this).val() == 1) var new_value = 1;
	
	$("input:checkbox").each(function(){
	
		if($(this).attr('data-id') != check_id ) {
	
			$(this).attr("checked","");
			$(this).val(0);
		
		}
	
	});

	$.get('/admin/headers/setDefault/' + new_value + '/' + check_id, function(ret) {}, 'json');

});

// -- TAB SELECTOR -- // 

$(function() {
	
	$(".tab-selector a").live('click',function() {
		
		var container = $(this).closest('.tab-container');
		
		var index = $(this).parent().attr('data-index');
		
		$(container).find('.tab-selector li').removeClass('selected');
		
		$(container).find('.tab-selector li[data-index="' + index + '"]').addClass('selected');
		
		$(container).find('.tab-page').removeClass('tab-selected');
		
		$(container).find('.tab-page[data-index="' + index + '"]').addClass('tab-selected');
		
	});
	
});


$(function() {

	$(".submenu").live('mouseenter',function() {
				
			$(this).parent().addClass('hover_menu');		
	});


	$(".submenu").live('mouseleave',function() {
		
			$(this).parent().removeClass('hover_menu');
	});
	

});

$(function() {

	
	if(layout == "desktop") {
	
	$(".datePicker").live('focus',function() {
		
		 $(this).not('.hasDatePicker').datepicker({showOn: 'both'});
		 //$(this).datepicker('destroy').datepicker({showOn: 'both'}).focus();
		 
		
	});
	
	$('.datePicker').live('keyup', function(e){
	
		var datePicker = $(this);
		var value = datePicker.val();
		var val_l = value.length;
		
		if(val_l == 2 || val_l == 5) {
		
			datePicker.val(value + '/');
		
		}
	
	});
	
	} else {
	
	
	$(".datePicker").live('focus',function() {	
	
		$(this).scroller({
			preset: 'date',
			invalid: { daysOfWeek: [0, 6], daysOfMonth: ['5/1', '12/24', '12/25'] },
			theme: 'jqm',
			display: 'inline',
			mode: 'scroller',
			animate: ' ',
			dateOrder: 'mm ddyy'
		});
    
    });
    
    }
		
});

$(function() {
	
		$(".scheda").hide();
		
		function switcher(e) {
			
			e.stopPropagation();
			
			if ($(this).attr('data-uniqid') == undefined) $(this).attr('data-uniqid',e.timeStamp + "|" + Math.random());
			
			my_class = $(this).attr('data-dest');
			
			me = $(this);
			
			$(".switch").each(function() {
				
					
				
					if (my_class != $(this).attr('data-dest')) {
						
							$('.' + $(this).attr('data-dest')).slideUp(300);
							
							$(this).removeClass('selected');
						
					
					} else {
				
						if ($(this).attr('data-uniqid') == $(me).attr('data-uniqid')) {
							if ($(this).attr('data-ajax') == undefined) {
						
								$('.' + my_class).slideDown(300);
								$(this).addClass('selected');
						
							} else {
								
								var old = $(this);
								
								timmyloader('show');
								
								$.get($(this).attr('data-ajax'),function(data) {
									
									$("." + $(old).attr('data-dest')).html(data).slideDown(300,function() {
									
									$(window).scrollTop(0);
										
									});
									
									$(old).addClass('selected');
						
									timmyloader('hide');
						
								},'html');
																
							}
							
						}
					}
				
			});
			
		}
		
		$(".switch:not(tr)").live('click',switcher);
		$("tr.switch").live('dblclick',switcher);
	
});

jQuery(function($){
	$.datepicker.regional['it'] = {
		clearText: 'Svuota', clearStatus: 'Annulla',
		closeText: 'Chiudi', closeStatus: 'Chiudere senza modificare',
		prevText: '&#x3c;Prec', prevStatus: 'Mese precedente',
		prevBigText: '&#x3c;&#x3c;', prevBigStatus: 'Mostra l\'anno precedente',
		nextText: 'Succ&#x3e;', nextStatus: 'Mese successivo',
		nextBigText: '&#x3e;&#x3e;', nextBigStatus: 'Mostra l\'anno successivo',
		currentText: 'Oggi', currentStatus: 'Mese corrente',
		monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
		'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
		monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
		'Lug','Ago','Set','Ott','Nov','Dic'],
		monthStatus: 'Seleziona un altro mese', yearStatus: 'Seleziona un altro anno',
		weekHeader: 'Sm', weekStatus: 'Settimana dell\'anno',
		dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
		dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'],
		dayStatus: 'Usa DD come primo giorno della settimana', dateStatus: '\'Seleziona\' D, M d',
		dateFormat: 'dd/mm/yy', firstDay: 1,
		minDate: new Date(1945, 1 - 1, 01), 
		initStatus: 'Scegliere una data', isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['it']);
});

if(layout == "desktop") {

$(function() {
	
	// 	Timmy Tooltips
	
	$("*[rel='timmytip']").live('mouseenter',function(e) {
		
		if ($(this).attr('data-tip-id') == undefined) {
			
			$(this).attr('data-tip-title',$(this).attr('title'));
			$(this).removeAttr('title');
			
			$(this).attr('data-tip-id','tip_' + e.timeStamp);
			
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
							.appendTo('body')
							.delay(500)
							.fadeIn(300);
							
				$(timmytip).html('<span class="arrow_tooltip"></span>' + $(timmytip).text());
							
		} else {
			
				timmytip = $('#' + $(this).attr('data-tip-id')).html('<span class="arrow_tooltip"></span>' + $(this).attr('data-tip-title')).fadeIn(300);
				timmytip.css('z-index','999')
						.css('position','absolute')
						.css('left',$(this).offset().left+8)
						.css('top',$(this).offset().top-28);
				
				
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

}


// Navigator 


$(function() {
	
	var url = location.pathname;
	
	$(".admin-navigation li").each(function() {
		
		var mylink = $(this).find('a:first').attr('href');
		
		
		
		if (mylink != undefined) {
		
				if (url.substr(0,mylink.length) == mylink || (url == "/admin" && mylink == "/admin/dashboards/index")) {
					
					$(this).addClass('selected');
					$(this).parents('li').attr('id','selected');
			
					if ($(this).parents('li').length == 0) $(this).attr('id','selected');

				}
		
		}
		
	});
	
});

// autocomplete

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

$('.autoComplete').live('keyup.autocomplete, focus.autocomplete', function() {
	 
	
	var url = $(this).attr('data-url');
	var dest = $(this).attr('data-dest');
	
	
	$(this).autocomplete({ source : url,
		minLength: 0,
		delay: 100,
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

// controllo ora

$(function() {

	$('.control_ora').live('keyup',function(e) {
	
		var code = e.keyCode;
		
		var val = $(this).val();
		var val_l = $(this).val().length;
		
		var HH = val.substr(0,2);
		var SS = val.substr(3,5);
				
		if(HH > 24 && HH.length == 2) { HH = '00'; $(this).val('00.' + SS); }
		else if(SS > 60 && SS.length == 2) { SS = '00'; $(this).val(HH + '.'); }
		//else { SS = '00'; HH = '00'; $(this).val('00.00'); }
		
		if(val_l == 2 && code != 8) $(this).val(val + '.');

	});
	
	$('.control_ora').live('keydown',function(e) {
		console.log(e);
		var code = e.keyCode;
			
		if(isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46) return false;
		
		var val = $(this).val();
		var val_l = $(this).val().length;
		
		if(val_l > 4 && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46) return false;
		
		var HH = val.substr(0,2);
		var SS = val.substr(3,5);
				
		if(code == 40) {
		
			if(parseInt(SS) == 0) SS = 60;
				else SS = SS - 1;
			$(this).val(HH + '.' + SS);
		
		}
		
		if(code == 38) {
			if(parseInt(SS) == 60) SS = 0;
				else SS = parseInt(SS) + 1;
			$(this).val(HH + '.' + SS);
		
		}
		
		// 40 GIU, 38 SU, 39 DESTRA, 37 SINISTRA, 116 F5
		
	});

});

	$(function() {

		$("#refresh_ranking").live('click',function() {
			
				timmy_load("/admin/rankings/refresh");
			
		});
		
	});
	
	$(function() {

		$("#refresh_champ").live('click',function() {
			
				timmy_load("/admin/matches/refresh");
			
		});
		
	});
	
	$(function() {

		$("#print_bullettins").live('click',function() {
		
			var tab = $(this).attr('data-tab');
			
			if(tab == undefined) {
			
				timmy_load("/admin/prints/index");
				
			} else {
			
				timmy_load("/admin/prints/index/"+tab);
			
			}
			
		});
		
		$("#print_almanacco").live('click', function(){
			
			timmy_load("/admin/squadres/almanacco_index");
		
		});		
		
		$("#print_etichette_full").live('click', function(){
		
			timmy_load("/admin/prints/label_full");
		
		});
		
		$("#sendLdaComunication").live('click', function(){
		
			timmy_load("/admin/matches/sendLdaIndex");
		
		});
		
		$("#print_rank").live('click', function(){
		
				timmy_load("/admin/prints/rank_index/"+$("#RankingDummyCampionato").val()+'/'+$("#HalfDescrizione").val());
		
		});
		
		$("#print_calendars").live('click',function() {
			
				timmy_load("/admin/prints/calendars_index");
			
		});
		
		$("#print_responsible").live('click',function() {
			
				timmy_load("/admin/prints/responsible_index");
			
		});
		
		$("#forumExport").live('click',function() {
			
			timmy_load("/admin/matches/forum_export");
		
		});		
		
		$("#print_single_lda").live('click',function() {
			
				timmy_load("/admin/prints/single_lda_index");
			
		});
		
		$("#print_general_lda").live('click',function() {
			
				timmy_load("/admin/prints/general_lda_index");
			
		});
		
		$("#mark_ranking").live('click',function() {
			
				var id_campionato = $("#RankingDummyCampionato").val();
				var id_girone = $("#HalfDescrizione").val();
				
				timmy_load("/admin/rankings/rankingMarkers/" + id_campionato + "/" + id_girone);
			
		});
		
		$("#discipline_ranking").live('click',function() {
		
				var id_campionato = $("#RankingDummyCampionato").val();
				var id_girone = $("#HalfDescrizione").val();
			
				timmy_load("/admin/rankings/rankingDiscipline/" + id_campionato + "/" + id_girone);
			
		});
		
		$("#FinalStageGenerate").live('click', function(){
		
			var id_campionato = $("#CampionatiCampionato").val();
			var id_precedente = $("#CampionatiCampionatoPrecedente").val();
			
			timmyloader('show');
			
			$.get("/admin/campionatis/finalStage/" + id_campionato + "/" + id_precedente, function(data){
				
					timmyloader('hide');
					
					if(data.error == 1) {
					
						alert('Impossibile generare fasi finali, creare prima classifica');
						
						return false;
						
					}
					
					alert('Fasi finali generate con successo');
					
					$('#index_table').find('a.index-row-edit[data-id='+id_campionato+'][data-tip-title="Gironi"]').trigger('click');
				
			},'json');
		
		});
		
	});


	$(function() {
		
		$(".arbitro-info").each(function() {
			
			var className = $(this).attr('data-class');
			
			$(this).parents('td').addClass(className);
			
		});
		
	});
	
$(function(){

	$('#FreeHourAtleta').live('change', function(){
	
		var value  = $(this).val();	
		var prefix = 'Athlete';
		
			$.get('/admin/free_hours/infoAthlete/'+value, function(data) {
			
				$('.infoAthlete').find('input').each(function(){
				
					var id    = $(this).attr('id');
					var field = id.replace(prefix,'');
					
					$(this).val(data.Athlete[field]);
				
				});
				
			},'json');
	
	});					

});	

//SMS EMAIL Script

	$(function() {
	
		$("#add_contacts").bind('click', function(){
		
			var option     = $(this).attr('data-index');
			var athlete_id = new Array;
		
			$(".index-select-checkbox:checked").each(function() {
				
				athlete_id.push($(this).val());
				
			});
			
			if(athlete_id.length > 0) {
			
				$.post('/admin/athletes/createList/'+option, { "athletes":athlete_id}, function(data){
				
					var atleta   = 'atleti';
					var aggiunto = 'aggiunti';
				
					if(data.diff == 1) {
					
						atleta   = 'atleta';
						aggiunto = 'aggiunto'; 
						
					}
				
					if(data.update == 1) {
					
						alert(data.diff + ' ' + atleta + ' ' + aggiunto + '.');
					
					}
				
				},'json');
			
			} else {
			
				alert('Selezionare almeno un atleta.');
			
			}
			
		});
		
		$("#sendMailSms").live('click', function(){
		
			$.get('/admin/athletes/checkSession', function(data){
			
				if(data.check == 1) {
				
					timmy_load('/admin/athletes/sendMailSms');
				
				} else {
				
					alert('Selezionare almeno un atleta.');
					
				}
			
			},'json');
		
			
		
		});
				
	});
	

	$(function() {
		
			$("#switch_admin").change(function() {
			
				$.post('/admin/dashboards/setType',{ type: $(this).val() },function() { location.href = '/admin'; });
				
			});
			
			$("#switch_data_type").change(function() {
			
				$.post('/admin/dashboards/setDataType',{ type: $(this).val() },function() { location.reload(); });
				
			});		
		
	});

	/* Newsletter */
	
	$(function(){
		
		$('.index-row-previewNewsletter').live('click', function(){
			
			var newsletter_id = $(this).attr('data-id');
			
			window.open('/admin/newsletters/getNewsletterPreview/' + newsletter_id);
			
		});		
		
		$('.index-row-storyNewsletter').live('click', function(){
			
			var newsletter_id = $(this).attr('data-id');
			
			timmy_load('/admin/newsletters/getStory/' + newsletter_id);
			
		});
		
	});
	
	/*----------*/	
	
$(".filtra input[type='radio']").live('mousedown',function(e) {

	if ($(this).is(':checked')) { 
		e.stopPropagation();
		e.preventDefault();
		var me = $(this);
		$(this).attr('checked','');
		
		t = setTimeout(function() {
				me.attr('checked','').removeAttr('checked');
		},200);
		
		return false;
	}
});