$(function(){
	
	$('td.tools').css('min-width', 70);
	$('.td_giornata').css('min-width', 90);
	$('.search').remove();
	
		$('.td_campionato, .td_note, .td_girone').css('overflow','hidden')
						   .css('white-space','nowrap')
						   .css('text-overflow','ellipsis');
	
});