
$(function(){
	
	// $("#select-type").select2({
	// 	minimumResultsForSearch: "Infinity"});
	
	// var opened = false;
	// $("#header .select2").on("click", function(){
	
	// 	$(this).closest(".dropdown-menu").addClass("drp-open");
	// 	opened = true;
	// });
	
	// $("#select-type").on("change", function(){
	// 	if(opened)
	// 	{
	// 		$("#header .select2").closest(".dropdown-menu").removeClass("drp-open");
	// 		opened = false;
	// 		$("#headerAccount, #headerAccount a").click();
	// 	}
	// });
	
	
	$("document").on("change", ".select2-results__option", function(){
		
		if(opened)
		{
			$("#header .select2").closest(".dropdown-menu").removeClass("drp-open");
			opened = false;
		}
	});
})
$(document).on("change", "[name=squadra_id]", function(){
	hideSelectedSquadra();
})
function hideSquadrav2()
{
	var $rivals = $("[name=avversario_id] option");
	
	$rivals.prop("disabled", false);
	$rivals.each(function(){
		console.log(squadra_id +" == " +$(this).val());
		if( squadra_id == $(this).val() )
		{
			
			$(this).prop("disabled", true);
			
			return;
		}
	});
	$("[name=avversario_id]").select2();
}

function selectJSON (input,output) {
	/* Fills select with JSON values */
	
	//GIUSEPPE 10/10/2016 ----------------------------- RIPULISCE LA LISTBOX PER EVITARE GLI "APPEND" DEI VALORI
	$(output.selector + " option").remove();
	
	
	if(input.length>0)
	{
		$(output).append('<option value>Seleziona</option>');
	}
	else
	{
		$(output).append('<option value>Nessun Risultato</option>');
	}
	//-----------------------------------------
	
	
	for (var i = 0; i < input.length; i++) {
		$(output).append('<option value="' + input[i].id +'">' + input[i].value + '</option>');
	}
	$(output).find("option:first").attr("selected");
	$(output).select2();
	
	
}

function ajaxLoader(opt) {
	
	/* Displays ajax loader (parameters: 'show', 'hide') */
	
	if (opt == 'show') {
		
		$("#ajax-loader").fadeIn(200);
		
		} else {
		
		$("#ajax-loader").fadeOut(200);
		
	}
	
}

// LET'S DO THIS PIG DIO (D-INPUT/OUTPUT)
var imexecuting2 = 0;

var imexecuting = 0;
function getFilter(champ_id,half_id,type,noTrigger, cb, appendTo) {
	
	//console.log("- getFilter executed - noTrigger = " + noTrigger);
	
	
	if (imexecuting == 1) 
	return;
	imexecuting = 1;
	ajaxLoader('show');
	
	
	if (champ_id == undefined) 
	champ_id = $("*[name='campionati_id']").val()
	
	
	if ($("[name='squadra_id']").val() != 0) 
	squadra_id = $("*[name='squadra_id']").val();
	else
	squarda_id = 0;
	
	if (champ_id == "" && $("[name='campionati_id']").val() == "") 
	{
		ajaxLoader('hide');
		return;
	}
	
	$.get("/sections/getFilter/" + champ_id + "/" + half_id + "/" + type + "/" + squadra_id,function(data) {
		var $data = data;
		if(appendTo)
		{
			$(appendTo).html($data).css('opacity',0).slideDown(300,function() {
				
				$(this).animate({'opacity': 1},500);
				
				if ($("*[name='squadra_id']").val() != 0)
				if (noTrigger == undefined)
				$("*[name='squadra_id']").trigger('change');
				
				location.hash = '#campionato='+champ_id+'-girone='+half_id+'-tab='+type;
				
				
				
			});
			} else {
			$(".table-container").html($data).css('opacity',0).slideDown(300,function() {
				$(this).animate({'opacity': 1},500);
				
				if ($("*[name='squadra_id']").val() != 0)
				if (noTrigger == undefined)
				$("*[name='squadra_id']").trigger('change');
				
				location.hash = '#campionato='+champ_id+'-girone='+half_id+'-tab='+type;
			});
		}
		
		ajaxLoader('hide');
		imexecuting = 0;
		// if(cb)
		// 	cb();
		hideSquadrav2();
		
		
		
		
	},'html');
}


