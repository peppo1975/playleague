<script type="text/javascript">
	$(function(){
		$("#recoverUser").live('submit',function() {
			
			$(this).find('.error-message').html('&nbsp;');
			
			var goOn = true;
			
			$(this).find('div.required').each(function() {
				
				var input = $(this).find('input.text').val();
				
				
				if ($.trim(input) == '') {
			
					$(this).find('.error-message').text('Campo obbligatorio').show();
				
					goOn = false;
			
				}
			
			});
			
			var email = $(this).find('input[name="data[User][username]"]');
			
			if (!isValidEmail(email.val())) {
				
				email.closest('div.input').find('.error-message').text('Inserire un indirizzo e-mail valido').show();
				
				goOn = false;
				
			} 
			
			if (goOn == false) return false;
			
			var goTo = $(this).attr('action');
			
			var form = $(this);
			
			$.post(goTo,$(this).serialize(),function(data) {
				
				if (data.found == 1) {
				var nome = form.find('input[name="data[User][nome]"]').val();
				var cognome = form.find('input[name="data[User][cognome]"]').val();
				
				$("#recoverUser").html(
				'<h3>Grazie ' + nome + " " + cognome + ",</h3><br />" +
				'La sua procedura di recupero password &egrave; stata completata, ricever&agrave; al pi&ugrave; presto le nuove credenziali di accesso via e-mail'
				);
				
				} else {
				
				$("#recoverUser").html(
				'<h3>Siamo spiacenti,</h3><br />' +
				'Non è stata trovata alcuna corrispondenza con i dati da lei inseriti'
				);
				
					
				}
			},'json');
			
			return false;
			
		});
		
		$(function(){
		$(".confirm_password").live('paste', function(){return false;});
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
				<a data-ajax="false" href="/mobile/login" title="Login">			
				Login
				</a>
				&rsaquo; 
			</li>			
			<li>
				Recupero password
			</li>
			
		</ul>
		
	</div>

				<div class="ui-tabs-container ui-tab-passrecovery form-container">

							<h2>Recupero password</h2>
						
							<form method="post" id="recoverUser" action="/mobile/passrecovery/user" data-ajax="false">
							
							<div class="input required">
							
								<label>Indirizzo e-mail</label><div class="error-message">&nbsp;</div>
								<input type="text" class="text" name="data[User][username]" />
							</div>
							
							<div class="clear"></diV>
							
							<div class="input required">
								
								<label>Nome</label><div class="error-message">&nbsp;</div>
								<input type="text" class="text" name="data[User][nome]" />
							
							</div>
							
							<div class="input required">
						
								<label>Cognome</label><div class="error-message">&nbsp;</div>
								<input type="text" class="text" name="data[User][cognome]" />
								
							</div>
						
							<div class="input">
								<label>&nbsp;</label>
								<input type="submit" class="submit" value="Recupera password" />
								<a class="forgotten-password" data-ajax="false" title="Login" href="/mobile/login">Login</a>
								<div class="clear"></div>								
							</div>
											
						</form>

				</div> <!-- close ui-tabs-container -->
