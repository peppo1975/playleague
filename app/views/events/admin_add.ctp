
	<?=$this->Form->create('Event', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova manifestazione</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset', 'id' => 'resetSession', 'div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('inserisci',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Nome',array('label' => 'Nome', 'type' => 'text', 'class' => 'big'));?>	
		
	<?=$this->Form->end();?>
