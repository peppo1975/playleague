<? //GIUSEPPE 2016_09_19 ?>
<? //$main_menu = $this->requestAction('/pages/getMenu'); ?>
<? //$className = array('primary','secondary','quaternary'); ?>
<? //$serverName = array('midland2016.timmytag.it','midlandgs.it','mgstennis.it');?>
<?
	//$key = "";
	//$menu = "";
	//foreach($serverName as $indexServer  => $server)
	//{
	//	if($_SERVER["SERVER_NAME"] == $serverName[$indexServer])
	//	{
	//		//echo $_SERVER["SERVER_NAME"];
	//		$key = $indexServer;
	//		//$menu = $main_menu[$key];
	//		//echo json_encode($main_menu);
	//	}
	//};
	
	
	// $class restituisce Name e Key	(primary, secondary o quaternary) e 0,1 o 2

	$fixed = $this->requestAction('fixeds/read_all_fixed');
	
	$main_menu = $this->requestAction('/pages/getMenu');

	if(empty($classPage)){
		$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller
	}
	$menu = $main_menu[$classPage['Key']];
	
?>
<? //GIUSEPPE 2017-02-16 ricavo il nome del file associato all'avatar partendo dall'id atleta
	function read_file_avatar($id)
	{
		
		$row = array();
		
		$img['path'] = "/img/website/icon_profile_default.png"; // nel caso non ci sia nessun avatar, inserisco l'immagine di default
		
		$img['ext'] = 'png';
		
		$query = "SELECT COUNT(path) as num_file, ext, path FROM files WHERE athlete_id = '$id'";
		
		$result = mysql_query($query);
		
		$row = mysql_fetch_assoc($result);
		
		if($row['num_file'] > 0)
		{
			$img['path'] = $row['path'];
			
			$img['ext'] = $row['ext'];
		}
		
		return $img;
	}
	
	
