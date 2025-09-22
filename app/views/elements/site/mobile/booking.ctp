						<? 
						
							$campo  = $booking['campo'];
							$giorni = $booking['giorni'];
							
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
							
								$(".js_booking").click(function(){
								
									if(!$('#booking-box').is(':visible'))
									$('#booking-box').slideDown('fast');
									else
									$('#booking-box').slideUp('fast');
									
								
								});
							
								$(".booking-allowed").bind('click', function() {
									
									$(".booking-allowed").not($(this)).removeAttr('data-selected');
									$(this).attr('data-selected','1');
									
									// $(".selected-importo").text($(this).attr('data-importo'));
									// $(".selected-data").text($(this).attr('data-giorno-it'));
									
									// $(".selected-impianto").text('<?=$campo['Campi']['Descrizione'];?> dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'));
									
									// $(".booking-data").fadeIn(500);
									
									$.post('/mobile/saveBookingSession', {
									
										'importo': $(this).attr('data-importo'),
										'data': $(this).attr('data-giorno-it'),
										'campo': '<?=$campo['Campi']['Campo'];?>',
										'impianto': '<?=$campo['Campi']['Descrizione'];?>',
										'ora': 'dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'),		
										'ora_real': $(this).attr('data-ora'),
										'data_real': $(this).attr('data-giorno')
									
									},function() {
										
										$('#execBooking').load('/mobile/booking_timmy', function(){
										
											$('html,body').animate({ "scrollTop" : $('#execBooking').offset().top - 20 }, 100);
											$('#bookingData').trigger('create');
										
										});
										
										
									});
									
						
									
								});
								/*
								
								$("#bookingData").die('submit').live('submit', function() {
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
										
										$.post(
										
										'/mobile/bookingSend',{
											
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
												
													
													
												});
												
											});
									
										return false;
											
										}
										,'html');
										
									}
									
									return false;
									
								});
								*/
								
								$('select[name="giorno_id"]').die('change').live('change', function(){
								
									var me = $(this);
										
										$('.booking-container').find('table').hide();
										$('.booking-container').find('table[data-id='+me.val()+']').show();
								
								});
								
								
								
							});
						
						</script>
					
						
						<div class="booking-box" id="booking-box">
								<?/*<h2>Disponibilità campo <b><?=$campo['Campi']['Descrizione'];?></b></h2>*/?>
								<p>Verifica le ore disponibili per il campo <b><?=$campo['Campi']['Descrizione'];?></b> e seleziona quella che preferisci.</p>	
								
								<select name="giorno_id" autocomplete="off">
									<? $max = 0; ?>
				
										<option value="">Seleziona giorno</option>
				
									<? foreach ($giorni as $i => $giorno): ?>
									
										<option value="<?=strtotime($giorno['Data'] . " 00:00:01");?>">
										
										<?=substr($dow[$giorno['DayOfWeek']],0,3);?>
										<?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?>
										
										<? if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']); ?>
										
										</option>
									
									<? endforeach; ?>									
								</select>
								
								<div class="popup-container" data-role="popup" id="execBooking">
									
								</div>
								
								<div id="results-box" class="booking-container">
								
									<? foreach ($giorni as $k => $giorno): ?>								

									<table style="display: none;" data-id="<?=strtotime($giorno['Data'] . " 00:00:01");?>" data-role="table" data-mode="reflow" class="ui-responsive table-matches">
										<tr class="table-header">
											<th><?=substr($dow[$giorno['DayOfWeek']],0,3);?>&nbsp;<?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?></th>
										</tr>
										
											<? if (isset($giorno['Orari']) && !empty($giorno['Orari'])): ?>
										
											<? foreach($giorno['Orari'] as $orario): ?>
										
											<tr data-id="<?=strtotime($giorno['Data'] . " 00:00:01");?>-<?=substr($orario['Ora'],0,-3);?>">
											<td>
											
											<? /*
											
											<? if ($orario['Occupato'] == 1): ?>
											<a class="booking-disabled" href="#" title="Occupato" >
												<?=substr($orario['Ora'],0,-3);?>
											<a>
											<? endif; ?>
											
											*/ ?>
	<?/*										
											<? if (strtotime($giorno['Data'] . " 00:00:02") > strtotime("2014-12-29 00:00:01") && strtotime($giorno['Data'] . " 00:00:01") < strtotime("2015-01-06 00:00:01")): ?>
												<? $orario['Occupato'] = 1; ?>
										<? endif; ?>
*/ ?>


											<? $disabled = 0; ?>
											<? 
									
											foreach ($campo['CampiDisabled'] as $date) {

													if ($date['giorno'] == $giorno['Data']) $orario['Occupato'] = 1;

											}

											?>

											<? if ($orario['Occupato'] == 1): ?>
											
											<? if ($orario['Info'] != ""): ?>
																						
											<? endif; ?>
											
											<? if ($orario['Info'] != ""): ?>
											
											<a class="booking-disabled" style="text-decoration: none;" href="javascript:;" title="<?=strip_tags($orario['Info']);?>" >
												<?=substr($orario['Ora'],0,-3);?>
											</a>
											
											<? endif; ?>
											
											<? if ($orario['Info'] == ""): ?>
													
											<a class="booking-disabled" style="text-decoration: none;" href="javascript:;" title="<?=strip_tags($orario['Info']);?>" >
												<?=substr($orario['Ora'],0,-3);?>
											</a>											
											<? endif; ?>
											
											<? else: ?>
											
											
											<a class="booking-allowed" data-transition="pop" href="#execBooking" data-rel="popup" data-giorno="<?=$giorno['Data'];?>" data-giorno-it="<?=$giorno['Data_it'];?>"  data-ora="<?=substr($orario['Ora'],0,-3);?>" data-ora-plus="<?=date("H:i",strtotime("+1 hour",strtotime("2011-01-01 " . $orario['Ora'])));?>" data-importo="<?=$orario['Importo'];?>">
												<?=substr($orario['Ora'],0,-3);?>
											</a>
											
											<? endif; ?>
											
											</td>
											</tr>
											
											<? endforeach; ?>
											
											<? else: ?>
											
											<tr>
											<td>
												Nessun orario disponibile
											</td>
											</tr>											
											
											<? endif; ?>										

									</table>

									<? endforeach; ?>

