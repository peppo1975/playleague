	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Right', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica permesso: <span><?=$this->data['Right']['resource'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('id');?>
	<?=$this->Form->input('group_id', array('label' => 'Gruppo', 'type' => 'select', 'options' => $groups));?>

	<div class="clear"></div>

	<?=$this->Form->input('resource', array('label' => 'Risorsa', 'type' => 'select', 'options' => $controllers, 'empty' => 'Scegli controller'));?>

	<div class="clear"></div>

	<?=$this->Form->input('action', array('label' => 'Azione', 'type' => 'select', 'empty' => true, 'options' => $actions));?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		$('.formAdd').delegate('#RightResource','change', function(){
		
			$("#RightAction").empty();
		
			var controller = $(this).val();
				
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