	<script type="text/javascript">
	if (typeof $ != "undefined") {
		$(function() {
			
			$("#MatchCampionato").live('change',function() {
				
				if ($.trim($(this).val()) == '') $(".refreshSubmit").attr('disabled','disabled');
				else $(".refreshSubmit").removeAttr('disabled');
				
			});
			
			$("#MatchRefreshForm").submit(function(e) {
				
				e.preventDefault();
				
				$.post('/admin/matches/refresh',$(this).serialize(),function(data) {
					
						if (data.result == true) {
							
							alert('Generazione calendario completata');
							location.reload();
						
						} else {
							
							alert("Errore nella generazione");
							
						}
						
				},'json');
				
				return false;
				
			});
			
		});
	}
	</script>

	<?=$this->Form->create('Match', array('action' => 'refresh','prefix' => 'admin','class' => 'refreshSubmitForm'));?>

	<div class="form_header">

		<h2>Generazione calendario</h2>
		
	</div>
	

	<?=$this->Form->input('Campionato', array('type' => 'select', 'label' => 'Campionato', 'options' => $campionati));?>
	
	<div class="clear"></div>
	<? if ($layout != 'tablet'): ?>
	<?=$this->Form->input('Tipologia',
	array(
	
	'type' => 'radio',
	'options' => array( 'A'=>'Solo andata', 'AR'=>'Andata e ritorno' ),
	'label' => 'Tipologia gironi',
	'value' => 'AR'

	));?>
	<? else: ?>
	<? $x = $this->Form->input('Tipologia',
	array(
	
	'type' => 'radio',
	'options' => array( 'A'=>'Solo andata', 'AR'=>'Andata e ritorno' ),
	'label' => 'Tipologia gironi',
	'value' => 'AR'

	));
	
	
	$x = str_replace('fieldset','fieldset style="width: 195px;"',$x);
	echo $x;
	?>
	
		
	<? endif;?>
	
	<div class="clear"></div>
	
	
	<div class="input">
	<label>&nbsp;</label>
	<?=$this->Form->submit('crea',array('type' => 'submit','div' => false,'disabled' => 'disabled','class' => 'refreshSubmit'));?>
	</div>
	<div class="clear"></div>
		
	<?=$this->Form->end();?>
