<?

	print $backend->formIndex('ChampCategory',
	
			
				array( 
				
					'Categoria' => 	 
							array(
							
								'field' => 'ChampCategory.Nome',
								'order' => true,

							),
							// GIUSEPPE 03/10/2016  ------------
					'Sport' =>
							array(
							
								'field' => 'ChampCategory.sport',
								'order' => true,
							),
							// --------------
					'Data inizio torneo' =>
							array(

								'field' => 'ChampCategory.published_it',
								'order' => true
							)
							
				)
	
	,array(
	
		'defaultOrder' => 'ChampCategory.Nome',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella categorie campionati',

	));


?>
/