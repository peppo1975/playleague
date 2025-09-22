<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	var id_campionato = $("#RankingDummyCampionato").val();
	$("#SquadreCampionatiCampionato").val(id_campionato);
	$("#HalfDescrizione").removeAttr('disabled');
	$.get('/admin/rankings/gironeSearch/' + id_campionato, function(ret){
	
		var newOptions = ret;
							 
		var select = $('#HalfDescrizione');
		if(select.prop) {
		  var options = select.prop('options');
		}
		else {
		  var options = select.attr('options');
		}
		$('option', select).remove();
		 
		$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
		});
		
		var val = '<?=(isset($this->data['Half']['Descrizione']))? $this->data['Half']['Descrizione'] : '';?>';						
		select.val(val);
	
	
	}, 'json');

	$("#RankingDummyCampionato").live('change', function() {
	
		id_campionato = $("#RankingDummyCampionato").val();
		$("#SquadreCampionatiCampionato").val(id_campionato);
		$("#HalfDescrizione").removeAttr('disabled');
		$.get('/admin/rankings/gironeSearch/' + id_campionato, function(ret){
		
			var newOptions = ret;
								 
			var select = $('#HalfDescrizione');
			if(select.prop) {
			  var options = select.prop('options');
			}
			else {
			  var options = select.attr('options');
			}
			$('option', select).remove();
			 
			$.each(newOptions, function(val, text) {
			options[options.length] = new Option(text, val);
			});				
		
		}, 'json');

	 });
	 
	$("#HalfDescrizione").focus(function() {

		var id_campionato = $("#RankingDummyCampionato").val();
		$("#HalfDescrizione").attr('data-url','/admin/rankings/searchGirone/' + id_campionato);

	});

});
}
</script>

	<?=$this->Form->create('Ranking', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca classifiche</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('dummy_Campionato', array('type' => 'select', 'options' => $campionati, 'label' => 'Campionato'));?>
	<?=$this->Form->input('SquadreCampionati.Campionato', array('type' => 'hidden'));?>
	
	<div class="clear"></div>	
	
	<?$disabled = 1;?>

	<?=$this->Form->input('Half.Descrizione',array('label' => 'Girone', 'disabled' => 'disabled', 'type' => 'select'));?>
		
	<div class="clear"></div>	
	
	<?=$this->Form->end();?>
