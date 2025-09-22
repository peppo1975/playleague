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

<script type="text/javascript">

$(function(){

	$(".ldaPrint").click(function() {
		
		var start   = $(this).attr('data-start');
		var end	    = $(this).attr('data-end');
		var year    = $(this).attr('data-year');
		var mounth  = $(this).attr('data-mounth');
		var athlete = $(this).attr('data-id');
	
		var start_date = start + '/' + mounth + '/' + year;
		var end_date   = end + '/' + mounth + '/' + year;
		
		data = { "start": start_date, "end": end_date, "athlete": athlete}
		
		$.post('/prints/single_lda/', {"datas":data},function(ret) {
		
				location.href = '/' + ret.link;
			
		},'json');
		
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
			Buste paga
		</li>
		
	</ul>
	
</div>

<div class="reserved-area">


			<div class="container-table-profile">
			
			<div id="results-box" class="table-profile">
			
			<table class="table-matches">
			
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
			
			<tr>
				<th>TOTALE RANKING PERSONALE</th>
				<td><?=$options[@ceil($tot_media / $count_mounths)];?></th>				
			</tr>
			<tr>			
				<th>TOT GARE</th>
				<td><?=$tot_gare;?></th>				
			</tr>				
			<tr>				
				<th>TOT VOTI</th>
				<td><?=$tot_vote;?></th>				
			</tr>				
			<tr>				
				<th>TOT BONUS</th>
				<td><?=$tot_bonus;?></th>				
			</tr>				
			<tr>				
				<th>TOT COMPENSI</th>
				<td><?="€ " . $tot_compensi;?></th>				
			</tr>	
			
			</table>	
			
			<p class="table-info">
				Si ricorda che avranno accesso al bonus tutti coloro che al 30/07/12 avranno la "Media Ranking" almeno di "Sufficiente"", che abbiano superato le 125 gare e che abbiano eseguito le votazioni in tutte le gare effettuate.				
			</p>			
					
			<? if(count($mounths)): ?>
			
			<? $now = date("m"); ?>
			
			<script type="text/javascript">
				
				$(function(){
				
					$('select[name="giornata_id"]').die('change').live('change', function(){
					
						var me      = $(this);

						$('.table-buste').hide();
						
						if(me.val() == "")
							return false;
						
						$('.table-buste[data-id='+me.val()+']').show();
					
					});
				
				});
			
			</script>
		
			
			<select name="giornata_id" autocomplete="off">
		
				<option value="">Seleziona mese di riferimento</option>
		
				<? 
				
					$tmp_mounths = $mounths; 
					asort($tmp_mounths);
				
				?>
		
				<? foreach($tmp_mounths as $mese => $mounth): ?>

				<option value="<?=$mese;?>" <? if($mese == $now): ?>selected="selected"<? endif; ?>><?=$mesi[$mese];?></option>
				
				<? endforeach; ?>
			
			</select>				
			
			<? foreach($mounths as $mese => $mounth): ?>			
			
			<table class="table-matches table-buste" <? if($mese != $now): ?>style="display: none;"<? endif; ?> data-id="<?=$mese;?>">
			
				<tr>
					<th>RANKING</th>
					<td><?=$options[$mounth['MediaRanking']];?></td>
				</tr>
				<tr>					
					<th>NUM GARE</th>
					<td><?=$mounth['NumeroGare'];?></td>				
				</tr>
				<tr>
					<th>VOTAZIONI</th>
					<td><? if($mounth['Votazioni']['class'] != 'not-rated'): ?> <span class="<?=$mounth['Votazioni']['class'];?>"><?=$mounth['Votazioni']['label']; ?><span> <? else: ?> <a title="<?=$mounth['Votazioni']['label'];?>" data-ajax="false" href="/mobile/vote#<?=$mese;?>" class="<?=$mounth['Votazioni']['class'];?>"><?=$mounth['Votazioni']['label'];?></a><? endif; ?></td>				
				</tr>
				<tr>
					<th>BONUS</th>
					<td><?=$mounth['Bonus'];?></td>				
				</tr>
				<tr>
					<th>COMPENSI</th>
					<td><?=$mounth['Compenso'];?></td>				
				</tr>
				<tr>
					<th>BUSTA PAGA</th>
					<td><a href="javascript:;" data-ajax="false" target="_blank" data-id="<?=($this->Session->read('Login.data.id') != '')? $this->Session->read('Login.data.id') : $athlete_id;?>" data-mounth="<?=$mese;?>" data-start="01" data-end="<?=$end_days[$mese];?>" data-year="<?=$mounth['Anno'];?>" class="ldaPrint" title="Stampa busta paga di <?=$mesi[$mese];?>" rel="timmytip"><img alt="stampa" src="/img/icon-pdf.png"/></a></td>					
				</tr>
					
					<?
					
					$tot_media += $mounth['MediaRanking'];
					$tot_gare  += $mounth['NumeroGare'];
					$tot_vote  += $mounth['VoteSend'];
					$tot_bonus += $mounth['Bonus'];
					$tot_compenso += $mounth['Compenso'];
					$cont++;
					
					?>	
			
			</table>
			
			<? endforeach; ?>			
			
			</div>
			
			<? else: ?>
			
			Nessun riepilogo per questa sgagione.
	
			<? endif; ?>
	
			</div>
			
			</div>
		
			<div class="clear"></div>

</div><!-- close wrapper-box -->