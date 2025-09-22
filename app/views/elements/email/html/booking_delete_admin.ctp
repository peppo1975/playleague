<img src="http://<?=$_SERVER['SERVER_NAME'];?>/img/website/logo-midland.png" alt="Logo Midland Sport" />
<br />
Gentile <?=$campo['Campi']['NomeGestore'];?> <?=$campo['Campi']['CognomeGestore'];?></b>,<br />

<?=$nome;?> <?=$cognome;?> ha effettuato la disdetta del campo sportivo. 

<br />Dati prenotazione cancellata: <br />

<table border="1" width="450">
<tr><th>Data</th><td><?=$data;?></td></tr>
<tr><th>Ora</th><td><?=$ora;?></td></tr>
<tr><th>Campo</th><td><?=$campo['Campi']['Descrizione'];?></td></tr>
</table>
<p>
