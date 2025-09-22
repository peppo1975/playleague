
	<? if(!empty($slider)): ?>

		<?/*
		<div class="slide-image-home">
			<ul class="slide-product image-slider">
			
				<? foreach($slider as $k => $slide): ?>
			
				<li data-index="<?=$k;?>">
					<img src="<?=$thumbnail->link(array('path' => $slide['Upload']['path'], 'w' => 980, 'h' => 400, 'zc' => 1));?>" alt="<?=$slide['Upload']['title'];?>" />
				</li>
				
				<? endforeach; ?>
				
			</ul>	
			
		</div>
		*/?>
		
		<? if(count($slider) > 1): ?>
		
		<script type="text/javascript">
		
			$(document).bind("orientationchange", function(event, data){
				
				$('.iosSlider').iosSlider('update');
				location.reload();
				
			});			
		
			$(document).ready(function() {
			
				$('.iosSlider').iosSlider({
					elasticPullResistance: 1,
					frictionCoefficient: 0,
					responsiveSlides: false,
					snapToChildren: true,
					desktopClickDrag: true,
					infiniteSlider: true,
					onSlideStart: function() {
						
						$('.iosSlider').iosSlider('lock');
						
					},
					onSlideChange: function(slider) {
						
						var n = slider.currentSlideNumber;
						$('.slide-ul').find('li').removeClass('selected');
						$('.slide-ul').find('li[data-index='+n+']').addClass('selected');
						
					}
				});
			
			});		
		
		</script>
		
		<? endif; ?>

		<script type="text/javascript">
	
			(function(window, $, PhotoSwipe){
				
				$(document).ready(function(){
					
					var options = {
							
						'allowRotationOnUserZoom' : true,
						'jQueryMobileDialogHash': 'gallery-photo'
							
					};
					$("a.image-photoswipe").photoSwipe(options);
				
				});
				
			}(window, window.jQuery, window.Code.PhotoSwipe));
								
		</script>
		
		<!-- slider container -->
		<div class="iosSlider">
		
			<!-- slider -->
			<div class="slider">
			
				<? foreach($slider as $k => $slide): ?>
			
				<div class="slide" data-index="<?=$k;?>">
				
					<a class="image-photoswipe" target="foo" href="<?=$thumbnail->link(array('path' => $slide['Upload']['path'], 'w' => 1200));?>" title="<?=$slide['Upload']['title'];?>">
						<img src="<?=$thumbnail->link(array('path' => $slide['Upload']['path'], 'w' => 640, 'h' => 360, 'zc' => 1, 'q' => 100));?>" alt="<?=$slide['Upload']['title'];?>" />
					</a>
					
				</div>
				
				<? endforeach; ?>				
				
			</div>
		
		</div>
		
		<? if(count($slider) > 1): ?>
			
			
			<div class="slide-round-button">
				<ul class="slide-ul" style="width: <?=17*count($slider);?>px;">
				
					<? foreach($slider as $k => $slide): ?>
				
						<li class="<? if($k == 0): ?>selected<? endif; ?>" data-index="<?=$k;?>">
							<a title="<?__('Immagine');?> <?=($k+1);?>"></a>
						</li>
					
					<? endforeach; ?>
					
				</ul>
			</div>	
		
		<? endif; ?>				
			
	<? endif; ?>