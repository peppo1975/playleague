<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Impianti</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
		
		<div class="row">
			<div class="col-md-12">
				<h2>Impianti sportivi</h2>
				<hr />
			<div class="contents-block-left">
								<? if (isset($booking)): ?>
								<div class="alert alert-success">
								<b>Gentile <?=$booking['CampiBooking']['bookerNome'];?> <?=$booking['CampiBooking']['bookerCognome'];?>,</b>	
								<p>
								
									La sua prenotazione &egrave; stata eliminata correttamente
								
								</p>
								</div>
								<? else: ?>
									<div class="alert alert-danger">
									<b>Siamo spiacenti,</b>
									<p>
										La prenotazione selezionata è inesistente o già annullata
									</p>
									</div>
								<? endif; ?>
					
			</div>
			</div>
			</div>
		</div>
</div>