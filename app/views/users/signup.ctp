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
					obj.parent('div').append('<div class="text-danger">Username già esistente.</div>');
				} else {
					obj.parent('div').append('<div class="text-success">Username disponibile.</div>');
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

	<div class="container"  id="main-custom">
		
		<div class="row">
			<div class="col-md-9">
				
			<h2>Modulo di registrazione utenti</h2>


				<div class="row">
					<div class="col-md-12">
								

				<?=$this->Form->create('User', array('url' => '/registrazione', 'id' => 'formSignup',
     
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
        

				)));?>
<fieldset>

<div class="row">
<div class="col-md-6">
				<?=$this->Form->input('nome', array('label' => 'Nome'));?>
				</div>
<div class="col-md-6">


					<?=$this->Form->input('cognome', array('label' => 'Cognome'));?>
</div>
</div>
				<div class="clear"></div>
<div class="row">
<div class="col-md-6 flt">

				<?=$this->Form->input('data_nascita', array('label' => 'Data di nascita', "style"=>"width: 33% !important", "class" => "form-control select2 col-xs-3", 'empty' => true, 'dateFormat' => 'DMY', 'maxYear' => date("Y") - 18, 'minYear' => 1945, 'monthNames' => false,'separator' => ''));?>
</div>
<div class="col-md-6">
				<?=$this->Form->input('nazione', array('label' => 'Nazione'));?>
</div>
</div>
				<div class="clear"></div>
				
				<?=$this->Form->input('username', array('label' => 'Email'));?>
				
				<div class="clear"></div>
				
				<?=$this->Form->input('password', array('label' => 'Password'));?>
				
				<?=$this->Form->input('password_confirm', array('label' => 'Conferma password<sup>*</sup>', 'class' => 'confirm_password form-control', 'type' => 'password', 'onpaste' => false));?>
				
				<div class="clear"></div>
					
				<div class="input">
					<?=$this->Form->submit('Registrati ora',array('type' => 'submit','class' => 'btn btn-primary pull-left mb-xl'));?>
				</div>		
</fieldset>
				<?=$this->Form->end();?>
					</div>
				</div>

			<div class="clear"></div>	

				
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
<style>
.select2-container{
	width: 33% !important;
}
</style>
<script>
$(function(){
	$(".select2").select2();
})
</script>