<div class="tab-squadra">
		<div class="list-tab">
			<ul>
				<? if(!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/1<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<? endif; ?>
				<? if(!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])):?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/2<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Albo d'oro - trofei</a></li>
				<? endif; ?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/3<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="giocatori / statistiche <?=$squadra['Squadre']['Denominazione'];?>">Giocatori / Statistiche</a></li>
				<? if(!empty($uploads['Gallery'])): ?>
				<li class="selected"><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/4<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="galleria foto <?=$squadra['Squadre']['Denominazione'];?>">Galleria</a></li>
				<? endif; ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="content-tab">
			
			<div class="photo-gallery gruppo" id="uploadDiv">
			
			<? if(isset($uploads['Gallery'])): ?>
			
				<ul>
				<? foreach($uploads['Gallery'] as $k => $upload): ?>
				<? 
				$link  = $thumbnail->link(array('path' => $upload['path'], 'w' => 152,'h' => 85, 'q' => 100, 'zc' => 1)); 
				$links = $thumbnail->link(array('path' => $upload['path'], 'w' => 600, 'q' => 100)); 
				?>
				<li>
					<a href="javascript:;" title="<?=($upload['title'])? $upload['title']:$upload['name'];?>" rel="timmygallery" link="<?=$links;?>">
						<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
						<span class="timmy_description"><?=$upload['description'];?></span>
					</a>
				</li>					
				<? endforeach; ?>										
				</ul>
				
			<? else: ?>
			
			Nessuna foto inserita.
			
			<? endif; ?>	
			
			</div>			
			
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>	