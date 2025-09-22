<? /*if(count($anni)) {

	echo $this->element('/site/filter_squadre');
	
	?>
	<script type="text/javascript">
	
		$(function(){
		
			var anno = $('input[name=anno_id]');
			    anno.next('.values-of-select').find('li:eq(0)').click();
		
		});
	
	</script>
	<?
	
} else {

	echo "Nessun campionato presente";

}
*/

?>

<style type="text/css">
	
#tabsNavigationSimpleIcons1 .owl-stage {
	margin: 0 auto;
}

</style>

<script type="text/javascript" src="/js/readmore.min.js"></script>
<script type="text/javascript" src="/js/layoutv2.js"></script>
<?
//Ripartizione upload


$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo


if(isset($uploads['Logo'][0])) {


	
	$squadra['Info']['Logo']=$uploads['Logo'][0]['path'];	
}

?>	

<script>
function twitterbtn(url) {
  sharelink = "http://twitter.com/share?url="+url;
  newwindow=window.open(sharelink,'name','height=400,width=600');
  if (window.focus) {newwindow.focus()}                                                                                                                                
  return false;
}   
</script>


		
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="">Squadre</li>
						<li class="">

			<? $pass = explode("-", $_GET['option']); ?>
			<? $tipo = $pass[0]; ?>
			<? $sesso= $pass[1]; ?>
			<? $url = array('maschile','femminile'); ?>
			<a href="/<?=$url[$sesso];?>/<?=$tipo;?>/<?=$sesso;?>">
			<? if ($tipo == 0): ?>

						Calcio a 5 <?if($sesso == 0):?>maschile<?else:?>femminile<?endif;?>
					
						
					<? else: ?>
					
						Calcio a 7 <?if($sesso == 0):?>maschile<?else:?>femminile<?endif;?>
					<? endif; ?>
					</a>
						</li>

						</li>
						<li class="active">
						<?= $squadra['Squadre']['Denominazione'];?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
		
		<div class="row">
			<div class="col-md-12">


		<div class="post-content">
		<div class="row">
		<div class="col-md-8">
				<div class="pull-left" style="margin-right: 15px;">

			<? if (!empty($squadra['Info']['Logo'])): ?>
															<div class="img-thumbnail" style="width: 70px; height: 70px;">
															<div style="background-image:url(<?=$thumbnail->link(array('path' => $squadra['Info']['Logo'],'w' => 70, 'f' => 'png'));?>); background-size: contain; background-repeat: no-repeat; background-position: center center; width: 100%; height: 100%;"/>&nbsp;
															</div>
															</div>
															<? else: ?>
															
															<div class="img-thumbnail text-center" style="width: 70px; height: 70px;">

															<i class="fa fa-3x fa-shield" style="line-height: 63px;"></i>
															
															</div>
			<? endif; ?>
				</div>
				<h2 class="detail-squadra-name">


				<?= $squadra['Squadre']['Denominazione'];?>

				</h2>
				</div>

		<div class="col-md-2 col-xs-6">
		<div class="counters">
<div class="counter">
										<strong data-to="<?=$squadra['Info']['Stagioni'];?>"><?=$squadra['Info']['Stagioni'];?></strong>
										<label>Stagioni</label>
									</div>
		</div>
		</div>

		<div class="col-md-2 col-xs-6">
		<div class="counters">
<div class="counter">
										<strong data-to="<?=$squadra['Info']['Campionati'];?>"><?=$squadra['Info']['Campionati'];?></strong>
										<label>Campionati</label>
									</div>
		</div>
		</div>

	</div>
		</div>
				<hr />

