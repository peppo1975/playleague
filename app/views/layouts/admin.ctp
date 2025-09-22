<? if (!isset($_GET['is_xls'])): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="IT" lang="IT">


		<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>Play League Sport | <?=$title_for_layout;?></title>
				<meta name="author" content="timmytag | web oriented services" />
				<meta name="keywords" content="" />
				<meta name="description" content="" />
						   
				<link rel="Shortcut Icon" type="image/x-icon" href="/img/favicon.ico" />
				<link href="/css/layout_admin.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="/css/layout_admin_private.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="/css/Aristo/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="/css/timmygallery.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="/css/timmybox.css" rel="stylesheet" type="text/css" media="screen" />
				
							<!--[if lt IE 7.]>
									<script defer type="text/javascript" src="js/pngfix.js"></script>
									<link href="/css/layout_ie.css" rel="stylesheet" type="text/css" media="screen" />
							<![endif]-->
							
				<script type="text/javascript">var layout = "desktop";</script>
							
				<script type="text/javascript" src="/js/jquery-1.5.1.min.js"></script>
				<script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
				<script type="text/javascript" src="/js/jquery.form.js"></script>
				<script type="text/javascript" src="/js/eye.js"></script>	
				<script type="text/javascript" src="/js/colorpicker.js"></script>	
				<script type="text/javascript" src="/js/layout_admin.js"></script>	
				<script type="text/javascript" src="/js/swfobject.js"></script>	
				
				<!--GIUSEPPE 2024-08-31 *******************-->
                <script src="/js/script_my.js" type="text/javascript"></script>
	<!--			
				<script src="/js/mobiscroll/dev/js/mobiscroll.core-2.3.1.js" type="text/javascript"></script>
				<script src="/js/mobiscroll/dev/js/mobiscroll.core-2.3.1-it.js" type="text/javascript"></script>
				
				<link href="/js/mobiscroll/dev/css/mobiscroll.core-2.3.1.css" rel="stylesheet" type="text/css" />
				
				<script src="/js/mobiscroll/dev/js/mobiscroll.datetime-2.3.js" type="text/javascript"></script>
				<script src="/js/mobiscroll/dev/js/mobiscroll.datetime-2.3-it.js" type="text/javascript"></script>
				<script src="/js/mobiscroll/dev/js/mobiscroll.jqm-2.3.js" type="text/javascript"></script>
				
				<link href="/js/mobiscroll/dev/css/mobiscroll.jqm-2.3.css" rel="stylesheet" type="text/css" />				
-->
				<!-- Add mousewheel plugin (this is optional) -->
				<script type="text/javascript" src="/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
			
				<!-- Add fancyBox main JS and CSS files -->
				<script type="text/javascript" src="/js/fancybox/source/jquery.fancybox.js?v=2.1.3"></script>
				<link rel="stylesheet" type="text/css" href="/js/fancybox/source/jquery.fancybox.css?v=2.1.2" media="screen" />
				
				<? if($layout == "tablet"): ?>
				
				<script type="text/javascript" src="/js/timmy_tablet.js"></script>
				
				<? else: ?>
				
				<script type="text/javascript" src="/js/timmy.js"></script>
				
				<? endif; ?>
				
				<?=$scripts_for_layout;?>
				
				<script type="text/javascript">
				
					<?
					
						if (isset($currentUser) && ($currentUser['User']['group_id'] == 9 || $currentUser['User']['group_id'] == 11)) {
						
						$admin_type = 'web';
						
						} else {
					
						$admin_type = 'champ';
						
						}
						
						if ($session->check('admin_type')) {
						
							$admin_type = $session->read('admin_type');
							
						}
						
						/*
						$admin_data_type = 'current';
						
						if ($session->check('admin_data_type')) {
						
							$admin_data_type = $session->read('admin_data_type');
							
						}*/	
						
						if($admin_data_type == "")
							$admin_data_type = "current";					
						
					?>
				
					var admin_type = '<?=$admin_type;?>';
				
				</script>
				
				<? if($layout == "tablet"): ?>
				
					<link href="/css/layout_admin_tablet.css" rel="stylesheet" type="text/css" media="screen" />
					<script type="text/javascript">
					
						$(function(){
						
							$('a').die('mouseover').live('mouseover', function(){
							
								var me = $(this);
								
									if(me.attr('href') != "" && me.attr('href') != undefined && me.attr('href') != "undefined") {
										me.trigger('click');
									}
							
							});
							
							/*
							if($('table td.tools:eq(1)').attr('style') != "") {
								
								//$('table td.tools').css('min-width', $('table td.tools').css('min-width')+90);
							}*/
						
						});
					
					</script>
				
				<? endif; ?>					 
		</head>
		<body>
		
		<div id="timmyloader"><img src="/img/timmyshare/preloader.gif" alt="" /></div>
		<div id="timmybox"></div>

		<div id="container">	
			<div id="top">
				<ul class="left">
				
				<? if(isset($currentUser) && in_array($currentUser['User']['group_id'], Configure::read('admin_group_id'))): ?>
				
					<li class="select_mode">
						<form>
							<select id="switch_admin">
								<option value="champ" <? if ($admin_type == "champ") { print "selected=\"selected\""; } ?>>Gestionale League</option>
								<option value="web" <? if ($admin_type == "web") { print "selected=\"selected\""; } ?>>Sito internet</option>
							</select>
						</form>						
					</li>
			
				<? endif; ?>
					
					<? if (isset($currentUser)): ?>
					
					<? if(in_array($currentUser['User']['group_id'],Configure::read('admin_group_id')) && $admin_type == "champ"): ?>
					
						<li class="select_mode" data-user="<?=$admin_data_type;?>">
							<form autocomplete="off">
								<select id="switch_data_type">
									<option value="all" <? if ($admin_data_type == "all") { print "selected=\"selected\""; } ?>>Tutti i dati</option>
									<option value="current" <? if ($admin_data_type == "current") { print "selected=\"selected\""; } ?>>Dati utente corrente</option>
								</select>
							</form>	
						</li>	

					<? endif; ?>
						
						<?=$this->element('admin/top_panel');?>
						
					<? endif; ?>
				
				</ul>
				<div class="clear"></div>
			</div><!-- close top -->
			
			<div id="header">
					<ul class="admin-navigation">
						<li class="logo"><img src="/img/logo_playleague_admin.png" width="35" height="35" alt="Play League Sport - Firenze" /></li>						
						
						<? if (isset($currentUser)): ?>
						
						<?
						
						if($currentUser['User']['Nomegruppo'] == 'News') $admin_type = 'web';
						
						?>

									<?=$this->element('admin/head_menu_' . $admin_type);?>
						
						<? endif; ?>
						
					</ul>
			</div><!-- close header -->
			
			<div id="body_page">
				
				<div id="contents">
					
					
					<?=$content_for_layout;?>
					
					
				</div><!-- close contents-->
				<div class="clear"></div>
				<!--
				<?=$this->element('sql_dump');?>
				-->
			</div><!-- close body_page -->
			
			<div id="bottom">
				<ul class="left">
					<li>Play League SSDARL | via G. Pagnini, 13/R - Firenze</li>
				</ul>
				<ul class="right">
					<li><a href="" title="Play League SSDARL &copy; 2023">Play League SSDARL &copy; 2023</a></li>
				</ul>
				<div class="clear"></div>
			</div>

		</div><!-- close container -->


		</body>


</html>
<? else: ?>

<?=$content_for_layout;?>

<? endif; ?>
