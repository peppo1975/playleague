		<link rel="stylesheet" href="/vendor/theme.admin.extension.css">

		<link rel="stylesheet" href="/vendor/theme.extension.css">

<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="" ><a href="/impianti">Impianti</a></li>
						<li class="active" href="" ><?=$data[0]['Campi']['Descrizione'];?></li>

					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
		

<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
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

<div class="wrapper-box col-md-12">
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
				
				$address = str_replace("<br>","&nbsp;&nbsp;",$address);
				$address = str_replace("<br />","&nbsp;&nbsp;",$address);
				
				?>
				
				<div class="content-header">
				
				<div class="post-content">
				
				<h2><?=$data['Campi']['Descrizione'];?></h2>
				<?if(!empty($strip_address)):?><p class="lead"><?=$address;?></p><?endif;?>
								<?if(!empty($strip_type)):?>   <label class="label label-info"><?=$type_campo;?></label><?endif;?>

				</div>
				<hr />
					<? if(!empty($strip)): ?>
										<?=$data['Campi']['descrizione_campo'];?>	
									<? endif; ?>
								
				
				<div class="clear"></div>
				
				</div>
				
							<div class="tabs">
								<ul class="nav nav-tabs">


									<? if($data['Campi']['countHour'] > 0): ?>

									<li class="active">
										<a href="#popular" data-toggle="tab">Prenotazioni</a>
									</li>
									<? endif; ?>
									<? if($data['Campi']['latitudine'] != '' && $data['Campi']['longitudine'] != ''): ?>	

									<li>
										<a href="#recent" data-toggle="tab" <? if($data['Campi']['countHour'] == 0): ?>class="active"<?endif;?>>Mappa</a>
									</li>
								<? endif; ?>
								</ul>
								<div class="tab-content">
									<div id="popular" class="tab-pane <? if($data['Campi']['countHour'] > 0): ?>active<?endif;?>">
														
									<? if($data['Campi']['countHour'] > 0): ?>
										<?=$this->element('/site/impianti/booking');?>
									<? endif; ?>	

									</div>
									<div id="recent" class="tab-pane <? if($data['Campi']['countHour'] == 0): ?>active<?endif;?>">
									

									<? $data['Campi']['info']=$data['Campi']; ?>
									<?=$this->element('/site/google_maps_impianti',$data['Campi']);?>

									</div>
								</div>
							</div>



					

								<div class="contents-block-left">
								<div class="contents-text">
	
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

		$("body").addClass('impianti');

		$("a[data-toggle]").click(function() {
setTimeout(function() {
	google.maps.event.trigger(map, 'resize');

var lng = new google.maps.LatLng('<?=$data['Campi']['latitudine'];?>', '<?=$data['Campi']['longitudine'];?>');

  
    //  if (infowindow) infowindow.close();


map.setCenter(lng);

$("#map").hide();

setTimeout(function() {

$("#map").show();
google.maps.event.trigger(map, 'resize');
map.setCenter(lng);


},100);


},1000);
		});
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

					</div>
					</div>

					</div>
					<div class="contents-box-right col-md-3" style="display: none;">
					
					</div><!-- contents-box-right -->
					
					<div class="clear"></div>
					
			</div><!-- close contents-box -->
		</div><!-- close wrapper-box-contents -->
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->

