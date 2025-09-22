					<div class="operations_bar">
						<div class="left">
							<ul class="table_operations">
								<li>
									<form class="index-set-limit">
										<select name="limit">
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="150">150</option>
											<option value="200">200</option>
										</select>
									</form>	
								</li>
								<?if($order_option == 1): ?>
								<li>
									<form class="index-order-argument">
										<select name="order-argument">
											<option value="random()">Casuale</option>
											<option value="created">Data creazione</option>
											<option value="published">Data pubblicazione</option>
											<option value="order">Drag & Drop</option>
											<option value="title">Titolo</option>
										</select>
									</form>
								</li>
								<li>
									<form class="index-order-type">
										<select name="order-type">
											<option value="ASC">Ascendente</option>
											<option value="DESC">Discendente</option>
										</select>
									</form>
								</li>
								<? endif; ?>								
								<li class="index-select-all"><a href="javascript:;" title="seleziona tutti">seleziona tutti</a></li>
								<li class="index-revert-selection"><a href="javascript:;" title="inverti selezione">inverti selezione</a></li>
								<li class="index-delete-selected"><a href="javascript:;" title="cancella selezionati">cancella selezionati</a></li>
							</ul>
						</div>
						<div class="right">
							<ul class="paging">

							<li class="count-record">Record visualizzati: <span class="record"><?=count($data);?> &nbsp;</span></li>
							
								<? if ($pages < 6): ?>
							
									<? for ($i=1;$i<=$pages;$i++): ?>
									
										<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
									
									<? endfor; ?>
								
									
								<? else: ?>
							
									<? if ($page <= 6): ?>
													
										<? for ($i=1;$i<=7;$i++): ?>
										
											<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
										
										<? endfor; ?>
										
										<? $ultime = $pages; ?>
										
										<? for ($i=$pages;$i>$pages-4&&$i>7;$i--) $ultime = $i; ?>
										
										<? if ($ultime-7 > 1): ?><li><a href="javascript:;">...</a></li><? endif; ?>
										
										<? for ($i = $ultime;$i<$pages;$i++): ?>
										
											<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
										
										<? endfor; ?>
									
									<? else: ?>
									
										<? for ($i=1;$i<4;$i++): ?>
													
													
											<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
										
										
										<? endfor; ?>
										
										<? for ($i=$pages;$i>$pages-4&&$i>7;$i--) $ultime = $i; ?>
										
										
										<li><a href="javascript:;">...</a></li>
										
										<? for ($i=(($page >= 4 && $page-2 >= 4)? $page-2 : $page);$i<=$page+2&&$i<$ultime;$i++): ?>
											
											<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
										
										<? endfor; ?>
									
										<? if ($ultime - $i > 1): ?>
										<li><a href="javascript:;">...</a></li>
										<? endif; ?>
				
										<? for ($i = $ultime;$i<$pages;$i++): ?>
										
											<li class="<?=(($i==$page)? 'selected' : '');?>"><a href="<?=$url;?>/page/<?=$i;?>"><?=$i;?></a></li>
										
										<? endfor; ?>
									
										
									<? endif; ?>
														
								<? endif; ?>
							
							</ul>
						</div>
					<div class="clear"></div>
					</div><!-- close operations_bar-->
