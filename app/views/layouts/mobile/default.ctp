<!DOCTYPE html> 
<html> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<title><?=$title_for_layout;?></title>
			
	<?=$this->element('site/mobile/head', array('scripts_for_layout' => $scripts_for_layout));?>
	<?=$this->element('site/metadata');?>
	<?=$this->element('site/metadata_facebook');?>
	
</head> 
<body> 

<div data-role="page" data-dom-cache="never" data-theme="a">
<div id="main-container">
	<?=$this->element('site/mobile/header');?>

	<div data-role="content" <? if ($_SERVER['REQUEST_URI'] == '/mobile'):?>id="index"<?endif;?>>
	
		<?=$content_for_layout;?>

	</div><!-- /content -->
</div>	
	<?=$this->element('site/mobile/footer');?>

</div><!-- /page -->

</body>
</html>
