									<p class="disciplinare-info">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
									</p>		
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['squalificati']) && count($disciplinari['squalificati'])): ?>

									<table id="diffidati" class="table-matches" data-giornata-id="<?=$nextDay;?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th>Ammonizioni</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari['squalificati'] as $k => $diffidato): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$diffidato['IdSquadra'];?>">
											<td><?=$diffidato['Squadra'];?></td>
											<td><?=$diffidato['Anagrafica'];?></td>
											<td><?=$diffidato['Periodo'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
										
										<? if($c_sq == 0): ?>
										
										<tr>
											<td colspan="3">
											Nessuno squalificato, prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
											</td>
											</tr>										
										
										<? endif; ?>										
										
									</table>
					
									<? else: ?>
											
									<table class="table-matches">
									<tr>
									<td>prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>,Nessuno squalificato
									</td>
									</tr>
								
									</table>
									
									<? endif; ?>
									
									