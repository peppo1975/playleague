<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Iscrizione campionati http://www.midlandsport.it</title>
	<style type="text/css">
		a{ text-decoration: none; color: #D299F2;}
		tr.post-message td a:hover{ text-decoration: underline;}
		table.newsletter{ font-family: Verdana,sans-serif; font-size: 11px; color: #374953; width: 700px;}
		table.disclaimer{font-family: Verdana,sans-serif; font-size: 10px; color: #374953; width: 600px; text-align: center;}
		tr, td{ border: 0 none; padding: 0;}
		tr.logo td a img{ border: none; display: block;}
		tr.post-title td a h1{color: #C6CCF3; font-size: 30px; font-weight: normal; text-shadow: 0 1px 1px #CCCCCC; margin: 20px; font-family: "Din", Arial;}
		tr.post-allegato td a img{border: none; margin: 0 0 0 20px; display: block;}
		tr.post-allegato td p, tr.post-message td p, tr.post-message td ul li, tr.post-message td ol li{color: #999999; font-family: Arial; font-size: 14px; line-height: 1.5em; margin: 20px;}
		tr.post-message td ul li, tr.post-message td ol li{ margin: 5px;}
		tr.post-message td{ border-bottom: 2px solid #fff;}
		tr.post-footer td p{color: #666; font-size: 12px; margin: 0 0 10px 20px; padding-top: 5px;}
		tr.post-footer td p a, tr.disclaimer-txt td a{color: #D299F2; text-decoration: none;}
		tr.post-footer td p a:hover, tr.disclaim:hoverer-txt td a{ text-decoration: underline;}
		tr.disclaimer-txt{ background: #fff;}
	</style>
	
</head>
<body style="margin 0 auto;" >
	<table align="center" class="newsletter" cellspacing="0">
		<thead>
		<tr class="logo">
			<!-- head logo -->
			<td align="left">
				<a href="http://midlandsport.it/contenuti/80/prossime-manifestazioni" style="vertical-align: middle;" title="http://www.midlandsport.it">
					

				<img src="http://www.midlandsport.it/img/signuphd.jpg" alt=""/>

				</a></td>
		</tr>
		</thead>
		<tbody style="background-color: #f5f5f5;">
			<tr class="post-message">
				<td align="left">

				<div style="padding: 10px;">
			
Grazie <?=$this->data['Subscription']['nome_' . $index];?> <?=$this->data['Subscription']['cognome_' . $index];?>, la tua richiesta di iscrizione al campionato <b><?=$campionato['Campionati']['Nome'];?></b> è stata effettuata correttamente.<br /><br />


<h4>RIEPILOGO DATI ISCRIZIONE:</h4>

<? if (!empty($this->data['Subscription']['nomesquadra'])): ?>
Nome squadra:<b> <?=$this->data['Subscription']['nomesquadra'];?></b><br />
<? else:?>
Nome squadra:<b> <?=$squadra['Squadre']['Denominazione'];?></b><br />
<? endif; ?>

<? if (!empty($this->data['Subscription']['segnalate'])): ?>
Segnalazioni:<b> <?=$this->data['Subscription']['segnalazioni'];?></b><br />
<? endif; ?>

<? if (!empty($this->data['Subscription']['girone'])): ?>
Girone campionato:<b> <?=$girone['Half']['Descrizione'];?></b><br />
<? endif; ?>
<? if (!empty($this->data['Subscription']['campo'])): ?>
Impianto:<b> <?=$campo['Campi']['Descrizione'];?></b><br />
<? endif; ?>
<? if (!empty($giorno)): ?>
Giorno:<b> <?=$giorno;?></b><br />
<? endif; ?>

<? if (!empty($giorno)): ?>
Ora:<b> <?=$this->data['Subscription']['ora'];?></b><br />
<? endif; ?>


<h4>Responsabili:</h4>

<? $reponsabili = array('Presidente','Vice Presidente','Segretario'); ?>

<? for ($i = 0; $i < 3; $i++): ?>

<b><?=$reponsabili[$i];?></b>: 

<? if ($this->data['Subscription']['resptype_' . $i] == 0): ?>

<?=$this->data['Subscription']['nome_' . $i];?> <?=$this->data['Subscription']['cognome_' . $i];?><br />
<b>Nato il:</b> <?=$this->data['Subscription']['data_' . $i];?> - <b>C.F.</b>: <b>Nato il:</b> <?=$this->data['Subscription']['cf_' . $i];?><br />

<? $docstype = array('Carta di Identità','Patente','Passaporto'); ?>

<b>Documento tipo:</b>  <?=$docstype[$this->data['Subscription']['doc_' . $i]];?> - <?=$this->data['Subscription']['numerodoc_' . $i];?> - 
<?=$this->data['Subscription']['datadoc_' . $i];?> <br />

<b>Residenza:</b> <?=$this->data['Subscription']['via_' . $i];?>, <?=$this->data['Subscription']['cap_' . $i];?> <?=$this->data['Subscription']['comune_' . $i];?> <?=$this->data['Subscription']['pv_' . $i];?> 

<b>Recapiti:</b> <?=$this->data['Subscription']['telefono_' . $i];?> - <?=$this->data['Subscription']['cellulare_' . $i];?> - <?=$this->data['Subscription']['email_' . $i];?>

<? else: ?>

<?=$this->data['Subscription']['respname_' . $i];?><br />

<? endif; ?>
<br /><br />
<? endfor; ?>

<p style="font-size: 10px;">
Con la presente iscrizione la squadra in predicato nelle figure dei tre responsabili dichiarano;<br />
- di conoscere e accettare lo statuto e tutti i regolamenti MIDLAND GS per la stagione sportiva <br />
- di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle 
manifestazioni” <br />
- che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva amatoriale<br /><br />

Il Presidente e tutto il Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. 
Eventuali disdette saranno accettate (con relativa restituzione del deposito cauzionale) entro 7 giorni dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari).<br /><br />

<a href="http://www.midlandsport.it" style="text-decoration: none;">Midland Global Sport SSDRL</a>
</p>

</div>
				</td>
			</tr><!-- close post -->
			
		</tbody>
		<tfoot align="left">
			<tr class="post-footer"> <!-- footer -->
				<td>
					<p>
						<a href="http://midlandsport.it/contatti" style="text-decoration: none;">
							

				<img src="http://www.midlandsport.it/img/signupft.jpg" alt=""/>

						</a>
					</p>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
</body>
</html>