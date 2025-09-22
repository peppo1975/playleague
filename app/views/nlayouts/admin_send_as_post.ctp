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

						$('.GroupClass,#SendTitle').live('change', function(){
							
							if($('.GroupClass:checked').length > 0 && $("#SendTitle").val() != '') $('.sendButton[data-attr="invia"]').attr('disabled', false);
							else									$('.sendButton[data-attr="invia"]').attr('disabled', true);
							
						});
						
						$("#SendTitle").live('change', function(){
							
							if($(this).val() != '')  $('.sendButton[data-attr="salva"]').attr('disabled', false);	
							else $('.sendButton[data-attr="salva"]').attr('disabled', true);								
							
						});
						
						$('.sendButton').one('click', function(e){
							
							e.preventDefault();
							e.stopPropagation();
							
							if($(this).attr('data-attr') == 'salva') {
								
								$("#SendForm").trigger('submit', ['save']);
								
							} else {
								
								$("#SendForm").trigger('submit', ['send']);
								
							}
							
						});
						
						$("#SendForm").submit(function(e, attr){
							
							var groups_id = new Array;
							
							$('.GroupClass:checked').each(function(){
								groups_id.push($(this).val());
							});
							
							$.post('/admin/newsletters/send_message_as_post/' + attr, { "newsletters" : newsletter_id, "groups" : groups_id, "title":$("#SendTitle").val(), "layout":$("#SendLayout").val() }, function(data){
								
								alert(data.msg);
								
								timmy_close();
								
							},'json');
							
							return false;
							
						});
						
						});
						
</script>

<? //debug($groups); ?>

<? if(count($groups) > 0): ?>

<?=$this->Form->create('Send', array('id' => 'SendForm'));?>

<h3>Info newsletter</h3>

<?=$this->Form->input('title', array('type' => 'text', 'label' => 'Titolo', 'class' => 'big', 'div' => array('class' => 'input required'))); ?>

<?=$this->Form->input('layout', array('type' => 'select', 'label' => 'Layout', 'options' => $layouts)); ?>

<div class="clear"></div>

<h3>Gruppi</h3>

<ul class="list-groups">

<? foreach($groups as $id => $group): ?>

<li>
<?=$this->Form->checkbox('Group_' . $id, array('div' => false, 'value' => $id, 'class' => 'GroupClass'));?>
<label for="<?=$id;?>"><?=$group;?></label>
</li>

<? endforeach; ?>

</ul>

<?=$this->Form->button('invia', array('type' => 'submit', 'data-attr' => 'invia', 'disabled' => true, 'class' => 'sendButton'));?>

<?=$this->Form->button('salva', array('type' => 'submit', 'data-attr' => 'salva', 'disabled' => true, 'class' => 'sendButton'));?>

<?=$this->Form->end();?>

<? else: ?>

<div class="error-message">Non ci sono gruppi a cui inviare il messaggio.</div>

<? endif; ?>