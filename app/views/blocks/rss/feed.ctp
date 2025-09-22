<?
    $this->set('documentData', array(
    'xmlns:atom' => 'http://www.w3.org/2005/Atom'));
    $this->set('channelData', array(
    'title' => __("midlandsport.it", true),
    'link' => $this->Html->url('/', true),
    'description' => __(Configure::read('default_description'), true),
	'image' => array('url' => 'http://www.midlandsport.it/img/logo-midland-gs-2.png', 'title' => 'midlandsport.it', 'link' => $this->Html->url('/', true)),
    'language' => 'it-it'));
	
	foreach ($data as $k => $post) {
	
		$postTime = strtotime($post['published']);
		$postLink = '/blocchi/'.$post['id'].'/'.strtolower(Inflector::Slug($post['title'],'-'));
		
		App::import('Sanitize');
		
		$bodyText = preg_replace('=\(.*?\)=is', '', $post['content']);
		$bodyText = $this->Text->stripLinks($bodyText);
		$bodyText = Sanitize::stripAll($bodyText);
		$bodyText = $this->Text->truncate($bodyText, 400, array(
		'ending' => '...',
		'exact' => true,
		'html' => true,
		));
		
		if($post['img_evidenza'] != '') {
		
			$img_src = $thumbnail->link(array('path' => $post['img_evidenza'],'w' => 220,'h' => 124));
			
			$bodyText .= '<img src="' . $img_src . '" width="100px" />';
		
		}
		
		echo $this->Rss->item(array(), array(
		'title' => $post['title'],
		'link' => $postLink,
		'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
		'description' => $bodyText,
		//'dc:creator' => $post['author'],
		'pubDate' => date(DATE_RSS,$postTime)));
		
		if($k == Configure::read('default_feed_limit') - 1) break;
    
	}
?>
