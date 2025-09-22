						
						<? 
						
							$dow['1'] = 'Lunedì';
							$dow['2'] = 'Martedì';
							$dow['3'] = "Mercoledì";
							$dow['4'] = 'Giovedì';
							$dow['5'] = 'Venerdì';
							$dow['6'] = 'Sabato';
							$dow['7'] = 'Domenica';
						
						?>
						
						<script type="text/javascript">
						
							function isValidEmail(str) {
							   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
							}
						
							$(function() {
								$(".booking-allowed").click(function() {
									
									$(".booking-allowed").not($(this)).removeAttr('data-selected');
									$(this).attr('data-selected','1');
									
									$(".selected-importo").text($(this).attr('data-importo'));
									$(".selected-data").text($(this).attr('data-giorno-it'));
									
									$(".selected-impianto").text('<?=$campo['Campi']['Descrizione'];?> dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'));
									
									$(".booking-data").fadeIn(500);
									
								});
								
								$("#bookingData").submit(function() {
									$(this).find('.errors').css('opacity',0);
									var error = 0;
									
									var email = $(this).find('input[name="bookerEmail"]').val();
									
									if (!isValidEmail(email)) error = 2;
									
									$(this).find(".required input").each(function() {
										
										if ($.trim($(this).val()) == '') error = 1;
										
									});
									
									if (error == 2) $(this).find('.errors').html('Inserire un indirizzo e-mail valido!').animate({ 'opacity': 1 },500);
									if (error == 1) $(this).find('.errors').html('Compilare tutti i campi obbligatori!').animate({ 'opacity': 1 },500);
									
									if (error == 0) {
										
										ajaxLoader('show');
										
										$.post(
										
										'/sections/bookingSend',{
											
											'bookerNome': $(this).find('input[name="bookerNome"]').val(),
											'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
											'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
											'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
											'Data': $(".booking-allowed[data-selected=1]").attr('data-giorno'),
											'Ora':  $(".booking-allowed[data-selected=1]").attr('data-ora'),
											'campo_id': <?=$campo['Campi']['Campo'];?>,
											'Importo': $(".booking-allowed[data-selected=1]").attr('data-importo')
										
										
										},function(data) {
											
											$(".bookingResult").fadeOut(200,function() {
												
												$(this).html(data).fadeIn(200,function() {
												
															
													ajaxLoader('hide');
													
												});
												
											});
									
											
										}
										,'html');
										
									}
									
									return false;
									
								});
								
							});
						
						</script>
					
						
						<div class="wrapper-box">
							<div class="wrapper-box-top"></div>
							<div class="wrapper-box-contents" id="filter-box">
								<div id="bg-retino" class="bookingResult">
								<h2>Disponibilità campo <b><?=$campo['Campi']['Descrizione'];?></b></h2>	
								<div class="table-container" style="display: block;">
									<table class="table-matches">
										<tr class="table-header">
						
											<? $max = 0; ?>
						
											<? foreach ($giorni as $i => $giorno): ?>
											
												<th>
												
												<?=substr($dow[$giorno['DayOfWeek']],0,3);?><br />
												<?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?>
												
												<? if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']); ?>
												
												</th>
											
											<? endforeach; ?>
						
										</tr>
										
										
										<? for ($i=0;$i<$max;$i++): ?>
										
						
											
										<tr class="<?=(!($i%2))? 'alternate' : '';?>">
										
										<? foreach ($giorni as $k => $giorno): ?>
										
											<td>
											
											<? if (isset($giorno['Orari'][$i])): ?>
											
											<? if ($giorno['Orari'][$i]['Occupato'] == 1): ?>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
											
											<div class="tip_open" rel="timmytip" title="<small><?=$giorno['Orari'][$i]['Info'];?></small>">
											
											<? endif; ?>
											
											<span class="booking-disabled"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></span>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
											
											<img src="/img/icon_classifica_marcatori.png" alt="" />
											
											<? endif; ?>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
											
											</div>
											
											<? endif; ?>
											
											<? else: ?>
											
											
											<span class="booking-allowed" data-giorno="<?=$giorno['Data'];?>" data-giorno-it="<?=$giorno['Data_it'];?>"  data-ora="<?=substr($giorno['Orari'][$i]['Ora'],0,-3);?>" data-ora-plus="<?=date("H:i",strtotime("+1 hour",strtotime("2011-01-01 " . $giorno['Orari'][$i]['Ora'])));?>" data-importo="<?=$giorno['Orari'][$i]['Importo'];?>"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></span>
											
											
											<? endif; ?>
											
											<? else: ?>
											
											&nbsp;
											
											<? endif; ?>
											</td>
												
										<? endforeach; ?>
											
										</tr>
										
										
										
										<? endfor; ?>
										
										
									</table>

								</div>
								
								<div class="booking-data">
								
								
									<h2>Compila i tuoi dati personali</h2>
									
									<h2><b>Data prenotazione:</b> <span class="selected-data"></span></h2>
									<h2><b>Impianto:</b> <span class="selected-impianto"></span></h2>
									<h2><b>Importo:</b> <span class="selected-importo"></span>&euro;</h2>
									
									<form id="bookingData">
								
									<div class="input required">
									
										<label>Nome</label>
										<input type="text" class="text" name="bookerNome" autocomplete="off"/>
									
									</div>
									

									<div class="input required">
									
										<label>Cognome</label>
										<input type="text" class="text" name="bookerCognome" autocomplete="off"/>
									
									</div>
								
									<div class="input required">
									
										<label>E-Mail</label>
										<input type="text" class="text" name="bookerEmail" autocomplete="off"/>
									
									</div>
								
									<div class="input required">
									
										<label>Telefono/Cellulare</label>
										<input type="text" class="text" name="bookerTelefono" autocomplete="off"/>
									
									</div>
									
									<div class="input">
										<input type="submit" class="submit" value="Prenota" />
										
										<div class="errors alert alert-danger">&nbsp;</div>
									
									</div>
								
									</form>
									
								</div>
								
									<div class="clear"></div>
								</div>		
							</div>
							<div class="wrapper-box-bottom"></div>
						</div>
