<?
	
	$query_list = array();
	
	do {
		$query_list[] = $row_Recordset;
	} while ($row_Recordset = mysql_fetch_assoc($result));
	
	
	$rename = "";
	
	$list_query = array();
	
	$array_clear = array();
	
	foreach ($query_list as $unique_query) {

		$rename['sc']['IdSquadra'] = $unique_query['IdSquadra'];
		$rename['s']['NomeSquadra'] = $unique_query['squadra'];
		$rename['g']['SetTennis'] = $unique_query['set_tennis'];
		
		$list_query[] = $rename;
	}
	
	
	foreach ($list_query as $query) {
		
		if (strstr($query['g']['SetTennis'], 'athletes')) {
			
			if ($query['sc']['IdSquadra'] != "") {
				$result = read_tennis_players($query);
				//echo json_encode($result);
				foreach ($result as $i) {
					//echo json_encode($i)."<br><br>";
					
					$array_clear[] = $i;
				}
			}
		}
	}

	// ordinamento bubble sort in base al nome cognome --------------------------
	
	while (true) {
		$temp;
		$scambio = false;
		
		for ($i = 0; $i < count($array_clear) - 1; $i++) {
			
			if($array_clear[$i]['0']['anagrafica']>$array_clear[$i+1]['0']['anagrafica']){
			//if ((int) $array_clear[$i]['0']['id_atleta_tennis'] > (int) $array_clear[$i + 1]['0']['id_atleta_tennis']) {
				$temp = $array_clear[$i + 1];
				$array_clear[$i + 1] = $array_clear[$i];
				$array_clear[$i] = $temp;
				$scambio = true;
			}
		}
		
		if (!$scambio)
        break;
	}
	
	// SOMMO I PUNTEGGI ---------------------------------------------------------------------
	
	$atl_tenn;
	
	$switch = true;
	
	$array_final = array();
	
	
	for ($i = 0; $i < count($array_clear); $i++) {
		
		if ($switch) {
			
			$atl_tenn = $array_clear[$i];
			
			if ($i == count($array_clear) - 1) {
				
				$array_final[] = $atl_tenn;
			}
		}
		
		if ($i < count($array_clear) - 1) {
			
			if ((int) $atl_tenn['0']['id_atleta_tennis'] == (int) $array_clear[$i + 1]['0']['id_atleta_tennis']) {
				
				$atl_tenn['0']['goals'] = strval((int) $atl_tenn['0']['goals'] + (int) $array_clear[$i + 1]['0']['goals']);
				
				$switch = false;
				} else {
				
				$array_final[] = $atl_tenn;
				
				$switch = true;
			}
		}
	}
	
	// ordinamento bubble sort in base ai punti aleta --------------------------
	
	while (true) {
		$temp;
		$scambio = false;
		
		for ($i = 0; $i < count($array_final) - 1; $i++) {
			
			if ((int) $array_final[$i]['0']['goals'] < (int) $array_final[$i + 1]['0']['goals']) {
				$temp = $array_final[$i + 1];
				$array_final[$i + 1] = $array_final[$i];
				$array_final[$i] = $temp;
				$scambio = true;
			}
		}
		
		if (!$scambio)
        break;
	}
	
	
	
?>


