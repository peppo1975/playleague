<? if (!empty($backgroundAd)): ?>
<div id="ads-layer" <? if (!empty($backgroundAd['Upload']['description'])): ?> onclick="location.href = '<?=$backgroundAd['Upload']['description'];?>'" <? endif;?> style="background-image: url(<?=$thumbnail->link(array('path' => $backgroundAd['Upload']['path'],'q' => 100, 'f' => 'png'));?>?rnd=<?=uniqid();?>); height: <?=$backgroundAd['Upload']['height'];?>px"></div>

<? if (!empty($backgroundAd['Upload']['color'])): ?>

	
	<script type="text/javascript">
	
		$(function() {

				$('body').css('background-color','<?=$backgroundAd['Upload']['color'];?>');
			
		});
	
	</script>
	

<? endif; ?>

<? endif; ?>