<div class="tabs tabs-bottom tabs-center tabs-simple">
								<ul class="nav nav-tabs">
									<li class="active">
										<a data-toggle="tab" href="#tabsNavigationSimpleIcons1" aria-expanded="true">
											<span class="featured-boxes featured-boxes-style-6 p-none m-none">
												<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
													<span class="box-content p-none m-none">
														<i class="icon-featured fa fa-shield"></i>
													</span>
												</span>
											</span>									
											<p class="mb-none pb-none">Squadra</p>
										</a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tabsNavigationSimpleIcons4" aria-expanded="false">
											<span class="featured-boxes featured-boxes-style-6 p-none m-none">
												<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
													<span class="box-content p-none m-none">
														<i class="icon-featured fa fa-group"></i>
													</span>
												</span>
											</span>									
											<p class="mb-none pb-none">Atleti</p>
										</a>
									</li>

									<li class="">
										<a data-toggle="tab" href="#tabsNavigationSimpleIcons2" aria-expanded="false">
											<span class="featured-boxes featured-boxes-style-6 p-none m-none">
												<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
													<span class="box-content p-none m-none">
														<i class="icon-featured fa fa-trophy"></i>
													</span>
												</span>
											</span>									
											<p class="mb-none pb-none">Albo d'oro</p>
										</a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tabsNavigationSimpleIcons3" aria-expanded="false">
											<span class="featured-boxes featured-boxes-style-6 p-none m-none">
												<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
													<span class="box-content p-none m-none">
														<i class="icon-featured fa fa-calendar"></i>
													</span>
												</span>
											</span>									
											<p class="mb-none pb-none">Stagioni e tornei</p>
										</a>
									</li>
						
								</ul>
								<div class="tab-content">
									


									<div id="tabsNavigationSimpleIcons4" class="tab-pane">


		<div id="filter-pad">

									<blockquote class="with-borders fields-filter-container filter-container-squadra">
									<div id="wrapper-select" class="row">

									<div class="select-filter col-md-6">

										<div class="select-box little-select selcect-year-roster">
											<div class="content-select">
												<label class="active-value"><b>Stagione</b></label>
							
												<div>
												<select id="stagione" data-plugin-selectTwo class="form-control populate" autocomplete="off" name="anno_new_id" data-squadra="<?=$this->params['pass'][0];?>">
														<option value="">Seleziona stagione...</option>
														<? $godpig = 0; ?>
														<? foreach ($anni as $anno): ?>
														
															<option value="<?=$anno;?>" <? if ($godpig==0):?>selected<?$godpig=1;?><?endif;?>><?=$anno;?></option>
														
														<? endforeach; ?>
														
														
														
												</select>
												</div>
											</div>
										</div>
									</div>

									<div class="select-filter col-md-6" style="display: block">

										<div class="select-box little-select selcect-year-roster">
											<div class="content-select">
												<label class="active-value"><b>Campionato</b></label>
							
												<div>
												<select id="campionati" data-plugin-selectTwo class="form-control populate" autocomplete="off" name="anno_new_id" data-squadra="<?=$this->params['pass'][0];?>">
														<option value="">Seleziona campionato...</option>
														
														
														
														
												</select>
												</div>
											</div>
										</div>
									</div>
									
									<script type="text/javascript">

									squadra_id = "<?=$squadra['Squadre']['Denominazione'];?>".toLowerCase();


									function getChampFromYear(squadra_id, year, callback)
									{
										ajaxLoader('show');
										$.get(`/squadres/getChampFromYear/${squadra_id}/${year}`, callback);
									}

									function inflateSelect(select, champs)
									{
										$(select).find("option:not(:first)").remove();
										$(select).find("option:first").change();

										for(champ of champs)
										{
											$("<option></option>")
												.attr("value", champ.id)
												.html(champ.value)
												.appendTo($(select));
										}
									}

									function hide($el)
									{
										//$el.hide();
									}
									function show($el)
									{
										//$el.show();
									}


									$(document).on("change", "#stagione", function(){
										var $selected = $(this).find("option:selected");
										var year = $selected.val();
										var squadra_id = <?=$squadra['Squadre']['Squadra']?>;
										hide($("#campionati").closest(".select-filter"))
										if(year)
										{

											getChampFromYear(squadra_id, year, function(res){
												ajaxLoader('hide');
												inflateSelect("#campionati", JSON.parse(res));
												show($("#campionati").closest(".select-filter"));
											});
										}
										
									});

									$(document).on("change", "#campionati", function(){
										var campionati_id = $(this).val();
										if(campionati_id)
										{
											reloadRoaster(<?=$squadra['Squadre']['Squadra'];?>, campionati_id);

										}
										
									});

									function reloadRoaster(squadra_id, campionati_id)
									{
										ajaxLoader('show');
										$.get(`/squadres/brendicrist/${squadra_id}/${campionati_id}`, function(res){

											$(".roster").html(res);
											ajaxLoader('hide');
										});
									}

									$(document).ready(function() {
										$("#stagione").change();
										$("#stagione").change(function() {

											if ($(this).val() != "") {
												ajaxLoader('show');
												$.get('/squadres/roster/<?=$squadra['Squadre']['Squadra'];?>/' + $(this).val(),function(data) {

													$(".roster").html(data);

													ajaxLoader('hide');


												},'html');

											}

										});

									});

									</script>
				
									</div><!-- wrapper-select -->
									</blockquote>
									<div class="clear"></div>


									<div class="tabs tabs-center text-center">

									<!-- <ul id="switch-button-logged" class="switch-button switch-filters hidden nav nav-tabs">
								
										<li class="switch-value hidden"><input type="hidden" value="calendario" autocomplete="off" name="filter_select" /></li>
										<li data-value="calendario" class="active"><a href="javascript:;">Calendario</a></li>
										<li data-value="classifica"><a href="javascript:;">Classifica</a></li>
										<li data-value="marcatori"><a href="javascript:;">Marcatori</a></li>
										<li data-value="diffidati"><a href="javascript:;">Diffidati</a></li>
										<li data-value="espulsi"><a href="javascript:;">Espulsi</a></li>
										<li data-value="squalificati"><a href="javascript:;">Squalificati</a></li>
										<li data-value="disciplinari"><a href="javascript:;">Sanzioni</a></li>
									</ul> -->
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

									<div class="roster">
									<?=$roster;?>
									</div>

									</div>
									<div id="tabsNavigationSimpleIcons1" class="tab-pane active">
									

								<? if(!empty($squadra['Squadre']['Storia'])): ?>
								
									<div class="row">
									<div class="col-md-12">
									<div class="storia">
										<?=$squadra['Squadre']['Storia'];?>
									</div>
									<hr />
									</div>
									</div>
									<script type="text/javascript">


									$(document).ready(function() {

if ($(".storia").height() > 94) {
$('.storia').readmore({
  speed: 500,
  collapsedHeight: 94,
  lessLink: '<div class="text-center"><label href="#" class="label label-info" style="display: inline-block; width: auto; margin-top: 15px; cursor: pointer;">Chiudi</label></div>',
  moreLink: '<div class="text-center"><label href="#" class="label label-info" style="display: inline-block; width: auto; margin-top: 15px; cursor: pointer;">Leggi tutto</label></div>',
});

}
									});

									</script>
								<? endif; ?>		


									<div class="row">
