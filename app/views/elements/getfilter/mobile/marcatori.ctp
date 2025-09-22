									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
										
										<? if ($i == 0): ?>
										<li class="selected"><a href="javascript:;" title=""><?=$giornata['Campionati']['Nome'];?> <? if ($giornata['Half']['Descrizione'] != "."): ?> | <?=$giornata['Half']['Descrizione'];?><? endif; ?></a></li>
										<? endif; ?>
										<? endforeach; ?>
									</ul>
									
									<div class="clear"></div>
									
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? foreach ($giornate as $i => $giornata): ?>
									
									<table class="table-matches <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th class="number">Goal</th>
										</tr>
										
										<? $marks = $marcatori[$giornata['Match']['Giornata']]; ?>
										
										<? foreach ($marks as $k => $marcatore): ?>
										
										<?
										
										//debug($marcatore);
										
										//$marcatore = $marcatore[0];
										
										//debug($marcatore);
										
										?>
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$marcatore['sc']['IdSquadra'];?>">
											<td><?=$marcatore['s']['NomeSquadra'];?></td>
											<td><?=$marcatore[0]['anagrafica'];?></td>
											<td class="number"><?=$marcatore[0]['goals'];?></td>
								
										</tr>
										
										<? endforeach; ?>
										
									</table>
									
									<? endforeach; ?>
									
									</div><!-- close results-box -->
