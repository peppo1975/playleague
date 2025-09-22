
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('NewsletterConfig', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica utente newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('salva',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	<?=$this->Form->input('id');?>	

	<?=$this->Form->input('account_email_id', array('label' => 'Account email', 'type' => 'select', 'options' => $accounts));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('is_default',
	array(
	
	'type' => 'radio',
	'options' => array( '1'=>'Si', '0'=>'No' ),
	'legend' => 'Configurazione di default',
	'default' => '0',

	));?>	
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('nr_email', array('label' => 'Nr. email', 'type' => 'text'));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('disclaimer', array('type' => 'textarea', 'class' => 'rte', 'label' => 'Disclaimer'));?>
	
	<script type="text/javascript">
	var iso = 'it' ;
	var pathCSS = '/css/' ;
	var ad = '/admin/' ;
	</script>
	
	<script type="text/javascript" src="/js/tinymce.inc.js"></script>	
		
	<?=$this->Form->end();?>