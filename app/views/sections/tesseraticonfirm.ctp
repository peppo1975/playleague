<? //GIUSEPPE  20/10/2016 -> filtra la classe
	
	$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
	
	$nameClass = $classPage["Name"];
	
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
						<li>Tesseramenti e iscrizioni online  <?=$anno_precedente."/".$anno_sportivo?></li>
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
								Tesseramenti e iscrizioni online  <?=$anno_precedente."/".$anno_sportivo?>
							</h2>
						</div>
					</div>
					
					<? /*$steps = array(
						
						
						'1'=>'Iscrizione',
						'2'=>'Cauzione',
						'3'=>'Tesseramenti',
						'4'=>'Pagamento'
						
					);*/ 
					
					
					$steps = array(
					
						'1'=>'Tesseramenti',
						'2'=>'Pagamento',
						'5'=>'Conferma Dati'
					
					);
					
					?>
					
					<? if (isset($_GET['d']) && $_GET['d']==1): ?>
					
					
					<? $steps = array(
						
						'1'=>'Tesseramenti',
						'2'=>'Pagamento',
						'5'=>'Conferma Dati'
						
					); ?>
					<? endif; ?>
					
					<? /*$steps = array(
						
						
						'1'=>'Informazioni',
						'2'=>'Iscrizione',
						'3'=>'Cauzione',
						'4'=>'Tesseramenti',
						'5'=>'Pagamento'
						
					);*/ ?>
					
					
					<? $cur_step =  5; ?>
					
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
						
						<div class="alert alert-success" style="text-align: center;">
							Grazie, in data <strong><?=date("d/m/Y H:i");?></strong> abbiamo ricevuto la tua richiesta per n.° 
							<strong><?=count($tesserati['atleti']);?> </strong> tesseramenti <br />e un pagamento di 
							<strong><?=$tesserati['atleti'][0]['totale'];?> &euro;</strong> effettuato tramite <strong>Carta di Credito</strong>
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
