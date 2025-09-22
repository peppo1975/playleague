<script type="text/javascript">
if (typeof $ != "undefined") {
		$(function() {
					
				<?if(isset($_GET['selected'])):?> 
				
					var selected = <?=$_GET['selected'];?>;
					
					var container = $('.tab-container');
					
					$(container).find('.tab-selector li').removeClass('selected');
					
					$(container).find('.tab-selector li[data-index="' + selected + '"]').addClass('selected');
					
					$(container).find('.tab-page').removeClass('tab-selected');
					
					$(container).find('.tab-page[data-index="' + selected + '"]').addClass('tab-selected');
						
				<?endif;?>
				
		});
		
		// $(function(){

			// $('.tab-selector').bind('click', function(){
			
				// var empty = 0;
			
				// $("div.required").each(function(index){
			
					// if($(this).find('input').val() == '') empty = 1;
			
				// });
						
				// if(empty == 1) return false;
				
				
			
			// });
			
		// });
}
</script>