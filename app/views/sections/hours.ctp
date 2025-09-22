<? $orari =  (array)unserialize($campionato['Campionati']['subscriptions']);



  ?>
<? $random = rand(1,1000); ?>
<div class="modal-title">
Giorni e orari dei gironi
</div>

<p class="">
	
	Per effettuare l'iscrizione devi cliccare sul girone di riferimento e selezionare il giorno e l'orario di tua preferenza.

</p>

							<div class="panel-group panel-group-primary" id="accordion_<?=$random; ?>">
							<? $j = 0; ?>
								<? foreach ($orari as $girone => $orari): ?>
								<? $show = 0; ?>


										<? for ($i = 0; $i < $orari['caselle']; $i++): ?>

											<? if ($orari['Campo'][$i]==$campo): ?>
												<? $show = 1; ?>

											<? endif; ?>
										<? endfor; ?>

								<? if ($show == 1): ?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_<?=$random;?>" href="#<?=$random;?>_<?=$girone;?>">
											<? $nomegirone = ""; ?>
												<? foreach ($campionato['Half'] as $half): ?>

												<? if ($half['GironeCampionato'] == $girone) {

													$nomegirone = $half['Descrizione'];

												}
												?>

												<? endforeach; ?>

												<i class="fa fa-calendar"></i>
												Girone <?=$nomegirone;?>
											</a>
										</h4>
									</div>
									<div id="<?=$random;?>_<?=$girone;?>" class="accordion-body collapse <? if ($i == 0 || 1==1):?>in<? endif; ?>">
										<div class="panel-body">
										
											<table class="table table-bordered table-striped table-condensed table-hours">
												
											<thead>
											<tr>
												<th>Giorno</th><th>Ora</th>
											</tr>
											</thead>

											<? $giorni = array('1' => 'Lunedì','2' => 'Martedì','3' => 'Mercoledì','4' => 'Giovedì','5' => 'Venerdì','6' => 'Sabato','7' => 'Domenica'); ?>

											<? for ($i = 0; $i < $orari['caselle']; $i++): ?>

											<? if ($orari['Campo'][$i]==$campo): ?>
											<? $a = strtolower(substr($giorni[$orari['Giorno'][$i]], 0, 4))."-".$orari['Orario'][$i] ?>
											<? 
											$nn = false;
											foreach($not_disp as $n)
											{
												$a = str_replace(":", ".", $a);
												if($n == $a)
												{
													$nn = true;
													break;
												}
											} ?>
											<? if(!$nn): ?>
											<tr style="cursor: pointer;" onclick="location.href = '/subscriptions/tesseramenti?giorno=<?=$orari['Giorno'][$i];?>&orario=<?=$orari['Orario'][$i];?>';">
												<td><?=$giorni[$orari['Giorno'][$i]];?></td>
												<td><?=$orari['Orario'][$i];?> <span style="" class="text-color-primary"><small>Seleziona</small></span></td>
											</tr>
											<? endif ?>
										<? endif; ?>

											<? endfor; ?>

											</table>
										</div>
									</div>
								</div>
								<? endif; ?>
								<? $j++; ?>
								<? endforeach; ?>

							</div>


