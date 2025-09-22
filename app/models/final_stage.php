<?


	class FinalStage extends AppModel {
			
				var $name = 'FinalStage';
				var $useTable = 'CalendariFinali';
								
				var $virtualFields = array(
				
					'Data_it' => 'DATE_FORMAT(FinalStage.Data, "%d/%m/%Y")',
					'NomeCampo' => "(SELECT Campi.Descrizione FROM Campi WHERE Campi.Campo = FinalStage.Campo)",
					'NomeGirone' => "(SELECT GironiCampionati.Descrizione FROM GironiCampionati WHERE FinalStage.Girone = GironiCampionati.GironeCampionato)",
					'NomeGironeCasa' => "(SELECT GironiCampionati.Descrizione FROM GironiCampionati WHERE FinalStage.GironeCasa = GironiCampionati.GironeCampionato)",
					'NomeGironeTrasferta' => "(SELECT GironiCampionati.Descrizione FROM GironiCampionati WHERE FinalStage.GironeTrasferta = GironiCampionati.GironeCampionato)",
					'NomeGaraCasa' => "(IF((@NomeGara := (SELECT Calendari.NomeGara FROM Calendari WHERE Calendari.Calendario = FinalStage.GaraCasa)) != '', @NomeGara,(SELECT CONCAT('Giornata ', Calendari.Giornata,' - Partita ', Calendari.Partita) FROM Calendari WHERE Calendari.Calendario = FinalStage.GaraCasa)))",
					'NomeGaraTrasferta' => "(IF((@NomeGara := (SELECT Calendari.NomeGara FROM Calendari WHERE Calendari.Calendario = FinalStage.GaraTrasferta)) != '', @NomeGara,(SELECT CONCAT('Giornata ', Calendari.Giornata,' - Partita ', Calendari.Partita) FROM Calendari WHERE Calendari.Calendario = FinalStage.GaraTrasferta)))",
					

				);
				
				var $belongsTo = array(
				
					'Campionati' => array(
					'className' => 'Campionati',
					'foreignKey' => 'Campionato'
					),
					'Half' => array(
					'className' => 'Half',
					'foreignKey' => 'Girone',					
					),
					'HalfCasa' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeCasa',					
					),
					'HalfTrasferta' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeTrasferta',					
					),									
				);


				function beforeValidate() {
					
					if (!empty( $this->data['FinalStage']['Data'])) {
					
						$this->dmy2ymd($this->data['FinalStage']['Data']);

					}
					
					return true;
				}

				
				
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(

						'Campionato' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						
					);
					
				} 
				

	}

?>
