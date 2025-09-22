<script type="text/javascript">
if (typeof $ != "undefined") {
$("#genera").click(function() {
$.get("/admin/users/generatepwd",function(ret) {
	$("#UserPassword").val(ret.pwd);
	$("#UserPasswordConfirm").val(ret.pwd);
	},'json');
 });
 }
</script>
<?=$this->element("/backend/add_edit_scripts");?>
	<?=$this->Form->create('User', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo utente</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
		
	<?=$this->Form->input('nome', array('label' => 'Nome'));?>
	<?=$this->Form->input('cognome', array('label' => 'Cognome'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('username', array('label' => 'Email'));?>
	
	<?=$this->Form->input('NomeAtleta',array('label' => 'Atleta', 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'UserAthleteId'));?>
	<?=$this->Form->input('athlete_id',array('type' => 'hidden'));?>	
	
	<div class="clear"></div>
	
	<?=$this->Form->input('password', array('label' => 'Password'));?>
	
	<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'type' => 'password'));?>

	<div class="input">
	<label>&nbsp;</label>
	<?=$this->Form->submit('Genera password',array('type' => 'button','div' => false,'id' => 'genera'));?>
	</div>
	
	<div class="clear"></div>
		
	<?
	$options = array();
	foreach($groups as $group) {
	  $options[$group['Group']['id']] = $group['Group']['nome'];
	 }
	?>
	
	<?=$this->Form->input('group_id', array('label' => 'Gruppo', 'type'=>'select', 'options' => $options));?>
		
	<?=$this->Form->end();?>
