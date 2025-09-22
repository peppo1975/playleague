<script src="https://maps.google.com/maps/api/js?key=AIzaSyBzSQwMS0NzVkgfFZeyUW9cOjbTDwUMjHU"></script>
<?
$data = $data[0];
//debug($data);

?>
<?

//debug($data);
$isiPad    = (bool)strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$ext       = array('flv','mp4');
$strip 	   = strip_tags($data['Campi']['descrizione_campo']); 
$ext_doc   = array('doc','xls','zip','xlsx','rar','pdf');
$ext_video = array('flv','mp4');
$ext_img   = array('jpg','jpeg','png','gif');

$mimes = array(

	'doc' => 'icon-doc.png',
	'xls' => 'icon-xls.png',
	'zip' => 'icon-zip.png',
	'xlsx' => 'icon-xls.png',
	'rar' => 'icon-zip.png',
	'pdf' => 'icon-pdf.png',

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
				<?
				$address       = (($data['Campi']['Indirizzo'] == '')? $data['Campi']['Indirizzo'] : $data['Campi']['Indirizzo'] . ' -') . ' ' . $data['Campi']['Citta'] . ' ' . (($data['Campi']['Provincia'] == '')? $data['Campi']['Provincia'] : '(' . $data['Campi']['Provincia'] . ')') . ' ' . (($data['Campi']['Telefono'] == '')? $data['Campi']['Telefono'] : '- Tel.' . $data['Campi']['Telefono']);
				if($data['Campi']['Email'] != '') $address .= '<br /><a href="mailto:'.$data['Campi']['Email'].'">' . $data['Campi']['Email'] . '</a>';
				$strip_address = ereg_replace('[^a-zA-Z0-9]','',$address);
				$type_campo = '';
				if($data['Campi']['is5'] && $data['Campi']['is7'])      $type_campo = 'Calcio a 5, calcio a 7';
				elseif($data['Campi']['is5'] && !$data['Campi']['is7']) $type_campo = 'Calcio a 5';
				elseif($data['Campi']['is7'] && !$data['Campi']['is5']) $type_campo = 'Calcio a 7';
				else													$type_campo = '';
				if($data['Campi']['isEsclusive'] == 1) $type_campo .= ' - IN ESCLUSIVA';
				$strip_type    = ereg_replace('[^a-zA-Z0-9]','',$type_campo);
				?>
				<h1><?=$data['Campi']['Descrizione'];?></h1>
				<?if(!empty($strip_type)):?>   <h2><?=$type_campo;?></h2><?endif;?>
				<?if(!empty($strip_address)):?><p class="post-info"><?=$address;?></p><?endif;?>

								<div class="contents-block-left">
								<div class="contents-text">
									<? if(!empty($strip)): ?>
										<?=$data['Campi']['descrizione_campo'];?>	
									<? endif; ?>
								
							
								<? if($data['Campi']['latitudine'] != '' && $data['Campi']['longitudine'] != ''): ?>	
									<a href="javascript:;" title="Visualizza mappa" class="open_mappa">Visualizza mappa</a>
								<? endif; ?>
								
								<? if($countLink > 0): ?>
								
								<div class="attachment-link"><!-- div foto collegamenti -->
								
								<? foreach($data['Upload'] as $attach): ?>
								
								<? if($attach['tag'] == 'link'): ?>
								
								<?
									
									if($attach['title'] != '') $title = $attach['title'];
									else					   $title = $attach['name'];
									
									$path = $thumbnail->link(array('path' => $attach['path'], 'w' => 300,'h' => 300, 'q' => 100, 'f' => 'jpg'));
									//Cambiare dimensioni file.
								
								?>								
								
								<a href="<?=$attach['description'];?>" title="<?=$title;?>">
									<img src="<?=$path;?>" alt="<?=$title;?>" />
								</a>
								
								<? endif; ?>
								
								<? endforeach;?>
								
								</div><!-- end div foto collegamenti -->
								
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
							
							$path = $thumbnail->frame_link(array('path' => $attach['path'], 'w' => 150,'h' => 110, 'q' => 100, 'zc' => 1, 'f' => 'jpg'));
							
						?>
						
							<? if($isiPad): ?>
							
								<? if($attach['ext'] == 'mp4'): ?>
								
								<video width="150" height="110" src="<?=$attach['path'];?>" controls="controls" x-webkit-airplay="allow">
									<source src="<?=$attach['path'];?>"></source>
								</video>	

								<? endif; ?>
							
							<? else: ?>
							
								<a class="is_video" rel="timmygallery" title="<?=$title;?>" href="javascript:;" link="<?=$attach['path'];?>">
								<div class="play-video">
									<?php echo $this->Html->image("icon-play-video.png", array( "alt" => "Play video")); ?>
								</div>
									<img src="<?=$path;?>" alt="<?=$title;?>"/>
									<span class="timmy_description"><?=$attach['description'];?></span>
								</a>
								
							<? endif; ?>
							
						<? endif; ?>
							
						<? endforeach; ?>
						
							<div class="clear"></div>
							
						</div><!-- close documents-container -->
					</div> <!-- close contentents-files-documents -->
					
<? endif; ?>

<? if($data['Campi']['latitudine'] != '' && $data['Campi']['longitudine'] != ''): ?>
	
	<script type="text/javascript">
	$(function(){
		$('.open_mappa').click(function(){
			$.post('/campis/saveMapsSession', {
				'Nome':'<?=$data['Campi']['Descrizione'];?>',
				'latitudine':'<?=$data['Campi']['latitudine'];?>',
				'longitudine':'<?=$data['Campi']['longitudine'];?>',
				'indirizzo':'<?=$data['Campi']['Indirizzo'];?>',
				'citta':'<?=$data['Campi']['Citta'];?>',
				'provincia':'<?=$data['Campi']['Provincia'];?>',
				'telefono':'<?=$data['Campi']['Telefono'];?>',
				'email':'<?=$data['Campi']['Email'];?>',
			}, function(){
			
				timmy_load('/campis/maps');
			
			});
		});
	});
	</script>


<? endif; ?>
					
					</div><!-- close contents-text -->	
					</div><!-- close contents-box-left -->

					<div class="contents-box-right">
					
							<div class="contents-box-right-container"><!-- ognuno di questi blocchi va stampato solo se esiste -->
								<ul class="preview-page">
									<li><a href="javascript:history.back()" title="pagina precedente">pagina precedente</a></li>
								</ul>
							</div><!-- contents-box-right-container -->		

							<div class="contents-box-right-container"><!-- ognuno di questi blocchi va stampato solo se esiste -->
								<h3>Sharing</h3>
								<ul class="share-menu">
								<!-- Example social sharing <img src="/img/website/sharing.png" alt="" /> -->
								<? $pageLink = 'https://' . $_SERVER['HTTP_HOST'] . '/impianti/' . $data['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($data['Campi']['Descrizione'],'-')) ;?>
								<li class="fb-share"><!-- Facebook share button -->
								<iframe src="https://www.facebook.com/plugins/like.php?app_id=147431638679678&amp;href=<?=$pageLink;?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:90px;" allowTransparency="true"></iframe>
								</li><!-- Facebook share button end -->
								<li class="twt-share"><!-- Twitter button -->
								<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$pageLink;?>" data-count="vertical" data-lang="it">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
								</li><!-- Twitter button end -->
								<li class="g-share"><!-- Google button -->
								<g:plusone size="tall" href="<?=$pageLink;?>"></g:plusone>
								</li><!-- Google button end -->
								</ul>
							</div><!-- contents-box-right-container -->							
					
							<div class="contents-box-right-container categories"><!-- il blocco dei tags prende l'id categories -->
								<h3>Impianti</h3>
								<? if(count($campis)): ?>
								<ul>
									<? foreach($campis as $campo): ?>
									<?
									$block_link    = '/impianti/' . $campo['Campi']['Campo'] . '/' . strtolower(Inflector::Slug($campo['Campi']['Descrizione'],'-'));
									if($campo['Campi']['descrizione_campo'] == '' && $campo['Upload'] == array()) $block_link = "";											
									?>
											
											<? if($block_link != ''): ?>
											
											<li <? if($campo['Campi']['Campo'] == $data['Campi']['Campo']) echo 'class="selected"'; ?>>
											
											<a href="<?=$block_link;?>" title="<?=$campo['Campi']['title'];?>">
												<?=$campo['Campi']['title'];?>
											</a>
											
											</li>
											
											<? else: ?>
											
												<li class="categories-no-link"><?=$campo['Campi']['title'];?></li>
											
											<? endif; ?>
										</li>
									<? endforeach; ?>
								</ul>
								<? endif; ?>
							</div><!-- contents-box-right-container -->					
							
							<!-- tag
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

					</div><!-- contents-box-right -->
					
					<div class="clear"></div>
					
			</div><!-- close contents-box -->
		</div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->

