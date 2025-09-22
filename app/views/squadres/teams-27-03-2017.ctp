<?
//Ripartizione upload

$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo
if(isset($uploads['Logo'][0])) {
	
	$logo = $thumbnail->link(array('path' => $uploads['Logo'][0]['path'], 'h' => 50, 'q' => 100, 'f' => 'png')); 
	
} else {
	
	$logo = $thumbnail->link(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));
	
}

?>	
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
		

		<div class="clear"></div>
				<h3 class="title-profile-menu"><img class="team-logo" src="<?=$logo;?>" /> <span class="team-manage text-color-primary">Gestione squadra</span> <span><?=$squadra['Squadre']['Denominazione'];?></span></h3>
			<div class="clear"></div>
			<?=$this->element('site/squadre/' . $element, array('squadra' => $squadra, 'uploads' => $uploads));?>
		
			<div class="clear"></div>		

			</div>

			<div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary">Gestione account</h4>
						<ul class="nav nav-list narrow">
				<li><a href="/gestione/profilo/<?=$this->Session->read('Login.data.id');?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
				<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
				<li class="active"><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
				<li class="edit-team" data-value="edit"><a class="edit-team-button" href="/squadres/teams_edit/<?=$squadra['Squadre']['Squadra'];?>/1" title="Modifica">Modifica squadra</a></li>
						</ul>
				</aside>
			</div>
</div>
</div>