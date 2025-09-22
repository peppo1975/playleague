<?

//CLASSE FUNZIONI PERSONALIZZATE PHPBB PER MIDLAND.
require_once("midland_functions.php");

error_reporting(0);

$post = $_REQUEST['data'];

//print "<pre>";
//print_r($post);
//print "</pre>";

$anagrafica = $post['anagrafica'];

//exit;

?>
<?ob_start();?>

<div class="anagrafica-container">
<? foreach($anagrafica as $squadra): ?>

	<div class="anagrafica-squadra-container">
	
		<h2><?=$squadra['Info']['NomeSquadra'];?></h2>
		
		<ul class="giocatori-container">
		
			<? foreach($squadra['rosa'] as $giocatore): ?>
			
				<li>
				
					<span class="ruolo"><?=$giocatore['Yearbook']['Ruolo'];?></span><span class="nominativo"><?=$giocatore['Yearbook']['NomeAtleta'];?></span>
				
				</li>
			
			<? endforeach; ?>
		
		</ul>
	
	</div>

<? endforeach; ?>
</div>
		
<?	

	$text = ob_get_clean();
	$text = str_replace("\n","",$text);
	if(!empty($post['data']['Print']['Testo'])) $text .= '<br />' . $post['data']['Print']['Testo'];
	$posting_userid = 2;
	//$topic_id       = $post['data']['Print']['Forum'] . $post['data']['PrintMarcatori']['Campionato'] . $post['data']['Print']['Gironi'][0] . $post['data']['Print']['Giornate'][0];
	
		$url_nuovo_post = create_forum_post($post['data']['PrintAnagrafica']['Titolo'], $text, $post['data']['Print']['Forum'], $posting_userid);
		
		$url = str_replace($path . 'http://c5toscana.mooo.com', '',$url_nuovo_post);
		
		$url_decoded = ereg_replace('[^=0-9]', '', $url);
		
		$url_params  = explode('=',$url_decoded);
		
		$forum_id = $url_params[1];
		$post_id  = $url_params[2];
		
		print json_encode(array('f' => $forum_id,'t' => $post_id));	

?>

