<?

	print $backend->formIndex('NewsletterConfig',
	
			
				array(
				
					'Abilita/Disabilita' =>
						array(
							'field' => 'NewsletterConfig.disabled'
						),				
					'Predefinito' => 	 
							array(
							
								'field' => 'NewsletterConfig.is_default',
								'order' => true,
								'afterRender' => 'getValue'

							),				
					'Account email' => 	 
							array(
							
								'field' => 'NewsletterAccount.username',
								'order' => true,

							),
					'Nr. Email' => 	 
							array(
							
								'field' => 'NewsletterConfig.nr_email',
								'order' => true,

							),
					'Disclaimer' => 	 
							array(
							
								'field' => 'NewsletterConfig.disclaimer',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'NewsletterAccount.username',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Configurazioni newsletter',
		'quickSearch' => array('NewsletterAccount.username')

	));


?>