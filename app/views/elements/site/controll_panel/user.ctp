
<?php 
	
$data = $this->Session->read('Login.data');

$id = $data['id'];
	header("Location: /gestione/profilo/". $id . "/User"); ?>	
