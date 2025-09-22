<?=$this->element("/backend/add_edit_scripts");?>
<script type="text/javascript">
if (typeof $ != "undefined") {
$("#PrintCampionatoSearch").change(function() {

	$("#PrintGironeSearch").removeAttr('disabled');
	$("#PrintGironeSearch").val('');
	$("#PrintSquadraCasaSearch").val('');
	$("#PrintSquadraTrasfertaSearch").val('');
	$("#PrintGironeSearch").focus();

 });
 
$("#PrintGironeSearch").focus(function() {

	var id_campionato = $("#PrintCampionato").val();
	$("#PrintGironeSearch").attr('data-url','/admin/matches/searchGirone/' + id_campionato);

});
$("#PrintGironeSearch").change(function() {

	$("#PrintSquadraCasaSearch").removeAttr('disabled');
	$("#PrintSquadraCasaSearch").val('');
	$("#PrintSquadraTrasfertaSearch").removeAttr('disabled');
	$("#PrintSquadraTrasfertaSearch").val('');

 });

// $("#cambiaGirone").live('click', function() {

// var val_campionato = $("#RankingNomeCampionato").val();

// if(val_campionato != '') $("#HalfDescrizione").removeAttr('disabled');
// else alert("Selezionare prima campionato.");

	
// });
}
</script>

	<?=$this->Form->create('Print', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Stampa nuovo bollettino</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
		<?=$this->Form->input('CampionatoSearch',array('label' => 'Campionato', 'class' => 'autoComplete', 'data-url' => '/admin/campionatis/searchCampionato','data-dest' => 'PrintCampionato'));?>
		<?=$this->Form->input('Campionato',array('type' => 'hidden'));?>
		
	<div class="clear"></div>
		
		<?=$this->Form->input('GironeSearch',array('label' => 'Girone', 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchGirone','data-dest' => 'PrintGironeCampionato'));?>
		<?=$this->Form->input('GironeCampionato', array('type' => 'hidden'));?>
		
	<div class="clear"></div>
		
		<?=$this->Form->input('Giornata', array('label' => 'Giornata'));?>
			
	<?=$this->Form->end();?>