
	<?=$this->Form->create('Campionati', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella campionati</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	

	<?=$this->Form->input('Nome',array('label' => 'Campionato','class' => 'autoComplete','data-url' => '/admin/campionatis/searchCampionato','data-dest' => 'Fittizio'));?>	
	
	
	<?
	$options = array();
	$options[''] = '';
	foreach($AnniSportivi as $AnnoSportivo) {
	  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
	 }
	?>
	


	<?=$this->Form->input('created', array('label' => 'Data creazione', 'class' => 'datePicker','type' => 'text'));?>
	

	<?=$this->Form->input('AnnoSportivo_v', array('label' => 'Anno sportivo', 'type'=>'select', 'default'=>'1', 'options' => $options));?>

	<div class="clear"></div>

	<?=$this->Form->input('NomeCampionatoPrecedente',array('label' => 'Campionato Precedente','class' => 'autoComplete','data-url' => '/admin/campionatis/searchCampionato','data-dest' => 'CampionatiNomeCampionatoPrecedente'));?>

	<div class="clear"></div>

	<?=$this->Form->input('TariffaArbitro', array('label' => 'Tariffa Arbitro'));?>
	<?=$this->Form->input('TariffaArbitro2', array('label' => 'Tariffa Arbitro 2'));?>
	<?=$this->Form->input('TariffaDelegato', array('label' => 'Tariffa Delegato'));?>
	<?=$this->Form->input('TariffaDelegatoA', array('label' => 'Tariffa Delegato A'));?>
		
	<?=$this->Form->end();?>
