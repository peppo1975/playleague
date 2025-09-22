<div class="tab-container">

	<ul class="tab-selector">
	
		<li data-index="1" class="selected"><a href="javascript:;">Invio</a></li>
		<li data-index="2"><a href="javascript:;">Lista</a></li>
		
	</ul>
	
<div class="tab-page tab-selected" data-index="1">

<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	$("#SendMailSmsAdminSendMailSmsForm").submit(function(){
	
		var data = $(this).serialize();
		var exit = 0;
		$('.count').remove();
		
		$('.email-sms').find('.required:not(:hidden)').each(function(){
		
			if($(this).val() == '') {
			
			exit = 1;			
			$(this).parent('div').append($('<div>').addClass('error-message').addClass('count').text('Campo obbligatorio.'));
			
			}
		
		});
		
		if(exit == 1) return false;
		
		if (confirm("Sicuri di procedere con l'invio?\n*Controllare la lista contatti prima di procedere")) {
		
		$.post('/admin/athletes/sendMailSms_go', data, function(ret){
		
			alert(ret.send + " sono stati aggiunti allo spooler, Visualizza la tabella spooler per controllare lo stato di invio");
			
			//var dont = $('<div>').addClass('count').text('Email non inviate: '+ret.dontSend);
		
			timmy_close();
		
		
		},'json');
		
		}
		return false;
	
	});
	
	$('input[name="data[SendMailSms][SendOption]"]').change(function(){
	
	var object = $("#SendMailSmsObject").parent('div');
	var text   = $("#SendMailSmsText").parent('div');
	
	if($(this).val() == 'sms') {
		for(name in CKEDITOR.instances)
		{
		    CKEDITOR.instances[name].destroy()
		}
		object.hide();
		text.find('label').remove();
		text.prepend('<label for="SendMailSmsText">Testo (<span class="count_char">160</span> rimanenti)</label>');
		$("#SendMailSmsText").attr('maxlength', 160);
		$("#SendMailSmsText").val('');
	
	} else {
	
		$('#SendMailSmsText').ckeditor(config);
		object.show();
		text.find('label').remove();
		text.prepend('<label for="SendMailSmsText">Testo</label>');
		$("#SendMailSmsText").removeAttr('maxlength');	
		$("#SendMailSmsText").val('');		
	
	}
	
	});
	
	$("#SendMailSmsText").keyup(function(e){
	
		var length = $(this).val().length;
		var total  = parseInt($('.count_char').text());
		var diff   = 160 - length;
		
		$('.count_char').text(diff);
		
	
	});


    var config = {
        toolbar:
            [
                ['Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-'],
                ['Find', 'Replace', 'SelectAll', 'RemoveFormat'],
                ['Bold', 'Italic', 'Underline', 'Strike'],
                ['Link', 'Unlink', 'Anchor'],
                ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote']
            ]
    };

    $('#SendMailSmsText').ckeditor(config);

});
}
</script>

<div class="email-sms">

<?=$this->Form->create('SendMailSms');?>

<?=$this->Form->input('SendOption', array(
'type' => 'radio',
'legend' => 'Modalità di invio',

'options' => array(

	'email' => 'Email',
	'sms' => 'Sms',

),
'value' => 'email'
));?>

<div class="clear"></div>

<?=$this->Form->input('object', array('label' => 'Oggetto', 'type' => 'text', 'class' => 'required'));?>

<div class="clear"></div>

<?=$this->Form->input('text', array('label' => 'Testo', 'class' => 'required', 'type' => 'textarea'));?>

<div class="clear"></div>

<?=$this->Form->end('Invia');?>

</div>

</div>

<div class="tab-page tab-overflow" data-index="2">

	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		$('.tab-container').delegate('.deleteRecord','click', function(){
		
			var tr = $(this).parents('tr');
			var id = tr.attr('data-id');
			
			if(confirm('Sei sicuro di voler eliminare?')) {
			
				$.get('/admin/athletes/deleteSmsEmail/'+id, function(data){
				
					if(data.delete == 1) {
					
						tr.remove();
					
					}
					
					if($("#listTable").find('tr').length < 2) {
					
						alert('La lista degli atleti è vuota.');
						$("#timmybox").find('input.close').trigger('click');
					
					}					
				
				},'json');
			
			}
		
		});
		
		$('.tab-container').delegate('.select-all','click', function(){
		
			$("#listTable").find('input[type=checkbox]').each(function(){
			
				$(this).attr('checked', true);
			
			});
		
		});
		
		$('.tab-container').delegate('.revert-selection','click', function(){
		
			$("#listTable").find('input[type=checkbox]').each(function(){
			
				if($(this).is(':checked')) {
				
					$(this).attr('checked', false);
				
				} else {
				
					$(this).attr('checked', true);
				
				}
			
			});
		
		});
		
		$('.tab-container').delegate('.delete-selected','click', function(){
		
			var athlete_arr = new Array();
			
			$("#listTable").find('input[type=checkbox]').each(function(){
			
				if($(this).is(':checked')) {
					
					athlete_arr.push($(this).val());
				
				}
			
			});
			
			if(athlete_arr.length > 0) {
			
				if(confirm(athlete_arr.length + ' verranno eliminati. Procedere?')) {
			
					$.post('/admin/athletes/deleteSmsEmailAll', {"athletes":athlete_arr}, function(data){
					
						if(data.delete == 1) {
						
							for(var i in athlete_arr) {
							
								$("#check-"+athlete_arr[i]).parents('tr[data-id='+athlete_arr[i]+']').remove();
							
							}
							
							if($("#listTable").find('tr').length < 2) {
							
								alert('La lista degli atleti è vuota.');
								timmy_close();
							
							}
						
						}
					
					},'json');
				
				}
			
			} else {
			
				alert('Nessun atleta selezionato.');
			
			}
		
		});
		
	
	});
	}
	</script>
	<ul class="table_operations">					
		<li class="select-all"><a title="seleziona tutti" href="javascript:;">seleziona tutti</a></li>
		<li class="revert-selection"><a title="inverti selezione" href="javascript:;">inverti selezione</a></li>
		<li class="delete-selected"><a title="cancella selezionati" href="javascript:;">cancella selezionati</a></li>
	</ul>
	<table id="listTable" class="form_table form_table_full">
	<tr>
		<th>Seleziona</th>
		<th>Nominativo</th>
		<th>Squadra</th>
		<th>Email</th>
		<th>Sms</th>
		<th>Opzioni</th>
	</tr>
	<?foreach($athletes as $athlete):?>
	<tr data-id="<?=$athlete['Athlete']['Atleta'];?>">
		<td><input type="checkbox" value="<?=$athlete['Athlete']['Atleta'];?>" class="athleteSelect" id="check-<?=$athlete['Athlete']['Atleta'];?>" /></td>
		<td><?=$athlete['Athlete']['reverseAnagrafica'];?></td>
		<td><?=$athlete['Athlete']['NomeSquadra'];?></td>
		<td><?=$athlete['Athlete']['Email'];?></td>
		<td><?=$athlete['Athlete']['Cellulare'];?></td>
		<td>
			<a href="javascript:;" title="Elimina" class="deleteRecord">
				<img src="/img/timmyshare/icon_delete.png" />
			</a>
		</td>
	</tr>
	<?endforeach;?>
	</table>

</div>

</div>
