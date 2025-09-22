<?

	print $backend->formIndex('Ticket',
	
			
				array( 
				
					'Nome' => 	 
							array(
							
								'field' => 'Ticket.nome',
								'order' => true
							),
					'Gravità' => 
					
							array(
							
								'field' => 'Ticket.gravita_it',
								'order' => true
								
							),
					
					'Descrizione' =>
					
							array(
							
								'field' => 'Ticket.descrizione',
								'order' => false								
							),
							
					'Sito' =>
					
							array(
							
								'field' => 'Site.nome',
								'order' => true
								
							),
					'Data creazione' =>
					
							array(
								
								'field' => 'Ticket.created_it',
								'order' => true
								
							),							
					'Data modifica' =>
					
							array(
								
								'field' => 'Ticket.modified_it',
								'order' => true
								
							),
							

				)
	
	
	,array(
	
		'defaultOrder' => 'Ticket.created_it',
		'defaultDir'   => 'DESC'
	
	));


?>
