
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('TipiAssicurazione', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica tipo assicurazione: <span><?=$this->data['TipiAssicurazione']['Descrizione'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('TipoAssicurazione');?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Descrizione');?>

	<div class="clear"></div>	
		

	<?=$this->Form->input('Costo');?>

	<div class="clear"></div>	


		
	<?=$this->Form->end();?>
