									<div class="alert alert-default">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
																	
									</div>	
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['squalificati']) && count($disciplinari['squalificati'])): ?>

									<table id="diffidati" class="table-matches table table-condensed table-striped table-bordered" data-giornata-id="<?=$nextDay;?>">
										<tr class="table-header">
											<th class="text-left">Societ&agrave;</th>
											<th class="text-left">Nominativo</th>
											<th class="text-center">Ammonizioni</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari['squalificati'] as $k => $diffidato): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$diffidato['IdSquadra'];?>">
											<td class="text-left"><?=$diffidato['Squadra'];?></td>
											<td class="text-left"><?=$diffidato['Anagrafica'];?></td>
											<td class="text-center"><?=$diffidato['Periodo'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
																			</table>

										<? if($c_sq == 0): ?>
											<div class="alert alert-warning">
											Nessuno squalificato in questa giornata, prossimo aggiornamento squalificati <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
											</div>										
										
										<? endif; ?>										
										
					
									<? else: ?>
											<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessuno squalificato in questa giornata, prossimo aggiornamento squalificati <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessuno squalificato in questa giornata, prossimo aggiornamento squalificati <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>

									
									<? endif; ?>
									
									