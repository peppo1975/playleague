
	<?=$this->Form->create('User', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

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
	foreach($groups as $group) {
	  $options[$group['Group']['nome']] = $group['Group']['nome'];
	 }
	?>
	
	<?=$this->Form->input('nome');?>

	<?=$this->Form->input('cognome');?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('username', array('label' => 'Email'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('created_it', array('label' => 'Data registrazione', 'class' => 'datePicker'));?>
	
	<?=$this->Form->input('Nomegruppo', array('label' => 'Gruppo', 'type' => 'select', 'options' => $options));?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
