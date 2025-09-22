	
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Banner', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica banner: <span><?=$this->data['Banner']['Titolo'];?> - <?=$this->data['Banner']['Link'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	<?=$this->Form->input('id');?>

			<?=$this->Form->input('Titolo', array('label' => 'Titolo', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>
		
			<?=$this->Form->input('Link', array('label' => 'Link', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('row_id', array('label' => 'Spazio banner', 'type' => 'select', 'options' => $rows));?>
			
			<?
			
				$types = array(
				
					'Full-960-130-4' => 'Full banner (960x100px)',
					'Half-475-130-2' => 'Half banner (475x100px)',
					'Quarter-232-130-1' => 'Quarter banner (232x100px)',					
				
				);
			
			?>
			
			<?=$this->Form->input('Tipo', array('label' => 'Tipo', 'type' => 'select', 'options' => $types));?>
			
			<div class="clear"></div>
			
			<div id="formUploadContainer">
		
				<?=$backend->getFiles('banner_id', $this->data['Banner']['id'], array('limit' => 1));?>
		
			</div>
	
	<?=$this->Form->end();?>
