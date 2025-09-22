							<?
							
							$menus = $this->requestAction('/menus/getMenu');
							
							?>
							
							<div id="main-menu">
								
								<ul>
								
									<? foreach($menus['Menu'] as $menu): ?>
									
									<?
									
										$link = $this->requestAction('/pages/getPageByCode', array('code' => $menu['code']));
									
									?>
									
									<?//debug($menu);?>
									
										<li>
											<a href="<?=$link;?>" title="<?=$menu['titolo'];?>"><?=$menu['titolo'];?></a>
											<? if(isset($menu['Children'])): ?>
											<ul>
												<? foreach($menu['Children'] as $child): ?>
												
												<? 
												
												$link = $this->requestAction('/pages/getPageByCode', array('code' => $child['code']));												
												
												if(isset($child['Children'])) {
												
													$pre   = '+';
													
												} else {
												
													$pre = '//';
												
												}
												
												$title = $pre . ' ' . $child['titolo']; 
												
												?>
												
													<li>
														<a href="<?=$link;?>" title="<?=$title;?>"><?=$title;?></a>
														
														<? if(isset($child['Children'])): ?>
														
														<ul>
															<? foreach($child['Children'] as $children): ?>
															
															<?
															
																$link = $this->requestAction('/pages/getPageByCode', array('code' => $child['code']));																											
															
															?>
															
																<li>
																	<a href="<?=$link;?>" title="<?=$children['titolo'];?>"><?=$children['titolo'];?></a>
																</li>
															
															<? endforeach; ?>
														</ul>
														
														<? endif; ?>
														
													</li>
												
												<? endforeach; ?>
											</ul>											
											<? endif; ?>
										</li>
										
									<? endforeach; ?>
								
								</ul>
								
								<form action="#">
									<input value="cerca nel sito..." type="text" />
									<input type="submit" value="&nbsp;" /><!-- prende la classe  class="reset-search" dopo aver effettuato la ricerca -->
								</form>
								<div class="clear"></div>
							</div><!-- close main-menu -->