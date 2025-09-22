
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Stream', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica streaming <span><?=$this->data['Stream']['title'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('id');?>	
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('title', array('label' => 'Titolo', 'type' => 'text', 'class' => 'big'));?>
				
	<?=$this->Form->input('subtitle', array('label' => 'Sottotitolo', 'type' => 'text', 'class' => 'big'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('link', array('label' => 'Link', 'type' => 'text', 'class' => 'big'));?>
	
	<?=$this->Form->input('file', array('label' => 'Nome file', 'type' => 'text'));?>
	
	<?=$this->Form->input('embed', array('label' => 'Embed', 'type' => 'select', 'options' => array('0' => 'No', '1' => 'Si')));?>
	
	<div class="clear"></div>
	
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

				<h3>Contenuto</h3>
				
				<?=$cksource->ckeditor('content',array('config' => $config, 'escape' => false));?>
			
			</div>
		
		</div>

	<div class="clear"></div>	
			
	<?=$this->Form->end();?>
	