<?=$this->element("/backend/add_edit_scripts");?>
<script type="text/javascript">
$("#PrintCampionato").live('change', function() {

	var Campionato = $("#PrintCampionato").val();
	
	$.get('/admin/prints/getDay/' + Campionato, function(ret) {
	
		if(ret.find != '') {
	
			var i = 1;
			var n = parseInt(ret.find);
			
			$('.giornate').html('');
	
			while(i <= n) {
			
				$(
				'<span>' + i + '</span>' +
				'<input type="checkbox" name="PrintGiornata_' + i + '" id="PrintGiornata_' + i + '" value="' + i + '" />'
				).appendTo('.giornate');
				
				i++;
			
			}
			
			$.get('/admin/prints/getHalf/' + Campionato, function(ret2) {
			
				var arr_l = ret2.length;
				var j = 0;
				
				$('.gironi').html('');
				$('.options').html('');
				
				while(j < 2) {
				
					$(
					'<span>' + ret2[j].Half.Descrizione + '</span>' +
					'<input type="checkbox" name="' + ret2[j].Half.GironeCampionato + '" id="' + ret2[j].Half.GironeCampionato + '" value="' + ret2[j].Half.GironeCampionato + '" />'
					).appendTo('.gironi');
					
					j++;
				
				}
				
				$(
				
				'<span>PDF</span>' +
				'<input type="radio" id="options" name="options" value="pdf" />' +
				'<span>XLS</span>' +
				'<input type="radio" id="options" name="options" value="xls" />'
				
				).appendTo('.options');
			
				
			
			}, 'json');
		
		}
	
	}, 'json');

 });
 
 $('.printButton').live('click', function() {
 
	var arr_giornate = new Array();
	var Campionato = $("#PrintCampionato").val();
		
	$('.error_giornate').html('');
	$('.error_gironi').html('');
	$('.error_campionato').html('');
	
	if(Campionato == '') { $('.error_campionato').html('Campionato obbligatorio.');  return false; }
 
	$('.giornate :checkbox').each(function(index) {
		
		if($(this).attr('checked')) arr_giornate[index] = $(this).val();
	
	});
	
	var arr_gironi = new Array();
	
	$('.gironi :checkbox').each(function(index) {
		
		if($(this).attr('checked')) arr_gironi[index] = $(this).val();
	
	});
	
	var error = 0;

	if(arr_giornate == '') { $('.error_giornate').html('Inserire almeno una giornata.'); error = 1; }
		if(arr_gironi == '') { $('.error_gironi').html('Inserire almeno un girone.'); error = 1; }
			//if($("#options").val() == '') { $('.error_options').html('Inserire almeno un opzione.'); error = 1; }
		
	if(error == 1) return false;
	 
	$.post('/admin/prints/bullettins/' + Campionato, { Gironi: arr_gironi, Giornate: arr_giornate }, function(ret) {
	
		
	
	}, 'json');
	
	location.href = '/admin/prints/pdf';
	
 });
 
 $("#options").live('click', function() {
 
	if($(this).val() == 'pdf') {
	
		$('.options_pdf').remove();
		$('.options_xls').remove();
		$(
			'<div class="options_pdf">' +
				'<span>1 Girone</span>' +
				'<input id="options_pdf" type="radio" name="pdf_gironi" value="1"/>' +
				'<span>2 Gironi</span>' +
				'<input id="options_pdf" type="radio" name="pdf_gironi" value="2"/>' +
			'</div>'
		
		).appendTo('.options');
		
	
	}
	
	if($(this).val() == 'xls') {
	
		$('.options_pdf').remove();
		$('.options_xls').remove();
		$('<div class="options_xls">opzioni xls</div>').appendTo('.options');
		
	
	}
 
 });
 
 $("#options_pdf").live('click', function() {
 
	var options_pdf = $(this).val();

	$.get('/admin/prints/setOptions/' + options_pdf, function(ret) {});
 
 });


</script>

<?$this->Session->delete('gare');?>
<?$this->Session->delete('gare_prossima_giornata');?>
<?$this->Session->delete('gare_prossima_giornata2');?>
<?$this->Session->delete('classifica_marcatori');?>
<?$this->Session->delete('diffidati');?>
<?$this->Session->delete('espulsi');?>
<?$this->Session->delete('classifiche');?>
<?$this->Session->delete('options_pdf');?>
<?$this->Session->delete('gironi');?>
<?$this->Session->delete('giornate');?>

	<?=$this->Form->create('Print');?>
	
	<div class="clear"></div>	
	
	<div class="campionato">
		<?=$this->Form->input('CampionatoSearch',array('label' => 'Campionato', 'div' => false, 'class' => 'autoComplete big', 'data-url' => '/admin/campionatis/searchCampionato','data-dest' => 'PrintCampionato'));?>
		<?=$this->Form->input('Campionato',array('type' => 'hidden', 'div' => false));?>
	</div>
	<div class="error_campionato"></div>
		
	<div class="clear"></div>
	
		<div class="giornate"></div>
		<div class="error_giornate"></div>
	
	<div class="clear"></div>
	
		<div class="gironi"></div>
		<div class="error_gironi"></div>
		
	<div class="clear"></div>
	
		<div class="options"></div>
		<div class="error_options"></div>
					
		<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'div' => false));?>
			
	<?=$this->Form->end();?>
