<?
	print $backend->formIndex('Spool',
	
			
				array( 
				
					'Inviato a' => 	 
							array(
							
								'field' => 'Spool.email',
								'afterRender' => 'getNumber',
								'order' => true
							),				
				
					'Oggetto' => 	 
							array(
							
								'field' => 'EmailModel.subject',
								'order' => true
							),
							
					'Data creazione' => 
					
							array(
							
								'field' => 'EmailModel.created',
								'afterRender' => 'make_date',
								'order' => true
							
							),
					
					'Stop/Riprendi' =>
					
							array(
							
								'field' => 'Spool.error',
								'order' => true
								
							)

				)
	
	
	,array(
	
		'defaultOrder' => 'Spool.created',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Tabella spooler',
		'allow_add' => false,
		'allow_edit' => false,
		'allow_filters' => false,
		'quickSearch' => array('Spool.email','EmailModel.subject')


	));
?>
