<?

	class BlocksController extends AppController {
	
		var $name = "Blocks";
		var $helpers = array('Backend','Javascript','Cksource');
		var $firstModel = 'Block';
		var $uses = array('Upload','Page','Block','Order');
		var $login_required = true;
		var $components = array('RequestHandler');
		
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
				
				$menus = $this->Page->generatetreelist(null, null, null, '_');
				$tree  = array();
				foreach($menus as $menu) {
					$v = $menu;
					$k = str_replace('_','',$menu);
					
					if(in_array($this->Auth->user('group_id'), Configure::read('group_id_news'))) {

						if(in_array($k,Configure::read('blocks_parent_id')) || $k == "News MGS") $tree[$k] = $v;

						if ( $k == "News MGS" ) {
							$tree[$k] = $v;
						} 

						//print_r($tree);
					} 
					elseif($this->Auth->user('group_id') == 11) {
						if(in_array($k,array('Menu','DTV, Web TV & YouTube'))) $tree[$k] = $v;				
					}					
					else $tree[$k] = $v;
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
			
			function admin_changeStatus($block_id, $status) {
				
				$this->layout = "ajax";
				
				$this->data = $this->Block->read(null, $block_id);
				$this->data['Block']['disabled'] = $status;
				
				$this->Block->set($this->data);
				
				if($this->Block->save()) {
					
					$status = $status;
				
				} else {
				
					$status = 'error';
				
				}
				
				$this->set('result', json_encode(array('status' => $status)));
				$this->render('/backend/ajaxResult');
			
			}

 			function admin_ajax_add() {
			
				$this->layout = "ajax";	
				
				$this->Block->set($this->data);
				
				if ($this->Block->save()) {

					$error = 0;
					$block = $this->Block->read(null, $this->Block->id);
					
				} else {
				
					$error = 1;
					$tmp   = $this->Block->invalidFields();
					
					foreach($tmp as $field => $error) {
						$block[ucfirst($field)] = $error;
					}
				
				}
				
				$this->set('result',json_encode(array('data' => $block, 'error' => $error)));
				$this->render('/backend/ajaxResult');				
				
			}
			
 			function admin_add() {
			
				$this->layout = "ajax";

				$menus = $this->Page->generatetreelist(null, null, null, '_');
				$tree  = array();
				foreach($menus as $key => $menu) {
					$v = $menu;
					$k = str_replace('_','',$menu);
					if($this->Auth->user('group_id') == Configure::read('group_id_news')) {
						if(in_array($k,Configure::read('blocks_parent_id'))) $tree[$key] = $v;
					} 
					elseif($this->Auth->user('group_id') == 11) {
						if(in_array($k,array('Menu','You Tube Channel'))) $tree[$key] = $v;				
					}										
					else $tree[$key] = $v;
				}
				$this->set('tree', $tree);		
										
				if (!empty($this->data)) {
				
					$this->Block->set($this->data);
					
					if ($this->Block->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('block_id',$this->Block->id) == true) {
						
							$ADD_OK = true;
							
						}						
													
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}			
			
			function admin_ajax_edit($id) {
			
				$this->layout = "ajax";

				$data = $this->Block->read(null, $id);
				
				$this->set('result',json_encode(array('block' => $data )));
				$this->render('/backend/ajaxResult');
		
			
			}	
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				
				$menus = $this->Page->generatetreelist(null, null, null, '_');
				$tree  = array();

				foreach($menus as $key => $menu) {
					$v = $menu;
					$k = str_replace('_','',$menu);
					if($this->Auth->user('group_id') == Configure::read('group_id_news')) {
						if(in_array($k,Configure::read('blocks_parent_id'))) $tree[$key] = $v;
					} 
					elseif($this->Auth->user('group_id') == 11) {
						if(in_array($k,array('Menu','DTV, Web TV & YouTube'))) $tree[$key] = $v;				
					}										
					else $tree[$key] = $v;
				}
				
				$this->set('tree', $tree);

				if (empty($this->data)) {
								
					$this->data = $this->Block->find('first',array('conditions' => array('Block.id' => $id)));
					$this->data['Block']['published'] = ($this->data['Block']['published_it'] != '00/00/0000')? $this->data['Block']['published_it'] : '';
           
		            // Funzione data fine per news dalla redazione e ultim'ora 04/05/2018

		            $this->data['Block']['over'] = ($this->data['Block']['over_it'] != '00/00/0000') ? $this->data['Block']['over_it'] : '';

		            // ----------------------------------------------	

					$this->data['Block']['created'] = $this->data['Block']['created_form'];
				
				} else {
						
				$this->Block->set($this->data);
				
					if ($this->Block->save()) {
					
						$ADD_OK = true;		
						
						if ($this->__adminUploadFile('block_id',$id) == true) {
						
							$ADD_OK = true;
							
						}							
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}				
			
			function admin_ajax_delete($id) {
			
				$this->layout = "ajax";
				
				if($this->Block->delete($id)) {
				
					$delete = 1;
				
				} else {
				
					$delete = 0;
				
				}
				
				$this->set('result', json_encode(array('delete' => $delete)));
				$this->render('/backend/ajaxResult');				
			
			}
			
			
			/* 
				function getBlockNews($tagf = null) {

				if(!isset($this->params['tag'])) $tag = array(Configure::read('default_news_type'),"News calcio a 5");
				else 							 $tag = $this->params['tag'];
				
				if($tagf)
				{
					$tag = [$tagf];
				}


				foreach($tag as $title) {
				
				$page = $this->Page->find('first', array('conditions' => array('Page.title' => $title)));
				$childrens = $this->Page->children($page['Page']['id'], null);
				
				$tags = array($title);
				foreach($childrens as $child) {
					$tags[] = $child['Page']['title'];
				}
				
				foreach($tags as $tg) {
				
				$blocks[$tg] = $this->Block->find('all', array(
				
					'conditions' => array(
						
						'Block.mother_page' => $tg,
						'Block.disabled' => 0,
						'Block.published <= NOW()',
					
					),
					'order' => 'Block.published DESC',
					'limit' => 12
				
				));
				
				}
				
				}
				

				$data = array();
				
				foreach($blocks as $title => $articoli) {
				
					foreach($articoli as $k => $news) { 
				
					$data[$title][]['News'] = $news['Block'];
					if($news['Block']['published'] == '0000-00-00 00:00:00') unset($data[$title][$k]);
					
					}
				
				}
				
				return $data;
				
			}
			*/


		
		/* FUNZIONE STAMPA NEWS SVILUPPATA DA GIUSEPPE - 27/09/2016 */
		function getBlockNews($nameClass)
		{
			
			$tag = "";
			switch($nameClass)
			{
				case 'primary':
				//$tag = $this->params['tag'];
				$tag = array(Configure::read('option_news_type'),Configure::read('option_news_type_1'));
				break;
				
				case 'secondary':
				//$tag = array(Configure::read('default_news_type'),"News calcio a 5");
				$tag = array(Configure::read('option_scuola'));
				break;
				
				case 'quaternary':
				$tag = array(Configure::read('option_tennis'));
				//$tag = array(Configure::read('default_news_type'),"News calcio a 5");
				break;
			}
			
			
			foreach($tag as $title) {
				
				$page = $this->Page->find('first', array('conditions' => array('Page.title' => $title)));
				$childrens = $this->Page->children($page['Page']['id'], null);
				
				$tags = array($title);
				foreach($childrens as $child) {
					$tags[] = $child['Page']['title'];
				}
				
				foreach($tags as $tg) {
					
					$blocks[$tg] = $this->Block->find('all', array(
					
					'conditions' => array(
					
					'Block.mother_page' => $tg,
					'Block.disabled' => 0,
					'Block.published <= NOW()',
					
					),
					'order' => 'Block.published DESC',
					'limit' => 12
					
					));
					
				}
				
			}
			
			
			$data = array();
			
			foreach($blocks as $title => $articoli) {
				
				foreach($articoli as $k => $news)
				{ 
					$data[$title][]['News'] = $news['Block'];
					if($news['Block']['published'] == '0000-00-00 00:00:00') unset($data[$title][$k]);
				}
			}
			
			return $data;
			
			//echo(json_encode($blocks));
			//exit;
		}

		/* ---------------------------------------------------------------- */
		
			/* FRONT END FUNCTION */
			
			function index($id = null) {
			
				$this->layout = 'content';
				$this->facebook = true;
				
				$data = $this->Block->findById($id);
				
				//if($data['Block']['type'] == 0) $this->redirect('/contenuti/' . $data['Page']['id'] . '/' . strtolower(Inflector::Slug($data['Page']['title'],'-')));
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
					
					$data_children[$k]['Block'] = $tmp;
					
				}				
				$data['Brothers'] = $data_children;
				//Controllo publicazione
				$notNow = 0;
				$published = strtotime($data['Block']['published']);
				$now       = strtotime(date("Y-m-d"));
				if($published <= $now) $notNow = 0;
				else 				   $notNow = 1;
				
				//if(empty($data) || $notNow || $data['Block']['type'] == 0) $this->redirect('/errors/404');			
				
				$this->set('data',$data);
			
			}
			
			/* */
			
			/* Feed rss notizie */
			
			function feed_campionati() {
			
				if( $this->RequestHandler->isRss() ){

				$data = $this->getBlockNews("News dalla redazione");
				$news = array();
				
				foreach($data as $categorie) {
					foreach($categorie as $new) {
						foreach($new as $tmp) {
							$news[] = $tmp;
						}
					}
				}
				
				$news_order = array_orderby($news, 'published', SORT_DESC);
				
				$this->set('data', $news_order);
								$this->render('feed');

				}
			
			}

			function feed_scuolaa5() {
			
				if( $this->RequestHandler->isRss() ){

				$data = $this->getBlockNews("News");
				$news = array();
				
				foreach($data as $categorie) {
					foreach($categorie as $new) {
						foreach($new as $tmp) {
							$news[] = $tmp;
						}
					}
				}
				
				$news_order = array_orderby($news, 'published', SORT_DESC);
				
				$this->set('data', $news_order);
				$this->render('feed');
				}
			
			}

}