<?

	class Comunication extends AppModel {
			
				var $name = 'Comunication';
				var $useTable = 'Bollettini';
				var $primaryKey = 'Bollettino';

				var $belongsTo = array(

					'Half' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeCampionato'
					)
					
				);
				
				var $virtualFields = array(
				
					'Campionato' => '(SELECT Campionati.Nome FROM Campionati WHERE Campionati.Campionato = (SELECT Campionato FROM GironiCampionati WHERE GironiCampionati.GironeCampionato = Comunication.GironeCampionato))',
					'CampionatoAnno' => '(SELECT Campionati.AnnoSportivo FROM Campionati WHERE Campionati.Campionato = (SELECT Campionato FROM GironiCampionati WHERE GironiCampionati.GironeCampionato = Comunication.GironeCampionato))',
				
				);
				
				function beforeSave() {
					
					if($this->data['Comunication']['Note'] == '') {
						
						$this->invalidate('Note', 'Campo obbligatorio.');
						return false;						
						
					}
					
					$count = $this->find('count', array(
					
						'conditions' => array(
						
							'AND' => array(
						
								'Comunication.GironeCampionato' => $this->data['Comunication']['GironeCampionato'],
								'Comunication.Giornata'         => $this->data['Comunication']['Giornata'],
							
							),
						
						),
					
					));
					
					if($count > 0) {
						
						$this->invalidate('GironeCampionato', 'Comunicazione già effettuata in questo girone.');
						$this->invalidate('Giornata', 'Comunicazione già effettuata in questa giornata.');
						return false;
						
					}
					
					return parent::beforeSave();
					
				}
				
	}

?>
