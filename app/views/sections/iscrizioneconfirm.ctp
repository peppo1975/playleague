<? //GIUSEPPE  20/10/2016 -> filtra la classe

	$fixed = $this->requestAction('fixeds/read_all_fixed');//GIUSEPPE 2018-08-28 --richiama la tabella dei contenuti fissi
	
	$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
	
	$nameClass = $classPage["Name"];
	
	$type_sport = array("primary"=>"CALCIO", "secondary"=>"CALCIO", "quaternary"=>"TENNIS");
	
	$cauzione = $this->requestAction('sections/readDeposit/'.$nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)
	
	$anno_sportivo = $this->requestAction('sections/readAnnoSportivo'); // questo valore lo troviamo nel controller 
	
	$anno_precedente = $anno_sportivo - 1;

	$fixed = $this->requestAction('fixeds/read_all_fixed');//GIUSEPPE 2018-08-28 --richiama la tabella dei contenuti fissi
?>
<script type="text/javascript" src="/js/layout.js"></script>


<style type="text/css">
	
	.contents-text p, .contents-text h3 { padding-left: 20px; }
	.contents-text { padding-top: 20px; }
	
	#progress * {
    box-sizing: border-box;
	}
	
	#progress {
    padding: 0;
    list-style-type: none;
    font-family: arial;
    font-size: 12px;
    clear: both;
    line-height: 1em;
    margin: 0 -1px;
    text-align: center;
	}
	
	#progress li {
    float: left;
    padding: 10px 30px 10px 40px;
    background: #eeeeee;
    color: #444;
    position: relative;
    border-top: 1px solid #eeeeee;
    border-bottom: 1px solid #eeeeee;
    width: 19%;
    margin: 0 1px;
	}
	
	#progress li:first-child:before {
	content: none !important;
	}
	#progress li:before {
    content: '';
    border-left: 16px solid #fff;
    border-top: 16px solid transparent;
    border-bottom: 16px solid transparent;
    position: absolute;
    top: 0;
    left: 0;
    
	}
	#progress li:after {
    content: '';
    border-left: 16px solid #eeeeee;
    border-top: 16px solid transparent;
    border-bottom: 16px solid transparent;
    position: absolute;
    top: 0;
    left: 100%;
    z-index: 20;
	}
	
	#progress li.active {
    background: #fd8a15;
    color: #fff;
	}
	
	#progress li.active:after {
    border-left-color: #fd8a15;
	}
	
	
</style>

