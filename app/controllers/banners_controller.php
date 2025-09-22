<?

	class BannersController extends AppController {
	
		var $name = "Banners";
		var $helpers = array('Backend','Javascript','Cksource');
		var $uses = array('Upload','Banner','BannersRow','Order');
		var $login_required = true;
		
			function admin_index() {
				
					
				
			}
			
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
				
				$rows = $this->BannersRow->find('list', array(
				
					'fields'     => array('BannersRow.Descrizione','BannersRow.Descrizione'),
					'conditions' => array(
					
						'BannersRow.disabled' => 0,
					
					),
					'order' => 'BannersRow.Descrizione ASC',
				
				));
				
				$this->set('rows', $rows);					
						
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
			
			}

 			function admin_add() {
			
				$this->layout = "ajax";	
				
				$rows = $this->BannersRow->find('list', array(
				
					'fields'     => array('BannersRow.id','BannersRow.Descrizione'),
					'conditions' => array(
					
						'BannersRow.disabled' => 0,
					
					),
					'order' => 'BannersRow.Descrizione ASC',
				
				));
				
				$this->set('rows', $rows);
										
				if (!empty($this->data)) {
				
					list($type, $width, $height, $valenza)    = split('-', $this->data['Banner']['Tipo']);
					
					$this->data['Banner']['Tipo']   = $type;
					$this->data['Banner']['width']  = $width;
					$this->data['Banner']['height'] = $height;
					$this->data['Banner']['valenza']= $valenza;
					
					if(!$this->checkSpace($type, $this->data['Banner']['row_id'], $valenza)) {
					
						$this->Banner->invalidate('row_id', 'Spazio banner pieno.');
					
						return false;
					
					}
				
					$this->Banner->set($this->data);
					
					if ($this->Banner->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('banner_id',$this->Banner->id) == true) {
						
							$ADD_OK = true;
							
						}						
													
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				
				$rows = $this->BannersRow->find('list', array(
				
					'fields'     => array('BannersRow.id','BannersRow.Descrizione'),
					'conditions' => array(
					
						'BannersRow.disabled' => 0,
					
					),
					'order' => 'BannersRow.Descrizione ASC',
				
				));
				
				$this->set('rows', $rows);	
				
				Configure::write('debug',2);

				/*
				$type_virtual = array(
				
					'Full' => 'Full-960-100-4',
					'Half' => 'Half-475-100-2',
					'Quarter' => 'Quarter-232-100-1',
				
				);
				*/	
				
				$type_virtual = array(
				
					'Full' => 'Full-960-130-4',
					'Half' => 'Half-475-130-2',
					'Quarter' => 'Quarter-232-130-1',
				
				);								

				if (empty($this->data)) {
								
					$this->data = $this->Banner->find('first',array('conditions' => array('Banner.id' => $id)));					
					$this->data['Banner']['Tipo'] = $type_virtual[$this->data['Banner']['Tipo']];
				
				} else {
				
					$data = $this->Banner->findById($this->data['Banner']['id']);
				
					list($type, $width, $height, $valenza)    = explode('-', $this->data['Banner']['Tipo']);
					
					$this->data['Banner']['Tipo']   = $type;
					$this->data['Banner']['width']  = $width;
					$this->data['Banner']['height'] = $height;
					$this->data['Banner']['valenza']= $valenza;	
					
					if(!$this->checkSpace($type, $this->data['Banner']['row_id'], $valenza, $id)) {
					
						$this->Banner->invalidate('row_id', 'Spazio banner pieno.');
					
						return false;
					
					}
					
					$this->Banner->set($this->data);
				
					if ($this->Banner->save()) {
					
						$ADD_OK = true;		
		
						if ($this->__adminUploadFile('banner_id',$this->Banner->id) == true) {
						
							$ADD_OK = true;
							
						}		
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}	
			
			function checkSpace($type = null, $row_id = null, $valenza = 4, $banner_id = null) {
			
				$data   = $this->BannersRow->findById($row_id);
				
				if($banner_id != null) {
					/* Count valenza */
					$totale = 0;
					foreach($data['Banner'] as $banner) {
						if($banner['id'] != $banner_id && $banner['disabled'] == 0) $totale += $banner['valenza'];
					}
					/* ------------- */				
				} else {

				
					$totale = $data['BannersRow']['countFull'] + $data['BannersRow']['countHalf'] + $data['BannersRow']['countQuarter'];
				
				}
				
				$diff 	= 4 - $totale;
				
				if($valenza <= $diff) {
				
					return true;
				
				} else {
				
					return false;
				
				}
				
			}
			
			function getBannersRow() {
				
				$this->layout = null;
				
				$data = $this->BannersRow->find('all', array(
				
					'conditions' => array(
					
						'BannersRow.disabled' => 0,
					
					)
				
				));
				
				$order = $this->Order->find('first', array(
				
					'conditions' => array(
					
						'Order.model' => 'Banner',
					
					),
				
				));
				
				foreach($data as $k => $row) {

					switch($order['Order']['order_type']) {
					
						case 'ASC':
							$row_order = array_orderby($row['Banner'],$order['Order']['argument'],SORT_ASC);
						break;
						case 'DESC':
							$row_order = array_orderby($row['Banner'],$order['Order']['argument'],SORT_DESC);
						break;
					
					}
					
					unset($data[$k]['Banner']);
					$data[$k]['Banner'] = $row_order;
				}
				
				return $data;
			
			}
	
	}
