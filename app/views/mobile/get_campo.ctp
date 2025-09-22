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

			<div class="contents-box campo-box">
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
				
				<div class="content-header">
				
				<div class="content-title">
				
				<h1><?=$data['Campi']['Descrizione'];?></h1>
				<?if(!empty($strip_type)):?>   <h2><?=$type_campo;?></h2><?endif;?>
				<?if(!empty($strip_address)):?><p class="other-info"><?=$address;?></p><?endif;?>

				<div class="clear"></div>
				
							
				</div>

								<div class="contents-block-left">
								<div class="contents-text">
									<? if(!empty($strip)): ?>
										<?=$data['Campi']['descrizione_campo'];?>	
									<? endif; ?>
								
							
								<ul class="content-options">
								
								<!--
								
								<li>
							
								<? if($data['Campi']['latitudine'] != '' && $data['Campi']['longitudine'] != ''): ?>	
									<a href="javascript:;" title="Visualizza mappa" class="open_mappa">Visualizza mappa</a>
								<? endif; ?>
								
								</li>
								
								-->
								
								<? if($data['Campi']['countHour'] > 0): ?>
														
									<li>
										<a data-role="button" href="javascript:;" title="Prenota" class="js_booking">Prenota</a>
									</li>
								
								<? endif; ?>

								</ul>

								<? if($data['Campi']['countHour'] > 0): ?>
								<?=$this->element('/site/mobile/booking');?>
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
				
<?=$this->element("site/mobile/slider",array('upload' => $data['UploadImg']));?>				

									
								

					</div><!-- close contents-box-left -->

