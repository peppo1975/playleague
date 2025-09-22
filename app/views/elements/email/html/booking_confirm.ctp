<img src="http://<?=$_SERVER['SERVER_NAME'];?>/img/website/logo-midland.png" alt="Logo Midland Sport" />
<br />
Gentile <?=$nome;?> <?=$cognome;?></b>,<br />

Di seguito il riepilogo della Vostra prenotazione:

<table border="1" width="450">
<tr><th>Data</th><td><?=$data;?></td></tr>
<tr><th>Ora</th><td><?=$ora;?></td></tr>
<tr><th>Campo</th><td><?=$campo['Campi']['Descrizione'];?></td></tr>
<tr><th>Importo</th><td><?=$importo;?>&euro;</td></tr>
</table>

			<? $past_time = strtotime("-1 days",strtotime($data_real . " " . $ora)); ?>
								
Le ricordiamo che può effettuare la cancellazione dell'ordine le <b>ore <?=date("H:i",$past_time);?> del <?=date("d/m/Y",$past_time);?></b> utilizzando il link seguente:<br />

<a href="http://<?=$_SERVER['SERVER_NAME'];?>/disdetta/impianti/<?=md5($book_id);?>" title="Cancella prenotazione">http://<?=$_SERVER['SERVER_NAME'];?>/sections/bookingCancel/<?=md5($book_id);?></a>
