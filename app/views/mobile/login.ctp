<script type="text/javascript">
	$(function(){
	
		$("#loginForm").submit(function() {
		
			var username = $(this).find('.login_username');
			var password = $(this).find('.login_password');
			var form     = $(this);
			var form_data= form.serialize();
			
			$('.error-message').empty();
			
			if ($.trim(username.val()) == '') {
				
				username.closest('div.input').find('.error-message').text('Campo obbligatorio').show();
				
				return false;
				
			}
			
			if (!isValidEmail(username.val())) {
				
				username.closest('div.input').find('.error-message').text('Inserire un indirizzo e-mail valido').show();
				
				return false;
				
			}	
		
			if ($.trim(password.val()) == '') {
				
				password.closest('div.input').find('.error-message').text('Campo obbligatorio').show();
				
				return false;
				
			}
			
			$.post('/mobile/login_exec', form_data, function(data){
			
				if(data.error == 1) {
					
					username.closest('div.input').find('.error-message').text('username o password errati.').show();
					
				} else {
				
					$('<div>').addClass('ok-message').text('login effettuato con successo').insertAfter('.breadcrumbs-container');
					
					$('body,html').animate({ "scrollTop" : $('.ok-message').offset().top-20 });
				
					t = setTimeout(function(){
						
						location.href = '/mobile/reserved';
					
					},5000);
				
					
				
				}
			
			},'json');
			
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
				Login
			</li>
			
		</ul>
		
	</div>

	<div class="login-form form-container">
	
		<h2>Login</h2>
	
		<form method="post" id="loginForm" action="/mobile/login_exec" data-ajax="false">
	
			<div class="input required">
			
				<label>Indirizzo e-mail</label><div class="error-message">&nbsp;</div>
				<div class="clear"></div>
				<input type="text" class="text login_username" name="data[Login][username]" />								
			</div>
			<div class="clear"></div>
			
			<div class="input required">
			
				<label>Password</label><div class="error-message">&nbsp;</div>
				<div class="clear"></div>
				<input type="password" class="text login_password" name="data[Login][password]" />
			</div>
			<div class="clear"></div>
			
			<ul class="checkbox">
			
			<script type="text/javascript">
			$(function(){
				$('.type_login').live('change', function(){
					$('.type_login').not(this).attr('checked',false);
					var value = $('.type_login:checked').attr('data-value');
					if(value != undefined) $("#typeLogin").val(value);
					else $("#typeLogin").val('');
				});
			});
			</script>
			
				<li>
					<input type="checkbox" data-value="athlete" class="text type_login" id="checkAth"/>
					<label>Atleta</label>
					<div class="clear"></div>					
				</li>												
				<li class="last">
					<input type="checkbox" data-value="arb" class="text type_login" id="checkArb"/>
					<label>Arbitro/Delegato</label>
					<div class="clear"></div>					
				</li>
	
				<input type="hidden" value="" id="typeLogin" name="data[Login][type_login]" />
	
			</ul>
			<div class="clear"></div>											
					
			<div class="input">
					<input type="submit" value="Login" />	
					<a class="forgotten-password" data-ajax="false" title="Recupera la tua password di accesso!" href="/mobile/passrecovery">Recupero password</a>
					<div class="clear"></div>
			</div>
		</form>	
	
	</div>