
	<?=$this->Form->create('Comunication', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella comunicazioni</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Campionato', array('label' => 'Campionato', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GironeCampionato', array('label' => 'Girone', 'type' => 'text'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Giornata', array('label' => 'Giornata', 'type' => 'text'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Note', array('label' => 'Note'));?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>