								<?
								
									$data = $this->Session->read('BookingData');
								
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
										
										'/campis/bookingSend',{
											
											'bookerNome': $(this).find('input[name="bookerNome"]').val(),
											'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
											'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
											'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
											'Data': $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-giorno'),
											'Ora':  $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-ora'),
											'campo_id': '<?=$data['campo'];?>',
											'Importo': $('.table-matches').find(".booking-allowed[data-selected=1]").attr('data-importo')
										
										
										},function(data) {
										
											bookClicked = 0;
								
											$("#bookingData").fadeOut(200,function() {
												
												$(".booking-data").html(data).fadeIn(200,function() {
												
															
												});
												
											});
											
											setTimeout(function() {
										
											$("#timmybox").remove();
											
											timmy_close();
								
											//global_booking.removeClass('booking-allowed').addClass('booking-disabled');
											
											},6000);
									
											
										}
										,'html');
										
									} else {
									
									
											bookClicked = 0;
									
									}
									
									return false;
									
								});
								
							});




$(function () {

    window.verifyRecaptchaCallback = function (response) {
        $('input[data-recaptcha]').val(response).trigger('change')
    }

    window.expiredRecaptchaCallback = function () {
        $('input[data-recaptcha]').val("").trigger('change')
    }


});							
						
						</script>								
								
								<div class="bookingResult"></div>
								
								<div class="booking-data">
								
									<h1 class="modal-title">Compila il modulo con i tuoi dati personali</h1>
									<table class="table table-striped table-condensed">
								

										<tr>
					
											<th style="border-top: 0px;">Data prenotazione</th>
											<td style="border-top: 0px;"><?=$data['data'];?></td>
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
									<div class="booking-pad">
					
									<section class="panel">
									<div class="panel-body">
									<form id="bookingData" class="form-horizontal form-bordered">
									<div class="input  form-group">
										<label class="col-md-3 control-label">Nome<sup>*</sup></label>
										<div class="col-md-9">
										<input type="text" class="form-control" name="bookerNome" autocomplete="off" required>
										</div>
									</div>
									<div class="input last-input form-group">
									
										<label class="col-md-3 control-label">Cognome<sup>*</sup></label>
																				<div class="col-md-9">

										<input type="text" class="form-control" name="bookerCognome" autocomplete="off" required>			
										</div>	
									</div>

									<div class="input form-group">
										<label class="col-md-3 control-label">E-Mail<sup>*</sup></label>
																				<div class="col-md-9">

										<input type="email" class="form-control" name="bookerEmail" autocomplete="off" required>
										</div>
									</div>
									<div class="input form-group">
										<label class="col-md-3">Telefono/Cellulare<sup>*</sup></label>
																				<div class="col-md-9">


										<input type="text" class="form-control" name="bookerTelefono" autocomplete="off" required>
										</div>
									</div>
									<div class="input last-input form-group">
										<script src='https://www.google.com/recaptcha/api.js'></script>
										<div class="g-recaptcha" data-sitekey="6LfqrIwUAAAAAOd8-U7Ne_8A0KezWvq9XPF38DbD" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback" style="width: 304px; margin: 0 auto;"></div>
                           			    <input class="form-control d-none" data-recaptcha="true" required data-error="Please complete the Captcha" style="display: none;">
									</div>									
									<div class="clear"></div>


				<p class="alert alert-warning">
									 A seguito della compilazione del  modulo, ricever&agrave; una mail all'indirizzo indicato 
									 contenente i dati relativi alla prenotazione ed un link per la disdetta della stessa.
									 La prenotazione potr&agrave; essere disdetta entro e non oltre le <b>ore <?=date("H:i",$past_time);?> del <?=date("d/m/Y",$past_time);?></b>.
									 Oltrepassato tale limite saranno addebitate &euro; 20,00 a titolo di penale.
									</p>
									
									<div class="errors alert alert-danger" style="opacity: 0;;">&nbsp;</div>

									<div class="input row">
									<div class="col-md-12 text-center">
										<input type="submit" class="btn btn-lg btn-success" value="Conferma prenotazione" />
									</div>
									</div>
									<div class="clear"></div>
									</form>
									</div>
									</section>



									</div>
								</div>
