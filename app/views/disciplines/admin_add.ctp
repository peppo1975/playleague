
	<?=$this->Form->create('Discipline', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova disciplinare</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Descrizione', array('label' => 'Descrizione', 'type' => 'text', 'class' => 'big'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Punti', array('label' => 'Punti', 'type' => 'text'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Sanzione', array('label' => 'Sanzione', 'type' => 'text'));?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