?>
<div class="header-container header-nav header-nav-bar header-nav-bar-<?=$classPage['Name']//$className[$key];?>">
	<div class="container">
		<style>
			.select2-container{
			width: 100% !important;
			}
		</style>
		
		
        <!-- MENU' MOBILE SWITCH SU MOBILE -->
        <div class="row">
            <div class="col-xs-10">
              <!-- <div class=" visible-xs visible-sm" style="    margin: 12px 0 8px 15px; width: 80%">
                    <select name="menu" id="menu-select" class="form-control" data-plugin-selectTwo>
                    <?php if($classPage['Name'] == "primary"): ?>
                    <option value="<?= $fixed['url_midlandsport'] ?>" selected>Calcio</option>
                    <? else: ?>
                    <option value="<?= $fixed['url_midlandsport'] ?>">Calcio</option>
                    <? endif; ?>
                    
                    <?php if($classPage['Name'] == "secondary"): ?>
                    <option value="<?= $fixed['url_midlandgs'] ?>" selected>Futsal School</option>
                    <? else: ?>
                    <option value="<?= $fixed['url_midlandgs'] ?>" >Futsal School</option>
                    <? endif; ?>
                    
                    <?php if($classPage['Name'] == "quaternary"): ?>    
                    <option value="<?= $fixed['url_mgstennis'] ?>" selected>Tennis</option>
                    <? else: ?>
                    <option value="<?= $fixed['url_mgstennis'] ?>" >Tennis</option>
                    <? endif; ?>

                        <?php if ($classPage['Name'] == "basket"): ?>
                            <option value="<?= $fixed['url_midlandsport'] ?>/contenuti/196/le-manifestazioni-di-basket" selected>Basket</option>
                        <? else: ?>
                            <option value="<?= $fixed['url_midlandsport'] ?>/contenuti/196/le-manifestazioni-di-basket">Basket</option>
                        <? endif; ?>

                        <?php if ($classPage['Name'] == "padel"): ?>
                            <option value="<?= $fixed['url_mgstennis'] ?>/contenuti/213/le-manifestazioni-di-padel" selected>Padel</option>
                        <? else: ?>
                            <option value="<?= $fixed['url_mgstennis'] ?>/contenuti/213/le-manifestazioni-di-padel">Padel</option>
                        <? endif; ?>

                        <?php if ($classPage['Name'] == "volley"): ?>
                            <option value="<?= $fixed['url_midlandsport'] ?>/contenuti/195/le-manifestazioni-di-bv" selected>Beach Volley</option>
                        <? else: ?>
                            <option value="<?= $fixed['url_midlandsport'] ?>/contenuti/195/le-manifestazioni-di-bv">Beach Volley</option>
                        <? endif; ?>
                    
                    <option value="<?= $fixed['url_shop_online'] ?>">Shop online</option>
                    
                    </select>
                </div> -->
				
            </div>
            <div class="col-xs-2">
                <button class="btn header-btn-collapse-nav pull-right" data-toggle="collapse" data-target=".header-nav-main">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- ------------------------------------------------- -->
		
		
		
		
		<div class="header-nav-main header-nav-main-light header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
			<nav>
				<ul class="nav nav-pills" id="mainNav">
					<?php if($classPage['Name'] == "primary"): ?>
					<li><a href="/#menu=primary">Home</a></li>
					<?php elseif($classPage['Name'] == "secondary"/*$className[$key] == "secondary"*/): ?>
					<li><a href="/#menu=secondary">Home</a></li>
					<?php elseif($classPage['Name'] == "quaternary"/*$className[$key] == "quaternary"*/): ?>
					<li><a href="/#menu=quaternary">Home</a></li>
					<?php endif; ?>
					<?//echo json_encode($menu);?>
					<? foreach ($menu['children'] as $first_level): ?>
					<li <? if (!empty($first_level['children']) && count($first_level['children'])): ?>class="dropdown  <? if (count($first_level['children'][0]['children'])): ?>dropdown-mega<? endif;?>"<? endif;?>><a <? if (!empty($first_level['children']) && count($first_level['children'])): ?>class="dropdown-toggle" href="javascript:;"<? endif;?>href="<?=$first_level['url'];?>"><?=$first_level['name'];?></a>
						
						<? if (!empty($first_level['children']) && count($first_level['children'])): ?>
						<ul class="dropdown-menu">
							
							<? if (!count($first_level['children'][0]['children'])): ?>
							<!-- drop down classico -->
							
							<? foreach ($first_level['children'] as $second_level): ?>
							<li>
								<a href="<?=$second_level['url'];?>">
									<?=$second_level['name'];?>
								</a>
							</li>
							<? endforeach; ?>
							
							<? else: ?>
							
							<!-- drop down mega -->
							
							<div class="dropdown-mega-content">
								<div class="row">
									
									<? foreach ($first_level['children'] as $second_level): ?>
									<div class="col-md-3">
										<span class="dropdown-mega-sub-title"><?=$second_level['name'];?></span>
										<ul class="dropdown-mega-sub-nav">
											<? foreach ($second_level['children'] as $last_children): ?>
											<li><a href="<?=$last_children['url'];?>"><?=$last_children['name'];?></a></li>
											<? endforeach; ?>
											
										</ul>
									</div>
									<? endforeach; ?>
									
									
								</div>
							</div>
							
							
							<? endif; ?>
						</ul>
						
						<? endif; ?>
					</li>
					
					<? endforeach; ?>
					
					<? if (1 || isset($key) && $key == 0): ?>
					<!-- SIGN IN BLOCK ///////////////////////////////////////////////////////////// -->
					
					<li class="dropdown dropdown-mega dropdown-mega-signin signin" id="headerAccount">
						
						<? if (!$this->Session->read('Login.data')): ?>
						<a class="dropdown-toggle" href="#">
							<i class="fa fa-user"></i> Accedi
						</a>
						<ul class="dropdown-menu">
							<li>
								<div class="dropdown-mega-content">
									<div class="row">
										<div class="col-md-12">
											
											<div class="signin-form">
												
												<div class="alert alert-danger error-box" style="display: none;">
													<p class="login-error" style="color: #a94442">Username o password errata</p>
													<p class="radio-error" style="color: #a94442">Devi selezionare un profilo</p>
												</div>
												
												<form action="<?=$_SERVER['REQUEST_URI'];?>" id="frmSignIn" method="post">
													<div class="row">
														<div class="col-md-12">
															<label>Seleziona profilo</label>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="input-group mb-md">
																<span class="input-group-addon">
																	<input type="radio" name="data[Login][type_login]" value="athlete" >
																</span>
																<input type="text" class="form-control" readonly="" value="Atleta">
															</div>
														</div><!---->
														
														<!--<div class="col-md-6" >
															<div class="input-group mb-md">
															<span class="input-group-addon">
															<input type="radio" name="data[Login][type_login]" value="">
															</span>
															<input type="text" class="form-control" readonly="" value="Utente">
															</div>
														</div>-->
														
														<div class="col-md-6" >
															<div class="input-group mb-md">
																<span class="input-group-addon">
																	<input type="radio" name="data[Login][type_login]" value="arb">
																</span>
																<input type="text" class="form-control" readonly="" value="Arbitro/Delegato">
															</div>
														</div>

														<div class="col-md-6" >
															<div class="input-group mb-md">
																<span class="input-group-addon">
																	<input type="radio" name="data[Login][type_login]" value="imp">
																</span>
																<input type="text" class="form-control" readonly="" value="Impianto sportivo">
															</div>
														</div>
														
													</div>
													
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<label>Indrizzo email</label>
																<input type="email" required name="data[Login][username]" value="" class="form-control input-lg" tabindex="1">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<a class="pull-right mt-none p-none" id="headerRecover" href="#">Password dimenticata?</a>
																<label>Password</label>
																<input type="password" required name="data[Login][password]" value="" class="form-control input-lg" tabindex="2">
															</div>
														</div>
													</div>
													
													
													<div class="row">
														<div class="col-md-6 text-center">
															<input type="submit" value="Login" class="btn btn-primary mb-xl pull-left col-xs-12" style="" data-loading-text="Loading...">
														</div>
													</div>
												</form>
												
												<p class="sign-up-info">Non hai ancora un account? <a href="#" id="headerSignUp" class="p-none m-none ml-xs">Registrati ora!</a></p>
												
											</div>
											
											<div class="signup-form">
												<div class="alert alert-danger error-box" style="display: none;">
													<p class="radio-error" style="color: #a94442">Devi selezionare un profilo</p>
												</div>
												<!-- <span class="dropdown-mega-sub-title">Seleziona tipologia utente</span> -->
												<span class="dropdown-mega-sub-title">Iscrizione atleti</span>
												<form action="/registrazione" id="frmSignUp" method="post">
													<!-- <div class="row">
														<div class="form-group">
															<div class="col-md-12">
																
																<div class="input-group">
																	<span class="input-group-addon">
																		<input type="radio" name="optionsRadios" value="/registrazione" id="optionsRadios1">
																	</span>
																	<input type="text" class="form-control" readonly="" value="Registrazione utente">
																</div>
																<span class="color-grey"><?=utf8_encode("? la prima volta su Midland Sport?")?></span>
																
															</div>
														</div>
													</div>
													<hr /> -->
													<div class="row" class="color-grey">
														<div class="form-group">
															<div class="col-md-12">
																<div class="input-group"  style="margin-bottom: 10px;">
																	<span class="input-group-addon">
																		<input type="radio" name="optionsRadios" value="/registrazione/atleti"  id="optionsRadios1" checked="checked" />
																	</span>
																	<input type="text" class="form-control" readonly="" value="Registrazione atleta" />
																</div>
																<!-- <span class="color-grey">Sei un atleta tesserato Midland Sport?</span> -->
																<span class="color-grey">Procedura valida per l'iscrizione di nuovi atleti alle manifestazioni sportive <strong><?= $fixed['societa_nome'] ?></strong>.</span>
															</div>
														</div>
													</div>
													
													<div class="row" style="margin-top: 20px;">
														<div class="col-md-6">

															<input type="submit" value="Registrati ora" class="btn btn-primary pull-left col-xs-12 mb-xl" data-loading-text="Loading...">
														</div>
														<div class="col-md-6">
														</div>
													</div>
												</form>
												
												<p class="log-in-info" style="text-align: left;"><?=utf8_encode("Sei gi&agrave; registrato?") ?><a href="#" id="headerSignIn" class="p-none m-none ml-xs">Accedi</a></p>
											</div>
											
											<div class="recover-form">
												<span class="dropdown-mega-sub-title">Recupera password</span>
												
												<div class="alert alert-danger recover-error" style="display: none;">I dati inseriti sono errati</div>
												
												<p>Completa il seguente modulo per recuperare le tue credenziali di accesso.</p>
												
												<div class="alert alert-success recover-success" style="display: none;"><?=utf8_encode("La sua procedura di recupero password &egrave; completata, ricever&agrave; al pi&ugrave; presto le nuove credenziali di accesso via e-mail")?></div>
												
												<form action="/sections/passrecovery/user" id="frmResetPassword" method="post">
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<label>Indirizzo email<sup>*</sup></label>
																<input type="email" name="data[User][username]" required value="" class="form-control input-lg">
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<label>Nome<sup>*</sup></label>
																<input type="text" required value="" name="data[User][nome]"  class="form-control input-lg">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<label>Cognome<sup>*</sup></label>
																<input type="text" required value="" name="data[User][cognome]" class="form-control input-lg">
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-12">
															<input type="submit" value="Invia richiesta" class="btn btn-primary pull-left mb-xl" data-loading-text="Caricamento...">
														</div>
													</div>
												</form>
												
												<p class="log-in-info"><?=utf8_encode("Sei gi&agrave; registrato? ") ?><a href="#" id="headerRecoverCancel" class="p-none m-none ml-xs">Accedi</a></p>
											</div>
											
										</div>
									</div>
								</div>
							</li>
						</ul>
					</li>
					
					
					<? else: ?>
					
					<? $user = $this->Session->read('Login.data'); ?>
					
					<? $avatar = read_file_avatar($user['id']);?>
					
					<li class="dropdown dropdown-mega dropdown-mega-signin signin logged" id="headerAccount">
						<a class="dropdown-toggle" href="javascript:;">
							<i class="fa fa-user"></i> <?=$user['nome'];?> <?=$user['cognome'];?>
						</a>
						<ul class="dropdown-menu">
							<li>
								<div class="dropdown-mega-content">
									
									<div class="row">
										<div class="col-md-8">
											<div class="user-avatar">
												<div class="img-thumbnail">
													<!--<img src="/assets/images/thumbs/e5337c3079baed3aeac1df080594b731.png" alt="">-->
													<?//if($this->data['Athlete']['avatar'] != ""):?>
													<?//=$thumbnail->show(array('path' => $this->data['Athlete']['avatar'], 'w' => 50, 'h' => 50, 'zc' => 1));?>
													<?//else:?>
													<?//=$thumbnail->show(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));?>
													<?//endif;?>
													<?=$thumbnail->show(array('path' => $avatar['path'], 'w' => 50, 'h' => 50, 'zc' => 1, 'f' =>  $avatar['ext']));?>
												</div>
												<p><strong><?=$user['nome'];?> <?=$user['cognome'];?></strong><span>
													
													<? if ($user['is_arbitro'] == 1): ?>
													Arbitro
													<? elseif($user["is_atleta"] == 1): ?>
													Atleta
													<?else: ?>
													Utente
													<?endif;?>
												</span></p>
											</div>
										</div>
										<div class="col-md-4">
											<ul class="list-account-options">
												<li>
													<a href="/area/riservata">Area riservata</a>
												</li>
												<li>
													<a href="/?logout=1">Log Out</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</li>
				</ul>
				<? endif; ?>
				<!-- SIGN IN BLOCK ///////////////////////////////////////////////////////////// -->
				<? endif; ?>
			</ul>
		</nav>
	</div>
</div>
</div>
