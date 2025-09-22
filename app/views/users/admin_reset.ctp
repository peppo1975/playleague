<h1 class="page_title">Recupera password</h1>
<div class="clear"></div>
<div class="form-container">
	<p>
	<?=$User['nome'];?> <b><?=$User['cognome'];?></b>, modifica la tua password
	</p>

	<?=$this->Form->create('User', array('action' => 'reset','prefix' => 'admin'));?>
	
	<?=$this->Form->input('password',array('autocomplete' => 'off'));?>
	<?=$this->Form->input('cpassword',array('onpaste' => 'return false;','autocomplete' => 'off','type' => 'password','error' => $confirm,'label' => 'Conferma password','div' => array('class' => 'input password required')));?>

	<div class="clear"></div>

	<?=$this->Form->submit('Modifica');?>
	
	<?=$this->Session->flash();?>

	
	<?=$this->Form->end();?>
	

</div>
