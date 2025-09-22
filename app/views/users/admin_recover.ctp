<h1 class="page_title">Recupera password</h1>
<div class="clear"></div>
<div class="form-container">

	<?=$this->Form->create('User', array('action' => 'recover','prefix' => 'admin'));?>
	
	<?=$this->Form->input('username');?>


	<div class="clear"></div>

	<?=$this->Form->input('nome');?>
	<?=$this->Form->input('cognome');?>


	<div class="clear"></div>

	<?=$this->Form->submit('Recupera');?>
	
	
	<?=$html->link("Vai al login","/admin/users/login");?>


	
	<?=$this->Form->end();?>
	
	<? if ($this->Session->check('Message.flash')): ?>
	
		<?=$this->Session->flash();?>
		
	<? endif; ?>

</div>
