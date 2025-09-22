$( document ).on( "pageshow", function( event ) {
	var id = getUrlVars()["s"];
	displaySheet();
});

$( document ).on( "pageinit", function( event ) {
	var id = getUrlVars()["s"];
	displaySheet();
});
