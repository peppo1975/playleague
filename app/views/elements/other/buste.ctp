<?

$data = $this->Session->read('Login.data');

if($data['is_arbitro'])   $type = 'Athlete';
elseif($data['is_user'])  $type = 'User';

$id = $data['id'];

$mesi = array(

	'01' => 'Gennaio',
	'02' => 'Febbraio',
	'03' => 'Marzo',
	'04' => 'Aprile',
	'05' => 'Maggio',
	'06' => 'Giugno',
	'07' => 'Luglio',
	'08' => 'Agosto',
	'09' => 'Settembre',
	'10' => 'Ottobre',
	'11' => 'Novembre',
	'12' => 'Dicembre',

);

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

$end_days = array(
	
	'01' => '31',
	'02' => '29',
	'03' => '31',
	'04' => '30',
	'05' => '31',
	'06' => '30',
	'07' => '31',
	'08' => '31',
	'09' => '30',
	'10' => '31',
	'11' => '30',
	'12' => '31',	

);

?>

<div class="container-table-profile table-container">
			
	<div id="results-box" class="table-profile">

		<table class="table-matches table table-bordered table-condensed table-striped">

			<?
			
				$tot_media    = 0; 
				$tot_gare     = 0;
				$tot_vote     = 0;
				$tot_bonus    = 0;
				$tot_compenso = 0;
				$cont         = 0;
				$count_mounths= 0;
				
			foreach($mounths as $mese => $mounth):	
				$tot_media += $mounth['MediaRanking'];
				$tot_gare  += $mounth['NumeroGare'];
				$tot_vote  += $mounth['VoteSend'];
				$tot_bonus += $mounth['Bonus'];
				$tot_compenso += $mounth['Compenso'];
				$cont++;
				if($mounth['MediaRanking'] > 0) $count_mounths++;
			endforeach;
			
			?>			
			
			<thead class="table-header">
				<th>TOTALE RANKING PERSONALE</th>
				<th class="text-center">TOT GARE</th>
				<th class="text-center">TOT VOTI</th>
				<th class="text-right">TOT BONUS</th>
				<th class="text-right">TOT COMPENSI</th>
			</thead>	
			
			<tr>
				<td><?=$options[@ceil($tot_media / $count_mounths)];?></th>
				<td class="text-center"><?=$tot_gare;?></th>
				<td class="text-center"><?=number_format($tot_vote, 1, ".", ",");?></th>
				<td class="text-right"><?=number_format($tot_bonus, 1, ".", ",");?></th>
				<td class="text-right"><?="&euro; " . number_format($tot_compensi, 2, ".", ",");?></th>
			</tr>
	
		</table>	
		
		<div class="alert alert-warning">
			Si ricorda che avranno accesso al bonus tutti coloro che al 30/07/12 avranno la "Media Ranking" almeno di "Sufficiente"", che abbiano superato le 125 gare e che abbiano eseguito le votazioni in tutte le gare effettuate.	
		</div>			


	</div>		

	<div id="results-box">

		<? if(count($mounths) || count($spese)): ?>
		
			<table class="table-matches table-header table table-bordered table-condensed table-striped">
			
				<thead class="">
					<th>MESE</th>
					<th>RANKING</th>
					<th class="text-center">NUM GARE</th>
					<th>VOTAZIONI</th>
					<th class="text-right">BONUS</th>
					<th class="text-right">COMPENSI</th>
					<th class="text-center">BUSTA PAGA</th>
				</thead>
			
				<? foreach($mounths as $mese => $mounth): ?>
				
					<tr class="<?=(($cont+1) % 2 == 0)? 'alternate' : '';?>">
						<td><?=$mesi[$mese];?></td>
						<td><?=$options[$mounth['MediaRanking']];?></td>
						<td class="text-center"><?=$mounth['NumeroGare'];?></td>
						<td><? if($mounth['Votazioni']['class'] != 'not-rated'): ?> <span class="<?=$mounth['Votazioni']['class'];?>"><?=$mounth['Votazioni']['label']; ?><span> <? else: ?> <a title="<?=$mounth['Votazioni']['label'];?>" href="/gestione/votazioni#<?=$mese;?>" class="<?=$mounth['Votazioni']['class'];?>"><?=$mounth['Votazioni']['label'];?></a><? endif; ?></td>
						<td class="text-right"><?=number_format($mounth['Bonus'], 1, ".", ",")?></td>
						<td class="text-right"><?="&euro; " . number_format(str_replace("€", "", $mounth['Compenso']), 2, ".", ",");?></td>
						<td class="text-center"><a href="javascript:;" data-id="<?=($this->Session->read('Login.data.id') != '')? $this->Session->read('Login.data.id') : $athlete_id;?>" data-mounth="<?=$mese;?>" data-start="01" data-end="<?=$end_days[$mese];?>" data-year="<?=$mounth['Anno'];?>" class="ldaPrint" data-tooltip="" data-original-title="Stampa busta paga di <?=$mesi[$mese];?>"  rel="timmytip"><img alt="stampa" src="/img/icon-pdf.png"/></a></td>	
					</tr>		
					
					<?
					
					$tot_media += $mounth['MediaRanking'];
					$tot_gare  += $mounth['NumeroGare'];
					$tot_vote  += $mounth['VoteSend'];
					$tot_bonus += $mounth['Bonus'];
					$tot_compenso += $mounth['Compenso'];
					$cont++;
					
					?>	
				
				<? endforeach; ?>
			
			</table>
		
		</div>
<? /*
		<table class="table-matches table-header table table-bordered table-condensed table-striped">
			<thead class="">
				<tr>
					<th>DATA</th>
					<th class="text-right">IMPORTO</th>
					<th>DESCRIZIONE</th>
				</tr>
			</thead>
			
			<? foreach($spese as $spesa): ?>
			<? $count = 0; ?>
			<tr>
				<td><?=$spesa['AthleteExpense']['Data_it'];?></td>
				<td class="text-right"><?="&euro; " . number_format($spesa['AthleteExpense']['Importo'], 2, ".", ",");?></td>
				<td><?=$spesa['AthleteExpense']['Descrizione'];?></td>		
			</tr>
			
			<? endforeach; ?>
		
		</table>

*/
?>		

		<? else: ?>
		
		<div class="alert alert-warning">
		Nessun riepilogo per questa sgagione.

		</div>
		<? endif; ?>

	</div>

</div>