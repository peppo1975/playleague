$(function(){

var loc_hash = location.hash;

$('.index-order-argument select').find('option[value="random()"]').remove();
// $('.index-order-argument select').find('option[value="published"]').remove();

if(loc_hash == '#addMode') {

	$('.add').trigger('click');
	location.hash = '';

}else $('.search').click();

$('.add').click(function(){

	if($('.view_mode').is(':hidden') == false) {
	
		location.hash = '#addMode';
		location.reload();
	
	}

});

$('ul.table_operations li:eq(1)').hide();
$('ul.table_operations li:eq(2)').hide();

$('ul.table_operations li:eq(1)').hide();
$('ul.table_operations li:eq(2)').hide();

console.log('ul.table_operations');

});

$(document).ready(function(){
	
	$('ul.table_operations:eq(0) li:eq(1)').hide();
	$('ul.table_operations:eq(0) li:eq(2)').hide();
	$('ul.table_operations:eq(1) li:eq(1)').hide();
	$('ul.table_operations:eq(1) li:eq(2)').hide();
	
});