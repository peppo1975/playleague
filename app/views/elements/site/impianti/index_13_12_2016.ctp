<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Impianti</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
		
		<div class="row">
			<div class="col-md-12">
				<h2>Impianti sportivi</h2>
				<p class="lead">Questi tutti gli impianti sportivi a disposizione</p>
				<hr />
			<div class="contents-block-left">
<?

//debug($data);

?>
<div class="contents-text">
				<p>Utilizza la nostra applicazione per la prenotazione degli impianti sportivi. Puoi verificare la diponibilit&agrave; degli orari e date che preferisci. Il sistema offre un lista aggiornata dalla data odierna ai 14 giorni successivi.
				Non ti resta che selezionare l'orario di tuo interesse e bloccare la prenotazione.</p>
			
</div>
<blockquote class="with-borders fields-filter-container">
<div class="row">
	<div class="form-groups">
											<div class="col-md-6 type-calcio">

		<div class="row">
												<label class="control-label col-md-12">Tipologia</label>
											</div>

												<select data-plugin-selectTwo class="form-control populate" data-filter="calcio">
												<option value="" selected>Seleziona una tipologia...</option>
												<option value="is5" data-filter="is5" data-title="is5" title="Calcio a 5">Calcio a 5</option>
												<option value="is7" data-filter="is7" data-title="is7" title="Calcio a 7">Calcio a 7</option>
												<option value="is11" data-filter="is11" data-title="is11" title="Calcio a 11">Calcio a 11</option>
											
												</select>
											</div>

											<div class="col-md-6 type-city">

		<div class="row">
												<label class="control-label col-md-12">Citt&agrave;</label>
											</div>

												<select data-plugin-selectTwo class="form-control populate" data-filter="citta">
													<option value="" selected>Seleziona una citt&agrave;...</option>

													<? foreach($citta as $dati): ?>
							
															<option value="<?=$dati;?>"><?=$dati;?></option>
							
													<? endforeach; ?>
												</select>
											</div>
											
	</div>
</div>
</blockquote>
	<br />

