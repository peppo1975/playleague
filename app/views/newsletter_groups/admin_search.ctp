
	<?=$this->Form->create('NewsletterGroup', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca gruppi newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('title', array('label' => 'Nome gruppo'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('summary', array('label' => 'Riassunto'));?>
	
	<?=$this->Form->end();?>