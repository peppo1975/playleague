<?

	print $backend->formIndex('TipiAssicurazione',
	
			
				array( 
				
					'Descrizione' => 	 
							array(
							
								'field' => 'TipiAssicurazione.Descrizione',
								'order' => true,
					

							),
						
			
					'Costo' => 	 
							array(
							
								'field' => 'TipiAssicurazione.Costo',
								'order' => true,
					

							),				
						
				)
	
	,array(

		'defaultOrder' => 'TipiAssicurazione.Descrizione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella tipi assicurazione',
		'quickSearch' => array('TipiAssicurazione.Descrizione','TipiAssicurazione.Costo')

	));


?>
