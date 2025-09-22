<?
	function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = utf8ize($v);
			}
			} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}
	class SubscriptionsController extends AppController {
		
		var $name = "Subscriptions";
		var $helpers = array('Backend');
		var $uses = array('Athlete','User','Subscription','Match','Campionati','Campicampionati','Ranking','Yearbook','Disciplinari','Discipline','Half','Squadre','Campi','Causalresult','AnniSportivi','SquadreCampionati','Athlete','Lda','Matchgoal','Notgame','EmailModel','Spool','NewsletterConfig','TipiAssicurazione');
		
		var $components = array('Password', 'RequestHandler', 'Email','ControllerList'); 
		
		
		// old non giusta
		// function isTesserat($id)
		// {
		
		// mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
		// mysql_select_db("MidlandDev2016");
		// $q = mysql_query("SELECT COUNT(*) FROM Annuario WHERE Atleta = $id AND AnnoSportivo = " . date("Y"));
		// echo(mysql_fetch_array($q)[0]);exit;
		
		// }
		
		
		//GIUSEPPE prima variante
		
		// function isTesserat($id)
		// {
		
		// mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
		
		// mysql_select_db("MidlandDev2016");
		
		// $q = mysql_query("SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`");
		
		// $anno_in_corso = mysql_fetch_array($q)['AnnoInCorso'];
		
		// $q = mysql_query("SELECT COUNT(*) FROM Annuario WHERE Atleta = $id AND AnnoSportivo = " . $anno_in_corso);
		
		// echo(mysql_fetch_array($q)[0]);
		
		// exit;
		
		// }
		
		//GIUSEPPE seconda variante
		
   

	   function isTesserat($id, $sport) //QUI NON CONTROLLO IL TIPO DI SPORT
	    {
	        //GIUSEPPE 13/11/2016 inserito sport 'CALCIO' o 'TENNIS' nella tabella Annuario
	   
	        $query = "SELECT COUNT(*) AS count, Squadre.sport FROM Annuario 
				INNER JOIN SquadreCampionati
				ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato
				INNER JOIN Squadre
				ON Squadre.Squadra = SquadreCampionati.Squadra
				WHERE Atleta = '$id' AND AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)";

	        //$query = "SELECT COUNT(*) FROM Annuario WHERE Atleta = $id AND AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)";

	        $q = mysql_query($query);

	        $row = mysql_fetch_assoc($q);
	        
	        echo json_encode($row);

	        exit;
	    }

	    //GIUSEPPE 2017-02-07 CONTROLLA LA PRESENZA DI NOME COGNOME E DATA DI NASCITA ............


		//GIUSEPPE 2017-02-07 CONTROLLA LA PRESENZA DI NOME COGNOME E DATA DI NASCITA ............
		
		function readAnagrafici()
		{
			
			$result = 0;
			
			if($_POST['cognome']!="" && $_POST['nome'] !="" && $_POST['data']!="" )
			{
				//echo $_POST['cognome']." - ". $_POST['nome']." - ". $_POST['data'];
				$cognome = strtolower($_POST['cognome']);
				
				$nome = strtolower($_POST['nome']);
				
				$data_expl = explode("/",$_POST['data']);
				
				$data = $data_expl[2]."-".$data_expl[1]."-".$data_expl[0];
				
				$query = "SELECT COUNT(Nome) FROM Atleti WHERE LOWER(Cognome)='$cognome' AND LOWER(Nome) = '$nome' AND DataNascita = '$data'";
				
				$q = mysql_query($query);
				
				$result = mysql_fetch_array($q)[0];
				
			}
			
			echo($result);
			
			exit;
		}
		
		// .......................................................................................


	 //GIUSEPPE 2017-02-09 ....................................................................
		
		function controlathlete()
		{
			// questa è una "funzione di sicurezza"
			// i controlli presenti in questa funzione in realtà vengono gia fatti precedentemente, durante la digitazione dei dati.
			// se per caso duarante la digitazione dovesse sfuggire qualche controllo non è un problema: comunque rieseguo tutti i controlli qui prima di andare avanti
			
			$result_total = array();
			
			foreach($_POST['atleti'] as $i => $single_athlete)
			{
				if($single_athlete['Atleta']=="") // ATLETA NUOVO controllo se gia esiste
				{
					
					$cognome = strtolower($single_athlete['Cognome']);
					
					$nome = strtolower($single_athlete['Nome']);
					
					$data_expl = explode("/",$single_athlete['DataNascita']);
					
					$data = $data_expl[2]."-".$data_expl[1]."-".$data_expl[0];
					
					$query = "SELECT COUNT(Nome) FROM Atleti WHERE LOWER(Cognome)='$cognome' AND LOWER(Nome) = '$nome' AND DataNascita = '$data'";
					
					$q = mysql_query($query);
					
					$result = mysql_fetch_array($q)[0];
					
					if($result > 0)
					{
						$result_total[] = " - ".$single_athlete['Cognome']." ".$single_athlete['Nome']."\n - nato il ".$single_athlete['DataNascita']."\n è gia presente nel nostro sistema e non puo essere reinserito !";
					}
					
				}
				else if($single_athlete['Atleta']!="" && !isset($single_athlete['Iscrizione'])) // ATLETA ESISTENTE controllo se è gia tesserato (nel caso mi trovo nella pagina dei tesseramenti)
				{
					$id = $single_athlete['Atleta'];
					
					$sport = $single_athlete['sport'];
					
					$query = "SELECT COUNT(Tessera) FROM Annuario 
					INNER JOIN SquadreCampionati
					ON Annuario.SquadraCampionato = SquadreCampionati.SquadraCampionato
					INNER JOIN Squadre
					ON Squadre.Squadra = SquadreCampionati.Squadra
					WHERE Atleta = '$id' AND Squadre.Sport = '$sport' AND AnnoSportivo = (SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`)";
					
					$q = mysql_query($query);
					
					$result = mysql_fetch_array($q)[0];
					
					if($result > 0)
					{
						$result_total[] = " - ".$single_athlete['Cognome']." ".$single_athlete['Nome']."\n - nato il ".$single_athlete['DataNascita']."\n risulta gia tesserato per la stagione sportiva in corso !";
					}
				}
				
				if($single_athlete['Atleta']=="") // qui controllo se la e-mail esiste nel caso di nuovo atleta
				{
					$email = $single_athlete['Email'];
					
					$query = "SELECT COUNT(Email) FROM Atleti WHERE Email = '$email'";
					
					$q = mysql_query($query);
					
					$result = mysql_fetch_array($q)[0];
					
					if($result > 0)
					{
						$result_total[] = " - ".$single_athlete['Cognome']." ".$single_athlete['Nome']."\n - nato il ".$single_athlete['DataNascita']."\n ha una mail già associata ad un altro atleta !";
					}
					
				}
				else if($single_athlete['Atleta']!="") // questo è il controllo che eseguo se l'atleta esiste, proviene dal vecchio gestionale e non ha la e-mail
				{
					$email = $single_athlete['Email'];
					
					$id = $single_athlete['Atleta'];
					
					$query = "SELECT COUNT(Email) FROM Atleti WHERE Email = '$email' AND Atleta <> '$id'";
					
					$q = mysql_query($query);
					
					$result = mysql_fetch_array($q)[0];
					
					if($result > 0)
					{
						$result_total[] = " - ".$single_athlete['Cognome']." ".$single_athlete['Nome']."\n - nato il ".$single_athlete['DataNascita']."\n ha una mail già associata ad un altro atleta !";
					}
				}
				
			}
			
			print json_encode($result_total);
			
			exit;
		}
		
		// .......................................................................................

		
		function resp()
		{
			$squadra_id = (int)$_GET["squadra_id"];
			$this->getresp($squadra_id);
		}
		
		
		
		
		
	function getresp2($id)
		{
			//GIUSEPPE 2017-02-12 --- riscritta la query per la ricerca di nome cognome datanascita nelle iscrizioni squadre
			
			$term = $_GET['term'];
			
			//$term = @mysql_real_escape_string($_GET['term']);
			
			$ret = array();
			
			if($term!="")
			{
				
				$query = "SELECT Atleta, Cognome, Nome, DataNascita FROM Atleti WHERE CONCAT(Cognome,' ',Nome) LIKE '$term%' ORDER BY CONCAT(Cognome,' ',Nome) ASC LIMIT 30";
				
				$result = mysql_query($query);
				
				if (mysql_num_rows($result) > 0) 
				{
					
					while($row = mysql_fetch_assoc($result)) 
					{
						
						$ret[] = array(
						'id'=>$row['Atleta'] ,
						'label'=>$row['Cognome']." ".$row['Nome']." ".date("d/m/Y",strtotime($row['DataNascita'])),
						'id_input' => $id
						);
					}
				}
				
				print json_encode($ret);
			}
			
			exit;
			
		}
			
			// function getresp2($id)
		// {
			
			// $this->autoRender = false;
			
			
			// if($_GET['term'] !="")
			
			// {
			// $term = @mysql_real_escape_string($_GET['term']);
			
			// // $athletes = $this->Athlete->find('all',array(
			
			// // 'conditions' =>
			// // array( 
			// // 'OR' => 
			// // array("CONCAT(Cognome,' ',Nome) LIKE '%$term%'","CONCAT(Cognome,' ',Nome) LIKE '%$term%'",)
			
			// // ),
			// // 'limit' => 15, 
			// // 'order' => 'Athlete.Atleta DESC'
			// // ));
			
			// $athletes = $this->Athlete->find('all',array(
			
			// 'conditions' =>
			// array( 
			// 'OR' => 
			// array("CONCAT(Cognome,' ',Nome) LIKE '$term%'","CONCAT(Cognome,' ',Nome) LIKE '$term%' ",)
			
			// ),
			// 'limit' => 15, 
			// 'order' => 'Athlete.Atleta ASC'
			// ));
			
			// $ret = array();
			// foreach ($athletes as $athlete) {
			// //	$ret[]=array(			
			// //	'id'=>$athlete['Athlete']['Atleta'],
			// //	'label'=>($athlete['Athlete']['Anagrafica'] . " - " . date("d/m/Y",strtotime($athlete['Athlete']['DataNascita'])))
			// //	);	
			// //GIUSEPPE 29/09/2016
			// $ret[]=array(			
			// 'id'=>$athlete['Athlete']['Atleta']
			// ,'label'=>($athlete['Athlete']['Cognome'] ." " . $athlete['Athlete']['Nome']  . " - " . date("d/m/Y",strtotime($athlete['Athlete']['DataNascita'])))
			// ,'id_input' => $id
			// );
			// }
			
			// print json_encode($ret); 
			// //print json_encode($athletes);
			// }
			
			
			// exit;
		// }
		
		
		
		
		function getresp($squadra_id) {
			
			
			$this->autoRender = false;
			
			//				$squadre_campionati = $this->SquadreCampionati->find('all',array('conditions'=>array('SquadreCampionati.Squadra'=>($squadra_id))));
			$squadrecampionati = array();
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			mysql_select_db("MidlandDev2016");
			$q = mysql_query("SELECT * FROM SquadreCampionati");
			
			
			
			while ($ret = mysql_fetch_assoc($q)) { $squadrecampionati[] = $ret['SquadraCampionato']; }
			
			$squadrecampionati = "(" . implode(",", $squadrecampionati) . ")";
			
			$qq = mysql_query("SELECT * FROM Atleti ORDER BY Cognome, Nome LIMIT 1000");
			
			
			$responsabili = array();
			
			$out = [];
			$ids = [];
			while ($ret = mysql_fetch_assoc($qq)) {
				if(!in_array($ret["Atleta"], $ids))
				{
					if(!empty($ret["Nome"]))
					{
						$responsabili[] = [
						"nome" => $ret["Nome"],
						"cognome" => $ret["Cognome"],
						"id" => $ret["Atleta"]
						];
						$ids[] = $ret["Atleta"];
					}
				}
			}	
			
			print json_encode(utf8ize($responsabili));
			exit;
			
			
			
		}
		
		function atleta($id) {
			
			
			
			$this->autoRender = false;
			$atleta = $this->Athlete->findByAtleta($id);
			
			print json_encode($atleta['Athlete']);
			
			exit;
			
			
		}
		
		// function find() {
		
		// $this->autoRender = false;
		
		// $term = @mysql_real_escape_string($_GET['term']);
		// $athletes = $this->Athlete->find('all',array(
		
		// 'conditions' =>
		// array( 
		// 'OR' => 
		// array("CONCAT(Nome,' ',Cognome) LIKE '%$term%'","CONCAT(Cognome,' ',Nome) LIKE '%$term%'",
		// )
		
		// ),
		// 'limit' => 15,
		// 'order' => 'Athlete.Atleta DESC'
		// ));
		
		// $ret = array();
		// foreach ($athletes as $athlete) {
		
		
		
		// $ret[]=array(
		
		// 'id'=>$athlete['Athlete']['Atleta'],
		// 'label'=>(date("d/m/Y",strtotime($athlete['Athlete']['DataNascita'])) . " - " . $athlete['Athlete']['Anagrafica'])
		
		
		// );
		
		
		
		// }
		
		// print json_encode($ret);
		// exit;
		// }
		
		
		
		//GIUSEPPE 2017-02-07 riscritta completamente la query
		
		function find() 
		{
			
			$term = $_GET['term'];
			
			//$term = @mysql_real_escape_string($_GET['term']);
			
			$ret = array();
			
			if($term!="")
			{
				
				$query = "SELECT Atleta, Cognome, Nome, DataNascita FROM Atleti WHERE CONCAT(Cognome,' ',Nome) LIKE '$term%' ORDER BY CONCAT(Cognome,' ',Nome) ASC LIMIT 30";
				
				$result = mysql_query($query);
				
				if (mysql_num_rows($result) > 0) 
				{
					
					while($row = mysql_fetch_assoc($result)) 
					{
						
						$ret[] = array(
						'id'=>$row['Atleta'] ,
						'label'=>$row['Cognome']." ".$row['Nome']." ".date("d/m/Y",strtotime($row['DataNascita']))
						);
					}
				}
				
				print json_encode($ret);
			}
			
			exit;
		}
		// ----------------------------
		
		
		function getat($squadra_id) {
			
			
			$this->autoRender = false;
			
			//				$squadre_campionati = $this->SquadreCampionati->find('all',array('conditions'=>array('SquadreCampionati.Squadra'=>($squadra_id))));
			$squadrecampionati = array();
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			mysql_select_db("MidlandDev2016");
			$q = mysql_query("SELECT * FROM SquadreCampionati WHERE Squadra = $squadra_id"); 
			
			
			while ($ret = mysql_fetch_assoc($q)) { $squadrecampionati[] = $ret['SquadraCampionato']; }
			
			$squadrecampionati = "(" . implode(",", $squadrecampionati) . ")";
			
			$qq = mysql_query("SELECT * FROM Atleti,Annuario WHERE Atleti.Atleta = Annuario.Atleta AND SquadraCampionato IN $squadrecampionati ORDER BY Cognome, Nome");
			
			$responsabili = array();
			
			while ($ret = mysql_fetch_assoc($qq)) {
				
				$responsabili[] = $ret['Cognome'] . " " . $ret['Nome'];
			}
			
			$responsabili = array_unique($responsabili);
			return $responsabili;
			exit;
			
			
			
		}
		
		
		// function add() {
		
		// $this->layout = "content";
		// $user = $this->Session->read('Login.data');
		// $model = 'User';
		
		// if ($user['is_atleta'] == 1) $model = 'Athlete';
		
		// $data = $this->{$model}->find('first', array(
		
		// 'conditions' => array(
		// $model . '.' . $this->{$model}->primaryKey => $user['id'],
		// ),
		
		// ));
		// $data_new = array();
		// foreach ($data[$model] as $key => $value) {
		
		// $data_new[ucfirst(Inflector::camelize($key))] = $value;
		
		// }
		
		// $data = $data_new;
		
		
		
		// $prefill = array();
		
		// if (isset($data['Nome'])) $prefill['SubscriptionNome0']=$data['Nome'];
		// if (isset($data['Cognome'])) $prefill['SubscriptionCognome0']=$data['Cognome'];
		// if (isset($data['DataNascita']) && $data['DataNascita'] != '0000-00-00') $prefill['SubscriptionData0']=date("d/m/Y",strtotime($data['DataNascita']));
		// if (isset($data['Telefono'])) $prefill['SubscriptionTelefono0']=$data['Telefono'];
		// if (isset($data['Cellulare'])) $prefill['SubscriptionCellulare0']=$data['Cellulare'];
		// if (isset($data['NumeroDocumento'])) $prefill['SubscriptionNumerodoc0']=$data['NumeroDocumento'];
		// if (isset($data['ScadenzaDocumento']) && $data['ScadenzaDocumento'] != '0000-00-00') $prefill['scadenza']=date("d/m/Y",strtotime($data['ScadenzaDocumento']));
		// if (isset($data['LuogoNascita'])) $prefill['luogonascita']=$data['LuogoNascita'];
		// if (isset($data['Email'])) $prefill['SubscriptionEmail0']=$data['Email'];
		// if (isset($data['Username'])) $prefill['SubscriptionEmail0']=$data['Username'];
		// if (isset($data['Localita'])) $prefill['SubscriptionComune0']=$data['Localita'];
		// if (isset($data['Indirizzo'])) $prefill['SubscriptionVia0']=$data['Indirizzo'];
		// if (isset($data['Provincia'])) $prefill['SubscriptionPv0']=$data['Provincia'];
		// if (isset($data['Cap'])) $prefill['SubscriptionCap0']=$data['Provincia'];
		
		
		
		// $prefill_ok = array();
		
		// foreach ($prefill as $key => $value) $prefill_ok[] = array('key'=>$key,'value'=>$value);
		
		
		// $this->set('prefill',$prefill_ok);
		
		
		
		
		
		// if (isset($this->data['Subscription'])) {
		
		
		
		// $campionato = $this->Campionati->findByCampionato($this->data['Subscription']['campionato']);
		
		// $this->set('campionato',$campionato);
		
		
		// $girone = $this->Half->find('first',array('conditions'=>array('Half.GironeCampionato' => $this->data['Subscription']['girone'])));
		
		// $this->set('girone',$girone);
		
		
		// if (!empty($this->data['Subscription']['nomesquadra2'])) {
		
		
		// $this->set('squadra',$this->Squadre->findBySquadra($this->data['Subscription']['nomesquadra2']));
		
		
		// }
		
		// $this->set('campo',$this->Campi->findByCampo($this->data['Subscription']['campo']));
		
		// $giorni = array(
		
		// '1' => 'Lunedi',
		// '2' => 'Martedi',
		// '3' => 'Mercoledi',
		// '4' => 'Giovedi',
		// '5' => 'Venerdi',
		// '6' => 'Sabato',
		// '7' => 'Domenica'
		// );
		
		
		// $this->set('giorno',$giorni[$this->data['Subscription']['giorno']]);
		
		// $config     = $this->NewsletterConfig->find('first', array(
		// 'conditions' => array('NewsletterConfig.is_default' => 1),
		// ));
		
		// $this->Email->smtpOptions = array(
		// 'port'=>$config['NewsletterAccount']['port'],
		// 'timeout'=>'30',
		// 'host' => $config['NewsletterAccount']['host'],
		// 'username'=>$config['NewsletterAccount']['username'],
		// 'password'=>$config['NewsletterAccount']['password'],
		// 'client' => 'CAKE'
		// );
		
		// $this->Email->delivery = 'smtp';
		// $this->Email->sendAs = 'both';
		// $this->Email->replyTo = $config['NewsletterAccount']['sender_mail'];
		// $this->Email->from = $config['NewsletterAccount']['sender_name'] . '<' . $config['NewsletterAccount']['sender_mail'] . '>';											
		
		// $this->Email->subject = 'Nuova richiesta di iscrizione effettuata';
		// $this->Email->template = 'signupchamp';
		
		// $this->Email->to = 'antonio.timmytag@gmail.com';
		
		// $this->set('index',0);
		
		// $this->Email->send();
		
		// Email di test
		// $this->Email->to = 'giuseppelag@gmail.com';
		
		// $this->Email->to = 'lucamare@midlandeuropa.com';
		
		// $this->set('index',0);
		
		// $this->Email->send();
		
		// $this->Email->to = 'info@timmytag.it';
		
		// $this->set('index',0);
		
		// $this->Email->send();
		
		// for ($i = 0; $i < 15; $i++) {
		
		// if (isset($this->data['Subscription']['email_'.$i]) && !empty($this->data['Subscription']['email_'.$i])) {
		// $this->Email->to = $this->data['Subscription']['email_'.$i];
		// $this->set('index',$i);
		// $this->Email->send();
		
		
		// }
		
		// }
		
		
		// $this->render('add_ok');
		
		
		
		// return;
		// }
		
		// $squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
		
		// 'SquadraServizio'=> 0,
		// 'Denominazione NOT LIKE \'%CLASS GIR%\'',
		// 'Denominazione NOT LIKE \'%CLASS. GIR%\'',
		// 'Denominazione NOT LIKE \'%CLASS.%\'',
		// 'Denominazione NOT LIKE \'%L.D.A.%\'',
		// 'Denominazione NOT LIKE \'%CLASS "%\'',
		// ),
		
		// 'order'=>'Denominazione ASC'
		
		// ));
		
		
		
		
		// $this->set('squadres',$squadres);
		
		// $campionati = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome'),
		
		// 'conditions' => array(
		
		// 'Campionati.iscrizioni' => 1
		
		// )
		
		// ));
		
		// $campionatijson = $this->Campionati->find('all',array('fields'=>array('Campionati.Campionato','Campionati.Nome','Campionati.subscriptions'),
		
		// 'conditions' => array(
		
		// 'Campionati.iscrizioni' => 1
		
		// )
		
		// ));
		
		// $ret = array();
		// $retcampi = array();
		
		// foreach ($campionatijson as $campionato) {
		
		// $gironi = $this->Half->find('all',array('fields' => array('Half.GironeCampionato','Half.Descrizione'),'conditions' => array('Half.Campionato' => $campionato['Campionati']['Campionato'])));
		
		// $gr = array();
		
		// foreach ($gironi as $girone) {
		
		// $gr[] = array('id'=>$girone['Half']['GironeCampionato'],'nome'=>$girone['Half']['Descrizione']);
		
		// }
		
		// $ret[$campionato['Campionati']['Campionato']] = (array)unserialize($campionato['Campionati']['subscriptions']);
		
		// foreach ($ret[$campionato['Campionati']['Campionato']] as $girone => $riga) {
		
		
		// unset($riga['Campo'][count($riga['Campo'])-1]);
		// foreach ($riga['Campo'] as $campo) {
		
		
		// $c = $this->Campi->findByCampo($campo);
		
		// $retcampi[$campo] = $c['Campi']['Descrizione'];
		
		// }
		// }
		
		// $ret[$campionato['Campionati']['Campionato']]['CampoNome']
		
		// $campi = $this->Campi->find('list',array('fields' => array('Campi.Campo','Campi.Descrizione')));
		
		
		// $ret[$campionato['Campionati']['Campionato']]['gironi'] = $gr;
		// }
		
		// $this->set('campij',$retcampi);
		// $this->set('campionati',$campionati);
		// $this->set('campionatijson',$ret);
		
		// $giorni = array(
		
		// '1' => 'Lunedi',
		// '2' => 'Martedi',
		// '3' => 'Mercoledi',
		// '4' => 'Giovedi',
		// '5' => 'Venerdi',
		// '6' => 'Sabato',
		// '7' => 'Domenica'
		// );
		
		// $this->set('giorni',$giorni);
		
		// $this->render('add');
		
		// }
		
		
		
		
		//GIUSEPPE 18/10/2016 -------------------------
		
		
		function read_sport_championship()
		{
			$res = array();
			
			foreach($_POST['id'] as $id)
			{
				$qq = mysql_query("SELECT id_sport FROM Campionati WHERE Campionato = $id");
				
				$ret = mysql_fetch_assoc($qq);
				
				$obj = [ "id_championship" => $id,"id_sport" =>  $ret['id_sport']];
				
				$res[] = $obj;
			}
			
			echo  json_encode($res);			
			
			exit;
		}
		
		//---------------------------------------------
		
		
		function add() {
			
			$this->layout = "content";
			$user = $this->Session->read('Login.data');
			$model = 'User';
			
			if ($user['is_atleta'] == 1) $model = 'Athlete';
			
			$data = $this->{$model}->find('first', array(
			
			'conditions' => array(
			$model . '.' . $this->{$model}->primaryKey => $user['id'],
			),
			
			));
			$data_new = array();
			foreach ($data[$model] as $key => $value) {
				
				$data_new[ucfirst(Inflector::camelize($key))] = $value;
				
			}
			
			$data = $data_new;
			
			
			
			$prefill = array();
			
			if (isset($data['Nome'])) $prefill['SubscriptionNome0']=$data['Nome'];
			if (isset($data['Cognome'])) $prefill['SubscriptionCognome0']=$data['Cognome'];
			if (isset($data['DataNascita']) && $data['DataNascita'] != '0000-00-00') $prefill['SubscriptionData0']=date("d/m/Y",strtotime($data['DataNascita']));
			if (isset($data['Telefono'])) $prefill['SubscriptionTelefono0']=$data['Telefono'];
			if (isset($data['Cellulare'])) $prefill['SubscriptionCellulare0']=$data['Cellulare'];
			if (isset($data['NumeroDocumento'])) $prefill['SubscriptionNumerodoc0']=$data['NumeroDocumento'];
			if (isset($data['ScadenzaDocumento']) && $data['ScadenzaDocumento'] != '0000-00-00') $prefill['scadenza']=date("d/m/Y",strtotime($data['ScadenzaDocumento']));
			if (isset($data['LuogoNascita'])) $prefill['luogonascita']=$data['LuogoNascita'];
			if (isset($data['Email'])) $prefill['SubscriptionEmail0']=$data['Email'];
			if (isset($data['Username'])) $prefill['SubscriptionEmail0']=$data['Username'];
			if (isset($data['Localita'])) $prefill['SubscriptionComune0']=$data['Localita'];
			if (isset($data['Indirizzo'])) $prefill['SubscriptionVia0']=$data['Indirizzo'];
			if (isset($data['Provincia'])) $prefill['SubscriptionPv0']=$data['Provincia'];
			if (isset($data['Cap'])) $prefill['SubscriptionCap0']=$data['Provincia'];
			
			
			
			$prefill_ok = array();
			
			foreach ($prefill as $key => $value) $prefill_ok[] = array('key'=>$key,'value'=>$value);
			
			
			$this->set('prefill',$prefill_ok);
			
			//--- fin qui è per l'autenticazione //GIUSEPPE
			
			
			if (isset($this->data['Subscription'])) {
				
				
				
				$campionato = $this->Campionati->findByCampionato($this->data['Subscription']['campionato']);
				
				$this->set('campionato',$campionato);
				
				
				$girone = $this->Half->find('first',array('conditions'=>array('Half.GironeCampionato' => $this->data['Subscription']['girone'])));
				
				$this->set('girone',$girone);
				
				
				if (!empty($this->data['Subscription']['nomesquadra2'])) {
					
					
					$this->set('squadra',$this->Squadre->findBySquadra($this->data['Subscription']['nomesquadra2']));
					
					
				}
				
				$this->set('campo',$this->Campi->findByCampo($this->data['Subscription']['campo']));
				
				$giorni = array(
				
				'1' => 'Lunedi',
				'2' => 'Martedi',
				'3' => 'Mercoledi',
				'4' => 'Giovedi',
				'5' => 'Venerdi',
				'6' => 'Sabato',
				'7' => 'Domenica'
				);
				
				
				$this->set('giorno',$giorni[$this->data['Subscription']['giorno']]);
				
				$config     = $this->NewsletterConfig->find('first', array(
				'conditions' => array('NewsletterConfig.is_default' => 1),
				));
				
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
				
				$this->Email->subject = 'Nuova richiesta di iscrizione effettuata';
				$this->Email->template = 'signupchamp';
				
				// $this->Email->to = 'antonio.timmytag@gmail.com';
				
				// $this->set('index',0);
				
				// $this->Email->send();
				
				// Email di test
				
				//$this->Email->to = 'giuseppelag@gmail.com';
				
				$this->Email->to = 'info@midlandeuropa.com';
				
				$this->set('index',0);
				
				$this->Email->send();
				
				//$this->Email->to = 'info@timmytag.it';
				
				//$this->set('index',0);
				
				//$this->Email->send();
				
				for ($i = 0; $i < 15; $i++) {
					
					if (isset($this->data['Subscription']['email_'.$i]) && !empty($this->data['Subscription']['email_'.$i])) {
						$this->Email->to = $this->data['Subscription']['email_'.$i];
						$this->set('index',$i);
						$this->Email->send();
						
						
					}
					
				}
				
				
				$this->render('add_ok');
				
				
				
				return;
			}
			
			$squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
			
			'SquadraServizio'=> 0,
			'Denominazione NOT LIKE \'%CLASS GIR%\'',
			'Denominazione NOT LIKE \'%CLASS. GIR%\'',
			'Denominazione NOT LIKE \'%CLASS.%\'',
			'Denominazione NOT LIKE \'%L.D.A.%\'',
			'Denominazione NOT LIKE \'%CLASS "%\'',
			),
			
			'order'=>'Denominazione ASC'
			
			));
			
			
			
			
			//GIUSEPPE inserisco l'id_sport nei risultati della query
			
			$this->set('squadres',$squadres);
			
			$campionati = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome'),
			
			'conditions' => array(
			
			'Campionati.iscrizioni' => 1
			
			)
			
			));
			
			$campionatijson = $this->Campionati->find('all',array('fields'=>array('Campionati.Campionato','Campionati.Nome','Campionati.subscriptions'),
			
			'conditions' => array(
			
			'Campionati.iscrizioni' => 1
			
			)
			
			));
			
			
			//echo (json_encode($campionatijson ));
			
			//exit;
			
			$ret = array();
			$retcampi = array();
			
			foreach ($campionatijson as $campionato) {
				
				$gironi = $this->Half->find('all',array('fields' => array('Half.GironeCampionato','Half.Descrizione'),'conditions' => array('Half.Campionato' => $campionato['Campionati']['Campionato'])));
				
				$gr = array();
				
				foreach ($gironi as $girone) {
					
					$gr[] = array('id'=>$girone['Half']['GironeCampionato'],'nome'=>$girone['Half']['Descrizione']);
					
				}
				
				$ret[$campionato['Campionati']['Campionato']] = (array)unserialize($campionato['Campionati']['subscriptions']);
				
				foreach ($ret[$campionato['Campionati']['Campionato']] as $girone => $riga) {
					
					
					unset($riga['Campo'][count($riga['Campo'])-1]);
					foreach ($riga['Campo'] as $campo) {
						
						
						$c = $this->Campi->findByCampo($campo);
						
						$retcampi[$campo] = $c['Campi']['Descrizione'];
						
					}
				}
				
				//		$ret[$campionato['Campionati']['Campionato']]['CampoNome']
				
				//$campi = $this->Campi->find('list',array('fields' => array('Campi.Campo','Campi.Descrizione')));
				
				
				$ret[$campionato['Campionati']['Campionato']]['gironi'] = $gr;
			}
			
			$this->set('campij',$retcampi);
			$this->set('campionati',$campionati);
			$this->set('campionatijson',$ret);
			
			$giorni = array(
			
			'1' => 'Lunedi',
			'2' => 'Martedi',
			'3' => 'Mercoledi',
			'4' => 'Giovedi',
			'5' => 'Venerdi',
			'6' => 'Sabato',
			'7' => 'Domenica'
			);
			
			$this->set('giorni',$giorni);
			
			$this->render('add');
			
		}
		
		
		
		
		
		function tesseramenti() {
			
			$this->layout = "content";	
			
			
			if (@$_GET['step']==4) {
				
				$squadra = "";
				if (!empty($this->data['Subscription']['nomesquadra2']) && $this->data['Subscription']['nomesquadra2'] != 0) {
					
					$sq = $this->Squadre->findBySquadra($this->data['Subscription']['nomesquadra2']);
					$squadra = $sq['Squadre']['Denominazione'];
					
					
					$this->set('atleti',$this->getat($this->data['Subscription']['nomesquadra2']));
					
					} else {
					$squadra = $this->data['Subscription']['nomesquadra'];
				}
				
				$this->set('squadra',$squadra);
				
				$squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
				
				'SquadraServizio'=> 0,
				'Denominazione NOT LIKE \'%CLASS GIR%\'',
				'Denominazione NOT LIKE \'%CLASS. GIR%\'',
				'Denominazione NOT LIKE \'%CLASS.%\'',
				'Denominazione NOT LIKE \'%L.D.A.%\'',
				'Denominazione NOT LIKE \'%CLASS "%\'',
				),
				
				'order'=>'Denominazione ASC'
				
				));
				
				
				
				
				$this->set('squadres',$squadres);
				$this->set('TipiAssicurazione',$this->TipiAssicurazione->find('all', array( 'conditions' => 'TipiAssicurazione.TipoAssicurazione IN (1,2,3)','order' => array('TipiAssicurazione.Descrizione ASC'))));
				
			}
			
			if (@$_GET['step']==2) {
				
				
				$user = $this->Session->read('Login.data');
				$model = 'User';
				
				if ($user['is_atleta'] == 1) $model = 'Athlete';
				
				$data = $this->{$model}->find('first', array(
				
				'conditions' => array(
				$model . '.' . $this->{$model}->primaryKey => $user['id'],
				),
				
				));
				$data_new = array();
				foreach ($data[$model] as $key => $value) {
					
					$data_new[ucfirst(Inflector::camelize($key))] = $value;
					
				}
				
				$data = $data_new;
				
				
				
				$prefill = array();
				
				if (isset($data['Nome'])) $prefill['SubscriptionNome0']=$data['Nome'];
				if (isset($data['Cognome'])) $prefill['SubscriptionCognome0']=$data['Cognome'];
				if (isset($data['DataNascita']) && $data['DataNascita'] != '0000-00-00') $prefill['SubscriptionData0']=date("d/m/Y",strtotime($data['DataNascita']));
				if (isset($data['Telefono'])) $prefill['SubscriptionTelefono0']=$data['Telefono'];
				if (isset($data['Cellulare'])) $prefill['SubscriptionCellulare0']=$data['Cellulare'];
				if (isset($data['NumeroDocumento'])) $prefill['SubscriptionNumerodoc0']=$data['NumeroDocumento'];
				if (isset($data['ScadenzaDocumento']) && $data['ScadenzaDocumento'] != '0000-00-00') $prefill['scadenza']=date("d/m/Y",strtotime($data['ScadenzaDocumento']));
				if (isset($data['LuogoNascita'])) $prefill['luogonascita']=$data['LuogoNascita'];
				if (isset($data['Email'])) $prefill['SubscriptionEmail0']=$data['Email'];
				if (isset($data['Username'])) $prefill['SubscriptionEmail0']=$data['Username'];
				if (isset($data['Localita'])) $prefill['SubscriptionComune0']=$data['Localita'];
				if (isset($data['Indirizzo'])) $prefill['SubscriptionVia0']=$data['Indirizzo'];
				if (isset($data['Provincia'])) $prefill['SubscriptionPv0']=$data['Provincia'];
				if (isset($data['Cap'])) $prefill['SubscriptionCap0']=$data['Provincia'];
				
				
				
				$prefill_ok = array();
				
				foreach ($prefill as $key => $value) $prefill_ok[] = array('key'=>$key,'value'=>$value);
				
				
				$this->set('prefill',$prefill_ok);
				
				
				
				$squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
				
				'SquadraServizio'=> 0,
				'Denominazione NOT LIKE \'%CLASS GIR%\'',
				'Denominazione NOT LIKE \'%CLASS. GIR%\'',
				'Denominazione NOT LIKE \'%CLASS.%\'',
				'Denominazione NOT LIKE \'%L.D.A.%\'',
				'Denominazione NOT LIKE \'%CLASS "%\'',
				),
				
				'order'=>'Denominazione ASC'
				
				));
				
				
				
				
				$this->set('squadres',$squadres);
				
				
				//GIUSEPPE-------------------------------------------------------------
				
				$squadres_calcio = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
				
				'SquadraServizio'=> 0,
				'Denominazione NOT LIKE \'%CLASS GIR%\'',
				'Denominazione NOT LIKE \'%CLASS. GIR%\'',
				'Denominazione NOT LIKE \'%CLASS.%\'',
				'Denominazione NOT LIKE \'%L.D.A.%\'',
				'Denominazione NOT LIKE \'%CLASS "%\'',
				'id_sport' => 0
				),
				
				'order'=>'Denominazione ASC'
				
				));
				
				
				$this->set('squadres_calcio',$squadres_calcio);
				
				
				
				
				
				$squadres_tennis = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
				
				'SquadraServizio'=> 0,
				'Denominazione NOT LIKE \'%CLASS GIR%\'',
				'Denominazione NOT LIKE \'%CLASS. GIR%\'',
				'Denominazione NOT LIKE \'%CLASS.%\'',
				'Denominazione NOT LIKE \'%L.D.A.%\'',
				'Denominazione NOT LIKE \'%CLASS "%\'',
				'id_sport' => 1
				),
				
				'order'=>'Denominazione ASC'
				
				));
				
				
				$this->set('squadres_tennis',$squadres_tennis);
				
				
				//---------------------------------------------------------------------
				
				
				
				$campionati = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome'),
				
				'conditions' => array(
				
				'Campionati.iscrizioni' => 1
				
				)
				
				));
				
				//GIUSEPPE-------------------------------------------
				
				$campionati_calcio = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome'),
				
				'conditions' => array(
				
				'Campionati.iscrizioni' => 1
				,'Campionati.id_sport' => 0
				)
				
				));
				
				
				$campionati_tennis = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome'),
				
				'conditions' => array(
				
				'Campionati.iscrizioni' => 1
				,'Campionati.id_sport' => 1
				)
				
				));
				
				//$campionati =  $this->Campionati->query("SELECT id_sport,Campionato,Nome FROM Campionati WHERE iscrizioni = 1");
				//----------------------------------------------------------
				
				
				$campionatijson = $this->Campionati->find('all',array('fields'=>array('Campionati.Campionato','Campionati.Nome','Campionati.subscriptions'),
				
				'conditions' => array(
				
				'Campionati.iscrizioni' => 1
				
				)
				
				));
				
				$ret = array();
				
				$retcampi = array();
				
				foreach ($campionatijson as $campionato) {
					
					$gironi = $this->Half->find('all',array('fields' => array('Half.GironeCampionato','Half.Descrizione'),'conditions' => array('Half.Campionato' => $campionato['Campionati']['Campionato'])));
					
					$gr = array();
					
					foreach ($gironi as $girone) {
						
						$gr[] = array('id'=>$girone['Half']['GironeCampionato'],'nome'=>$girone['Half']['Descrizione']);
						
					}
					
					$ret[$campionato['Campionati']['Campionato']] = (array)unserialize($campionato['Campionati']['subscriptions']);
					
					foreach ($ret[$campionato['Campionati']['Campionato']] as $girone => $riga) {
						
						
						unset($riga['Campo'][count($riga['Campo'])-1]);
						foreach ($riga['Campo'] as $campo) {
							
							
							$c = $this->Campi->findByCampo($campo);
							
							$retcampi[$campo] = $c['Campi']['Descrizione'];
							
						}
					}
					
					//		$ret[$campionato['Campionati']['Campionato']]['CampoNome']
					
					//$campi = $this->Campi->find('list',array('fields' => array('Campi.Campo','Campi.Descrizione')));
					
					
					$ret[$campionato['Campionati']['Campionato']]['gironi'] = $gr;
				}
				
				$this->set('campij',$retcampi);
				$this->set('campionati',$campionati);
				$this->set('campionatijson',$ret);
				
				//GIUSEPPE ----------------------------------------
				$this->set('campionati_calcio',$campionati_calcio);
				$this->set('campionati_tennis',$campionati_tennis);
				//--------------------------------------------------
				
				$giorni = array(
				
				'1' => 'Lunedi',
				'2' => 'Martedi',
				'3' => 'Mercoledi',
				'4' => 'Giovedi',
				'5' => 'Venerdi',
				'6' => 'Sabato',
				'7' => 'Domenica'
				);
				
				$this->set('giorni',$giorni);
				
				
			}
			
			
			
			if (@$_GET['step']==3) {
				
				
				
				
			}
			
			// if (@$_GET['step']==4) {
			
			// $squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
			
			// 'SquadraServizio'=> 0,
			// 'Denominazione NOT LIKE \'%CLASS GIR%\'',
			// 'Denominazione NOT LIKE \'%CLASS. GIR%\'',
			// 'Denominazione NOT LIKE \'%CLASS.%\'',
			// 'Denominazione NOT LIKE \'%L.D.A.%\'',
			// 'Denominazione NOT LIKE \'%CLASS "%\'',
			// 'Denominazione NOT LIKE \'%CLASS %\'',
			// 'sport' => $_GET['sport'],
			// ),
			
			// 'order'=>'Denominazione ASC'
			
			// ));
			
			// $this->set('squadres',$squadres);
			
			// }
			
			
			if (@$_GET['step']==4) { //GIUSEPPE filtro squadre in base al campionato dell'anno in corso e al tipo di sport
				
				
				$query = "SELECT MAX(AnnoSportivo) As AnnoInCorso FROM `AnniSportivi`";
				
				$q = mysql_query($query);
				
				$anno_sportivo = mysql_fetch_array($q)[0];
				
				
				// $squadres = $this->Squadre->find('list', array(
				// 'joins' => array(
				// array(
				// 'table' => 'AnnuarioSquadre',
				// 'type' => 'INNER',
				// 'conditions' => array(
				// 'Squadre.Squadra = AnnuarioSquadre.Squadra'
				// )
				// )
				// ),
				// 'conditions' => array(
				// 'Squadre.SquadraServizio'=> 0,
				// 'Squadre.Denominazione NOT LIKE \'%CLASS GIR%\'',
				// 'Squadre.Denominazione NOT LIKE \'%CLASS. GIR%\'',
				// 'Squadre.Denominazione NOT LIKE \'%CLASS.%\'',
				// 'Squadre.Denominazione NOT LIKE \'%L.D.A.%\'',
				// 'Squadre.Denominazione NOT LIKE \'%CLASS "%\'',
				// 'Squadre.Denominazione NOT LIKE \'%CLASS %\'',
				// 'Squadre.sport' => $_GET['sport'],
				// 'AnnuarioSquadre.AnnoSportivo' => $anno_sportivo,
				// ),
				// 'fields' => array('Squadre.Squadra','Squadre.Denominazione'),
				// 'order'=>'Squadre.Denominazione ASC'
				// ));
				
				
				$squadres = array();
				
				$sql_query = "";
				
				if($_GET['sport']=="CALCIO")
				{
					$sql_query = "SELECT DISTINCT Squadre.Squadra,Squadre.Denominazione FROM `SquadreCampionati`
					INNER JOIN Campionati 
					ON SquadreCampionati.Campionato = Campionati.Campionato
					INNER JOIN Squadre
					ON SquadreCampionati.Squadra = Squadre.Squadra
					WHERE Campionati.AnnoSportivo = '".$anno_sportivo."' AND Squadre.sport = '".$_GET['sport']."' AND Squadre.SquadraServizio = 0 ORDER BY Squadre.Denominazione ASC";
				}
				else if($_GET['sport']=="TENNIS")
				{
					$sql_query = "SELECT DISTINCT Squadre.Squadra,Squadre.Denominazione FROM `Squadre`
					WHERE  Squadre.sport = '".$_GET['sport']."'  AND Squadre.SquadraServizio = 0  ORDER BY Squadre.Denominazione ASC";
				}
				
				//echo $sql_query ;
				
				
				$q = mysql_query($sql_query);
				
				
				while($row = mysql_fetch_assoc($q)) {
					//echo "id: " . $row["Squadra"]. " - Name: " . $row["Denominazione"] ."<br>";
					$squadres[$row["Squadra"]]=$row["Denominazione"];
					
				}
				//print_r($squadres);
				//print_r(mysql_fetch_array($q));
				
				//print_r($sql_query);
				
				$this->set('squadres',$squadres);
				
			}
		
		}
			
			
			
			
			
			
			function tesseramenti2() {
			
			$this->layout = "content";
			
			$user = $this->Session->read('Login.data');
			$model = 'User';
			
			if ($user['is_atleta'] == 1) $model = 'Athlete';
			
			$data = $this->{$model}->find('first', array(
			
			'conditions' => array(
			$model . '.' . $this->{$model}->primaryKey => $user['id'],
			),
			
			));
			$data_new = array();
			foreach ($data[$model] as $key => $value) {
			
			$data_new[ucfirst(Inflector::camelize($key))] = $value;
			
			}
			
			$data = $data_new;
			
			
			
			
			
			$prefill = array();
			
			if (isset($data['Nome'])) $prefill['SubscriptionNome0']=$data['Nome'];
			if (isset($data['Cognome'])) $prefill['SubscriptionCognome0']=$data['Cognome'];
			if (isset($data['DataNascita']) && $data['DataNascita'] != '0000-00-00') $prefill['SubscriptionData0']=date("d/m/Y",strtotime($data['DataNascita']));
			if (isset($data['Telefono'])) $prefill['SubscriptionTelefono0']=$data['Telefono'];
			if (isset($data['Cellulare'])) $prefill['SubscriptionCellulare0']=$data['Cellulare'];
			if (isset($data['NumeroDocumento'])) $prefill['SubscriptionNumerodoc0']=$data['NumeroDocumento'];
			if (isset($data['ScadenzaDocumento']) && $data['ScadenzaDocumento'] != '0000-00-00') $prefill['scadenza']=date("d/m/Y",strtotime($data['ScadenzaDocumento']));
			if (isset($data['LuogoNascita'])) $prefill['luogonascita']=$data['LuogoNascita'];
			if (isset($data['Email'])) $prefill['SubscriptionEmail0']=$data['Email'];
			if (isset($data['Username'])) $prefill['SubscriptionEmail0']=$data['Username'];
			if (isset($data['Localita'])) $prefill['SubscriptionComune0']=$data['Localita'];
			if (isset($data['Indirizzo'])) $prefill['SubscriptionVia0']=$data['Indirizzo'];
			if (isset($data['Provincia'])) $prefill['SubscriptionPv0']=$data['Provincia'];
			if (isset($data['Cap'])) $prefill['SubscriptionCap0']=$data['Provincia'];
			
			
			
			$prefill_ok = array();
			
			foreach ($prefill as $key => $value) $prefill_ok[] = array('key'=>$key,'value'=>$value);
			
			
			$this->set('prefill',$prefill_ok);
			
			
			
			
			
			if (isset($this->data['Subscription']) || 1==1) {
			
			
			$squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
			
			'SquadraServizio'=> 0,
			'Denominazione NOT LIKE \'%CLASS GIR%\'',
			'Denominazione NOT LIKE \'%CLASS. GIR%\'',
			'Denominazione NOT LIKE \'%CLASS.%\'',
			'Denominazione NOT LIKE \'%L.D.A.%\'',
			'Denominazione NOT LIKE \'%CLASS "%\'',
			),
			
			'order'=>'Denominazione ASC'
			
			));
			
			
			
			
			$this->set('squadres',$squadres);
			
			
			$campionato = $this->Campionati->findByCampionato($this->data['Subscription']['campionato']);
			
			$this->set('campionato',$campionato);
			
			
			$girone = $this->Half->find('first',array('conditions'=>array('Half.GironeCampionato' => $this->data['Subscription']['girone'])));
			
			$this->set('girone',$girone);
			
			
			if (!empty($this->data['Subscription']['nomesquadra2'])) {
			
			
			$this->set('squadra',$this->Squadre->findBySquadra($this->data['Subscription']['nomesquadra2']));
			
			
			}
			
			$this->set('campo',$this->Campi->findByCampo($this->data['Subscription']['campo']));
			
			$giorni = array(
			
			'1' => 'Lunedi',
			'2' => 'Martedi',
			'3' => 'Mercoledi',
			'4' => 'Giovedi',
			'5' => 'Venerdi',
			'6' => 'Sabato',
			'7' => 'Domenica'
			);
			
			
			$this->set('giorno',$giorni[$this->data['Subscription']['giorno']]);
			/*
			$config     = $this->NewsletterConfig->find('first', array(
			'conditions' => array('NewsletterConfig.is_default' => 1),
			));
			
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
			
			$this->Email->subject = 'Nuova richiesta di iscrizione effettuata';
			$this->Email->template = 'signupchamp';
			
			$this->Email->to = 'antonio.timmytag@gmail.com';
			
			$this->set('index',0);
			
			$this->Email->send();
			
			$this->Email->to = 'lucamare@midlandeuropa.com';
			
			$this->set('index',0);
			
			$this->Email->send();
			
			for ($i = 0; $i < 3; $i++) {
			
			if (isset($this->data['Subscription']['email_'.$i]) && !empty($this->data['Subscription']['email_'.$i])) {
			$this->Email->to = $this->data['Subscription']['email_'.$i];
			$this->set('index',$i);
			$this->Email->send();
			
			
			}
			
			}
			
			*/
			
			
			
			$squadra = "";
			if (!empty($this->data['Subscription']['nomesquadra2']) && $this->data['Subscription']['nomesquadra2'] != 0) {
			
			$sq = $this->Squadre->findBySquadra($this->data['Subscription']['nomesquadra2']);
			$squadra = $sq['Squadre']['Denominazione'];
			
			
			$this->set('atleti',$this->getat($this->data['Subscription']['nomesquadra2']));
			
			} else {
			$squadra = $this->data['Subscription']['nomesquadra'];
			}
			
			$this->set('squadra',$squadra);
			
			
			$this->set('TipiAssicurazione',$this->TipiAssicurazione->find('all', array( 'order' => array('TipiAssicurazione.Descrizione ASC'))));
			
			
			$this->render('add_step_2');
			
			
			
			
			
			return;
			}
			
			$squadres = $this->Squadre->find('list',array('fields'=>array('Squadra','Denominazione'),'conditions'=>array(
			
			'SquadraServizio'=> 0,
			'Denominazione NOT LIKE \'%CLASS GIR%\'',
			'Denominazione NOT LIKE \'%CLASS. GIR%\'',
			'Denominazione NOT LIKE \'%CLASS.%\'',
			'Denominazione NOT LIKE \'%L.D.A.%\'',
			'Denominazione NOT LIKE \'%CLASS "%\'',
			),
			
			'order'=>'Denominazione ASC'
			
			));
			
			
			//GIUSEPPE aggiunti gli id sport per un successivo filtraggio
			
			$this->set('squadres',$squadres);
			
			$campionati = $this->Campionati->find('list',array('fields'=>array('Campionati.Campionato','Campionati.Nome','Campionati.id_sport'),
			
			'conditions' => array(
			
			'Campionati.iscrizioni' => 1,
			
			)
			
			));
			
			$campionatijson = $this->Campionati->find('all',array('fields'=>array('Campionati.Campionato','Campionati.Nome','Campionati.subscriptions','Campionati.id_sport'),
			
			'conditions' => array(
			
			'Campionati.iscrizioni' => 1
			
			)
			
			));
			
			
			
			$ret = array();
			
			$retcampi = array();
			
			foreach ($campionatijson as $campionato) {
			
			$gironi = $this->Half->find('all',array('fields' => array('Half.GironeCampionato','Half.Descrizione'),'conditions' => array('Half.Campionato' => $campionato['Campionati']['Campionato'])));
			
			$gr = array();
			
			foreach ($gironi as $girone) {
			
			$gr[] = array('id'=>$girone['Half']['GironeCampionato'],'nome'=>$girone['Half']['Descrizione']);
			
			}
			
			$ret[$campionato['Campionati']['Campionato']] = (array)unserialize($campionato['Campionati']['subscriptions']);
			
			foreach ($ret[$campionato['Campionati']['Campionato']] as $girone => $riga) {
			
			
			unset($riga['Campo'][count($riga['Campo'])-1]);
			foreach ($riga['Campo'] as $campo) {
			
			
			$c = $this->Campi->findByCampo($campo);
			
			$retcampi[$campo] = $c['Campi']['Descrizione'];
			
			}
			}
			
			//		$ret[$campionato['Campionati']['Campionato']]['CampoNome']
			
			//$campi = $this->Campi->find('list',array('fields' => array('Campi.Campo','Campi.Descrizione')));
			
			
			$ret[$campionato['Campionati']['Campionato']]['gironi'] = $gr;
			}
			
			$this->set('campij',$retcampi);
			$this->set('campionati',$campionati);
			$this->set('campionatijson',$ret);
			
			$giorni = array(
			
			'1' => 'Lunedi',
			'2' => 'Martedi',
			'3' => 'Mercoledi',
			'4' => 'Giovedi',
			'5' => 'Venerdi',
			'6' => 'Sabato',
			'7' => 'Domenica'
			);
			
			$this->set('giorni',$giorni);
			
			$this->render('add2');
			
			}
			
			
			
			
			function admin_index() {
			
			
			}
			
			function admin_edit($id) {
			
			
			if (isset($this->data['Gironi'])) {
			
			
			$dati = serialize($this->data['Gironi']);
			
			$this->Campionati->query("UPDATE Campionati SET subscriptions = '" . @mysql_real_escape_string($dati) . "' WHERE Campionato = $id");
			
			}
			
			$campionato = $this->Campionati->findByCampionato($id);
			
			//print_r($campionato);
			$iscrizioni = unserialize($campionato['Campionati']['subscriptions']);
			$iscrizioni = (array)($iscrizioni);
			if (is_array($iscrizioni) && count($iscrizioni)) {
			
			
			$this->set('iscrizioni',$iscrizioni);
			
			}
			
			$real_id = $id;
			
			$this->layout = "ajax";
			
			
			$giorni = array(
			
			'1' => 'Lunedi',
			'2' => 'Martedi',
			'3' => 'Mercoledi',
			'4' => 'Giovedi',
			'5' => 'Venerdi',
			'6' => 'Sabato',
			'7' => 'Domenica'
			);
			
			
			$orari = array();
			
			for($i=0;$i<=23;$i++) {
			
			$orari[] = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
			
			$orari[] = str_pad($i, 2, "0", STR_PAD_LEFT) . ":10";
			$orari[] = str_pad($i, 2, "0", STR_PAD_LEFT) . ":20";
			$orari[] = str_pad($i, 2, "0", STR_PAD_LEFT) . ":30";
			
			$orari[] = str_pad($i, 2, "0", STR_PAD_LEFT) . ":45";
			}
			
			$this->set('giorni',$giorni);
			$this->set('orari',$orari);
			
			$campi = $this->Campi->find('list',array('fields' => array('Campi.Campo','Campi.Descrizione'),'order'=>'Campi.Descrizione ASc'));
			
			$real_campi = array();
			
			foreach ($campi as $id => $campo) $real_campi[]=array('id'=>$id,'campo'=>$campo);
			$id = $real_id;
			$this->set('campi',$real_campi);
			$this->set('orari',$orari);
			$this->set('giorni',$giorni);
			$this->set('campionato',$campionato);
			$this->set('gironi',$this->Half->find('all',array('conditions' => array('Half.Campionato' => $id))));
			
			}
			
			}
			
						