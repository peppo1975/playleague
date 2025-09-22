<img src="http://<?=$_SERVER['SERVER_NAME'];?>/img/website/logo-midland.png" alt="Logo Midland Sport" />
<br />
 
<? if ($cauzione == 1): ?>
<? if ($force == 1): ?>


In data <b><?=date("d/m/Y H:i");?></b> è stato effettuato un'iscrizione tramite <b>Bonifico Bancario</b> di 150,00 &euro;:


<? else: ?>

In data <b><?=date("d/m/Y H:i");?></b> è stato effettuato un'iscrizione tramite <b>Carta di Credito</b> di 150,00 &euro;:

<? endif; ?>
<? else: ?>

In data <b><?=date("d/m/Y H:i");?></b> è stato effettuato un'iscrizione tramite <b>Deposito cauzionale gi&agrave; versato</b>

<? endif; ?>


<? foreach ($iscrizione as $tesserato): ?>
<p>
<? foreach ($tesserato as $key => $value): ?>
<? if ($key != "totale"): ?>
<b><?=ucfirst($key);?>:</b> <?=$value;?><br />
<? endif; ?>
<? endforeach; ?>
</p>
<hr />

<? endforeach; ?>