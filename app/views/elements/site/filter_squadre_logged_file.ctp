<script type="text/javascript" src="/js/timmygallery.js"></script>	
<div class="clear"></div>

<script type="text/javascript">

	if (typeof $ != "undefined") {

	$("select[name='change_type']").bind('change',function() {
		
		var toShow = $(".upload-type-" + $(this).find('option:selected').val());
		
		$(".upload-type-file,.upload-type-media").hide();
		
		$(toShow).show();
		
	});
	
	}

</script>

<div id="files_form" style="<?if(count($files) >= $limit && $limit > 0):?>display: none;<?endif;?>">

	<h3></h3>
	
	
	
	<div class="input hidden">
		
		<label>Tipologia media</label>
		
		<select name="change_type">
			<option value="file">File</option>
			<option value="media">Vimeo/YouTube</option>
		</select>
	</div>


	<div class="upload-type-file">
	
	<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?=uniqid();?>" />
	
	<?=$this->Form->input('Upload.percorso',array('label' => 'Inserisci file','type' => 'file'));?>
	<?=$this->Form->input('Upload.extract',array('div' => array('class' => 'hidden'),'label' => 'Se archivio (.zip)','type' => 'select','default' => 0,'options' => array('1' => 'Estrai archivio','0' => 'Non estrarre')));?>
	<?if($tag != array()):?>
	
		<?=$this->Form->input('Upload.tag', array('type' => 'select', 'options' => $tag, 'label' => 'Tipo'));?>
	
	<?endif;?>
	
	<div class="clear"></div>
	
	<?
	
		$years = array();
		$now   = date("Y");
		
		for($i = 1999; $i <= $now; $i++) {
			$years[$i] = $i;
		}
		
		$years = array_reverse($years, true);
		
	
	?>
	
	<?=$this->Form->input('Upload.yearTrofeo',array('label' => 'Anno','type' => 'select', 'options' => $years, 'empty' => true, 'div' => array('class' => 'hidden input select')));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Upload.title',array('label' => 'Titolo','type' => 'text'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('Upload.description',array('label' => 'Descrizione','type' => 'textarea'));?>
	
	<div class="input">
		<label>&nbsp;</label>

		<div class="progress_bar">
			<div class="bar_status"></div>
		</div>

	</div>
	
	<div class="clear"></div>
	
	<div class="input" style="clear: both;">
		<?=$this->Form->submit('carica file',array('type' => 'submit','class'=>'btn btn-primary pull-right mb-xl','div' => false));?>
	</div>
	</div>
	<br />
	<hr />
	<div class="upload-type-media" style="display: none;">
	

	<?=$this->Form->input('Media.percorso',array('label' => 'YouTube/Vimeo URL','type' => 'text'));?>

	<?if($tag != array()):?>
	
		<?=$this->Form->input('Media.tag', array('type' => 'select', 'options' => $tag, 'label' => 'Tipo'));?>
	
	<?endif;?>
	<?=$this->Form->input('Media.description',array('label' => 'Descrizione','type' => 'text'));?>

	<div class="input">

	<label>&nbsp;</label>

	<?=$this->Form->submit('inserisci media',array('type' => 'submit','div' => false));?>

	</div>
	
	</div>
	
</div>
<div class="clear"></div>

<? if (count($files)): ?>
<div class="operations_files_bar">
<ul class="table_operations pagination pagination-sm">
	<li><a href="javascript:;" class="index-files-select-all" title="">seleziona tutti</a></li>
	
	<li><a href="javascript:;" class="index-files-revert-selected" title="">inverti selezione</a></li>
	
	<li><a href="javascript:;" class="index-files-delete-selected" title="">cancella selezionati</a></li>
</ul>
</div>

<div class="table-container-files" id="no-margin-container-file"><!-- table-container-files -->

<div id="results-box-files">

							

