<?


$xmlData = array(

	'Menu' => array(
	
		'Home' => array(
		
			'titolo' => 'Home',
			'code' => 'home_page',
		
		),
		'MidlandFirenze' => array(
		
			'titolo' => 'Midland Firenze',
			'code' => 'midland_firenze',
			'children' => array(
			
				'ChiSiamo' => array(
				
					'titolo' => 'Chi Siamo',
					'code' => 'chi_siamo',
					'children' => array(
					
						'BollettiniCalendari' => array(
						
							'titolo' => 'Bollettini e calendari',
							'code' => 'bollettini_e_calendari',
						
						),
					
					),
					
				
				),
				'LoStaff' => array(
				
					'titolo' => 'Lo Staff',
					'code' => 'lo_staff',
					
				
				),
				'LegaArbitri' => array(
				
					'titolo' => 'Lega Arbitri',
					'code' => 'lega_arbitri',
					
				
				),
				'Impianti' => array(
				
					'titolo' => 'Impianti',
					'code' => 'impianti',
					
				
				),
				'LavoraConNoi' => array(
				
					'titolo' => 'Lavora con noi',
					'code' => 'lavora_con_noi',
					
				
				),
				'Pubblicita' => array(
				
					'titolo' => 'Pubblicita',
					'code' => 'pubblicita',
					
				
				),
				'Partners' => array(
				
					'titolo' => 'Partners',
					'code' => 'partners',
					
				
				),	
				'Beneficienza' => array(
				
					'titolo' => 'Beneficienza',
					'code' => 'beneficienza',
					
				
				),				
			
			),			
			
		),
		'ManifestazioniInCorso' => array(
		
			'titolo' => 'Manifestazioni in corso',
			'code' => 'manifestazioni_in_corso',
		
		),
		'Notizie' => array(
		
			'titolo' => 'Notizie',
			'code' => 'notizie',
		
		),
		'Download' => array(
		
			'titolo' => 'Download',
			'code' => 'download',
		
		),
		'AltreAttivita' => array(
		
			'titolo' => 'Altre Attivita',
			'code' => 'altre_attivita',
		
		),
	
	)

);
 
App::import('Helper', 'Xml');
$xml = new XmlHelper();
 
$file = $xml->header(); // We need the XML header before our data.
$file .= $xml->serialize($xmlData, array('whitespace' => true));
 
// Write the file.
$xmlFile = fopen('filename.xml', 'a');
fwrite($xmlFile, $file);
fclose($xmlFile);

?>