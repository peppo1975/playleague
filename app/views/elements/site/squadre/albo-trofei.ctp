<div class="tab-squadra">
		<div class="list-tab">
			<ul>
				<? if(!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/1/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<? endif; ?>
				<? if(!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])):?>
				<li class="selected"><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/2/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Albo d'oro - trofei</a></li>
				<? endif; ?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/3/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="giocatori / statistiche <?=$squadra['Squadre']['Denominazione'];?>">Giocatori / Statistiche</a></li>
				<? if(!empty($uploads['Gallery'])): ?>
				<li><a href="/squadre/<?=$squadra['Squadre']['Squadra'];?>/4/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="galleria foto <?=$squadra['Squadre']['Denominazione'];?>">Galleria</a></li>
				<? endif; ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="content-tab">
			<div class="box-sx-tab">
			
			<? if(count($squadra['SquadreAlbo'])): ?>
			
				<div class="albo-oro ">
					<h3 class="albo-oro-h3">Albo d'oro</h3>
					
					<ul class="albo-oro-ul">
					
						<li class="albo-head">
							<span class="albo-champ">Campionato</span>
							<span class="albo-pos">Posizione</span>
							<div class="clear"></div>
						</li>					
					
					<? foreach($squadra['SquadreAlbo'] as $k => $albo): ?>
					
						<li <? if(($k +1) % 2 == 0): ?>class="alternate"<? endif; ?>>
							<span class="albo-champ"><?=$albo['Campionato'];?></span> 
							<span class="albo-pos"><?=$albo['Posizione'];?></span>
							<div class="clear"></div>
						</li>
					
					<? $i = $k; ?>
					
					<? endforeach; ?>					
					
					</ul>
				</div>
				
			<? else: ?>
			
			Albo d'oro non trovato.
			
			<? endif; ?>				
				
			</div>
			<div class="box-dx-tab">
				<div class="trofei">
					
					<? if(isset($uploads['Trofeo'])): ?>
					
					<h3>Palmares/Trofei</h3>
					
						<ul>
						<? 
						
						$uploads['Trofeo'] = array_orderby($uploads['Trofeo'], 'yearTrofeo', SORT_DESC);
						
						foreach($uploads['Trofeo'] as $k => $upload): 
						
						?>
						
						<? 
						$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 53,'h' => 54, 'q' => 100, 'zc' => 1)); 
						?>
						
						<li>
							<img src="<?=$link;?>" <? if(!empty($upload['title'])): ?>rel="timmytip" title="<?=($upload['title'])? $upload['title']:$upload['name'];?>"<? endif; ?> alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
						</li>										
						<? endforeach; ?>										
						</ul>
						
					<? else: ?>
					
					Nessuna foto trofeo inserita.
					
					<? endif; ?>					

				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>