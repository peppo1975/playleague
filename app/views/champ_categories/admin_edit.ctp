<?= $this->element("/backend/edit_scripts"); ?>


<?= $this->Form->create('ChampCategory', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Modifica categoria campionato: <span><?= $this->data['ChampCategory']['Nome']; ?></span></h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<?= $this->Form->input('id'); ?>



<?php
////GIUSEPPE 03/10/2016 ----------

$arrayRadio = array();

$res = mysql_query("SELECT * FROM TipoSport WHERE 1");

while ($row = mysql_fetch_assoc($res))
{
    $arrayRadio[] = $row['sport'];
}


//$key = array_search($this->data['ChampCategory']['sport'], $arrayRadio); // cerca l'indice dello sport associato alla categoria nell'array degli sport

$key = $this->data['ChampCategory']['id_sport'];

//print_r($this->data['ChampCategory']);
// -----------------------------
?>

<?= $this->Form->radio('sport', $arrayRadio, array('value' => $key)); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")" ?>




<?= $this->Form->input('Nome', array('label' => 'Categoria', 'type' => 'text', 'class' => 'big')); ?>
<!--//GIUSEPPE 2019-11-20 ----------------------------------- -->
<? if ($this->data['ChampCategory']['sport'] == "TENNIS"): ?>

    <?= $this->Form->input('fattore_campionato', array('label' => 'Fattore correttivo', 'type' => 'text', 'class' => 'small')); ?>

<? endif; ?>
<!-- -------------------------------------------------------- -->


<div class="clear"></div>
<?= $this->Form->input('data_inizio', array('label' => 'Data inizio torneo', 'type' => 'text', 'class' => '')); ?>

<div class="clear"></div>
<?= $this->Form->input('data_fine', array('label' => 'Data fine iscrizioni', 'type' => 'text', 'class' => '')); ?>
<div class="clear"></div>

<div id="formUploadContainer">

    <?=
    $backend->getFiles('cat_id', $this->data['ChampCategory']['id'], array(
        'tag' => array('' => 'Logo'),
    ));
    ?>

</div>

<?= $this->Form->end(); ?>
	
