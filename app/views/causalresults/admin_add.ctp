
	<?=$this->Form->create('Causalresult', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo risultato causale</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Descrizione', array('label' => 'Descrizione', 'type' => 'text'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Sanzione', array('label' => 'Sanzione', 'type' => 'text'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('PuntiDisciplina', array('label' => 'Punti Disciplina', 'type' => 'text'));?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
