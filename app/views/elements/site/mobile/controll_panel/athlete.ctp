<?

$data = $this->Session->read('Login.data');

$id = $data['id'];

?>


<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			Gestione profilo
		</li>
		
	</ul>
	
</div>

<div class="reserved-area">
			
			<ul data-inset="true" data-role="listview" data-theme="a">
				<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
					Gestione profilo
				</li>	
				<li>
					<a data-ajax="false" href="/mobile/profilo/<?=$id;?>/Athlete" title="Informazioni personali">Informazioni personali</a>
				</li>	
				<li>
					<a data-ajax="false" href="/mobile/vota" title="Votazioni">Votazioni</a>
				</li>
				<li><a data-ajax="false" class="button-logout" href="/mobile/?logout=1" class="user-logout" title="Logout">Logout</a></li>						
				<?/*
				<li>
					<a data-ajax="false" href="/mobile/squadre" title="Modifica squadre">Modifica squadre</a>
				</li>
				*/?>
			</ul>
			
</div>