<script type="text/javascript">

	$(document).bind('pageinit',function() {
	
		$('select[name="campo_id"]').change(function(){
		
			var me = $(this);
			
			if(me.val() == "") 
				{
					return false;
				}
			
			$.get('/mobile/getCampo/' + me.val(), function(data){
			
				$('.campo-filter').html(data).trigger('create');
			
			},'html');
		
		});
		
		<? if($id_impianto != null): ?>
		
		$('select[name="campo_id"]').val(<?=$id_impianto;?>);
		$('select[name="campo_id"]').trigger('change');
		
		<? endif; ?>
	
	});
	
</script>

<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			Impianti sportivi
		</li>
		
	</ul>
	
</div>

<form id="filter-form" autocomplete="off">

	<div class="input campo-input">

	<select name="campo_id">
	
			<option value="">Seleziona impianto sportivo</option>
		
			<? foreach($campi as $c): ?>
			
				<option value="<?=$c['Campi']['Campo'];?>">
					<?=$c['Campi']['Descrizione'];?> <? if($c['Campi']['countHour'] > 0): ?> - prenotabile <? endif; ?>
				</option>
			
			<? endforeach; ?>
		
	</select>
	
	</div>

</form>

<div class="campo-filter">

</div>