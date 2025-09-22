<?=$this->Html->script('/js/script_for_freehour.js');?>
<?

	print $backend->formIndex('FreeHour',
	
			
				array( 
				
					'Campo' => 	 
							array(
							
								'field' => 'Campi.Descrizione',
								'order' => true,

							),
					'Data' => 	 
							array(
							
								'field' => 'FreeHour.Data',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'								

							),
					'Data finale' => 	 
							array(
							
								'field' => 'FreeHour.Data_finale',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'								

							),							
					'Ora' => 	 
							array(
							
								'field' => 'FreeHour.Ora',
								'order' => true,

							),		
							
							
					'Nominativo' =>
							
							array(
								
								'field' => 'FreeHour.Nominativo',
								'order' => true
								
							)
						
				)
	
	,array(

		'defaultOrder' => 'FreeHour.Data',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Gestione ore libere',

	));


?>
