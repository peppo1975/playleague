<?


	class UsersController extends AppController {
	
			var $name = "Users";
			var $login_required = true;
			var $login_site = false;
			var $helpers = array('Backend');
			var $uses = array('User','Group','Athlete','Yearbook','AnniSportivi','Squadre','SquadreCampionati','AnniSportivi','Upload');
			var $components = array('Password', 'RequestHandler', 'Email','ControllerList'); 
			
			function beforeFilter() {
		
				parent::beforeFilter();
				
				switch ($this->action) {
					
					case 'admin_recover':
					case 'admin_reset':
					case 'admin_generatepwd':
					
					
						if (!$this->Auth->user()) $this->Auth->allow('*');
					
					break;
					
				}
					
			}
			
			function admin_create_rights() {
			
				$list = array();
				
				$controllers = $this->ControllerList->get();
				
				foreach($controllers as $controller => $action) {
				
					$list[$controller] = $controller;
				
				}
				
				$groups = $this->Group->find('list', array(
				
					'fields' => array('Group.id','Group.nome'),
					'conditions' => array(
					
						'Group.nome !=' => 'Amministratore'
					
					),
				
				));
				
				$this->set('groups', $groups);
				$this->set('controllers', $list);
				
				if(!empty($this->data)) {
				
					if(!empty($this->data['Right']['action'])) {
					
						$this->data['Right']['resource'] = $this->data['Right']['resource'].'|'.$this->data['Right']['action'];
					
					}
					
					$this->Right->create();
					$this->Right->set($this->data);
					$this->Right->save();
				
				}
			
			}
	
			function admin_login() {
			
			
					if (isset($this->data)) {
					
					$this->User->set($this->data);
					
					$ret = $this->User->validates();
					
	
					}	

			}
			
			function admin_index() {
				
				
					$group_id   = $this->Auth->user('group_id');
			
					if ($group_id > 1) {
						
						$this->set('conditions',array( 
						
							'User.id' => $this->Auth->user('id')
						
						));
						
					} else {
						
						$this->set('conditions',array());
						
					}
					
			}
			
			function admin_add() {
			
				$this->set('groups',$this->Group->find('all'));
				$this->layout = "ajax";	
				
				if (!empty($this->data)) {
				
					$pwd_send = $this->data['User']['password_confirm'];
					$email_to = $this->data['User']['username'];
				
					$this->data['User']['password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);

					$this->User->set($this->data);
					
					if ($this->User->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('link',"http://" . $_SERVER['SERVER_NAME'] . '/admin/');
							$this->set('anagrafica',$this->data['User']['nome'] . ' ' . $this->data['User']['cognome']);
							$this->set('user',$email_to);
							$this->set('pwd',$pwd_send);
							$this->Email->to = $email_to;
							$this->Email->subject = 'Midland Sport | Registrazione nuovo utente';
							$this->Email->template = 'user_add'; 
							$this->Email->send();
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
	
		function admin_edit($id) {
			
				$this->set('groups',$this->Group->find('all'));
				$this->layout = "ajax";
                                
                                //GIUSEPPE 2023-07-28
                                $this->set('idUtente',$id);
                                //-------------------
				
				if (empty($this->data)) {
								
				$this->data = $this->User->find('first',array('conditions' => array('User.id' => $id)));
				$this->data['User']['old_password'] = $this->data['User']['password'];
				$this->data['User']['old_username'] = $this->data['User']['username'];
				$this->data['User']['password'] = '';
				$this->User->set($this->data);
				
				} else {
				
					$pwd_send = $this->data['User']['password_confirm'];
						
					$this->data['User']['password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
					
					$this->data['User']['id'] = $id;
						
					$this->User->set($this->data);
					
					$ADD_OK = true;

						if ($this->User->save()) {
														
							if ($ADD_OK) {
							
								if($this->data['User']['username'] != $this->data['User']['old_username'] || $this->data['User']['password'] != $this->data['User']['old_password']) { 
								
									$email_to = $this->data['User']['username'];
									$this->set('link',"http://" . $_SERVER['SERVER_NAME'] . '/admin/');
									$this->set('anagrafica',$this->data['User']['nome'] . ' ' . $this->data['User']['cognome']);
									$this->set('user',$email_to);
									$this->set('pwd',$pwd_send);
									$this->Email->to = $email_to;
									$this->Email->subject = 'Midland Sport | Modifica utente';
									$this->Email->template = 'user_edit'; 
									$this->Email->send();
									
								}
								
								$this->set('result','ADD_OK');
								$this->render('/backend/ajaxResult');
							}	
						}
					
				}

				$this->loadModel('Campi');
				$this->set('campi', $this->Campi->find('list', array('fields' => ['id', 'Descrizione'], 'order' => array('Descrizione'))));
				
			}

                        //GIUSEPPE 2023-07-28
                        public function admin_readCampi($utente)
                        {
//                            echo $utente;
                            $query = "SELECT 
                                    `CampiUtenti`.*, 
                                    Campi.Descrizione 
                            FROM 
                                    `CampiUtenti` 
                                    INNER JOIN Campi ON CampiUtenti.Campo = Campi.Campo 
                            WHERE 
                                    CampiUtenti.Utente = '{$utente}'";
                                    
                            $res = $this->select_sql($query);
                            
                            echo json_encode($res);
                            
                            exit();
                        }

                        
                        public function admin_insertCampi()
                        {
                            $json = file_get_contents('php://input');
                            $post = json_decode($json, true);
                          
                            $utente = $post['Utente'];
                            $campi = $post['Campi'];
                            
                            $query = "DELETE FROM `CampiUtenti` WHERE `Utente`= '{$utente}';";
                            $this->my_query($query);
                            
                            foreach ($campi as $campo)
                            {
                                $table = "CampiUtenti";
                                $values['Utente'] = $utente;
                                $values['Campo'] = $campo;
                                $query = $this->insert_into($table, $values);
                            }
                            
                            exit();
                        }
                        //-------------------

			
			function admin_search() {
				
				$this->layout = "ajax";	
				
				$this->set('groups',$this->Group->find('all', array('order' => array('Group.nome ASC'))));
					
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
				
			}
			
			function admin_filters() {
			
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
		// function admin_reset($pw = "") {
		
		
		// $this->set("confirm",$this->User->getError('PASSWORD_CONFIRM'));
		
		
		// if (!empty($pw)) {
		
		// $this->Session->write('Reset.password',$pw);
		
		// }
		
		// if (empty($pw)) {
		
		// if ($this->Session->check('Reset.password')) $pw = $this->Session->read('Reset.password');
		
		// }
		
		// if (isset($pw) && !empty($pw)) {
		
		
		// $user = $this->User->find('first',
		
		// array(
		
		// 'conditions' => array(
		
		// 'MD5(User.id)' => $pw
		
		// )
		
		// )
		
		// );
		
		
		// if ($user != false) {
		
		// $this->set("User",$user['User']);
		
		// if (isset($this->data)) {
		
		// $this->data['User']['id'] = $user['User']['id'];
		// $this->data['User']['username'] = $user['User']['username'];
		
		// $this->User->set($this->data);
		// $ret = $this->User->validates();
		
		// if ($ret == true) {
		
		// if ($this->data['User']['password'] != $this->data['User']['cpassword']) {
		
		// $this->User->invalidate('cpassword');
		
		// $ret = false;
		
		// }
		
		// }
		
		// if ($ret == true) {
		
		// $this->data = $this->Auth->hashPasswords($this->data);
		
		// $this->User->set($this->data);
		
		// $this->render('/elements/users/recover_done');
		
		// $this->Session->delete('Reset.password');
		
		// }
		
		// }
		
		// } else {
		
		// $this->redirect('/admin/users/login');
		
		// }
		
		
		// } else {
		
		// $this->redirect('/admin/users/login');
		
		// }
		
		// }
		
		
		
		function admin_reset($pw = "") 
		{
			
			$this->set("confirm",$this->User->getError('PASSWORD_CONFIRM'));
			
			
			if (!empty($pw)) 
			{
				
				$this->Session->write('Reset.password',$pw);
				
			}
			
			if (empty($pw))
			{
				
				if ($this->Session->check('Reset.password')) $pw = $this->Session->read('Reset.password');
				
			}
			
			if (isset($pw) && !empty($pw)) 
			{
				
				
				$user = $this->User->find('first',array('conditions' => array('MD5(User.id)' => $pw)));
				
				
				if ($user != false) 
				{
					
					$this->set("User",$user['User']);
					
					if (isset($this->data))
					{
						
						$this->data['User']['id'] = $user['User']['id'];
						$this->data['User']['username'] = $user['User']['username'];
						
						$this->User->set($this->data);
						$ret = $this->User->validates();
						
						if ($ret == true) 
						{
							
							if ($this->data['User']['password'] != $this->data['User']['cpassword']) {
								
								$this->User->invalidate('cpassword');
								
								$ret = false;
								
							}
							
						}
						
						if ($ret == true) 
						{
							
							//GIUSEPPE 2017-02-11 - inserimento nuova password ------------------------------------------
							
							$update = "UPDATE `users` SET `password` = '" . $this->Auth->password($this->data['User']['cpassword']) . "' WHERE (id) = '".$this->data['User']['id']."'";
							
							mysql_query($update);
							
							//-------------------------------------------------------------------------------------------
							
							
							//$this->data = $this->Auth->hashPasswords($this->data);
							
							//$this->User->set($this->data);
							
							//$this->Session->delete('Reset.password');
							
							$this->render('/elements/users/recover_done');
							
						}
						
					}
					
				}
				else 
				{
					
					$this->redirect('/admin/users/login');
					
				}
				
			} 
			else 
			{
				
				$this->redirect('/admin/users/login');
				
			}
			
		}






		
			
			function sendRecoverMail($data) {
				

					$this->Email->to = $data['username'];
					$this->Email->subject = 'Midland Sport | Recupero password';
					$this->Email->template = 'recover'; 
					$this->Email->send();
				
			}
			
		
		function admin_recover() { //GIUSEPPE 2017-02-10 riscritta la query per interrogare la tabella users
			
			
			if (isset($this->data)) {
				
				$username = $this->data['User']['username'];
				
				$nome = $this->data['User']['nome'];
				
				$cognome = $this->data['User']['cognome'];
				
				/*$this->set('User',$user['User']);
					
				$this->set('link',"http://" . $_SERVER['SERVER_NAME'] . '/admin/users/reset/' . md5($user['User']['id']));*/
				
				$query = "SELECT COUNT(nome) as result, LOWER(cognome) as cognome, LOWER(nome) as nome, LOWER(username) as username, id FROM users WHERE LOWER(username) = '".strtolower($username)."'";
				
				$result = mysql_query($query);
				$row = array();
				
				$result_return = array();
				
				if (mysql_num_rows($result) > 0) {
					
					$row = mysql_fetch_assoc($result);
				}
				
				if($row['result']>0 && $row['cognome']==strtolower($cognome)  && $row['nome']==strtolower($nome))
				{
					
					
					$this->set('User',$row);
					$this->set('link',"http://" . $_SERVER['SERVER_NAME'] . '/admin/users/reset/' . md5($row['id']));
					$this->sendRecoverMail($row);
					$this->render('/elements/users/recover_ok');
					
				}
				else
				{
					$this->Session->setFlash('Non è stata trovata alcuna corrispondenza con i dati da lei inseriti');
				}
				
			}
			
		}
		
		// function admin_recover() {
		
		// if (isset($this->data)) {
		
		// $this->User->set($this->data);
		
		// $res = $this->User->validates();
		
		
		// if ($res == true) {
		
		// $user = 
		
		// $this->User->find('first',
		
		// array(
		// 'conditions' => 
		
		// array('LOWER(User.username)' => strtolower($this->data['User']['username']),
		// 'LOWER(User.nome)'  => strtolower($this->data['User']['nome']),
		// 'LOWER(User.cognome)' => strtolower($this->data['User']['cognome'])
		// )
		// )
		
		// );
		
		// if ($user == false) {
		
		// $this->Session->setFlash('Non è stata trovata alcuna corrispondenza con i dati da lei inseriti');
		
		// } else {
		
		
		// $this->set('User',$user['User']);
		// $this->set('link',"http://" . $_SERVER['SERVER_NAME'] . '/admin/users/reset/' . md5($user['User']['id']));
		// $this->sendRecoverMail($user['User']);
		// $this->render('/elements/users/recover_ok');
		
		
		// }
		
		// }
		
		// }
		
		// }



    //GIUSEPPE 2017-20-02 --------------------------------------


    function readSportAthlete($id, $sport)
    {

        /* $classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

          $nameClass = $classPage["Name"];

          $typeSport = array("primary"=>"CALCIO","secondary"=>"CALCIO","quaternary"=>"TENNIS");

          $sport = $typeSport[$nameClass]; */

        $output = array();

        $query = "SELECT 
                    COUNT(tessera) as num_record
                  , Annuario.Tessera
                  , Annuario.Atleta 
                  , Squadre.sport
                  FROM `Annuario` 
                  INNER JOIN SquadreCampionati
                  ON
                  Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato
                  INNER JOIN
                  Squadre
                  ON
                  SquadreCampionati.Squadra = Squadre.Squadra
                  INNER JOIN
                  Atleti
                  ON
                  Annuario.Atleta = Atleti.Atleta
                  WHERE 
                  Atleti.Atleta = '$id' 
                  AND
                  AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)
                  AND
                  Squadre.sport = '$sport'
                  ORDER BY `Squadre`.`sport`  DESC";


        $result = mysql_query($query);

        if (mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $output[] = $row['sport'];
            }
        }

        return $output;
    }

    //----------------------------------------------------------





		
			
			function admin_generatepwd() {
			
				$this->layout = "ajax";
				$this->set('result', json_encode(array('pwd' => substr(md5(time()),0,6))));
				$this->render('/backend/ajaxResult');
			
			}
			
			function signup() {
			
				$this->layout = "content";
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
							$this->set('activate_function', '/attivazione/');
							$this->Email->to = $email_to;
							$this->Email->subject = 'Midland Sport | Registrazione nuovo utente';
							$this->Email->template = 'user_add_site'; 
							$this->Email->send();
							
							$this->redirect('/registrazione/conferma');
						
						}
						
					} else {
					
						$this->data['User']['password'] = '';
						$this->data['User']['password_confirm'] = '';
					
					}
					
				}				
			
			}
			
			function signup_athlete($registra = null) {
			
				$this->layout = "content";
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
							$this->set('activate_function', '/attivazione/atleti/');
							$this->Email->to = $email_to;
							$this->Email->subject = 'Midland Sport | Registrazione nuovo utente';
							$this->Email->template = 'user_add_site'; 
							$this->Email->send();
							
							$this->redirect('/registrazione/conferma');
						
						}
						
					} else {
					
						$this->data['Athlete']['password'] = '';
						$this->data['Athlete']['password_confirm'] = '';
					
					}					
				
				}
			
			}
			
			function add_ok() {
			
				$this->layout = "content";
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
				$this->render('/users/signup_athlete_check');
				
			}
			
			function activate($id = null) {
				
				$this->layout = "content";
				
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
				
				$this->layout = "content";
				
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
			
			/* Control panel */
			
			function cp($type_user = null) {
			
				$this->layout = "content";
				
			}
			
			function cp_teams() {
			
				$this->layout = "content";
				$this->login_site = true;
				
				$data_users = $this->Session->read('Login.data');
				
				if($data_users['is_atleta']) {
				
					$data = $this->Yearbook->find('all', array(
						'conditions' => array(
							'Yearbook.Atleta' => $data_users['id'],
							'Yearbook.isAdmin' => 1,
							//'Yearbook.AnnoSportivo' => 2011,
							'Yearbook.AnnoSportivo' => $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1)),
						),
						'group' => array('SquadreCampionati.Squadra'),
					));
					
				} else {
					
					$data = array();
					
				}
				
				$this->set('data', $data);
			
			}
			
			function delete_avatar($model_id, $model){
				
				$this->layout = "ajax";
			
				$this->Upload->deleteAll(
				
					array('Upload.' . strtolower($model) . '_id' => $model_id)
				);
				
				exit;

			}
			
			function edit_profile($id,$model) {
			
				$error = false;
				$this->layout = "content";
				$this->login_site = true;
				
				if($this->Session->check('Login.data')) {
					

					$data_users = $this->Session->read('Login.data');

					/*
					if($id != $data_users['id'])
						$this->redirect('/');					
						*/
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

					// $data = $this->{$model}->find('first', array(
						
					// 		'conditions' => array(
					// 			$model . '.' . $this->{$model}->primaryKey => $id,
					// 		),
						
					// 	));
					
					// unset($data[$model]['password']);
					// unset($data[$model]['password_confirm']);

					// $this->data = array_merge($this->data, $data);
					//var_dump($this->data);


					//Get user info
					$user = $this->$model->read(null, $id);
					$pwd  = $this->data[$model]['password'];
					$cpwd = $this->data[$model]['password_confirm'];
					//

						if(isset($this->data['Upload']['percorso']['name']) && !empty($this->data['Upload']['percorso']['name'])) { 
							
							if($this->__adminUploadFile(strtolower($model) . '_id',$id)) { $this->redirect('/gestione/profilo/' . $id . '/' . $model); }
							
						} else {
		
						$return = 0;
							
							if(isset($this->data[$model]['password']) && isset($this->data[$model]['password_confirm'])) {
								
								if($model == 'User') { $this->data[$model]['password_confirm'] = $this->Auth->password($this->data[$model]['password_confirm']); }
	
								if($this->data[$model]['password'] != $this->data[$model]['password_confirm']) { 
									$this->Session->setFlash('Alcuni campi sono errati.', 'site/message/error_message');
									$error = true;
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
									if(!$error){
										$this->Session->setFlash('Alcuni campi sono errati.', 'site/message/error_message');
										$error = true;
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
									
									return false;
									
								}	 
							
								$this->$model->set($this->data);
								
								if($this->$model->save()) {
									
									$this->Session->setFlash('Atleta modificato con successo.', 'site/message/ok_message');
									$this->redirect('/gestione/profilo/' . $id . '/' . $model);
								
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
			
			
			/* ------------- */

			function admin_logout() {
				$this->Session->destroy();
				$this->redirect($this->Auth->logout());
			}
			 		
			function logout() {
				$this->redirect($this->Auth->logout());
			}
 		
 		
	}