<div class="table-scroll">
<table class="form_table table-matches gruppo table table-bordered table-striped table-condensed" id="uploadTable">
						<thead class="table-header">
		<th style="width: 110px;" class="first">Strumenti</th>
		<th style="width: 61px;">Anteprima</th>
		<th>Nome file</th>
		<th>Titolo</th>
		<th>Descrizione</th>
		<th class="last">Tipo</th>
	</thead>			
								<? $alterna = ""; ?>
								
								<? $files = Set::sort($files, '{n}.Upload.tag', 'ASC'); ?>
								
								<? foreach ($files as $file): ?>

								
								<tr data-id="<?=$file['Upload']['id'];?>" class="index-file-row <?=$alterna;?>">
								<td class="tools">
									<ul class="pagination pagination-sm">
										<li><a style="border: 0px !important; background-color: transparent !important;" href="javascript:;"><input type="checkbox" class="index-file-checkbox" data-id="<?=$file['Upload']['id'];?>" /></a></li>

										<? if(isset($buttons) && $buttons != array()): ?>
										
											<? foreach($buttons as $button => $value): ?>
											
												<? if(isset($button)) $title = $button; else $button = 'Default title'; ?>
												<? if(isset($value['class'])) $class = $value['class']; else $class = ''; ?>
												<? if(isset($value['img'])) $img = $value['img']; else $img = ''; ?>
												<? if(isset($value['link'])) $link = $value['link']; else $link = '';?>
												<? if(isset($value['action'])) $action = $value['action']; else $action = ''; ?>
												
												<li style="display: inline; float: left;">
												 <a href="javascript:;" class="index-row-<?=$class;?>" data-id="<?=$file['Upload']['id'];?>"  rel="timmytip" title="<?=$title;?>">
													<img src="<?=$img;?>" width="16" height="16">
												 </a>
												</li>
											
											<? endforeach; ?>
										
										<? endif; ?>
							
							
										<li>
											<a href="javascript:;" style="border: 0px !important; background-color: transparent !important;"  class="index-file-delete" data-id="<?=$file['Upload']['id'];?>" rel="timmytip" title="Cancella">
												<img src="/img/timmyshare/icon_delete.png" width="16" height="16" alt="cancella" />
											</a>
										</li>

									</ul>
								</td>
								
									<? switch($file['Upload']['group']):
								
									case 'image': 
									case 'youtube':
									case 'vimeo':
					
									?>
								
									<td align="center">
										<a class="anteprima" data-toggle="modal" href="<?=$thumbnail->link(array('path' =>  $file['Upload']['path'], 'w' => 1024));?>" rel="timmygallery" title="<?=$file['Upload']['name'];?>">
							
											<?	$thumbnail->show(array('path' => $file['Upload']['path'], 'w' => 50,'h' => 50,'q' => 100,'zc' => 1)); ?>
											<span class="timmy_description"><?=$file['Upload']['description'];?></span>
										</a>

									</td>	
									
									
									
									<? break; ?>
									
									<?
										
									case 'video': 
					
									?>
								
									<td>
										<a class="anteprima is_video" href="javascript:;"  link="<?=$file['Upload']['path'];?>" rel="timmygallery" title="<?=$file['Upload']['name'];?>">
											<span>
												<img src="/img/timmyshare/icon_zoom.png" width="16" height="16" />
											</span>
											<?	
												if (is_file(APP .'/webroot/img/mimetypes/mime-' . $file['Upload']['group'] . "-" . $file['Upload']['ext'] . ".png"))
												$thumbnail->show(array('path' => '/img/mimetypes/mime-' . $file['Upload']['group'] . "-" . $file['Upload']['ext'] . ".png", 'w' => 50,'h' => 50,'q' => 100,'zc' => 1)); 
												else
												$thumbnail->show(array('path' => '/img/mimetypes/mime-' . $file['Upload']['group'] . ".png", 'w' => 50,'h' => 50,'q' => 100,'zc' => 1)); 
											
											?>
											<span class="timmy_description"><?=$file['Upload']['description'];?></span>
										</a>

									</td>	
									
									
									
									<? break; ?>
									
									
									<? default: ?>
									
									<td>
										<div class="anteprima">
			
											<?	
												if (is_file(APP .'/webroot/img/mimetypes/mime-' . $file['Upload']['group'] . "-" . $file['Upload']['ext'] . ".png"))
												$thumbnail->show(array('path' => '/img/mimetypes/mime-' . $file['Upload']['group'] . "-" . $file['Upload']['ext'] . ".png", 'w' => 50,'h' => 50,'q' => 100,'zc' => 1)); 
												else
												$thumbnail->show(array('path' => '/img/mimetypes/mime-' . $file['Upload']['group'] . ".png", 'w' => 50,'h' => 50,'q' => 100,'zc' => 1)); 
											
											?>
											
										</div>

									</td>										
									
									<? endswitch; ?>
									<td class="td_filename" title="<?=$file['Upload']['name'];?>"><?=$this->Text->truncate($file['Upload']['name'],20);?></td>
									<td class="td_title"><?=$file['Upload']['title'];?></td>
									<td class="td_description"><?=$file['Upload']['description'];?></td>
									<td><?=($file['Upload']['tag'] != '')? $file['Upload']['tag'] : 'Galleria foto';?></td>
								</tr>
								<? if ($alterna == "") $alterna = "alternate";
								   else $alterna = "";
								?>
								<? endforeach; ?>
</table>

</div><!-- close table-scroll -->

</div><!-- close results-box-files -->

</div><!-- close table-container-files -->

<div class="operations_files_bar">
<ul class="table_operations pagination pagination-sm">
	<li><a href="javascript:;" class="index-files-select-all" title="">seleziona tutti</a></li>
	
	<li><a href="javascript:;" class="index-files-revert-selected" title="">inverti selezione</a></li>
	
	<li><a href="javascript:;" class="index-files-delete-selected" title="">cancella selezionati</a></li>
</ul>
</div>
<? endif; ?>
