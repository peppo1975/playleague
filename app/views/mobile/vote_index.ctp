<? 

$data = $this->Session->read('Login.data');

if($data['is_arbitro'])   $type = 'Arbitro';
else				      $type = 'Athlete';

?>

<script type="text/javascript">

$(function(){

	$("#LdaVoteVoteIndexForm").submit(function(){
	
		var data = $(this).serialize();
		
		$.post('/mobile/vote_exec', data, function(ret){
			
			<? if($type == "Athlete"): ?>
			location.href = '/mobile/vota#<?=$hash;?>';
			<? else: ?>
			location.href = '/mobile/vote#<?=$hash;?>';
			<? endif; ?>
			
		
		},'json');
		
		return false;
	
	});

});

</script>

<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			<a data-ajax="false" href="/mobile/reserved" title="Gestione profilo">			
				Gestione profilo
			</a>
			&rsaquo;
		</li>
		<li>
			<? if($type == "Athlete"): ?>
					
			<a data-ajax="false" href="/mobile/vota" title="Votazioni">
			
			<? else: ?>
			
			<a data-ajax="false" href="/mobile/vote" title="Votazioni">
			
			<? endif; ?>		
			
			Votazioni			
			</a>
			&rsaquo;
		</li>
		<li>
			Vota
		</li>
		
	</ul>
	
</div>

								<div class="rating-box" data-athlete="<?if($match['Lda']['Arbitro'] == $athlete['Athlete']['Atleta']):?>Arbitro<?else:?>Delegato<?endif;?>" data-athlete-id="<?=$athlete['Athlete']['Atleta'];?>" data-match-id="<?=$match['Match']['Calendario'];?>">
								
									<h1>
										<?if($match['Lda']['Arbitro'] == $athlete['Athlete']['Atleta']):?>
											Valuta l'arbitro
										<?else:?>
											Valuta il delegato
										<?endif;?>
									</h1>
									<div id="results-box">
									<table>
										<tr>
											<th>Data</th>
											<td><?=$match['Match']['Data_it'];?></td>
										</tr>
										<tr>
											<th>Squadre</th>
											<td><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></td>
										</tr>
										<tr>
											<th>Risultato</th>
											<td><?=$match['Match']['Risultato'];?></td>
										</tr>
										<tr class="last-row">
											<th>
												Persona da votare
											</th>
											<td>
												<?if($match['Lda']['Arbitro'] == $athlete['Athlete']['Atleta']):?>
												Arbitro
												<?else:?>
												Delegato
												<?endif;?>
											</td>
										</tr>
									</table>
									</div>
								
									<p>
										Inserisci qui il ranking. Una volta effettuata l'operazione non sarà piu possibile votare.
									</p>
									
									<?=$this->Form->create('LdaVote', array('data-ajax' => 'false'));?>
									
									<?=$this->Form->input('match_id', array('type' => 'hidden', 'value' => $match['Match']['Calendario']));?>
									<?=$this->Form->input('athlete_lda_id', array('type' => 'hidden', 'value' => $athlete['Athlete']['Atleta']));?>
									<?=$this->Form->input('athlete_id', array('type' => 'hidden', 'value' => $this->Session->read('Login.data.id')));?>
									
									<?
										
										$options = array(
										
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
									
									?>
									
									<?=$this->Form->input('ranking', array('type' => 'select', 'options' => $options, 'label' => '&nbsp;'));?>
									
									<div class="input">
									<label> &nbsp;</label>
									<?=$this->Form->submit('Vota',array('type' => 'submit','div' => false));?>
									</div>										
									
									<?=$this->Form->end();?>
										
								</div>