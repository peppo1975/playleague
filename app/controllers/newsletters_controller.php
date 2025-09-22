<?

class NewslettersController extends AppController
{

	var $name = "Newsletters";
	var $helpers = array('Backend', 'Javascript', 'Cksource');
	var $uses = array('Newsletter', 'Upload', 'NewsletterGroup', 'EmailModel', 'Spool', 'NewsletterConfig', 'NewsletterUser', 'Athlete', 'NewsletterGroupUser', 'Nlayout', 'Campionati', 'Match', 'Yearbook');


	function admin_index()
	{
		
	}


	function admin_filters()
	{

		$this->layout = "ajax";

		if (!empty($this->data))
		{

			$this->Session->write($this->name . ".searchFilters", $this->data['searchFilters']);
			$this->set('result', 'RELOAD_OK');
			$this->render('/backend/ajaxResult');
		}

	}

	function admin_search()
	{

		$this->layout = "ajax";

		if (!empty($this->data))
		{

			$this->Session->write($this->name . ".searchData", $this->data);
			$this->set('result', 'RELOAD_OK');
			$this->render('/backend/ajaxResult');
		}

		if ($this->Session->check($this->name . ".searchData", $this->data))
		{

			$this->data = $this->Session->read($this->name . ".searchData");
		}

	}
	function admin_add()
	{

		$this->layout = "ajax";

		if (!empty($this->data))
		{

			$this->Newsletter->set($this->data);

			if ($this->Newsletter->save())
			{

				$this->__adminUploadFile('newsletter_id', $this->Newsletter->id);
				
				$this->__getLayouts();

				$this->set('result', 'ADD_OK');
				$this->render('/backend/ajaxResult');
			}
		}
		else
		{
			$this->__getLayouts();
		}

	}

	function admin_edit($id)
	{

		$this->layout = "ajax";


		if (empty($this->data))
		{

			$this->data = $this->Newsletter->find('first', array('conditions' => array('Newsletter.id' => $id)));
			$this->data['Newsletter']['published'] = ($this->data['Newsletter']['published_it'] != '00/00/0000') ? $this->data['Newsletter']['published_it'] : '';

			$this->Newsletter->set($this->data);
			$this->__getLayouts();
		}
		else
		{

			$this->Newsletter->set($this->data);

			if ($this->Newsletter->save())
			{

				$this->__adminUploadFile('newsletter_id', $id);

				$this->__getLayouts(true);

				$this->set('result', 'EDIT_OK');
				$this->render('/backend/ajaxResult');

			}

		}

	}

