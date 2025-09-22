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
			$.post('/users/checkUsername', {"username":obj.val()}, function(data){
				if(data.count > 0) {
					obj.parent('div').append('<div class="form-msg error-message">Username già esistente.</div>');
				} else {
					obj.parent('div').append('<div class="form-msg ok-message">Username disponibile.</div>');
				}
			},'json');
		}
	});
	
});
</script>


<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Registrazione utenti</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		
		<div class="row">
			<div class="col-md-9">
				
			<h2>Modulo di registrazione utenti</h2>


<div class="alert alert-success">La registrazione è andata a buon fine. A breve riceverai un email con i dati di accesso e il link di conferma.</div>

				
			</div><!-- close contents-box -->

			<div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary">Crea nuovo account</h4>
						<ul class="nav nav-list narrow">
								<li  class="active" >
									<a href="/registrazione" title="">
										Registrazione utenti
									</a>
								</li>
								<li>
									<a href="/registrazione/atleti"  class="" title="">
										Registrazione atleti
									</a>
								</li>

						</ul>
				</aside>
			</div>

		</div><!-- close wrapper-box-contents -->
		
</div><!-- close wrapper-box -->
</div>


