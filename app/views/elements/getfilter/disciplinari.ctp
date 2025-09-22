									<div class="alert alert-default">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
																	
									</div>
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
																			<? if(count($disciplinari)): ?>

									<table class="table-matches table table-condensed table-striped table-bordered" data-giornata-id="<?=$nextDay;?>">
										
										<thead>
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Descrizione</th>
											<th class="text-center">Punti</th>
											<th class="text-center">Sanzione</th>
										</tr>
										</thead>
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari as $k => $disciplinare): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$disciplinare['SquadreCampionati']['SquadraCampionato'];?>">
											<td class="text-left"><?=$disciplinare['Disciplinari']['NomeSquadra'];?></td>
											<td class="text-left"><?=$disciplinare['Disciplinari']['Descrizione'];?></td>
											<td class="text-center"><?=$disciplinare['Disciplinari']['Punti'];?></td>
											<td class="text-center"><?=$disciplinare['Disciplinari']['Sanzione'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
									
									</table>
										<? else: ?>
										
												<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessuna disciplinare, prossimo aggiornamento disciplinari <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessuna disciplinare, prossimo aggiornamento disciplinari <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>
										
										<? endif; ?>
										
									
									</div><!-- close results-box -->
