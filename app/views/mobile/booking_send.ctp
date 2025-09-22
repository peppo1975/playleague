<? if ($booked): ?>
	

	<h2>Grazie <?=$nome;?> <?=$cognome;?>,</h2>
		<p>
	La procedura di prenotazione &egrave; andata a buon fine, riceverai al pi&ugrave; presto via e-mail un riepilogo dei dati selezionati e le istruzioni per annullare la prenotazione
	
	</p>
	
<? else: ?>

	<h2>Siamo spiacenti,</h2>
	<p>
	Si &egrave; verificato un errore durante la procedura di prenotazione
	</p>
<? endif ;?>
