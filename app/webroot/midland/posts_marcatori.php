<?

//CLASSE FUNZIONI PERSONALIZZATE PHPBB PER MIDLAND.
require_once("midland_functions.php");

error_reporting(0);

$post = $_REQUEST['data'];

?>
<?ob_start();?>
			<table border="1" width="100%" id="posts-marcatori" class="posts">
			
				<tr class="header">
					<td align="center" colspan="2"  style="bold" size="10">
						Classifica marcatori
					</td>
				</tr>
				<tr class="header">
					<td align="center">Nominativo</td>
					<td align="center">Goals</td>
				</tr>
			
				<? foreach ($post['marcatori'] as $marcatore): ?>
				
					<tr>
					
						<td><?=$marcatore[0]['anagrafica'];?> (<?=$marcatore['s']['NomeSquadra'];?>)</td>
						<td align="center"><?=$marcatore[0]['goals'];?></td>
					
					</tr>
				
				<? endforeach; ?>
			
			</table>
<?	

	$text = ob_get_clean();
	$text = str_replace("\n","",$text);
	if(!empty($post['data']['Print']['Testo'])) $text .= '<br />' . $post['data']['Print']['Testo'];
	$posting_userid = 2;
	$topic_id       = $post['data']['Print']['Forum'] . $post['data']['PrintMarcatori']['Campionato'] . $post['data']['Print']['Gironi'][0] . $post['data']['Print']['Giornate'][0];
	
		$url_nuovo_post = create_forum_post($post['data']['PrintMarcatori']['Titolo'], $text, $post['data']['Print']['Forum'], $posting_userid);
		
		$url = str_replace($path . 'http://c5toscana.mooo.com', '',$url_nuovo_post);
		
		$url_decoded = ereg_replace('[^=0-9]', '', $url);
		
		$url_params  = explode('=',$url_decoded);
		
		$forum_id = $url_params[1];
		$post_id  = $url_params[2];
		
		print json_encode(array('f' => $forum_id,'t' => $post_id));	

?>

