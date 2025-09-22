<?

App::Import('Model', $model);
$models = new $model;

?>					
<!-- 

	Serve per usare le 
	funzioni del back end 
	anche nel frontoffice 

-->
					<script type="text/javascript">

						// $(function() {
							
							// var n_cols = $(".index_table tr").find("th").length;
						
							// $("<tr class='top_scroller'><td colspan='" + n_cols + "'><div class='left_scroller'><div class='filler'>&nbsp;</div></div></td></tr>").insertAfter(".index_table tr:first");
							
							// $(".top_scroller td").css('padding',0);
						
							// $(".left_scroller").css('max-width',$(".table_container").innerWidth()).css('overflow-x','scroll');
							// $(".left_scroller .filler").width($(".index_table").width()).css('height',0);
							// $(".left_scroller").scrollLeft($(".table_container").scrollLeft());
							
							// $(".left_scroller").bind('scroll',function() {
								// $(this).css('margin-left',$(this).scrollLeft());
								// $(".table_container").scrollLeft($(this).scrollLeft());
								
							// });
						
							// $(".table_container").bind('scroll',function() {
								// $(".left_scroller").css('margin-left',$(this).scrollLeft());
								// $(".left_scroller").scrollLeft($(this).scrollLeft());
								
							// });
						
							// $(window).resize(function() {
								
							// $(".left_scroller").css('max-width',$(".table_container").innerWidth()).css('overflow-x','scroll');
							// $(".left_scroller .filler").width($(".index_table").width()).css('height',0);
								
							// });
							
						// });

					
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
													
							$(".timmyFileEdit").live('submit',function(e) {

								var data = $(this).serialize();

								$.post('/uploads/edit', data, function(ret){
									
									if(ret.ok == 1) {
										
										$("#uploadTable").find('tr[data-id="' + ret.id + '"]').find('.td_title').text(ret.title);
										$("#uploadTable").find('tr[data-id="' + ret.id + '"]').find('.td_description').text(ret.description);
										
										timmy_close();
										
									}
									
								},'json')
								
								return false;
								
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
								
								timmy_load("/uploads/edit/" + $(this).attr('data-id'));
								
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
									
									$.get("/uploads/delete/" + $(this).attr('data-id'),function() {
										
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
									
													$.get("/uploads/delete/" + $(this).attr('data-id'),function() {
										
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

								
								
								//se la tabella � vuota la nascondo
								
								if($("#index_table").find('tr.index-row').length == 0) {
								
									$('.table_container').hide();
									$('.operations_bar').hide();
								
								}
							
						});
						
						
				
						
					</script>