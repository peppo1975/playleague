<?

	print $backend->formIndex('Right',
	
			
				array( 
				
					'Gruppo' => 	 
							array(
							
								'field' => 'Group.nome',
								'order' => true,

							),
					'Risorsa' => 	 
							array(
							
								'field' => 'Right.resource',
								'order' => true,			

							),
					'Accesso' => 	 
							array(
							
								'field' => 'Right.allow',
								'order' => true,						

							),							
						
				)
	
	,array(

		'defaultOrder' => 'Right.resource',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione permessi',

	));


?>
