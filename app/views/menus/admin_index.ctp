<?

	print $backend->formIndex('Menu',
	
			
				array(

					'Abilita/disabilita' =>
							array(
								'field' => 'Menu.disabled'
							),						
				
					'Nome' => 	 
							array(
							
								'field' => 'Menu.title',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'Menu.title',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella menu',

	));


?>
