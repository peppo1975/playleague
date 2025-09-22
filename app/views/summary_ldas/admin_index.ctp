<?

	print $backend->formIndex('Athlete',
	
			
				array(
				
					'Nominativo' => 	 
							array(
							
								'field' => 'Athlete.Anagrafica',
								'order' => true,

							),
					'Ranking' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getRanking',

							),		
					
					'Voti ricevuti' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getVote',

							),
																		
					'Tot gare' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getGare',

							),	
					'Tot voti' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getVoti',

							),			
					'Tot bonus' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getBonus',
							),
							
					'Tot compensi' => 	 
							array(
							
								'field' => 'Athlete.Atleta',
								'order' => false,
								'afterRender' => 'getCompensi',

							),																														
						
				)
	
	,array(

		'defaultOrder' => 'Athlete.Anagrafica',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Riepilogo lda',
		'conditions' => array('Athlete.Arbitro' => 'Si','Athlete.ArbitroAttivo' => 1),
		'allow_edit' => false,
		'allow_add'  => false,
		'allow_delete' => false

	));


?>
