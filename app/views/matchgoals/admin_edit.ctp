
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Matchgoal', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica espulsione: <span><?=$this->data['Matchgoal']['DatiAtleta'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('GoalPartita');?>
	
	<?=$this->Form->input('AnnoSportivo', array('label' => 'Anno sportivo', 'type' => 'text', 'readonly' => true));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Data', array('label' => 'Data', 'type' => 'text', 'readonly' => true));?>
	
	<?=$this->Form->input('DatiAtleta', array('label' => 'Atleta', 'type' => 'text', 'readonly' => true));?>
	
	<?=$this->Form->input('NomeSquadra', array('label' => 'Squadra', 'type' => 'text', 'readonly' => true));?>

	<div class="clear"></div>	
	
	<?$options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4');?>
	<?=$this->Form->input('EspulsioneGiornate', array('label' => 'Giornate', 'type' => 'select', 'options' => $options, 'empty' => true));?>
	
	<?=$this->Form->input('EspulsioneFine', array('label' => 'Data fine', 'type' => 'text', 'class' => 'datePicker'));?>
		
	<?=$this->Form->end();?>