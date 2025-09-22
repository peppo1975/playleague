<?php
$default_array = [
	"label" => false,
	"div" => false,
	"class" => "form-control"
	];

$fields = [
	"Denominazione" => [],
];

$names = [
	"Denominazione"    => "Denominazione",
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
						<li class="active">Rinomina squadra</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">
  	<div class="row">
  	<div class="col-md-8 col-md-offset-2">
	
		<h2 class="text-center">Rinomina squadra</h2>
		<?=$this->Form->create('Squadre', [
			'url' => '/gestione/impianto/rinomina_squadra/'.$id.'/'.$campionatoId, 
			'id' => 'rinomina_squadra-form',
			"class" => "form-horizontal form-bordered"]); ?>

			<div class="form-group">

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
			</div>
		<?=$this->Form->end();?>


	</div>
	</div>
	</div>
</div>