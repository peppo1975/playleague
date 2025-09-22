<?

	$ext_doc   = array('doc','xls','zip','xlsx','rar','pdf','mp3');
	
	$mimes = array(
	
		'doc' => 'icon-doc.png',
		'xls' => 'icon-xls.png',
		'zip' => 'icon-zip.png',
		'xlsx' => 'icon-xls.png',
		'rar' => 'icon-zip.png',
		'pdf' => 'icon-pdf.png',
		'mp3' => 'icon-mp3.png',
	
	);

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
			<a data-ajax="false" href="/mobile/reserved" title="Gestione profilo">
				Gestione profilo
			</a>
			&rsaquo;
		</li>
		<li>
			Bacheca
		</li>
		
	</ul>
	
</div>

<div class="reserved-area">

				<div class="contents-text">

				<? if(!empty($messages)): ?>
				
					<ul class="blocks message-blocks" data-role="listview">
	<li class="ui-bar-a btn-bacheca ui-corner-top" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
				Bacheca
	</li>	
					<? foreach($messages as $message): $uploads = $message['Upload']; $message = $message['LdaWall']; ?>
					
						<li class="block-box">
							<h2 class="message-title"><?=$message['title'];?></h2>
							<p class="post-info">Pubblicato il <?=$message['published_it'];?></p>

							<div class="block-content">
							<?=$message['content'];?>
							</div>
							
							<? 
							
							$countDocuments_blocks = 0;
							foreach($uploads as $t) {
								if(in_array($t['ext'], $ext_doc)) $countDocuments_blocks++;
							} 
							
							?>
							
							<? if($countDocuments_blocks > 0): ?>
							
							<div class="contentents-files-documents">
								<div class="documents-container">
								
								<? foreach($uploads as $attach): ?>
								
								<? if(in_array($attach['ext'], $ext_doc)): ?>
								
								<?
									
									if($attach['title'] != '') $title = $attach['title'];
									else					   $title = $attach['name'];
								
								?>
								
									<div class="attached-document">
										<a data-ajax="false" href="/files/uploads/<?=$attach['name'];?>" title="<?=$title;?>">
											<span class="icon"><img src="/img/website/<?=$mimes[$attach['ext']];?>" alt=""></span><?=$title;?>
										</a>
										<span class="close-link"></span>
									</div>
									
								<? endif; ?>
									
								<? endforeach; ?>
								
									<div class="clear"></div>
									
								</div><!-- close documents-container -->
							</div> <!-- close contentents-files-documents -->
							
							<? endif; ?>								
							
						</li>
					
					<? endforeach; ?>
					
					</ul>
				
				<? else: ?>
				
					<div class="error-message">
						Nessun messaggio in bacheca.
					</div>
				
				<? endif; ?>
				
				</div>

			<div class="clear"></div>
			
</div><!-- close wrapper-box -->