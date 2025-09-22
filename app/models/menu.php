<?


	class Menu extends AppModel {
			
				var $name = 'Menu';
				var $useTable = 'menu';
								
				var $virtualFields = array(
				
					'created_id' => 'DATE_FORMAT(Menu.created, "%d/%m/%Y")',
					'modified_it' => 'DATE_FORMAT(Menu.modified, "%d/%m/%Y")',
					'Node'        => 'CONCAT("node-", Menu.id)',

				);
				
				var $hasAndBelongsToMany = array(
			
					'Page' =>
				
						array(
						
							'className' => 'Page',
							'joinTable' => 'pages_as_menus',
							'foreignKey' => 'menu_id',
							'associationForeignKey' => 'page_id',
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
					
				} 
				

	}

?>
