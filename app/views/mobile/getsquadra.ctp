	<table class="table-matches table-calcio table-squadra" width="100%">
														<tr>
															<td><b>SQUADRA</b></td>
															<td><a href="/mobile/squadra/<?=$squadra['Squadre']['Squadra'];?>?type=<?=$_GET['type'];?>" title="" data-ajax="false"><?=$squadra['Squadre']['Denominazione'];?></a></td>
														</tr>
														<tr>
															<td><b>LOGO</b></td>
															<td>
														
															<? if (!empty($squadra['Info']['Logo'])): ?>
															<?=$thumbnail->show(array('path' => $squadra['Info']['Logo'],'w' => 50, 'f' => 'png'));?>
															<? else: ?>
															
															<img src="/img/website/icon_team_default.png" alt="Logo <?=$squadra['Squadre']['Denominazione'];?>" />
															<? endif; ?>
															</td>
														</tr>	
														<tr>
															<td><b>SPONSOR</b></td>
															<td>
															<? if (!empty($squadra['Info']['sponsor'])): ?>
															<?=$thumbnail->show(array('path' => $squadra['Info']['sponsor'],'w' => 50));?>
															<? else: ?>
															
															<img src="/img/website/icon_team_default.png" alt="Sponsor <?=$squadra['Squadre']['Denominazione'];?>" />
															
															<? endif; ?>
															</td>
														</tr>
														<tr>
															<td><b>CAMPIONATI DISPUTATI</b></td>
																												
															<td><?=$squadra['Info']['Campionati'];?></td>
														</tr>
														<tr>
														<td><b>STAGIONI</b></td>
																						
														<td><?=$squadra['Info']['Stagioni'];?></td>
														</tr>
																	
																		
	</table>
	
<div class="atleta-boxes" style="padding: 20px;">
<? foreach ($roster as $k => $atleta): ?>



<div class="atleta-box" style="width: 100%; height: 200px; border: 3px solid #C6CCF3; margin-bottom: 20px; background: #F4F6FF; box-shadow: 1px 1px 5px #CCCCCC;">

	<div class="atleta-left" style="float: left; margin-right: 10px; padding: 20px;">
	
<?
			
				if (!empty($atleta['Athlete']['avatar'])) {
					
					$link = $atleta['Athlete']['avatar'];
					
				} else {
				
					$link = '/img/icon_profile_default.png';
					
				}
			
			?>
			
			<img src="<?=$thumbnail->link(array('path' => $link,'w' => '50','f' => 'png'));?>" alt="<?=$atleta['Athlete']['Nome'];?> <?=$atleta['Athlete']['Cognome'];?>" />
				

	</div>
	
	<div class="atleta-right" style="float: left; padding-top: 20px;">
	
	
			<p class="atleta-nome" style=" color: #444; font-family: Din,Arial; font-size: 15px; margin-bottom: 5px;"><?=$atleta['Athlete']['Cognome'];?> <?=$atleta['Athlete']['Nome'];?></p>		
			<p style="line-height: 20px;" class="atleta-tessera"><b>Tessera:</b> <span style="float: right;"><?=$atleta['Yearbook']['Tessera'];?></span></p>
			<? if ($atleta['Yearbook']['Ruolo'] != ''):?>
			<p style="line-height: 20px;" class="atleta-ruolo"><b>Ruolo:</b> <span style="float: right;"><?=$atleta['Yearbook']['Ruolo'];?></span></p>
			<? endif; ?>
			<p style="line-height: 20px;" class="atleta-reti"><b>Reti:</b> <span style="float: right;"><?=$atleta['stats']['Reti'];?></span></p>	
			<p style="line-height: 20px;" class="atleta-reti"><b>Presenze:</b> <span style="float: right;"><?=$atleta['stats']['Presenze'];?></span></p>			
						<? if ($atleta['Yearbook']['NumeroMaglia'] != ''):?>	
			<p style="line-height: 20px;" class="atleta-maglia"><b>Numero maglia:</b> <span style="float: right;"><?=($atleta['Yearbook']['NumeroMaglia'] != 0)? $atleta['Yearbook']['NumeroMaglia']:'';?></span></p>
			<? endif; ?>
			<p style="line-height: 20px;" class="atleta-assicurazione"><b>Assicurazione:</b> <span style="float: right;"><?=$atleta['TipiAssicurazione']['Simbolo'];?></span></p>
	
			<div style="margin-left: 18px; width: 25px; height: 25px; background-color: darkred; color: #fff; line-height: 25px; text-align: center; float: left; border: 1px solid white;"><?=$atleta['stats']['Espulsioni'];?></div>
			<div style="width: 25px; height: 25px; background-color: gold; color: #fff; float: left; margin-left: 20px; line-height: 25px; text-align: center; border: 1px solid white;"><?=$atleta['stats']['Ammonizioni'];?></div>
	
	</div>


</div>
<? endforeach; ?>
<div class="clear"></div>
</div>