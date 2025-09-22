									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
										
										<? if ($i == ($nextDay - 1)): ?>
										<li class="selected"><a href="javascript:;" title=""><?=$giornata['Campionati']['Nome'];?> <? if ($giornata['Half']['Descrizione'] != "."): ?>| <?=$giornata['Half']['Descrizione'];?><? endif; ?></a></li>
										<? endif; ?>
										<? endforeach; ?>
									</ul>
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(count($espulsi)): ?>

									<? foreach ($giornate as $i => $giornata): ?>
									
									<? $tmp = $espulsi[$giornata['Match']['Giornata']]; ?>
									
									<? if(count($tmp)): ?>

									<table id="espulsi" class="table-matches <?=($giornata['Match']['Giornata'] != ($nextDay-1))? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th>Periodo</th>
										</tr>
										
										<? foreach ($tmp as $k => $espulso): ?>
										
										<?
										
										//debug($espulso);
										if(!isset($espulso[0]['Data'])) $espulso[0]['Data'] = '0000/00/00';
										
										?>
										
										<?$giorni = $espulso['GoalPartite']['EspulsioneGiornate'];?> 
										<?$inizio   = date('d/m/Y', strtotime($espulso[0]['Data']));?>
										<?$fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));?>
										
										<?if($giorni != '' && $giorni != 0){
										
											$periodo = $giorni . ' giornate';
										
										} else {
										
											if($inizio != '00/00/0000' && $fine != '00/00/0000') {
											
												$periodo = $inizio . ' - ' . $fine;
											
											} else {
											
												$periodo = '1 giornata';
											
											}
										
										}?>									
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$espulso[0]['IdSquadra'];?>">
											<td><?=$espulso[0]['NomeSquadra'];?></td>
											<td><?=$espulso[0]['anagrafica'];?></td>
											<td><?=$periodo;?></td>
								
										</tr>
										
										<? endforeach; ?>
										
									</table>	

									<? endif; ?>
									
									<? endforeach; ?>
									
									<? else: ?>
									<table class="table-matches">
									<tr>
									<td>
Prossimo aggiornamento espulsi <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
									</td>
									</tr>
									</table>
									<? endif; ?>
									
									</div><!-- close results-box -->
