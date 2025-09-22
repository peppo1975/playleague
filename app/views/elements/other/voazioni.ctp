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

<? if(count($sfide_mensili)): ?>
			<div class="text-center">
			<ul class="switch-table-menu pagination pagination-sm">
			
			<?
			
			end($sfide_mensili);
			$first = key($sfide_mensili);
			
			?>

				<? foreach($sfide_mensili as $mese => $matches): ?>

				<li class="switch-giornata <?if($mese == $first): ?>active<?endif;?>" data-giornata-id="<?=$mese;?>"><a href="javascript:;" title="<?=$mesi[$mese];?>"><?=$mesi_short[$mese];?></a></li>
				
				<? endforeach; ?>

			</ul>	
			</div>
			<div class="clear"></div>			
			
			<div id="results-box">
			
			<? foreach($sfide_mensili as $k => $matches): ?>
						
			<table class="table table-bordered table-condensed table-striped table-matches <?if($k != $first): ?>hidden<?endif;?>" data-giornata-id="<?=$k;?>">	
			
			<thead class="table-header">
				<th class="text-center">Giorno</th>
				<th class="text-center">Ora</th>
				
				<th>Impianto</th>
				
				<th>Partita</th>
				<th class="text-center">Ris.</th>
	
	<!--			<th>Note</th>
				<th>Gara</th>
				
