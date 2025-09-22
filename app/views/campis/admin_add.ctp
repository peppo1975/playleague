
<?=$this->Form->create('Campi', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

<div class="form_header">
	
	<h2>Aggiungi nuovo campo</h2>
	<ul>
		
		<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
		<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
		<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
	</ul>
	<div class="clear"></div>
	
</div><!-- close form_header -->

<div class="clear"></div>

<div class="tab-page tab-selected" data-index="1">	
	
	<?=$this->Form->input('Descrizione', array('label' => 'Nome campo', 'type' => 'text'));?>
	
	<script type="text/javascript">
		if (typeof $ != "undefined") {
			$(function(){
				
				$("#checkMidland").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIsMidland").val(1);
						} else {
						$("#CampiIsMidland").val(0);
					}
				});
				$("#check5").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIs5").val(1);
						} else {
						$("#CampiIs5").val(0);
					}
				});
				$("#check7").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIs7").val(1);
						} else {
						$("#CampiIs7").val(0);
					}
				});		
				
				$("#check11").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIs11").val(1);
						} else {
						$("#CampiIs11").val(0);
					}
				});	
				
				//GIUSEPPE 2016-12-13
				$("#checkTennis").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIsTennis").val(1);
						} else {
						$("#CampiIsTennis").val(0);
					}
				});	
				
				$("#checkEsclusive").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIsEsclusive").val(1);
						} else {
						$("#CampiIsEsclusive").val(0);
					}
				});					
				
			});
		}
	</script>			
	
	<div class="input">
		<label for="checkMidland">Campo midland</label>
		<input type="checkbox" id="checkMidland" />
	</div>
	<div class="input">
		<label for="check5">Campo a 5</label>
		<input type="checkbox" id="check5" />
	</div>
	<div class="input">
		<label for="check7">Campo a 7</label>
		<input type="checkbox" id="check7" />
	</div>	
	
	<div class="input">
		<label for="check11">Campo a 11</label>
		<input type="checkbox" id="check11" />
	</div>	
	
	
	<!-- //GIUSEPPE 2016-12-13-->
	<div class="input">
		<label for="checkTennis">Campo Tennis</label>
		<input type="checkbox" id="checkTennis" />
	</div>	
	
	<div class="input">
		<label for="checkEsclusive">In esclusiva</label>
		<input type="checkbox" id="checkEsclusive" />
	</div>					
	
	<?=$this->Form->input('isMidland', array('type' => 'hidden', 'value' => 0));?>
	<?=$this->Form->input('is5', array('type' => 'hidden', 'value' => 0));?>
	<?=$this->Form->input('is7', array('type' => 'hidden', 'value' => 0));?>	
	<?=$this->Form->input('is11', array('type' => 'hidden', 'value' => 0));?>			
	
	<?=$this->Form->input('isTennis', array('type' => 'hidden', 'value' => 0));//GIUSEPPE 2016-12-13?>	
	
	<?=$this->Form->input('isEsclusive', array('type' => 'hidden', 'value' => 0));?>			
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Importo', array('label' => 'Importo', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<div class="post_content">
		
		<?=$this->element('/backend/ckeditor', array('name' => 'descrizione_campo', 'title' => 'Descrizione campo'));?>
		
	</div>
	
	<div class="clear"></div>	
	
	<?//=$this->Form->input('Importo', array('label' => 'Importo quota', 'type' => 'text'));?>
	
	<?=$this->Form->input('Indirizzo', array('label' => 'Indirizzo', 'type' => 'text'));?>
	<?=$this->Form->input('Citta', array('label' => 'Città', 'type' => 'text'));?>
	<?=$this->Form->input('Provincia', array('label' => 'Provincia', 'type' => 'text'));?>
	<?=$this->Form->input('Telefono', array('label' => 'Telefono', 'type' => 'text'));?>
	<?=$this->Form->input('Email', array('label' => 'Email', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<h3>Google map</h3>
	
	<?=$this->Form->input('latitudine', array('label' => 'Latitudine', 'type' => 'text'));?>
	<?=$this->Form->input('longitudine', array('label' => 'Longitudine', 'type' => 'text'));?>
	
	<? if($group_id == 5): ?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('google_link', array('label' => 'Google link', 'type' => 'text', 'class' => 'big'));?>
	
	<? endif; ?>
	
	<div class="clear"></div>
	
	<h3>Gestore campo</h3>
	
	<?=$this->Form->input('CognomeGestore', array('label' => 'Cognome', 'type' => 'text'));?>
	
	<?=$this->Form->input('NomeGestore', array('label' => 'Nome', 'type' => 'text'));?>
	
	<?=$this->Form->input('EmailGestore', array('label' => 'Email', 'type' => 'text'));?>
	
	<?=$this->Form->input('CellulareGestore', array('label' => 'Cellulare', 'type' => 'text'));?>
	
	<div class="clear"></div>
	
	<h3>Allegati</h3>
	
	<div id="formUploadContainer">
		
		<script type="text/javascript">
			if (typeof $ != "undefined") {
				$(function(){
					var upload = $("#UploadTag");
					var desc   = $("#UploadDescription");
					upload.change(function(){
						if(upload.val() == '') { desc.parent('div').find('label').text('Descrizione'); desc.removeClass('big'); desc.val(''); }
						else 				   { desc.parent('div').find('label').text('Link'); desc.addClass('big'); desc.val('http://'); }
					});
				});
			}
		</script>			
		
		<?=$backend->getFiles('campi_id', 0,array(
			
			'tag' => array('' => 'Allegato','link' => 'Collegamento'),
			
		));?>
		
	</div>			
	
	<?=$this->Form->end();?>
