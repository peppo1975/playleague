<form id="cristomorto" class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">
	<section class="panel">
		<header class="panel-heading">
			
			<h2 class="panel-title">Iscrizione</h2>
		</header>
		<div class="panel-body">
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">Tipologia squadra:<sup>*</sup></label>
				<div class="col-md-6">
					<?= $this->Form->input('selezione', array('options' => array('0' => 'Nuova squadra', '1' => 'Squadra già esistente'), 'type' => 'select', 'label' => '', 'div' => false, 'label' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>											
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDisabled">La societ&agrave; sportiva<sup>*</sup></label>
				<div class="col-md-6">
					
					<div class="nuovasquadra">
						<?= $this->Form->input('nomesquadra', array('type' => 'text', 'label' => false, 'div' => false)); ?>
					</div>
					
					<div class="esistente" style="display: none;">
						<?= $this->Form->input('nomesquadra2', array('type' => 'select', 'empty' => 'Seleziona una squadra...', 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1, 'label' => false, 'options' => $squadres_calcio, 'div' => false)); ?>					
					</div>
					
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputReadOnly">Intende iscriversi al campionato:<sup>*</sup></label>
				<div class="col-md-6">
					<?= $this->Form->input('campionato', array('empty' => 'Seleziona campionato...', 'options' => $campionati_calcio, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>	
				</div>											
			</div>
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputHelpText">Eventuali segnalazioni:</label>
				<div class="col-md-6">
					
					<?= $this->Form->input('segnalazioni', array('type' => 'textarea', 'style' => 'resize: none; height: 60px', 'maxlength' => 200, 'resizable' => 'false', 'label' => false, 'div' => false)); ?>	
				
				</div>
			</div>
			
		</div>
	</section>
	
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Preferenze</h2>
		</header>
		<div class="panel-body">
			<form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputDefault">Girone:<sup>*</sup></label>
					<div class="col-md-6">
						
						<?//= $this->Form->input('girone', array('type' => 'select', 'empty' => 'Seleziona prima un campionato', 'label' => false, 'div' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>	
						
						<select name = "data[Subscription][girone]" id="SubscriptionGirone" class="form-control populate"  type="select" label="false" div="fale">
							<option value="">Seleziona prima un campionato</option>
						</select>
						
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputDisabled">Impianto sportivo<sup>*</sup></label>
					<div class="col-md-6">
						
						<?//= $this->Form->input('campo', array('empty' => 'Seleziona prima un girone', 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>	
						
						<select name = "data[Subscription][campo]" id="SubscriptionCampo" class="form-control">
							<option value="">Seleziona prima un girone</option>
						</select>
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputReadOnly">Preferenza giorno<sup>*</sup></label>
					<div class="col-md-6">
						
						<?//= $this->Form->input('giorno', array('type' => 'select', 'empty' => 'Seleziona prima un campo', 'label' => false, 'div' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>	
						
						<select name = "data[Subscription][giorno]" id="SubscriptionGiorno" class="form-control" >
							<option value="volvo">Seleziona prima un campo</option>
						</select>
						
					</div>											
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputHelpText">Preferenza ora<sup>*</sup></label>
					<div class="col-md-6">
						
						<?//= $this->Form->input('ora', array('empty' => 'Seleziona prima un giorno', 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'form-control populate', 'data-plugin-selectTwo' => 1)); ?>	
						
						<select name = "data[Subscription][ora]" id="SubscriptionOra" class="form-control" >
							<option value="">Seleziona prima un giorno</option>
						</select>
						
					</div>
				</div>
				
			</div>
			
		</section>
		
		<!--
			<p class="lead" style="">Responsabili:</p>
		-->
		
		<? $reponsabili = array('Presidente','Vice Presidente','Segretario'); ?>
		
		<? foreach ($reponsabili as $i => $responsabile): ?>
		
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title left-element"><?= $responsabile; ?>&#9;&#9;&#9;</h2> <a class="btn btn-default right-element" onclick="reset_text_box('<?=$i?>')">Svuota</a>
				<div class="clear"></div>
			</header>
			<div class="panel-body">
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Cognome<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input type="text" name="<?= 'Cognome_' . $i ?>" data-url="/subscriptions/getresp2/<?= $i ?>" data-dest="Cognome" id="<?= 'Cognome_' . $i ?>" class="form-control select-sa autocomplete_resp">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Nome<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Nome_' . $i ?>" type="text" id="<?= 'Nome_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault" class="form-control"><strong>E-mail<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Email_' . $i ?>" type="text" id="<?= 'Email_' . $i ?>" class="form-control  email">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Data di nascita<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'DataNascita_it_' . $i ?>" type="text" id="<?= 'DataNascita_it_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<!--//GIUSEPPE 11/10/2016 -->
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Luogo di nascita:<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'LuogoNascita_' . $i ?>" type="text" id="<?= 'LuogoNascita_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Codice fiscale<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'CodiceFiscale_' . $i ?>" type="text" id="<?= 'CodiceFiscale_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Indirizzo<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Indirizzo_' . $i ?>" type="text" id="<?= 'Indirizzo_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>CAP<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Cap_' . $i ?>" type="text" id="<?= 'Cap_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Località<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Localita_' . $i ?>" type="text" id="<?= 'Localita_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault" class="form-control"><strong>Provincia<sup>*</sup></strong></label>
					<div class="col-md-6">
                                            <input name="<?= 'Provincia_' . $i ?>" type="text" id="<?= 'Provincia_' . $i ?>" class="form-control" maxlength="2" style="text-transform:uppercase">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault" class="form-control"><strong>Telefono cellulare<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'Cellulare_' . $i ?>" type="text" id="<?= 'Cellulare_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Sesso<sup>*</sup></strong></label>
					<div class="col-md-6">
						<?=
							$this->Form->input('Sesso_' . $i, array(
							'label' => false,
							'class' => 'form-control',
							'type' => 'select',
							'options' => array( 'Maschio' => 'Maschio', 'Femmina' => 'Femmina'),
							));
						?>
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Tipo Documento<sup>*</sup></strong></label>
					<div class="col-md-6">
						<?=
							$this->Form->input('TipoDocumento_' . $i, array(
							'label' => false,
							'class' => 'form-control',
							'options' => array(
							'Carta Identità' => 'Carta Identità',
							'Patente' => 'Patente',
							'Passaporto' => 'Passaporto'
							)
							));
						?>
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Numero documento<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'NumeroDocumento_' . $i ?>" type="text" id="<?= 'NumeroDocumento_' . $i ?>" class="form-control">
					</div>
				</div>
				
				<div class="form-group not-exists">
					<label class="col-md-3 control-label" for="inputDefault"><strong>Scadenza documento<sup>*</sup></strong></label>
					<div class="col-md-6">
						<input name="<?= 'ScadenzaDocumento_' . $i ?>" type="text" class="form-control" id="<?= 'ScadenzaDocumento_' . $i ?>">
					</div>
				</div>	
			</div>
			<?php if ($i == count($reponsabili) - 1): ?>
			<div class="panel-footer">
				<ul class="pager">
					
					<li class="next" id="validate">
						<a class="btn btn-success" href="#">Salva e continua <i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
			</div>
			<?php endif ?>
		</section>
		
		<? endforeach; ?>
	</form>
