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
#nextstep2 {

  background: #ff8000;
  border: 1px solid #FF5C26;
  color: #FFF;
  width: 180px;
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
  float: right;

}

.stats {

	clear:  both;
	border-left: 5px solid #FF5C26;

	padding-left: 10px;

	color: #FF5C26;
	font-size: 18px;

	line-height: 40px;
}

div.input, form div.submit {
    float: left;
    margin: 5px 20px 0 0;
}

div.required label::after {
    content: "*";
}
div.required label {
    font-weight: bold;
}
label {
    display: block;
}

.input input, textarea {
	width: auto;
}
.radio input {
    float: left;
}

.radio {
	padding-top: 15px;
}

.radio label {
    clear: right;
    float: left;
    margin-right: 10px;
}


.athlete-box {

	border-bottom: 1px solid #CCC;
	margin-top: 20px;
	margin-bottom: 20px;
	padding-bottom: 20px;
}
</style>
<div id="wrapper-contents">
<div class="wrapper-box">
<div class="wrapper-box-top"></div>
<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">

<div class="content-header">

	<div class="content-title">
	
		<h1>Tesseramenti anno <?=date("Y");?>/<?=date("Y")+1;?></h1>
	
			
	</div>

	
	<div class="clear"></div>


<div class="contents-block-left" style="float: none; width: 940px;">
<div class="contents-text">

<script type="text/javascript">
	$(document).ready(function() {


	setInterval(function() {

		$(".tesserati").text($(".athlete-box:visible").length);

		var euro = 0;

		$("body").find("#TipoAssicurazione:visible").each(function() {

			if ($(this).val() != "")
			euro += parseInt($(this).val());

		});
		$(".euro").text(euro);

	},1000);

	$(".resptype").live('change',function() {


		var val = $(this).val();

		if (val == 1) {

			$(this).closest('.athlete-box').find('.anagrafica-box').hide();
			$(this).closest('.athlete-box').find('.respname').show();
		} else {

			$(this).closest('.athlete-box').find('.anagrafica-box').show();
			$(this).closest('.athlete-box').find('.respname').hide();

		}

	});

	$("#nextstep2").click(function() {

		var box = $(".athlete-box:first").clone();


		box.show().appendTo($(".athletes-container"));
	});

	$("#nextstep2").trigger('click');

	});


	$(".removeme").live('click',function() {

		if (confirm("Rimuovere l'anagrafica selezionata?")) {
			$(this).closest('.athletes-box').remove();
		}

	});

</script>



<div class="athlete-box" style="display: none;">


<img style="cursor: pointer; float: right;" class="removeme" src="/remove.png" />


<? if (!empty($this->data['Subscription']['nomesquadra2'])&&$this->data['Subscription']['nomesquadra2']>0): ?>
	<div class="input">
		<?=$this->Form->input('atltype',array('class' => 'resptype','type' => 'select', 'label' => 'Tipo inserimento','options'=>array('0' => 'Crea un nuovo atleta','1' => 'Seleziona un atleta esistente'), 'div' => false));?>

	</div>	
	<div class="clear"></div>
				<div class="input respname" style="display: none;">
				<?=$this->Form->input('respname', array('class'=>'respnameselect','type' => 'select', 'label' => 'Seleziona un atleta','options'=>$atleti

				, 'div' => false));?>

				</div>	

	<div class="clear"></div>

<? endif; ?>

<div class="anagrafica-box">
	<?=$this->Form->input('Cognome');?>
	<?=$this->Form->input('Nome');?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Indirizzo');?>
	<?=$this->Form->input('Cap');?>
	<?=$this->Form->input('Localita');?>
	<?=$this->Form->input('Provincia');?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Telefono');?>
	<?=$this->Form->input('Cellulare');?>
	<?=$this->Form->input('Lavoro',array('label' => 'Telefono lavoro'));?>
	<?=$this->Form->input('Email', array('class' => 'big'));?>
	<?=$this->Form->input('Fax');?>	
	<?=$this->Form->input('CodiceFiscale');?>	

	
	<?=$this->Form->input('LuogoNascita',array('label' => 'Luogo di nascita'));?>
	<?=$this->Form->input('DataNascita',array('label' => 'Data di nascita','type' => 'text','class' => 'datePicker'));?>	
	<?=$this->Form->input('Sesso',
	array(
	
	'type' => 'radio',
	'options' => array( 'Maschio'=>'M', 'Femmina'=>'F' ),

	));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('TipoDocumento',array(
	
	'label' => 'Tipo documento',
	'options' => array(
	
	
		'Carta Identità' => 'Carta Identità',
		'Patente' => 'Patente',
		'Passaporto' => 'Passaporto'
	
	
	)
	));?>
	
	<?=$this->Form->input('NumeroDocumento',array('label' => 'Num. documento'));?>
	
	<?=$this->Form->input('ScadenzaDocumento',array('label' => 'Scadenza documento','type' => 'text','class' => 'datePicker'));?>
		

	<div class="clear"></div>

</div>


	<?
	$options1 = array();

	foreach($TipiAssicurazione as $TipoAssicurazione) {
	  $options1[$TipoAssicurazione['TipiAssicurazione']['Costo']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
	 }
	?>

	<div class="ass">
	<?=$this->Form->input('TipoAssicurazione', array('type'=>'select', 'empty'=>true, 'options' => $options1));?>
	
	<div class="clear"></div>
	</div>
	
</div>

<form class="">

			<div class="nuovasquadra">

				<?=$this->Form->input('nomesquadra2', array('type' => 'select', 'empty' => 'Seleziona una squadra...', 'label' => 'Seleziona squadra:','options'=>$squadres, 'div' => false));?>

				</div>	

<div class="athletes-container">
</div>
	<div class="clear"></div>

	<?=$this->Form->submit('Aggiungi nuovo atleta',array('type' => 'button','div' => false, 'id' => 'nextstep2'));?>

	<div class="clear"></div>




	<?=$this->Form->submit('Prosegui',array('type' => 'submit','div' => false, 'id' => 'nextstep'));?>
	<br /><br />


</form>

<div class="stats">


<div>Atleti da tesserare: <span class="tesserati">0,00</span></div>

<div>Totale importo da pagare: <span class="euro">0,00</span> &euro;</div>

</div>


</div>
</div>
</div>
</div>
</div>
</div>
</div>