<script type="text/javascript">

$(function() {
    $.fn.hasScrollBar = function() {
    	if(typeof this.get(0) != "undefined")
        	return this.get(0).scrollHeight > this.height();
    }
});


$(function(){
	
	$('.content-story').height($(".content-tab").height() - 63).css('background','#FFF');
	$('.team-story').height($(".content-tab").height() - 63);
	
	
});

$(document).ready(function(){
	
	if($('.table-scroll').hasScrollBar()) return false;
	
	$("#uploadTable").width($('.table-scroll').width());
	
});

</script>

<div class="tab-squadra">
		<div class="list-tab text-center">
			<ul class="pagination pagination-sm">
				<li><a href="/squadres/teams_edit/<?=$squadra['Squadre']['Squadra'];?>/1" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<li class="active"><a href="/squadres/teams_edit/<?=$squadra['Squadre']['Squadra'];?>/2" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Upload</a></li>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="content-tab">
		
<div class="edit-squadra table-container-files" style="display: block; opacity: 1;">
								
					<?=$this->element('/backend/js_form_index', array('model' => 'Squadre', 'url' => '/squadres/teams', 'limit' => 100));?>
					
					<script type="text/javascript">
					
						$(function(){
							
							$("#saveUpload").submit(function(){
								
								$(this).find('.error-message').remove();
								
								ajaxLoader('show');
								
								var tag = $('#UploadTag');
								
								var error = 0;
								
								if($("#UploadPercorso").val() == '') {
									
									$("#UploadPercorso").parent('div').append('<div class="error-message">Immagine obbligatoria.</div>');
									error = 1;
									
								}
								
								if(tag.val() == 'nullo') {
									
									tag.parent('div').append('<div class="error-message">Tag immagine obbligatorio.</div>');
									error = 1;
									
								}
								
								if($("#UploadYearTrofeo").val() == '' && $("#UploadYearTrofeo").parent('div').hasClass('required')) {
									
									$("#UploadYearTrofeo").parent('div').append('<div class="error-message">Anno obbligatorio.</div>');
									error = 1;
									
								}											
								
								if(error == 1) { ajaxLoader('hide'); return false; }
								
							});										
							
						});
					
					</script>
					
					<?=$this->Form->create('Squadre', array('url' => '/squadres/saveUpload/' . $squadra['Squadre']['Squadra'], 'type' => 'file', 'id' => 'saveUpload',



        'class'         => 'form-horizontal',
        'inputDefaults' => array(
            'format'  => array( 'before', 'between','label',
                                'input', 'error', 'after' ),
            'class' => 'form-control',
            'div'     => array( 'class' => 'form-group' ),
            'label'   => array( 'class' => 'control-label' ),
            'between' => '<div class="col-lg-12">',
            'after'   => '</div>',
            'error'   => array( 'attributes' => array( 'wrap'  => 'span',
                                                       'class' => 'text-danger' 

					)))));?>
					
					<?
					
					$tags['nullo'] = '';
					
					$tags['Trofeo'] = 'Albo d\'oro';
					$tags[''] = 'Galleria fotografica';	
						
					if(empty($uploads['Squadra'])) $tags['Squadra'] = 'Immagine squadra';
					if(empty($uploads['Logo']))    $tags['Logo'] = 'Logo squadra';
					
					$tags['Sponsor'] = 'Sponsor squadra';
					
					?>
					
					<?=$backend->getFiles('squadra_id',$squadra['Squadre']['Squadra'], array(
					
						'tag'        => $tags,
						'element' => 'site/filter_squadre_logged_file'
					
					));?>

					<?=$this->Form->end();?>
					</div><!-- close edit-squadra -->
									
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>	