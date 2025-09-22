<script type="text/javascript" src="/js/layout.js"></script>


<script type="text/javascript">

$(function(){

	$(".ldaPrint").click(function() {
		
		var start   = $(this).attr('data-start');
		var end	    = $(this).attr('data-end');
		var year    = $(this).attr('data-year');
		var mounth  = $(this).attr('data-mounth');
		var athlete = $(this).attr('data-id');
	
		var start_date = start + '/' + mounth + '/' + year;
		var end_date   = end + '/' + mounth + '/' + year;
		
		data = { "start": start_date, "end": end_date, "athlete": athlete}
		
		$.post('/prints/single_lda/', {"datas":data},function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
	});
					
});

</script>


<script type="text/javascript">

$(function(){
	
	$("body").delegate('.isNumber','keydown',function(e) {
	
		var code = e.keyCode;
			
		if(isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46) return false;
		
	});
	
});

</script>


<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Buste paga</li>
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

						<li class="">
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

						<li class="active">
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
							<?=$this->element('other/buste');?>
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
