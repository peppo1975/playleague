<?

$data = $this->Session->read('Login.data');

if($data['is_arbitro'])   $type = 'Athlete';
elseif($data['is_user'])  $type = 'User';

$id = $data['id'];

$mesi = array(

	'01' => 'Gennaio',
	'02' => 'Febbraio',
	'03' => 'Marzo',
	'04' => 'Aprile',
	'05' => 'Maggio',
	'06' => 'Giugno',
	'07' => 'Luglio',
	'08' => 'Agosto',
	'09' => 'Settembre',
	'10' => 'Ottobre',
	'11' => 'Novembre',
	'12' => 'Dicembre',

);

$mesi_short = array(

	'01' => 'Gen',
	'02' => 'Feb',
	'03' => 'Mar',
	'04' => 'Apr',
	'05' => 'Mag',
	'06' => 'Giu',
	'07' => 'Lug',
	'08' => 'Ago',
	'09' => 'Set',
	'10' => 'Ott',
	'11' => 'Nov',
	'12' => 'Dic',

);

$mesi_short = $mesi;

$options = array(

	0 => 'Nessun voto',
	1 => 'Gravemente insufficiente',
	2 => 'Insufficiente',
	3 => 'Appena sufficiente',
	4 => 'Sufficiente',
	5 => 'Discreto',
	6 => 'Buono',
	7 => 'Ottimo',

);

$end_days = array(
	
	'01' => '31',
	'02' => '29',
	'03' => '31',
	'04' => '30',
	'05' => '31',
	'06' => '30',
	'07' => '31',
	'08' => '31',
	'09' => '30',
	'10' => '31',
	'11' => '30',
	'12' => '31',	

);

?>

<?

end($sfide_mensili);
$first = key($sfide_mensili);

?>

<script type="text/javascript">

$(function(){
	
	var old_hash = location.hash.replace('#','');
	
	if(old_hash == "" || isNaN(old_hash))
		location.hash = '<?=$first;?>';
	
	$('.vote').die('click').live('click', function(){
	
		var obj     = $(this);
		var type    = obj.attr('data-type');
		var athlete = obj.attr('data-id');
		var allow   = obj.parents('tr').attr('vote-allow');
		var match   = obj.parents('tr').attr('data-id');
		
		location.href = '/mobile/vote_index/' + match + '/' + athlete + '/'+ location.hash.replace('#','');
		
		return false;
		
	});	
	
	$('.switch-giornata').bind('click', function(){
		
		location.hash = $(this).attr('data-giornata-id');
		
		var me = $(this);
		
		$('.switch-giornata').removeClass('selected');
		me.addClass('selected');
		
		var data_id = me.attr('data-giornata-id');
		
		$('.table-matches').addClass('hidden');
		$('.table-matches[data-giornata-id='+data_id+']').removeClass('hidden');
		
	});
					
});

$(document).ready(function(){
	
	var loc_hash = location.hash.replace('#','');
	
	$('select[name="giornata_id"]').val(loc_hash);
	$('select[name="giornata_id"]').trigger('change');	
	
	$('.switch-giornata[data-giornata-id="' + loc_hash + '"]').trigger('click');
	
	
});

</script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBzSQwMS0NzVkgfFZeyUW9cOjbTDwUMjHU"></script>


<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			<a data-ajax="false" href="/mobile/reserved" title="Gestione profilo">
				Gestione profilo
			</a>
			&rsaquo;
		</li>
		<li>
			Votazioni
		</li>
		
	</ul>
	
</div>

			<? if(count($sfide_mensili)): ?>

			<script type="text/javascript">
				
				$(function(){
				
					$('select[name="giornata_id"]').die('change').live('change', function(){
					
						var me      = $(this);
						var buttons = $('.buttons-container');
						
						buttons.find('ul').find('li[data-giornata-id='+me.val()+']').trigger('click');
					
					});
				
				});
			
			</script>
		
			
			<select name="giornata_id">
		
				<? foreach($sfide_mensili as $mese => $matches): ?>

				<option <?if($mese == $first): ?>selected="selected"<?endif;?> value="<?=$mese;?>"><?=$mesi_short[$mese];?></option>
				
				<? endforeach; ?>
			
			</select>			
			
			<div data-role="navbar" class="buttons-container" style="display: none;">	
			
			<ul class="switch-table-menu">
			
			<?
			
			end($sfide_mensili);
			$first = key($sfide_mensili);			
			
			?>

				<? foreach($sfide_mensili as $mese => $matches): ?>

				<li class="switch-giornata <?if($mese == $first): ?>selected<?endif;?>" data-giornata-id="<?=$mese;?>"><a href="javascript:;" title="<?=$mesi[$mese];?>"><?=$mesi_short[$mese];?></a></li>
				
				<? endforeach; ?>

			</ul>	
			
			</div>
			
			<? endif; ?>

