
	<?=$this->Form->create('Teambook', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo annuario squadra</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('SquadraSearch',array('label' => 'Squadra','class' => 'autoComplete','data-url' => '/admin/teambooks/searchSquadra','data-dest' => 'TeambookSquadra'));?>
	<?=$this->Form->input('Squadra',array('type' => 'hidden'));?>
	
	<?
	$options = array();
	foreach($AnniSportivi as $AnnoSportivo) {
	  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
	 }
	?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'default'=>'1', 'options' => $options));?>
		
	
	<div class="clear"></div>
	
	<?=$this->Form->input('DepositoCauzionale');?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Note');?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
