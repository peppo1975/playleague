<?

	print $backend->formIndex('Causalresult',
	
			
				array( 
				
					'Descrizione' => 	 
							array(
							
								'field' => 'Causalresult.Descrizione',
								'order' => true,

							),
					'Sanzione' => 	 
							array(
							
								'field' => 'Causalresult.Sanzione',
								'order' => true,
								'afterRender' => 'make_euro'

							),
					'Punti disciplina' => 	 
							array(
							
								'field' => 'Causalresult.PuntiDisciplina',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'Causalresult.Descrizione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella causali risultato',
		'quickSearch' => array('Causalresult.Descrizione','Causalresult.Sanzione','Causalresult.PuntiDisciplina')

	));


?>
