
	<?=$this->Form->create('Slide', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Inserimento nuova immagine</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('test',array('type' => 'hidden'));?>
	
	
	<div class="clear"></div>
	

	
	<? /*
	<?=$this->Form->input('Upload.color',array('label' => 'Colore di sfondo','value' => '#FFF'));?>
	*/ ?>
	
	<div id="formUploadContainer">
	
	<?=$backend->getFiles('id','> 0');?>
	
	</div>
	
		
	<?=$this->Form->end();?>
