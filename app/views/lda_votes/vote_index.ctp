<script type="text/javascript">

$(function(){

	$("#LdaVoteVoteIndexForm").submit(function(e){
	
		var selected = $("[name='data[LdaVote][ranking]'] option:selected").val();
		if( selected == 0 )
		{
			displayError();
			e.preventDefault();
			return;
		}

		var data = $(this).serialize();
		ajaxLoader('show');
		$.post('/lda_votes/vote', data, function(ret){
			ajaxLoader('hide');
		
			timmy_close();
			
			console.log(ret.voto);
			
			$('.table-matches').find('tr[data-id="' + $('.booking-data').attr('data-match-id') + '"]')
							   .find('a[data-id="' + $('.booking-data').attr('data-athlete-id') + '"]')
							   .parents('td')
							   .empty('')
							   .html($('<span>').addClass('rated').attr('rel','timmytip').attr('title','Voto: ' + ret.voto).attr('data-tip-title','Voto: ' + ret.voto).text($('.booking-data').attr('data-athlete')))
		
		location.reload();
		},'json');
		
		return false;
	
	});

});

function displayError()
{
	alert("Devi inserire un giudizio!");
}
$(".select2").select2();

</script>

								<div class="booking-data" data-athlete="<?if($match['Lda']['Arbitro'] == $athlete['Athlete']['Atleta']):?>Arbitro<?else:?>Delegato<?endif;?>" data-athlete-id="<?=$athlete['Athlete']['Atleta'];?>" data-match-id="<?=$match['Match']['Calendario'];?>">
								
									<h1 class="modal-title">Esprimi il tuo giudizio</h1>
									<table class="table table-striped table-condensed">
										<tr>
											<th style="border-top: 0">Data</th>
											<td style="border-top: 0"><?=$match['Match']['Data_it'];?></td>
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
									<div class="booking-pad">
										<div class="row">
											<div class="col-md-12 text-center">
												<div class="alert alert-warning">
													Inserisci qui il ranking. Una volta effettuata l'operazione non sarà piu possibile votare.
												</div>
												
												<?=$this->Form->create('LdaVote');?>
												
												<?=$this->Form->input('match_id', array('type' => 'hidden', 'value' => $match['Match']['Calendario']));?>
												<?=$this->Form->input('athlete_lda_id', array('type' => 'hidden', 'value' => $athlete['Athlete']['Atleta']));?>
												<?=$this->Form->input('athlete_id', array('type' => 'hidden', 'value' => $this->Session->read('Login.data.id')));?>
												
												<?
													
													$options = array(
														0 => 'Esprimi il tuo giudizio',
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
												<style>

												</style>
												<div class="row">
													<div class="col-md-10">
														<?=$this->Form->input('ranking', array('type' => 'select', 'div' => false, 'class' => 'from-control select2 pull-left', 'options' => $options, 'label' => ''));?>
													</div>
													<div class="col-md-2">
														<?=$this->Form->submit('vota',array('type' => 'submit','div' => false, "style" => "padding: 8px 17px !important", 'class'=>'btn btn-success btn-md pull-right'));?>
													</div>
												</div>
											</div>
									</div>									
									
									<?=$this->Form->end();?>
										
								</div>