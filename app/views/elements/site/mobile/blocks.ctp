<? if(!empty($section['Block'])): ?>
					
			<ul id="block-list">
				
						<? foreach($section['Block'] as $i => $block): ?>
						
							<? $preview = getPreview($block['Upload'], array('width' => 65, 'height' => 65, 'zc' => 1, 'q' => 80,'f' => 'png')); ?>
						
							<li>
                                 
								<a href="/mobile/blocks/view/<?=getLink($block['Block']['id'], $block['Block']['title']);?>" title="">
									
									<? if(!empty($preview)): ?>
									
									<span class="thumb">
										<img class="timmy-lazy" src="<?=$preview['path'];?>" alt="" />
									</span>
									<? endif; ?>									
									
									<span class="type">
										<? if(!empty($block['Category'])): ?>
										
										<?=$block['Category']['title'];?>
										<? else: ?>
		                                                                &nbsp;
										<? endif; ?>
									</span>
									<span class="title">
										<?=$this->Text->truncate($block['Block']['title'],65);?>
									</span>
								</a>								
								
							</li>
						
						<? endforeach; ?>
						
			</ul>
						
					
<? endif; ?>