<?

	class Order extends AppModel {
		
				var $name = 'Order';
				var $useTable = 'tables_orders';
				
			function __construct($id = false, $table = null, $ds = null) {
		
			parent::__construct($id, $table, $ds);
				
			$this->validate = 
			
			array(
				
				'model' => array(
					'rule' => 'isUnique',
					'message' => $this->getError('DUPLICATE_RECORD')
				),
				
			);
			
		}
	
	}

?>
