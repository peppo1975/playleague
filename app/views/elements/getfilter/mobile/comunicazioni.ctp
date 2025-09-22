									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<table class="table-matches" data-giornata-id="<?=$nextDay;?>">
										
										<? if(count($comunications)): ?>
										
										<tr class="table-header">
											<th>#</th>
											<th>Note</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach ($comunications as $k => $comunication): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>">
											<td><?=($c_sq+1);?></td>
											<td><?=$comunication['Comunication']['Note'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
									
										<? else: ?>
										
											<tr>
												<td colspan="4">
									prossimo aggiornamento <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>,Nessuna comunicazione
												</td>
												</tr>									
										
										<? endif; ?>
										
									</table>
									
									</div><!-- close results-box -->
