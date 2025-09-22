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
	3 => 'Non sufficiente',
	4 => 'Quasi sufficiente',
	5 => 'Sufficiente',
	6 => 'Discreto',
	7 => 'Buono',
	8 => 'Molto buono',
	9 => 'Ottimo'

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

function arbitroCount($id_calendario) {
	
		App::Import('Model', 'Match');
		$Match = new Match;
		
		$data_oggi = $Match->find('first', array(
		
			'fields' => array(
			
				'Match.Calendario',
				'Match.Giornata',
				'Match.Campionato',
				'Match.NomeArbitro',
				'Lda.Arbitro',
				'Match.Casa',
				'Match.Trasferta'
			
			),
			'conditions' => array(
			
				'Match.Calendario' => $id_calendario
			
			),
			'recursive' => 0
		
		));
		
		//debug('Arbitro: ' . $data_oggi['Match']['NomeArbitro']);
		//debug('ID Casa: ' . $data_oggi['Match']['Casa']);
		//debug('ID Tras: ' . $data_oggi['Match']['Trasferta']);
		
		$data_prec = $Match->find('all', array(
		
			'fields' => array(
			
				'Match.Calendario',
				'Match.Giornata',
				'Lda.Arbitro',
				'Match.Casa',
				'Match.NomeArbitro',
				'Match.Trasferta'				
			
			),
			'conditions' => array(
			
				'Lda.Arbitro' => $data_oggi['Lda']['Arbitro'],
				'Match.Campionato' => $data_oggi['Match']['Campionato'],
				'AND' => array(
				
					array('Match.Giornata <' => $data_oggi['Match']['Giornata']),
					array('Match.Giornata >=' => $data_oggi['Match']['Giornata'] - 2),
				
				),
				'OR' => array(
				
					'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
					'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
				
				)
			
			),
			'recursive' => 0
		
		));
		
		$data_prec_total = $Match->find('all', array(
		
			'fields' => array(
			
				'Match.Calendario',
				'Match.Giornata',
				'Lda.Arbitro',
				'Match.Casa',
				'Match.NomeArbitro',
				'Match.Trasferta'				
			
			),
			'conditions' => array(
			
				'Lda.Arbitro' => $data_oggi['Lda']['Arbitro'],
				'Match.Giornata <' => $data_oggi['Match']['Giornata'],
				'Match.Campionato' => $data_oggi['Match']['Campionato'],
				'OR' => array(
				
					'Match.Casa' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
					'Match.Trasferta' => array($data_oggi['Match']['Casa'], $data_oggi['Match']['Trasferta']),
				
				)
			
			),
			'recursive' => 0
		
		));		
		
		//debug($data_prec);
		
		if(!empty($data_prec) && $data_oggi['Lda']['Arbitro'] > 0) {
		
			//debug('Numero volte: ' . count($data_prec));
			
			if (count($data_prec) + 1 >= 2) $class = "arbitro-yellow";
			if (count($data_prec) + 1 >= 3) $class = "arbitro-red";
			
			if(isset($data_prec_total) && !empty($data_prec_total)) {
				if(count($data_prec_total) + 1 >= 7) $class = "arbitro-black";
			}
			
			return $class;		
			
		} else {
			
			return '';
			
		}
	
}

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

