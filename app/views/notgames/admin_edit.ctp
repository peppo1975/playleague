
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Notgame', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica data di non gioco</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('GiornoNonGioco');?>	
	<?=$this->Form->input('Data', array('type' => 'text', 'class' => 'datePicker'));?>
	
		
	<?=$this->Form->end();?>
	