/** jQuery mobile Swipe Up / Down Event */
    (function() {
// initializes touch and scroll events
        var supportTouch = $.support.touch,
                scrollEvent = "touchmove scroll",
                touchStartEvent = supportTouch ? "touchstart" : "mousedown",
                touchStopEvent = supportTouch ? "touchend" : "mouseup",
                touchMoveEvent = supportTouch ? "touchmove" : "mousemove";
 
 // handles swipeup and swipedown
        $.event.special.swipeupdown = {
            setup: function() {
                var thisObject = this;
                var $this = $(thisObject);
 
                $this.bind(touchStartEvent, function(event) {
                    var data = event.originalEvent.touches ?
                            event.originalEvent.touches[ 0 ] :
                            event,
                            start = {
                                time: (new Date).getTime(),
                                coords: [ data.pageX, data.pageY ],
                                origin: $(event.target)
                            },
                            stop;
 
                    function moveHandler(event) {
                        if (!start) {
                            return;
                        }
 
                        var data = event.originalEvent.touches ?
                                event.originalEvent.touches[ 0 ] :
                                event;
                        stop = {
                            time: (new Date).getTime(),
                            coords: [ data.pageX, data.pageY ]
                        };
 
                        // prevent scrolling
                        if (Math.abs(start.coords[1] - stop.coords[1]) > 10) {
                            event.preventDefault();
                        }
                    }
 
                    $this
                            .bind(touchMoveEvent, moveHandler)
                            .one(touchStopEvent, function(event) {
                        $this.unbind(touchMoveEvent, moveHandler);
                        if (start && stop) {
                            if (stop.time - start.time < 1000 &&
                                    Math.abs(start.coords[1] - stop.coords[1]) > 30 &&
                                    Math.abs(start.coords[0] - stop.coords[0]) < 75) {
                                start.origin
                                        .trigger("swipeupdown")
                                        .trigger(start.coords[1] > stop.coords[1] ? "swipeup" : "swipedown");
                            }
                        }
                        start = stop = undefined;
                    });
                });
            }
        };
 
//Adds the events to the jQuery events special collection
        $.each({
            swipedown: "swipeupdown",
            swipeup: "swipeupdown"
        }, function(event, sourceEvent){
            $.event.special[event] = {
                setup: function(){
                    $(this).bind(sourceEvent, $.noop);
                }
            };
        });
 
    })();

/** Get param of url Es: ?id=1 */
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function getUrlVarsByUrl(url_href) {
    var vars = [], hash;
    var hashes = url_href.slice(url_href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

/** Display menu */
function displayMenu() {

	var vmAPI = serviceURL + 'menu?jsoncallback=?';
	
	$.getJSON( vmAPI, {
		format: "json"
	}).done(function(data){
	
		$('#cbp-spmenu-s1').find('a.ajax-load-sht-tg-lst').remove();
		$(data.html).insertAfter($('#cbp-spmenu-s1').find('a:eq(0)'));
	
	});

}

/** Get info */
function displayInfo() {

	var vmAPI = serviceURL + 'info?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json"
	}).done(function(data){
	
		$('#info-detail').css('opacity',0);
		$('#info-detail').html(data.html);
		$('#info-detail').css('opacity',1); 	
		
		$.mobile.hidePageLoadingMsg();		
	
	});
	
}

/** Get sheet list */
function getSheetList() {

	var vmAPI = serviceURL + 'sheetlist?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json"
	}).done(function(data){
	
		$('#sheet-list').css('opacity',0);
		$('#sheet-list').html(data.html);
		$('#sheet-list').find('ul').listview();
		$('#sheet-list').css('opacity',1); 	
		
		$.mobile.hidePageLoadingMsg();		
	
	});
	
}

/** Get tag sheet list */
function getSheetTgList(data) {

	var tg_id = data;
	var vmAPI = serviceURL + 'tgsheetlist?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format : "json",
		id_tag : tg_id
	}).done(function(data){
	
		$('#sheet-tg-list').css('opacity',0);
		$('#sheet-tg-list').attr('data-tg-id', tg_id).html(data.html);
		$('#sheet-tg-list').find('ul').listview();
		$('#sheet-tg-list').css('opacity',1); 	
		
		$.mobile.hidePageLoadingMsg();		
	
	});
	
}

/** Get Events list */
function getEventsList() {

	var vmAPI = serviceURL + 'eventslist?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json"
	}).done(function(data){
	
		$('#vnts-list').css('opacity',0);
		$('#vnts-list').html(data.html);
		$('#vnts-list').find('ul.listview').listview();
		$('#vnts-list').css('opacity',1); 	
		
		$.mobile.hidePageLoadingMsg();		
	
	});
	
}