	private function __getLayouts($update = false)
	{

		// App::Import('Libs', 'Folder');
		// $dir = new Folder('../views/elements/email/html/newsletter');
		// $layouts = $dir->read();
		// $list_layouts = $layouts[1];

		// foreach ($list_layouts as $layout => $k)
		// {

		// 	unset($list_layouts[$layout]);

		// 	$k = explode('.', $k);
		// 	$layout = '/newsletter/' . $k[0];
		// 	if ($k[0] == "default")
		// 		$list_layouts[$layout] = $k[0];
		// }

		$list_layouts = [21 => 'default'];

		$nlayouts = $this->Nlayout->find('all', array('conditions' => array('disabled' => 0)));

		foreach ($nlayouts as $lout)
		{

			$fname = Inflector::slug($lout['Nlayout']['id'] . "_" . $lout['Nlayout']['title'], '_');
			$fname = strtolower(($fname));

			if($update) {
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

			$list_layouts['/newsletter/' . $fname] = $lout['Nlayout']['title'];
		}

		// Commentato perché non veniva usato
		$this->set('layouts', $list_layouts);
	}

	function admin_send()
	{

		$this->layout = "timmybox";

		$groups_list = array();
		$groups = $this->NewsletterGroup->find('all', array(
			'conditions' => array(
				'NewsletterGroup.disabled' => 0,
				'NewsletterGroup.CountUser !=' => 0,
			),
			'order' => 'NewsletterGroup.CountUser DESC'
		));

		foreach ($groups as $group)
		{
			$groups_list[$group['NewsletterGroup']['id']] = $group['NewsletterGroup']['title'] . '(' . $group['NewsletterGroup']['CountUser'] . ')';
		}

		$this->set('groups', $groups_list);

	}

	function admin_send_message($forcedParams = [])
	{

		$this->layout = "ajax";

		$postdata = json_encode(!empty($forcedParams) ? $forcedParams : $_POST);
		//mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
		//mysql_select_db("MidlandDev2016");
		$postdata = @mysql_real_escape_string($postdata);

		mysql_query("INSERT INTO timmy_queue (data,status) VALUES ('$postdata',0)");



		//GIUSEPPE aggiunta la funzione gia esistente ----------
		/* $result = mysql_query("SELECT id FROM `timmy_spools` WHERE sent = 0");
		$coda = mysql_num_rows($result); */

		$coda = $this->send_message(); //return del numero di inserimenti
		$this->set('result', json_encode(array('msg' => $coda . ' Messaggi email in coda nello spool e pronti per l\'invio.')));
		// -----------------------------------------------------

		$this->render('/backend/ajaxResult');

	}





    function send_message()
    {

        $this->autoRender = false;

        ignore_user_abort(true);

        set_time_limit(0);

        $status1 = mysql_query("SELECT * FROM timmy_queue WHERE status = 1");

        /* if (mysql_num_rows($status1))
          exit; */

        //GIUSEPPE 2017-07-02 quando trovava questa condizione la riteneva non buona e usciva dal codice.
        // Adesso questa condizione non buona la cancella
        if (mysql_num_rows($status1))
        {
            mysql_query("DELETE FROM `timmy_queue` WHERE status = 1");
        }




        $data = mysql_query("SELECT * FROM timmy_queue WHERE status = 0 ORDER BY id ASC LIMIT 1");

        /* if (!mysql_num_rows($data))
          exit; */

        //GIUSEPPE 2017-07-02 quando trovava questa condizione e la riteneva non buona e usciva dal codice.
        // adesso esce dalla funzione e continua col codice
        if (!mysql_num_rows($data))
            return;

        $postdata = mysql_fetch_assoc($data);

        $postdata_id = $postdata['id'];

        mysql_query("UPDATE timmy_queue SET status = 1 WHERE id = $postdata_id");
        $postdata = $postdata['data'];
        $postdata = json_decode($postdata, TRUE);

        $newsletters = $postdata['newsletters'];
        $groups = $postdata['groups'];

        foreach ($newsletters as $newsletter)
        {

            $this->data = $this->Newsletter->findById($newsletter);

            /* Salveo msg per la newsletter */
            $this->EmailModel->create();

            $this->data['EmailModel']['from'] = 'noreply@playleaguesport.it';
            $this->data['EmailModel']['subject'] = $this->data['Newsletter']['title'];
            $this->data['EmailModel']['message'] = $this->data['Newsletter']['content'];
            $this->data['EmailModel']['layout'] = $this->data['Newsletter']['layout'];
            $this->data['EmailModel']['newsletter_id'] = $this->data['Newsletter']['id'];
            $this->EmailModel->set($this->data);
            if ($this->EmailModel->save())
            {
                $email_id = $this->EmailModel->id;
                if (isset($this->data['Upload']) && count($this->data['Upload']))
                {

                    foreach ($this->data['Upload'] as $upload)
                    {

                        $this->Upload->read(null, $upload['id']);
                        $this->Upload->set('email_id', $email_id);
                        $this->Upload->save();
                    }
                }
            }
            else
                continue;
            /* ---------------------------- */

            $peoples = array();

            foreach ($groups as $group)
            {

                $data = $this->NewsletterGroup->findById($group);

                foreach ($data['NewsletterUser'] as $user)
                {

                    $peoples[$user['id']] = $user['email'];
                }
            }

            // test giuseppe ----
            /* $handle = fopen(APP . 'webroot/files/json/json_newsletter.json', "w+");
              fwrite($handle, json_encode($peoples));
              fclose($handle); */
            // ----


            $insert_all = array();


            foreach ($peoples as $people)
            {
                $email = $people;

                /* INVIO EMAIL NEWSLETTER */
                /* $this->Spool->create();
                  $this->data['Spool']['mail_id'] = $email_id;
                  $this->data['Spool']['email'] = $email;
                  $this->Spool->set($this->data);
                  $this->Spool->save();
                 * COMMENTATO DA GIUSEPPE
                 */
                /* -------------------- */

                //GIUSEPPE 2017-07-07 - - - - - - -
                //anzichè fare l'iserimento 1 a 1, lo faccio a blocchi di 100 (con la funzione '$this->insert_spools')
                //in questo modo stresso meno il database e velocizzo l'inserimento.
                //per inserire tutti i gruppi (per una singloa news letter) si è passati da qualche minuto (10-15 con il rischio di blocco) a circa 15 secondi)
                //per inserire tutti i gruppi (per una 10 news letter) il tempo è stato di circa 3 minuti senza nessuna perdita
                $info['email'] = $email;
                $info['mail_id'] = $email_id;
                $insert_all[] = $info;
            }

            $this->insert_spools($insert_all, "all");
        }

        mysql_query("UPDATE timmy_queue SET status = 2 WHERE id = $postdata_id");

        //GIUSEPPE 2017-07-06 - controllo doppioni email nello spool (per sbaglio si possono generare due volte la stessa newsletter ad un utente)- - - - - - -
        //$array_all = $this->read_all();

        $this->read_unique();

        return 0;

        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

    }









    function read_unique() //GIUSEPPE 2018-09-03
    {

        do
        {
            //$return = array();
            //trovo i doppioni nello spool

            $sql = "SELECT id, email, mail_id, count(*) as num FROM timmy_spools GROUP BY email, mail_id  HAVING num > 1 ORDER BY email DESC";

            $result = mysql_query($sql);

            $filter = array();

            $array_filter = array();

            $count = 1;

            $num_or = 0;

            if (mysql_num_rows($result) > 0)
            {

                while ($row = mysql_fetch_assoc($result))
                {
                    $id = $row['id'];

                    $return[$id] = $id;

                    $array_filter[$count][] = "(id = '$id')";

                    $num_or = 100;

                    if (mysql_num_rows($result) < 100)
                    {
                        $num_or = mysql_num_rows($result);
                    }

                    if (count($array_filter[$count]) % $num_or == 0)
                    {
                        $filter[$count] = implode(" OR ", $array_filter[$count]);

                        $count++;
                    }
                }
            }
            else
            {
                break;
            }

            //print_r($filter);

            foreach ($filter as $key => $delete)
            {
                $sql = "DELETE FROM `timmy_spools` WHERE ($delete)";

                mysql_query($sql);

                unset($filter[$key]);
            }
        }
        while (true);
        
        return true;

    }



//--------------------------------------------------------
    function read_unique_user() //GIUSEPPE 2018-10-11
    {

        do
        {
           
            //trovo i doppioni di email in 'newsletters_users'

            $sql = "SELECT id, email, count(*) as num FROM newsletters_users GROUP BY email HAVING num > 1 ORDER BY email DESC";

            $result = mysql_query($sql);

            $filter = array();

            $array_filter = array();

            $count = 1;

            $num_or = 0;

            if (mysql_num_rows($result) > 0)
            {

                while ($row = mysql_fetch_assoc($result))
                {
                    $id = $row['id'];

                    $return[$id] = $id;

                    $array_filter[$count][] = "(id = '$id')";

                    $num_or = 100;

                    if (mysql_num_rows($result) < 100)
                    {
                        $num_or = mysql_num_rows($result);
                    }

                    if (count($array_filter[$count]) % $num_or == 0)
                    {
                        $filter[$count] = implode(" OR ", $array_filter[$count]);

                        $count++;
                    }
                }
            } else
            {
                break;
            }

            //print_r($filter);

            foreach ($filter as $key => $delete)
            {
                $sql = "DELETE FROM `newsletters_users` WHERE ($delete)";

                mysql_query($sql);

                unset($filter[$key]);
            }
        } while (true);

        
        echo "duplicati eliminati";
        
        exit;
    }





//--------------------------------------------------------





	private function clear_all($id_min)
	{
		$sql = "DELETE FROM `timmy_spools` WHERE `timmy_spools`.`id`>= " . $id_min;

		$result = mysql_query($sql);

	}

	private function insert_spools($array_records, $type)
	{
		$array_insert = array();

		$array_query = array();

		$string_insert = "INSERT INTO `timmy_spools` (`id`, `email`, `sent`, `created`, `modified`, `mail_id`, `error`) VALUES ";

		foreach ($array_records as $id => $single_record)
		{
			$email = "";

			$created = "";

			$modified = "";

			$mail_id = "";

			$record = "";

			if ($type == "unique")
			{
				$email = $single_record['email'];
				$created = $single_record['created'];
				$modified = $single_record['modified'];
				$mail_id = $single_record['mail_id'];
				$record = "(NULL, '$email', '0', '$created', '$modified', '$mail_id', '0')";
			}
			else if ($type == "all")
			{
				$email = $single_record['email'];
				$mail_id = $single_record['mail_id'];
				$record = "(NULL, '$email', '0', NOW(), NOW(), '$mail_id', '0')";
			}




			$array_insert[] = $record;

			if ($id > 0 && $id % 99 == 0) // ogni 100 elementi creo una query
			{
				$values = implode(',', $array_insert);

				$array_query[] = $string_insert . $values . ";";

				$array_insert = array();
			}
		}


		if (count($array_insert))
		{
			$values = implode(',', $array_insert);
			$array_query[] = $string_insert . $values . ";";
		}
		// test GIUSEPPE
		/*$handle = fopen(APP . 'webroot/files/json/json_newsletter_unique.json', "w+");
		fwrite($handle, json_encode($array_query));
		fclose($handle);
		file_get_contents('http://151.50.88.10/test_http_get/index.php?val=2');*/

		foreach ($array_query as $sql)//inseirmento iterativo
		{
			mysql_query($sql);
		}

	}


	function admin_getNewsletterPreview($newsletter_id)
	{

		$this->layout = "newsletter";
		$nl = $this->Newsletter->findById($newsletter_id);
		$this->set('data', $this->Newsletter->findById($newsletter_id));


		$config = $this->NewsletterConfig->find('first', array(
			'conditions' => array('NewsletterConfig.is_default' => 1),
		));

		if (substr_count($nl['Newsletter']['layout'], "forum"))
		{


			$config = $this->NewsletterConfig->findById(3);
		}


		if (substr_count($nl['Newsletter']['layout'], "yourgame"))
		{


			$config = $this->NewsletterConfig->findById(4);
		}


		if (!empty($config))
			$this->set('disclaimer', $config['NewsletterConfig']['disclaimer']);

	}

	function admin_getStory($newsletter_id)
	{

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
				'Spool.sent' => 1,
			),
			'order' => array('Spool.modified DESC'),
		));

		$this->set('stories', $spools);

	}

	function admin_send_as_post()
	{

		$this->layout = "timmybox";

		$groups_list = array();
		$groups = $this->NewsletterGroup->find('all', array(
			'conditions' => array(
				'NewsletterGroup.disabled' => 0,
				'NewsletterGroup.CountUser !=' => 0,
			),
			'order' => 'NewsletterGroup.CountUser DESC'
		));

		foreach ($groups as $group)
		{
			$groups_list[$group['NewsletterGroup']['id']] = $group['NewsletterGroup']['title'] . '(' . $group['NewsletterGroup']['CountUser'] . ')';
		}

		$this->set('groups', $groups_list);

		App::Import('Libs', 'Folder');
		$dir = new Folder('../views/elements/email/html/newsletter');
		$layouts = $dir->read();
		$list_layouts = $layouts[1];

		foreach ($list_layouts as $layout => $k)
		{

			unset($list_layouts[$layout]);

			$k = explode('.', $k);
			$layout = '/newsletter/' . $k[0];
			$list_layouts[$layout] = $k[0];
		}

		$this->set('layouts', $list_layouts);

	}

	function admin_send_message_as_post($type = 'save')
	{

		$this->layout = "ajax";

		App::Import('Helper', 'Thumbnail');
		$thumbnail = new ThumbnailHelper;

		$newsletters = $_POST['newsletters'];
		$groups = $_POST['groups'];

		/* Creo messaggio contenente i post */
		$code = '';

		foreach ($newsletters as $newsletter)
		{
			$code .= $newsletter;
		}

		$msg_newsletter = '';

		foreach ($newsletters as $post_id)
		{

			$post = $this->BlogPost->findById($post_id);

			$link = 'http://' . $_SERVER['SERVER_NAME'] . '/articoli/' . $post['BlogPost']['id'] . '/' . strtolower(Inflector::Slug($post['BlogPost']['title'], '-'));
			$title = $post['BlogPost']['title'];

			$text = $this->Text->truncate(
				strip_tags($post['BlogPost']['content']), 400, array(
					'ending' => '...',
					'exact' => false
				)
			);

			$msg_newsletter .= '<tr class="post-title">
			<td align="left">
			<a href="' . $link . '" title="' . $title . '">
			<h1>' . $title . '</h1>
			</a>
			</td>
			</tr>';

			if (!empty($post['Upload']))
			{

				$link_img = '';

				foreach ($post['Upload'] as $t)
				{
					if ($t['isEvidenza'])
					{
						if ($t['group'] == 'image')
						{
							$link_img = $thumbnail->link(array('path' => $t['path'], 'h' => 200, 'zc'));
						}
						else
						{
							$link_img = $thumbnail->frame_link(array('path' => $t['path'], 'h' => 200, 'zc'));
						}
					}
				}

				if ($link_img != '')
				{

					shuffle($post['Upload']);
					$t = $post['Upload'][0];

					if ($t['group'] == 'image')
					{
						$link_img = $thumbnail->link(array('path' => $t['path'], 'h' => 200, 'zc'));
					}
					else
					{
						$link_img = $thumbnail->frame_link(array('path' => $t['path'], 'h' => 200, 'zc'));
					}


					$msg_newsletter .= '
					<tr class="post-allegato">
					<td align="left">
					<a title="' . $title . '" href="' . $link . '">
					<img src="http://' . $_SERVER['SERVER_NAME'] . '/' . $link_img . '" alt="' . $title . '" />
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
			<p>' . $text . '</p>
			</td>
			</tr>
			';
		}

		$this->data = $this->Newsletter->find('first', array(
			'conditions' => array(
				'Newsletter.code' => $code,
			),
		));

		if (empty($this->data))
		{

			$this_data['Newsletter']['title'] = $_POST['title'];
			$this_data['Newsletter']['content'] = $msg_newsletter;
			$this_data['Newsletter']['layout'] = $_POST['layout'];
			$this_data['Newsletter']['code'] = $code;

			$this->Newsletter->create();
			$this->Newsletter->set($this_data);
			$this->Newsletter->save();
		}
		else
		{

			$this->Newsletter->read(null, $this->data['Newsletter']['id']);
			$this->Newsletter->set('title', $_POST['title']);
			$this->Newsletter->set('content', $msg_newsletter);
			$this->Newsletter->set('layout', $_POST['layout']);
			$this->Newsletter->save();
		}

		$msg = 'Messaggio correttamente salvato nella Newsletter.';

		/* -------------------------------- */

		if ($type == 'send')
		{

			$this->data = $this->Newsletter->read(null, $this->Newsletter->id);

			/* Salveo msg per la newsletter */
			$this->EmailModel->create();

			$this->data['EmailModel']['from'] = 'newsletter@naturetica.it';
			$this->data['EmailModel']['subject'] = $this->data['Newsletter']['title'];
			$this->data['EmailModel']['message'] = $this->data['Newsletter']['content'];
			$this->data['EmailModel']['layout'] = $this->data['Newsletter']['layout'];
			$this->data['EmailModel']['newsletter_id'] = $this->data['Newsletter']['id'];
			$this->EmailModel->set($this->data);
			if ($this->EmailModel->save())
			{
				$email_id = $this->EmailModel->id;
				if (isset($this->data['Upload']) && count($this->data['Upload']))
				{

					foreach ($this->data['Upload'] as $upload)
					{

						$this->Upload->read(null, $upload['id']);
						$this->Upload->set('email_id', $email_id);
						$this->Upload->save();
					}
				}
			}
		// else
		// 	continue;
			/* ---------------------------- */

			foreach ($groups as $group)
			{

				$data = $this->NewsletterGroup->findById($group);

				foreach ($data['NewsletterUser'] as $user)
				{
					$email = $user['email'];
					/* INVIO EMAIL NEWSLETTER */
					$this->Spool->create();

					$this->data['Spool']['mail_id'] = $email_id;
					$this->data['Spool']['email'] = $email;

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







	function unsubscribe($email = null)
	{

		$this->layout = "newsletter";

		if (!isset($email))
			$this->redirect('/');

	}

	function unsubscribe_confirm($email = null)
	{

		$this->layout = "newsletter";

		if (!isset($email))
			$this->redirect('/');

		$data = $this->NewsletterUser->find('first', array(
			'conditions' => array('md5(NewsletterUser.email)' => $email),
		));

		if ($this->NewsletterUser->delete($data['NewsletterUser']['id']))
		{

			$this->set('msg', 'La tua cancellazione da ' . $_SERVER['SERVER_NAME'] . ' è andata a buon fine.');
		}
		else
		{

			$this->set('msg', 'Impossibile cancellare, utente inesistente.');
		}

	}

	function updateAthleteGroup()
	{

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

		if (empty($athletes))
		{

			debug('Nessun\'atleta da inserire');
			exit;
		}

		$validation = & Validation::getInstance();

		$atleti_presenti = 0;
		$atleti_inseriti = 0;

		foreach ($athletes as $key => $athlete)
		{

			$email = filter_var($athlete['Athlete']['Email'], FILTER_SANITIZE_EMAIL);
			$user = $this->NewsletterUser->findByEmail($email);

			if (empty($user) && $validation->email($email))
			{

				$this->NewsletterUser->create();
				$this->NewsletterUser->set('email', $email);
				$this->NewsletterUser->set('name', $athlete['Athlete']['Nome']);
				$this->NewsletterUser->set('surname', $athlete['Athlete']['Cognome']);

				if ($this->NewsletterUser->save())
				{

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

					$this->NewsletterGroupUser->create();
					$this->NewsletterGroupUser->set('newsletter_group_id', $group_id);
					$this->NewsletterGroupUser->set('newsletter_user_id', $this->NewsletterUser->id);

					if (!$this->NewsletterGroupUser->save())
					{
						continue;
					}

					$atleti_inseriti++;
					debug('Atleta inserito correttamente nella newsletter');
				}
			}
			else
			{

				$atleti_presenti++;
				debug('Atleta già presente nella newsletter');
				$this->Athlete->query('UPDATE Atleti SET newsletter_disabled = 0 WHERE Atleta = ' . $athlete['Athlete']['Atleta']);
			}
		}

		debug("Totale atleti: " . count($athletes));
		debug("Totale esistenti: " . $atleti_presenti);
		debug("Totale inseriti: " . $atleti_inseriti);

	}

	public function cronAggiornaListe()
	{
		$this->updateAtletiCalcioSettimanali();
		$this->updateAllAthletesGroups();
	}

	public function updateAllAthletesGroups()
	{
		// 17	Tesserati stagione corrente (Calcio)
		$this->_updateAtletiNewsletter(17, CURRENT_YEAR, 'CALCIO');

		// 18	Tesserati stagione precedente (Calcio)
		$this->_updateAtletiNewsletter(18, CURRENT_YEAR - 1, 'CALCIO');

		// 19	Tesserati stagione corrente (scuola C5)
		$this->_updateAtletiNewsletter(19, CURRENT_YEAR, 'C5');

		// 20	Tesserati stagione precedente (scuola C5)
		$this->_updateAtletiNewsletter(20, CURRENT_YEAR - 1, 'C5');

		// 21	Tesserati stagione corrente (Tennis)
		$this->_updateAtletiNewsletter(21, CURRENT_YEAR, 'TENNIS');

		// 22	Tesserati stagione precedente (Tennis)
		$this->_updateAtletiNewsletter(22, CURRENT_YEAR - 1, 'TENNIS');

		// 23	Tutti i tesserati (Calcio)
		$this->_updateAtletiNewsletter(23, false, 'CALCIO');

		// 24	Tutti i tesserati (Scuola C5)
		$this->_updateAtletiNewsletter(24, false, 'C5');

		// 25	Tutti i tesserati (Tennis)
		$this->_updateAtletiNewsletter(25, false, 'TENNIS');

		// 26	Tutti i tesserati
		$this->_updateAtletiNewsletter(26);

	}

	/**
	 * Aggiorna le liste 
	 * Accetta l'id del gruppo email da aggiornare, l'anno sportivo e lo sport. Se presente filtra, altrimeni li mette tutti, escludendo i duplicati
	 */
	protected function _updateAtletiNewsletter($groupId, $annoSportivo = false, $sport = false)
	{
		$this->autoRender = false;

		$conditions = [];

		if(!empty($annoSportivo))
			$conditions[] = "Campionati.AnnoSportivo = $annoSportivo";

		if($sport == 'CALCIO'){
			$conditions[] = "Campionati.scuola != 1";
			$conditions[] = "Campionati.sport = 'CALCIO'";
		}
		elseif($sport == 'C5'){
			$conditions[] = "Campionati.scuola = 1";
			$conditions[] = "Campionati.sport = 'CALCIO'";
		}
		elseif($sport == 'TENNIS'){
			$conditions[] = "Campionati.sport = 'TENNIS'";
		}

		$this->Campionati->virtualFields = [];
		$atleti = $this->Campionati->find('all', [
			'conditions' => $conditions, 
			'fields' => ['Athlete.Email'],
			'recursive' => -1,
			'joins' => [
				[
	 				'alias' => 'SquadreCampionati',
	 				'table' => 'SquadreCampionati',
	 				'type' => 'INNER',
	 				'conditions' => '`SquadreCampionati`.`Campionato` = `Campionati`.`Campionato`'
				],
				[
	 				'alias' => 'Yearbook',
	 				'table' => 'Annuario',
	 				'type' => 'INNER',
	 				'conditions' => '`Yearbook`.`SquadraCampionato` = `SquadreCampionati`.`SquadraCampionato`'
				],
				[
	 				'alias' => 'Athlete',
	 				'table' => 'Atleti',
	 				'type' => 'INNER',
	 				'conditions' => '`Yearbook`.`Atleta` = `Athlete`.`Atleta` AND Athlete.Email <> ""'
				],
	 		]
		]);
		// debug($atleti); die();

		$emails = [];
		foreach($atleti as $atleta)
		{
			$emails[] = $atleta['Athlete']['Email'];
		}

		sort($emails);
		$emails = array_unique($emails);
		// debug($emails);die();
		print(count($emails)."indirizzi \n");
		$this->__addEmailsToGroup($emails, $groupId);
	}

	/**
	 * Aggiorna elenco giocatori calcio che hanno giocato questa settimana
	 * Viene richiamata da cron ogni sabato mattina prima della generazione dei bollettini
	 * @return void 
	 */
	public function updateAtletiCalcioSettimanali()
	{
		$this->autoRender = false;

		$monday = strtotime("last Monday");
		$saturday = strtotime("+5 days",$monday);

		$datain = date("Y-m-d",$monday);
		$dataout = date("Y-m-d",$saturday);

		$matches = $this->Match->find('all', array(
				'conditions' => array(
					// 'Match.Campionato' => $campionato['Campionati']['Campionato'],
					'Match.Data >= ' => $datain,
					'Match.Data <= ' => $dataout,
					"Campionati.scuola != 1",
					"Campionati.sport = 'CALCIO'",
				), 
				'order' => 'Match.Giornata ASC',
				'fields' => ['Match.*', 'Casa.*', 'Trasferta.*'],
			)
		);

		// debug($sel);

		$emails = [];
		foreach($matches as $match)
		{
			// debug($match);
			foreach(['Casa', 'Trasferta'] as $squadra)
			{
				// debug($sel[$squadra]['SquadraCampionato']);
				$annuarioAtleta = $this->Yearbook->find('all', [
					'conditions' => [
						'Yearbook.SquadraCampionato' => $match[$squadra]['SquadraCampionato'],
						'Athlete.Email <>' => '',
					],
					'fields' => ['Athlete.Atleta, Athlete.Email']
				]);

				foreach($annuarioAtleta as $atleta)
				{
					$emails[] = $atleta['Athlete']['Email'];
				}
			}
		}

		// debug($emails);
		$this->__addEmailsToGroup(array_unique($emails), 16);
	}

	/**
	 * Aggiunge le email a un gruppo di newsletter (ripescando prima gli eventuali newsletter_users già esistenti)
	 * @param  array  $emails   
	 * @param  int  $group_id 
	 * @return void
	 */
	private function __addEmailsToGroup($emails, $ngroup_id, $removeOthers = true)
	{
		// debug($emails);
		print("Processo gruppo $ngroup_id\n");

		$nusers = $this->NewsletterUser->find('list', [
			'conditions' => ['email' => $emails],
			'fields' => ['NewsletterUser.email', 'NewsletterUser.id'],
			'recursive' => -1,
		]);

		// debug($nusers);

		// Aggiunge gli utenti mancanti nella tabella nlUsers
		foreach($emails as $email)
		{
			if(!isset($nusers[$email]))
			{
				$this->NewsletterUser->create();
				if($this->NewsletterUser->save(['email' => $email]))
				{
					$nusers[$email] = $this->NewsletterUser->id;
				}
			}
		}
		unset($emails);

		// Aggiunge gli utenti mancanti al gruppo
		$ngus = $this->NewsletterGroupUser->find('list', [
			'conditions' => ['newsletter_group_id' => $ngroup_id, 'newsletter_user_id' => $nusers ],
			'fields' => ['NewsletterGroupUser.newsletter_user_id', 'NewsletterGroupUser.id'],
			'recursive' => -1,
		]);

		foreach($nusers as $email => $nuser_id)
		{
			if(!isset($ngus[$nuser_id]))
			{
				$this->NewsletterGroupUser->create();
				if($this->NewsletterGroupUser->save(['newsletter_group_id' => $ngroup_id, 'newsletter_user_id' => $nuser_id]))
				{
					$ngus[$nuser_id] = $this->NewsletterGroupUser->id;
				}
			}
		}

		// Rimuove gli utenti in eccesso dal gruppo
		if($removeOthers)
		{
			// debug($ngus);
			$deletable = $this->NewsletterGroupUser->find('list', ['conditions' => ['newsletter_group_id' => $ngroup_id, 'NOT' => ['id' => $ngus]], 'recursive' => -1]);
			$this->NewsletterGroupUser->deleteAll(['id' => $deletable]);
		}

		unset($nusers);
		unset($ngus);

		print("Fatto\n\n");
	}


}

?>