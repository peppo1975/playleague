<style type="text/css">
	
select {

  border: 1px solid #ccc;
  padding: 5px;
  font-size: 12px;
  width: 310px;

}

textarea {

	width: 768px;
}

.resp {
	margin-top: 10px;
	padding-bottom: 10px;
	border-bottom:  1px dotted #ccc;

}


.resp .input {

	margin-bottom: 10px;
}
.resp .comune {

	width: 170px;
}
.resp .pv {

	width: 30px;
}
.resp input, .resp select {


	width: 200px;



}
#nextstep {

  background: #ff8000;
  border: 1px solid #FF5C26;
  color: #FFF;
  width: 108px;
  height: 40px;
  line-height: 40px;
  cursor: pointer;
  padding-left: 5px;
  padding-right: 5px;
  text-align: center;
  margin-top: 10px;
  border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  font-size: 13px;

}

</style>
<div id="wrapper-contents">
<div class="wrapper-box">
<div class="wrapper-box-top"></div>
<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">

<div class="content-header">

	<div class="content-title">
	
		<h1>Iscrizione campionati <?=date("Y");?>/<?=date("Y")+1;?></h1>
	
			
		<h2>1) Modulo d'iscrizione</h2>

			
	</div>

	
	<div class="clear"></div>


<div class="contents-block-left" style="float: none; width: 940px;">
<div class="contents-text">



<script type="text/javascript">
	
