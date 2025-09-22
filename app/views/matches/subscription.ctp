<script type="text/javascript">
	
	$(document).ready(function() {

		$("#SubscriptionSegnalazioni").css('width','95%');
				//$("#SubscriptionSegnalazioni").css('width','95%');
		$("#SubscriptionCampionatoId").change(function() {


			var id = $(this).val();

			$.get('/matches/subscriptiongetgirone/'+id,function(ret) {

				$("#SubscriptionGironeId").html('');

				for (data in ret) {


					$("#SubscriptionGironeId").append('<option value=' + data + '>' + ret[data] + '</option>');

				}

			},'json');

		});

	});

</script>
<div class="wrapper-box">
		<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
	
			<div class="contents-box" id="bg-retino">
				<h1>MODULO D’ISCRIZIONE CAMPIONATI</h1>
				<div class="clear"></div>
				<? if (!isset($_POST['data']['Subscription'])): ?>
				<?=$this->Form->create('Subscription', array('url' => $_SERVER['REQUEST_URI'], 'id' => 'formSignupAthlete'));?>
				
				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('squadra_id', array('type' => 'select', 'label' => 'La società sportiva:', 'readonly'=>'readonly','disabled'=>'disabled', 'options' => $squadres,'value'=>$squadra_id,'div' => false));?>	
				</div>

				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('campionato_id', array('autocomplete'=>false,'empty'=>true,'type' => 'select', 'label' => 'Intende iscriversi al campionato:', 'options'=>$campionati, 'div' => false));?>
				</div>
				<div class="clear"></div>
				<br />
				<h2 style="padding-left: 0px;">Preferenze:</h2>
				<div class="clear"></div>
				<div class="input required">
				<?=$this->Form->input('campo_id', array('type' => 'select', 'label' => 'Impianto:', 'options'=>$campis,'div' => false));?>
				</div>
				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('girone_id', array('type' => 'select', 'label' => 'Girone:', 'options'=>array('---------------------------------'),'div' => false));?>
				</div>
				<div style="float: left; width: 50%;">
				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('giorno', array('type' => 'select', 'label' => 'Giorno:','options'=>array('Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato'), 'div' => false));?>
				</div>
				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('orario', array('type' => 'time', 'label' => 'Orario', 'showMeridian'=>false,'meridian'=>false,'timeFormat'=>'24','div' => false));?>
				</div>		



				</div>	

				<div class="clear"></div>
				<br /><br />
							<div class="input required" style="clear: both; width: 100%;">
				<?=$this->Form->input('segnalazioni', array('type' => 'text', 'label' => 'Eventuali segnalazioni','width'=>'100%', 'showMeridian'=>false,'meridian'=>false,'timeFormat'=>'24','div' => false));?>
				
				</div>			

				<br />
				<h2 style="padding-left: 0px;">Responsabili:</h2>
				<div class="clear"></div>
				<div class="input required"  style="float: left; width: 50%;">
				<?=$this->Form->input('responsabile_1', array('type' => 'select', 'label' => 'Responsabile 1:', 'options'=>$responsabili,'div' => false));?>
				</div>
				<div class="input"  style="float: left; width: 50%;">
				<?=$this->Form->input('responsabile_2', array('empty'=>true,'type' => 'select', 'label' => 'Responsabile 2:', 'options'=>$responsabili,'div' => false));?>
				</div>
				<div class="clear"></div>

				<br /><br />				
				<div class="input">
				<?=$this->Form->input('responsabile_3', array('empty'=>true,'type' => 'select', 'label' => 'Responsabile 3:', 'options'=>$responsabili,'div' => false));?>
				</div>
				<div class="input">
				<label>&nbsp;</label>
				<?=$this->Form->submit('Richiedi iscrizione',array('type' => 'submit','div' => false, 'id' => 'controlla'));?>
				</div>		
				
				<?=$this->Form->end();?>
				<div style="padding: 20px;">
				<p>
				Il Presidente e tutto il Consiglio Direttivo (vice presidente e segretario) della Società in predicato, che si propone come partecipante alla suddetta manifestazione, dichiarano a nome
di tutti i componenti della squadra di:<br />
- accettare lo statuto e tutti i regolamenti MIDLAND per la stagione sportiva in predicato;<br />
- di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle
manifestazioni” consegnato al momento dell’iscrizione;<br />
- che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva;<br />
- che tutti i tesserati della squadra sono in possesso della certificazione medica valida per il periodo di svolgimento della manifestazione sportiva MIDLAND.<br /> Il Presidente e tutto il
Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro
ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. Eventuali disdette saranno accettate (con restituzione del deposito cauzionale) entro 7 giorni
dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari)

</p>
</div>
<? else :?>
				<div style="padding: 20px;">

<p>Grazie, la richiesta di iscrizione della squadra è avvenuta correttamente. Riceverai al più presto una e-mail di conferma.</p>
</div>
<? endif; ?>
				<div class="athlete_info">
				
				</div>
	
			</div>
		</div>
		<div class="wrapper-box-bottom"></div>
</div>