<div class="col-md-6 text-center">
			<div class="squadra-img img-thumbnail <? if(!isset($uploads['Squadra'])): ?>hidden<? endif; ?>">
									
									<? if(isset($uploads['Squadra'])): ?>
									
									<? 
									$link = $thumbnail->link(array('path' => $uploads['Squadra'][0]['path'], 'w' => 475,'h' => 270, 'q' => 100)); 
									?>

											<div class="lightbox" data-plugin-options='{"delegate": "a", "type": "image", "gallery": {"enabled": true}, "mainClass": "mfp-with-zoom", "zoom": {"enabled": true, "duration": 300}}'>
									<a href="<?=$thumbnail->link(array('path' => $uploads['Squadra'][0]['path'], 'w' => 960, 'q' => 100));?>">
									<img src="<?=$link;?>" alt="<?=($uploads['Squadra'][0]['title'])? $uploads['Squadra'][0]['title']:$uploads['Squadra'][0]['name'];?>" />															
									</a>
									</div>
									<? endif; ?>
									
				</div><!-- close squadra-img -->
								
</div>

<div class="col-md-6">
<div class="stagioni">
<h4>Stagioni</h4>
<p class="lead" style="font-size: 15px;"><?=implode(", ",$squadra['Info']['StagioniList']);?></p>
</div>

			
									<div class="trofei-sponsor <? if(!isset($uploads['Trofeo']) && !isset($uploads['Sponsor'])&&!isset($uploads['Coccarda'])): ?>hidden<? endif; ?>">
			
				<? if(isset($uploads['Coccarda'])): ?>

					<div class="riga-squadra">
										
											<h4>Vittorie</h4>
											<div class="row">



											<? foreach($uploads['Coccarda'] as $k => $upload): ?>
											<? 
											$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 70,'h' => 70, 'q' => 100)); 
											?>
											<div class="col-md-3 col-xs-4" <? if(($k+1) % 3 == 0): ?>class="no-margin-sponsor"<? endif; ?><? if(!empty($upload['title'])): ?> rel="" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">

											<div class="img-thumbnail" style="margin-bottom: 15px; width: 75px; height: 75px;">
												<div style="display: block; background-image: url(<?=$link;?>); background-repeat: no-repeat; background-position: center center; background-size: contain; width: 70px; height: 70px" width="100%" height="100%" alt=""
