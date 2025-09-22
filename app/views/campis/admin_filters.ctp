
	<?=$this->Form->create('Campi', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra tabella anni sportivi</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('Campi.Descrizione');?>

	<div class="clear"></div>
	
	<?=$backend->getFilter('Campi.NominativoGestore');?>

		
	<?=$this->Form->end();?>
