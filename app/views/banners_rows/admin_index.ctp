<?$html->script('/js/script_for_banners.js', false);?>
<?

	print $backend->formIndex('BannersRow',
	
			
				array(
				
					'Abilita/disabilita' =>
							array(
								'field' => 'BannersRow.disabled'
							),				
					'Nome' => 	 
							array(
							
								'field' => 'BannersRow.Descrizione',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'BannersRow.order',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gestione banner (spazi)',
		//'conditions' => array( 'News.page_id' => 0 ),
		'order_option' => true
		//'quickSearch' => array('Newsletteruser.email','Newsletteruser.title','Newsletteruser.created_it')

	));


?>
