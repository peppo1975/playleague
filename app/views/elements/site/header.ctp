<?php
	use PHPHtmlParser\Dom;
	
	$dom = new Dom; 
	
	$header_links = array();
	
	$menu_pd = [
	"scuola calcio a 5",
	"la scuola",
	"prima squadra",
	"il calcio a 5",
	"Juoniores Regionali",
	"modulistica",
	"costi",
	"juniores",
	"news" 
	];

	$fixed = $this->requestAction('fixeds/read_all_fixed');
	
	if(empty($classPage)){
		$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller
	}
	
	//print_r(	$classPage);
?>

<header id="header" class="header-no-border-bottom" data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 161, "stickySetTop": "-161px", "stickyChangeLogo": false}'>
	<div class="header-body">
		<div class="header-top header-top-style-2">
			<div class="container">
				<p class="pull-left hidden-xs">
					<i class="fa fa-map-marker"></i> &nbsp; <?= $fixed['societa_indirizzo'] ?> &nbsp; &nbsp;
					<i class="fa fa-phone"></i> &nbsp; <?= $fixed['societa_telefono'] ?> &nbsp; &nbsp;
					<span class="hidden-sm">
                        <i class="fa fa-envelope"></i> &nbsp; 
                        <?php if ($classPage['Name'] == "primary")://if(isset($home)):  ?>
                            <a class="primary" href="mailto:<?= $fixed['email_midlandsport'] ?>"><?= $fixed['email_midlandsport'] ?></a>

                        <?php elseif ($classPage['Name'] == "secondary"): //else: ?>
                            <a class="secondary"  href="mailto:<?= $fixed['email_midlandgs'] ?>"><?= $fixed['email_midlandgs'] ?></a>
                            
                        <?php elseif ($classPage['Name'] == "quaternary"): ?>
                            <!-- <a class="quaternary"  href="mailto:<?= $fixed['email_mgstennis'] ?>"><?= $fixed['email_mgstennis'] ?></a> -->
                        <?php endif; ?>
					</span>
				</p>
				<div class="pull-right social-box">
					<ul class="social-icons">
						<?php if($classPage['Name']=="primary")://if(isset($home)): ?>
						
						<li class="primary social-icons-facebook">
							<a href="<?= $fixed['url_playleaguesport_facebook'] ?>" target="_blank" title="Pagina facebook">
								<i class="fa fa-facebook"></i>
							</a>
						</li>
						<li  class="primary social-icons-instagram">
							<a href="<?= $fixed['url_playleaguesport_instagram'] ?>"  target="_blank" title="Pagina instagram">
								<i class="fa fa-instagram"></i>
							</a>
						</li>					
						<?php elseif($classPage['Name']=="secondary"): ?>

						<?php elseif($classPage['Name']=="quaternary"):?>
						<?php endif; ?>
					</ul>
					
					<div class="header-search hidden-xs">
						<?php  //GIUSEPPE 22/09/2016
							$pageSearch = "/sections/searchcampionati";
							
							switch($classPage['Name'])
							{
								case 'prymary':
								$pageSearch = "/sections/searchcampionati";
								break;
								
								case 'secondary':
								$pageSearch = "/sections/searcha5";
								break;
								
								case 'quaternary':
								$pageSearch = "/sections/searchcampionati";
								break;
							}
							
						?>
						
						<form class="<?=$classPage['Name']?>" id="searchForm" action="<?=$pageSearch?>" method="post" novalidate="novalidate">
							<div class="input-group">
								<input type="text" name="data[Search][value]" class="form-control" name="q" id="q" placeholder="Cerca nel sito..." required="" aria-required="true">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</form>
						
						
						
						
					</div>
					
					</div>
						</div>
		</div>
		<div class="header-container container">
			<div class="visible-xs" style="text-align: center">
				<a id="logo-mobile" href="/">
                    <img alt="" height="65" src="/img/logo-playleaguesport.png">
                </a>
			</div>
			<div class="header-row hidden-xs">
				<div class="header-column">
					<div class="header-logo">
						<a id="logo" href="/">
							<img alt=""  height="80" src="/img/logo-playleaguesport.png">
						</a>
					</div>
				</div>
				<div class="header-column">
					<ul class="header-extra-info hidden-xs">
						
					<!-- <li>                           
                            <a href="<?= $fixed['url_midlandsport'] ?>">
                                <img src="/img/logo-calcio.jpg" width="60" />
                                <h2 data-menu="primary" class="menu-primary">Calcio</h2>
                            </a>
                        </li> -->
						
                      	<!-- <li>
                            <a href="<?= $fixed['url_midlandgs'] ?>">
                                <img src="/img/logo-scuola-c5.jpg" width="60" />
                                <h2 data-menu="secondary" class="menu-secondary">Futsal School</h2>
                            </a>
                        </li> -->
						
                      <!-- <li>
                            <a href="<?= $fixed['url_mgstennis'] ?>">
                                <img src="/img/logo-tennis.jpg" width="60" />
                                <h2 data-menu="quaternary" class="menu-quaternary">Tennis</h2>
                            </a>
                        </li> -->

                      <!-- <li>
                            <a href="<?= $fixed['url_midlandsport'] ?>/contenuti/196/le-manifestazioni-di-basket">
                                <img src="/img/logo-basket.jpg" width="60" />
                                <h2 data-menu="" class="menu-primary">Basket</h2>
                            </a>
                        </li> -->
						
                        <!-- <li>
                            <a href="<?= $fixed['url_mgstennis'] ?>/contenuti/213/le-manifestazioni-di-padel">    
                                <img src="/img/logo-padel.jpg" width="60" />
                                <h2 data-menu="" class="menu-quaternary">Padel</h2>
                            </a>
                        </li>  -->
						
                       <!-- <li>
                            <a href="<?= $fixed['url_midlandsport'] ?>/contenuti/195/le-manifestazioni-di-bv">
                                <img src="/img/logo-volley.jpg" width="60" />
                                <h2 data-menu="" class="menu-primary">Beach Volley</h2>
                            </a>
                        </li>  -->

                       <!-- <li>   
                            <a href="<?= $fixed['url_shop_online'] ?>">
                                <img src="/img/logo-store.jpg" width="60" />
                                <h2 data-menu="tertiary" class="menu-tertiary">Shop online</h2>
                            </a>
                        </li> -->
						
					</ul>
				</div>
			</div>
		</div> 
		
		<?=$this->element('site/menu', ['classPage' => $classPage]);//è la barra dei menu //=$this->element('site/menu_test_giuseppe');?>
		
		<div class="header-container header-nav header-nav-bar header-nav-bar-tertiary " style="display: none !important;">
			<div class="container">
				
				<button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
					<i class="fa fa-bars"></i>
				</button>
				<div class="header-nav-main header-nav-main-light header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
					<nav>
						<ul class="nav nav-pills" id="mainNav">
							<?php foreach($header_links as $link) : 
								if($link->find("a")->getAttribute("title") == "Catalogo")
								{
									$sub_links = $link->find("li", 1);
									$sub_links->find("a", 0)->setAttribute("title", "test");
									
									print str_replace("Nuovi prodotti", "Prodotti", $sub_links);
									
									
								}
								else
								{
									print $link;
								}
							endforeach; ?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</header>
<script>
	$(function(){
		$(document).on("change", "#menu-select", function(){
			document.location.href = $(this).find("option:selected").val();
		});
	});
</script>
