	<?=$this->element('/backend/blog_script');?>
	<?=$this->element("/backend/edit_scripts");?>
	
	<div class="tab-container">
	
		<ul class="tab-selector">
		
			<li data-index="1" class="selected"><a href="javascript:;">Pagina</a></li>
			<li data-index="3"><a href="javascript:;">Allegati</a></li>
			<li data-index="5"><a href="javascript:;">Metadata</a></li>
			<li data-index="2"><a href="javascript:;">Blocchi</a></li>
		
		</ul>

	<?=$this->Form->create('Page', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica pagina: <span><?=$this->data['Page']['title'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('id');?>

		<div class="tab-page" data-index="5">
		
			<?=$this->element('/backend/metadata');?>
		
		</div>
	
	
		<div class="tab-page tab-selected" data-index="1">
	
			<?=$this->Form->input('title', array('label' => 'Nome pagina', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('title_mobile', array('label' => 'Nome pagina (solo versione mobile)', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>				
			
			<?=$this->Form->input('alias', array('label' => 'Alias', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>						
			
			<?=$this->Form->input('subtitle', array('label' => 'Sottotitolo', 'class' => 'big', 'type' => 'text'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('id_css', array('type' => 'text', 'label' => 'ID CSS'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('parent_id', array('label' => 'Genitore', 'type' => 'select', 'options' => $tree));?>
			<?=$this->Form->input('published', array('label' => 'Data pubblicazione', 'type' => 'text', 'class' => 'datePicker'));?>
			
			<div class="clear"></div>	
			
			<?=$this->Form->input('type', array('label' => 'Tipo contenuto', 'type' => 'select', 'options' => array('static' => 'Statico', 'dinamic' => 'Dinamico', 'url' => 'Esterno'), 'empty' => 'Scegli tipo contenuto...'));?>
			
			<div class="clear"></div>
			
			<?=$this->element('/backend/page_type');?>
			
			<div class="type-content static-content">
			
				<?=$this->Form->input('block_limit', array('label' => 'Limite blocchi (lasciare 0 per non paginare)', 'type' => 'text'));?>
				
				<div class="clear"></div>			
			
				<?=$this->element('/backend/ckeditor', array('name' => 'content', 'title' => 'Contenuto pagina'));?>
			
			</div>
			
			<div class="type-content dinamic-content">
			
				<?=$this->Form->input('controller', array('type' => 'text', 'label' => 'Controller', 'type' => 'select', 'options' => $controllers, 'empty' => 'Scegli controller...'));?>
				<?=$this->Form->input('action', array('type' => 'text', 'label' => 'Azione', 'type' => 'select', 'empty' => true));?>
				<?=$this->Form->input('params', array('type' => 'text', 'class' => 'big', 'label' => 'Parametri (separati da virgola)'));?>
			
			</div>
			
			<div class="type-content url-content">
			
				<?=$this->Form->input('url', array('type' => 'text', 'label' => 'Link', 'class' => 'big'));?>
			
			</div>
		
		</div>
		
		<div class="tab-page" data-index="3">
		
		<script type="text/javascript">
		if (typeof $ != "undefined") {
		$(function(){
		var upload = $("#UploadTag");
		var desc   = $("#UploadDescription");
			upload.change(function(){
				if(upload.val() == '') { desc.parent('div').find('label').text('Descrizione'); desc.removeClass('big'); desc.val(''); }
				else 				   { desc.parent('div').find('label').text('Link'); desc.addClass('big'); desc.val('http://'); }
			});
		});
		}
		</script>
		
			<div id="formUploadContainer">
		
				<?=$backend->getFiles('page_id', $this->data['Page']['id'], array(
				
					'tag' => array('' => 'Allegato','link' => 'Collegamento'),
				
				));?>
		
			</div>
		
		</div>		
		
		<?=$this->Form->end();?>
		
		<div class="tab-page" data-index="2">
		<script>
		CKEDITOR.config.jqueryOverrideVal = true;
		</script>
		<!-- Important: The JQuery adapter is loaded *after* setting jqueryOverrideVal -->
		<script src="/js/ckeditor/adapters/jquery.js"></script>
		<script type="text/javascript">
		if (typeof $ != "undefined") {
		$("#BlockUrlPageId").find('option:eq(1)').remove();
		
		$(function(){
		
			//Inizializzo Ckeditor con jquery
		
			var config = {
				toolbar:
				[
					['Source', '-', 'Undo','Redo','-', 'Cut','Copy','Paste','PasteText','PasteFromWord','-'],
					['Find','Replace','SelectAll','RemoveFormat'],
					['Bold', 'Italic', 'Underline', 'Strike'],
					['Image', 'Link', 'Unlink', 'Anchor'],
					['NumberedList','BulletedList','Outdent','Indent','Blockquote']
				]
			};   			
		
			$('#BlockContent').ckeditor(config);	
		
			//Ordinamento 
			
			$.get('/orders/getOrder/Block', function(data){
			
				$('#block-order-argument').val(data.last.Order['argument']);
				$('#block-order-type').val(data.last.Order['order_type']);
			
			},'json');
			
			$('.tab-container').delegate("#block-order-argument, #block-order-type","change", function(){
			
				$.get('/orders/setOrder/Block/' + $(this).val());
			
			});
			
			//Sortable table
			
			$(document).ready(function() {
			
				$("#blockTable").sortable({
					items: ".block-row",
					cursor: "pointer",
					axis: "y",
					opacity: "0,6",
					update: function() {
						var model = 'Block';
						var order = $("#blockTable").sortable('toArray');
						$.post('/orders/sortableOrder/' + model, { Data: order });
					}
				});
				
				$("#blockTable").disableSelection();
			
			});			
		
			//Controllo url se link statico o collegamento a pagina cms.
			$("#BlockUrlPageId").change(function(){ 
			
				if($(this).val() != '') $("#BlockUrl").fadeOut('fast');
				else					$("#BlockUrl").fadeIn('fast');
				
			});
			
			//Disable/Enable blocks
			$(".tab-container").delegate('.block-disabled-switch','click', function(){
				var block_id = $(this).parents('tr').attr('data-id');
				var img      = $(this).children('img');
				if(img.attr('src') == '/img/timmyshare/icon_disabled_0.gif') {
					//Disabilita
					$.get('/admin/blocks/changeStatus/' + block_id + '/1', function(data){
						if(data.status != 'error') {
							img.attr('src','/img/timmyshare/icon_disabled_' + data.status + '.gif');
						}
					},'json');
				} else {
					//Abilita
					$.get('/admin/blocks/changeStatus/' + block_id + '/0', function(data){
						if(data.status != 'error') {
							img.attr('src','/img/timmyshare/icon_disabled_' + data.status + '.gif');
						}						
					},'json');					
				}
			});
			
			//Submit del form, aggiunta blocco o modifica
			$("#BlockAdminEditForm").submit(function(){
				
				var data = $(this).serialize();
				$.post('/admin/blocks/ajax_add', data, function(ret){
			
						if(ret.error == 0) {
						
							if($("#BlockId").val() == undefined) { //ADD
							
								var tr = $('<tr>').attr('data-id', ret.data.Block['id']).attr('id',ret.data.Block['id']).addClass('block-row');
								var add_edit = 'aggiunto';
							
							} else {//EDIT
							
								var tr = $('#blockTable').find('tr[data-id='+$("#BlockId").val()+']').empty();
								var add_edit = 'modificato';
							
							}							
						
							var field = ["title","type_it","url","page_url"];
						
							for(i = 0; i < field.length; i++) {
							
								var td = $('<td>').text(ret.data.Block[field[i]]);
								tr.append(td);
							
							}
							
							/*td opzioni*/
							
							var td_option = $('<td>').html(
								'<a class="block-disabled-switch" href="javascript:;">'+
									'<img alt="disabled" src="/img/timmyshare/icon_disabled_' + ret.data.Block['disabled'] + '.gif">'+
								'</a>' +
								'&nbsp;'+
								'<a href="javascript:;" class="blockEdit">' +
									'<img src="/img/timmyshare/icon_edit.png" />' +
								'</a>' +
								'&nbsp;' + 
								'<a href="javascript:;" class="blockDelete">' +
									'<img src="/img/timmyshare/icon_delete.png" />' +
								'</a>'
							);
							tr.append(td_option);
								
							//alert('Orario ' + add_edit + ' con successo.');
							
							tr.insertAfter($("#blockTable").find('tr:first'));
							
							reset();
						
						} else {
						
							for(field in ret.data) {
							
								var error = $('<div>').addClass('error-message').text(ret.data[field]);
								$("#Block"+field).parent('div').append(error);
							
							}
							
						}	
	
				},'json');
				
						return false;				
				
			});
			
			//Delete
			
			$('.tab-container').delegate(".blockDelete","click", function(){
			
				var tr        = $(this).parents('tr');
				var delete_id = tr.attr('data-id');
				
				if(confirm("Sei sicuro di voler eliminare?")) {
				
					$.get('/admin/blocks/ajax_delete/' + delete_id, function(ret){
					
						if(ret.delete == 1) {
						
							alert('Blocco eliminato con successo.');
							tr.remove();
						
						} else {
						
							alert('Impossibile eliminare blocco');
						
						}
					
					},'json');
				
				}
			
			});	

			//Edit Form
			
			$('.tab-container').delegate('.blockEdit','click', function(){
			
				$('.inputShort').remove();
			
				var tr      = $(this).parents('tr');
				var edit_id = tr.attr('data-id');
				
				$.get('/admin/blocks/ajax_edit/' + edit_id, function(data){
				
					var field    = ["Title","Type","Url","UrlPageId","Content"];
					var field_db = ["title","type","url","url_page_id","content"]
				
					for(i = 0; i < field.length; i++) {
					
						$("#Block"+field[i]).val(data.block.Block[field_db[i]]);
					
					}
				
				},'json');
				
				//Input id blocco
				var input_edit = $('<input value="'+edit_id+'">').addClass('hidden')
											 .addClass('bloccoClass')
											 .addClass('editStatus')
											 .addClass('inputShort')
											 .attr('id','BlockId')
											 .attr('name', 'data[Block][id]');
											 
				$("#BlockAdminEditForm").prepend(input_edit);
				
				//Input reset
				var submit = $("#BlockAdminEditForm").find('div.submit');
				var reset  = submit.clone().empty();
					reset.addClass('input').addClass('inputShort').removeClass('submit');
					var input = $('<input type="button" />').attr('id', 'blockReset').addClass('editStatus').val('annulla');
					reset.append(input);
				
				reset.insertAfter(submit);
				
				//Button edit
				
				//$("#orariAdd").val('modifica');
				
			});		

			$('.tab-container').delegate("#blockReset","click", function(){

				reset();
			
			});				
		
			//Reset
			
			function reset() {
			
				$('.editStatus').remove();
				$('#BlockContent').val('');
				$("#BlockAdminEditForm").resetForm();				
			
			}				
		
		});
		}
		</script>
		
		<?=$this->Form->create('Block', array('type' => 'file'));?>
		
			<?=$this->Form->input('Block.title', array('type' => 'text', 'label' => 'Titolo blocco', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.type', array('type' => 'select', 'label' =>'Tipo','options' => array('0' => 'Mostra tutto', '1' => 'Mostra anteprima')));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.page_id', array('type' => 'hidden', 'value' => $this->data['Page']['id']));?>
			
			<?=$this->Form->input('Block.url_page_id', array('label' => 'Collegamento pagina', 'type' => 'select', 'options' => $tree, 'empty' => true));?>
			<?=$this->Form->input('Block.url', array('type' => 'text', 'label' => '&nbsp;', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<div class="post_content">
			
			<?//=$this->element('/backend/ckeditor', array('name' => 'Block.content','id' => 'ContentCkeditor', 'title' => 'Contenuto blocco'));?>
			
			<?=$this->Form->input('Block.content', array('type' => 'textarea', 'label' => 'Contenuto'));?>
			
			</div>
			
			<div class="clear"></div>
			
			<?=$this->Form->submit('aggiungi',array('type' => 'submit','div' => true));?>
			
		<?=$this->Form->end();?>
		
		<?

		//debug($blocks);
		
		?>
		
		<div class="operations_bar">
			<div class="left">		
				<ul class="table_operations">
					<li>
						<select id="block-order-argument">
							<option value="random()">Casuale</option>
							<option value="created">Data creazione</option>
							<option value="order">Drag & Drop</option>
							<option value="title">Titolo</option>
						</select>
					</li>
					<li>
						<select id="block-order-type">
							<option value="ASC">Ascendente</option>
							<option value="DESC">Discendente</option>
						</select>
					</li>
				</ul>	
			</div>	
		<div class="clear"></div>			
		</div>
		
		<table id="blockTable" class="form_table form_table_full">
		
			<tr>
				<th>Titolo</th>
				<th>Tipo</th>
				<th>Collegamento</th>			
				<th>Collegamento pagina</th>			
				<th>Opzioni</th>
			</tr>
		
			<? foreach($blocks as $block): ?>
			
				<tr class="block-row" data-id="<?=$block['Block']['id'];?>" id="<?=$block['Block']['id'];?>">
					<td><?=$block['Block']['title'];?></td>
					<td><?=$block['Block']['type_it'];?></td>
					<td><?=$block['Block']['url'];?></td>
					<td><?=$block['Block']['page_url'];?></td>
					<td>
						<a class="block-disabled-switch" href="javascript:;">
							<img alt="disabled" src="/img/timmyshare/icon_disabled_<?=$block['Block']['disabled'];?>.gif">
						</a>
						<a href="javascript:;" class="blockEdit">
							<img src="/img/timmyshare/icon_edit.png" />
						</a>							
						<a href="javascript:;" class="blockDelete">
							<img src="/img/timmyshare/icon_delete.png" />
						</a>								
					</td>					
				</tr>
			
			<? endforeach; ?>
		
		</table>
		
		</div>
		
	</div>
		
