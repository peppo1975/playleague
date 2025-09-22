<?php

class TennisimpiantoController extends AppController {
	
	var $name = "Tennisimpianto";
	var $login_required = true;
	var $uses = array('Campi','ChampCategory','AnniSportivi','Campionati', 'Half' /* = GironiCampionati */, 'SquadreCampionati', 'Yearbook', 'Athlete', 'Squadre', 'Match');

	function beforeFilter() {
	
		parent::beforeFilter();

		$error = false;
		$this->layout = "content";
		$this->login_site = true;
		
		$this->__checkIsImpianto();
	}

	function index_tornei()
	{
		$campo = $this->__getMyCampo();
		$categoriaId = $this->__getCategoriaByCampo($campo['Campi']['Descrizione']);

		$all = empty($categoriaId) ? [] : $this->Campionati->find('all', [
			'conditions' => ['Categoria' => $categoriaId],
		]);

		array_walk($all, function(&$r){
			if(empty($r['Half'][0]['GironeCampionato'])){
				$r['Campionati']['has_matches'] = false;
				return;
			}

			$matches = $this->Match->find('first', ['conditions' => [
				'Match.Campionato' => $r['Campionati']['Campionato'],
				'Match.GironeCampionato' => $r['Half'][0]['GironeCampionato'],
			]]);
			$r['Campionati']['has_matches'] = (bool)$matches;
			return;
		});

		$this->set(compact('campo', 'all'));
	}

	function add_torneo()
	{
		$campo = $this->__getMyCampo();

		if(!empty($this->data))
		{
			$categoriaId = $this->__getCategoriaByCampo($campo['Campi']['Descrizione']);

			$this->data['Campionati'] = array_merge($this->data['Campionati'], [
				'AnnoSportivo' => $this->__getLastAnnoSportivo(),
				'InCorso' => 'Si',
				'InUso' => 'Si',
				'Italiana' => 'Si',
				'Tipo' => 0,
				'group_id' => 1,
				'Categoria' => $categoriaId,
				'id_sport' => 1,
				'sport' => 'TENNIS',
			]);

			$this->Campionati->set($this->data);
			if ($this->Campionati->save()) {

				$this->data = ['Half' => [
					'Campionato' => $this->Campionati->id,
					'Descrizione' => '//',
					'NumeroSquadre' => 0,
					'DataInizio' => date('Y-m-d H:i:s'),
					'group_id' => 1,
					'active' => 1,
				]];
				$this->Half->save($this->data);
				$this->redirect('/gestione/impianto/index_tornei');
			}
		}

		$this->set(compact('campo'));

	}

	public function edit_torneo($id)
	{
		$id = (int)$id;
		$r = $this->Campionati->read(null, $id);

		$campo = $this->__getMyCampo();
		$categoriaId = $this->__getCategoriaByCampo($campo['Campi']['Descrizione']);

		// Se non è un campionato mio non posso modificare
		if($r['Campionati']['Categoria'] != $categoriaId)
		{
			$this->Session->setFlash('Non puoi modificare questo torneo', 'site/message/error_message');
			return $this->redirect('/gestione/impianto/index_tornei');
		}

		// POST
		if (!empty($this->data))
		{
			$this->Campionati->set($this->data);
			$this->Campionati->set('TariffaArbitro', 0);
		    $this->Campionati->set('TariffaArbitro2', 0);
		    $this->Campionati->set('TariffaDelegato', 0);
		    $this->Campionati->set('TariffaDelegatoA', 0);
		    // debug($this->Campionati->data);
			if($this->Campionati->save())
			{
				$this->Session->setFlash('Modifica effettuata', 'site/message/ok_message');
				return $this->redirect('/gestione/impianto/index_tornei');
			}
			else{
				debug($this->Campionati->validationErrors);
				$this->Session->setFlash('Errore durante il salvataggio', 'site/message/error_message');
			}
		}
		else
			$this->data = $r;

		$this->set(compact('id'));

	}

