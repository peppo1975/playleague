<?

App::import('Vendor', 'php-excel-reader/excel_reader2');

	class TestsController extends AppController {
	
			var $name = "Tests";
			var $login_required = true;
			var $helpers = array('Backend','fpdf','xls','Excel','Xml');
			var $uses = array('Upload','Group','ChampCategory','AnniSportivi','Right','Matchgoal','SquadreCampionati','Athlete','NewsletterUser','NewsletterGroupUser','NewsletterGroupUser','Yearbook','Campionati','SquadreCampionati','Squadre','Campi','Lda','Match');
			var $components = array('RequestHandler');
			
			//var $FPDF = null;
			
			//var $uses = array('Campi');
			
			function albo_test() {
				
				$this->layout = "ajax";
				
				//Anno sportivo corrente
				$anno = $this->AnniSportivi->find('list', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
				$anno = $anno[key($anno)];				
				
				//Campionati anno sportivo corrente
				$campionati_tmp = $this->Campionati->find('list', array(
				
					'fields' => array(
						'Campionati.Campionato',
						'Campionati.Nome',
						'Campionati.Tipo',
						
					),
					'conditions' => array(
					
						'Campionati.group_id' => 1,	
						'Campionati.SquadraCampionato_id',
						'Campionati.Categoria !=' => 0,			
					
					),
					'order' => array('Campionati.AnnoSportivo' => 'DESC', 'Campionati.Tipo ASC', 'Campionati.SessoTipo ASC', 'Campionati.Nome ASC'),
				
				));
				
				//debug($campionati_tmp);
				
				$campionati = array();
				
				$tipo_arr  = array(0 => 'Calcio a 5', 1 => 'Calcio a 7');
				$sessoTipo = array(0 => 'Maschile', 1 => 'Femminile', 2 => 'Misto');
				
				foreach($campionati_tmp as $tipo => $campionati_tipo_tmp) {
					foreach($campionati_tipo_tmp as $id_campionato => $campionato_tmp) {
						
						$campionato = $this->Campionati->find('first', array(
						
							'fields' => array(
							
								'Campionati.Campionato',
								'Campionati.AnnoSportivo',
								'Campionati.Nome',
								'Campionati.Tipo',
								'Campionati.SessoTipo',
								'Campionati.SquadraCampionato_id',
								'Campionati.Categoria',
							
							),
							'conditions' => array(
								'Campionati.Campionato' => $id_campionato,
							),
							'recursive' => -1,
						
						));
						
						$champCategory = $this->ChampCategory->find('first', array(
						
							'fields' => array(
								'ChampCategory.id',
								'ChampCategory.Nome',
							),
							'conditions' => array(
								'ChampCategory.id' => $campionato['Campionati']['Categoria']
							),
							'recursive' => -1,
						
						));
						
						//$campionati[$tipo.'|'.$tipo_arr[$tipo]][$campionato['Campionati']['AnnoSportivo']][$campionato['Campionati']['SessoTipo'].'|'.$sessoTipo[$campionato['Campionati']['SessoTipo']]][] = $campionato;	
						$campionati[$tipo_arr[$tipo]][$sessoTipo[$campionato['Campionati']['SessoTipo']]][$champCategory['ChampCategory']['Nome']][$campionato['Campionati']['AnnoSportivo']][$id_campionato] = $campionato;
					}					
				}
				
				debug($campionati);
				
				
				
				exit;
				
			}
			
			function albo_doro() {
				
				$this->layout = "content";
				
			}
			
			function importCsv($filename = 'posts.csv') {

				$filename = TMP . 'uploads' . DS . 'csv' . DS . $filename;
			
				// open the file
		 		$handle = fopen($filename, "r");
	 		
				$data = array();
	
		 		// read each data row in the file
		 		while (($row = fgetcsv($handle)) !== FALSE) {
		
				
		
					if(isset($row[28]) && !empty($row[28])) {
		
					$data[]['NewsletterUser'] = array(
					
						'name' => $row[1],
						'surname' => $row[3],
						'email' => $row[28],
					
					);
					
					}
		
		 		}
	 		
		 		// close the file
		 		fclose($handle);
		 		
		 		debug($data);
	 		
		 		// return the messages
		 		return $data;
				
			}
			
			function createNewsletterUserFromCsv($filename = 'posts.csv') {
				
				Configure::Write('debug',2);
				$data = $this->importCsv($filename);
				
				$athletes       = count($data);
				$athletes_saved = 0;
				$blonde = 0;
				foreach($data as $user) {
					
					$this->data = $user;
					
					$this->NewsletterUser->create();
					$this->NewsletterUser->set($this->data);
					
					if($this->NewsletterUser->save()) {
						$athletes_saved++;
						$this->NewsletterGroupUser->create();
						$this->NewsletterGroupUser->set('newsletter_group_id', 8);
						$this->NewsletterGroupUser->set('newsletter_user_id', $this->NewsletterUser->id);
						$this->NewsletterGroupUser->save();
					} else {
					
						debug($this->NewsletterUser->validationErrors);
			
			
							$morr = $this->NewsletterUser->findByEmail($this->data['NewsletterUser']['email']);
							
							if ($morr != null) {
							$blonde++;						
							$athletes_saved++;
							$this->NewsletterGroupUser->create();
							$this->NewsletterGroupUser->set('newsletter_group_id', 8);
							$this->NewsletterGroupUser->set('newsletter_user_id', $morr['NewsletterUser']['id']);
							$this->NewsletterGroupUser->save();					
							}
					}
					
				}
				
				debug('Totale record trovati: ' . $athletes);
				debug('Totale record salvati: ' . $athletes_saved);
			
				exit;
				
			}
			
			function createUserNewsletter($arbitro = 'No') {
				
				Configure::Write('debug',2);
				
				$groups = array(
				
					'Si'  => 2,
					'No'  => 3,
				
				);
				
				$athletes = $this->Athlete->find('all', array(
				
					'fields' => array(
						'Athlete.Cognome',
						'Athlete.Nome',
						'Athlete.Localita',
						'Athlete.Email',
						'Athlete.Indirizzo',
						'Athlete.Fax',
						'Athlete.Telefono',
						'Athlete.Cellulare'
					),
					'conditions' => array(
						'Athlete.Arbitro' => $arbitro
					),
					'recursive' => -1,
				
				));
				
				$tot_atleti = count($athletes);
				$athletes_saved = 0;
				
				foreach($athletes as $ath) {
					$ath = $ath['Athlete'];
					$this->NewsletterUser->create();
					$this->NewsletterUser->set('name', $ath['Nome']);
					$this->NewsletterUser->set('surname', $ath['Cognome']);
					$this->NewsletterUser->set('city', $ath['Localita']);
					$this->NewsletterUser->set('address', $ath['Indirizzo']);
					$this->NewsletterUser->set('tel', $ath['Telefono']);
					$this->NewsletterUser->set('cel', $ath['Cellulare']);
					$this->NewsletterUser->set('fax', $ath['Fax']);
					$this->NewsletterUser->set('email', $ath['Email']);
					if($this->NewsletterUser->save()) {
						$athletes_saved++;
						$this->NewsletterGroupUser->create();
						$this->NewsletterGroupUser->set('newsletter_group_id', $groups[$arbitro]);
						$this->NewsletterGroupUser->set('newsletter_user_id', $this->NewsletterUser->id);
						$this->NewsletterGroupUser->save();
					} 
				}
				
				debug('Totale atleti trovati: ' . $tot_atleti);
				debug('Totale atleti salvati: ' . $athletes_saved);
				
				exit;
				
			}
			
			function admin_index() {
			

			
			} 
			
			function admin_rankings() {
			
				// $marcatori = $this->Matchgoal->find('all', array(
				
					// 'conditions' => array(
					
						// 'SquadreCampionati.Campionato' => 30,
						// 'SquadreCampionati.GironeCampionato' => 46
					
					// ),
					// 'group' => 'Matchgoal.Atleta'
				
				// ));
				
				$classifica_marcatori = $this->Matchgoal->query(
				
					"SELECT 

					(SELECT Squadre.Denominazione FROM Squadre WHERE Squadre.Squadra IN (SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = GoalPartite.SquadraCampionato)) as nomesquadra,
					(SELECT CONCAT(Cognome,' ',Nome) FROM Atleti WHERE Atleti.Atleta = GoalPartite.Atleta) as anagrafica, 
					SUM(GoalPartite.Goal) as goals FROM GoalPartite 
					WHERE Calendario IN (SELECT Calendario FROM Calendari WHERE Calendari.Campionato = 35 AND Calendari.GironeCampionato = 69) 
					GROUP BY GoalPartite.Atleta ORDER BY goals DESC"
				
				);
				
				$this->set('classifica_marcatori', $classifica_marcatori);
			
			}
			
			function file() {
			
				$this->RequestHandler->setContent('xls', 'application/vnd.ms-excel'); 
			
			}
			
			function admin_xls() {
			
				$this->layout = "xls";
				
				$this->set('table', $this->params['pass']);
			
			}
			
			function admin_prova() {
			
				$this->layout = "ajax";
								
				$this->set('array', $this->Upload->find('all'));
			
			}
			
			function admin_blob() {

				$athletes = $this->Athlete->find('first', array(
				
					'conditions' => array('Athlete.Atleta' => 12320),
				
				));
				
				$this->set('athletes', $athletes);
		
			}
			
			function xml() {
			
				$this->layout = "ajax";
				
				   App::import('Xml');

					//your XML file's location
					$file = APP . "/vars/menu.xml";

					//now parse it
					$parsed_xml =& new XML($file);
					$parsed_xml = Set::reverse($parsed_xml); // this is what i call magic

					//see the returned array
					
					unset($parsed_xml['Menu']['Home']);
					
					App::import('Helper', 'Xml');
					$xml = new XmlHelper();
					
					$files = $xml->header(); // We need the XML header before our data.
					$files .= $xml->serialize($parsed_xml, array('whitespace' => true));
					 
					// Write the file.
					$xmlFile = fopen($file, 'w');
					fwrite($xmlFile, $files);
					fclose($xmlFile);					
			
			}
			
			function test_menu() {
			
				$this->layout = "ajax";
			
			}
			
			function provaJson() {
			
				$this->layout = "ajax";
				
				$giorno     = date('w');
				
				$dodici     = strtotime(date("Y-m-d") . " 09:30:00");
				$mezzanotte = strtotime(date("Y-m-d") . " 23:59:59");
				$adesso     = time();
				
				$current    = date("Y-m-d");
				
				if($giorno == 4) { // se � sabato
				
					if($adesso >= $dodici && $adesso <= $mezzanotte) {
					
						$array = array('Prova','Prova2','Prova3');
						file_put_contents(APP . "/webroot/files/json/espulsi.json", json_encode($array));
					
					} else {
					
						$array = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi.json"),1);
					
					}
				
				} else {
				
					$array = json_decode(file_get_contents(APP . "/webroot/files/json/espulsi.json"),1);
				
				}
				
			}
			
			function script_annuario($id) {
			
				$this->layout = "ajax";
				
				$data = $this->Yearbook->find('all', array(
				
					'conditions' => array('SquadreCampionati.Campionato' => $id),
				
				));
				
				debug(count($data));
				
				foreach($data as $annuario) {
				
					if($annuario['SquadreCampionati']['AnnoCampionato'] != $annuario['Yearbook']['AnnoSportivo']) {
					
						$this->Yearbook->query("DELETE FROM Annuario WHERE Annuario.Annuario = '".$annuario['Yearbook']['Annuario']."'");
						
						debug('eliminato');
						
					}
				
				}
				
			}
			
 function read_excel_anagrafica() {
 	
 	$this->layout = "ajax";
 	
 	$saved = 0;
 	$saved_athlete = 0;
 	
	$data = new Spreadsheet_Excel_Reader('anagrafica.xls', true);
	$temp = $data->dumptoarray();
	
	//debug($temp);
	
	$head_table = $temp[1];
	unset($temp[1]);
	
	//debug($head_table);
	
	$head_table = array(
	
		1  => 'Yearbook|AnnoSportivo',
		2  => 'Yearbook|Tessera',
		3  => 'Yearbook|DataVidimazione',
		4  => 'Yearbook|SquadraCampionato',
		5  => 'Yearbook|Responsabile',
		6  => 'Yearbook|isAdmin',
		7  => 'Yearbook|TipoAssicurazione',
		8  => 'Yearbook|Note',
		9  => 'Yearbook|NumeroMaglia',
		10 => 'Yearbook|Ruolo',
		11 => 'Yearbook|Giovanili',
		12 => 'Athlete|Cognome',
		13 => 'Athlete|Nome',
		14 => 'Athlete|DataNascita',
		15 => 'Athlete|LuogoNascita',
		16 => 'Athlete|SessoM',
		17 => 'Athlete|SessoF',
		18 => 'Athlete|TipoDocumento',
		19 => 'Athlete|Indirizzo',
		20 => 'Athlete|Cap',
		21 => 'Athlete|Localita',
		22 => 'Athlete|Provincia',
		23 => 'Athlete|SportivoSi',
		24 => 'Athlete|SportivoNo',
		25 => 'Athlete|ArbitroSi',
		26 => 'Athlete|ArbitroNo',
		27 => 'Athlete|NumeroDocumento',
		28 => 'Athlete|Telefono',
		29 => 'Athlete|Cellulare',
		30 => 'Athlete|Lavoro',
		31 => 'Athlete|Email',
		32 => 'Athlete|Fax',
		33 => 'Athlete|ScadenzaDocumento',
	
	);
	
	$athletes = array();
	
	foreach($temp as $athlete) {
		
		foreach($athlete as $key => $field) {
			
			list($model, $value) = explode('|', $head_table[$key]);
			
			$tmp_athletes[$model][$value] = $field;
			
		}
		
		$tmp_athletes['Yearbook']['DataVidimazione'] = '2011-10-04';
		$tmp_athletes['Athlete']['Arbitro']   = 'No';
		$tmp_athletes['Athlete']['Sportivo']  = 'Si';
		
		if($tmp_athletes['Athlete']['SessoM'] == 1) $tmp_athletes['Athlete']['Sesso'] = 'Maschio';
		else 										$tmp_athletes['Athlete']['Sesso'] = 'Femmina'; 
		
		/* Nome squadra e Nome campionato */
		
		list($squadra, $campionato) = explode('/', $tmp_athletes['Yearbook']['SquadraCampionato']);
		
		$squadra 	= trim($squadra);
		$campionato = trim($campionato);
		
		/* Cerco id squadra */
		
		$tmp_squadra = $this->Squadre->find('first', array(
		
			'conditions' => array('Squadre.Denominazione' => $squadra, 'Squadre.group_id' => 5),
		
		));
		
		/* Cerco id campionato */
		
		$tmp_campionato = $this->Campionati->find('first', array(
		
			'conditions' => array('Campionati.Nome' => $campionato, 'Campionati.group_id' => 5),
		
		));		
		
		/* Cerco squadra campionato */
		
		$squadra_campionato = $this->SquadreCampionati->find('first', array(
		
			'conditions' => array(

				'SquadreCampionati.Campionato' => $tmp_campionato['Campionati']['Campionato'], 
				'SquadreCampionati.Squadra'    => $tmp_squadra['Squadre']['Squadra'], 
				'SquadreCampionati.group_id'   => 5
				
			),
			'fields' => array('SquadreCampionati.SquadraCampionato'),
		
		));
		
		list($giorno,$mese,$anno) = explode('/', $tmp_athletes['Athlete']['DataNascita']);
		
		$tmp_athletes['Athlete']['DataNascita'] = $anno . '-' . $mese . '-' . $giorno;
		$tmp_athletes['Athlete']['group_id']  = 5;
		$tmp_athletes['Yearbook']['group_id'] = 5;
		$tmp_athletes['Yearbook']['NomeSquadraCampionato'] = $tmp_athletes['Yearbook']['SquadraCampionato'];	
		$tmp_athletes['Yearbook']['SquadraCampionato'] = $squadra_campionato['SquadreCampionati']['SquadraCampionato'];
		$tmp_athletes['Yearbook']['TipoAssicurazione'] = 7;	
		$athletes[]							  = $tmp_athletes;

		/* Inserisco nel database */
		
		/* Creo atleta */
		
		$this->Athlete->create();
		$this->data = $tmp_athletes['Athlete'];
		$this->Athlete->set($this->data);
		
		$this->Athlete->unbindValidation('remove', array('Email','Cap','Provincia','Sportivo','Arbitro','Responsabile','Sesso','Cognome','Nome') ,false);
		$this->Yearbook->unbindValidation('remove', array('SquadraCampionato') ,false);
		
		if($this->Athlete->save()) {
			
			$this->Yearbook->create();
			$athlete_old = $this->data;
			unset($this->data);
			$tmp_athletes['Yearbook']['Atleta'] = $this->Athlete->id;
			
			if($tmp_athletes['Yearbook']['SquadraCampionato'] == '') $tmp_athletes['Yearbook']['SquadraCampionato'] = 0;
			
			$this->data = $tmp_athletes['Yearbook'];
			
			$this->Yearbook->set($this->data);
			
			if($this->Yearbook->save()) {
				
				$saved ++;
				
			} 
			
			$saved_athlete ++;
			
		}
		
	}
	
	debug('Sono stati salvati: ' . $saved_athlete . ' record in anagrafica.');
	debug('Sono stati salvati: ' . $saved . ' record in annuario.');
	
  }
  
  function read_excel() {
  	
 	$this->layout = "ajax";
 	
 	$saved_match = 0;
 	$saved_lda   = 0;
 	
	$data = new Spreadsheet_Excel_Reader('calendario.xls', true);
	$temp = $data->dumptoarray();
	
	//debug($temp);
	
	$head_table = $temp[1];
	unset($temp[1]);  	
	
	$head_table = array(
	
		1  => 'Data',
		2  => 'Ora',
		3  => 'Campionato',
		4  => 'GironeCampionato',
		5  => 'Casa',
		6  => 'Trasferta',
		7  => 'Giornata',
		8  => 'Partita',
		9  => 'Campo',
		10 => 'BloccatoSi',
		11 => 'BloccatoNo',
		12 => 'FestivoSi',
		13 => 'FestivoNo',
		14 => 'NomeGara',
		15 => 'CausaleRisultato',
		16 => 'Arbitro',
		17 => 'Arbitro2',
		18 => 'Delegato',
		19 => 'DelegatoA',
	
	);	
	
	$matches = array();
	
	foreach($temp as $match) {
		
		foreach($match as $key => $field) {
			
			$tmp_matches['Match'][$head_table[$key]] = $field;
			
		}
		
		//Cerco campionato
		$var_campionato = trim($tmp_matches['Match']['Campionato']);
		$tmp_campionati = $this->Campionati->find('first', array(
		
			'conditions' => array('Campionati.Nome' => $var_campionato),
		
		));
		$tmp_matches['Match']['Campionato'] = $tmp_campionati['Campionati']['Campionato'];
		$tmp_matches['Lda']['Campionato'] = $tmp_campionati['Campionati']['Campionato'];
		
		//Cerco girone
		
		$var_half = trim($tmp_matches['Match']['GironeCampionato']);
		foreach($tmp_campionati['Half'] as $half) {
			
			if($half['Descrizione'] == $var_half) { $tmp_matches['Match']['GironeCampionato'] = $half['GironeCampionato']; break; }
			
		}
		
		//Cerco squadra casa
		
		$var_casa = trim($tmp_matches['Match']['Casa']);
		$tmp_casa = $this->SquadreCampionati->find('first', array(
		
			'conditions' => array(
			
				'SquadreCampionati.Campionato' => $tmp_matches['Match']['Campionato'],
				'SquadreCampionati.GironeCampionato' => $tmp_matches['Match']['GironeCampionato'],
				'Squadre.Denominazione'		=> $tmp_matches['Match']['Casa'],
			
			),
		
		));
		
		$tmp_matches['Match']['Casa'] = $tmp_casa['SquadreCampionati']['SquadraCampionato'];
		$tmp_matches['Lda']['Casa'] = $tmp_casa['SquadreCampionati']['SquadraCampionato'];
		
		//Cerco squadra trasferta
		$var_trasferta = trim($tmp_matches['Match']['Trasferta']);
		$tmp_trasferta = $this->SquadreCampionati->find('first', array(
		
			'conditions' => array(
			
				'SquadreCampionati.Campionato' => $tmp_matches['Match']['Campionato'],
				'SquadreCampionati.GironeCampionato' => $tmp_matches['Match']['GironeCampionato'],
				'Squadre.Denominazione'		=> $tmp_matches['Match']['Trasferta'],
			
			),
		
		));
		
		$tmp_matches['Match']['Trasferta'] = $tmp_trasferta['SquadreCampionati']['SquadraCampionato'];
		$tmp_matches['Lda']['Trasferta'] = $tmp_trasferta['SquadreCampionati']['SquadraCampionato'];
		
		//Cerco campo
		$var_campo = trim($tmp_matches['Match']['Campo']);
		$tmp_campo = $this->Campi->find('first', array(
		
			'conditions' => array('Campi.Descrizione' => $var_campo),
		
		));
		
		$tmp_matches['Match']['Campo'] = $tmp_campo['Campi']['Campo'];
		$tmp_matches['Lda']['Campo'] = $tmp_campo['Campi']['Campo'];
		
		//Data e ora
		
		$tmp_matches['Lda']['Ora']  = $tmp_matches['Match']['Ora'];
		$tmp_matches['Lda']['Data'] = $tmp_matches['Match']['Data'];
		
		//Settings LDA
		
		$tmp_matches['Lda']['Arbitro']   = 0;
		$tmp_matches['Lda']['Arbitro2']  = 0;
		$tmp_matches['Lda']['Delegato']  = 0;
		$tmp_matches['Lda']['DelegatoA'] = 0;
		
		$tmp_matches['Lda']['ImportoArbitro']   = $tmp_campionati['Campionati']['TariffaArbitro'];
		$tmp_matches['Lda']['ImportoArbitro2']  = $tmp_campionati['Campionati']['TariffaArbitro2'];
		$tmp_matches['Lda']['ImportoDelegato']  = $tmp_campionati['Campionati']['TariffaDelegato'];
		$tmp_matches['Lda']['ImportoDelegatoA'] =  $tmp_campionati['Campionati']['TariffaDelegatoA'];
		
		$tmp_matches['Lda']['group_id']   = 5;
		$tmp_matches['Match']['group_id'] = 5;
		
		$tmp_matches['Match']['Bloccato'] = 'N';
		$tmp_matches['Match']['Festivo']  = 'N';
		
		if($tmp_matches['Match']['Casa'] == '') $tmp_matches['Match']['Casa'] = 0;
		if($tmp_matches['Match']['Trasferta'] == '') $tmp_matches['Match']['Trasferta'] = 0;
		if($tmp_matches['Match']['Campo'] == '') $tmp_matches['Match']['Campo'] = 0;
		if($tmp_matches['Lda']['Casa'] == '') $tmp_matches['Lda']['Casa'] = 0;
		if($tmp_matches['Lda']['Trasferta'] == '') $tmp_matches['Lda']['Trasferta'] = 0;
		if($tmp_matches['Lda']['Campo'] == '') $tmp_matches['Lda']['Campo'] = 0;

		//CREO LDA PER PRIMA COSA
		
		$this->Lda->create();
		$this->Lda->set($tmp_matches);
		
		if($this->Lda->save()) {
			
			$this->Match->create();
			$tmp_matches['Match']['lda_id'] = $this->Lda->id;
			$this->Match->set($tmp_matches);
			
			if($this->Match->save()) {
				
				$saved_match++;
				
			} 
			
			$saved_lda++;
			
		}
		
		$matches[] = $tmp_matches;
		
	}
	
	debug('Lda salvati: ' . $saved_lda);
	debug('Match salvati: ' . $saved_match);
	
	debug('totale record trovati nel file excel: ' . count($matches));	
	
	//debug($matches);
  	
  }	
  
  function sendSms() {
  	
  	$this->layout = "ajax";
  	
	 $buffer = array(
					"authlogin" => "midland@aimon.it",
					"authpasswd" => "0r0l0gi0", 
					"sender" => base64_encode("mittente"),
					"body" => base64_encode("PROVA SMS"),
					"destination" => "393286659940", 
					"id_api" => 106
				);
			
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://secure.apisms.it/http/send_sms"); 
	curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_POSTFIELDS, $buffer); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$ret = curl_exec($ch); 
	curl_close($ch); # ritorno dalle api print_r($ret);
	
	debug($ret);
	
	exit;
 	
  	
  }
  
  function fixYearbook() {
  	
  	Configure::write('debug',2);
  	
  	$this->layout = null;
  	$this->autoRender = false;
  	
  	$this->Yearbook->recursive = -1;
  	
  	$yearbooks = $this->Yearbook->find('all', array(
  	
  		'fields' => array('Yearbook.DataVidimazione','Yearbook.Tessera','Yearbook.Atleta','Yearbook.Annuario'),
  		'conditions' => array(
  			'Yearbook.DataVidimazione >=' => '2012-05-19'
  		),
  		'order' => array('Yearbook.DataVidimazione' => 'ASC','Yearbook.Annuario' => 'ASC'),
  	
  	));
  	
  	$tessera = 9881;
  	$saved   = 0;
  	$total   = 0;
  	
  	debug($yearbooks);
  	
  	$this->Yearbook->recursive = 2;
  	
  	foreach($yearbooks as $year) {
  		
  		if(strlen($year['Yearbook']['Tessera']) == 8)
  			continue;
  		
  		$tesseraNew = sprintf('%06d', $tessera);
  		
  		debug('Nuova tessera: ' . $tessera);
  		debug('Vecchia tessera: ' . $year['Yearbook']['Tessera']);
  		
  		$this->Yearbook->read(null, $year['Yearbook']['Annuario']);
  		$this->Yearbook->set('Tessera', '12'.$tesseraNew);
  		
  		if($this->Yearbook->save()) {
  			
  			debug('Record salvato');
  			$saved++;
  			
  		}
  		
  		$total++;
  		$tessera++;
  		
  	}
  	
  	debug('Record totali: ' . $total);
  	debug('Record modificati: ' . $saved);
  	
  	//debug($yearbooks);
  	
  }  
  
	function importXlsNewsletter() {
		
		Configure::Write('debug',2);
		
		$this->layout     = null;
		$this->autoRender = false;
		
		$data = new Spreadsheet_Excel_Reader('contatti1.xls', true);
		$temp = $data->dumptoarray();
		
		//debug($temp);
		
		$head_table = $temp[1];
		unset($temp[1]); 
		
		$contatti = array_merge($temp);
		
		$users = array();
		
		foreach($contatti as $c) {
			
			$users[] = filter_var($c[1], FILTER_SANITIZE_EMAIL);
			
		}
		
		foreach($users as $user) {
		
			$this->NewsletterUser->create();
			$this->NewsletterUser->set('email', $user);
			if($this->NewsletterUser->save()) {
				$this->NewsletterGroupUser->create();
				$this->NewsletterGroupUser->set('newsletter_group_id', 8);
				$this->NewsletterGroupUser->set('newsletter_user_id', $this->NewsletterUser->id);
				$this->NewsletterGroupUser->save();
				
				debug('Saved');
				
			} 		
			
		}			
		
	}  
	
    function readCSV()  
    {  
    	
		$this->layout     = null;
		$this->autoRender = false;	    	
    	
    	Configure::Write('debug',2);
    	$filepath = APP . 'tmp/uploads/csv/contatti_csv.csv';
    	
        App::import("Vendor","parsecsv");  
      
        $csv = new parseCSV();  
        $csv->auto($filepath);  
        
        $users = array();
      
        foreach ($csv->data  as $row)  
        {  
        	
        	if(!isset($row['E-mail Address']))
        		continue;
        	
        	$email   = filter_var($row['E-mail Address'], FILTER_SANITIZE_EMAIL);
        	
        	if($this->isValidEmail($email))
        		$users[] = $email;

        }  
        
        //debug($users);
        //exit;
        
        foreach($users as $user) {
        	
			$this->NewsletterUser->create();
			$this->NewsletterUser->set('email', $user);
			if($this->NewsletterUser->save()) {
				$this->NewsletterGroupUser->create();
				$this->NewsletterGroupUser->set('newsletter_group_id', 7);
				$this->NewsletterGroupUser->set('newsletter_user_id', $this->NewsletterUser->id);
				$this->NewsletterGroupUser->save();
				
				debug('Saved: ' . $this->NewsletterUser->id);
				
			} else {
				
				debug($this->NewsletterUser->invalidFields());
				
			}      	
        	
        }
        
    } 
    
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}     		
			
				
	function cleanNewsletter() {
	
		$this->layout = "ajax"; 
		$this->autoRender = false;
		
		ini_set('max_execution_time',9999999);
		
		Configure::Write('debug',2);
		
		$contacts = $this->NewsletterUser->find('all', array(
		
			'limit' => 6000,
			'order' => array('NewsletterUser.id' => 'ASC')
			
		));
		
		$validation =& Validation::getInstance();
		
		foreach($contacts as $contact) {
		
			$email = filter_var($contact['NewsletterUser']['email'], FILTER_SANITIZE_EMAIL);
			//$email = $contact['NewsletterUser']['email'];
		
			if(!$validation->email($email)) {

				//debug("#".$contact['NewsletterUser']['id']. " Contatto non valido: " . $email . ", eliminato");
				if($this->NewsletterUser->delete($contact['NewsletterUser']['id'])) {
				
					$this->NewsletterUser->query("DELETE FROM newsletters_groups_users WHERE newsletter_user_id = " . $contact['NewsletterUser']['id']);
					debug("#".$contact['NewsletterUser']['id']. " Contatto non valido: " . $email . ", eliminato");
				
				}
			
			}
		
		}
	
	}
	
	function createyt() {
	
		$this->autoRender = false;
		
		$rights = $this->Right->find('all', array(
		
			'conditions' => array('Right.group_id' => 9)
		
		));
		
		foreach($rights as $k => $right) {
		
			$this->Right->create();
			$this->Right->set('group_id', 11);
			$this->Right->set('resource', $right['Right']['resource']);			
			$this->Right->set('allow', $right['Right']['allow']);	
								
			if($this->Right->save())
				debug('Salvato: ' . $k);
		
		}
	
	}	
				
}
