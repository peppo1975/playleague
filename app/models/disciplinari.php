<?

	class Disciplinari extends AppModel {
			
				var $name = 'Disciplinari';
				var $useTable = 'Disciplinari';
				var $primaryKey = 'Disciplinare';
				
				var $belongsTo = array(
				
					'SquadreCampionati' => array(
					
						'className' => 'SquadreCampionati',
						'foreignKey' => 'SquadraCampionato'
					
					),
					'Discipline' => array(
						'className' => 'Discipline',
						'foreignKey' => 'DisciplinareChiave'
					),
					

				
				);
				
				var $virtualFields = array(
					
					'NomeSquadra' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE Disciplinari.SquadraCampionato = SquadreCampionati.SquadraCampionato))',
				
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Punti' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						),
						'Descrizione' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						),
						
					);
					
				}

	}

?>
