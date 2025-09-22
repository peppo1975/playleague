<?$html->script('/js/script_for_pages.js', false);?>
<?

	print $backend->formIndex('Page',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'Page.disabled'
							),				
					'Nome contenuto' => 	 
							array(
							
								'field' => 'Page.title',
								'order' => true,

							),
					'Alias' => 	 
							array(
							
								'field' => 'Page.alias',
								'order' => true,

							),							
					'Sottotitolo' => 	 
							array(
							
								'field' => 'Page.subtitle',
								'order' => true,

							),		
					'Tipo' => array(
					
						'field' => 'Page.type_it',
						'order' => true,
					
					),
					'Genitore' => array(
					
						'field' => 'Page.Genitore',
						'order' => true,
					
					),					
					'Data creazione' => 	 
							array(
							
								'field' => 'Page.created_it',
								'order' => true,

							),
					'Data ultima modifica' => 	 
							array(
							
								'field' => 'Page.modified_it',
								'order' => true,							

							),
						
				)
	
	,array(

		'defaultOrder' => 'Page.order',
		'conditions' => array('Page.parent_id !=' => 0),
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Contenuti',
		'order_option' => true

	));


?>
