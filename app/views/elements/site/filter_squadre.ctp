<script type="text/javascript">
	
$(function() {
	$('img').css('opacity',0);
});	

$(window).load(function() {


	var div = $('.squadra-img');
	var img = div.children('img');

	var first = div.width() / 2;
	var second= img.width() / 2;
	
	var margin_top = first - second;
	
	img.css('margin-left',margin_top);
	
	$('img').css('opacity',1);
	
});	</script>
						
						<div class="wrapper-box">
							<div class="wrapper-box-top"></div>
							<div class="wrapper-box-contents" id="filter-box">
								<div id="bg-retino">
								<h1 class="team-name-title"><?=$squadra['Squadre']['Denominazione'];?></h1>	
								<ul class="preview-page team-back-link">
									<li>
											<a title="pagina precedente" href="javascript:history.back()">pagina precedente</a>
									</li>
								</ul>
								<div class="clear"></div>

								<!-- element /site/filter_squadre_img -->
								<?=$this->element('site/filter_squadre_img');?>
								
								<div id="filter-pad">
									<div id="wrapper-select">
									<div class="select-filter">
										<h3>Stagione di riferimento *</h3>
										<div class="select-box little-select selcect-year">
											<div class="content-select">
												<span class="selected-value">Stagione di riferimento...</span>
												
												<input type="hidden" name="anno_id" data-squadra="<?=$this->params['pass'][0];?>" class="select-value" autocomplete="off" />
												
												<div class="values-of-select">
													<ul>
		
														
														<? foreach ($anni as $anno): ?>
														
															<li data-value="<?=$anno;?>"><?=$anno;?></li>
														
														<? endforeach; ?>
														
														
													</ul>
												</div><!-- close values-of-select -->
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div><!-- close select-box -->
									</div><!-- close select filter -->
									
									<div class="select-filter filter-campionato hidden">
										<h3>Seleziona torneo di riferimento *</h3>
										<div class="select-box select-girone middle-select">
											<div class="content-select">
												<span class="selected-value">Seleziona torneo di riferimento...</span>
												
												<input type="hidden" name="campionati_id" class="select-value" autocomplete="off" />
												
												<div class="values-of-select">
													<ul>
		
														

														
													</ul>
												</div><!-- close values-of-select -->
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div><!-- close select-box -->
									</div><!-- close select filter -->
									
									<div class="select-filter select-squadre hidden">
										<h3 class="no-required">Seleziona squadra di appartenenza</h3>
										<div class="select-box middle-select yellow">
											<input name="filter_team" type="hidden" value="true" autocomplete="off" />
											<span class="checkbox-unset hidden"></span>
											<div class="content-select">
												<span class="selected-value">Seleziona squadra di appartenenza...</span>
												
												
												<input type="hidden" name="squadra_id" class="select-value" autocomplete="off" />
												<input type="hidden" name="girone_id" class="select-value" autocomplete="off" />
												
												<div class="values-of-select">
													<ul>

													</ul>
												</div><!-- close values-of-select -->
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div><!-- close select-box -->
									</div><!-- close select filter -->
									
									</div><!-- wrapper-select -->
									<div class="clear"></div>
									<ul class="switch-button switch-filters hidden">
										<li class="switch-value hidden"><input type="hidden" value="calendario" autocomplete="off" name="filter_select" /></li>
										<li data-value="calendario" class="selected">Calendario</li>
										<li data-value="classifica">Classifica</li>
										<li data-value="marcatori">Marcatori</li>
										<li data-value="diffidati">Diffidati</li>
										<li data-value="espulsi">Espulsi</li>
										<li data-value="squalificati">Squalificati</li>
										<li data-value="disciplinari">Disciplinare</li>
										<li data-value="comunicazioni">Comunicazioni</li>
										<li class="yellow yellow-squadre hidden" id="team-button" data-value="squadra">Squadra/Atleti</li>
									</ul>
									<!--
									<ul class="switch-button switch-checkbox hidden" id="team-button">
									
										
											<li class="yellow"><input name="filter_team" type="hidden" value="true" autocomplete="off" /><span class="checkbox-unset"></span><span class="checkbox-label">Squadra/Atleti</span></li>
									
									</ul>
									-->
									<div class="clear"></div>
								</div><!-- close filter-pad -->
								
								<div class="table-container">
									<ul class="switch-table-menu">
										<li><a href="#" title="01">01</a></li>
										<li><a href="#" title="02">02</a></li>
										<li class="selected"><a href="#" title="03">03</a></li>
										<li><a href="#" title="04">04</a></li>
									</ul>
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									<table>
										<tr class="table-header">
											<th>Giorno</th>
											<th>Ora</th>
											<th>Impianto</th>
											<th>Partita</th>
											<th>Ris.</th>
											<th>Note</th>
											<th>Gara</th>
											<th>&nbsp;</th>
										</tr>
										<tr class="selected">
											<td>lun. <span class="number">07/07/2011</span></td>
											<td><span class="number">17:00</span></td>
											<td><a href="#" title="SALES C7">PALLANOVOLI 2011 SOCCER</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td><span class="number">12 - 10</span></td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"><a href="#" title="Stampa nota gara"><img src="/img/icon-pdf.png" width="16" height="16" alt="Stampa nota gara" /></a></td>
										</tr>
										<tr class="alternate">
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
										<tr>
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
										<tr class="alternate">
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
<tr>
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
										<tr class="alternate">
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
<tr>
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
										<tr class="alternate">
											<td>lun. 07/07/2011</td>
											<td>17:00</td>
											<td><a href="#" title="SALES C7">SALES C7</a></td>
											<td><a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a> - <a href="#" title="SELECAO ARGENTINOS">SELECAO ARGENTINOS</a></td>
											<td>12 - 10</td>
											<td>RINV.</td>
											<td></td>
											<td class="last-column"></td>
										</tr>
									</table>
									<div class="other-info-row">
										<p>
											<b>Risposa:</b> <a href="#" title="INTERNACIONAL DE BORGO ALLEGRI">INTERNACIONAL DE BORGO ALLEGRI</a>
										</p>
									</div>
									<div class="other-function-row">
										
										<div class="left">
											<h3>Comunicazioni</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu velit ac magna dapibus porta. Integer sollicitudin, leo quis auctor pellentesque, enim leo auctor velit, at laoreet mauris ligula id lectus. Aliquam vulputate, lacus nec dapibus tempor, magna dui venenatis arcu, adipiscing euismod justo nisl id nulla.</p>
										</div> <!-- close left -->
										<div class="right">
										<h4>Cerca giornata di sfida</h4>
										<div class="select-box middle-select grey">
											<div class="content-select">
												<span class="selected-value">SALES C7</span>
												<div class="values-of-select">
													<ul>
														<li>CAMPO DI CALCETTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
														<li>CAMPO DI CALCETTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
														<li>CAMPO DI CALCIOTTO 1</li>
													</ul>
												</div><!-- close values-of-select -->
											</div>
											<div class="close-select"></div>
											<div class="clear"></div>
										</div><!-- close select-box -->
										
										<ul class="match-results-menu">
											<li><a href="#" title="Giornata 02">Giornata 02</a></li>
											<li><a href="#" title="Giornata 12">Giornata 12</a></li>
											<li><a href="#" title="Giornata 15">Giornata 15</a></li>
										</ul>
										</div><!-- close right -->
										<div class="clear"></div>									
									</div>
									
									</div><!-- close results-box -->
								</div><!-- close table-container -->
								<div class="clear"></div>
							</div>		
							</div><!-- close filter-box -->
							<div class="wrapper-box-bottom"></div>
						</div>