<? /*

										<? foreach ($giorni as $k => $giorno): ?>
											
											<ul data-inset="true" data-role="listview" data-theme="a" data-id="<?=strtotime($giorno['Data'] . " 00:00:01");?>" style="display: none;">
											
											<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
											<?=substr($dow[$giorno['DayOfWeek']],0,3);?>
											<?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?>
											</li>

											<? if (isset($giorno['Orari']) && !empty($giorno['Orari'])): ?>
										
											<? foreach($giorno['Orari'] as $orario): ?>
										
											<li>
											
											<? if ($orario['Occupato'] == 1): ?>
											
											<? if ($orario['Info'] != ""): ?>
																						
											<? endif; ?>
											
											<? if ($orario['Info'] != ""): ?>
											
											<a class="booking-disabled" data-top="-25px;" href="#" title="<?=htmlentities($orario['Info']);?>" >
												<?=substr($orario['Ora'],0,-3);?>

											<a>
											
											<? endif; ?>
											
											<? if ($orario['Info'] != ""): ?>
																						
											<? endif; ?>
											
											<? else: ?>
											
											
											<a class="booking-allowed" data-giorno="<?=$giorno['Data'];?>" data-giorno-it="<?=$giorno['Data_it'];?>"  data-ora="<?=substr($orario['Ora'],0,-3);?>" data-ora-plus="<?=date("H:i",strtotime("+1 hour",strtotime("2011-01-01 " . $orario['Ora'])));?>" data-importo="<?=$orario['Importo'];?>">
												<?=substr($orario['Ora'],0,-3);?>
											</a>
											
											
											<? endif; ?>
											
											</li>
											
											<? endforeach; ?>
											
											<? else: ?>
											
											<li>
												Nessun orario disponibile
											</li>
											
											<? endif; ?>											
											
										</ul>
										
										<? endforeach; ?>									
										
*/ ?>										
										
								</div>
								
								<div class="clear"></div>
						</div>
