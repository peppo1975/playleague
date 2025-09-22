<? /*

<? if(isset($upload) && !empty($upload)): ?>

	<script type="text/javascript">
	
		$(function(){
		
			$.getScript('/js/jquery.bxslider/jquery.bxslider.min.js', function(){
		
				$('.slider').bxSlider({
				
					adaptiveHeight : true,
					controls: false,
					pager: false,
					adaptiveHeightSpeed : 200
				
				});
			
			});
		
		});
	
	</script>

	<ul class="slider">
	
		<? foreach($upload as $attach): ?>
		
			<? if(isset($ext_img) && in_array($attach['ext'], $ext_img) && $attach['tag'] != 'link') { ?> 
		
			<li>
				<img width="100%" src="<?=$thumbnail->link(array('path' => $attach['path'], 'w' => 600, 'h' => 300, 'q' => 100));?>" />
			</li>
			
			<? } ?>
		
		<? endforeach; ?>
	
	</ul>

<? endif; ?>

*/ ?>

	<? 
	
		$id_block = uniqid();
		
	?>

	<script type="text/javascript">

		(function(window, $, PhotoSwipe){
			
			$(document).ready(function(){
				
				var options = {
						
					'allowRotationOnUserZoom' : true,
					'jQueryMobileDialogHash': 'gallery-photo'
						
				};
		
				$("#<?=$id_block;?>-gallery a").photoSwipe(options);
			
			});
			
		}(window, window.jQuery, window.Code.PhotoSwipe));
							
	</script>

	<ul class="slider block-attachment attachment-link" id="<?=$id_block;?>-gallery">
		<? $ext_img = array('jpg','jpeg','png','gif','bmp'); ?>
		<? foreach($upload as $attach): ?>
		
			<? if(isset($ext_img) && in_array($attach['ext'], $ext_img) && $attach['tag'] != 'link') { ?> 
		
			<li>
				<a target="foo" href="<?=$thumbnail->link(array('path' => $attach['path'], 'w' => 1200));?>" title="<?=$attach['title'];?>">				
					<img class="timmy-lazy" src="<?=$thumbnail->link(array('path' => $attach['path'], 'w' => 200, 'h' => 80, 'zc' => 1, 'q' => 100));?>" />
				</a>
			</li>
			
			<? } ?>
		
		<? endforeach; ?>
	
	</ul>