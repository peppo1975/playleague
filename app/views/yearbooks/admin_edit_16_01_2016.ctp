<script type="text/javascript">
if (typeof $ != "undefined") {
$("#genera").click(function() {
$.get("/admin/users/generatepwd",function(ret) {
	$("#YearbookSignupCode").val(ret.pwd);
	},'json');
 });
 }
</script>	

<?=$this->element("/backend/add_edit_scripts");?>

	<?=$this->element("/backend/edit_scripts");?>
	

	<?=$this->Form->create('Yearbook', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica annuario: <span><?=$this->data['Athlete']['Anagrafica'] . ' - ' . $this->data['Yearbook']['Tessera'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<input type="hidden" name="modded" value="false" />
	
	<?=$this->Form->input('Athlete.Anagrafica', array('type' => 'hidden'));?>
	
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
	
	<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'options' => $options));?>
	
	<div class="clear"></div>
	
		<!-- stampa etichetta -->
	
		<ul class="tab-menu">
			<li>
				<a rel="timmytip" href="/admin/prints/yearLabel/<?=$this->data['Yearbook']['Annuario'];?>" title="Stampa etichetta">
					<img src="/img/timmyshare/icon_print.png">
				</a>
			</li>
		</ul>
		
		<!-- fine stampa etichetta -->
	
	<?=$this->Form->input('Tessera',array('readonly' => 'readonly'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('DataVidimazione',array('label' => 'Data di Vidimazione','type' => 'text','class' => 'datePicker'));?>	
	
	<?=$this->Form->input('SquadraCampionato', array('type' => 'hidden'));?>

	
	<div class="clear"></div>
	
	<?=$this->Form->input('TipoAssicurazione', array('type' => 'hidden'));?>
	
	<div class="clear"></div>
	
	<!-- Campi nascosti con id -->
	<?=$this->Form->input('NomeSquadraCampionato',array('label' => 'Squadra/Campionato','class' => 'autoComplete','data-url' => '/admin/yearbooks/searchSquadraCampionato','data-dest' => 'YearbookSquadraCampionato'));?>
	<?=$this->Form->input('AtletaSearch',array('label' => 'Atleta', 'data-id' => $this->data['Yearbook']['Atleta'], 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'YearbookAtleta'));?>
	<?=$this->Form->input('Atleta',array('type' => 'hidden'));?>
	<?=$this->Form->input('Responsabile',
	array(
	
	'type' => 'radio',
	'options' => array( 'Si'=>'Si', 'No'=>'No' ),

	));?>	
	<?=$this->Form->input('isAdmin',
	array(
	
	'legend'=> 'Amministratore squadra',
	'type' => 'radio',
	'options' => array( '1'=>'Si', '0'=>'No' ),

	));?>	
	<?
	$options1 = array();
	foreach($TipiAssicurazione as $TipoAssicurazione) {
	  $options1[$TipoAssicurazione['TipiAssicurazione']['TipoAssicurazione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
	 }
	?>
	
	<?=$this->Form->input('signup_code',array('label' => 'Codice controllo','type' => 'text'));?>
	
	<div class="input">
	<label>&nbsp;</label>
	<?=$this->Form->submit('Genera codice',array('type' => 'button','div' => false,'id' => 'genera'));?>
	</div>		
	
	<div class="clear"></div>

	<?=$this->Form->input('TipoAssicurazione', array('type'=>'select', 'options' => $options1, 'empty' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Note');?>

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
	
	<?=$this->Form->input('NumeroMaglia', array('type'=>'text', 'label' => 'Numero maglia'));?>
	
	<?=$this->Form->input('Ruolo', array('type' => 'select', 'label' => 'Ruolo', 'empty' => true, 'options' => $ruoli));?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
		$(function(){
		
			var value = $("#YearbookGiovanili").val();
			
			if(value == 'Si') {
			
				$("#checkGiovanili").attr('checked', true);
			
			} else if(value == 'No') {
			
				$("#checkGiovanili").attr('checked', false);
			
			}
		
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
		<input type="checkbox" id="checkGiovanili" />
		<?=$this->Form->input('Giovanili', array('type'=>'hidden'));?>
	</div>	
		
	<?=$this->Form->end();?>
	
