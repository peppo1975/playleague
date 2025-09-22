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


<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="">Iscrizione online</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
		
		<div class="row">
			<div class="col-md-12">


		<div class="post-content">
		<div class="row">
		<div class="col-md-12">

				<h2 class="">


				Iscrizione campionati 2015/2016

				</h2>
				</div>
		</div>
		<p class="lead">1) Modulo d'iscrizione</p>
		<hr />

<? if (!$this->Session->read('Login.data')): ?>



	<div class="alert alert-danger">Effettua il login per accedere alla funzione iscrizioni.</div>

	<script type="text/javascript">

	</script>




<? else: ?>

<script type="text/javascript">
	
$(document).ready(function() {




	var campionati_json = <?=json_encode($campionatijson);?>;
	var ret_campi = <?=json_encode($campij); ?>;
	var giorni = <?=json_encode($giorni); ?>;
	var prefill = <?=json_encode($prefill); ?>;

	var row;
	for (var z = 0; z < prefill.length; z++) {


		row = prefill[z];

		if ($("#" + row.key).length > 0) $("#" + row.key).val(row.value);

	}


	$(".input.required").each(function() {

		$(this).find('input:visible, select:visible').attr('required','required');
	$(this).find('input:hidden, select:hidden').attr('required','');

	});

	setInterval(function() {


	$(".input.required").each(function() {


	$(this).find('input:hidden, select:hidden').attr('required','');
		$(this).find('input:visible, select:visible').attr('required','required');

});

	},2000);

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

	$("#SubscriptionSelezione").change(function() {


		if ($(this).val() == 0) {

		 $(".nuovasquadra").show();
		 $(".esistente").hide();
		 $(".esistente2").hide();
		} else {

			$(".nuovasquadra").hide();
			$(".esistente").show();
		}
	});

	$("select#SubscriptionNomesquadra2").change(function() {

		$(".esistente2").show();
		$(".respnameselect").html('');
		$.get("/subscriptions/getresp/" + $(this).val(),function (ret) {

/*
			for (var i = 0; i < ret.length; i++) {
*/

			console.log(ret);
			for (i in ret)
				if (ret[i] != "null" && ret[i] != "" && ret[i] != null) 
					$(".respnameselect").append('<option value="' + ret[i] + '">' + ret[i] + "</option>");
			

		},'json');

	});

	$(".resptype").change(function() {


		var index = $(this).attr('data-index');

		var val = $(this).val();

		if (val == 1) {


			$(".classic-form[data-index=" + index + "]").hide();
			$(".respname[data-index=" + index + "]").show();
		} else {

			$(".classic-form[data-index=" + index + "]").show();
			$(".respname[data-index=" + index + "]").hide();

		}

	})
});


