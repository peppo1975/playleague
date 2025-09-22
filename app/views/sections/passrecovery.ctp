<div class="wrapper-box">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			
			<div class="contents-box" id="bg-retino">

				<h1>Modulo di recupero password</h1>
				<div class="clear"></div>
				<div class="ui-tabs-container ui-tab-passrecovery">

						
							<form method="post" id="recoverUser" action="/sections/passrecovery/user">
							
							<div class="input required">
							
								<label>Indirizzo e-mail</label>
								<input type="text" class="text" name="data[User][username]" />
								<div class="error-message">&nbsp;</div>
							</div>
							
							<div class="clear"></diV>
							
							<div class="input required">
								
								<label>Nome</label>
								<input type="text" class="text" name="data[User][nome]" />
								<div class="error-message">&nbsp;</div>
							
							</div>
							
							<div class="input required">
						
								<label>Cognome</label>
								<input type="text" class="text" name="data[User][cognome]" />
								<div class="error-message">&nbsp;</div>
								
							</div>
						
							<div class="input">
								<input type="submit" class="submit" value="Recupera password" />
								
							</div>
					
						
						</form>

				</div> <!-- close ui-tabs-container -->
			</div><!-- close contents-box -->
		</div>
	<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->