	/**
	 * Mostra l'elenco giocatori e ne gestisce l'aggiunta
	 * @param  int $campionatoId
	 * @return void
	 */
	public function manage_atleti($campionatoId)
	{
		$r = $this->Campionati->find('first', [
			'conditions' => ['Campionato' => $campionatoId],
			'contains' => ['Half'],
		]);
		$gironeId = $r['Half'][0]['GironeCampionato'];

		// Aggiunta giocatore
		if(!empty($this->data['atleta']))
		{

			$atleti = [
				1 => (int)$this->data['atleta'][1],
				2 => !empty($this->data['atleta'][2]) ? (int)$this->data['atleta'][2] : null,
			];

			if(empty($atleti[1])){
				$this->Session->setFlash("L'atleta è obbligatorio", 'site/message/error_message');
				return $this->redirect('/gestione/impianto/manage_atleti/'.$campionatoId);
			}

			if($r['Campionati']['TipoTorneoTennis'] == 'D' && empty($atleti[2])){
				$this->Session->setFlash("Entrambi gli atleti sono obbligatori", 'site/message/error_message');
				return $this->redirect('/gestione/impianto/manage_atleti/'.$campionatoId);
			}
				
			$squadraId = $this->__getSquadraByAtleti($atleti);
			
			// TODO controlla che non sia già stato aggiunto al torneo
			
			// Per aggiungere un giocatore a un torneo va creato un record in SquadreCampionati
			// che lega Campionato, Girone, Squadra
			$this->data = ['SquadreCampionati' => [
				'Squadra' => $squadraId,
				'Campionato' => $campionatoId,
				'GironeCampionato' => $r['Half'][0]['GironeCampionato'],
				'group_id' => '1',
			]];
			$res = $this->SquadreCampionati->save($this->data);
			if($res)
			{
				// TODO Creare record Annuario
			}
		}

		$squadre = $this->__getSquadreCampionato($campionatoId, $gironeId);
		
		$this->set(compact('campionatoId', 'gironeId', 'r', 'squadre'));
	}

	public function rinomina_squadra($id, $campionatoId)
	{
		$r = $this->Squadre->read(null, (int)$id);
		$this->set(compact('r', 'id', 'campionatoId'));

		if(!empty($this->data))
		{
			$this->Squadre->create();
			$this->Squadre->set($r);
			$this->Squadre->set('Denominazione', $this->data['Squadre']['Denominazione']);
			if($this->Squadre->save())
				$this->Session->setFlash('Squadra rinominata', 'site/message/ok_message');
			else
				$this->Session->setFlash('Errore nel salvataggio', 'site/message/error_message');

			return $this->redirect('/gestione/impianto/manage_atleti/'.$campionatoId);
		}
		else{
			$this->data = $r;
		}

	}

	public function delete_atleta_torneo($id)
	{
		$id = (int)$id;
		$sc = $this->SquadreCampionati->find('first', ['conditions' => ['SquadraCampionato' => $id]]);

		$campo = $this->__getMyCampo();
		$categoriaId = $this->__getCategoriaByCampo($campo['Campi']['Descrizione']);

		// Se è un campionato mio allora posso cancellare
		if($sc['Campionati']['Categoria'] == $categoriaId)
		{
			$this->SquadreCampionati->delete($id);
			$this->Session->setFlash('Atleta rimosso dal torneo', 'site/message/ok_message');
		}
		else
		{
			$this->Session->setFlash('Non puoi rimuovere l\'atleta dal torneo', 'site/message/error_message');
		}
		return $this->redirect('/gestione/impianto/manage_atleti/'.$sc['Campionati']['Campionato']);
	}

	private function __getNSquadre($n){
		$a = [8, 16, 32, 64];
		foreach($a as $g)
		{
			if($n <= $g)
				return $g;
		}
		throw new Exception("Troppi giocatori");
		
	}

