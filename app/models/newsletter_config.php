<?
	class NewsletterConfig extends AppModel {
	
		var $name = 'NewsletterConfig';
		var $useTable = 'newsletters_configs';
		
		var $belongsTo = array(
		
			'NewsletterAccount' => array(
			
				'className' => 'NewsletterAccount',
				'foreignKey' => 'account_email_id'
			
			)
		
		);
		
		function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'account_email_id' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				),
				'nr_email' => array(
					'rule' => 'notEmpty',
					'message' => $this->getError('REQUIRED_FIELD')
				)
				
			);
			
		}
	
	}
	
?>