$(document).ready(function() {


	var campionati_json = <?=json_encode($campionatijson);?>;
	var ret_campi = <?=json_encode($campij); ?>;
	var giorni = <?=json_encode($giorni); ?>;

	$(".input.required").each(function() {

		$(this).find('input, select').attr('required','required');

	});


	function resetAll() {


		$("#SubscriptionGirone").html('<option value="">Seleziona prima un campionato...</option>');
		$("#SubscriptionCampo").html('<option value="">Seleziona prima un girone...</option>');
		$("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un campo...</option>');
		$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');

	}

	function resetAll2() {


		$("#SubscriptionCampo").html('<option value="">Seleziona prima un girone...</option>');
		$("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un campo...</option>');
		$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');

	}
	function resetAll3() {


		$("#SubscriptionGiorno").html('<option vlue="">Seleziona prima un campo...</option>');
		$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');

	}
	function resetAll4() {


		$("#SubscriptionOra").html('<option vlue="">Seleziona prima un giorno...</option>');

	}
	function fillCampi(girone_id) {
		if (girone_id == "" || girone_id == 0) {

			resetAll2();
			return;


		}

 		$("#SubscriptionCampo").html('<option value="" selected></option>');
 		var campionato_id = $("#SubscriptionCampionato").val();

		for (var i = 0; i < campionati_json[campionato_id][girone_id].Campo.length-1; i++) {



			if ($("#SubscriptionCampo").find('option[value=' + campionati_json[campionato_id][girone_id].Campo[i]  + ']').length == 0) {

			$("#SubscriptionCampo").append('<option value="' + campionati_json[campionato_id][girone_id].Campo[i] + '">' + ret_campi[campionati_json[campionato_id][girone_id].Campo[i]] +'</option>');

			}

		}

	}



	function fillGiorni(campo_id) {
		if (campo_id == "" || campo_id == 0) {

			resetAll3();
			return;


		}

 		$("#SubscriptionGiorno").html('<option value="" selected></option>');
 		
 		var campionato_id = $("#SubscriptionCampionato").val();
 		var girone_id = $("#SubscriptionGirone").val();
 		var campo_id = $("#SubscriptionCampo").val();

		for (var i = 0; i < campionati_json[campionato_id][girone_id].Giorno.length-1; i++) {



			if (campionati_json[campionato_id][girone_id].Campo[i] == campo_id && $("#SubscriptionGiorno").find('option[value=' + campionati_json[campionato_id][girone_id].Giorno[i]  + ']').length == 0) {

			$("#SubscriptionGiorno").append('<option value="' + campionati_json[campionato_id][girone_id].Giorno[i] + '">' + giorni[campionati_json[campionato_id][girone_id].Giorno[i]] +'</option>');

			}

		}

	}

	function fillOra(giorno_id) {
		if (giorno_id == "" || giorno_id == 0) {

			resetAll4();
			return;


		}

 		$("#SubscriptionOra").html('<option value="" selected></option>');
 		
 		var campionato_id = $("#SubscriptionCampionato").val();
 		var girone_id = $("#SubscriptionGirone").val();
 		var campo_id = $("#SubscriptionCampo").val();

		for (var i = 0; i < campionati_json[campionato_id][girone_id].Orario.length-1; i++) {



			if (campionati_json[campionato_id][girone_id].Giorno[i] == giorno_id && campionati_json[campionato_id][girone_id].Campo[i] == campo_id && $("#SubscriptionOra").find('option[value="' + campionati_json[campionato_id][girone_id].Orario[i]  + '"]').length == 0) {

			$("#SubscriptionOra").append('<option value="' + campionati_json[campionato_id][girone_id].Orario[i] + '">' + campionati_json[campionato_id][girone_id].Orario[i] +'</option>');

			}

		}

	}

	function fillGironi(campionato_id) {


		if (campionato_id == "" || campionato_id == 0) {

			resetAll();
			return;
		}
 		$("#SubscriptionGirone").html('<option value="" selected></option>');

		for (var i = 0; i < campionati_json[campionato_id].gironi.length; i++) {


			$("#SubscriptionGirone").append('<option value="' + campionati_json[campionato_id].gironi[i].id + '">' + campionati_json[campionato_id].gironi[i].nome +'</option>');

		}
	}

	$("#SubscriptionCampionato").change(function() {

			fillGironi($(this).val());

	});
	$("#SubscriptionGirone").change(function() {

			fillCampi($(this).val());

	});

	$("#SubscriptionCampo").change(function() {

			fillGiorni($(this).val());

	});

	$("#SubscriptionGiorno").change(function() {

			fillOra($(this).val());

	});
});


</script>


<?=$this->Form->create('Subscription', array('url' => '/subscriptions/add', 'id' => 'subForm','autocomplete'=>'off'));?>

<? $index = 0; ?>

Grazie <?=$this->data['Subscription']['nome_' . $index];?> <?=$this->data['Subscription']['cognome_' . $index];?>, la tua richiesta di iscrizione al campionato <b><?=$campionato['Campionati']['Nome'];?></b> è stata effettuata correttamente.<br /><br />

Con la presente iscrizione la squadra in predicato nelle figure dei tre responsabili dichiarano;<br />
- di conoscere e accettare lo statuto e tutti i regolamenti MIDLAND GS per la stagione sportiva <br />
- di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle 
manifestazioni” <br />
- che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva amatoriale<br /><br />

Il Presidente e tutto il Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. 
Eventuali disdette saranno accettate (con relativa restituzione del deposito cauzionale) entro 7 giorni dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari).<br /><br />

<h4>Riepilogo dati:</h4><br />

<? if (!empty($this->data['Subscription']['nomesquadra'])): ?>
<b>Nome squadra:</b> <?=$this->data['Subscription']['nomesquadra'];?><br />
<? else:?>
<b>Nome squadra:</b> <?=$squadra['Squadre']['Denominazione'];?><br />
<? endif; ?>

<? if (!empty($this->data['Subscription']['segnalate'])): ?>
<b>Segnalazioni:</b> <?=$this->data['Subscription']['segnalazioni'];?><br />
<? endif; ?>

<? if (!empty($this->data['Subscription']['girone'])): ?>
<b>Girone campionato:</b> <?=$girone['Half']['Descrizione'];?><br />
<? endif; ?>
<? if (!empty($this->data['Subscription']['campo'])): ?>
<b>Impianto:</b> <?=$campo['Campi']['Descrizione'];?><br />
<? endif; ?>
<? if (!empty($giorno)): ?>
<b>Giorno:</b> <?=$giorno;?><br />
<? endif; ?>

<? if (!empty($giorno)): ?>
<b>Ora:</b> <?=$this->data['Subscription']['ora'];?><br />
<? endif; ?>


<h4>Responsabili:</h4><br />

<? $reponsabili = array('Presidente','Vice Presidente','Segretario'); ?>

<? for ($i = 0; $i < 3; $i++): ?>

<h5><?=$reponsabili[$i];?></h5>

<? if ($this->data['Subscription']['resptype_' . $i] == 0): ?>

<?=$this->data['Subscription']['nome_' . $i];?> <?=$this->data['Subscription']['cognome_' . $i];?><br />
<b>Nato il:</b> <?=$this->data['Subscription']['data_' . $i];?> - <b>C.F.</b>: <b>Nato il:</b> <?=$this->data['Subscription']['cf_' . $i];?><br />

<? $docstype = array('Carta di Identità','Patente','Passaporto'); ?>

<b>Documento tipo:</b>  <?=$docstype[$this->data['Subscription']['doc_' . $i]];?> - <?=$this->data['Subscription']['numerodoc_' . $i];?> - 
<?=$this->data['Subscription']['datadoc_' . $i];?> <br />

<b>Residenza:</b> <?=$this->data['Subscription']['via_' . $i];?>, <?=$this->data['Subscription']['cap_' . $i];?> <?=$this->data['Subscription']['comune_' . $i];?> <?=$this->data['Subscription']['pv_' . $i];?> <br />

<b>Recapiti:</b> <?=$this->data['Subscription']['telefono_' . $i];?> - <?=$this->data['Subscription']['cellulare_' . $i];?> - <?=$this->data['Subscription']['email_' . $i];?>
<? else: ?>

<?=$this->data['Subscription']['respname_' . $i];?><br />

<? endif; ?>
<br /> <br />
<? endfor; ?>
<br />
<a href="/">Continua la navigazione</a>

</p>
				
<?=$this->Form->end();?>

</div>
</div>


</div>
</div>
</div>
</div>
</div>
<!--

<script type="text/javascript">
$(document).ready(function(){
	$("#genera").click(function() {
	$("#UserPassword").val('');
	$("#UserPasswordConfirm").val('');
	$.get("/admin/users/generatepwd",function(ret) {
		$("#UserPassword").val(ret.pwd);
		$("#UserPasswordConfirm").val(ret.pwd);
		},'json');
	});
});

$(function(){

	$("#formSignupAthlete").submit(function(){
	
		var data = $(this).serialize();
		
		$('.error-message').remove();
		var error = 0;
		$('#formSignupAthlete .required').each(function(){
			var obj = $(this);
			if(obj.find('input').val() == '') {
				obj.append('<div class="error-message">Campo obbligatorio.</div>');
				error = 1;
			}
		});
		if(error == 1) return false;	
		
		ajaxLoader('show');
		
		$.post('/users/checkTessera', data, function(ret){
			$('.athlete_info').html(ret);
			ajaxLoader('hide');
		},'html');
	
		return false;
	
	});
	
});

</script>

<div class="wrapper-box">
		<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
	
			<div class="contents-box" id="bg-retino">
				<h1>Modulo registrazione atleti</h1>
				<div class="clear"></div>
				
				<?=$this->element("/backend/add_edit_scripts");?>
				<?=$this->Form->create('User', array('url' => '/registrazione/atleti', 'id' => 'formSignupAthlete'));?>
				
				<div class="input required">
				<?=$this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome', 'div' => false));?>	
				</div>
				<div class="input required">
				<?=$this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome', 'div' => false));?>
				</div>
				<div class="input required">
				<?=$this->Form->input('Tessera', array('type' => 'text', 'label' => 'Inserisci numero tessera.', 'div' => false));?>
				</div>
				<div class="input required">
				<?=$this->Form->input('signup_code', array('type' => 'text', 'label' => 'Inserisci codice controllo.', 'div' => false));?>
				</div>				
				
				<div class="input">
				<label>&nbsp;</label>
				<?=$this->Form->submit('Controlla',array('type' => 'submit','div' => false, 'id' => 'controlla'));?>
				</div>		
				
				<?=$this->Form->end();?>
				
				<div class="athlete_info">
				
				</div>
	
			</div>
		</div>
		<div class="wrapper-box-bottom"></div>
</div>

-->