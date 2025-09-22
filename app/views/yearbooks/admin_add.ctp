<? if($layout == "desktop"): ?>

<?=$this->element("/backend/add_edit_scripts");?>

<? else: ?>

<?=$this->element("/backend/tablet/add_edit_scripts");?>

<? endif; ?>

<script type="text/javascript">
if (typeof $ != "undefined") {
	$(function() {

		function saveSession() {
		
			var data = $("#YearbookAddForm").serialize();
			
			$.post('/admin/yearbooks/saveSession', data, function() {
			
				alert('Sessione salvata con successo.');
			
			});
		
		}	
		
		function resetSession() {
		
			$.post('/admin/yearbooks/resetSession', function(){
			
				alert('Campi azzerati con successo.');
			
			});
		
		}
		
		$.get("/admin/yearbooks/tesseraGen/" + $("#YearbookAnnoSportivo").val(),function(ret) {
			
				$("#YearbookTessera").val(ret.tessera);
				
		
		},'json');
		
		$("#YearbookAnnoSportivo").change(function() {
			

			$.get("/admin/yearbooks/tesseraGen/" + $("#YearbookAnnoSportivo").val(),function(ret) {
				
					$("#YearbookTessera").val(ret.tessera);
					
			
			},'json');
			
				
		});
		
		$('.formAdd').delegate("#YearbookAtleta","change", function(){
			
			if($(this).val() != '') {
				
				var athlete_id = $(this).val();
				var year       = $("#YearbookAnnoSportivo").val();
				
				$.get('/admin/yearbooks/checkYearbook/' + athlete_id + '/' + year, function(data){
					
					if(data.null == 0) {
						for(field in data.return.Yearbook) {
							var me = "#Yearbook" + field;
							$(me).val(data.return.Yearbook[field]);	
						}
					}
					
					$('input,select,textarea').attr('disabled',false);
					
				},'json');

			}
			
		});
		
		$('.formAdd').delegate('#saveSession','click', function(){
		
			saveSession();
		
		});
		
		$('.formAdd').delegate('#resetSession','click', function(){
		
			resetSession();
			$('.add').trigger('click');
		
		});
		
		$("#YearbookAtleta").trigger('change');		
		
	});
}
</script>

	<?=$this->Form->create('Yearbook', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo annuario squadra</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset', 'id' => 'resetSession', 'div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('inserisci',array('type' => 'submit','div' => false));?></li>
									<li><?=$this->Form->submit('salva sessione',array('type' => 'button', 'id' => 'saveSession', 'div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?
	$options = array();
	foreach($AnniSportivi as $AnnoSportivo) {
	  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
	 }
	?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		function getFilter() {
		
			anno 	= $("#YearbookAnnoSportivo");
			squadra = $("#YearbookSquadraCampionatoSearch");
			
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
	
	<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'default'=>'1', 'options' => $options));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('AtletaSearch',array('label' => 'Atleta', 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'YearbookAtleta'));?>
	<?=$this->Form->input('Atleta',array('type' => 'hidden'));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Tessera',array('disabled' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('DataVidimazione',array('label' => 'Data di Vidimazione', 'disabled' => true, 'type' => 'text','class' => 'datePicker', 'value' => date('d/m/Y')));?>	
	
	<div class="clear"></div>
	
	<?=$this->Form->input('SquadraCampionatoSearch',array('label' => 'Squadra/Campionato', 'disabled' => true, 'class' => 'autoComplete','data-url' => '/admin/yearbooks/searchSquadraCampionato','data-dest' => 'YearbookSquadraCampionato'));?>
	<?=$this->Form->input('SquadraCampionato',array('type' => 'hidden'));?>
	
	<?//=$this->Form->input('AtletaSearch',array('label' => 'Atleta','class' => 'autoComplete','data-url' => '/admin/yearbooks/searchAtleta','data-dest' => 'YearbookAtleta'));?>
	
	<?=$this->Form->input('Responsabile',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),
	'value' => 'No',
	'disabled' => true,

	));?>

	<?=$this->Form->input('isAdmin',
	array(
	
	'legend'=> 'Amministratore squadra',
	'type' => 'radio',
	'options' => array( '1'=>'Si', '0'=>'No' ),
	'disabled' => true,

	));?>		
	
	<div class="clear"></div>
	
	<?
	$options1 = array();

	foreach($TipiAssicurazione as $TipoAssicurazione) {
	  $options1[$TipoAssicurazione['TipiAssicurazione']['TipoAssicurazione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
	 }
	?>

	<?=$this->Form->input('TipoAssicurazione', array('type'=>'select', 'empty'=>true, 'options' => $options1, 'disabled' => true,));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Note', array('disabled' => true,));?>

	<div class="clear"></div>

	<h3>Forum</h3>
	
	<?
	
		$ruoli = array(
		
			"POR" => "POR", 
			"CEN" => "CEN", 
			"LAT" => "LAT", 
			"UNI" => "UNI", 
			"PIV" => "PIV", 
			"ALL" => "ALL",
			"DIR" => "DIR",
			"DIF" => "DIF",
			"ATT" => "ATT",
		
		);
	
	?>
	
	<?=$this->Form->input('NumeroMaglia', array('type'=>'text', 'label' => 'Numero maglia', 'disabled' => true,));?>
	
	<?=$this->Form->input('Ruolo', array('type' => 'select', 'label' => 'Ruolo', 'empty' => true, 'options' => $ruoli, 'disabled' => true,));?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
		$(function(){
		
			$("#YearbookGiovanili").val('No');
		
			$("#checkGiovanili").change(function(){
			
				var checked = $(this).is(':checked');
				
				if(checked) {
				
					$("#YearbookGiovanili").val('Si');
				
				} else {
				
					$("#YearbookGiovanili").val('No');
				
				}
			
			});
		
		});
	}
	</script>
	
	<div class="input">
		<label for="checkGiovanili">Giovanili</label>
		<input type="checkbox" id="checkGiovanili" disabled="disabled" />
		<?=$this->Form->input('Giovanili', array('type'=>'hidden'));?>
	</div>
	
		
	<?=$this->Form->end();?>
