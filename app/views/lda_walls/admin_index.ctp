<?

	print $backend->formIndex('LdaWall',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'LdaWall.disabled'
							),				
					'Titolo' => 	 
							array(
							
								'field' => 'LdaWall.title',
								'order' => true,

							),
					'Data pubblicazione' => 	 
							array(
							
								'field' => 'LdaWall.published',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'									

							),	
					'Data di creazione' => 	 
							array(
							
								'field' => 'LdaWall.created',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'									

							),
					'Data ultima modifica' => 	 
							array(
							
								'field' => 'LdaWall.modified',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'									

							),																					
						
				)
	
	,array(

		'defaultOrder' => 'LdaWall.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella bacheca LDA',
		
	));


?>
