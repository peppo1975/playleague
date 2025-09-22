<?php
// debug($fasi);
$canPassTurno = false;
?>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li><a href="/gestione/impianto/index_tornei">Elenco tornei</a></li>
						<li class="active">Gestisci partite torneo</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">
  	<div class="row">
	
		<h2 class="">Gestisci partite torneo</h2>
		<p class="alert alert-info">NB: Ricorda di salvare la data e i punteggi di ogni partita <u>singolarmente</u></p>

		<div id="tournament" class="torneo-tennis">
		<?php for($k = 1; $k <= $maxFasi; $k++): 
			if($k == $faseAttuale && $fasi[$k]['DaGiocare'] == 0)
				$canPassTurno = true;
		?>
			<ul class="round round-<?=$k?>">
				<li class="spacer">&nbsp;</li>
			<?php foreach($fasi[$k]['Matches'] as $r): ?>
				<?php
				// debug($r);
				$getSquadra = function($r, $idx) use ($squadreList) {
					if(empty($r)) 
						return '';
					return !empty($squadreList[$r[$idx]['SquadraCampionato']]) ? $squadreList[$r[$idx]['SquadraCampionato']] : 'Bye';
				};
				$squadra1 = $getSquadra($r, 'Casa');
				$squadra2 = $getSquadra($r, 'Trasferta');

				$isComplete = $r['Match']['Casa'] != 0 && $r['Match']['Trasferta'] != 0;

				$editable = $isComplete && !$r['Match']['hasBye'] &&(empty($r['Match']['Calendario']) || empty($r['Match']['Data']) ||
					!isset($r['MatchgoalOriginal'][0]['Goal']) || !isset($r['MatchgoalOriginal'][0]['Goal']));
				?>
				
				<?php 
				// Deve ancora essere giocata
				if($editable && !empty($r['Match']['Calendario']) && empty($r['Match']['hasBye'])): 
				?>

					<li class="game game-top "><span><?=$squadra1?></span> </li>
					<li class="game game-spacer">
					<?=$this->Form->create('Match', ['url' => '/gestione/impianto/save_match/'.$r['Match']['Calendario']]); ?>
					<?=$this->Form->input('Data' ,array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'gg/mm/aaaa', 'class' => 'input-sm form-control data-partita', 'value' => !empty($r['Match']['Data']) ? $r['Match']['Data_it'] : ''));?> 
					<?=$this->Form->input('Ora' ,array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'oo.mm', 'class' => 'input-sm form-control ora-partita', 'value' => !empty($r['Match']['Ora']) ? $r['Match']['Ora'] : ''));?> 
					<?=$this->Form->input('PuntiSet' ,array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'Punti set (o ND)', 'class' => 'input-sm form-control punti-set', 'value' => !empty($r['Match']['PuntiSet']) ? $r['Match']['PuntiSet'] : ''));?> 
					<?=$this->Form->input('punteggio_casa' ,array('label' => false, 'div' => false, 'placeholder' => '-', 'class' => 'form-control punti punti-top'));?>
					<?=$this->Form->input('punteggio_trasferta' ,array('label' => false, 'div' => false, 'placeholder' => '-', 'class' => 'form-control punti punti-bottom'));?>
					<?=$this->Form->submit('&#x2713;' ,array('type' => 'submit', 'div' => false, 'escape' => false, 'class' => 'btn btn-xs btn-info'));?>
					<?=$this->Form->end();?>
					</li>
					<li class="game game-bottom "><span><?=$squadra2?></span> </li>

					<li class="spacer">&nbsp;</li>
				<?php 
				// Bye - passa il turno automaticamente, serve solo la data
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
				elseif($isComplete && !empty($r['Match']['Calendario'])): 
				?>
					<li class="game game-top <?=$r['Matchgoal'][$r['Casa']['SquadraCampionato']]['is_vincitore'] ? 'winner' : ''?>"><span><?=$squadra1?></span> <b><?=$r['Matchgoal'][$r['Casa']['SquadraCampionato']]['Goal']?></b></li>
					<li class="game game-spacer">
						<p class="data-partita"><?=!empty($r['Match']['Data']) ? $r['Match']['DataOra_it'] : ''?>&nbsp;</p>
						<p class="punti-set"><?=!empty($r['Match']['PuntiSet']) ? $r['Match']['PuntiSet'] : ''?>&nbsp;</p>
					</li>
					<li class="game game-bottom <?=$r['Matchgoal'][$r['Trasferta']['SquadraCampionato']]['is_vincitore'] ? 'winner' : ''?>"><span><?=$squadra2?></span> <b><?=$r['Matchgoal'][$r['Trasferta']['SquadraCampionato']]['Goal']?></b></li>

					<li class="spacer">&nbsp;</li>
				<?php 
				// Fase successiva
				else: 
				?>
					<li class="game game-top"><span><?=($r['Match']['Casa'] ? $squadra1 : '-')?></span> <b>-</b></li>
					<li class="game game-spacer">&nbsp;</li>
					<li class="game game-bottom "><span><?=($r['Match']['Trasferta'] ? $squadra2 : '-')?></span> <b>-</b></li>

					<li class="spacer">&nbsp;</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		<?php endfor; ?>

		<ul class="round round-<?=$k?>">
			<li class="game game-top winner"><span><?=(!empty($vincitore) ? $vincitore['NomeSquadra'] : '')?></span></li>
		</ul>

	</div>
	</div>
	</div>
</div>