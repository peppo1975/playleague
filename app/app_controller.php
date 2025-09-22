<?php
// File modificato
function array_orderby()
{
	$args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field) {
		if (is_string($field)) {
			$tmp = array();
			foreach ($data as $key => $row)
				$tmp[$key] = $row[$field];
			$args[$n] = $tmp;
		}
	}
	$args[]  = &$data;

	$args2 = $args;

	foreach ($args as $i => $row)
		$args[$i] = &$args2[$i];

	call_user_func_array('array_multisort', $args);
	return array_pop($args);
}

function isValidURL($value) {

	$value = trim($value);
	$validhost = true;

	if (strpos($value, 'http://') === false && strpos($value, 'https://') === false) {
		$value = 'http://'.$value;
	}

			//first check with php's FILTER_VALIDATE_URL
	if (filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false) {
		$validhost = false;
	} else {
			//not all invalid URLs are caught by FILTER_VALIDATE_URL
			//use our own mechanism

		$host = parse_url($value, PHP_URL_HOST);
		$dotcount = substr_count($host, '.');

			//the host should contain at least one dot
		if ($dotcount > 0) {
			//if the host contains one dot
			if ($dotcount == 1) {
			//and it start with www.
				if (strpos($host, 'www.') === 0) {
			//there is no top level domain, so it is invalid
					$validhost = false;
				}
			} else {
			//the host contains multiple dots
				if (strpos($host, '..') !== false) {
			//dots can't be next to each other, so it is invalid
					$validhost = false;
				}
			}
		} else {
			//no dots, so it is invalid
			$validhost = false;
		}
	}

			//return false if host is invalid
			//otherwise return true
	return $validhost;
}

function isYoutubeVideo($value) {

	if (preg_match('/http:\/\/(?:youtu\.be\/|(?:[a-z]{2,3}\.)?youtube\.com\/watch(?:\?|#\!)v=)([\w-]{11}).*/i',$value)) {
		return true;
	} else {
		return false;
	}

}

function getYoutubeId($url)
{
	$url_string = parse_url($url, PHP_URL_QUERY);
	parse_str($url_string, $args);
	return isset($args['v']) ? $args['v'] : false;
}

function make_date($value) {

	if (preg_match('~((19|20)[0-9]{2})[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])~Ui',substr($value,0,strlen('0000-00-00')),$datan)) {

		return $datan[4] . "/" . $datan[3] . "/" . $datan[1];

	}

	if($value == '0000-00-00 00:00:00') $value = 'Nessuna';

	return $value;

}

function invert_date($data) {

	if (preg_match('~(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)[0-9]{2})~Ui',$data,$datan)) {


		return $datan[3] . "-" . $datan[2] . "-" . $datan[1];

	}


	return $data;

}

function make_euro ($value) {

	if ($value >= 0 && $value != "" && is_numeric($value))
		return $value . ' &euro;';
	return $value;

}

function getMetadata($model,$id) {

	App::Import('Model','Metadata');
	$Metadata = new Metadata;

	$meta = $Metadata->find('first', array(

		'conditions' => array(

			'Metadata.model' => $model,
			'Metadata.model_id' => $id

			)

		)

	);

	return $meta['Metadata'];

}

