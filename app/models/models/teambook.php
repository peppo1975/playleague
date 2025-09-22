<?

	class Teambook extends AppModel {
			
				var $name = 'Teambook';
				var $useTable = 'AnnuarioSquadre';
				var $primaryKey = 'AnnuarioSquadra';

				var $belongsTo = array(
					'Squadre' => array(
					'className' => 'Squadre',
					'foreignKey' => 'Squadra'
					),
					
					'AnniSportivi' => array(
					'className' => 'AnniSportivi',
					'foreignKey' => 'AnnoSportivo'
					)
				); 
				
				var $virtualFields = array(
				
					'NomeSquadra' 		 => "(SELECT Denominazione FROM Squadre WHERE Teambook.Squadra = Squadre.Squadra)",
					'SquadraAnno'		 => "CONCAT(Teambook.Squadra,'-',Teambook.AnnoSportivo,'-',Teambook.AnnuarioSquadra)",
					
				);
				
					
				function beforeValidate() {
				
					if(!isset($this->data['Teambook']['AnnuarioSquadra'])) {

						$unique_check = array(
								'Squadra' => $this->data[$this->name]["Squadra"],
								'AnnoSportivo' => $this->data[$this->name]["AnnoSportivo"]
						);

						if (!$this->isUnique($unique_check)) {
						
							$this->invalidate('AnnoSportivo',$this->getError('DUPLICATE_RECORD'));
							
						}
						
					}
				} 

				
	}

?>
