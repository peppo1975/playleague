<div id="marcatori">

<script type="text/javascript">

$(document).ready(function(){
	
	$.get('/midland/jumpbox.php', function(data){
		
		$("#marcatori #PrintMarcatoriAdminForumExportForm").prepend(data);
		
	},'html');
	
});

</script>

<script type="text/javascript">
$(function() {
	
	$("#marcatori").delegate("#marcatori #PrintMarcatoriCampionato","change", function() {
	
		if($(this).val() == 'default') {
		
			$('.gironi').hide();
			$('.giornate').hide();
			
			return false;
		
		}
		
		$.get("/admin/prints/getDay/" + $(this).val(),function(ret) {
			
			if (ret.find == undefined) 
				var giornate = 0; 
			else
				var giornate = ret.find;
			
			$("#marcatori .giornate div.input .checkbox").remove();
			
			for (var i = 0; i < giornate; i++) {
				
				$("#marcatori .giornate div.input").append('<div class="checkbox"><label>' + (i+1) + '</label><input type="checkbox" name="data[Print][Giornate][]" class="checkGiornate" value="' + (i+1) + '"/></div>');
				
			}
			
			$("#marcatori .giornate").show();
			
			
		},'json');
		
		$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
	
				if (ret != undefined && ret.length > 0) {
					
					$("#marcatori .gironi div.input .checkbox").remove();
					
					for (var i = 0; i < ret.length; i++) {
				
						$("#marcatori .gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
				
					}
					
					$("#marcatori .gironi").show();
					
				}
	
			
		},'json');

	});
	
	$("#marcatori").delegate("#marcatori .printButton","click", function() {
		
		var data = $("#marcatori #PrintMarcatoriAdminForumExportForm").serialize();
		
		if(confirm('Sei sicuro di voler aggiungere la giornata di campionato al forum?')) {
		
		$.post('/admin/matches/getMarcatori', data, function(data_return){
			
			$.post('/midland/posts_marcatori', { "data" : data_return }, function(ret){
				
				if(ret.data != 0) {
					
					if(confirm('Post aggiunto correttamente al forum. Vuoi visitarlo ora?')) {
	
						window.open('http://midlandsport.forum/viewtopic.php?f=' + ret.f + '&t=' + ret.t);
						
					}
					
				} else {
					
					alert('Post gia esistente');
					
				}
				
				timmy_close();
				
			},'json');			
			
		},'json');
		
		}
			
	});
	
	
	$("#marcatori").delegate("#marcatori .checkGironi, #marcatori .checkGiornate","change",function() {
		
		if ($("#marcatori .checkGironi:checked").length > 0 && $("#marcatori .checkGiornate:checked").length > 0) $("#marcatori .printButton").removeAttr('disabled');
		else $("#marcatori .printButton").attr('disabled','disabled');
		
		var this_class = $(this).attr('class'); $('.' + this_class).not(this).attr('checked',false);
		
	});
	
	
});

</script>

<div id="forum-jumpbox" class="form">

<div class="options">

				<?=$this->Form->create('PrintMarcatori');?>
				
				<div class="clear"></div>
				
				<?=$this->Form->input('Titolo', array('type' => 'text', 'label' => 'Titolo'));?>
				
				<div class="clear"></div>	
				
				<?=$this->Form->input('Campionato', array('type' => 'select', 'label' => 'Campionato', 'options' => $campionati, 'div' => false));?>

				<div class="clear"></div>

				<div class="giornate" style="display: none;">

				<?=$this->Form->input('Giornate', array(
				'type' => 'select',
				'label' => 'Giornate',
				'multiple' => 'checkbox',
				'options' => array(
				)
				));?>
				
				</div>
				
				<div class="gironi" style="display: none;">

				<?=$this->Form->input('Girone', array(
				'type' => 'select',
				'label' => 'Gironi',
				'multiple' => 'checkbox',
				'options' => array(
				)
				));?>
				
				</div>

				<div class="clear"></div>
				
				<?=$this->Form->input('Testo', array('type' => 'textarea', 'label' => 'Testo aggiuntivo.'));?>
				
				<div class="clear"></div>
						
				<?=$this->Form->button('Invia al forum', array('type' => 'button', 'class' => 'printButton', 'disabled' => 'disabled','div' => true,'label' => ''));?>
						
				<?=$this->Form->end();?>

</div>

</div>

</div>