	<!--

	Richiamato da last_section.ctp

	-->
	
	<script type="text/javascript">
	/*
	$(function(){
		$('.bannerFlash').click(function(e){
		
			e.stopPropagation();
			e.preventDefault();
		
			if($(this).attr('data-url') != '') {
				window.open($(this).attr('data-url'),'_blank');
			}
		});
	});
*/
	</script>	

	<? 

		$banners = $this->requestAction('/banners/getBannersRow');

	?>

	<? if(count($banners)): ?>

	<? foreach($banners as $i => $row): ?>
	
	<? if(count($row['Banner'])): ?>

	<div class="banner-box  <? if ($i==0): ?> no-margin-top <? endif; ?>">
	
<div class="row content-grid-row">
	
		<? $f=0;foreach($row['Banner'] as $banner): if($banner['disabled']) continue; ?>
		<? if(!$f%3): ?>
		
		<? endif; ?>
		<div class="content-grid-item col-md-4 center">
			<div class="<?=strtolower($banner['Tipo']);?>-banner">

			
			<?
			
				switch($banner['banner_ext']) {
				
					case 'gif':
					
						if($banner['Link'] != '') 
					
						$tag = '
								<a target="_blank" href="'.$banner['Link'].'" title="'.$banner['Titolo'].'">
									<img class="img-responsive" src="'.$banner['banner'].'" width="'.$banner['width'].'" height="100" />
								</a>						
							   ';
						else
						
						$tag = '
								<img class="img-responsive" src="'.$banner['banner'].'" width="'.$banner['width'].'" height="100" />						
							   ';						
						
					break;
					
					case 'swf':
						$click_div = '<div class="bannerFlash" style="cursor:pointer; width:'.$banner['width'].'px; height:100px; position: absolute;" data-url="'.$banner['Link'].'"></div>';
						$tag = '
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
									codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
									width="'.$banner['width'].'" height="100">

							  <param name="movie" value="'.$banner['banner'].'">
							  <param name="quality" value="high">
							  <embed src="'.$banner['banner'].'" quality="high" width="'.$banner['width'].'" height="100" 
									 type="application/x-shockwave-flash" 
									 wmode="transparent"
									 allowscriptaccess="always"
									 pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
							  </embed>
							</object>	
						';
						
						if($banner['Link'] != '') $tag = $click_div . $tag;
						
					break;
					
					default:
						$img = $thumbnail->link(array('path' => $banner['banner'], 'w' => $banner['width'], 'h' => 100, 'aoe' => 1, 'zc' => 1, 'q' => 95));
						
						if($banner['Link'] != '') 
						
						$tag = '
								<a target="_blank" href="'.$banner['Link'].'" title="'.$banner['Titolo'].'">
									<img class="img-responsive" src="'.$img.'" />
								</a>						
							   ';
							   
						else 
						
						$tag = '
									<img class="img-responsive" src="'.$img.'" />						
							   ';						
							   
					break;
				
				}

			?>	

			<!-- Html banner -->
			
			<?=$tag;?>
			
			<!-- end -->
			
			</div>
		</div>
		<? if(!($f+1)%3): ?>
		</div>
				<div class="row content-grid-row">

		<? endif ?>
		<? $f++; ?>
		<? endforeach; ?>
		
		
	</div>
	
	<? endif; ?>

	<? endforeach; ?>

	<? endif; ?>
