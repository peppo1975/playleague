
	<?=$this->Form->create('Block', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra blocchi</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$backend->getFilter('Block.mother_page');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Block.title');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Block.type_it');?>	
	

	<?=$this->Form->end();?>
