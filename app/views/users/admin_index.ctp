<?

	print $backend->formIndex('User',
	
			
				array( 
				
					'Nome' => 	 
							array(
							
								'field' => 'User.nome',
								'order' => true
							),
					'Cognome' => 
					
							array(
							
								'field' => 'User.cognome',
								'order' => true
								
							),
					
					'E-Mail' =>
					
							array(
							
								'field' => 'User.username',
								'order' => true
								
							),
							
					'Data registrazione' =>
					
							array(
							
								'field' => 'User.created_it',
								'order' => true
								
							),
							
					'Ultimo accesso' =>
					
							array(
								
								'field' => 'User.modified_it',
								'order' => true
								
							),
							
				
					'Gruppo' =>  
					
							array(
								
								'field' => 'User.Nomegruppo',
			
								
							)
				)
	
	
	,array(
	
		'defaultOrder' => 'User.cognome',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella utenti',
		'conditions' => $conditions
	
	));


?>
