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
			
<div id="athlete-form" class="reserved-area form-container">	

	<script type="text/javascript">     
	
		$(function(){
		
			$('.error-message').each(function(){
			
				var error = $(this);
				var div   = error.parents('.input');
				
					$('<div class="error-message">'+error.text()+'</div>').insertAfter(div.find('label'));
				
					$(this).remove();
			
			});		
			
			if($('#flashMessage').length > 0) {
			
			var ok_message = $('#flashMessage').text();
							 $('#flashMessage').remove();
							 
				$('<div>').addClass('ok-message').text(ok_message).insertAfter('.breadcrumbs-container');
				
			}
		
		});	
	
	
	</script>	

				<?=$this->Form->create('User', array('url' => '/mobile/profilo/' . $this->data['User']['id'] . '/' . 'User','type' => 'file', 'id' => 'profile-form', 'data-ajax' => 'false'));?>
					
					<?=$this->Form->input('id');?>
					
					<?=$this->Form->input('nome', array('label' => 'Nome'));?>
					<?=$this->Form->input('cognome', array('label' => 'Cognome'));?>
				
				<div class="clear"></div>

				<?=$this->Form->input('data_nascita', array('label' => 'Data di nascita', 'empty' => '-', 'separator' => false, 'dateFormat' => 'DMY', 'maxYear' => date("Y") - 18, 'minYear' => 1945, 'monthNames' => false));?>
				<?=$this->Form->input('nazione', array('label' => 'Nazione'));?>
				<div class="clear"></div>
				
				<?=$this->Form->input('username', array('label' => 'Email', 'readonly' => true));?>
				
				<div class="clear"></div>
				
				<script type="text/javascript">
				
					$(function(){
						
						$('#UserPassword').keyup(function(){
							
							$('#UserPasswordHidden').val($(this).val());
							
						});
						
					});
				
				</script>
				
				<?=$this->Form->input('password', array('label' => 'Password', 'type' => 'password'));?>
				<?=$this->Form->input('password_hidden', array('type' => 'hidden'));?>
				<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'class' => 'confirm_password', 'type' => 'password', 'onpaste' => false));?>
				
				<div class="clear"></div>
					
				<div class="input">
					<label>&nbsp;</label>
					<?=$this->Form->submit('Modifica profilo',array('type' => 'submit','div' => false));?>
				</div>		
				
				<?=$this->Form->end();?>
				
</div><!-- close athlete-form -->