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

	<? $nrows = 10; ?>
	<? $crows = 0; ?>
	<? $cpages = 0; ?>

	<ul data-inset="true" data-role="listview" data-theme="a">
		<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			<?=$data['Page']['title'];?>
		</li>	
		<? foreach($menu as $id => $m) : ?>
					<? if ($m['Block']['disabled'] == 0): ?>

			<? if(strtotime($m['Block']['published']) > strtotime(date("Y-m-d"))) continue; ?>
		
			<li data-page="<?=$cpages;?>" <? if ($cpages > 0): ?>style="display: none;"<? endif; ?>>
				<a class="news-link" data-ajax="false" data-cache="false" href="/mobile/view/<?=$page['Page']['id'];?>/<?=strtolower(Inflector::Slug($page['Page']['title'],'-'));?>/<?=$m['Block']['id'];?>/<?=strtolower(Inflector::Slug($m['Block']['title'],'-'));?>" title="<?=$m['Block']['title'];?>">
					
					<? if($m['Block']['published_it'] != "" && $m['Block']['published_it'] != "00/00/0000"): ?>
					
					<span class="news-data">
						<?=$m['Block']['published_it'];?>
					</span><br />
					
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
		<? endforeach; ?>			
	</ul>		
	
	<? if (ceil(count($menu)/$nrows) > 1): ?>
	<a href="javascript:;" class="show-more-news" data-role="button" data-page="0" data-max-page="<?=ceil(count($menu)/$nrows);?>">mostra altri</a>

	<script type="text/javascript">

		
		$(document).bind('pageinit',function() {
		
			$(window).scrollTop(0);
			$('body').trigger('create').trigger('refresh');
			

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