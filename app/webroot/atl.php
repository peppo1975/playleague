<?php



$atleti = mysql_query("SELECT Email FROM Atleti,Annuario WHERE Atleti.Atleta = Annuario.Atleta AND Annuario.AnnoSportivo = 2016");

while ($ret = mysql_fetch_assoc($atleti)) {


 if (!empty($ret['Email'])) {

$email = $ret['Email'];
	mysql_query("INSERT INTO newsletters_users (email) VALUES ('$email')");
	$id = mysql_insert_id();
	mysql_query("INSERT INTO newsletters_groups_users (newsletter_group_id,newsletter_user_id) VALUES (12,$id)");


 }

}