// Gestione degli hash nell'url
$(document).ready(function(){
	
	if(location.pathname == '/') {
		
		var info       = location.hash.replace('#','').split('-');
		
		var info_champ = (info[0] != undefined)? info[0].split('=') : 0;
		var info_half  = (info[1] != undefined)? info[1].split('=') : 0;
		var info_tab   = (info[2] != undefined)? info[2].split('=') : 0;
		
		var champ      = info_champ[1];
		var half       = info_half[1];
		var tab        = info_tab[1];
		
		
		
		if(champ != undefined && half != undefined) {
			
			$("[name='campionato_id']:visible").val(champ).trigger('change');
			
			triggerHalf = setInterval(function(){
				
				if($("[name='girone_id']").find('option[value='+half+']').length > 0) {
					
					
					$("[name='girone_id']").val(half).trigger('change');
					clearInterval(triggerHalf);
					
				}
				
			},300);
			
			
			// triggerTab = setInterval(function(){
			
			// 	if(tab == 'squadra')
			// 		tab = 'calendario';
			
			// 	console.log(tab);
			// 	if($('.switch-filters').find('li[data-value="'+tab+'"]').length > 0) {
			
			// 		$('.switch-filters').find('li[data-value="'+tab+'"] a').trigger('click');
			// 		clearInterval(triggerTab);
			
			// 	}
			
			// },300);
			
		}
		
	}
	
});

// Pdf download
$(document).on('click', ".nota-gara", function() {
	
	
	location.href = '/sections/getNotes/' + $(this).attr('data-match-id') + '/' + $("[name='squadra_id']").val();
	
});

// Cambio di tabella
$(document).on('click', ".switch-filters li", function(e) {
	
	
	var $target = $(e.target).parent();
	$target.parent().find('li:not(.switch-value)').not($target).removeClass('active');
	
	$target.addClass('active');
	
	tab = $target.data("value");
	if(typeof home != "undefined")
	getFilter($("[name=campionato_id]").val(),$("[name='girone_id']").val(),tab);
	
});

// Cambio giornata menu classifica
$(document).on('click', '.switch-giornata.classifica', function(e){
	
	var $target = $(e.target).parent();
	ajaxLoader('show');
	
	$target.find('li:not(.switch-value)').not($target).removeClass('active');
	
	$target.addClass('active');
	
	$.get('/sections/getFilter/'+$("[name=campionato_id]").val()+'/'+$("[name=girone_id]").val()+'/classifica/0/' + $(e.target).parent().data('giornata-id'), function(data){
		
		$('.table-container').html(data);
		ajaxLoader('hide');
		
	});
	
});

// Cambio giornata menu calendario
$(document).on("click", ".switch-giornata.calendario, .search-opponent .switch-giornata", function(e){
	
	var $element = $(e.target).parent();
	
	var giornata_id = $element.data('giornata-id');
	
	$(".table-matches").addClass('hidden');
	$(".table-matches[data-giornata-id='" + giornata_id +"']").removeClass('hidden');
	
	$(".other-info-row").addClass('hidden');
	$(".other-info-row[data-giornata-id='" + giornata_id + "']").removeClass('hidden');
	
	$(".match-comunication").addClass('hidden');
	$(".match-comunication[data-giornata-id='" + giornata_id + "']").removeClass('hidden');
	
	$(".switch-giornata").removeClass('active');
	$(".switch-giornata[data-giornata-id='" + giornata_id + "']").addClass('active');
	
	if($(e.target).parent().parent().parent().hasClass("search-opponent"))
	{
		
		displayNote($("[name=avversario_id]").val());
	}
	
});


// Scelta avversario
$(document).on('change', "[name='avversario_id']", function() {
	
	ajaxLoader('show');
	
	$.post('/sections/getOpponent/' + $(this).val() + '/' + $("[name='squadra_id']").val(), function(data) {
		
		$(".match-results-menu li").remove();
		$(".match-results-menu").removeClass('hidden');
		for (var i = 0; i < data.length; i++) {
			
			$(".match-results-menu").append('<li class="switch-giornata" data-giornata-id="' + data[i] + '"><a href="javascript:;" title="">Giornata ' + data[i] + '</a></li>');	
		}
		
		$(".search-opponent .switch-giornata").last().find("a").click().parent().addClass('active');
		setTimeout(function() {
			$(".search-opponent .switch-giornata").last().addClass('active');
		},1000);
		ajaxLoader('hide');
		
	},'json');
});


