<!-- -->
<? //GIUSEPPE 13/11/2016
	$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
	
	$nameClass = $classPage["Name"];
	
	$type_sport = array("primary"=>"CALCIO","secondary"=>"CALCIO","quaternary"=>"TENNIS");
?>

<? //GIUSEPPE 24/10/2016
	
	$anno_sportivo = $this->requestAction('sections/readAnnoSportivo'); // questo valore lo troviamo nel controller 
	
	$anno_precedente = $anno_sportivo - 1;
?>
<? if (!$this->Session->read('Login.data')): ?>

<? if (isset($_GET['step']) && $_GET['step']==2): ?>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		
		$(".signin .dropdown-menu").css({
			display: "block",
			opacity: "1",
			top: "auto"
			
		});
		
	});
	
</script>

<? endif;?>

<? endif; ?>


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
								<?//GIUSEPPE 24/10/2016 inserito anno sportivo automatico?>
								
								Tesseramenti e iscrizioni online <?=$anno_precedente."/".$anno_sportivo?>
							</h2>
						</div>
					</div>
					
					<?php $steps = array(
						
						
						'1'=>'Iscrizione',
						'2'=>'Cauzione',
						'3'=>'Tesseramenti',
						'4'=>'Pagamento'		
						); 
						
						//GIUSEPPE ---selezione dei passaggi iscizione/tesseramento
						
						if(isset($_GET['step']))
						{
							$step = $_GET['step'];
							
							if($step == 2 || $step == 3 )
							{
								if($type_sport[$nameClass]=="CALCIO")
								{
									$steps = array(
									
									'1'=>'Iscrizione',
									'2'=>'Cauzione',
									'3'=>'Pagamento',
									'5'=>'Conferma Dati'
									); 
									
								}
								elseif($type_sport[$nameClass]=="TENNIS")
								{
									$steps = array(
									
									'1'=>'Iscrizione',
									'2'=>'Quota iscrizione',
									'3'=>'Pagamento',
									'5'=>'Conferma Dati'
									
									); 
								}
								
								
							}
							
						}
						
						
						
						if (isset($_GET['c']))
						{
							
							$c = $_GET['c'];
							
							switch($c)
							{
								case 0: // tesseramento
								$steps = array(
								
								'3'=>'Tesseramenti',
								'4'=>'Pagamento',
								'5'=>'Conferma Dati'
								
								); 
								
								break;
								
								
								case 1: // iscrizione
								
								switch($type_sport[$nameClass])
								{
									case "CALCIO":
									$steps = array(
									'1'=>'Iscrizione',
									'2'=>'Cauzione',
									'4'=>'Pagamento',
									'5'=>'Conferma Dati'
									); 
									break;
									
									
									case "TENNIS":
									$steps = array(
									
									'1'=>'Iscrizione',
									'2'=>'Quota iscrizione',
									'4'=>'Pagamento',
									'5'=>'Conferma Dati'
									
									); 
									break;
									
								}
								
								
								
								break;
								
								
							}
							
							
							
						}
						
						/*$steps = array(
							
							
							'1'=>'Informazioni',
							'2'=>'Iscrizione',
							'3'=>'Cauzione',
							'4'=>'Tesseramenti',
							'5'=>'Pagamento'
							
						);*/ ?>
						
						
						<? $cur_step = (isset($_GET['step']))? $_GET['step']:1; ?>
						
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
					
					
					<? if (!isset($_GET['step']) || $_GET['step']==1): ?>
					
					
					
					<div class="col-md-6">
						<div class="featured-box primo featured-box-quaternary mt-sm" style="">
							<div class="box-content">
								<div class="row">
									<div class="col-md-12">
										
										<?if($type_sport[$nameClass]=="CALCIO"):?>
										<h2 class="mb-lg text-left">
											<i class="fa fa-shield fa-2x m-none" style=""></i> Iscrizione della squadra
										</h2>
										<p class="text-left">
											
											Con la presente iscrizione la squadra è a tutti gli effetti iscritta alla manifestazione.<br />
											Anche se in attesa del versamento del deposito cauzionale e dell'effettuazione dei tesseramenti 
											assicurativi per i giocatori.<br />
											Di seguito si dovrà versare il deposito cauzionale suddetto almeno che non sia già stato versato nella stagione in corso per altre manifestazione e di conseguenza già in giacenza in sede.<br />
											Il diritto di recesso è esercitabile a termine di legge nei 7 giorni successivi all'iscrizione (attraverso 
											comunicazione scritta degli stessi autori dell'iscrizione con fax o e-mail) a meno che  non siano 
											chiuse le iscrizioni, infatti da tal giorno non sarà più possibile effettuare disdette.  
										</p>

										<!-- <button class="btn btn-quaternary mr-xs mb-lg" type="button"  onclick="location.href='/subscriptions/tesseramenti?step=2';">Procedi con l'iscrizione</button> -->
										<button class="btn btn-quaternary mr-xs mb-lg" type="button"  onclick="location.href='/prossime-manifestazioni';">Procedi con l'iscrizione</button>
										
										<? elseif($type_sport[$nameClass]=="TENNIS"):?>
										
										<h2 class="mb-lg text-left">
											<i class="fa fa-shield fa-2x m-none" style=""></i> Iscrizione ai tornei
										</h2>
										<p class="text-left">
											
											Con questa veloce e pratica funzione on line, dopo esserti registrato, potrai iscrivere ai 
											tornei la tua squadra (o direttamente te ai tornei per singoli).<br />
											Scegli il torneo, specifica il campo di gioco, le preferenze di data e ora e inserisci i dati di uno, due o tre responsabili.<br />
											Passa così direttamente al pagamento della quota di iscrizione con carta di credito o bonifico bancario. <br />
											&nbsp;</br />
											&nbsp;</br />
										</p>
										
										<!-- Inserito link al google DOC -->
										<button class="btn btn-quaternary mr-xs mb-lg" type="button"  onclick="window.open('https://www.mgstennis.it/contenuti/164/campionati-a-squadre');">Procedi con l'iscrizione</button>

										<? endif?>
										
										<!-- <button class="btn btn-quaternary mr-xs mb-lg" type="button"  onclick="location.href='/subscriptions/tesseramenti?step=2';">Procedi con l'iscrizione</button> -->

									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						
						<div class="featured-box secondo featured-box-quaternary mt-sm" style="">
							<div class="box-content">
								<div class="row">
									<div class="col-md-12">
										<?if($type_sport[$nameClass]=="CALCIO"):?>
										<h2 class="mb-lg text-left"> 
											<i class="fa fa-group fa-2x m-none"></i> Tesseramento di atleti e/o dirigenti
										</h2>
										
										<p class="text-left">
											
											Alla manifestazione possono partecipare solo i tesserati per la stagione sportiva in corso.<br />
											Ogni squadra dovrà avere un minimo consigliato, iniziale di giocatori tesserati, 8 per C5 e 10 per 
											C7, non esiste un limite massimo al numero dei tesserati.<br />
											L’accettazione del tesseramento sarà a discrezione del consiglio L.D.A. Midland.<br />
											Sono soggetti a tesseramento anche gli elementi facenti parte dello staff tecnico, della dirigenza e 
											gli accompagnatori della squadra che intendono seguire la stessa all’interno del terreno di gioco.
											&nbsp;</br />
											&nbsp;</br />
											&nbsp;</br />
										</p>
										
										

										<? elseif($type_sport[$nameClass]=="TENNIS"):?>
										
										<h2 class="mb-lg text-left"> 
											<i class="fa fa-group fa-2x m-none"></i> Tesseramento giocatori
										</h2>



										<p class="text-left">
											
											Alle manifestazioni possono partecipare solo i tesserati per la stagione sportiva in corso.<br />
											Con questa procedura puoi tesserare velocemente uno o più giocatori. Accedi direttamente alla funzione, verifica la presenza o meno dell'atleta/i nel database, inserisci così i dati richiesti e passa al pagamento solo con carta di credito.<br />
											I tesseramenti si possono effettuare anche presso la sede o inviando via e-mail o via fax l'apposito modulo (completamente compilato) con la ricevuta del relativo bonifico.
										</p>
										
										<? endif?>
										
										
										<button class="btn btn-quaternary mr-xs mb-lg" type="button"  onclick="location.href='/subscriptions/tesseramenti?step=4&c=0&d=1&sport=<?=$type_sport[$nameClass]?>';">Procedi con il tesseramento</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<? endif; ?>
					
					
					<? if (isset($_GET['step']) && $_GET['step']==2): ?>
					
					<div style="padding: 20px;">
						<?=$this->element('subscriptions/add');?>
					</div>
					<? endif; ?>
					
					<? if (isset($_GET['step']) && $_GET['step']==3): ?>
					
					<div style="padding: 20px;">
						<?=$this->element('subscriptions/cauzione');?>
					</div>
					<? endif; ?>
					
					<? if (isset($_GET['step']) && $_GET['step']==4): ?>
					
					<div style="padding: 20px;">
						<?=$this->element('subscriptions/tesseramenti',array('squadres'=>$squadres));?>
					</div>
					<? endif; ?>
					
					<? if (isset($_GET['step']) && $_GET['step']==5): ?>
					
					<div style="padding: 20px;">
						<?=$this->element('subscriptions/pagamento');?>
					</div>
					<? endif; ?>
					
					
				</div>
			</div>
		</div>
	</div>		
