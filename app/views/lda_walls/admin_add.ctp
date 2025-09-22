
	<?=$this->Form->create('LdaWall', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo messaggio</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->

			<?=$this->Form->input('LdaWall.title', array('type' => 'text', 'label' => 'Titolo blocco', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('LdaWall.published', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data pubblicazione'));?>
			
			<div class="clear"></div>
			
			<div class="post_content">
			
			<?=$this->element('/backend/ckeditor', array('name' => 'content', 'title' => 'Contenuto blocco'));?>
			
			</div>
			
			<div class="clear"></div>
			
			<div id="formUploadContainer">	
		
				<?=$backend->getFiles('lda_wall_id', 0);?>
		
			</div>
	
	<?=$this->Form->end();?>