// Eventi lanciati dalle select dei filtri (torneo, girone, squadra)
$(document).on("change", ".flt", function(e){
	
	var $element = $(e.target);
	
	
	
	if($element.attr("id") == "campionato_id")
	{
		
		ajaxLoader('show');
		
		reset($(".select-girone, .select-squadre")); 
		
		// GIUSEPPE 07/10/2016 ---------------------------------
		
		// NON SERVE PIU: IL CLIENTE AVEVA VALUTATO IN MODO ERRONEO LA NON VISUALIZZAZIONE DEI GIRONI TENNIS
		
		
		// if($(this).val()<0) // qui sappiamo che si tratta di tennis
		// {
		// $.get("/sections/getSquadreTennisFromCampionato/" + $(this).val(),function(data) {
		// selectJSON(data,$(".select-squadre")); // salto i gironi e vado direttamente alle squadre
		// ajaxLoader('hide');
		// },'json');
		
		// }
		// else
		// {
		// $.get("/sections/getGironiFromCampionato/" + $(this).val(),function(data) {
		// selectJSON(data,$(".select-girone"));
		// ajaxLoader('hide');
		// },'json');
		// }
		
		// -----------------------------------------------
		
		$.get("/sections/getGironiFromCampionato/" + $(this).val(),function(data) {
			selectJSON(data,$(".select-girone"));
			ajaxLoader('hide');
		},'json');
		
		
		
	} 
	else if($element.attr("id") == "girone_id")
	{
		if($("#girone_id").val() == "")
		{
			hideTable();
		} 
		else 
		{
			ajaxLoader('show');
			reset($(".select-squadre"));
			var page_get = "/sections/getSquadreFromGirone/" + $(this).val();
			$.get(page_get,function(data) {
				showTable();
				selectJSON(data,$(".select-squadre"));
				ajaxLoader('hide');

			},'json');
			
			
			$(".switch-filters").removeClass('hidden');
			
			if($('[name="filter_select"]').val() != 'squadra')
			tab = $('[name="filter_select"]').val();
			else
			tab = 'calendario';
			
			getFilter($("[name='campionato_id']:visible").val(),$element.val(),tab);
			
			if(tab == 'calendario') {
				
				t = setTimeout(function(){
					$('.select-squadre').find('.checkbox-unset:visible').trigger('click');
					$('.switch-button').find('li').removeClass('active');
					$('.switch-button').find('li[data-value="calendario"]').addClass('active');
				},500);
				
			}
		}
		
		
		
	}
	else if($element.attr("id") == "squadra_id")
	{
		$(".nota-gara").hide();
		
		
		var squadra = $element.val();
		
		$("[name='filter_team']").attr('value','true');
		
		
		$(".table-matches tr").removeClass('active');
		
		displayNote(squadra);
		
		hideSelectedSquadra();
		
		$(".search-opponent").removeClass('hidden');
		$(".search-opponent select[data-squadra-id=" + squadra + "]").addClass('hidden');
		
		if ($("#team-button").hasClass('active')) 
		{
			getFilter($("[name='campionato_id']:visible").val(),$("[name='girone_id']").val(),$("[name='filter_select']").val(),1);
		}
		
		if($("[name=avversario_id]").length)
		$("[name=avversario_id]").select2();
		
	}
	
	
});

function displayNote(squadra)
{
	$(".table-matches tr").removeClass('active');
	$(".table-matches tr[data-casa-id=" + squadra + "]").addClass('active');
	
	$(".table-matches tr[data-trasferta-id='" + squadra + "']").addClass('active');
	
	$(".table-matches tr").find(".nota-gara").hide();
	$(".table-matches tr[data-casa-id=" + squadra + "]").find(".nota-gara").show();
	$(".table-matches tr[data-trasferta-id=" + squadra + "]").find(".nota-gara").show();
}


function hideSelectedSquadra()
{
	var squadra_id = $("#squadra_id option:selected").val();
	var $rivals = $("[name=avversario_id] option");
	
	$rivals.prop("disabled", false);
	$rivals.each(function(){
		if( squadra_id == $(this).val() )
		{
			$(this).prop("disabled", true);
			return;
		}
	});
}

function hideTable()
{
	$(".table-container").html("");
	$(".switch-filters").hide();
}

function showTable()
{
	$(".switch-filters").show();
}


function reset(el)
{
	$(el).find("option:not(:first)").remove();
}

function timmy_close() {
	
	$(".modal").modal('hide');
}
function timmy_load(url) {
	
	//var modal_id = $(this).attr('data-target');
	$.get(url, function(data) {
		
		data = $(data);
		
		if (data.find('.modal-title').length > 0) {
			$(data).find('.modal-title-tmp').html($(data).find('.modal-title').html());
			$(data).find('.modal-title').remove();
			$(data).find('.modal-title-tmp').removeClass('modal-title-tmp').addClass('modal-title');
		}
		$(data).modal();
	},'html');
}

$(document).ready(function() {
	
	function reset_thumbnails() {
		
		$("div.img-thumbnail").each(function() {
			
			if ($(this).find('div:not(.lightbox)').length > 0 && $(this).closest('.post-large').length == 0) {
				var thisw = $(this).outerWidth();
				var thisww = $(this).width();
				$(this).outerHeight(thisw);
				//$(this).css('line-height',thisww);
				$(this).find('div').width(thisww).height(thisww);
			}
			
		});
		
	}
	reset_thumbnails();
	$(window).resize(reset_thumbnails);
	
	
	if($("#campionato_id").length)
	$("#campionato_id").select2();
	if($("#girone_id").length)
	$("#girone_id").select2();
	if($("#squadra_id").length)
	$("#squadra_id").select2();
	
	
});															