function isAllowed($controller,$action) {


	App::import('Component', 'AuthComponent');
	App::Import('Component', 'SessionComponent');
	$auth = new AuthComponent();

	App::Import('Model','Right');

	$right = new Right;

	$auth->Session = new SessionComponent();


	$group_id   = $auth->user('group_id');

	$resource   = $controller.'|'.$action;

	$isEnable = $right->find('first', array(

		'conditions' => array(

			'Right.group_id' => $group_id,
			'Right.resource' => $resource,


			),

		));

	$isEnable_2 = $right->find('count',array(

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

	} else {

		return true;

	}


}

class AppController extends Controller {

	var $components = array('Session','Cookie','Auth','Email','Uploader.Uploader','RequestHandler');
	var $Order = null;

	function dmy2ymd(&$field) {

		$data = array();

		if (preg_match('~(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)[0-9]{2})~Ui',$field,$data)) {

			$field = $data[3] . "-" . $data[2] . "-" . $data[1];

		}
		return $field;
	}

	function _setErrorLayout() {
/*
if ($this->name == 'CakeError') {

if($_SERVER['SERVER_NAME'] != Configure::read('localhost')) { $this->redirect('/'); }

}*/

}

function getError($errName) {

	require APP . '/vars/errors.php';


	if (isset($formErrors[$errName])) return $formErrors[$errName];
	return "";

}

function admin_writeSessionModel($id_element, $model) {

	$this->layout = "ajax";

	$this->Session->write($model . '.ElementSelected', $id_element);

	exit;

}

function admin_readSessionModel($model) {

	$this->layout = "ajax";

	$this->set('result', json_encode(array('element_id' => $this->Session->read($model . '.ElementSelected'))));
	$this->render('/backend/ajaxResult');

}

function beforeFilter() {

	require_once APP . 'libs/Mobile_Detect.php';
	$detect = new Mobile_Detect();


$layout = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');

	$this->set('layout', $layout);
/*
	$is_admin = (isset($this->params['prefix']) && $this->params['prefix'] == "admin")? "admin" : "";

	if($layout == "mobile" && $this->params['controller'] != "mobile" && $this->action != "single_lda" && $is_admin == "")
		$this->redirect('/mobile');
*/
	if(!isset($this->params['prefix'])) $this->Session->write('admin_data_type','');

	$this->helpers[] = 'Text';

	App::Import('Helper','Text');

	$this->set('admin_data_type', $this->Cookie->read('admin_data_type'));

	$this->Text = new TextHelper();

	/* Controllo permessi in richieste ajax */

	$this->set('myInstance',$this);

	if(isset($_GET['filter'])) {

		$this->set('lochash', $_GET['filter']);

	}

	App::Import('Model','Right');
	$this->Right = new Right;

	$group_id = $this->Auth->user('group_id');

	$controller = Inflector::Camelize($this->params['controller']);
	$action = $this->action;

	$resource = $controller.'|'.$action;

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
			'Right.allow' => 0,


			)

		));



	$resultEnable = true;

	if ($isEnable_2 > 0) $resultEnable = false;

	if ($isEnable) {

		if ($isEnable['Right']['allow'] == 1) $resultEnable = true;
		else 						 $resultEnable = false;


	}


	if ($resultEnable == false) {

		if ($this->action == "admin_edit") {

			$this->set('admin_writable',0);

			if (isset($_GET['modded']) && $_GET['modded'] == 'true') {

				$this->layout = "ajax";
				$this->render('/backend/ajaxDontAllow');

				return;

			}

		} else {
			if ($this->params['controller'] != 'Users' && $this->action != 'admin_index') {

				$this->layout = "timmybox";
				$this->render('/backend/ajaxDontAllow');

			} else {

				$this->redirect('/admin/');

			}
			return;
		}
	}



	/**/

	$this->Uploader->uploadDir 	 = 'files/uploads/';
	$this->Uploader->maxFileSize = '200M';

	App::Import('Model','Upload');

	$upload = new Upload;

	$bg = $upload->find('first',array(

		'conditions' => array(

			'tag' => 'HEADER',
			'default' => '1',
			'published <= NOW()',

			)

		));

	if (empty($bg)) {


		$bg = $upload->find('first',array(

			'conditions' => array(

				'Upload.tag' => 'HEADER',
				'Upload.disabled' => 0,
				'published <= NOW()',

				),

			'order' => 'RAND()'

			));

	}

	$this->set('backgroundAd',$bg);



	$this->Email->smtpOptions = array(
		'port'=>'25',
		'timeout'=>'30',
		'host' => 'pro.eu.turbo-smtp.com',
		'username'=>'smtp@timmytag.it',
		'password'=>'ea7zdFNQ',
		'client' => 'CAKE'
		);

	$this->Email->delivery = 'smtp';
	$this->Email->sendAs = 'both';
	$this->Email->replyTo = 'noreply@playleaguesport.it';
	$this->Email->from = 'noreply@playleaguesport.it';

	if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
		$this->layout = 'admin';
	}

	$this->helpers[] = "Time";
	$this->helpers[] = "Html";
	$this->helpers[] = "Thumbnail";
	$this->helpers[] = "Facebook.Facebook";

	$this->Auth->userScope = array('User.group_id !=' => 6);
	$this->Auth->authorize = 'controller';

	$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login','admin' => true,'prefix' => 'admin');


	if (empty($this->User)) $this->loadModel('User');

	if (!isset($this->login_required) || $this->login_required == false) $this->Auth->allow('*');

	if ($this->Auth->user()) {

		$userInfo = $this->Auth->user();


		if (!$this->Session->check('User')) {

			$this->Session->write('User', $userInfo['User']);

			$this->User->query("UPDATE users AS User SET modified = '" . date("Y-m-d H:i:s") . "' WHERE id = " . $userInfo['User']['id']);

		}


		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && $this->params['action'] != "admin_logout") {
			$group_id = $this->Auth->user('group_id');

			if ($group_id == 6 || $group_id == 7 || $group_id == 13) {
				header("Location: /admin/users/logout");
				exit;
			}
		}

		$currentUser = $this->User->find('first',

			array(

				'conditions' => array( 'User.id' => $userInfo['User']['id'] )

				)
			);

		$this->set('currentUser',$currentUser);

	}

	$auth_required = str_replace('admin_', '', $this->action);

	if($auth_required == $this->action) {

		if (!$this->Auth->user()) $this->Auth->allow('*');

	}

	$this->getTitle();


	if (isset($_GET['logout'])) {

		$this->Session->delete('Login.data');
		$this->redirect('/');

	}

	if (is_array($this->data) && isset($this->data['Login']) && !$this->RequestHandler->isAjax()) {

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

			$this->redirect('/area/riservata');

		} else if (isset($is_atleta) && !empty($is_atleta)) {

			$data['id'] = $is_atleta['Athlete']['Atleta'];
			$this->Athlete->query('UPDATE Atleti SET password = \'' . $auth_password . '\' WHERE Atleta = ' . $is_atleta['Athlete']['Atleta']);
			$data['nome'] = $is_atleta['Athlete']['Nome'];
			$data['cognome'] = $is_atleta['Athlete']['Cognome'];
			$data['data_nascita'] = $is_atleta['Athlete']['DataNascita'];
			$data['email'] = $data['username'] = $is_atleta['Athlete']['Email'];
			$data['is_atleta'] = 1;
			$data['is_user'] = 0;
			$data['is_arbitro'] = 0;
			$this->Session->write('Login.data',$data);

			$this->redirect('/area/riservata');

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

			$this->redirect('/area/riservata');

		} else {

			$this->set('login_error',1);
			//$this->Session->setFlash('Impossibile loggarsi, username o password errati.');

		}

	}




}

