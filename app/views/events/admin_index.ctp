<?

	print $backend->formIndex('Event',
	
			
				array( 
				
					'Categoria' => 	 
							array(
							
								'field' => 'Event.Nome',
								'order' => true,

							),
					'Sport' => 	 
							array(
							
								'field' => 'Event.sport',
								'order' => true,

							),
                                   							

					'Data inizio torneo' =>
							array(

								'field' => 'Event.published_it',
								'order' => true
							),

				)
	
	,array(
	
		'pageTitle' =>	'Tabella prossime manifestazioni',
		'defaultOrder' => 'Event.order',
		'defaultDir'   => 'ASC',
		'order_option' => 1,


	));


?>
