<?php
error_reporting(0);
ini_set('display_errors','off');
function __array_orderby()
{
				$args = func_get_args();
				$data = array_shift($args);
				foreach ($args as $n => $field) {
					if (is_string($field)) {
						$tmp = array();
						foreach ($data as $key => $row)
							$tmp[$key] = $row[$field];
						$args[$n] = $tmp;
						}
				}
				$args[]  = &$data;
				
				$args2 = $args;
				
				foreach ($args as $i => $row) 
					$args[$i] = &$args2[$i];
				
				call_user_func_array('array_multisort', $args);
				return array_pop($args);
}
?>

<?if($export == 'pdf'):?>
<? if ($this->data['Print']['Stampa'] != 2): ?>

<? $fpdf->setup('P','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->AddFont('Calibri','','calibri.php'); ?>
<? $fpdf->AddFont('CalibriB','','calibrib.php'); ?>
<? $fpdf->SetFont('Helvetica','',8); ?>

<? $xls_table = ''; ?>

	<? foreach($gironi as $girone): ?>

		<? foreach ($giornate as $giornata): ?>
				
		<? $fpdf->AddPage(); ?>
		<? $fpdf->Ln(); ?>
		
		<?ob_start();?>
			<table border="1" width="100%" >
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="10"><b><?=$campionato['Campionati']['Nome'];?> - <?=$girone['Half']['Descrizione'];?></b></td>
				</tr>

			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>

		<? $fpdf->htmltable($table);?>

		<? $fpdf->Cell(189,6,'RISULTATI ' . $giornata . '° GIORNATA',0,1,'C'); ?>
		
		<?
		   $fpdf->setX(30); 
		?>
		
		<?ob_start();?>
		
		<table border="1" width="80%" align="center">
		
			<? foreach ($gare[$giornata][$girone['Half']['GironeCampionato']] as $match): ?>
		
						<tr>
							<td><?=$match['Match']['CasaNome'];?></td>
							<td><?=$match['Match']['TrasfertaNome'];?></td>
							<td width="10" align="center"><?=$match['Match']['Risultato'];?>&nbsp;&nbsp;</td>
							<td><?=$match['Causalresult']['Descrizione'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td><?=$riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
							<td></td>
							<td></td>
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
	
		<? $fpdf->htmltable($table);?>
		<? $fpdf->SetX(10.2); ?>
		
		<? if($campionato['Campionati']['Italiana'] == 'No'): ?>
		
		<? //$fpdf->Cell(189,10,'CLASSIFICA',0,1,'C'); ?>
		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>	
		
		<?ob_start();?>
		
			<table border="1" width="100%">
			
				<tr bgcolor="#ffff99">
					<td align="center" colspan="9"  style="bold" size="10">
						CLASSIFICA
					</td>
				</tr>
				<tr bgcolor="#ffff99">
					<td align="center" width="55">Società</td>
					<td align="center">Punti</td>
					<td align="center">Giocate</td>
					<td align="center">Vinte</td>
					<td align="center">Perse</td>
					<td align="center">Nulle</td>
					<td align="center">Goal Fatti</td>
					<td align="center">Goal Subiti</td>
					<td align="center">Coppa Disc.</td>
				</tr>
			
				<? 
					
					$c_classifica = $classifiche[$giornata][$girone['Half']['GironeCampionato']];
					 
					foreach($c_classifica as $k => $classifica)
						$c_classifica[$k]['SquadraNome'] = $classifica['InfoSquadra']['Squadre']['Denominazione'];	
						
					foreach ($c_classifica as $k => $classifica) 
						$c_classifica[$k]['DiffReti'] = (int)($classifica['GoalFatti'] - $classifica['GoalSubiti']);									
					
					$c_classifica = __array_orderby($c_classifica,'Punti',SORT_DESC,'DiffReti',SORT_DESC,'GoalFatti',SORT_DESC,'CoppaDisciplina',SORT_ASC);
					
				?>
			
				<? foreach ($c_classifica as $classifica): ?>
				
					<tr>
					
						<td><?=$classifica['InfoSquadra']['Squadre']['Denominazione'];?></td>
						<td align="center"><?=$classifica['Punti'];?></td>
						<td align="center"><?=$classifica['Giocate'];?></td>
						<td align="center"><?=$classifica['Vinte'];?></td>
						<td align="center"><?=$classifica['Perse'];?></td>
						<td align="center"><?=$classifica['Nulle'];?></td>
						<td align="center"><?=$classifica['GoalFatti'];?></td>
						<td align="center"><?=$classifica['GoalSubiti'];?></td>
						<td align="center"><?=$classifica['CoppaDisciplina'];?></td>
					
					</tr>
				
				<? endforeach; ?>
			
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<? endif; ?>	
		
		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>	
		
		
		
		<?ob_start();?>
		
		<table border="1" width="100%">
		
				<tr bgcolor="#ffff99">
					<td colspan="6" align="center" style="bold" size="10">
						Prossima giornata
					</td>
				</tr>	
				
			<?
			
			$c_gare_prox = $gare_prossima_giornata[$giornata][$girone['Half']['GironeCampionato']];
			$gare_prox   = array();
			foreach($c_gare_prox as $g_prox) {
				$gare_prox[$g_prox['Match']['Calendario']]          = $g_prox['Match'];
				$gare_prox[$g_prox['Match']['Calendario']]['Campo'] = $g_prox['Campi']['Descrizione'];
			}
			$gare_prox = __array_orderby($gare_prox,'Data',SORT_ASC,'Campo',SORT_ASC,'Ora',SORT_ASC);
			
			$count_gare_prox = count($gare_prox);
			
			
			?>	
		
			<? foreach ($gare_prox as $match): ?>
			
						<?
						
						$giorni_array = array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
					
						$giorno_array = getdate(strtotime($match['Data']));
						
						$match_giorno = $giorni_array[$giorno_array['wday']-1];	
						
						
						if(strlen($match['CasaNome']) > 27)
							$casa = substr($match['CasaNome'], 0, 27).'...';
						else $casa = $match['CasaNome']; 
						
						if(strlen($match['TrasfertaNome']) > 27)
							$trasferta = substr($match['TrasfertaNome'], 0, 27).'...';
						else $trasferta = $match['TrasfertaNome']; 						

						?>		
		
						<tr>
							<td width="13"><?=$match_giorno;?></td>
							<td width="16"><?=$match['Data_it'];?></td>
							<td width="11"><?=$match['Ora'];?></td>						
							<td width="50"><?=$casa;?></td>
							<td width="50"><?=$trasferta;?></td>
							<td><?=$match['Campo'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo_prox[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td colspan="5"><?=$riposo_prox[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
	
		<? if($count_gare_prox > 0): ?>
	
		<? $fpdf->htmltable($table);?>
		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>
		
		<? endif; ?>	
		
		<?ob_start();?>
		
		<table border="1" width="100%">
		
				<tr bgcolor="#ffff99">
					<td colspan="6" align="center" style="bold" size="10">
						<?=($giornata + 2) . '° GIORNATA';?>
					</td>
				</tr>			
				
			<?
			
			$c_gare_prox = $gare_prossima_giornata2[$giornata][$girone['Half']['GironeCampionato']];
			$gare_prox   = array();
			foreach($c_gare_prox as $g_prox) {
				$gare_prox[$g_prox['Match']['Calendario']]          = $g_prox['Match'];
				$gare_prox[$g_prox['Match']['Calendario']]['Campo'] = $g_prox['Campi']['Descrizione'];
			}
			$gare_prox = __array_orderby($gare_prox,'Data',SORT_ASC,'Campo',SORT_ASC,'Ora',SORT_ASC);
			
			$count_gare_prox2 = count($gare_prox);
			
			?>					
		
			<? foreach ($gare_prox as $match): ?>
			
						<?
						
						$giorni_array = array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
					
						$giorno_array = getdate(strtotime($match['Data']));
						
						$match_giorno = $giorni_array[$giorno_array['wday']-1];	
						
						if(strlen($match['CasaNome']) > 27)
							$casa = substr($match['CasaNome'], 0, 27).'...';
						else $casa = $match['CasaNome']; 
						
						if(strlen($match['TrasfertaNome']) > 20)
							$trasferta = substr($match['TrasfertaNome'], 0, 20).'...';
						else $trasferta = $match['TrasfertaNome']; 							

						?>		
		
						<tr>
							<td width="13"><?=$match_giorno;?></td>
							<td width="16"><?=$match['Data_it'];?></td>
							<td width="11"><?=$match['Ora'];?></td>						
							<td width="50"><?=$casa;?></td>
							<td width="50"><?=$trasferta;?></td>	
							<td><?=$match['Campo'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo_prox2[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td colspan="5"><?=$riposo_prox2[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
	
		<? if($count_gare_prox2 > 0): ?>
	
		<? $fpdf->htmltable($table);?>
		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>
		
		<? endif; ?>		
		
		<? //print_r($diffidati[$giornata][$girone['Half']['GironeCampionato']]); ?>
		
		<?ob_start();?>
		
			<table border="1" width="95">
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="10">Diffidati</td>
					
				</tr>

			
				<tr>

					<? $text = '';?>

					<? $i = 1; ?>

					<? foreach ($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $diffidato): ?>
				
								<? if ($diffidato[0]['Ammonizioni'] % 3 == 2): ?>
								
								<?
								
								$squadra = $diffidato[0]['NomeSquadra'];
								
								if(strlen($squadra) > 8) $squadra = substr($squadra, 0, 8).'...';
								
								?>
				
								<? $text .= " - " . $diffidato[0]['anagrafica']. " (" . $squadra . ")"; ?>
								
								<? if(($i%2) == 0) $text .= "\n\n"; ?> 

								<? $i++; ?>
						
								<? endif; ?>
								
					<? endforeach; ?>
					
					<? $text = nl2br($text); ?>
					
					<td width="95">
					
					<?=$text;?>

					</td>
				</tr>
				
			
			</table>
		
		<? $table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<? $_oldY = $fpdf->getY(); ?>

		<? $fpdf->setY($oldY); ?>
		
		<? $fpdf->setX(109); ?>
		
		<?ob_start();?>
		
			<table border="1" width="90">
			
				<tr bgcolor="#ffff99" width="90">
					<td align="center" width="90" colspan="2" style="bold" size="10">Classifica Marcatori</td>
					
				</tr>
				
				<? foreach ($classifica_marcatori[$giornata][$girone['Half']['GironeCampionato']] as $marcatori): ?>

					<tr width="90">
						<td width="7"><?=$marcatori[0]['goals'];?></td>
						<td width="83"><?=$marcatori[0]['anagrafica'];?> (<?=$marcatori[0]['NomeSquadra'];?>)</td>
					</tr>
					
				<? endforeach; ?>
				
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<?
		
		if($_oldY > $fpdf->getY()) {
			
			$newY = $_oldY + 5;
			
		} else {
			
			$newY = $fpdf->getY();
			
		}
		
		?>
		
		<?ob_start();?>		
		
		<? $fpdf->setY($newY); $oldY = $fpdf->getY(); ?>
		 
			<table border="1" width="95">
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="10">Squalificati - 1 gg.</td>
				</tr>

					<? foreach ($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $diffidato): ?>
				
								<? if ($diffidato[0]['Ammonizioni'] % 3 == 0 && $diffidato[0]['AmmonitoOggi'] == 1): ?>
								
								<tr>
									
									<td width="95">				
												<?=$diffidato[0]['anagrafica'];?> (<?=$diffidato[0]['NomeSquadra'];?>)
									</td>
								
								</tr>
				
								<? endif; ?>
					<? endforeach; ?>

			</table>

		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<? $firstColumnY = $fpdf->getY(); ?>
		
		<? $fpdf->setY($oldY); ?>
		
		<? $fpdf->setX(109); ?>
		
		<?ob_start();?>
		
			<table border="1" width="90">
			
				<tr bgcolor="#ffff99">
					<td colspan="2" align="center" style="bold" size="10">Espulsi</td>
				</tr>
				
				<?$i = 1;?>
				<? foreach ($espulsi[$giornata][$girone['Half']['GironeCampionato']] as $espulso): ?>
				
					<?$giorni = $espulso['GoalPartite']['EspulsioneGiornate'];?> 
					<?$inizio   = date('d/m/Y', strtotime($espulso[0]['Data']));?>
					<?$fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));?>
					
					<?if($giorni != '' && $giorni != 0){
					
						$periodo = $giorni . ' giornate';
					
					} else {
					
						if($inizio != '00/00/0000' && $fine != '00/00/0000') {
						
							$periodo = $inizio . ' - ' . $fine;
						
						} else {
						
							$periodo = '1 giornata';
						
						}
					
					}?>

					<tr>
						<td><?=$espulso[0]['anagrafica'];?> (<?=(strlen($espulso[0]['NomeSquadra']) > 12)? substr($espulso[0]['NomeSquadra'],0,12) . '...' : $espulso[0]['NomeSquadra'];?>)	</td>
						<td><?=$periodo;?></td>
					</tr>	
						<?$i ++;?>
				
				<? endforeach; ?>
			
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<?
		
		if($firstColumnY > $fpdf->getY()) {
			
			$newY = $firstColumnY + 5;
			
		} else {
			
			$newY = $fpdf->getY();
			
		}
		
		?>				
		
		<?/* if($fpdf->getY() > $firstColumnY) {
			
			$newY = $fpdf->getY();
			
		} else {
			
			$newY = $firstColumnY;
			
		}*/?>
		
		<? $fpdf->setY($newY + 3); ?>
		
		<?ob_start();?>
		
			<table border="1" width="100%">
			
				<tr bgcolor="#ffff99" style="bold" size="10">
					<td align="center" style="bold" size="10">Provvedimenti disciplinari</td>
					
				</tr>

			
				<? foreach ($disciplinari[$giornata][$girone['Half']['GironeCampionato']] as $disciplinare): ?>
				
					<tr>
								<tr><td><?=$disciplinare['Disciplinari']['NomeSquadra'];?> - <?=$disciplinare['Disciplinari']['Descrizione'];?> - <?=$disciplinare['Disciplinari']['Sanzione'];?>€</td></tr> 
					</tr>
				
				<? endforeach; ?>
			
			</table>
		
			<?$table = ob_get_clean();?>
			<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>		
		
		<? $fpdf->setY($fpdf->getY()+3); ?>

		
		<?ob_start();?>
		
			<table border="1" width="100%">
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="10">Comunicazioni</td>
					
				</tr>

			
				<? foreach ($comunicazioni[$giornata][$girone['Half']['GironeCampionato']] as $comunicazione): ?>
				
				<? 
					
					$limit = 131; 
					$text  = $comunicazione['Comunication']['Note'];
					$l     = strlen($text);
					$page  = ceil($l/$limit);
					
					$comunication = '';
					
					for($i = 0; $i < $page; $i++) {
						
						$comunication .= substr($text, $i*$limit, $limit) . "\n\n";
						
					}
					
					$comunication = nl2br($comunication);
				
				?>
				
								<tr><td><?=$comunication;?></td></tr> 
				
				<? endforeach; ?>
			
			</table>
		
			<?$table = ob_get_clean();?>
			<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>		
		 
		<? endforeach; ?>

	<? endforeach; ?>
	
<? else: ?>
	
	
<? $fpdf->setup('L','mm','a4'); ?>
<? $fpdf->SetMargins('10.2','5.0'); ?>
<? $fpdf->SetFont('Helvetica','',6.5); ?>


<? $xls_table = ''; ?>
<? $offsetX = 0; ?>

<? $p_counter = 0; ?>
<? $offsetX = 0; ?>
<? $fpdf->AddPage(); ?>

	<? foreach($giornate as $giornata): ?>

		<? foreach ($gironi as $girone): ?>

			<? if ($p_counter == 2) {
				
							
					 $fpdf->AddPage();
				
					 $p_counter = 0; 
				
					 $offsetX = 0;
				
				}
				
				if ($p_counter == 1) {
					$offsetX = 155;
					$fpdf->setY(0);
				}	
			?>

		<? $fpdf->setX($offsetX); ?>
		
		<? ob_start();?>
			<table border="1" width="135">
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="8"><b><?=$campionato['Campionati']['Nome'];?> - <?=$girone['Half']['Descrizione'];?></b></td>
				</tr>
			
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>
		
		<? $fpdf->Cell(135,6,'RISULTATI ' . $giornata . '° GIORNATA',0,1,'C'); ?>
		
		<? $fpdf->setX($offsetX); ?>
		
		<?ob_start();?>
		
		<table border="1" width="120">
		
			<? foreach ($gare[$giornata][$girone['Half']['GironeCampionato']] as $match): ?>
		
						<tr>
							<td><?=$match['Match']['CasaNome'];?></td>
							<td><?=$match['Match']['TrasfertaNome'];?></td>
							<td width="10" align="center"><?=$match['Match']['Risultato'];?>&nbsp;&nbsp;</td>
							<td><?=$match['Causalresult']['Descrizione'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td><?=$riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		<? $fpdf->htmltable($table);?>
		
		<? //$fpdf->Cell(142,10,'CLASSIFICA',0,1,'C'); ?>

		<? $fpdf->setX($offsetX); ?>
		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>	
		
		<? if($campionato['Campionati']['Italiana'] == 'No'): ?>

		<?ob_start();?>
		
			<table border="1" width="135">
				<tr bgcolor="#ffff99">
					<td align="center" colspan="9" style="bold" size="8">
						CLASSIFICA
					</td>
				</tr>			
			
				<tr bgcolor="#ffff99">
					<td align="center" width="50">Società</td>
					<td>Punti</td>
					<td>Giocate</td>
					<td>Vinte</td>
					<td>Perse</td>
					<td>Nulle</td>
					<td align="center" width="10">G.F.</td>
					<td align="center" width="10">G.S.</td>
					<td width="17">Coppa. Disc.</td>
				</tr>
			
				<? 
					
					$c_classifica = $classifiche[$giornata][$girone['Half']['GironeCampionato']];
					 
					foreach($c_classifica as $k => $classifica)
						$c_classifica[$k]['SquadraNome'] = $classifica['InfoSquadra']['Squadre']['Denominazione'];					 
					
					$c_classifica = __array_orderby($c_classifica,'Punti',SORT_DESC,'Giocate',SORT_DESC,'SquadraNome',SORT_ASC);
					
					foreach ($c_classifica as $k => $classifica) 
						$c_classifica[$k]['DiffReti'] = (int)($classifica['GoalFatti'] - $classifica['GoalSubiti']);									
					
					$c_classifica = __array_orderby($c_classifica,'Punti',SORT_DESC,'DiffReti',SORT_DESC,'GoalFatti',SORT_DESC,'CoppaDisciplina',SORT_ASC);					
					
				?>
			
				<? foreach ($c_classifica as $classifica): ?>
				
					<tr>
					
						<td><?=$classifica['InfoSquadra']['Squadre']['Denominazione'];?></td>
						<td align="center"><?=$classifica['Punti'];?></td>
						<td align="center"><?=$classifica['Giocate'];?></td>
						<td align="center"><?=$classifica['Vinte'];?></td>
						<td align="center"><?=$classifica['Perse'];?></td>
						<td align="center"><?=$classifica['Nulle'];?></td>
						<td align="center"><?=$classifica['GoalFatti'];?></td>
						<td align="center"><?=$classifica['GoalSubiti'];?></td>
						<td align="center"><?=$classifica['CoppaDisciplina'];?></td>
					
					</tr>
				
				<? endforeach; ?>
			
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? if ($p_counter != 1): ?>
			
			<? $fpdf->setX($offsetX+80-(80/2)-5); ?>
			
		<? else: ?>
		
			<? $fpdf->setX(135+80-(80/2)); ?>
		
		<? endif; ?>		
		
		<? $fpdf->setX($offsetX); ?>		
		
		<? $fpdf->htmltable($table);?>		
		
		<? endif; ?>

		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>		
		<? //$fpdf->setX($offsetX); ?>
		
		<?ob_start();?>
		
		<table border="1" width="135">
		
				<tr bgcolor="#ffff99">
					<td colspan="6" size="8" style="bold">
						Prossima giornata
					</td>
				</tr>	
				
			<?
			
			$c_gare_prox = $gare_prossima_giornata[$giornata][$girone['Half']['GironeCampionato']];
			$gare_prox   = array();
			foreach($c_gare_prox as $g_prox) {
				$gare_prox[$g_prox['Match']['Calendario']]          = $g_prox['Match'];
				$gare_prox[$g_prox['Match']['Calendario']]['Campo'] = $g_prox['Campi']['Descrizione'];
			}
			$gare_prox = __array_orderby($gare_prox,'Data',SORT_ASC,'Campo',SORT_ASC,'Ora',SORT_ASC);
			
			$count_gare_prox = count($gare_prox);
			
			?>							
		
			<? foreach ($gare_prox as $match): ?>
			
						<?
						
						$giorni_array = array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
					
						$giorno_array = getdate(strtotime($match['Data']));
						
						$match_giorno = $giorni_array[$giorno_array['wday']-1];	
						
						if(strlen($match['CasaNome']) > 18)
							$casa = substr($match['CasaNome'], 0, 18).'...';
						else $casa = $match['CasaNome']; 
						
						if(strlen($match['TrasfertaNome']) > 18)
							$trasferta = substr($match['TrasfertaNome'], 0, 18).'...';
						else $trasferta = $match['TrasfertaNome']; 							

						?>		
		
						<tr>
							<td width="7"><?=$match_giorno;?></td>
							<td width="15"><?=$match['Data_it'];?></td>
							<td width="9"><?=$match['Ora'];?></td>						
							<td width="30"><?=$casa;?></td>
							<td width="30"><?=$trasferta;?></td>
							<td><?=$match['Campo'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo_prox[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td colspan="5"><?=$riposo_prox[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
	
		<? if($count_gare_prox > 0): ?>
	
			<? if ($p_counter != 1): ?>
				
				<? $fpdf->setX($offsetX+80-(80/2)-5); ?>
				
			<? else: ?>
			
				<? $fpdf->setX(135+80-(80/2)); ?>
			
			<? endif; ?>		
			
			<? $fpdf->setX($offsetX); ?>	
		
			<? $fpdf->htmltable($table);?>
			<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>	
		
		<? endif; ?>

		<?ob_start();?>
		
		<table border="1" width="135">
		
				<tr bgcolor="#ffff99">
					<td colspan="6" style="bold" size="8">
						<?=($giornata + 2) . '° GIORNATA';?>
					</td>
				</tr>
				
			<?
			
			$c_gare_prox = $gare_prossima_giornata2[$giornata][$girone['Half']['GironeCampionato']];
			$gare_prox   = array();
			foreach($c_gare_prox as $g_prox) {
				$gare_prox[$g_prox['Match']['Calendario']]          = $g_prox['Match'];
				$gare_prox[$g_prox['Match']['Calendario']]['Campo'] = $g_prox['Campi']['Descrizione'];
			}
			$gare_prox = __array_orderby($gare_prox,'Data',SORT_ASC,'Campo',SORT_ASC,'Ora',SORT_ASC);
			
			$count_gare_prox_2 = count($gare_prox);
			
			?>									
		
			<? foreach ($gare_prox as $match): ?>
			
						<?
						
						$giorni_array = array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
					
						$giorno_array = getdate(strtotime($match['Data']));
						
						$match_giorno = $giorni_array[$giorno_array['wday']-1];	
						
						if(strlen($match['CasaNome']) > 18)
							$casa = substr($match['CasaNome'], 0, 18).'...';
						else $casa = $match['CasaNome']; 
						
						if(strlen($match['TrasfertaNome']) > 18)
							$trasferta = substr($match['TrasfertaNome'], 0, 18).'...';
						else $trasferta = $match['TrasfertaNome']; 							

						?>		
		
						<tr>
							<td width="7"><?=$match_giorno;?></td>
							<td width="15"><?=$match['Data_it'];?></td>
							<td width="9"><?=$match['Ora'];?></td>						
							<td width="30"><?=$casa;?></td>
							<td width="30"><?=$trasferta;?></td>
							<td><?=$match['Campo'];?></td>
						</tr>
		
			<? endforeach; ?>
			<? if (isset($riposo_prox2[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
						<tr>
							<td>Riposa:</td>
							<td colspan="5"><?=$riposo_prox2[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra'];?></td>							
						</tr>
			<? endif; ?>
		
		</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? if($count_gare_prox_2 > 0): ?>
		
			<? if ($p_counter != 1): ?>
				
				<? $fpdf->setX($offsetX+80-(80/2)-5); ?>
				
			<? else: ?>
			
				<? $fpdf->setX(135+80-(80/2)); ?>
			
			<? endif; ?>		
			
			<? $fpdf->setX($offsetX); ?>			
		
			<? $fpdf->htmltable($table);?>
			<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>			
		
		<? endif; ?>
		
		<?ob_start();?>
		
			<table border="1" width="135">
			
				<tr bgcolor="#ffff99">
					<td align="center" style="bold" size="8">Diffidati</td>
					
				</tr>
				
				<tr>
					
					<? $text = '';?>

					<? $i = 1; ?>

					<? foreach ($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $diffidato): ?>
				
								<? if ($diffidato[0]['Ammonizioni'] % 3 == 2): ?>
								
								<?
								
								$squadra = $diffidato[0]['NomeSquadra'];
								
								if(strlen($squadra) > 7) $squadra = substr($squadra, 0, 7).'.';
								
								?>
				
								<? $text .= " - " . $diffidato[0]['anagrafica']. " (" . $squadra . ")"; ?>
								
								<? if(($i%4) == 0) $text .= "\n\n"; ?> 

								<? $i++; ?>
						
								<? endif; ?>
								
					<? endforeach; ?>
					
					<? $text = nl2br($text); ?>
					
					<td width="59">
					
					<?=$text;?>

					</td>					

				</tr>
				
			
			</table>
		
		<? $table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		<? $fpdf->setX($offsetX); ?>	
		<? $fpdf->htmltable($table);?>	
		

		<? $_oldY = $fpdf->getY(); ?>

		<? $fpdf->setY($fpdf->getY()+3); $oldY = $fpdf->getY(); ?>	
			<? if ($p_counter != 1): ?>
				
				<? $fpdf->setX($offsetX+80-(80/2)-5); ?>
				
			<? else: ?>
			
				<? $fpdf->setX(135+80-(80/2)); ?>
			
			<? endif; ?>		
			
			<? $fpdf->setX($offsetX); ?>	
		<?ob_start();?>
		
			<table border="1" width="74">
			
				<tr bgcolor="#ffff99">
					<td align="center" size="8" style="bold" colspan="2">Classifica Marcatori</td>
					
				</tr>
					
				<? foreach ($classifica_marcatori[$giornata][$girone['Half']['GironeCampionato']] as $marcatori): ?>

					<tr>
						<td width="7"><?=$marcatori[0]['goals'];?></td>
						<td width="67"><?=$marcatori[0]['anagrafica'];?> (<?=$marcatori[0]['NomeSquadra'];?>)</td>
					</tr>
					
				<? endforeach; ?>
				
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<?
		
		if($_oldY > $fpdf->getY()) {
			
			$newY = $_oldY + 3;
			
		} else {
			
			$newY = $fpdf->getY() + 3;
			
		}
		
		?>		
		
		<? $fpdf->setY($newY); $oldY = $fpdf->getY(); ?>
		<? $fpdf->setX($offsetX); ?>
		<?ob_start();?>		
				 
			<table border="1" width="59">
			
				<tr bgcolor="#ffff99">
					<td align="center" size="8" style="bold">Squalificati - 1 gg.</td>
					
				</tr>

				<? foreach ($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $diffidato): ?>
			
							<? if ($diffidato[0]['Ammonizioni'] % 3 == 0 && $diffidato[0]['AmmonitoOggi'] == 1): ?>
							
							<tr>
								
								<td width="60">				
											<?=$diffidato[0]['anagrafica'];?> (<?=$diffidato[0]['NomeSquadra'];?>)
								</td>
							
							</tr>
			
							<? endif; ?>
				<? endforeach; ?>
				
			
			</table>
			
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
	
		<? $firstColumnY = $fpdf->getY(); ?>
	
		<? $fpdf->setY($oldY); ?>
		
		<?if($p_counter == 0): $x = 10;?>
		<?else: $x = $offsetX; ?>
		<?endif;?>
		<? $fpdf->setX($x + 61); ?>
		
		<?ob_start();?>
		
			<table border="1" width="74">
			
				<tr bgcolor="#ffff99">
					<td align="center" size="8" style="bold" colspan="2">Espulsi</td>
				</tr>

				<tr>					

				<? $i= 1;?>					
				<? foreach ($espulsi[$giornata][$girone['Half']['GironeCampionato']] as $espulso): ?>
				
					<?$giorni = $espulso['GoalPartite']['EspulsioneGiornate'];?> 
					<?$inizio   = date('d/m/Y', strtotime($espulso[0]['Data']));?>
					<?$fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));?>
					
					<?if($giorni != '' && $giorni != 0){
					
						$periodo = $giorni . ' giornate';
					
					} else {
					
						if($inizio != '00/00/0000' && $fine != '00/00/0000') {
						
							$periodo = $inizio . ' - ' . $fine;
						
						} else {
						
							$periodo = '1 giornata';
						
						}
					
					}?>


					<tr>
						<td><?=$espulso[0]['anagrafica'];?> (<?=$espulso[0]['NomeSquadra'];?>)	</td>
						<td><?=$periodo;?></td>
					</tr>	

				<?$i++;?>
				
				<? endforeach; ?>

				</tr>			
			</table>
		
		<?$table = ob_get_clean();?>
		<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>	
		
		<?
		
		if($firstColumnY > $fpdf->getY()) {
			
			$newY = $firstColumnY + 5;
			
		} else {
			
			$newY = $fpdf->getY() + 5;
			
		}
		
		?>	
		
		<? $fpdf->setY($newY); ?>				
		
		<? $fpdf->setX($offsetX); ?>
		
		<?ob_start();?>
		
			<table border="1" width="135">
			
				<tr bgcolor="#ffff99">
					<td align="center" size="8" style="bold">Provvedimenti disciplinari</td>
					
				</tr>

			
				<? foreach ($disciplinari[$giornata][$girone['Half']['GironeCampionato']] as $disciplinare): ?>
				
					<tr>
								<tr><td><?=$disciplinare['Disciplinari']['NomeSquadra'];?> - <?=$disciplinare['Disciplinari']['Descrizione'];?> - <?=$disciplinare['Disciplinari']['Sanzione'];?>€</td></tr> 
					</tr>
				
				<? endforeach; ?>
			
			</table>
		
			<?$table = ob_get_clean();?>
			<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>		
		
		
		<? $fpdf->setY($fpdf->getY()+3); ?>
		<? $fpdf->setX($offsetX); ?>
		
		<?ob_start();?>
		
			<table border="1" width="135">
			
				<tr bgcolor="#ffff99">
					<td align="center" size="8" style="bold">Comunicazioni</td>
					
				</tr>
				
				<? foreach ($comunicazioni[$giornata][$girone['Half']['GironeCampionato']] as $comunicazione): ?>
				
				<? 
					
					$limit = 131; 
					$text  = $comunicazione['Comunication']['Note'];
					$l     = strlen($text);
					$page  = ceil($l/$limit);
					
					$comunication = '';
					
					for($i = 0; $i < $page; $i++) {
						
						$comunication .= substr($text, $i*$limit, $limit) . "\n\n";
						
					}
					
					$comunication = nl2br($comunication);
				
				?>
				
								<tr><td><?=$comunication;?></td></tr> 
				
				<? endforeach; ?>				
			
			</table>
		
			<?$table = ob_get_clean();?>
			<?$xls_table .= $table; ?>
		
		<? $fpdf->htmltable($table);?>		

		<? $p_counter++; ?>
			 
		<? endforeach; ?>

	<? endforeach; ?>
	
<? endif; ?><?

$lol = uniqid();
$fpdf->output('files/pdf/gestione_campionati_bollettino_'.$lol.Inflector::Slug($campionato['Campionati']['Nome']).'.pdf', 'F'); ?><? print '/files/pdf/gestione_campionati_bollettino_'.$lol.Inflector::Slug($campionato['Campionati']['Nome']).'.pdf';?><?else:?><?
	PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setTitle("Bollettino");
	$objPHPExcel->getProperties()->setSubject("Prova");
	$objPHPExcel->getProperties()->setKeywords("My DB office 2007 openxml php");
	
	/* Sheet Gare */
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Gare');
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->getStartColor()->setARGB('ffff99');
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', $campionato['Campionati']['Nome']);	
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
		$baseRow = $offsetBase + $i;
		
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
					
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $baseRow-1, "Girone: " . $girone['Half']['Descrizione'] . " Giornata $giornata");
						
			foreach($gare[$giornata][$girone['Half']['GironeCampionato']] as $r => $match):
									
					$row = $baseRow + $r;
					
					$risultato = str_replace('-',' - ',$match['Match']['Risultato']);
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $match['Match']['CasaNome']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $match['Match']['TrasfertaNome']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $risultato);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', $row, $match['Causalresult']['Descrizione']);
					
					$i++;
								
			endforeach;
			if (isset($riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No') {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row + 1, 'Riposa:');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row + 1, $riposo[$giornata][$girone['Half']['GironeCampionato']][0][0]['NomeSquadra']);
			}
			$offsetBase += 3;
					
		endforeach;
	
	endforeach;
	
	/* Sheet classifiche */
	
	if($campionato['Campionati']['Italiana'] == 'No'):
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setTitle('Classifiche');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,2)->getFill()->getStartColor()->setARGB('ffff99');
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,2)->getFill()->getStartColor()->setARGB('ffff99');
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', '2', $campionato['Campionati']['Nome']);
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
			$c_classifica = $classifiche[$giornata][$girone['Half']['GironeCampionato']];
			 
			foreach($c_classifica as $k => $classifica)
				$c_classifica[$k]['SquadraNome'] = $classifica['InfoSquadra']['Squadre']['Denominazione'];					 
			
			$c_classifica = __array_orderby($c_classifica,'Punti',SORT_DESC,'Giocate',SORT_DESC,'SquadraNome',SORT_ASC);
			
			$baseRow = $offsetBase + $i;
		
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,$baseRow-1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,$baseRow-1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,$baseRow-1)->getFill()->getStartColor()->setARGB('ffff99');
					
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $baseRow-1, "Girone: " . $girone['Half']['Descrizione'] . " Giornata $giornata");
		
			foreach($c_classifica as $r => $classifica):
									
					$row = $baseRow + $r;
									
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $classifica['InfoSquadra']['Squadre']['Denominazione']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $classifica['Punti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $classifica['Giocate']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('4', $row, $classifica['Vinte']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('5', $row, $classifica['Perse']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('6', $row, $classifica['Nulle']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('7', $row, $classifica['GoalFatti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('8', $row, $classifica['GoalSubiti']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('9', $row, $classifica['CoppaDisciplina']);
					
					$i++;
								
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	endif;
	
	/* Sheet diffidati */
 
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setTitle('Diffidati');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
			$baseRow = $offsetBase + $i;
			
			foreach($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $r => $diffidato):
			
				if ($diffidato[0]['Ammonizioni'] % 3 == 2):
				
					$row = $baseRow + $r;
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $diffidato[0]['anagrafica'] . ' (' . $diffidato[0]['NomeSquadra'] . ')');
		
				endif;
				
				$i++;
			
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	/* Sheet Marcatori */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(3);
	$objPHPExcel->getActiveSheet()->setTitle('Marcatori');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
		$baseRow = $offsetBase + $i;
		
			foreach($classifica_marcatori[$giornata][$girone['Half']['GironeCampionato']] as $r => $marcatori):
						
					$row = $baseRow + $r;
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $marcatori[0]['goals']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $marcatori[0]['NomeSquadra']);
					
					$i++;
			
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	/* Sheeet Squalificati */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(4);
	$objPHPExcel->getActiveSheet()->setTitle('Squalificati');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
		$baseRow = $offsetBase + $i;
		
			foreach($diffidati[$giornata][$girone['Half']['GironeCampionato']] as $r => $diffidato):
			
				if ($diffidato[0]['Ammonizioni'] % 3 == 0 && $diffidato[0]['AmmonitoOggi'] == 1):
				
					$baseRow = 5;
			
					$row = $baseRow + $r;
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $diffidato[0]['anagrafica']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $diffidato[0]['NomeSquadra']);	

					$i++;
						
				endif;
						
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	/* Sheeet Espulsi */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(5);
	$objPHPExcel->getActiveSheet()->setTitle('Espulsi');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	foreach($gironi as $girone):
	
		foreach($giornate as $giornata):
				
		$baseRow = $offsetBase + $i;
		
			foreach($espulsi[$giornata][$girone['Half']['GironeCampionato']] as $r => $espulso):
			
					$giorni   = $espulso['GoalPartite']['EspulsioneGiornate'];
					$inizio   = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneInizio']));
					$fine     = date('d/m/Y', strtotime($espulso['GoalPartite']['EspulsioneFine']));
					
					if($giorni != '' && $giorni != 0){
					
						$periodo = $giorni . ' giornate';
					
					} else {
					
						if($inizio != '01/01/1970' && $fine != '01/01/1970') {
						
							$periodo = $inizio . ' - ' . $fine;
						
						} else {
						
							$periodo = '1 giornata';
						
						}
					
					}
						
					$row = $baseRow + $r;
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $espulso[0]['anagrafica']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $espulso[0]['NomeSquadra']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $periodo);
					
					$i++;
			
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	/* Sheeet provvedimenti disciplinari */
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(6);
	$objPHPExcel->getActiveSheet()->setTitle('Disciplinari');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

	$i = 0;
	$offsetBase = 5;
	
	foreach($gironi as $girone):

		foreach ($giornate as $giornata):
		
			$baseRow = $offsetBase + $i;
		
			foreach($disciplinari[$giornata][$girone['Half']['GironeCampionato']] as $r => $disciplinare):
						
					$row = $baseRow + $r;
										
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('1', $row, $disciplinare['Disciplinari']['NomeSquadra']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('2', $row, $disciplinare['Disciplinari']['Descrizione']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('3', $row, $disciplinare['Disciplinari']['Sanzione']);
					
					$i++;
			
			endforeach;
			
			$offsetBase += 3;
		
		endforeach;
	
	endforeach;
	
	/* Creazione file */
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="prova.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('files/xls/gestione_campionati_bollettino_'.Inflector::Slug($campionato['Campionati']['Nome']).'.xlsx');

print '/files/xls/gestione_campionati_bollettino_'.Inflector::Slug($campionato['Campionati']['Nome']).'.xlsx';?><?endif;?>
