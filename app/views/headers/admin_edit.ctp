
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Header', array('url' => '/admin/headers/edit/' . $this->params['pass'][0],'prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica immagine</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Upload.name',array('label' => 'Nome'));?>
	<?=$this->Form->input('Upload.description',array('label' => 'Link','type' => 'text'));?>


	<? 
	if ($this->data['Upload']['published'] == '30/11/-0001'): 
		?>


	<?=$this->Form->input('Upload.published', array('type' => 'text', 'class' => 'datePicker', 'value' => date("d/m/Y"),'label' => 'Data pubblicazione'));?>
							
	<? else: ?>

	<?=$this->Form->input('Upload.published', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data pubblicazione'));?>
							

	<? endif; ?>
	<div class="clear"></div>
	
	<div class="input">
	<label>Tavolozza colori</label>
	<div id="colorPicker">
	
	</div>
	
	</div>
	
	<div class="clear"></div>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function() {
		
		$('#colorPicker').ColorPicker({flat: true,
					
					onChange: function (hsb, hex, rgb) {
						$('#UploadColor').val('#' + hex);
					}
					
		});
	
	});
	}
	</script>
	
	<?=$this->Form->input('Upload.color',array('label' => 'Colore di sfondo'));?>
		
	

			
	<?=$this->Form->end();?>
