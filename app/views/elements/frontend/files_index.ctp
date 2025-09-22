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
	
	
	
	<div class="input">
		
		<label>Tipologia media</label>
		
		<select name="change_type">
			<option value="file">File</option>
			<option value="media">Vimeo/YouTube</option>
		</select>
	</div>


	<div class="upload-type-file">
	
	<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?=uniqid();?>"/>
	
	<?=$this->Form->input('Upload.percorso',array('label' => 'Inserisci file','type' => 'file'));?>
	<?=$this->Form->input('Upload.extract',array('label' => 'Se archivio (.zip)','type' => 'select','options' => array('1' => 'Estrai archivio','0' => 'Non estrarre')));?>
	<?if($tag != array()):?>
	
		<?=$this->Form->input('Upload.tag', array('type' => 'select', 'options' => $tag, 'label' => 'Tipo'));?>
	
	<?endif;?>
	<?=$this->Form->input('Upload.description',array('label' => 'Descrizione','type' => 'text'));?>

	<div class="input">

	<label>&nbsp;</label>

	<div class="progress_bar">

	<div class="bar_status"></div>

	</div>

	</div>
	
	<div class="input">

	<label>&nbsp;</label>

	<?=$this->Form->submit('carica file',array('type' => 'submit','div' => false));?>

	</div>

	</div>
	
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
<ul class="table_operations">
	<li><a href="javascript:;" class="index-files-select-all" title="">seleziona tutti</a></li>
	
	<li><a href="javascript:;" class="index-files-revert-selected" title="">inverti selezione</a></li>
	
	<li><a href="javascript:;" class="index-files-delete-selected" title="">cancella selezionati</a></li>
</ul>
</div>
<table id="uploadTable" class="form_table">
								<tr>
									<th class="first">&nbsp;</th>
									<th>Anteprima</th>
									<th>Nome file</th>
									<th>Titolo</th>
									<th>Descrizione</th>
									<th>Dimensione</th>
									<th>Tipologia</th>
									<th>Formato</th>
									<th class="last">Tipo</th>
								</tr>
								
								<? $alterna = ""; ?>
								
								<? foreach ($files as $file): ?>

								
								<tr class="index-file-row <?=$alterna;?>">
								<td class="tools">
									<ul>
										<li><input type="checkbox" class="index-file-checkbox" data-id="<?=$file['Upload']['id'];?>" /></li>

										<? if(isset($buttons) && $buttons != array()): ?>
										
											<? foreach($buttons as $button => $value): ?>
											
												<? if(isset($button)) $title = $button; else $button = 'Default title'; ?>
												<? if(isset($value['class'])) $class = $value['class']; else $class = ''; ?>
												<? if(isset($value['img'])) $img = $value['img']; else $img = ''; ?>
												<? if(isset($value['link'])) $link = $value['link']; else $link = '';?>
												<? if(isset($value['action'])) $action = $value['action']; else $action = ''; ?>
												
												<li>
												 <a href="javascript:;" class="index-row-<?=$class;?>" data-id="<?=$file['Upload']['id'];?>"  rel="timmytip" title="<?=$title;?>">
													<img src="<?=$img;?>" width="16" height="16">
												 </a>
												</li>
											
											<? endforeach; ?>
										
										<? endif; ?>
										<li>
											<?
											
												if((int)$file['Upload']['isEvidenza'] == 0) $title = 'Metti in evidenza';
													else $title = 'Non in evidenza';
											
											?>
											<a href="javascript:;" class="index-file-evidenza" data-id="<?=$file['Upload']['id'];?>" rel="timmytip" title="<?=$title;?>">
												<img src="/img/timmyshare/icon_evidenza_<?=(int)$file['Upload']['isEvidenza'];?>.png" width="16" height="16" />
											</a>
											
										</li>																				
										<li>

											<a href="javascript:;" class="index-file-edit" data-id="<?=$file['Upload']['id'];?>" rel="timmytip" title="Modifica">
												<img src="/img/timmyshare/icon_edit.png" width="16" height="16" />
											</a>
											
										</li>
										<li>

											<a href="<?=$file['Upload']['path'];?>" rel="timmytip" title="Download">
												<img src="/img/timmyshare/icon_download.png" width="16" height="16" />
											</a>
											
										</li>
										<li>
											<a href="javascript:;" class="index-file-delete" data-id="<?=$file['Upload']['id'];?>" rel="timmytip" title="Cancella">
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
								
									<td>
										<a class="anteprima" href="javascript:;" link="<?=$thumbnail->link(array('path' =>  $file['Upload']['path'], 'w' => 1024));?>" rel="timmygallery" title="<?=$file['Upload']['name'];?>">
											<span>
												<img src="/img/timmyshare/icon_zoom.png" width="16" height="16" />
											</span>
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
									<td><?=$file['Upload']['name'];?></td>
									<td><?=$file['Upload']['title'];?></td>
									<td><?=$file['Upload']['description'];?></td>
									<td><?=$file['Upload']['filesize'];?></td>
									<td><?=$file['Upload']['group'];?></td>
									<td><?=$file['Upload']['ext'];?></td>
									<td><?=$file['Upload']['tag'];?></td>
								</tr>
								<? if ($alterna == "") $alterna = "alterna";
								   else $alterna = "";
								?>
								<? endforeach; ?>
</table>

<div class="operations_files_bar">
<ul class="table_operations">
	<li><a href="javascript:;" class="index-files-select-all" title="">seleziona tutti</a></li>
	
	<li><a href="javascript:;" class="index-files-revert-selected" title="">inverti selezione</a></li>
	
	<li><a href="javascript:;" class="index-files-delete-selected" title="">cancella selezionati</a></li>
</ul>
</div>
<? endif; ?>
