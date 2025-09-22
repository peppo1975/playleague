
	<?=$this->Form->create('Banner', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra banner</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$backend->getFilter('Banner.Titolo');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Banner.Link');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('BannersRow.Descrizione');?>	
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Banner.Tipo');?>
	

	<?=$this->Form->end();?>
