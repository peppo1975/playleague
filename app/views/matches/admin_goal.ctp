	<script type="text/javascript">
	if (typeof $ != "undefined") {

		$(".GoalDelete").live('click', function () {

			var delete_id = $(this).closest('tr').attr('data-id');
			
			if(confirm('Eliminare record?')) {
			
				$.get("/admin/matches/goaldelete/" + delete_id, function(ret) {
				
					if(ret.delete == 1) $(".goal-row-delete[data-id='" + delete_id + "']").remove();
				
				},'json');
			
			}

		});

		$("#GoalAdd").live('click', function () {
		
			if($("#MatchgoalGoalPartita").val() != '') var edit_id = $("#MatchgoalGoalPartita").val();
						
			var goal_add = $("#MatchgoalGoaladdForm").serialize();
			
			var Squadra = $("#MatchgoalSquadraCampionato option:selected").html();
			
			var Atleta = $("#MatchgoalAtletaSearch").val();
			var Goal = $("#MatchgoalGoal").val();
			var Autogoal = $("#MatchgoalAutogoal").val();
			
			var Ammonizione = $(".Ammonizione:checked").val();
			var Espulsione = $(".Espulsione:checked").val();
			
			var Motivo = $("#MatchgoalMotivo").val();
			
			var Delete = '<a class="GoalDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>';
			
				$.post("/admin/matches/goaladd", goal_add, function(ret) {
				
					if(ret.aggiunto != 0) {
					
						var Edit = '<a class="GoalEdit" data-id="'+ ret.aggiunto +'" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>';
					
						if(edit_id != 'undefined' && edit_id != ret.aggiunto) {
						
							$("#GoalTable").append('<tr class="goal-row-delete" data-id="' + ret.aggiunto + '"><td>' + Squadra + '</td><td>' + Atleta + '</td><td>' + Goal + '</td><td>' + Autogoal + '</td><td>' + Ammonizione + '</td><td>' + Espulsione + '</td><td>' + Motivo + '</td><td>' + Delete + Edit + '</td></tr>');
							$('form').clearForm();
						
						} else {
						
						
							 $("tr[data-id=" + edit_id + "]").each(function() {

								$('<tr class="goal-row-delete" data-id="' + ret.aggiunto + '"><td data-squadra="' + Squadra + '">' + Squadra + '</td><td data-Atleta="' + Atleta + '__' + id_atleta + '">' + Atleta + '</td><td data-goal="' + Goal + '">' + Goal + '</td><td data-autogoal="' + Autogoal + '">' + Autogoal + '</td><td data-ammonizione="' + Ammonizione + '">' + Ammonizione + '</td><td data-espulsione="' + Espulsione + '">' + Espulsione + '</td><td data-motivo="' + Motivo + '">' + Motivo + '</td><td>' + Delete + Edit + '</td></tr>').insertAfter($(this));
								$(this).remove(); 

							});
						
						}
						
						$('.error_atleta').html('');
						$('.error_goal').html('');
						$('.error_autogoal').html('');
						
					
					} else {
					
						$('.error_atleta').html(ret.errori.Atleta);
						$('.error_goal').html(ret.errori.Goal);
						$('.error_autogoal').html(ret.errori.Autogoal);
					
					}
							
				},'json');
			

		});

		$(".GoalEdit").live('click', function () {

			var edit_id = $(this).closest('a').attr('data-id');
			
			var Squadra = '';
			var Atleta = '';
			var Goal = '';
			var Autogoal = '';
			var Ammonizione = '';
			var Espulsione = '';
			var Motivo = '';
			
			var i = 0;
			
			$(this).closest('tr').find('td').each( function(index) {
			
				i = 0;
				
				if($(this).closest('tr').attr('data-id') == edit_id) {
				
					if(Squadra == '' && i == 0) { Squadra = $(this).closest('td').attr('data-squadra'); i = 1;}
					if(Atleta == '' && i == 0) { Atleta = $(this).closest('td').attr('data-atleta'); i = 1; arrAtleta = Atleta.split('__'); Atleta = arrAtleta[0]; id_atleta = arrAtleta[1]; }
					if(Goal == '' && i == 0) { Goal = $(this).closest('td').attr('data-goal'); i = 1;}
					if(Autogoal == '' && i == 0) { Autogoal = $(this).closest('td').attr('data-autogoal'); i = 1;}
					if(Ammonizione == '' && i == 0) { Ammonizione = $(this).closest('td').attr('data-ammonizione'); i = 1;}
					if(Espulsione == '' && i == 0) { Espulsione = $(this).closest('td').attr('data-espulsione'); i = 1;}
					if(Motivo == '' && i == 0) { Motivo = $(this).closest('td').attr('data-motivo'); i = 1;}
							
				}
			
			});
				
			$("select#MatchgoalSquadraCampionato option").each(function() { 
			
				this.selected = (this.text == Squadra); 
			
			});
				
			$("#MatchgoalAtletaSearch").val(Atleta);
			$("#MatchgoalAtleta").val(id_atleta);
			$("#MatchgoalGoal").val(Goal);
			$("#MatchgoalAutogoal").val(Autogoal);
			
			if(Ammonizione == 'Si') $("#MatchgoalAmmonizioneSi").attr('checked','checked');
			else if(Ammonizione == 'No') $("#MatchgoalAmmonizioneNo").attr('checked','checked');
			
			if(Espulsione == 'Si') $("#MatchgoalEspulsioneSi").attr('checked','checked');
			else if(Espulsione == 'No') $("#MatchgoalEspulsioneNo").attr('checked','checked');
				
			$("#MatchgoalMotivo").val(Motivo);
			
			$(".reset_field").css('display','block');
			
			$("#MatchgoalGoalPartita").val(edit_id);
						
		});
		
		$('.reset_field').live('click', function() {
		
			$('form').clearForm();
			$(".reset_field").css('display','none');
			$("#MatchgoalGoalPartita").val('');
		
		});
	}
	</script>
	<div class="">

		Campionato: <?=$calendario['Campionati']['Nome'];?>

	</div>

	<div class="">

		Squadre: <?=$calendario['Match']['CasaNome']. ' - ' . $calendario['Match']['TrasfertaNome']. ' ' . $calendario['Match']['Risultato'];?>

	</div>

	<?

	$squadre = array();

	$squadre[$calendario['Casa']['SquadraCampionato']] = $calendario['Match']['CasaNome'];
	$squadre[$calendario['Trasferta']['SquadraCampionato']] = $calendario['Match']['TrasfertaNome']; 

	?>

	<table id="GoalTable" width="100%">
		<tr>
			<th>Squadra</th>
			<th>Atleta</th>
			<th>Goal</th>
			<th>Autogoal</th>
			<th>Ammonizione</th>
			<th>Espulsione</th>
			<th>Motivo</th>
			<th>Opzioni</th>
		</tr>
		
		<?foreach($goals as $goal):?>

		<tr class="goal-row-delete" data-id="<?=$goal['Matchgoal']['GoalPartita'];?>">

			<td data-squadra="<?=$goal['Matchgoal']['NomeSquadra'];?>"><?=$goal['Matchgoal']['NomeSquadra'];?></td>
			<td data-atleta="<?=$goal['Athlete']['Anagrafica'].'__'.$goal['Athlete']['Atleta'];?>"><?=$goal['Athlete']['Anagrafica'];?></td>
			<td data-goal="<?=$goal['Matchgoal']['Goal'];?>"><?=$goal['Matchgoal']['Goal'];?></td>
			<td data-autogoal="<?=$goal['Matchgoal']['Autogoal'];?>"><?=$goal['Matchgoal']['Autogoal'];?></td>
			<td data-ammonizione="<?=$goal['Matchgoal']['Ammonizione'];?>"><?=$goal['Matchgoal']['Ammonizione'];?></td>
			<td data-espulsione="<?=$goal['Matchgoal']['Espulsione'];?>"><?=$goal['Matchgoal']['Espulsione'];?></td>
			<td data-motivo="<?=$goal['Matchgoal']['Motivo'];?>"><?=$goal['Matchgoal']['Motivo'];?></td>
			<td data-option="">
				<a class="GoalDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>
				<a class="GoalEdit" data-id="<?=$goal['Matchgoal']['GoalPartita'];?>" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>
			</td>

		</tr>
		
		<?endforeach;?>
		
	</table>

	<div class="goalAdd">
	<?=$this->Form->create('Matchgoal', array('action' => 'goaladd','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>
	<table>
	<tr>
		
			
		<td>
		<?=$this->Form->input('GoalPartita', array('type' => 'hidden'));?>
		<?=$this->Form->input('Calendario', array('label' => '', 'type' => 'hidden', 'value' => $calendario['Match']['Calendario']));?>
		<?=$this->Form->input('SquadraCampionato', array('label' => '', 'type' => 'select', 'options' => $squadre));?>
		</td>
		<td>
		<?=$this->Form->input('AtletaSearch',array('label' => '', 'class' => 'autoComplete','data-url' => '/admin/matches/searchAtleta','data-dest' => 'MatchgoalAtleta'));?>
		<?=$this->Form->input('Atleta', array('label' => '', 'type' => 'hidden'));?>
		<span class="error_atleta"></span>
		</td>
		
		<td>
		<?=$this->Form->input('Goal', array('label' => '', 'type' => 'text'));?>
		<span class="error_goal"></span>
		</td>
		
		<td>
		<?=$this->Form->input('Autogoal', array('label' => '', 'type' => 'text'));?>
		<span class="error_autogoal"></span>
		</td>
		
		<td>
		<?=$this->Form->input('Ammonizione',
			array(

			'class' => 'Ammonizione',
			'legend' => false,
			'type' => 'radio',
			'options' => array( 'Si'=>'Si', 'No'=>'No' ),
			'default' => 'No',
			'hiddenField' => false

		));?>
		</td>
		<td>
		<?=$this->Form->input('Espulsione',
			array(
			
			'class' => 'Espulsione',
			'legend' => false,
			'type' => 'radio',
			'options' => array( 'Si'=>'Si', 'No'=>'No' ),
			'default' => 'No',
			'hiddenField' => false

		));?>
		</td>
		<td>
		<?=$this->Form->input('Motivo', array('label' => '', 'type' => 'text'));?>
		</td>
		<td>
		<a class="goal_add" id="GoalAdd" href="javascript:;"><img src="/img/timmyshare/icon_add.png"/></a>
		<a style="display: none;" class="reset_field" id="ResetGoal" href="javascript:;"><img src="/img/timmyshare/icon_reset_quick_search.png"/></a>
			</td>
		
	</tr>
	</table>
		<?=$this->Form->end();?>
	</div>