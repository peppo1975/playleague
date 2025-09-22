<?

	print $backend->formIndex('AnniSportivi',
	
			
				array( 
				
					'Anno Sportivo' => 	 
							array(
							
								'field' => 'AnniSportivi.AnnoSportivo',
								'order' => true,

							),
					'Data Inizio' => 	 /* //GIUSEPPE 2017-08-29 */
							array(
							
								'field' => 'AnniSportivi.DataInizio',
								'order' => true,

							),
						
				)
	
	,array(
		'edit' => false,
		'defaultOrder' => 'AnniSportivi.AnnoSportivo',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Tabella anni sportivi',
		'quickSearch' => array('AnniSportivi.AnnoSportivo'),
		'allow_search' => false,
		'allow_filters' => false

	));


?>
