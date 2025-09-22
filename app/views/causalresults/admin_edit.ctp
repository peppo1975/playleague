
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Causalresult', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica causale risultato: <span><?=$this->data['Causalresult']['Descrizione'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	<?=$this->Form->input('CausaleRisultato');?>	
	<?=$this->Form->input('Descrizione');?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Sanzione');?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('PuntiDisciplina');?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
	