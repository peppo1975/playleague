
	<?=$this->Form->create('News', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova news</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->

	<div class="tab-container">
	
			<ul class="tab-selector">
			
				<li data-index="1" class="selected"><a href="javascript:;">News</a></li>
				<li data-index="3"><a href="javascript:;">Allegati</a></li>
				<li data-index="4"><a href="javascript:;">Metadata</a></li>
			
			</ul>		

		<div class="tab-page tab-selected" data-index="1">
		
			<?=$this->Form->input('isLastHour', array('label' => 'News di ultim\' ora', 'type' => 'select', 'options' => array('0' => 'No', '1' => 'Si')));?>
			
			<div class="clear"></div>
		
			<?=$this->Form->input('title', array('label' => 'Titolo news', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('subtitle', array('label' => 'Sottotitolo', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('disabled', array('label' => 'Pubblica', 'class' => 'big', 'type' => 'select', 'options' => array('1' => 'No', '0' => 'Si')));?>
			
			<?=$this->Form->input('published', array('label' => 'Data di pubblicazione', 'class' => 'datePicker', 'type' => 'text'));?>
			
			<div class="clear"></div>

			<?=$this->element('/backend/ckeditor', array('name' => 'content'));?>	

		</div>

		<div class="tab-page" data-index="3">
		
			<div id="formUploadContainer">
		
				<?=$backend->getFiles('news_id', 0);?>
		
			</div>
		
		</div>
		
		<div class="tab-page" data-index="4">
		
			<?=$this->element('/backend/metadata');?>
		
		</div>
	
	</div>
	
	<?=$this->Form->end();?>