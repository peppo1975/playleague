								<?
								
									$data = $this->Session->read('BookingData');
								
								?>
						<script type="text/javascript">
						
							function isValidEmail(str) {
							   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
							}
						
							$(function() {
								
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
										
										$.post(
										
										'/mobile/bookingSend',{
											
											'bookerNome': $(this).find('input[name="bookerNome"]').val(),
											'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
											'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
											'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
											'Data': $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-giorno'),
											'Ora':  $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-ora'),
											'campo_id': '<?=$data['campo'];?>',
											'Importo': $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-importo')
										
										
										},function(data) {
										
											$('#execBooking').html(data);
											$('html,body').animate({ "scrollTop" : $('#execBooking').offset().top - 20 }, 100);											
											
										}
										,'html');
										
									}
									
									return false;
									
								});
								
							});
						
						</script>								
								
								<div class="booking-data">
								
									<h1>Compila il modulo con i tuoi dati personali</h1>
									<table>

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
									
									<form id="bookingData" data-ajax="false">
								
									<div class="input required">
										<label>Nome</label>
										<input type="text" name="bookerNome" autocomplete="off"/>
									</div>
									<div class="input last-input required">
									
										<label>Cognome</label>
										<input type="text" name="bookerCognome" autocomplete="off"/>				
									</div>
									<div class="clear"></div>
									
									<div class="input required">
										<label>E-Mail</label>
										<input type="text" name="bookerEmail" autocomplete="off"/>
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
