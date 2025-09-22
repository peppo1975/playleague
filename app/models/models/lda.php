<?


	class Lda extends AppModel {
			
				var $name = 'Lda';
				var $useTable = 'LDA';
				var $primaryKey = 'LDA';
				
				var $virtualFields = array(
				
					'PagatoArbitro_it' => "DATE_FORMAT(Lda.PagatoArbitro,'%d/%m/%Y')",
					'PagatoArbitro2_it' => "DATE_FORMAT(Lda.PagatoArbitro2,'%d/%m/%Y')",
					'PagatoDelegato_it' => "DATE_FORMAT(Lda.PagatoDelegato,'%d/%m/%Y')",
					'PagatoDelegatoA_it' => "DATE_FORMAT(Lda.PagatoDelegatoA,'%d/%m/%Y')",
					'Data_it' => "DATE_FORMAT(Lda.Data, '%d/%m/%Y')",
					'CasaNome' => "(SELECT Squadre.Denominazione from Squadre WHERE Squadre.Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Lda.Casa))",
					'TrasfertaNome' => "(SELECT Squadre.Denominazione from Squadre WHERE Squadre.Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Lda.Trasferta))",
					'CampoNome' => "(SELECT Campi.Descrizione FROM Campi WHERE Campi.Campo = Lda.Campo)"
				
				);
				
				var $belongsTo = array(
				
					'Campionati' => array(
					
						'className' => 'Campionati',
						'foreignKey' => 'Campionato'
					
					),
				
				);
				
				/*
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
							'isUnique' => 
								array(
										'rule' => 'isUnique',
										'message' => $this->getError('DUPLICATE_RECORD')
								)
						),
						
					);
					
				} 
				*/

	}

?>
