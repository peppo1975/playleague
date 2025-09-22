
<?=$this->Form->create('ChampCategory', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

<div class="form_header">
	
	<h2>Aggiungi nuova categoria campionato</h2>
	<ul>
		
		<li><?=$this->Form->submit('reset campi',array('type'=>'reset', 'id' => 'resetSession', 'div' =>false));?></li>
		<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
		<li><?=$this->Form->submit('inserisci',array('type' => 'submit','div' => false));?></li>
	</ul>
	<div class="clear"></div>
	
</div><!-- close form_header -->

<?php 
	
	//GIUSEPPE 03/10/2016 -------------------
	
	$arrayRadio = array();
	
	$res = mysql_query("SELECT * FROM TipoSport WHERE 1");
	
	while($row = mysql_fetch_assoc($res))
	{
		$arrayRadio[] = $row['sport'];
	}
	
?>

<?=$this->Form->radio( 'sport', $arrayRadio, array('value'=> 0)); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")"?>

<? // print_r($this->Form)// -------------------------------------- ?>

<?=$this->Form->input('Nome',array('label' => 'Categoria', 'type' => 'text', 'class' => 'big'));?>	

<?=$this->Form->end();?>
