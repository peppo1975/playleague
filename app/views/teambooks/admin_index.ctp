<?$html->script('/js/script_for_teambook.js', false);?>
<?

	print $backend->formIndex('Teambook',
	
			
				array( 
				
					'Squadra' => 	 
							array(
							
								'field' => 'Teambook.NomeSquadra',
								'order' => true,

							),
					'Anno sportivo' => 
					
							array(
							
								'field' => 'Teambook.AnnoSportivo',
								'order' => true
								
							),
				
					'Deposito cauzionale' => 
					
							array(
							
								'field' => 'Teambook.DepositoCauzionale',
								'order' => true,
								'afterRender' => 'make_euro'
								
							),
							
					'<span class="order_debito"><img width="12" height="17" alt="" src="/img/timmyshare/order_default.png"></span>Debito' => 
					
							array(
							
								'field' => 'Teambook.SquadraAnno',
								'afterRender' => 'getDebito',
								//'order' => true
								
							),							
										
							
					'Note' => 
					
							array(
							
								'field' => 'Teambook.Note',
								'order' => false
								
							)
						
				)
	
	,array(
	
		'defaultOrder' => 'Squadre.Denominazione',
		'defaultDir'   => 'ASC',
		'conditions'   => array('Teambook.AnnoSportivo = (SELECT AnnoSportivo FROM AnniSportivi ORDER BY AnnoSportivo DESC LIMIT 1)'),
		'pageTitle' =>	'Gestione Annuario Squadre',
		'buttons' => array('Riepilogo' => array('class' => 'riepilogo', 'img' => '/img/icon_resume.png', 'action' => 'edit', 'selected' => '2')),
		'quickSearch' => array('Teambook.NomeSquadra','Teambook.AnnoSportivo')

	));


?>
