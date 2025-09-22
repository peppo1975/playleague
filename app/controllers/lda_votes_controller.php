<?

	class LdaVotesController extends AppController {
	
		var $name = "LdaVotes";
		var $helpers = array('Backend','Javascript','Cksource');	
		var $login_required = true;
		var $uses = array('LdaVote','Match','Athlete');
		
		function vote_index($match, $athlete) {
		
			$this->layout = "timmybox_web";
			
			$this->set('match', $this->Match->findByCalendario($match));
			$this->set('athlete', $this->Athlete->findByAtleta($athlete));
			
		}
		
		function vote() {
		
			$this->layout = "ajax";
			
			$this->LdaVote->create();
			$this->LdaVote->set($this->data);
			$this->LdaVote->save();
			
			$options = array(
			
				1 => 'Gravemente insufficiente',
				2 => 'Insufficiente',
				3 => 'Appena sufficiente',
				4 => 'Sufficiente',
				5 => 'Discreto',
				6 => 'Buono',
				7 => 'Ottimo',
			
			);			
			
			$this->set('result', json_encode(array('voto' => $options[$this->data['LdaVote']['ranking']])));
			$this->render('/backend/ajaxResult');
		
		}
		
		function giaVotato($athlete, $lda, $match = null) {
		
			$count = $this->LdaVote->find('first', array(
			
				'conditions' => array(
				
					'LdaVote.match_id' => $match,
					'LdaVote.athlete_lda_id' => $lda,
					'LdaVote.athlete_id' => $athlete,
				
				),
			
			));
			
			if(count($count) > 0) return $count;
			else 			      return false;
		
		}
	
	}
