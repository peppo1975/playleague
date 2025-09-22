<?

$fixed = $this->requestAction('fixeds/read_all_fixed');//GIUSEPPE 2018-08-28 --richiama la tabella dei contenuti fissi

if (empty($classPage))
{
    $classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // GIUSEPPE // mi restituisce primary, secondary o quaternary 
}
?>

<footer id="footer" class="light">
	
	<section class="socials">
		<div class="container">
			<div class="row">
				
				<!--
					<div class="footer-ribbon">
					<span>Get in Touch</span>
					</div>
					
				-->

				<div class="col-md-3">
					
					<? if($classPage['Name']=='primary'): //GIUSEPPE?> 
					
					<!-- SEZIONE NEWSLETTER, PER LA SEZIONE CAMPIONATI / TORNEI -->
					<div class="primary newsletter"> 
						<h4>Newsletter</h4>
						<p>Iscriviti alla nostra newsletter per restare aggiornato sulle novità del mondo Play League</p>
						
						<div class="alert alert-success hidden" id="newsletterSuccess">
							<strong>Grazie!</strong> La tua iscrizione &agrave; stata effettuata.
						</div>
						
						<div class="alert alert-danger hidden" id="newsletterError"></div>
						
						<form id="newsletterForm" action="/newsletter_users/addUser" method="GET">
							<div class="input-group">
								<input class="form-control" placeholder="Indirizzo e-mail" name="newsletterEmail" id="newsletterEmail" type="text">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit">Iscriviti</button>
								</span>
							</div>
						</form>
					</div>

					<!-- WIDGET FACEBOOK MIDLAND GLOBAL SPORT SQUADRA FUSTAL -->
					<? elseif($classPage['Name']=='secondary'):?> 
					<div  class="secondary fb-page" data-href="https://www.facebook.com/MidlandGlobalSport/?fref=ts" data-width="261" data-height="280" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"> 
						<div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/MidlandGlobalSport/?fref=ts"><a href="https://www.facebook.com/MidlandGlobalSport/?fref=ts">Midland squadra</a></blockquote>
						</div>
					</div>

					<!-- SEZIONE NEWSLETTER, PER LA SEZIONE TENNIS -->
					<? elseif($classPage['Name']=='quaternary'):?> 
					<div class="quaternary newsletter"> 
						<h4>Newsletter</h4>
						<p>Iscriviti alla nostra newsletter per restare aggiornato sulle novità del mondo Midland</p>
						
						<div class="alert alert-success hidden" id="newsletterSuccess">
							<strong>Grazie!</strong> La tua iscrizione &agrave; stata effettuata.
						</div>
						
						<div class="alert alert-danger hidden" id="newsletterError"></div>
						
						<form id="newsletterForm" action="/newsletter_users/addUser" method="GET">
							<div class="input-group">
								<input class="form-control" placeholder="Indirizzo e-mail" name="newsletterEmail" id="newsletterEmail" type="text">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit">Iscriviti</button>
								</span>
							</div>
						</form>
					</div>
					<? endif ?>
				</div>
				
				
				
				<!-- SEZIONE WIDGET TWITTER -->
				   <style>
						#twitter-widget-0{
					    	height: 280px !important;
					    }
					</style>
				<div class="col-md-3">
					<!-- WIDGET TWITTER CAMPIONATI / TORNEI -->
					<? if($classPage['Name']=='primary'): //GIUSEPPE?> 
					<h4>Instagram</h4>
					<!-- LightWidget WIDGET --><script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="https://cdn.lightwidget.com/widgets/5ffd2860873b5907ad4192126454d9c7.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe>

					
					<!-- WIDGET TWITTER SCUOLA CALCIO A 5 -->
					<? elseif($classPage['Name']=='secondary'):?>
					
					<!-- WIDGET FACEBOOK TENNIS -->
					<? elseif($classPage['Name']=='quaternary'):?>
					<? endif ?>			
				</div>
						
				<!-- SEZIONE WIDGET FACEBOOK -->
				<div class="col-md-3">
					
						<!-- WIDGET FACEBOOK PLAY LEAGUE SPORT UFFICIALE -->
						<? if($classPage['Name']=='primary'): //GIUSEPPE?>
						<h4>Facebook</h4>
						<div class="primary fb-page" data-href="https://www.facebook.com/PlayLeagueSport" data-width="261" data-height="350" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true">
							<div class="fb-xfbml-parse-ignore">
								<blockquote cite="https://www.facebook.com/PlayLeagueSport">
									<a href="https://www.facebook.com/PlayLeagueSport">Midland GS</a>
								</blockquote>
							</div>
						</div>
						
						<div id="fb-root"></div>	
						<script>(function(d, s, id)
							{
								var js, fjs = d.getElementsByTagName(s)[0];
								if (d.getElementById(id)) return;
								js = d.createElement(s); js.id = id;
								js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.5&appId=135487336533422";
								fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));
						</script>
						
						<!-- WIDGET FACEBOOK SCUOLA CALCIO A 5 UFFICIALE -->
						<? elseif($classPage['Name']=='secondary'):?>
						
						<? endif ?>
				</div>
				
				
				<!-- SEZIONE CONTATTI -->
				<div class="col-md-3">
					
					<div class="contact-details">
						<h4>Contattaci</h4>
						<ul class="contact">
							<li><p><i class="fa fa-map-marker"></i> <strong>Indirizzo:</strong> <?= $fixed['societa_indirizzo'] ?></p></li>
							<li><p><i class="fa fa-phone"></i> <strong>Telefono:</strong> <?= $fixed['societa_telefono'] ?></p></li>
							
							<!-- CONTATTI MIDLAND GLOBAL SPORT, SPECIFICI -->
							<? if($classPage['Name']=='primary'): //GIUSEPPE?>
							<li class="primary"><p><i class="fa fa-whatsapp"></i> <strong><?= $fixed['referente_midlandsport_nome'] ?>:</strong> <?= $fixed['referente_midlandsport_telefono'] ?></p></li>
							<li class="primary"><p><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:<?= $fixed['email_midlandsport'] ?>"><?= $fixed['email_midlandsport'] ?></a></p></li>
							
							<!-- CONTATTI SCUOLA CALCIO A 5, SPECIFICI -->
							<? elseif($classPage['Name']=='secondary'):?>
							<li class="secondary"><p><i class="fa fa-phone"></i> <strong><?= $fixed['referente_midlandgs_nome'] ?>:</strong> <?= $fixed['referente_midlandgs_telefono'] ?></p></li>
							<li class="secondary"><p><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:<?= $fixed['email_midlandgs'] ?>"><?= $fixed['email_midlandgs'] ?></a></p></li>
							
							<!-- CONTATTI TENNIS, SPECIFICI -->
							<? elseif($classPage['Name']=='quaternary'):?>
							<li class="quaternary"><p><i class="fa fa-phone"></i> <strong><?= $fixed['referente_mgstennis_nome'] ?>:</strong> <?= $fixed['referente_mgstennis_telefono'] ?></p></li>
							<li class="quaternary"><p><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:<?= $fixed['email_mgstennis'] ?>"><?= $fixed['email_mgstennis'] ?></a></p></li>
							<? endif ?>
						</ul>
					</div>

					<!-- SOCIAL ICONS -->
					
					<h4 class="follow-us">Seguici su:</h4> 
					<ul class="social-icons">
						
						<!-- ICONE SOCIAL MIDLAND GLOBAL SPORT -->
						<? if($classPage['Name']=='primary'): //GIUSEPPE?>
						<li class="primary social-icons-facebook"><a href="<?= $fixed['url_playleaguesport_facebook'] ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
						<li  class="primary social-icons-instagram"><a href="<?= $fixed['url_playleaguesport_instagram'] ?>"  target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
					
						<!-- ICONE SOCIAL SCUOLA CALCIO A 5 -->
						<? elseif($classPage['Name']=='secondary'):?>	
						<!-- ICONE SOCIAL TENNIS -->
						<? elseif($classPage['Name']=='quaternary'):?>
						<? endif ?>
					</ul>

				</div>
			</div>
		</div>
	</section>


	<!-- SEZIONE SPONSOR, UTILIZZATA DA TUTTI E TRE I SITI-->
	<section class="section sponsors">	
		<div class="container">	
			<div class="row">
				<div class="col-lg-12">
					<div class="content-grid content-grid-dashed mt-xlg mb-lg">
						<?= $this->element("site/banner"); ?>
					</div>
				</div>
			</div>
		</div>		
	</section>
	
	<? if($classPage['Name']=='primary'): //GIUSEPPE?>
	<? elseif($classPage['Name']=='secondary'):?>
	<? elseif($classPage['Name']=='quaternary'):?>
	<? endif ?>
	
	<div class="footer-top" style="background-color: #1491CE;">
		<div class="container">
			<p style="margin-bottom: 15px; margin-top: 15px; color: #fff !important;">
				<img class="logo-regione" width="110" src="/img/logo-regione-toscana.jpg" style="margin-right: 15px; background: #fff; padding: 5px; border-radius: 5px;" /><small>Play League Sport SSD a RL comunica di aver ottenuto dalla Regione Toscana, il contributo di € 1.288,00 per il sostegno dell'attività sportiva anno 2024</small>
			</p>
		</div>
	</div>
	
	<div class="footer-copyright style-grey <?=$classPage['Name']?>">
		<div class="container">

			<div class="row">
				<div class="col-md-8">
					<a href="index.html" class="logo">
						<img alt="" class="" width="30" src="/img/logo_playleague_admin.png">
					</a>
					<? if($classPage['Name']=='primary'): //GIUSEPPE?>
					<p><a href="/contenuti/127/copyright"><?= $fixed['societa_nome'] ?> &copy; <?=date("Y");?></a>  - p.i. <?= $fixed['societa_p_iva'] ?></p>
					<? elseif($classPage['Name']=='secondary'):?>
					<p><a href="/contenuti/129/copyright"><?= $fixed['societa_nome'] ?> &copy; <?=date("Y");?></a>  - p.i. <?= $fixed['societa_p_iva'] ?></p>
					<? elseif($classPage['Name']=='quaternary'):?>
					<p><a href="/contenuti/129/copyright"><?= $fixed['societa_nome'] ?> &copy; <?=date("Y");?></a>  - p.i. <?= $fixed['societa_p_iva'] ?></p>
					<? endif ?>
					
					
				</div>
				<div class="col-md-4">
					<nav id="sub-menu">
						<ul>
							
							<? if($classPage['Name']=='primary'): //GIUSEPPE?>
							<li><a href="/contenuti/120/informazioni-sull-uso-dei-cookie">Informativa sull'uso dei cookie</a></li>
							<li><a href="/contenuti/126/privacy-policy">Privacy policy</a></li>
							<? elseif($classPage['Name']=='secondary'):?>
							<li><a href="/contenuti/130/informazioni-sull-uso-dei-cookie">Informativa sull'uso dei cookie</a></li>
							<li><a href="/contenuti/128/privacy-policy">Privacy policy</a></li>
							<? elseif($classPage['Name']=='quaternary'):?>
							<li><a href="/contenuti/130/informazioni-sull-uso-dei-cookie">Informativa sull'uso dei cookie</a></li>
							<li><a href="/contenuti/128/privacy-policy">Privacy policy</a></li>
							<? endif ?>
							
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	

	
</footer>
</div>

