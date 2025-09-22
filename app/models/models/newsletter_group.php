<?
	class NewsletterGroup extends AppModel {
	
		var $name = 'NewsletterGroup';
		var $useTable = 'newsletters_groups';
		
			var $hasAndBelongsToMany = array(
		
				'NewsletterUser' =>
			
					array(
					
						'className' => 'NewsletterUser',
						'joinTable' => 'newsletters_groups_users',
						'foreignKey' => 'newsletter_group_id',
						'associationForeignKey' => 'newsletter_user_id',
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
			
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'title' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				
			);
			
			$this->virtualFields = array(
			
				'CountUser' => 
				
				"
				SELECT COUNT(*)
				FROM ".Configure::read('default_db_prefix')."newsletters_groups_users AS NewsletterGroupUser
				WHERE NewsletterGroupUser.newsletter_group_id = NewsletterGroup.id
				"
			
			);
			
		}
	
	}
	
?>