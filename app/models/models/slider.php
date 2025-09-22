<?

	class Slider extends AppModel {
			
				var $name = 'Slider';
				var $useTable = 'sliders';
				
				var $hasMany = array(
				
					'Upload' => array(
						'className' => 'Upload',
						'foreignKey' => 'slider_id',
						'order' => array('Upload.order' => 'ASC')
					)
				
				);				

				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'title' => array(

							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						)
						
					);
					
					
					$order = $this->getOrder($this->name);
					
					$this->order = array(
					
						'Slider.' . $order['Order']['argument'] => $order['Order']['order_type'],
					
					);
					
				}

	}

?>