function isAuthorized() {

	if (!$this->Auth->user()) return false;

	$group = $this->User->Group->find('first',array(
		'conditions' => array('Group.id' => $this->Auth->user('group_id')),
		'contain' => array(
			'Right' => array(
				'conditions' => array('Right.resource LIKE' => $this->name . '%'),
				'order' => 'Right.resource ASC'
				)

			)
		));



	$rights = $group['Right'];

	$myURL = $this->name . "|" . $this->action;

	$ret = true;

	foreach ($rights as $right) {


		if (substr_count($myURL,$right['resource']) && $right['allow'] == 0) $ret = false;
		else $ret = true;

	}

	return $ret;

}

function getTitle() {

	require APP . '/vars/titles.php';

	$myURL = $this->name . "|" . $this->action;

	if (isset($titles[$myURL])) $this->set("title_for_layout",$titles[$myURL]);

}

function getOrder($model) {

	$this->layout = "ajax";

	App::Import('Model','Order');

	$this->Order = new Order;

	$getOrderModel = $this->Order->find('first', array('conditions' => array('Order.model' => $model)));

	if($getOrderModel == array()) {

		$this->Order->create();
		$this->Order->set('model', $model);
		$this->Order->set('argument', 'created');
		$this->Order->set('order_type', 'ASC');
		$this->Order->save();

		$last = $this->Order->id;

	} else $last = $getOrderModel;

	$this->set('result', json_encode(array('last' => $last)));
	$this->render('/backend/ajaxResult');

}

