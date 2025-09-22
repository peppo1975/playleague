<?php
// Da rifare
	class AppModel extends Model {
		
			public $formErrors;
		
			function __construct($id = false, $table = null, $ds = null) {
			
				parent::__construct($id, $table, $ds);
				
				require APP . '/vars/errors.php';
						
				$this->formErrors = $formErrors;
				
				App::import('component', 'CakeSession');        
				
			}
			
			function beforeSave() 
			{
				
				App::import('component', 'CakeSession');        
				
				if (CakeSession::check('Auth.User.group_id') && in_array(CakeSession::read('Auth.User.group_id'), Configure::read('group_acl')))
				{
				$group_id = CakeSession::read('Auth.User.group_id');
				}
				else
				{
				$group_id = 1;
				}

					$exists = $this->exists();
					if ( !$exists && $this->hasField('group_id') && empty($this->data[$this->alias]['group_id']) ) {
							$this->data[$this->alias]['group_id'] = $group_id;
					}

				return true;
				
			}	
			
			function beforeFind(&$queryData)
			{				
				App::import('Component', 'Cookie');
				$Cookie = new CookieComponent();
				App::import('Component', 'Session');
				$Session = new SessionComponent();
				$type = $Cookie->read('admin_data_type');
				
				//debug($type);
		
				if(empty($type)) $type = 'current';
					
				if($type == 'current') {

					if($this->hasField('group_id') && $Session->read('User.group_id') != '' && in_array($this->useTable,Configure::read('group_table'))) {

						if(in_array($Session->read('User.group_id'), Configure::read('group_acl'))) {
							$queryData['conditions'][$this->alias . '.group_id'] = (int)$Session->read('User.group_id');
						} else {
							$queryData['conditions'][$this->alias . '.group_id'] = 1;
						}
						
					} 		
				
				}

				return $queryData;
				
			}		
			
			function unbindValidation($type, $fields, $require=false)
			{
			    if ($type === 'remove')
			    {
			        $this->validate = array_diff_key($this->validate, array_flip($fields));
			    }
			    else
			    if ($type === 'keep')
			    {
			        $this->validate = array_intersect_key($this->validate, array_flip($fields));
			    }
			    
			    if ($require === true)
			    {
			        foreach ($this->validate as $field=>$rules)
			        {
			            if (is_array($rules))
			            {
			                $rule = key($rules);
			                
			                $this->validate[$field][$rule]['required'] = true;
			            }
			            else
			            {
			                $ruleName = (ctype_alpha($rules)) ? $rules : 'required';
			                
			                $this->validate[$field] = array($ruleName=>array('rule'=>$rules,'required'=>true));
			            }
			        }
			    }
			} 			

			function getOrder($model) {
			
				App::Import('Model', 'Order');
				$this->Order = new Order;
				
				return $this->Order->find('first', array('conditions' => array('Order.model' => $model)));
			
			}
		
		   function getError($errName) {

					if (isset($this->formErrors[$errName])) return $this->formErrors[$errName];
					return "";
			   
		   }
		   
		   function dmy2ymd(&$field) {
			   
				$data = array();
			   
				if (preg_match('~(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)[0-9]{2})~Ui',$field,$data)) {
					
					$field = $data[3] . "-" . $data[2] . "-" . $data[1];
					
				}

				return $field;
			   
		   }
		   
		   function password_compare($pwd,$pwd_confirm) {
		   
				if($pwd != $pwd_confirm) { $this->invalidate('password_confirm',$this->getError('PASSWORD_CONFIRM')); }
				
				else return true;
		   
		   }
		   
			function isUnique($params, $id="") {
			 
					if (!is_array($params)) {
						trigger_error(__METHOD__ . ' - $params must be an array', E_USER_ERROR);
					}

					$query = array();

					$this->recursive = -1;

					foreach ($params as $field => $value) {
						
						$query[$this->name . '.' . $field] = $value;
					
					}

					if (empty($id))
						$fields[$this->name.'.id'] = "!= NULL"; 
					else
						$fields[$this->name.'.id'] = "!= {$id}";

					if ($this->hasAny($query)) {
					
						return false;
						
					}else return true;            
			}

			function is_unique($conditions = array(), $invalidate = '') {
			
				App::Import('Model', $this->name);
				$Model = new $this->name;
			
				$data = $Model->find('count', array(
				
					'conditions' => $conditions,
				
				));
				
				if($data > 0) {
				
					$Model->invalidate($invalidate, 'Record esistente.');
					return false;
				
				} 
			
			}
			
		   function setMetadata($model) {
		   
				//$model = $this->name;
				
				App::Import('Model','Metadata');
				$this->Metadata = new Metadata;
				
				App::Import('Model', $model);
				$this->$model = new $model;
				
				$last_insert = $this->$model->find('first', array('order' => $model . '.id DESC'));
				$last_id = $last_insert[$model]['id'];
				
				if(isset($this->data[$model]['id'])) {
				
					$metadata = $this->Metadata->find('first', array(
						
							'conditions' => array(
							
								'Metadata.model' => $model,
								'Metadata.model_id' => $this->data[$model]['id']
							
							)
						
						)
						
					);
					
						$metadata['Metadata']['title'] = $this->data['Metadata']['title'];
						$metadata['Metadata']['keywords'] = $this->data['Metadata']['keywords'];
						$metadata['Metadata']['description'] = $this->data['Metadata']['description'];
						$metadata['Metadata']['model'] = $model;
						$metadata['Metadata']['model_id'] = $this->data[$model]['id'];
						
						$this->Metadata->set($metadata);
						$this->Metadata->save();
				
				} else {

					if(isset($this->data['Metadata']) && !empty($this->data['Metadata'])) {
					
						$this->Metadata->create();
						$this->Metadata->set($this->data);
						$this->Metadata->set('model', $model);
						$this->Metadata->set('model_id', $last_id);
						$this->Metadata->save();
					
					}
				
				}
		   
		   }
		   
				function beforeDelete() {
								
				   App::Import('Model','Right');
				   $this->Right = new Right;
				   
				   $controller = explode('/',$_REQUEST['url']);
				   
						if (isset($_SESSION['Auth']['User']['group_id']))
						$group_id   = $_SESSION['Auth']['User']['group_id'];
						else
						$group_id = 0;
						
						$controller = Inflector::Camelize($controller[1]);
						$action     = 'delete';
						
						$resource   = $controller.'|'.$action;
						
						$isEnable = $this->Right->find('first', array(
						
							'conditions' => array(
							
								'Right.group_id' => $group_id,
								'Right.resource' => $resource,

							
							),
						
						));
						
						$isEnable_2 = $this->Right->find('count',array(
						
							'conditions' => array(
								
								'Right.group_id' => $group_id,
								'Right.resource' => $controller,
								'Right.allow'    => 0,							
							
							
							)
						
						));
						
						
						
						$resultEnable = true;
						
						if ($isEnable_2 > 0) $resultEnable = false;
						
						if ($isEnable) {
							
							if ($isEnable['Right']['allow'] == 1) $resultEnable = true;
							else  						 $resultEnable = false;
							
							
						}
 						
						
						if ($resultEnable == false) {
						
							return false;
							
						} else return true;
				
				}	

} 
			
 