</script>


				<?=$this->Form->create('Subscription', array('url' => '/subscriptions/add', 'id' => 'subForm','autocomplete'=>'off'));?>
				

				<div class="input required" style="float: left; width: 50%;">


				
				<?=$this->Form->input('selezione', array('options'=>array('0'=>'Squadra nuova','1' => 'Squadra esistente'),'type' => 'select', 'label' => 'Tipologia squadra: ', 'div' => false));?>	
				</div>


				<div class="input required" style="float: left; width: 50%;">


				<div class="nuovasquadra">
				<?=$this->Form->input('nomesquadra', array('type' => 'text', 'label' => 'La società sportiva: ', 'div' => false));?>
				</div>
				<div class="esistente" style="display: none;">
				<?=$this->Form->input('nomesquadra2', array('type' => 'select', 'empty' => 'Seleziona una squadra...', 'label' => 'La società sportiva:','options'=>$squadres, 'div' => false));?>

				</div>	
				</div>


				<div class="clear"></div>
				<br />
				<div class="input required" style="float: left; width: 50%">
				<?=$this->Form->input('campionato', array('empty'=> 'Seleziona campionato...', 'options' => $campionati, 'type' => 'select', 'label' => 'Intende iscriversi al campionato:', 'div' => false));?>	
				</div>
				<div class="clear"></div>
				<br />
				<div class="input" style="width: 100%;">

				<?=$this->Form->input('segnalazioni', array('type' => 'textarea','style'=>'resize: none; height: 60px','maxlength'=>200,'resizable'=>'false','label' => 'Eventuali segnalazioni**', 'div' => false));?>	
				</div>


				<div class="clear"></div>
				<p class="post-info" style="padding-left: 0px; font-size: 14px;">Preferenze:</p>


				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('girone', array('type' => 'select', 'empty' => 'Seleziona prima un campionato','label' => 'Girone', 'div' => false));?>	
				</div>

				<div class="input required" style="float: left; width: 50%">
				<?=$this->Form->input('campo', array('empty'=> 'Seleziona prima un girone', 'type' => 'select', 'label' => 'Campo', 'div' => false));?>	
				</div>

				<div class="clear"></div>

				<br />

				<div class="input required" style="float: left; width: 50%;">
				<?=$this->Form->input('giorno', array('type' => 'select', 'empty' => 'Seleziona prima un campo','label' => 'Giorno', 'div' => false));?>	
				</div>

				<div class="input required" style="float: left; width: 50%">
				<?=$this->Form->input('ora', array('empty'=> 'Seleziona prima un giorno', 'type' => 'select', 'label' => 'Ora', 'div' => false));?>	
				</div>

				<div class="clear"></div>



				<div class="clear"></div>
				<p class="post-info" style="padding-left: 0px; font-size: 14px;">Responsabili:</p>



				<? $reponsabili = array('Presidente','Vice Presidente','Segretario'); ?>

				<? foreach ($reponsabili as $i => $responsabile): ?>
				<div class="resp">
	
				<h3><?=$responsabile;?>:</h3>			
				<div class="esistente2" style="display: none;">
				<div class="input">
				<?=$this->Form->input('resptype_' . $i, array('class' => 'resptype','data-index'=>$i,'type' => 'select', 'label' => 'Tipo inserimento','options'=>array('0' => 'Crea un nuovo responsabile','1' => 'Seleziona un responsabile esistente'), 'div' => false));?>

				</div>	

				<div class="input respname" data-index="<?=$i;?>" style="display: none;">
				<?=$this->Form->input('respname_' . $i, array('class'=>'respnameselect','data-index'=>$i,'type' => 'select', 'label' => 'Seleziona un responsabile','options'=>array()

				, 'div' => false));?>

				</div>	

				<div class="clear"></div>
				<br />
				</div>



				<div class="classic-form" data-index="<?=$i;?>">
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('nome_'.$i, array('type' => 'text', 'label' => 'Nome', 'div' => false));?>	
				</div>
				
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('cognome_'.$i, array('type' => 'text', 'label' => 'Cognome', 'div' => false));?>	
				</div>
				
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('data_'.$i, array('type' => 'text', 'label' => 'Nato il', 'div' => false));?>	
				</div>
				<div class="clear"></div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 50%;">
				<?=$this->Form->input('cf_'.$i, array('type' => 'text', 'label' => 'Codice fiscale', 'div' => false));?>	
				</div>
				
				<div class="clear"></div>

				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('doc_'.$i, array('type' => 'select','options'=>array('Carta di Identità','Patente','Passaporto'),'label' => 'Documento tipo:', 'div' => false));?>	
				</div>
				
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('numerodoc_'.$i, array('type' => 'text', 'label' => 'Numero', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('datadoc_'.$i, array('type' => 'text', 'label' => 'Rilasciato il', 'div' => false));?>	
				</div>

				<div class="clear"></div>
				
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('via_'.$i, array('type' => 'text', 'label' => 'Residente in via', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('cap_'.$i, array('type' => 'text', 'label' => 'CAP', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 20%;">
				<?=$this->Form->input('comune_'.$i, array('type' => 'text', 'label' => 'Comune', 'class' => 'comune', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 10%;">
				<?=$this->Form->input('pv_'.$i, array('type' => 'text', 'label' => 'Provincia', 'class' => 'pv', 'div' => false));?>	
				</div>
				<div class="clear"></div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('telefono_'.$i, array('type' => 'text', 'label' => 'Telefono', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('cellulare_'.$i, array('type' => 'text', 'label' => 'Cellulare', 'div' => false));?>	
				</div>
				<div class="input <? if ($i >= 0): ?>required<? endif;?>" style="float: left; width: 30%;">
				<?=$this->Form->input('email_'.$i, array('type' => 'text', 'label' => 'E-mail', 'div' => false));?>	
				</div>
								<div class="clear"></div>
				</div>
				</div>

			<? endforeach; ?>


<p style="font-size: 12px;">

Il Presidente e tutto il Consiglio Direttivo (vice presidente e segretario) della Società in predicato, che si propone come partecipante alla suddetta manifestazione, dichiarano a nome
di tutti i componenti della squadra di: <br />
- accettare lo statuto e tutti i regolamenti MIDLAND per la stagione sportiva in predicato; <br />
- di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle <br />
manifestazioni” consegnato al momento dell’iscrizione; <br />
- che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva; <br />
- che tutti i tesserati della squadra sono in possesso della certificazione medica valida per il periodo di svolgimento della manifestazione sportiva MIDLAND. <br />Il Presidente e tutto il
Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro
ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. Eventuali disdette saranno accettate (con restituzione del deposito cauzionale) entro 7 giorni
dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari) <br />
* Campi riservati a preferenze, non vincolanti, esprimibili in relazione solo ai Campionati invernali, in relazione alle disponibilità pubblicate <br />
** Segnalazioni del tutto non vincolanti atte alla massima miglioria dei servizi di ogni manifestazione


</p>

<br />

<?=$this->Form->submit('Invia richiesta',array('type' => 'submit','div' => false, 'id' => 'nextstep'));?>


<?=$this->Form->end();?>


<? endif; ?>

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