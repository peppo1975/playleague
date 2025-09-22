
<ul class="menu-home" data-filter="false" data-inset="true" data-role="listview">

<? $menu = $this->requestAction('/sections/getSections'); ?>

<? foreach($menu as $id_section => $m): ?>

	<? if($id_section == getConfig("SECTION_ID_FAQ") || $id_section == getConfig("SECTION_ID_CONTATTI")) continue; ?>

	<li>
		<? if($id_section == getConfig('SECTION_ID_BLOG')): ?>
		
		<a href="/mobile/blog" title="<?=$m;?>"><?=$m;?></a>
		
			<? /*
		
			<? $categories = $this->requestAction('/blog_categories/getCategories'); ?>
		
			<? if(!empty($categories) || !empty($login_data)): ?>
		
			<div class="submenu-container">	
				<ul class="submenu">
					
					<? if(!empty($categories)): ?>
				
					<? foreach($categories as $id_category => $category): ?>
						<li><a href="/categories/<?=getLink($id_category, $category);?>" title="<?=$category;?>"><?=$category;?></a></li>
					<? endforeach; ?>
					
					<? endif; ?>
					
					<? if (!empty($login_data)): ?>
					
					<li class="login-option">
						<a class="last" href="/users/article_add" title="Nuovo articolo"><img src="/img/website/icon-article-add.png" onmouseover="this.src='/img/website/icon-article-add-hover.png';" onmouseout="this.src='/img/website/icon-article-add.png';" alt="Nuovo articolo" /></a>
					</li>													
					<li class="login-option">
						<a href="/users/article_list" title="I miei articoli"><img src="/img/website/icon-article-list.png" onmouseover="this.src='/img/website/icon-article-list-hover.png';" onmouseout="this.src='/img/website/icon-article-list.png';" alt="I miei articoli" /></a>
					</li>													
					<li class="login-option">
						<a href="/users/profile" title="Profilo"><img src="/img/website/icon-profile.png" onmouseover="this.src='/img/website/icon-profile-hover.png';" onmouseout="this.src='/img/website/icon-profile.png';" alt="Profilo" /></a>
					</li>
					
					<? endif; ?>	
				</ul>

			</div>	
			
			<? endif; ?>	
			
			*/ ?>
			
		<? else: ?>
		
		<a href="/mobile/sections/view/<?=getLink($id_section, $m);?>" title="<?=$m;?>"><?=$m;?></a>	
		
		<? endif; ?>
	</li>

<? endforeach; ?>

</ul>