
	<?=$this->Form->create('Stream', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca streaming</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('title', array('label' => 'Titolo', 'type' => 'text', 'class' => 'big'));?>
				
	<?=$this->Form->input('subtitle', array('label' => 'Sottotitolo', 'type' => 'text', 'class' => 'big'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('link', array('label' => 'Link', 'type' => 'text', 'class' => 'big'));?>
	
	<?=$this->Form->input('file', array('label' => 'Nome file', 'type' => 'text'));?>
	
	<div class="clear"></div>
				
	<?=$this->Form->end();?>