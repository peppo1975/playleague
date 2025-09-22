<?


?>

<script type="text/javascript">
	
	$(function(){
	
		$('select[name="giornata_id"]').die('change').live('change', function(){
		
			var me      = $(this);
			var buttons = $('.buttons-container');
			
			buttons.find('ul').find('li[data-giornata-id='+me.val()+']').trigger('click');
		
		});
		
		$('.getResult').die('click').live('click', function(){
		
			var me = $(this);
				
				$('#getResult').popup("close");
				
				$.get(me.attr('data-href'), function(data){
				
					$('#getResult').html(data);
					
					$('#getResult').popup("open");
				
				},'html');
		
		});
	
	});

</script>
	<div data-role="navbar" class="buttons-container" style="display: none;">
									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
											
										<li class="switch-giornata <?=($giornata['Match']['Giornata']==$nextDay)? 'selected' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>"><a href="javascript:;" title="Giornata <?=$giornata['Match']['Giornata'];?>"><?=$giornata['Match']['Giornata'];?></a></li>
									
										<? endforeach; ?>

									</ul>
	</div>
	
	<select name="giornata_id">
	
		<? foreach ($giornate as $i => $giornata): ?>
		
			<option <?=($giornata['Match']['Giornata']==$nextDay)? 'selected="selected"' : '';?> value="<?=$giornata['Match']['Giornata'];?>">Giornata: <?=$giornata['Match']['Giornata'];?></option>
		
		<? endforeach; ?>
	
	</select>
									
									<div class="clear"></div>
									
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<div id="getResult" data-role="popup"></div>
									
									<div class="popup-container">
									
										<? foreach ($giornate as $i => $giornata): ?>
										
											<? $matches = $partite[$giornata['Match']['Giornata']]; ?>
											
											<? foreach ($matches as $k => $match): ?>	
											
											<div data-role="popup" id="matches-<?=$match['Match']['Calendario'];?>">
												<table>

													<tr>
														<th>Data</th>
														<td><?=$match['Match']['Data_it'];?> - ore <?=$match['Match']['Ora'];?></td>														
													</tr>

													<tr>
														<th>Home</th>
														<td><?=$match['Match']['CasaNome'];?></td>														
													</tr>
													
													<tr>
														<th>Visitors</th>
														<td><?=$match['Match']['TrasfertaNome'];?></td>														
													</tr>																										
									
													<tr>
														<th>Risultato</th>
														<td>
															<span class="number">
															
																<? $risultato = (($match['Match']['Risultato'])? $match['Match']['Risultato']:'&nbsp;-&nbsp;');?>
																
																<? if($match['Match']['Risultato']): ?>
																
																	<a data-transition="pop" class="getResult" href="#getResult" data-href="/mobile/getResult/<?=$match['Match']['Calendario'];?>" data-rel="popup"><?=$risultato;?></a>	
																
																<? else: ?>
																
																	<?=$risultato;?>	
																
																<? endif; ?>
																
															</span>
														</td>														
													</tr>									
													
													<tr>
														<th>Impianto</th>
														<td>
														<?
														
														if($match['Campi']['isMidland'] == 1 && isset($match['Campi']['isMidland'])) $campo_link = '/mobile/impianti/' . $match['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($match['Campi']['Descrizione'],'-'));
														else $campo_link = '';
														?>
														<? if($campo_link != ''): ?>
															<a data-ajax="false" href="<?=$campo_link;?>" title="<?=$match['Campi']['Descrizione'];?>">
																<?=$match['Campi']['Descrizione'];?>
															</a>
														<? else: ?>
															<?=$match['Campi']['Descrizione'];?>
														<? endif; ?>
														</td>														
													</tr>
									
													<tr>
														<th>Note</th>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<th>Gara</th>
														<td>&nbsp;</td>
													</tr>												
												</table>
											</div>									
										
											<? endforeach; ?>										
										
										<? endforeach; ?>
									
									</div>
									
									<? foreach ($giornate as $i => $giornata): ?>
									
									<table data-role="table" id="my-table-<?=$giornata['Match']['Giornata'];?>" style="<?=($giornata['Match']['Giornata'] != $nextDay)? 'display: none;' : '';?>" data-mode="reflow" class="ui-responsive table-matches <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
								
											<th>Partita</th>
											<th>Ris.</th>
			
										</tr>
										
										<? $matches = $partite[$giornata['Match']['Giornata']]; ?>
										
										<? foreach ($matches as $k => $match): ?>
										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$match['Match']['Casa'];?>" data-trasferta-id="<?=$match['Match']['Trasferta'];?>">
					
											
											<td><a data-transition="pop" href="#matches-<?=$match['Match']['Calendario'];?>" data-rel="popup"><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></a></td>			
										
											
											<td>
												<span class="number">
												
													<? $risultato = (($match['Match']['Risultato'])? $match['Match']['Risultato']:'&nbsp;-&nbsp;');?>
													
													<? if($match['Match']['Risultato']): ?>
													
														<a data-transition="pop" class="getResult" href="#getResult" data-href="/mobile/getResult/<?=$match['Match']['Calendario'];?>" data-rel="popup"><?=$risultato;?></a>	
													
													<? else: ?>
													
														<a data-transition="pop" href="#matches-<?=$match['Match']['Calendario'];?>" data-rel="popup"><?=$risultato;?></a>	
													
													<? endif; ?>
													
												</span>
											</td>
											
							
										
										</tr>
										
										<? endforeach; ?>
										
									</table>
									
									<? $riposo = $riposi[$giornata['Match']['Giornata']] ?>
									
									<? if (count($riposo) && $giornata['Campionati']['Italiana'] == 'No'): ?>
									
									<div class="other-info-row <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<p>
											<b>Riposa:</b> <?=$riposo[0][0]['NomeSquadra'];?>										
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

										<div class="clear"></div>									
									</div>
									
									
									</div><!-- close results-box -->
