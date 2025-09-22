<img src="http://<?=$_SERVER['SERVER_NAME'];?>/img/website/logo-midland.png" alt="Logo Midland Sport" />
<br />
Gentile <?=$campo['Campi']['NomeGestore'];?> <?=$campo['Campi']['CognomeGestore'];?></b>,<br />

<?=$nome;?> <?=$cognome;?> ha effettuato una prenotazione presso il Vostro campo sportivo. 

<table border="1" width="450">
<tr><th>Data</th><td><?=$data;?></td></tr>
<tr><th>Ora</th><td><?=$ora;?></td></tr>
<tr><th>Campo</th><td><?=$campo['Campi']['Descrizione'];?></td></tr>
<tr><th>Importo</th><td><?=$importo;?>&euro;</td></tr>
</table>
<p>
Recapiti del prenotatario:<br />

<b>Telefono/Cellulare:</b>  <?=$telefono;?><br />
<b>E-Mail:</b> <?=$email;?>
