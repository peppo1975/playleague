$(function(){

	
	if(location.hash != '') {
	
		$("#index_table").find('a[data-ajax="/admin/br_reports/edit/'+location.hash.replace('#','')+'?modded=true"]').click();
	
	}


});