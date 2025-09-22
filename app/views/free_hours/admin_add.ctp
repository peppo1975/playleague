	
	<?=$this->Form->create('FreeHour', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova prenotazione</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('CampoSearch', array('type' => 'text', 'label' => 'Campo', 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchCampo', 'data-dest' => 'FreeHourCampo' ));?>
	<?=$this->Form->input('Campo', array('type' => 'hidden'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Data', array('label' => 'Data', 'type' => 'text', 'class' => 'datePicker'));?>
	
	<?=$this->Form->input('Ora', array('label' => 'Ora', 'type' => 'text', 'class' => 'control_ora'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('AtletaSearch',array('label' => 'Tesserato', 'class' => 'autoComplete','data-url' => '/admin/free_hours/searchTessera','data-dest' => 'FreeHourAtleta'));?>
	<?=$this->Form->input('Atleta', array('type' => 'hidden'));?> 
	
	<div class="clear"></div>
	
	<h3>Info tesserato</h3>
	
	<div class="infoAthlete">
	
		<?=$this->Form->input('Athlete.Atleta');?>
	
		<?=$this->Form->input('Athlete.Cognome', array('type' => 'text', 'readonly' => false, 'label' => 'Cognome'));?>
		
		<?=$this->Form->input('Athlete.Nome', array('type' => 'text', 'readonly' => false, 'label' => 'Nome'));?>
		
		<?=$this->Form->input('Athlete.Cap', array('type' => 'text', 'readonly' => false, 'label' => 'Cap'));?>
	
		<?=$this->Form->input('Athlete.Localita', array('type' => 'text', 'readonly' => false, 'label' => 'Localita'));?>
		
		<?=$this->Form->input('Athlete.Provincia', array('type' => 'text', 'readonly' => false, 'label' => 'Provincia'));?>
		
		<?=$this->Form->input('Athlete.Telefono', array('type' => 'text', 'label' => 'Telefono'));?>
		
		<?=$this->Form->input('Athlete.Cellulare', array('type' => 'text', 'label' => 'Cellulare'));?>
		
		<?=$this->Form->input('Athlete.Email', array('type' => 'text', 'label' => 'Email'));?>
	
	</div>
	
	<!--
	
	Prova: 032371
	
	-->
		
	<?=$this->Form->end();?>
