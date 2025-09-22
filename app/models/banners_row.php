<?

	class BannersRow extends AppModel {
			
				var $name = 'BannersRow';
				var $useTable = 'banners_rows';
				
				var $virtualFields = array(
				
					'title'     => 'BannersRow.Descrizione',
					'countFull' => '(SELECT SUM(banners.valenza) FROM banners WHERE banners.row_id = BannersRow.id AND banners.disabled = 0 AND banners.Tipo = \'Full\')',
					'countQuarter' => '(SELECT SUM(banners.valenza) FROM banners WHERE banners.row_id = BannersRow.id AND banners.disabled = 0 AND banners.Tipo = \'Quarter\')',
					'countHalf' => '(SELECT SUM(banners.valenza) FROM banners WHERE banners.row_id = BannersRow.id AND banners.disabled = 0 AND banners.Tipo = \'Half\')',
				
				);
				
				var $hasMany = array(
				
					'Banner' => array(
					
						'className' => 'Banner',
						'foreignKey' => 'row_id',
					
					),
				
				);
				
				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
						'Descrizione' => array(

								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),
						
						
						),
						
					);
					
					$order = $this->getOrder($this->name);
					
					$this->order = array(
					
						'BannersRow.' . $order['Order']['argument'] => $order['Order']['order_type'],
					
					);						
					
				}			
		
	}

?>
