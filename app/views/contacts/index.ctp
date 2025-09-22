<?
	$fixed = $this->requestAction('fixeds/read_all_fixed');//GIUSEPPE 2018-08-28 --richiama la tabella dei contenuti fissi
?>

<!-- PAGINA CONTATTI SITO PLAY LEAGUE SPORT ---------------------------------------->
	<div role="main" class="main">
		
		<div style="background: #f5f5f5; width: 100%; min-height: 40px;" class="row">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb" style="margin-bottom: 0">
							<li><a href="/">Home</a></li>
							<li class="active">Contatti</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
					<div id="googlemaps" class="google-map" style="background-color: #f5f5f5 !important;"></div>

					<div class="container" id="main-custom">

						<div class="row">
							<div class="col-md-12">
								<h4 class="heading-primary mt-lg"><strong><?= $fixed['societa_nome'] ?></strong></h4>
								<p><?= $fixed['societa_subtitle'] ?></p>
								<hr>
							</div>
							<div class="col-md-6">

								

								<!-- <h4 class="heading-primary"><?= $fixed['sede_firenze_nord'] ?></h4> -->
								<ul class="list list-icons list-icons-style-3 mt-xlg">
									<li><i class="fa fa-map-marker"></i> <strong>Indirizzo: </strong> Via Dell’Argingrosso, 65/67 - Firenze (FI)</li>
									<li><i class="fa fa-phone"></i> <strong>Telefono:</strong> 055 0121293</li>
									<li><i class="fa fa-whatsapp"></i><strong>WhatsApp:</strong> 392 2307347</li>
									<li>&nbsp;</li>
									
								</ul>
							</div>
							<div class="col-md-6">

								<h4 class="heading-primary">Orari:</h4>
								<ul class="list list-icons list-icons-style-3 mt-xlg">
									<li><i class="fa fa-clock-o"></i> <strong>Dal lunedi al giovedì</strong><br />
										 09:00 – 19:00</li>
									<li><i class="fa fa-clock-o"></i> <strong>Venerdì</strong><br />
										09:00 – 17:00</li>
									<li><i class="fa fa-clock-o"></i> <strong>Sabato e domenica</strong> <br />
										chiuso</li>
								</ul>
								
								<!-- <ul class="list list-icons list-icons-style-3 mt-xlg">
									<li>Dal 15 novembre al 31 dicembre (da confermare)</li>
									<li><i class="fa fa-clock-o"></i> <strong>Dal lunedi al venerdì</strong><br />
										 08:30 – 13:00 / 15:00 - 20:00</li>
									<li><i class="fa fa-clock-o"></i> <strong>Sabato e domenica</strong><br />
										chiuso</li>
								</ul> -->							
								
							</div>

						</div>

					</div>

				</div>


			</div>

			<!-- Vendor -->
			<script src="/vendor/jquery/jquery.min.js"></script>
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
			
			<!-- Theme Base, Components and Settings -->
			<script src="/js/theme.js"></script>

			<!-- Current Page Vendor and Views -->
			<script src="/js/views/view.contact.js"></script>
			
			<!-- Theme Custom -->
			<script src="/js/custom.js"></script>
			
			<!-- Theme Initialization Files -->
			<script src="/js/theme.init.js"></script>

			<script src="https://maps.google.com/maps/api/js?key=AIzaSyCOaIwsBzFbjvX6PqfjhcKcjCW8t8kW_Gk"></script>
			<script>

				/*
				Map Settings

					Find the Latitude and Longitude of your address:
						- http://universimmedia.pagesperso-orange.fr/geo/loc.htm
						- http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

				*/

				// Map Markers

	    var t =  new Object();
	    t.lat =  43.781570;
	    t.lng =  11.205680;
		var addr = new google.maps.LatLng(t.lat, t.lng);


				var mapMarkers = [{

	      
	            latitude: t.lat,
	            longitude: t.lng,
	       

					html: '<div class="marker"><ul style="list-style-type: none; padding-left: 0px;"><li class="first"><b>Sede</b></li><li>Via Dell Argingrosso, 65/67</li><li>50134 Firenze (FI)</li><li>Tel. 055 4630649</li><li>WhatsApp 392 2307347</li><li><a href="mailto:info@midlandsport.it">info@playleaguesport.it</a></li></ul></div>',
					icon: {
						image: "/img/pin-porto.png",
						iconsize: [26, 46],
						iconanchor: [12, 46]
					},
					popup: true
				}];

				// Map Initial Location
				var initLatitude =  43.781570;
				var initLongitude = 11.205680;

				// Map Extended Settings
				var mapSettings = {
					controls: {
						draggable: (($.browser.mobile) ? false : true),
						panControl: true,
						zoomControl: true,
						mapTypeControl: true,
						scaleControl: true,
						streetViewControl: true,
						overviewMapControl: true
					},
					scrollwheel: false,
					markers: mapMarkers,
					latitude: initLatitude,
					longitude: initLongitude,
					zoom: 16
				};

				var map = $("#googlemaps").gMap(mapSettings);

				// Map Center At
				var mapCenterAt = function(options, e) {
					e.preventDefault();
					$("#googlemaps").gMap("centerAt", options);
				}

			</script>

	</div>