function setOrder($model,$params) {

	$this->layout = "ajax";

	App::Import('Model','Order');

	$this->Order = new Order;

	$getOrderModel = $this->Order->find('first', array('conditions' => array('Order.model' => $model)));

	$this->data = $getOrderModel;

	if($params == 'ASC' || $params == 'DESC') $this->data['Order']['order_type'] = $params;

	else $this->data['Order']['argument'] = $params;

	$this->Order->id = $this->data['Order']['id'];

	unset($this->data['Order']['id']);
	unset($this->data['Order']['model']);

	if($this->Order->save($this->data)) {

		$last = 1;

	} else {

		$last = 0;

	}

	$this->set('result', json_encode(array('last' => $last)));
	$this->render('/backend/ajaxResult');

}

function sortableOrder($model) {

	$this->layout = "ajax";

	App::Import('Model', $model);
	$this->$model = new $model;

	foreach($_POST['Data'] as $order => $id) {

		if ($model != "Campionati")
		$this->data = $this->$model->findById($id);
		else 
		$this->data = $this->$model->findByCampionato($id);
		
		$this->data[$model]['order'] = $order;
		$this->$model->set($this->data);
		$this->$model->save();

	}

}

function getList($model,$limit) {

	$model = ucfirst($model);

	App::Import('Model',$model);
	$this->$model = new $model;

	$data = $this->$model->find('all', array('limit' => $limit));

	if(!empty($this->params['requested'])) {

		return $data;

	}

}