/** Display sheet detail */
function displaySheet(data) {

	var sht_id = data;
	var vmAPI  = serviceURL + 'sheetdetail?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json",
		id_card : data
	}, function(data){
	
		$('#sheet-detail').attr('data-sht-id', sht_id).html(data.html);
		$.mobile.hidePageLoadingMsg();
	
	});
	
}

/** Display sheet detail */
function displayEvent(data) {

	var vnts_id = data;
	var vmAPI   = serviceURL + 'eventdetail?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json",
		id_event : data
	}, function(data){
	
		$('#event-detail').attr('data-vnts-id', vnts_id).html(data.html);
		$.mobile.hidePageLoadingMsg();
	
	});
	
}

$(document).bind('pagebeforeshow', function(e){
//	$(this).remove();
//	alert('fire');
//$(e.target).remove();
});
$(document).bind('pageinit', function () {
	$.mobile.defaultPageTransition = 'none';
	$.mobile.page.prototype.options.domCache = false;
	$.mobile.pushStateEnabled = false;
	$.mobile.changePage.defaults.changeHash = false;   
	$.mobile.ignoreContentEnabled=true;   
	$.mobile.linkBindingEnabled = false; // delegating all the events to chaplin  
	$.mobile.ajaxEnabled = false;
	$.mobile.hashListeningEnabled = false;  
});

// Bind to the navigate event
$( window ).on( "navigate", function( event, data ) {
  return false;
});

/** Map locator */
function show_map(id_element, model) {

	if(typeof(model) == "undefined")
		model = "Card";

	var header_container = $('.hdr');
	var map_container    = header_container.find('.mp-cntnr');
	var header           = header_container.find('header');
	
	var page_height      = window.innerHeight-header.height();
	
	map_container.height(page_height);
	header_container.css('position','fixed').css('top', '-' + page_height + 'px');
	header_container.css('top', 0);
	header_container.addClass('open');
	
	var vmAPI = serviceURL + 'loadMap/'+id_element+'/'+model+'?jsoncallback=?';

	$.mobile.showPageLoadingMsg();
	
	$.getJSON( vmAPI, {
		format: "json",
	}, function(data){
	
		map_container.html(data.html);
		$.mobile.hidePageLoadingMsg();
	
	});

}

function hide_map() {

	var header_container = $('.hdr');
	var map_container    = header_container.find('.mp-cntnr');
	var header           = header_container.find('header');
	
	var page_height      = $(window).height()-header.height();
	
	map_container.height(page_height);
	header_container.css('top', '-' + page_height + 'px');
	header_container.removeClass('open');

	setTimeout(function(){
		header_container.css('position','');
	},200);
	
	map_container.empty();

}

$(window).resize(function(){

	if($('.hdr').css('top').replace('px','') == 0)
		{
			var header_container = $('.hdr');
			var map_container    = header_container.find('.mp-cntnr');
			var header           = header_container.find('header');
	
			var page_height      = window.innerHeight-header.height();
	
			map_container.height(page_height);
			header_container.css('top', '-' + page_height + 'px');
			header_container.css('top', 0);	
			if (typeof(map) != "undefined")
				google.maps.event.trigger(map, 'resize');	
		}

});

$(function(){

/*
	$('.hdr').bind('swipeup', function(){
		hide_map();
	});
*/
	
	$( document ).on( "click", ".mp-cls", function() {
	  hide_map();
	});	

	$( document ).on("click", ".mn-lnggs ul li:not(slctd)", function(){
	
		var me = $(this);
	
		$('.mn-lnggs').find('ul').find('li').removeClass('slctd');
		me.addClass('slctd');
		
		setCookie('lang', me.attr('data-id'), 30);
		
		/* Change html */
		var page_id = $('.pg-wrppr').attr('id');
		
		console.log(page_id);
		
		$.getScript( "js/translates.js", function(){
		
			/** translate main page */
			switch(page_id) {
		
				case 'sheet-list':
					$('a.ajax-load-sht-lst').trigger('click');
				break;

				case 'sheet-tg-list':
					var tmp_href = $('<a>').addClass('ajax-load-sht-tg-lst').css('opacity', 0).attr('href', 'indextg.html?s=' + $('.pg-wrppr').attr('data-tg-id'));
						$('body').append(tmp_href);
						tmp_href.trigger('click').remove();
				break;
			
				case 'sheet-detail':
					var tmp_href = $('<a>').addClass('ajax-load-sht').css('opacity', 0).attr('href', 'sheet.html?s=' + $('.pg-wrppr').attr('data-sht-id'));
						$('body').append(tmp_href);
						tmp_href.trigger('click').remove();
				break;
			
				case 'vnts-list': 
					$('a.ajax-load-vnts-lst').trigger('click');
				break;
			
				case 'event-detail':
					var tmp_href = $('<a>').addClass('ajax-load-vnts').css('opacity', 0).attr('href', 'event.html?s=' + $('.pg-wrppr').attr('data-vnts-id'));
						$('body').append(tmp_href);
						tmp_href.trigger('click').remove();				
				break;
			
				case 'info-detail':
					$('a.ajax-load-info').trigger('click');
				break;
		
			}
			
			/** translate menu */
			//translates[lang+'.Luoghi']			
			
			$('#cbp-spmenu-s1').find('a').each(function(){
				var item = $(this);
					item.text(translates[me.attr('data-id')+'.'+item.attr('data-lang-string')]);
			});
			
		
		});
		
		return false;
	
	});

});

