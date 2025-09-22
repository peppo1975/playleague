<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	function checkUrl() {
	
		if($("#BlockUrlPageId").val() != '') { $("#BlockUrl").fadeOut('fast'); $("#BlockUrl").val(''); }
		else					  			 { $("#BlockUrl").fadeIn('fast');  $("#BlockUrl").val('http://'); }
	
	}
	
	checkUrl();

	$("#BlockUrlPageId").change(function(){ 
	
		checkUrl();
		
	});
	
	checkUrl();
	
	$("#BlockPageId").find('option:eq(1)').remove();
	$("#BlockUrlPageId").find('option:eq(1)').remove();
	
	function typeBlock(){
	
		if($("#BlockType").val() == 0) {
		
			$("#BlockUrlPageId").parent('div').hide();
			$("#BlockUrl").parent('div').hide();
		
		} else {
		
			$("#BlockUrlPageId").parent('div').show();
			$("#BlockUrl").parent('div').show();		
		
		}
	
	}
	
	typeBlock();
	
	$("#BlockType").change(function(){typeBlock();});	
	
});
}
</script>
	<?=$this->element('/backend/blog_script');?>
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('Block', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica blocco: <span><?=$this->data['Block']['title'];?> - <?=$this->data['Block']['mother_page'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	<?=$this->Form->input('id');?>

				<?=$this->Form->input('Block.page_id', array('label' => 'Pagina madre', 'type' => 'select', 'options' => $tree));?>
			
			<div class="clear"></div>

			<?=$this->Form->input('Block.title', array('type' => 'text', 'label' => 'Titolo blocco', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.type', array('type' => 'select', 'label' =>'Tipo','options' => array('0' => 'Mostra tutto', '1' => 'Mostra anteprima')));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.url_page_id', array('label' => 'Collegamento pagina', 'type' => 'select', 'options' => $tree, 'empty' => '------->'));?>
			<?=$this->Form->input('Block.url', array('type' => 'text', 'label' => '&nbsp;', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.created', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data creazione'));?>
			<?=$this->Form->input('Block.published', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data pubblicazione'));?>

			<!--  Data fine, pagina di edit 04/05/2018 -->	
			<?= $this->Form->input('Block.over', array('type' => 'text', 'class' => 'datePicker', 'label' => 'Data fine')); ?>					
			
			<div class="clear"></div>
			
			<div class="post_content">
			
			<?=$this->element('/backend/ckeditor', array('name' => 'content', 'title' => 'Contenuto blocco'));?>
			
			</div>
			
			<div class="clear"></div>
			
			<div id="formUploadContainer">
			<script type="text/javascript">
			if (typeof $ != "undefined") {
			$(function(){
			var upload = $("#UploadTag");
			var desc   = $("#UploadDescription");
			    upload.val('link');
				desc.parent('div').find('label').text('Link'); desc.addClass('big'); desc.val('http://');
				upload.change(function(){
					if(upload.val() == '') { desc.parent('div').find('label').text('Descrizione'); desc.removeClass('big'); desc.val(''); }
					else 				   { desc.parent('div').find('label').text('Link'); desc.addClass('big'); desc.val('http://'); }
				});
			});
			}
			</script>		
		
				<?=$backend->getFiles('block_id', $this->data['Block']['id'], array(
				
					'tag' => array('' => 'Allegati','link' => 'Collegamenti'),
				
				));?>
		
			</div>
	
	<?=$this->Form->end();?>