<ul class="switch-table-menu">
	<li class="switch-giornata selected"><a href="javascript:;" title=""><?=$squadra['Squadre']['Denominazione'];?></a></li>
	<li class="yearbookSave"><a href="javascript:;" title="Salva cambiamenti">Salva</a></li>
</ul>

<div class="clear"></div>

<div id="results-box">
<?

	$ruoli = array(
	
		"POR" => "POR", 
		"CEN" => "CEN", 
		"LAT" => "LAT", 
		"UNI" => "UNI", 
		"PIV" => "PIV", 
		"ALL" => "ALL",
		"DIR" => "DIR",
		"DIF" => "DIF",
		"ATT" => "ATT",		
	
	);

?>
<script type="text/javascript">
$(function(){
	$('td.tools .yearbookEdit').live('click', function(){
		
		console.log('clicked');
		var obj 	 = $(this);
		var tr  	 = obj.parents('tr');
		var yearbook = tr.attr('data-id');
		var ruolo 	 = tr.find('td.td_ruolo');
		var maglia 	 = tr.find('td.td_numero_maglia');
		var reset    = tr.find('a.yearbookReset');
		
		//Edit td ruolo
		maglia.children('span').hide();
		maglia.children('input').removeClass('hidden');
		//Edit td maglia
		ruolo.children().not('select').not('td:has(select)').hide();
		ruolo.children('select').removeClass('hidden');
		
		reset.removeClass('hidden');
		
		$('.switch-table-menu li.yearbookSave').removeClass('hidden');
		
	});
	
	$('td.tools .yearbookReset').live('click', function(){
		
		console.log('clicked');
		var obj 	 = $(this);
		var tr  	 = obj.parents('tr');
		var yearbook = tr.attr('data-id');
		var ruolo 	 = tr.find('td.td_ruolo');
		var maglia 	 = tr.find('td.td_numero_maglia');
		var reset    = tr.find('a.yearbookReset');
		
		//Edit td ruolo
		maglia.children().not('input').not('td:has(select)').show();
		maglia.children('input').addClass('hidden');
		//Edit td maglia
		ruolo.children().not('select').not('td:has(select)').show();
		ruolo.children('select').addClass('hidden');
		
		reset.addClass('hidden');
		
	});
	
	$('.switch-table-menu .yearbookSave').one('click', function(){
		
		var data = new Object;
		
		$("#results-box .table-matches").find('tr:not(".table-header")').each(function(){
			
			var tr = $(this);
			var yearbook = tr.attr('data-id');
			var ruolo 	 = tr.find('td.td_ruolo');
			var maglia 	 = tr.find('td.td_numero_maglia');
			var reset    = tr.find('a.yearbookReset');		
			
			data[yearbook] = { "ruolo" : ruolo.find('select').val(), "maglia" : maglia.find('input').val() }	
			
		});
		
		$.post('/squadres/edit_yearbook', { "data": data }, function(){
			$("#team-button-edit").trigger('click');	
		});
		
	});
	
});
</script>

<table id="tableSquadraLogged" class="table-matches">

	<tr class="table-header">
		<th class="athlets-photo">Foto</th>
		<th>Cognome</th>		
		<th>Nome</th>
		<th>Tessera</th>
		<th>Ruolo</th>
		<th align="center">Goal</th>		
		<th>Numero maglia</th>
		<th>Opzioni</th>
	</tr>
	
	<? foreach ($roster as $k => $atleta): ?>
	
		<tr data-id="<?=$atleta['Yearbook']['Annuario'];?>" class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>">
			<td class="athlets-photo"><?
			
				if (!empty($atleta['Athlete']['avatar'])) {
					
					$link = $atleta['Athlete']['avatar'];
					
				} else {
				
					$link = '/img/icon_profile_default.png';
					
				}
			
			?>
			
			<img src="<?=$thumbnail->link(array('path' => $link,'w' => '50','f' => 'png'));?>" alt="<?=$atleta['Athlete']['Nome'];?> <?=$atleta['Athlete']['Cognome'];?>" />
			
			</td>
			<td><?=$atleta['Athlete']['Cognome'];?></td>
			<td><?=$atleta['Athlete']['Nome'];?></td>			
			<td><?=$atleta['Yearbook']['Tessera'];?></td>
			<td class="td_ruolo">
				<span class="td_text"><?=$atleta['Yearbook']['Ruolo'];?></span>
				<select class="ruolo_edit hidden">
					<option value=""></option>
					<? foreach($ruoli as $r): ?>
						<option value="<?=$r;?>" <? if($r == $atleta['Yearbook']['Ruolo']): ?>selected="selected"<? endif; ?>><?=$r;?></option>
					<? endforeach; ?>
				</select>
			</td>
			<td align="center" class=""><?=$atleta['stats']['Reti'];?></td>			
			<td class="td_numero_maglia">
				<span class="td_text"><?=($atleta['Yearbook']['NumeroMaglia'] != 0)? $atleta['Yearbook']['NumeroMaglia']:'';?></span>
				<input type="text" maxlength="3" class="maglia_edit hidden" value="<?=($atleta['Yearbook']['NumeroMaglia'] != 0)? $atleta['Yearbook']['NumeroMaglia']:'';?>" />
			</td>
			<td class="tools">
				<a rel="timmytip" title="Modifica ruolo, numero di <?=$atleta['Athlete']['Cognome'];?> <?=$atleta['Athlete']['Nome'];?>" href="javascript:;" class="yearbookEdit"><img src="/img/timmyshare/icon_edit.png"></a>
				<a href="javascript:;" class="yearbookReset hidden"><img src="/img/timmyshare/icon-filter-delete-th.png"></a>												
			</td>
			
			
		</tr>
	
	<? endforeach; ?>
	
</table>
	

</div>
