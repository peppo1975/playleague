<script type="text/javascript">
$(document).ready(function(){
	$("#genera").click(function() {
	$("#UserPassword").val('');
	$("#UserPasswordConfirm").val('');
	$.get("/admin/users/generatepwd",function(ret) {
		$("#UserPassword").val(ret.pwd);
		$("#UserPasswordConfirm").val(ret.pwd);
		},'json');
	});
});

$(function(){

	$("#formSignupAthlete").submit(function(){
	
		var data = $(this).serialize();
		
		$('.error-message').remove();
		var error = 0;
		$('#formSignupAthlete .required').each(function(){
			var obj = $(this);
			if(obj.find('input').val() == '') {
				$('<div class="error-message">Campo obbligatorio.</div>').insertAfter(obj.find('label'));
				error = 1;
			}
		});
		if(error == 1) return false;	
		
		$.post('/mobile/checkTessera', data, function(ret){
			
			$('.athlete_info').html(ret).trigger('create');
			
			if($('.errore-message-signup').length > 0) {
				
				var text = $('.errore-message-signup').text();
			
				$('<div>').addClass('error-message').addClass('error-message-signup').text(text).insertAfter($('.breadcrumbs-container'));
			
			} else {
			
				$('.athlete_info').show();
			
			}
	
		},'html');
	
		return false;
	
	});
	
});

</script>

			<div class="breadcrumbs-container">
			
				<ul>
			
					<li>
						<a data-ajax="false" href="/mobile" title="Home page">
							Home
						</a>
						&rsaquo; 
					</li>
					<li>
						<a data-ajax="false" href="/mobile/reserved" title="Login/Registrazione utenti">			
						Login/Registrazione utenti
						</a>
						&rsaquo; 
					</li>
					<li>
						Registrazione atleti
					</li>
					
				</ul>
				
			</div>

			<div class="contents-box form-container">
			
				<h1>Modulo registrazione atleti</h1>
				<div class="clear"></div>
				
				<?=$this->element("/backend/add_edit_scripts");?>
				<?=$this->Form->create('User', array('url' => '/mobile/signup_athlete', 'id' => 'formSignupAthlete', 'data-ajax' => false));?>
				
				<div class="input required">
				<?=$this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome', 'div' => false));?>	
				</div>
				<div class="input required">
				<?=$this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome', 'div' => false));?>
				</div>
				<div class="input required">
				<?=$this->Form->input('Tessera', array('type' => 'text', 'label' => 'Numero tessera.', 'div' => false));?>
				</div>
				<div class="input required">
				<?=$this->Form->input('signup_code', array('type' => 'text', 'label' => 'Codice controllo.', 'div' => false));?>
				</div>				
				
				<div class="input">
				<label>&nbsp;</label>
				<?=$this->Form->submit('Controlla',array('type' => 'submit','div' => false, 'id' => 'controlla'));?>
				</div>		
				
				<?=$this->Form->end();?>
				
				<div class="athlete_info" style="display: none;">
				
				</div>
	
			</div>