-->
				<th>Arbitro</th>
				<th>Delegato</th>
				<th>Delegato A.</th>
				<th>Arbitro2</th>
			</thead>
			
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
				<td class="text-center"><span class="number"><?=$match['Match']['Data_it'];?></span></td>
				<td class="text-center"><span class="number"><?=$match['Match']['Ora'];?></span></td>
			
				<td>

				<? if($match['Campi']['latitudine'] != '' && $match['Campi']['longitudine'] != '' && empty($match['Match']['Risultato'])): ?>
				
						<?=$match['Campi']['Descrizione'];?>
				<? else: ?>
					<?=$match['Campi']['Descrizione'];?>
				<? endif; ?>
				</td>
				
				<td><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></td>
				<td class="text-center"><span class="number"><?=$match['Match']['Risultato'];?></span></td>
				<!--
				<td><?=$match['Causalresult']['Descrizione'];?></td>
				<td><?=$match['Match']['NomeGara'];?></td>
				-->

				<?
				
				$lda = array($match['Lda']['Arbitro'], $match['Lda']['Delegato'], $match['Lda']['DelegatoA']);
				@list($cognome, $nome) = explode(' ', trim($match['Match']['NomeArbitro']));
				if($match['Match']['NomeArbitro'] != '') {
					$arbitro = $nome . ' ' . $cognome[0] . '.';
					$count_ex = explode(' ',($match['Match']['NomeArbitro']));
					
					if ($count_ex >= 3) {
					
						$nome = $count_ex[2];
						
						$cognome = $count_ex[0] . " " . $count_ex[1][0] . ".";
						
						$arbitro = $nome . ' ' . $cognome;
					
					}
				
				}
				else									 $arbitro = ''; 
				@list($cognome, $nome) = explode(' ', $match['Match']['NomeDelegato']);
				if($match['Match']['NomeDelegato'] != '') { 
				$delegato = $nome . ' ' . $cognome[0] . '.';
					//$arbitro = $nome . ' ' . $cognome[0] . '.';
					$count_ex = explode(' ',($match['Match']['NomeDelegato']));
					
					if ($count_ex >= 3) {
					
						$nome = $count_ex[2];
						
						$cognome = $count_ex[0] . " " . $count_ex[1][0] . ".";
						
						$delegato = $nome . ' ' . $cognome;
					
					}
				}
				else $delegato = '';
				@list($cognome, $nome) = explode(' ', $match['Match']['NomeDelegatoA']);
				if($match['Match']['NomeDelegatoA'] != '') {
					$delegatoA = $nome . ' ' . $cognome[0] . '.'; 	
						$count_ex = explode(' ',($match['Match']['NomeDelegatoA']));
					
					if ($count_ex >= 3) {
					
						$nome = $count_ex[2];
						
						$cognome = $count_ex[0] . " " . $count_ex[1][0] . ".";
						
						$delegatoA = $nome . ' ' . $cognome;
					
					}			
				}
				else $delegatoA = '';	
				@list($cognome, $nome) = explode(' ', $match['Match']['NomeArbitro2']);
				if($match['Match']['NomeArbitro2'] != '') {
					$arbitro2 = $nome . ' ' . $cognome[0] . '.'; 	
		
				$count_ex = explode(' ',($match['Match']['NomeArbitro2']));
					
					if ($count_ex >= 3) {
					
						$nome = $count_ex[2];
						
						$cognome = $count_ex[0] . " " . $count_ex[1][0] . ".";
						
						$arbitro2 = $nome . ' ' . $cognome;
					
					}	
					
				}
				else $arbitro2 = '';							 				
				
				?>
				
				<? if(in_array($this->Session->read('Login.data.id'), $lda)): ?>
				
				<?
				
					$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);
					
					if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
					else 										  $title = $arbitro;
				
				?>
				<td class="<?=arbitroCount($match['Match']['Calendario']);?>">
				
					<? if($match['Lda']['Arbitro'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
					
					<?if(!$giaVotato):?>

					<? if($match['Match']['NomeArbitro'] != ''): ?>
					
					<?=$arbitro;?>
					
						<? if(!$dont_allow): ?> 
							<a class="not-rate label label-sm label-success vote" href="javascript:;" data-type="arbitro" data-id="<?=$match['Lda']['Arbitro'];?>" title="<?=$title;?>">vota</a>
						<? endif; ?>
					
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					
					<?else:?>
					
					<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$arbitro;?></span>
					
					<?endif;?> 
						
					</a>
				<? else: ?>
					<?=$arbitro;?>
				<? endif; ?>
				
				</td>
				<?
				
					$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
					if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
					else 										  $title = $delegato;							
				
				?>	
				<td>
					<? if($match['Lda']['Delegato'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
					
					<?if(!$giaVotato):?>
					
					<? if($match['Match']['NomeDelegato'] != ''): ?>
					
					<?=$delegato;?>
					
						<? if(!$dont_allow): ?>
						(<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['Delegato'];?>" title="<?=$title;?>">vota</a>)
						<? endif; ?>
					
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					
					<?else:?>
					
					<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$delegato;?></span>
					
					<? endif; ?>
						
					<? else: ?>
					<?=$delegato;?>
					<? endif; ?>																		
				</td>
				<?
				
					$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['DelegatoA'] . '/' . $match['Match']['Calendario']);
					if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
					else 										  $title = $delegatoA;							
				
				?>					
				<td>
					<? if($match['Lda']['DelegatoA'] != $this->Session->read('Login.data.id') && $vote_allow): ?>
					
					<?if(!$giaVotato):?>
					
					<? if($match['Match']['NomeDelegatoA'] != ''): ?>
					
					<?=$delegatoA;?>
					
						<? if(!$dont_allow): ?>
						(<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?=$match['Lda']['DelegatoA'];?>" title="<?=$title;?>">vota</a>)
						<? endif; ?>
					
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					
					<?else:?>
					
					<span class="rated" title="<?=$title;?>" rel="timmytip"><?=$delegatoA;?></span>
					
					<? endif; ?>
						
					<? else: ?>
					<?=$delegatoA;?>
					<? endif; ?>																		
				</td>
				
				<? else: ?>
				
				<td><?=$match['Match']['NomeArbitro'];?></td>
				<td><?=$match['Match']['NomeDelegato'];?></td>
				<td><?=$match['Match']['NomeDelegatoA'];?></td>
				
				<? endif; ?>
				
				<td class="<?=arbitroCount($match['Match']['CountArbitro2']);?>">
					<?=$arbitro2;?>
				</td>				
			
			</tr>
			
			<? $j++; ?>
			
			<? endforeach; ?>
			
			</table>
			
			<? endforeach; ?>	
		
			<? else: ?>
			
			<div class="alert alert-warning">
			Nessuna gara arbitrata nella stagione corrente.
			</div>
			<? endif; ?>