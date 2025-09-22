						<?
						
							$campi = $this->requestAction('/campis/getList');
							
							//debug($campi);
						
						?>
					


				<section class="impianti-prenotazioni" style="display: none !important;">
				<div class="featured-boxes-full featured-boxes-full-scale">


								<div class="col-md-6">
									<!-- <div class="divider divider-solid divider-style-4 half-section">
										<i class="fa fa-chevron-down"></i>
									</div> -->
									<div class="featured-box-full featured-box-full-quintenary">
										<p><i class="fa fa-trophy"></i></p>
										<table>
											<tbody>
												<tr>
													<td class="td-middle">
															<h4>Iscrizioni online</h4>
															<p>Iscrivi la tua squadra ad un campionato o ad un torneo tutto on line! Registrati, compila il format ed è fatta!</p>

															
													</td>
													<td class="td-middle">
														<h4>Tesseramenti online</h4>
															<p>Tessera chi e quando vuoi risparmiando anche i costi aggiuntivi sul campo o ai point! Inserisci i dati e paga in sicurezza con carta di credito.</p>

															
													</td>
												</tr>
												<tr>
													<td>
														<p>
																&nbsp;<br /><br />
																<button type="button" onclick="location.href='/subscriptions/tesseramenti';" class="btn btn-success btn-lg mr-xs mb-sm">Iscriviti ora</button>
															</p>
													</td>
													<td>
														<p>
																&nbsp;<br /><br />
																<button type="button" onclick="location.href='/subscriptions/tesseramenti';" class="btn btn-success btn-lg mr-xs mb-sm">Vai ai tesseramenti</button>
															</p>
													</td>
												</tr>
											</tbody>
										</table>

										

									</div>
								</div>

								<div class="col-md-6">
									<!-- <div class="divider divider-solid divider-style-4 half-section">
										<i class="fa fa-chevron-down"></i>
									</div> -->
									 <div class="featured-box-full featured-box-full-quintenary">
									 	<br>
									 	<i class="fa fa-calendar-check-o"></i>
										<h4>Prenotazione impianti sportivi</h4>
										<p>Cerca tra gli impianti abilitati, verifica le disponibilità di orari e date, seleziona, inserisci i tuoi dati e via!</p>
										<p><br><br><br></p>
									 	 	<div class="col-md-1"></div>
											<div class="input col-md-5" style="margin-bottom: 40px">
												<select name="disponibilita" class="form-control" data-plugin-selectTwo>
														
														<option value="">Seleziona un impianto sportivo...</option>
															<? foreach($campi as $campo): ?>
															<option value="/impianti/<?=$campo['Campi']['Campo']?>/<?=Inflector::slug(strtolower($campo['Campi']['Descrizione'],'-'));?>"><?=$campo['Campi']['Descrizione']?></option>
															<? endforeach; ?>
												</select>
											</div>
											<div class="col-md-2"></div>
											<div class="col-md-3">
												<button type="button" class="btn btn-success btn-lg mr-xs mb-sm btnverifica">Verifica disponibilit&agrave;</button>
											</div>
											<p><br><br><br><br></p>

											<script type="text/javascript">

											$(".btnverifica").click(function() {

													var select = $("select[name='disponibilita']").val();

													if (select != "") {

														location.href = select;
													}

											});

											</script>
										
									</div>


							</div>

				</div>
				<br style="clear: both;" />
				</section>