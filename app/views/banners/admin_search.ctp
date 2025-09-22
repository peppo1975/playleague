
	<?=$this->Form->create('Banner', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca banner</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	
			<?=$this->Form->input('Titolo', array('label' => 'Titolo', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>
		
			<?=$this->Form->input('Link', array('label' => 'Link', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('BannersRow.Descrizione', array('label' => 'Spazio banner', 'type' => 'select', 'options' => $rows, 'empty' => true));?>
			
			<?
			
				$types = array(
				
					'Full-960-100' => 'Full banner (960x100px)',
					'Half-475-100' => 'Half banner (475x100px)',
					'Quarter-232-100' => 'Quarter banner (232x100px)',
				
				);
			
			?>
			
			<?=$this->Form->input('Tipo', array('label' => 'Tipo', 'type' => 'select', 'options' => $types, 'empty' => true));?>
	
	<?=$this->Form->end();?>