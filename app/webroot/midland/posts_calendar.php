<?

//CLASSE FUNZIONI PERSONALIZZATE PHPBB PER MIDLAND.
require_once("midland_functions.php");

error_reporting(0);

$post = $_REQUEST['data'];

//print "<pre>";
//print_r($post);
//print "</pre>";

//exit;

$calendario = $post['calendario'];

?>
<?ob_start();?>
	<table border="0" id="posts-calendar" class="posts" width="100%">
		<tr class="header">
			<td width="80" colspan="5" align="center" size="9" style="bold"><?=$post['nome_campionato'];?> - <?=$post['data']['Print']['Gironi'][0];?></td>
		</tr>	
		<?
			$giornate = array();
			foreach($calendario as $g) {
				$giornate[$g['Match']['Giornata']][] = $g;
			}
		?>
		<? $first = key($giornate); ?>
		<? foreach($giornate as $giornata => $matches): ?>
	
		<tr class="header">
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
				<td width="105"><?=$match['Match']['CasaNome'];?> - <?=$match['Match']['TrasfertaNome'];?></td>
			</tr>
		<? endforeach; ?>

		<? endforeach; ?>				
			
</table>		
<?	

	$text = ob_get_clean();
	$text = str_replace("\n","",$text);
	if(!empty($post['data']['Print']['Testo'])) $text .= '<br />' . $post['data']['Print']['Testo'];
	$posting_userid = 2;
	//$topic_id       = $post['data']['Print']['Forum'] . $post['data']['PrintMarcatori']['Campionato'] . $post['data']['Print']['Gironi'][0] . $post['data']['Print']['Giornate'][0];
	
		$url_nuovo_post = create_forum_post($post['data']['PrintCalendarioRisultati']['Titolo'], $text, $post['data']['Print']['Forum'], $posting_userid);
		
		$url = str_replace($path . 'http://c5toscana.mooo.com', '',$url_nuovo_post);
		
		$url_decoded = ereg_replace('[^=0-9]', '', $url);
		
		$url_params  = explode('=',$url_decoded);
		
		$forum_id = $url_params[1];
		$post_id  = $url_params[2];
		
		print json_encode(array('f' => $forum_id,'t' => $post_id));	

?>

