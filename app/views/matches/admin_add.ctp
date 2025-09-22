<?=$this->element("/backend/add_edit_scripts");?>
<script type="text/javascript">
if (typeof $ != "undefined") {
$(function() {

	function getCampi() {
	
	console.log('executed');
	
		$("#MatchCampo").empty();
	
			$.get($("#MatchCampo").attr('data-url'), function(data){
			
				for(i in data) {
				
					var option = $('<option>').attr('value', data[i].id).text(data[i].label);
					$("#MatchCampo").append(option);
				
				}
				
			},'json');	
	
	}
	
	$('.formAdd').delegate("#MatchCampionato","change", function(){
	
		var val = $(this).val();
		
		if(val != '') {
		
			$("#MatchCampo").attr('data-url','');
			$("#MatchCampo").attr('data-url','/admin/matches/searchCampoByCampionato/'+val);
			$("#MatchCampo").attr('disabled', false);
			
			$("#MatchOra").attr('data-url', '');
			$("#MatchOra").attr('data-url', '/admin/matches/getOre/'+val);
			$("#MatchOra").attr('disabled', false);
			
			getCampi();
		
		}
	
	});
	
$('.formAdd').delegate("#MatchCampionatoSearch","change", function() {

	$("#MatchGironeSearch").removeAttr('disabled');
	if($("#MatchGironeSearch").val() == '') $("#MatchGironeSearch").val('');
	if($("#MatchSquadraCasaSearch").val() == '') $("#MatchSquadraCasaSearch").val('');
	if($("#MatchSquadraTrasfertaSearch").val() == '') $("#MatchSquadraTrasfertaSearch").val('');
	//$("#MatchGironeSearch").focus();
	
 });
 
$('.formAdd').delegate("#MatchGironeSearch","focus", function() {

	var id_campionato = $("#MatchCampionato").val();
	$("#MatchGironeSearch").attr('data-url','/admin/matches/searchGirone/' + id_campionato);

});
$('.formAdd').delegate("#MatchGironeCampionato","change", function() {

	if($(this).val() != '') {

		$("#MatchSquadraCasaSearch").removeAttr('disabled');
		if($("#MatchSquadraCasaSearch").val() == '') $("#MatchSquadraCasaSearch").val('');
		$("#MatchSquadraTrasfertaSearch").removeAttr('disabled');
		if($("#MatchSquadraTrasfertaSearch").val() == '') $("#MatchSquadraTrasfertaSearch").val('');
	
	}

 });
 $('.formAdd').delegate("#MatchSquadraCasaSearch","focus", function() {

	var id_campionato = $("#MatchCampionato").val();
	var id_girone = $("#MatchGironeCampionato").val();
	$("#MatchSquadraCasaSearch").attr('data-url','/admin/matches/searchSquadraCampionato/' + id_campionato + '/' + id_girone);

});


 $('.formAdd').delegate("#MatchData","change", function() {

 
		var data = $(this).val();
		var me = $(this);
		$.post('/admin/matches/checkdate',{ 'data': data },function(ret) {
		
		
				var ret = parseInt(ret);
				
				if (ret > 0) {
				
					if (confirm('La data scelta e\' un giorno di non gioco, vuoi procedere comunque?')) {
					
					} else {
					
						me.val('');
					}
				
				}
			
		},'html');
 
});

$('.formAdd').delegate("#MatchSquadraTrasfertaSearch","focus",function() {

	var id_campionato = $("#MatchCampionato").val();
	var id_girone = $("#MatchGironeCampionato").val();
	$("#MatchSquadraTrasfertaSearch").attr('data-url','/admin/matches/searchSquadraCampionato/' + id_campionato + '/' + id_girone);

});	

t = setTimeout(function(){ 
	
	if($("#MatchData").parent('div').find('.error-message').length > 0) $("#MatchData").val('');
	if($("#MatchOra").parent('div').find('.error-message').length > 0); $("#MatchOra").val('');
	
	$("#MatchCampionatoSearch").trigger('change');
	 $("#MatchCampionato").trigger('change'); 
	 $("#MatchGironeCampionato").trigger('change');
	 getCampi(); 
	 
},'1000');
	
});



}
</script>

	<?=$this->Form->create('Match', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova gara</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
<h3>Creazione gara</h3>
			
			<?=$this->Form->input('Data', array('label' => 'Data', 'type' => 'text', 'class' => 'datePicker'));?>
			
			<?=$this->Form->input('Ora', array('label' => 'Ora', 'disabled' => true, 'type' => 'text', 'class' => 'autoComplete control_ora small', 'data-url' => '/admin/matches/getOre', 'data-dest' => 'MatchOraFittizio'));?>
			
			<?=$this->Form->input('CampionatoSearch',array('label' => 'Campionato','class' => 'autoComplete big','data-url' => '/admin/matches/searchCampionato','data-dest' => 'MatchCampionato'));?>
			<?=$this->Form->input('Campionato', array('type' => 'hidden'));?>
			<?=$this->Form->input('GironeSearch',array('label' => 'Girone','disabled' => 'disabled', 'class' => 'autoComplete','data-url' => '/admin/matches/searchGirone','data-dest' => 'MatchGironeCampionato'));?>
			<?=$this->Form->input('GironeCampionato', array('type' => 'hidden'));?>
			
			
			<?=$this->Form->input('SquadraCasaSearch',array('label' => 'Casa','disabled' => 'disabled','class' => 'autoComplete','data-url' => '/admin/matches/searchSquadraCampionato','data-dest' => 'MatchCasa'));?>
			<?=$this->Form->input('Casa', array('type' => 'hidden'));?>
			<?=$this->Form->input('SquadraTrasfertaSearch',array('label' => 'Trasferta','disabled' => 'disabled','class' => 'autoComplete','data-url' => '/admin/matches/searchSquadraCampionato','data-dest' => 'MatchTrasferta'));?>
			<?=$this->Form->input('Trasferta', array('type' => 'hidden'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('Giornata', array('label' => 'Giornata', 'type' => 'text'));?>
			<?=$this->Form->input('Partita', array('label' => 'Partita', 'type' => 'text'));?>
			
	<? if($layout == "tablet"): ?>
		<div class="clear"></div>
	<? endif; ?>			
			
			<?=$this->Form->input('Campo', array('type' => 'select', 'label' => 'Nome campo', 'data-url' => '/admin/matches/searchCampoByCampionato'));?>
			

			<?=$this->Form->input('Bloccato',
				array(
				
				'type' => 'radio',
				'options' => array( 'S'=>'Si', 'N'=>'No' ),

				));?>
			<?=$this->Form->input('Festivo',
			array(
			
			'type' => 'radio',
			'options' => array( 'S'=>'Si', 'N'=>'No' ),

			));?>
			
			
			<?=$this->Form->input('NomeGara', array('label' => 'Nome gara', 'type' => 'text'));?>
			
			
			<?
			$options = array();
			$options[''] = '';
			foreach($causali as $causale) {
			  $options[$causale['Causalresult']['CausaleRisultato']] = $causale['Causalresult']['Descrizione'];
			 }
			?>
			
			<?=$this->Form->input('CausaleRisultato', array('type'=>'select', 'options' => $options));?>

			<div class="clear"></div>		
			
			<h3>Creazione LDA (non obbligatorio) </h3>
			
			<?=$this->Form->input('ArbitroSearch',array('label' => 'Arbitro', 'data-arbitro' => 1, 'disabled' => false, 'class' => 'searchAthlete','data-url' => '/admin/athletes/searchAthlete','data-dest' => 'MatchArbitro'));?>
			<?=$this->Form->input('Arbitro', array('type' => 'hidden'));?>

			<?=$this->Form->input('Arbitro2Search',array('label' => 'Arbitro Singolo', 'data-arbitro' => 1,  'disabled' => false, 'class' => 'searchAthlete','data-url' => '/admin/athletes/searchAthlete','data-dest' => 'MatchArbitro2'));?>
			<?=$this->Form->input('Arbitro2', array('type' => 'hidden'));?>
			
			<?=$this->Form->input('DelegatoSearch',array('label' => 'Delegato', 'data-arbitro' => 1, 'class' => 'searchAthlete','data-url' => '/admin/athletes/searchAthlete','data-dest' => 'MatchDelegato'));?>
			<?=$this->Form->input('Delegato', array('type' => 'hidden'));?>
			
			<?=$this->Form->input('DelegatoASearch',array('label' => 'Delegato Singolo', 'data-arbitro' => 1, 'class' => 'searchAthlete','data-url' => '/admin/athletes/searchAthlete','data-dest' => 'MatchDelegatoA'));?>
			<?=$this->Form->input('DelegatoA', array('type' => 'hidden'));?>
		
	<?=$this->Form->end();?>
