<script type="text/javascript">

$(document).bind('pageinit', function() {
	$(window).scrollTop(0);
	$('body').trigger('create').trigger('refresh');

});​

</script>
<?


$isiPad    = (bool)strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$ext       = array('flv','mp4');
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

?>

<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			<a data-ajax="false" href="/mobile/categories/<?=$parent['Page']['id'];?>/<?=strtolower(Inflector::Slug($parent['Page']['title'],'-'));?>" title="<?=$parent['Page']['title'];?>">
				<?=$parent['Page']['title'];?>
			</a>
			&rsaquo;
		</li>
		<li>
			<?=$data['Page']['title'];?> 
		</li>
		
	</ul>
	
</div>

<? if(isset($blocks) && !empty($blocks)): ?>

	<? if(in_array($data['Page']['id'], Configure::read('id_news'))) {
		
		$blocks = Set::Sort($blocks,'{n}.Block.published','DESC');
		
	} ?>
	
	<? if(in_array($data['Page']['id'], Configure::read('id_news'))): ?>
	
	<? $menu = $blocks; $page = $data; ?>
	<? $nrows = 10; ?>
	<? $crows = 0; ?>
	<? $cpages = 0; ?>

	<ul data-inset="true" data-role="listview" data-theme="a">
		<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			<?=$data['Page']['title'];?>
		</li>	
		<? foreach($menu as $id => $m) : ?>
			<? if ($m['Block']['disabled'] == 0): ?>
			<? if(strtotime($m['Block']['published']) > strtotime(date("Y-m-d"))): ?>		
		
			<li data-page="<?=$cpages;?>" <? if ($cpages > 0): ?>style="display: none;"<? endif; ?>>
				<a class="news-link" data-ajax="false" data-cache="false" href="/mobile/view/<?=$page['Page']['id'];?>/<?=strtolower(Inflector::Slug($page['Page']['title'],'-'));?>/<?=$m['Block']['id'];?>/<?=strtolower(Inflector::Slug($m['Block']['title'],'-'));?>" title="<?=$m['Block']['title'];?>">
	
					<? if($m['Block']['published_it'] != "" && $m['Block']['published_it'] != "00/00/0000"): ?>
					
					<span class="news-data"><?=$m['Block']['published_it'];?></span><br /> 
					
					<? endif; ?>
					
					<span class="news-title"><?=$m['Block']['title'];?></span>
				</a>
			</li>
		
			<? 
				$crows++;
				
				if ($crows == $nrows) {
					$crows = 0;
					$cpages++;
				}
			?>
			<? endif; ?>
		<? endif; ?>
		<? endforeach; ?>			
	</ul>		
	
	<? if (ceil(count($menu)/$nrows) > 1): ?>
	<a href="javascript:;" class="show-more-news" data-role="button" data-page="0" data-max-page="<?=ceil(count($menu)/$nrows);?>">mostra altri</a>
	
	<script type="text/javascript">
		
		$(document).bind('pageinit',function() {
		
				$(".show-more-news").bind('click',function() {
					
		
						var cur_page = parseInt($(this).attr('data-page'));
						var max_page = parseInt($(this).attr('data-max-page'));
						
						cur_page++;
						
						$("li[data-page=" + cur_page + "]").show();
						
						$(this).attr('data-page',cur_page);
						
						if (cur_page == max_page-1) $(this).hide();
		
				
				});
		
		});
		
	</script>
	<? endif; ?>
	
	<? else: ?>

	<ul data-inset="true" data-role="listview" data-theme="a">
		<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			<?=$data['Page']['title'];?>
		</li>	
		<? foreach($blocks as $id => $block) : ?>
		
			<li <? if($block['Block']['type'] == 0): ?>class="block-show-all"<? endif; ?>>
			
				<? if($block['Block']['type'] == 1): ?>
			
				<a data-ajax="false" href="/mobile/view/<?=$data['Page']['id'];?>/<?=strtolower(Inflector::Slug($data['Page']['title'],'-'));?>/<?=$block['Block']['id'];?>/<?=strtolower(Inflector::Slug($block['Block']['title'],'-'));?>" title="<?=$block['Block']['title'];?>">
					<?=$block['Block']['title'];?>
				</a>
				
				<? else: ?>
				
					<h2><?=$block['Block']['title'];?></h2>
					
					<? if($block['Block']['content'] != ""): ?>
					
						<div class="page-content">
						
							<?=$block['Block']['content'];?>
						
						</div>
					
					<? endif; ?>
					
					<? if(count($block['Upload'])): ?>
					<? $countDocuments_blocks = 0; ?>
					<? $countImg_blocks = 0; ?>
					<? 
					foreach($block['Upload'] as $attach) {
						if(in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link') $countImg_blocks++;
						if(in_array($attach['ext'], $ext_doc)) $countDocuments_blocks++;
					}
					?>
					
					<? if($countImg_blocks > 0): ?>
					
					<div class="contentents-link-documents">
					
					<ul class="block-attachment gruppo" id="<?=$block['Block']['id'];?>">
					
					<? foreach($block['Upload'] as $attach): ?>
					
						<? if(in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link'): ?>
					
						<li>
								<?
								
								$isVideo = 1;
								$link    = $thumbnail->link(array('path' => $attach['path'], 'w' => 212,'h' => 80));
								$path    = $attach['path'];
								
								$title   = ($attach['title'] != '')? $attach['title']:$attach['name'];
								
								$href = ($attach['description'] != '')? $attach['description'] : 'javascript:;';
								
								?>
							
							<? if(substr($attach['description'],0,7) == 'http://' && strlen($attach['description']) > 7): ?>
							
							<a data-ajax="false" href="<?=$href;?>" title="<?=$title;?>">
								<img src="<?=$link;?>" alt="<?=$title;?>" />
							</a>
							
							<? else: ?>
							
								<img src="<?=$link;?>" alt="<?=$title;?>" />
							
							<? endif; ?>						
			
						</li>
						
						<? endif; ?>
						
					<? endforeach; ?>
					
					</ul>
					
					</div>
					
					<? endif; ?>
					
					<? if($countDocuments_blocks > 0): ?>
					
					<div class="contentents-files-documents">

						<div class="documents-container">
						
						<? foreach($block['Upload'] as $attach): ?>
						
						<? if(in_array($attach['ext'], $ext_doc)): ?>
						
						<?
							
							if($attach['title'] != '') $title = $attach['title'];
							else					   $title = $attach['name'];
						
						?>
						
							<div class="attached-document">
								<a data-ajax="false" href="/files/uploads/<?=$attach['name'];?>" title="<?=$title;?>">
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
					
					<? endif; ?>									
				
				<? endif; ?>				
			</li>
		
		<? endforeach; ?>			
	</ul>		
	
	<? endif; ?>
	
<? else: ?>

</script>
<?

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
	if($tmp['tag'] == 'link' && !in_array($tmp['ext'], $ext_doc)) $countLink++;	
}	
}

?>

	<h2><?=$data['Page']['title'];?></h2>
	
	<? if($data['Page']['content'] != ""): ?>
	
	<div class="page-content">
	
		<?=$data['Page']['content'];?>
	
	</div>
	
	<? endif; ?>
	
	<? if($countLink > 0): ?>
	
	<ul class="attachment-link block-attachment"><!-- div foto collegamenti -->
	
	<? foreach($data['Upload'] as $attach): ?>
	
	<? if($attach['tag'] == 'link'): ?>
	
	<?
		
		if($attach['title'] != '') $title = $attach['title'];
		else					   $title = $attach['name'];
		
		$path = $thumbnail->link(array('path' => $attach['path'], 'w' => 212,'h' => 80));
	
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
		<a data-ajax="false" href="<?=$attach['path'];?>" title="<?=$title;?>">
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
	
	<?=$this->element('site/mobile/slider', array('upload' => $data['Upload'], 'ext_img' => $ext_img));?>
	
	<? endif; ?>

<? endif; ?>