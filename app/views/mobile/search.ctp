<div class="search-results">

<ul data-role="listview" class="ui-corner-all">
	
	<li class="ui-bar-a btn-search-result ui-corner-top" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
				Risultati: <span><?=$searchValue;?></span>
	</li>	
		
	<? if (count($results)): ?>
	
	
	<? foreach ($results as $result): ?>
	
		<li>

		<h2><a data-ajax="false" href="<?=$result['link'];?>" title="<?=$result['title'];?>"><?=$result['title'];?></a></h2>
		<? if (trim($result['description']) != ""): ?>
		<p class="search-result-description">
			<? if (strlen(strip_tags($result['description'])) > 300): ?>
			<?=substr(strip_tags($result['description']),0,300);?>...
			<? else: ?>
			<?=strip_tags($result['description']);?>
			<? endif; ?>
		</p>
		<? endif; ?>
	
		</li>
	
	<? endforeach; ?>
	

	
	<? else: ?>
	
		<li class="post-info">Nessun risultato per &ldquo;<span><?=$searchValue;?></span>&rdquo;</li>
	
	<? endif; ?>
</ul>
</div><!-- close search-result -->
