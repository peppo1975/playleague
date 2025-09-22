
	<?=$this->Form->create('Athlete', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra tabella atleti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$backend->getFilter('Athlete.Cognome');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Nome');?>
	
	<div class="clear"></div>

	<?=$backend->getFilter('Athlete.Indirizzo');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Cap');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Localita');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Provincia');?>

	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Telefono');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Cellulare');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Lavoro');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Fax');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.Email');?>

	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.LuogoNascita');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.DataNascita');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.NumeroDocumento');?>
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Athlete.ScadenzaDocumento');?>

		
	<?=$this->Form->end();?>
