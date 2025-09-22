<?php
$sessoTipo = array(0 => 'Maschile', 1 => 'Femminile', 2 => 'Misto');
$tipoTorneoTennis = array('S' => 'Singolo', 'D' => 'Doppio');
?>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Gestione impianto</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">

  		<h1><?=$campo['Campi']['Descrizione']?></h1>
  		<p>
		<a href="/gestione/impianto/add_torneo" class="btn btn-default">
			<i class="fa fa-plus"></i> Crea nuovo torneo
		</a>
		</p>
		
		<?php if(empty($all)): ?>
			<p>Nessun torneo</p>
		<?php else: ?>
			<table class="table table-bordered">
				<tr>
					<th>Nome</th>
					<th colspan="2" style="text-align: center">Tipo</th>
					<th style="width: 90px">In corso</th>
					<th style="width: 220px">Azioni</th>
				</tr>
			<?php foreach($all as $r): ?>
				<tr>
					<td><?=$r['Campionati']['Nome'];?></td>
					<td style="width: 90px"><?=$tipoTorneoTennis[$r['Campionati']['TipoTorneoTennis']];?></td>
					<td style="width: 90px"><?=$sessoTipo[$r['Campionati']['SessoTipo']];?></td>
					<td><?=$r['Campionati']['InCorso'] ? 'SI' : 'NO';?></td>
					<td>
					<a href="/gestione/impianto/edit_torneo/<?=$r['Campionati']['Campionato'];?>">Modifica</a> | 
					<?php if(!$r['Campionati']['has_matches']): ?>
						<a href="/gestione/impianto/manage_atleti/<?=$r['Campionati']['Campionato'];?>">Gestione atleti</a>
					<?php else: ?>
						<a href="/gestione/impianto/manage_partite/<?=$r['Campionati']['Campionato'];?>/<?=$r['Half'][0]['GironeCampionato'];?>">Gestione partite</a>
					<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>


	</div>
</div>