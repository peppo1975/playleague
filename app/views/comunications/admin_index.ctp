<?$html->script('/js/script_for_comunication.js', false);?>
<?

	print $backend->formIndex('Comunication',
	
			
				array( 
				
					'Campionato' => array(
					
						'field' => 'Comunication.Campionato',
						'order' => true
					
					),
				
					'Girone' => 	 
							array(
							
								'field' => 'Half.Descrizione',
								'order' => true,

							),
					'Giornata' => 	 
							array(
							
								'field' => 'Comunication.Giornata',
								'order' => true,

							),
					'Note' => 	 
							array(
							
								'field' => 'Comunication.Note',
								'order' => true,

							),
					'Anno' =>
						   array('field' => 'Comunication.CampionatoAnno')
						
				)
	
	,array(

		'defaultOrder' => 'Half.Descrizione',
		'defaultDir'   => 'ASC',
		//'conditions'   => array('Comunication.CampionatoAnno BETWEEN ? AND ?' => array(date("Y"),date("Y")+2)),
		'conditions' => array('Comunication.CampionatoAnno' => $anno),
		'pageTitle' =>	'Tabella comuicazioni',

	));


?>
