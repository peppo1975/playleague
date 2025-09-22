<? 
	// Copiato da /elements/site/impianti/booking.ctp
	// Testalo da /testWidgetDisponibilita.htm
	$campo  = $booking['campo'];
	$giorni = $booking['giorni'];

	$dow['1'] = 'Lunedì';
	$dow['2'] = 'Martedì';
	$dow['3'] = "Mercoledì";
	$dow['4'] = 'Giovedì';
	$dow['5'] = 'Venerdì';
	$dow['6'] = 'Sabato';
	$dow['7'] = 'Domenica';

?>
<div class="wrapper-box" id="booking-box">
	<div class="wrapper-box-top"></div>
	<div class="wrapper-box-contents" id="filter-box">
		<div class="bookingResult">
		<p>Disponibilità per il campo <b><?=$campo['Campi']['Descrizione'];?></b></p>	
		<div class="table-container booking-table-container table-responsive">
			<table class="table-matches table-border table-striped table-condensed table">
				<thead class="table-header">

					<? $max = 0; ?>

					<? foreach ($giorni as $i => $giorno): ?>
					
						<td align="center" style="font-weight: normal;">
						<small>
						<? if ($giorno['DayOfWeek']==2||$giorno['DayOfWeek']==3):?>
						<?=substr($dow[$giorno['DayOfWeek']],0,3);?><br />

					<? else: ?>
						<?=substr($dow[$giorno['DayOfWeek']],0,3);?><br />
					<? endif; ?>
					</small>
						<b><?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?></b>
						
						<? if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']); ?>
						
						</th>
					
					<? endforeach; ?>

				</thead>
				
				
				<? for ($i=0;$i<$max;$i++): ?>
				

					
				<tr class="<?=(!($i%2))? 'alternate' : '';?>">
				
				<? foreach ($giorni as $k => $giorno): ?>
				
					<td align="center">
					
					<? if (isset($giorno['Orari'][$i])): ?>
					
					<? if ($giorno['Orari'][$i]['Occupato'] == 1): ?>
					
					<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
																
					<? endif; ?>
					<!-- fa fa-soccer-ball-o -->

					<? 
						$infos = explode("<br />",($giorno['Orari'][$i]['Info']));
					?>

					<label class="label label-sm label-danger booking-disabled">

					<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
					<i class="fa fa-soccer-ball-o"></i>
					<? endif; ?>
					<?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
					
					<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
					<!--
					<a data-top="-25px;" href="#" rel="timmytip" title="<?=htmlentities($giorno['Orari'][$i]['Info']);?>" >
						<img src="/img/website/icon_goals.png" width="16" haight=="16" alt="" />
					<a>
					-->
					
					<? endif; ?>
					
					<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
																
					<? endif; ?>
					
					<? else: ?>




					<? $disabled = 0; ?>
					<? 

					foreach ($campo['CampiDisabled'] as $date) {

							if ($date['giorno'] == $giorno['Data']) $disabled = 1;

					}

					?>

					<? if ($disabled == 0): ?>
					
					
					<label class="label label-sm label-success"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
					

					<? else: ?>

					<label class="label label-sm label-danger booking-disabled"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
					
					<? endif; ?>
					
					<? endif; ?>
					
					<? else: ?>
					
					&nbsp;
					
					<? endif; ?>
					</td>
						
				<? endforeach; ?>
					
				</tr>
				
				
				
				<? endfor; ?>
				
				
			</table>

		</div>
		
			<div class="clear"></div>
		</div>	
		<p class="text-center">
			<a href="http://www.midlandsport.it/impianti/<?=$booking['campo']['Campi']['id']?>#popular" target="_blank" class="belize-hole-flat-button">Prenota il campo &raquo;</a>	
		</p>
	</div>
	<div class="wrapper-box-bottom"></div>
</div>