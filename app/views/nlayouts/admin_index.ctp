<?
	
	print $backend->formIndex('Nlayout',
	
			
				array( 
				
					'Abilita/Disabilita' =>
						array(
							'field' => 'Nlayout.disabled'
						),
				
					'Nome grafica' => 	 
							array(
							
								'field' => 'Nlayout.title',
								'order' => true,

							),
					'Data creazione' => 	 
							array(
							
								'field' => 'Nlayout.created',
								'order' => true,
												'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'	

							),
					'Data ultima modifica' => 	 
							array(
							
								'field' => 'Nlayout.modified',
								'order' => true,
												'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'	

							),
						
				)
	
	,array(

		'defaultOrder' => 'Nlayout.created',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Gestione grafiche',
		'quickSearch' => array('Nlayout.title')	

	));


?>