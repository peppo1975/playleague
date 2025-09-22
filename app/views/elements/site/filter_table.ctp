<? //GIUSEPPE  20/11/2016 -> filtra la classe
	$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
	$nameClass = $classPage["Name"];
?>
<style>
	.table>tbody>tr.active>td, .table>tbody>tr.active>th, .table>tbody>tr>td.active, .table>tbody>tr>th.active, .table>tfoot>tr.active>td, .table>tfoot>tr.active>th, .table>tfoot>tr>td.active, .table>tfoot>tr>th.active, .table>thead>tr.active>td, .table>thead>tr.active>th, .table>thead>tr>td.active, .table>thead>tr>th.active{
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
	.nav-tabs {
	
	border-bottom: 0px !important;
	
	}
	
	.table-container {
	max-height: 450px; 
	overflow-y: scroll;
	}
</style>

<div class="filter-container tabs">
	<style>
		@media screen and (min-width: 990px) {
		.initial{
		width: auto;
		padding: 0;
		}
		}
	</style>
	<ul class="nav nav-tabs switch-button switch-filters" style="display: none">
		<input type="hidden" value="calendario" autocomplete="off" name="filter_select" />
		<? if($nameClass=="primary" || $nameClass == "secondary"): //GIUSEPPE 20/11/2016 ?>
		<li data-value="calendario" class="col-xs-12 initial selected" data-twitter="Calendario" data-facebook="il calendario"><a href="javascript:;">Calendario</a></li>
		<li data-value="classifica" class="col-xs-12 initial" data-facebook="la classifica" data-twitter="Classifica"><a href="javascript:;">Classifica</a></li>
		<li data-value="marcatori"  class="col-xs-12 initial" data-facebook="i marcatori" data-twitter="Marcatori"><a href="javascript:;">Marcatori</a></li>
		<li data-value="diffidati"  class="col-xs-12 initial" data-facebook="i diffidati" data-twitter="Diffidati"><a href="javascript:;">Diffidati</a></li>
		<li data-value="espulsi"  class="col-xs-12 initial" data-facebook="gli espulsi" data-twitter="Espulsi"><a href="javascript:;">Espulsi</a></li>
		<li data-value="squalificati"  class="col-xs-12 initial" data-facebook="gli squalificati" data-twitter="Squalificati"><a href="javascript:;">Squalificati</a></li>
		<li data-value="disciplinari"  class="col-xs-12 initial" data-facebook="le sanzioni" data-twitter="Sanzioni"><a href="javascript:;">Sanzioni</a></li>
		<li data-value="comunicazioni"  class="col-xs-12 initial" data-facebook="le comunicazioni" data-twitter="Comunicazioni"><a href="javascript:;">Comunicazioni</a></li>
		<li class="yellow hidden" id="team-button" data-value="squadra" data-facebook="la rosa" data-twitter="Squadra"><a href="javascript:;">Squadra/Atleti</a></li>
		<? elseif($nameClass=="quaternary"):?>
		<li data-value="calendario" class="col-xs-12 initial selected" data-twitter="Calendario" data-facebook="il calendario"><a href="javascript:;">Calendario</a></li>
		<li data-value="classifica" class="col-xs-12 initial" data-facebook="la classifica" data-twitter="Classifica"><a href="javascript:;">Classifica squadre</a></li><!-- -->
		<!--	<li data-value="ranking-femminili"  class="col-xs-12 initial" data-facebook="i marcatori" data-twitter="Marcatori"><a href="javascript:;">Classifica campionato femminile</a></li>
		<li data-value="ranking-maschili"  class="col-xs-12 initial" data-facebook="i marcatori" data-twitter="Marcatori"><a href="javascript:;">Classifica campionato maschile</a></li>-->
	
		
		<li data-value="comunicazioni"  class="col-xs-12 initial" data-facebook="le comunicazioni" data-twitter="Comunicazioni"><a href="javascript:;">Comunicazioni</a></li>
		<li class="yellow hidden" id="team-button" data-value="squadra" data-facebook="la rosa" data-twitter="Squadra"><a href="javascript:;">Squadra/Atleti</a></li>
		<? endif; ?>
	</ul>
	
	<div class="">
		<div class="tab-content table-container">
			
			
			
		</div><!-- close table-container -->
	</div>
</div>