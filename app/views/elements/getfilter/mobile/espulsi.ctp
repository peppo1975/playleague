
									<p class="disciplinare-info">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
									</p>			

									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['espulsi']) && count($disciplinari['espulsi'])): ?>

									<table id="espulsi" class="table-matches" data-giornata-id="<?=@$nextDay;?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th>Periodo</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach($disciplinari['espulsi'] as $k => $espulso): ?>								
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$espulso['IdSquadra'];?>">
											<td><?=$espulso['Squadra'];?></td>
											<td><?=$espulso['Anagrafica'];?></td>
											<td><?=$espulso['Periodo'];?></td>
								
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
																			
									<? else: ?>
									
									<table class="table-matches">
									<tr>
									<td>
prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>, Nessun espulso
									</td>
									</tr>
									</table>
									<? endif; ?>
									
									</div><!-- close results-box -->
