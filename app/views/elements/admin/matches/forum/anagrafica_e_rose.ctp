<div id="anagrafica">

<script type="text/javascript">

$(document).ready(function(){
	
	$.get('/midland/jumpbox.php', function(data){
		
		$("#anagrafica #PrintAnagraficaAdminForumExportForm").prepend(data);
		
	},'html');
	
});

</script>

<script type="text/javascript">
$(function() {
	
	$("#anagrafica").delegate("#anagrafica #PrintAnagraficaCampionato","change", function() {
	
		if($(this).val() == 'default') {
		
			$('.gironi').hide();
			$('.giornate').hide();
			
			return false;
		
		}
		
		$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
	
				if (ret != undefined && ret.length > 0) {
					
					$("#anagrafica .gironi div.input .checkbox").remove();
					
					for (var i = 0; i < ret.length; i++) {
				
						$("#anagrafica .gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
				
					}
					
					$("#anagrafica .gironi").show();
					
				}
	
			
		},'json');

	});
	
	$("#anagrafica").delegate("#anagrafica .printButton","click", function() {
		
		var data = $("#anagrafica #PrintAnagraficaAdminForumExportForm").serialize();
		
		if(confirm('Sei sicuro di voler aggiungere la giornata di campionato al forum?')) {
		
		$.post('/admin/matches/getAnagrafica', data, function(data_return){
			
			$.post('/midland/posts_anagrafica', { "data" : data_return }, function(ret){
				
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
	
	
	$("#anagrafica").delegate("#anagrafica .checkGironi","change",function() {
		
		if ($("#anagrafica .checkGironi:checked").length > 0) $("#anagrafica .printButton").removeAttr('disabled');
		else $("#anagrafica .printButton").attr('disabled','disabled');
		
		var this_class = $(this).attr('class'); $('.' + this_class).not(this).attr('checked',false);
		
	});
	
	
});

</script>

<div id="forum-jumpbox" class="form">

<div class="options">

				<?=$this->Form->create('PrintAnagrafica');?>
				
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