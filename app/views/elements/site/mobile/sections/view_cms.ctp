<div class="container-detail-post">
	
	<div class="header-post-detail">
		<h1><?=$section['Section']['title'];?></h1>
	</div>
	
	<div class="container-detail-post">
		<?=$section['Section']['content'];?>
	</div>
	
		<? if(!empty($section['UploadDoc']) || !empty($section['UploadImg'])): ?>
		
		<div class="allegati">
		
			<? if(!empty($section['UploadImg'])): ?>
		
			<div class="allegati-img">
				<script type="text/javascript">
			
					(function(window, $, PhotoSwipe){
						
						$(document).ready(function(){
							
							var options = {
									
								'allowRotationOnUserZoom' : true,
								'jQueryMobileDialogHash': 'gallery-photo'
									
							};
							$("#<?=$section['Section']['id'];?> a").photoSwipe(options);
						
						});
						
					}(window, window.jQuery, window.Code.PhotoSwipe));
										
				</script>			
				<ul id="<?=$section['Section']['id'];?>">
				
					<? foreach($section['UploadImg'] as $k => $upload): ?>
					
					<li <? if(($k+1)%4 == 0): ?>class="no-margin"<? endif; ?>>
						
						<? $tmp 	= array(); $tmp[] = $upload; ?>
					
						<? $img 	= getPreview($tmp, array('width' => 62, 'height' => 62, 'zc' => 1)); ?>	
						<? $img_big = getPreview($tmp, array('width' => 960, 'height' => 960, 'zc' => 0)); ?>			
						
						<a target="foo" href="<?=($upload['group'] == 'image')? $img_big['path'] : '/uploads/view/' . $upload['id'];?>" <?=($upload['group'] == 'image')? 'rel="cms_gallery"' : 'rel="cms_gallery_video"';?> title="<?=($upload['title'] != '')? $upload['title'] : $upload['name'];?>">
							<img alt="<?=($upload['title'] != '')? $upload['title'] : $upload['name'];?>" src="<?=$img['path'];?>">
						</a>											
						
					</li>
					
					<? endforeach; ?>
					
				</ul>
			</div>
			
			<? endif; ?>
			
			<? if(!empty($section['UploadDoc'])): ?>
			
			<div class="allegati-file">				
				<ul>
				
					<? foreach($section['UploadDoc'] as $upload): ?>
				
					<li class="document-<?=$upload['ext'];?>">
						<a target="foo" href="<?=$upload['path'];?>" title="<?=($upload['title'] != '')? $upload['title'] : $upload['name'];?>"><?=($upload['title'] != '')? $upload['title'] : $upload['name'];?></a>
					</li>
					
					<? endforeach; ?>
					
				</ul>
			</div>
			
			<? endif; ?>
			
		</div>	
		
		<? endif; ?>		
	
	<div class="clear"></div>	
	
</div>