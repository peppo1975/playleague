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
							
							<? $giorno_settimana = ["Domenica","Lunedi","Martedi", "Mercoledi","Giovedi","Venerdi","Sabato","Domenica"]; // ho messo due volte domenica perchè non so se è definta come indice 0  o 6 ?>
							In data <b><?=date("d/m/Y H:i");?></b> è stata effettuata un'iscrizione:
							
							<h4>RIEPILOGO DATI ISCRIZIONE:</h4>
							
							
							Nome squadra: <b> <?=$iscrizione['Subscription']['nomesquadra'];?></b><br />
							
							<? if($iscrizione['Subscription']['campionato']!=""):?>
							Campionato: <b> <?=$iscrizione['Subscription']['campionato'];?></b><br />
							<? endif?>
							
							<? if(isset($iscrizione['Subscription']['SubscriptionSegnalazioni'])):?>
							Segnalazioni: <b> <?=$iscrizione['Subscription']['SubscriptionSegnalazioni'];?></b><br />
							<? endif; ?>
							
							<? if(isset($iscrizione['Subscription']['girone'])):?>
							Girone campionato: <b> <?=$iscrizione['Subscription']['girone'];?></b><br />
							<? endif; ?>
							
							Impianto: <b> <?=$iscrizione['Subscription']['campo'];?></b><br />
							
							
							Giorno: <b> <?=$giorno_settimana[$iscrizione['Subscription']['SubscriptionGiorno']];?></b><br />
							
							
							Ora: <b> <?=$iscrizione['Subscription']['SubscriptionOra'];?></b><br />
							
							Cauzione: <b> <?=$cauzione;?></b><br />
							
							<? if(isset($squadra_tennis)):?>
							Squadra: <b> <?=$squadra_tennis;?></b><br />
							<? endif;?>
							
							<h4>Responsabili:</h4>
							
							<!--Presidente: <b><?//=$iscrizione['Subscription']["presidente"]?></b><br>
								
								Vicepresidente: <b><?//=$iscrizione['Subscription']["vicepresidente"]?></b><br>
								
							Segretario: <b><?//=$iscrizione['Subscription']["segretario"]?></b><br>-->
							
							<? 
								$reponsabili="";
								
								switch($sport)
								{
									case 0:
									$reponsabili = array('Presidente','Vice Presidente','Segretario'); 
									break;
									
									case 1:
									$reponsabili = array('Responsabile 1','Responsabile 2','Responsabile 3'); 
									break;
									
								}
								
							?>
							<? foreach ($reponsabili as $i => $responsabile): ?>
							<?if($iscrizione['Subscription']["Cognome_" . $i]!=""):?>
							<b><?=$responsabile?></b>:<br>
							Nome: <?=ucfirst($iscrizione['Subscription']['Nome_' . $i])?><br>
							Cognome: <?=ucfirst($iscrizione['Subscription']["Cognome_" . $i])?><br> 
							Data di nascita: <?=$iscrizione['Subscription']['DataNascita_it_' . $i]?><br>
							Codice fiscale: <?=$iscrizione['Subscription']['CodiceFiscale_' . $i]?><br>
							Documento: <?=$iscrizione['Subscription']['SubscriptionTipoDocumento' . $i]?><br>
							Data di scadenza: <?=$iscrizione['Subscription']['ScadenzaDocumento_' . $i]?><br>
							Numero documento: <?=$iscrizione['Subscription']['NumeroDocumento_' . $i]?><br>
							Via: <?=$iscrizione['Subscription']['Indirizzo_' . $i]?><br>
							Cap: <?=$iscrizione['Subscription']['Cap_' . $i]?><br>
							Comune: <?=ucfirst($iscrizione['Subscription']['Localita_' . $i])?><br>
							Provincia: <?=strtoupper($iscrizione['Subscription']['Provincia_' . $i])?><br>
							Telefono cellulare: <?=$iscrizione['Subscription']['Cellulare_' . $i]?><br>
							Email: <?=$iscrizione['Subscription']['Email_' . $i]?><br>
							
							<br><br>
							<? endif; ?>
							<? endforeach ?>
							
							
							<h4>Coordinate bancarie (nel caso di pagamento da effettuarsi con bonifico):</h4>
							
							Intestatario: <strong>Midland Global Sport ssdrl</strong> <br />
							IBAN: <strong>IT 19 C 05704 02803 000000197100</strong>

							
							
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
