<?

App::Import('Model', $model);
$models = new $model;

?>
					<script type="text/javascript">

						$(function() {
							
							var n_cols = $(".index_table tr").find("th").length;
						
							$("<tr class='top_scroller'><td colspan='" + n_cols + "'><div class='left_scroller'><div class='filler'>&nbsp;</div></div></td></tr>").insertAfter(".index_table tr:first");
							
							$(".top_scroller td").css('padding',0);
						
							$(".left_scroller").css('max-width',$(".table_container").innerWidth()).css('overflow-x','scroll');
							$(".left_scroller .filler").width($(".index_table").width()).css('height',0);
							$(".left_scroller").scrollLeft($(".table_container").scrollLeft());
							
							$(".left_scroller").bind('scroll',function() {
								$(this).css('margin-left',$(this).scrollLeft());
								$(".table_container").scrollLeft($(this).scrollLeft());
								
							});
						
							$(".table_container").bind('scroll',function() {
								$(".left_scroller").css('margin-left',$(this).scrollLeft());
								$(".left_scroller").scrollLeft($(this).scrollLeft());
								
							});
						
							$(window).resize(function() {
								
							$(".left_scroller").css('max-width',$(".table_container").innerWidth()).css('overflow-x','scroll');
							$(".left_scroller .filler").width($(".index_table").width()).css('height',0);
								
							});
							
						});

					
						$(function() {



							$(".index-disabled-switch").live('click',function(e) {
							
									e.stopPropagation();
									
									var me = $(this);
									
									var disabled = $(this).attr('data-disabled');
									
									var old = disabled;
									
									if (disabled == 1) disabled = 0;
									else disabled = 1;
							
									$.get("<?=$url;?>/switchdisabled/" + $(this).attr('data-id') + "/" + disabled,function(ret) {
										
										$(me).attr('data-disabled',disabled);
										$(me).find('img').attr('src',ret.src);
							
										
									},'json');
								
							});							
						

							
							$(".formAdd").live('submit',function(e) {
								
									var form = $(this);
									var scheda = $(form).closest('.scheda');
									e.preventDefault(); // <-- important
									
									var trgt = scheda;
									
									if ($.browser.msie) trgt = form;
									
									var useIframe = false;
									
									if ($(this).find('input[type="file"]').length > 0) useIframe = true;
									
									$(this).ajaxSubmit({
										target: trgt,
										iframe: useIframe,
										beforeSubmit: function() {
											timmyloader('show');
										},
										success: function(responseText) {
											
											timmyloader('hide');
											
											if ($.trim(responseText) == 'ADD_OK') {
												
												$(scheda).slideUp(100);
											
												$(".switch").removeClass('selected');
												
												location.reload();
											}
											
											if ($.trim(responseText) == 'RELOAD_OK') {
												
												$(scheda).slideUp(100);
											
												location.href = '<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'index'));?>';
												
											}
											
										}
									});
								
							});
							
							$(".timmyFileEdit").live('submit',function(e) {

									e.preventDefault(); // <-- important
									$(this).ajaxSubmit({
										target: $("#timmybox_container"),
										beforeSubmit: function() {
											timmyloader('show');
										},
										success: function() {
														var indirizzo = $('.formAdd').attr('action') + '?modded=true';
														$("#timmy_overlay").remove();

														$.post(indirizzo,{ modded: true },function(ret) {
									
														$(".view_mode").html(ret);
									
														},'html');
														
														timmyloader('hide');
										}
									});
								
							});
							
							$("#formReset").live('click',function() {
								
								$(this).closest('.scheda').slideUp(300);
								$(".switch").removeClass('selected');
								
							});
							
							$("#formResetFields").live('click',function() {
								
								$(':input',$(this).closest('form'))
								 .not(':button, :submit, :reset, :hidden, [readonly]')
								 .removeAttr('checked')
								 .removeAttr('selected')
								 .not(':checkbox, :radio')
								 .removeAttr('value')

							});
							
							$(".index-set-limit option[value='<?=$limit;?>']").attr('selected','selected');
							
							$(".index-set-limit select").live('change',function() {
								
								var limit = $(this).val();
								
								$.post("<?=$url;?>",{ "defaultLimit": limit },function() {
									
										location.reload();
									
								});
								
							});
							
							$(".index-order-argument select").ready(function () {
							
								$.get('/orders/getOrder/<?=$model;?>', function(ret) {
															
									$(".index-order-type select option[value='" + ret.last.Order.order_type + "']").attr("selected", "selected");
									$(".index-order-argument select option[value='" + ret.last.Order.argument + "']").attr("selected", "selected");
								
								}, 'json');
							
							});
							
							$(".index-order-argument select").live('change', function() {
							
							var argument = $(".index-order-argument select option:selected").val();

								$.get('/orders/setOrder/<?=$model;?>/' + argument, function(ret) {
								
									location.reload();
								
								}, 'json');
										
							});
							
							$(".index-order-type select").live('change', function() {
							
							var type = $(".index-order-type select option:selected").val();
							
								$.get('/orders/setOrder/<?=$model;?>/' + type, function(ret) {
								
									location.reload();
								
								}, 'json');
							
							});
						
							$(".index-select-all").live('click',function() {
								
								$(".index-select-checkbox").attr('checked','checked');
								
							});
							
							$(".index-file-edit").live('click',function() {
								
								timmy_load("/admin/uploads/edit/" + $(this).attr('data-id'));
								
							});
							
							$(".index-select-checkbox").live('click',function(e) {
								e.stopPropagation();
							});
							
							$(".index-revert-selection").live('click',function() {
								
								$(".index-select-checkbox").each(function() {
									
									if ($(this).is(':checked')) {
										
										$(this).removeAttr('checked');
										
									} else {
										
										$(this).attr('checked','checked');
										
									}
									
								});
								
							});
							
							
							$(".index-files-revert-selected").live('click',function() {
								
								$(".index-file-checkbox").each(function() {
									
									if ($(this).is(':checked')) {
										
										$(this).removeAttr('checked');
										
									} else {
										
										$(this).attr('checked','checked');
										
									}
									
								});
								
							});
							$(".index-row-delete").live('click',function(e) {
								
								e.stopPropagation();
								if (confirm("Cancellare l'elemento selezionato?")) {
									
									var com = $(this);
									
									$.get("<?=$url;?>/delete/" + $(this).attr('data-id'),function(data) {
									
										if(data == null) data = 0;
										
										if(data.ret != false || data == 0) {
										
											$(com).closest('.index-row').remove();
										
										} else {
										
											alert('Impossibile cancellare.');
										
										}
										
									},'json');
									
								}
								
							});
							
							$('.index-file-evidenza').live('click', function(){
							
								var obj = $(this);
							
								var file_id = $(this).attr('data-id');
								var primaryKey = camelize('<?=$models->primaryKey;?>');	
								var element_id = $("#<?=$model;?>"+primaryKey).val();
								
								$.get('/uploads/evidenza/'+element_id+'/'+file_id+'/<?=$model;?>', function(ret){
								
									var title = new Array('Metti in evidenza','Non in evidenza');
								
									$('.index-file-evidenza').children('img').attr('src', '/img/timmyshare/icon_evidenza_0.png');
									$('.index-file-evidenza').attr('data-tip-title', title[0]);
									obj.children('img').attr('src', '/img/timmyshare/icon_evidenza_'+ret.check+'.png');
									obj.attr('data-tip-title', title[ret.check]);									
									
								
								},'json');
							
							});							
							
							$(".index-file-delete").live('click',function() {
								
								if (confirm("Cancellare l'elemento selezionato?")) {
									
									var com = $(this);
									
									$.get("/admin/uploads/delete/" + $(this).attr('data-id'),function() {
										
										$(com).closest('.index-file-row').remove();
										
										if($('.form_table').find('tr.index-file-row').length == 0) {
										
											$("#files_form").show();
											$('.operations_files_bar').hide();
											$('.form_table').hide();
										
										}										
										
									});
									
								}
								
							});
							
							$(".index-files-select-all").live('click',function() {
								
								$(".index-file-checkbox").attr('checked','checked');
								
							});
							
							
							$(".index-files-delete-selected").live('click',function() {
								
								var num = $(".index-file-checkbox:checked").length;
								
								if (num != 0) {
									
									var ids = new Array;
									
									if (confirm(num + " elementi verranno cancellati, continuare?")) {
										
										$(".index-file-checkbox:checked").each(function() {
									
													var com = $(this);
									
													$.get("/admin/uploads/delete/" + $(this).attr('data-id'),function() {
										
															$(com).closest('.index-file-row').remove();
										
													});
										});
					
									}
									
								}
								
							});
							
							$(".index-delete-selected").live('click',function() {
								
								var num = $(".index-select-checkbox:checked").length;
								
								if (num != 0) {
									
									var ids = new Array;
									
									if (confirm(num + " elementi verranno cancellati, continuare?")) {
										
										$(".index-select-checkbox:checked").each(function() {
									
											ids.push($(this).val());
											
										});
										
										$.post("<?=$url;?>/deleteall/",{ "ids" : ids },function(ret) {
											
											$(".index-select-checkbox:checked").closest('.index-row').remove();
											
										});
										
									}
									
								}
								
							});
							
							$(".quickSearch").live('click',function() {
								
								var enabled = $(this).attr('data-quick-enabled');
								
								if (enabled == "false") {
									
									$(this).val('');
									
									
									$(this).attr('data-quick-enabled','true');
									
								}
								
							});
				
							$(".quickSearch").live('blur',function() {
								
								var enabled = $(this).attr('data-quick-enabled');
								
								if ($(this).val() == '') {
									
									$(this).attr('value',$(this).attr('data-default-text'));
									$(this).attr('data-quick-enabled','false');
								}
								
							});
							
							$("#quickSearchGo").live('submit',function(e) { e.preventDefault(); return false; });
							
							$(".quickSearch").live('keydown',function(e) {
								
								if ($(this).val() != "") {
									
									if (e.keyCode == 13) {
										
										timmyloader('show');
										
										$.post('<?=$url;?>',{ quickSearch: $(this).val() },function() {
											
												location.reload();
											
												timmyloader('hide');
											
										});
										
									}
									
								}
								
							});
							
							/* Salvo sessione per selezionare le righe delle tabelle */
							
							$(".index-row-edit").bind('click', function(){
							
								/* Riga selezionata */
								var element_id = $(this).attr('data-id');
								var tr = $(this).parents('tr');
									tr.addClass('selected').css('background-color', '#A7F993');
									
								$("#index_table").find('tr').not(tr).removeClass('selected').css('background-color','#fff');
								$("#index_table").find('tr:odd').not(tr).removeClass('selected').css('background-color','#EFEFEF');
								
								$.get('/admin/dashboards/writeSessionModel/' + element_id + '/<?=$model;?>');
								
								/* Record visualizzati, numerazione */
								
								$('.this-record').remove();
								
								var this_record = $("table.#index_table tr").index(tr) - 1;
								var tot_record  = $('.count-record').find('.record');
								
								var span = $('<span>').addClass('this-record').text(this_record + ' di ');
								
								span.insertBefore(tot_record);
							
							});
							
							$('.index-row').bind('click', function(){
								
								var element_id = $(this).attr('id');
								var tr = $(this);
									tr.addClass('selected').css('background-color', '#A7F993');
									
								$("#index_table").find('tr').not(tr).removeClass('selected').css('background-color','#fff');
								$("#index_table").find('tr:odd').not(tr).removeClass('selected').css('background-color','#EFEFEF');
								
								$.get('/admin/dashboards/writeSessionModel/' + element_id + '/<?=$model;?>');
								
								/* Record visualizzati, numerazione */
								
								$('.this-record').remove();
								
								var this_record = $("table.#index_table tr").index(tr) - 1;
								var tot_record  = $('.count-record').find('.record');
								
								var span = $('<span>').addClass('this-record').text(this_record + ' di ');
								
								span.insertBefore(tot_record);									
								
							});
							
							$.get('/admin/dashboards/readSessionModel/<?=$model;?>', function(ret){
								
								if(ret != null) {
									
									var tr = $("#" + ret.element_id);
										tr.css('background-color', '#A7F993').addClass('selected');
									
									$("#index_table").find('tr').not(tr).css('background-color','#fff').removeClass('selected');
									$("#index_table").find('tr:odd').not(tr).css('background-color','#EFEFEF').removeClass('selected');	
									
									if(tr.offset() != null) {
									
									$("html, body").animate({ scrollTop: tr.offset().top - 20 },100);
									
									}	
									
								}
								
							},'json');	
							
							/* ------------------------------------------------------- */						
							
							$(".reset_quick_search").live('click',function(e) {
					
										
										$.post('<?=$url;?>',{ deleteQuickSearch: 'true' },function() {
											
												location.reload();
											
										});
								
							});
															
								$(document).ready(function() {
								
									if($('.index-order-argument').length != 0) {
								
										$("#index_table tbody.content").sortable({
										
											items: ".index-row",
											
											cursor: "pointer",
											
											axis: "y",
											
											opacity: "0,6",
										
											update: function() {
											
												var model = '<?=$model;?>';
												
												var order = $("#index_table tbody.content").sortable('toArray');
												
												$("#index_table tr.index-row").removeClass('alterna');
													
												$("#index_table tr:even").addClass('alterna');
																					
												$.post('/orders/sortableOrder/' + model, { Data: order });
																							
											}
										
										});
										
										$("#index_table tbody.content").disableSelection();
									
									}
								
								});

								
								
								//se la tabella è vuota la nascondo
								
								if($("#index_table").find('tr.index-row').length == 0) {
								
									$('.table_container').hide();
									$('.operations_bar').hide();
								
								}
							
						});
						
						
				
						
					</script>
						
						<?
						
							$defaultText = $session->read(Inflector::Camelize($this->params['controller']) . "." . $model . ".quickSearch");
						
							if ($defaultText == '') {
							
								$quickEnabled = "false";
								
							} else {
							
								$quickEnabled = "true";
								
							}
						
						?>
						
						<h1 class="page_title"><?=$pageTitle;?></h1>
						

						
						<div class="quick_search">
							
							<form id="quickSearchGo">
								<div class="text">
									<input type="text" name="quickSearch" autocomplete="off" value="<?=(($defaultText != "")? $defaultText : 'Cerca nella tabella...');?>" data-quick-enabled="<?=$quickEnabled;?>" class="quickSearch" data-default-text="Cerca nella tabella..." />
									
									<? if ($defaultText != ""): ?><span class="reset_quick_search"></span><? endif; ?>

								</div>
							</form>
						</div>
					
						<? if ($besideQuickSearch != ""): ?>
						
							<div class="beside_quick_search">
							
								<?=$besideQuickSearch;?>
							
							</div>
						
						<? endif ;?>
					
						<ul class="main_functions">
						
							<? if (isAllowed($this->params['controller'],'admin_add') && $allow_add == true): ?>
							<li class="switch" data-dest="anagrafica" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'add'));?>"><a class="add" href="#" title="Aggiungi nuovo">Aggiungi nuovo</a></li>
							<? endif; ?>
							<? if (isAllowed($this->params['controller'],'admin_search') && $allow_search == true): ?>
							<li class="switch" data-dest="ricerca" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'search'));?>"><a class="search"  href="#" title="Ricerca">Ricerca avanzata</a></li>
							<? endif; ?>
							<? if (isAllowed($this->params['controller'],'admin_filters') && $allow_filters == true): ?>
							<li class="switch" data-dest="filtra" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'filters'));?>"><a class="filter" href="#" title="Filtri">Filtri</a></li>
							<? endif; ?>
							<? if ($this->params['action'] == 'admin_index' && isAllowed('Xls','admin_index')): ?>
							<li><a class="export" href="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'index'));?>?is_xls=true" title="Esporta XLS">Esporta XLS</a></li>
							<? endif; ?>
						</ul>
						
					<div class="clear"></div>

					<div class="scheda view_mode">
				
					</div><!-- close scheda view_mode -->
					
					<div class="scheda anagrafica">
					
					</div><!-- close scheda anagrafica -->
					
					<div class="scheda ricerca">
		
					</div>
					
					
					<div class="scheda filtra">
						<form>
							<div class="form_header">
								<h2>Ordinamento tabella</h2>
								<ul>
									<li><input type="submit" value="annulla" /></li>
									<li><input type="submit" value="filtra tabella" /></li>
								</ul>
								<div class="clear"></div>
							</div><!-- close form_header -->
							<div class="clear"></div>
							
							<div class="form_data">
							<div class="input">
								<input type="text" value="congnome" readonly="readonly" />							
							</div>
							<div class="input">
								<input name="congnome" type="radio" value="" /><span>uguale</span>
								<input name="congnome" type="radio" value="" /><span>contiene</span>
								<input name="congnome"  type="radio" value="" /><span>maggiore di</span>
								<input name="congnome"  type="radio" value="" /><span>minore di</span>
							</div>
							<div class="input">
								<input type="text" value="" />							
							</div>
							<div class="clear"></div>
							<div class="input">
								<input type="text" value="nome" readonly="readonly" />							
							</div>
							<div class="input">
								<input name="nome" type="radio" value="" /><span>uguale</span>
								<input name="nome" type="radio" value="" /><span>contiene</span>
								<input name="nome"  type="radio" value="" /><span>maggiore di</span>
								<input name="nome"  type="radio" value="" /><span>minore di</span>
							</div>
							<div class="input">
								<input type="text" value="" />							
							</div>
							<div class="clear"></div>
							<div class="input">
								<input type="text" value="indirizzo" readonly="readonly" />							
							</div>
							<div class="input">
								<input name="indirizzo" type="radio" value="" /><span>uguale</span>
								<input name="indirizzo" type="radio" value="" /><span>contiene</span>
								<input name="indirizzo"  type="radio" value="" /><span>maggiore di</span>
								<input name="indirizzo"  type="radio" value="" /><span>minore di</span>
							</div>
							<div class="input">
								<input type="text" value="" />							
							</div>
							<div class="clear"></div>							
							</div><!-- close form_data -->
						</form>
					</div><!-- close scheda filtra -->	
						
					<div class="clear"></div>
					
					<?=$this->element('backend/operations_bar',$reference);?>
					
					<? if ($session->check(Inflector::Camelize($this->params['controller']) . ".searchData") || $session->check(Inflector::Camelize($this->params['controller']) . ".searchFilters")): ?>

						<? 
						
							$searchData = $session->read(Inflector::Camelize($this->params['controller']) . ".searchData");
							$searchExtra = $session->read(Inflector::Camelize($this->params['controller']) . ".searchFilters");
						?>
			
					<? ob_start(); ?>
				
					<? $searchFields = false; ?>
				
					<? foreach ($fields as $key => $value): ?> 
					
						<? if ($backend->getField($searchData,$value['field']) != ""): $searchFields = true; ?>
						
			
								<li class="searchRow"><span class="fieldName"><?=$key;?></span> <span class="fieldValue"><?=$backend->getField($searchData,$value['field']);?></span> <a class="fieldResetLink" href="<?=$url;?>/unset/<?=$value['field'];?>"><span class="fieldReset"></span></a></li>
			
						<? endif; ?>
						
						
						<? if (isset($searchExtra[$value['field']]) && isset($searchExtra[$value['field']]['type']) && isset($searchExtra[$value['field']]['value'])): $searchFields = true; ?>
						
								<li class="searchRow"><span class="fieldName"><?=$key;?> '<b><?=$backend->getFilterType($searchExtra[$value['field']]['type']);?></b>'</span> <span class="fieldValue"><?=$searchExtra[$value['field']]['value'];?></span> <a class="fieldResetLink" href="<?=$url;?>/unset/<?=$value['field'];?>"><span class="fieldReset"></span></a></li>
						
						<? endif ;?>
					
					<? endforeach; ?>
					
					<? $searchFilters = ob_get_contents(); ob_end_clean(); ?>
					
					<? if ($searchFields == true): ?>
					
						<ul class="searchData">
							<?=$searchFilters;?>
						</ul>
						
					<? endif; ?>
					
					<? endif; ?>
					
						
					<? $show_status = ''; ?>
															
					<div class="table_container">
						<table id="index_table" class="index_table index_<?=strtolower($this->name);?>">
						<tbody class="content">
							<tr class="th_row sortable_disabled">
								<th class="first">&nbsp;</th>

								<? $i = 0; ?>

								<? foreach ($fields as $key => $value): ?>
																
											<? if ($value['field'] != "$model.disabled"): ?>
											<th class="<?=(($i==count($fields)-1)? 'last' : '')?> th_<?=strtolower(Inflector::slug($key));?>"><?=$backend->getOrder($value,$key);?></th>
											<? else: ?>
											<? $show_status = $key; ?>
											<? endif; ?>
											
											<? $i++; ?>

								<? endforeach; ?>
	
							</tr>
							<? $j = 0; ?>
							<? foreach ($data as $row): ?> 
							<? if ($allow_edit == true): ?>													
							<tr id="<?=$row[$model][$pk];?>" class="index-row switch <?=(($j==1)? 'alterna' : '')?> " data-dest="view_mode" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'edit',$row[$model][$pk]));?>">
							<? else: ?>
							<tr id="<?=$row[$model][$pk];?>" class="index-row">
							<? endif; ?>
								<td class="tools">
									<ul>
										<li><input type="checkbox" value="<?=$row[$model][$pk];?>" class="index-select-checkbox" /></li>
										
										<? if (!empty($show_status)): ?>
										<li>
											<a href="javascript:;" class="index-disabled-switch" data-id="<?=$row[$model][$pk];?>" data-disabled="<?=$row[$model]['disabled'];?>" rel="timmytip" title="<?=$show_status;?>">
												<img src="/img/timmyshare/icon_disabled_<?=$row[$model]['disabled'];?>.gif" alt="disabled" />
											</a>
										</li>
										<? endif; ?>
																				
										<? if(isset($buttons) && $buttons != array()): ?>
										
										<? foreach($buttons as $button => $value): ?>
										
											<? if(isset($button)) $title = $button; else $button = 'Default title'; ?>
											<? if(isset($value['class'])) $class = $value['class']; else $class = ''; ?>
											<? if(isset($value['img'])) $img = $value['img']; else $img = ''; ?>
											<? if(isset($value['link'])) $link = $value['link']; else $link = '';?>
											<? if(isset($value['action'])) $action = $value['action']; else $action = ''; ?>
											<? if(isset($value['selected'])) $selected = $value['selected']; else $selected = ''; ?>
											
											<li>
											 <a href="javascript:;" class="index-row-<?=$class;?> switch" data-id="<?=$row[$model][$pk];?>" <?if($action != ''):?>data-dest="view_mode" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => $action,$row[$model][$pk]));?>?modded=true<?if($selected != ''):?>&selected=<?=$selected;?><?endif;?>"<?endif;?> rel="timmytip" title="<?=$title;?>">
												<img src="<?=$img;?>" width="16" height="16">
											 </a>
											</li>
										
										<? endforeach; ?>
										
										<? endif; ?>
										
										<? if ($edit == true && $allow_edit == true): ?>
										<? if (isAllowed($this->params['controller'],'admin_edit')): ?>
										<li>
											<a href="javascript:;" class="index-row-edit switch" data-id="<?=$row[$model][$pk];?>" data-dest="view_mode" data-ajax="<?=$html->url(array('controller' => $this->params['controller'],'prefix' => $this->params['prefix'],'action' => 'edit',$row[$model][$pk]));?>?modded=true" rel="timmytip" title="Modifica">
												<img src="/img/timmyshare/icon_edit.png" width="24" height="24" />
											</a>
										</li>
										<? endif; ?>
										<? endif; ?>
										<? if(isAllowed($this->params['controller'],'delete')): ?>
										<li>
											<a href="javascript:;" class="index-row-delete" data-id="<?=$row[$model][$pk];?>"  rel="timmytip" title="Cancella">
												<img src="/img/timmyshare/icon_delete.png" width="16" height="16" alt="cancella" />
											</a>
										</li>
										<? endif; ?>
									</ul>
								</td>
					
								
									<? $i = 0; ?>
								

									<? foreach ($fields as $key => $value): ?>
										<? if ($value['field'] != "$model.disabled"): ?>
										<? if (!isset($value['afterRender'])): ?>
										<td class="<?=(($i==count($fields)-1)? 'last' : '')?> td_<?=strtolower(Inflector::slug($key));?>"><?=$backend->getField($row,$value['field']);?></td>
										<? else: ?>
										<td class="<?=(($i==count($fields)-1)? 'last' : '')?> td_<?=strtolower(Inflector::slug($key));?>"><?=$value['afterRender']($backend->getField($row,$value['field']));?></td>
										<? endif; ?>
										<? endif; ?>
										<? $i++; ?>
									
									<? endforeach; ?>
					
								
							</tr>
							<? $j = ($j == 1)? 0 : 1; ?>
							<? endforeach; ?>
						</tbody>
						</table>
					</div><!-- close table_container -->

					<?=$this->element('backend/operations_bar',$reference);?>
		
