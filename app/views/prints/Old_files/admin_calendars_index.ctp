<?=$this->element("/backend/add_edit_scripts");?>
<script type="text/javascript">

$(function() {
	
	$("#PrintCampionato").live('change', function() {
	
		if($(this).val() == 'default') {
		
			$('.gironi').hide();
			$('.giornate').hide();
			
			return false;
		
		}
				
		$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
	
				if (ret != undefined && ret.length > 0) {
					
					$(".gironi div.input .checkbox").remove();
					
					for (var i = 0; i < ret.length; i++) {
				
						$(".gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
				
					}
					
					$(".gironi").show();
					
				}
	
			
		},'json');

	});
	
	$(".printButton").click(function() {
		
		var data = $("#PrintAdminCalendarsIndexForm").serialize();		
		
		$.post('/admin/prints/calendars/'+$("#PrintCampionato").val(),data,function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
	});
	
	$(".checkGironi").live('change',function() {
		
		if ($(".checkGironi:checked").length > 0) $(".printButton").removeAttr('disabled');
		else $(".printButton").attr('disabled','disabled');
		
	});
	
	// $(".checkGironi").live('change',function() {
		
		// if ($(".checkGironi:checked").length > 1) {
			
			// $(".tip_stampa").show();
			
		// } else {
			
			// $(".tip_stampa").hide();
			
		// }
		
	// });
		
	
});

</script>

	<?=$this->Form->create('Print');?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Campionato', array('type' => 'select', 'label' => 'Campionato', 'options' => $campionati, 'div' => false));?>

	<div class="clear"></div>
	
	<div class="gironi" style="display: none;">

	<?=$this->Form->input('Girone', array(
    'type' => 'select',
    'label' => 'Gironi',
    'multiple' => 'checkbox',
    'options' => array(
    )
    ));?>
	
	</div>

	<div class="clear"></div>

	<div class="tip_stampa" style="display: none;">

	<?/*=$this->Form->input('Stampa', array(
    'type' => 'radio',
    'label' => 'Modalità di stampa',

    'options' => array(
    
		'1' => '1 girone per pagina',
		'2' => '2 gironi per pagina',
    
    ),
    'value' => '1'
    ));*/?>
	
	</div>

	<div class="clear"></div>

	<div class="tip_export">

	<?=$this->Form->input('Export', array(
    'type' => 'radio',
    'label' => 'Modalità di esportazione',

    'options' => array(
    
		'pdf' => 'PDF',
		'xls' => 'XLS',
    
    ),
    'value' => 'pdf'
    ));?>
	
	</div>


	<div class="clear"></div>
			
	<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'disabled' => 'disabled','div' => true,'label' => ''));?>
			
	<?=$this->Form->end();?>