function isiOS() {
	return (navigator.userAgent.match(/like Mac OS X/i)) ? true: false;
}

/** Menu function */
var showLeftPush = document.getElementById('mn-icon');
var menuLeft     = document.getElementById('cbp-spmenu-s1');
var documentBody = body = document.body;

	showLeftPush.onclick = function(e) {
		e.stopPropagation();
		e.preventDefault();
		classie.toggle( this, 'active' );
		classie.toggle( menuLeft, 'cbp-spmenu-open' );
		classie.toggle( documentBody, 'cbp-spmenu-push-toright' );		
	};
	
$(function(){

	$('div[data-role="page"]').bind('click', function(e){
	
		//Menu close condition
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}
	
	});

});

document.getElementById('pg-ndx').addEventListener("touchmove", function(event) {
  if ($('body').hasClass('cbp-spmenu-push-toright')) {
    // no more scrolling
    event.preventDefault();
  }
}, false);

/** Phonegap Ajax Custom Navigation */

$(function(){
	
	/** Ajax open sheet */
	$(document).on('click','a.ajax-load-sht', function(e){
	
		var obj    = $(this);
		var sht_id = getUrlVarsByUrl(obj.attr('href'))["s"];
		
		/** Browser history */
		history.pushState({}, '', obj.attr('href'));		

		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);

		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-sht-lst').addClass('slctd');
		
		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}						
		
		/** Set body id */
		$('body').attr('id','shts');
		$('#pg-ndx').removeClass('vnts-wrapper-grdnt');
		$('.pg-wrppr').attr('id', 'sheet-detail').empty();
		
		/** Back button */
		toggleBack('show');		
		
		displaySheet(sht_id);
		
		return false;
	
	});
	
	/** Ajax open sheet list */
	$(document).on('click','a.ajax-load-sht-lst', function(e){
	
		if($('.hdr').hasClass('open')) {
			hide_map();
		}	
	
		var obj    = $(this);

		/** Browser history */
		history.pushState({}, '', obj.attr('href'));	

		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);		

		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-sht-lst').addClass('slctd');		

		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}		

		/** Set body id */
		$('body').attr('id','');
		$('#pg-ndx').removeClass('vnts-wrapper-grdnt');		
		$('.pg-wrppr').attr('id', 'sheet-list').empty();
				
		/** Back button */
		toggleBack('hide');				
				
		getSheetList();
		
		return false;
	
	});	

	/** Ajax open sheet tag list */
	$(document).on('click','a.ajax-load-sht-tg-lst', function(e){
	
		var obj    = $(this);
		var tag_id = getUrlVarsByUrl(obj.attr('href'))["s"];

		/** Browser history */
		history.pushState({}, '', obj.attr('href'));	
		
		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);		

		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-sht-tg-lst[data-id='+ tag_id +']').addClass('slctd');		

		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}		
		
		/** Set body id */
		$('body').attr('id','');
		$('#pg-ndx').removeClass('vnts-wrapper-grdnt');		
		$('.pg-wrppr').attr('id', 'sheet-tg-list').empty();
				
		/** Back button */
		toggleBack('hide');				
				
		getSheetTgList(tag_id);
		
		return false;
	
	});	

	/** Ajax open events list */
	$(document).on('click','a.ajax-load-vnts-lst', function(e){
	
		var obj    = $(this);
		
		/** Browser history */
		history.pushState({}, '', obj.attr('href'));
		
		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);					
		
		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-vnts-lst').addClass('slctd');				
		
		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}		
		
		/** Set body id */
		$('body').attr('id','vnts');	
		$('#pg-ndx').addClass('vnts-wrapper-grdnt');					
		$('.pg-wrppr').attr('id', 'vnts-list').empty();
		
		/** Back button */
		toggleBack('hide');		
		
		getEventsList();
		
		return false;
	
	});
	
	/** Ajax open event */
	$(document).on('click','a.ajax-load-vnts', function(e){
	
		var obj     = $(this);
		var vnts_id = getUrlVarsByUrl(obj.attr('href'))["s"];
		
		/** Browser history */
		history.pushState({}, '', obj.attr('href'));
		
		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);					
		
		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-vnts-lst').addClass('slctd');						
		
		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}				
		
		/** Set body id */
		$('body').attr('id','vnts');
		$('#pg-ndx').addClass('vnts-wrapper-grdnt');		
		$('.pg-wrppr').attr('id', 'event-detail').empty();
		
		/** Back button */
		toggleBack('show');		
		
		displayEvent(vnts_id);
		
		return false;
	
	});	
	
	/** Ajax open info */
	$(document).on('click','a.ajax-load-info', function(e){
	
		var obj     = $(this);
		
		/** Browser history */
		history.pushState({}, '', obj.attr('href'));	
		
		// "_trackEvent" is the pageview event, 
		_gaq.push(['_trackPageview', obj.attr('href')]);				
		
		/** Set menu slctd class */
		$('.cbp-spmenu').find('a').removeClass('slctd');
		$('.cbp-spmenu').find('a.ajax-load-info').addClass('slctd');						
		
		/** Close menu if open */
		if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
			e.stopPropagation();
			e.preventDefault();
			classie.toggle( this, 'active' );
			classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
		}				
		
		/** Set body id */
		$('body').attr('id','');	
		$('#pg-ndx').removeClass('vnts-wrapper-grdnt');			
		$('.pg-wrppr').attr('id', 'info-detail').empty();
		
		/** Back button */
		toggleBack('hide');		
		
		displayInfo();
		
		return false;
	
	});		

});

