
	<?=$this->Form->create('Page', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca contenuti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('type', array('label' => 'Tipo contenuto', 'type' => 'select', 'options' => array('static' => 'Statico', 'dinamic' => 'Dinamico', 'url' => 'Esterno'), 'empty' => 'Scegli tipo contenuto...'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('title', array('label' => 'Nome pagina', 'class' => 'big', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('subtitle', array('label' => 'Sottotitolo', 'class' => 'big', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Genitore', array('label' => 'Genitore', 'type' => 'select', 'options' => $tree));?>
	
	<?=$this->Form->input('created_it', array('label' => 'Data creazione', 'class' => 'datePicker', 'type' => 'text'));?>
	
	<?=$this->Form->input('modified_it', array('label' => 'Data ultima modifica', 'class' => 'datePicker', 'type' => 'text'));?>
	
	<?=$this->Form->end();?>