<div class="reserved-area">

			<div class="container-table-profile">
			
			<? if(count($sfide_mensili)): ?>
						
			<div class="clear"></div>
			
			<div id="results-box">

			<? foreach($sfide_mensili as $k => $matches): ?>

				<? foreach ($matches as $k => $match): ?>
				
				<?
				
					if(!empty($match['Match']['Risultato'])) { $vote_allow = 1; }
					else 									 { $vote_allow = 0; }									
				
				?>									

				<div data-role="popup" id="matches-<?=$match['Match']['Calendario'];?>">
						
				<table>
				
					<tr>
						<th>Giorno</th>
						<td><span class="number"><?=$match['Match']['Data_it'];?></span></td>
					</tr>			
					<tr>
						<th>Ora</th>	
						<td><span class="number"><?=$match['Match']['Ora'];?></span></td>													
					</tr>			
					<tr>
						<th>Impianto</th>
						<td><?=$match['Campi']['Descrizione'];?></td>
					</tr>			
					<tr>
						<th>Partita</th>
						<td><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></td>							
					</tr>			
					<tr>
						<th>Ris.</th>	
						<td><span class="number"><?=$match['Match']['Risultato'];?></span></td>						
					</tr>			
					<tr>
						<th>Note</th>
						<td><?=$match['Causalresult']['Descrizione'];?></td>							
					</tr>			
					<tr>
						<th>Gara</th>	
						<td><?=$match['Match']['NomeGara'];?></td>						
					</tr>			
					<tr data-id="<?=$match['Match']['Calendario'];?>">
						<th>Arbitro</th>				
						<?
						
							$giaVotato = $this->requestAction('/mobile/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);
							
							if(is_array($giaVotato) && count($giaVotato)) $title = $options[$giaVotato['LdaVote']['ranking']];
							else 										  $title = 'Arbitro';
						
						?>
						<td>
							<? if($vote_allow && $match['Lda']['Arbitro'] != $match['Lda']['Delegato']): ?>
							
							<?if(!$giaVotato):?>
							
							<? if($match['Match']['NomeArbitro'] != ''): ?>
							
							<a class="not-rate vote" href="javascript:;" data-type="arbitro" data-id="<?=$match['Lda']['Arbitro'];?>" title="<?=$title;?>">Vota l'arbitro</a>
							<? else: ?>
							
							&nbsp;
							
							<? endif; ?>
							
							<?else:?>
							
							<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$title;?></span>
							
							<?endif;?> 
								
							</a>
							<? else: ?>
							
							Arbitro
							
							<? endif; ?>
						</td>							
					</tr>			
					<tr data-id="<?=$match['Match']['Calendario'];?>">
						<th>Delegato</th>	
						<?
						
							if(!empty($match['Lda']['DelegatoA'])) {
								$match['Match']['NomeDelegato'] = $match['Match']['NomeDelegatoA'];
								$match['Lda']['Delegato'] = $match['Lda']['DelegatoA'];
							} 				
						
							$giaVotato = $this->requestAction('/mobile/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
							if(is_array($giaVotato) && count($giaVotato)) $title = $options[$giaVotato['LdaVote']['ranking']];
							else 										  $title = 'Delegato';							
						
						?>	
						<td>
						
							<? if($vote_allow && $match['Lda']['Arbitro'] != $match['Lda']['Delegato']): ?>
							
							<?if(!$giaVotato):?>
							
							<? if($match['Match']['NomeDelegato'] != ''): ?>
							
							<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['Delegato'];?>" title="<?=$title;?>">Vota il delegato</a>
							
							<? else: ?>
							
							&nbsp;
							
							<? endif; ?>
							
							<?else:?>
							
							<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$title;?></span>
							
							<? endif; ?>
								
							<? else: ?>
							Delegato
							<? endif; ?>																		
						</td>						
					</tr>																																											
				
				</table>
				
				</div>				
				
				<? endforeach; ?>
							
			<? endforeach; ?>
			
			<? foreach($sfide_mensili as $k => $matches): ?>
						
			<table class="table-matches <?if($k != $first): ?>hidden<?endif;?>" data-giornata-id="<?=$k;?>">	
			
			<tr class="table-header">
				<th>Giorno</th>
				<th>Partita</th>
			</tr>
			
			<? $j = 0; ?>
			
			<? foreach ($matches as $k => $match): ?>
			
			<?
			
				if(!empty($match['Match']['Risultato'])) { $vote_allow = 1; }
				else 									 { $vote_allow = 0; }									
			
			?>					
			
			<tr class="<?=(($j+1) % 2 == 0)? 'alternate' : '';?>" data-casa-squadra-id="<?=$match['Casa']['Squadra'];?>" data-trasferta-squadra-id="<?=$match['Trasferta']['Squadra'];?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>" vote-allow="<?=$vote_allow;?>" data-id="<?=$match['Match']['Calendario'];?>">
				<td><a data-transition="pop" data-rel="popup" href="#matches-<?=$match['Match']['Calendario'];?>"><?=$match['Match']['Data_it'];?></a></td>
				<td><a data-transition="pop" data-rel="popup" href="#matches-<?=$match['Match']['Calendario'];?>"><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></a></td>

				<? /*
				<td><span class="number"><?=$match['Match']['Risultato'];?></span></td>
				*/ ?>
												
			
			</tr>
			
			<? $j++; ?>
			
			<? endforeach; ?>
			
			</table>
			
			<? endforeach; ?>	
			
			</div>	
			
			<? else: ?>
			
			Nessuna gara arbitrata nella stagione corrente.
			
			<? endif; ?>	
	
			</div>
			<div class="clear"></div>

</div><!-- close wrapper-box -->