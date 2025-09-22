<?

	print $backend->formIndex('News',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'News.disabled'
							),				
					'Nome pagina' => 	 
							array(
							
								'field' => 'News.title',
								'order' => true,

							),
					'Sottotitolo' => 	 
							array(
							
								'field' => 'News.subtitle',
								'order' => true,

							),
					'News di ultim\' ora' => 	 
							array(
							
								'field' => 'News.isLastHour',
								'afterRender' => 'checkLastHour',
								'order' => true,

							),		
					'Data pubblicazione' => 	 
							array(
							
								'field' => 'News.published_it',
								'order' => true,

							),							
					'Data creazione' => 	 
							array(
							
								'field' => 'News.created_it',
								'order' => true,

							),
					'Data ultima modifica' => 	 
							array(
							
								'field' => 'News.modified_it',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'News.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'News',
		//'conditions' => array( 'News.page_id' => 0 ),
		'order_option' => true
		//'quickSearch' => array('Newsletteruser.email','Newsletteruser.title','Newsletteruser.created_it')

	));


?>
