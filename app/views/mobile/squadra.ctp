<?
//Ripartizione upload

$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo
if(isset($uploads['Logo'][0])) {
	
	$logo = $thumbnail->link(array('path' => $uploads['Logo'][0]['path'], 'h' => 50, 'q' => 100, 'f' => 'png')); 
	
} else {
	
	$logo = $thumbnail->link(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));
	
}

?>	

<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			<a data-ajax="false" href="/mobile/categories/<?=$parent['Page']['id'];?>/<?=strtolower(Inflector::Slug($parent['Page']['title'],'-'));?>" title="<?=$parent['Page']['title'];?>">
				<?=$parent['Page']['title'];?>
			</a>
			&rsaquo;
		</li>
		<li>
			<a data-ajax="false" href="/mobile/view/<?=$parent2['Page']['id'];?>/<?=strtolower(Inflector::Slug($parent2['Page']['title'],'-'));?>" title="<?=$parent2['Page']['title'];?>">
				<?=$parent2['Page']['title'];?>
			</a>
			&rsaquo;
		</li>
		<li>
		<?=$squadra['Squadre']['Denominazione'];?>
		</li>
		
	</ul>
	
</div>

<div class="team-content">
					
				<h3 class="title-profile-menu title-profile-menu-site"><img class="team-logo" src="<?=$logo;?>" /> <span><?=$squadra['Squadre']['Denominazione'];?></span><br class="clear"></div></h3>



			<? if(!empty($squadra['Squadre']['Storia'])): ?>
			

				<div class="team-story">
				<div class="content-story contents-text">
					
					<?=$squadra['Squadre']['Storia'];?>
					
				</div>
				</div>
				

				
				<? endif; ?>


				<div class="team-photo">
					<? if(isset($uploads['Squadra'])): ?>
					
					<? 
					$link = $thumbnail->link(array('path' => $uploads['Squadra'][0]['path'], 'w' => 464, 'q' => 100)); 
					?>
					<img src="<?=$link;?>" width="100%" alt="<?=($uploads['Squadra'][0]['title'])? $uploads['Squadra'][0]['title']:$uploads['Squadra'][0]['name'];?>" />															
					
					<? else: ?>
					
					<img src="/img/default-photo.png" alt="nome squadra" width="464px" height="261px"/>
					
					<? endif; ?>					
					
				</div>
<!--
				<div class="team-sponsor">
				
					<? if(isset($uploads['Sponsor'])): ?>
					
						<?
						
							$last_line  = count($uploads['Sponsor']) % 3;
							$total_line = count($uploads['Sponsor']);
						
						?>							
					
					
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
			</div>
-->			

					
					<? if(isset($uploads['Coccarda'])): ?>
					<div class="team-awards">
	
					
						<ul>
						
							<? foreach($uploads['Coccarda'] as $k => $upload): $j = $k + 1;?>
							
							<? 
							$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 45, 'q' => 100));											 
							?>
								
							<li <? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
								<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
							</li>	
									
							<? endforeach; ?>	
							<div class="clear"></div>
						</ul>				
						<div class="clear"></div>
					</div>
					<? endif; ?>							
<div id="results-box">
	<table class="table-matches table-calcio table-squadra" width="100%">

														<tr>
															<td><b>SPONSOR</b></td>
															<td>
															<? if (!empty($info['Info']['sponsor'])): ?>
															<?=$thumbnail->show(array('path' => $info['Info']['sponsor'],'w' => 50));?>
															<? else: ?>
															
															<img src="/img/website/icon_team_default.png" alt="Sponsor <?=$info['Squadre']['Denominazione'];?>" />
															
															<? endif; ?>
															</td>
														</tr>
														<tr>
															<td><b>CAMPIONATI DISPUTATI</b></td>
																												
															<td><?=$info['Info']['Campionati'];?></td>
														</tr>
														<tr>
														<td><b>STAGIONI</b></td>
																						
														<td><?=$info['Info']['Stagioni'];?></td>
														</tr>
																	
																		
	</table>
</div>

	</div>	