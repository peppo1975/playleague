
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Ranking', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica classifica <span><?=$this->data['Ranking']['NomeSquadra'];?></span></h2>
								<ul>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Classifica');?>

	<?=$this->Form->input('NomeCampionato',array('label' => 'Campionato','class' => 'autoComplete','data-url' => '/admin/rankings/searchCampionato','data-dest' => 'RankingCampionato', 'readonly' => true));?>
	<?=$this->Form->input('Campionato', array('type' => 'hidden'));?>
	
	
	<?=$this->Form->input('NomeGirone',array('label' => 'Girone','class' => 'autoComplete','data-url' => '/admin/rankings/searchGirone','data-dest' => 'RankingGironeCampionato','disabled' => 'disabled', 'readonly' => true));?>
	<?=$this->Form->input('GironeCampionato', array('type' => 'hidden'));?>
	
	<?=$this->Form->input('NomeSquadra',array('label' => 'Squadra','class' => 'autoComplete','data-url' => '/admin/rankings/searchSquadraCampionato','data-dest' => 'RankingSquadraCampionato','disabled' => 'disabled', 'readonly' => true));?>
	<?=$this->Form->input('SquadraCampionato', array('type' => 'hidden'));?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->input('Giocate', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('Punti', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('Vinte', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('Perse', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('Nulle', array('class' => 'mini', 'readonly' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GiocateCasa', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('VinteCasa', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('PerseCasa', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('NulleCasa', array('class' => 'mini', 'readonly' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('GiocateFuori', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('VinteFuori', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('PerseFuori', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('NulleFuori', array('class' => 'mini', 'readonly' => true));?>
	

	<div class="clear"></div>
	
	<?=$this->Form->input('GoalFatti', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('GoalFattiCasa', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('GoalFattiFuori', array('class' => 'mini', 'readonly' => true));?>
	

	<div class="clear"></div>
	
	
	<?=$this->Form->input('GoalSubiti', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('GoalSubitiCasa', array('class' => 'mini', 'readonly' => true));?>
	<?=$this->Form->input('GoalSubitiFuori', array('class' => 'mini', 'readonly' => true));?>
	

	<div class="clear"></div>
	
	<?=$this->Form->input('CoppaDisciplina', array('class' => 'mini', 'readonly' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('PuntiPenalizzazione', array('class' => 'mini', 'label' => 'Punti di penalizzazione'));?>
							
						
			
	
	<?=$this->Form->end();?>
