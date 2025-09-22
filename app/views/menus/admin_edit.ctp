	<script type="text/javascript">
	if (typeof $ != "undefined") {
	$(function(){
	
		$("#MenuEditForm").submit(function(){
		
			var data    = $(this).serialize();
			var page    = $("#MenuPage :selected").text();
			var page_id = $("#MenuPage").val();
			
			$.post('/admin/menus/addChild', data, function(ret){
			
				if(ret.add != 0) {

					var bt_edit   = $('<a>').addClass('children-edit').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_edit.png'));			
					var bt_delete = $('<a>').addClass('children-delete').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_delete.png'));
					var td_option = $('<td>').append(bt_edit).append(bt_delete);
					var td_name = $('<td>').text(page);
					var tr = $('<tr>').append(td_name).append(td_option);
					
					$("#children-page").append(tr);
				
				}
			
			},'json');
		
			return false;
		
		});
		
		$('.children-page').delegate('.children-edit', 'click', function(){
		
			/*
			
				clear
			
			*/
			
			$('.children-option').remove();
			$("#AddChildParent").val(tr_id);
			
			/*
			
				end clear
			
			*/
			
			var tr_id  = $(this).parents('tr').attr('data-id');
			var tr     = $("#children-" + tr_id);			
			
			$.get('/admin/menus/getChild/' + tr_id, function(data){
			
				for(i in data.children) {
				
					var bt_delete = $('<a>').addClass('children-remove').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_delete.png'));			
					var new_tr    = $('<tr>').addClass('children-option')
											 .attr('data-id', i)
											 .attr('id', 'childs-' + i)
											 .append($('<td>').text(' - ' + data.children[i]))
											 .append($('<td>').append(bt_delete));
					
					new_tr.insertAfter(tr);				
				
				}
							  
				$('.children-add-div').slideDown('fast');
				
				$.get('/admin/menus/getChildren/'+tr_id, function(ret){
				
					$("#AddChildChild ").find("option:not(:eq(0))").remove();
				
					for(i in ret.children) {
				
						var option = $('<option>').attr('value', i).text(ret.children[i]);
						$('#AddChildChild').append(option);
					
					}
					
					$("#AddChildParent").val(tr_id);
					
				},'json');
				
			}, 'json');
		
		});
		
		$("#AddChildAdminEditForm").submit(function(){
		
			var tr_id = $("#AddChildParent").val();
			var tr    = $("#children-" + tr_id);
			var data  = $(this).serialize();
			
			$.post('/admin/menus/addChilds', data, function(rets){
			
				for(i in rets.add) {
				
					console.log(i);
					console.log(rets.add[i]);
				
					var bt_delete = $('<a>').addClass('children-remove').attr('href', 'javascript:;').append($('<img>').attr('src', '/img/timmyshare/icon_delete.png'));			
					var new_tr    = $('<tr>').addClass('children-option')
											 .attr('data-id', i)
											 .attr('id', 'childs-' + i)
											 .append($('<td>').text(' - ' + rets.add[i]))
											 .append($('<td>').append(bt_delete));

					new_tr.insertAfter(tr);

				}
			
			
			},'json');
		
			return false;
		
		});
		
		// $('.children-remove') evento per i tr figli
		// $('.children-delete') evento per i tr genitori
	
	});
	}
	</script>
	
	
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Menu', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica menu: <span><?=$this->data['Menu']['title'];?></span></h2>
								<ul>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('id');?>
	
	<?=$this->Form->input('page', array('label' => 'Pagina', 'type' => 'select', 'options' => $pages_total, 'empty' => 'Seleziona pagina'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->submit('Aggiungi',array('type' => 'submit','div' => true));?>
	
	<?=$this->Form->end();?>
	
	<div class="clear"></div>
	
	<div class="children-page">
	
		<table id="children-page" class="form_table form_table_full">
		
			<tr>
				<th>Nome</th>
				<th>Opzioni</th>
			</tr>
			
			<? foreach($pages as $page): ?>
			
				<tr id="children-<?=$page['Page']['id'];?>" data-id="<?=$page['Page']['id'];?>">
					<td><?=$page['Page']['title'];?></td>
					<td>
						<a class="children-edit" href="javascript:;">
							<img src="/img/timmyshare/icon_edit.png">
						</a>
						<a class="children-delete" href="javascript:;">
							<img src="/img/timmyshare/icon_delete.png">
						</a>
					</td>
				</tr>
			
			<? endforeach; ?>
		
		</table>
		<div class="clear"></div>
		
		<div class="children-add-div" style="display: none;">
		
		<?=$this->Form->create('AddChild');?>
		
			<?=$this->Form->input('menu_id', array('type' => 'hidden', 'value' => $this->data['Menu']['id']));?>
			<?=$this->Form->input('parent', array('type' => 'hidden'));?>
		
			<?=$this->Form->input('child', array('label' => '', 'type' => 'select', 'empty' => 'Seleziona figlio...'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->submit('Aggiungi',array('type' => 'submit','div' => true));?>
		
		<?=$this->Form->end();?>
		
		</div>
	
	</div>