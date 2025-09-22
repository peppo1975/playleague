
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Gestione squadre</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		
		<div class="row">
			<div class="col-md-9">
		
			<h2 class="title-profile-menu">Gestione squadre</h2>
			<div class="clear"></div>	
			
			<? if(count($data)): ?>
			
			<div class="table-container container-table-profile">
			
			<div id="results-box">
						
			<table class="table table-matches table-bordered table-striped table-condensed">	
			
			<thead class="table-header">
				<th>Squadra</th>
			</thead>

					<? foreach($data as $squadra): ?>
					
					<tr>
						<td>
							<a href="/squadre/<?=$squadra['Yearbook']['Squadra'];?>/1/<?=strtolower(Inflector::Slug($squadra['Yearbook']['NomeSquadra'],'-'));?>" title="Modifica squadra <?=$squadra['Yearbook']['NomeSquadra'];?>">
								<?=$squadra['Yearbook']['NomeSquadra'];?>
							</a>
						</td>	
					</tr>
					
					<? endforeach; ?>
					
			</table>
					
			</div><!-- close #results-box -->
			
			</div><!-- close table-container -->
					
			<? else: ?>
			<div class="alert alert-warning">
			Non ci sono squadre da amministrare.
			</div>
			<? endif; ?>				



			</div>

			<div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary">Gestione account</h4>
						<ul class="nav nav-list narrow">
		<li><a href="/gestione/profilo/<?=$this->Session->read('Login.data.id');?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
		<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
		<li class="active"><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>

						</ul>
				</aside>
			</div>
</div>
</div>