/** History function */

if (navigator.userAgent.match(/msie/i) ){


	$(document).ready(function(){
		popFunction();
	});

}

window.onpopstate = function(e){
	popFunction();
	event.preventDefault();
}

function popFunction(){

	var pathname = window.location.pathname;
	
	if(window.location.hash == '#vt' || window.location.hash == '#gallery')
		return false;

	displayMenu();

	$.mobile.showPageLoadingMsg();
	
	// "_trackEvent" is the pageview event, 
	_gaq.push(['_trackPageview', pathname]);
	
	switch(pathname) {
	
		case '/index.html':
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-sht-lst').addClass('slctd');		

			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}		

			/** Set body id */
			$('body').attr('id','');
			$('#pg-ndx').removeClass('vnts-wrapper-grdnt');					
			$('.pg-wrppr').attr('id', 'sheet-list').empty();
			
			/** Back button */
			toggleBack('hide');			
				
			getSheetList();
		break;
		
		case '/indextg.html':
			var tag_id = getUrlVars()["s"];
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			var addTgSlctd = null;
				addTgSlctd = setInterval(function(){
					$('.cbp-spmenu').find('a.ajax-load-sht-tg-lst[data-id='+tag_id+']').addClass('slctd');
					
					if($('.cbp-spmenu').find('a.ajax-load-sht-tg-lst.slctd').length > 0)
						clearInterval(addTgSlctd);	
				},'500');
			
			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}		

			/** Set body id */
			$('body').attr('id','');
			$('#pg-ndx').removeClass('vnts-wrapper-grdnt');					
			$('.pg-wrppr').attr('id', 'sheet-tg-list').empty();
				
			/** Back button */
			toggleBack('hide');			
				
			getSheetTgList(tag_id);
		break;		
				
		case '/sheet.html':
			var sht_id = getUrlVars()["s"];
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-sht-lst').addClass('slctd');
		
			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}						
		
			/** Set body id */
			$('body').attr('id','shts');
			$('#pg-ndx').removeClass('vnts-wrapper-grdnt');					
			$('.pg-wrppr').attr('id', 'sheet-detail').empty();
			
			/** Back button */
			toggleBack('show');			
			
			displaySheet(sht_id);
		break;
		
		case '/events.html':
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-vnts-lst').addClass('slctd');				
		
			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}		
		
			/** Set body id */
			$('body').attr('id','vnts');
			$('#pg-ndx').addClass('vnts-wrapper-grdnt');							
			$('.pg-wrppr').attr('id', 'vnts-list').empty();
		
			/** Back button */
			toggleBack('hide');			
		
			getEventsList();
		break;

		case '/event.html':
			/** Set menu slctd class */
			var vnts_id = getUrlVars()["s"];
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-vnts-lst').addClass('slctd');						
		
			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}				
		
			/** Set body id */
			$('body').attr('id','vnts');	
			$('#pg-ndx').addClass('vnts-wrapper-grdnt');											
			$('.pg-wrppr').attr('id', 'event-detail').empty();
		
			/** Back button */
			toggleBack('show');			
		
			displayEvent(vnts_id);		
		break;

		case '/info.html':
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-info').addClass('slctd');						
		
			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}				
		
			/** Set body id */
			$('body').attr('id','');		
			$('#pg-ndx').removeClass('vnts-wrapper-grdnt');					
			$('.pg-wrppr').attr('id', 'info-detail').empty();
		
			/** Back button */
			toggleBack('hide');			
		
			displayInfo();		
		break;
		
		default:
			/** Set menu slctd class */
			$('.cbp-spmenu').find('a').removeClass('slctd');
			$('.cbp-spmenu').find('a.ajax-load-sht-lst').addClass('slctd');		

			/** Close menu if open */
			if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
				e.stopPropagation();
				e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( documentBody, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
			}		

			/** Set body id */
			$('body').attr('id','');
			$('#pg-ndx').removeClass('vnts-wrapper-grdnt');					
			$('.pg-wrppr').attr('id', 'sheet-list').empty();
				
			/** Back button */
			toggleBack('hide');			
				
			getSheetList();		
		break;
	
	}
	
	/** Set language */
	$('.mn-lnggs').find('ul').find('li[data-id="'+lang+'"]').addClass('slctd');	

}

