								<?
								
									$data = $this->Session->read('BookingData');
									$user = $this->Session->read('User');
									
								?>
						<script type="text/javascript">
						
							function isValidEmail(str) {
  											var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
  											return pattern.test(str);
							}
						
							$(function() {
						
								$('#timmybox_container').css('background','#FFF');						
								
								
								var bookClicked = 0;
								
								$("#bookingData").submit(function() {
								
								
									if (bookClicked == 1) return false;
									
									bookClicked = 1;
								
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
										
										'/admin/campis/bookingSend',{
											
											'bookerNome': $(this).find('input[name="bookerNome"]').val(),
											'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
											'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
											'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
											'Data': $('#booking').find(".booking-allowed[data-selected=1]").attr('data-giorno-it'),
											'Ora':  $('#booking').find(".booking-allowed[data-selected=1]").attr('data-ora'),
											'campo_id': '<?=$data['campo'];?>',
											'Importo': $('#booking').find(".booking-allowed[data-selected=1]").attr('data-importo')
										
										
										},function(data) {
										
											bookClicked = 0;
											
											
											global_booking.removeClass('booking-allowed').addClass('booking-disabled');
										
											$("#timmybox").remove();
											
											timmy_close();
											
											$(".bookingResult").fadeOut(200,function() {
												
												$(this).html(data).fadeIn(200,function() {
												
															
												});
												
											});
									
											
										}
										,'html');
										
									} else {
									
									
											bookClicked = 0;
									
									}
									
									return false;
									
								});
								
							});
						
						</script>								
								
								<div class="booking-data">
								
									<h1>Compila il modulo con i tuoi dati personali</h1>
									<table>
				<? if (!empty($campo['Campi']['NomeGestore'])):?>
										<tr>
											<th>Gestore</th>
											<td><?=$campo['Campi']['NomeGestore'];?> <?=$campo['Campi']['CognomeGestore'];?><? if (!empty($campo['Campi']['CellulareGestore'])):?>,<?endif;?> <?=$campo['Campi']['CellulareGestore'];?> <a href="mailto:<?=$campo['Campi']['EmailGestore'];?>"><?=$campo['Campi']['EmailGestore'];?></a></td>
										<tr>
										
									<? endif; ?>
										<tr>
											<th>Data prenotazione</th>
											<td><?=$data['data'];?></td>
										</tr>
										<tr>
											<th>Impianto selezionato</th>
											<td><?=$data['impianto'];?></td>
										</tr>
										<tr>
											<th>Orario prenotazione</th>
											<td><?=$data['ora'];?></td>
										</tr>
										<tr class="last-row">
											<th>Costo orario impianto</th>
											<td><?=$data['importo'];?> &euro;</td>
										</tr>
									</table>
								
									<? $past_time = strtotime("-1 days",strtotime($data['data_real'] . " " . $data['ora_real'])); ?>
								
									<p>
									 A seguito della compilazione del seguente modulo, ricever&agrave; una mail all'indirizzo indicato<br />
									 contenente i dati relativi alla prenotazione ed un link per la disdetta della stessa.<br />
									 La prenotazione potr&agrave; essere disdetta entro e non oltre le <b>ore <?=date("H:i",$past_time);?> del <?=date("d/m/Y",$past_time);?></b>.
									 Oltrepassato tale limite saranno addebitate &euro; 20,00 a titolo di penale.
									</p>
									
									<form id="bookingData">
								
									<div class="input required">
										<label>Nome</label>
										<input type="text" name="bookerNome" value="<?=$user['nome'];?>" autocomplete="off"/>
									</div>
									<div class="input last-input required">
									
										<label>Cognome</label>
										<input type="text" name="bookerCognome" value="<?=$user['cognome'];?>"  autocomplete="off"/>				
									</div>
									<div class="clear"></div>
									
									<div class="input required">
										<label>E-Mail</label>
										<input type="text" name="bookerEmail" value="<?=$user['username'];?>" autocomplete="off"/>
									</div>
									<div class="input last-input required">
										<label>Telefono/Cellulare</label>
										<input type="text" name="bookerTelefono" autocomplete="off"/>
									</div>
									<div class="clear"></div>
									<div class="errors">&nbsp;</div>

									<div class="input">
										<input type="submit" value="Conferma prenotazione" />
									
									</div>
									<div class="clear"></div>
									</form>
									
								</div>
