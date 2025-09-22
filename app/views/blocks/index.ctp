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
	if($tmp['tag'] == 'link' && $tmp["type"] != "application/pdf") $countLink++;
}	
}

?>
<?
function fucking16_10izer($array)
{
	$tb = new ThumbnailHelper;

	$width = $array["w"];
	$height = 10/16*$width;
	return $tb->link(array_merge($array, ["w" => $width, "h" => $height, "zc" => 1]));


}
?>
<style>
	.img-thumbnail-spec img{
		width: 100%;
	}
	.img-thumbnail-spec{
		width: 100px;
	}
	.blog-posts article{
		border: 0 !important;
	}
	.post-meta a:not(.btn){
		color: #0088cc !important;
	}
</style>

		<style>
			article.post-large .post-image, article.post-large .post-date {
		    	margin-left: 0;
			}
			article.post-large {
			    margin-left: 0;
			}
		</style>




<div role="main" class="main" >
	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li><a href="/contenuti/<?=$data['Page']['id'];?>"><?=$data['Page']['title'];?></a></li>
						<li class="active"><?=$data['Block']['title'];?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container" id="main-custom">
			
			<div class="col-md-9">
				<div class="blog-posts single-post">

					<article class="post post-large blog-single-post">

						<?php 
							//print "<pre>";print_r($data["Upload"]);exit;
							$img_evidenza = null;
							foreach($data["Upload"] as $upload)
							{
								if($upload["isEvidenza"])
									$img_evidenza = $upload;
							}

							if(!$img_evidenza)
							{
								foreach( $data["Upload"] as $upload)
								{
									if($upload["type"] == "image/jpeg" && $upload['tag'] != 'link')
									{
										$img_evidenza = $upload;
										break;
									}
								}
							}

						if($img_evidenza):
							$src = $thumbnail->link(array('path' => $img_evidenza['path'], "w" => 759)); ?>

							<div class="post-image">
									<div class="owl-carousel owl-theme" data-plugin-options='{"items":1}'>
										<div style="text-align: center">
											<div class="img-thumbnail">
												<img class="img-responsive" style="width: 100%; margin: 0 auto;" src="<?=$src?>" alt="">
											</div>
										</div>
									</div>
								</div>
						<?php endif; ?>

						

						<div class="post-content">

							<h2><a><?=$data['Block']['title'];?></a></h2>


							<?php if($data['Block']['published_it'] != '' && $data['Block']['published_it'] != '00/00/0000' && ($data['Page']['title'] == "News" || $data['Page']['title'] == "News dalla redazione" || $data['Page']['title'] == "Ultim'ora")):?>
								<p style="margin-top: -10px"><span class="label label-info">del <?=$data['Block']['published_it']?></span></p>
							<?php endif; ?>

							<!-- CONTENUTO -->

							<p>
							<? if(!empty($strip)): ?>
								<?=$data['Block']['content'];?>	
							<? endif; ?>
							</p>

							<!-- Allegati pdf -->
							<? if($countDocuments > 0): ?>
								<div class="post-meta">
										<? foreach($data['Upload'] as $attach): ?>
											<? if(in_array($attach['ext'], $ext_doc)): ?>
												<?	
													if($attach['title'] != '') $title = $attach['title'];
													else					   $title = $attach['name'];
												?>
												<span>
													<a href="/files/uploads/<?=$attach['name'];?>" title="<?=$title;?>">
														<span class="icon"><img src="/img/website/icon-pdf.png" alt=""></span> <?=$title;?>
													</a>
												</span>
											<? endif; ?>
										<? endforeach; ?>
								</div> <!-- close contentents-files-documents -->
							<? endif; ?>
							
							<? if($countVideos > 0): ?>
								<hr>
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
												else 						{ $path = fucking16_10izer(array('path' => $attach['path'], 'w' => 150*2,'h' => 93*2, 'q' => 100, 'zc' => 1, 'f' => 'jpg')); $type = 'video'; $div_title = 'Visualizza video';}
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
									</div><!-- close documents-container -->
								</div> <!-- close contentents-files-documents -->
							<? endif; ?>

							<? if($countLink > 0): ?>
								<hr class="tall">
								<div style="margin-left: 40px !important; margin-right: 40px !important">
									<div class="row">

										<?php $i = 1; foreach($data['Upload'] as $attach): ?>
												<?php if($attach['tag'] == 'link' && $attach["type"] != "application/pdf"): 
														if($attach['title'] != '') $title = $attach['title'];
														else					   $title = $attach['name'];
														$path = fucking16_10izer(array('path' => $attach['path'], 'w' => 100,'q' => 100));?>

													<?php if(substr($attach['description'],0,7) == 'http://' && strlen($attach['description']) > 7): ?>
														<div class="col-md-3">	
															<a href="<?=$attach['description'];?>" title="<?=$title;?>">
																<span class="thumb-info" style="width: 116px; height: 72px; padding: 4px;">
																	<div style="height: 100%; width: 100%; text-align: center; background-image: url('<?=$path?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
																		<span class="thumb-info-wrapper" >
																			<span class="thumb-info-action">
																				<span class="thumb-info-action-icon"><i class="fa fa-link"></i></span>
																			</span>
																		</span>
																	</div>
																</span>
															</a>
														</div>

													<?php else: ?>
														<div class="col-md-3">	

																<span class="thumb-info" style="width: 116px; height: 72px; padding: 4px;">
																	<div style="height: 100%; width: 100%; text-align: center; background-image: url('<?=$path?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
																		
																	</div>
																</span>

														</div>

													<?php endif; ?>
													<?php if($i++%4 == 0): ?>
														<div class="col-xs-12">&nbsp;</div>
													<?php endif; ?>
												<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
							<? endif; ?>
							<!-- Galleria -->
							<? if($countMultimedia > 0): ?>
								<?=$this->element("site/slider",array('countMultimedia' => $countMultimedia,'data' => $data,'ext_img' => $ext_img));?>
							<? endif; ?>

							<?if($data['Block']['published_it'] != '' && $data['Block']['published_it'] != '00/00/0000'):?>
								
								<!-- <div class="post-block post-share">
									<h3 class="heading-primary"><i class="fa fa-share"></i>Condividi articolo</h3>
									<div class="addthis_native_toolbox"></div>

								</div> -->
								<!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56656153fe7d9823" async="async"></script> -->

		
							<? endif; ?>
						</div><!-- post content -->
					</article>
				</div>
			</div><!-- col 9 -->
			<!-- MENU DI DESTRA -->
			<div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary"><?=$data['Page']['Genitore'];?></h4>
						<? if(count($data['Brothers'])): ?>
							<ul class="nav nav-list mb-xlg">
								<? foreach($data['Brothers'] as $page): ?>
									<? $url = $this->requestAction('/pages/getPageUrl/' . $page['Page']['id']); ?>
									<li <? if($page['Page']['id'] == $data['Page']['id']) echo 'class="active"'; ?>>
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
															<li <?if($blk['id'] == $data['Block']['id'] && $blk['published'] == '0000-00-00 00:00:00'):?>class="active"<?endif;?>> 
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
				</aside>
			</div>

	</div>






