<?

$latitudine = $this->Session->read('MapsSession.latitudine');
$longitudine = $this->Session->read('MapsSession.longitudine');

$info = array(

	'Nome' => $this->Session->read('MapsSession.Nome'),
	'Indirizzo' => $this->Session->read('MapsSession.indirizzo'),
	'Citta' => $this->Session->read('MapsSession.citta'),
	'Provincia' => $this->Session->read('MapsSession.provincia'),
	'Telefono' => $this->Session->read('MapsSession.telefono'),
	'Email' => $this->Session->read('MapsSession.email'),

);


?>


<?=$this->element('/site/google_maps', array('latitudine' => $latitudine, 'longitudine' => $longitudine, 'info' => $info));?>