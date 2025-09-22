<?

	class MobileController extends AppController {
	
		var $name = "Mobile";
		var $helpers = array('Backend','Javascript','Cksource');	
		var $login_required = true;
		//var $uses = array('Upload','Page','Match','Order','Campionati','Block','Half','Campi','CampiOrari','CampiBooking');
		
		var $uses = array(
		
			'Campi',
			'Comunication',
			'CampiOrari',
			'CampiBooking',
			'Comunication', 
			'AnniSportivi',
			'Ranking',
			'Yearbook',
			'Campionati',
			'Half',
			'Campicampionati',
			'Match',
			'SquadreCampionati',
			'Matchgoal',
			'Disciplinari',
			'FinalStage',
			'Squadre',
			'Athlete',
			'Page',
			'Block',
			'User',
			'Athlete',
			'Upload',
			'Order',
			'Teambook',
			'Group',
			'LdaVote',
			'LdaWall',
			'AthleteExpense',
			'ChampCategory'
		);		
		
		var $main = 19;
		var $main_news = 52;
		var $exclude = array('52','49');
		
		function beforeFilter() {
		
			parent::beforeFilter();
			
			$menu_categories = $this->Page->find('all', array(
			
				'conditions' => array(
					'Page.parent_id' => $this->main,
					'Page.id NOT' => 0
				)
			
			));

			$menu_news = $this->Page->find('all', array(
			
				'conditions' => array(
					'Page.parent_id' => $this->main_news
				)
			
			));
			
			$categories = array();
			$news       = array();
			
			foreach($menu_categories as $cat) {
				$categories[$cat['Page']['id']] = array('title' => ($cat['Page']['title_mobile'] != "")? $cat['Page']['title_mobile'] : $cat['Page']['title'],'real_title'=>$cat['Page']['title']);
			}


			foreach($menu_news as $cat) {
				$news[$cat['Page']['id']] = ($cat['Page']['title_mobile'] != "")? $cat['Page']['title_mobile'] : $cat['Page']['title'];
			}
			
			$this->set('menu_categories', $categories);
			$this->set('menu_news', $news);			
		
		}
		
			function getAthleteInfo($id_athlete, $return = 0, $annuario) {
				
				/*
				 * PRESENZE - RETI - AMMONIZIONI - ESPULSIONI - GIORNATE SQUALIFICA 
				 */
				 
				 $this->layout = "ajax";
				 
					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					
					$data = $this->Yearbook->find('all', array(
						
						'fields'     => array('Yearbook.Annuario', 'Yearbook.Atleta', 'Yearbook.SquadraCampionato'),
						'conditions' => array(
						
							'Yearbook.Atleta' => $id_athlete,
							'Yearbook.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
							'Yearbook.Annuario' => $annuario
						
						),
						'group' => array('Yearbook.SquadraCampionato')
					
					));
					
					$athleteStats = array();
					
					foreach($data as $squadra_campionato) {
						
						/*
						 * PRESENZE - RETI - AMMONIZIONI - ESPULSIONI - GIORNATE SQUALIFICA 
						 */
						 
								  //$this->Matchgoal->recursive = 2;
						 $stats = $this->Matchgoal->find('all', array(
						 
							'conditions' => array(
							
								'Matchgoal.Atleta' => $id_athlete,
								'Matchgoal.SquadraCampionato' => $squadra_campionato['Yearbook']['SquadraCampionato']
							
							),
						 
						 ));
	
						 
						 //Presenze
						 
						 $presenze = count($stats);
						 
						 //Reti
						 $reti 		  = 0;
						 
						 //Ammonizioni
						 $ammonizioni = 0;
						 
						 //Espulsioni
						 $espulsioni  = 0;
						 $gEspulsioni = 0;
						 
						 //Autoreti  
						 $autoreti    = 0;
						 
						 foreach($stats as $stat) {
							
							$stat = $stat['Matchgoal'];
							
							if($stat['Ammonizione'] == 'Si')
								$ammonizioni++;
							if($stat['Espulsione'] == 'Si')
							{
								$espulsioni++;
								$gEspulsioni += $stat['Giornate'];	
							}
							if($stat['Goal'] > 0)
							{
								$reti+=$stat['Goal'];
							}
							if($stat['Autogoal'] > 0)
							{
								$autoreti+=$stat['Autogoal'];
							}				 	
							
						 }
						 
						 $stats = array(
						 
							'Presenze'    		 => $presenze,
							'Reti'        		 => $reti,
							'Autoreti'    		 => $autoreti,
							'Ammonizioni' 		 => $ammonizioni,
							'Espulsioni'  		 => $espulsioni,
							'GiornateSqualifica' => $gEspulsioni 
						 
						 );
						 
						 $this->Yearbook->recursive = 2;
						 
						 $yearBook = $this->Yearbook->read(null, $squadra_campionato['Yearbook']['Annuario']);
						 $yearBook['Stats'] = $stats;
						 
						 $athleteStats[] = $yearBook; 
						 
					}
					
					//$this->set('athlete', $this->Athlete->read(null, $id_athlete));
					//$this->set('data', $athleteStats);
				 
				 if($return)
					return $stats;
				 
					$this->set('athlete', $this->Athlete->read(null, $id_athlete));
					$this->set('data', $athleteStats);
				
			}		


			function getsquadra($id) {
				
				$this->layout = 'ajax';
				$squadre = $this->Squadre->find('all',array('order' => 'Squadre.Denominazione ASC','conditions' => array(
				
					"Squadre.Squadra IN 
					
					($id)",
					"Squadre.SquadraServizio" => 0
				
				)));
				
				
		
				foreach ($squadre as $squadra) {
					
					$nome = $squadra['Squadre']['Denominazione'];
					
					$campionati = $this->SquadreCampionati->find('count',array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra'])));
					
					$squadra['Info']['Campionati'] = $campionati;
					
					
					$stagioni = $this->SquadreCampionati->find('all',array('fields' => 'Campionati.AnnoSportivo','conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra']), 'order' => 'Campionati.AnnoSportivo DESC'));
					
					
					
					
					
					$tmp = array();
					$c = 0;
					$yt = date("Y");
					foreach($stagioni as $stagione) {
					if ($c == 0)  $yt = $stagione['Campionati']['AnnoSportivo'];
					$c++;
						$tmp[$stagione['Campionati']['AnnoSportivo']] = $stagione['Campionati']['AnnoSportivo'];
					}
					
				$squadra_id = $this->SquadreCampionati->find('first',array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra'],'Campionati.AnnoSportivo' => $yt), 'order' => 'Campionati.AnnoSportivo DESC'));
				$squadra_id = $squadra_id['SquadreCampionati']['SquadraCampionato'];
					
				$roster = $this->Athlete->query("
				
				SELECT Athlete.*,
				Yearbook.Tessera,
				Yearbook.NumeroMaglia,
				Yearbook.Ruolo,
				Yearbook.Annuario,
				TipiAssicurazione.Simbolo,
				DATE_FORMAT(Athlete.DataNascita,'%d.%m.%Y') AS Athlete__DataNascita_it,
				DATE_FORMAT(Athlete.ScadenzaDocumento,'%d/%m/%Y') AS Athlete__ScadenzaDocumento_it,
				CONCAT(Athlete.Nome,' ',Athlete.Cognome) AS Athlete__Anagrafica,
				CONCAT(Athlete.Cognome,' ',Athlete.Nome) AS Athlete__reverseAnagrafica,
				IF(foto_path != \"\",foto_path,(SELECT path FROM files WHERE athlete_id = Athlete.Atleta AND tag = \"avatar\" ORDER BY isEvidenza DESC LIMIT 1)) as Athlete__avatar
				
				
				
				FROM Atleti as Athlete,Annuario as Yearbook, TipiAssicurazione WHERE Athlete.Atleta = Yearbook.Atleta AND Yearbook.SquadraCampionato = $squadra_id AND Yearbook.TipoAssicurazione = TipiAssicurazione.TipoAssicurazione
				
				ORDER BY Athlete.Cognome, Athlete.Nome
				
				"
				);
				
				foreach($roster as &$a)
				{
					
					$a['stats'] = $this->getAthleteInfo($a['Athlete']['Atleta'],1, $a['Yearbook']['Annuario']);
					
				}
				
					$this->set('roster',$roster);
					
					unset($stagioni);
					$stagioni = $tmp;
					
					$string_stagioni = '';
					
					$stagioni = array_merge($stagioni);
					
					foreach($stagioni as $k => $stagione) {
						if($k+1 == count($stagioni)) $virgola = '';
						else $virgola = ', ';
						$string_stagioni .= $stagione . $virgola;
					}
					
					$squadra['Info']['Stagioni'] = $string_stagioni;
					
					$logo    = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Logo')));
					$sponsor = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Sponsor')));
					
					if (empty($logo)) {
						
						$squadra['Info']['logo'] = '';
						
					} else {
						
						$squadra['Info']['Logo'] = $logo['Upload']['path'];
						
					}
					
					if (empty($sponsor)) {
						
						$squadra['Info']['sponsor'] = '';
						
					} else {
						
						$squadra['Info']['sponsor'] = $sponsor['Upload']['path'];
						
					}					
					
					$start = substr(trim($nome),0,1);
					$end = substr(trim($nome),1,2);
					
					if (!is_numeric($start) && $end != "°") {
					
					$chiave = Inflector::Slug($start);
					
					//$alfabeto[strtoupper($chiave)][] = $squadra;

					$this->set('squadra',$squadra);
	
					}
					
		
				}
					
			
				
			}
				
			function getSquadre($tipo,$sessoTipo = 0,$anno = 'all',$varname = 'alfabeto') {
				
				if($anno == null) {
				
					$anni = $this->AnniSportivi->find('list', array('order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					$anno = $anni[key($anni)];
					$anno_string = $anno;
					$this->set('anno_s', $anno);
					
				} elseif($anno == 'all') {
					
					$anno = '';
					$anno_string = '';
					
				} else {
				
					$anno_string = $anno;
					$this->set('anno_s', $anno);
				
				}
				
				$this->set('years', $this->AnniSportivi->find('list', array('order' => 'AnniSportivi.AnnoSportivo DESC')));
				
				$alfabeto = array();
				
				if (!is_file(APP . '/webroot/files/json/get_squadre_' . $tipo . '_' . $sessoTipo . '_' . $anno_string . '.json')) {
					
				
				$squadre = $this->Squadre->find('all',array('order' => 'Squadre.Denominazione ASC','conditions' => array(
				
					"Squadre.Squadra IN 
					
					(SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.Campionato IN 
					
						(SELECT Campionati.Campionato FROM Campionati WHERE Tipo = '" . $tipo . "' AND SessoTipo = '" . $sessoTipo . "' AND AnnoSportivo LIKE '%".$anno."%' AND group_id = 1)
					
					)",
					"Squadre.SquadraServizio" => 0
				
				)));
				
				foreach ($squadre as $squadra) {
					
					$nome = $squadra['Squadre']['Denominazione'];
					
					$campionati = $this->SquadreCampionati->find('count',array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra'])));
					
					$squadra['Info']['Campionati'] = $campionati;
					
					
					$stagioni = $this->SquadreCampionati->find('all',array('fields' => 'Campionati.AnnoSportivo','conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra']), 'order' => 'Campionati.AnnoSportivo DESC'));
					
					$tmp = array();
					foreach($stagioni as $stagione) {
						$tmp[$stagione['Campionati']['AnnoSportivo']] = $stagione['Campionati']['AnnoSportivo'];
					}
					unset($stagioni);
					$stagioni = $tmp;
					
					$string_stagioni = '';
					
					$stagioni = array_merge($stagioni);
					
					foreach($stagioni as $k => $stagione) {
						if($k+1 == count($stagioni)) $virgola = '';
						else $virgola = ', ';
						$string_stagioni .= $stagione . $virgola;
					}
					
					$squadra['Info']['Stagioni'] = $string_stagioni;
					
					$logo    = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Logo')));
					$sponsor = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Sponsor')));
					
					if (empty($logo)) {
						
						$squadra['Info']['logo'] = '';
						
					} else {
						
						$squadra['Info']['Logo'] = $logo['Upload']['path'];
						
					}
					
					if (empty($sponsor)) {
						
						$squadra['Info']['sponsor'] = '';
						
					} else {
						
						$squadra['Info']['sponsor'] = $sponsor['Upload']['path'];
						
					}					
					
					$start = substr(trim($nome),0,1);
					$end = substr(trim($nome),1,2);
					
					if (!is_numeric($start) && $end != "°") {
					
					$chiave = Inflector::Slug($start);
					
					$alfabeto[strtoupper($chiave)][] = $squadra;
					
					}
					
					$json_data['timestamp'] = time();
					$json_data['data'] = $alfabeto;
					
					file_put_contents(APP . '/webroot/files/json/get_squadre_' . $tipo . '_' . $sessoTipo . '_' . $anno_string . '.json',json_encode($json_data));
					
				}
					
				} else {
					
					$json_data = json_decode(file_get_contents(APP . '/webroot/files/json/get_squadre_' . $tipo . '_' . $sessoTipo . '_' . $anno_string . '.json'),1);
					
					$alfabeto = $json_data['data'];
					
					$created = $json_data['timestamp'];
					
					$start = strtotime(date("Y-m-d") . " 00:00:00");
					$end = strtotime(date("Y-m-d") . " 23:59:59");
					
					if ($created <= $start || $created >= $end) {
						
						@unlink(APP . '/webroot/files/json/get_squadre_' . $tipo . '_' . $sessoTipo . '_' . $anno_string . '.json');
						
					}
					
				}
				
				
				
				
				
				$this->set($varname,$alfabeto);
				
			}
		
			function getShopCategories() {
			
				// create cURL resource
				$ch = curl_init();
				
				$host = $this->params['host'];
				 


				// set options
				curl_setopt($ch, CURLOPT_URL, $host . "/api/categories?display=full");
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, ('XFI9HUVUDJSQW6WHKMJKV9J449UG8DZK'));
				 
				// grab URL and pass it to the browser
				$ret = curl_exec($ch);
				
				//debug($ret);

				//print $ret;
				// close cURL resource, and free up system resources
				
				curl_close($ch);	

				// import XML class
				App::import('Xml');

				// your XML file's location
				//$file = "my_xml_file.xml";

				// now parse it
				$parsed_xml =& new XML($ret);
				$parsed_xml = Set::reverse($parsed_xml); // this is what i call magic
				
				//debug($parsed_xml);

				$categories = array();
				$except     = array('Home page'); //Mettere eccezioni.
				$limit      = 9;
				// see the returned array
				
				$jk = 0;
				

				foreach($parsed_xml['Prestashop']['Categories']['Category'] as $k => $cat) {
				
					
					
					if (isset($cat['id_parent']['value']) && $cat['id_parent']['value'] == 1) {
					if(!isset($cat['Associations']['Products']['Product'])) $cat['Associations']['Products']['Product'] = 0;
				
					if(!in_array($cat['Name']['language']['value'], $except)) {
				
					$categories[] = array(
					
						'id'   => $cat['id'],
						'name' => $cat['Name']['language']['value'],
						'url'  => "http://store.midlandsport.it" . '/' . $cat['id'] . '-' . strtolower(Inflector::Slug($cat['Name']['language']['value'],'-')),
						'count'=> count($cat['Associations']['Products']['Product']),
					
					);
					
					}
					
					
					if($jk == $limit) break;
					
					$jk++;
					
					}
					
				
				}

				$categories_order = array_orderby($categories,'count',SORT_DESC);				

				return $categories_order;			
			
			}		
		
		function copyright() {
		
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
		
		}
		
		function privacy() {
		
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
		
		}			
		
		function index() {
		
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
			
			/*
			$campionati = $this->Campionati->find('all',
			
				array(
				
					'conditions' => array(
				
							//'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale
							
							'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
							'Campionati.InCorso' => 'Si',
							'Campionati.group_id' => 1,
					
					),
					'order' => array('Campionati.Nome ASC'),
				
				)
			
			);

			$this->set('campionati',$campionati);
			
			$campi = $this->Campi->find('all', array(
			
				//'conditions' => array('Campi.isMidland' => 1,'(SELECT COUNT(*) FROM CampiOrari WHERE CampiOrari.campo_id = Campi.Campo) > 0'),
				'conditions' => array('Campi.isMidland' => 1),
				'order' => 'Campi.Descrizione ASC',
			
			));
			
			$this->set('campi',$campi);			
			*/
		
		}
		
			function search() {
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				if(!isset($this->data))
					$this->redirect('/mobile');
				
				$value = $this->data['Search']['search-mini'];
				
				$this->title  = "Risultati ricerca per: " . $value;
				
				$results = array();
				
				$blocks = $this->Block->find('all',array(
					'conditions' => array(
						array('OR' =>
							array(
								'Block.title LIKE' 	=> '%' . $value . '%',
								'Block.content LIKE' => '%' . $value . '%',
								'Page.title LIKE' 	=> '%' . $value . '%',
								'Page.content LIKE' => '%' . $value . '%',
								'PageURL.title LIKE' 	=> '%' . $value . '%',
								'PageURL.content LIKE' => '%' . $value . '%',
							)
						)
					)
				)
				);
				
				
				foreach ($blocks as $block) {
					
					$result = array();
					
					$result['title'] = $block['Block']['title'];
					$result['description'] = $block['Block']['content'];
					
					$result['link'] = '#';
					
					if ((int)$block['Block']['url_page_id'] == 0 && empty($block['Block']['url'])) {
						
						$result['link'] = '/mobile/view/'  . $block['Page']['id'] . '/' . strtolower(Inflector::Slug($block['Page']['title'],'-')) . '/' . $block['Block']['id'] . '/' . strtolower(Inflector::Slug($block['Block']['title'],'-'));
						
					}
					
					
					if (!empty($block['Block']['url'])) {
						
						
						$result['link'] = $block['Block']['url'];
						
					}
					
					if ((int)$block['Block']['url_page_id'] != 0) {
						
						$result['link'] = $this->getPageUrl($block['PageURL']);
						
					}
					
					if ($block['Block']['url_page_id'] != 0) {
						
						$result['link'] = $this->getPageUrl($block['Page']);
						
					}
					
 
					
					$results[] = $result;
					
				}
				
				
				
				$this->set('results',$results);
				$this->set('searchValue',$value);
				
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
				
					$url = '/view/' . $pages['id'] . '/' . strtolower(Inflector::Slug($pages['title'],'-'));
				
				break;
			
			}

			return ($url != '')? '/mobile' . $url : '#';
		
		}		
		
		function getMenu() {
		
			$this->layout = null;
			
			/* Get order from database */
			
			$order = $this->Order->find('first', array(
			
				'conditions' => array(
				
					'Order.model' => 'Page',
				
				)
			
			));
			
			//debug($order);
			
			/**/
			
			if($this->Page->hasField($order['Order']['argument']) == false) $order['Order']['argument'] = 'title';
			
			$data = $this->Page->find('first', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0))); $parent_id = $data['Page']['id'];				

			$menu = $this->getMenuByParent($parent_id, $order['Order']['argument'], $order['Order']['order_type']);
			
			return $menu;
		
		}
		
		//array_orderby($c_classifica,'Punti',SORT_DESC);
		
		function getMenuByParent($parent_id = null, $order_argument = 'order', $order_type = 'ASC') {
		
		   if($parent_id == null) { $data = $this->Page->find('first', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0))); $parent_id = $data['Page']['id']; } 
			
		   $menu = '';
		  
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
			 $menu_c = "";
			 $menu_c .= '<ul>';
			  
			  foreach ($figli as $i => $figlio) {
			  
			  $published = strtotime($figlio['published']);
			  $now       = strtotime(date("Y-m-d"));
			  
				if($figlio['disabled'] == 0 && $published <= $now) {
				
				$url = $this->getPageUrl($figlio);
				
				 $menu_c .= '<li><a href="'.$url.'" title="">' . $figlio['title'] . '</a>';
				 $menu_c .= $this->getMenuByParent($figlio['id'], $order_argument, $order_type);
				 $menu_c .= '</li>'; 
				 
				
				$r_figli++;
				
				}
				
				

			  }
			  
			 $menu_c .= '</ul>';
		   
		   if ($r_figli > 0) $menu .= $menu_c;
		   
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
		
		function getChildrenCategory($id) {
			$menu = $this->Page->find('list', array(
			
				'conditions' => array(
					'Page.parent_id' => $id
				)
			
			));			
			
			return $menu;		
		}
		
		function categories($id, $slug = null) {
		
			$this->layout = "mobile/default";
			$this->firstModel = 'Page';

			if ($id == 84) $this->redirect('/mobile/impianti');
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}				
			
			$data = $this->Page->read(null, $id);
			
			$menu = $this->Page->find('list', array(
			
				'conditions' => array(
					'Page.parent_id' => $id
				)
			
			));			
			
			$this->set('data', $data);
			$this->set('menu', $menu);
		
		}
		
		function news($id, $slug = null) {
		
			$this->layout = "mobile/default";
			
			$this->firstModel = 'Page';
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
			
			$data = $this->Page->read(null, $id);			
			
			$menu = $this->Block->find('all', array(
			
				'conditions' => array(
					'Block.page_id' => $id,
					'Block.published <= NOW()'
				),
				'order' => array('Block.published' => 'DESC')
			
			));			
			
			$this->set('page', $data);
			$this->set('data', $data);			
			$this->set('parent', $this->Page->read(null, $data['Page']['parent_id']));
			$this->set('menu', $menu);					
		
		}		
		
		function campionati($id_campionato = null) {
		
			$this->layout = "mobile/default";
			
			$this->title  = "Campionati";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}			
			
			$campionati = $this->Campionati->find('all',
			
				array(
				
					'conditions' => array(
				
							//'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)' originale
							
							'Campionati.AnnoSportivo = (SELECT MAX(C2.AnnoSportivo) FROM Campionati AS C2)',
							'Campionati.InCorso' => 'Si',
							'Campionati.group_id' => 1,
					
					),
					'order' => array('Campionati.Nome ASC'),
				
				)
			
			);

			$this->set('campionati',$campionati);			
			$this->set('id_campionato', $id_campionato);							
		
		}

		function getGironiFromCampionato($campionato_id) {
			
			$gironi = $this->Half->find('all',array('conditions' => array('Half.Campionato' => $campionato_id),'order' => 'Half.Descrizione ASC'));
			
			$halfs = array();
			
			foreach ($gironi as $k => $girone) {
				
				$half['id'] = $girone['Half']['GironeCampionato'];
				$half['value'] = $girone['Half']['Descrizione'];

				$halfs[] = $half;
				
			}
			
			die(json_encode(array('halfs' => $halfs)));
			
		}
		
			function filterCalendar($champ_id,$half_id) {
				
				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Campionati.Italiana'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));
				
				$giornate = $this->getUniqueGiornate($giornate);
				
				$partite = $this->Match->find('all',array(
				
					'fields' => array(
						'Match.Calendario',
						'Match.CasaNome',
						'Match.TrasfertaNome',
						'Match.Risultato',
						'Causalresult.Descrizione',
						'Match.Giornata',
						'Match.Data_it',
						'Match.Data',
						'Match.Ora',
						'Campi.Descrizione',
						'Match.Casa',
						'Match.Trasferta',
						'Match.NomeGara',
						'Campi.Campo',
						'Campi.isMidland',
						'Casa.Squadra',
						'Trasferta.Squadra',
						'Match.NomeArbitro',
						'Match.NomeDelegato',
						'Lda.Arbitro',
						'Lda.Delegato',
						'Campionati.Italiana',
				
					),
					
				
					'conditions' => array(
					
						array('Match.Campionato' => $champ_id,'Match.GironeCampionato' => $half_id)
					
					),
					'order' => array('Match.Giornata ASC','Match.Data ASC')
				
				));
				
				$ps = array();
				
				foreach ($partite as $partita) {
					
					$ps[$partita['Match']['Giornata']][] = $partita;
					
				}
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						array('Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Sunday')), date("Y-m-d",strtotime('next Saturday')))),					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => array('DISTINCT Match.Giornata','Match.Data'),
					'order' => array('Match.Giornata ASC')
					)
				);
				
				Configure::Write('debug',2);
				//debug($prossima_giornata);				
				
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				else
				//$nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] : 0;				
				$nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? 1 : 0;

				$riposi = array();
				$comunicazioni = array();
				
				$avversari = $this->SquadreCampionati->find('all',array(
				
				
					'conditions' => array(
					
						'SquadreCampionati.GironeCampionato' => $half_id,
					
					)
				
				));
				
				Configure::write('debug',2);
				
				foreach ($giornate as $giornata) {

						$comunicazione = $this->Comunication->find('first',array(
						
							'conditions' =>
								
								array(
									'Comunication.GironeCampionato' => $half_id,
									'Comunication.Giornata' =>  $giornata['Match']['Giornata']
								)
						
						));

						$riposo = $this->SquadreCampionati->query("
						
							SELECT (SELECT Squadre.Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra) as NomeSquadra, SquadreCampionati.SquadraCampionato as idSquadra
									
									FROM SquadreCampionati WHERE
									
									SquadreCampionati.Campionato = '$champ_id' AND
									SquadreCampionati.GironeCampionato = '$half_id' AND
									SquadreCampionati.SquadraCampionato NOT IN
									
									(
									
									 SELECT Casa as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id' AND Calendari.Giornata = {$giornata['Match']['Giornata']}
									 
									  UNION
									  
									 SELECT Trasferta as SquadraTest FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id' AND Calendari.Giornata = {$giornata['Match']['Giornata']}
									
									)
						
						");
						
						//debug($riposo);
						
					
					$riposi[$giornata['Match']['Giornata']] = $riposo; 
					$comunicazioni[$giornata['Match']['Giornata']] = $comunicazione;
					
				}
			
				
				$this->set('nextDay',$nextDay);
				$this->set('partite',$ps);
				$this->set('giornate',$giornate);
				$this->set('riposi',$riposi);
				$this->set('comunicazioni',$comunicazioni);
				$this->set('avversari',$avversari);
				
			}		
			
			function filterRankings($champ_id,$half_id, $squadra_id = 0, $giornata = 0) {
			
				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Campionati.Italiana','Match.Data'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));
				
				$giornate = $this->getUniqueGiornate($giornate);
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						'Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday'))),
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => 'DISTINCT Match.Giornata',
					'order' => array('Match.Giornata DESC')
					)
				);
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				//else $nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] + 1 : 0;
				else $nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? 2 : 0;			
				
				$infoGiornate = $this->getGiornataInCorso($giornate);
				
				$giornata_riferimento     = $infoGiornate['giornata_riferimento'];
				$giornata_riferimento_set = $infoGiornate['giornata_riferimento_set'];
				
				$nextDay 	  = $giornata_riferimento;
				$nextDay_real = $giornata_riferimento;
				
				// Generazione classifica //
				
				$campionato = $champ_id;
				$girone 	= $half_id;
				$arr_class  = array();
				
				$this->set('campionato', $champ_id);
				$this->set('girone', $half_id);

				$squadre = $this->SquadreCampionati->find('all', array(
				
					'conditions' => 
						array(
								'Campionati.Campionato' =>  $campionato,
								'Half.GironeCampionato' => $girone
							  )
					)
				);
				
				
				//$this->set('nextDay_real',$nextDay);
				
				//debug("Giornata set: " . $giornata_riferimento_set);
				
				if($giornata != 0) {
					
					$nextDay = $giornata;
					
					if($giornata_riferimento_set == 1)
						{
							$nextDay_real--;
						}					
					
				} else {
					
					if($giornata_riferimento_set == 1)
						{
							$nextDay--;	
							$nextDay_real--;
						}
						
					
				}
				
				$this->set('nextDay_real',$nextDay_real);
				
				foreach($giornate as $k => $gg) {
				
					$giornata = $gg['Match']['Giornata'];
					
					$partite = array();
					
					$classifiche = array();
		
					/*if($giornata == ($nextDay_real) && $giornata != count($giornate))
						unset($giornate[$k]);*/
						
					if($giornata != $nextDay) continue;
					
					foreach ($squadre as $squadra) {
						
						$classifica = array();
						
						$id_classifica = $this->Ranking->find('first', array(
						
							'fields' => array('Ranking.Classifica','Ranking.PuntiPenalizzazione'),
							'conditions' => 
								array(
								'Ranking.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
								'Ranking.GironeCampionato' => $girone
								)
							)
							
						);
						
						if (!empty($id_classifica)) {
							$classifica['Classifica'] = $id_classifica['Ranking']['Classifica'];
						} else {
							$classifica['Classifica'] = null;
						}
						
						$classifica['SquadraCampionato'] = $squadra['SquadreCampionati']['SquadraCampionato'];
						
						$info['InfoSquadra'] = $this->SquadreCampionati->find('first',array(
							
							'conditions' => array('SquadraCampionato' => $classifica['SquadraCampionato']),
							'fields' => array(
								'SquadreCampionati.SquadraCampionato',
								'SquadreCampionati.Squadra',
								'SquadreCampionati.Campionato',
								'SquadreCampionati.GironeCampionato',
								'Squadre.Squadra',
								'Squadre.Denominazione',
								'SquadreCampionati.SquadraCampionato',
								
							),
							
						));
						
						$classifica['SquadraId']   		= $info['InfoSquadra']['Squadre']['Squadra'];
						$classifica['SquadraNome'] 		= $info['InfoSquadra']['Squadre']['Denominazione'];
						$classifica['SquadraCampionato']= $info['InfoSquadra']['SquadreCampionati']['SquadraCampionato'];						
						$classifica['GironeCampionato'] = $girone;
						$classifica['Giocate'] = 0;
						$classifica['Punti'] = 0;
						$classifica['Vinte'] = 0;
						$classifica['Perse'] = 0;
						$classifica['Nulle'] = 0;
						$classifica['GiocateCasa'] = 0;
						$classifica['VinteCasa'] = 0;
						$classifica['PerseCasa'] = 0;
						$classifica['NulleCasa'] = 0;
						$classifica['GiocateFuori'] = 0;
						$classifica['VinteFuori'] = 0;
						$classifica['PerseFuori'] = 0;
						$classifica['NulleFuori'] = 0;
						$classifica['GoalFatti'] = 0;
						$classifica['GoalSubiti'] = 0;
						$classifica['GoalSubitiFuori'] = 0;
						$classifica['GoalSubitiCasa'] = 0;
						$classifica['GoalFattiFuori'] = 0;
						$classifica['GoalFattiCasa'] = 0;
						$classifica['CoppaDisciplina'] = 0;
						
						$partite = $this->Match->find('all',array(
							
							'fields' => array(
							
								'Causalresult.Descrizione',
								'Causalresult.Sanzione',
								'Causalresult.PuntiDisciplina',
								'Causalresult.CausaleRisultato',
								'Match.Casa',
								'Match.Trasferta',
								'Match.Calendario',
							
							),
							'conditions' => 
								array(
							
								'OR' => 
										array('Match.Casa' => $squadra['SquadreCampionati']['SquadraCampionato'],
											  'Match.Trasferta' => $squadra['SquadreCampionati']['SquadraCampionato']
										),
								
								'Match.Campionato' => $campionato,
								'Match.GironeCampionato' => $girone,
								'Match.Giornata <=' => $giornata,
								)
						
						));
					
						foreach ($partite as $partita) {
							
							$casa_fuori = 'Fuori';
							$fuori_casa = 'Casa';
							$risultato['Casa'] = 0;
							$risultato['Fuori'] = 0;
							
							if ($partita['Match']['Casa'] == $squadra['SquadreCampionati']['SquadraCampionato']) $casa_fuori = 'Casa';
					
							$disciplinari = $this->Disciplinari->find('all',array(
								
								'conditions' => array(
								
									'SquadreCampionati.SquadraCampionato' => $squadra['SquadreCampionati']['SquadraCampionato'],
									'Disciplinari.Calendario' => $partita['Match']['Calendario']
								
								)
							
							));
							
							//pr ($partita['Causalresult']);
							
							foreach ($disciplinari as $disciplinare) {
								
								$classifica['CoppaDisciplina'] += $disciplinare['Disciplinari']['Punti'];
								
							}
					
							
					
							if ($partita['Causalresult']['Descrizione'] != 'Recupero' && substr($partita['Causalresult']['Descrizione'],0,strlen('N.D.')) != 'N.D.' && $partita['Causalresult']['Descrizione'] != 'In attesa decisioni G.S.' && $partita['Causalresult']['Descrizione'] != 'Gara non omologabile.' && $partita['Causalresult']['Descrizione'] != 'Risultato non omologabile' && $partita['Causalresult']['Descrizione'] != 'RINV.') {
								
								$classifica['Giocate']++;
								$classifica['Giocate' . $casa_fuori]++;
							
								$goals = $this->Matchgoal->find('all',array(
								
									'conditions' => 
									
											array(
												
												'Matchgoal.Calendario' => $partita['Match']['Calendario'],
												
											)
								));
					
								foreach ($goals as $goal) {
									
									if ($casa_fuori == 'Casa')  $fuori_casa = 'Fuori';
									else 						$fuori_casa = 'Casa';
									
									if ($squadra['SquadreCampionati']['SquadraCampionato'] == $goal['Matchgoal']['SquadraCampionato']) {
										
											$classifica['GoalFatti'] += $goal['Matchgoal']['Goal'];
											$classifica['GoalSubiti'] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
											$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
											
											$risultato[$casa_fuori] += $goal['Matchgoal']['Goal'];
											$risultato[$fuori_casa] += $goal['Matchgoal']['Autogoal'];
											
											if ($goal['Matchgoal']['Ammonizione'] == 'Si') $classifica['CoppaDisciplina']++;
											if ($goal['Matchgoal']['Espulsione']  == 'Si')  $classifica['CoppaDisciplina']+=3;
											
											
									} else {
										
											$classifica['GoalFatti'] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalSubiti'] += $goal['Matchgoal']['Goal'];
											$classifica['GoalFatti' . $casa_fuori] += $goal['Matchgoal']['Autogoal'];
											$classifica['GoalSubiti' . $casa_fuori] += $goal['Matchgoal']['Goal'];
									
											$risultato[$fuori_casa] += $goal['Matchgoal']['Goal'];
											$risultato[$casa_fuori] += $goal['Matchgoal']['Autogoal'];
									}
					
								
								}
								
								if ($risultato[$casa_fuori] == $risultato[$fuori_casa]) {
										
										$classifica['Nulle']++;
										$classifica['Nulle' . $casa_fuori]++;
										$classifica['Punti']++;
										
								}
								
								if ($risultato[$casa_fuori] > $risultato[$fuori_casa]) {
									
										$classifica['Punti'] += 3;
										$classifica['Vinte' . $casa_fuori]++;
										$classifica['Vinte']++;
									
								}
								
								if ($risultato[$casa_fuori] < $risultato[$fuori_casa]) {
									
										$classifica['Perse' . $casa_fuori]++;
										$classifica['Perse']++;
									
										if (substr($partita['Causalresult']['Descrizione'],0,strlen('TAV')) == 'TAV') {
											
											$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
											
										} 
									
								}								
					
							} else {
									
									$classifica['CoppaDisciplina'] += $partita['Causalresult']['PuntiDisciplina'];
								
							}
						
						}
						
						// Tolgo penalizzazione
						
						$classifica['Punti'] = $classifica['Punti'] - (isset($id_classifica['Ranking']['PuntiPenalizzazione'])? $id_classifica['Ranking']['PuntiPenalizzazione']:0);						
						
						$classifiche[] = $classifica;
																				
					}
					
					$arr_class[$giornata] = $classifiche;

				}		

				$this->set('giornate', $giornate);
				$this->set('arr_class', $arr_class);
				$this->set('nextDay',$nextDay);
				
			}		
			
			function filterMarks($champ_id,$half_id) {

				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Campionati.Nome','Half.Descrizione'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));	
				
				$giornate = $this->getUniqueGiornate($giornate);
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						'Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday'))),
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => 'DISTINCT Match.Giornata',
					'order' => array('Match.Giornata DESC')
					)
				);
				
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				else $nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] : 0;
								
				
				//Calcolo marcatori per ogni giornata

				$marcatori = array();
				
				foreach($giornate as $gg) {
				
					$giornata = $gg['Match']['Giornata'];
					
					$marcatori[$giornata] = $this->Matchgoal->query(
				
						"SELECT sc.SquadraCampionato as IdSquadra, s.Denominazione as NomeSquadra, CONCAT(a.Cognome,' ',a.Nome) as anagrafica, SUM(g.Goal) as goals
						FROM Calendari c, GoalPartite g
						LEFT JOIN SquadreCampionati sc ON (sc.`SquadraCampionato` = g.`SquadraCampionato`)
						LEFT JOIN Squadre s ON (sc.`Squadra` = s.`Squadra`)
						LEFT JOIN Atleti a ON (a.`Atleta` = g.`Atleta`)
						WHERE g.Calendario = c.Calendario 
						AND c.Campionato = '$champ_id'
						AND c.GironeCampionato = '$half_id'
						AND g.Goal > 0 
						AND g.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND g.Atleta != 0
						GROUP BY g.Atleta ORDER BY goals DESC"
						
					);	
					
					/*
					
					$marcatori[$giornata] = $this->Matchgoal->query(
				
						"SELECT 
						(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						SUM(GoalPartite.Goal) as goals FROM GoalPartite 
						WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id') 
						AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata') AND GoalPartite.Atleta != 0
						GROUP BY GoalPartite.Atleta ORDER BY goals DESC LIMIT 15"
						
					);					
					
					*/

				}
				
				$this->set('nextDay',$nextDay);
				$this->set('giornate',$giornate);
				$this->set('marcatori', $marcatori);			
			
			}	
			
			function filterDiscipline($champ_id,$half_id) {
				
				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Match.Data','Campionati.Nome','Half.Descrizione'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));	
				
				$giornate = $this->getUniqueGiornate($giornate);				
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						'Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday'))),
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => 'DISTINCT Match.Giornata',
					'order' => array('Match.Giornata DESC')
					)
				);
				
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				$nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] : 0;
				
				/*  HACK GIORNATA DI RIFERIMENTO */
				$infoGiornate = $this->getGiornataInCorso($giornate);
				
				$giornata_riferimento     = $infoGiornate['giornata_riferimento'];
				$giornata_riferimento_set = $infoGiornate['giornata_riferimento_set'];
				
				$nextDay 	  = $giornata_riferimento;
				$nextDay_real = $giornata_riferimento;
				/* END HACK GIORNATA DI RIFERIMENTO */
								
				if($giornata_riferimento_set == 1)
					$nextDay--;
				
				$disciplinari = $this->Disciplinari->find('all',
					array('conditions' => array(
						'Disciplinari.Calendario IN (
							SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = ' . $nextDay . ' AND Calendari.GironeCampionato = ' . $half_id . '
						 )'
					))
				);
				
				//debug($disciplinari);					
								
				$this->set('nextDay',$nextDay);
				$this->set('disciplinari',$disciplinari);				
								
			
			}
			
			function filterComunication($champ_id, $half_id, $squadra_id) {
				
				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Campionati.Nome','Half.Descrizione'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));	
				
				$giornate = $this->getUniqueGiornate($giornate);				
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						'Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday'))),
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => 'DISTINCT Match.Giornata',
					'order' => array('Match.Giornata DESC')
					)
				);
				
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				else 
				$nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] : 0;	
				
				$prevDay = $nextDay - 1;
				
				$comunications = $this->Comunication->find('all', array(
				
					'conditions' => array(
					
						'Comunication.Giornata' => $prevDay,
						'Comunication.GironeCampionato' => $half_id
					
					),
				
				));				
				
				$this->set('nextDay', $prevDay);
				$this->set('comunications', $comunications);
				
			}
			
			function filterDiffidati($champ_id,$half_id,$squadra_id) {
			
				$giornate = $this->Match->find('all',array(
				
					'fields' => array('DISTINCT Match.Giornata','Campionati.Nome','Half.Descrizione'),
					'conditions' => array(
					
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					
					),
					'order' => 'Match.Giornata ASC'
				
				));	
				
				$giornate = $this->getUniqueGiornate($giornate);
				
				$prossima_giornata = $this->Match->find('first',
		
					array(
					
					'conditions' => array(
					
						'Match.Data between ? AND ?' => array(date("Y-m-d",strtotime('last Monday')), date("Y-m-d",strtotime('next Saturday'))),
						'Match.Campionato' => $champ_id,
						'Match.GironeCampionato' => $half_id
					),
					'fields' => 'DISTINCT Match.Giornata',
					'order' => array('Match.Giornata DESC')
					)
				);
				
				
				if (isset($prossima_giornata['Match']['Giornata'])) $nextDay = $prossima_giornata['Match']['Giornata'];
				else $nextDay = (isset($giornate[count($giornate)]['Match']['Giornata']))? $giornate[count($giornate)]['Match']['Giornata'] : 0;

				//Controllo se devo aggiornare i dati
				$giorno     = date('w');
				
				$dodici     = strtotime(date("Y-m-d") . " 12:30:00");
				$mezzanotte = strtotime(date("Y-m-d") . " 23:59:59");
				$adesso     = time();
				
				if($giorno == 123456) { // se è sabato //Condizione sempre negata per far in modo che legga solo da file.
				
					if($adesso >= $dodici && $adesso <= $mezzanotte) {
					
						//Calcolo diffidati ed espulsi
						
						$diffidati = array();
						$espulsi   = array();

						foreach($giornate as $gg) {
						
							$giornata = $gg['Match']['Giornata'];
							
							if($giornata > $nextDay) continue;								
							
							$diffidati[$giornata] = $this->Matchgoal->query(
							
								"SELECT 
								(SELECT COUNT(*) FROM GoalPartite as GP WHERE GP.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata' AND Calendari.Campionato = '$champ_id') AND GP.Ammonizione = 'Si' AND GP.Atleta = GoalPartite.Atleta) as AmmonitoOggi,
								(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
								(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data, 
								COUNT(*) as Ammonizioni FROM GoalPartite
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id') 
								AND GoalPartite.Ammonizione = 'Si'
								AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata <= '$giornata')
								GROUP BY GoalPartite.Atleta ORDER BY Ammonizioni DESC"
							
							);
							
							$espulsi[$giornata] = $this->Matchgoal->query(
							
								"SELECT 
								(SELECT SquadreCampionati.SquadraCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato) as IdSquadra,
								(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
								(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica,
								(SELECT Calendari.Data FROM Calendari WHERE Calendari.Calendario = GoalPartite.Calendario) as Data,
								GoalPartite.EspulsioneGiornate,
								GoalPartite.EspulsioneInizio,
								GoalPartite.EspulsioneFine,
								GoalPartite.Espulsione FROM GoalPartite
								WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = '$champ_id' AND Calendari.GironeCampionato = '$half_id') 
								AND GoalPartite.Espulsione = 'Si'
								AND GoalPartite.Calendario = ANY (SELECT Calendari.Calendario FROM Calendari WHERE Calendari.Giornata = '$giornata')
								GROUP BY GoalPartite.Atleta ORDER By NomeSquadra"
							
							);				
							
							
						}
						
						file_put_contents(APP . "/webroot/files/json/diffidati_".$champ_id."_".$half_id.".json", json_encode($diffidati));
						file_put_contents(APP . "/webroot/files/json/espulsi_".$champ_id."_".$half_id.".json", json_encode($espulsi));
					
					} else {
						if(file_exists(APP . "/webroot/files/json/diffidati_".$champ_id."_".$half_id.".json")) {
							$diffidati = json_decode(file_get_contents(APP . "/webroot/files/json/diffidati_".$champ_id."_".$half_id.".json"),1);
						}
						if(file_exists(APP . "/webroot/files/json/espulsi_".$champ_id."_".$half_id.".json")) {
							$espulsi = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi".$champ_id."_".$half_id.".json"),1);
						}						
					}
				
				} else {
				
					if(file_exists(APP . "/webroot/files/json/diffidati_".$champ_id."_".$half_id.".json")) {
						$diffidati = json_decode(file_get_contents(APP . "/webroot/files/json/diffidati_".$champ_id."_".$half_id.".json"),1);
					}
					if(file_exists(APP . "/webroot/files/json/espulsi_".$champ_id."_".$half_id.".json")) {
						$espulsi = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi_".$champ_id."_".$half_id.".json"),1);
					}					
				}
				if(!isset($diffidati)) $diffidati = array();
				if(!isset($espulsi)) $espulsi = array();
				//				
				
				$this->getEspulsiAmmoniti($champ_id, $half_id, $squadra_id);
				
				$this->set('nextDay',$nextDay);
				$this->set('giornate',$giornate);
				$this->set('diffidati',$diffidati);				
				$this->set('espulsi',$espulsi);
			
			}
			
			function getEspulsiAmmoniti($campionato_id, $girone_id, $squadra_id) {
				
				$girone_ = $this->Half->findByGironecampionato($girone_id);
				
				$campionatoPrecedente = $girone_['Campionati']['CampionatoPrecedente'];
				$girone_id = $girone_['Half']['GironeCampionato'];

				/*
				$classifica_espulsi = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Espulsione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
				");
				
				$classifica_ammoniti = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE GironeCampionato = '$girone_id' AND Campionato = '$campionato_id')
					AND GoalPartite.Ammonizione = 'Si'
					GROUP BY GoalPartite.Atleta ORDER By Tot DESC
				");
				
				$disciplinari = array();
				
				foreach($classifica_ammoniti as $ammonito) {
				
					$espulsioni = 0;
				
					foreach($classifica_espulsi as $espulso) {
					
						if($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id']) {
						
							$espulsioni = $espulso[0]['Tot'];
						
						}
					
					}
				
					$disciplinari[] = array(
					
						'Squadra' => $ammonito[0]['NomeSquadra'],
						'Atleta_id' => $ammonito[0]['atleta_id'],
						'Atleta' => $ammonito[0]['anagrafica'],
						'Ammonizioni' => $ammonito[0]['Tot'],
						'Espulsioni' => $espulsioni
					
					);
				
				}
				
				$this->set('disciplinari', $disciplinari);
				
				*/
				
				$disciplinari_campionato = array();
				
				$classifica_espulsi_campionato = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
					AND GoalPartite.Espulsione = 'Si'
					AND GoalPartite.SquadraCampionato = '$squadra_id'
					GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
				");	
				$classifica_ammoniti_campionato = $this->Ranking->query("
					SELECT 
					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
					COUNT(*) as Tot
					FROM GoalPartite
					WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionato_id')
					AND GoalPartite.Ammonizione = 'Si'
					AND GoalPartite.SquadraCampionato = '$squadra_id'
					GROUP BY GoalPartite.Atleta ORDER By Tot DESC
				");	
				
				if(!empty($campionatoPrecedente)) {
				
					$classifica_espulsi_campionato_precedente = $this->Ranking->query("
						SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
						COUNT(*) as Tot
						FROM GoalPartite
						WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
						AND GoalPartite.Espulsione = 'Si'
						GROUP BY GoalPartite.Atleta ORDER BY Tot DESC
					");	
					$classifica_ammoniti_campionato_precedente = $this->Ranking->query("
						SELECT 
						(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as NomeSquadra,
						(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
						(SELECT Atleta FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as atleta_id, 
						COUNT(*) as Tot
						FROM GoalPartite
						WHERE GoalPartite.SquadraCampionato IN (SELECT SquadraCampionato FROM SquadreCampionati WHERE Campionato = '$campionatoPrecedente')
						AND GoalPartite.Ammonizione = 'Si'
						GROUP BY GoalPartite.Atleta ORDER By Tot DESC
					");	
					
					$classifica_ammoniti_campionato = array_merge($classifica_ammoniti_campionato, $classifica_ammoniti_campionato_precedente);
					$classifica_espulsi_campionato  = array_merge($classifica_espulsi_campionato , $classifica_ammoniti_campionato_precedente);
				
				}
				
				foreach($classifica_ammoniti_campionato as $ammonito) {
				
					$espulsioni = 0;
				
					foreach($classifica_espulsi_campionato as $espulso) {
					
						if($espulso[0]['atleta_id'] == $ammonito[0]['atleta_id']) {
						
							$espulsioni = $espulso[0]['Tot'];
						
						}
					
					}
				
					$disciplinari_campionato[] = array(
					
						'Squadra' => $ammonito[0]['NomeSquadra'],
						'Atleta_id' => $ammonito[0]['atleta_id'],
						'Atleta' => $ammonito[0]['anagrafica'],
						'Ammonizioni' => $ammonito[0]['Tot'],
						'Espulsioni' => $espulsioni
					
					);
				
				}	

				$this->set('disciplinari_campionato', $disciplinari_campionato);
			
			}			
			
			function filterDisciplinari($champ_id, $half_id, $squadra_id) {
				
				$disciplinari = json_decode(file_get_contents(APP . "/webroot/files/json/disciplinari/disciplinare_".$champ_id."_".$half_id.".json"),1);	
				
				//$this->set('nextDay', $nextDay);
				$this->set('disciplinari', $disciplinari);				
				
			}															

			function getUniqueGiornate($giornate) {

				$giornate_arr = array();
				
				foreach($giornate as $giornata) {
					$giornate_arr[$giornata['Match']['Giornata']] = $giornata;
				}
				
				unset($giornate);
				$giornate = $giornate_arr;		
				
				return $giornate;				
				
			}
			
			function getGiornataInCorso($giornate) {
				
					//Configure::write('debug',2);
				
					$giornata_riferimento     = count($giornate);
					$giornata_riferimento_set = 0;
					
					//debug($giornate);
				
					foreach($giornate as $gg) {
						
						$gg = $gg['Match'];
						
						$match_data    = strtotime($gg['Data']);
						$last_saturday = strtotime(date("Y-m-d 12:30:00",strtotime('last Saturday')));
						$now           = strtotime(date("Y-m-d H:i:s"));
						$nowTime       = date("Y-m-d H:i:s");
						$matchTime     = $gg['Data'];
						$lastTime      = date("Y-m-d 12:30:00",strtotime('last Saturday'));
						
						//Check next saturday
						if(date('w', strtotime(date("Y-m-d"))) == 6) {
							
							$data = strtotime(date("Y-m-d 12:30:00"));
							$dateTime = date("Y-m-d 12:30:00");
							
						}
						else {
							
							$data = strtotime(date("Y-m-d 12:30:00",strtotime('next Saturday')));
							$dateTime = date("Y-m-d 12:30:00",strtotime('next Saturday'));
							
						}
						
						//debug($dateTime);
						
						/*
						debug("Data di riferimento : " . $dateTime);
						debug("Match data: " . $matchTime);
						debug("Now time: " . $nowTime);
						debug("Last saturday time: " . $lastTime);
						*/
						
						if($now >= $data) {
							
							//debug("La data è maggiore del prox sabato");
							
							if($match_data > $data)
							{
								
								$giornata_riferimento     = $gg['Giornata'];
								$giornata_riferimento_set = 1;
								//debug('Sono qua $match_data > $data');
								
							}
							
						} 
						elseif($now < $data) {
							
							//debug("La data è minore del prox sabato");
							
							if($match_data > $last_saturday)
							{
								
								//debug('Sono qua $match_data > $last_saturday');
								$giornata_riferimento     = $gg['Giornata'];
								$giornata_riferimento_set = 1;
								
							}							
							
						}
						
						//debug("Giornata attaule: " . $gg['Giornata']);
						
						if($giornata_riferimento_set == 1)
							break;
						
						
					}
					
					//debug("Giornata di riferimento: " . $giornata_riferimento);
					
					return array(
					
						'giornata_riferimento'     => $giornata_riferimento,
						'giornata_riferimento_set' => $giornata_riferimento_set,
					
					);				
				
			}				

			function getFilter($champ_id,$half_id,$type,$squadra_id = null, $giornata = 0) {
				
				$this->layout = "ajax";
				
				switch ($type) {
					
					case 'calendario':
					
						$this->filterCalendar($champ_id,$half_id);
					
					break;
					
					case 'classifica':
					
						$this->filterRankings($champ_id,$half_id, $squadra_id, $giornata);
						
					break;
					
					case 'marcatori':
					
						$this->filterMarks($champ_id,$half_id);
						
					break;

					case 'diffidati':
					
						$this->filterDisciplinari($champ_id,$half_id,$squadra_id);
						
					break;
					
					case 'espulsi':
					
						$this->filterDisciplinari($champ_id,$half_id, $squadra_id);
						
					break;

					case 'disciplinari':
					
						$this->filterDiscipline($champ_id,$half_id);
						
					break;	

					case 'squalificati':
					
						$this->filterDisciplinari($champ_id,$half_id,$squadra_id);
						
					break;	
					
					case 'squadra':
					
						$this->filterSquadra($champ_id,$half_id,$squadra_id);
					
					break;	
					
					case 'squadra_logged':
					
						$this->filterSquadra($champ_id,$half_id,$squadra_id);
					
					break;								

					case 'calendario_edit':
					
						$this->filterCalendar($champ_id,$half_id,$squadra_id);
					
					break;
					
					case 'calendario_arbitro':
					
						$this->filterCalendar($champ_id,$half_id,$squadra_id);
						
					break;
					
					case 'squadra_annuario':
					
						$this->filterTeambook($champ_id,$half_id,$squadra_id);
					
					break;	
					
					case 'comunicazioni':
					
						$this->filterComunication($champ_id,$half_id,$squadra_id);
					
					break;										
					
				}
				
				$this->render('/elements/getfilter/mobile/' . $type);
				
			}

		function impianti($id_impianto = null) {
		
			$this->title  = "Impianti";		
		
			$this->layout = "mobile/default";
			
				$campi = $this->Campi->find('all', array(
				
					//'conditions' => array('Campi.isMidland' => 1,'(SELECT COUNT(*) FROM CampiOrari WHERE CampiOrari.campo_id = Campi.Campo) > 0'),
					'conditions' => array('Campi.isMidland' => 1),
					'order' => 'Campi.Descrizione ASC',
				
				));
			
			$this->set('campi',$campi);
			$this->set('id_impianto', $id_impianto);
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}			
		
		}
		
	
			/* BOOKING */
/*			
			function booking($campo_id) {
				
				$this->layout = null;
				
				$campo = $this->Campi->findByCampo($campo_id);
				
				$now = strtotime(date("Y-m-d h:i:s"));
				
				$giorni = array();
				
				$dow_query = "(";
				
				for ($i=0;$i<14;$i++) {
					
					$giorno['Data_it'] 		= date("d/m/Y", strtotime("+$i days",$now));
					$giorno['Data'] 		= date("Y-m-d", strtotime("+$i days",$now));
					$giorno['DayOfWeek'] 	= date("N",		strtotime("+$i days",$now));
					
					$giorni[] = $giorno;
					
					$dow_query .= $giorno['DayOfWeek'] . ",";
					
				}
				
				$dow_query = substr($dow_query,0,strlen($dow_query)-1) . ")";
				
				$orari = $this->CampiOrari->find('all',
				
					array('conditions' => 
							
							
							array(
								'CampiOrari.campo_id' => $campo_id,
								'CampiOrari.Giorno IN ' . $dow_query
							),
						  
						  'order' => array('CampiOrari.Ora ASC')
							
					)
				
				);
				
				foreach ($giorni as $i => $giorno) {
					
					$giorno['Orari'] = array();
					
					foreach ($orari as $orario) {
						
						$tmp['Occupato'] = 0;
						$tmp['Info'] = '';
						if ($orario['CampiOrari']['Giorno'] == $giorno['DayOfWeek']) {
							

							$bookings = $this->CampiBooking->find('count',array(
								
								'conditions' =>
								
									array(
									
										  'CampiBooking.Data' => $giorno['Data'],
										  'CampiBooking.Ora'  => $orario['CampiOrari']['Ora'],
										  'CampiBooking.campo_id' => $campo_id
									)
								
							));
							
							if ($bookings > 0) $tmp['Occupato'] = 1;
							
							$matches = $this->Match->find('first',array(
							
							
								'conditions' => 
								
									array(
										'Match.Campo' => $campo_id,
										'DATE_FORMAT(Match.Data,"%Y-%m-%d")'  => $giorno['Data'],
										'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $orario['CampiOrari']['Ora']
									) 
							
							));
							
							if (!empty($matches)) {
								
								$tmp['Occupato'] = 1;
							
								$tmp['Info'] = 
								
								$matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] . 
								"<br />" .
								"Campionato: " . $matches['Campionati']['Nome'] . "<br />"  . 
								"Girone: " . $matches['Half']['Descrizione'] . "<br />";
								
							}
							
							$giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'Importo' => $orario['CampiOrari']['Importo'],'Occupato' => $tmp['Occupato'],'Info' => $tmp['Info']);
						}
							
					}
						
					
					$giorni[$i] = $giorno;
						
				}

				// $this->set('giorni',$giorni);
				// $this->set('campo',$campo);
				$data = array(
				
					'giorni' => $giorni,
					'campo'  => $campo,
				
				);
				
				return $data;
				
			}

*/

			function booking($campo_id) {
				
				$this->layout = null;
				
				$campo = $this->Campi->findByCampo($campo_id);
				
				$now = strtotime(date("Y-m-d h:i:s"));
				
				$giorni = array();
				
				$dow_query = "(";
				
				for ($i=0;$i<14;$i++) {
					
					$giorno['Data_it'] 		= date("d/m/Y", strtotime("+$i days",$now));
					$giorno['Data'] 		= date("Y-m-d", strtotime("+$i days",$now));
					$giorno['DayOfWeek'] 	= date("N",		strtotime("+$i days",$now));
					
					$giorni[] = $giorno;
					
					$dow_query .= $giorno['DayOfWeek'] . ",";
					
				}
				
				$dow_query = substr($dow_query,0,strlen($dow_query)-1) . ")";
				$orari = $this->CampiOrari->find('all',
				
					array('conditions' => 
							
							
							array(
								'CampiOrari.campo_id' => $campo_id,
								'CampiOrari.Giorno IN ' . $dow_query
							),
						  
						  'order' => array('CampiOrari.Ora ASC')
							
					)
				
				);

				
				foreach ($giorni as $i => $giorno) {
					
					$giorno['Orari'] = array();
					
					foreach ($orari as $orario) {
						
						$tmp['Occupato'] = 0;
						$tmp['Info'] = '';
						if ($orario['CampiOrari']['Giorno'] == $giorno['DayOfWeek']) {
							

							
							$bookings = $this->CampiBooking->find('count',array(
								
								'conditions' =>
								
									array(
									
										  'CampiBooking.Data' => $giorno['Data'],
										  'CampiBooking.Ora'  => $orario['CampiOrari']['Ora'],
										  'CampiBooking.campo_id' => $campo_id
									)
								
							));
							
							if ($bookings > 0) $tmp['Occupato'] = 1;
							
							$matches = $this->Match->find('first',array(
							
							
								'conditions' => 
								
									array(
										'Match.Campo' => $campo_id,
										'DATE_FORMAT(Match.Data,"%Y-%m-%d")'  => $giorno['Data'],
										'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $orario['CampiOrari']['Ora']
									) 
							
							));
							
							if (!empty($matches)) {
								
								$tmp['Occupato'] = 1;
							
								$tmp['Info'] = 
								
								$matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] . 
								"<br />" .
								"Campionato: " . $matches['Campionati']['Nome'] . "<br />"  . 
								"Girone: " . $matches['Half']['Descrizione'] . "<br />";
								
							}
							
							$giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'Importo' => $orario['CampiOrari']['Importo'],'Occupato' => $tmp['Occupato'],'Info' => $tmp['Info']);
						}
							
					}
						
					
					$giorni[$i] = $giorno;
						
				}

				// $this->set('giorni',$giorni);
				// $this->set('campo',$campo);
				$data = array(
				
					'giorni' => $giorni,
					'campo'  => $campo,
				
				);
				return $data;
				
			}

		function booking_oldi($campo_id) {
				
				$this->layout = null;
				
				$campo = $this->Campi->findByCampo($campo_id);
				
				$now = strtotime(date("Y-m-d h:i:s"));
				
				$giorni = array();
				
				$dow_query = "(";
				
				for ($i=0;$i<14;$i++) {
					
					$giorno['Data_it'] 		= date("d/m/Y", strtotime("+$i days",$now));
					$giorno['Data'] 		= date("Y-m-d", strtotime("+$i days",$now));
					$giorno['DayOfWeek'] 	= date("N",		strtotime("+$i days",$now));
					
					$giorni[] = $giorno;
					
					$dow_query .= $giorno['DayOfWeek'] . ",";
					
				}
				
				$dow_query = substr($dow_query,0,strlen($dow_query)-1) . ")";
				$orari = $this->CampiOrari->find('all',
				
					array('conditions' => 
							
							
							array(
								'CampiOrari.campo_id' => $campo_id,
								'CampiOrari.Giorno IN ' . $dow_query
							),
						  
						  'order' => array('CampiOrari.Ora ASC')
							
					)
				
				);

				
				foreach ($giorni as $i => $giorno) {
					
					$giorno['Orari'] = array();
					
					foreach ($orari as $orario) {
						
						$tmp['Occupato'] = 0;
						$tmp['Info'] = '';
						if ($orario['CampiOrari']['Giorno'] == $giorno['DayOfWeek']) {
							

							
							$bookings = $this->CampiBooking->find('count',array(
								
								'conditions' =>
								
									array(
									
										  'CampiBooking.Data' => $giorno['Data'],
										  'CampiBooking.Ora'  => $orario['CampiOrari']['Ora'],
										  'CampiBooking.campo_id' => $campo_id
									)
								
							));
							
							if ($bookings > 0) $tmp['Occupato'] = 1;
							
							$matches = $this->Match->find('first',array(
							
							
								'conditions' => 
								
									array(
										'Match.Campo' => $campo_id,
										'DATE_FORMAT(Match.Data,"%Y-%m-%d")'  => $giorno['Data'],
										'CONCAT(REPLACE(Match.Ora,".",":"),":00")' => $orario['CampiOrari']['Ora']
									) 
							
							));
							
							if (!empty($matches)) {
								
								$tmp['Occupato'] = 1;
							
								$tmp['Info'] = 
								
								$matches['Match']['CasaNome'] . " - " . $matches['Match']['TrasfertaNome'] . 
								"<br />" .
								"Campionato: " . $matches['Campionati']['Nome'] . "<br />"  . 
								"Girone: " . $matches['Half']['Descrizione'] . "<br />";
								
							}
							
							$giorno['Orari'][] = array('Ora' => $orario['CampiOrari']['Ora'], 'Importo' => $orario['CampiOrari']['Importo'],'Occupato' => $tmp['Occupato'],'Info' => $tmp['Info']);
						}
							
					}
						
					
					$giorni[$i] = $giorno;
						
				}

				// $this->set('giorni',$giorni);
				// $this->set('campo',$campo);
				$data = array(
				
					'giorni' => $giorni,
					'campo'  => $campo,
				
				);
				
				return $data;
				
			}
			
		
		function getCampo($campo_id) {
		
		
		
				
				if($campo_id == null) {
				
					$campo_id = $this->Campi->find('list', array('fields' => array('Campi.Campo'),'conditions' => array('Campi.disabled' => 0)));
					$detail   = 0;
					
				} else {
					
					$this->set('booking', $this->booking($campo_id));
					$detail   = 1;
				
				}
			
				$data = $this->Campi->find('all', array(
				
					'conditions' => array(
					
						'Campi.Campo'    => $campo_id,
						'Campi.disabled' => 0,
						'Campi.isMidland' => 1,
					
					),
					'order' => 'Campi.Descrizione ASC',
				
				));
				
				//Calcolo citta
				$citta = array();
				foreach($data as $tmp) {
					$string  = ucfirst(ereg_replace("[^a-zA-Z]","",strtolower($tmp['Campi']['Citta'])));
					if(!empty($string)) $citta[$string] = $tmp['Campi']['Citta'];
				}
				$citta = array_unique($citta);
				asort($citta);
				
				//Tutti i campi
				$campis = $this->Campi->find('all', array(
					'conditions' => array(
						'Campi.Campo'    => $this->Campi->find('list', array('fields' => array('Campi.Campo'),'conditions' => array('Campi.disabled' => 0))),
						'Campi.disabled' => 0,
						'Campi.isMidland' => 1,
					),
					'order' => 'Campi.Descrizione ASC',
				));
				
				$this->set('data', $data);
				$this->set('citta', $citta);
				$this->set('detail',$detail);
				$this->set('campis', $campis);
		
				$this->layout = null;
		
		
		}
		
		function view($id_page = null, $slug_page = null, $id_block = null, $slug_block = null) {
		
			$this->layout = "mobile/default";
			
			$this->firstModel = 'Page';			
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
			
			
			
		if (($id_page == 82 || $id_page == 83) && $id_block == null) $this->redirect('/mobile/news/' . $id_page);
			
			if($id_page == null)
				$this->redirect('/mobile');
				
			$data = $this->Page->read(null, $id_page);
			
			if(!$data)
				$this->redirect('/mobile');
			
			$this->set('parent', $this->Page->read(null, $data['Page']['parent_id']));			
			$this->set('data', $data);
			
			if($id_block == null) {
			
				$blocks = $this->Block->find('all', array(
				
					'conditions' => array(
						'Block.page_id'  => $id_page,
						'Block.disabled' => 0
					)
				
				));
				
				$this->set('blocks', $blocks);
				
				if($id_page == Configure::read('albo_oro'))
					{
						
						$albo = $this->getAlboDoro();
						$this->set('albo', $albo);
						$this->render('view_albo_doro');
					
					}
			
				if($id_page == Configure::read('calcio_a5') || $id_page == Configure::read('calcio_a7')) {
			
				$this->set('id_page',$id_page);
				//$this->set('squadre',$this->Squadre->find('all'));
						$this->set('parent', $this->Page->read(null, $id_page));			
		
				if ($id_page == Configure::read('calcio_a5')) {
					$this->set('femminile',1);
					$this->getSquadre(0,0,(isset($_GET['anno'])? $_GET['anno'] : 'all'),'maschile');
					$this->getSquadre(0,1,(isset($_GET['anno'])? $_GET['anno'] : 'all'),'femminile');
					
				} else {
					$this->set('no_femminile',1);				
					$this->getSquadre(1,0,(isset($_GET['anno'])? $_GET['anno'] : 'all'),'maschile');
		
				}
				
				$this->set('anno',(isset($_GET['anno'])? $_GET['anno'] : 'all'));
				$this->set('anni', $this->AnniSportivi->find('list', array('order' => array('AnniSportivi.AnnoSportivo' => 'DESC'))));
				$this->set('parent', $this->Page->read(null, 51));		
				$this->render('view_calcio');
				

				}			
			
			} else {
			
				$this->firstModel = 'Block';
				
				$this->params['pass'][0] = $id_block;
				
				$block = $this->Block->read(null, $id_block);	
			
				if(!$block)
					$this->redirect('/mobile');
					
				$this->set('block', $block);
				$this->render('view_block');
			
			}
			
		}
		
		function getAlboDoro() {
		
				//Anno sportivo corrente
				$anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
				$anno = $anno[key($anno)];				
				
				//Campionati anno sportivo corrente
				$campionati_tmp = $this->Campionati->find('list', array(
				
					'fields' => array(
						'Campionati.Campionato',
						'Campionati.Nome',
						'Campionati.Tipo',
						
					),
					'conditions' => array(
					
						'Campionati.group_id' => 1,	
						'Campionati.SquadraCampionato_id',
						'Campionati.Categoria !=' => 0,			
					
					),
					'order' => array('Campionati.AnnoSportivo' => 'ASC', 'Campionati.Tipo ASC', 'Campionati.SessoTipo ASC', 'Campionati.Nome ASC'),
				
				));
				
				//debug($campionati_tmp);
				
				$campionati = array();
				
				$tipo_arr  = array(0 => 'Calcio a 5', 1 => 'Calcio a 7');
				$sessoTipo = array(0 => 'Maschile', 1 => 'Femminile', 2 => 'Misto');
				
				foreach($campionati_tmp as $tipo => $campionati_tipo_tmp) {
					foreach($campionati_tipo_tmp as $id_campionato => $campionato_tmp) {
						
						$campionato = $this->Campionati->find('first', array(
						
							'fields' => array(
							
								'Campionati.Campionato',
								'Campionati.AnnoSportivo',
								'Campionati.Nome',
								'Campionati.Tipo',
								'Campionati.SessoTipo',
								'Campionati.SquadraCampionato_id',
								'Campionati.Categoria',
							
							),
							'conditions' => array(
								'Campionati.Campionato' => $id_campionato,
							),
							'recursive' => -1,
						
						));
						
						$champCategory = $this->ChampCategory->find('first', array(
						
							'fields' => array(
								'ChampCategory.id',
								'ChampCategory.Nome',
							),
							'conditions' => array(
								'ChampCategory.id' => $campionato['Campionati']['Categoria']
							),
							'recursive' => -1,
						
						));
						
						//debug($campionato['Campionati']['SquadraCampionato_id']);
						
						$squadra = $this->SquadreCampionati->findBySquadracampionato($campionato['Campionati']['SquadraCampionato_id']);
						
						//$campionati[$tipo.'|'.$tipo_arr[$tipo]][$campionato['Campionati']['AnnoSportivo']][$campionato['Campionati']['SessoTipo'].'|'.$sessoTipo[$campionato['Campionati']['SessoTipo']]][] = $campionato;	
						//$campionati[$tipo_arr[$tipo]][$sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][$campionato['Campionati']['AnnoSportivo']][$id_campionato] = $campionato;
						$campionati[$tipo_arr[$tipo] . ' ' . $sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][($campionato['Campionati']['AnnoSportivo']-1) . '/' . $campionato['Campionati']['AnnoSportivo']][] = $squadra['Squadre']['Denominazione'];
					}					
				}
				
				return $campionati;		
		
		}
	
		/*** Area riservata ***/
		
		/*** Login & Recupero password ***/
		function login() {
		
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}				
		
		}
		
		function login_exec() {
		
			$this->autoRender = false;
				
				$username = $this->data['Login']['username'];
				$password = $this->data['Login']['password'];
				
				$auth_password = $this->Auth->password($password);
				
				if (!isset($this->User)) $this->loadModel('User');
				if (!isset($this->Athlete)) $this->loadModel('Athlete');
				
				if(isset($this->data['Login']['type_login']) && $this->data['Login']['type_login'] != '') {
					
					switch ($this->data['Login']['type_login']) {
						
						case 'athlete':
							$is_atleta = $this->Athlete->find('first',array('conditions' => array(
							
								'Athlete.email' => $username,
								'Athlete.password' => $auth_password,
								
							)));								
						break;
						
						case 'arb':
							$is_arbitro = $this->Athlete->find('first',array('conditions' => array(
							
								'Athlete.email' => $username,
								'Athlete.password' => $auth_password,
							
							)));								
						break;
						
					}
					
				} else {
				
					$is_user = $this->User->find('first',array('conditions' => array(
					
						'User.username' => $username,
						'User.password' => $auth_password
					
					)));
		
					$is_atleta = $this->Athlete->find('first',array('conditions' => array(
					
						'Athlete.email' => $username,
						'Athlete.password' => $auth_password,
						'Athlete.Arbitro' => 'No',
						
					)));
					
					$is_arbitro = $this->Athlete->find('first',array('conditions' => array(
					
						'Athlete.email' => $username,
						'Athlete.password' => $auth_password,
						'Athlete.Arbitro' => 'Si',
					
					)));
				
				}					
				
				$this->Session->delete('Message.flash');
				
				if (isset($is_user) && !empty($is_user)) {
					
						
						$data['id'] = $is_user['User']['id'];
						$data['nome'] = $is_user['User']['nome'];
						$data['cognome'] = $is_user['User']['cognome'];
						$data['data_nascita'] = $is_user['User']['data_nascita'];
						$data['email'] = $data['username'] = $is_user['User']['username'];
						$data['is_atleta'] = 0;
						$data['is_user'] = 1;
						$data['is_arbitro'] = 0;
						$this->Session->write('Login.data',$data);
						
						$result = array('error' => 0);
					
				} else if (isset($is_atleta) && !empty($is_atleta)) {
					
						$data['id'] = $is_atleta['Athlete']['Atleta'];
						$data['nome'] = $is_atleta['Athlete']['Nome'];
						$data['cognome'] = $is_atleta['Athlete']['Cognome'];
						$data['data_nascita'] = $is_atleta['Athlete']['DataNascita'];
						$data['email'] = $data['username'] = $is_atleta['Athlete']['Email'];
						$data['is_atleta'] = 1;
						$data['is_user'] = 0;
						$data['is_arbitro'] = 0;
						$this->Session->write('Login.data',$data);
						
						$result = array('error' => 0);
					
				} else if(isset($is_arbitro) && !empty($is_arbitro)) {
					
						$data['id'] = $is_arbitro['Athlete']['Atleta'];
						$data['nome'] = $is_arbitro['Athlete']['Nome'];
						$data['cognome'] = $is_arbitro['Athlete']['Cognome'];
						$data['data_nascita'] = $is_arbitro['Athlete']['DataNascita'];
						$data['email'] = $data['username'] = $is_arbitro['Athlete']['Email'];
						$data['is_atleta'] = 0;
						$data['is_user'] = 0;
						$data['is_arbitro'] = 1;
						$this->Session->write('Login.data',$data);
						
						$result = array('error' => 0);
					
				} else {
					
						$result = array('error' => 1);
					
				}
				
			die(json_encode($result));
		
		}

		function passrecovery($action = "") {
			
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
			
			if (!empty($action)) {
				
				switch ($action) {
					
					case 'user':
					
						$this->layout = "ajax";
					
						$user = $this->User->find('first',
						
								array(
										'conditions' => 
										
											array('LOWER(User.username)' => strtolower($this->data['User']['username']),
												  'LOWER(User.nome)'  => strtolower($this->data['User']['nome']),
												  'LOWER(User.cognome)' => strtolower($this->data['User']['cognome'])
											)
								)
								
						);
						
						$atleta = $this->Athlete->find('first',
						
								array(
										'conditions' => 
										
											array('LOWER(Athlete.Email)' => strtolower($this->data['User']['username']),
												  'LOWER(Athlete.Nome)'  => strtolower($this->data['User']['nome']),
												  'LOWER(Athlete.Cognome)' => strtolower($this->data['User']['cognome'])
											)
								)
								
						);
						
						if (!empty($user) || !empty($atleta)) {
							
								$this->set('ret',json_encode(array('found' => '1')));
					
								$uid = 0;
								
								$newpass = substr(md5(uniqid()),0,8);
								
								if (!empty($user)) {
									
									$uid = $user['User']['id'];
									
									
									$this->User->updateAll(array('User.password' => "\"" . $this->Auth->password($newpass) . "\""),array('User.id' => $uid));
								}
								if (!empty($atleta)) {
									
									$uid = $atleta['Athlete']['Atleta'];
									
									$this->Athlete->updateAll(array('Athlete.password' => "\"" . $this->Auth->password($newpass) . "\""),array('Athlete.id' => $uid));
						
									
								}
								
								$udata = $this->data['User'];
					
					
								$this->set('User',$udata);
								$this->set('newpass',$newpass);
								$this->Email->to = $udata['username'];
								$this->Email->subject = 'Midland Sport | Recupero password';
								$this->Email->template = 'recover_fo'; 
								$this->Email->send();
								
							
							
						} else {
							
							
							$this->set('ret',json_encode(array('found' => '0')));
							
						}
						
						$this->render('/backend/ajax');
						
					break;
					
					case 'athlete':
					
					break;
					
				}
				
			}
			
		}		
		
		/*** Registrazione ***/
		
			function signup() {
			
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	

				$this->title  = "Registrazione";
				
				if (!empty($this->data)) {
				
					$pwd_send = $this->data['User']['password_confirm'];
					$email_to = $this->data['User']['username'];
					
					$group = $this->Group->find('first', array('conditions' => array('Group.nome' => 'Utente')));
				
					$this->data['User']['password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
					$this->data['User']['disabled']         = 1;
					$this->data['User']['group_id']         = $group['Group']['id'];
					$this->User->set($this->data);
					
					if ($this->User->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
										
							$this->set('md5_id', md5($this->User->id));
							$this->set('link',"http://" . $_SERVER['SERVER_NAME']);
							$this->set('anagrafica',$this->data['User']['nome'] . ' ' . $this->data['User']['cognome']);
							$this->set('user',$email_to);
							$this->set('pwd',$pwd_send);
							$this->set('activate_function', '/mobile/activate/');
							$this->Email->to = $email_to;
							$this->Email->subject = 'Midland Sport | Registrazione nuovo utente';
							$this->Email->template = 'user_add_site'; 
							$this->Email->send();
							
							$this->redirect('/mobile/signup_ok');
						
						}
						
					} else {
					
						$this->data['User']['password'] = '';
						$this->data['User']['password_confirm'] = '';
					
					}
					
				}				
			
			}
			
			function signup_athlete($registra = null) {
			
			$this->layout = "mobile/default";
			
			if ($this->RequestHandler->isAjax()) {

				$this->layout = '/mobile/ajax';
				
			}	
			
				$this->title = "Registrazione atleti";
				
				if(!empty($this->data)) {
				
					$this->Athlete->id = $this->data['Athlete']['Atleta'];
					
					$pwd_send = $this->data['Athlete']['password_confirm'];
					$email_to = $this->data['Athlete']['Email'];
					
					//$group = $this->Group->find('first', array('conditions' => array('Group.nome' => 'Utente')));
				
					$this->data['Athlete']['password']         = $this->Auth->password($this->data['Athlete']['password']);
					$this->data['Athlete']['password_confirm'] = $this->Auth->password($this->data['Athlete']['password_confirm']);
					$this->data['Athlete']['disabled']         = 1;
					
					if ($this->Athlete->save($this->data)) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
						
							$this->set('md5_id', md5($this->Athlete->id));
							$this->set('link',"http://" . $_SERVER['SERVER_NAME']);
							$this->set('anagrafica',$this->data['Athlete']['Nome'] . ' ' . $this->data['Athlete']['Cognome']);
							$this->set('user',$email_to);
							$this->set('pwd',$pwd_send);
							$this->set('activate_function', '/mobile/activate_athlete/');
							$this->Email->to = $email_to;
							$this->Email->subject = 'Midland Sport | Registrazione nuovo utente';
							$this->Email->template = 'user_add_site'; 
							$this->Email->send();
							
							$this->redirect('/mobile/signup_ok');
						
						}
						
					} else {
					
						$this->data['Athlete']['password'] = '';
						$this->data['Athlete']['password_confirm'] = '';
					
					}					
				
				}
			
			}
			
			function signup_ok() {
			
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
			
				$this->title  = "Registrazione completata.";
			
			}
			
			function checkTessera() {
				
				$this->layout = "ajax";
				
				//Cerco atleti
				$athletes = $this->Athlete->find('list', array(
					'fields'     => array('Athlete.Atleta'),
					'conditions' => array(
						'Athlete.Nome'     => $this->data['User']['Nome'],
						'Athlete.Cognome'  => $this->data['User']['Cognome'],
						//'Yearbook.Tessera' => $this->data['User']['Tessera'],
					),
				));
				
				if(!empty($athletes)) {
					
					$data = $this->Yearbook->find('all', array(
						'conditions' => array(
							'Yearbook.Tessera' => $this->data['User']['Tessera'],
							'Yearbook.Atleta'  => $athletes,
							//'Yearbook.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
						),
						//'group' => 'Yearbook.Atleta',
					));
					
					$checked = 0;
					
					if(!empty($data)) {
						
						foreach($data as $t) {
							$tmp = $this->Yearbook->find('count', array(
								'conditions' => array(
									'SquadreCampionati.Squadra' => $t['SquadreCampionati']['Squadra'],
									'Yearbook.signup_code'      => $this->data['User']['signup_code'],
								),
							));
							
							if($tmp > 0) {
								$checked = 1;
								break;
							}
						}
						
					}
					
					if($checked == 0) unset($data);
				
				}
				unset($this->data);
				$this->data = (isset($data[0])? $data[0]:array());
				
				//$this->data['Athlete']['password'] = '';
				
				$this->set('data',$this->data);
				$this->render('/mobile/signup_athlete_check');
				
			}
			
			function activate($id = null) {
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->data = $this->User->find('first', array(
				
					'conditions' => array(
					
						'md5(User.id)'  => $id,
						'User.disabled' => 1,
					
					),
				
				));
				
				if(count($this->data) && is_array($this->data)) {
				
					if($this->User->updateAll(array('User.disabled' => 0), array('User.id' => $this->data['User']['id']))) {
					
						$ok = 1;
					
					} else {
					
						$ok = 0;
					
					}
				
				} else {
				
					$ok = 0;
				
				}
				$this->set('ok', $ok);
			
			}
			
			function activate_athlete($id = null) {
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->data = $this->Athlete->find('first', array(
				
					'conditions' => array(
					
						'md5(Athlete.Atleta)'  => $id,
						'Athlete.disabled'     => 1,
					
					),
				
				));
				
				if(count($this->data) && is_array($this->data)) {
				
					if($this->Athlete->updateAll(array('Athlete.disabled' => 0), array('Athlete.Atleta' => $this->data['Athlete']['Atleta']))) {
					
						$ok = 1;
					
					} else {
					
						$ok = 0;
					
					}
				
				} else {
				
					$ok = 0;
				
				}
				$this->set('ok', $ok);
			
			}			
			
			function checkUsername() {
			
				$this->layout = "ajax";
				
				$username = $_POST['username'];
				
				$count = $this->User->find('count', array(
				
					'conditions' => array(
					
						'User.username' => $username
					
					),
					
				));
				
				$this->set('result', json_encode(array('count' => $count)));
				$this->render('/backend/ajaxResult');
			
			}		
			
			/*** AREA RISERVATA ********/
			
			function reserved() {
			
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}				
			
			}
			
			function profilo($id,$model) {
			
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->login_site = true;
				
				if($this->Session->check('Login.data')) {
					
				$data_users = $this->Session->read('Login.data');
				
				if($id != $data_users['id'])
					$this->redirect('/mobile');					
					
				//Se sono atleta prendo il numero della tessera.
				if($model == 'Athlete') {
					
					$tesseramento = $this->Yearbook->find('first', array('fields' => array('Yearbook.Tessera'), 'conditions' => array('Yearbook.Atleta' => $id), 'order' => 'Yearbook.AnnoSportivo DESC'));
					$this->set('tessera', $tesseramento['Yearbook']['Tessera']);
					
				}
				
				if(empty($this->data) || !is_array($this->data)) {

					$data = $this->{$model}->find('first', array(
					
						'conditions' => array(
							$model . '.' . $this->{$model}->primaryKey => $id,
						),
					
					));
					
					$data[$model]['password'] = '';
					
					$this->data = $data;
					
				} else {
					
					//Get user info
					$user = $this->$model->read(null, $id);
					$pwd  = $this->data[$model]['password'];
					$cpwd = $this->data[$model]['password_confirm'];
					//

						if(isset($this->data['Upload']['percorso']['name']) && !empty($this->data['Upload']['percorso']['name'])) { 
							
							if($this->__adminUploadFile(strtolower($model) . '_id',$id)) { $this->redirect('/mobile/profilo/' . $id . '/' . $model); }
							
						} else {
		
						$return = 0;
							
							if(isset($this->data[$model]['password']) && isset($this->data[$model]['password_confirm'])) {
								
								if($model == 'User') { $this->data[$model]['password_confirm'] = $this->Auth->password($this->data[$model]['password_confirm']); }
	
								if($this->data[$model]['password'] != $this->data[$model]['password_confirm']) { 
									$this->$model->invalidate('password_confirm','Password di conferma errata'); $return = 1; 
								}
		
							}	
							
							if(!$return) {
								
								if($model == 'User') {
								
									if(!empty($this->data[$model]['password_confirm'])) {
										
										$pwd = $cpwd;
								
										$this->data[$model]['password']         = $this->Auth->password($pwd);
										$this->data[$model]['password_confirm'] = $this->Auth->password($cpwd);
									
									}
									
								} else {
								
									if(!empty($this->data[$model]['password']) && !empty($this->data[$model]['password_confirm'])) {
								
									$this->data[$model]['password']         = $this->Auth->password($this->data[$model]['password']);				
									$this->data[$model]['password_confirm'] = $this->Auth->password($this->data[$model]['password_confirm']);
									
									}
										
								}
								
								if($pwd == '' && $cpwd == '') { $this->data[$model]['password'] = $user[$model]['password']; $pwd = '1234567'; $this->data[$model]['password_confirm'] = $user[$model]['password']; }
							
								if(strlen($pwd) < 5 || strlen($pwd) > 12) {
									
									$this->$model->invalidate('password_confirm','Lunghezza min: 6, Lunghezza max: 12'); 
									$this->$model->invalidate('password','Lunghezza min: 6, Lunghezza max: 12');
									
									switch($model) {
									
										case 'Athlete':
											if($this->Session->read('Login.data.is_arbitro')) $element = 'arbitro';
											else											  $element = 'athlete';
										break;
										
										case 'User':
											$element = 'user';
										break;
									
									}				
									
									$this->set('element',$element);										
									
									return false;
									
								}	 
							
								$this->$model->set($this->data);
								
								if($this->$model->save()) {
									
									if($model == "Athlete")
										$tipo_utente = "Atleta";
									else
										$tipo_utente = "Utente";
									
									$this->Session->setFlash("Informazioni personali modificate con successo");
									$this->redirect('/mobile/profilo/' . $id . '/' . $model);
								
								} else {
								
									$this->data[$model]['password']         = '';
									$this->data[$model]['password_confirm'] = '';
								
								}								
								
							}					
							
						}
				
				}
				
				switch($model) {
				
					case 'Athlete':
						if($this->Session->read('Login.data.is_arbitro')) $element = 'arbitro';
						else											  $element = 'athlete';
					break;
					
					case 'User':
						$element = 'user';
					break;
				
				}				
				
				$this->set('element',$element);	

				} else {
				
					$this->redirect('/');
				
				}
			
			}
				
				
			/* GESTIONE PROFILO ARBITRO */
			
			function vote() {
				
				Configure::Write('debug', 0);
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->login_site = true;
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					//$file = APP . 'webroot/files/json/vote/vote_lda_'.$this->Session->read('Login.data.id').'_'.date('d_m_Y').'.json';
					
					//$sfide_mensili = json_decode(file_get_contents($file));
					
					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					$matches = $this->Match->find('all',
					
						array(
						
							'conditions' => array(
							
								'OR' => array(
								
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
									
									
								),
								'AND' => array(
								
									'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
								
								),
							
							),
							'order' => array('Match.Data ASC'),
							'fields' => array(
								'Match.Calendario',
								'Match.CasaNome',
								'Match.TrasfertaNome',
								'Match.Risultato',
								'Causalresult.Descrizione',
								'Match.Giornata',
								'Match.Data_it',
								'Match.Data',
								'Match.Ora',
								'Match.CountArbitro',
								'Match.CountArbitro2',
								'Campi.Descrizione',
								'Match.Casa',
								'Match.Trasferta',
								'Match.NomeGara',
								'Campi.Campo',
								'Campi.isMidland',
								'Casa.Squadra',
								'Trasferta.Squadra',
								'Match.NomeArbitro',
								'Match.NomeDelegato',
								'Match.NomeDelegatoA',
								'Match.NomeArbitro2',
								'Lda.Arbitro',
								'Lda.Delegato',
								'Lda.DelegatoA',
								'Lda.Arbitro2',
								'Campionati.Italiana',
								'Campi.Descrizione',
								'Campi.latitudine',
								'Campi.longitudine',
								'Campi.Indirizzo',
								'Campi.Citta',
								'Campi.Provincia',
								'Campi.Telefono',
								'Campi.Email',
						
							),
						
						)
					
					);		
					
					//debug($matches);
					
					$sfide_mensili = array();	
					
					foreach($matches as $match) {
						
						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);
						
						$sfide_mensili[$mese][] = $match;	
						
					}						
					
					$this->set('sfide_mensili', $sfide_mensili);
					
				}
				
			}
			
			//Voto atleti
			function vota() {
				
				Configure::Write('debug',2);
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->login_site = true;
				
				if($this->Session->read('Login.data.is_atleta')) {
					
					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					
					$data_yearbooks = $this->Yearbook->find('list', array(
					
						'fields' => array('Yearbook.SquadraCampionato'),
						'conditions' => array(
							'Yearbook.Atleta' => $this->Session->read('Login.data.id'),
							'Yearbook.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo']
						),
					
					));
					
					$data_yearbooks = array_merge($data_yearbooks);
					$data_yearbooks = array_unique($data_yearbooks);
					
					$matches = $this->Match->find('all',
					
						array(
						
							'conditions' => array(
							
								'OR' => array(
								
									'Match.Casa' 	  => $data_yearbooks,
									'Match.Trasferta' => $data_yearbooks,
									
									
								),
								'AND' => array(
								
									'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
								
								),
							
							),
							'order' => array('Match.Data ASC'),
							'fields' => array(
								'Match.Calendario',
								'Match.CasaNome',
								'Match.TrasfertaNome',
								'Match.Risultato',
								'Causalresult.Descrizione',
								'Match.Giornata',
								'Match.Data_it',
								'Match.Data',
								'Match.Ora',
								'Campi.Descrizione',
								'Match.Casa',
								'Match.Trasferta',
								'Match.NomeGara',
								'Campi.Campo',
								'Campi.isMidland',
								'Casa.Squadra',
								'Trasferta.Squadra',
								'Match.NomeArbitro',
								'Match.NomeDelegato',
								'Match.NomeDelegatoA',
								'Lda.Arbitro',
								'Lda.Delegato',
								'Lda.DelegatoA',
								'Campionati.Italiana',
								'Campi.Descrizione',
								'Campi.latitudine',
								'Campi.longitudine',
								'Campi.Indirizzo',
								'Campi.Citta',
								'Campi.Provincia',
								'Campi.Telefono',
								'Campi.Email',
						
							),
						
						)
					
					);		
					
					$sfide_mensili = array();	
					
					foreach($matches as $match) {
						
						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);
						
						$sfide_mensili[$mese][] = $match;	
						
					}						
					
					$this->set('sfide_mensili', $sfide_mensili);
					
				}
				
			}			
			
			function lda_walls() {

				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
			
				$this->login_site = true;
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					$this->set('messages',$this->getMessage());
					
				}
			
			}
			
			function getMessage() {
				
				return $this->LdaWall->find('all', array(
				
					'conditions' => array(
						'LdaWall.disabled' => 0,
						'LdaWall.published <= NOW()',
					),	
					'order' => array('LdaWall.published DESC')
				
				));
				
			}				
			
			function buste($page = 1) {
				
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->login_site = true;
				
				//Configure::Write('debug',2);
				
				if($this->Session->read('Login.data.is_arbitro')) {
					
					$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
					
					//Cerco gare effettuate dall'atleta
					$matches = $this->Match->find('all',
					
						array(
						
							'conditions' => array(
							
								'OR' => array(
								
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
									
									
								),
								'AND' => array(
								
									'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
								
								),
							
							),
							'order' => array('Match.Data DESC'),
							'fields' => array(
							
								'Match.Calendario',
								'Match.Data',
								'Lda.Arbitro',
								'Lda.Arbitro2',
								'Lda.Delegato',
								'Lda.DelegatoA',
								'Campionati.TariffaArbitro',
								'Campionati.TariffaDelegato',
								'Campionati.TariffaDelegatoA',
								'Campionati.TariffaArbitro2',
								'Match.Risultato',
							
							),
						
						)
					
					);
					
					$riepilogo = array();
					
					foreach($matches as $match) {
						
						$datetime = strtotime($match['Match']['Data']);
						$mese = date("m", $datetime);
						$anno = date("Y", $datetime);
						
						$votes = $this->LdaVote->find('all', array(
						
							'conditions' => array(
								'match_id' => $match['Match']['Calendario'],
								'athlete_lda_id' => $this->Session->read('Login.data.id'),
							),
						
						));
						
						$votes_send = $this->LdaVote->find('all', array(
						
							'conditions' => array(
								'match_id' => $match['Match']['Calendario'],
								'athlete_id' => $this->Session->read('Login.data.id'),
							),
						
						));						
						
						$match['Match']['AnnoPartita'] = $anno;
						$match['LdaVote'] = $votes;
						$match['LdaVoteSend'] = $votes_send;
						
						$riepilogo[$mese][] = $match;
						
					}
					
					$mounth = array();
					$tot_compensi = 0;
					
					foreach($riepilogo as $k => $matches) {

						$count_vote = 0;
						$count_send = 0;
						$bonus      = 0;
 	 					$compenso   = 0;
 	 					$anno       = '';
 	 					$count_match= 0;
 	 					$count_vote_received = 0;

						foreach($matches as $match) {
							foreach($match['LdaVote'] as $vote) {
								$count_vote_received++;
								$count_vote += $vote['LdaVote']['ranking'];	
							}
							foreach($match['LdaVoteSend'] as $vote) {
								$count_send++;
							}
							
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro']):   $compenso += $match['Campionati']['TariffaArbitro']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['Delegato']):  $compenso += $match['Campionati']['TariffaDelegato']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['DelegatoA']): $compenso += $match['Campionati']['TariffaDelegatoA']; endif;
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro2']):	 $compenso += $match['Campionati']['TariffaArbitro2']; endif;
					
							if($this->Session->read('Login.data.id') == $match['Lda']['Arbitro']):   	 $bonus++; 
							elseif($this->Session->read('Login.data.id') == $match['Lda']['Arbitro2']):  $bonus++;
							elseif($this->Session->read('Login.data.id') == $match['Lda']['DelegatoA']): $bonus+=0.5;
							elseif($this->Session->read('Login.data.id') == $match['Lda']['Delegato']):  $bonus+=0.5; endif;	
							
							//if($this->Session->read('Login.data.id') == $match['Lda']['Delegato']) debug('sono delegato');
							
							if($match['Lda']['Arbitro'] != '' || $match['Lda']['Delegato'] != '' || $match['Lda']['DelegatoA']) { 
								if($match['Match']['Risultato'] != '' && $match['Lda']['Arbitro'] != $match['Lda']['Delegato'] && $match['Lda']['Arbitro'] != $match['Lda']['DelegatoA']) $count_match++; 
							} 
							
							$anno = $match['Match']['AnnoPartita'];
							
						}
						
						//debug($count_match);
						
						if($count_vote_received > 0) {
							$media_ranking = ceil($count_vote / $count_vote_received);
						}else {
							$media_ranking = 0;
						}
						
						if(($count_match - $count_send) <= 0) $vote_send = array('class' => 'full-rated', 'label' => 'Votazioni completate');
						else							      $vote_send = array('class' => 'not-rated', 'label' => ($count_match - $count_send) . ' partit'.(($count_match - $count_send == 1)? 'a' : 'e').' da votare');
						
						/*Calcolo spese*/
						$start_date = $anno . '-' . $k . '-' . '01';
						$end_date   = $anno . '-' . $k . '-' . '31';
						
						/*$lda = $this->Lda->find('all', array(
							 'conditions' => array(
								'Lda.Data between ? and ?' => array($start_date, $end_date),
								'OR' => array(
									'Lda.Arbitro' => $this->Session->read('Login.data.id'),
									'Lda.Arbitro2' => $this->Session->read('Login.data.id'),
									'Lda.Delegato' => $this->Session->read('Login.data.id'),
									'Lda.DelegatoA' => $this->Session->read('Login.data.id'),
								)
							 ),
							 'order' => 'Lda.Data DESC'
							)
						 );
						 
						 $bonus    = 0;
						 $compenso = 0;
						 
						 foreach($lda as $tmp) {
						 	
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Arbitro']):   $bonus += 1; $compenso += $tmp['Campionati']['TariffaArbitro']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Delegato']):  $bonus += 0.5; $compenso += $tmp['Campionati']['TariffaDelegato']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['DelegatoA']): $bonus += 1; $compenso += $tmp['Campionati']['TariffaDelegatoA']; endif;
							if($this->Session->read('Login.data.id') == $tmp['Lda']['Arbitro2']):  $compenso += $tmp['Campionati']['TariffaArbitro2']; endif;
						 	
						 }					
						 
						 //debug($compenso);	*/						 
						 						
						$altreSpese = $this->AthleteExpense->find('all', array(
						
							'conditions' => array(
							
								'AthleteExpense.Atleta' 	          => $this->Session->read('Login.data.id'),
								'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
							
							),
							'recursive' => 0,
						
						));
						
						$spese = 0;
						
						foreach($altreSpese as $spesa) {
							$spesa = $spesa['AthleteExpense'];
							$spese += $spesa['Importo'];
						}
						
						
						$file_media = APP . 'webroot/files/json/buste/'.$k.'_vote_' . date('m_Y') . '__.'.$this->Session->read('Login.data.id').'.json';
						
						if(!is_file($file_media))
						{
							file_put_contents($file_media, $media_ranking);		
						} else {
							$media = json_decode(file_get_contents($file_media));
							$media_ranking = $media;
						}/**/
											
						
						$mounth[$k]['NumeroGare']   = count($matches);
						$mounth[$k]['MediaRanking'] = $media_ranking;
						$mounth[$k]['Votazioni']    = $vote_send;
						$mounth[$k]['VoteSend']     = $count_send;
						$mounth[$k]['Bonus']    	= $bonus;
						$mounth[$k]['Compenso']		= '€ ' . ($compenso+$spese);
						$mounth[$k]['Anno']		    = $anno;
						
						$tot_compensi += $compenso;
						
					}
					
					if(empty($matches)) {
						
						$spese  = $this->AthleteExpense->find('all', array(
						
							'conditions' => array(
							
								'AthleteExpense.Atleta' => $this->Session->read('Login.data.id'),
								'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo'])
								
							),
							'recursive' => -1,
							'order' => array('AthleteExpense.Data DESC')
						
						));
						
						$riepilogo = array();				
						
						foreach($spese as $spesa) {
						
							$datetime = strtotime($spesa['AthleteExpense']['Data']);
							$mese     = date("m", $datetime);
							$anno     = date("Y", $datetime);
							
							$riepilogo[$mese][] = $spesa;
						
						}
						
						$mounth = array();
						$tot_compensi = 0;
						
						foreach($riepilogo as $k => $tmp) {
							
							/*Calcolo spese*/
							$start_date = $anno . '-' . $k . '-' . '01';
							$end_date   = $anno . '-' . $k . '-' . '31';
							 						
							$altreSpese = $this->AthleteExpense->find('all', array(
							
								'conditions' => array(
								
									'AthleteExpense.Atleta' 	          => $this->Session->read('Login.data.id'),
									'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
								
								),
								'recursive' => 0,
							
							));
							
							$spese = 0;
							
							foreach($altreSpese as $spesa) {
								$spesa = $spesa['AthleteExpense'];
								$spese += $spesa['Importo'];
							}	
							
							$mounth[$k]['NumeroGare']   = 0;
							$mounth[$k]['MediaRanking'] = 0;
							$mounth[$k]['Votazioni']    = 0;
							$mounth[$k]['VoteSend']     = 0;
							$mounth[$k]['Bonus']    	= 0;
							$mounth[$k]['Compenso']		= '€ ' . $spese;
							$mounth[$k]['Anno']		    = $anno;	
							
							$tot_compensi += $spese;						
							
						}
						
					}					
					
					//debug($mounth);
					
					//debug($tot_compensi);
					
					//Setto stagione
					$this->set('stagione', $last_year['AnniSportivi']['AnnoSportivo']);		
					$this->set('tot_compensi', $tot_compensi);
					
					$this->set('athlete_id', $this->Session->read('Login.data.id'));		
					
					//debug($riepilogo);
					$this->set('mounths', $mounth);
					
				}
				
			}
			
			/* ------------------------ */						
			
			function vote_index($match, $athlete, $hash = "") {
			
				$this->layout = "mobile/default";
				
				if ($this->RequestHandler->isAjax()) {
	
					$this->layout = '/mobile/ajax';
					
				}	
				
				$this->set('hash', $hash);
				$this->set('match', $this->Match->findByCalendario($match));
				$this->set('athlete', $this->Athlete->findByAtleta($athlete));
				
			}
			
			function vote_exec() {
			
				$this->autoRender = false;
				
				$this->LdaVote->create();
				$this->LdaVote->set($this->data);
				$this->LdaVote->save();
				
				$options = array(
				
					1 => 'Gravemente insufficiente',
					2 => 'Insufficiente',
					3 => 'Appena sufficiente',
					4 => 'Sufficiente',
					5 => 'Discreto',
					6 => 'Buono',
					7 => 'Ottimo',
				
				);			
				
				$this->set('result', json_encode(array('voto' => $options[$this->data['LdaVote']['ranking']])));
				$this->render('/backend/ajaxResult');
			
			}			
			
		
		function giaVotato($athlete, $lda, $match = null) {
		
			$count = $this->LdaVote->find('first', array(
			
				'conditions' => array(
				
					'LdaVote.match_id' => $match,
					'LdaVote.athlete_lda_id' => $lda,
					'LdaVote.athlete_id' => $athlete,
				
				),
			
			));
			
			if(count($count) > 0) return $count;
			else 			      return false;
		
		}			
		
		/** BOOKING **/

			function booking_timmy() {
			}
			function saveBookingSession() {
			
				$this->layout = "ajax";
				
				$this->Session->delete('BookingData');
				$this->Session->write('BookingData', $_POST);
				
				exit;
			
			}	
			
			function bookingCancel($book_id) {
				
				Configure::Write('debug',2);
				
					$this->layout = "mobile/default";
					
					if ($this->RequestHandler->isAjax()) {
		
						$this->layout = '/mobile/ajax';
						
					}	
				
					$booking = $this->CampiBooking->find('first',array(
					
						'conditions' => array(
						
							'MD5(CampiBooking.id)' => $book_id
						
						)
					
					));
					
					if (!empty($booking))
					$this->set('booking',$booking);
					
					if($this->CampiBooking->delete($booking['CampiBooking']['id'])) {
						
						$campo = $this->Campi->findByCampo($booking['CampiBooking']['campo_id']);
						
						$this->set('nome',$booking['CampiBooking']['bookerNome']);
						$this->set('cognome',$booking['CampiBooking']['bookerCognome']);
						$this->set('email',$booking['CampiBooking']['bookerEmail']);
						$this->set('telefono',$booking['CampiBooking']['bookerTelefono']);
						$this->set('campo',$campo);
						$this->set('data',date("d/m/Y",strtotime($booking['CampiBooking']['Data'] . " " . $booking['CampiBooking']['Ora'])));
						$this->set('ora',$booking['CampiBooking']['Ora']);
						
						$this->set('data_real',$booking['CampiBooking']['Data']);						
						
						$this->Email->to = $booking['CampiBooking']['bookerEmail'];
						$this->Email->subject = 'Midland Sport | Disdetta campo';
						$this->Email->template = 'booking_delete'; 
						$this->Email->send();
					
						if (!empty($campo['Campi']['EmailGestore'])) {
						$this->Email->to = $campo['Campi']['EmailGestore'];
						$this->Email->subject = 'Midland Sport | Disdetta campo da parte di ' . $booking['CampiBooking']['bookerNome'] . " " . $booking['CampiBooking']['bookerCognome'];
						$this->Email->template = 'booking_delete_admin'; 
						$this->Email->send();
						}								
						
					}
					
					$this->render('booking_cancel');
				
			}			
			
			function bookingSend() {
				
					$this->layout = "ajax";
				
					$this->CampiBooking->create();
					
					unset($this->data);
					
					$this->data['CampiBooking']['campo_id'] 		 = $_POST['campo_id'];
					$this->data['CampiBooking']['bookerNome'] 		 = $_POST['bookerNome'];
					$this->data['CampiBooking']['bookerCognome']	 = $_POST['bookerCognome'];
					$this->data['CampiBooking']['bookerTelefono']	 = $_POST['bookerTelefono'];
					$this->data['CampiBooking']['bookerEmail'] 		 = $_POST['bookerEmail'];
					$this->data['CampiBooking']['Data'] 			 = $_POST['Data'];
					$this->data['CampiBooking']['Ora'] 				 = $_POST['Ora'];
					
					$this->CampiBooking->set($this->data);
					
					$campo = $this->Campi->findByCampo($_POST['campo_id']);
				
					$this->set('nome',$_POST['bookerNome']);
					$this->set('cognome',$_POST['bookerCognome']);
					$this->set('email',$_POST['bookerEmail']);
					$this->set('telefono',$_POST['bookerTelefono']);
					$this->set('campo',$campo);
					$this->set('data',date("d/m/Y",strtotime($_POST['Data'] . " " . $_POST['Ora'])));
					$this->set('ora',$_POST['Ora']);
					$this->set('importo',$_POST['Importo']);
					
					$this->set('data_real',$_POST['Data']);
					
					if ($this->CampiBooking->save()) {
					
					$this->set('book_id',$this->CampiBooking->id);
						
					$this->set('booked',1);
						
					$this->Email->to = $_POST['bookerEmail'];
					$this->Email->subject = 'Midland Sport | Prenotazione campo';
					$this->Email->template = 'mobile_booking_confirm'; 
					$this->Email->send();
				
					if (!empty($campo['Campi']['EmailGestore'])) {
					$this->Email->to = $campo['Campi']['EmailGestore'];
					$this->Email->subject = 'Midland Sport | Prenotazione campo da parte di ' . $_POST['bookerNome'] . " " . $_POST['bookerCognome'];
					$this->Email->template = 'booking_confirm_admin'; 
					$this->Email->send();
					}
						
						
					} else {
						$this->set('booked',0);
					}
				
				
			}					
			
			function squadra($squadra,$nome_squadra = null,$tab = 1) {
			
				$this->layout = 'mobile/default';
			

					$this->set('parent', $this->Page->read(null, 51));	
					
					$this->set('parent2', $this->Page->read(null, $_GET['type']));	
				$squadre_campionati = $this->SquadreCampionati->find('all', array(
				
					'fields' => array('Campionati.AnnoSportivo'),
					'conditions' => array(
						'SquadreCampionati.Squadra' => $squadra,
					),
					'order' => 'Campionati.AnnoSportivo DESC',
				
				));
				
				$squadra = $this->Squadre->findBySquadra($squadra);
				
				$uploads = array();
				foreach($squadra['Upload'] as $upload) {
					if($upload['tag'] == '') $upload['tag'] = 'Gallery';
					$uploads[$upload['tag']][] = $upload;
				}
				
				$this->set('squadra', $squadra);
				$this->set('anni', Set::combine($squadre_campionati, '{n}.Campionati.AnnoSportivo','{n}.Campionati.AnnoSportivo'));
				
				//Check if tab not have information
				
				$elements = array(
				
					1 => 'squadra_site',
					2 => 'albo-trofei_site',
					3 => 'statistiche_site',
					4 => 'galleria_site',
				
				);
				
				if(!isset($elements[$tab])) $tab = 1;				
				
				switch($tab) {
					
					case 1:
					
						if(empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
							$tab = 3;
					
					break;
					
					case 2:
					
						if(empty($squadra['SquadreAlbo']) && empty($uploads['Trofeo'])) {
							if(empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
								$tab = 3;
							else 
								$tab = 1;
						}
												
					
					break;
					
					case 3:
							$tab = 3;
					break;
					
					case 4:
					
						if(empty($uploads['Gallery'])) {
							if(empty($squadra['Squadre']['Storia']) && empty($uploads['Squadra']))
								$tab = 3;
							else 
								$tab = 1;							
						}
					
						
					break;
					
				}
				
				//Back button option
				if(isset($_GET['option'])) {
					$params = explode('-',$_GET['option']);
					$link = '/lista';
					foreach($params as $p) {
						$link .= '/'.$p;
					}
					$tipo  = (isset($params[0])? $params[0]:0);
					$sesso = (isset($params[1])? $params[1]:0); 
				} else {
					$link  = '/lista/0/0';
					$tipo  = 0;
					$sesso = 0; 
				}
				
				$this->set('tipo', $tipo);
				$this->set('sesso', $sesso);
				$this->set('back', $link);
				$this->set('element', $elements[$tab]);
			
		$squadre = $this->Squadre->find('all',array('order' => 'Squadre.Denominazione ASC','conditions' => array(
				
					"Squadre.Squadra IN 
					
					({$squadra['Squadre']['Squadra']})",
					"Squadre.SquadraServizio" => 0
				
				)));
				
				foreach ($squadre as $squadra) {
					
					$nome = $squadra['Squadre']['Denominazione'];
					
					$campionati = $this->SquadreCampionati->find('count',array('conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra'])));
					
					$squadra['Info']['Campionati'] = $campionati;
					
					
					$stagioni = $this->SquadreCampionati->find('all',array('fields' => 'Campionati.AnnoSportivo','conditions' => array('SquadreCampionati.Squadra' => $squadra['Squadre']['Squadra']), 'order' => 'Campionati.AnnoSportivo DESC'));
					
					$tmp = array();
					foreach($stagioni as $stagione) {
						$tmp[$stagione['Campionati']['AnnoSportivo']] = $stagione['Campionati']['AnnoSportivo'];
					}
					unset($stagioni);
					$stagioni = $tmp;
					
					$string_stagioni = '';
					
					$stagioni = array_merge($stagioni);
					
					foreach($stagioni as $k => $stagione) {
						if($k+1 == count($stagioni)) $virgola = '';
						else $virgola = ', ';
						$string_stagioni .= $stagione . $virgola;
					}
					
					$squadra['Info']['Stagioni'] = $string_stagioni;
					
					$logo    = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Logo')));
					$sponsor = $this->Upload->find('first',array('conditions' => array('Upload.squadra_id' => $squadra['Squadre']['Squadra'],'tag' => 'Sponsor')));
					
					if (empty($logo)) {
						
						$squadra['Info']['logo'] = '';
						
					} else {
						
						$squadra['Info']['Logo'] = $logo['Upload']['path'];
						
					}
					
					if (empty($sponsor)) {
						
						$squadra['Info']['sponsor'] = '';
						
					} else {
						
						$squadra['Info']['sponsor'] = $sponsor['Upload']['path'];
						
					}					
					
					$start = substr(trim($nome),0,1);
					$end = substr(trim($nome),1,2);
					
					if (!is_numeric($start) && $end != "°") {
					
					$chiave = Inflector::Slug($start);
					
					//$alfabeto[strtoupper($chiave)][] = $squadra;

					$this->set('info',$squadra);
	
					}
					
		
				}
			
			}
	
			function getResult($id_calendar) {
				
				$this->layout = "ajax";
							
						   $this->Match->recursive = 2;
				$partita = $this->Match->findByCalendario($id_calendar);
				
				$goal	 = array();
				$espulsi = array();
				$ammoniti= array();
				$agoal   = array();
				
				$listAtleti = array();
				//Check ammoniti, espulsi, goal
				
				foreach($partita['Matchgoal'] as $stat) {
					
					if($stat['Ammonizione'] == 'Si')
						$ammoniti[] = $stat['Atleta'];
					if($stat['Espulsione'] == 'Si')
						$espulsi[] = $stat['Atleta'];
					if($stat['Goal'] > 0)
					{
						$goal[$stat['Atleta']] = array(
							'Atleta' => $stat['Atleta'],
							'Goal'   => $stat['Goal']
						);
					}
					if($stat['Autogoal'] > 0)
					{
						$agoal[$stat['Atleta']] = array(
							'Atleta' => $stat['Atleta'],
							'Goal'   => $stat['Autogoal']
						);
					}	
					
					$listAtleti[] = $stat['Atleta'];			
					
				}
				
				//Find atleti
				$atleti_casa = array();
				$atleti_trasf= array();
				
				foreach($partita['Casa']['Yearbook'] as $atleta) {
					
					$this->Athlete->recursive = 0;
					$athlete = $this->Athlete->findByAtleta($atleta['Atleta']);
					
					$athlete['Athlete']['Goal']      = 0;
					$athlete['Athlete']['Autogoal']  = 0;
					$athlete['Athlete']['Espulso']   = 'No';
					$athlete['Athlete']['Ammonito']  = 'No';
					$athlete['Athlete']['NumeroMaglia'] = $atleta['NumeroMaglia'];
					$athlete['Athlete']['Ruolo'] = $atleta['Ruolo'];
					
					if(isset($goal[$atleta['Atleta']])) {
						
						$athlete['Athlete']['Goal'] = $goal[$atleta['Atleta']]['Goal'];
						
					}
					if(isset($agoal[$atleta['Atleta']])) {
						
						$athlete['Athlete']['Autogoal'] = $agoal[$atleta['Atleta']]['Goal'];
						
					}				
					if(in_array($atleta['Atleta'], $espulsi)) {
						
						$athlete['Athlete']['Espulso'] = 'Si';
						
					}
					if(in_array($atleta['Atleta'], $ammoniti)) {
						
						$athlete['Athlete']['Ammonito'] = 'Si';
						
					}			
					
					if(in_array($atleta['Atleta'], $listAtleti))	
						$atleti_casa[] = $athlete['Athlete'];
					
				}
				foreach($partita['Trasferta']['Yearbook'] as $atleta) {
					
					$this->Athlete->recursive = 0;
					$athlete = $this->Athlete->findByAtleta($atleta['Atleta']);
					
					$athlete['Athlete']['Goal']      = 0;
					$athlete['Athlete']['Autogoal']  = 0;
					$athlete['Athlete']['Espulso']   = 'No';
					$athlete['Athlete']['Ammonito']  = 'No';
					
					$athlete['Athlete']['NumeroMaglia'] = $atleta['NumeroMaglia'];
					$athlete['Athlete']['Ruolo'] = $atleta['Ruolo'];				
					
					if(isset($goal[$atleta['Atleta']])) {
						
						$athlete['Athlete']['Goal'] = $goal[$atleta['Atleta']]['Goal'];
						
					}
					if(isset($agoal[$atleta['Atleta']])) {
						
						$athlete['Athlete']['Autogoal'] = $agoal[$atleta['Atleta']]['Goal'];
						
					}				
					if(in_array($atleta['Atleta'], $espulsi)) {
						
						$athlete['Athlete']['Espulso'] = 'Si';
						
					}
					if(in_array($atleta['Atleta'], $ammoniti)) {
						
						$athlete['Athlete']['Ammonito'] = 'Si';
						
					}					
					
					if(in_array($atleta['Atleta'], $listAtleti))	
						$atleti_trasf[] = $athlete['Athlete'];				
					
					
				}	
				
				/*
				debug($atleti_trasf);
				
				debug('Goal');
				debug($goal);
				
				debug('Espulsi');
				debug($espulsi);
				
				debug('Ammoniti');
				debug($ammoniti);	
				*/
				
				$data = array(
				
					'AtletiCasa'      => $atleti_casa,
					'AtletiTrasferta' => $atleti_trasf,
					'Casa'			  => $this->Squadre->findBySquadra($partita['Casa']['Squadre']['Squadra']),
					'Trasferta'		  => $this->Squadre->findBySquadra($partita['Trasferta']['Squadre']['Squadra']),
					'Match'			  => $partita['Match'],
					'Espulsi'		  => $espulsi,
					'Goal'			  => $goal,
					'Ammoniti'		  => $ammoniti,
					'Campo'			  => $partita['Campi']
				
				);					
				
				$this->set('data',$data);
				
			}	
	
	}