title="" data-placement="top" data-toggle="tooltip" type="button" data-plugin-tooltip="" data-original-title=""

												 >&nbsp;</div>
											</div>
											</div>					
											<? endforeach; ?>										
											</div>
					</div>
										<? endif; ?>	

									
										<div class="riga-squadra sponsor <? if(!isset($uploads['Sponsor'])): ?>hidden<? endif; ?>">
										



										<? if(isset($uploads['Sponsor'])): ?>
										
											<h4>Sponsor</h4>
											<div class="lightbox" data-plugin-options='{"delegate": "a", "type": "image", "gallery": {"enabled": true}, "mainClass": "mfp-with-zoom", "zoom": {"enabled": true, "duration": 300}}'>
											<div class="row">



											<? foreach($uploads['Sponsor'] as $k => $upload): ?>
											<? 
											$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 70,'h' => 70, 'q' => 100, 'zc' => 1)); 
											?>
											<div class="col-md-3 col-xs-4" <? if(($k+1) % 3 == 0): ?>class="no-margin-sponsor"<? endif; ?><? if(!empty($upload['title'])): ?> rel="" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">

											<div class="img-thumbnail" style="margin-bottom: 15px;">
											<a href="<?=$thumbnail->link(array('path' => $upload['path'], 'w' => 960, 'q' => 100));?>">
												<img src="<?=$link;?>" width="100%" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>"
title="" data-placement="top" data-toggle="tooltip" type="button" data-plugin-tooltip="" data-original-title=""

												 />
											</a>
											</div>
											</div>					
											<? endforeach; ?>										
											</div>
											</div>
											
										<? endif; ?>	
											



										</div><!-- close sponsor -->
									

	

									</div><!-- close trofei-sponsor -->

					
									


</div>
<hr />
				<div class="col-md-12">

<? $data['Upload'] = $uploads['Gallery']; ?>
<?=$this->element("site/slider",array('countMultimedia' => count($data['Upload']),'data' => $data,'ext_img' => array("jpg","png")));?>


									</div>
									<div class="col-md-12">
			<div class="post-block post-share text-center">
									<h3 class="heading-primary"><i class="fa fa-share"></i>Condividi pagina squadra</h3>
									<div class="addthis_native_toolbox" style="width: 380px; margin: 0 auto;"></div>
									</div>
								</div>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56e14a002088d571"></script>
									</div>

									</div>
									<div id="tabsNavigationSimpleIcons2" class="tab-pane">
								
			<div class="row">
			<div class="col-md-12">
			<? if(count($squadra['SquadreAlbo'])): ?>
			
					
					<table class="table table-striped table-condensed table-responsive table-bordered">
					
						<thead>
							<th class="albo-champ">Campionato</th>
							<th class="albo-pos">Posizione</th>
						</thead>					
					
					<? $squadra['SquadreAlbo']=array_reverse($squadra['SquadreAlbo']); ?>
					<? foreach($squadra['SquadreAlbo'] as $k => $albo): ?>
					
					<tr>
							<td class="albo-champ"><?=$albo['Campionato'];?></td> 
								<td class="albo-pos"><?=$albo['Posizione'];?></td>
						</tr>
					
					<? $i = $k; ?>
					
					<? endforeach; ?>					
					
					</table>
				
			<? else: ?>
			
			<div class="alert alert-warning text-center">
			Non &egrave; presente un albo d'oro per questa squadra
			</div>
			<? endif; ?>	
			</div>
			<div class="col-md-12">
				
						
										<div class="trofei <? if(!isset($uploads['Trofeo'])): ?>hidden<? endif; ?>">
										
										<? if(isset($uploads['Trofeo'])): ?>
										
