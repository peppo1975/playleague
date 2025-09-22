<!DOCTYPE html> 
<html> 
<head> 

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?=$title_for_layout;?></title>
			
	<?=$this->element('site/mobile/head');?>
	
</head> 
<body> 

<div data-role="page" id="cacheMe" data-dom-cache="false">

	<div data-role="content" style="padding: 0;">
	

		<?=$content_for_layout;?>

		
	</div><!-- /content -->

</div><!-- /page -->

</body>
</html>
