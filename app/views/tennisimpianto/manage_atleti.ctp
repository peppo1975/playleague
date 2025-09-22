<?php
$isSingolo = $r['Campionati']['TipoTorneoTennis'] == 'S';
?>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li><a href="/gestione/impianto/index_tornei">Elenco tornei</a></li>
						<li class="active">Gestisci giocatori torneo</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  	<div class="container" id="main-custom">
  	<div class="row">
  	<div class="col-md-8 col-md-offset-2">
	
		<h2 class="text-center">Gestisci giocatori torneo</h2>
		<p>Tipo torneo: <?=$isSingolo ? 'Singolo' : 'Doppio'?></p>
		<?=$this->Form->create('SquadreCampionati', [
			'url' => '/gestione/impianto/manage_atleti/'.$campionatoId, 
			'id' => 'manage-atleti-form',
			"class" => "form-horizontal form-bordered"]); ?>

			<div class="form-group">
				<?php for($i = 1; $i <= ($isSingolo ? 1 : 2); $i++ ): ?>
				<div class="col-md-<?=($isSingolo ? 10 : 5 )?>">
					<?=$this->Form->input("atleta.$i",array('type' => 'select', 'label' => false, 'div' => false, 'class' => 'select2'));?>
				</div>
				<?php endfor; ?>
				<div class="col-md-2">
					<?=$this->Form->submit('Aggiungi',array('type' => 'submit','class' => 'btn btn-lg btn-info'));?>
				</div>
			</div>
		<?=$this->Form->end();?>

		<?php if(empty($squadre)): ?>
			<p class="text-center">Nessun giocatore presente</p>
		<?php else: ?>
			<table class="table table-bordered">
				<tr>
					<th>Nome</th>
					<th>Azioni</th>
				</tr>
			<?php foreach($squadre as $r): ?>
				<tr>
					<td><?=$r['Squadre']['Denominazione'];?></td>
					<td>
						<a href="/gestione/impianto/rinomina_squadra/<?=$r['Squadre']['Squadra'];?>/<?=$campionatoId;?>">Cambia nome</a> | 
						<a href="/gestione/impianto/delete_atleta_torneo/<?=$r['SquadreCampionati']['SquadraCampionato'];?>">Rimuovi</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>

		<a href="/gestione/impianto/manage_partite/<?=$campionatoId?>/<?=$gironeId?>" class="btn btn-lg btn-info">Gestione partite</a>

	</div>
	</div>
	</div>
</div>

<script type="text/javascript">

$(function(){


});
$(".select2").select2({
  ajax: {
    url: "/gestione/impianto/search_atleti",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    processResults: function (data) {
      return {
        results: data
      };
    },
    cache: true
  },
  placeholder: "Cerca atleta per cognome o numero tessera",
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 2,
});

</script>