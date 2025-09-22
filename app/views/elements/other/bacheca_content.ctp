<? if(!empty($messages)): ?>
	<div class="panel-group" id="accordion" >
		<? $i = 0; foreach($messages as $message): $uploads = $message['Upload']; $message = $message['LdaWall']; ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$i?>">
								<?=$message['title'];?>
							</a>
						</h4>
					</div>
					<div id="collapse-<?=$i++?>" class="accordion-body collapse">
						<div class="panel-body">
							<p class="post-info"><small>Pubblicato il <?=$message['published_it'];?></small></p>
							<div class="block-content"><?=$message['content'];?></div>
							<? 
								$countDocuments_blocks = 0;
								foreach($uploads as $t)
									if(in_array($t['ext'], $ext_doc)) 
										$countDocuments_blocks++;
							?>
							<? if($countDocuments_blocks > 0): ?>
								<div class="contentents-files-documents">
									<h3>File e documenti allegati</h3>
										<div class="documents-container">
											<? foreach($uploads as $attach):
													if(in_array($attach['ext'], $ext_doc)): ?>
														<?
															if($attach['title'] != '') $title = $attach['title'];
															else					   $title = $attach['name'];
														?>
												
														<div class="attached-document">
															<a href="/files/uploads/<?=$attach['name'];?>" title="<?=$title;?>">
																<span class="icon"><img src="/img/website/<?=$mimes[$attach['ext']];?>" alt=""></span><?=$title;?>
															</a>
															<span class="close-link"></span>
														</div>
													<? endif; ?>
											<? endforeach; ?>
										</div>
								</div>
							<? endif; ?>
						</div>
					</div>
				</div>
		<? endforeach; ?>
	</div>
<? else: ?>				
	<div class="error-message alert alert-warning">
		Nessun messaggio in bacheca.
	</div>
<? endif; ?>


					
