<script type="text/javascript">

	$(document).bind('pageinit',function() {
	
		$('select[name="campionato_id"]').change(function(){
		
			var me 	   = $(this);
			var select = $('select[name="girone_id"]');	
				select.empty();
				
				select.parents('div.input').find('.ui-btn-text').find('span').text('Seleziona girone di riferimento');				
			
			if(me.val() == "") 
				{
					select.closest('.input').hide();	
					$('select[name="filter_id"]').closest('.input').hide();
					$('.filter-container').empty();
					return false;
				}
			
			$('select[name="filter_id"]').val('calendario');	
			$('select[name="filter_id"]').closest('.input').hide();				
	
			$('.filter-container').empty()
		
			$.get('/mobile/getGironiFromCampionato/' + me.val(), function(data){
			
				if(data.halfs.length > 0) {

						select.closest('.input').show();	
						select.append('<option value="" selected="selected">SELEZIONA TORNEO DI RIFERIMENTO</option>');
						
						for(i in data.halfs) {
						
							var option = $('<option>').attr('value',data.halfs[i].id).text(data.halfs[i].value);
								select.append(option);
						
						}
						
						select.val('');
				
				}
			
			},'json');
			
		});	
		
		$('select[name="girone_id"]').change(function(){
		
			var me = $(this);
			
			if(me.val() == "") 
				{
					$('select[name="filter_id"]').closest('.input').hide();
					$('.filter-container').empty();
					return false;
				}
			
			$.get('/mobile/getFilter/' + $('select[name="campionato_id"]').val() + '/' + me.val() + '/calendario', function(data){
			
				$('.filter-container').empty().append(data).trigger('create');
				$('select[name="filter_id"]').closest('.input').show();
			
			},'html');
		
		});

		$('select[name="filter_id"]').change(function(){
		
			var me = $(this);
			
			if(me.val() == "") 
				{
					return false;
				}
			
			$.get('/mobile/getFilter/' + $('select[name="campionato_id"]').val() + '/' + $('select[name="girone_id"]').val() + '/' + me.val(), function(data){
			
				$('.filter-container').empty().append(data).trigger('create');
				$('select[name="filter_id"]').closest('.input').show();
			
			},'html');
		
		});
	
		$('.switch-table-menu').find('li.switch-giornata').die('click').live('click', function(){
		
			var me = $(this);
			
			$('.switch-table-menu').find('li').removeClass('selected');
			me.addClass('selected');
			
			$('.table-matches').addClass('hidden');
			$('.table-matches').hide();
			$('.table-matches[data-giornata-id='+me.attr('data-giornata-id')+']').removeClass('hidden');
			$('.table-matches[data-giornata-id='+me.attr('data-giornata-id')+']').show();
		
		});
	
		<? if($id_campionato != null): ?>
		
		$('select[name="campionato_id"]').val(<?=$id_campionato;?>);
		$('select[name="campionato_id"]').trigger('change');
		
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
				Calendari/Classifiche/Note gara
			</li>
			
		</ul>
		
	</div>

<form id="filter-form" autocomplete="off">

	<div class="input champ-input">

	<select name="campionato_id">
	
			<option value="">Seleziona torneo di riferimento</option>
		
			<? foreach($campionati as $c): ?>
			
				<option value="<?=$c['Campionati']['Campionato'];?>"><?=$c['Campionati']['Nome'];?></option>
			
			<? endforeach; ?>
		
	</select>
	
	</div>

	<div class="input half-input" style="display: none;">

	<select name="girone_id">
	
		<option value="">Seleziona girone di riferimento</option>
	
	</select>
	
	</div>

	<div class="input filter-input" style="display: none;">

	<select name="filter_id">
	
		<option value="calendario">Calendario</option>
		<option value="classifica">Classifica</option>
		<option value="marcatori">Marcatori</option>
		<option value="diffidati">Diffidati</option>
		<option value="espulsi">Espulsi</option>
		<option value="squalificati">Squalificati</option>
		<option value="disciplinari">Sanzioni</option>
		<option value="comunicazioni">Comunicazioni</option>
	
	</select>
	
	</div>

</form>

<div class="filter-container"></div>