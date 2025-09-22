<?

	class User extends AppModel {
				var $name = 'User';
				var $belongsTo = array(
					'Group' => array(
					'className' => 'Group',
					'foreignKey' => 'group_id'
					)
				); 
				var $virtualFields = array(
				'created_it' => "DATE_FORMAT(User.created,'%d/%m/%Y %H:%i:%s')",
				'modified_it' => "DATE_FORMAT(User.modified,'%d/%m/%Y %H:%i:%s')",
				'Nomegruppo' => "SELECT nome from groups WHERE User.group_id = groups.id",
				'NomeAtleta' => "(SELECT CONCAT(Atleti.Cognome,' ',Atleti.Nome) FROM Atleti WHERE Atleti.Atleta = User.athlete_id)",
				'avatar' => '(SELECT path FROM files WHERE user_id = User.id AND tag = "avatar" ORDER BY isEvidenza DESC LIMIT 1)'
				);
				

				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
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
						
						'username' => array(
						
							'email' => array(
							'rule' => 'email',
							'message' => $this->getError('VALID_EMAIL')
							),
							'isUnique' => array (
								'rule' => array('checkUniqueUser'),
								'message' => 'Username già esistente.'
							),							
						),
					
						'nome' => array(
							array('rule' => 'notEmpty',
								  'message' => $this->getError('REQUIRED_FIELD')
							)
						),
					
						'cognome' => array(
						
							array('rule' => 'notEmpty',
								  'message' => $this->getError('REQUIRED_FIELD')
							)
						
						),
						
						'data_nascita' => array(
						
							array('rule' => 'notEmpty',
								  'message' => $this->getError('REQUIRED_FIELD')
							)
						
						),						
						
					);
					
				}

				function checkUniqueUser() {
				
					$conditions = array('User.username' => $this->data['User']['username']);
					
					if(isset($this->data['User']['id']) && !empty($this->data['User']['id'])) 
						$conditions['User.id !='] = $this->data['User']['id'];
						
					return ($this->find('count', array('conditions' => $conditions)) == 0);
				
				}
				
				
				function beforeSave() {
				
					if(isset($this->data['User']['password']) && isset($this->data['User']['password_confirm'])) {

						if($this->data['User']['password'] == '1a6dfa98cdea32e67b5fc3104efe3d8d78ab1f49') { $this->invalidate('password',$this->getError('REQUIRED_FIELD')); return false; }
						
						//else if(strlen($this->data['User']['password']) < 6) { $this->invalidate('password',$this->getError('PASSWORD_LENGTH')); return false; }
						
						else if($this->data['User']['password_confirm'] == '1a6dfa98cdea32e67b5fc3104efe3d8d78ab1f49') { $this->invalidate('password_confirm',$this->getError('REQUIRED_FIELD')); return false; }
						
						else if($this->data['User']['password'] != $this->data['User']['password_confirm']) { $this->invalidate('password_confirm',$this->getError('PASSWORD_CONFIRM')); return false; }

					}

					return parent::beforeSave();
					
				}

	
	}

?>