<div role="main" class="main">
	
	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<? if (@$_GET['step']==4): ?>
						<!--<li>Tesseramenti e iscrizioni online 2015/2016</li>-->
						<!--//GIUSEPPE 24/10/2016 -->
						<li>Tesseramenti e iscrizioni online <?=$anno_precedente."/".$anno_sportivo?></li>
						<? else: ?>
						<li class="">Iscrizione online</li>
						<? endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- Admin Extension Specific Page Vendor CSS -->
	
	<link rel="stylesheet" href="/vendor/theme.admin.extension.css">
	
    <link rel="stylesheet" href="/vendor/theme.extension.css">
	<div class="container" id="main-custom">
		
		<div class="row">
			<div class="col-md-12">
				
				
				<div class="post-content">
					<div class="row">
						<div class="col-md-12">
							
							<h2 class="text-center">
								
								<!--//GIUSEPPE 24/10/2016 -->
								Tesseramenti e iscrizioni online <?=$anno_precedente."/".$anno_sportivo?>
								<!--Tesseramenti e iscrizioni online 2015/2016-->
							</h2>
						</div>
					</div>
					
					<? 
						
						//GIUSEPPE 17/10/2016 -> modificata fisualizzazione passi iscrizione
						
						$steps = "";
						
						if($nameClass=="primary")
						{
							
							$steps = array(
							
							
							'1'=>'Iscrizione',
							'2'=>'Cauzione',
							'4'=>'Pagamento',
							'5'=>'Verifica Dati'
							);
							
						}
						
						if($nameClass=="quaternary")
						{
							
							$steps = array(
							
							
							'1'=>'Iscrizione',
							'2'=>'Quota iscrizione',
							'4'=>'Pagamento',
							'5'=>'Verifica Dati'
							
							);
							
						}
					?>
					
					<? if (isset($_GET['d']) && $_GET['d']==1): ?>
					
					
					<? $steps = array(
						
						'3'=>'Tesseramenti',
						'4'=>'Pagamento',
						'5'=>'Verifica Dati'
						
					); ?>
					<? endif; ?>
					
					
					<? //$cur_step =  ; ?>
					
					<hr />
					
					
					
				</div>
				
				
				<? if ($cur_step != 1): ?>	
				<div class="row">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator" style="width: 0%;"></div>
						</div>
						<ul class="wizard-steps">
							
							<? $i = 1; ?>
							<? foreach ($steps as $key => $step): ?>
							<li <? if ($key==($cur_step-1)): ?>class="active"<? endif;?>><a href="#" style="cursor: default !important;"><span style="cursor: default !important;"><?=$i;?></span><?=$step;?></a></li>
							<? $i++; ?>
							<? endforeach; ?>
							
							
						</ul>
					</div>
					
				</div>
				<? endif; ?>
				
				
				<div class="contents-text">
					
					
					
					
					<div style="padding: 20px;">
						
						<? if ($ok == 1): ?>
						
						<div class="alert alert-success">
							
							Grazie, in data <strong><?=date("d/m/Y H:i");?></strong> abbiamo ricevuto la tua richiesta di iscrizione al campionato.
						</div>
						
						<? if ($force == 1): ?>
						<? if($type_sport[$nameClass]=="CALCIO"):?>
						<div class="alert alert-success">
							
							<!--//GIUSEPPE 24/10/2016 cauzione dinamica-->
							
							Grazie, la tua richiesta di iscrizione è stata effettuata correttamente.<br /><br />
							
							Nel caso di necessità di effettuazione versamento per reintegro deposito cauzionale, riportiamo di seguito le nostre coordinate bancarie.<br /><br />
							
							
							Intestatario: <strong><?= $fixed['iban_intestatario'] ?></strong> <br />
                            IBAN: <strong><?= $fixed['iban'] ?></strong>

						</div>
						<?endif;?>
						<? endif; ?>
						
						<div class="alert alert-success">
							Grazie, la tua richiesta di iscrizione al campionato è stata effettuata correttamente.<br /><br />
							
							Con la presente iscrizione la squadra in predicato nelle figure dei tre responsabili dichiarano;<br />
							- di conoscere e accettare lo statuto e tutti i regolamenti MIDLAND GS per la stagione sportiva <br />
							- di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle 
							manifestazioni” <br />
							- che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva amatoriale<br /><br />
							
							Il Presidente e tutto il Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. 
							Eventuali disdette saranno accettate (con relativa restituzione del deposito cauzionale) entro 7 giorni dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari).<br /><br />
						</div>
						
						
						
						<div class="call-to-action-btn" style="text-align: center; margin-top: 40px;"> 
							<a class="btn btn-sm btn-primary" href="/"><?= $fixed['message_torna_home'] ?></a>
						</div>				
						<? else: ?>
						<div class="alert alert-danger" style="text-align: center;">
							<?= $fixed['alert_message_transazione'] ?> <a href="/subscriptions/tesseramenti" class="alert-link"><?= $fixed['alert_message_riprova'] ?></a>
						</div>
						<div class="call-to-action-btn" style="text-align: center; margin-top: 40px;"> 
							<a class="btn btn-sm btn-primary" href="/"><?= $fixed['message_torna_home'] ?></a>
						</div>	
						
						<? endif; ?>
						</div>
						
						
						</div>
						</div>
		</div>
	</div>
