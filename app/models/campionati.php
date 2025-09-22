<?

	class Campionati extends AppModel {
			
				var $name = 'Campionati';
				var $useTable = 'Campionati';
				var $primaryKey = 'Campionato';
				
				var $belongsTo = array(
				
					'AnniSportivi' => array(
					'className' => 'AnniSportivi',
					'foreignKey' => 'AnnoSportivo'
					),
					'ChampCategory' => array(
					'className' => 'ChampCategory',
					'foreignKey' => 'Categoria'
					),					
						
				); 
				
				var $hasMany = array(
				
					'Campicampionati' => array(
					
						'className' => 'Campicampionati',
						'foreignKey' => 'Campionato'
					
					),
					'Half' => array(
					
						'className' => 'Half',
						'foreignKey' => 'Campionato'
					
					)
				
				);
				
				var $virtualFields = array(
				
					'AnnoSportivo_v' => "(SELECT AnnoSportivo FROM AnniSportivi WHERE Campionati.AnnoSportivo = AnniSportivi.AnnoSportivo)",
					'NomeCampionatoPrecedente' => "(SELECT Campionati2.Nome FROM Campionati as Campionati2 WHERE Campionati2.Campionato = Campionati.CampionatoPrecedente)",
					'countGare' => "(SELECT COUNT(*) FROM Calendari WHERE Calendari.Campionato = Campionati.Campionato)"
	
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
						
						'Nome' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),
						'Categoria' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),						
						'AnnoSportivo' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),
						'InCorso' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),
						'InUso' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),
						'Italiana' => array(
							'rule' => 'notEmpty',
							'message' => $this->getError('REQUIRED_FIELD')
						),
						'TariffaArbitro' => array(
							'rule' => 'numeric',
							'message' => $this->getError('NUMERIC_FIELD')
						),
						'TariffaArbitro2' => array(
							'rule' => 'numeric',
							'message' => $this->getError('NUMERIC_FIELD')
						),
						'TariffaDelegato' => array(
							'rule' => 'numeric',
							'message' => $this->getError('NUMERIC_FIELD')
						),
						'TariffaDelegatoA' => array(
							'rule' => 'numeric',
							'message' => $this->getError('NUMERIC_FIELD')
						),
										
					);


		$order = $this->getOrder($this->name);
			
			$this->order = array(
			
				'Campionati.' . $order['Order']['argument'] => $order['Order']['order_type'],
			
			);	

					
				}
				
				function beforeDelete() {
				
					$id = $this->id;
					
					App::Import('Model','SquadreCampionati');
					$SquadreCampionati = new SquadreCampionati;
					
					App::Import('Model','Half');
					$Half = new Half;
					
					App::Import('Model', 'Match');
					$Match = new Match;
					
					$hasMatch = $Match->find('count', array('conditions' => array(
					
						'Match.Campionato' => $id,
					
					)));
					
					$hasHalf = $Half->find('count', array('conditions' => array(
					
						'Half.Campionato' => $id,
					
					)));
					
					$hasTeam = $SquadreCampionati->find('count', array(
					
					'conditions' => array(
					
						'SquadreCampionati.Campionato' => $id,
						
						)
					
					));
										
					if($hasMatch != 0) return false;	
					if($hasTeam != 0) return false;	
					if($hasHalf != 0) return false;									
					
					return true;
					
				}

	}

?>
