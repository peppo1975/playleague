<?

	class Banner extends AppModel {
			
				var $name = 'Banner';
				var $useTable = 'banners';
				
				var $hasMany = array(
				
					'Upload' => array(
						'className' => 'Upload',
						'foreignKey' => 'banner_id'
					)
				
				);
				
				var $belongsTo = array(
				
					'BannersRow' => array(
					
						'className' => 'BannersRow',
						'foreignKey' => 'row_id',
					
					),
				
				);
				
				var $virtualFields = array(
				
					'banner' => '(SELECT files.path FROM files WHERE files.banner_id = Banner.id)',
					'banner_ext' => '(SELECT files.ext FROM files WHERE files.banner_id = Banner.id)',
					'title' => 'Banner.Titolo',
				
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
						'Titolo' => array(

								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						),
						
						
						'Tipo' => array(

								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						),		
						
							
						
					);
					
					$order = $this->getOrder($this->name);
					
					$this->order = array(
					
						'Banner.' . $order['Order']['argument'] => $order['Order']['order_type'],
					
					);						
					
				}			
		
	}

?>
