
	<script type="text/javascript">
	
	if (typeof $ != "undefined") {
	
		$(function() {
			
			<? if (isset($admin_writable) && $admin_writable == 0): ?>
			
				$(".formAdd").find('input[value="modifica"]').remove();
				$(".formAdd").find('input[type="reset"]').remove();
			<? endif; ?>
			
		});
	
		$(function() {
		
			var modded = <? if (!isset($_POST['modded']) && !isset($_GET['modded'])): ?> false <? else: ?> true <?endif;?>;
			if (modded == false) 
			$('.formAdd :input').not('[type="submit"]').not("#formReset").attr('disabled', true);
			
			if(modded == true) $(".formAdd input[type='submit']").val('salva');
		
			$(".formAdd input[type='submit']").click(function() {
				
				if (modded == false) {
					
					$('.formAdd :input').not('[type="submit"]').not("#formReset").removeAttr('disabled');
					$(".formAdd input[type='submit']").val('salva');
					
					modded = true;
					
					return false;
				}
				
			});
		
		});
		
	}
		
	</script>
