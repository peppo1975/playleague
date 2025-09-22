<?

//CLASSE FUNZIONI PERSONALIZZATE PHPBB PER MIDLAND.
require_once("midland_functions.php");

error_reporting(0);

$post = $_REQUEST['data'];

//print "<pre>";
//print_r($post);
//print "</pre>";

$espulsi 	= $post['espulsi'];
$diffidati 	= $post['diffidati'];

?>
<?ob_start();?>

<? if(count($diffidati)): ?>

	<h2>Diffidati</h2>

	<table id="diffidati" class="posts" width="100%">
		<tr class="header">
			<td>Società</td>
			<td>Nominativo</td>
			<td>Ammonizioni</td>
		</tr>
		
		<? foreach ($diffidati as $k => $diffidato): ?>
		
		<?
		
		$diffidato = $diffidato[0];
		
		?>
		
		<? if ($diffidato['Ammonizioni'] % 3 == 2): ?>
		
		<tr>
			<td><?=$diffidato['NomeSquadra'];?></td>
			<td><?=$diffidato['anagrafica'];?></td>
			<td><?=$diffidato['Ammonizioni'];?></td>
		</tr>
		
		<? endif; ?>
		
		<? endforeach; ?>
		
	</table>

<? else: ?>

<div class="error-message">Non ci sono diffidati in questa giornata</div>

<? endif; ?>

<? if(count($espulsi)): ?>

	<h2>Espulsi</h2>

		<table id="espulsi" class="posts" width="100%">
			<tr class="header">
				<td>Società</td>
				<td>Nominativo</td>
				<td>Periodo</td>
			</tr>
			
			<? foreach ($espulsi as $k => $espulso): ?>
			
			<?
			
			if(!isset($espulso[0]['Data'])) $espulso[0]['Data'] = '0000/00/00';
			
			?>
			
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
				<td><?=$espulso[0]['NomeSquadra'];?></td>
				<td><?=$espulso[0]['anagrafica'];?></td>
				<td><?=$periodo;?></td>
			</tr>
			
			<? endforeach; ?>
			
		</table>	

<? else: ?>

<div class="error-message">Non ci sono espulsi in questa giornata</div>

<? endif; ?>
		
<?	

	$text = ob_get_clean();
	$text = str_replace("\n","",$text);
	if(!empty($post['data']['Print']['Testo'])) $text .= '<br />' . $post['data']['Print']['Testo'];
	$posting_userid = 2;
	//$topic_id       = $post['data']['Print']['Forum'] . $post['data']['PrintMarcatori']['Campionato'] . $post['data']['Print']['Gironi'][0] . $post['data']['Print']['Giornate'][0];
	
		$url_nuovo_post = create_forum_post($post['data']['PrintDisciplinari']['Titolo'], $text, $post['data']['Print']['Forum'], $posting_userid);
		
		$url = str_replace($path . 'http://c5toscana.mooo.com', '',$url_nuovo_post);
		
		$url_decoded = ereg_replace('[^=0-9]', '', $url);
		
		$url_params  = explode('=',$url_decoded);
		
		$forum_id = $url_params[1];
		$post_id  = $url_params[2];
		
		print json_encode(array('f' => $forum_id,'t' => $post_id));	

?>

