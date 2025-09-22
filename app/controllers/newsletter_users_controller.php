<?

	class NewsletterUsersController extends AppController {
	
			var $name = "NewsletterUsers";
			var $helpers = array('Backend');
			var $uses = array('NewsletterUser','NewsletterGroup','NewsletterGroupUser');
			
			function admin_index() {
			
			
			}
			
			function admin_exportx($i,$records,$total_pages) {
				$this->autoRender = false;
				$filename = APP . '/webroot/tmp/' . date("YmdH") . ".xls";
				if ($i == 0) {
				
					file_put_contents($filename,'<table>');
				
				}
				
				$users = $this->NewsletterUser->find('all',array(
				
					'conditions' => array(
						'NewsletterUser.disabled' => 0
					),
					'limit' => ($i*$records) . "," . $records,
					'order' => 'NewsletterUser.id DESC'
				
				));
				
				$campi = array('email','nome','cognome','compagnia','p_iva','citta','indirizzo','telefono','cellulare','fax');
				
				foreach ($users as $user) {
				
					$app = '<tr>';
					$usr = $user['NewsletterUser'];
					foreach ($usr as $key => $value) {
					
						if (in_array($key,$campi)) {
							$app .= '<td>' . $value . '</td>';
						}
					
					}
					
					$app .= '</tr>';
				
					
					file_put_contents($filename,$app,FILE_APPEND);
				
				}
				
				
				if ($i == ($total_pages-1)) {
					
					file_put_contents($filename,'</table>',FILE_APPEND);
				
				}
				
				print json_encode(array('link' => '/tmp/' . date("YmdH") . ".xls"));
			
			}
			
			function admin_countgroups($records) {
			
				$this->autoRender = false;
				
				$groups = $_POST['groups'];
			
				$total_pages = $this->NewsletterUser->find('count',array(
				
					'conditions' => array(
						'NewsletterUser.disabled' => 0,
						'NewsletterUser.id IN (SELECT newsletter_user_id FROM newsletters_groups_users WHERE newsletter_group_id IN (' . $groups . '))'
					)
				));			
			
				print ceil($total_pages/$records);
			
			}
	
			function admin_exportx2($i,$records,$total_pages) {
				$this->autoRender = false;
				$filename = APP . '/webroot/tmp/' . date("YmdH") . "_gr.xls";
				if ($i == 0) {
				
					file_put_contents($filename,'<table>');
				
				}
				$groups = $_POST['groups'];
				$users = $this->NewsletterUser->find('all',array(
				
					'conditions' => array(
						'NewsletterUser.disabled' => 0,
						'NewsletterUser.id IN (SELECT newsletter_user_id FROM newsletters_groups_users WHERE newsletter_group_id IN (' . $groups . '))'

					),
					'limit' => ($i*$records) . "," . $records,
					'order' => 'NewsletterUser.id DESC'
				
				));
				
				$campi = array('email','nome','cognome','compagnia','p_iva','citta','indirizzo','telefono','cellulare','fax');
				
				foreach ($users as $user) {
				
					$app = '<tr>';
					$usr = $user['NewsletterUser'];
					foreach ($usr as $key => $value) {
					
						if (in_array($key,$campi)) {
							$app .= '<td>' . $value . '</td>';
						}
					
					}
					
					$app .= '</tr>';
				
					
					file_put_contents($filename,$app,FILE_APPEND);
				
				}
				
				
				if ($i == ($total_pages-1)) {
					
					file_put_contents($filename,'</table>',FILE_APPEND);
				
				}
				
				print json_encode(array('link' => '/tmp/' . date("YmdH") . "_gr.xls"));
			
			}
			
			function admin_export() {
			
				$this->layout = "ajax";
			
			}
			
		
			function admin_export2() {
			
				$this->layout = "ajax";
			
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



			/* GIUSEPPE 12/06/2017 ----------------------------------------------------- */
		    function insert_group_user($id, $group)
		    {
		        $sql = "INSERT INTO `newsletters_groups_users` (`id`, `disabled`, `newsletter_group_id`, `newsletter_user_id`) VALUES (NULL, '0', '$group', '$id')";

		        mysql_query($sql);
		    }



		/* MODIFICATA DA GIUSEPPE 12/06/2017 ----------------------------------------------------- */
     	function admin_add()
	    {

	        $this->layout = "ajax";

	        $this->set('groups', $this->NewsletterGroup->find('all', array('order' => 'NewsletterGroup.title ASC')));

	        if (!empty($this->data))
	        {

	            $this->NewsletterUser->set($this->data);

	            if ($this->NewsletterUser->save())
	            {

	                $user_id = $this->NewsletterUser->id;

	                // Assegnazione gruppi a utente

	                foreach ($this->data['NewsletterUser'] as $data => $value)
	                {

	                    if (substr($data, 0, 6) == 'group_')
	                    {

	                        $this->insert_group_user($user_id, $value);

	//                        $data_replace = str_replace('group_', '', $data);
	//
	//                        $group_id = $value['group_' . $data_replace];
	//
	//                        $this->NewsletterGroupUser->create();
	//
	//                        $this->NewsletterGroupUser->set('newsletter_user_id', $user_id);
	//
	//                        $this->NewsletterGroupUser->set('newsletter_group_id', $group_id);
	//
	//                        $this->NewsletterGroupUser->save();
	                    }
	                }

	                $ADD_OK = true;

	                if ($ADD_OK)
	                {
	                    $this->set('result', 'ADD_OK');
	                    $this->render('/backend/ajaxResult');
	                }
	            }
	        }
	    }




		/* GIUSEPPE 12/06/2017 ----------------------------------------------------- */

			    function read_user_mail($email)
			    {
			        $res = array();

			        $sql = "SELECT id FROM newsletters_users WHERE email = '$email' AND email <> ''";

			        //echo $sql;

			        $result = mysql_query($sql);

			        if (mysql_num_rows($result) > 0)
			        {
			            $res['result'] = 1;
			        }
			        else
			        {
			            $res['result'] = 0;
			        }


			        echo json_encode($res);

			        exit;
			        //+return $res;
			    }




			/* GIUSEPPE 12/06/2017 ----------------------------------------------------- */
			function clear_groups($id)
			{
			        $sql = "DELETE FROM `newsletters_groups_users` WHERE `newsletter_user_id` = '$id'";

			        mysql_query($sql);
			}






			/* MODIFICATA DA GIUSEPPE 12/06/2017 ----------------------------------------------------- */
			function admin_edit($id = null)
			    {

			        $this->layout = "ajax";

			        $this->set('groups', $this->NewsletterGroup->find('all', array('order' => 'NewsletterGroup.title ASC')));

			        if (empty($this->data))
			        {

			            $this->data = $this->NewsletterUser->find('first', array('conditions' => array('NewsletterUser.id' => $id)));

			            $this->NewsletterUser->set($this->data);
			        }
			        else
			        {

			            //if ($this->NewsletterUser->save())
			            //{
			            $this->NewsletterUser->set($this->data);

			            $this->NewsletterUser->save();

			            $user_id = $this->NewsletterUser->id;

			            $this->clear_groups($user_id);

			            //$this->NewsletterGroupUser->deleteAll(array('NewsletterGroupUser.newsletter_user_id' => $user_id));
			            // Assegnazione gruppi a utente

			            foreach ($this->data['NewsletterUser'] as $data => $value)
			            {
			                if (substr($data, 0, 6) == 'group_')
			                {

			                    $this->insert_group_user($user_id, $value);

			//                        $data_replace = str_replace('group_', '', $data);
			//
			//                        $group_id = $value['group_' . $data_replace];
			//
			//                        $this->NewsletterGroupUser->create();
			//                        $this->NewsletterGroupUser->set('newsletter_user_id', $user_id);
			//                        $this->NewsletterGroupUser->set('newsletter_group_id', $group_id);
			//                        $this->NewsletterGroupUser->save();
			                }
			            }

			            //

			            $ADD_OK = true;

			            if ($ADD_OK)
			            {

			                $this->set('result', 'ADD_OK');
			                $this->render('/backend/ajaxResult');
			            }
			            //}
			        }
			    }






			
			function addUser($email) {

				$this->layout = "ajax";
				
				$this->NewsletterUser->create();
				$this->NewsletterUser->set('email', $email);
				
				if($this->NewsletterUser->save()) {
				
					$last_id = 1;
					
					$this->NewsletterGroupUser->create();
					$this->NewsletterGroupUser->set('newsletter_group_id',1);
					$this->NewsletterGroupUser->set('newsletter_user_id',$this->NewsletterUser->id);
					$this->NewsletterGroupUser->save();
				
				}else {
				
					$last_id = $this->NewsletterUser->invalidFields();
				
				}
				
				$this->set('result', json_encode(array('aggiunto' => $last_id)));
				$this->render('/backend/ajaxResult'); 
			
			}
			
			function addUserFromStore() {
			
				$this->layout = null;
				
				$email = $_REQUEST['email'];
				
				$this->NewsletterUser->create();
				$this->NewsletterUser->set('email', $email);
				
				if($this->NewsletterUser->save()) {
				
					$last_id = 1;
					
					$this->NewsletterGroupUser->create();
					$this->NewsletterGroupUser->set('newsletter_group_id',Configure::read('default_newsletter_group'));
					$this->NewsletterGroupUser->set('newsletter_user_id',$this->NewsletterUser->id);
					$this->NewsletterGroupUser->save();
				
				}else {
				
					$last_id = $this->NewsletterUser->invalidFields();
				
				}
			
				$return = "my_callback_method(" . json_encode(array('aggiunto' => $last_id)). ")";	
				print $return;
				
				exit;
				
			}			


    /* //GIUSEPPE 2020-09-01 ---------------------------------------- */




    public function admin_read_newsletter_users_xlsx()
    {
        $name_file = $_FILES['file']['tmp_name'];

        $all = $this->read_xlsx_newsletters_users($name_file);

        $newsletters_users = $all['newsletters_users'];

//        $this->write_file('_newsletters_users', $newsletters_users);

        $res = array();

        /* inserisco in newsletters_users */

        $_SESSION['newsletters_group_users'] = array();

        foreach ($newsletters_users as $maildaverificare => $value)
        {
            /* verifico la validità della mail (non l'esistenza) */

            if ($this->is_valid_mail($maildaverificare))
            {

                /* verifico se la mail è presente tra i gli utenti delle newsletter e prendo l'id */

                $is_exist_mail = $this->is_exist_mail($maildaverificare);

                if (count($is_exist_mail) > 0)
                {
                    /* cerco gli gruppi a cui è gia associato */

                    $groups = $this->search_groups($is_exist_mail['id']);
                    $is_exist_mail['old_groups'] = $groups;
                    $is_exist_mail['groups'] = array_unique($value); /* evito le ripetizioni */

                    $res['to_edit'][] = $is_exist_mail;
                }
                else
                {
                    $insert['email'] = $maildaverificare;
                    $insert['groups'] = array_unique($value); /* evito le ripetizioni */

                    $res['to_insert'][] = $insert;
                }
            }
            else
            {
                $res['not_valid'][] = $maildaverificare;
            }
        }

        $_SESSION['newsletters_group_users'] = $res;

        $_SESSION['newsletters_info_users'] = $all['user'];

//        $this->write_file('_newsletters_group_users', $res);

        include __DIR__ . '/../views/newsletter_users/admin_add_table.ctp';

        $res['table'] = $html;

        print_r(json_encode($res));

        die();
    }

    
    
    public function read_xlsx_newsletters_users($name_file)
    {
        /* solo strutture a lettura orizzontale (es gruppi newsletter) */


        include_once __DIR__ . '/../../vendors/excel/PHPExcel.php';

        if (!file_exists($name_file))
        {
            exit("Manca FILE {$name_file}");
        }

        $index_column = $this->create_index_column();


        $callStartTime = microtime(true);

        $objPHPExcel = PHPExcel_IOFactory::load($name_file);

        $keys_table = array();

        $res = array();


        for ($i = 2; $i <= 1048576; $i++)
        {

            $key = '';
            $email = '';

            $type_val = 'info_user';


            foreach ($index_column as $column)
            {
                $key = (string) $objPHPExcel->getActiveSheet()->getCell("{$column}{$i}")->getValue(); /* valori sulla prima riga */

                $key = trim($key);

                $res['user'][$i][$column] = $key;

                if ($type_val === 'info_user')
                {
                    if ($column === 'J')
                    {
                        $type_val = 'email';
                    }
                }
                if ($type_val === 'email')
                {

                    if ($column === 'J')
                    {
                        if ($key === '')
                        {
                            $type_val = 'exit';
                        }
                        else
                        {
                            $email = $key;
                            $res['newsletters_users'][$email] = array();
                        }
                    }
                    else
                    {
                        $type_val = 'groups';
                    }
                }
                if ($type_val === 'groups')
                {
                    if ($key === '')
                    {
                        unset($res['user'][$i][$column]);
                        break;
                    }

                    $res['newsletters_users'][$email][$key] = $key;
                }
            }



            if ($type_val === 'exit')
            {
                unset($res['user'][$i]);
                break;
            }
        }

//        $this->write_file("_news_letters", $res);
        //die();

        return $res;
    }



    private function is_exist_mail($maildaverificare)
    {
        $query = "
                        SELECT 
                                `id`, 
                                `email` 
                        FROM 
                                `newsletters_users` 
                        WHERE 
                                `email` = '{$maildaverificare}'      
            ";

        $res = $this->select_sql($query);

        return $res[0];
    }




    private function search_groups($id)
    {
        $query = "
            SELECT 
                    id, newsletter_group_id
            FROM 
                    `newsletters_groups_users` 
            WHERE 
                    `newsletter_user_id` = '{$id}'
                    AND `disabled` = '0';
                        ;
            ";

        $res = $this->select_sql($query);

        return $res;
    }




    public function admin_save_users_groups()
    {
//        print_r($_SESSION['newsletters_group_users']);

        if (isset($_SESSION['newsletters_group_users']['to_insert']))
        {
            $to_insert = $_SESSION['newsletters_group_users']['to_insert'];
            $this->insert_user($to_insert);
        }

        if (isset($_SESSION['newsletters_group_users']['to_edit']))
        {
            $to_edit = $_SESSION['newsletters_group_users']['to_edit'];
            $this->edit_groups($to_edit);
        }

        unset($_SESSION['newsletters_group_users']);
        unset($_SESSION['newsletters_info_users']);

        exit;
    }




    private function insert_user($to_insert)
    {
        foreach ($to_insert as $email_groups)
        {

            $email = strtolower($email_groups['email']);
            
            /* inserisco utente */
            $table = 'newsletters_users';
            
            $values = $this->add_info_user($email);
            
            $values['email'] = $email;
            $res = $this->insert_into($table, $values, true);

            /* inserisco utente e gruppo nella tabella di associazione */
            $id = $res['last_id'];
            $groups = $email_groups['groups'];
            $this->associate_groups($id, $groups);
        }
    }




    private function add_info_user($email)
    {
        $info_users = $_SESSION['newsletters_info_users'];

        $res = array();

        $convert['A'] = 'name';
        $convert['B'] = 'surname';
        $convert['C'] = 'company';
        $convert['D'] = 'piva';
        $convert['E'] = 'city';
        $convert['F'] = 'address';
        $convert['G'] = 'tel';
        $convert['H'] = 'cel';
        $convert['I'] = 'fax';

        foreach ($info_users as $info_user)
        {
            if ($info_user['J'] === $email)
            {
                foreach ($convert as $key=>$value)
                {
                    $res[$value] = $info_user[$key];
                }
            }
        }
        
//        $this->write_file("_$email", $res);
        
        return $res;
    }




    private function associate_groups($id, $groups)
    {
        $table = 'newsletters_groups_users';
        $values['newsletter_user_id'] = $id;
        foreach ($groups as $key => $id_group)
        {
            $values['newsletter_group_id'] = $id_group;
            $this->insert_into($table, $values);
        }
    }




    private function edit_groups($to_edit)
    {
        foreach ($to_edit as $user)
        {
            $id = $user['id'];

            /* cancello i gruppi associati all'id utente */

            $query = " 
                DELETE FROM 
                        `newsletters_groups_users` 
                WHERE 
                        `newsletter_user_id` = '{$id}'
                                ";

            $this->my_query($query);

            $groups = $user['groups'];

            /* inserisco utente e gruppo nella tabella di associazione */
            $this->associate_groups($id, $groups);
        }
    }




    /* //END GIUSEPPE ----------------------------------------------- */






										
	}
	
?>