	<?=$this->Form->create('Right', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo permesso</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('group_id', array('label' => 'Gruppo', 'type' => 'select', 'options' => $groups));?>

	<div class="clear"></div>

	<?=$this->Form->input('resource', array('label' => 'Risorsa', 'type' => 'select', 'options' => $controllers, 'empty' => 'Scegli controller'));?>

	<div class="clear"></div>

	<?=$this->Form->input('action', array('label' => 'Azione', 'type' => 'select', 'empty' => true));?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		$('.formAdd').delegate('#RightResource','change', function(){
		
			var controller = $(this).val();
			
			$("#RightAction").empty();
				
			$.get('/pages/ajaxGetAction/'+controller, function(data){
			
			$("#RightAction").append('<option value=""></option>');
			
				for(var i in data) {
				
					var option = $('<option>').attr('value', data[i]).text(data[i]);
					
					$("#RightAction").append(option);
				
				}
			
			},'json');				
		
		});
	
	});
	}
	</script>

	<div class="clear"></div>

	<?=$this->Form->input('allow', array('label' => 'Autorizzazione', 'type' => 'select', 'options' => array('1' => 'Si', '0' => 'No')));?>

	<div class="clear"></div>
		
	<?=$this->Form->end();?>
