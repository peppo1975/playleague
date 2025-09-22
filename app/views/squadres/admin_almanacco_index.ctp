<? if(!empty($campionati)): ?>

	<script type="text/javascript">
	
	$(function(){
		
		$("#printAlmanacco").delegate('.selectChamp','change', function(){
			
			if($('.selectChamp:checked').length > 0) {
				$("#printButton").attr('disabled',false);
			} else {
				$("#printButton").attr('disabled',true);
			}
			
		});
		
		$("#printAlmanacco").submit(function(){
			
			var data = $(this).serialize();
			
			$.post('/admin/squadres/almanacco', data, function(ret){
				
				location.href  = ret.link;
				
			},'json');
			
			return false;
			
		});
		
	});
	
	</script>

	<form id="printAlmanacco" action="post">

	<ul class="list-champ"> 

	<? foreach($campionati as $id_campionato => $nome_campionato): ?>
	
		<li>
			<input type="checkbox" name="data[Campionati][Campionato_<?=$id_campionato;?>]" value="<?=$id_campionato;?>" class="selectChamp" id="Campionato_<?=$id_campionato;?>" />
			<label for="Campionato_<?=$id_campionato;?>"><?=$nome_campionato;?></label>
		</li>
	
	<? endforeach; ?>
	
	</ul>
	
	<div class="clear"></div>
	
	<div class="input">
		<input type="submit" value="stampa" disabled="disabled" id="printButton" />
	</div>
	
	</form>

<? endif; ?>