public function __adminUploadFile($fk,$fkValue, $options = array()) {

	App::Import('Lib','pclzip');

	if (!isset($this->data['Upload'])) return false;

	$saveData = $this->data['Upload'];

// Additional settings
	if(!empty($options)) {
		if(isset($options['maxsize'])) { if($saveData['percorso']['size'] > $options['maxsize']) return false; }
		if(isset($options['exts'])) { if(!in_array($saveData['percorso']['type'], $options['exts'])) return false; }
	}
//

	if (empty($this->data['Media']['percorso'])) {

		if ($this->data['Upload']['percorso']['size'] > 0) {

			$upload = $this->Uploader->upload('Upload.percorso');

			if ($upload != false) {

				if (!($upload['ext'] == 'zip' && $this->data['Upload']['extract'] == 1)) {

					$upload[$fk] = $fkValue;

					$saveData = array_merge($saveData,$upload);

					$this->Upload->create();

//Tmp mod
					$saveData['path'] = '/files/uploads/' . $saveData['path'];

					if (!$this->Upload->save($saveData)) return false;

					return true;

				} else {


					$archive = new PclZip(APP . '/webroot/' . $upload['path']);

					$path = '/files/uploads/' . $this->name . '_' . uniqid();

					if (!$archive->extract(APP . '/webroot/' . $path)) return false;

					$files = $archive->listContent();



					foreach ($files as $file) {

						$opts = array();

						$opts['uploaded'] = $opts['created'] = $opts['modified'] = date("Y-m-d H:i:s");

						$opts['name'] = basename($file['filename']);

						$opts['size'] = $file['size'];

						$opts['filesize'] = $this->Uploader->bytes($file['size']);

						$opts['path'] = $path . '/' . $file['filename'];

						$opts['ext'] = $this->Uploader->ext($opts['name']);

						$opts['mime'] = mime_content_type(APP . '/webroot/' . $opts['path']);

						$group = explode("/",$opts['mime']);

						$opts[$fk] = $fkValue;

						if (count($group)) $opts['group'] = $group[0];

						$this->Upload->create();

						if (!$this->Upload->save($opts)) return false;

					}

					return true;


				}

			} else {

				$this->Upload->invalidate("percorso",$this->getError('INVALID_FILEFORMAT'));

				return false;

			}

		}

	} else {

/*
$upload[$fk] = $fkValue;

$saveData = array_merge($saveData,$upload);

$this->Upload->set($saveData);

if (!$this->Upload->save()) return false;

return true;

*/

if (isYoutubeVideo($this->data['Media']['percorso'])) {


	$data = json_decode(file_get_contents("http://www.youtube.com/oembed?url=" . $this->data['Media']['percorso'] . "&amp;format=json"),1);

	$filename = Inflector::Slug($this->data['Media']['percorso']) . ".jpg";

	file_put_contents(APP . '/webroot/files/uploads/' . $filename,file_get_contents($data['thumbnail_url']));

	@chmod(APP . '/webroot/files/uploads/' . $filename,0644);

	$this->data['Media']['name'] = $this->data['Media']['percorso'];
	$this->data['Media']['path'] = '/files/uploads/' . $filename;

	$upload[$fk] = $fkValue;
	$upload['name'] = $this->data['Media']['name'];
	$upload['path'] = $this->data['Media']['path'];
	$upload['type'] = "youtube";
	$upload['group'] = "youtube";
	$upload['ext'] = "jpg";
	$upload['tag'] = $this->data['Media']['tag'];
	$upload['description'] = $this->data['Media']['description'];

	$saveData = array_merge($saveData,$upload);

	$this->Upload->set($saveData);

	if (!$this->Upload->save()) return false;


	return true;

}

if (preg_match('/http:\/\/(www\.)*vimeo\.com\/.*/',$this->data['Media']['percorso'])) {


	$data = json_decode(file_get_contents("http://vimeo.com/api/oembed.json?url=" . $this->data['Media']['percorso']),1);

	$filename = Inflector::Slug($this->data['Media']['percorso']) . ".jpg";

	file_put_contents(APP . '/webroot/files/uploads/' . $filename,file_get_contents($data['thumbnail_url']));

	@chmod(APP . '/webroot/files/uploads/' . $filename,0644);

	$this->data['Media']['name'] = $this->data['Media']['percorso'];
	$this->data['Media']['path'] = '/files/uploads/' . $filename;

	$upload[$fk] = $fkValue;
	$upload['name'] = $this->data['Media']['name'];
	$upload['path'] = $this->data['Media']['path'];
	$upload['type'] = "vimeo";
	$upload['group'] = "vimeo";
	$upload['ext'] = "jpg";
	$upload['tag'] = $this->data['Media']['tag'];
	$upload['description'] = $this->data['Media']['description'];

	$saveData = array_merge($saveData,$upload);

	$this->Upload->set($saveData);

	if (!$this->Upload->save()) return false;


	return true;

}


return false;


}

return true;

}

