<?

	print $backend->formIndex('Type',
	
			
				array( 
				
					'Nome' => 	 
							array(
							
								'field' => 'Type.Nome',
								'order' => true,

							),

					'Manifestazione' =>
							array(

								'field' => 'Event.Nome',
								'order' => true
							)
							
				)
	
	,array(
	
		'defaultOrder' => 'Type.Nome',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella tipologie manifestazioni',

	));


?>
