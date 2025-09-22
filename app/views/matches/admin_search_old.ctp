
	<?=$this->Form->create('Match', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca gare</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Data', array('label' => 'Data', 'type' => 'text', 'class' => 'datePicker'));?>
	<?=$this->Form->input('Ora', array('label' => 'Ora', 'type' => 'text'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Campionati.Nome',array('label' => 'Campionato'));?>

	<?=$this->Form->input('Half.Descrizione',array('label' => 'Girone'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('CasaNome',array('label' => 'Casa'));?>

	<?=$this->Form->input('TrasfertaNome',array('label' => 'Trasferta'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Giornata', array('label' => 'Giornata', 'type' => 'text'));?>
	<?=$this->Form->input('Partita', array('label' => 'Partita', 'type' => 'text'));?>


	<div class="clear"></div>	
	
	<?=$this->Form->input('NomeGara', array('label' => 'Nome gara', 'type' => 'text'));?>
	
	<?=$this->Form->input('Campi.Descrizione',array('label' => 'Campo'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Match.Risultato',array('label' => 'Risultato'));?>
	
	<div class="clear"></div>	
	
	
	<?=$this->Form->end();?>
