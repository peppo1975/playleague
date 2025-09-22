<div class="tab-squadra">
		<div class="list-tab">
			<ul>
				<? if(!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/1<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<? endif; ?>
				<? if(!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])):?>
				<li class="selected"><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/2<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Albo d'oro - trofei</a></li>
				<? endif; ?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/3<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="giocatori / statistiche <?=$squadra['Squadre']['Denominazione'];?>">Giocatori / Statistiche</a></li>
				<? if(!empty($uploads['Gallery'])): ?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/4<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="galleria foto <?=$squadra['Squadre']['Denominazione'];?>">Galleria</a></li>
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
					
					<? endif; ?>					

				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>