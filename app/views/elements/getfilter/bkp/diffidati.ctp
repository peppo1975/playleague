									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
										
										<? if ($i == 0): ?>
										<li class="selected"><a href="javascript:;" title=""><?=$giornata['Campionati']['Nome'];?><? if ($giornata['Half']['Descrizione'] != "."): ?> | <?=$giornata['Half']['Descrizione'];?><? endif; ?></a></li>
										<? endif; ?>
										<? endforeach; ?>
									</ul>
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(count($diffidati)): ?>

									<? foreach ($giornate as $i => $giornata): ?>
									
									<table id="diffidati" class="table-matches <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th>Ammonizioni</th>
										</tr>
										
										<? $diff = $diffidati[$giornata['Match']['Giornata']]; ?>
										
										<? foreach ($diff as $k => $diffidato): ?>
										
										<?
										
										//debug($diffidato);
										
										$diffidato = $diffidato[0];
										
										?>
										
										<? if ($diffidato['Ammonizioni'] % 3 == 2): ?>
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$diffidato['IdSquadra'];?>">
											<td><?=$diffidato['NomeSquadra'];?></td>
											<td><?=$diffidato['anagrafica'];?></td>
											<td><?=$diffidato['Ammonizioni'];?></td>
										</tr>
										
										<? endif; ?>
										
										<? endforeach; ?>
										
									</table>
					
									<? endforeach; ?>
									
									<? else: ?>
											
									<table class="table-matches">
									<tr>
									<td>
Prossimo aggiornamento diffidati <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
									</td>
									</tr>
								
									</table>
									
									<? endif; ?>
									
									</div><!-- close results-box -->
