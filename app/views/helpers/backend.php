<?

	class BackendHelper extends AppHelper {
		
			public $opts = array();
			
			public $fields = array();
			
			public $Session = null;
			
			public $Cookie = null;

			public $Form = null;

			public $model = null;
			
			public $limit = 100;
			
			public $page = 1;
			
			function __construct() {
				
			
				App::Import('Component','Session');
				App::Import('Component','Cookie');

				$this->Session 	= new SessionComponent();
				$this->Form 	= new FormHelper();
				parent::__construct();
				
			}
			
			function isAjax() {
				return env('HTTP_X_REQUESTED_WITH') === "XMLHttpRequest";
			}
			
			function searchQuick($arr,$fieldName) {
				
				foreach ($arr as $key => $value) {
				
					if ($value['field'] == $fieldName) return $arr[$key];
					
				}
				
				return null;
				
			}
			
			function getField($arr,$objectNotation) {
				
				$objectNotation = explode(".",$objectNotation);
				
				if (isset($arr[$objectNotation[0]][$objectNotation[1]])) return $arr[$objectNotation[0]][$objectNotation[1]];
				
				return "";
				
			}

			function getFilter($field,$opts = array()) {
			
					$out = '';
			
					if ($this->Session->check($this->params['controller'] . ".modelFields")) {
						
						$this->fields = $this->Session->read($this->params['controller'] . ".modelFields");
						
					}
					
					
					$searchFilters = array();
					
					if ($this->Session->check(Inflector::Camelize($this->params['controller']) . ".searchFilters")) {
						
						$searchFilters = $this->Session->read(Inflector::Camelize($this->params['controller']) . ".searchFilters");
						
					}
					
					$class = "";
					
					if (isset($opts['date']) && $opts['date'] == true) $class .= ' datePicker';
					
					
					foreach ($this->fields as $key => $value) {
				
				
						if ($value['field'] == $field) {
							
							$out = '
							<div class="input">
								<input type="text" value="' . $key . '" readonly="readonly" />							
							</div>
							<div class="input">
								<input name="data[searchFilters][' . $value['field'] . '][type]" type="radio" value="equ" ' . ((isset($searchFilters[$value['field']]['type']) && $searchFilters[$value['field']]['type'] == "equ")? 'checked="checked"' : '') . '/><span>uguale</span>
								<input name="data[searchFilters][' . $value['field'] . '][type]" type="radio" value="con" ' . ((isset($searchFilters[$value['field']]['type']) && $searchFilters[$value['field']]['type'] == "con")? 'checked="checked"' : '') . '/><span>contiene</span>
								<input name="data[searchFilters][' . $value['field'] . '][type]" type="radio" value="max" ' . ((isset($searchFilters[$value['field']]['type']) && $searchFilters[$value['field']]['type'] == "max")? 'checked="checked"' : '') . '/><span>maggiore di</span>
								<input name="data[searchFilters][' . $value['field'] . '][type]" type="radio" value="min" ' . ((isset($searchFilters[$value['field']]['type']) && $searchFilters[$value['field']]['type'] == "min")? 'checked="checked"' : '') . '/><span>minore di</span>
							</div>
							<div class="input text-input">
								<input type="text" class="' . $class . '" name="data[searchFilters][' . $value['field'] . '][value]" value="' . ((isset($searchFilters[$value['field']]['value']))? $searchFilters[$value['field']]['value'] : ''). '" />							
							</div>
							';
						}
				}
				
				return $out;
				
			}
			
			
			function getFilterType($type) {
	
						switch ($type) {
							
							
							case 'equ':
								return "uguale a";
							break;
							
							case 'con':
								return "contiene";
							break;
							
							case 'min':
								return "minore di";
							break;
							
							case 'max':
								return "maggiore di";
							break;
							
							
						}
						
						return "";
				
			}

			function getOrder($key,$name) {
				
				App::Import('Helper','Html');
								
				$html = new HtmlHelper();
				
				$orderType = 'default';
								
				if ($this->Session->check('order_array')) {
									
					//$field = $this->Session->read($this->params['controller'] . "." . $this->model . ".defaultOrder");
					
					$fields = $this->Session->read('order_array');
										
					if(!isset($fields[$this->params['controller']])) $fields[$this->params['controller']] = array();
					
					if($fields[$this->params['controller']] != array()) {
																														
						foreach($fields[$this->params['controller']] as $field) {						
												
							$campo = explode(' ', $field);
																			
							if($campo[0] == $key['field']) {														
							
								//$orderType = $this->Session->read($this->params['controller'] . "." . $this->model . ".defaultDir");
								
								$orderType = $campo[1];
																														
							}
						
						}
					
					}
					
				}
												
				if ($orderType == 'desc' || $orderType == 'default') $revertOrder = 'asc';
				else 												 $revertOrder = 'desc';
								
				if (isset($key['order']) && $key['order'] == true) {
				
					$link = '<a href="' . $html->url(array('controller'=>$this->params['controller'], 'action'=>$this->params['action'])) . '/order/' . $key['field'] . '/' . $revertOrder . '" title=""><span class="order_type"><img src="/img/timmyshare/order_' . $orderType . '.png" width="12" height="17" alt=""></span><span class="name_type">' . $name . '</span></a>';
					
					if($orderType != 'default'):
					
						$link .= '<a href="'. $html->url(array('controller'=>$this->params['controller'], 'action'=>$this->params['action'])) . '/deleteOrder/' . $key['field'] . '"><span class="delete-th-filter"><img src="/img/timmyshare/icon-filter-delete-th.png" /></span></a>';
					
					endif;
					
					return $link;
					
				} else return $name;
				
			}
		
			function getFiles($pk,$id, $options = array()) {

					if(!isset($options['buttons'])) $options['buttons'] = array();
					if(!isset($options['limit'])) $options['limit'] = 0;
					if(!isset($options['title'])) $options['title'] = 'Documenti/allegati/files';
					if(!isset($options['tag'])) $options['tag'] = array();
					if(!isset($options['conditions'])) $options['conditions'] = array();
					if(!isset($options['element'])) $options['element'] = 'backend/files_index';
					
 					$view =& ClassRegistry::getObject('view');
				
					App::Import('Model','Upload');
					
					$uploads = new Upload();
					
					$conditions = array(
					
						$pk => $id,
						"$pk != " => 0
						
					);
					
					if(!empty($options['conditions'])) $conditions = array_merge($conditions, $options['conditions']);
					
					$files = $uploads->find('all',array('conditions' => $conditions,'order' => 'created DESC'));
					
					return $view->element($options['element'],array('files' => $files, 'buttons' => $options['buttons'], 'limit' => $options['limit'],'title' => $options['title'],'tag' => $options['tag']));
				
			}
		
			function formIndex($model,$fields = null,$opts,&$pointer = null) {
							
				App::Import('Helper','Html');
				
				$html = new HtmlHelper();
				
				$this->model = $model;
								
				$view =& ClassRegistry::getObject('view');
				
				$this->opts = $opts;
				
				$this->fields = $fields;
				
				$this->Session->write($this->params['controller'] . ".modelFields",$this->fields);
				
				if (empty($this->{$model})) App::Import('Model',$model);
			
				$this->{$model} = new $model();
				
				$options = array();
				
				$options['conditions'] = array();
				
				if (!isset($opts['quickSearch'])) {
					
					foreach ($fields as $field) {
						
						$field_ = explode('.', $field['field']);						
						
						if($field_[1] != 'disabled'){
						
							$opts['quickSearch'][] = $field['field'];
						
						}
					}
					
				}
				
				if (!isset($opts['edit'])) $opts['edit'] = true;
				
				if (isset($opts['recursive'])) $options['recursive'] = $opts['recursive'];
				
				if (isset($_POST['quickSearch'])) {
					
					$this->Session->write(Inflector::Camelize($this->params['controller']) . "." . $model . ".quickSearch",$_POST['quickSearch']);
		
					$view->layout = 'ajax';

					$ret = "OK";
			
					$view->set('ret',$ret);
										
					return $view->render('/backend/ajax');
		
				}
				
				if (isset($_POST['deleteQuickSearch'])) {
				
					$this->Session->delete(Inflector::Camelize($this->params['controller']) . "." . $model . ".quickSearch");
		
					$view->layout = 'ajax';

					$ret = "OK";
			
					$view->set('ret',$ret);
					
					
					return $view->render('/backend/ajax');
					
				}
				
				$quickSearch = $this->Session->read(Inflector::Camelize($this->params['controller']) . "." . $model . ".quickSearch");
				
				if ($quickSearch != "") {


					if ($this->params['controller'] == 'spools') {
				
				//	$opts['conditions'][0] = "1=1";
					$oldcond = $opts['conditions'];
					$or_cond = array();


					
					foreach ($opts['quickSearch'] as $quickField) {
						
						$infoField = $this->searchQuick($fields,$quickField);
						
						
						
						if (!isset($infoField['afterSearch'])) {
						
						$or_cond[] = array($quickField . " LIKE" => '%' . $quickSearch . '%');

						} else {
							
						$or_cond[] = array($quickField . " LIKE" => '%' . $infoField['afterSearch']($quickSearch) . '%');
	
						} 

					}


					if ($this->params['action'] != 'admin_index')
					$options['conditions'] = array_merge(array('OR' => $or_cond),$opts['conditions']);
					else
					$options['conditions'] = array('OR' => $or_cond);


					}  else {
			
					$opts['conditions'][0] = "1=1";
					
					foreach ($opts['quickSearch'] as $quickField) {
						
						$infoField = $this->searchQuick($fields,$quickField);
						
						
						
						if (!isset($infoField['afterSearch'])) {
						
						$or_cond[] = array($quickField . " LIKE" => '%' . $quickSearch . '%');

						} else {
							
						$or_cond[] = array($quickField . " LIKE" => '%' . $infoField['afterSearch']($quickSearch) . '%');
	
						} 

					}
					$options['conditions'] = array('OR' => $or_cond);
					


					}
					
				}
				
				if(isset($opts['buttons'])) {
									
					$buttons = $opts['buttons'];
				
				} else $buttons = array();
				
				if(isset($opts['order_option'])) {
				
					$order_option = true;
				
				} else $order_option = false;
				
				if (isset($opts['conditions'])) $options['conditions'] = array_merge($options['conditions'],$opts['conditions']);
				 				
				if (isset($opts['defaultDir'])) $opts['defaultDir'] = strtolower($opts['defaultDir']);
				
				if (isset($opts['defaultOrder'])) {
					
					$dir = (isset($opts['defaultDir']))? $opts['defaultDir'] : '';
					
					$options['order'] = $opts['defaultOrder'] . " " . $dir;
				
				}
				
				if (isset($_POST['defaultLimit'])) {
					
						$this->Session->write($this->params['controller'] . "." . $this->model . ".defaultLimit",$_POST['defaultLimit']);
				
								$view->layout = 'ajax';
	
								$ret = "OK";
						
								$view->set('ret',$ret);
								
								
								return $view->render('/backend/ajax');
					
				}
				
				if (!$this->Session->check($this->params['controller'] . "." . $this->model . ".defaultOrder") && isset($opts['defaultOrder'])) {
					$this->Session->write($this->params['controller'] . "." . $this->model . ".defaultOrder",$opts['defaultOrder']);
				}
				

				if (!$this->Session->check($this->params['controller'] . "." . $this->model . ".defaultDir") && isset($opts['defaultDir'])) {
					$this->Session->write($this->params['controller'] . "." . $this->model . ".defaultDir",$opts['defaultDir']);
				}
				
				
				
				if (isset($this->params['pass'][0])) {
					
					switch ($this->params['pass'][0]) {
						
							case 'page':
							
								$this->page = $this->params['pass'][1];
							
							break;
						
							case 'switchdisabled':
							
								$view->layout = 'ajax';
								
								// $this->{$model}->create();
								// $this->{$model}->set($this->{$model}->primaryKey,$this->params['pass'][1]);
								// $this->{$model}->set('disabled',$this->params['pass'][2]);
								// $this->{$model}->save();
								
								$this->{$model}->id = $this->params['pass'][1];
								//if($this->{$model}->hasField('published') && $this->{$model}->field('published') == '0000-00-00 00:00:00') $this->params['pass'][2] = 1;
								$this->{$model}->set('disabled',$this->params['pass'][2]);
								$this->{$model}->save();
								
								$view->set('ret',json_encode( array( 'src' => '/img/timmyshare/icon_disabled_' . $this->params['pass'][2] . '.gif' ) ));
				
								return $view->render('/backend/ajax');
							
							
							break;
						
						
							case 'delete':
							
								$view->layout = 'ajax';
						
								$ret = $this->{$model}->delete($this->params['pass'][1],true);
						
								$view->set('ret',json_encode( array( 'ret' => $ret ) ));
						
								return $view->render('/backend/ajax');
								
								
					
							break;
							
							case 'deleteall':
							
								$view->layout = 'ajax';
								
								$ret = $this->{$model}->deleteAll(array($model . '.' . $this->{$model}->primaryKey => $_POST['ids']));
	
								$view->set('ret',json_encode ( array('ret' => $error ) ));								
								
								return $view->render('/backend/ajax');
								
							break;
							
							case 'order':
							
								$this->Session->write($this->params['controller'] . "." . $this->model . ".defaultOrder",$this->params['pass'][1]);
								$this->Session->write($this->params['controller'] . "." . $this->model . ".defaultDir",$this->params['pass'][2]);
				
							break;
							
							case 'deleteOrder':
							
								$field = $this->params['pass'][1];
							
								$order_array = $this->Session->read('order_array');
								
								foreach($order_array[$this->params['controller']] as $k => $order) {
								
									$order = explode(' ', $order);
									
									if($order[0] == $field) {
																											
										unset($order_array[$this->params['controller']][$k]);
									
									}
								
								}
																																
								$this->Session->delete('order_array');
								$this->Session->write('order_array', $order_array);
																															
							break;
							
							case 'unset':
						
						
								$field = $this->params['pass'][1];
								
								$oldfield = $this->params['pass'][1];
								
								$data = $this->Session->read(Inflector::Camelize($this->params['controller']) . ".searchData");
								
								$field = explode(".",$field);
								
								unset($data[$field[0]][$field[1]]);
															
								$this->Session->write(Inflector::Camelize($this->params['controller']) . ".searchData",$data);
									
								$data = $this->Session->read(Inflector::Camelize($this->params['controller']) . ".searchFilters");
								
								unset($data[$oldfield]);
								
								$this->Session->write(Inflector::Camelize($this->params['controller']) . ".searchFilters",$data);
									
							break;
							
						
						
					}
					
				}
		
				if ($this->Session->check($this->params['controller'] . "." . $this->model . ".defaultLimit")) {
					
					$this->limit = $this->Session->read($this->params['controller'] . "." . $this->model . ".defaultLimit");
					
				}

				if ($this->Session->check(Inflector::Camelize($this->params['controller']) . ".searchData")) {

					$data = $this->Session->read(Inflector::Camelize($this->params['controller']) . ".searchData");
					
					$search = 0;
					
					foreach ($data as $model => $searchFields) {
											
						foreach ($searchFields as $searchField => $value) {
												
							if(substr($searchField,0,6) != 'dummy_') { //se non  un campo fittizio!
								
								if (!empty($value)) {
									
									$search = 1;

									$infoField = $this->searchQuick($fields,$model.".".$searchField);
		
									if (isset($infoField['afterSearch'])) $value = $infoField['afterSearch']($value);
		
									$options['conditions'][$model . "." . $searchField . " LIKE"] = "$value%";

									
								}
								
							}
						
						}
						
					}
					
					if($search == 1) $options['conditions'][0] = "1=1";
					
				}
				
				if ($this->Session->check(Inflector::Camelize($this->params['controller']) . ".searchFilters")) {

					$data = $this->Session->read(Inflector::Camelize($this->params['controller']) . ".searchFilters");
										
					$filtered = 0;
															
					foreach ($data as $key => $value) {
										
						if($value['value'] != '') $filtered = 1;
										
						$infoField = $this->searchQuick($fields,$key);
		
						if (isset($infoField['afterSearch'])) $value['value'] = $infoField['afterSearch']($value['value']);

						if (isset($value['type']) && isset($value['value'])) {
						
							if ($value['type'] == "equ") $options['conditions'][$key] = $value['value'];
							if ($value['type'] == "con") $options['conditions'][$key . " LIKE"] = "%" . $value['value'] . "%";
							if ($value['type'] == "min") $options['conditions'][$key . " <"] = $value['value'];
							if ($value['type'] == "max") $options['conditions'][$key . " >"] = $value['value'];
							
						}
						
					}									
					
					if($filtered == 1) $options['conditions'][0] = "1=1";
						
					
					
				}
		
				$count = $this->{$this->model}->find('count',$options);
				
				$pages = ceil ($count/$this->limit);
				
				

					
			
				$options['fields'] = array();
				
				$options['fields'][] = $this->{$this->model}->primaryKey;
					
				foreach ($fields as $field) {
					
					$options['fields'][] = $field['field'];
					
				}
								
				if ($this->Session->check($this->params['controller'] . "." . $this->model . ".defaultOrder")) {
					
					$options['order'] = $this->Session->read($this->params['controller'] . "." . $this->model . ".defaultOrder") . " " . $this->Session->read($this->params['controller'] . "." . $this->model . ".defaultDir");
					
					$order_array = $this->Session->read('order_array');
										
						$str = explode('.', $options['order']);
						$key = explode(' ', $str[1]);
																	
						$order_array[$this->params['controller']][$key[0]] = $options['order'];
						
						

						
						if(!isset($this->params['pass'][0])) $this->params['pass'][0] = '';												
						if($this->params['pass'][0] != 'deleteOrder') $this->Session->write('order_array', $order_array);		
										
				}
				
				if (!isset($_GET['is_xls']) && !isset($_GET['is_all']))															
					$options['limit'] = $this->limit;
				else
					ini_set('max_execution_time',9999);
				
				$options['page'] =  $this->page;
		
				$options['order'] = array();
				
				foreach($order_array[$this->params['controller']] as $order) {
				
					$options['order'][] = $order;
				
		
				}
		
		
				
				if ($this->model == 'Teambook') {
				
					if (count($options['order']) > 1) {
						
							foreach ($options['order'] as $ord) {
							
								if ($ord == 'Teambook.AnnoSportivo desc' || $ord == 'Teambook.AnnoSportivo asc') {
								
									$options['order'] = array($ord);
									break;
								
								}
								
							}
					
					}
				
				}
				
				if (isset($options['conditions']['Block.mother_page LIKE'])) {
				
					if (($options['conditions']['Block.mother_page LIKE']) == 'News dalla redazione%' || ($options['conditions']['Block.mother_page LIKE']) == 'Ultim\'ora%') {
					
						if ($options['order'][0] == 'Block.order asc')
						$options['order'] = array('Block.published desc');
					
					}
				
				}

					if(substr_count($_SERVER["REQUEST_URI"], "campionatis") != 0)
						$options["order"] = ["Campionati.AnnoSportivo_v desc", "Campionati.order asc"];

				
				

				$data = $this->{$this->model}->find('all',$options);
	
	
				$vars = 
							
							array( 'fields'  => $fields,
								   'data' 	 => $data,
								   'page' 	 => $this->page,
								   'pages'	 => $pages,
								   'url' 	 => $html->url(array('controller'=>$this->params['controller'], 
								   'action'  =>	$this->params['action'])),
								   'limit' 	 => $this->limit,
								   'pk'		 => $this->{$this->model}->primaryKey,
								   'pageTitle' => ((isset($opts['pageTitle']))? $opts['pageTitle'] : ucfirst($this->params['controller'])),
								   'buttons' => $buttons,
								   'order_option' => $order_option,
								   'model'	 => $this->model,
								   'edit' 	 => $opts['edit'],
								   'besideQuickSearch' =>  ((isset($opts['besideQuickSearch']))? $opts['besideQuickSearch'] : ''),
								   'allow_edit' => ((isset($opts['allow_edit']))? $opts['allow_edit'] : true),
								   'allow_add' => ((isset($opts['allow_add']))? $opts['allow_add'] : true),
								   'allow_search' => ((isset($opts['allow_search']))? $opts['allow_search'] : true),
								   'allow_filters' => ((isset($opts['allow_filters']))? $opts['allow_filters'] : true)
								
							);
							
				$ref = $vars;
				
				$ref['reference'] = $vars;
	
				if (isset($_GET['is_xls']))
				return $view->element('backend/xls_index',$ref);
				else
				return $view->element('backend/form_index',$ref);
				
				
			}
		
	}
	
?>
