<?

	print $backend->formIndex('NewsletterAccount',
	
			
				array( 
				
					'Abilita/Disabilita' =>
						array(
							'field' => 'NewsletterAccount.disabled'
						),
				
					'Host' => 	 
							array(
							
								'field' => 'NewsletterAccount.host',
								'order' => true,

							),
					'Porta' => 	 
							array(
							
								'field' => 'NewsletterAccount.port',
								'order' => true,

							),
					'Sicurezza' => 	 
							array(
							
								'field' => 'NewsletterAccount.secure',
								'order' => true,

							),
					'Auth' => 	 
							array(
							
								'field' => 'NewsletterAccount.auth',
								'order' => true,
								'afterRender' => 'getValueAccount'

							),
					'Username' => 	 
							array(
							
								'field' => 'NewsletterAccount.username',
								'order' => true,

							),
					'Test' => 	 
							array(
							
								'field' => 'NewsletterAccount.test',
								'order' => true,
								//'afterRender' => 'getValue'

							),
					'Default' => 	 
							array(
							
								'field' => 'NewsletterAccount.default',
								'order' => true,
								'afterRender' => 'getDefault'

							),
					'Email' => 	 
							array(
							
								'field' => 'NewsletterAccount.sender_mail',
								'order' => true,

							),
					'Nome' => 	 
							array(
							
								'field' => 'NewsletterAccount.sender_name',
								'order' => true,

							),
							
						
				)
	
	,array(

		'defaultOrder' => 'NewsletterAccount.sender_mail',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Account email newsletter',
		'quickSearch' => array('NewsletterAccount.sender_mail')

	));


?>