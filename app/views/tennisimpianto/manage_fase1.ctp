<?php
$nBye = $squadreTorneo - count($squadre);
$squadreKeys = array_keys($squadreList);

$data = flatten($this->data, '');

// debug($squadreList);
?>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li><a href="/gestione/impianto/index_tornei">Elenco tornei</a></li>
						<li class="active">Gestisci partite torneo</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">
  	<div class="row">
  	<div class="col-md-6 col-md-offset-3 text-center">
	
		<h2 class="">Gestisci partite torneo</h2>
		<p>
			Seleziona le coppie di giocatori per generare la fase 1 del torneo.<br>
			Torneo da <b><?=$squadreTorneo?> giocatori</b>, di cui <b><?=$nBye?> bye</b>.
		</p>
		<?=$this->Form->create('Match', [
			'url' => '/gestione/impianto/create_partite/'.$campionatoId.'/'.$gironeId, 
			'id' => 'manage-partite-form',
			"class" => "form-horizontal form-bordered"]); ?>

				<?php

				// $defaultOptions = ['type' => 'select', 'label' => false, 'div' => false, 'options' => $squadreList];

				for($i=0; $i< $squadreTorneo; $i = $i+2) { 
					$pos = (int)($i/2);
					?>

			<div class="form-group">
				<div class="col-md-12 text-center">

					<select name="data[Match][<?=$pos?>][casa]" id="Match<?=$pos?>casa" class="select-squadre" data-p="casa" style="width: 250px"></select>
					<br> vs <br>
					<select name="data[Match][<?=$pos?>][trasferta]" id="Match<?=$pos?>trasferta" class="select-squadre" data-p="trasferta" style="width: 250px"></select>

					<hr>

				</div>
			</div>

				<?php } #for ?>
		<?=$this->Form->submit('Genera partite' ,array('type' => 'submit','class' => 'btn btn-lg btn-info'));?>
		<?=$this->Form->end();?>


	</div>
	</div>
	</div>
</div>

<script type="text/javascript">
	var squadre = [ 
<?php foreach($squadreList as $k=>$r){echo "{value: $k, name: \"$r\"},\n";} ?>
	];
	var squadreSelected = [];
	var preselected = [
<?php foreach($data as $k=>$r){echo "{id: \"$k\", value: \"$r\"},\n";} ?>
	];

	$(function(){

		updateSelects();
		preload();
		updateSelects();

		$('.select-squadre').change(function(e){
			updateSelects();
		});
		
	});

	function updateSelects()
	{
		squadreSelected = [];

		$('.select-squadre').each(function(){
			var selected = $(this).find(":selected").val();
			if(selected && selected != '-1')
				squadreSelected.push(selected);
		});

		$('.select-squadre').each(function(){
			var pos = $(this).data('p');
			// console.log(pos);
			filterSquadre($(this));
		});
	}

	function filterSquadre(el)
	{
		var selected = el.find(":selected").val();

		el.find('option').remove().end().append("<option>-</option>");

		$.each(squadre, function(k, obj) {
			// console.log(obj);
			el.append($("<option></option>").attr("value",obj.value).text(obj.name));
		});

		$.each(squadreSelected, function(k, val) {
			if(val != selected){
				el.children("option[value='" + val + "']").remove();
			}
		});

		if(el.data('p') == 'trasferta')
		{
			el.append("<option value='-1'>- Bye -</option>");
		}

		el.val(selected);
	}

	function preload()
	{
		$.each(preselected, function(k, obj) {
			$('#' + obj.id).val(obj.value);
		});
	}

</script>
<?php

function flatten(array $data, $separator = '.') {
	$result = array();
	$stack = array();
	$path = null;
	reset($data);
	while (!empty($data)) {
		$key = key($data);
		$element = $data[$key];
		unset($data[$key]);
		if (is_array($element) && !empty($element)) {
			if (!empty($data)) {
				$stack[] = array($data, $path);
			}
			$data = $element;
			reset($data);
			$path .= $key . $separator;
		} else {
			$result[$path . $key] = $element;
		}
		if (empty($data) && !empty($stack)) {
			list($data, $path) = array_pop($stack);
			reset($data);
		}
	}
	return $result;
}

?>