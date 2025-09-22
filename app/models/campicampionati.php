<?


	class Campicampionati extends AppModel {
			
				var $name = 'Campicampionati';
				var $useTable = 'CampiCampionati';
				var $primaryKey = 'CampoCampionato';
				
				// var $belongsTo = array(
				
					// 'Campionati' => array(
					// 'className' => 'Campionati',
					// 'foreignKey' => 'Campionato'
					// ),
					// 'Campi' => array(
					// 'className' => 'Campi',
					// 'foreignKey' => 'Campo'
					// )
				
				// );
				
				// function beforeSave() {
				
					// if (!empty( $this->data['Half']['DataInizio'])) {
					
						// $this->dmy2ymd($this->data['Half']['DataInizio']);

					
					// }
					
					// return true;
				
				// }

				
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
				
				var $virtualFields = array(
				
					'NomeCampo' => '(SELECT Campi.Descrizione FROM Campi WHERE Campi.Campo = Campicampionati.Campo)',
				
				);

	}

?>
