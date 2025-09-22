
$(document).ready(function() {


		

		$(document).on("click", ".header-extra-info h2", function(e) {
/*
			e.preventDefault();
			e.stopPropagation();
			*/
			
			return;
			$(".header-extra-info h2").removeClass('selected');
			$(this).addClass('selected');

			var menu = $(this).data('menu');
			if(typeof home !== 'undefined')
			{
				menuChanged(menu);

				
			} else {
				document.location = "/#menu="+menu;
			}
	 		

			/*return false;*/
		});

	if(typeof home !== "undefined")
		{
			bodydisp();
			menu = location.hash.replace("#", "").split("-");
			if(menu[0] != ""){
				menu = menu[0].split("=");
				if(menu[0] == "menu")
				{
					
					$(".header-extra-info h2[data-menu="+menu[1]+"]").click();
				}
			}
			
		} else {

			menuChanged($(".header-extra-info h2.selected").data('menu'));

		}


	function menuChanged(menu){
		$(".header-nav-bar").attr('style','display: none !important;');
		$(".header-nav-bar-" + menu).attr('style','');

		$(".slider-wrapper").css('opacity',0).css('position','absolute').css('top','-9999px');

		$(".slider-wrapper[data-menu='"+menu+"']").css('opacity',1).css('position','static').css('top','auto');

		if(menu == "secondary")
		{
			$("#logo").attr("href", "/#menu=secondary");
			$(".impianti-prenotazioni").hide(100);
			$(".ultima-ora").hide(100);
			
			$(".section.social").hide(100);
			$("<link rel=\"stylesheet\" href=\"/css/theme-elements-secondary.css\">").appendTo("head");
			$("<link rel=\"stylesheet\" href=\"/css/skins/default-secondary.css\">").appendTo("head");

			$(".news-redazione").hide(100);
			$(".news-redazione-scuolaa5").show(100);
			$(".news-redazione-scuolaa5").removeClass("col-md-9").addClass("col-md-12");

			$(".secondary").show(100);
			$(".primary").hide(150, bodydisp);

			$(".filter-primary h2").html('Gli eventi della scuola<span>Date, campi e orari di partite e raggruppamenti con le convocazioni</span>');

			if($("[name=campionato_id]").length)
				$("[name=campionato_id]").select2();
		} else if( menu == "primary"){
			$("#logo").attr("href", "/");
			$(".impianti-prenotazioni").hide(100);
			$(".ultima-ora").show(100);
			$(".news-redazione").addClass("col-md-9").removeClass("col-md-12");
			$(".section.social").show(100);
			$("link[href='/css/skins/default-secondary.css']").remove();
			$("link[href='/css/theme-elements-secondary.css']").remove();

			$(".news-redazione-scuolaa5").hide(100);
			$(".news-redazione").show(100);
			$(".news-redazione").removeClass("col-md-12").addClass("col-md-9");

			$(".secondary").hide(100);
			$(".primary").show(150, bodydisp);
			$(".filter-primary h2").html(' Calendari e classifiche <span>Tutti i calendari di gioco, risultati, classifiche e disciplinari</span>');


		}


	}

		if (document.location.hostname.indexOf('midlandgs') > -1) {

			menuChanged('secondary');

			$(".menu-primary,.menu-secondary").removeClass('selected');
			$(".menu-secondary").addClass('selected');
		}


	function bodydisp()
	{
		$("body").css("opacity", 1);
		var $owl = $('.owl-carousel');
		$owl.trigger('destroy.owl.carousel');

		$owl.html($owl.find('.owl-stage-outer').html()).removeClass('owl-loaded');
		$owl.each(function() {

		var input = JSON.parse($(this).attr('data-plugin-options'));
		$(this).owlCarousel(input);
		$(".owl-next").html('');
		$(".owl-prev").html('');
		   });

	}
});