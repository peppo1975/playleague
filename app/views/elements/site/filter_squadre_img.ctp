								<?
								//Ripartizione upload
								
								$uploads = array();
								foreach($squadra['Upload'] as $upload) {
									if($upload['tag'] == '') $upload['tag'] = 'Gallery';
									$uploads[$upload['tag']][] = $upload;
								}
								?>
								
								<div class="container-squadra-info <? if(empty($squadra['Upload']) && $squadra['Squadre']['Storia'] == ''): ?>hidden<? endif; ?>">
								
								<div class="container-squadra-img <? if(empty($uploads)): ?>hidden<? endif; ?>"><!-- container-squadra-img -->
								
									<div class="squadra-img <? if(!isset($uploads['Squadra'])): ?>hidden<? endif; ?>">
									
									<? if(isset($uploads['Squadra'])): ?>
									
									<? 
									$link = $thumbnail->link(array('path' => $uploads['Squadra'][0]['path'], 'w' => 475,'h' => 270, 'q' => 100)); 
									?>
									<img src="<?=$link;?>" alt="<?=($uploads['Squadra'][0]['title'])? $uploads['Squadra'][0]['title']:$uploads['Squadra'][0]['name'];?>" />															
									
									<? endif; ?>
									
									</div><!-- close squadra-img -->
								
									<div class="trofei-sponsor <? if(!isset($uploads['Trofeo']) && !isset($uploads['Sponsor'])): ?>hidden<? endif; ?>">
									
										<div class="trofei <? if(!isset($uploads['Trofeo'])): ?>hidden<? endif; ?>">
										
										<? if(isset($uploads['Trofeo'])): ?>
										
											<h4>Palmares/Trofei</h4>
											<ul>
											<? 
											
											$uploads['Trofeo'] = array_orderby($uploads['Trofeo'], 'yearTrofeo', SORT_DESC);
											
											foreach($uploads['Trofeo'] as $k => $upload): 
											
											?>
											
											<? 
											$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 36,'h' => 36, 'q' => 100, 'zc' => 1)); 
											?>
											<li <? if(($k+1) % 11 == 0): ?>class="no-margin-trofei"<? endif; ?> <? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
												<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
											</li>						
											<? endforeach; ?>										
											</ul>
										
										<? endif; ?>	
										
										</div><!-- close trofei -->
									
										<div class="sponsor <? if(!isset($uploads['Sponsor'])): ?>hidden<? endif; ?>">
										
										<? if(isset($uploads['Sponsor'])): ?>
										
											<h4>Sponsor squadra</h4>
											<ul>
											<? foreach($uploads['Sponsor'] as $k => $upload): ?>
											<? 
											$link = $thumbnail->link(array('path' => $upload['path'], 'w' => 148,'h' => 82, 'q' => 100, 'zc' => 1)); 
											?>
											<li <? if(($k+1) % 3 == 0): ?>class="no-margin-sponsor"<? endif; ?><? if(!empty($upload['title'])): ?> rel="timmytip" <? endif; ?> title="<?=($upload['title'])? $upload['title']:'';?>">
												<img src="<?=$link;?>" alt="<?=($upload['title'])? $upload['title']:$upload['name'];?>" />
											</li>					
											<? endforeach; ?>										
											</ul>
											
										<? endif; ?>	
											
										</div><!-- close sponsor -->
									
									</div><!-- close trofei-sponsor -->
									
									<div class="clear"></div>
									
								</div><!-- close container-squadra-img -->	
									
								<? if(!empty($squadra['Squadre']['Storia'])): ?>
								
									<h4 class="history-title">La storia</h4>
									<div class="team-history">
										<?=$squadra['Squadre']['Storia'];?>
									</div>
								
								<? endif; ?>									
									
									<!-- photo gallery -->
									
										<div class="photo-gallery gruppo" id="uploadDiv">
										
										<? if(isset($uploads['Gallery'])): ?>
										
											<h4>Foto gallery</h4>
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
										
										<? endif; ?>	
											
										</div><!-- close photo gallery -->
									
									<div class="clear"></div>

								</div><!-- close container-squadra-info -->

								<div class="clear"></div>
								