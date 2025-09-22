<?$html->script('/js/script_for_matchgoal.js', false);?>
<?

	print $backend->formIndex('Matchgoal',
	
			
				array( 
				
					'Anno' => 	 
							array(
							
								'field' => 'Matchgoal.AnnoSportivo',
								'order' => true,

							),				
				
					'Data' => 	 
							array(
							
								'field' => 'Matchgoal.Data',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'

							),
					'Atleta' => 	 
							array(
							
								'field' => 'Matchgoal.DatiAtleta',
								'order' => true,

							),
					'Squadra' => 	 
							array(
							
								'field' => 'Matchgoal.NomeSquadra',
								'order' => true,

							),
					'Campionato' => 	 
							array(
							
								'field' => 'Matchgoal.NomeCampionato',
								'order' => true,

							),							
					'Giornate' => 	 
							array(
							
								'field' => 'Matchgoal.EspulsioneGiornate',
								'order' => true,

							),
					'Data fine' => 	 
							array(
							
								'field' => 'Matchgoal.EspulsioneFine',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'								

							),							
						
				)
	
	,array(

		'defaultOrder' => 'Matchgoal.Data',
		'defaultDir'   => 'DESC',
		'pageTitle' =>	'Tabella espulsioni',
		'conditions' => array(
			"Matchgoal.Espulsione" => "Si", 
		),

	));


?>
