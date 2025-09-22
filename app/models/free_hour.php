<?


	class FreeHour extends AppModel {
			
				var $name = 'FreeHour';
				var $useTable = 'CampiAffitti';
								
				var $virtualFields = array(
				
					'Data_it' => 'DATE_FORMAT(FreeHour.Data, "%d/%m/%Y")',
					'Data_finale' => 'Data',
					'NomeCampo' => "(SELECT Campi.Descrizione FROM Campi WHERE Campi.Campo = FreeHour.Campo)",	
					'Nominativo' => "(SELECT CONCAT(Atleti.Cognome,' ',Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = FreeHour.Atleta)",

				);
				
				var $belongsTo = array(
				
					'Campi' => array(
					'className' => 'Campi',
					'foreignKey' => 'Campo'
					),
					'Athlete' => array(
					'className' => 'Athlete',
					'foreignKey' => 'Atleta',					
					)
					
				);

				function beforeValidate() {
					
					if (!empty( $this->data['FreeHour']['Data'])) {
					
						$this->dmy2ymd($this->data['FreeHour']['Data']);

					}
					
					return true;
				}
				
				function beforeSave() {
				
				App::Import('Model', 'Match');
				$this->Match = new Match;
						
						$count = $this->Match->query("
						
							SELECT COUNT( * ) as Count
							FROM Calendari
							WHERE DATA = '".$this->data['FreeHour']['Data']."'
							AND Ora = '".$this->data['FreeHour']['Ora']."'
							AND Campo = ".$this->data['FreeHour']['Campo']." 						
						
						");

				if($count[0][0]['Count'] > 0) {
				
					$this->invalidate('CampoSearch', 'Campo occupato');
					return false;
				
				} 

				return parent::beforeSave();
				
				}

 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(

						'Campo' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'CampoSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),	
						'Atleta' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),						
						'AtletaSearch' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),
						'Data' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),		
						'Ora' => array(
						
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')

						),								
						
					);
					
				} 
				

	}

?>
