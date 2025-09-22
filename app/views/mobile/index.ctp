<? /*
Calendari/Classifiche/Note gara
- Seleziona torneo di riferimento

Impianti sportivi
- Seleziona impianto sportivo

Shop online
- Categorie

*/




 ?>

	<ul data-inset="true" data-role="listview" data-theme="a">

			<li class="ui-bar-a btn-mondo-midland" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
				Midland Global Sport
			</li>	
		
	<li class="btn-campionati" id="li-filtro">
				<a href="/mobile/campionati" data-ajax="false" title="Calendari/Classifiche/Note gara">Calendari/Classifiche/Note gara</a>
			</li>
		<? foreach ($menu_categories as $id => $cat): ?>
		<? if (strtoupper($cat['title']) != "HOME"): ?> 
		<li class="btn-<?=strtolower(Inflector::Slug($cat['title'],'-'));?>" id="li-<?=$id;?>">
			<a  data-ajax="false" href="/mobile/categories/<?=$id;?>/<?=strtolower(Inflector::Slug($cat['title'],'-'));?>" title="<?=$cat['title'];?>">
				<?=$cat['real_title'];?>
			</a>
		</li>			
	<? endif; ?>
	<? endforeach; ?>

					
			<? /*
			<li>
				<select name="campionato_id" onchange="location.href = '/mobile/campionati/' + $(this).val();" autocomplete="off">
				
						<option value="">Seleziona torneo di riferimento</option>
					
						<? foreach($campionati as $c): ?>
						
							<option value="<?=$c['Campionati']['Campionato'];?>"><?=$c['Campionati']['Nome'];?></option>
						
						<? endforeach; ?>
					
				</select>
			</li>
			*/ ?>
			<!--
			<li class="btn-<?=strtolower(Inflector::Slug($menu_categories[51],'-'));?>" id="li-51">
				<a data-ajax="false" href="/mobile/categories/51/<?=strtolower(Inflector::Slug($menu_categories[51],'-'));?>" title="<?=$menu_categories[51];?>">
					<?=$menu_categories[51];?>
				</a>
			</li>
			-->
			<!--
			<li class="btn-squadre-atleti" id="li-51">
				<a data-ajax="false" href="/mobile/categories/105/<?=strtolower(Inflector::Slug($menu_categories[105],'-'));?>" title="<?=$menu_categories[105];?>">
					<?=$menu_categories[105];?>
				</a>
			</li>
			-->
		

			<!--	
			<li class="btn-<?=strtolower(Inflector::Slug($menu_categories[53],'-'));?>" id="li-53">
				<a data-ajax="false" href="/mobile/categories/53/<?=strtolower(Inflector::Slug($menu_categories[53],'-'));?>" title="<?=$menu_categories[53];?>">
					<?=$menu_categories[53];?>
				</a>
			</li>	
			-->

			
			<? $login_data = $session->read('Login.data'); ?>
			
			<? if(!empty($login_data)): ?>			

			<li class="btn-reserved" id="li-reserved">
				<a href="/mobile/reserved" data-ajax="false" title="Gestione profilo">Gestione profilo</a>				 
			</li>		
			
			<? else: ?>
			
			<li class="btn-reserved" id="li-reserved">
				<a href="/mobile/reserved" data-ajax="false" title="Login/Registrazione utenti">Login/Registrazione utenti</a>				 
			</li>			
			
			<? endif; ?>
			
			<?/*
			<li>
				<select name="campo_id" onchange="location.href = '/mobile/impianti/' + $(this).val();" autocomplete="off">
				
						<option value="">Seleziona impianto sportivo</option>
					
						<? foreach($campi as $c): ?>
						
							<option value="<?=$c['Campi']['Campo'];?>"><?=$c['Campi']['Descrizione'];?></option>
						
						<? endforeach; ?>
					
				</select>			
			</li>
			*/ ?>
			
	</ul>
	<!--
	<ul data-inset="true" data-role="listview" data-theme="a">	
			
			<li class="ui-bar-a btn-notizie" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
				Notizie
			</li>
			
			<? foreach($menu_news as $id => $m) : ?>
			
				<li class="btn-<?=strtolower(Inflector::Slug($m,'-'));?>">
					<a data-ajax="false" href="/mobile/news/<?=$id;?>/<?=strtolower(Inflector::Slug($m,'-'));?>" title="<?=$m;?>">
						<?=$m;?>
					</a>
				</li>
			
			<? endforeach; ?>
			
	</ul>
	-->
	<? /*
	
	<ul data-inset="true" data-role="listview" data-theme="a">				
			
			<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			 Utenti / Atleti
			</li>
			
			<? if(empty($login_data)): ?>
		
				<li><a data-ajax="false" href="/mobile/login">Login</a></li>
				<li><a data-ajax="false" href="/mobile/signup">Registrazione utenti</a></li>				
				<li><a data-ajax="false" href="/mobile/signup_athlete">Registrazione atleti</a></li>						
			
			<? else: ?>
			
				<li><a data-ajax="false" class="button-profile" href="/mobile/reserved" title="accedi al tuo pannello di controllo"><span>Gestione profilo di <?=$login_data['nome'];?></a></li>
				<li><a data-ajax="false" class="button-logout" href="/mobile/?logout=1" class="user-logout" title="Logout">Logout</a></li>		
			
			<? endif; ?>	
			
	</ul>
	
	*/ ?>
	

	<!--
	<ul data-inset="true" data-role="listview" data-theme="a">
			
		<li class="ui-bar-a btn-informazioni" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			Informazioni
		</li>				
			

		<? foreach ($menu_categories as $id => $cat): ?>

		<li class="btn-<?=strtolower(Inflector::Slug($cat,'-'));?>" id="li-<?=$id;?>">
			<a  data-ajax="false" href="/mobile/categories/<?=$id;?>/<?=strtolower(Inflector::Slug($cat,'-'));?>" title="<?=$cat;?>">
				<?=$cat;?>
			</a>
		</li>			

	<? endforeach; ?>
		
	
	</ul>
	-->
	
		<ul data-inset="true" data-role="listview" data-theme="a">
			
			<li class="mobile-shop-online">	
				<a data-ajax="false" href="http://<?=Configure::read('shop_url');?>" title="Midland Sport online store">Midland Sport online store</a>
			</li>					
			
	</ul>	