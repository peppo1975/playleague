<script type="text/javascript">

var submitting = 0;

function isValidEmail(str) {
   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
}


$(function() {

	$(".login-username.error").live('focus',function() {
		
		
		$(this).removeClass('error');
		$(this).val('');
		
		
	});
	
	$(".login-password-tmp").live('focus',function() {
		
		$(this).hide();
		var pw = $(this).closest('div').find('.login-password');
		pw.show().val('').focus();
		
	});
	
	$("#recover-pw").click(function() {
	
		$("#login-form").hide();
		$("#recover-form").show();
		
	});
	
	$("#reset-recover").click(function(){
		
		$("#login-form").show();
		$("#recover-form").hide();		
		
	});
		
	$("#recover-form").bind('submit',function(e) {
		
	    if (submitting == 1) return false;

		e.stopPropagation();
		e.preventDefault();
		
		$(this).find('input').blur();
		
		var username = $(this).find('.login-username').val();

		var noerrors = 1;
		
		if (!isValidEmail(username)) {
		
			$(this).find('.login-username').addClass('error').val('indirizzo email non valido');
			noerrors = 0;
		}
		
		
		var me = $(this);
		
		if (noerrors == 1) {
			
			submitting = 1;
			
			$(".recover-loader").css('display','block');
			
			$.post("/mobile/sections/recover", { 'username': username },function(ret) {
				
				submitting = 0;
				
				ret = $.parseJSON(ret);
				
				if (ret.User != undefined) {
					
					$(".other-data").hide();
					$(".recover-ok").show().find('.user-nome').text(ret.User.nome + " " + ret.User.cognome);
			
				} else {
					
					$(me).find('.login-username').addClass('error').val('l\'username non esiste');
							
				}
				
			});
			
		}
		
		return false;
		
		
	});
	
	$("#login-form").live('submit',function(e) {
		
	    if (submitting == 1) return false;

		e.stopPropagation();
		e.preventDefault();
		
		$(this).find('input').blur();
		
		var username = $(this).find('.login-username').val();
		var password = $(this).find('.login-password').val();
		
		var noerrors = 1;
		
		if (!isValidEmail(username)) {
		
			$(this).find('.login-username').addClass('error').val('indirizzo email non valido');
			noerrors = 0;
		}
		
		if ($.trim(password) == '') {
			
			$(this).find('.login-password').hide();
			$(this).find('.login-password-tmp').addClass('error').val('campo obbligatorio').show();
			noerrors = 0;
		}
		
		var me = $(this);
		
		if (noerrors == 1) {
			
			submitting = 1;
			
			$.post("/mobile/sections/login", { 'username': username, 'password': password },function(ret) {
				
				submitting = 0;
				
				ret = $.parseJSON(ret);
				
				if (ret.User != undefined) {
					
					location.href = '/mobile/area/riservata';
					
			
				} else {
					
					$(me).find('.login-password').hide();
					$(me).find('.login-password-tmp').addClass('error').val('login incorretto').show();
							
				}
				
			});
			
		}
		
		return false;
		
		
	});
	
});

</script>

			<form method="post" action="<?=$_SERVER['REQUEST_URI'];?>" id="login-form" data-ajax="false">
		
			<div class="box-login">
				<h3>Accesso area riservata</h3>
				<div class="user">
					<label>Username</label>
					<input type="email" class="login-username" value="" />
				</div>
				<div class="psw">
					<label>Password</label>
					<input type="password" class="login-password" value="" />
					<input type="text" class="login-password-tmp" value="" />
				</div>
				<a href="javascript:;" id="recover-pw" title="recupera password">Recupera password</a>
				<div class="function-button">
					<div class="login" onclick="$('#login-form').submit();">
						<input type="submit" class="submit-button" value="Login" />
					</div>
					<div class="annulla" onclick="$.mobile.changePage( '/mobile', { transition: 'slidedown'} );">
						<input type="button" value="Annulla" class="button-annulla" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
			
			</form>
			
		
			<form method="post" action="<?=$_SERVER['REQUEST_URI'];?>" id="recover-form" data-ajax="false">
		
			<div class="box-login">
				<div class="recover-ok">
				
					<h3>Grazie <span class="user-nome"></span>,</h3>
					<p>La sua procedura di recupero password è completata, riceverà al più presto le nuove credenziali di accesso via e-mail</p>
				
				</div>
				<div class="other-data">
				<h3>Recupera la password</h3>
			
				<div class="user">
					<label>Username</label>
					<input type="email" class="login-username" value="" />
				</div>
			
				<div class="function-button">
					<div class="login" onclick="$('#recover-form').submit();">
						<input type="submit" value="Recupera" />
					</div>
					<div class="annulla" id="reset-recover">
						<input type="button" value="Annulla" class="button-annulla" />
					</div>
					<div class="clear"></div>
				</div>

				</div>
				
			</div>
			
			</form>