<?

	class BrCommentsController extends AppController {
	
		var $name = "BrComments";
		var $helpers = array('Backend','Javascript','Cksource');
		var $uses = array('Upload','BrReport','BrComment','BrZone','BrCategory');
		var $login_required = true;
		
		function admin_ajax_add() {
		
			$this->layout = "ajax";
			
			$this->BrComment->create();
			$this->BrComment->set($this->data);
			
			if($this->BrComment->save()) {
			
				$data = $this->BrComment->read(null, $this->BrComment->id);
				$error= 0;
				
				/* Invio email agli admin o all'utente */
				
				$report = $this->BrReport->read(null, $data['BrComment']['report_id']);
				
				if($this->data['BrComment']['email'] == $report['BrReport']['email']) {// Invia agli admin
				
					foreach(Configure::read('default_admin_email') as $email) {
					
						$this->set('report', $report);
						$this->set('comment', $data);
						$this->set('link', 'http://' . $_SERVER['SERVER_NAME'] . '/admin/br_reports/index#' . $report['BrReport']['id']);
						$this->Email->to = $email;
						$this->Email->subject = Configure::read('site_name') . ' | Commento report: ' . $report['BrReport']['id'] . ' - ' . $report['BrReport']['title'] . ' da parte di ' . $data['BrComment']['author'];
						$this->Email->template = 'br_comment_send'; 
						$this->Email->send();
					
					}

				} else {// Invia all'utente 
				
						$this->set('report', $report);
						$this->set('comment', $data);		
						$this->set('link', 'http://' . $_SERVER['SERVER_NAME'] . '/admin/br_reports/index#' . $report['BrReport']['id']);						
						$this->Email->to = $report['BrReport']['email'];
						$this->Email->subject = Configure::read('site_name') . ' | Commento report: ' . $report['BrReport']['id'] . ' - ' . $report['BrReport']['title'] . ' da parte di ' . $data['BrComment']['author'];
						$this->Email->template = 'br_comment_send'; 
						$this->Email->send();					
				
				}
				
				/* ---------------------- */				

				
			} else {
			
				$data = $this->BrComment->invalidFields();
				$error = 1;
			
			}
			
			$this->set('result', json_encode(array('data' => $data, 'error' => $error)));
			$this->render('/backend/ajaxResult');
		
		}		
		
	}

