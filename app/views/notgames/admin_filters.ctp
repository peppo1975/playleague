
	<?=$this->Form->create('Notgame', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra giorni di non gioco</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$backend->getFilter('Notgame.Data_it');?>
		
	<?=$this->Form->end();?>
