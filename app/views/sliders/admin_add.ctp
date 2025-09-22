
	<?=$this->Form->create('Slider', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova slide</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('title', array('label' => 'Nome prodotto'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('link', array('label' => 'Link','value' => 'http://'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('price', array('label' => 'Prezzo prodotto'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('description', array('label' => 'Descrizione'));?>


	<div class="clear"></div>	
	
	
		<?=$backend->getFiles('slider_id',0, array(
	
		
			'limit' => 1
		
		));?>	
		
	<p><b>Dimensioni consigliate:</b> 277x202 (o dimensione maggiore mantenendo la proporzione 4:3)</p>
	<?=$this->Form->end();?>
