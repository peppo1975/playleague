
	<?=$this->Form->create('Discipline', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra tabella causali risultato</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('Discipline.Descrizione');?>

	<div class="clear"></div>	
	
	<?=$backend->getFilter('Discipline.Punti');?>

	<div class="clear"></div>	
	
	<?=$backend->getFilter('Discipline.Sanzione');?>

	<div class="clear"></div>

		
	<?=$this->Form->end();?>
