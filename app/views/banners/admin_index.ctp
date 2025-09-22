<?$html->script('/js/script_for_banners.js', false);?>
<?

	print $backend->formIndex('Banner',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'Banner.disabled'
							),				
					'Nome' => 	 
							array(
							
								'field' => 'Banner.Titolo',
								'order' => true,

							),
					'Link' => 	 
							array(
							
								'field' => 'Banner.Link',
								'order' => true,

							),
					'Tipo' => 	 
							array(
							
								'field' => 'Banner.Tipo',
								'order' => true,

							),	
					'Spazio' => 	 
							array(
							
								'field' => 'BannersRow.Descrizione',
								'order' => true,

							),								
						
				)
	
	,array(

		'defaultOrder' => 'Banner.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione banners',
		//'conditions' => array( 'News.page_id' => 0 ),
		'order_option' => true
		//'quickSearch' => array('Newsletteruser.email','Newsletteruser.title','Newsletteruser.created_it')

	));


?>
