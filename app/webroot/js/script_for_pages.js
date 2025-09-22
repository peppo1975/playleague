$(function(){

var loc_hash = location.hash;

$('.index-order-argument select').find('option[value="random()"]').remove();
$('.index-order-argument select').find('option[value="published"]').remove();

if(loc_hash == '#addMode') {

	$('.add').trigger('click');
	location.hash = '';

}

$('.add').click(function(){

	if($('.view_mode').is(':hidden') == false) {
	
		location.hash = '#addMode';
		location.reload();
	
	}

});

});