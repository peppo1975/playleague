
	<?=$this->Form->create('Teambook', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella annuario squadre</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?
	$options = array();
	$options[''] = '';
	foreach($AnniSportivi as $AnnoSportivo) {
	  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
	 }
	?>
	
	<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'options' => $options));?>
	
	<div class="clear"></div>

	<?=$this->Form->input('NomeSquadra',array('label' => 'Squadra','class' => 'autoComplete','data-url' => '/admin/teambooks/searchSquadra','data-dest' => 'TeambookDenominazione'));?>
		
	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
