<script type="text/javascript">
if (typeof $ != "undefined") {
$(function() {
	
	$(".printButton").click(function() {
		
		var data = $("#RankingAdminRankIndexForm").serialize();	
		
		$.post('/admin/prints/rank/', data,function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
	});
			
});
}
</script>
	<?=$this->Form->create('Ranking');?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->input('Campionato', array('type' => 'text', 'label' => 'Campionato', 'class' => 'big', 'div' => false, 'value' => $campionato, 'readonly' => true));?>
	<?=$this->Form->input('Campionato_id', array('type' => 'hidden', 'value' => $campionato_id));?>
	
	<div class="clear"></div>	
	
	<?=$this->Form->input('Girone', array('type' => 'text', 'label' => 'Girone', 'class' => 'big', 'div' => false, 'value' => $girone, 'readonly' => true));?>
	
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
	
	<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'disabled' => false, 'div' => true,'label' => ''));?>
			
	<?=$this->Form->end();?>