<?
	$query_data = "SELECT 
	Campionati.Nome as nome_campionato
	,Squadre.Denominazione as squadra
	,SquadreCampionati.SquadraCampionato as IdSquadra
	,GoalPartite.SetTennis as set_tennis
	
	FROM Campionati
	INNER JOIN SquadreCampionati
	ON Campionati.Campionato = SquadreCampionati.Campionato
	
	INNER JOIN Squadre
	ON SquadreCampionati.Squadra = Squadre.Squadra
	
	INNER JOIN GoalPartite
	ON GoalPartite.SquadraCampionato = SquadreCampionati.SquadraCampionato
	
	WHERE  AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`) 
	AND Campionati.sport = 'TENNIS' 
	AND GoalPartite.SetTennis LIKE '%athletes%'
	ORDER BY `squadra` ASC";
	
	$result = mysql_query($query_data);
	
	$row_Recordset = mysql_fetch_assoc($result);
?>



<?
	
	//GIUSEPPE 2016-12-13 --------------------------------
	function read_tennis_players($id_players) {
		
		$array_points = json_decode($id_players['g']['SetTennis'], true);
		
		$array_to_query = array();
		
		$array_exit = array();
		
		
		$array_to_query[] = array($array_points['athletes']['casa_s1'], $array_points['check_win']['s_1_4']);
		$array_to_query[] = array($array_points['athletes']['casa_s2'], $array_points['check_win']['s_3_4']);
		$array_to_query[] = array($array_points['athletes']['casa_d1'], $array_points['check_win']['s_5_4']);
		$array_to_query[] = array($array_points['athletes']['casa_d2'], $array_points['check_win']['s_5_4']);
		
		$i = 0;
		foreach ($array_to_query as $index_atleta) {
			
			$index = $index_atleta[0];
			$check_point = $index_atleta[1];
			
			$query = "SELECT CONCAT(Cognome,' ',Nome) as anagrafica
			,sesso as sesso
			FROM Atleti 
			WHERE Atleta = '$index' ";
			
			$q = mysql_query($query);
			
			// $result = mysql_fetch_array($q)[0];
			
			$res = mysql_fetch_array($q);
			
			$result = $res[0];
			
			$sesso = $res[1];
			
			array_splice($id_players, 2); // tiene i primi due elementi dell'array
			
			$id_players["0"]["anagrafica"] = $result;
			
			$id_players["0"]["sesso"] = $sesso;
			
			$id_players["0"]["goals"] = "1"; // lo metto di default, poi se ha vinto lo cambio (lo vedo negli if(isset(...)))
			
			$id_players["0"]["id_atleta_tennis"] = $index;
			
			if ($i == 0) {
				if ($check_point == "1") {
					$id_players["0"]["goals"] = "3";
				}
			}
			
			if ($i == 1) {
				if ($check_point == "1") {
					$id_players["0"]["goals"] = "3";
				}
			}
			
			if ($i == 2) {
				if ($check_point == "1") {
					$id_players["0"]["goals"] = "2";
				}
			}
			
			if ($i == 3) {
				if ($check_point == "1") {
					$id_players["0"]["goals"] = "2";
				}
			}
			
			$array_exit[] = $id_players;
			
			$i++;
		}
		
		return $array_exit;
	}
	
	//----------------------------------------------------
?>