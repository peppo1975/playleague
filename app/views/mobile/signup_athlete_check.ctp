<? if(!empty($this->data)): ?>

	<? if(ereg_replace("[^0-9]","",strtotime($this->data['Athlete']['data_registrazione'])) == null || $this->data['Athlete']['data_registrazione'] == '0000-00-00 00:00:00'): ?>
	
		<script type="text/javascript">
		$(document).ready(function(){
			$("#genera").click(function() {
			$("#AthletePassword").val('');
			$("#AthletePasswordConfirm").val('');
			$.get("/admin/users/generatepwd",function(ret) {
				$("#AthletePassword").val(ret.pwd);
				$("#AthletePasswordConfirm").val(ret.pwd);
				},'json');
			});
		});		
		$(function(){
			$("#formSignupAthlete").remove();
			$("#annulla").click(function(){location.href = '/mobile/signup_athlete'});
			
			$("#formSignupAthleteCheck").submit(function(){
				$('.error-message').remove();
				var error = 0;
				$('#formSignupAthleteCheck .required').each(function(){
					var obj = $(this);
					if(obj.find('input').val() == '') {
						$('<div class="error-message">Campo obbligatorio.</div>').insertAfter(obj.find('label'));
						error = 1;
					}
				});
				if($("#AthletePassword").val() != $("#AthletePasswordConfirm").val()) { $('<div class="error-message">Le password non coincidono.</div>').insertAfter($("#AthletePasswordConfirm").parent('div').find('label')); error = 1; }
				if(error == 1) return false;
			});
			
		});
		</script>

		<?=$this->Form->create('Athlete', array('url' => '/mobile/signup_athlete', 'id' => 'formSignupAthleteCheck'));?>
		
		<?=$this->Form->input('Atleta');?>
		
		<?=$this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome', 'readonly' => true));?>	
		<?=$this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome', 'readonly' => true));?>
		
		<div class="clear"></div>
		
		<?=$this->Form->input('Yearbook.Tessera', array('type' => 'text', 'label' => 'Inserisci numero tessera.', 'readonly' => true));?>
		<div class="input required">
			<?=$this->Form->input('Email', array('type' => 'text', 'label' => 'Email', 'div' => false));?>
		</div>
		<?=$this->Form->input('Telefono', array('type' => 'text', 'label' => 'Telefono'));?>
		<?=$this->Form->input('Cellulare', array('type' => 'text', 'label' => 'Cellulare'));?>
		
		<div class="clear"></div>
		
		<div class="input required">
			<?=$this->Form->input('password', array('label' => 'Password', 'type' => 'password', 'div' => false));?>
		</div>
		<div class="input required">
			<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'class' => 'confirm_password', 'type' => 'password', 'div' => false));?>
		</div>	

		<div class="input">		
		<!-- campi hidden -->
		
		<?=$this->Form->input('data_registrazione', array('type' => 'hidden', 'value' => date("Y-m-d H:i:s")));?>
			
		<div class="input">
			<label> &nbsp;</label>
			<?=$this->Form->submit('Registra',array('type' => 'submit','div' => false));?>
			<?=$this->Form->submit('Annulla',array('type' => 'button','div' => false, 'id' => 'annulla'));?>
		</div>		
		
		<?=$this->Form->end();?>
		
	<? else: ?>
	
		<div class="error-message errore-message-signup">
			Atleta già registrato.
		</div>	
	
	<? endif; ?>

<? else: ?>

<div class="error-message">
Atleta non presente nell'annuario sportivo.
</div>

<? endif; ?>