<div class="lightbox" data-plugin-options='{"delegate": "a", "type": "image", "gallery": {"enabled": true}, "mainClass": "mfp-with-zoom", "zoom": {"enabled": true, "duration": 300}}'>


<? $data['Upload'] = $uploads['Trofeo']; ?>
<?=$this->element("site/slider",array('countMultimedia' => count($data['Upload']),'tooltip' => 1,'data' => $data,'ext_img' => array("jpg","png")));?>

</div>
<? /*
											<div class="row">
											<? 
											
											$uploads['Trofeo'] = array_orderby($uploads['Trofeo'], 'yearTrofeo', SORT_DESC);
											
											foreach($uploads['Trofeo'] as $k => $upload): 
											
											?>
											
											<? 
											$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 120,'h' => 120, 'q' => 100, 'zc' => 1)); 
											?>
											<div  class="col-md-3 col-xs-4" <? if(($k+1) % 11 == 0): ?>class="no-margin-trofei"<? endif; ?> <? if(!empty($upload['title'])): ?> rel="" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
											<div class="img-thumbnail" style="margin-bottom: 15px;">
												<a href="<?=$thumbnail->link(array('path' => $upload['path'], 'w' => 960, 'q' => 100));;?>"><img src="<?=$link;?>" width="100%" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>"
title="" data-placement="top" data-toggle="tooltip" type="button" data-plugin-tooltip="" data-original-title="<?=($upload['title'])? $upload['title']:$upload['name'];?>"
												 /></a>
											</div>
											</div>						
											<? endforeach; ?>										
											</div>
</div>
*/ ?>
										<? endif; ?>	
										
										</div><!-- close trofei -->

			</div>
			</div>

									</div>
									<div id="tabsNavigationSimpleIcons3" class="tab-pane">
										
			<?=$this->element('site/squadre/' . 'statistiche_new', array('squadra' => $squadra, 'uploads' => $uploads));?>


									</div>
					
								</div>
							</div>

			</div>

		</div>

	</div>
</div>


<div class="wrapper-box" style="display: none;">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">
		
			<div class="clear"></div>
				<h3 class="title-profile-menu title-profile-menu-site"><img class="team-logo" src="<?=$logo;?>" /> <span><?=$squadra['Squadre']['Denominazione'];?></span></h3>
			<div class="clear"></div>
			<div class="search-team">
					<script type="text/javascript">
					$(function(){
						$("#id_squadra").change(function(){
							if($(this).val() != '') {
							
								var obj = $(this);
								var val = obj.val();

								ajaxLoader('show');
								
								$.get('/sections/getSlugMod/' + val, function(ret){
								
									ajaxLoader('hide');
									if(ret.anno == '') ret.anno = obj.attr('data-anno');
									location.href = '/squadra/dettaglio/' + val + '/' + ret.slug + '?option=' + obj.attr('data-tipo') + '-' + obj.attr('data-sesso') + '-' + ret.anno; 
								
								},'json');
								
							}
						});
					});
						</script>			
				<div class="input autocomplete-input">
					<input onblur="javascript:if(this.value == '') this.value = 'Ricerca la squadra...';" onclick="javascript:this.value = '';" id="ricercaSquadre" class="autoComplete" type="text" data-dest="id_squadra" data-url="/sections/getSquadreAjax/<?=$tipo;?>/<?=$sesso;?>" value="Ricerca la squadra...">
					<input data-tipo="<?=$tipo;?>" data-sesso="<?=$sesso;?>" data-anno="<?=$anni[key($anni)];?>" type="hidden" id="id_squadra" />					
				</div>
			</div>
			<?
			
			$first_letter = $squadra['Squadre']['Denominazione'][0];
			if(is_numeric($first_letter)) $first_letter = '0-9';
			
			?>
			<div class="prev-page"><a href="<?=$back;?>#<?=$first_letter;?>" title="Torna alla lista delle squadre">Indice squadre</a></div>

			<?=$this->element('site/squadre/' . $element, array('squadra' => $squadra, 'uploads' => $uploads));?>
		
			<div class="clear"></div>
			</div><!-- close contents-box -->
		 </div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->	