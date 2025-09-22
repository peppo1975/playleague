
	<?=$this->Form->create('Page', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra contenuti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$backend->getFilter('Page.type');?>
	
	<div class="clear"></div>		
	
	<?=$backend->getFilter('Page.title');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Page.subtitle');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Page.Genitore');?>
	
	<div class="clear"></div>		
	
	<?=$backend->getFilter('Page.content');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Page.created_it');?>
	
	<div class="clear"></div>	
	
	<?=$backend->getFilter('Page.modified_it');?>

	<?=$this->Form->end();?>
