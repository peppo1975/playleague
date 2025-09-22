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

$countDocuments  = 0;
$countMultimedia = 0;
$countVideos     = 0;
$countLink       = 0;
$countIpad       = 0;
if(isset($block['Upload']) && count($block['Upload'])) {
//Check documents and media upload
foreach($block['Upload'] as $tmp){
	if(in_array($tmp['ext'], $ext_doc)) $countDocuments++;
	if(in_array($tmp['ext'], $ext_img) && $tmp['tag'] != 'link') $countMultimedia++;
	if(in_array($tmp['ext'], $ext_video)) $countVideos++;
	if($tmp['ext'] == 'mp4') $countIpad++;
	if($tmp['tag'] == 'link' && !in_array($tmp['ext'], $ext_doc)) $countLink++;
}	
}

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
			<a data-ajax="false" href="/mobile/view/<?=$data['Page']['id'];?>/<?=strtolower(Inflector::Slug($data['Page']['title'],'-'));?>" title="<?=$data['Page']['title'];?>">
				<?=$data['Page']['title'];?> 
			</a>
			&rsaquo;
		</li>
	
		<li>
			<?=$block['Block']['title'];?>
		</li>
		
	</ul>
	
</div>

<h2><?=$block['Block']['title'];?></h2>

<? if($block['Block']['content'] != ""): ?>

	<div class="page-content">
	
		<?=$block['Block']['content'];?>
	
	</div>

<? endif; ?>

<? if($countLink > 0): ?>

	<ul data-link="<?=$countLink;?>" class="block-attachment attachment-link"><!-- div foto collegamenti -->
	
	<? foreach($block['Upload'] as $attach): ?>
	
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
		<img class="timmy-lazy" src="<?=$path;?>" alt="<?=$title;?>" />
	</a>
	
	<? else: ?>
	
		<img class="timmy-lazy" src="<?=$path;?>" alt="<?=$title;?>" />
	
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

<? if($countMultimedia > 0): ?>

<?=$this->element('site/mobile/slider', array('upload' => $block['Upload'], 'ext_img' => $ext_img));?>

<? endif; ?>