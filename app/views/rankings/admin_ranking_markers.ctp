<div style="height: 400px; overflow: auto;">
	<div class="tab-container">
	
		<ul class="tab-selector">
	
			<li data-index="1" class="selected"><a href="javascript:;">Girone</a></li>
			<li data-index="2"><a href="javascript:;">Campionato</a></li>
		
		</ul>
		
		<div class="tab-page tab-selected" data-index="1">
		
			<table class="form_table form_table_full">
				<tr>
					<th>Goal</th>
					<th>Atleta (Squadra)</th>
				</tr>
				<?foreach($girone as $g): ?>
				<tr>
					<td><?=$g[0]['goals'];?></td>
					<td><?=$g[0]['anagrafica'] . ' ( ' . $g[0]['NomeSquadra'] . ' ) ';?></td>
				</tr>
				<?endforeach;?>
			</table>		
		
		</div>
		<div class="tab-page" data-index="2">
		
			<table class="form_table form_table_full">
				<tr>
					<th>Goal</th>
					<th>Atleta (Squadra)</th>
				</tr>
				<?foreach($campionato as $c): ?>
				<tr>
					<td><?=$c[0]['goals'];?></td>
					<td><?=$c[0]['anagrafica'] . ' ( ' . $c[0]['NomeSquadra'] . ' ) ';?></td>
				</tr>
				<?endforeach;?>
			</table>		
		
		</div>
	</div>
</div>