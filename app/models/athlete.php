<?
	
	class Athlete extends AppModel {
		
		var $name = 'Athlete';
		var $useTable = 'Atleti';
		var $primaryKey = 'Atleta';
		
		var $hasMany = array(
		
		'Upload' => array(
		'className' => 'Upload',
		'foreignKey' => 'athlete_id',
		'order' => array('Upload.order' => 'ASC')
		)
		
		);
		
		var $virtualFields = array(
		
		//'inUso' => "(IF((SELECT Campionati.InUso FROM Campionati WHERE Campionati.Campionato = ( @campionato:= (SELECT SquadreCampionati.Campionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = (SELECT Annuario.SquadraCampionato FROM Annuario WHERE Annuario.Atleta = Athlete.Atleta AND Annuario.AnnoSportivo = (SELECT Campionati.AnnoSportivo FROM Campionati WHERE Campionati.Campionato = @campionato ))))) = 'Si',0,1))",
		'DataNascita_it' => "DATE_FORMAT(DataNascita,'%d.%m.%Y')",
		'ScadenzaDocumento_it' => "DATE_FORMAT(ScadenzaDocumento,'%d/%m/%Y')",
		'ScadenzaCertificatoMedico_it' => "DATE_FORMAT(ScadenzaCertificatoMedico,'%d/%m/%Y')",
		'Anagrafica' => "CONCAT(Athlete.Nome,' ',Athlete.Cognome)",
		'reverseAnagrafica' => "CONCAT(Athlete.Cognome,' ',Athlete.Nome)",
		'avatar' => 'IF(foto_path != "",foto_path,(SELECT path FROM files WHERE athlete_id = Athlete.Atleta AND tag = "avatar" ORDER BY isEvidenza DESC LIMIT 1))'
		
		);
		
		function __construct($id = false, $table = null, $ds = null) {
			
			parent::__construct($id, $table, $ds);
			
			$this->validate = 
			
			array(
			'Nome' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			
			
			'Cognome' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),		
			
			'Sesso' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			
			'Responsabile' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			
			'Arbitro' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			'Allenatore' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),						'Delegato' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			
			'LuogoNascita' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),						
			
			/* //GIUSEPPE 2017-02-10 .............*/
			'DataNascita' => array(
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),	
			
			'Sportivo' => array(
			
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),
			
			'password' => array(
			
			'minLength' =>
			array(
			'rule' => array('minLength','5'),
			'message' => $this->getError('PASSWORD_LENGTH')
			),
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),	
			
			'password_confirm' => array(
			
			'minLength' =>
			array(
			'rule' => array('minLength','5'),
			'message' => $this->getError('PASSWORD_LENGTH')
			),								
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD')
			),
			
			
			),							
			
			'Email' => array(
			
			'email' => 
			array(
			'rule' => 'email',
			'message' => $this->getError('VALID_EMAIL')
			),
			
			
			),
			
			'Cap' => array(
			
			
			
			'postal' => 
			array(
			'allowEmpty' => true,
			'rule' => array('postal',null,'it'),
			'message' => $this->getError('INVALID_ZIPCODE')
			),
			
			),
			
			'Provincia' => array(
			
			
			
			'maxlength' => 
			array(
			'allowEmpty' => true,
			'rule' => array('maxlength',2),
			'message' => $this->getError('INVALID_PROVINCE')
			),
			
			
			'minlength' => 
			array(
			'allowEmpty' => true,
			'rule' => array('minlength',2),
			'message' => $this->getError('INVALID_PROVINCE')
			),
			
			
			),
			
			);
			
		}
		
		function afterDelete() {
			
			$id = $this->id;
			
			App::Import('Model', 'Yearbook');
			$Yearbook = new Yearbook;
			
			$y = $Yearbook->find('all', array(
			
			'conditions' => array(
			
			'Yearbook.Atleta' => $id
			
			)
			
			));
			
			foreach($y as $atleta) {
				
				$Yearbook->read(null, $atleta['Yearbook']['Annuario']);
				$Yearbook->set('Atleta', 0);
				$Yearbook->set('SquadraCampionato', 0);
				$Yearbook->set('DataVidimazione', 0);
				$Yearbook->set('Responsabile', 0);
				$Yearbook->set('AnnoSportivo', 0);
				$Yearbook->set('TipoAssicurazione', 0);
				$Yearbook->set('Note', 0);
				$Yearbook->save();
				
			}
			
		}
		
		function beforeSave() {
			
			if(!isset($this->data['Athlete']['do_not_convert'])) {
				
				if (!empty( $this->data['Athlete']['DataNascita'])) {
					
					$this->dmy2ymd($this->data['Athlete']['DataNascita']);
					
					
				}
				
				if (!empty( $this->data['Athlete']['ScadenzaDocumento'])) {
					
					$this->dmy2ymd($this->data['Athlete']['ScadenzaDocumento']);
					
				}
				
				if (!empty( $this->data['Athlete']['ScadenzaCertificatoMedico'])) {
					
					$this->dmy2ymd($this->data['Athlete']['ScadenzaCertificatoMedico']);
					
				}
				
			}
			
			/* Check Nome, Cognome, Data, Luogo */
			
			// if(isset($this->data['Athlete']['DataNascita'])) {
			
			// $conditions_unique = array(
			
			// 'Athlete.Nome'         => $this->data['Athlete']['Nome'],
			// 'Athlete.Cognome'      => $this->data['Athlete']['Cognome'],
			// 'Athlete.DataNascita'  => $this->data['Athlete']['DataNascita'],	
			// 'Athlete.LuogoNascita' => $this->data['Athlete']['LuogoNascita'],					
			
			// );
			
			// if(isset($this->data['Athlete']['Atleta']) && !empty($this->data['Athlete']['Atleta']))
			// $conditions_unique['Athlete.Atleta !='] = $this->data['Athlete']['Atleta'];
			
			// $count = $this->find('count', array(
			// 'conditions' => $conditions_unique,
			// ));
			
			// if($count > 0) {
			// $this->invalidate('Nome','Atleta già esistente.');
			// $this->invalidate('Cognome','Atleta già esistente.');
			// $this->invalidate('DataNascita','Atleta già esistente.');
			// $this->invalidate('LuogoNascita','Atleta già esistente.');
			// return false;
			// }
			
			// }
			
			/* -------------------------------- */
			
			
			return parent::beforeSave();
			
		}
		
		
	}
	
?>
