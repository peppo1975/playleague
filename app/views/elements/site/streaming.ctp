<? if($layout == "desktop"): ?>
								
<?

	$stream = $this->requestAction('/streams/getStream');
	
?>
								
<div id="streaming-box">								
				<div id="player-video">
						<div id="player-container">
						
							<? if($stream['Stream']['embed'] == 1): ?>
							
								<?=$stream['Stream']['link'];?>
							
							<? endif;?>
						
						</div>
						
						<? if($stream['Stream']['embed'] == 0): ?>

						<script type="text/javascript">
							$(function() {
								
								jwplayer("player-container").setup({
									file: "<?=$stream['Stream']['file'];?>",
									flashplayer: "http://<?=$_SERVER['SERVER_NAME'];?>/player.swf",
									skin: "http://<?=$_SERVER['SERVER_NAME'];?>/skin/lulu.zip",
									streamer: "<?=$stream['Stream']['link'];?>",
									autostart: "true",
									stretching: "fill",
									height: 300,
									width: 547
								});
								
							});
							
						</script>											
						<div id="interactive-bar">
							<div id="embed-code">
								<span>&lsaquo; embed &rsaquo;</span>
								<input type="text" id="embed-code-text" value="<iframe style='border: none; overflow: hidden;' src='http://<?=$_SERVER['HTTP_HOST'];?>/streams/embedStreaming/<?=$stream['Stream']['id'];?>' width='640' height='370'></iframe>" />
							</div><!-- close embed-code -->											
						</div><!-- close interactive-bar -->	

						<? endif; ?>
							
				</div><!-- close player-video -->	
</div><!-- close streaming div -->

<? else: ?>

<?

	App::Import('Model','Block');
	$block_tmp = new Block;
	
	$data = $block_tmp->read(null, Configure::read('player_block_id'));
	
?>

<div id="streaming-box">								
		<div id="player-video">
				<div id="player-container">
				
					<? if($data && isset($data['UploadYt']) && !empty($data['UploadYt'])): ?>
					
						<? foreach($data['UploadYt'] as $up): ?>
						
							<? $ytid = getYoutubeId($up['name']); ?>
						
						<? endforeach; ?>
						
						<? if(isset($ytid)): ?>
						
							 <iframe src="http://www.youtube.com/embed/<?=$ytid;?>?rel=0" frameborder="0" width="560" height="339"></iframe>						
						
						<? endif; ?>
						
					<? endif; ?>
				
				</div>	
		</div><!-- close player-video -->	
</div><!-- close streaming div -->

<? endif; ?>