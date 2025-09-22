
	<?=$this->Form->create('Campi', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca tabella anni sportivi</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('Campi.Descrizione', array('type' => 'text', 'label' => 'Nome campo'));?>
	
			<script type="text/javascript">
			if (typeof $ != "undefined") {
			$(function(){
			
				$("#checkMidland").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIsMidland").val(1);
					} else {
						$("#CampiIsMidland").val('');
					}
				});
				$("#check5").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIs5").val(1);
					} else {
						$("#CampiIs5").val('');
					}
				});
				$("#check7").change(function(){
					if($(this).is(':checked')) {
						$("#CampiIs7").val(1);
					} else {
						$("#CampiIs7").val('');
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
			
			<?=$this->Form->input('isMidland', array('type' => 'hidden'));?>
			<?=$this->Form->input('is5', array('type' => 'hidden'));?>
			<?=$this->Form->input('is7', array('type' => 'hidden'));?>		
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Campi.NominativoGestore', array('type' => 'text', 'label' => 'Gestore'));?>
	
	<?=$this->Form->end();?>