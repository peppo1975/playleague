<?
	$grp = $this->Session->read('User.group_id');
	if ($grp != 12) {
	print $backend->formIndex('Campi',
	
			
				array( 
				
					'Campo' => 	 
							array(
							
								'field' => 'Campi.Descrizione',
								'order' => true,

							),
						
					'Gestore' =>
							
							array(
								
								'field' => 'Campi.NominativoGestore',
								'order' => true
								
							),
					'Email' =>
							
							array(
								
								'field' => 'Campi.EmailGestore',
								'order' => true
								
							),
					'Cellulare' =>
							
							array(
								
								'field' => 'Campi.CellulareGestore',
								'order' => true
								
							),		
					'Campo midland' =>
							
							array(
								
								'field' => 'Campi.isMidland',
								'afterRender' => 'getValueOfCampo',
								
							),
					'Campo a 5' =>
							
							array(
								
								'field' => 'Campi.is5',
								'afterRender' => 'getValueOfCampo',
								
							),		
					'Campo a 7' =>
							
							array(
								
								'field' => 'Campi.is7',
								'afterRender' => 'getValueOfCampo',
								
							),		
					'Campo Tennis' =>
							
							array(
								
								'field' => 'Campi.isTennis',
								'afterRender' => 'getValueOfCampo',
								
							),	
						
				)
	
	,array(

		'defaultOrder' => 'Campi.Descrizione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella campi',
		'quickSearch' => array('Campi.Descrizione')

	));
	}else {
	
		print $backend->formIndex('Campi',
	
					
				array( 
				
					'Campo' => 	 
							array(
							
								'field' => 'Campi.Descrizione',
								'order' => true,

							),
						
					'Gestore' =>
							
							array(
								
								'field' => 'Campi.NominativoGestore',
								'order' => true
								
							),
					'Email' =>
							
							array(
								
								'field' => 'Campi.EmailGestore',
								'order' => true
								
							),
					'Cellulare' =>
							
							array(
								
								'field' => 'Campi.CellulareGestore',
								'order' => true
								
							),		
					'Campo midland' =>
							
							array(
								
								'field' => 'Campi.isMidland',
								'afterRender' => 'getValueOfCampo',
								
							),
					'Campo a 5' =>
							
							array(
								
								'field' => 'Campi.is5',
								'afterRender' => 'getValueOfCampo',
								
							),		
					'Campo a 7' =>
							
							array(
								
								'field' => 'Campi.is7',
								'afterRender' => 'getValueOfCampo',
								
							),									
						
				)
	
	,array(

		'defaultOrder' => 'Campi.Descrizione',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Tabella campi',
		'quickSearch' => array('Campi.Descrizione'),
		'conditions' => array('Campi.check1' => 1)

	));	
	}

?>
