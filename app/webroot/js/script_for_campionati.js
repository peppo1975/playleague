$(function(){

	$(".quickSearch").addClass('autoComplete').attr('data-url','/admin/campionatis/searchCampionato').attr('data-dest','NoID');

	$('.index-row-delete-match').live('click', function(){

		if(confirm('Sei sicuro di voler eliminare il calendario?')) {
		
			$.get('/admin/campionatis/deleteMatches/'+$(this).attr('data-id'), function(ret){
			
				if(ret.del == 1) {
				
					alert('Calendario eliminato con successo.');
				
				} else {
				
					alert('Manifestazione in corso, impossibile cancellare.');
				
				}
			
			},'json');
		
		}

	});
	
	var max = 0;
	var max_value = '';
	
	$('.td_nome').each(function(){
	
		if($(this).text().length > max) {

			max = $(this).text().length;
			max_value = $(this).text();
			
		}
	
	});
	
	$('.td_nome').css('min-width', 121+(max*4));
	$('.td_anno').css('min-width', 65);
	$('.td_in_corso').css('min-width', 85);
	$('.td_in_uso').css('min-width', 75);
	
});
