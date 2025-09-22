
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Newsletter', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('salva',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->

	<div class="tab-container">
	
		<ul class="tab-selector">
			<li data-index="1" class="selected"><a href="javascript:;">Newsletter</a></li>
			<li data-index="2"><a href="javascript:;">Allegati</a></li>
		</ul>
		
		<div class="tab-page" data-index="2">
		
			<h3>Allegati</h3>
			
				<div id="formUploadContainer">
	
				<?=$backend->getFiles('newsletter_id', $this->data['Newsletter']['id'], array());?>

				</div>
		
		</div>
		
		<div class="tab-page tab-selected" data-index="1">
		
			<?=$this->Form->input('id');?>	
		
			<?=$this->Form->input('layout', array('type' => 'select', 'label' => 'Layout'));?>
			
			<div class="clear">
			
			<?=$this->Form->input('title', array('type' => 'text', 'class' => 'big', 'label' => 'Oggetto'));?>
			
			<div class="clear">
			
				
			<?=$this->Form->input('published', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data pubblicazione'));?>
			
			<div class="clear">
			
				
			
			<div class="post">
	
				<?
				$config['toolbar'] = array(
					array( 'Source', '-', 'Undo','Redo','-', 'Cut','Copy','Paste','PasteText','PasteFromWord','-' ),
					array( 'Find','Replace','SelectAll','RemoveFormat' ),
					array( 'Bold', 'Italic', 'Underline', 'Strike' ),
					array( 'Image', 'Link', 'Unlink', 'Anchor' ),
					array( 'NumberedList','BulletedList','Outdent','Indent','Blockquote' )
				);
				?> 
				<div class="post_content">

					<?=$this->element('/backend/ckeditor', array('name' => 'content'));?>	
				
				</div>

			</div>
		
		</div>
				
	</div>
		
	<?=$this->Form->end();?>