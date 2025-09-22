<div class="tab-container">

<ul class="tab-selector">
		
			<li class="selected" data-index="1"><a href="javascript:;">Responsabili</a></li>
			<li data-index="2"><a href="javascript:;">Etichette</a></li>
			
</ul>

<div class="tab-page tab-selected" data-index="1">

<script type="text/javascript">
if (typeof $ != "undefined") {
$(function() {
	
	$(".printButton").click(function() {
		
		var data = $("#PrintAdminResponsibleIndexForm").serialize();	
		
		$.post('/admin/prints/responsible/', data,function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
	});
			
});
}
</script>

	<?=$this->Form->create('Print');?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->input('AnnoSportivo',array('type' => 'select', 'options' => $AnniSportivi, 'div' => false));?>

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
	
	<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'div' => true,'label' => ''));?>
			
	<?=$this->Form->end();?>
	
</div>

<div class="tab-page" data-index="2">

					<?=$this->Form->create('labelFullYear');?>
					
					<script type="text/javascript">
					if (typeof $ != "undefined") {
						var match_id = new Array;
					
						$(function() {
							
							$(".index-select-checkbox:checked").each(function() {
								
								match_id.push($(this).val());
								
							});
							
							$(".print-number").text(match_id.length);
									
							if (match_id.length == 0) {
								
								$(".labelFullYearButton").attr('disabled','disabled');
								
							}
							
							$("#labelFullYearAdminResponsibleIndexForm").submit(function(e) {
								
								e.preventDefault();
								
								$.post("/admin/prints/label_full_year_go",{ "labels": match_id },function(ret) {
									
										location.href = ret.link;
									
								},'json');
							
								return false;
								
							}); 
									
							
									
						});
					}
					</script>
					
					<div class="input">
					<label><span class="print-number"></span> etichette verranno stampate</label>
					<?=$this->Form->button('Stampa', array('type' => 'submit', 'class' => 'labelFullYearButton', 'div' => false));?>
					</div>
					
					<?=$this->Form->end();?>

</div>

</div>