									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
							
										<? if(count($comunications)): ?>
												
									<table class="table-matches table table-bordered  table-condensed table-striped" data-giornata-id="<?=$nextDay;?>">
										
										<thead>
										<tr class="table-header">
											<th>#</th>
											<th>Note</th>
										</tr>
										</thead>
										<? $c_sq = 0; ?>
										
										<? foreach ($comunications as $k => $comunication): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>">
											<td><?=($c_sq+1);?></td>
											<td><?=$comunication['Comunication']['Note'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
									
									</table>
										<? else: ?>
											
												<?php if((date("D") == "Sat" || date("D") == "Sab") && date("hi") < 1230): ?>
													<div class="alert alert-warning">
													Nessuna comunicazione, prossimo aggiornamento <b>oggi alle 12:30</b>
													</div>
												<?php else: ?>
													<div class="alert alert-warning">
													Nessuna comunicazione, prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
													</div>
												<?php endif; ?>
					
										
										<? endif; ?>
										
									
									</div><!-- close results-box -->
