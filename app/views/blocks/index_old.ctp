<script type="text/javascript">
$(function() {
	$('.block-attachment li').find('img').css('opacity',0);
});
$(window).load(function() {

	$('.block-attachment').find('li').each(function(index){
	
		var first = parseFloat($(this).height() / 2);
		var second= parseFloat($(this).find('img').height() / 2);
	
		var margin_top = first - second;
		//var margin_left = ($(this).width() / 2) - ($(this).children('img').width() / 2);
		
		$(this).find('img').css('margin-top', margin_top);
		//$(this).children('img').css('margin-left', margin_left);

	});
	
	$('.block-attachment li').find('img').css('opacity',1);
	
});				

</script>
<?

$isiPad    = (bool)strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$ext       = array('flv','mp4');
$strip 	   = strip_tags($data['Block']['content']); 
$ext_doc   = array('doc','xls','zip','xlsx','rar','pdf','mp3');
$ext_video = array('flv','mp4','mp3');
$ext_img   = array('jpg','jpeg','png','gif');

$mimes = array(

	'doc' => 'icon-doc.png',
	'xls' => 'icon-xls.png',
	'zip' => 'icon-zip.png',
	'xlsx' => 'icon-xls.png',
	'rar' => 'icon-zip.png',
	'pdf' => 'icon-pdf.png',
	'mp3' => 'icon-mp3.png',

);

$countDocuments  = 0;
$countMultimedia = 0;
$countVideos     = 0;
$countLink       = 0;
$countIpad       = 0;
if(isset($data['Upload']) && count($data['Upload'])) {
//Check documents and media upload
foreach($data['Upload'] as $tmp){
	if(in_array($tmp['ext'], $ext_doc)) $countDocuments++;
	if(in_array($tmp['ext'], $ext_img) && $tmp['tag'] != 'link') $countMultimedia++;
	if(in_array($tmp['ext'], $ext_video)) $countVideos++;
	if($tmp['ext'] == 'mp4') $countIpad++;
	if($tmp['tag'] == 'link') $countLink++;
}	
}

?>

<div class="wrapper-box">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">

<div class="content-header">

<div class="content-title">
<h1><?=$data['Block']['title'];?></h1>
<?if($data['Block']['published_it'] != '' && $data['Block']['published_it'] != '00/00/0000'):?>
<p class="post-info">pubblicato il <?=$data['Block']['published_it'];?></p><!-- solo per le news -->
<?endif;?>
</div>

	<?if($data['Block']['published_it'] != '' && $data['Block']['published_it'] != '00/00/0000'):?>
	
	<ul class="content-share">
	<!-- Example social sharing <img src="/img/website/sharing.png" alt="" /> -->
	<? $pageLink = 'http://' . $_SERVER['HTTP_HOST'] . '/blocchi/' . $data['Block']['id'] . '/' . strtolower(Inflector::Slug($data['Block']['title'],'-')) ;?>
	<li class="fb-share"><!-- Facebook share button -->
	<iframe src="http://www.facebook.com/plugins/like.php?app_id=147431638679678&amp;href=<?=$pageLink;?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:90px;" allowTransparency="true"></iframe>
	</li><!-- Facebook share button end -->
	<li class="twt-share"><!-- Twitter button -->
	<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?=$pageLink;?>" data-counturl="<?=$pageLink;?>" data-count="vertical" data-lang="it">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</li><!-- Twitter button end -->
	<li class="g-share"><!-- Google button -->
	<g:plusone size="tall" href="<?=$pageLink;?>"></g:plusone>
	</li><!-- Google button end -->
	</ul>
	
	<? endif; ?>
	
<div class="clear"></div>

