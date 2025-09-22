<?php
$sessoTipo = array(0 => 'Maschile', 1 => 'Femminile', 2 => 'Misto');
$tipoTorneo = array('S' => 'Singolo', 'D' => 'Doppio');
$default_array = [
	"label" => false,
	"div" => false,
	"class" => "form-control"
	];

$fields = [
	"Nome" => [],
	"Descrizione torneo" => ['type' => 'textarea'],
	"Singolo/Doppio" => ['options' => $tipoTorneo],
	"Tipo torneo" => ['options' => $sessoTipo],
];

$names = [
	"Descrizione torneo"    => "descrizione_torneo",
	"Singolo/Doppio"    => "TipoTorneoTennis",
	"Tipo torneo"    => "SessoTipo",
];
?>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li><a href="/gestione/impianto/index_tornei">Elenco tornei</a></li>
						<li class="active">Modifica torneo impianto</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">
	
		<h2 class="text-center">Modifica torneo</h2>
		<?=$this->Form->create('Campionati', [
			'url' => '/gestione/impianto/edit_torneo/'.$id, 
			'id' => 'edit-torneo-form',
			"class" => "form-horizontal form-bordered"]); ?>
		<?php foreach( $fields as $name => $field ): ?>
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault"><?=$name?>:</label>
				<div class="col-md-6">
					<?php if(isset($names[$name])): ?>
						<?=$this->Form->input($names[$name], array_merge($default_array, $fields[$name])) . "\n";?>
					<?php else: ?>
						<?=$this->Form->input($name, array_merge($default_array, $fields[$name])) . "\n";?>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="text-center">
		<?=$this->Form->submit('Salva',array('type' => 'submit','class' => 'btn btn-lg btn-info'));?>
		</div>
		<?=$this->Form->end();?>

	</div>
</div>