<?

	print $backend->formIndex('Expulsion',
	
			
				array( 
				
					'Espulsione' => 	 
							array(
							
								'field' => 'Expulsion.Espulsione',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'Expulsion.Espulsione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella espulsioni',
		'quickSearch' => array('Expulsion.Espulsione')

	));


?>