<!-- Vendor -->
<script src="/vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="/vendor/jquery-cookie/jquery-cookie.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/vendor/common/common.min.js"></script>
<script src="/vendor/jquery.validation/jquery.validation.min.js"></script>
<script src="/vendor/jquery.stellar/jquery.stellar.min.js"></script>
<script src="/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<script src="/vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="/vendor/isotope/jquery.isotope.min.js"></script>
<script src="/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="/vendor/vide/vide.min.js"></script>

<script src="/js/custom.js"></script>
<!-- Theme Base, Components and Settings -->
<script src="/js/theme.js"></script>

<!-- Current Page Vendor and Views -->
<script src="/vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="/vendor/circle-flip-slideshow/js/jquery.flipshow.min.js"></script>
<script src="/js/views/view.home.js"></script>
<script src="/porto_admin/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>

<!-- Theme Custom -->


<!-- Theme Initialization Files -->
<script src="/js/theme.init.js"></script>

<script src="/js/layout_new.js"></script>
<script>
	
	
	
	var cur_index = 1;
	var imcoming = 0;
	// console.log('here');
	
	var scroller = setInterval(function() {
		// console.log(cur_index);
		imcoming = 1;
		$(".owl-dots .owl-dot:eq(" + cur_index + ") *").trigger('click');
		cur_index++;
		
		if (cur_index==6) cur_index=0;
		
	},4500);
	$(".owl-dots .owl-dot *").click(function() {
		// console.log('imcoming ' + imcoming);
		if (imcoming == 0) clearInterval(scroller);
		imcoming = 0;
		
	});
	
	// Gestione della newsletter
	$("#newsletterForm").submit(function(e){
		
		$.get($(this).attr("action") + "/" + $(this).find("#newsletterEmail").val(), "", function(response){
			response = JSON.parse(response);
			// console.log(response);
			if( response.aggiunto == 1 )
			{
				$("#newsletterSuccess").removeClass("hidden");
				// Ok
				setTimeout(function(){
					$("#newsletterSuccess").addClass("hidden");
				}, 1500);
				} else {
				
				for( field in response.aggiunto )
				{
					var desc = response.aggiunto[field];
					
					$("#newsletterError").html(desc + "<br>");
					
					
					
				}
				
				$("#newsletterError").removeClass("hidden");
				setTimeout(function(){
					$("#newsletterError").addClass("hidden");
				}, 1500);
			}
		});
		e.preventDefault();
	});
