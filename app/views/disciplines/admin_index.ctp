<?

	print $backend->formIndex('Discipline',
	
			
				array( 
				
					'Descrizione' => 	 
							array(
							
								'field' => 'Discipline.Descrizione',
								'order' => true,

							),
					'Punti' => 	 
							array(
							
								'field' => 'Discipline.Punti',
								'order' => true,

							),
					'Sanzione' => 	 
							array(
							
								'field' => 'Discipline.Sanzione',
								'order' => true,
								'afterRender' => 'make_euro'

							),
						
				)
	
	,array(

		'defaultOrder' => 'Discipline.Descrizione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella disciplinare',
		'quickSearch' => array('Discipline.Descrizione','Discipline.Punti','Discipline.Sanzione')

	));


?>
