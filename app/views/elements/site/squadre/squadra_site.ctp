<script type="text/javascript">

$(function(){
	
	$('.content-story').height($(".content-tab").height() - 63).css('background','#FFF');
	$('.team-story').height($(".content-tab").height() - 63);
	
});

</script>

<div class="tab-squadra">
		<div class="list-tab">
			<ul>
			    <? if(!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
				<li class="selected"><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/1<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="<?=$squadra['Squadre']['Denominazione'];?>">Squadra</a></li>
				<? endif; ?>
				<? if(!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])):?>
				<li><a href="/squadra/dettaglio/<?=$squadra['Squadre']['Squadra'];?>/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>/2<? if(isset($_GET['option']) && !empty($_GET['option'])) echo '?option=' . $_GET['option'];?>" title="albo d'oro <?=$squadra['Squadre']['Denominazione'];?>">Albo d'oro - trofei</a></li>
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
				<div class="team-image">
					<h3>Foto squadra</h3>
					
					<? if(isset($uploads['Squadra'])): ?>
					
					<? 
					$link = $thumbnail->link(array('path' => $uploads['Squadra'][0]['path'], 'w' => 475, 'q' => 100)); 
					?>
					<img src="<?=$link;?>" alt="<?=($uploads['Squadra'][0]['title'])? $uploads['Squadra'][0]['title']:$uploads['Squadra'][0]['name'];?>" />															
					
					<? else: ?>
					
					<img src="/img/default-photo.png" alt="nome squadra" width="465px" height="261px"/>
					
					<? endif; ?>					
					
				</div>
				
				<? if(isset($uploads['Sponsor']) || isset($uploads['Coccarda'])): ?>
								
				<div class="team-sponsor">
				
					<? if(isset($uploads['Sponsor'])): ?>
					
					<?
					
						$last_line  = count($uploads['Sponsor']) % 3;
						$total_line = count($uploads['Sponsor']);
					
					?>							
					
						<h3>Sponsor squadra</h3>
					
						<ul>
						<? foreach($uploads['Sponsor'] as $k => $upload): $j = $k + 1;?>
						
						<? 
						$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 148,'h' => 83, 'q' => 100, 'zc' => 1));											 
						?>
						
							<? switch($last_line): 
							
								case 1: 
							
							?>
							
								<li <? if($j == $total_line): ?>style="margin-bottom: 0px;"<? endif; ?><? if(($k+1) % 3 == 0): ?>class="last"<? endif; ?><? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
									<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
								</li>									
							
							<? break; ?>
							
							<? case 2: ?>
							
								<li <? if($j == $total_line || $j == ($total_line - 1)): ?>style="margin-bottom: 0px;"<? endif; ?> <? if(($k+1) % 3 == 0): ?>class="last"<? endif; ?><? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
									<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
								</li>							
							
							<? break; ?>
							
							<? case 0: ?>
							
								<li <? if($j == $total_line || $j == ($total_line - 1) || $j == ($total_line - 2)): ?>style="margin-bottom: 0px;"<? endif; ?> <? if(($k+1) % 3 == 0): ?>class="last"<? endif; ?><? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
									<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
								</li>							
							
							<? break; ?>
							
							<? endswitch; ?>
						
			
						<? endforeach; ?>										
						</ul>
						
					<? endif; ?>
						
					<? if(isset($uploads['Coccarda'])): ?>
					
					<h3>
						<a title="Clicca per la legenda delle vittorie" style="text-decoration: none; color: #000;" link="/img/timmybox/blank.gif" rel="timmygallery" url="/squadres/coccarde" href="javascript:;">
							Vittorie
						</a>
					</h3>
					
						<ul>
						
							<? foreach($uploads['Coccarda'] as $k => $upload): $j = $k + 1;?>
							
							<? 
							$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 50, 'q' => 100));											 
							?>
								
							<li <? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
								<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
							</li>	
									
							<? endforeach; ?>	
						
						</ul>				
					
					<? endif; ?>							
					
					<div class="clear"></div>	
				</div>
				
				<? endif; ?>
				
				<div class="clear"></div>
			</div>
			<div class="box-dx-tab">
			
				<? if(!empty($squadra['Squadre']['Storia'])): ?>
			
				<h3>Storia</h3>
				<div class="team-story">
				<div class="content-story">
					
					<?=$squadra['Squadre']['Storia'];?>
					
				</div>
				</div>

				
				<? endif; ?>				
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>	