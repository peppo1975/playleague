<?php
	/**
		* Routes configuration
		*
		* In this file, you set up routes to your controllers and their actions.
		* Routes are very important mechanism that allows you to freely connect
		* different urls to chosen controllers and their actions (functions).
		*
		* PHP versions 4 and 5
		*
		* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
		* Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
		*
		* Licensed under The MIT License
		* Redistributions of files must retain the above copyright notice.
		*
		* @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
		* @link          http://cakephp.org CakePHP(tm) Project
		* @package       cake
		* @subpackage    cake.app.config
		* @since         CakePHP(tm) v 0.2.9
		* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
	*/
	/**
		* Here, we are connecting '/' (base path) to controller called 'Pages',
		* its action called 'display', and we pass a param to select the view file
		* to use (in this case, /app/views/pages/home.ctp)...
	*/
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	/**
		* ...and connect the rest of 'Pages' controller's urls.
	*/
 	/* Feed Rss */
	Router::parseExtensions('rss');
	
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect ('/', array('controller'=>'sections', 'action'=>'home'));
	Router::connect	('/admin/:controller/:action/*', array('prefix' => 'admin'));
	Router::connect ('/admin', array('controller' => 'Dashboards','action' => 'index','prefix' => 'admin'));
	
	/* Seo friendly Url */
	
	Router::connect	('/contenuti/*', array('controller' => 'pages', 'action' => 'index'));
	Router::connect	('/blocchi/*', array('controller' => 'blocks', 'action' => 'index'));
	Router::connect	('/news/*', array('controller' => 'blocks', 'action' => 'index'));
	Router::connect	('/feed-campionati/*', array('controller' => 'blocks', 'action' => 'feed_campionati', 'url' => array('ext' => 'rss')));
	Router::connect	('/feed-scuola/*', array('controller' => 'blocks', 'action' => 'feed_scuolaa5', 'url' => array('ext' => 'rss')));
	//Router::connect	('/impianti/*', array('controller' => 'campis', 'action' => 'index'));
	Router::connect	('/disdetta/impianti/*', array('controller' => 'campis', 'action' => 'bookingCancel'));
	Router::connect	('/registrazione/conferma/*', array('controller' => 'users', 'action' => 'add_ok'));
	Router::connect	('/registrazione/atleti/*', array('controller' => 'users', 'action' => 'signup_athlete'));
	Router::connect	('/registrazione/*', array('controller' => 'users', 'action' => 'signup'));
	Router::connect	('/attivazione/atleti/*', array('controller' => 'users', 'action' => 'activate_athlete'));
	Router::connect	('/attivazione/*', array('controller' => 'users', 'action' => 'activate'));
	Router::connect ('/squadra/dettaglio/*', array('controller' => 'Squadres', 'action' => 'teams_detail'));
	Router::connect ('/dettaglio/squadra/*', array('controller' => 'Squadres', 'action' => 'teams_detail'));
	Router::connect ('/contatti/*', array('controller' => 'Contacts', 'action' => 'index'));
	Router::connect ('/iscrizioni/*', array('controller' => 'Matches', 'action' => 'subscription'));
	Router::connect ('/tesseramenti/*', array('controller' => 'Matches', 'action' => 'tesseramenti'));
	
	Router::connect ('/iscrizioni2/*', array('controller' => 'Matches', 'action' => 'subscription2'));
	
	Router::connect ('/area/riservata/*', array('controller' => 'users', 'action' => 'cp'));
	Router::connect ('/gestione/squadre/*', array('controller' => 'users', 'action' => 'cp_teams'));
	Router::connect ('/gestione/profilo/*', array('controller' => 'users', 'action' => 'edit_profile'));
	Router::connect ('/gestione/votazioni/*', array('controller' => 'athletes', 'action' => 'vote'));
	Router::connect ('/gestione/vota/*', array('controller' => 'athletes', 'action' => 'vota'));
    Router::connect ('/gestione/tennis_points/*', array('controller' => 'athletes', 'action' => 'tennis_points')); //GIUSEPPE 2017-02-22 redirect verso la pagina di inserimento punti tennis
	Router::connect ('/gestione/buste/*', array('controller' => 'athletes', 'action' => 'buste'));
	Router::connect ('/squadre/*', array('controller' => 'Squadres', 'action' => 'teams'));
	Router::connect ('/lista/*', array('controller' => 'Sections', 'action' => 'getSquadre'));
	Router::connect ('/albo/doro/*', array('controller' => 'Squadres', 'action' => 'albo_doro'));
	Router::connect ('/albo/doro/tennis/*', array('controller' => 'Squadres', 'action' => 'albo_doro_tennis')); //GIUSEPPE 2017-05-02
	Router::connect('/albo-oro-basket/*', array('controller' => 'Squadres', 'action' => 'albo_doro_basket')); //GIUSEPPE 2020-01-31
	
	Router::connect ('/gestione/impianto/:action', array('controller' => 'Tennisimpianto'));
	Router::connect ('/gestione/impianto/:action/*', array('controller' => 'Tennisimpianto'));
	Router::connect ('/impianti/torneo/*', array('controller' => 'Campis', 'action' => 'torneo'));

    Router::connect ('/ranking/atleti/*', array('controller' => 'Athletes', 'action' => 'ranking_atleti')); //GIUSEPPE 2017-06-13

        	
	/* Router dinamico per pagine dinamiche */
	
	App::Import('Model','Page');
	$Page = new Page;
	$menus = $Page->find('all', array('conditions' => array('Page.type' => 'dinamic')));
	
	if(!empty($menus)) {
		foreach($menus as $menuitem){
			Router::connect('/' . strtolower(Inflector::Slug($menuitem['Page']['alias'],'-')) . '/*', array('controller' => $menuitem['Page']['controller'], 'action' => ($menuitem['Page']['action'] != '')? $menuitem['Page']['action']:'index'));
		} 	
	}
	
	/* ------------------------------------ */
