
	<?=$this->Form->create('Athlete', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella atleti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Cognome');?>
	<?=$this->Form->input('Nome');?>
	<?=$this->Form->input('DataNascita',array('label' => 'Data di nascita','type' => 'text','class' => 'datePicker'));?>	
	<?=$this->Form->input('LuogoNascita',array('label' => 'Luogo di nascita'));?>
	
	<? if($layout == "tablet"): ?>
	
	<div class="clear"></div>
	
	<? endif; ?>


	<?=$this->Form->input('ScuolaCalcio',
	array(
	
	'type' => 'radio',
	'options' => array( 1=>'Si', 0=>'No' ),

	));?>
	
	<?=$this->Form->input('Sesso',
	array(
	
	'type' => 'radio',
	'options' => array( 'Maschio'=>'M', 'Femmina'=>'F' ),


	));?>
	
	<?=$this->Form->input('Responsabile',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>
	
	<?=$this->Form->input('TipoDocumento',array(
	
	'label' => 'Tipo documento',
	'options' => array(
	
		'Carta Identità' => 'Carta Identità',
		'Patente' => 'Patente',
		'Passaporto' => 'Passaporto'
	
	
	),
	'empty' => true
	));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Indirizzo');?>
	<?=$this->Form->input('Cap');?>
	<?=$this->Form->input('Localita');?>
	<?=$this->Form->input('Provincia');?>
	
	<? if($layout == "tablet"): ?>
	
	<div class="clear"></div>
	
	<? endif; ?>
	
	<?=$this->Form->input('Sportivo',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>	
	
	<?=$this->Form->input('Arbitro',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>
	
	<?=$this->Form->input('ArbitroAttivo',
	array(
	
	'legend' => 'Arbitro attivo',
	'type' => 'radio',
	'options' => array( 1=>'Si', 0=>'No' ),

	));?>		
	<?=$this->Form->input('Delegato',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>		

	<?=$this->Form->input('Allenatore',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>	

	<?=$this->Form->input('NumeroDocumento',array('label' => 'Num. documento'));?>

	<div class="clear"></div>

	<?=$this->Form->input('CodiceFiscale',array('label' => 'Codice Fiscale','type' => 'text'));?>

	<?=$this->Form->input('Telefono');?>
	<?=$this->Form->input('Cellulare');?>
	<?=$this->Form->input('Lavoro',array('label' => 'Telefono lavoro'));?>
	
	
	
	<?=$this->Form->input('Email', array('class' => ($layout != "tablet")? "big" : "big-tablet"));?>

	<? if($layout == "tablet"): ?>
	
	<div class="clear"></div>
	
	<? endif; ?>

	<?=$this->Form->input('Fax');?>	

	<div class="input text <? if($layout != "tablet"): ?>scadenza<? endif; ?>">
	<?=$this->Form->input('ScadenzaDocumento',array('label' => 'Scadenza documento','type' => 'text','class' => 'datePicker', 'div' => false));?>
	</div>	


	<?=$this->Form->end();?>

