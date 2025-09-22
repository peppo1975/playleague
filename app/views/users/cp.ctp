<?

//debug($this->Session->read('Login.data'));

$data = $this->Session->read('Login.data');

?>

<? 

if($data['is_atleta']):

echo $this->element('/site/controll_panel/athlete');

elseif($data['is_user']):

echo $this->element('/site/controll_panel/user');

elseif($data['is_arbitro']):

echo $this->element('/site/controll_panel/arbitro');

elseif($data['is_impianto']):
?>
<script type="text/javascript">
	
	location.href = '/gestione/impianto/index_tornei';

</script>
<?php

else:

echo 'Non autorizzato. Effettua prima il login.';

endif; 

?>