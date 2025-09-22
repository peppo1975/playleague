<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	$("#ComunicationCampionato").find('option').each(function(){
	
		if($(this).text() == '<?=$this->data['Comunication']['Campionato'];?>') {
		
			$(this).attr('selected', 'selected');
			
			getGironi($(this).val());
		
		}
	
	});

	function getDay() {
	
	$("#ComunicationGiornata").empty();
	
		$.get('/admin/campionatis/countGiornate/2/' + $("#ComunicationGironeCampionato").val(), function(ret){
		
			for(i = 1; i <= ret.casa; i++) {
			
				var option = $('<option>').attr('value', i).text('Giornata ' + i);
				
				if (i == '<?=$this->data['Comunication']['Giornata'];?>') option.attr('selected','selected');
				
				    $("#ComunicationGiornata").append(option);
			
			}
		
		},'json');
	
	}
	
	function getGironi(value) {

		var select = $("#ComunicationGironeCampionato").empty();
		
		$.get('/admin/comunications/findHalf/' + value, function(data){
		
			for(i in data) {
			
				var option = $('<option>').attr('value', i).text(data[i]);
				
				if (i == '<?=$this->data['Comunication']['GironeCampionato'];?>') option.attr('selected','selected');
				
					select.append(option);
			
			}
			
			getDay();
		
		},'json');	
	
	}

	$('.formAdd').delegate('#ComunicationCampionato','change', function(){
	
		getGironi($(this).val());
	
	});
	
	$('.formAdd').delegate("#ComunicationGironeCampionato","change", function(){
	
		getDay();
	
	});

});
}
</script>

	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Comunication', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica comunicazione: <span> Girone <?=$this->data['Half']['Descrizione'] . ' - Giornata ' . $this->data['Comunication']['Giornata'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->

	<?=$this->Form->input('Bollettino');?>

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
	