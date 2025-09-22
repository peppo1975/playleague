<script type="text/javascript">

$(function(){
	
	$('.isNumber').live('keydown',function(e) {
	
		var code = e.keyCode;
			
		if(isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46) return false;
		
	});
	
			if($('#flashMessage').length > 0) {
			
			var ok_message = $('#flashMessage').text();
							 $('#flashMessage').remove();
							 
				$('<div>').addClass('ok-message').text(ok_message).insertAfter('.breadcrumbs-container');
				
			}	
	
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
			<a data-ajax="false" href="/mobile/reserved" title="Gestione profilo">
				Gestione profilo
			</a>
			&rsaquo;
		</li>
		<li>
			Informazioni personali
		</li>
		
	</ul>
	
</div>

						
<div id="athlete-form" class="reserved-area">	

	<?=$this->Form->create('Athlete', array('class' => 'edit-athlete', 'url' => '/mobile/profilo/' . $this->data['Athlete']['Atleta'] . '/' . 'Athlete', 'type' => 'file', 'id' => 'profile-form', 'data-ajax' => 'false'));?>

	<?=$this->Form->input('Atleta');?>
	
	<?=$this->Form->input('Cognome', array('readonly' => true));?>
	<?=$this->Form->input('Nome', array('readonly' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Indirizzo', array('readonly' => true));?>
	<?=$this->Form->input('Cap', array('readonly' => true));?>
	<div class="clear"></div>
	
	<?=$this->Form->input('Localita', array('readonly' => true));?>
	
	<?=$this->Form->input('Provincia', array('readonly' => true));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('Telefono', array('class' => 'isNumber enabled'));?>
	<?=$this->Form->input('Cellulare', array('class' => 'isNumber enabled'));?>
	<div class="clear"></div>
	
	<?=$this->Form->input('Lavoro',array('label' => 'Telefono lavoro', 'class' => 'isNumber enabled'));?>
	<?=$this->Form->input('Email', array('readonly' => true));?>
	<div class="clear"></div>
	
	<?=$this->Form->input('password', array('type' => 'password','label' => 'Password', 'class' => 'enabled'));?>
	<?=$this->Form->input('password_confirm', array('type' => 'password','label' => 'Conferma password', 'class' => 'confirm_password enabled'));?>
	<div class="clear"></div>
	
	<?=$this->Form->input('Fax', array('class' => 'isNumber enabled'));?>	
	<?=$this->Form->input('CodiceFiscale', array('label' => 'Codice fiscale', 'readonly' => true));?>	
	<div class="clear"></div>
	
	<?=$this->Form->input('LuogoNascita',array('label' => 'Luogo di nascita', 'readonly' => true));?>
	<?=$this->Form->input('DataNascita_it',array('label' => 'Data di nascita', 'readonly' => true));?>
		
	<div class="clear"></div>
	<!-- <?=$this->Form->input('Sesso',
	array(
	
	'type' => 'radio',
	'options' => array( 'Maschio'=>'M', 'Femmina'=>'F' ),
	'disabled' => true,

	));?>

	<div class="clear"></div> -->
	
	<?=$this->Form->input('do_not_convert',array('type' => 'hidden'));?>
	
				<div class="input">
					<label>&nbsp;</label>
					<?=$this->Form->submit('Modifica profilo',array('type' => 'submit','div' => false));?>
				</div>			
		
	<?=$this->Form->end();?>
</div>
