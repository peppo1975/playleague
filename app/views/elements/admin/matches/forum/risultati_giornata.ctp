<div id="rgiornata">

<script type="text/javascript">

$(document).ready(function(){
	
	$.get('/midland/jumpbox.php', function(data){
		
		$("#PrintAdminForumExportForm").prepend(data);
		
	},'html');
	
});

</script>

<script type="text/javascript">
$(function() {
	
	$("#rgiornata").delegate("#PrintCampionato","change", function() {
	
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
			
			$(".giornate div.input .checkbox").remove();
			
			for (var i = 0; i < giornate; i++) {
				
				$(".giornate div.input").append('<div class="checkbox"><label>' + (i+1) + '</label><input type="checkbox" name="data[Print][Giornate][]" class="checkGiornate" value="' + (i+1) + '"/></div>');
				
			}
			
			$(".giornate").show();
			
			
		},'json');
		
		$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
	
				if (ret != undefined && ret.length > 0) {
					
					$(".gironi div.input .checkbox").remove();
					
					for (var i = 0; i < ret.length; i++) {
				
						$(".gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
				
					}
					
					$(".gironi").show();
					
				}
	
			
		},'json');

	});
	
	$("#rgiornata").delegate(".printButton","click", function() {
		
		var data = $("#PrintAdminForumExportForm").serialize();
		
		if(confirm('Sei sicuro di voler aggiungere la giornata di campionato al forum?')) {
		
		$.post('/admin/matches/getMatchesInfo/Print', data, function(data_return){
			
			$.post('/midland/posts', { "data" : data_return }, function(ret){
				
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
	
	
	$("#rgiornata").delegate(".checkGironi, .checkGiornate","change",function() {
		
		if ($(".checkGironi:checked").length > 0 && $(".checkGiornate:checked").length > 0) $(".printButton").removeAttr('disabled');
		else $(".printButton").attr('disabled','disabled');
		
		var this_class = $(this).attr('class'); $('.' + this_class).not(this).attr('checked',false);
		
	});
	
	
});

</script>

<div id="forum-jumpbox" class="form">

<div class="options">

				<?=$this->Form->create('Print');?>
				
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