	public function buildFasiTorneo()
	{
		$this->squadre = $this->__getSquadreCampionato($this->campionatoId, $this->gironeId);
		$this->squadreTorneo = $this->__getNSquadre(count($this->squadre));
		$this->squadreList = [];
		foreach($this->squadre as $r){ $this->squadreList[$r['SquadreCampionati']['SquadraCampionato']] = $r['Squadre']['Denominazione'];}

		$this->maxFasi = log($this->squadreTorneo, 2);
		$this->fasi = [];
		$this->faseAttuale = false;
		$this->vincitore = false;

		// Popola le fasi
		for($i = 1; $i <= $this->maxFasi; $i++){
			$this->fasi[$i] = ['Matches' => $this->__getPartiteTorneo($this->campionatoId, $this->gironeId, $i)];
			$this->fasi[$i]['DaGiocare'] = array_reduce($this->fasi[$i]['Matches'], function($c, $r){ 
				return $c + (empty($r['Matchgoal']) && !$r['Match']['hasBye'] ? 1 : 0);
			});

			$this->fasi[$i]['isFaseAttuale'] = false; // Sarà settato dal for successivo
			if(empty($this->fasi[$i]['Matches']))
			{
				$dummiesCount = pow(2, $this->maxFasi - $i + 1);
				$this->fasi[$i]['Matches'] = array_fill(0, $dummiesCount/2, '');
			}
		}

		// Determina la fase attuale partendo dal fondo e prendendo la prima che ha un match programmato
		for($i = $this->maxFasi; $i >= 1; $i--) {
			if(!empty($this->fasi[$i]['Matches'][0]['Match'])) {
				$this->fasi[$i]['isFaseAttuale'] = true;
				$this->faseAttuale = $i;
				break;
			}
		}

		// Controlla se è concluso
		if($this->faseAttuale == $this->maxFasi && !empty($this->fasi[$this->maxFasi]['Matches'][0]['Match']['Risultato']))
		{
			$this->vincitore = $this->__getWinner($this->fasi[$this->maxFasi]['Matches'][0]);

			if($this->fasi[$this->maxFasi]['Matches'][0]['Campionati']['InCorso'] == 'Si')
				$this->__concludiCampionato($this->fasi[$this->maxFasi]['Matches'][0]['Campionati']['Campionato']);
		}
	}

	private function __concludiCampionato($id)
	{
		$this->Campionati->read(null, $id);
		return $this->Campionati->saveField('InCorso', 'No');
	}

	public function manage_partite($campionatoId, $gironeId, $prefill = false)
	{
		$this->campionatoId	= $campionatoId;
		$this->gironeId		= $gironeId;
		$this->set(compact('prefill'));

		$this->buildFasiTorneo();

		// debug($this->fasi);
		$this->set([
			'campionatoId'	=> $this->campionatoId, 
			'gironeId'		=> $this->gironeId, 
			'squadre'		=> $this->squadre, 
			'squadreTorneo'	=> $this->squadreTorneo, 
			'squadreList'	=> $this->squadreList, 
			'fasi'			=> $this->fasi, 
			'faseAttuale'	=> $this->faseAttuale, 
			'maxFasi'		=> $this->maxFasi,
			'vincitore'		=> $this->vincitore,
		]);

		if(empty($this->faseAttuale))
		{
			if(count($this->squadre) < 5)
			{
				$this->Session->setFlash('Seleziona almeno 5 giocatori!', 'site/message/error_message');
				$this->redirect('/gestione/impianto/manage_atleti/'.$campionatoId);
			}
			$this->render('manage_fase1');
		}
	}

