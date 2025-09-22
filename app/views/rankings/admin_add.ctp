
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function() {
	
		$("#RankingCampionato").change(function() {
		
			var id_champ = $("#RankingCampionato").val();
			
			if (id_champ != '') {
			
				$("#RankingNomeGirone").removeAttr('disabled');
			
				$("#RankingNomeGirone").attr('data-url','/admin/rankings/searchGirone/' + id_champ);
			
			} else {
					
				$("#RankingNomeGirone").attr('disabled','disabled');
				
			}
		});
		
		$("#RankingGironeCampionato").change(function() {
		
			var id_girone = $("#RankingGironeCampionato").val();
			
			if (id_girone != '') {
			
				$("#RankingNomeSquadra").removeAttr('disabled');
			
				$("#RankingNomeSquadra").attr('data-url','/admin/rankings/searchSquadraCampionato/' + id_girone);
			
			} else {
					
				$("#RankingNomeSquadra").attr('disabled','disabled');
				
			}
		});
		
	});
	}
	</script>

	<?=$this->Form->create('Ranking', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuova classifica</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	

	<div class="clear"></div>	

	<?=$this->Form->input('NomeCampionato',array('label' => 'Campionato','class' => 'autoComplete','data-url' => '/admin/rankings/searchCampionato','data-dest' => 'RankingCampionato'));?>
	<?=$this->Form->input('Campionato', array('type' => 'hidden'));?>
	
	
	<?=$this->Form->input('NomeGirone',array('label' => 'Girone','class' => 'autoComplete','data-url' => '/admin/rankings/searchGirone','data-dest' => 'RankingGironeCampionato','disabled' => 'disabled'));?>
	<?=$this->Form->input('GironeCampionato', array('type' => 'hidden'));?>
	
	<?=$this->Form->input('NomeSquadra',array('label' => 'Squadra','class' => 'autoComplete','data-url' => '/admin/rankings/searchSquadraCampionato','data-dest' => 'RankingSquadraCampionato','disabled' => 'disabled'));?>
	<?=$this->Form->input('SquadraCampionato', array('type' => 'hidden'));?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->input('Giocate', array('class' => 'mini'));?>
	<?=$this->Form->input('Punti', array('class' => 'mini'));?>
	<?=$this->Form->input('Vinte', array('class' => 'mini'));?>
	<?=$this->Form->input('Perse', array('class' => 'mini'));?>
	<?=$this->Form->input('Nulle', array('class' => 'mini'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GiocateCasa', array('class' => 'mini'));?>
	<?=$this->Form->input('VinteCasa', array('class' => 'mini'));?>
	<?=$this->Form->input('PerseCasa', array('class' => 'mini'));?>
	<?=$this->Form->input('NulleCasa', array('class' => 'mini'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GiocateFuori', array('class' => 'mini'));?>
	<?=$this->Form->input('VinteFuori', array('class' => 'mini'));?>
	<?=$this->Form->input('PerseFuori', array('class' => 'mini'));?>
	<?=$this->Form->input('NulleFuori', array('class' => 'mini'));?>
	

	<div class="clear"></div>
	
	<?=$this->Form->input('GoalFatti', array('class' => 'mini'));?>
	<?=$this->Form->input('GoalFattiCasa', array('class' => 'mini'));?>
	<?=$this->Form->input('GoalFattiFuori', array('class' => 'mini'));?>
	

	<div class="clear"></div>
	
	
	<?=$this->Form->input('GoalSubiti', array('class' => 'mini'));?>
	<?=$this->Form->input('GoalSubitiCasa', array('class' => 'mini'));?>
	<?=$this->Form->input('GoalSubitiFuori', array('class' => 'mini'));?>
	

	<div class="clear"></div>
	
	<?=$this->Form->input('CoppaDisciplina', array('class' => 'mini'));?>
							
						
			
	
	<?=$this->Form->end();?>
