<?

	class AthleteExpense extends AppModel {
			
				var $name = 'AthleteExpense';
				var $useTable = 'AtletiSpese';
				var $primaryKey = 'AtletaSpesa';
				
				var $virtualFields = array(
				
					'Data_it' => "DATE_FORMAT(Data,'%d/%m/%Y')",
					'Stato_it'=> "(IF(stato = 1,'+','-'))",
				
				);
				
				var $belongsTo = array(
				
					'Athlete' => array(
					
						'className'  => 'Athlete',
						'foreignKey' => 'Atleta'
					
					),
				
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
						'Data' => array(

								
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
							'numeric' => 
								array(
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
								),								
								
						),		
	
						
					);
					
				}
				
				function beforeSave() {
					
					if (!empty( $this->data['AthleteExpense']['Data'])) {
					
						$this->dmy2ymd($this->data['AthleteExpense']['Data']);

					
					}
					
					return parent::beforeSave();
				}
	
	}

?>
