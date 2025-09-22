<div class="tab-container" style="height: 400px; overflow: auto;">
	
		<ul class="tab-selector">
	
			<li data-index="1" class="selected"><a href="javascript:;">Girone</a></li>
			<li data-index="2"><a href="javascript:;">Campionato</a></li>
		
		</ul>
		
		<div class="tab-page tab-selected" data-index="1">
		
			<table class="form_table form_table_full">
				<tr>
					<th>Nominativo</th>
					<th>Squadra</th>
					<th>Totale ammonizioni</th>
					<th>Totale espulsioni</th>
				</tr>
				<?foreach($disciplinari as $disciplinare):?>
				<tr>
					<td><?=$disciplinare['Atleta'];?></td>
					<td><?=$disciplinare['Squadra'];?></td>
					<td><?=$disciplinare['Ammonizioni'];?></td>
					<td><?=$disciplinare['Espulsioni'];?></td>
				</tr>
				<?endforeach;?>
			</table>
			
		</div>

		<div class="tab-page" data-index="2">
		
			<table class="form_table form_table_full">
				<tr>
					<th>Nominativo</th>
					<th>Squadra</th>
					<th>Totale ammonizioni</th>
					<th>Totale espulsioni</th>
				</tr>
				<?foreach($disciplinari_campionato as $disciplinare):?>
				<tr>
					<td><?=$disciplinare['Atleta'];?></td>
					<td><?=$disciplinare['Squadra'];?></td>
					<td><?=$disciplinare['Ammonizioni'];?></td>
					<td><?=$disciplinare['Espulsioni'];?></td>
				</tr>
				<?endforeach;?>
			</table>
			
		</div>		
		
</div>