	public function create_partite($campionatoId, $gironeId)
	{
		if(empty($this->data['Match']))
			die("Nessun dato inviato");

		// Controllo se ho già generato le partite
		$r = $this->Match->find('first', [
			'conditions' => [
				'Match.Campionato' => (int)$campionatoId,
				'Match.GironeCampionato' => (int)$gironeId,
			],
		]);
		if(!empty($r))
			return $this->redirect('/gestione/impianto/manage_partite/'.$campionatoId.'/'.$gironeId);

		$newMatches = [1 => $this->data['Match']];

		$this->campionatoId	= $campionatoId;
		$this->gironeId		= $gironeId;

		$this->buildFasiTorneo();
		
		// Controlla che l'utente abbia selezionato tutti i giocatori e non ci siano mancanti o doppioni
		$checkSquadre = $this->squadreList;
		$countBye = 0;

		try{

			foreach($newMatches[1] as $l1)
			{
				if(empty($l1['casa']) || empty($l1['trasferta']))
					throw new \Exception('Uno o più giocatori non sono stati selezionati.');

				if($l1['casa'] == -1 && $l1['trasferta'] != -1)
					throw new \Exception('Se vuoi impostare un solo bye, impostalo sul secondo giocatore.');

				foreach($l1 as $id)
				{
					if(isset($checkSquadre[$id]))
						unset($checkSquadre[$id]);
					if($id == -1)
						$countBye++;
				}
			}

			if(!empty($checkSquadre))
				throw new \Exception('Uno o più atleti non sono stati selezionati!');

			if($this->squadreTorneo != (count($this->squadreList) + $countBye))
				throw new \Exception('Hai selezionato due volte lo stesso atleta!');

// debug($newMatches);
	
			for($fase = 1; $fase <= $this->maxFasi; $fase++)
			{
				$count = 0;
				foreach($newMatches[$fase] as $match)
				{
					$isLastFase = $fase == $this->maxFasi;
					$count++;
					$nextId = ceil($count / 2);

					$this->Match->create();
					$this->Match->set([
						'Campionato' => (int)$campionatoId,
						'GironeCampionato' => (int)$gironeId,
						'Giornata' => $fase,
						'Casa' => $match['casa'],
						'Trasferta' => $match['trasferta'],
						'parents' => !empty($match['parentsList']) ? join(',', $match['parentsList']) : '',
						'Partita' => 1,
						'group_id' => 1,
					]);
					$e = $this->Match->save();

					// debug($this->Match->id);
					if(!$isLastFase)
					{
						if(!isset($newMatches[$fase+1][$nextId]))
							$newMatches[$fase+1][$nextId] = ['parentsList' => [], 'casa' => 0, 'trasferta' => 0];

						// Se vince contro bye, passa il turno
						if($match['trasferta'] == -1)
							$newMatches[$fase+1][$nextId][$count % 2 == 1 ? 'casa' : 'trasferta'] = $match['casa'];
						
						$newMatches[$fase+1][$nextId]['parentsList'][] = $this->Match->id;
					}
				}
			}
		}catch(\Exception $e)
		{
			$this->Session->setFlash($e->getMessage(), 'site/message/error_message');

			return $this->manage_partite($campionatoId, $gironeId);
		}

		return $this->redirect('/gestione/impianto/manage_partite/'.$campionatoId.'/'.$gironeId);
		
	}

