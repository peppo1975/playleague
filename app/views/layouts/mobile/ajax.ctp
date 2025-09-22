<div data-role="page" data-cache="never">
<div id="main-container">
	<?=$this->element('site/mobile/header');?>
	
	<div data-role="content" <? if ($_SERVER['REQUEST_URI'] == '/mobile'):?>id="index"<?endif;?> data-theme="a">
		
		<?=$content_for_layout;?>
		
	</div>
</div>	
	
	<?=$this->element('site/mobile/footer');?>
	
</div>