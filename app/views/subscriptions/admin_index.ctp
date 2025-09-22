<script type="text/javascript">

	<? if (!isset($_SESSION['campionatis_desc'])): ?>
		
		<? $_SESSION['campionatis_desc'] = 1; ?>
		

		
	<? endif; ?>
	

</script>
<?

	print $backend->formIndex('Campionati',
	
			
				array( 
				
					'Nome' => 	 
							array(
							
								'field' => 'Campionati.Nome',
								'order' => true,

							),
				
					'Data creazione' => 
					
							array(
								
								'field' => 'Campionati.created',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'
								
							),
				
					'Anno' => 	 
							array(
							
								'field' => 'Campionati.AnnoSportivo_v',
								'order' => true,

							),

				)
	
	,array(

		'defaultOrder' => 'Campionati.AnnoSportivo_v',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Tabella campionati con iscrizioni aperte',
		'quickSearch' => array('Campionati.Nome'),
		'conditions' => array('Campionati.iscrizioni' => 1),
		'allow_add' => false,
		'allow_delete' => false,
		'allow_filters' => false,
		'allow_search' => false
	
				

	));


?>
