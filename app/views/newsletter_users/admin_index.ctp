<script type="text/javascript">

	$(function() {
	
		$("li a.export").click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			$.fancybox.open({ 'href': '/admin/newsletter_users/export', 'type': 'ajax' });
			return false;
		});
	
	});

</script>
<?

	print $backend->formIndex('NewsletterUser',
	
			
				array( 
				
					'Abilita/Disabilita' =>
							array(
								'field' => 'NewsletterUser.disabled'
							),
					'email' => 	 
							array(
							
								'field' => 'NewsletterUser.email',
								'order' => true,

							),
					'Nome' => 	 
							array(
							
								'field' => 'NewsletterUser.name',
								'order' => true,

							),
					'Cognome' => 	 
							array(
							
								'field' => 'NewsletterUser.surname',
								'order' => true,

							),
					'Compagnia' => 	 
							array(
							
								'field' => 'NewsletterUser.company',
								'order' => true,

							),
					'P.IVA' => 	 
							array(
							
								'field' => 'NewsletterUser.piva',
								'order' => true,

							),
					'Citt&agrave;' => 	 
							array(
							
								'field' => 'NewsletterUser.city',
								'order' => true,

							),
					'Indirizzo' => 	 
							array(
							
								'field' => 'NewsletterUser.address',
								'order' => true,

							),
					'Telefono' => 	 
							array(
							
								'field' => 'NewsletterUser.tel',
								'order' => true,

							),
					'Cellulare' => 	 
							array(
							
								'field' => 'NewsletterUser.cel',
								'order' => true,

							),
					'Fax' => 	 
							array(
							
								'field' => 'NewsletterUser.fax',
								'order' => true,

							),
					'Data registrazione' => 	 
							array(
							
								'field' => 'NewsletterUser.created_it',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'NewsletterUser.created',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Utenti newsletter',
		'quickSearch' => array('NewsletterUser.email')

	));


?>