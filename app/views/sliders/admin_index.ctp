<?

	print $backend->formIndex('Slider',
	
			
				array( 
				
					'Abilita/disabilita' =>
							array(
								'field' => 'Slider.disabled'
							),			
				
					'Nome prodotto' => 	 
							array(
							
								'field' => 'Slider.title',


							),
							
					'Link' => 	 
							array(
							
								'field' => 'Slider.link',


							),
						
							
					'Prezzo' => 	 
							array(
							
								'field' => 'Slider.price',

							),
						
				)
	
	,array(
	
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella slider',
		'quickSearch' => array('Slider.title'),
		'order_option' => true,
		'defaultDir'   => 'ASC',
		'defaultOrder' => 'Slider.order',
	));


?>
