<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo;
		</li>	
		<li>
			<?=$data['Page']['title'];?> 
		</li>

	</ul>

</div>

<ul data-inset="true" data-role="listview" data-theme="a">
	<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
		<?=$data['Page']['title'];?>
	</li>	
	<? foreach($menu as $id => $m) : ?>
	
		<li>
			<a data-ajax="false" href="/mobile/view/<?=$id;?>/<?=strtolower(Inflector::Slug($m,'-'));?>" title="<?=$m;?>">
				<?=$m;?>
			</a>
		</li>
	
	<? endforeach; ?>			
</ul>		