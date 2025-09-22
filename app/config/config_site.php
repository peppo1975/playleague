<?  
	// Create the config array
    $config = array();

	$config['site_name'] = 'Play League Sport';    
	
    // Define config variable
	$config['default_prefix'] = 'Play League Sport';
    $config['default_title'] = 'Calcio a 5, calcio a 7, tornei';
	$config['default_author'] = 'timmytag | web oriented services';
	$config['default_keywords'] = 'Play League Sport, Firenze, calcio, sport, associazione, calcio, tornei';
	$config['default_description'] = 'Play League Sport Firenze Calcio a 5, calcio a 7, tornei';
	$config['default_feed_limit'] = 20;
	$config['default_news_type'] = 'Notizie';
	$config['option_news_type'] = 'News dalla redazione';
	$config['option_news_type_1'] = 'Ultim\'ora';
	$config["option_scuola"] = "News";
	$config["option_tennis"] = "NewsTennis";
	$config['isNews'] = array('Notizie','Ultim\'ora');
	$config['id_news'] = array(82);
	$config['admin_group_id'] = array(1,10);
	$config['default_admin_email'] = array('info@timmytag.it');
	$config['localhost'] = 'playleaguesport';
	//$config['shop_url'] = 'store.playleaguesport.it';	
	//$config['server_name'] = 'https://stagingversion.playleaguesport.it';
	$config['server_name'] = 'https://playleaguesport.it';
	$config['group_acl'] = array(1,5);
	
	//SMS
	
	$config['options_sms_username'] = 'midland@aimon.it';
	$config['options_sms_password'] = '0r0l0gi0';
	$config['options_sms_api'] 		= 106;
	
	//Account news.
	
	$config['group_id_news'] = array(9,10);
	$config['blocks_parent_id'] = array('News MGS','Ultim\'ora','News dalla redazione','News MGS','News MGS');
	
	$config['player_block_id'] = 434;	
	
	$config['albo_oro'] = 103;
	$config['calcio_a5'] = 87;
	$config['calcio_a7'] = 88;	
	
	//Group table
	
	$config['group_table'] = array(
	
		'Annuario',
		'AnnuarioSquadre',
		'Atleti',
		'AtletiSpese',
		'Calendari',
		'Bollettini',
		'CalendariFinali',
		'Campi',
		'CampiAffitti',
		'CampiBooking',
		'CampiCampionati',
		'Campionati',
		'CausaliRisultato',
		'Classifiche',
		'Disciplinare',
		'Disciplinari',
		'Espulsioni',
		'GironiCampionato',
		'GoalPartite',
		'LDA',
		'Squadre',
		'SquadreCampionati',
		'TipiAssicurazione',
		
	);

?>
