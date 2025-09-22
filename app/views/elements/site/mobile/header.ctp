<script type="text/javascript">

	$(document).bind('pageinit',function() {

		$("#submenu-container").css('z-index',2);

		$("#toggle-menu").unbind('tap').bind('tap',function() {
			if ($("#submenu-container").is(':visible')) {
				$("#submenu-container").fadeOut(350);
			} else {
				$("#submenu-container").fadeIn(350);
			}
		
		});
		

		$("#toggle-search").unbind('tap').bind('tap',function() {
			if ($("#search-container").is(':visible')) {
				$("#search-container").fadeOut(350);
			} else {
				$("#search-container").fadeIn(350);
			}
		
		});
			
	
		$("#submenu").find('a').unbind('tap').bind('tap', function(){
			var me        = $(this);
			var container = $('#submenu-lists');
				container.find('ul').hide();
				container.find('ul[data-id="'+me.attr('data-id')+'"]').show();
		});
		
		$('input[name="data[Search][search-mini]"]').unbind('change').bind('change', function(){
		
			var me = $(this);
			
				$('.searchForm').submit();
		
		});
		
	});

</script>

<div id="header" data-role="header" >

<!--
	<div id="logo">
		<a href="/mobile" data-ajax="false" title="midlandsport.it - mobile version">
			<img src="/img/mobile/logo-mobile.png" width="30" height="" alt="Midland Sport Firenze" />
		</a>
		<h1>Midland Sport Mobile Web</h1>
		<div class="clear"></div>
	</div>
		
	<a href="#" id="toggle-menu" class="ui-btn-right">Men&ugrave;</a>

	<div class="clear"></div>
	
	-->
	
<a data-icon="home" data-role="button" data-theme="a" data-iconpos="notext" href="/mobile" data-ajax="false"></a>

<h1 class="ui-title" aria-level="1" role="heading" tabindex="0" id="header-logo">
 <!-- <img class="ml-logo" src="/img/mobile/ml-header-mobile.png" height="30" /> -->
</h1>
<div data-type="horizontal" style="top:-8px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
<a id="toggle-search" data-icon="search" data-role="button" data-theme="a" data-iconpos="notext"  data-inline="true"></a>
	
<? /*<a id="toggle-menu" data-icon="grid" data-role="button" data-theme="a" data-iconpos="notext" data-inline="true"></a>*/ ?>
 </div>


<div id="search-container" style="display: none;" data-role="navbar">
<div id="search-bar">
	<form action="/mobile/search" method="post" class="searchForm" data-ajax="false">
		<input type="search" name="data[Search][search-mini]" id="search-mini" value="" data-mini="true" />
	</form>
</div>
</div>
	
<div id="submenu-container">


<div data-role="navbar" id="submenu">
	<ul>
		<li>
			<a href="#" data-id="category" class="ui-btn-active ui-state-persist">Categorie</a>
		</li>
		<li>
			<a data-id="news" href="#">Notizie</a>	
		</li>
		<li>
			<a data-id="user" href="#">Utenti</a>	
		</li>
	</ul>
</div><!-- /navbar -->


<div id="submenu-lists">

	<ul data-id="category" data-role="listview">
		<? foreach($menu_categories as $id => $m) : ?>
		
			<li>
				<a data-ajax="false" href="/mobile/categories/<?=$id;?>/<?=strtolower(Inflector::Slug($m,'-'));?>" title="<?=$m;?>">
					<?=$m;?>
				</a>
			</li>
		
		<? endforeach; ?>
	</ul>

	<ul data-id="news" data-role="listview" style="display: none;">
		<? foreach($menu_news as $id => $m) : ?>
		
			<li>
				<a data-ajax="false" href="/mobile/news/<?=$id;?>/<?=strtolower(Inflector::Slug($m,'-'));?>" title="<?=$m;?>">
					<?=$m;?>
				</a>
			</li>
		
		<? endforeach; ?>			
	</ul>		

	<ul data-id="user" data-role="listview" style="display: none;">
	
		<? $login_data = $session->read('Login.data'); ?>
		
		<? if(empty($login_data)): ?>
	
			<li><a data-ajax="false" href="/mobile/login">Login</a></li>
			<li><a data-ajax="false" href="/mobile/signup">Registrazione utenti</a></li>				
			<li><a data-ajax="false" href="/mobile/signup_athlete">Registrazione atleti</a></li>						
		
		<? else: ?>
		
			<li><a data-ajax="false" class="button-profile" href="/mobile/reserved" title="accedi al tuo pannello di controllo"><span>Gestione profilo di <?=$login_data['nome'];?></a></li>
			<li><a data-ajax="false" class="button-logout" href="/mobile/?logout=1" class="user-logout" title="Logout">Logout</a></li>		
		
		<? endif; ?>
	</ul>			

</div>



<div class="clear"></div>

</div>

</div><!-- close header -->


