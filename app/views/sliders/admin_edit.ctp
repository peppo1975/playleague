
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Slider', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica squadra: <span><?=$this->data['Slider']['title'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<input type="hidden" name="modded" value="false" />
	
	</div><!-- close form_header -->
	
	<?=$this->Form->input('title', array('label' => 'Nome prodotto'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('link', array('label' => 'Link'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('price', array('label' => 'Prezzo prodotto'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('description', array('label' => 'Descrizione'));?>


		<?=$backend->getFiles('slider_id',$this->data['Slider']['id'], array(

			'limit' => 1
		
		));?>
		
	<p><b>Dimensioni consigliate:</b> 277x202 (o dimensione maggiore mantenendo la proporzione 4:3)</p>
	
	<?=$this->Form->end();?>
