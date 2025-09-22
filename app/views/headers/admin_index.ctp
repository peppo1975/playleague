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
					'Predefinito' =>
							array(
								'field' => 'Upload.default',
								'order' => false,
								'afterRender' => 'getDefaultValue'
							),
					'Nome file' => 	 
							array(
							
								'field' => 'Upload.name',
								'order' => true
					),
					
					'Link' => 
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
							)
 				

				)
	
	,array(
	
		'defaultOrder' => 'Upload.created',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione immagini',
		'conditions' => array('Upload.tag' => 'HEADER'),
		'quickSearch' => array('Upload.name')

	));



?>

