
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Slide', array('url' => '/admin/slides/edit/' . $this->params['pass'][0],'prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica immagine</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Upload.name',array('label' => 'Titolo','class'=>'big'));?><div class="clear"></div>
	<?=$this->Form->input('Upload.link',array('label' => 'Link al click','class'=>'big'));?><div class="clear"></div>
	<?=$this->Form->input('Upload.description',array('label' => 'Descrizione','type' => 'textarea'));?><div class="clear"></div>
	<?=$this->Form->input('Upload.effect',array('label'=>'Effetto','type'=>'select','options'=>array('0' => 'Fade da sinistra','1' => 'Fade da destra')));?><div class="clear"></div>

	<?=$this->Form->input('Upload.category',array('label'=>'Sezione/Categoria','type'=>'select','options'=>array('0' => 'Campionati/Tornei','1' => 'Scuola calcio a 5','2' => 'Tennis')));?>


<div class="clear"></div>

	<? 
	if ($this->data['Upload']['published'] == '30/11/-0001'): 
		?>


	<?=$this->Form->input('Upload.published', array('type' => 'text', 'class' => 'datePicker', 'value' => date("d/m/Y"),'label' => 'Data pubblicazione'));?>

	<!-- timmytag - Inserimento data fine 04/05/2018 -->
    <?= $this->Form->input('Upload.over', array('type' => 'text', 'class' => 'datePicker', 'value' => date("d/m/Y"), 'label' => 'Data fine')); ?>
							
	<? else: ?>

	<?=$this->Form->input('Upload.published', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data pubblicazione'));?>

	<!-- timmytag - Inserimento data fine 04/05/2018 -->
    <?= $this->Form->input('Upload.over', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data fine')); ?>
							

	<? endif; ?>
	<div class="clear"></div>

	

			
	<?=$this->Form->end();?>
