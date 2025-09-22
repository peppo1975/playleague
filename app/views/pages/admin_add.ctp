<script>
//CKEDITOR.basePath = '/js/ckeditor/';
//CKEDITOR.config.jqueryOverrideVal = true;
</script>
<!-- Important: The JQuery adapter is loaded *after* setting jqueryOverrideVal -->
<!--<script src="/js/ckeditor/adapters/jquery.js"></script>-->
<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	$("#PageTitle").keyup(function(e){ $("#PageAlias").val($(this).val()); });
	$("#PageType").val('static');

});

// $(document).ready(function(){

	// var config = {
		// toolbar:
		// [
			// ['Source', '-', 'Undo','Redo','-', 'Cut','Copy','Paste','PasteText','PasteFromWord','-'],
			// ['Find','Replace','SelectAll','RemoveFormat'],
			// ['Bold', 'Italic', 'Underline', 'Strike'],
			// ['Image', 'Link', 'Unlink', 'Anchor'],
			// ['NumberedList','BulletedList','Outdent','Indent','Blockquote']
		// ]
	// };   			

	// $('#PageContent').ckeditor(config);	

// });
}
</script>


	<?=$this->Form->create('Page', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo contenuto</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="tab-container">
	
		<ul class="tab-selector">
		
			<li data-index="1" class="selected"><a href="javascript:;">Pagina</a></li>
			<li data-index="2"><a href="javascript:;">Allegati</a></li>
			<li data-index="5"><a href="javascript:;">Metadata</a></li>
		
		</ul>	
		
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
			
				<?=$this->Form->input('block_limit', array('label' => 'Limite blocchi (lasciare 0 per non paginare)', 'type' => 'text', 'value' => 0));?>
				
				<div class="clear"></div>
			
				<?=$this->element('/backend/ckeditor', array('name' => 'we', 'title' => 'Contenuto pagina'));?>
				
				<?//=$this->Form->input('content', array('label' => 'Contenuto pagina', 'type' => 'textarea'));?>
			
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
		
		<div class="tab-page" data-index="2">
		
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
		
				<?=$backend->getFiles('page_id', 0, array(
				
					'tag' => array('' => 'Allegato','link' => 'Collegamento'),
				
				));?>
		
			</div>
		
		</div>
		
		<div class="tab-page" data-index="5">
		
			<?=$this->element('/backend/metadata');?>
		
		</div>
	
	</div>
	
	<?=$this->Form->end();?>
