
	<?=$this->Form->create('Header', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra tabella atleti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('User.nome');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('User.cognome');?>	
	
	<div class="clear"></div>

	<?=$backend->getFilter('User.username');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('User.created_it', array('class' => 'datePicker'));?>
	
	<div class="clear"></div>

	<?=$backend->getFilter('Group.nome');?>
		
	<?=$this->Form->end();?>
