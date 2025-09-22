<?php





$champs = array( 689 // C7
		,681 // C5 FEM
		,679); // C5 CLASS


$gironi[689] = array(1741,1756);
$gironi[681] = array(1735);
$gironi[679] = array(1759,1734,1760,1774);

foreach ($champs as $champ) {

	$champ_n = mysql_query("SELECT Nome FROM Campionati WHERE Campionato = $champ");
	$champ_n = mysql_fetch_assoc($champ_n);
	$champ_n = $champ_n['Nome'];


	print "Gironi di $champ_n\r\n\r\n";
	foreach ($gironi[$champ] as $girone) {


		$girone_n = mysql_query("SELECT Descrizione FROM GironiCampionati WHERE GironeCampionato = $girone");
		$girone_n = mysql_fetch_assoc($girone_n);
		$girone_n = $girone_n['Descrizione'];
		

		print  "Girone $girone_n\r\n\r\n";


		$calendari = mysql_query("SELECT * FROM Calendari WHERE GironeCampionato = $girone AND Giornata > 11");
		while ($calendario = mysql_fetch_assoc($calendari)) {

			$new_data = date("Y-m-d",strtotime("-1 weeks",strtotime($calendario['Data'])));
			print $calendario['Data'] . " -> $new_data | " . $calendario['Giornata'] . "\r\n\r\n";

			mysql_query("UPDATE Calendari SET Data = '" . $new_data . "' WHERE Calendario = " .  $calendario['Calendario']);
			print mysql_error();
		}

	}

}
