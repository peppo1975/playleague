									<div class="alert alert-default">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
																	
									</div>			

									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['espulsi']) && count($disciplinari['espulsi'])): ?>

									<table id="espulsi" class="table-matches table table-condensed table-striped table-bordered" data-giornata-id="<?=$nextDay;?>">
										<thead>
										<tr class="table-header">
											<th class="text-left">Societ&agrave;</th>
											<th class="text-left">Nominativo</th>
											<th class="text-center">Periodo</th>
										</tr>
										</thead>
										<? $c_sq = 0; ?>
										
										<? foreach($disciplinari['espulsi'] as $k => $espulso): ?>								
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$espulso['IdSquadra'];?>">
											<td class="text-left"><?=$espulso['Squadra'];?></td>
											<td class="text-left"><?=$espulso['Anagrafica'];?></td>
											<td class="text-center"><?=$espulso['Periodo'];?></td>
								
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
																			
									<? else: ?>
									<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessun espulso in questa giornata, prossimo aggiornamento espulsi <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessun espulso in questa giornata, prossimo aggiornamento espulsi <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>
		
									<? endif; ?>
									
									</div><!-- close results-box -->
