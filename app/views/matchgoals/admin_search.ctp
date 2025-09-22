
	<?=$this->Form->create('Matchgoal', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella espulsioni</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('AnnoSportivo', array('label' => 'Anno', 'type' => 'select', 'options' => $AnniSportivi, 'empty' => true));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Data', array('label' => 'Data', 'class' => 'datePicker', 'type' => 'text', 'readonly' => false));?>
	
	<?=$this->Form->input('DatiAtleta', array('label' => 'Atleta', 'type' => 'text', 'readonly' => false));?>
	
	<?=$this->Form->input('NomeSquadra', array('label' => 'Squadra', 'type' => 'text', 'readonly' => false));?>

	<div class="clear"></div>	
	
	<?$options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4');?>
	<?=$this->Form->input('EspulsioneGiornate', array('label' => 'Giornate', 'type' => 'select', 'options' => $options, 'empty' => true));?>
	
	<?=$this->Form->input('EspulsioneFine', array('label' => 'Data fine', 'type' => 'text', 'class' => 'datePicker'));?>
		
	<?=$this->Form->end();?>