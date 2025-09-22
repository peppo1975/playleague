<style>
.table>tbody>tr.selected>td, .table>tbody>tr.selected>th, .table>tbody>tr>td.active, .table>tbody>tr>th.active, .table>tfoot>tr.selected>td, .table>tfoot>tr.selected>th, .table>tfoot>tr>td.active, .table>tfoot>tr>th.active, .table>thead>tr.selected>td, .table>thead>tr.selected>th, .table>thead>tr>td.active, .table>thead>tr>th.active{
	background: #FFFC80;

}
.results-box {
}
.table-container {
	opacity: 0;

	padding-top: 30px;
	padding-bottom: 30px;
}

.filter-container {

	margin-top: 30px;
}
p.text-day-search {
	line-height: 36px;
	margin-bottom: 0px !important;
}
.switch-table-menu.pagination {
	margin: 0 0 20px 0!important;
}
.match-results-menu {
	margin-bottom: 0px !important;
	margin-top: 30px;
}


</style>
<div class="tab-squadra">
		<div class="clear"></div>
		<div class="content-tab">

<script type="text/javascript" src="/js/layoutv2.js"></script>
<div class="filters-element"><!-- filters-element -->
								
								<div id="filter-pad">

									<blockquote class="with-borders fields-filter-container filter-container-squadra">
									<div id="wrapper-select" class="row">

									<div class="select-filter col-md-6">

										<div class="select-box little-select selcect-year">
											<div class="content-select">
												<label class="active-value"><b>Stagione</b></label>
							
												<div>
												<select data-plugin-selectTwo class="form-control populate" autocomplete="off" name="anno_id" data-squadra="<?=$this->params['pass'][0];?>">
														<option value="" selected>Seleziona stagione...</option>

														<? foreach ($anni as $anno): ?>
														
															<option value="<?=$anno;?>"><?=$anno;?></option>
														
														<? endforeach; ?>
														
														
														
												</select>
												</div>
											</div>
										</div>
									</div>
									
									<div class="select-filter filter-campionato col-md-6">
				
										<div class="select-box select-girone middle-select">
												<label class="active-value"><b>Torneo</b></label>
												<div>
												<select data-plugin-selectTwo class="form-control populate" disabled autocomplete="off" name="campionati_id" data-squadra="<?=$this->params['pass'][0];?>">
														<option value="">Seleziona torneo...</option>

														
														
												</select>
												</div>
										</div><!-- close select-box -->
									</div><!-- close select filter -->
									
									<div class="select-filter select-squadre hidden col-md-4">
										<h3 class="no-required">Seleziona squadra di appartenenza</h3>
										<div class="select-box middle-select yellow">
											<input name="filter_team" type="hidden" value="true" autocomplete="off" />
											<span class="checkbox-unset hidden"></span>
											<div class="content-select">
												<span class="active-value">Seleziona squadra di appartenenza...</span>
												
												
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
									</blockquote>
									<div class="clear"></div>


									<div class="tabs tabs-center text-center">

									<ul id="switch-button-logged" class="switch-button switch-filters hidden nav nav-tabs">
								
										<li class="switch-value hidden"><input type="hidden" value="calendario" autocomplete="off" name="filter_select" /></li>
										<li data-value="calendario" class="active"><a href="javascript:;">Calendario</a></li>
										<li data-value="classifica"><a href="javascript:;">Classifica</a></li>
										<li data-value="marcatori"><a href="javascript:;">Marcatori</a></li>
										<li data-value="diffidati"><a href="javascript:;">Diffidati</a></li>
										<li data-value="espulsi"><a href="javascript:;">Espulsi</a></li>
										<li data-value="squalificati"><a href="javascript:;">Squalificati</a></li>
										<li data-value="disciplinari"><a href="javascript:;">Sanzioni</a></li>
									</ul>
									<!--
									<ul class="switch-button switch-checkbox hidden" id="team-button">
									
										
											<li class="yellow"><input name="filter_team" type="hidden" value="true" autocomplete="off" /><span class="checkbox-unset"></span><span class="checkbox-label">Squadra/Atleti</span></li>
									
									</ul>
									-->
									<div class="clear"></div>
					
								<div class="table-container tab-content">
								<div class="text-center">
									<ul class="switch-table-menu pagination pagination-sm">
			
									</ul>
								</div>
									<div id="results-box">
									
					
									</div><!-- close results-box -->
								</div><!-- close table-container -->
								</div>
								</div><!-- close filters-element -->			
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>	