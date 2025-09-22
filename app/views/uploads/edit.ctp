<!--
<script type="text/javascript">
if (typeof $ != "undefined") {
$(function(){

	var option = new Array();
	var upload_id = $("#UploadId").val();	
	var tag_value = $('.index-file-row ').find('a[data-id=' + upload_id + ']').parents('.index-file-row').find('td:last').text();

	$("#UploadTag").children('option').each(function(index){
	
		var value = $(this).val();
		var text  = $(this).text();
		
		option[index] = { "value":value, "text":text }
		
	});
	
	if(option.length == 0) return false;
	
	var select     = $("<select>").addClass('tag').attr('id','UploadTag').attr('name','data[Upload][tag]');
	var div_select = $("<div>").addClass('input').append(select);
	
	for(i = 0; i < option.length; i++) {
	
		select.append($("<option>").attr('value', option[i].value).text(option[i].text));
	
	}
	
	div_select.insertAfter($("#UploadTitle").parent('div.input'));	
	$("<div>").addClass('clear').insertBefore(div_select);
	
	$(".tag").val(tag_value);

});
}
</script>
 -->

	<?=$this->Form->create('Upload', array('action' => 'edit', 'class' => 'timmyFileEdit','type' => 'file'));?>
	<?=$this->Form->input('id');?>
	<?=$this->Form->input('name',array('label' => 'Nome file', 'readonly' => true));?>

	<div class="clear"></div>
	
	<?=$this->Form->input('title',array('label' => 'Titolo','type' => 'text'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('description',array('label' => 'Descrizione','class' => 'timmy_textarea'));?>

	<div class="clear"></div>
	<?=$this->Form->submit('salva',array('class' => 'submit fileEdit'));?>

	<?=$this->Form->end();?>

