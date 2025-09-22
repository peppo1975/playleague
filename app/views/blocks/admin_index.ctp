<?$html->script('/js/script_for_blocks.js', false);?>
<?

	print $backend->formIndex('Block',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'Block.disabled'
							),				
					'Titolo' => 	 
							array(
							
								'field' => 'Block.title',
								'order' => true,

							),
					'Pagina madre' => 	 
							array(
							
								'field' => 'Block.mother_page',
								'order' => true,

							),
					'Tipo' => 	 
							array(
							
								'field' => 'Block.type_it',
								'order' => true,

							),							
					'Url' => 	 
							array(
							
								'field' => 'Block.url',
								'order' => true,

							),	
					'Collegamento pagina' => 	 
							array(
							
								'field' => 'Block.page_url',
								'order' => true,

							),	
					'Data pubblicazione' => 	 
							array(
							
								'field' => 'Block.published',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'									

							),		

            // Colonna data fine tabella indice blocchi 04/05/2018 --------------------------------
            		'Data fine' =>
            				array(
				                'field' => 'Block.over',
				                'order' => true,
				                'afterRender' => 'make_date',
				                'afterSearch' => 'invert_date'
				            ),					
						
				)
	
	,array(

		'defaultOrder' => 'Block.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione blocchi pagine',
		'conditions' => array("1=0"),
		'order_option' => 1,
		//'conditions' => array( 'News.page_id' => 0 ),
		//'quickSearch' => array('Newsletteruser.email','Newsletteruser.title','Newsletteruser.created_it')

	));


?>
