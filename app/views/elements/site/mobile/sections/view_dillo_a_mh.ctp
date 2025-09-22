<script type="text/javascript">

  function isValidEmail(str) {
			var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
			return emailPattern.test(str);
}

    $(function() {
		
		$(".fakeSubmit").live('click',function() {
			
			var no_errors = checkForm();
			
			if (no_errors == true) {
				
				var data = $(this).closest('form').serialize();
				
				
				$.post("/mobile/sections/curriculum",data,function(ret) {
					
					$("#formMh:visible").html(ret);
					
					
				},'html');
				
				
				
			} else {
				
				$("html, body").animate({ 'scrollTop': 0 },200);
				
			}
			
		});
		

	});
	
	function checkForm() {

		 var formMh_errors = false;

		 $('body').find("#formMh:visible").find('input:text').each(function() {
			
			
			if ($(this).val() == '' || $(this).val() == $(this).attr('data-default')){
				 $(this).addClass('error');
				 
				 if (($(this).attr('name') == 'domanda_extra' || $(this).attr('name') == 'collaborare_extra') && $(this).is(':visible')) {
					 
					 $(this).addClass('border-error');
					 
				 }
				 if ($(this).is(':visible'))
					formMh_errors = true;
			}
			if ($(this).attr('name') == 'email' && !isValidEmail($(this).val())) {
				 formMh_errors = true;
				 $(this).addClass('error');
			}
			 
			
		 });
		 
		 
		$(".box-dillo-mh ul").each(function() {
			
			if ($(this).find('input:checked').length == 0) {
				
				$(this).addClass('no-radio');
				formMh_errors = true;
			}
				
		});
			

	 
		if (formMh_errors != false)
		return false;
		else {
			
			return true;
			
		}
		
	}


$(function() {
	
	$('#formMh').find('input').live('focus', function(){
		
		var me = $(this);
			if(me.val() == me.attr('data-default'))
				me.val('');
		
	});
	
	$("#formMh").find('input[type="radio"]').live('click',function() {
		
		$(this).closest('ul').removeClass('no-radio');
		
	});
	
	$('#formMh').find('input.error').live('focus', function(){
		
		var me = $(this);
			me.removeClass('error').removeClass('border-error').val('');
		
	});	
	
	$('#formMh').find('input').live('blur', function(){
		
		var me = $(this);
			if(me.val() == '')
				me.val(me.attr('data-default'));
		
	});	
	
	
	
	
	$("#curriculum").live('change',function() {
		
		$("span.error-file").fadeOut(200);
		
	});
	
	$("input[name='collaborare']").click(function() {
		
		var container = $(this).closest('li').find('.collaborare_extra_container');
		
		$("input[name='collaborare_extra']").val('').prependTo(container).show();
		
	});
	
	$("input[name='domanda']").change(function() {
		
		if ($(this).val() == 'formula una domanda specifica a Matera HUB') $("input.big[name='domanda_extra']").show();
		else $("input.big[name='domanda_extra']").hide();
		
	});
	
	
});

</script>
<div id="content" class="dillo-mh categories">

	<div class="header-post-detail">
		<h1><?=$section['Section']['title'];?></h1>
		<? if($section['Section']['subtitle'] != ''): ?>
		<h2><?=$section['Section']['subtitle'];?></h2>
		<? endif; ?>
	</div>
	<div class="container-detail-post">


	<div class="form">
		<form id="formMh" autocomplete="off" method="post" enctype="multipart/form-data" action="/mobile/sections/curriculum">
			<h3>Chi sono?</h3>
			<div class="box-dillo-mh">
				<div class="container-input">
					
					<div class="input required select-input">
					
					<select class="select" name="tipologia">
					
							<option value="Ente pubblico">Ente pubblico</option>
							<option value="Impresa">Impresa</option>
							<option value="Libero professionista">Libero professionista</option>
							<option value="Associazione">Associazione</option>
							<option value="Studente">Studente</option>
							<option value="Altro">Altro</option>
					
					</select>
					
					<div class="clear"></div>
					
					</div>
					
					<div class="clear"></div>
					
					<div class="input required">
			
							<input class="text" type="text" name="nome" value="Nome" data-default="Nome"/>
					</div>
			
					<div class="input required">
						<input class="text" type="text" name="cognome" value="Cognome" data-default="Cognome"/>
					</div>
					<div class="clear"></div>
				</div>
				<div class="container-input">
					<div class="input required">
						<input class="text" type="text" name="email" value="E-mail" data-default="E-mail"/>
					</div>
					<div class="input required">
						<input class="text" type="text" name="cellulare" value="Cellulare" data-default="Cellulare"/>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<h3>Cosa chiedo a Matera HUB?</h3>
			<div class="box-dillo-mh">
				<ul>
					<li>
						<input type="radio" value="informazioni sulle attività di Matera HUB" name="domanda"/><span>informazioni sulle attività di Matera HUB</span><div class="clear"></div>
					</li>
					<li>
						<input type="radio" value="di incontrare Matera HUB" name="domanda"/><span>di incontrare Matera HUB</span><div class="clear"></div>
					</li>
					<li>
						<input type="radio" value="formula una domanda specifica a Matera HUB" name="domanda"/><span>formula una domanda specifica a Matera HUB:</span><div class="clear"></div>
						<input class="big" type="text" name="domanda_extra"/>
					</li>
				</ul>
			</div>
			<h3>Cosa offro a Matera HUB</h3>
			<div class="box-dillo-mh collaboratore-extra">
				<ul>
					<li>
						<input type="radio" value="ho una idea e la voglio condividere" name="collaborare"/><span>ho una idea e la voglio condividere</span><div class="clear"></div>
						<div class="collaborare_extra_container">
							<input class="big" type="text" value="" autocomplete="off" name="collaborare_extra"/>
							<div class="clear"></div>
						</div>
					</li>
					<li>
						<input type="radio" value="sono un'impresa e ho un servizio da condividere" name="collaborare"/><span>sono un'impresa e ho un servizio da condividere</span><div class="clear"></div>
						<div class="collaborare_extra_container">
								<div class="clear"></div>
								</div>
					</li>
					<li>
						<input type="radio" value="sono un Ente pubblico/privato e voglio condividere un bene comune" name="collaborare"/><span>sono un Ente pubblico/privato e voglio condividere un bene comune</span><div class="clear"></div>
						<div class="collaborare_extra_container">
								<div class="clear"></div>
						</div>
					</li>
					<li>
						<input type="radio" value="voglio investire su una idea/progetto innovativo" name="collaborare" /><span>voglio investire su una idea/progetto innovativo</span><div class="clear"></div>
						<div class="collaborare_extra_container">
								<div class="clear"></div></div>
					</li>
				</ul>
			</div>
			<div class="submit-container">
				<input class="submit fakeSubmit" type="button" value="invia"/>
				<input data-theme="b" class="reset" type="reset" value="reset">
				<div class="clear"></div>
			</div>
		</form>
	</div>
	</div>
	
	<div class="clear"></div>
	
</div>
