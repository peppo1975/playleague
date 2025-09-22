						<div class="container-squadra-info">
								
								
									<h4 class="history-title">La storia</h4>
									<div class="team-history">
										<textarea id="story-textarea"><?=$squadra['Squadre']['Storia'];?></textarea>
										<textarea class="hidden" id="hidden-textarea"><?=$squadra['Squadre']['Storia'];?></textarea>
										<div class="input">
											<input type="submit" class="saveStory" value="salva" />
										</div>										
									</div>
								
								<div class="table-container-files container-albo-oro"><!-- container-albo-oro -->									
									<h4 class="albo-title">Albo d'oro</h4>
									<div id="results-box-albo">
									<script type="text/javascript">
									
									var data = '';
									
									$(function(){
										function updateAlbo() {
											$.get('/squadres/updateAlboTr/<?=$squadra['Squadre']['Squadra'];?>', function(html_data){
												$('.tr-albo-doro').remove();
												$(html_data).insertAfter('#alboTable .table-header');
												var tr_prev = $('.insertAlbo').prev('tr');
												if(tr_prev.hasClass('alternate')) $('.insertAlbo').removeClass('alternate');
												else $('.insertAlbo').addClass('alternate');
											},'html');
										}
										//Add
										$('.container-albo-oro').delegate('.AlboAdd','click', function(){
											var error = 0;
											data  += '&Squadra=<?=$squadra['Squadre']['Squadra'];?>';
											$('.insertAlbo div.error-message').remove();
											$('.insertAlbo input.required').each(function(){
												var obj = $(this);
												if(obj.val() == '') {
													obj.parent('td').append($('<div class="error-message">Campo obbligatorio.</div>'))
													error = 1;
												} else if(obj.attr('data-min') != 'undefined' && obj.val().length < obj.attr('data-min')) {
													obj.parent('td').append($('<div class="error-message">Lunghezza minima: ' + obj.attr('data-min') + ' caratteri.</div>'))
													error = 1;													
												}
												else if(obj.attr('data-max') != 'undefined' && obj.val().length > obj.attr('data-max')) {
													obj.parent('td').append($('<div class="error-message">Lunghezza massima: ' + obj.attr('data-max') + ' caratteri.</div>'))
													error = 1;													
												}												
												data += '&' + obj.attr('id') + '=' + obj.val();
											});
											if(error == 0) {
												$.post('/squadres/newAlbo', data, function(ret){
													if(ret.error == 0) {
														updateAlbo();
														data = '';
														$('.AlboReset').addClass('hidden');
														$('tr.insertAlbo td input').val('');
													}
												},'json');
											}
										});
										//Edit
										$('.container-albo-oro').delegate('.AlboEdit','click', function(){
											var tr = $(this).parents('tr');
											  data = '&id=' + tr.attr('data-id');
											  var campionato = tr.find('.td_campionato').text();
											  var posizione  = tr.find('.td_posizione').text();
											  $('.insertAlbo td input#Campionato').val(campionato);
											  $('.insertAlbo td input#Posizione').val(posizione);
											  $('.AlboReset').removeClass('hidden');
										});										
										//Delete
										$('.container-albo-oro').delegate('.AlboDelete','click', function(){
											var tr = $(this).parents('tr');
											if(confirm('Sei sicuro di voler eliminare?')) {
												$.get('/squadres/deleteAlbo/' + tr.attr('data-id'), function(ret2){
													if(ret2.delete == 1) {
														updateAlbo();
													}
												},'json');
											}
										});
										$('.container-albo-oro').delegate('.AlboReset','click', function(){
											data = '';
											$('.insertAlbo div.error-message').remove();
											$(this).addClass('hidden');
											$('tr.insertAlbo td input').val('');
										});										
									});
									</script>
										<table class="table-matches" id="alboTable">
											<tr class="table-header">
												<th class="first">Campionato</th>
												<th>Posizionamento</th>
												<th>Opzioni</th>
											</tr>
											
											<? $i = 0; ?>
											
											<? foreach($squadra['SquadreAlbo'] as $k => $albo): ?>
											
											<tr class="tr-albo-doro <? if(($k +1) % 2 == 0): ?>alternate<? endif; ?>" data-id="<?=$albo['id'];?>">
												<td class="td_campionato"><?=$albo['Campionato'];?></td>
												<td class="td_posizione"><?=$albo['Posizione'];?></td>
												<td class="tools">
													<a href="javascript:;" class="AlboEdit"><img src="/img/timmyshare/icon_edit.png"></a>												
													<a href="javascript:;" class="AlboDelete"><img src="/img/timmyshare/icon_delete.png"></a>
												</td>
											</tr>
											
											<? $i = $k; ?>
											
											<? endforeach; ?>
											
											<tr class="insertAlbo hidden <? if(($i +2) % 2 == 0): ?>alternate<? endif; ?>">
												<td>
													<input data-min="5" data-max="40" type="text" id="Campionato" class="required big" />											
												</td>
												<td>
													<input data-max="12" type="text" class="required" id="Posizione" />
												</td>
												<td class="tools">
													<a href="javascript:;" class="AlboAdd"><img src="/img/timmyshare/icon_add.png"></a>
													<a href="javascript:;" class="AlboReset hidden"><img src="/img/timmyshare/icon-filter-delete-th.png"></a>													
												</td>
											</tr>																							
										</table>
									</div>
									
								</div><!-- close container-albo-oro -->	
								<div class="clear"></div>																
								
								<div class="edit-squadra table-container-files" style="display: block; opacity: 1;">
								
								<?=$this->element('/backend/js_form_index', array('model' => 'Squadre', 'url' => '/squadres/teams', 'limit' => 100));?>
								
								<script type="text/javascript">
								
									$(function(){
										
										$("#saveUpload").submit(function(){
											
											$(this).find('.error-message').remove();
											
											ajaxLoader('show');
											
											var tag = $('#UploadTag');
											
											var error = 0;
											
											if($("#UploadPercorso").val() == '') {
												
												$("#UploadPercorso").parent('div').append('<div class="error-message">Immagine obbligatoria.</div>');
												error = 1;
												
											}
											
											if(tag.val() == 'nullo') {
												
												tag.parent('div').append('<div class="error-message">Tag immagine obbligatorio.</div>');
												error = 1;
												
											}
											
											if($("#UploadYearTrofeo").val() == '' && $("#UploadYearTrofeo").parent('div').hasClass('required')) {
												
												$("#UploadYearTrofeo").parent('div').append('<div class="error-message">Anno obbligatorio.</div>');
												error = 1;
												
											}											
											
											ajaxLoader('hide');
											
											if(error == 1) return false;
											
										});										
										
									});
								
								</script>
								
								<?=$this->Form->create('Squadre', array('url' => '/squadres/saveUpload/' . $squadra['Squadre']['Squadra'], 'type' => 'file', 'id' => 'saveUpload'));?>
								
								<?
								
								$tags['nullo'] = '';
								
								$tags['Trofeo'] = 'Albo d\'oro';
								$tags[''] = 'Galleria fotografica';	
									
								if(empty($uploads['Squadra'])) $tags['Squadra'] = 'Immagine squadra';
								if(empty($uploads['Logo']))    $tags['Logo'] = 'Logo squadra';
								
								$tags['Sponsor'] = 'Sponsor squadra';
								
								?>
								
								<?=$backend->getFiles('squadra_id',$squadra['Squadre']['Squadra'], array(
								
									'tag'        => $tags,
									'element' => 'site/filter_squadre_logged_file'
								
								));?>

								<?=$this->Form->end();?>
								</div><!-- close edit-squadra -->							

								
						</div><!-- close container-squadra-info -->

								<div class="clear"></div>
								