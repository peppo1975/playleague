
	<?=$this->Form->create('Stream', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra streaming</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('Stream.title');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Stream.subtitle');?>

	<div class="clear"></div>	
	
	<?=$backend->getFilter('Stream.link');?>

	<div class="clear"></div>	
	
	<?=$backend->getFilter('Stream.file');?>

	<?=$this->Form->end();?>