	public function save_match($id)
	{
		if(empty($this->data['Match']) || empty($id))
			die("Nessun dato inviato");

		$r = $this->Match->find('first', [
			'conditions' => ['Match.Calendario' => (int)$id],
			'contain' => 'Matchgoal',
		]);

		if(empty($r['Match']))
			die("Partita inesistente");

		$risultati = [];
		$punteggio = [];

		// Combina gli attuali in k=>r
		foreach($r['Matchgoal'] as $goal)
		{
			$risultati[$goal['SquadraCampionato']] = $goal;
		}

		foreach(['Casa', 'Trasferta'] as $quale)
		{
			$id = $r[$quale]['SquadraCampionato'];
			// Se devo creare il record
			if(!isset($risultati[$id]))
			{
				$risultati[$id] = [
					'Calendario' => $r['Match']['Calendario'],
					'SquadraCampionato' => $id,
					'Atleta' => 0,
				]; 
			}
			$risultati[$id]['Goal'] = (int)$this->data['Match']['punteggio_'.strtolower($quale)];
			$risultati[$id]['Autogoal'] = 0;
			$punteggio[] = (int)$this->data['Match']['punteggio_'.strtolower($quale)];
		}

		$r['Match']['Data'] = !empty($this->data['Match']['Data']) ? (new \DateTime())->createFromFormat('d/m/Y', $this->data['Match']['Data'])->format('Y-m-d') : null;
		$r['Match']['PuntiSet'] = !empty($this->data['Match']['PuntiSet']) ? $this->data['Match']['PuntiSet'] : '';
		$r['Match']['Ora'] = $this->data['Match']['Ora'];
		$r['Match']['is_tennis'] = true;
		$r['Match']['Autogoal'] = 0;
		$this->data = ['Match' => $r['Match']];

		$punteggiOk = ($punteggio[0] + $punteggio[1] > 0) && ($punteggio[0] - $punteggio[1] != 0);
		// debug($punteggio); die();

		if($punteggiOk)
		{
			$this->data['Matchgoal'] = $risultati;
			$this->data = $this->__setRankingPoints($this->data, $r);
		}


		$ret = $this->Match->saveAll($this->data);
		if($this->Match->invalidFields())
		{
			$firstError = array_pop(array_pop($this->Match->invalidFields()));
			$this->Session->setFlash('Errore: '.print_r($firstError, 1), 'site/message/error_message');
		}
		else
		{
			$this->__updateTorneo($r['Match']['Campionato'], $r['Match']['GironeCampionato']);
			$this->Session->setFlash('Salvataggio effettuato!', 'site/message/ok_message');
		}
		// debug($risultati);
		// debug($ret);
		return $this->redirect('/gestione/impianto/manage_partite/'.$r['Match']['Campionato'].'/'.$r['Match']['GironeCampionato']);

	}

	private function __setRankingPoints($data, $fullData)
	{
		// 0 punti se contro Bye
		// 0 punti se vinta a tavolino, nota "ND" (non disputata)
		if($data['Match']['Casa'] == -1 || $data['Match']['Trasferta'] == -1 || trim(strtolower($data['Match']['PuntiSet'])) == 'nd')
		{
			foreach($data['Matchgoal'] as &$r)
				$r['PuntiRanking'] = 0;
		}
		else
		{
			$punteggioVincitore = 0;
			foreach($data['Matchgoal'] as $r){
				if($r['Goal'] > $punteggioVincitore)
					$punteggioVincitore = $r['Goal'];
			}
			foreach($data['Matchgoal'] as &$r)
			{
				if($r['Goal'] == $punteggioVincitore)
					$r['PuntiRanking'] = $fullData['Campionati']['TipoTorneoTennis'] == 'S' ? 3 : 2;
				else
					$r['PuntiRanking'] = 1;
			}
		}
		// debug($data); die();
		return $data;
	}

	private function __updateTorneo($campionatoId, $gironeId)
	{
		$this->campionatoId = $campionatoId;
		$this->gironeId = $gironeId;
		$this->buildFasiTorneo();

		for($fase = 1; $fase <= $this->maxFasi; $fase++)
		{
			$count = 0;
			foreach($this->fasi[$fase]['Matches'] as $match)
			{
				$isLastFase = $fase == $this->maxFasi;
				$count++;
				$nellaProssimaGiocoCome = $count % 2 == 1 ? 'Casa' : 'Trasferta';

				// Copio i dati del vincitore nella prossima partita
				if(!$isLastFase && empty($match['Match']['hasBye']) && !empty($match['Match']['Vincitore']))
				{
					$nextMatch = $this->Match->find('first', [
						'conditions' => ['OR' => [
							['Match.parents LIKE' => $match['Match']['Calendario'].",%"],
							['Match.parents LIKE' => "%,".$match['Match']['Calendario']],
						]],
						'contain' => 'Matchgoal',
					]);

					$this->__processMatchData($nextMatch);
					if(empty($nextMatch['Match']['Vincitore']))
					{
						$this->Match->id = $nextMatch['Match']['Calendario'];
						$this->Match->saveField($nellaProssimaGiocoCome, $this->__getWinnerId($match));
						// debug($nellaProssimaGiocoCome);
						// debug($nextMatch);
					}
				}
			}
		}
		// die();
	}

