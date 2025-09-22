<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	function getDay() {
	
	$("#ComunicationGiornata").empty();
	
		$.get('/admin/campionatis/countGiornate/2/' + $("#ComunicationGironeCampionato").val(), function(ret){
		
			for(i = 1; i <= ret.casa; i++) {
			
				var option = $('<option>').attr('value', i).text('Giornata ' + i);
				    $("#ComunicationGiornata").append(option);
			
			}
		
		},'json');
	
	}

	$('.formAdd').delegate('#ComunicationCampionato','change', function(){
	
		var value  = $(this).val();
		var select = $("#ComunicationGironeCampionato").empty();
		
		$.get('/admin/comunications/findHalf/' + value, function(data){
		
			for(i in data) {
			
				var option = $('<option>').attr('value', i).text(data[i]);
					select.append(option);
			
			}
			
			getDay();
		
		},'json');
	
	});
	
	$('.formAdd').delegate("#ComunicationGironeCampionato","change", function(){
	
		getDay();
	
	});
	
	$("#ComunicationCampionato").trigger('change');

});
}
</script>

	<?=$this->Form->create('Comunication', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova comunicazione</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Campionato', array('label' => 'Campionato', 'type' => 'select', 'options' => $campionati, 'empty' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GironeCampionato', array('label' => 'Girone', 'type' => 'select'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Giornata', array('label' => 'Giornata', 'type' => 'select'));?>

	<div class="clear"></div>	
	
	<?=$this->Form->input('Note', array('label' => 'Note'));?>

	<div class="clear"></div>	
		
	<?=$this->Form->end();?>
