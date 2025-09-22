									<p class="disciplinare-info">
										La disciplinare settimanale è  visibile dal sabato dalle ore 12.30
									</p>		
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? if(isset($disciplinari['diffidati']) && count($disciplinari['diffidati'])): ?>

									<table id="diffidati" class="table-matches" data-giornata-id="<?=$nextDay;?>">
										<tr class="table-header">
											<th>Societ&agrave;</th>
											<th>Nominativo</th>
											<th>Ammonizioni</th>
										</tr>
										
										<? $c_sq = 0; ?>
										
										<? foreach ($disciplinari['diffidati'] as $k => $diffidato): ?>
										
										<tr class="<?=(($c_sq+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$diffidato['IdSquadra'];?>">
											<td><?=$diffidato['Squadra'];?></td>
											<td><?=$diffidato['Anagrafica'];?></td>
											<td><?=$diffidato['Periodo'];?></td>
										</tr>
										
										<? $c_sq++; ?>
										
										<? endforeach; ?>
										
										<? if($c_sq == 0): ?>
										
										<tr>
											<td colspan="3">
											Nessun diffidato, prossimo aggiornamento  <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>
											</td>
											</tr>										
										
										<? endif; ?>										
										
									</table>
					
									<? else: ?>
											
									<table class="table-matches">
									<tr>
									<td>prossimo aggiornamento  <b><?=date("d/m/Y",strtotime('next Saturday'));?></b>,Nessun diffidato
									</td>
									</tr>
								
									</table>
									
									<? endif; ?>
									
									<? if(isset($disciplinari_campionato) && count($disciplinari_campionato)): ?>
									
									<div class="summary-discipline">
									
									<h3>Situazione disciplinare</h3>
									
										<table class="form_table form_table_full">
											<tr class="table-header">
												<th>Nominativo</th>
												<!--<th>Squadra</th>-->
												<th>Totale ammonizioni</th>
												<th>Totale espulsioni</th>
											</tr>
											<?foreach($disciplinari_campionato as $disciplinare):?>
											<tr>
												<td><?=$disciplinare['Atleta'];?></td>
												<?/*<td><?=$disciplinare['Squadra'];?></td>*/?>
												<td><?=$disciplinare['Ammonizioni'];?></td>
												<td><?=$disciplinare['Espulsioni'];?></td>
											</tr>
											<?endforeach;?>
										</table>
										
									</div>									
									
									<? endif; ?>
									
									</div><!-- close results-box -->
