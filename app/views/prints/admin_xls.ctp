<?$gare 				   = $this->Session->read('gare');?>
<?$gare_prossima_giornata  = $this->Session->read('gare_prossima_giornata');?>
<?$gare_prossima_giornata2 = $this->Session->read('gare_prossima_giornata2');?>
<?$classifica_marcatori    = $this->Session->read('classifica_marcatori');?>
<?$diffidati 			   = $this->Session->read('diffidati');?>
<?$espulsi 				   = $this->Session->read('espulsi');?>
<?$classifiche 			   = $this->Session->read('classifiche');?>
<?$options_pdf 			   = $this->Session->read('options_pdf');?>
<?$gironi				   = $this->Session->read('gironi');?>
<?$giornate				   = $this->Session->read('giornate');?>
<?$n_giornate 			   = count($giornate);?>
<?$n_gironi 			   = count($gironi);?>
<?$i 					   = 2;?>

<? //debug($gare); ?>

<? foreach($giornate as $giornata) : ?>
		
	<? foreach($gironi as $girone): ?>
			
		<h3>Girone: <?=$this->requestAction('/admin/prints/findHalf/' . $girone) . ' - Giornata: ' . $giornata; ?></h3>
				
		<? foreach($gare[$giornata][$girone] as $gara): ?>
				
			<table class="result">
							
							<tr>
								<td><?=$gara['Match']['CasaNome'];?></td>
								<td><?=$gara['Match']['TrasfertaNome'];?></td>
								<td><?=$gara['Match']['Risultato'];?></td>
							</tr>
			
			</table>
					
		<? endforeach;?>
				
		<h3>Classifica</h3>
		
		<table class="classifica">
		
			<tr>
				<th>Societ&agrave</th>
				<th>Punti</th>
				<th>Giocate</th>
				<th>Vinte</th>
				<th>Perse</th>
				<th>Nulle</th>
				<th>Goal Fatti</th>
				<th>Goal Subiti</th>
				<th>Coppa Disc.</th>		
			</tr>
			
			<? foreach($classifiche[$giornata][$girone] as $classifica): ?>

						<tr>
							<td><?=$this->requestAction('/admin/prints/findTeam/' . $classifica['SquadraCampionato']);?></td>
							<td><?=$classifica['Punti'];?></td>
							<td><?=$classifica['Giocate'];?></td>
							<td><?=$classifica['Vinte'];?></td>
							<td><?=$classifica['Perse'];?></td>
							<td><?=$classifica['Nulle'];?></td>
							<td><?=$classifica['GoalFatti'];?></td>
							<td><?=$classifica['GoalSubiti'];?></td>
							<td><?=$classifica['CoppaDisciplina'];?></td>
						</tr>

			<? endforeach; ?>
		
		</table>
				
		<h3>Classifica Marcatori</h3>
		
		<table class="marcatori">
		
			<tr>
				<th>Goal</th>
				<th>Giocatore</th>
				<th>Squadra</th>
			</tr>
		
			<? foreach($classifica_marcatori[$giornata][$girone] as $marcatore => $value): ?>
			
				<tr>
					<td><?=$value[0]['goals'];?></td>
					<td><?=$value[0]['anagrafica'];?></td>
					<td><?=$value[0]['NomeSquadra'];?></td>
				</tr>
			
			<? endforeach; ?>
		
		</table>
		
		<h3>Diffidati</h3>
		
		<table class="diffidati">
		
			<tr>
				<td>
				
					<? foreach($diffidati[$giornata][$girone] as $diffidato): ?>
					
					<? $r = $diffidato[0]['Ammonizioni']%2; ?>
					
					<?//debug('Ammonizioni: ' . $diffidato[0]['Ammonizioni'] . ' Resto: ' . $r);?>
											
						<? if($r == 0): ?>
						
							- <?=$diffidato[0]['anagrafica'] . ' ( ' . $diffidato[0]['NomeSquadra'] . ' ) ';?>
						
						<? endif; ?>
					
					<? endforeach; ?>
				
				</td>
			</tr>
		
		</table>
			
		<h3>Espulsi</h3>
		
		<table class="espulsi">
		
			<tr>
				<td>
				
					<? foreach($espulsi[$giornata][$girone] as $espulso): ?>
					
						- <?=$espulso[0]['anagrafica'] . ' ( ' . $espulso[0]['NomeSquadra'] . ' ) ';?>
					
					<?endforeach; ?>
				
				</td>
			</tr>
		
		</table>
		
		<?if($options_pdf == 1): ?>
		
			<hr>
			
			<? elseif($options_pdf == 2): ?>
			
				<? if($i % 2 != 0): ?>

					<hr>
				
				<? endif; ?>				
				
		<?endif;?>
		
		<?$i ++; ?>
		
	<? endforeach; ?>

<? endforeach; ?>