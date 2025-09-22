<script type="text/javascript">
						var newsletter_id = new Array;
					
						$(function() {
							
							$(".index-select-checkbox:checked").each(function() {
								
								newsletter_id.push($(this).val());
								
							});
							
						if(newsletter_id.length == 0) {
						
							alert('Nessuna newsletter selezionata.');
							
							t = setTimeout(function(){
							
								$("#timmy_close").click();	
								
							},'300');	
							
						}

						$('.GroupClass').live('change', function(){
							
							if($('.GroupClass:checked').length > 0) $('.sendButton').attr('disabled', false);
							else									$('.sendButton').attr('disabled', true);
							
						});
						
						$("#SendForm").submit(function(){
							
							$('.sendButton').hide();
							
							var groups_id = new Array;
							
							$('.GroupClass:checked').each(function(){
								groups_id.push($(this).val());
							});
							
							$.post('/admin/newsletters/send_message', { "newsletters" : newsletter_id, "groups" : groups_id }, function(data){
								
								alert(data.msg);
								
								$('.sendButton').show();
								
							},'json');
							
							return false;
							
						});
						
						});
						
</script>

<? //debug($groups); ?>

<? if(count($groups) > 0): ?>

<? asort($groups); ?>

<?=$this->Form->create('Send', array('id' => 'SendForm'));?>

<ul class="list-groups">

<? foreach($groups as $id => $group): ?>

<li>
<?=$this->Form->checkbox('Group_' . $id, array('div' => false, 'value' => $id, 'class' => 'GroupClass'));?>
<label style="display: inline;" for="<?=$id;?>"><?=$group;?></label>
</li>

<? endforeach; ?>

</ul>

<?=$this->Form->button('invia', array('type' => 'submit', 'disabled' => true, 'class' => 'sendButton', 'style' => 'margin-top: 10px'));?>

<?=$this->Form->end();?>

<? else: ?>

<div class="error-message">Non ci sono gruppi a cui inviare il messaggio.</div>

<? endif; ?>