</div>
								<div class="contents-block-left">
								<div class="contents-text">
									<? if(!empty($strip)): ?>
										<?=$data['Block']['content'];?>	
									<? endif; ?>
								
								<? if($countLink > 0): ?>
								
								<ul class="block-attachment attachment-link"><!-- div foto collegamenti -->
								
								<? foreach($data['Upload'] as $attach): ?>
								
								<? if(in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link'): ?>
								
								<?
									
									if($attach['title'] != '') $title = $attach['title'];
									else					   $title = $attach['name'];
									
									$path = $thumbnail->link(array('path' => $attach['path'], 'w' => 212,'h' => 80, 'q' => 100, 'f' => 'jpg'));
									//Cambiare dimensioni file.
								?>

								<li>
								
								<? if(substr($attach['description'],0,7) == 'http://' && strlen($attach['description']) > 7): ?>
								
								<a href="<?=$attach['description'];?>" title="<?=$title;?>">
									<img src="<?=$path;?>" alt="<?=$title;?>" />
								</a>
								
								<? else: ?>
								
									<img src="<?=$path;?>" alt="<?=$title;?>" />
								
								<? endif; ?>
								
								</li>
								
								<? endif; ?>
								
								<? endforeach;?>
								
								</ul><!-- end div foto collegamenti -->
								
								<div class="clear"></div>
								
								<? endif; ?>
					
					<? if($countDocuments > 0): ?>
					
					<div class="contentents-files-documents">
						<h3>Files e documenti allegati</h3>
						<div class="documents-container">
						
						<? foreach($data['Upload'] as $attach): ?>
						
						<? if(in_array($attach['ext'], $ext_doc)): ?>
						
						<?
							
							if($attach['title'] != '') $title = $attach['title'];
							else					   $title = $attach['name'];
						
						?>
						
							<div class="attached-document">
								<a href="/files/uploads/<?=$attach['name'];?>" title="<?=$title;?>">
									<span class="icon"><img src="/img/website/<?=$mimes[$attach['ext']];?>" alt=""></span><?=$title;?>
								</a>
								<span class="close-link"></span>
							</div>
							
						<? endif; ?>
							
						<? endforeach; ?>
						
							<div class="clear"></div>
							
						</div><!-- close documents-container -->
					</div> <!-- close contentents-files-documents -->
					
					<? endif; ?>
					
<? if($countMultimedia > 0): ?>

<?=$this->element("site/slider",array('countMultimedia' => $countMultimedia,'data' => $data,'ext_img' => $ext_img));?>
					


<? endif; ?>

<? if($countVideos > 0): ?>
					
					<div class="contentents-files-documents" id="videoUpload">
						<h3>Video allegati</h3>
						<div class="documents-container" id="videoUploadContainer">
						
						<? foreach($data['Upload'] as $attach): ?>
						
						<? if(in_array($attach['ext'], $ext_video)): ?>
						
						<?
							
							if($attach['title'] != '') $title = $attach['title'];
							else					   $title = $attach['name'];
							
							//Calcolare anteprima e video full!!!!
							if($attach['ext'] == 'mp3') { $path = '/img/icon_black.png'; $type = 'audio'; $div_title = 'Ascolta audio'; }
							else 						{ $path = $thumbnail->frame_link(array('path' => $attach['path'], 'w' => 150,'h' => 110, 'q' => 100, 'zc' => 1, 'f' => 'jpg')); $type = 'video'; $div_title = 'Visualizza video';}
							
						?>
						
							<? if($isiPad): ?>
							
								<? if($attach['ext'] == 'mp4'): ?>
								
								<video width="150" height="110" src="<?=$attach['path'];?>" controls="controls" x-webkit-airplay="allow">
									<source src="<?=$attach['path'];?>"></source>
								</video>	

								<? endif; ?>
							
							<? else: ?>
							
							<div class="play-video">
								<a class="is_video type_<?=$type;?>" rel="timmygallery" title="<?=$title;?>" href="javascript:;" link="<?=$attach['path'];?>">
									<?php echo $this->Html->image("icon-play-".$type.".png", array( "alt" => "Play " . $type, 'class' => 'play-icon')); ?>
									<img src="<?=$path;?>" alt="<?=$title;?>"/>
									<span class="timmy_description"><?=$attach['description'];?></span>
								</a>
							</div>	
							
							<? endif; ?>
							
						<? endif; ?>
							
						<? endforeach; ?>
						
							<div class="clear"></div>
							
						</div><!-- close documents-container -->
					</div> <!-- close contentents-files-documents -->
					
<? endif; ?>
					
					</div><!-- close contents-text -->	
					</div><!-- close contents-box-left -->

					<div class="contents-box-right">
						<? /*
							<div class="contents-box-right-container">
								<ul class="preview-page">
									<li><a href="javascript:history.back()" title="pagina precedente">pagina precedente</a></li>
								</ul>
							</div>
							
						 */ ?>		
						<div class="categories contents-box-right-container"><!-- il blocco dei tags prende l'id categories -->
								<h3><?=$data['Page']['Genitore'];?></h3>
								<? if(count($data['Brothers'])): ?>
								<ul>
									<? foreach($data['Brothers'] as $page): ?>
									<? $url = $this->requestAction('/pages/getPageUrl/' . $page['Page']['id']); ?>
									<li <? if($page['Page']['id'] == $data['Page']['id']) echo 'class="selected"'; ?>>
										<a href="<?=$url;?>" title="<?=$page['Page']['title'];?>">
											<?=$page['Page']['title'];?>
										</a>
										<? if($page['Page']['id'] == $data['Page']['id']): ?>
											
											<?
												
												//Check explose block
												$explose_count = array();
												foreach($page['Block'] as $k => $blk) {
													if($blk['type'] == 1 && $blk['published'] == '0000-00-00 00:00:00') $explose_count[] = $page['Block'][$k];
												}
											
											?>
											<? if(count($explose_count)): ?>
											
											<ul>
											
												<? foreach($explose_count as $blk): ?>
												
													<li <?if($blk['id'] == $data['Block']['id'] && $blk['published'] == '0000-00-00 00:00:00'):?>class="selected"<?endif;?>> 
														<a href="/blocchi/<?=$blk['id'];?>/<?=strtolower(Inflector::Slug($blk['title'],'-'));?>" title="<?=$blk['title'];?>">
															<?=$blk['title'];?>
														</a>													
													</li>
												
												<? endforeach; ?>
											
											</ul>
											
											<? endif; ?>
											
										<? endif; ?>										
									</li>
									<? endforeach; ?>
								</ul>
								<? endif; ?>
							</div>
							
							<?/*
							
							<div class="contents-box-right-container"><!-- ognuno di questi blocchi va stampato solo se esiste -->
								<h3>Sharing</h3>
								<ul class="share-menu">
								<!-- Example social sharing <img src="/img/website/sharing.png" alt="" /> -->
								<? $pageLink = 'http://' . $_SERVER['HTTP_HOST'] . '/blocchi/' . $data['Block']['id'] . '/' . strtolower(Inflector::Slug($data['Block']['title'],'-')) ;?>
								<li class="fb-share"><!-- Facebook share button -->
								<iframe src="http://www.facebook.com/plugins/like.php?app_id=147431638679678&amp;href=<?=$pageLink;?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:90px;" allowTransparency="true"></iframe>
								</li><!-- Facebook share button end -->
								<li class="twt-share"><!-- Twitter button -->
								<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?=$pageLink;?>" data-counturl="<?=$pageLink;?>" data-count="vertical" data-lang="it">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
								</li><!-- Twitter button end -->
								<li class="g-share"><!-- Google button -->
								<g:plusone size="tall" href="<?=$pageLink;?>"></g:plusone>
								</li><!-- Google button end -->
								</ul>
							</div><!-- contents-box-right-container -->							
							
							*/?>

							<?/*

							<!-- contents-box-right-container -->					
							
							<!-- Tag 
							<div class="contents-box-right-container" id="tags">
								<h3>Tags</h3>
								<ul>
									<li><a href="#" title="Nullam">Nullam</a></li>
									<li><a href="#" title="elementum eu fermentum">elementum eu fermentum</a></li>
									<li><a href="#" title="interdum diam">Ninterdum diamullam</a></li>
									<li><a href="#" title="posuere quis">posuere quis</a></li>
									<li><a href="#" title="quam sapien">quam sapien</a></li>
									<li><a href="#" title="egestas sapien">egestas sapien</a></li>
									<li><a href="#" title="turpis ipsum">euismod mauris</a></li>
									<li><a href="#" title="quis elit magna">quis elit magna</a></li>
								</ul>
							</div>
							-->
							
							*/ ?>

					</div><!-- contents-box-right -->
					
					<div class="clear"></div>
					
			</div><!-- close contents-box -->
		</div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->





<div class="content-body">




</div>
