									<? $login_data = $session->read('Login.data'); ?>
									
			
									
									<? if (empty($login_data)): ?>
									<li><a href="javascript:;" class="login-open" title="Login">Login</a>
									
									<div class="login-form">
									
										<form method="post" id="loginForm" action="<?=$_SERVER['REQUEST_URI'];?>">
									
											<div class="input required">
											
												<label>Indirizzo e-mail</label>
												<input type="text" class="text login_username" name="data[Login][username]"/>
												<div class="error-message">&nbsp;</div>
								
											</div>
											<div class="clear"></div>
											
											<div class="input required">
											
												<label>Password</label>
												<input type="password" class="text login_password" name="data[Login][password]"/>
												<div class="error-message">&nbsp;</div>
								
											</div>
											<div class="clear"></div>
													
											<div class="input">
													<input type="submit" value="Login" />	
													<a class="forgotten-password" title="Recupera la tua password di accesso!" href="/sections/passrecovery">Recupero password</a>
													<div class="clear"></div>
											</div>
										</form>													
									</div>
									
								
									
									</li>
									
									<? else: ?>
										<li><a href="/area/riservata" title="accedi al tuo pannello di controllo">Benvenuto <?=$login_data['nome'];?> <?=$login_data['cognome'];?></a></li>
									<? endif; ?>
									