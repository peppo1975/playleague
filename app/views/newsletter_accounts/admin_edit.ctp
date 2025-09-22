
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('NewsletterAccount', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica account newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('salva',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	<?=$this->Form->input('id');?>	
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('host', array('label' => 'Host', 'type' => 'text'));?>
	
	<?=$this->Form->input('port', array('label' => 'Porta', 'type' => 'text'));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('secure', array('label' => 'Sicurezza', 'type' => 'text'));?>

	<div class="clear"></div>
			
	
	
	<div class="clear"></div>
	
	<?=$this->Form->input('username', array('label' => 'Username', 'type' => 'text'));?>
	
	<?=$this->Form->input('password', array('label' => 'Password', 'type' => 'password'));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('sender_mail', array('label' => 'Email', 'type' => 'text'));?>
	
	<?=$this->Form->input('sender_name', array('label' => 'Nome', 'type' => 'text'));?>

	<div class="clear"></div>
	
	<?=$this->Form->radio('auth', array('0' => 'Si', '1' => 'No'), array('legend' => 'Autenticazione'));?>
	
	<?=$this->Form->radio('test', array('0' => 'Si', '1' => 'No'), array('legend' => 'Test'));?>
	
	<div class="clear"></div>
			
	<?=$this->Form->end();?>