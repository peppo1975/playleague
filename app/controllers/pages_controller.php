<?

	class PagesController extends AppController {
	
		var $name = "Pages";
		var $helpers = array('Backend','Javascript','Cksource');
		var $login_required = true;
		var $firstModel = 'Page';
		var $components = array('ControllerList');
		var $uses = array('Upload','Page','Order','Block');

		function prova()
		{
			$this->autoRender = false;
			$this->Page->recover();
					debug( $this->getMenu());
					exit;
		}


		function admin_index()
		{
			// NON CANCELLATE QUESTA CAZZO DI FUNZIONE 
			// PERCHE' UNA FUNZIONE VUOTA E' UTILE !!! DIO PORCO
			// 
		}
		
		function getController() {

//			$controllers = $this->ControllerList->get(); //GIUSEPPE 2023-09-09
			$controllers = $this->ControllerList; //GIUSEPPE 2023-09-09

			ksort($controllers);

			$out = array();
			
			foreach($controllers as $controller => $action) {
			
				$out[$controller] = $controller;
			
			}
			
			return $out;

		}

		function getAction($controller = null) {
		
			$controllers = $this->ControllerList->get();
			
			$controller_ = $controllers[$controller];
			$actions = array();
			
			foreach($controller_ as $action) {
			
				$actions[$action] = $action;
			
			}
			
			return $actions;
			
		}
		
		function ajaxGetAction($controller) {
		
			$this->layout = "ajax";
			
			$actions = $this->getAction($controller);
			
			$this->set('result', json_encode($actions));
			$this->render('/backend/ajaxResult');
			
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
			
			$tree = array();
			$tmp  = $this->Page->generatetreelist(null, null, null, '_');
			
			foreach($tmp as $t) {
			
				$tree[str_replace('_','',$t)] = $t;
			
			}
			
			$this->set('tree', $tree);
					
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
			
			$this->set('controllers', $this->getController());
			
			$this->set('tree', $this->Page->generatetreelist(null, null, null, '_'));
			
			//$this->get_array_elems($arraydiritorno);	
			
			$this->getAction('AnniSportivis');
									
			if (!empty($this->data)) {
			
				$this->Page->set($this->data);
				
				if ($this->Page->save()) {
					
					$ADD_OK = true;
					
					if ($this->__adminUploadFile('page_id',$this->Page->id) == true) {
					
						$ADD_OK = true;
						
					}
												
					if ($ADD_OK) {
								
						$this->set('result','ADD_OK');
						$this->render('/backend/ajaxResult');
					
					}
					
				}
				
			}
			
		}
		
		function getMenu()
		{
			
			$this->layout = null;
			
			/* Get order from database */
			
			$order = $this->Order->find('first', array(
			
			'conditions' => array(
			
			'Order.model' => 'Page', 
			
			)
			
			));
			
			//debug($order);
			
			/**/
			
			if($this->Page->hasField($order['Order']['argument']) == false) $order['Order']['argument'] = 'title'; //GIUSEPPE
			
			//$data = $this->Page->find('all', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0)));	
			
			$data = $this->Page->find('all', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0),'order'=>array('Page.id'=>'asc'))); //GIUSEPPE 
			
			$menus = [];
			
			foreach( $data as $parent )
			{
				$menus[] = array('name' => $parent['Page']['title'],'children' => $this->getMenuByParent($parent["Page"]["id"], $order['Order']['argument'], $order['Order']['order_type']));
			}
			
			//debug($menus);
			
			return $menus;
			
		}
		
		//array_orderby($c_classifica,'Punti',SORT_DESC);
		
		function getMenuByParent($parent_id = null, $order_argument = 'order', $order_type = 'ASC') {
		
		   if($parent_id == null) { $data = $this->Page->find('first', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0))); $parent_id = $data['Page']['id']; } 
			
		   $menu = array();
		  
		   $figli = $this->Page->children($parent_id,true);




		   if (count($figli)) {

			$tmp = array();
			
			foreach($figli as $i => $f) {
				$tmp[$i] = (isset($f['Page']))? $f['Page'] : $f;
			}
		   
		    $c_figli = $tmp;
			
			switch($order_type) {
				case 'ASC':	
					$c_figli = array_orderby($c_figli,$order_argument,SORT_ASC);
				break;
				case 'DESC':
					$c_figli = array_orderby($c_figli,$order_argument,SORT_DESC);
				break;
			}
			
			$figli = $c_figli;				
		   if (count($figli)) {
			   
			 $r_figli = 0;
			 $menu_c = array();
			 //$menu_c .= '<ul class="menu-main">';
			  
			  foreach ($figli as $i => $figlio) {
			  
			  $published = strtotime($figlio['published']);
			  $now       = strtotime(date("Y-m-d"));
			  
				if($figlio['disabled'] == 0 && $published <= $now) {
				
				$url = $this->getPageUrl($figlio);
				
				 //$menu_c .= '<li><a href="'.$url.'" title="">' . $figlio['title'] . '</a>';
				 $menu_c[] = array('url' => $url,'name' => $figlio['title'],'children' => $this->getMenuByParent($figlio['id'], $order_argument, $order_type));
				 //$menu_c[]=$this->getMenuByParent($figlio['id'], $order_argument, $order_type);
				 //$menu_c .= '</li>'; 
				 
				
				$r_figli++;
				
				}
				
				

			  }
			  
			 //$menu_c .= '</ul>';
		   
		   if ($r_figli > 0) $menu = array_merge($menu,$menu_c);
		   
		   }
		   }
		   
		   return $menu;
		 
		}	
		
		function listArrayRecursive($array_name){
			
			if (is_array($array_name)){
				foreach ($array_name as $k => $v){
					if (is_array($v)){
						echo ((isset($v['Page']['title']))? $v['Page']['title'] : '') . "<br />";
						$this->listArrayRecursive($v);
					}
				}				
			}
			
		}			
		
		function admin_edit($id) {
		
			$this->layout = "ajax";
			
			$this->set('controllers', $this->getController());
			$this->set('blocks', $this->Block->find('all', array('conditions' => array('Block.page_id' => $id),'order' => array('Block.order ASC'))));
			$this->set('tree', $this->Page->generatetreelist(null, null, null, '_'));

			if (empty($this->data)) {
							
				$this->data = $this->Page->find('first',array('conditions' => array('Page.id' => $id)));
				$this->data['Metadata'] = getMetadata('Page', $id);
				$this->data['Page']['published'] = ($this->data['Page']['published_it'] != '00/00/0000')? $this->data['Page']['published_it'] : '';
				$this->Page->set($this->data);
			
			} else {
									
			$this->Page->set($this->data);
			
				if ($this->Page->save()) {
				
					$ADD_OK = true;		

					if ($this->__adminUploadFile('page_id',$id) == true) {
					
						$ADD_OK = false;
						
					}						
					
					if ($ADD_OK) {
						$this->set('result','ADD_OK');
						$this->render('/backend/ajaxResult');
					}	
				
				}
				
			}
		
		}	
		
		function getPageUrl($pages = array()) {
		
			$this->layout = null;
				
			$url = '';
			
			if(!is_array($pages)) {
				$data = $this->Page->findById($pages);
				$pages= $data['Page'];
			}
			
			switch($pages['type']) {
			
				case 'url':
				
					$url = '/' . ($pages['url'] != '')? $pages['url']: '#';	
				
				break;
				
				case 'dinamic':
				
					//CHECK PREFIX
					
						$tmp = explode('_', $pages['action']);
						
						if(count($tmp) == 2) {
						
							$prefix 				 = '/' . $tmp[0] . '/';
							$pages['action'] = $tmp[1];
						
						} else {
						
							$prefix = '/';
						
						}
					
					//	
				
					//$url = $prefix . strtolower($pages['controller']) . '/' . $pages['action'];
					
					$url = '/' . strtolower(Inflector::Slug($pages['alias'],'-'));
					
					if($pages['params'] != '') {
					
						$params = explode(',',$pages['params']);
						
						foreach($params as $param) {
						
							$url .= '/' . $param;
						
						}
					
					}
					
				break;
				
				case 'static':
				
					$url = '/contenuti/' . $pages['id'] . '/' . strtolower(Inflector::Slug($pages['title'],'-'));
				
				break;
			
			}

			return ($url != '')? $url : '#';
		
		}
		
		//FUNCTION FRONT END 
		
		function copyright() {
		
			$this->layout = "ajax";
		
		}
		
		function privacy() {
		
			$this->layout = "ajax";
		
		}	

		function index($id = null, $slug = null, $page = 1) {
		
			if($id == null || !is_numeric($id)) $this->redirect('/errors/404');
		
			$this->layout = "content";
			$this->facebook = true;
			
			$data = $this->Page->findById($id);
			
			$this->viewVars['id_css'] = ($data['Page']['id_css'] == '')? $data['Page']['id_css']:'id="'.$data['Page']['id_css'].'"';
			
			unset($data['Block']);
			
			$blocks_tmp = $this->Block->find('all', array(
				'conditions' => array(
					'Block.disabled' => 0,
					'Block.published <= NOW()',
					'Block.page_id'  => $id,
				),
			));
			$n_blocks = count($blocks_tmp);
			$options = array(
				'conditions' => array(
					'Block.disabled' => 0,
					'Block.published <= NOW()',
					'Block.page_id'  => $id,
				),
				'limit' => $data['Page']['block_limit'],
				'offset' => ($page - 1) * $data['Page']['block_limit'],	
			);
			//Check if is news block (se sono news hanno tutte la data di pubblicazione) :D
			$count_published = 0;
			foreach($blocks_tmp as $block_page) {
				if(in_array($block_page['Page']['title'], Configure::read('isNews'))) $count_published++;
			}
			
			//if ($count_published == $n_blocks) //GIUSEPPE 2018-09-05 → commentato questo if, elencava con data di pubblicazione decrescente solo i "Campionati/tornei"

			$options['order'] = array('Block.published DESC');
			
			$blocks = $this->Block->find('all', $options);
			
			if($data['Page']['block_limit'] > 0) {
				$n_page = ceil($n_blocks/$data['Page']['block_limit']);
			} else $n_page = 0;
			
			$notNow = 0;
			$published = strtotime($data['Page']['published']);
			$now       = strtotime(date("Y-m-d"));
			if($published <= $now) $notNow = 0;
			else 				   $notNow = 1;
			
			if(empty($data) || $notNow || $data['Page']['type'] != 'static') $this->redirect('/errors/404');
			$data['Block'] = $blocks;
			
			//Children of mother page --- > brothers
			$tmp = $this->Page->children($data['Page']['parent_id'],true);
			$childrens = array();
			foreach($tmp as $children) {
				$childrens[] = $children['Page']['id'];
			}
			$data_children = $this->Page->find('all', array(
				'conditions' => array(
					'Page.id' => $childrens,
					'Page.disabled' => 0,
				),
			));
			//Ordino pagine e blocchi
			$orders = $this->Order->find('first', array(
				'conditions' => array(
					'Order.model' => 'Block',
				),
			));
			foreach($data_children as $k => $children) {
				
				switch($orders['Order']['order_type']) {
				
					case 'ASC':
						$tmp = array_orderby($children['Block'],$orders['Order']['argument'],SORT_ASC);
					break;
					
					case 'DESC':
						$tmp = array_orderby($children['Block'],$orders['Order']['argument'],SORT_DESC);
					break;
				
				}

				unset($data_children[$k]['Block']);
				
				//$data_children[$k]['Block'] = $tmp;
				
			}
			
			$data['Brothers'] = $data_children;
			
			$this->set('n_page', $n_page);
			$this->set('data', $data);
		
		}
		
		function isDisabled($id) {
			
			$this->layout = null;
			
			$page = $this->Page->findById($id);
			
			if (isset($page['Page']['disabled'])) return $page['Page']['disabled'];
			
			return 1;
			
		}
	
	}
