<?$html->script('/js/script_for_br_report.js', false);?>
<?

	print $backend->formIndex('BrReport',
	
			
				array( 
				
					'Apri/Chiudi' => array(
					
						'field' => 'BrReport.disabled',
				
					
					),
					'Titolo' => 	 
							array(
							
								'field' => 'BrReport.title',
								'order' => true,

							),
						
					'Zona' =>
							
							array(
								
								'field' => 'BrZone.title',
								'order' => true
								
							),
					'Categoria' =>
							
							array(
								
								'field' => 'BrCategory.title',
								'order' => true
								
							),
					'Priorità' =>
							
							array(
								
								'field' => 'BrReport.priority',
								'order' => true,
								'afterRender' => 'getPriorityVal',
								
							),		
					'Tipo richiesta' =>
							
							array(
								
								'field' => 'BrReport.type_request',
								'order' => true,
								
							),
					'Data creazione' =>
							
							array(
								
								'field' => 'BrReport.created',
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date',								
								'order' => true,
								
							),		
					'Data ultima modifica' =>
							
							array(
								
								'field' => 'BrReport.modified',
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date',								
								'order' => true,
								
							),									
						
				)
	
	,array(

		'defaultOrder' => 'BrReport.priority',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Tabella bugreport',
		'buttons' => array(
						    'Commenti' => array('class' => 'edit','img' => '/img/timmyshare/icon_map.png', 'action' => 'edit','selected' => 2),
					),		

	));


?>
