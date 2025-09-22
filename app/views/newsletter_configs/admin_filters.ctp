
	<?=$this->Form->create('NewsletterConfig', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra utenti newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('NewsletterAccount.username');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('NewsletterConfig.nr_email');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('NewsletterConfig.disclaimer');?>
	
	<div class="clear"></div>
		
	<?=$this->Form->end();?>