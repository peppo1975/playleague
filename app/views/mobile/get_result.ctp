<?

	$data['Casa']['UploadLogo'] = array();
	
	foreach($data['Casa']['Upload'] as $up) {
		if($up['tag'] == 'Logo')
			$data['Casa']['UploadLogo'] = $up;
	}

	$data['Trasferta']['UploadLogo'] = array();
	
	foreach($data['Trasferta']['Upload'] as $up) {
		if($up['tag'] == 'Logo')
			$data['Trasferta']['UploadLogo'] = $up;
	}

	list($goalcasa, $goaltrasferta) = explode("-", $data['Match']['Risultato']);
	
	$goalcasa_array = array();
	$goaltrasferta_array = array();
	
	foreach($data['AtletiCasa'] as $k => $atleta) {
		
		 if($atleta['Goal'] > 0) {
			
			$count = 0;
			
			for($i = 0; $i < $atleta['Goal']; $i++) {
				$count++;
			}
			
			$goalcasa_array[] = array(
			
				'label' => $atleta['Anagrafica'] . (($count > 1)? " (" . $count . ")" : ""),
				'goal'  => $count
			
			);
			
		 }
		 if($atleta['Autogoal'] > 0) {
		 
		 	$count = 0;
		 
			for($i = 0; $i < $atleta['Autogoal']; $i++) {
				$count++;
			}
			
			$goaltrasferta_array[] = array(
			
				'label' => $atleta['Anagrafica'] . (($count > 1)? " (" . $count . ")" : ""),
				'goal'  => $count			
			
			);
			
		 }
		
	}

	foreach($data['AtletiTrasferta'] as $k => $atleta) {
		
		 if($atleta['Goal'] > 0) {
			
			$count = 0;
			
			for($i = 0; $i < $atleta['Goal']; $i++) {
				$count++;
			}
			
			$goaltrasferta_array[] = array(
			
				'label' => $atleta['Anagrafica'] . (($count > 1)? " (" . $count . ")" : ""),
				'goal'  => $count
			
			);
			
		 }
		 if($atleta['Autogoal'] > 0) {
		 
		 	$count = 0;
		 
			for($i = 0; $i < $atleta['Autogoal']; $i++) {
				$count++;
			}
			
			$goalcasa_array[] = array(
			
				'label' => $atleta['Anagrafica'] . (($count > 1)? " (" . $count . ")" : ""),
				'goal'  => $count			
			
			);
			
		 }
		
	}
	
	$goalcasa_array = Set::Sort($goalcasa_array, '{n}.goal', 'DESC');
	$goaltrasferta_array = Set::Sort($goaltrasferta_array, '{n}.goal', 'DESC');	
	
	$count_row = 0;
	
	if(count($goalcasa_array) >= count($goaltrasferta_array)) {
		$count_row = count($goalcasa_array);
	} else {
		$count_row = count($goaltrasferta_array);
	}
	
	//debug($count_row);

?>

								<div class="booking-data">
								
									<table>
									
										<tr>
											<th>Data</th>
											<td><?=$data['Match']['Data_it'];?> - ore <?=$data['Match']['Ora'];?></td>
										</tr>

										<tr>
											<th>Home</th>
											<td><?=$data['Match']['CasaNome'];?></td>											
										</tr>
										
										<tr>
											<th>Visitors</th>
											<td><?=$data['Match']['TrasfertaNome'];?></td>												
										</tr>
										
										<tr>
											<th>Risultato</th>
											<td><?=$data['Match']['Risultato'];?></td>																					
										</tr>																				
										
									</table>
									
									<table class="match-result">
									
										<tr>
											<th>Marcatori <?=$data['Match']['CasaNome'];?></th>
										</tr>
										<tr>
											<td>
												<? for($i = 0; $i < count($goalcasa_array); $i++): ?>

													<?=(isset($goalcasa_array[$i]['label']))? $goalcasa_array[$i]['label'] : "";?><? if($i+1 < count($goalcasa_array)): ?>,<? endif; ?>
										
												<? endfor; ?>													
											</td>										
										</tr>

									</table>									

									<table class="match-result">
									
										<tr>
											<th>Marcatori <?=$data['Match']['TrasfertaNome'];?></th>										
										</tr>
										
										<tr>
											<td>
												<? for($i = 0; $i < count($goaltrasferta_array); $i++): ?>

													<?=(isset($goaltrasferta_array[$i]['label']))? $goaltrasferta_array[$i]['label'] : "";?><? if($i+1 < count($goaltrasferta_array)): ?>,<? endif; ?>													
										
												<? endfor; ?>																				
											</td>										
										</tr>

									</table>									
									
								</div>
																
