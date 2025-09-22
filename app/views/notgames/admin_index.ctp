<?

	print $backend->formIndex('Notgame',
	
			
				array( 
				
					'Data' => 	 
							array(
							
								'field' => 'Notgame.Data',
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date',								
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'Notgame.Data',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella giorni non gioco',
		'quickSearch' => array('Notgame.Data'),
		'allow_filters' => false,
		'allow_search' => false

	));


?>
