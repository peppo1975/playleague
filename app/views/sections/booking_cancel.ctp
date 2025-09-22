	
						<div class="wrapper-box">
							<div class="wrapper-box-top"></div>
							<div class="wrapper-box-contents" id="filter-box">
								<div id="bg-retino" class="bookingResult">
								<? if (isset($booking)): ?>
								<h2>Gentile <?=$booking['CampiBooking']['bookerNome'];?> <?=$booking['CampiBooking']['bookerCognome'];?>,</h2>	
								<p>
								
									La sua prenotazione &egrave; stata eliminata correttamente
								
								</p>
								<? else: ?>
								
									<h2>Siamo spiacenti,</h2>
									<p>
										La prenotazione selezionata è inesistente o già annullata
									</p>
								
								<? endif; ?>
								<div class="clear"></div>
								</div>		
							</div>
							<div class="wrapper-box-bottom"></div>
						</div>