<div class="reserved-area">

			<div class="container-table-profile">
			
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
				
						<?
						
						$lda = array($match['Lda']['Arbitro'], $match['Lda']['Delegato'], $match['Lda']['DelegatoA']);
						@list($cognome, $nome) = explode(' ', trim($match['Match']['NomeArbitro']));
						if($match['Match']['NomeArbitro'] != '') $arbitro = $nome . ' ' . $cognome[0] . '.';
						else									 $arbitro = ''; 
						@list($cognome, $nome) = explode(' ', $match['Match']['NomeDelegato']);
						if($match['Match']['NomeDelegato'] != '') $delegato = $nome . ' ' . $cognome[0] . '.';
						else $delegato = '';
						@list($cognome, $nome) = explode(' ', $match['Match']['NomeDelegatoA']);
						if($match['Match']['NomeDelegatoA'] != '') $delegatoA = $nome . ' ' . $cognome[0] . '.'; 	
						else $delegatoA = '';	
						@list($cognome, $nome) = explode(' ', $match['Match']['NomeArbitro2']);
						if($match['Match']['NomeArbitro2'] != '') $arbitro2 = $nome . ' ' . $cognome[0] . '.'; 	
						else $arbitro2 = '';							 				
						
						?>
						
						<? if(in_array($this->Session->read('Login.data.id'), $lda)): ?>
						
						<?
						
							$giaVotato = $this->requestAction('/mobile/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);
							
							if(is_array($giaVotato) && count($giaVotato)) $title = $options[$giaVotato['LdaVote']['ranking']];
							else 										  $title = $arbitro;
						
						?>
						<tr data-id="<?=$match['Match']['Calendario'];?>">
						<td>Arbitro</td>
						<td class="<?=arbitroCount($match['Match']['Calendario']);?>">
						
							<? if($match['Lda']['Arbitro'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
							
							<?if(!$giaVotato):?>
							
							<? if($match['Match']['NomeArbitro'] != ''): ?>
							
							<?=$arbitro;?>
							
								<? if(!$dont_allow): ?> 
									(<a class="not-rate vote" href="javascript:;" data-type="arbitro" data-id="<?=$match['Lda']['Arbitro'];?>" title="<?=$title;?>">Vota arbitro</a>)
								<? endif; ?>
							
							<? else: ?>
							
							&nbsp;
							
							<? endif; ?>
							
							<?else:?>
							
							<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$arbitro;?> - <?=$title;?></span>
							
							<?endif;?> 
								
							</a>
						<? else: ?>
							<?=$arbitro;?>
						<? endif; ?>
						</td>
						</tr>
						<?
						
							$giaVotato = $this->requestAction('/mobile/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
							if(is_array($giaVotato) && count($giaVotato)) $title = $options[$giaVotato['LdaVote']['ranking']];
							else 										  $title = $delegato;							
						
						?>	
						<tr data-id="<?=$match['Match']['Calendario'];?>">
						<td data-id="<?=$match['Match']['Calendario'];?>">Delegato</td>						
						<td>
							<? if($match['Lda']['Delegato'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
							
							<?if(!$giaVotato):?>
							
							<? if($match['Match']['NomeDelegato'] != ''): ?>
							
							<?=$delegato;?>
							
								<? if(!$dont_allow): ?>
								(<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['Delegato'];?>" title="<?=$title;?>">Vota delegato</a>)
								<? endif; ?>
							
							<? else: ?>
							
							&nbsp;
							
							<? endif; ?>
							
							<?else:?>
							
							<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$delegato;?> - <?=$title;?></span>
							
							<? endif; ?>
								
							<? else: ?>
							<?=$delegato;?>
							<? endif; ?>																		
						</td>
						</tr>
						<?
						
							$giaVotato = $this->requestAction('/mobile/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['DelegatoA'] . '/' . $match['Match']['Calendario']);
							if(is_array($giaVotato) && count($giaVotato)) $title = $options[$giaVotato['LdaVote']['ranking']];
							else 										  $title = $delegatoA;							
						
						?>	
						<tr data-id="<?=$match['Match']['Calendario'];?>">
						<td>Delegato A.</td>										
						<td>
							<? if($match['Lda']['DelegatoA'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
							
							<?if(!$giaVotato):?>
							
							<? if($match['Match']['NomeDelegatoA'] != ''): ?>
							
							<?=$delegatoA;?>
							
								<? if(!$dont_allow): ?>
								(<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['DelegatoA'];?>" title="<?=$title;?>">Vota delegato arbitro</a>)
								<? endif; ?>
							
							<? else: ?>
							
							&nbsp;
							
							<? endif; ?>
							
							<?else:?>
							
							<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$delegatoA;?> - <?=$title;?></span>
							
							<? endif; ?>
								
							<? else: ?>
							<?=$delegatoA;?>
							<? endif; ?>																		
						</td>
						</tr>
						
						<? else: ?>
						
						<tr>
							<td>Arbitro</td>
							<td><?=$match['Match']['NomeArbitro'];?></td>
						</tr>
						
						<tr>
							<td>Delegato</td>						
							<td><?=$match['Match']['NomeDelegato'];?></td>
						</tr>
						
						<tr>
							<td>Delegato A</td>						
							<td><?=$match['Match']['NomeDelegatoA'];?></td>
						</tr>
						
						<? endif; ?>
						
						<tr>
						<td>Arbitro 2</td>						
						<td class="<?=arbitroCount($match['Match']['CountArbitro2']);?>">
							<?=$arbitro2;?>
						</td>	
						</tr>						
						
					</table>						
				
				</div>
				
				<? endforeach; ?>
				
			<? endforeach; ?>
						
			
			<? foreach($sfide_mensili as $k => $matches): ?>
						
			<table class="table-matches <?if($k != $first): ?>hidden<?endif;?>" data-giornata-id="<?=$k;?>">	
			
			<tr class="table-header">
				<th>Data</th>
				<th>Partita</th>
			</tr>
			
			<? $j = 0; ?>
			
			<? foreach ($matches as $k => $match): ?>
			
			<?
			
				if(!empty($match['Match']['Risultato'])) { $vote_allow = 1; }
				else 									 { $vote_allow = 0; }	
				
				$time_default = strtotime("-120 days",time()); //Cambiare 120 giorni con 40.
				$time_match   = strtotime($match['Match']['Data']);
				
				if($time_match <= $time_default) $dont_allow = 1;
				else 							 $dont_allow = 0;
				
			?>					
			
			<tr class="<?=(($j+1) % 2 == 0)? 'alternate' : '';?>" data-casa-squadra-id="<?=$match['Casa']['Squadra'];?>" data-trasferta-squadra-id="<?=$match['Trasferta']['Squadra'];?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>" vote-allow="<?=$vote_allow;?>" data-id="<?=$match['Match']['Calendario'];?>">

				<td><a data-transition="pop" data-rel="popup" href="#matches-<?=$match['Match']['Calendario'];?>"><?=$match['Match']['Data_it'];?></a></td>
				<td><a data-transition="pop" data-rel="popup" href="#matches-<?=$match['Match']['Calendario'];?>"><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></a></td>
						
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