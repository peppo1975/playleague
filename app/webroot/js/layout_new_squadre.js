
// Pdf download
$(document).on('click', ".nota-gara", function() {


	location.href = '/sections/getNotes/' + $(this).attr('data-match-id') + '/' + $("[name='squadra_id']").val();

});


// Cambio giornata menu classifica
$(document).on('click', '.switch-giornata.classifica', function(e){

		var $target = $(e.target).parent();
		ajaxLoader('show');

		$target.find('li:not(.switch-value)').not($target).removeClass('active');

		$target.addClass('active');

		$.get('/sections/getFilter/'+$("[name=campionati_id]").val()+'/'+$("[name=girone_id]").val()+'/classifica/0/' + $(e.target).parent().data('giornata-id'), function(data){

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
		$.get("/sections/getGironiFromCampionato/" + $(this).val(),function(data) {
			selectJSON(data,$(".select-girone"));
			ajaxLoader('hide');
		},'json');


	} else if($element.attr("id") == "girone_id"){
		if($("#girone_id").val() == "")
		{
			hideTable();
		} else {
			ajaxLoader('show');
			reset($(".select-squadre"));
			$.get("/sections/getSquadreFromGirone/" + $(this).val(),function(data) {
					showTable();
					selectJSON(data,$(".select-squadre"));

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



	} else if($element.attr("id") == "squadra_id") {
			$(".nota-gara").hide();
			

			var squadra = $element.val();

			$("[name='filter_team']").attr('value','true');


			$(".table-matches tr").removeClass('active');

			displayNote(squadra);
			hideSelectedSquadra();

			$(".search-opponent").removeClass('hidden');
			$(".search-opponent select[data-squadra-id=" + squadra + "]").addClass('hidden');

			if ($("#team-button").hasClass('active')) {


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

	$rivals.show();
	$rivals.each(function(){
		if( squadra_id == $(this).val() )
		{
			$(this).hide();
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