<?

//debug($this->Session->read('Login.data'));

$data = $this->Session->read('Login.data');

?>

<? 

if($data['is_atleta']):

echo $this->element('/site/mobile/controll_panel/athlete');

elseif($data['is_user']):

echo $this->element('/site/mobile/controll_panel/user');

elseif($data['is_arbitro']):

echo $this->element('/site/mobile/controll_panel/arbitro');

else:

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
				Login/Registrazione utenti
			</li>
			
		</ul>
		
	</div>

	<ul data-inset="true" data-role="listview" data-theme="a">
		<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">
			Login/Registrazione utenti
		</li>	
			<li><a data-ajax="false" href="/mobile/login">Login</a></li>
			<li><a data-ajax="false" href="/mobile/signup">Registrazione utenti</a></li>				
			<li><a data-ajax="false" href="/mobile/signup_athlete">Registrazione atleti</a></li>								
	</ul>
<?

endif; 

?>