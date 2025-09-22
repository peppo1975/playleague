
	<?=$this->Form->create('Yearbook', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella annuario atleti</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		function getFilter() {
		
			anno 	= $("#YearbookAnnoSportivo");
			squadra = $("#YearbookNomeSquadraCampionato");
			
			if(anno.val() == '') {
			
				squadra.attr('data-url', '/admin/yearbooks/searchSquadraCampionato');
			
			} else {
			
				squadra.attr('data-url', '/admin/yearbooks/searchSquadraCampionato/' + anno.val());
			
			}			
		
		}
		
		$(document).ready(function(){
		
			getFilter();
		
		});
	
		$('.formAdd').delegate("#YearbookAnnoSportivo","change", function(){
		
			getFilter();
		
		});
	
	});
	}
	</script>
	
	<?
	$options = array();
	$options[''] = '';
	foreach($AnniSportivi as $AnnoSportivo) {
	  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
	 }
	?>
	
	<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'options' => $options));?>
	
	<div class="input text squa-camp">
	<?=$this->Form->input('NomeSquadraCampionato',array('label' => 'Squadra/Campionato','class' => 'autoComplete','data-url' => '/admin/yearbooks/searchSquadraCampionato','data-dest' => 'isnull', 'div' => false));?>
	</div>
	
	<div class="input text nome-atleta">
	<?=$this->Form->input('NomeAtleta',array('label' => 'Atleta','class' => 'autoComplete','data-url' => '/admin/yearbooks/searchAtleta','data-dest' => 'isnull', 'div' => false));?>
	</div>
	
	<div class="input text responsabile">
	<?=$this->Form->input('Responsabile',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),
	'div' => false
	));?>
	</div>
	
	<div class="input text note">
	<?=$this->Form->input('Note', array('divi' => false));?>
	</div>
	
	<div class="clear"></div>
	
	<div class="box-annuario-atleti">
	<?=$this->Form->input('Tessera');?>
	
	
	<?=$this->Form->input('DataVidimazione',array('label' => 'Data di Vidimazione','type' => 'text','class' => 'datePicker'));?>	
	
	<?
	$options1 = array();
	$options1[''] = '';
	foreach($TipiAssicurazione as $TipoAssicurazione) {
	  $options1[$TipoAssicurazione['TipiAssicurazione']['Descrizione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
	 }
	?>

	<?=$this->Form->input('Tipo', array('type'=>'select', 'options' => $options1));?>
	</div>
	<div class="clear"></div>	
	
	
		
	<?=$this->Form->end();?>
