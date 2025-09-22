<ul class="switch-table-menu">
	<li class="switch-giornata selected"><a href="javascript:;" title=""><?=$squadra['Squadre']['Denominazione'];?></a></li>
</ul>

<div class="clear"></div>

<div id="results-box">

<table class="table-matches">

	<tr class="table-header">
		<th class="athlets-photo">Foto</th>
		<th>Cognome</th>		
		<th>Nome</th>
		<th>Tessera</th>
		<th>Ruolo</th>
		<th align="center">Goal</th>
		<th>Numero maglia</th>
		<th>Assicurazione&nbsp;**</th>
	</tr>
	
	<? foreach ($roster as $k => $atleta): ?>
	
		<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>">
			<td class="athlets-photo"><?
			
				if (!empty($atleta['Athlete']['avatar'])) {
					
					$link = $atleta['Athlete']['avatar'];
					
				} else {
				
					$link = '/img/icon_profile_default.png';
					
				}
			
			?>
			
			<img src="<?=$thumbnail->link(array('path' => $link,'w' => '50','f' => 'png'));?>" alt="<?=$atleta['Athlete']['Nome'];?> <?=$atleta['Athlete']['Cognome'];?>" />
			
			</td>
			<td><?=$atleta['Athlete']['Cognome'];?></td>
			<td><?=$atleta['Athlete']['Nome'];?></td>			
			<td><?=$atleta['Yearbook']['Tessera'];?></td>
			<td><?=$atleta['Yearbook']['Ruolo'];?></td>
			<td align="center"><?=$atleta['stats']['Reti'];?></td>			
			<td><?=($atleta['Yearbook']['NumeroMaglia'] != 0)? $atleta['Yearbook']['NumeroMaglia']:'';?></td>
			<td><?=$atleta['TipiAssicurazione']['Simbolo'];?></td>
			
		</tr>
	
	<? endforeach; ?>
	
</table>

<p class="legend-team">

**&nbsp;Assicurazione
<br />
<b>B</b> Tesseramento con Assicurazione Base / <b>M</b> Tesseramento con Assicurazione Base / <b>F</b> Tesseramento con Assicurazione Full

</p>
	
</div>
