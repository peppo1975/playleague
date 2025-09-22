<?
	print $backend->formIndex('Stream',
	
			
				array(
					'Abilita/Disabilita' => array(
					
						'field' => 'Stream.disabled'
					
					),
					'Titolo' => 	 
							array(
							
								'field' => 'Stream.title',
								'order' => true,

							),
					'Sottotitolo' => 	 
							array(
							
								'field' => 'Stream.subtitle',
								'order' => true,

							),
					'Link' => 	 
							array(
							
								'field' => 'Stream.link',
								'afterRender' => 'checkEmbed',
								'order' => true,

							),
					'File' => 	 
							array(
							
								'field' => 'Stream.file',
								'order' => true,

							),
					'Data pubblicazione' => 	 
							array(
							
								'field' => 'Stream.created_it',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'Stream.created_it',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Live streaming',
		//'buttons' => array('Predefinito/Non predefinito' => array('class' => 'defaultStream'))

	));


?>
