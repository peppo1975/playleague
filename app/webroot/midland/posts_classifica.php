<?

//CLASSE FUNZIONI PERSONALIZZATE PHPBB PER MIDLAND.
require_once("midland_functions.php");

error_reporting(0);

$post = $_REQUEST['data'];

?>
<?ob_start();?>
			<table border="1" width="100%" id="posts-classifica" class="posts">
			
				<tr class="header">
					<td align="center" colspan="9"  style="bold" size="10">
						CLASSIFICA
					</td>
				</tr>
				<tr class="header">
					<td align="center" width="40">Società</td>
					<td align="center">Punti</td>
					<td align="center">Giocate</td>
					<td align="center">Vinte</td>
					<td align="center">Perse</td>
					<td align="center">Nulle</td>
					<td align="center">Goal Fatti</td>
					<td align="center">Goal Subiti</td>
					<td align="center">Coppa Disc.</td>
				</tr>
			
				<? foreach ($post['classifica'] as $classifica): ?>
				
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
<?	

	$text = ob_get_clean();
	$text = str_replace("\n","",$text);
	if(!empty($post['data']['Print']['Testo'])) $text .= '<br />' . $post['data']['Print']['Testo'];
	$posting_userid = 2;
	$topic_id       = $post['data']['Print']['Forum'] . $post['data']['PrintClassifica']['Campionato'] . $post['data']['Print']['Gironi'][0] . $post['data']['Print']['Giornate'][0];
	
		$url_nuovo_post = create_forum_post($post['data']['PrintClassifica']['Titolo'], $text, $post['data']['Print']['Forum'], $posting_userid);
		
		$url = str_replace($path . 'http://c5toscana.mooo.com', '',$url_nuovo_post);
		
		$url_decoded = ereg_replace('[^=0-9]', '', $url);
		
		$url_params  = explode('=',$url_decoded);
		
		$forum_id = $url_params[1];
		$post_id  = $url_params[2];
		
		print json_encode(array('f' => $forum_id,'t' => $post_id));	

?>

