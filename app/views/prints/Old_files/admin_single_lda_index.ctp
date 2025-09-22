<script type="text/javascript">

$(function() {
	
	$(".printButton").click(function() {
		
		var data = $("#PrintAdminSingleLdaIndexForm").serialize();	
		
		$.post('/admin/prints/single_lda/', data,function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
	});
	
	$("input").live('change',function() {
		
		var vuoto = 1;
		
		$("#PrintAdminSingleLdaIndexForm").children("input").each(function(index){
		
			if($(this).val() == '') vuoto = 0;
		
		});
		
		if(vuoto == 0) $('.printButton').attr('disabled', 'disabled');
		else $('.printButton').attr('disabled', false);
		
	});
			
});

</script>

	<?=$this->Form->create('Print');?>
	
	<div class="clear"></div>	
		
	<?=$this->Form->input('DataIn', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker', 'div' => false));?>
	<?=$this->Form->input('DataOut', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker', 'div' => false));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('NomeAtleta',array('label' => 'Arbitro','class' => 'autoComplete','data-url' => '/admin/matches/searchArbitro','data-dest' => 'PrintAtleta'));?>
	<?=$this->Form->input('Atleta', array('type' => 'hidden'));?> 

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
	
	<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'disabled' => true, 'div' => true,'label' => ''));?>
			
	<?=$this->Form->end();?>