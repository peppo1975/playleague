
	<?=$this->Form->create('FreeHour', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella ore libere</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Campi.Descrizione', array('type' => 'text', 'label' => 'Campo', 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchCampo', 'data-dest' => 'Fittizio' ));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('FreeHour.Data', array('label' => 'Data', 'type' => 'text', 'class' => 'datePicker'));?>
	
	<?=$this->Form->input('FreeHour.Ora', array('label' => 'Ora', 'type' => 'text', 'class' => 'control_ora'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('FreeHour.Nominativo',array('label' => 'Tesserato', 'class' => 'autoComplete','data-url' => '/admin/free_hours/searchTessera','data-dest' => 'Fittizio'));?>
	
	<?=$this->Form->end();?>