

<script type="text/javascript">

$(function(){
	$("body").addClass("votazioni");	
	$(".table-matches").delegate('.vote','click', function(){
	
		var obj     = $(this);
		var type    = obj.attr('data-type');
		var athlete = obj.attr('data-id');
		var allow   = obj.parents('tr').attr('vote-allow');
		var match   = obj.parents('tr').attr('data-id');

		timmy_load('/lda_votes/vote_index/' + match + '/' + athlete);
		
	});	
	
	$('.switch-giornata').bind('click', function(){
		
		location.hash = $(this).attr('data-giornata-id');
		
	});
					
});

$(document).ready(function(){
	
	var loc_hash = location.hash.replace('#','');
	$('.switch-giornata[data-giornata-id="' + loc_hash + '"]').trigger('click');
	
});

</script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="/js/layout.js"></script>


<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Votazioni</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="main-custom">
	   	 <div class="row">
	      	<div class="col-md-12">
	      		<div class="tabs tabs-bottom tabs-center tabs-simple">
	      			<ul class="nav nav-tabs">
						<li class="">
							<a data-toggle="" href="/area/riservata" aria-expanded="true">
								<span class="featured-boxes featured-boxes-style-6 p-none m-none">
									<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
										<span class="box-content p-none m-none">
											<i class="icon-featured fa fa-user"></i>
										</span>
									</span>
								</span>									
								<p class="mb-none pb-none">Profilo utente</p>
							</a>
						</li>

						<li class="">
							<a data-toggle="" href="/lda_walls/index" aria-expanded="false">
								<span class="featured-boxes featured-boxes-style-6 p-none m-none">
									<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
										<span class="box-content p-none m-none">
											<i class="icon-featured fa fa-table"></i>
										</span>
									</span>
								</span>									
								<p class="mb-none pb-none">Bacheca</p>
							</a>
						</li>

						<li class="active">
							<a data-toggle="" href="/gestione/votazioni" aria-expanded="false">
								<span class="featured-boxes featured-boxes-style-6 p-none m-none">
									<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
										<span class="box-content p-none m-none">
											<i class="icon-featured fa fa-star"></i>
										</span>
									</span>
								</span>									
								<!--<p class="mb-none pb-none">Votazioni</p>-->
								<p class="mb-none pb-none">Partite</p> <!-- GIUSEPPE 2022-10-15 -->
							</a>
						</li>

						<li class="">
							<a data-toggle="" href="/gestione/buste" aria-expanded="false">
								<span class="featured-boxes featured-boxes-style-6 p-none m-none">
									<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
										<span class="box-content p-none m-none">
											<i class="icon-featured fa fa-euro"></i>
										</span>
									</span>
								</span>									
								<p class="mb-none pb-none">Buste paga</p>
							</a>
						</li>

						
					</ul>
					<div id="tabsNavigationSimpleIcons1" class="tab-pane">


						<div style="padding: 20px;">
							<?=$this->element('other/voazioni');?>
						</div>
					</div>
				</div>
			</div>

			
					<!-- <div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary">Gestione account</h4>
					<ul class="nav nav-list narrow">
						<li class="active"><a href="/gestione/profilo/<?=$this->data['Athlete']['Atleta'];?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
						<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
						<li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
					</ul>
				</aside>
			</div> -->
		</div>
	</div>
</div>