<? if(count($data)): ?>	




	<div class="row notfound" style="display: none;">
	<div class="col-md-12">

	<div class="alert alert-warning">
	Siamo spiacenti, nessun risultato corrisponde ai criteri di ricerca.
	</div>
	</div>
	</div>

		<? foreach($data as $block): ?>
		
	<div class="row campi" data-hour="<?=$block['Campi']['countHour'];?>" class="block-box" data-is5="<?=($block['Campi']['is5'] != 1)? $block['Campi']['is5']:'is5';?>"  data-is11="<?=($block['Campi']['is11'] != 1)? $block['Campi']['is11']:'is11';?>"  data-is7="<?=($block['Campi']['is7'] != 1)? $block['Campi']['is7']:'is7';?>" data-citta="<?=$block['Campi']['Citta'];?>">
	
			<div>
				
				<?// Mostra anteprima ?>
				
				<?
				//Genero link
				$block_link    = '/impianti/' . $block['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($block['Campi']['Descrizione'],'-'));
				if($block['Campi']['descrizione_campo'] == '' && $block['Upload'] == array()) $block_link = "";
				$address       = (($block['Campi']['Indirizzo'] == '')? $block['Campi']['Indirizzo'] : $block['Campi']['Indirizzo'] . ' -') . ' ' . $block['Campi']['Citta'] . ' ' . (($block['Campi']['Provincia'] == '')? $block['Campi']['Provincia'] : '(' . $block['Campi']['Provincia'] . ')') . ' ' . (($block['Campi']['Telefono'] == '')? $block['Campi']['Telefono'] : '- Tel.' . $block['Campi']['Telefono']);
				$strip_address = ereg_replace('[^a-zA-Z0-9]','',$address);
				$type_campo = '';
				if($block['Campi']['is5'] && $block['Campi']['is7'])      $type_campo = 'Calcio a 5, calcio a 7';
				elseif($block['Campi']['is5'] && !$block['Campi']['is7']) $type_campo = 'Calcio a 5';
				elseif($block['Campi']['is7'] && !$block['Campi']['is5']) $type_campo = 'Calcio a 7';
				else													  $type_campo = '';
				if($block['Campi']['isEsclusive'] == 1) $type_campo .= ' - IN ESCLUSIVA';

				if ($block['Campi']['is11']==1) $type_campo .= ', Calcio a 11';

				$strip_type    = ereg_replace('[^a-zA-Z0-9]','',$type_campo);
				?>
				
				<? if($block['Campi']['img_evidenza'] != ''): ?>
				
				<div class="block-preview-img col-md-2">
				
				<?
				
				$title = ($block['Campi']['descrizione_evidenza'] != '')? $block['Campi']['descrizione_evidenza'] : $block['Campi']['name_evidenza'];
				$desc  = $block['Campi']['descrizione_evidenza'];
				$link  = $thumbnail->link(array('path' => $block['Campi']['img_evidenza'], 'w' => 120,'h' => 75, 'zc' => 1));
				
				?>
				
					<a title="<?=$title;?>" class="img-thumbnail" href="<?=$block_link;?>" style="background-image: url(/img/img-loader.gif); background-position: center center; background-repeat: no-repeat;">
						<img data-original="<?=$link;?>" alt="" src="" style="display: block;" class="lazy" width="120" height="75"  />
					</a>
				
				</div>	
				
				<? else: ?>
				
				<div class="block-preview-img col-md-2">

					<a title="<?=$title;?>" class="img-thumbnail" href="<?=$block_link;?>">
						<img src="/img/website/ml-impianti-default.jpg" alt="<?=$title;?>" />
					</a>
					
				</div>

				<? endif; ?>
				
				<? if(!empty($strip_type) || !empty($strip_address)): ?>

				<div class="block-preview col-md-6">
					<? if($block_link != ''): ?>
					
					<a href="<?=$block_link;?>" class="field-title" title="<?=$block['Campi']['Descrizione'];?>"><?=$block['Campi']['Descrizione'];?></a>
					
					<? else: ?>
					
					<b><?=$block['Campi']['Descrizione'];?></b>
					
					<? endif; ?>
									<?if(!empty($strip_address)):?><p class="other-info"><?=$address;?><br /><?if(!empty($strip_type)):?><label class="label label-info"><?=$type_campo;?></label><?endif;?>
									</p><?endif;?>
					

									
				</div>

				<div class="col-md-4">
						<? if($block['Campi']['countHour'] > 0): ?>
					
						<a href="<?=$block_link;?>" title="Prenota" class="booking btn btn-success pull-right mb-xl"><i class="fa fa-check"></i> Prenotabile</a>
					
					<? endif; ?>	
						
				</div>
				<? endif; ?>
				
				<div class="clear"></div>				
			
			</div>
	<div class="col-md-12">
	<hr />
	</div>
	</div><!--Div blocchi -->

		<? endforeach; ?>

<? endif; ?>
			</div><!-- close contents-box-left -->
			<div class="contents-box-right">
			<script type="text/javascript">
			$(function(){

				$(window).load(function() {
			$("img.lazy").lazyload({
				threshold: 1500
});
		});
				filterMe();
				function filterMe() {

					var type_calcio = $(".type-calcio select").val();
					var type_city = $(".type-city select").val();

					$(".campi").show();
					$(".campi hr").show();
					$(".notfound").hide();

					if (type_calcio != "") {

						$(".campi[data-" + type_calcio + "!='" + type_calcio + "']").hide();
					}

					if (type_city != "") {

						$(".campi[data-citta!='" + type_city + "']").hide();
					}

					if ($(".campi:visible").length == 0) $(".notfound").show();

					$("#main-custom hr:visible:last").hide();
				}

				$(".type-calcio select, .type-city select").change(function() {
					filterMe();
				});

				var li = $('.contents-box-right-container ul li');
				
				var types  = '';
				var values = new Array();
				var types_array = new Object();
				var j = 0;
				
				li.click(function(e,params){
				
					var obj   = $(this);					    
					var	type  = $.trim(obj.children('a').attr('data-filter'));
					var value = $.trim(obj.children('a').attr('data-title'));
					
					location.hash = value;
					
					if(obj.hasClass('selected')) obj.removeClass('selected');
					else 						 obj.addClass('selected');

					li.not(obj).removeClass('selected');

					ajaxLoader('show');
					
					$('.blocks').find('.block-box').each(function(){
				
						var impianto = $(this);
						
							if($.trim(impianto.attr('data-' + type)) == value) {
								impianto.css('display','block');
							} else {
								impianto.css('display','none');
							}						
							
					});
					
					if(value.length == '' || $('.contents-box-right-container ul li.selected').length == 0) {
						$('.blocks').find('.block-box').show();
						location.hash = '';
					}
					
					ajaxLoader('hide');
					
				});
				
				$(document).ready(function(){
					if(location.hash != '') {
					var hash = location.hash;
						li.each(function(){
							if(hash.replace("#","") == $.trim($(this).children('a').attr('data-title')))
								$(this).click();
						});
					}
				});
				
			});
			</script>


</div>
</div>
		 <div class="col-md-3" style="display: none;">
					<h4 class="heading-primary">Tipo</h4>
						<ul class="nav nav-list narrow">
							<li><a href="javascript:;" data-filter="is5" data-title="is5" title="Calcio a 5">Calcio a 5</a></li>
							<li><a href="javascript:;" data-filter="is7" data-title="is7" title="Calcio a 7">Calcio a 7</a></li>
							<li><a href="javascript:;" data-filter="is11" data-title="is11" title="Calcio a 11">Calcio a 11</a></li>
						</ul>					
						<hr />	
						<? if(count($citta)): ?>
						<h4 class="heading-primary">Province</h4>
						<ul class="province nav nav-list narrow">
							<? foreach($citta as $dati): ?>
							<li>
								<a href="javascript:;" data-filter="citta" data-title="<?=$dati;?>" title="<?=$dati;?>"><?=$dati;?></a>
							</li>
							<? endforeach; ?>
						</ul>
						<? endif; ?>
			</div><!-- contents-box-right-container -->

			
</div><!-- close wrapper-box -->
