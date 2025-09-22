<?

	class ProvasController extends AppController {
	
			var $name = "Provas";
			var $uses = array('User','Group','EmailModel','Spool','Newsletter','NewsletterConfig');
			var $components = array('Email'); 
			
			function sendMails() {
				
				$this->layout = "ajax";
				
				$spool = $this->Spool->find('all',
				
				array(
				
					'conditions' => array(
					
						'sent' => 0,
						'Spool.created <= NOW()',
						'EmailModel.disabled' => 0,
						'EmailModel.created <= NOW()',
						'Spool.error' => 0
						
					),
					'limit' => 50
				
				));
				
				foreach ($spool as $mail) {
					
							/*NEWSLETTER*/
							$mail_data = $this->EmailModel->findById($mail['EmailModel']['id']);					
					
							if($mail['EmailModel']['newsletter_id'] != 0) {
								
								$newsletter = $this->Newsletter->findById($mail['EmailModel']['newsletter_id']);
								
								$config     = $this->NewsletterConfig->find('first', array(
									'conditions' => array('NewsletterConfig.is_default' => 1),
								));

								if(!empty($config)) {
									
									$this->Email->smtpOptions = array(
									'port'=>$config['NewsletterAccount']['port'],
									'timeout'=>'30',
									'host' => $config['NewsletterAccount']['host'],
									'username'=>$config['NewsletterAccount']['username'],
									'password'=>$config['NewsletterAccount']['password'],
									'client' => 'CAKE'
									);
						
									$this->Email->delivery = 'smtp';
									$this->Email->sendAs = 'both';
									$this->Email->replyTo = $config['NewsletterAccount']['sender_mail'];
									$this->Email->from = $config['NewsletterAccount']['sender_name'] . '<' . $config['NewsletterAccount']['sender_mail'] . '>';											
									
									$this->set('disclaimer', str_replace('#unsubscribe', '/' . md5($mail['Spool']['email']), $config['NewsletterConfig']['disclaimer']));
									
								}
								
								$this->set('data', $newsletter);
								$this->set('uploads', $newsletter['Upload']);
								
							}							
							/*----------*/					
					
							$this->Spool->create();
					
							$this->data['Spool']['id'] = $mail['Spool']['id'];
							$this->data['Spool']['error'] = 0;
							
							if(substr_count($mail['Spool']['email'],'@smsviaemail.it') == 0) {//Se è un email.
					
								$this->Email->to = $mail['Spool']['email'];
								if($mail['EmailModel']['from'] != '') $this->Email->from = $mail['EmailModel']['from'];
								$this->Email->subject = $mail['EmailModel']['subject'];
								$this->Email->template = $mail['EmailModel']['layout']; 
								
							    /* Invio email con allegati (se ci sono) */
							    if(isset($mail_data['Upload']) && count($mail_data['Upload'])) {
								    $uploads = array();
									
								    foreach($mail_data['Upload'] as $upload){
								    	$uploads[$upload['name']] = APP . 'webroot/' . $upload['path'];
								    }
								    $this->Email->attachments = $uploads; 
								    $this->set('uploads', $mail_data['Upload']);
							    }
							    /*______________________________________*/								
								
								$this->set('text',$mail['EmailModel']['message']);
								$this->set('subject', $mail['EmailModel']['subject']);
								
								//if ($this->Email->send())
								if(1 == 1) 
								{
									$this->data['Spool']['sent'] = 1;
									debug(date("d/m/Y H:i:s") . ": " . $mail['Spool']['email'] . " sent");
								} 
								else 
								{
									debug(date("d/m/Y H:i:s") . ": " . $mail['Spool']['email'] . " not sent");
									$this->data['Spool']['sent'] = 0;
									$this->data['Spool']['error'] = 1;
								}
							
							} else {//Se è un sms da aimon.
							
								/*
								$to_number = str_replace('@smsviaemail.it','',$mail['Spool']['email']);
								
								if($this->sendSms(array('dest' => $to_number, 'text' => $mail['EmailModel']['subject']))) {
									$this->data['Spool']['sent'] = 1;
									debug(date("d/m/Y H:i:s") . " (sms): " . $mail['Spool']['email'] . " sent");
								} else {
									debug(date("d/m/Y H:i:s") . " (sms): " . $mail['Spool']['email'] . " not sent");
									$this->data['Spool']['sent'] = 0;
									$this->data['Spool']['error'] = 1;									
								}
								*/
							
							}
							$this->Spool->set($this->data);
							$this->Spool->save();
					
				}
				
			}
			
			/*
			
			function sendSms($options = array()) {
				
				if(!isset($options['mit'])) $options['mit'] = '';
				
				
				$text = trim($options['text']);
				$text = utf8_decode($options['text']);
				$text = substr($text,0,160);
				$text = base64_encode($text);
				
				$buffer = array(
								"authlogin" => Configure::read('options_sms_username'),
								"authpasswd" => Configure::read('options_sms_password'), 
								"sender" => base64_encode($options['mit']),
								"body" => $text,
								"destination" => $options['dest'], 
								"id_api" => Configure::read('options_sms_api')
							);
						
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://secure.apisms.it/http/send_sms"); 
				curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_POSTFIELDS, $buffer); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				$ret = curl_exec($ch); 
				curl_close($ch); # ritorno dalle api print_r($ret);	
				
				$retrn = substr_count($ret, '+01 SMS Queued');
				
				if($retrn > 0) return true;
				else           return false;
				
			}
			*/

 		
	}
