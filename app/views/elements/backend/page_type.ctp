			<script type="text/javascript">
			
			$(function(){
			
			var edit_action = '<?=$this->data['Page']['action'];?>';
			
				function getAction(controller) {
				
					$.get('/pages/ajaxGetAction/'+controller, function(data){
					
						for(var i in data) {
						
							var option = $('<option>').attr('value', data[i]).text(data[i]);
							
							$("#PageAction").append(option);
						
						}
						
						$("#PageAction").val(edit_action);
					
					},'json');				
				
				}
			
				$('.type-content').slideUp('fast');
				$('.'+$("#PageType").val()+'-content').slideDown('fast');
				if($("#PageController").val().length > 0) getAction($("#PageController").val());
				
				$(".formAdd").delegate("#PageType","change", function(){
				
					$('.type-content').slideUp('fast');
					$('.'+$(this).val()+'-content').slideDown('fast');
				
				});
				
				$(".formAdd").delegate("#PageController","change", function(){
				
					$("#PageAction").empty();
				
					getAction($(this).val());
					
				});
				
			
			});
			
			</script>