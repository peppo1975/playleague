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