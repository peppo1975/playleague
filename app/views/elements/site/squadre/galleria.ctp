<div class="tab-squadra">
		<div class="list-tab">
			<ul>
				<? if(!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/1/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<? endif; ?>
				<? if(!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])):?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/2/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Albo d'oro - trofei</a></li>
				<? endif; ?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/3/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="giocatori / statistiche <?=$squadra['Squadre']['Denominazione'];?>">Giocatori / Statistiche</a></li>
				<? if(!empty($uploads['Gallery'])): ?>
				<li class="selected"><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/4/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="galleria foto <?=$squadra['Squadre']['Denominazione'];?>">Galleria</a></li>
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