								<script type="text/javascript">
								$(function(){
								
									$(".table-matches").delegate('.vote','click', function(){
									
										var obj     = $(this);
										var type    = obj.attr('data-type');
										var athlete = obj.attr('data-id');
										var allow   = obj.parents('tr').attr('vote-allow');
										var match   = obj.parents('tr').attr('data-id');
										
										if(obj.parents('tr').hasClass('selected')) {
										
										$('.table-message').remove();
										
										if(type == 'delegato') {
											var collega = obj.parents('tr').find('a[data-type="arbitro"]').attr('data-id');
										} else {
											var collega = obj.parents('tr').find('a[data-type="delegato"]').attr('data-id');
										}
										
										if(athlete == collega || allow == 0) { obj.parent('td').append($('<span>').addClass('error-message').addClass('table-message').text('Impossibile votare')); $('.table-message').fadeOut(4000); return false };
										
										timmy_load('/lda_votes/vote_index/' + match + '/' + athlete);
										
										}
										
									});
									
								
								});
								
								</script>
									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
											
										<li class="switch-giornata <?=($giornata['Match']['Giornata']==$nextDay)? 'selected' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>"><a href="javascript:;" title="Giornata <?=$giornata['Match']['Giornata'];?>"><?=$giornata['Match']['Giornata'];?></a></li>
									
										<? endforeach; ?>
									</ul>
									
									<div class="clear"></div>									
									
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<?
										
										$options = array(
										
											1 => 'Gravemente insufficiente',
											2 => 'Insufficiente',
											3 => 'Appena sufficiente',
											4 => 'Sufficiente',
											5 => 'Discreto',
											6 => 'Buono',
											7 => 'Ottimo',
										
										);
												
									?>									
									
									<? foreach ($giornate as $i => $giornata): ?>
									
									<?
									
										if(!empty($match['Match']['Risultato'])) { $vote_allow = 1; }
										else 									 { $vote_allow = 0; }									
									
									?>
									
									<table class="table-matches <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
											<th>Giorno</th>
											<th>Ora</th>
											<th>Impianto</th>
											<th>Partita</th>
											<th>Ris.</th>
											<th>Note</th>
											<th>Gara</th>
											<th>Arbitro</th>
											<th>Delegato</th>
											<th>&nbsp;</th>
										</tr>
										
										<? $matches = $partite[$giornata['Match']['Giornata']]; ?>
										
										<? foreach ($matches as $k => $match): ?>
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-squadra-id="<?=$match['Casa']['Squadra'];?>" data-trasferta-squadra-id="<?=$match['Trasferta']['Squadra'];?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>" vote-allow="<?=$vote_allow;?>" data-id="<?=$match['Match']['Calendario'];?>">
											<td><span class="number"><?=$match['Match']['Data_it'];?></span></td>
											<td><span class="number"><?=$match['Match']['Ora'];?></span></td>
											<td>
											<?
											
											if($match['Campi']['isMidland'] == 1 && isset($match['Campi']['isMidland'])) $campo_link = '/impianti/' . $match['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($match['Campi']['Descrizione'],'-'));
											else $campo_link = '';
											?>
											<? if($campo_link != ''): ?>
												<a href="<?=$campo_link;?>" title="<?=$match['Campi']['Descrizione'];?>">
													<?=$match['Campi']['Descrizione'];?>
												</a>
											<? else: ?>
												<?=$match['Campi']['Descrizione'];?>
											<? endif; ?>
											</td>
											<td><a href="/squadra/dettaglio/<?=$match['Casa']['Squadra'];?>/<?=strtolower(Inflector::Slug($match['Match']['CasaNome'],'-'));?>" title="<?=$match['Match']['CasaNome'];?>"><?=$match['Match']['CasaNome'];?></a> - <a href="/squadra/dettaglio/<?=$match['Trasferta']['Squadra'];?>/<?=strtolower(Inflector::Slug($match['Match']['TrasfertaNome'],'-'));?>" title="<?=$match['Match']['TrasfertaNome'];?>"><?=$match['Match']['TrasfertaNome'];?></td>
											<td><span class="number"><?=$match['Match']['Risultato'];?></span></td>
											<td><?=$match['Causalresult']['Descrizione'];?></td>
											<td><?=$match['Match']['NomeGara'];?></td>
											
											<? if($this->params['pass'][3] == $match['Match']['Casa'] || $this->params['pass'][3] == $match['Match']['Trasferta'] ): ?>
											
											<?
											
												$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);
												
												if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
												else 										  $title = $match['Match']['NomeArbitro'];
											
											?>
											<td>
												<a href="javascript:;" <?if(!$giaVotato):?>class="vote"<?else:?>rel="timmytip"<?endif;?> data-type="arbitro" data-id="<?=$match['Lda']['Arbitro'];?>" title="<?=$title;?>">
													<?=$match['Match']['NomeArbitro'];?>
												</a>
											</td>
											<?
											
												$giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
												if(is_array($giaVotato) && count($giaVotato)) $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
												else 										  $title = $match['Match']['NomeDelegato'];							
											
											?>	
											<td>
												<a href="javascript:;" <?if(!$giaVotato):?>class="vote"<?else:?>rel="timmytip"<?endif;?> data-type="delegato" data-id="<?=$match['Lda']['Delegato'];?>" title="<?=$title;?>">
													<?=$match['Match']['NomeDelegato'];?>
												</a>							
											</td>
											
											<? else: ?>
											
											<td><?=$match['Match']['NomeArbitro'];?></td>
											<td><?=$match['Match']['NomeDelegato'];?></td>
											
											<? endif; ?>
											
											<td class="last-column">
										
											<? if (time() <= strtotime(substr($match['Match']['Data'],0,strlen('0000-00-00')) . " " . str_replace(".",":",$match['Match']['Ora']))): ?>
					
											<a href="javascript:;" class="nota-gara" data-match-id="<?=$match['Match']['Calendario'];?>" title="Stampa nota gara" rel="timmytip"><img src="/img/icon-pdf.png" width="16" height="16" alt="Stampa nota gara" /></a>
											<? endif; ?>
											
											</td>										
										
										</tr>
										
										<? endforeach; ?>
										
									</table>
									
									<? $riposo = $riposi[$giornata['Match']['Giornata']] ?>
									
									<? if (count($riposo)): ?>
									
									<div class="other-info-row <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<p>
											<b>Risposa:</b> <?=$riposo[0][0]['NomeSquadra'];?>										
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
										<div class="select-box middle-select grey">
											<div class="content-select">
												<span class="selected-value">Seleziona sfidante...</span>
												
												<input type="hidden" name="avversario_id" class="select-value" value="0" autocomplete="off" />
												
												
												<div class="values-of-select">
													<ul>

														<? foreach ($avversari as $avversario): ?>
														
															<li data-squadra-id="<?=$avversario['SquadreCampionati']['SquadraCampionato'];?>" data-value="<?=$avversario['SquadreCampionati']['SquadraCampionato'];?>"><?=$avversario['Squadre']['Denominazione'];?></li>
														
														<? endforeach; ?>
														
													</ul>
												</div>
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div>
										
										<ul class="match-results-menu hidden">
											<li><a href="#" title="Giornata 02">Giornata 02</a></li>
											<li><a href="#" title="Giornata 12">Giornata 12</a></li>
											<li><a href="#" title="Giornata 15">Giornata 15</a></li>
										</ul>
										</div>

										</div>
										<div class="clear"></div>									
									</div>
									
									
									</div><!-- close results-box -->
