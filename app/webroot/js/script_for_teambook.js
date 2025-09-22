$(function(){


	$("#index_table").find('.th_span_class_order_debito_img_width_12_height_17_alt_src_img_timmyshare_order_default_png_span_debito')
					 .removeClass('. th_span_class_order_debito_img_width_12_height_17_alt_src_img_timmyshare_order_default_png_span_debito')
					 .addClass(' th_debito');
					 
	$("#index_table").find('.td_span_class_order_debito_img_width_12_height_17_alt_src_img_timmyshare_order_default_png_span_debito')
					 .removeClass('. td_span_class_order_debito_img_width_12_height_17_alt_src_img_timmyshare_order_default_png_span_debito')
					 .addClass(' td_debito');					 
					 
	$('.order_debito').css('cursor','pointer');
					 
	$('.order_debito').live('click',function(){
	
		timmyloader('show');
	
		var type_order = $(this).find('img');
		
		if(type_order.attr('src') == '/img/timmyshare/order_desc.png') {
		
			location.reload();
		
		} else {		
		
			type_order.attr('src','/img/timmyshare/order_desc.png');
			
		}
	
		var debito = new Array;
		
		$("#index_table").find('.td_debito').each(function(){
		
			$(this).attr('data-debito', $(this).text());
		
			debito.push($(this).text());
		
		});
		
		debito.sort(function(a,b){return a - b});
		$.unique(debito);
		
		var old_table = $("#index_table");
		var new_table = old_table.clone();
		
		new_table.find('.index-row').remove();
		
		for(var i in debito) {
		
			$("#index_table").find('tr:has(td[data-debito=' + debito[i] + '])').each(function(){
			
				var tr = $(this);
					tr.removeClass('alterna');
				
				new_table.append(tr);
			
			});
		
		}
		
		new_table.find('tr:even').addClass('alterna');
		
		old_table.remove();
		
		$('.table_container').append(new_table);
		
		timmyloader('hide');
	
	});
	
});