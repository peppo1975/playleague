<?



	$users = mysql_query("SELECT * FROM newsletters_users WHERE created LIKE '%2014-10-30%'");
	print mysql_error();
	while ($user = mysql_fetch_assoc($users)) {
	print_r($user);
	mysql_query("INSERT INTO newsletters_groups_users (newsletter_group_id,newsletter_user_id) VALUES (9," . $user['id'] . ")");
	}