</script>



<?=$this->element('site/footer_scripts');?>

<script type="text/javascript">
	
	function stc(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	}
	
	function gtc(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return "";
	}
	$(document).ready(function() {
		
		
		if (!gtc("consent")) {
			
			$(".cookies").show();
		}
		
	});
</script>
<div class="alert alert-info fade in nomargin cookies" style="position: fixed; bottom: 0; width: 100%; margin-bottom: 0px; z-index: 99999; display: none;">
	<p>
		
		Questo sito non utilizza dei cookie di profilazione di terze parti. 
		Sono utilizzati cookie tecnici di terze parti legati alla presenza dei “social plugin” e "Google Analytics". 
		Continuando la navigazione accetti il nostro uso dei cookie, per ulteriori dettagli e per disabilitare le tipologie di cookie consulta la nostra <a href="/contenuti/120/informazioni-sull-uso-dei-cookie" class="primary">informativa estesa</a> <a href="/contenuti/130/informazioni-sull-uso-dei-cookie" class="secondary" style="display: none;">informativa estesa</a>.
		
	</p>
	<p class="primary">
		<button class="btn btn-info btn-sm mt-xs mb-xs" type="button" onclick="$('.cookies').hide();stc('consent',1,30);">OK, Acconsento</button>
		<button class="btn btn-default btn-sm mt-xs mb-xs" type="button" onclick="location.href = '/contenuti/120/informazioni-sull-uso-dei-cookie';">No, voglio pi&ugrave; informazioni</button>
	</p>
	<p class="secondary" style="display: none;">
		
		<button class="btn btn-info mt-xs mb-xs" type="button" onclick="$('.cookies').hide();stc('consent',1,30);">OK, Acconsento</button>
		<button class="btn btn-default mt-xs mb-xs" type="button" onclick="location.href = '/contenuti/130/informazioni-sull-uso-dei-cookie';">No, voglio pi&ugrave; informazioni</button>
	</p>
	<p class="quaternary" style="display: none;">
		
		<button class="btn btn-info mt-xs mb-xs" type="button" onclick="$('.cookies').hide();stc('consent',1,30);">OK, Acconsento</button>
		<button class="btn btn-default mt-xs mb-xs" type="button" onclick="location.href = '/contenuti/130/informazioni-sull-uso-dei-cookie';">No, voglio pi&ugrave; informazioni</button>
	</p>
</div>

</body>
</html>									