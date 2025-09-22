
									<ul class="switch-table-menu  nav nav-tabs">
		
										<? foreach ($giornate as $i => $giornata): ?>
											
										<li class="switch-giornata <?=($giornata['Match']['Giornata']==$nextDay)? 'selected' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>"><a href="javascript:;" title="Giornata <?=$giornata['Match']['Giornata'];?>"><?=$giornata['Match']['Giornata'];?></a></li>
									
										<? endforeach; ?>
									</ul>
									
									<div class="clear"></div>									
									
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? foreach ($giornate as $i => $giornata): ?>
									
									<table class="table-matches table table-bordered <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
											<th>Giorno</th>
											<th>Ora</th>
											<th>Impianto</th>
											<th>Partita</th>
											<th>Ris.</th>
											<th>Note</th>
											<th>Gara</th>
											<th>&nbsp;</th>
										</tr>
										
										<? $matches = $partite[$giornata['Match']['Giornata']]; ?>
										
										<? foreach ($matches as $k => $match): ?>
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>">
											<td><span class="number"><?=$match['Match']['Data_it'];?></span></td>
											<td><span class="number"><?=$match['Match']['Ora'];?></span></td>
											<td>
											<?
											if($match['Campi']['isMidland'] == 1) $campo_link = '/impianti/' . $match['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($match['Campi']['Descrizione'],'-'));
											else $campo_link = 'javascript:;';
											?>
												<a href="<?=$campo_link;?>" title="<?=$match['Campi']['Descrizione'];?>">
													<?=$match['Campi']['Descrizione'];?>
												</a>
											</td>
											<td><a href="#" title="<?=$match['Match']['CasaNome'];?>"><?=$match['Match']['CasaNome'];?></a> - <a href="#" title="<?=$match['Match']['TrasfertaNome'];?>"><?=$match['Match']['TrasfertaNome'];?></a></td>
											<td><span class="number"><?=$match['Match']['Risultato'];?></span></td>
											<td><?=$match['Causalresult']['Descrizione'];?></td>
											<td><?=$match['Match']['NomeGara'];?></td>
											<td class="last-column"><a href="javascript:;" class="nota-gara" data-match-id="<?=$match['Match']['Calendario'];?>" title="Stampa nota gara" rel="timmytip"><img src="/img/icon-pdf.png" width="16" height="16" alt="Stampa nota gara" /></a></td>
										</tr>
										
										<? endforeach; ?>
										
									</table>
									
									<? $riposo = $riposi[$giornata['Match']['Giornata']] ?>
									
									<? if (count($riposo)): ?>
									
									<div class="other-info-row <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<p>
											<b>Risposa:</b> <a href="#" title="<?=$riposo[0][0]['NomeSquadra'];?>"><?=$riposo[0][0]['NomeSquadra'];?></a>
										</p>
									</div>
									
									<? endif; ?>
									
															
									<? endforeach; ?>
						
									<div class="other-function-row">
										
										<div class="left">

											

										
										
											<? foreach ($giornate as $i => $giornata): ?>
											
											<div class="match-comunication <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
											
											<? if (!empty($comunicazioni[$giornata['Match']['Giornata']])): ?>
											<h3>Comunicazioni</h3>
											<p><?=$comunicazioni[$giornata['Match']['Giornata']]['Comunication']['Note'];?></p>
											<? endif; ?>
											
											</div>
											
											<? endforeach; ?>								
										
											
										</div>
										<div class="right">

										<div class="search-opponent hidden">
										<h4>Cerca giornata di sfida</h4>
										<div class="select-box middle-select">
											<div class="content-select">
												<span class="selected-value"></span>
																								
												
												<div class="values-of-select">
													<select name="avversario_id">
														<option value="0">Seleziona sfidante...</option>

														<? foreach ($avversari as $avversario): ?>
														
															<option data-squadra-id="<?=$avversario['SquadreCampionati']['SquadraCampionato'];?>" value="<?=$avversario['SquadreCampionati']['SquadraCampionato'];?>"><?=$avversario['Squadre']['Denominazione'];?></option>
														
														<? endforeach; ?>
														
													</select>
												</div>
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div>
										
										<ul class="match-results-menu hidden nav nav-tabs">
											<li><a href="#" title="Giornata 02">Giornata 02</a></li>
											<li><a href="#" title="Giornata 12">Giornata 12</a></li>
											<li><a href="#" title="Giornata 15">Giornata 15</a></li>
										</ul>
										</div>

										</div>
										<div class="clear"></div>									
									</div>
									
									
									</div><!-- close results-box -->
