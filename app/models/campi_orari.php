<?

	class CampiOrari extends AppModel {
			
				var $name = 'CampiOrari';
				var $useTable = 'CampiOrari';
				
				var $belongsTo = array(
				
					'Campi' => array(
						
						'className' => 'Campi',
						'foreignKey' => 'campo_id',
					
					),
				
				);
								
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Giorno' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),

						),
						
						'Ora' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),

						),
						'Importo' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),

						),

						
					);
					
				}

				var $virtualFields = array(
				
					'NomeCampo' => "(SELECT Descrizione FROM Campi WHERE Campi.Campo = CampiOrari.campo_id)",
				
				);				

	}

?>
