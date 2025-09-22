									<div class="alert alert-default">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
																	
									</div>	
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['diffidati']) && count($disciplinari['diffidati'])): ?>

									<table id="diffidati" class="table-matches table-condensed table-striped table table-bordered" data-giornata-id="<?=@$nextDay;?>">
										<thead>
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th class="text-center">Ammonizioni</th>
										</tr>
										</thead>
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari['diffidati'] as $k => $diffidato): ?>
										<? if ($diffidato['Anagrafica'] != 'Scardovi Dario'): ?>
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$diffidato['IdSquadra'];?>">
											<td class="text-left"><?=$diffidato['Squadra'];?></td>
											<td class="text-left"><?=$diffidato['Anagrafica'];?></td>
											<td class="text-center"><?=$diffidato['Periodo'];?></td>
										</tr>
										<? else: ?>
										<? continue; ?>
										<? endif; ?>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
												
									</table>	
										<? if($c_sq == 0): ?>

										
											<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessun diffidato in questa giornata, prossimo aggiornamento diffidati  <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessun diffidato in questa giornata, prossimo aggiornamento diffidati  <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>
										
										<? endif; ?>										
							
					
									<? else: ?>
																		

<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessun diffidato in questa giornata, prossimo aggiornamento diffidati  <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessun diffidato in questa giornata, prossimo aggiornamento diffidati  <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>

									<? endif; ?>
									
									<? if(isset($disciplinari_campionato) && count($disciplinari_campionato)): ?>
									
									<div class="summary-discipline">
									
									
										<table class="form_table form_table_full table table-bordered table-condensed table-striped">
											<tr class="table-header">
												<th>Nominativo</th>
												<!--<th>Squadra</th>-->
												<th>Totale ammonizioni</th>
												<th>Totale espulsioni</th>
											</tr>
											<?foreach($disciplinari_campionato as $disciplinare):?>
											<tr>
												<td><?=$disciplinare['Atleta'];?></td>
												<?/*<td><?=$disciplinare['Squadra'];?></td>*/?>
												<td><?=$disciplinare['Ammonizioni'];?></td>
												<td><?=$disciplinare['Espulsioni'];?></td>
											</tr>
											<?endforeach;?>
										</table>
										
									</div>									
									
									<? endif; ?>
									
									</div><!-- close results-box -->
