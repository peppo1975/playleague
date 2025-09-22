
	<?=$this->Form->create('NewsletterConfig', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca utenti newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('account_email_id', array('label' => 'Account email', 'type' => 'select', 'options' => $accounts));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('nr_email', array('label' => 'Nr. email', 'type' => 'text'));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('disclaimer', array('label' => 'Disclaimer', 'type' => 'textarea'));?>
		
	<?=$this->Form->end();?>