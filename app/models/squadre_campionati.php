<?

	class SquadreCampionati extends AppModel {
			
				var $name = 'SquadreCampionati';
				var $useTable = 'SquadreCampionati';
				var $primaryKey = 'SquadraCampionato';

				var $belongsTo = array(
					'Squadre' => array(
					'className' => 'Squadre',
					'foreignKey' => 'Squadra'
					),
					'Half' => array(
					'className' => 'Half',
					'foreignKey' => 'GironeCampionato'
					),
					'Campionati' => array(
					'className' => 'Campionati',
					'foreignKey' => 'Campionato'
					),
					'Campi' => array(
					'className' => 'Campi',
					'foreignKey' => 'Campo'
					
					)
					
				); 
				
				var $hasMany = array(
				
					'Yearbook' => array(
					
						'className' => 'Yearbook',
						'foreignKey'=> 'SquadraCampionato',
						'fields' => array('Yearbook.Atleta', 'Yearbook.NumeroMaglia', 'Yearbook.Ruolo')
					
					)
				
				);				
				
				var $virtualFields = array(
				
					'inUso' => "(IF((SELECT Campionati.InCorso FROM Campionati WHERE SquadreCampionati.Campionato = Campionati.Campionato) = 'Si',0,1))",
					'SquadraNome' => '(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra = SquadreCampionati.Squadra)',
					'AnnoCampionato' => '(SELECT AnnoSportivo FROM Campionati WHERE Campionati.Campionato = SquadreCampionati.Campionato)',
				
				);
				
				function beforeDelete() {
				
					// if($this->field('inUso') == 1) {
					
						// return true;
					
					// } else {
					
						// return false;				
				
					// }
					
					$id = $this->id;
					
					App::Import('Model', 'Yearbook');
					$Yearbook = new Yearbook;
					
					$hasTesserati = $Yearbook->find('count', array('conditions' => array(
					
						'Yearbook.SquadraCampionato' => $id,
					
					)));
					
					if($hasTesserati != 0) return false;
					
					return true;
				
				}

				
	}

?>
