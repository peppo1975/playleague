<?

$data = $this->Session->read('Login.data');

$id = $data['id'];

?>
<!---
	<li><a href="/gestione/profilo/<?=$id;?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
							<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
							<li><a href="/gestione/squadre" title="Modifica squadre">Modifica squadre</a></li>
-->
<script type="text/javascript">
	
	location.href = '/gestione/profilo/<?=$id;?>/Athlete';

</script>
