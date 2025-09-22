
	<script type="text/javascript">
	if (typeof $ != "undefined") {
		$("#RankingCampionato").live('change',function() {
			
			var champ_id = $(this).val();
			
			if (champ_id == undefined || champ_id == '') {
				
					 $("#RankingGironeCampionato").attr('disabled','disabled');
					 $(".refreshSubmit").attr('disabled','disabled');
					 $("#RankingGironeCampionato").html('');
					 
					 timmyloader('hide');
				
					 return;
					 
					 
			}
			
			$("#RankingGironeCampionato").html('');
			
			timmyloader('show');
			
			$.get('/admin/rankings/searchGirone/' + champ_id + '?term=',function(ret) {
				
				timmyloader('hide');
				
				for (var i = 0; i < ret.length; i++) {
					
					$("#RankingGironeCampionato").append('<option value="' + ret[i].id + '">' + ret[i].label + '</option>');
					
				}
				
				if (ret.length > 0) {
					 $("#RankingGironeCampionato").removeAttr('disabled');
					 $(".refreshSubmit").removeAttr('disabled');
				}
				
				
			},'json');
			
		});
	
		$(".refreshSubmitForm").live('submit',function(e) {
				
				timmyloader('show');		
				
				e.stopPropagation();
				e.preventDefault();
				
				var url = $(this).attr('action');
				var data = $(this).serialize();
				
				$.post(url,data,function(ret) {

						
						timmyloader('hide');					
						
					$.post('/admin/rankings/refreshSave', { "ret2": ret },function(ret2) {

						if(ret2.update == 1) alert("Classifica aggiornata.");
							else alert("Aggiornamento classifica non riuscito.");
					
					},'json');
						
				
					
				},'json');
				
				
				return false;
			
			
		});
	}
	</script>

	<?=$this->Form->create('Ranking', array('action' => 'refresh','prefix' => 'admin','class' => 'refreshSubmitForm'));?>

	<div class="form_header">

		<h2>Aggiorna classifica</h2>
		
	</div>
	
	<?=$this->Form->input('Campionato', array('type' => 'select', 'options' => $campionati));?>

	<div class="clear"></div>
		
	<?=$this->Form->input('GironeCampionato', array('type' => 'select','label' => 'Girone', 'disabled' => 'disabled'));?>
	
	<div class="clear"></div>
	
	<div class="input">
	<label>&nbsp;</label>
	<?=$this->Form->submit('aggiorna',array('type' => 'submit','div' => false,'disabled' => 'disabled','class' => 'refreshSubmit'));?>
	</div>
	<div class="clear"></div>
		
	<?=$this->Form->end();?>
