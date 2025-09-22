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

<script type="text/javascript">

$(function(){
	
	$(".table-matches").delegate('.vote','click', function(){
	
		var obj     = $(this);
		var type    = obj.attr('data-type');
		var athlete = obj.attr('data-id');
		var allow   = obj.parents('tr').attr('vote-allow');
		var match   = obj.parents('tr').attr('data-id');

		timmy_load('/lda_votes/vote_index/' + match + '/' + athlete);
		
	});	
	
	$('.switch-giornata').bind('click', function(){
		
		location.hash = $(this).attr('data-giornata-id');
		
	});
					
});

$(document).ready(function(){
	
	var loc_hash = location.hash.replace('#','');
	$('.switch-giornata[data-giornata-id="' + loc_hash + '"]').trigger('click');
	
});

</script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBzSQwMS0NzVkgfFZeyUW9cOjbTDwUMjHU"></script>
<div class="wrapper-box">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">

			<ul class="tab-profile-menu">
				<li><a href="/gestione/profilo/<?=$this->Session->read('Login.data.id');?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
				<li class="selected"><a href="/gestione/votazioni" title="Votazioni">Votazioni</a></li>
				<li><a href="/gestione/buste" title="Buste paga">Buste paga</a></li>
			</ul>
			<h1 class="profile-name-title">Gestione profilo arbitro // <span><?=$this->Session->read('Login.data.cognome');?> <?=$this->Session->read('Login.data.nome');?></span></h1>
			<div class="clear"></div>
				<h3 class="title-profile-menu">Votazioni</h3>
			<div class="clear"></div>

			<div class="table-container container-table-profile">
			
			<? if(count($sfide_mensili)): ?>
			
			<ul class="switch-table-menu">
			
			<?
			
			$first = date("m");
			
			?>

				<? foreach($sfide_mensili as $mese => $matches): ?>

				<li class="switch-giornata <?if($mese == $first): ?>selected<?endif;?>" data-giornata-id="<?=$mese;?>"><a href="javascript:;" title="<?=$mesi[$mese];?>"><?=$mesi_short[$mese];?></a></li>
				
				<? endforeach; ?>

			</ul>	
			
			<div id="results-box">
			
			<? foreach($sfide_mensili as $k => $matches): ?>
						
			<table class="table-matches <?if($k != $first): ?>hidden<?endif;?>" data-giornata-id="<?=$k;?>">	
			
			<tr class="table-header">
				<th>Giorno</th>
				<th>Ora</th>
				<th>Impianto</th>
				<th>Partita</th>
				<th>Ris.</th>
				<th>Note</th>
				<th>Gara</th>
				<th>Arbitro</th>
				<th>Delegato</th>
				<th>&nbsp;</th>
			</tr>
			
			<? $j = 0; ?>
			
			<? foreach ($matches as $k => $match): ?>
			
			<?
			
				if(!empty($match['Match']['Risultato'])) { $vote_allow = 1; }
				else 									 { $vote_allow = 0; }									
			
			?>					
			
			<tr class="<?=(($j+1) % 2 == 0)? 'alternate' : '';?>" data-casa-squadra-id="<?=$match['Casa']['Squadra'];?>" data-trasferta-squadra-id="<?=$match['Trasferta']['Squadra'];?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>" vote-allow="<?=$vote_allow;?>" data-id="<?=$match['Match']['Calendario'];?>">
				<td><span class="number"><?=$match['Match']['Data_it'];?></span></td>
				<td><span class="number"><?=$match['Match']['Ora'];?></span></td>
				<td>

				<? if($match['Campi']['latitudine'] != '' && $match['Campi']['longitudine'] != '' && empty($match['Match']['Risultato'])): ?>
					<script type="text/javascript">
					$(function(){
						$('.open_maps').click(function(){
							$.post('/campis/saveMapsSession', {
								'Nome':'<?=$match['Campi']['Descrizione'];?>',
								'latitudine':'<?=$match['Campi']['latitudine'];?>',
								'longitudine':'<?=$match['Campi']['longitudine'];?>',
								'indirizzo':'<?=$match['Campi']['Indirizzo'];?>',
								'citta':'<?=$match['Campi']['Citta'];?>',
								'provincia':'<?=$match['Campi']['Provincia'];?>',
								'telefono':'<?=$match['Campi']['Telefono'];?>',
								'email':'<?=$match['Campi']['Email'];?>',
							}, function(){
							
							var uniqid = Math.random();
							
								timmy_load('/campis/maps?midland=' + uniqid);
							
							});
						});
						
					});
					</script>				
					<a class="open_maps" href="javascript:;" rel="timmytip" title="<?=$match['Campi']['Descrizione'];?>">
						<?=$match['Campi']['Descrizione'];?>
					</a>
				<? else: ?>
					<?=$match['Campi']['Descrizione'];?>
				<? endif; ?>
				</td>
				<td><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></td>
				<td><span class="number"><?=$match['Match']['Risultato'];?></span></td>
				<td><?=$match['Causalresult']['Descrizione'];?></td>
				<td><?=$match['Match']['NomeGara'];?></td>
				
				<?
				
				$lda = array($match['Lda']['Arbitro'], $match['Lda']['Delegato']);
				
				?>
				
				<? if(in_array($this->Session->read('Login.data.id'), $lda)): ?>
				
				<?
				
					$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);
					
					if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
					else 										  $title = $match['Match']['NomeArbitro'];
				
				?>
				<td>
					<? if($match['Lda']['Arbitro'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
					
					<?if(!$giaVotato):?>
					
					<? if($match['Match']['NomeArbitro'] != ''): ?>
					
					<?=$match['Match']['NomeArbitro'];?>
					(<a class="not-rate vote" href="javascript:;" data-type="arbitro" data-id="<?=$match['Lda']['Arbitro'];?>" title="<?=$title;?>">vota</a>)
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					
					<?else:?>
					
					<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$match['Match']['NomeArbitro'];?></span>
					
					<?endif;?> 
						
					</a>
					<? else: ?>
					<?=$match['Match']['NomeArbitro'];?>
					<? endif; ?>
				</td>
				<?
				
					$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
					if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
					else 										  $title = $match['Match']['NomeDelegato'];							
				
				?>	
				<td>
					<? if($match['Lda']['Delegato'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
					
					<?if(!$giaVotato):?>
					
					<? if($match['Match']['NomeDelegato'] != ''): ?>
					
					<?=$match['Match']['NomeDelegato'];?>
					
					(<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['Delegato'];?>" title="<?=$title;?>">vota</a>)
					
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					
					<?else:?>
					
					<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$match['Match']['NomeDelegato'];?></span>
					
					<? endif; ?>
						
					<? else: ?>
					<?=$match['Match']['NomeDelegato'];?>
					<? endif; ?>																		
				</td>
				
				<? else: ?>
				
				<td><?=$match['Match']['NomeArbitro'];?></td>
				<td><?=$match['Match']['NomeDelegato'];?></td>
				
				<? endif; ?>
				
				<td class="last-column">
			
				<? if (time() <= strtotime(substr($match['Match']['Data'],0,strlen('0000-00-00')) . " " . str_replace(".",":",$match['Match']['Ora']))): ?>

				<a href="javascript:;" class="nota-gara" data-match-id="<?=$match['Match']['Calendario'];?>" title="Stampa nota gara" rel="timmytip"><img src="/img/icon-pdf.png" width="16" height="16" alt="Stampa nota gara" /></a>
				<? endif; ?>
				
				</td>										
			
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
			</div><!-- close contents-box -->
		 </div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->