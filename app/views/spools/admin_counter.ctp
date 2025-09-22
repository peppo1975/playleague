<?
	print $backend->formIndex('Spool',
	
			
				array( 
				
					'Da inviare a' => 	 
							array(
							
								'field' => 'Spool.email',
								'afterRender' => 'getNumber',
								'order' => true
							),				
				
					'Oggetto' => 	 
							array(
							
								'field' => 'EmailModel.subject',
								'order' => true
							),
							
					'Data creazione' => 
					
							array(
							
								'field' => 'EmailModel.created',
								'afterRender' => 'make_date',
								'order' => true
							
							),
					

				)
	
	
	,array(
	
		'defaultOrder' => 'Spool.created',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Elenco dei messaggi/mail non inviate',
		'allow_add' => false,
		'allow_edit' => false,
		'allow_filters' => false,
		'allow_search' => false,
		'conditions' => array(
		
						'sent' => 0,
						'Spool.created <= NOW()',
						'Spool.modified BETWEEN ? AND ?' => array(date("Y-m-d H:i:s", strtotime("-4 days")), date("Y-m-d H:i:s")),
						'EmailModel.disabled' => 0,
						'EmailModel.created <= NOW()'		
		
		)
	));
?>
