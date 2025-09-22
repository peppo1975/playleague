
	<?=$this->Form->create('Yearbook', array('action' => 'filters','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Filtra tabella annuario atleti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('filtra tabella',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
	<?=$backend->getFilter('Yearbook.AnnoSportivo');?>
	
	<div class="clear"></div>

	<?=$backend->getFilter('Yearbook.Tessera');?>
	
	<div class="clear"></div>

	<?=$backend->getFilter('Yearbook.DataVidimazione', array('date' => true));?> 

	<div class="clear"></div>
	
	<?=$backend->getFilter('Yearbook.NomeSquadraCampionato');?> 
	
	<div class="clear"></div>

	<?=$backend->getFilter('Yearbook.NomeAtleta');?> 
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Yearbook.Responsabile');?> 
	
	<div class="clear"></div>
	
	<?=$backend->getFilter('Yearbook.NomeAssicurazione');?> 	
		
	<?=$this->Form->end();?>
