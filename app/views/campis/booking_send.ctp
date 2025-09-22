<div style="padding: 15px;">
<? if ($booked): ?>
	
	<div class="alert alert-success">

	<b>Grazie <?=$nome;?> <?=$cognome;?>,</b>
		<p>
	La procedura di prenotazione &egrave; andata a buon fine, riceverai al pi&ugrave; presto via e-mail un riepilogo dei dati selezionati e le istruzioni per annullare la prenotazione
	
	</p>

	</div>
	
<? else: ?>

	<div class="alert alert-danger">
	<b>Siamo spiacenti,</b>
	<p>
	Si &egrave; verificato un errore durante la procedura di prenotazione
	</p>

	</div>
<? endif ;?>
</div>
