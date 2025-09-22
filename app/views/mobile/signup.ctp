<script type="text/javascript">
function isValidEmail(str) {
   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
}
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
	$("#formSignup").delegate("#UserUsername","focusout", function(){
		$('.form-msg').remove();
		var obj = $(this);
		if(obj.val() != '' && isValidEmail(obj.val())) {
			$.post('/mobile/checkUsername', {"username":obj.val()}, function(data){
				if(data.count > 0) {
					$('<div class="form-msg error-message">Username già esistente.</div>').insertAfter(obj.parent('div').find('label'));
				} else {
					obj.parent('div').append('<div class="form-msg ok-message">Username disponibile.</div>');
				}
			},'json');
		}
	});
	
	$("#UserDataNascitaDay").closest('.ui-select').find('span.ui-btn-text').find('span').text('Giorno');
	$("#UserDataNascitaMonth").closest('.ui-select').find('span.ui-btn-text').find('span').text('Mese');
	$("#UserDataNascitaYear").closest('.ui-select').find('span.ui-btn-text').find('span').text('Anno');				
	
	$('.error-message').each(function(){
	
		var error = $(this);
		var div   = error.parents('.input');
		
			$('<div class="error-message">'+error.text()+'</div>').insertAfter(div.find('label'));
		
			$(this).remove();
	
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
				Registrazione utenti
			</li>
			
		</ul>
		
	</div>
	
	<script type="text/javascript">     
	
		$(document).ready( function() {

			$("#UserDataNascitaDay").closest('.ui-select').find('span.ui-btn-text').find('span').text('Giorno');
			$("#UserDataNascitaMonth").closest('.ui-select').find('span.ui-btn-text').find('span').text('Mese');
			$("#UserDataNascitaYear").closest('.ui-select').find('span.ui-btn-text').find('span').text('Anno');			


		});
	
	</script>	

			<div class="contents-box form-container">
			
			<h1>Modulo di registrazione utenti</h1>
			<div class="clear"></div>	

				<?=$this->element("/backend/add_edit_scripts");?>
				<?=$this->Form->create('User', array('url' => '/mobile/signup', 'id' => 'formSignup', 'data-ajax' => false));?>
					
					<?=$this->Form->input('nome', array('label' => 'Nome'));?>
					<?=$this->Form->input('cognome', array('label' => 'Cognome'));?>
				<div class="clear"></div>

				<?=$this->Form->input('data_nascita', array('label' => 'Data di nascita', 'separator' => false, 'empty' => '-', 'dateFormat' => 'DMY', 'maxYear' => date("Y") - 18, 'minYear' => 1945, 'monthNames' => false));?>
				<?=$this->Form->input('nazione', array('label' => 'Nazione'));?>
				<div class="clear"></div>
				
				<?=$this->Form->input('username', array('label' => 'Email'));?>
				
				<div class="clear"></div>
				
				<?=$this->Form->input('password', array('label' => 'Password'));?>
				
				<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'class' => 'confirm_password', 'type' => 'password', 'onpaste' => false));?>
				
				<div class="clear"></div>
					
				<div class="input">
					<label>&nbsp;</label>
					<?=$this->Form->submit('Registrati',array('type' => 'submit','div' => false));?>
				</div>		
				
				<?=$this->Form->end();?>
				
			</div><!-- close contents-box -->
