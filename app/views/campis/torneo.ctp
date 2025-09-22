<?php 
$days_short = array("1" => "lun", "2" => "mar", "3" => "mer", "4" => "gio", "5" => "ven", "6" => "sab", "7" => "dom",);
?>
<div role="main" class="main">
	
	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="" ><a href="/impianti">Impianti</a></li>
						<li class="" ><a href="/impianti/<?=$campoId;?>" ><?=$r['ChampCategory']['Nome'];?></a></li>
						<li class="active"><?=$r['Campionati']['Nome'];?></li>
						
					</ul>
				</div>
			</div>
		</div>
	</div>

	<?php //debug($fasi); ?>

<h1 class="text-center"><?=$r['Campionati']['Nome'];?></h1>

<?php if(!empty($fasi)): ?>
<div id="tournament" class="torneo-tennis" style="width: <?=(($maxFasi+1) * 220)?>px; margin: 0px auto 40px;">
<?php for($k = 1; $k <= $maxFasi; $k++): 
?>
	<ul class="round round-<?=$k?>">
		<li class="spacer">&nbsp;</li>
	<?php foreach($fasi[$k]['Matches'] as $r): ?>
		<?php
		// debug($fase);
		$getSquadra = function($r, $idx) use ($squadreList) {
			if(empty($r)) 
				return '';
			return !empty($squadreList[$r[$idx]['SquadraCampionato']]) ? $squadreList[$r[$idx]['SquadraCampionato']] : ($r['Match']['hasBye'] ? '<i>bye</i>' : '');
		};
		$squadra1 = $getSquadra($r, 'Casa');
		$squadra2 = $getSquadra($r, 'Trasferta');

		$editable = empty($r['Match']['Calendario']) || empty($r['Match']['Data']) ||
					(empty($r['MatchgoalOriginal'][0]['Goal']) && empty($r['MatchgoalOriginal'][1]['Goal']));

		// Formatta la data anteponendo il giorno della settimana
		if(!empty($r['Match']['Data']))
		{
			$giorno = (new \DateTime())->createFromFormat('Y-m-d', $r['Match']['Data'])->format('N');
			// debug($giorno);
			$r['Match']['DataOra_it'] = $days_short[$giorno] .' '. $r['Match']['DataOra_it'];
		}
			
		?>
		
		<?php 
		// Deve ancora essere giocata
		if($editable && !empty($r['Match']['Calendario']) && empty($r['Match']['hasBye'])): 
		?>

			<li class="game game-top "><span><?=$squadra1?></span> </li>
			<li class="game game-spacer">
				<p class="data-partita"><?=!empty($r['Match']['Data']) ? $r['Match']['DataOra_it'] : ''?>&nbsp;</p>
				<p class="punti-set"><?=!empty($r['Match']['PuntiSet']) ? $r['Match']['PuntiSet'] : ''?>&nbsp;</p>
			</li>
			<li class="game game-bottom "><span><?=$squadra2?></span> </li>
			<li class="spacer">&nbsp;</li>
		<?php 
		// Bye - passa il turno automaticamente, serve solo la data
		// FIXME, assicurarsi che il buy non sia il primo delle due squadre!
		elseif(!empty($r['Match']['hasBye'])): 
			$buyWin = 1; $buyLose = 0;
		?>
			<li class="game game-top winner"><span><?=$squadra1?></span> <b><?=$buyWin?></b></li>
			<li class="game game-spacer">
				<p class="data-partita"><?=!empty($r['Match']['Data']) ? $r['Match']['DataOra_it'] : ''?>&nbsp;</p>
				<p class="punti-set"><?=!empty($r['Match']['PuntiSet']) ? $r['Match']['PuntiSet'] : ''?>&nbsp;</p>
			</li>
			<li class="game game-bottom"><span><?=$squadra2?></span> <b><?=$buyLose?></b></li>

			<li class="spacer">&nbsp;</li>
		<?php 
		// Già giocata
		elseif(!empty($r['Match']['Calendario'])): 
		?>
			<li class="game game-top <?=$r['Matchgoal'][$r['Casa']['SquadraCampionato']]['is_vincitore'] ? 'winner' : 'loser'?>"><span><?=$squadra1?></span> <b><?=$r['Matchgoal'][$r['Casa']['SquadraCampionato']]['Goal']?></b></li>
			<li class="game game-spacer">
				<p class="data-partita"><?=!empty($r['Match']['Data']) ? $r['Match']['DataOra_it'] : ''?>&nbsp;</p>
				<p class="punti-set"><?=!empty($r['Match']['PuntiSet']) ? $r['Match']['PuntiSet'] : ''?>&nbsp;</p>
			</li>
			<li class="game game-bottom <?=$r['Matchgoal'][$r['Trasferta']['SquadraCampionato']]['is_vincitore'] ? 'winner' : 'loser'?>"><span><?=$squadra2?></span> <b><?=$r['Matchgoal'][$r['Trasferta']['SquadraCampionato']]['Goal']?></b></li>

			<li class="spacer">&nbsp;</li>
		<?php 
		// Fase successiva
		else: 
		?>
			<li class="game game-top"><span><?=$squadra1?></span> <b>-</b></li>
			<li class="game game-spacer">&nbsp;</li>
			<li class="game game-bottom "><span><?=$squadra2?></span> <b>-</b></li>

			<li class="spacer">&nbsp;</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endfor; ?>

	<ul class="round round-<?=$k?>">
		<li class="game game-top winner"><span><?=(!empty($vincitore) ? $vincitore['NomeSquadra'] : '')?></span></li>
	</ul>

</div>
<?php else: ?>
	<p class="text-center">Nessuna informazione disponibile</p>
<?php endif; ?>

<div class="table-container booking-table-container table-responsive">
	<table class="table-matches table-border table-striped table-condensed table" style="max-width: 600px; margin: 0 auto 40px;">
	<?php
		foreach($fasi as $fase => $matches)
		{
			if(empty($matches['Matches'][0]))
				continue;

			?>
			<tr><th colspan="99" class="text-center">Fase <?=$fase?></th></tr>
			<?php
			foreach($matches['Matches'] as $match)
			{

				if($match['Match']['Risultato'])
					$risultato = $match['Match']['Risultato'];
				elseif($match['Match']['hasBye'])
					$risultato = 'Passa il turno';
				else
					$risultato = 'Da giocare';

				echo "<tr>";
				echo "<td class='".($match['Match']['Vincitore'] == 'Casa' ? 'bold' : '')."'>".($match['Match']['CasaNome'] ? $match['Match']['CasaNome'] : '<i>bye</i>')."</td>";
				echo "<td class='".($match['Match']['Vincitore'] == 'Trasferta' ? 'bold' : '')."'>".($match['Match']['TrasfertaNome'] ? $match['Match']['TrasfertaNome'] : '<i>bye</i>')."</td>";
				echo "<td>".$match['Match']['Data']."</td>";
				echo "<td>".$risultato."</td>";
				echo "</tr>";
			} #eo fe
		} #eo fe
	?>
	</table>
</div>

	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->