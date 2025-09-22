<?php

/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */


function doShortCode($content)
{
     $shortcode_regex = "/\[prodotto.*\]+/";
     $name_regex = "/nome=&quot;.*&quot;/U";
     $cost_regex = "/prezzo=&quot;.*&quot;/U";
 
     preg_match_all($shortcode_regex, $content, $shortcodes);
     $shortcodes = $shortcodes[0];

     foreach($shortcodes as $sc)
     {
          preg_match($name_regex, $sc, $name);
          $name = split("=", $name[0])[1];
          $name = str_replace("&quot;", "", $name);
      
          preg_match($cost_regex, $sc, $price);
          $price = split("=", $price[0])[1];
          $price = str_replace("&quot;", "", $price);
          $price = str_replace(",", ".", $price);
          $price_virg = str_replace(".", ",", $price);

          $uniq_id = substr(sha1($sc), 0, 13);
          if(!is_file(__DIR__."/../payment_links/$uniq_id"))
          {
          	$data = json_encode([
          		"name" => $name,
          		"price" => $price
          	]);
          	file_put_contents(__DIR__."/../payment_links/$uniq_id", $data);
          }

          $replace = "<a class=\"product-link\" href=\"/sections/productform?uniqid=$uniq_id\">$name - € $price_virg</a>";
         //$replace = "<a class=\"product-link\" href=\"/sections/productform?uniqid=$uniq_id&product_price=$price_virg&product_name=$name&redirect=$pay_link\">$name € $price_virg</a>";
          $content = str_replace($sc, $replace, $content);
     }

     return $content;
 }


 /* //GIUSEPPE  2018-07-16 ***************************************** */
function payment_link($description, $cost, $trackid)
{

    include '../views/elements/subscriptions/payment_data.ctp';

    $divisa = "EUR";
    $importo = $cost * 100;

    // Calcolo MAC
    $mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $CHIAVESEGRETA);

    // Parametri obbligatori
    $obbligatori = array(
        'alias' => $ALIAS,
        'importo' => $importo,
        'divisa' => $divisa,
        'codTrans' => $codTrans,
        'url' => 'https://' . $_SERVER['SERVER_NAME'] . '/sections/productverify/' . $trackid . "/1",
        'url_back' => 'https://' . $_SERVER['SERVER_NAME'] . '/sections/productconfirm/' . $trackid . "/0",
        'mac' => $mac,
    );

    // Parametri facoltativi
    $facoltativi = array(
    );

    $requestParams = array_merge($obbligatori, $facoltativi);

    $aRequestParams = array();

    foreach ($requestParams as $param => $value)
    {
        $aRequestParams[] = $param . "=" . $value;
    }

    $stringRequestParams = implode("&", $aRequestParams);

    $redirectUrl = $requestUrl . "?" . $stringRequestParams;

    return $redirectUrl;
}





/* * *************************************************************** */
 
if(!empty($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'midlandsport.net' || $_SERVER['SERVER_NAME'] == 'www.midlandsport.net'))
	header("Location: http://store.midlandsport.it");
 
Configure::load('config_site'); 

require __DIR__ . '/../../vendor/autoload.php';