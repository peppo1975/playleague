<?

function getPriorityVal($value) {

	$options = array('0' => 'Bassa','1'=>'Media','2'=>'Alta');

	return $options[$value];

}

	class BrReportsController extends AppController {
	
		var $name = "BrReports";
		var $helpers = array('Backend','Javascript','Cksource');
		var $uses = array('Upload','BrReport','BrComment','BrZone','BrCategory');
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
				
				$this->set('zones', $this->BrZone->find('list', array('fields' => array('BrZone.id','BrZone.title'),'conditions'=>array('BrZone.disabled' => 0), 'order' => 'BrZone.title ASC')));
				$this->set('categories', $this->BrCategory->find('list', array('fields' => array('BrCategory.id','BrCategory.title'),'conditions'=>array('BrCategory.disabled' => 0), 'order' => 'BrCategory.title ASC')));
				$this->set('auth', array('id' => $this->Auth->user('id'), 'name' => $this->Auth->user('cognome') . ' ' . $this->Auth->user('nome'), 'email' => $this->Auth->user('username')));						
						
				if (!empty($this->data)) {
				
					$this->BrReport->set($this->data);
					
					if ($this->BrReport->save()) {
						
						$ADD_OK = true;
						
						if ($this->__adminUploadFile('brreport_id',$this->BrReport->id) == true) {
						
							$ADD_OK = true;
							
						}

						/* Inviare mail ad amministratore */
						
						$report = $this->BrReport->read(null, $this->BrReport->id);
						
						foreach(Configure::read('default_admin_email') as $email) {
						
						$this->set('report', $report);
						$this->set('link', 'http://' . $_SERVER['SERVER_NAME'] . '/admin/br_reports/index#' . $report['BrReport']['id']);
						$this->Email->to = $email;
						$this->Email->subject = Configure::read('site_name') . ' | Nuovo report: ' . $report['BrReport']['id'] . ' - ' . $report['BrReport']['title'] . ' avviato da ' . $report['BrReport']['author'];
						$this->Email->template = 'br_report_add'; 
						$this->Email->send();						
						
						}
						
						/* ------------------------------ */
													
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				
				$this->set('zones', $this->BrZone->find('list', array('fields' => array('BrZone.id','BrZone.title'),'conditions'=>array('BrZone.disabled' => 0), 'order' => 'BrZone.title ASC')));
				$this->set('categories', $this->BrCategory->find('list', array('fields' => array('BrCategory.id','BrCategory.title'),'conditions'=>array('BrCategory.disabled' => 0), 'order' => 'BrCategory.title ASC')));				
				$this->set('comments', $this->BrComment->find('all', array('order' => array('BrComment.created DESC'))));
				$this->set('auth', array('id' => $this->Auth->user('id'), 'name' => $this->Auth->user('cognome') . ' ' . $this->Auth->user('nome'), 'email' => $this->Auth->user('username')));
				
				if (empty($this->data)) {
								
					$this->data = $this->BrReport->find('first',array('conditions' => array('BrReport.id' => $id)));
				
				} else {
										
				$this->BrReport->set($this->data);
				
					if ($this->BrReport->save()) {
					
						$ADD_OK = false;		
						
						if ($this->__adminUploadFile('brreport_id',$id) == true) {
						
							$ADD_OK = false;
							
						}							
						
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					
					}
					
				}
			
			}

			function admin_countReport() {
			
				$this->layout = "ajax";
				
				$count = $this->BrReport->find('count', array(
				
					'conditions' => array('BrReport.disabled' => 0,'BrReport.user_id' => $this->Auth->user('id')),
				
				));
				
				return $count;
			
			}
	
	}
