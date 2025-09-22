<?

define('IN_PHPBB',1);

$server_dir  = $_SERVER['DOCUMENT_ROOT'];
$server_name = $_SERVER['SERVER_NAME'];

$tmp = explode('/', $server_dir);

foreach($tmp as $k => $t) {
	
	if($t == $server_name) unset($tmp[$k]);
	
}

 $path = implode('/', $tmp);

 $phpbb_root_path = '/var/www/midlandsport_forum/';
 
 //print($phpbb_root_path);
 $phpEx = substr(strrchr(__FILE__, '.'), 1);
 // The common.php file is required.
 include($phpbb_root_path . 'common.' . $phpEx);
 // this is required for auto posting
 include($phpbb_root_path . 'config.' . $phpEx);
 include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
 //include($phpbb_root_path . 'includes/functions_content.' . $phpEx);
 include($phpbb_root_path . 'includes/message_parser.' . $phpEx);

 
?>