	/**
	 * Resitituisce il vincitore tra due partite (tutto l'array partita)
	 * @param  array $r 
	 * @return array
	 */
	private function __getWinner($r)
	{
		// Se partita giocata
		if(!empty($r['Matchgoal']))
		{
			foreach($r['Matchgoal'] as $team)
			{
				if($team['is_vincitore'])
					return $team;
			}
		}

		// Se partita contro Bye
		foreach(['Casa', 'Trasferta'] as $idx)
		{
			if(!empty($r[$idx]))
				return $r[$idx];
		}
	}

	/**
	 * Resitituisce il vincitore tra due partite (solo ID)
	 * @param  array $r 
	 * @return int
	 */
	private function __getWinnerId($r)
	{
		return $this->__getWinner($r)['SquadraCampionato'];
	}

	/**
	 * Restituisce i dati per l'autocomplete atleti in aggiunta ad un torneo
	 * @return json
	 */
	public function search_atleti()
	{
		App::import('Sanitize');
		$sanitize = new Sanitize;
		$this->layout = 'ajax';
		$q = Sanitize::escape($this->params['url']['q']);
		$y = $this->__getLastAnnoSportivo();

		$all = $this->Yearbook->query("
			SELECT DISTINCT
				Yearbook.tessera, Athlete.Atleta, Athlete.Nome, Athlete.Cognome 
			FROM 
				Annuario AS Yearbook LEFT JOIN
				Atleti AS Athlete ON Yearbook.Atleta = Athlete.Atleta
			WHERE
				(Yearbook.tessera LIKE '%$q%' OR Athlete.Cognome LIKE '%$q%')
				AND Yearbook.Atleta <> 0
				AND Yearbook.AnnoSportivo = $y
			ORDER BY
				Athlete.Cognome ASC, Athlete.Nome ASC
			LIMIT 30
			;");

		$all = array_map(function($r){
			return [
				'id' => $r['Athlete']['Atleta'],
				'text' => sprintf("%s %s - Tessera: %s", $r['Athlete']['Nome'], $r['Athlete']['Cognome'], $r['Yearbook']['tessera']),
			];
		}, $all);
		echo json_encode($all);
		$this->render(false);
	}

	private function __checkIsImpianto()
	{
		$userData = $this->Session->read('Login.data');
		if(empty($userData['is_impianto']))
			return $this->redirect('/');
		return true;
	}

	private function __getMyCampo()
	{
		$campoId = $this->Session->read('Login.data.campo_id');
		return $this->Campi->find('first', ['conditions' => ['Campo' => $campoId]]);
	}

	/**
	 * Cerca la categoria per nome impianto, e se non la trova la crea
	 * @param  string $descrizione
	 * @return int  l'id categoria
	 */
	private function __getCategoriaByCampo($descrizione)
	{
		$categoria = $this->ChampCategory->find('first', ['conditions' => ['Nome' => $descrizione, 'disabled' => 0]]);

		if(!empty($categoria))
			return $categoria['ChampCategory']['id'];

		$this->ChampCategory->set([
			'Nome' => $descrizione,
			'group_id' => 1,
			'sport' => 'TENNIS',
			'id_sport' => 1,
		]);

		if($ret = $this->ChampCategory->save()){
			return $this->ChampCategory->id;
		}
		return false;

	}

	private function __getLastAnnoSportivo()
	{
		$last_year = $this->AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));

