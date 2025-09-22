<?
	class NewsletterAccount extends AppModel {
	
		var $name = 'NewsletterAccount';
		var $useTable = 'newsletters_mails_accounts';
		
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'username' => array(
					'isUnique' => array (
						'rule' => array('checkUniqueUsername'),
						'message' => 'Username gia esistente.'
					),							
				),
				'host' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'port' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'sender_mail' => array(
					'rule' => 'email',
					'message' => $this->getError('VALID_EMAIL')
				),
				'sender_name' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				
			);
			
		}
		
		function checkUniqueUsername() {
		
			$conditions = array('NewsletterAccount.username' => $this->data['NewsletterAccount']['username']);
			
			if(isset($this->data['NewsletterAccount']['id']) && !empty($this->data['NewsletterAccount']['id'])) 
				$conditions['NewsletterAccount.id !='] = $this->data['NewsletterAccount']['id'];
				
			return ($this->find('count', array('conditions' => $conditions)) == 0);
		
		}			
			
	}
	
?>