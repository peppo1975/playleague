<?$html->script('/js/script_for_match.js', false);?>
<?

	print $backend->formIndex('Match',
	
			
				array( 
						
					'Manifestazione' => 
							array(
								'field' => 'Campionati.Nome',
								'order' => true
							),
							
					'Girone' => 
							array(
								'field' => 'Half.Descrizione',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),	
					'G.ta' => 
							array(
								'field' => 'Match.Giornata',
								'order' => true
							),									
							
					'GG' => 
							array(
								'field' => 'Match.Data3',
								'afterRender' => 'getDay',
								'order' => true
							),		
					'Data' => 
							array( 
								'field' => 'Match.Data',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'
							),
							
					'Data finale' => 
							array( 
								'field' => 'Match.Data2',
								'order' => true,
								'afterRender' => 'make_date',
								'afterSearch' => 'invert_date'
							),
														
					'H' => 
							
							array(
								'field' => 'Match.Ora',
								'order' => true
							),
							
					'SQ. CASA' => 	
					
							array(
								'field' => 'Match.CasaNome',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'SQ. TRAS.' => 
					
							array(
								'field' => 'Match.TrasfertaNome',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'Campo' => 
							array(
								'field' => 'Campi.Descrizione',
								'order' => true
							),
					
					'GOAL C.' => 
							array(
								'field' => 'Match.Risultato',
								'afterRender' => 'getGoalCasa',
							),
							
					'GOAL T.' => 
							array(
								'field' => 'Match.Risultato',
								'afterRender' => 'getGoalTrasferta',
							),
							
					'Causale' =>
							array(
								'field' => 'Causalresult.Descrizione',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'NOME G.' => 
							array(
								'field' => 'Match.NomeGara',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'ARB' => 
					
							/*array(
								'field' => 'Match.CountArbitro',
								'order' => false,
								'afterRender' => 'checkArbitro'
							),*/
							array(
								
								'field' => 'Match.Calendario',
								'afterRender' => 'countArbitroNew',
							
							),
					'ARBITRO' => 
					
							/*array(
								'field' => 'Match.CountArbitro',
								'order' => false,
								'afterRender' => 'checkArbitro'
							),*/
							array(
								
								'field' => 'Match.NomeArbitro',
							
							),
							
					'ARBITRO2' => 
					
							/*array(
								'field' => 'Match.CountArbitro',
								'order' => false,
								'afterRender' => 'checkArbitro'
							),*/
							array(
								
								'field' => 'Match.NomeArbitro2',
							
							),														
							
					'DEL' => 
					
							array(
								'field' => 'Match.NomeDelegato',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'DEL/ARB' => 
					
							array(
								'field' => 'Match.NomeDelegatoA',
								'afterRender' => 'truncateFieldz',
								'order' => true
							),
							
					'ARB.2' => 
					
							array(
								'field' => 'Match.Calendario',
								'afterRender' => 'countArbitro2New',
							),
							
					'N.GARA' => 	 
							array(
							
								'field' => 'Match.Partita',
								'order' => true,

							),
							
				)
	
	,array(

		'defaultOrder' => 'Match.Data',
		'defaultDir'   => 'DESC',
		'conditions' => array('Campionati.InUso' => 'Si'),
		'pageTitle' =>	'Gestione campionati',
		'quickSearch' => array('Match.Data','Half.Descrizione','Campionati.Nome','Match.CasaNome','Match.TrasfertaNome','Match.NomeGara','Campi.Descrizione'),
		'besideQuickSearch' => '
			<ul>
				' . ((isAllowed('Matches','admin_refresh'))? '<li><a href="javascript:;" title="Generazione calendario" rel="timmytip" id="refresh_champ"><img src="/img/timmyshare/icon_calendar.png" width="20" height="20" alt="" /></a></li>' : '') . '
				' . ((isAllowed('Prints','admin_index'))? '<li><a href="javascript:;" title="Stampe" rel="timmytip" id="print_bullettins"><img src="/img/timmyshare/icon_print.png" width="20" height="20" alt="" /></a></li>' : '') . '
				' . ((isAllowed('Squadres','admin_almanacco_index'))? '<li><a href="javascript:;" title="Stampa almanacco" rel="timmytip" id="print_almanacco"><img src="/img/icon_almanacco.png" width="20" height="20" alt="" /></a></li>' : '') . '
				' . ((isAllowed('Matches','sendLdaIndex'))? '<li><a href="javascript:;" title="Comunicazioni designatori" rel="timmytip" id="sendLdaComunication"><img src="/img/icon_lda_comunication.png" width="20" height="20" alt="" /></a></li>' : '') . '			
				' . ((isAllowed('Athletes','admin_sendMailSms'))? '<li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="20" height="20" alt="" /></a></li>' : '') . '
				' . ((isAllowed('Athletes','admin_createList'))? '<li><a href="javascript:;" title="Aggiungi contatti" data-index="matches" rel="timmytip" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="20" height="20" alt="" /></a></li>' : '') . '
			</ul>'
		
		,
		'conditions' => $conditions
		
		
	));


?>
