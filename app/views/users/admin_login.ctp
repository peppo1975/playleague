<h1 class="page_title">Login</h1>
<div class="clear"></div>
<div class="form-container">

	<?=$this->Form->create('User', array('action' => 'login','prefix' => 'admin'));?>
	<?=$this->Form->input('username');?>
	<?=$this->Form->input('password',array('error' => false));?>

	<div class="clear"></div>

	<?=$this->Form->submit('Login');?>

	
	<div class="clear"></div>


	<?=$html->link("Recupera password","/admin/users/recover");?>


	<?=$this->Form->end();?>
	

	<? if ($session->check('Message.auth')): 
			// Se non ci sono errori di input stampo il risultato del login
	?>
		
		<?=$session->flash('auth');?>

	<? endif; ?>

</div>
