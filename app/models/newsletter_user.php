<?
	class NewsletterUser extends AppModel {
	
		var $name = 'NewsletterUser';
		var $useTable = 'newsletters_users';
		
			var $hasAndBelongsToMany = array(
		
				'NewsletterGroup' =>
			
					array(
					
						'className' => 'NewsletterGroup',
						'joinTable' => 'newsletters_groups_users',
						'foreignKey' => 'newsletter_user_id',
						'associationForeignKey' => 'newsletter_group_id',
						'unique' => true,
						'conditions' => '',
						'fields' => '',
						'order' => '',
						'limit' => '',
						'offset' => '',
						'finderQuery' => '',
						'deleteQuery' => '',
						'insertQuery' => ''
					)
			
			);
			
		var $virtualFields = array(
		
			'created_it' => "DATE_FORMAT(NewsletterUser.created,'%d/%m/%Y %H:%i:%s')",
					
		);
				
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'email' => array(

						
					'notEmpty' => 
						array(
								'rule' => 'notEmpty',
								'message' => $this->getError('REQUIRED_FIELD')
						),
					'email' => array(
						'rule' => 'email',
						'message' => $this->getError('VALID_EMAIL')
					),
					'isUnique' => array(
						'rule' => array('checkUniqueEmail'),
						'message' => 'Utente già registrato alla newsletter.'
					),

				),
				
			);
			
		}
		
		function checkUniqueEmail() {
		
			$conditions = array('NewsletterUser.email' => $this->data['NewsletterUser']['email']);
			
			if(isset($this->data['NewsletterUser']['id']) && !empty($this->data['NewsletterUser']['id'])) 
				$conditions['NewsletterUser.id !='] = $this->data['NewsletterUser']['id'];
				
			return ($this->find('count', array('conditions' => $conditions)) == 0);
		
		}		
	
	}
	
?>