		return $last_year['AnniSportivi']['AnnoSportivo'];
	}

	private function __getSquadreCampionato($campionatoId, $gironeId)
	{
		return $this->SquadreCampionati->find('all', [
			'conditions' => [
				'SquadreCampionati.Campionato' => $campionatoId, 
				'SquadreCampionati.GironeCampionato' => $gironeId
			],
			'contains' => ['Squadre'],
		]);
	}

	private function __getPartiteTorneo($campionatoId, $gironeId, $fase = 1)
	{
		$all = $this->Match->find('all', [
			'conditions' => [
				'Match.Campionato' => $campionatoId, 
				'Match.GironeCampionato' => $gironeId,
				'Match.Giornata' => $fase,
			],
			'contains' => ['Casa', 'Trasferta', 'Matchgoal'],
		]);

		foreach($all as $k=>&$match)
		{
			$this->__processMatchData($match);
		}

		return $all;
	}

	private function __processMatchData(&$match)
	{
		$match['Match']['Vincitore'] = false;
		
		if(!empty($match['Matchgoal']))
		{
			$risultati = [];
			foreach($match['Matchgoal'] as $m=>$goal)
			{
				$goal['is_vincitore'] = $goal['Goal'] > $match['Matchgoal'][(int)!$m]['Goal'];
				$risultati[$goal['SquadraCampionato']] = $goal;

				if($goal['is_vincitore'])
				{
					$match['Match']['Vincitore'] = $goal['SquadraCampionato'] == $match['Casa']['SquadraCampionato'] ? 'Casa' : 'Trasferta';
				}
			}
			$match['MatchgoalOriginal'] = $match['Matchgoal'];
			$match['Matchgoal'] = $risultati;
		}

		$hasBye = $match['Match']['Casa'] == -1 || $match['Match']['Trasferta'] == -1;
		$match['Match']['hasBye'] = $hasBye;

		if($hasBye)
			$match['Match']['Vincitore'] = empty($match['Trasferta']['Squadra']) ? 'Casa' : 'Trasferta';
		// debug($match);
	}

	/**
	 * Recupera la squadra individuale per un atleta o la crea se inesistente
	 * @param  array $atleti
	 * @return int
	 */
	private function __getSquadraByAtleti($atleti)
	{
		$isSingolo = empty($atleti[2]);

		$atleta1 = $this->Athlete->findByAtleta($atleti[1]);
		$atleta2 = !$isSingolo ? $this->Athlete->findByAtleta($atleti[2]) : null;
		$r = $this->Squadre->find('first', ['conditions' => ['individuale' => $isSingolo ? 1 : 0, 'atleta_id' => $atleti[1], 'atleta2_id' => $atleti[2]]]);
		// debug($r);
		if(!empty($r))
			return $r['Squadre']['Squadra'];

		$denominazione = $isSingolo ? $atleta1['Athlete']['Cognome'].' '.$atleta1['Athlete']['Nome'] : sprintf('%s %s. - %s %s.', $atleta1['Athlete']['Cognome'], $atleta1['Athlete']['Nome'][0], $atleta2['Athlete']['Cognome'], $atleta2['Athlete']['Nome'][0]);

		$this->Squadre->create();
		$this->Squadre->set([
			'Denominazione' => $denominazione,
			'group_id' => 1,
			'sport' => 'TENNIS',
			'id_sport' => 1,
			'individuale' => $isSingolo ? 1 : 0,
			'atleta_id' => $atleti[1],
			'atleta2_id' => $atleti[2],
		]);

		// debug($this->Squadre->data);
		if($ret = $this->Squadre->save()){
			return $this->Squadre->id;
		}
		return false;
	}



	
}