
	<?=$this->Form->create('Expulsion', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella espulsioni</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Espulsione', array('type' => 'text'));?>
	
	<?=$this->Form->end();?>