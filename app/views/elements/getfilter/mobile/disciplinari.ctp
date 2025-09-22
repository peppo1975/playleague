									<p class="disciplinare-info">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
									</p>									

									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<table class="table-matches" data-giornata-id="<?=$nextDay;?>">
										
										<? if(count($disciplinari)): ?>
										
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Descrizione</th>
											<th>Punti</th>
											<th>Sanzione</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari as $k => $disciplinare): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$disciplinare['SquadreCampionati']['SquadraCampionato'];?>">
											<td><?=$disciplinare['Disciplinari']['NomeSquadra'];?></td>
											<td><?=$disciplinare['Disciplinari']['Descrizione'];?></td>
											<td><?=$disciplinare['Disciplinari']['Punti'];?></td>
											<td><?=$disciplinare['Disciplinari']['Sanzione'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
									
										<? else: ?>
										
											<tr>
												<td colspan="4">
												prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>,Nessuna disciplinare
												</td>
												</tr>									
										
										<? endif; ?>
										
									</table>
									
									</div><!-- close results-box -->
