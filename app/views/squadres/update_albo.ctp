											<? foreach($squadra['SquadreAlbo'] as $k => $albo): ?>
											
											<tr class="tr-albo-doro <? if(($k +1) % 2 == 0): ?>alternate<? endif; ?>" data-id="<?=$albo['id'];?>">
												<td class="td_campionato"><?=$albo['Campionato'];?></td>
												<td class="td_posizione"><?=$albo['Posizione'];?></td>
												<td class="tools">
													<a href="javascript:;" class="AlboEdit"><img src="/img/timmyshare/icon_edit.png"></a>												
													<a href="javascript:;" class="AlboDelete"><img src="/img/timmyshare/icon_delete.png"></a>
												</td>
											</tr>
											
											<? $i = $k; ?>
											
											<? endforeach; ?>										