<?$table_xls = '';?>
<?//error_reporting(0);?>
<?$fpdf->setup('P','mm','a4');?>
<?$fpdf->AddFont('calibri','B','calibri.php');?>
<?$fpdf->AddFont('calibri','','calibri.php');?>
<?$fpdf->SetFont('Helvetica','',6.5);?>
<?//$fpdf->SetMargins('10.2','3');?>
<? foreach($calendari as $girone => $calendario): ?>	

<?ob_start();?>
<table border="0">
		<tr>
			<td width="80" colspan="5" align="center" size="9" style="bold"><?=$nome_campionato;?> - <?=$this->requestAction('/admin/prints/findHalf/' . $girone);?></td>
		</tr>	
		<?
			$giornate = array();
			foreach($calendario as $g) {
				$giornate[$g['Match']['Giornata']][] = $g;
			}
		?>
		<? $first = key($giornate); ?>
		<? foreach($giornate as $giornata => $matches): ?>
		<? if($giornata != $first): ?>
		<tr>
			<td colspan="5" height="0.5">&nbsp;</td>
		</tr>		
		<? endif; ?>
		<tr>
			<td colspan="5" size="9" style="bold">Giornata N. <?=$giornata;?></td>
		</tr>			
		<? foreach($matches as $match): ?>		
			<tr>
				<?$giorni = array(
					1 => 'Lunedi',
					2 => 'Martedi',
					3 => 'Mercoledi',
					4 => 'Giovedi',
					5 => 'Venerdi',
					6 => 'Sabato',
					7 => 'Domenica'
				);?>
				<?$days = date('w',strtotime($match['Match']['Data']));?>
				<td width="15"><?=$giorni[$days];?></td>
				<td width="20"><?=$match['Match']['Data_it'];?></td>
				<td width="10"><?=$match['Match']['Ora'];?></td>
				<td width="35"><?=$match['Campi']['Descrizione'];?></td>
				<td width="105"><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?> (<?=$match['Match']['NomeGara'];?>)</td>
			</tr>
		<? endforeach; ?>
		<? if (isset($riposo[$girone][$giornata][0][0]['NomeSquadra']) && $campionato['Campionati']['Italiana'] == 'No'): ?>
				<? if (!substr_count($riposo[$girone][$giornata][0][0]['NomeSquadra'],"Vincente")): ?>
				<tr>			
					<td>Riposa: </td>
					<td colspan="4"><?=$riposo[$girone][$giornata][0][0]['NomeSquadra'];?></td>
				</tr>
				<? endif; ?>
		<? endif; ?>

		<? endforeach; ?>
		<tr>
			<td colspan="5" height="5">&nbsp;</td>
		</tr>			
		<tr height="30">
			<td height="30" colspan="5">Il calendario se pur ufficiale e definitivo  sottoposto alle normali problematiche stagionali, per tanto seguire costantemente i Comunicati Ufficiali cartacei.</td>
		</tr>		
			
</table>		
<?$table = ob_get_clean();?>
<?$fpdf->SetMargins('10.2','7');?>
<?$fpdf->AddPage();?>
<?$fpdf->Ln();?>
<?$fpdf->htmltable($table);?>
<?$table_xls .= $table;?>			
<? endforeach; ?>

<?if($export == 'pdf'):?>
<? $fpdf->output('files/pdf/gestione_campionati_calendario_'.Inflector::Slug($nome_campionato).'.pdf', 'F'); ?>
<?=json_encode(array('link' => 'files/pdf/gestione_campionati_calendario_'.Inflector::Slug($nome_campionato).'.pdf'));?>
<?else:?>
<?$handle = fopen('files/xls/gestione_campionati_calendario_'.Inflector::Slug($nome_campionato).'.xls',"w+");?>
<?fwrite($handle, iconv('utf8', 'cp1252', $table_xls));?>
<?fclose($handle);?>
<?=json_encode(array('link' => 'files/xls/gestione_campionati_calendario_'.Inflector::Slug($nome_campionato).'.xls'));?>
<?endif;?>