function beforeRender() {



	if(isset($this->login_site) && $this->login_site == true) {

		if(!$this->Session->check('Login.data')) $this->redirect('/');

	}

	/* Controllo se ci sono errori */

	$this->_setErrorLayout();

	/* --------------------------- */

	/* Get metadata function */
//Variabili di default presenti in /APP/config/config_site.php

	$prefix = Configure::read('default_prefix');
	$desc = Configure::read('default_description');
	$keyw = Configure::read('default_keywords');
	$title = (isset($this->title)? $this->title:Configure::read('default_title'));
	$auth = Configure::read('default_author');

	if(isset($this->params['prefix'])) $prefix_admin = $this->params['prefix'];
	else							 $prefix_admin = '';

	if($prefix_admin != 'admin') {

		$model = (isset($this->firstModel)? $this->firstModel : '');
		if(!isset($this->params['pass'][0])) $id = '';
		else 								 $id = $this->params['pass'][0];

//$model = Inflector::camelize(Inflector::singularize($this->params['controller']));

		if(isset($model) && $model != '' && $id != '' && is_numeric($id)) {

			App::Import('Model', $model);
			$Model = new $model;

			App::Import('Helper', 'Text');
			$this->Text = new TextHelper();

			$meta = getMetadata('Page', $id);
			$data = $Model->findById($id);

			if($data[$model]['img_evidenza'] != '') {

				$facebook_img = 'https://' . $_SERVER['SERVER_NAME'] . $data[$model]['img_evidenza'];

			} else {

				$facebook_img = 'https://' . $_SERVER['SERVER_NAME'] . '/img/logo-playleaguesport.png';

			}

//Get metadata if not exists

			if($data[$model]['content'] == '') {

				$description = $desc;
				$keywords = $keyw;

			} else {

				$description =

				$this->Text->truncate(
					strip_tags($data[$model]['content']),
					140,
					array(
						'ending' => '',
						'exact' => false
						)
					);

				$texts = trim(ereg_replace("[^a-zA-Z0-9]", " ", strip_tags($data[$model]['content'])));
				$arr_content = explode(' ', $texts);
				$words = array();

				foreach($arr_content as $text) {

					if(strlen($text) > 3) {
						$words[] = $text;
					}

				}

				$conteggio = array();

				foreach($words as $word) {
					if (isset($conteggio[$word])) $conteggio[$word]++;
					else $conteggio[$word] = 0;
				}
				asort($conteggio);
				$conteggio = array_reverse($conteggio);
				$limit = 12;
				$keywords = '';
				$i = 0;

				foreach($conteggio as $parola => $count) {

					$keywords .= ',' . $parola;
					$i++;
					if($i == $limit) break;

				}

				$keywords = substr_replace($keywords,'',0,1);

			}

//

			if(!isset($meta)) $meta = array();

			if(count($meta)) {

				$this->set('title_for_layout', $prefix . ' | ' . (($meta['title'] != '')? $meta['title'] : $data[$model]['title']));
				$this->viewVars['meta_author'] = $auth;
				$this->viewVars['meta_description'] = (($meta['description'] != '')? $meta['description'] : $description);
				$this->viewVars['meta_keywords'] = (($meta['keywords'] != '')? $meta['description'] : $keywords);

//Se esistono plugin facebook nella pagina
				if(isset($this->facebook) && $this->facebook == true) {

					$this->viewVars['facebook_title'] = $prefix . ' | ' . (($meta['title'] != '')? $meta['title'] : $data[$model]['title']);
					$this->viewVars['facebook_url'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->params['url']['url'];
					$this->viewVars['facebook_content'] = (($meta['description'] != '')? $meta['description'] : $description);
					$this->viewVars['facebook_site'] = Configure::read('site_name');
					$this->viewVars['facebook_img'] = $facebook_img;


				}

			} else {

				$this->set('title_for_layout', $prefix . ' | ' . (isset($data[$model]['title'])? $data[$model]['title']:$title));
				$this->viewVars['meta_author'] = $auth;
				$this->viewVars['meta_description'] = $description;
				$this->viewVars['meta_keywords'] = $keywords;

//Se esistono plugin facebook nella pagina
				if(isset($this->facebook) && $this->facebook == true) {

					$this->viewVars['facebook_title'] = $prefix . ' | ' . (isset($data[$model]['title'])? $data[$model]['title']:$title);
					$this->viewVars['facebook_url'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->params['url']['url'];
					$this->viewVars['facebook_content'] = $description;
					$this->viewVars['facebook_site'] = Configure::read('site_name');
					$this->viewVars['facebook_img'] = $facebook_img;


				}

			}

		} else {

			$this->set('title_for_layout', $prefix . ' | ' . $title);
			$this->viewVars['meta_author'] = $auth;
			$this->viewVars['meta_description'] = $desc;
			$this->viewVars['meta_keywords'] = $keyw;

//Se esistono plugin facebook nella pagina
			if(isset($this->facebook) && $this->facebook == true) {

				$this->viewVars['facebook_title'] = $prefix . ' | ' . $title;
				$this->viewVars['facebook_url'] = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $this->params['url']['url'];
				$this->viewVars['facebook_content'] = $desc;
				$this->viewVars['facebook_site'] = Configure::read('site_name');
				$this->viewVars['facebook_img'] = 'https://' . $_SERVER['SERVER_NAME'] . '/img/logo-playleaguesport.png';


			}

		}


	}

}

// /**/

}
