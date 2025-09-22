<?

	function getVote($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		return $info['vote'];
		
	}

	function getRanking($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		$options = array(
		
			0 => 'Nessun voto',
			1 => 'Gravemente insufficiente',
			2 => 'Insufficiente',
			3 => 'Non sufficiente',
			4 => 'Quasi sufficiente',
			5 => 'Sufficiente',
			6 => 'Discreto',
			7 => 'Buono',
			8 => 'Molto buono',
			9 => 'Ottimo'
		
		);		
		
		$tot_media    = 0;
		$tot_gare     = 0;
		$tot_vote     = 0;
		$tot_bonus    = 0;
		$tot_compenso = 0;
		$cont         = 0;
		$count_mounths= 0;
		
		foreach($info['mounths'] as $mese => $mounth) {
			
			$mounth = (array)$mounth;
			
			$tot_media += $mounth['MediaRanking'];
			$tot_gare  += $mounth['NumeroGare'];
			$tot_vote  += $mounth['VoteSend'];
			$tot_bonus += $mounth['Bonus'];
			$tot_compenso += $mounth['Compenso'];
			$cont++;
			if($mounth['MediaRanking'] > 0) $count_mounths++;
		}		
		
		return $options[@ceil($tot_media / $count_mounths)];
		
	}

	function getGare($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		$tot_media    = 0;
		$tot_gare     = 0;
		$tot_vote     = 0;
		$tot_bonus    = 0;
		$tot_compenso = 0;
		$cont         = 0;
		$count_mounths= 0;
		
		foreach($info['mounths'] as $mese => $mounth) {
			
			$mounth = (array)$mounth;
			
			$tot_media += $mounth['MediaRanking'];
			$tot_gare  += $mounth['NumeroGare'];
			$tot_vote  += $mounth['VoteSend'];
			$tot_bonus += $mounth['Bonus'];
			$tot_compenso += $mounth['Compenso'];
			$cont++;
			if($mounth['MediaRanking'] > 0) $count_mounths++;
		}		
		
		
		return $tot_gare;
		
	}
	
	function getVoti($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		$tot_media    = 0;
		$tot_gare     = 0;
		$tot_vote     = 0;
		$tot_bonus    = 0;
		$tot_compenso = 0;
		$cont         = 0;
		$count_mounths= 0;
		
		foreach($info['mounths'] as $mese => $mounth) {
			
			$mounth = (array)$mounth;
			
			$tot_media += $mounth['MediaRanking'];
			$tot_gare  += $mounth['NumeroGare'];
			$tot_vote  += $mounth['VoteSend'];
			$tot_bonus += $mounth['Bonus'];
			$tot_compenso += $mounth['Compenso'];
			$cont++;
			if($mounth['MediaRanking'] > 0) $count_mounths++;
		}		
		
		
		return $tot_vote;
		
	}
	
	function getBonus($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		$tot_media    = 0;
		$tot_gare     = 0;
		$tot_vote     = 0;
		$tot_bonus    = 0;
		$tot_compenso = 0;
		$cont         = 0;
		$count_mounths= 0;
		
		foreach($info['mounths'] as $mese => $mounth) {
			
			$mounth = (array)$mounth;
			
			$tot_media += $mounth['MediaRanking'];
			$tot_gare  += $mounth['NumeroGare'];
			$tot_vote  += $mounth['VoteSend'];
			$tot_bonus += $mounth['Bonus'];
			$tot_compenso += $mounth['Compenso'];
			$cont++;
			if($mounth['MediaRanking'] > 0) $count_mounths++;
		}		
		
		
		return $tot_bonus;
		
	}
	
	function getCompensi($id_athlete) {
		
		$info = (array)getBuste($id_athlete);
		
		$tot_media    = 0;
		$tot_gare     = 0;
		$tot_vote     = 0;
		$tot_bonus    = 0;
		$tot_compenso = 0;
		$cont         = 0;
		$count_mounths= 0;
		
		foreach($info['mounths'] as $mese => $mounth) {
			
			$mounth = (array)$mounth;
			
			$tot_media += $mounth['MediaRanking'];
			$tot_gare  += $mounth['NumeroGare'];
			$tot_vote  += $mounth['VoteSend'];
			$tot_bonus += $mounth['Bonus'];
			$tot_compenso += $mounth['Compenso'];
			$cont++;
			if($mounth['MediaRanking'] > 0) $count_mounths++;
		}		
		
		
		return "€ " . $info['tot_compensi'];
		
	}
	
	function getBuste($id) {
		
			$file = APP . 'webroot/files/json/lda/'.$id.'_summary_lda.json';
			
			if(is_file($file))
			{
				
				return json_decode(file_get_contents($file));
				
			} 
			
			App::Import('Model', 'AnniSportivi');
			$AnniSportivi = new AnniSportivi;
			App::Import('Model', 'Yearbook');
			$Yearbook = new Yearbook;			
			App::Import('Model', 'Match');
			$Match = new Match;	
			App::Import('Model', 'LdaVote');
			$LdaVote = new LdaVote;	
			App::Import('Model', 'AthleteExpense');
			$AthleteExpense = new AthleteExpense;									
			
			$last_year = $AnniSportivi->find('first', array('fields' => array('AnniSportivi.AnnoSportivo'), 'order' => 'AnniSportivi.AnnoSportivo DESC', 'limit' => 1));
			
			//Cerco gare effettuate dall'atleta
			$matches = $Match->find('all',
			
				array(
				
					'conditions' => array(
					
						'OR' => array(
						
							'Lda.Arbitro' => $id,
							'Lda.Arbitro2' => $id,
							'Lda.Delegato' => $id,
							'Lda.DelegatoA' => $id,
							
							
						),
						'AND' => array(
						
							'Campionati.AnnoSportivo' => $last_year['AnniSportivi']['AnnoSportivo'],
						
						),
					
					),
					'order' => array('Match.Data DESC'),
					'fields' => array(
					
						'Match.Calendario',
						'Match.Data',
						'Lda.Arbitro',
						'Lda.Arbitro2',
						'Lda.Delegato',
						'Lda.DelegatoA',
						'Campionati.TariffaArbitro',
						'Campionati.TariffaDelegato',
						'Campionati.TariffaDelegatoA',
						'Campionati.TariffaArbitro2',
						'Match.Risultato',
					
					),
				
				)
			
			);
			
			$riepilogo = array();
			
			foreach($matches as $match) {
				
				$datetime = strtotime($match['Match']['Data']);
				$mese     = date("m", $datetime);
				$anno     = date("Y", $datetime);
				
				$votes = $LdaVote->find('all', array(
				
					'conditions' => array(
						'match_id' => $match['Match']['Calendario'],
						'athlete_lda_id' => $id,
					),
				
				));
				
				$votes_send = $LdaVote->find('all', array(
				
					'conditions' => array(
						'match_id' => $match['Match']['Calendario'],
						'athlete_id' => $id,
					),
				
				));						
				
				$match['Match']['AnnoPartita'] = $anno;
				$match['LdaVote'] = $votes;
				$match['LdaVoteSend'] = $votes_send;
				
				$riepilogo[$mese][] = $match;
				
			}
			
			$mounth = array();
			$tot_compensi = 0;
			$votes = 0;
			
			foreach($riepilogo as $k => $matches) {

				$count_vote = 0;
				$count_send = 0;
				$bonus      = 0;
				$compenso   = 0;
				$anno       = '';
				$count_match= 0;
				$count_vote_received = 0;

				foreach($matches as $match) {
					foreach($match['LdaVote'] as $vote) {
						$count_vote_received++;
						$count_vote += $vote['LdaVote']['ranking'];	
					}
					foreach($match['LdaVoteSend'] as $vote) {
						$count_send++;
					}
					
					if($id == $match['Lda']['Arbitro']):   $compenso += $match['Campionati']['TariffaArbitro']; endif;
					if($id == $match['Lda']['Delegato']):  $compenso += $match['Campionati']['TariffaDelegato']; endif;
					if($id == $match['Lda']['DelegatoA']): $compenso += $match['Campionati']['TariffaDelegatoA']; endif;
					if($id == $match['Lda']['Arbitro2']):  $compenso += $match['Campionati']['TariffaArbitro2']; endif;
			
					if($id == $match['Lda']['Arbitro']):   	   $bonus++;
					elseif($id == $match['Lda']['Arbitro2']):  $bonus++; 
					elseif($id == $match['Lda']['DelegatoA']): $bonus+=0.5;
					elseif($id == $match['Lda']['Delegato']):  $bonus+=0.5;	endif;	
					
					if($match['Lda']['Arbitro'] != '' || $match['Lda']['Delegato'] != '' || $match['Lda']['DelegatoA']) { 
						if($match['Match']['Risultato'] != '' && $match['Lda']['Arbitro'] != $match['Lda']['Delegato'] && $match['Lda']['Arbitro'] != $match['Lda']['DelegatoA']) $count_match++; 
					} 
					
					$anno = $match['Match']['AnnoPartita'];
					
				}
				
				$votes += $count_vote_received;
				
				//debug($count_match);
				
				if($count_vote_received > 0) {
					$media_ranking = ceil($count_vote / $count_vote_received);
				}else {
					$media_ranking = 0;
				}
				
				if(($count_match - $count_send) <= 0) $vote_send = array('class' => 'full-rated', 'label' => 'Votazioni completate');
				else							      $vote_send = array('class' => 'not-rated', 'label' => ($count_match - $count_send) . ' partit'.(($count_match - $count_send == 1)? 'a' : 'e').' da votare');
				
				/*Calcolo spese*/
				$start_date = $anno . '-' . $k . '-' . '01';
				$end_date   = $anno . '-' . $k . '-' . '31';
				 						
				$altreSpese = $AthleteExpense->find('all', array(
				
					'conditions' => array(
					
						'AthleteExpense.Atleta' 	          => $id,
						'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
					
					),
					'recursive' => 0,
				
				));
				
				$spese = 0;
				
				foreach($altreSpese as $spesa) {
					$spesa = $spesa['AthleteExpense'];
					$spese += $spesa['Importo'];
				}						
				
				$mounth[$k]['NumeroGare']   = count($matches);
				$mounth[$k]['MediaRanking'] = $media_ranking;
				$mounth[$k]['Votazioni']    = $vote_send;
				$mounth[$k]['VoteSend']     = $count_send;
				$mounth[$k]['Bonus']    	= $bonus;
				$mounth[$k]['Compenso']		= '€ ' . ($compenso+$spese);
				$mounth[$k]['Anno']		    = $anno;
				
				$tot_compensi += $compenso;
				
			}
			
			if(empty($matches)) {
				
				$spese  = $AthleteExpense->find('all', array(
				
					'conditions' => array(
					
						'AthleteExpense.Atleta' => $id,
						'YEAR(AthleteExpense.Data)' => array(($last_year['AnniSportivi']['AnnoSportivo']-1), $last_year['AnniSportivi']['AnnoSportivo'])
						
					),
					'recursive' => -1,
					'order' => array('AthleteExpense.Data DESC')
				
				));
				
				$riepilogo = array();				
				
				foreach($spese as $spesa) {
				
					$datetime = strtotime($spesa['AthleteExpense']['Data']);
					$mese     = date("m", $datetime);
					$anno     = date("Y", $datetime);
					
					$riepilogo[$mese][] = $spesa;
				
				}
				
				$mounth = array();
				$tot_compensi = 0;
				
				foreach($riepilogo as $k => $tmp) {
					
					/*Calcolo spese*/
					$start_date = $anno . '-' . $k . '-' . '01';
					$end_date   = $anno . '-' . $k . '-' . '31';
					 						
					$altreSpese = $AthleteExpense->find('all', array(
					
						'conditions' => array(
						
							'AthleteExpense.Atleta' 	          => $id,
							'AthleteExpense.Data BETWEEN ? and ?' => array($start_date,$end_date),
						
						),
						'recursive' => 0,
					
					));
					
					$spese = 0;
					
					foreach($altreSpese as $spesa) {
						$spesa = $spesa['AthleteExpense'];
						$spese += $spesa['Importo'];
					}	
					
					$mounth[$k]['NumeroGare']   = 0;
					$mounth[$k]['MediaRanking'] = 0;
					$mounth[$k]['Votazioni']    = 0;
					$mounth[$k]['VoteSend']     = 0;
					$mounth[$k]['Bonus']    	= 0;
					$mounth[$k]['Compenso']		= '€ ' . $spese;
					$mounth[$k]['Anno']		    = $anno;	
					
					$tot_compensi += $spese;						
					
				}
				
			}
	
			$info = array(
			
				'stagione' => $last_year['AnniSportivi']['AnnoSportivo'],
				'tot_compensi' => $tot_compensi,
				'mounths' => $mounth,
				'vote' => $votes
			
			);
			
			file_put_contents($file, json_encode($info));
			
			return $info;
		
	}	

	class SummaryLdasController extends AppController {
	
		var $name = "SummaryLdas";
		var $helpers = array('Backend','Javascript','Cksource');	
		var $login_required = true;
		var $uses = array('Yearbook','Match','Athlete');
		
		function admin_index() {
			
			
			
		}
		
		function getInfo() {
			
			$this->autoRender = false;

			$athletes = $this->Athlete->find('list', array(
			
				'fields' => array('Athlete.Atleta', 'Athlete.Nome'),
				'conditions' => array('Athlete.Arbitro' => 'Si')
			
			));
			
			foreach($athletes as $id_athlete => $athlete) {
				
				debug('Executed: ' . $id_athlete);
				$file = APP . 'webroot/files/json/lda/'.$id_athlete.'_summary_lda.json';
				@unlink($file);					
				getBuste($id_athlete);	
				
			}
			
			exit;
			
		}
	
	}
