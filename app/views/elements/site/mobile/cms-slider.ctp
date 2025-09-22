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
	
		<? foreach($upload as $upload): ?>
		
			<li>
				<img width="100%" src="<?=$thumbnail->link(array('path' => $upload['path'], 'w' => 600, 'q' => 100));?>" />
			</li>
		
		<? endforeach; ?>
	
	</ul>

<? endif; ?>