/** Back button */

function toggleBack(option) {

	var back = $('#mn-back');
	var menu = $('#mn-icon');

	if(typeof(option) == "undefined") {	
		if(back.is(':visible')) {
			back.hide();
			menu.show();
		} else {
			menu.hide();
			back.show();
		}		
	} else {
		if(option == 'hide') {
			back.hide();
			menu.show();			
		} else {
			menu.hide();
			back.show();		
		}
	}

}

function goBack(go_home) {

	if(typeof(go_home) != "undefined") {
		$('.ajax-load-sht-lst').trigger('click');
		$('#mn-back').attr('onclick', 'goBack();');
		return;
	}

	if(window.history.length > 0) {
		window.history.back();
	} else {
		$('.ajax-load-sht-lst').trigger('click');
	}

}

$(document).ready(function(){
	/*
	$('#cbp-spmenu-s1').on('touchstart', function(event){
	});
	*/
});

$( window ).on( "orientationchange", function( event ) {
	if($('#cbp-spmenu-s1').hasClass('cbp-spmenu-open')) {
		location.reload(); 
	}
});

/** Menu drag gestures */
/*

$(function(){
	
	$('body').attr('data-left',0);
	$('body').swipe( {
			triggerOnTouchEnd : true,
			swipeStatus : swipeStatus,
			allowPageScroll:"vertical"
		});

	function swipeStatus(event, phase, direction, distance, fingers) {
	
			var duration = 0;		
	
			if(direction=="right" && distance <= 240 && $('body').attr('data-left') < 240) {
		
				if ( phase =="move" ) {
				
					scrollBody(-distance, duration);				
					
				}
				else if ( phase =="cancel" || phase =="end" )
				{
					if(distance < 120) {
						scrollBody(0, duration);
					} else {
						scrollBody(-240, duration);
					}
			
				}			
		
			}
		
			if(direction=="left" && distance <= 240 && $('body').attr('data-left') <= 240 && $('body').attr('data-left') > 0) {
			
				if ( phase =="move" ) {
				
					var new_distance = 240-distance;
					scrollBody(-new_distance, duration);				
					
				}
				else if ( phase =="cancel" || phase =="end" )
				{
					if(distance < 120) {
						scrollBody(-240, duration);
					} else {
						scrollBody(0, duration);
					}
			
				}					
			
			}
		
	
	}
	
	function scrollBody(distance, duration) {
	
		$('body').css("-webkit-transition-duration", (duration/1000).toFixed(1) + "s");

		//inverse the number we set in the css
		var value = (distance<0 ? "" : "-") + Math.abs(distance).toString();

		$('body').attr('data-left', Math.abs(value)).css("-webkit-transform", "translate3d("+value +"px,0px,0px)");	
	
	}

});

*/