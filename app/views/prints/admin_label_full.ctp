					<?=$this->Form->create('labelFull');?>
					
					<script type="text/javascript">
					if (typeof $ != "undefined") {
						var match_id = new Array;
					
						$(function() {
							
							$(".index-select-checkbox:checked").each(function() {
								
								match_id.push($(this).val());
								
							});
							
							$(".print-number").text(match_id.length);
									
							if (match_id.length == 0) {
								
								$(".labelFullButton").attr('disabled','disabled');
								
							}
							
							$("#labelFullAdminLabelFullForm").submit(function(e) {
								
								e.preventDefault();
								
								$.post("/admin/prints/label_full_go",{ "labels": match_id },function(ret) {
									
										location.href = ret.link;
									
								},'json');
							
								return false;
								
							}); 
									
							
									
						});
					}
					</script>
					
					<div class="input">
					<label><span class="print-number"></span> ricevute verranno stampate</label>
					<?=$this->Form->button('Stampa', array('type' => 'submit', 'class' => 'labelFullButton', 'div' => false));?>
					</div>
					
					<?=$this->Form->end();?>