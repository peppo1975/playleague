<?

require_once 'setup.php';

function create_forum_post($subject, $text, $forumid, $posting_userid, $topic_id=NULL) {

 global $user, $auth;
 
 $username = 'timmytag';
 $password = '123456';

 $title = html_entity_decode( $subject );
 $text  = html_entity_decode( $text );

 $forumid = $forumid;
 $topicid = $topic_id;

 $original_user_id = 2;
 $user->session_begin();
 $login = $auth->login($username, $password, false);
 $auth->acl($user->data);
 $user->setup();

 $title = utf8_normalize_nfc($title);
 $text = utf8_normalize_nfc($text);

 $poll = $uid = $bitfield = $options = '';

 $data = array(
     'forum_id'      => $forumid, 
     'topic_id'      => $topicid,
     'icon_id'      => false,
     'post_approved' => true,

     'enable_bbcode'   => true,
     'enable_smilies'   => true,
     'enable_urls'      => true,
     'enable_sig'      => true,

     'message'      => $text,
     'message_md5'   => md5($text),

     'bbcode_bitfield'   => $bitfield,
     'bbcode_uid'      => $uid,

     'post_edit_locked'   => 0,
     'topic_title'      => $title,
     'notify_set'      => false,
     'notify'         => false,
     'post_time'       => 0,
     'forum_name'      => '',
     'enable_indexing'   => true,
 );

 if ($topicid == NULL) {
 	$post_url =  submit_post('post', $title, '', POST_NORMAL, $poll, $data);
 } else {
 	//$post_url = submit_post('reply', $title, '', POST_NORMAL, $poll, $data);
 	$post_url =  submit_post('post', $title, 'forumBot', POST_NORMAL, $poll, $data);
 }
    

 $user->session_kill();
 $user->session_create($original_user_id, false, true);

 return $post_url;

}

//print($create_post);
function jumpbox_make($form_id = "Jumpbox", $input = "Jump") {
	
	global $db;
	
	$select_id = $form_id . $input;

	$sql = 'SELECT forum_id, forum_name, parent_id, forum_type, left_id, right_id
		FROM ' . FORUMS_TABLE . '
		ORDER BY left_id ASC';
	$result = $db->sql_query($sql, 600);
	
	$jumpbox = array();
	$list_forums = array();
	
	while($dati = $db->sql_fetchrow($result)) {
		
		$list_forums[$dati['forum_id']] = $dati;
	
	}	
	
		//print('<pre>');
		//print_r($list_forums);
		//print('</pre>');		
	
	foreach($list_forums as $dati) {
		
		@$jumpbox[$dati['parent_id'] . '|' . $list_forums[$dati['parent_id']]['forum_name']][] = $dati;
	
	}
	
	unset($jumpbox['0|']);
	
		//print('<pre>');
		//print_r($jumpbox);
		//print('</pre>');	
		
	$select = '<select id="'.$select_id.'" name="data[Print][Forum]">';
	
	foreach($jumpbox as $father => $childs) {
		
		list($father_id, $father_name) = explode('|', $father);
		$select .= '<option value="'.$father_id.'">'.$father_name.'</option>';
		
		foreach($childs as $child) {
			
			$select .= '<option value="'.$child['forum_id'].'">&nbsp;&nbsp;'.$child['forum_name'].'</option>';
			
		}
		
	}
	
	$select .= '</select>';
	
	return $select;
	
}


?>