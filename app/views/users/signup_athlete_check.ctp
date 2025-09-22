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
			
			$("#formSignupAthleteCheck").submit(function(){
				$('.error-message').remove();
				var error = 0;
				$('#formSignupAthleteCheck .required').each(function(){
					var obj = $(this);
					if(obj.find('input').val() == '') {
						obj.append('<div class="error-message text-danger">Campo obbligatorio.</div>');
						error = 1;
					}
				});
				if($("#AthletePassword").val() != $("#AthletePasswordConfirm").val()) { $("#AthletePasswordConfirm").parent('div').append('<div class="error-message text-danger">Le password non coincidono.</div>'); error = 1; }
				if(error == 1) return false;
			});
			
		});
		</script>

		<?=$this->Form->create('Athlete', array('url' => '/registrazione/atleti', 'id' => 'formSignupAthleteCheck',

      'class'         => 'form-horizontal',
        'inputDefaults' => array(
            'format'  => array( 'before', 'between','label',
                                'input', 'error', 'after' ),
            'class' => 'form-control',
            'div'     => array( 'class' => 'form-group' ),
            'label'   => array( 'class' => 'col-lg-2 control-label' ),
            'between' => '<div class="col-lg-12">',
            'after'   => '</div>',
            'error'   => array( 'attributes' => array( 'wrap'  => 'span',
                                                       'class' => 'text-danger' ) ),
        

				)

		));?>
		<fieldset>
		<?=$this->Form->input('Atleta');?>
		
		<?=$this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome', 'readonly' => true));?>	
		<?=$this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome', 'readonly' => true));?>
		
		<div class="clear"></div>
		
		<?=$this->Form->input('Yearbook.Tessera', array('type' => 'text', 'label' => 'Inserisci numero tessera.', 'readonly' => true));?>
		<div class="input required">
			<?=$this->Form->input('Email', array('type' => 'text', 'label' => 'Email'));?>
		</div>
		<?=$this->Form->input('Telefono', array('type' => 'text', 'label' => 'Telefono'));?>
		<?=$this->Form->input('Cellulare', array('type' => 'text', 'label' => 'Cellulare'));?>
		
		<div class="clear"></div>
		
		<div class="input required">
			<?=$this->Form->input('password', array('label' => 'Password', 'type' => 'password'));?>
		</div>
		<div class="input required">
			<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'class' => 'form-control confirm_password', 'type' => 'password'));?>
		</div>	

		<div class="input">		
		<!-- campi hidden -->
		
		<?=$this->Form->input('data_registrazione', array('type' => 'hidden', 'value' => date("Y-m-d H:i:s")));?>
			
			<?=$this->Form->submit('Registra',array('type' => 'submit','div' => false,'btn btn-primary pull-right mb-xl'));?>

		</fieldset>
		<?=$this->Form->end();?>
		
	<? else: ?>
	
		<div class="error-message alert alert-warning">
			Atleta già registrato.
		</div>	
	
	<? endif; ?>

<? else: ?>

<div class="error-message alert alert-danger">
Atleta non presente nell'annuario sportivo.
</div>

<? endif; ?>