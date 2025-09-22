<script type="text/javascript">

	function centerImg() {
		
		var div = $('.squadra-img');
		var img = div.children('img');
	
		var first = div.width() / 2;
		var second= img.width() / 2;
		
		var margin_top = first - second;
		
		img.css('margin-left',margin_top);
		
		$('img').css('opacity',1);
		
	}	

$(function() {

	$('img').css('opacity',0);
	
	$('.edit-team').click(function(){
	
		var obj = $(this);
	
		if(obj.attr('data-value') == 'edit') {
			
			location.hash = '#edit'
			
			$('.container-squadra-img').addClass('hidden');
			$('.filters-element').addClass('hidden');
			$('.photo-gallery').addClass('hidden');
			$('.edit-squadra').slideDown('slow');
			$('.container-squadra-info').removeClass('hidden');
			$('.container-albo-oro').removeClass('hidden');
			$('.insertAlbo').removeClass('hidden');
			$('.albo-oro').addClass('hidden');
			
			obj.attr('data-value','undo');
			obj.find('a').text('chiudi');
			
			if($('.team-history').length == 0) $('<h4 class="history-title">La storia</h4><div class="team-history"></div>').insertBefore($('.container-albo-oro'));
			else 							   $('.team-history').css('min-height','50px');
			/* edit function */
			
			console.log('width: ' + $(this).width());
			console.log('height: ' + $(this).height());
			
			var new_textarea = $('<textarea>').css('width', $('.team-history').width() - 10)
											  .css('height', '80px')
											  .css('padding-bottom','10px')
											  .css('padding-top','2px')
											  .attr('id', 'story-textarea')
											  .val($.trim($('.team-history').text()));
			
			$('.team-history').empty();
			$('.team-history').append(new_textarea).append(
			
				'<div class="input">' +
					'<input type="submit" class="saveStory" value="salva" />' +
				'</div>'			
			
			);
			
			/* ------------- */
			
			centerImg();
		
		} else {
			
			location.hash = '';
			location.reload();
			
		}
	
	});
	
	$(".saveStory").live('click', function(){
	
		var squadra_id = '<?=$id;?>';
		
		$('.ok-message').remove();
		
		ajaxLoader('show');
		
		$.post('/squadres/saveStory/' + squadra_id, {"storia":$("#story-textarea").val()}, function(){
			
			$('<div class="ok-message">Dati salvati con successo.</div>').insertAfter($("#story-textarea"));
			
			$('.ok-message').fadeOut(5000);
			
		});
		
		ajaxLoader('hide');
	
	});
	
	$("#UploadTag").live('change', function(){
		
		if($(this).val() == 'Trofeo') {
			$("#UploadYearTrofeo").parent('div').removeClass('hidden');
			$("#UploadYearTrofeo").parent('div').addClass('required');
		}
		else {
			$("#UploadYearTrofeo").parent('div').addClass('hidden');
			$("#UploadYearTrofeo").parent('div').removeClass('required');
		}
		
	});	

});	

$(document).ready(function(){
	
	if(location.hash == '#error') {
		
		$('.edit-team-button').click();
		location.hash = '#edit';
		
		$('#UploadPercorso').parent('div.file').append('<div class="error-message">Dimensione massima file: 500kb, Estensioni ammesse: jpeg,png</div>');
		
	} else if(location.hash == '#ok') {
		
		$('.edit-team-button').click();
		location.hash = '';		
		
	} else if(location.hash == '#edit') {
		
		$('.edit-team-button').click();
		
	} else {
		
		$('.container-squadra-img').removeClass('hidden');
		$('.filters-element').removeClass('hidden');
		$('.photo-gallery').removeClass('hidden');
		
	}
	
});

$(window).load(function() {

	centerImg();
	
});

</script>	
<?
//Ripartizione upload

$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo
if(isset($uploads['Logo'][0])) {
	
	$logo = $thumbnail->link(array('path' => $uploads['Logo'][0]['path'], 'h' => 50, 'q' => 100, 'f' => 'png')); 
	
} else {
	
	$logo = $thumbnail->link(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));
	
}

?>					
<div class="wrapper-box">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">
			
			<ul class="tab-profile-menu">
				<li><a href="/gestione/profilo/<?=$this->Session->read('Login.data.id');?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
				<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
				<li class="selected"><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
				<li class="edit-team" data-value="edit"><a class="edit-team-button" href="javascript:;" title="Modifica">Modifica</a></li>
			</ul>
			<h1 class="profile-name-title">Gestione profilo atleta // <span><?=$this->Session->read('Login.data.cognome');?> <?=$this->Session->read('Login.data.nome');?></span></h1>
			<div class="clear"></div>
				<h3 class="title-profile-menu"><img class="team-logo" src="<?=$logo;?>" /> <span><?=$squadra['Squadre']['Denominazione'];?></span> <span class="team-manage">Gestione squadra</span></h3>
			<div class="clear"></div>
			
								<!-- element: /site/filte_squadre_logged_img -->
								
								<?=$this->element('site/filter_squadre_logged_img', array('uploads' => $uploads)); ?>
								
								<div class="filters-element hidden"><!-- filters-element -->
								
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
										<li data-value="calendario">Calendario</li>
										<li data-value="classifica">Classifica</li>
										<li data-value="marcatori">Marcatori</li>
										<li data-value="diffidati">Diffidati</li>
										<li data-value="espulsi">Espulsi</li>
										<li data-value="squalificati">Squalificati</li>
										<li data-value="disciplinari">Sanzioni</li>
										<li class="yellow" id="team-button-edit" data-value="squadra_logged">Modifica atleti</li>
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
								
								</div><!-- close filters-element -->
								

			<div class="clear"></div>
			</div><!-- close contents-box -->
		 </div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->								
