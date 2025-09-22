<?

	class NlayoutsController extends AppController {
	
			var $name = "Nlayouts";
			var $helpers = array('Backend','Javascript','Cksource');
			var $uses = array('Nlayout','Upload');
			
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

				if (!empty($this->data)) {
				
					$this->Nlayout->set($this->data);
					
					if ($this->Nlayout->save()) {
						
						$this->__adminUploadFile('newsletter_id', $this->Nlayout->id);
						$this->__updateLayout($this->Nlayout->id);
									
						$this->set('result','ADD_OK');
						$this->render('/backend/ajaxResult');
						
						
					}
					
				} else {


					$this->data['Nlayout']['content']=file_get_contents(APP . '/views/elements/email/html/defaultnl.ctp');
					

				}
				
			}
			
			function admin_edit($id) {
			
				$this->layout = "ajax";
				
				if (empty($this->data)) {
								
					$this->data = $this->Nlayout->find('first',array('conditions' => array('Nlayout.id' => $id)));
					$this->data['Nlayout']['published'] = ($this->data['Nlayout']['published_it'] != '00/00/0000')? $this->data['Nlayout']['published_it'] : '';

					$this->Nlayout->set($this->data);
				
				} else {
										
					$this->Nlayout->set($this->data);
					
					if ($this->Nlayout->save()) {
						
						$this->__adminUploadFile('nlayout_id', $id);
						
						$this->__updateLayout($id);

						$this->set('result','EDIT_OK');
						$this->render('/backend/ajaxResult');
					}
					
				}
			
			}


			private function __updateLayout($id)
			{

				$lout = $this->Nlayout->find('first', array('conditions' => array('id' => $id)));

				$fname = Inflector::slug($lout['Nlayout']['id'] . "_" . $lout['Nlayout']['title'], '_');
				$fname = strtolower(($fname));
				$pt = APP . '/views/elements/email/html/newsletter/';

				$cnt = $lout['Nlayout']['content'];


				$immagine = '<? if(!isset($uploads)) $uploads = array();
				
				foreach($uploads as $tmp) {
					if($tmp[\'isEvidenza\'] == 1 && $tmp[\'group\'] == \'image\') { $evidenza = $tmp; break; }
				}
				
				if(isset($evidenza)):
					
					?>			
				
				<img src="<?=Configure::read(\'server_name\');?><?=$thumbnail->link(array(\'path\' => $evidenza[\'path\'], \'w\' => 760, \'h\' => 450, \'zc\' => 1));?>" alt="<?=$evidenza[\'name\'];?>" />

				
				<? endif; ?>
				';

				$contenuto = '<?=$text;?>';
				$titolo = '<?=$data[\'Newsletter\'][\'title\'];?>';
				$disclaimer = '<?=strip_tags($disclaimer,\'<a>\');?>';

				$cnt = str_replace("{titolo}", $titolo, $cnt);
				$cnt = str_replace("{disclaimer}", $disclaimer, $cnt);
				$cnt = str_replace("{immagine}", $immagine, $cnt);
				$cnt = str_replace("{contenuto}", $contenuto, $cnt);


				@unlink($pt . $fname . ".ctp");
				@unlink($pt . $fname);

				file_put_contents($pt . $fname . ".ctp", $cnt);


			}
			
			function admin_send() {
				
				$this->layout = "timmybox";
				
				$groups_list = array();
				$groups = $this->NlayoutGroup->find('all', array(
				
					'conditions' => array(
					
						'NlayoutGroup.disabled'     => 0,
						'NlayoutGroup.CountUser !=' => 0,  
					
					),
					'order' => 'NlayoutGroup.CountUser DESC'
				
				));
				
				foreach($groups as $group) {
					$groups_list[$group['NlayoutGroup']['id']] = $group['NlayoutGroup']['title'] . '(' . $group['NlayoutGroup']['CountUser'] . ')';
				}
				
				$this->set('groups', $groups_list);
				
			}
			
			function admin_send_message(){
				
				$this->layout = "ajax";
				
				$newsletters = $_POST['newsletters'];
				$groups      = $_POST['groups'];
				
				foreach($newsletters as $newsletter) {
					
					$this->data = $this->Nlayout->findById($newsletter);
					
					/* Salveo msg per la newsletter */
					$this->EmailModel->create();
	
					$this->data['EmailModel']['from']    = 'noreply@playleaguesport.it';						
					$this->data['EmailModel']['subject'] = $this->data['Nlayout']['title'];
					$this->data['EmailModel']['message'] = $this->data['Nlayout']['content'];
					$this->data['EmailModel']['layout']  = $this->data['Nlayout']['layout'];
					$this->data['EmailModel']['newsletter_id'] = $this->data['Nlayout']['id'];
					$this->EmailModel->set($this->data);
					if($this->EmailModel->save()) {
						$email_id = $this->EmailModel->id;
						if(isset($this->data['Upload']) && count($this->data['Upload'])) {
							
							foreach($this->data['Upload'] as $upload) {
								
								$this->Upload->read(null, $upload['id']);
								$this->Upload->set('email_id', $email_id);
								$this->Upload->save();
								
							}
							
						}						
					}else continue;
					/* ---------------------------- */					
					
					/*
					foreach($groups as $group) {
						
						$data = $this->NlayoutGroup->findById($group);
						
						foreach($data['NlayoutUser'] as $user) {
							$email 	  = $user['email']	;
							
								$this->Spool->create();
							
								$this->data['Spool']['mail_id'] = $email_id;
								$this->data['Spool']['email']   = $email;
														
								$this->Spool->set($this->data);
								$this->Spool->save();
							
							
						}						
						
					}*/
					
					$peoples = array();			
					
					foreach($groups as $group) {
						
						$data = $this->NlayoutGroup->findById($group);
						
						foreach($data['NlayoutUser'] as $user) {
							
							$peoples[$user['id']] = $user['email'];							

						}						
						
					}
					
					foreach ($peoples as $people) {
						
						$email 	  = $people;
						/* INVIO EMAIL NEWSLETTER */
							$this->Spool->create();
						
							$this->data['Spool']['mail_id'] = $email_id;
							$this->data['Spool']['email']   = $email;
													
							$this->Spool->set($this->data);
							$this->Spool->save();
						/* -------------------- */						
						
					}					
					
				}
				
				$this->set('result', json_encode(array('msg' => 'Messaggi email inseriti nello spool e pronti per l\'invio.')));
				$this->render('/backend/ajaxResult');
				
			}	
			
			function admin_getNlayoutPreview($newsletter_id) {
				
				$this->layout = "newsletter";
				$this->set('data',$this->Nlayout->findById($newsletter_id));
				
				$config     = $this->NlayoutConfig->find('first', array(
					'conditions' => array('NlayoutConfig.is_default' => 1),
				));				
				
				if(!empty($config)) $this->set('disclaimer', $config['NlayoutConfig']['disclaimer']);
				
			}
			
			function admin_getStory($newsletter_id) {
				
				$this->layout = "timmybox";
				
				$emails = $this->EmailModel->find('list', array(
				
					'conditions' => array(
				
					'EmailModel.disabled' => 0,
					'EmailModel.newsletter_id' => $newsletter_id,
					
					),
					'fields' => array('EmailModel.id'),
				
				));
				
				$spools = $this->Spool->find('all', array(
				
					'conditions' => array(
					
						'Spool.mail_id' => $emails,
						'Spool.sent'    => 1,
					
					),
					'order' => array('Spool.modified DESC'),
				
				));
				
				$this->set('stories', $spools);
				
			}
			
			function admin_send_as_post() {
				
				$this->layout = "timmybox";
				
				$groups_list = array();
				$groups = $this->NlayoutGroup->find('all', array(
				
					'conditions' => array(
					
						'NlayoutGroup.disabled'     => 0,
						'NlayoutGroup.CountUser !=' => 0,  
					
					),
					'order' => 'NlayoutGroup.CountUser DESC'
				
				));
				
				foreach($groups as $group) {
					$groups_list[$group['NlayoutGroup']['id']] = $group['NlayoutGroup']['title'] . '(' . $group['NlayoutGroup']['CountUser'] . ')';
				}
				
				$this->set('groups', $groups_list);
				
				App::Import('Libs','Folder');
				$dir = new Folder('../views/elements/email/html/newsletter');
				$layouts = $dir->read();
				$list_layouts = $layouts[1];				
				
				foreach($list_layouts as $layout => $k) {
				
					unset($list_layouts[$layout]);
					
					$k = explode('.', $k);
					$layout = '/newsletter/' . $k[0];
					$list_layouts[$layout] = $k[0];
				
				}
								
				$this->set('layouts', $list_layouts);				
				
			}
			
			function admin_send_message_as_post($type = 'save'){
				
				$this->layout = "ajax";
				
				App::Import('Helper','Thumbnail');
				$thumbnail = new ThumbnailHelper;
				
				$newsletters = $_POST['newsletters'];
				$groups      = $_POST['groups'];
				
				/* Creo messaggio contenente i post */
				$code = '';
				
				foreach($newsletters as $newsletter) {
					$code .= $newsletter;
				}				

					$msg_newsletter = '';
					
					foreach($newsletters as $post_id) {
						
						$post = $this->BlogPost->findById($post_id);
						
						$link = 'http://'.$_SERVER['SERVER_NAME'].'/articoli/' . $post['BlogPost']['id'] . '/' . strtolower(Inflector::Slug($post['BlogPost']['title'],'-'));
						$title= $post['BlogPost']['title'];

						$text = $this->Text->truncate(
						strip_tags($post['BlogPost']['content']),
						400,
						array(
						'ending' => '...',
						'exact' => false
						)
						);
						
						$msg_newsletter .= 
						'<tr class="post-title">
							<td align="left">
								<a href="'.$link.'" title="'.$title.'">
									<h1>'.$title.'</h1>
								</a>
							</td>
						</tr>';
						
						if(!empty($post['Upload'])) {
							
							$link_img = '';
							
							foreach($post['Upload'] as $t) {
								if($t['isEvidenza']) {
									if($t['group'] == 'image') {
										$link_img = $thumbnail->link(array('path' => $t['path'], 'h' => 200, 'zc'));
									} else {
										$link_img = $thumbnail->frame_link(array('path' => $t['path'], 'h' => 200, 'zc'));
									}
								}
							}
							
							if($link_img != '') {
							
							shuffle($post['Upload']);
							$t = $post['Upload'][0];
								
							if($t['group'] == 'image') {
								$link_img = $thumbnail->link(array('path' => $t['path'], 'h' => 200, 'zc'));
							} else {
								$link_img = $thumbnail->frame_link(array('path' => $t['path'], 'h' => 200, 'zc'));
							}
							
							
							$msg_newsletter .= '
								<tr class="post-allegato">
									<td align="left">
										<a title="'.$title.'" href="'.$link.'">
											<img src="http://'.$_SERVER['SERVER_NAME'].'/'.$link_img.'" alt="'.$title.'" />
										</a>
									</td>
								</tr>								
							';
							
							}
						}
						
						//Titolo;

						$msg_newsletter .= '
						<tr class="post-message">
							<td align="left">
								<p>'.$text.'</p>
							</td>
						</tr>
						';					
					}		
					
					$this->data = $this->Nlayout->find('first', array(
						'conditions' => array(
							'Nlayout.code' => $code,
						),
					));					
					
					if(empty($this->data)) {
						
						$this_data['Nlayout']['title']   = $_POST['title'];
						$this_data['Nlayout']['content'] = $msg_newsletter;
						$this_data['Nlayout']['layout']  = $_POST['layout'];
						$this_data['Nlayout']['code']    = $code;
									
						$this->Nlayout->create();
						$this->Nlayout->set($this_data);
						$this->Nlayout->save();
						
					} else {

						$this->Nlayout->read(null, $this->data['Nlayout']['id']);
						$this->Nlayout->set('title', $_POST['title']);
						$this->Nlayout->set('content', $msg_newsletter);
						$this->Nlayout->set('layout', $_POST['layout']);
						$this->Nlayout->save();						
						
					}
					
				$msg = 'Messaggio correttamente salvato nella Nlayout.';
					
				/* -------------------------------- */
				
				if($type == 'send') {
				
				$this->data = $this->Nlayout->read(null, $this->Nlayout->id);
					
					/* Salveo msg per la newsletter */
					$this->EmailModel->create();
	
					$this->data['EmailModel']['from']    = 'newsletter@naturetica.it';						
					$this->data['EmailModel']['subject'] = $this->data['Nlayout']['title'];
					$this->data['EmailModel']['message'] = $this->data['Nlayout']['content'];
					$this->data['EmailModel']['layout']  = $this->data['Nlayout']['layout'];
					$this->data['EmailModel']['newsletter_id'] = $this->data['Nlayout']['id'];
					$this->EmailModel->set($this->data);
					if($this->EmailModel->save()) {
						$email_id = $this->EmailModel->id;
						if(isset($this->data['Upload']) && count($this->data['Upload'])) {
							
							foreach($this->data['Upload'] as $upload) {
								
								$this->Upload->read(null, $upload['id']);
								$this->Upload->set('email_id', $email_id);
								$this->Upload->save();
								
							}
							
						}						
					}
					/* ---------------------------- */					
					
					foreach($groups as $group) {
						
						$data = $this->NlayoutGroup->findById($group);
						
						foreach($data['NlayoutUser'] as $user) {
							$email 	  = $user['email']	;
							/* INVIO EMAIL NEWSLETTER */
								$this->Spool->create();
							
								$this->data['Spool']['mail_id'] = $email_id;
								$this->data['Spool']['email']   = $email;
														
								$this->Spool->set($this->data);
								$this->Spool->save();
							/* -------------------- */
							
						}						
						
					}
				
					$msg = 'Messaggi email inseriti nello spool e pronti per l\'invio.';
					
				}
				
				$this->set('result', json_encode(array('msg' => $msg)));
				$this->render('/backend/ajaxResult');
				
			}
			
			function unsubscribe($email = null) {
				
				$this->layout = "newsletter";
				
				if(!isset($email)) $this->redirect('/');
				
			}	
			
			function unsubscribe_confirm($email = null) {
				
				$this->layout = "newsletter";
				
				if(!isset($email)) $this->redirect('/');
				
				$data = $this->NlayoutUser->find('first', array(
				
					'conditions' => array('md5(NlayoutUser.email)' => $email),
				
				));
				
				if($this->NlayoutUser->delete($data['NlayoutUser']['id'])) {
					
					$this->set('msg', 'La tua cancellazione da ' . $_SERVER['SERVER_NAME'] . ' è andata a buon fine.');
					
				} else {
					
					$this->set('msg', 'Impossibile cancellare, utente inesistente.');
					
				}
 				
			}	
			
			function updateAthleteGroup() {
			
				$this->layout = null;
				$this->autoRender = false;
				
				$group_id = 3;
							
							$this->Athlete->recursive = 0;
				$athletes = $this->Athlete->find('all', array(
				
					'fields' => array('Athlete.Atleta', 'Athlete.Email', 'Athlete.Nome', 'Athlete.Cognome'),
					'conditions' => array(
						'Athlete.email !=' => '',
						'Athlete.newsletter_disabled' => 1
					)
				
				));
				
				if(empty($athletes)) {
				
					debug('Nessun\'atleta da inserire');
					exit;
				
				}
				
				$validation =& Validation::getInstance();
				
				$atleti_presenti = 0;
				$atleti_inseriti = 0;
				
				foreach($athletes as $key => $athlete) {
				
					$email = filter_var($athlete['Athlete']['Email'], FILTER_SANITIZE_EMAIL);
					$user  = $this->NlayoutUser->findByEmail($email);
					
					if(empty($user) && $validation->email($email)) {
					
						$this->NlayoutUser->create();
						$this->NlayoutUser->set('email', $email);
						$this->NlayoutUser->set('name', $athlete['Athlete']['Nome']);
						$this->NlayoutUser->set('surname', $athlete['Athlete']['Cognome']);		
						
						if($this->NlayoutUser->save()) {
						
							/*
							$this->Athlete->read(null, $athlete['Athlete']['Atleta']);
							$this->Athlete->set('newsletter_disabled', 0);
							
							$this->Athlete->unbindValidation('remove', array('password','password_confirm'), true);							
							
							if(!$this->Athlete->save()) {
								debug($this->Athlete->invalidFields());
								continue;
							}
							*/
							
							$this->Athlete->query('UPDATE Atleti SET newsletter_disabled = 0 WHERE Atleta = ' . $athlete['Athlete']['Atleta']);
														
							$this->NlayoutGroupUser->create();
							$this->NlayoutGroupUser->set('newsletter_group_id', $group_id);
							$this->NlayoutGroupUser->set('newsletter_user_id', $this->NlayoutUser->id);
							
							if(!$this->NlayoutGroupUser->save()) {
								continue;
							}
							
							$atleti_inseriti++;
							debug('Atleta inserito correttamente nella newsletter');
						
						}				
					
					} else {
					
						$atleti_presenti++;
						debug('Atleta già presente nella newsletter');
						$this->Athlete->query('UPDATE Atleti SET newsletter_disabled = 0 WHERE Atleta = ' . $athlete['Athlete']['Atleta']);
					
					}
					
				
				}
				
				debug("Totale atleti: " . count($athletes));
				debug("Totale esistenti: " . $atleti_presenti);
				debug("Totale inseriti: " . $atleti_inseriti);
				
			
			}					
					
	}
	
?>