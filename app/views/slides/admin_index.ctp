<?

	print $backend->formIndex('Upload',
	
			
				array( 
				
					'Visualizza/Non visualizzare' =>
							array(
								'field' => 'Upload.disabled'
							),

					'Anteprima' =>
							array(
								'field' => 'Upload.path',
								'order' => false,
								'afterRender' => 'getThumb'
							),
	
					'Titolo' => 	 
							array(
							
								'field' => 'Upload.name',
								'order' => true
					),
					
					'Descrizione' => 
							array(
								'field' => 'Upload.description',
							),

					'Dimensione' => 
							array(
								'field' => 'Upload.filesize',
								'order' => true
							),

					'Data inserimento' => 
							array(
								'field' => 'Upload.created',
								'order' => true,
								'afterRender' => 'make_date'
							),	

	                'Data pubblicazione' =>
	                        array(
	                            'field' => 'Upload.published',
	                            'order' => true,
	                            'afterRender' => 'make_date'
	                        ),

	                'Data fine' =>
	                        array(
	                            'field' => 'Upload.over',
	                            'order' => true,
	                            'afterRender' => 'make_date'
	                        )
				)
	
	,array(
	
		'defaultOrder' => 'Upload.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione immagini slider',
		'conditions' => array('Upload.tag' => 'SLIDE'),
		'quickSearch' => array('Upload.name'),
		'order_option' => true

	)
);

?>

